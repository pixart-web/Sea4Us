#!/usr/bin/env python3
"""Build the traceable migration inventory from the crawled snapshot (source/).

Writes inventory/migration-inventory.csv (one row per URL and language) and
inventory/inventory-summary.md (counts per type). Run: python3 -I tools/inventory.py
"""
import csv, collections, glob, os, re, sys, json
sys.path.insert(0, os.path.dirname(os.path.abspath(__file__)))
import source_lib as L
from bs4 import BeautifulSoup

OUT = os.path.join(L.ROOT, 'inventory')
os.makedirs(OUT, exist_ok=True)

# Old page id → new page key / project slug (projects become structured project sheets).
PAGE_MAP = {
    4769: ('page', 'home'), 5316: ('page', 'about'), 5346: ('page', 'about'), 5391: ('page', 'team'),
    5536: ('page', 'partners'), 5472: ('page', 'science'), 5683: ('page', 'partnering'),
    3612: ('page', 'news'), 3537: ('page', 'links'), 2473: ('page', 'supporters'),
    3444: ('page', 'projects'), 8210: ('page', 'projects'),
}
NEW_PAGES = {
    'home': ('/pt/', '/en/'), 'about': ('/pt/sobre/', '/en/about/'), 'team': ('/pt/sobre/equipa/', '/en/about/team/'),
    'science': ('/pt/ciencia-e-tecnologia/', '/en/science-technology/'), 'programs': ('/pt/programas/', '/en/programmes/'),
    'projects': ('/pt/projetos/', '/en/projects/'), 'partners': ('/pt/parceiros/', '/en/partners/'),
    'partnering': ('/pt/parcerias-e-investimento/', '/en/partnering-investment/'), 'news': ('/pt/noticias/', '/en/news/'),
    'transparency': ('/pt/transparencia-na-investigacao/', '/en/research-transparency/'),
    'supporters': ('/pt/apoiantes-crowdfunding/', '/en/crowdfunding-supporters/'), 'links': ('/pt/links-uteis/', '/en/useful-links/'),
    'contact': ('/pt/contactos/', '/en/contact/'), 'privacy': ('/pt/privacidade-e-cookies/', '/en/privacy-cookies/'),
    'search': ('/pt/pesquisa/', '/en/search/'),
}
# Project pages that are duplicates or merged into another sheet.
PROJECT_MERGE = {
    'https-sea4us-pt-sobre-nos-projects-highly-qualified-human-resources': 'highly-qualified-human-resources',
    'algared-es': 'algared', 'alg-02-0752-feder-036043': None,
}
DEMO_TERMS = {'aenean', 'bullet-list', 'donec', 'long-post', 'malesuada', 'numbered-list', 'scelerisque', 'short-post', 'vestibulum', 'wenddisse'}
LOREM = re.compile(r'^(vestibulum|venenatis|ullamcorper|suspendisse|posuere|phasellus|pellentesque|nullam|nulla|nisi-turpis|aenean|donec|malesuada|scelerisque|wenddisse|lorem|ipsum|dolor|amet|tellus|tortor|turpis|sapien|sollicitudin|quisque|praesent|morbi|mauris|maecenas|lectus|integer|fusce|etiam|eget|curabitur|cras|aliquam|augue|consectetur|elit|felis|libero|ligula|magna|metus|nunc|odio|orci|purus|risus|sed|semper|vitae|vivamus|viverra|volutpat|vel|ut|nec|in|eu|at|ac)$')

def lang_of(t):
    t = ' ' + t.lower() + ' '
    pt = sum(t.count(w) for w in (' de ', ' da ', ' do ', ' que ', ' para ', ' com ', ' uma ', ' os ', ' não ', ' são '))
    en = sum(t.count(w) for w in (' the ', ' of ', ' and ', ' to ', ' with ', ' for ', ' is ', ' our ', ' we ', ' this '))
    return 'pt' if pt > en else 'en'

def main():
    rows = []
    rest = {}
    for kind in ('posts', 'pages'):
        for lang in ('pt', 'en'):
            for x in L.rest(kind, lang):
                rest[(x['id'], lang)] = x
    pages_tsv = {}
    for r in csv.DictReader(open(os.path.join(L.SRC, 'logs', 'pages.tsv'), encoding='utf-8'), delimiter='\t'):
        pages_tsv.setdefault(r['final_url'], r)

    for f in sorted(glob.glob(os.path.join(L.SRC, 'html', '*.html'))):
        name = os.path.basename(f)[:-5]
        parts = name.split('__')
        lang = parts[0]
        rest_path = '/'.join(parts[1:])
        path = '/' + lang + '/' + (('?' + rest_path) if rest_path.startswith(('attachment_id', 'elementskit')) else (rest_path + '/' if rest_path else ''))
        soup = BeautifulSoup(open(f, encoding='utf-8', errors='replace'), 'lxml')
        kind = L.page_kind(soup)
        b = soup.body.get('class', []) if soup.body else []
        wpid = next((int(c.split('-')[-1]) for c in b if re.match(r'^(postid|page-id)-\d+$', c)), None)
        title = re.sub(r'\s+–\s+Sea4Us$', '', soup.title.get_text(strip=True) if soup.title else '')
        r = dict(url='https://sea4us.pt' + path, path=path, lang=lang, type=kind, wp_id=wpid or '', title=title,
                 date='', modified='', words=0, images=0, documents='', video='', translation='', content_lang='',
                 proposed_type='', proposed_url='', action='', status='', notes='')
        x = rest.get((wpid, lang)) if wpid else None
        if x:
            other = rest.get((wpid, 'en' if lang == 'pt' else 'pt'))
            h, imgs, links, emb = L.clean(x['content']['rendered'])
            r.update(date=x['date'][:10], modified=x['modified'][:10], words=L.words(h), images=len(imgs))
            docs = sorted({l['href'] for l in links if re.search(r'\.pdf($|\?)', l['href'], re.I)})
            r['documents'] = ' '.join(docs)
            r['video'] = ' '.join(sorted(set(emb)))
            same = other and other['content']['rendered'] == x['content']['rendered']
            r['content_lang'] = lang_of(L.text(h))
            if not same and other:
                oh = L.clean(other['content']['rendered'])[0]
                same = L.text(oh) == L.text(h) or lang_of(L.text(oh)) == lang_of(L.text(h))
            r['translation'] = ('same language in PT and EN versions (not translated)' if same else 'translated')
        if kind == 'post':
            r.update(proposed_type='s4u_news', proposed_url=f'/{lang}/{"noticias" if lang == "pt" else "news"}/{x["slug"] if x else rest_path}/',
                     action='migrate + 301', status='content extracted')
            if r['translation'].startswith('same') and r['content_lang'] != lang:
                r['notes'] = f'Only available in {r["content_lang"].upper()} on the old site: translation to write.'
        elif kind in ('page', 'home'):
            if x and x['parent'] == 3444 or (rest_path.startswith('sobre-nos/projects/') and rest_path.count('/') == 2):
                slug = rest_path.split('/')[2]
                tgt = PROJECT_MERGE.get(slug, slug)
                if tgt is None:
                    r.update(proposed_type='s4u_project', proposed_url=NEW_PAGES['projects'][lang == 'en'], action='merge + 301',
                             notes='Code-only page (ALG-02-0752-FEDER-036043): merged into the matching project sheet; confirm which project.')
                else:
                    base = 'projetos' if lang == 'pt' else 'projects'
                    r.update(proposed_type='s4u_project', proposed_url=f'/{lang}/{base}/{tgt.lower()}/',
                             action='migrate + 301' if tgt == slug else 'merge + 301')
                    if tgt != slug:
                        r['notes'] = f'Duplicate of {tgt}: merged.'
                r['status'] = 'content extracted'
            else:
                key = PAGE_MAP.get(wpid, (None, None))[1]
                if key:
                    r.update(proposed_type='page', proposed_url=NEW_PAGES[key][lang == 'en'],
                             action='keep path' if key == 'home' else ('migrate + 301' if NEW_PAGES[key][lang == 'en'] != path else 'keep path'),
                             status='content extracted')
                    if wpid == 5346: r['notes'] = 'Mission & vision merged into About.'
                    if wpid == 8210: r['notes'] = '“Fundos Públicos” duplicates the projects index: merged.'
                    if wpid == 3612: r['notes'] = 'Old title “NotíciasNotícias” fixed to “Notícias”.'
                    if wpid == 5472: r['notes'] = 'Split: science & technology, programmes/pipeline and research transparency (#animalresearch).'
                else:
                    r.update(action='review', notes='Page not mapped')
        elif kind == 'attachment':
            parent = '/'.join(rest_path.split('/')[:-1])
            if parent:
                r.update(proposed_type='media', action='301 to parent', notes=f'Attachment page under /{lang}/{parent}/', status='media kept')
            else:
                img = soup.select_one('.entry-content img, article img, main img')
                src = img.get('src', '') if img else ''
                loc = L.local_upload(src)
                r.update(proposed_type='media', action='301 to file' if loc else 'no redirect (404)',
                         proposed_url=('/wp-content/uploads/' + loc) if loc else '', status='media kept' if loc else 'review',
                         notes='Attachment page (thin, auto-generated by WordPress).')
        elif kind == 'archive':
            seg = rest_path.split('/')
            news = NEW_PAGES['news'][lang == 'en']
            if seg[0] == 'category':
                if seg[1] in ('news', 'uncategorized'):
                    r.update(proposed_type='page', proposed_url=news, action='301', notes='Category archive → news archive.')
                else:
                    r.update(action='no redirect (404)', notes='Theme demo category, never used by real posts.')
            elif seg[0] == 'tag':
                if LOREM.match(seg[1]) or seg[1] in DEMO_TERMS:
                    r.update(action='no redirect (404)', notes='Theme demo tag (lorem ipsum).')
                else:
                    r.update(proposed_type='search', proposed_url=NEW_PAGES['search'][lang == 'en'] + '?q=' + seg[1],
                             action='301', notes='Tag archive → site search for the same term.')
            elif seg[0] == 'author':
                r.update(action='no redirect (404)', notes='Author archive: not kept (authorship shown on each article when published).')
            elif seg[0] == 'type':
                r.update(action='no redirect (404)', notes='Post-format archive (theme).')
            r['status'] = 'not migrated (listing)'
        elif kind == 'template':
            r.update(action='no redirect (404)', notes='ElementsKit template exposed publicly; not content.', status='not migrated')
        elif 'elementskit_template' in path:
            r.update(type='template', action='no redirect (404)', notes='Theme footer template exposed publicly; not content.', status='not migrated')
        elif rest_path == 'alg-02-0752-feder-036043':
            r.update(proposed_type='s4u_project', proposed_url=NEW_PAGES['projects'][lang == 'en'], action='merge + 301', status='content extracted',
                     notes='Standalone copy of the ALG-02-0752-FEDER-036043 funding sheet: merged into the matching project sheet.')
        else:
            r.update(action='review', notes='Unclassified page')
        rows.append(r)

    # Pages that failed during the crawl.
    for line in open(os.path.join(L.SRC, 'logs', 'failures.tsv'), encoding='utf-8'):
        p = line.rstrip('\n').split('\t')
        if len(p) > 2 and p[0] in ('page', 'media') and p[2] == '404':
            rows.append(dict(url=p[1], path=p[1].replace('https://sea4us.pt', ''), lang='', type='broken (404 on old site)',
                             action='none', status='missing on old site', notes='Linked from the old site but returns 404; nothing to migrate.'))

    fields = ['url', 'path', 'lang', 'type', 'wp_id', 'title', 'date', 'modified', 'words', 'images', 'documents', 'video',
              'translation', 'content_lang', 'proposed_type', 'proposed_url', 'action', 'status', 'notes']
    with open(os.path.join(OUT, 'migration-inventory.csv'), 'w', newline='', encoding='utf-8') as fh:
        w = csv.DictWriter(fh, fieldnames=fields, extrasaction='ignore')
        w.writeheader()
        for r in sorted(rows, key=lambda r: (r['type'], r['path'])):
            w.writerow({k: r.get(k, '') for k in fields})

    c = collections.Counter((r['type'], r['lang']) for r in rows)
    a = collections.Counter(r['action'] for r in rows)
    untranslated = [r for r in rows if r.get('notes', '').startswith('Only available')]
    media = collections.Counter(os.path.splitext(f)[1].lower() for f in L.upload_files())
    with open(os.path.join(OUT, 'inventory-summary.json'), 'w') as fh:
        json.dump({'by_type_lang': {f'{k[0]}:{k[1]}': v for k, v in c.items()}, 'by_action': a,
                   'untranslated': len(untranslated), 'media_by_ext': media, 'media_total': sum(media.values())}, fh, indent=1)
    print(json.dumps({'rows': len(rows), 'by_type_lang': {f'{k[0]}:{k[1]}': v for k, v in sorted(c.items())}, 'by_action': a, 'untranslated': len(untranslated), 'media': sum(media.values())}, indent=1))

main()
