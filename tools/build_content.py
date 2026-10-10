#!/usr/bin/env python3
"""Assemble the importer files from the extracted snapshot and the editorial work files.

Reads  source/extracted/posts, content/_work/*.json, content/{people,partners,projects,programs,pages}.json
Writes content/news.json, content/documents.json, content/media.json and content/_notes/assembly-report.md
Rewrites old links to placeholders, local images to {{media:KEY}}, and strips tracking parameters.
"""
import collections, glob, json, os, re, sys, urllib.parse
sys.path.insert(0, os.path.dirname(os.path.abspath(__file__)))
import source_lib as L

ROOT = L.ROOT
C = os.path.join(ROOT, 'content')
report = collections.defaultdict(list)


def load(name, default=None):
    p = os.path.join(C, name)
    return json.load(open(p, encoding='utf-8')) if os.path.exists(p) else default


# ---------- Link map: old URL path -> placeholder ----------
posts = {}
for f in glob.glob(os.path.join(L.SRC, 'extracted', 'posts', '*.json')):
    d = json.load(open(f, encoding='utf-8'))
    posts.setdefault(d['id'], {})[d['lang']] = d
slug_to_news = {d['pt']['slug'].lower(): i for i, d in posts.items()}

PAGE_PATHS = {
    '': 'home', 'sobre-nos': 'about', 'sobre-nos/mission-vision': 'about', 'sobre-nos/team': 'team', 'sobre-nos/partners': 'partners',
    'sobre-nos/projects': 'projects', 'public-funding': 'projects', 'technology': 'science', 'partnering': 'partnering',
    'news-2': 'news', 'useful-links': 'links', 'crowdfunding-supporters': 'supporters', 'category/news': 'news',
}
projects = load('projects.json', [])
proj_slugs = {p['id'].split(':', 1)[1] for p in projects}
PROJECT_ALIASES = {'https-sea4us-pt-sobre-nos-projects-highly-qualified-human-resources': 'highly-qualified-human-resources', 'algared-es': 'algared'}


def strip_tracking(url):
    p = urllib.parse.urlsplit(url)
    if not p.query:
        return url
    q = [(k, v) for k, v in urllib.parse.parse_qsl(p.query, keep_blank_values=True) if not k.lower().startswith(('utm_', 'fbclid', 'gclid'))]
    if len(q) != len(urllib.parse.parse_qsl(p.query, keep_blank_values=True)):
        report['tracking parameters removed'].append(url)
    return urllib.parse.urlunsplit((p.scheme, p.netloc, p.path, urllib.parse.urlencode(q), p.fragment))


def map_url(url, ctx):
    url = url.strip()
    p = urllib.parse.urlsplit(url)
    if p.netloc and 'sea4us.pt' not in p.netloc:
        return strip_tracking(url)
    if not p.netloc and not url.startswith('/'):
        return url
    if '/wp-content/uploads/' in p.path:
        key = L.local_upload(url)
        if key:
            return '{{media:%s}}' % key
        report['links to missing media (kept as original URL)'].append(f'{ctx}: {url}')
        return url
    path = re.sub(r'^/(pt|en)(/|$)', '/', p.path).strip('/')
    frag = ('#' + p.fragment) if p.fragment else ''
    if path in PAGE_PATHS:
        return '{{page:%s}}' % PAGE_PATHS[path] + frag
    if path in ('about-us/technology',):
        return '{{page:science}}' + frag
    if path in ('home/about-us/our-partners', 'about-us/our-partners'):
        return '{{page:partners}}' + frag
    m = re.match(r'^(?:sobre-nos/projects|our-projects)/([^/]+)$', path, re.I)
    if m:
        s = PROJECT_ALIASES.get(m.group(1).lower(), m.group(1).lower())
        if s in proj_slugs:
            return '{{item:project:%s}}' % s
    if path.lower() in slug_to_news:
        return '{{item:news:%s}}' % slug_to_news[path.lower()]
    report['internal links not mapped (left as relative old path)'].append(f'{ctx}: {url}')
    return '/' + p.path.lstrip('/')


def rewrite_html(h, ctx, media_used):
    def img(m):
        tag = m.group(0)
        src = re.search(r'src="([^"]*)"', tag)
        key = L.local_upload(src.group(1)) if src else None
        if not key:
            report['images dropped (file not in snapshot, no hotlinking)'].append(f'{ctx}: {src.group(1) if src else tag}')
            return ''
        media_used.add(key)
        alt = re.search(r'alt="([^"]*)"', tag)
        alt = alt.group(1) if alt else ''
        if re.fullmatch(r'[\w.-]*\d{6,}[\w.-]*', alt):
            alt = ''  # A file name is not a text alternative.
        return f'<img src="{{{{media:{key}}}}}" alt="{alt}"/>'
    h = re.sub(r'<img\b[^>]*>', img, h)
    h = re.sub(r'href="([^"]*)"', lambda m: 'href="%s"' % map_url(m.group(1), ctx), h)
    h = re.sub(r'<figure>\s*</figure>', '', h)
    # A link whose only content is an image needs a text alternative (WCAG 2.4.4 / 1.1.1).
    en = '/en/' in ctx
    def linked_img(m):
        key, href = m.group(2), m.group(1)
        label = LINKED_IMG_ALT.get(key)
        if label:
            text = label[1 if en else 0]
        elif href.startswith('{{media:'):
            text = 'View full-size image' if en else 'Ver imagem em tamanho real'
        elif href.startswith('http'):
            host = re.sub(r'^www\.', '', urllib.parse.urlparse(href).netloc)
            text = ('Open on %s' if en else 'Abrir em %s') % host
        else:
            text = 'Open link' if en else 'Abrir ligação'
            report['linked images with generic text alternative'].append(f'{ctx}: {key}')
        return m.group(0).replace('alt=""', 'alt="%s"' % text)
    h = re.sub(r'<a [^>]*href="([^"]*)"[^>]*>\s*<img src="\{\{media:([^}]+)\}\}" alt=""/>\s*</a>', linked_img, h)
    return h


LINKED_IMG_ALT = {
    '2024/06/EU.png': ('Cofinanciado pela União Europeia: ver o projeto Sea4Market', 'Co-funded by the European Union: see the Sea4Market project'),
}


# ---------- News ----------
ed = load('_work/news-editorial.json', {}) or {}
media_used = set()
news = []
for pid, langs in sorted(posts.items(), key=lambda kv: kv[1]['pt']['date'], reverse=True):
    e = ed.get(str(pid), {})
    base = langs['en'] if 'en' in langs else langs['pt']
    row = {'id': f'news:{pid}', 'date': langs['pt']['date'], 'shared': {}, 'pt': {}, 'en': {}}
    thumb = None
    for lang in ('pt', 'en'):
        d = langs.get(lang, base)
        ctx = d['link']
        html_src = (e.get(lang) or {}).get('html') or d['html']
        h = rewrite_html(html_src, ctx, media_used)
        title = (e.get(lang) or {}).get('title') or d['title']
        # Drop a leading heading that repeats the title.
        h = re.sub(r'^<h2>\s*' + re.escape(L.text(title)) + r'\s*</h2>', '', h, flags=re.I)
        first = re.match(r'^\s*(?:<figure>)?\s*<img src="\{\{media:([^}]+)\}\}"[^>]*/>\s*(?:</figure>)?', h)
        if first:
            thumb = thumb or first.group(1)
            if first.group(1) == thumb:
                h = h[first.end():]
        if not thumb:
            m = re.search(r'\{\{media:([^}]+\.(?:jpe?g|png|webp))\}\}', h, re.I)
            if m:
                thumb = m.group(1)
        excerpt = (e.get(lang) or {}).get('excerpt') or ''
        if not excerpt:
            t = re.sub(r'\s+', ' ', L.text(re.sub(r'<(/p|/h\d|br/?|/li)>', ' ', h))).strip()
            excerpt = (t[:200].rsplit(' ', 1)[0] + '…') if len(t) > 200 else t
        row[lang] = {'title': L.text(title), 'slug': langs['pt']['slug'], 'excerpt': excerpt, 'content': h.strip(),
                     'fields': {'legacy_url': d['link'], **({'milestone_label': e.get('milestone_label', {}).get(lang, '')} if e.get('milestone') else {})}}
    sh = {'topic': e.get('topic', 'company'), 'review_status': 'pending', 'source_url': base['link'], 'source_date': '2026-10-10'}
    for k in ('external_url', 'external_source', 'video_url'):
        if e.get(k):
            sh[k] = strip_tracking(e[k]) if k != 'external_source' else e[k]
    if not sh.get('video_url') and base.get('embeds'):
        sh['video_url'] = base['embeds'][0]
    if e.get('milestone'):
        sh['milestone'] = True
    if thumb:
        sh['thumbnail'] = thumb
        media_used.add(thumb)
    row['shared'] = sh
    if not e:
        report['news without editorial entry'].append(str(pid))
    news.append(row)
json.dump(news, open(os.path.join(C, 'news.json'), 'w', encoding='utf-8'), ensure_ascii=False, indent=1)

# ---------- Documents ----------
docs = (load('_work/documents-projects.json', []) or []) + (load('_work/documents-other.json', []) or [])
seen = set()
documents = []
for d in docs:
    if d['id'] in seen:
        continue
    seen.add(d['id'])
    documents.append(d)
json.dump(documents, open(os.path.join(C, 'documents.json'), 'w', encoding='utf-8'), ensure_ascii=False, indent=1)

# ---------- Rewrite links inside the other collections (projects, people, pages) ----------
for name in ('projects.json', 'people.json', 'documents.json'):
    rows = load(name, [])
    for r in rows:
        for lang in ('pt', 'en'):
            if r.get(lang, {}).get('content'):
                r[lang]['content'] = rewrite_html(r[lang]['content'], f'{name}:{r["id"]}', media_used)
    json.dump(rows, open(os.path.join(C, name), 'w', encoding='utf-8'), ensure_ascii=False, indent=1)

# Homepage highlights: the three most recent funded projects with an active or unknown status (editable later).
rows = load('projects.json', [])
for r in rows:
    r['shared']['highlight'] = r['id'] in ('project:sea4market', 'project:algae4ibd', 'project:blueflow')
json.dump(rows, open(os.path.join(C, 'projects.json'), 'w', encoding='utf-8'), ensure_ascii=False, indent=1)

# ---------- Media manifest ----------
KEY_RE = re.compile(r'\{\{media(?:-id)?:([^}]+)\}\}')
MEDIA_FIELDS = {'thumbnail', 'file', 'funding_logos', 'gallery'}


def walk(o):
    if isinstance(o, dict):
        for k, v in o.items():
            if k in MEDIA_FIELDS:
                for x in (v if isinstance(v, list) else [v]):
                    if isinstance(x, str) and x and not x.startswith('http'):
                        media_used.add(x)
            walk(v)
    elif isinstance(o, list):
        for v in o:
            walk(v)
    elif isinstance(o, str):
        media_used.update(KEY_RE.findall(o))


for name in ('pages.json', 'programs.json', 'projects.json', 'people.json', 'partners.json', 'news.json', 'documents.json', 'site.json'):
    walk(load(name, []))

ALT_OVERRIDES = {
    '2020/08/fundos-publicos.png': {'pt': 'IEFP, Algarve 2030, Portugal 2030, Cofinanciado pela União Europeia', 'en': 'IEFP, Algarve 2030, Portugal 2030, Co-funded by the European Union'},
    '2023/05/prr.png': {'pt': 'PRR – Plano de Recuperação e Resiliência, República Portuguesa, Financiado pela União Europeia NextGenerationEU', 'en': 'PRR – Recovery and Resilience Plan, Portuguese Republic, Funded by the European Union NextGenerationEU'},
    '2024/06/EU.png': {'pt': 'Este projeto recebeu financiamento do programa de investigação e inovação Horizonte Europa da União Europeia, acordo n.º 101145231', 'en': 'This project has received funding from the European Union’s Horizon Europe research and innovation programme under grant agreement No 101145231'},
    '2026/05/diver.sponge-scaled.jpg': {'pt': 'Mergulhador junto a uma esponja marinha', 'en': 'Diver next to a marine sponge'},
    '2026/05/Sea4Us_Foto_Nova_3-1-1-scaled-e1777917235170.jpg': {'pt': 'Investigadores da Sea4Us a examinar amostras ao estereomicroscópio', 'en': 'Sea4Us researchers examining samples under a stereo microscope'},
    '2026/05/Sea4UsTeam_Dez2023_3618-scaled-e1777918043177.jpg': {'pt': 'Equipa da Sea4Us, dezembro de 2023', 'en': 'Sea4Us team, December 2023'},
    '2026/05/Sea4UsTeam_Dez2023_3625-scaled-e1777918163936.jpg': {'pt': 'Equipa da Sea4Us, dezembro de 2023', 'en': 'Sea4Us team, December 2023'},
    '2026/05/underwater-pictures-1-scaled.jpg': {'pt': 'Ouriço-do-mar fotografado debaixo de água', 'en': 'Sea urchin photographed underwater'},
    '2026/05/Sagres_202507-3-scaled.jpeg': {'pt': 'Laboratório da Sea4Us em Sagres', 'en': 'Sea4Us lab in Sagres'},
    '2026/05/Sea4Us_Foto_Nova_3-2-scaled.jpg': {'pt': 'Investigador da Sea4Us no laboratório', 'en': 'Sea4Us researcher in the lab'},
    '2023/05/20221110_165759-scaled.jpg': {'pt': 'Falésias de Sagres vistas do mar', 'en': 'Sagres cliffs seen from the sea'},
    '2026/05/luisella-babai--scaled.jpg': {'pt': 'Nudibrânquio fotografado na costa portuguesa', 'en': 'Nudibranch photographed on the Portuguese coast'},
    '2020/08/Untitled-design-1-scaled.jpg': {'pt': 'Organismos marinhos no fundo do mar', 'en': 'Marine organisms on the seabed'},
    '2020/08/Untitled-design-scaled.jpg': {'pt': 'Organismos marinhos no fundo do mar', 'en': 'Marine organisms on the seabed'},
    '2026/05/Sea4UsLab_Apr2024_4440-scaled.jpg': {'pt': 'Investigação no Laboratório de Fisiologia', 'en': 'Research at the Physiology Lab'},
}
JR = {'2020/08/Untitled-design-1-scaled.jpg', '2020/08/Untitled-design-scaled.jpg'}
alts = {}
for f in glob.glob(os.path.join(L.SRC, 'extracted', '*', '*.json')):
    d = json.load(open(f, encoding='utf-8'))
    for i in d['images']:
        if i['local'] and i['alt'] and not re.match(r'^(CTA\d|IMG|DSC|image)', i['alt'], re.I):
            alts.setdefault(i['local'], {})[d['lang']] = i['alt']
media = []
files = L.upload_files()
for key in sorted(media_used):
    if key not in files:
        report['media referenced but missing on disk'].append(key)
        continue
    is_img = key.lower().endswith(('.jpg', '.jpeg', '.png', '.webp', '.gif'))
    credit = ''
    if key in JR:
        credit = 'João Rodrigues | CHIMERAVISUALS'
    elif is_img and re.search(r'(underwater|okamurae|diver|luisella|felimida|Rugulopteryx|Catedral|Sea4UsLab|Sea4UsTeam|Foto_Nova|Sagres_2025)', key):
        credit = '© Pedro A. Lima (a confirmar)'
    a = alts.get(key, {})
    a = {**a, **ALT_OVERRIDES.get(key, {})}
    media.append({'key': key, 'file': key, 'title': os.path.splitext(os.path.basename(key))[0],
                  'alt_pt': a.get('pt', ''), 'alt_en': a.get('en', a.get('pt', '')), 'credit': credit,
                  'source_url': 'https://sea4us.pt/wp-content/uploads/' + key})
json.dump(media, open(os.path.join(C, 'media.json'), 'w', encoding='utf-8'), ensure_ascii=False, indent=1)

with open(os.path.join(C, '_notes', 'assembly-report.md'), 'w', encoding='utf-8') as fh:
    fh.write('# Assembly report\n\n')
    fh.write(f'- news: {len(news)}\n- documents: {len(documents)}\n- media referenced: {len(media)}\n\n')
    for k, v in report.items():
        fh.write(f'## {k} ({len(v)})\n\n' + ''.join(f'- {x}\n' for x in sorted(set(v))) + '\n')
print(json.dumps({'news': len(news), 'documents': len(documents), 'media': len(media), **{k: len(set(v)) for k, v in report.items()}}, ensure_ascii=False))
