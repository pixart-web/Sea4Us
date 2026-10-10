#!/usr/bin/env python3
"""
Static preview exporter for the Sea4Us WordPress build.

Crawls every published URL of the local WordPress (from its XML sitemaps + language homes,
search and 404 pages), saves each page as <path>/index.html, copies every local asset the
pages reference (theme assets, uploads, core block CSS) and rewrites links to relative
paths so the preview works from any folder or static host.

The contact form keeps demo mode: with no server behind it, the browser-side validation
runs and the demo confirmation is shown; nothing is sent.

Usage: python3 export_static.py --base http://localhost:8080 --docroot /path/to/wp --out /path/to/preview
"""
import argparse
import html
import os
import posixpath
import re
import shutil
import sys
import urllib.parse
import urllib.request
import json

ATTR_RE = re.compile(r'''(?P<attr>\b(?:href|src|action|data-index|poster))=(?P<q>["'])(?P<url>[^"']*)(?P=q)''')
SRCSET_RE = re.compile(r'''\bsrcset=(["'])([^"']*)\1''')
CSS_URL_RE = re.compile(r'''url\((['"]?)([^)'"]+)\1\)''')


def fetch(url):
    req = urllib.request.Request(url, headers={'User-Agent': 'sea4us-static-export'})
    try:
        with urllib.request.urlopen(req) as r:
            return r.status, r.read(), r.headers.get('Content-Type', '')
    except urllib.error.HTTPError as e:
        return e.code, e.read(), e.headers.get('Content-Type', '')


def sitemap_urls(base):
    urls, todo, seen = [], [base + '/wp-sitemap.xml'], set()
    while todo:
        u = todo.pop()
        if u in seen:
            continue
        seen.add(u)
        status, body, _ = fetch(u)
        if status != 200:
            continue
        for loc in re.findall(r'<loc>([^<]+)</loc>', body.decode('utf-8', 'replace')):
            loc = html.unescape(loc)
            (todo if loc.endswith('.xml') else urls).append(loc)
    return urls


def page_file(path):
    path = urllib.parse.unquote(path)
    if path.endswith('/') or path == '':
        return path.lstrip('/') + 'index.html'
    if '.' in posixpath.basename(path):
        return path.lstrip('/')
    return path.lstrip('/') + '/index.html'


def rel(from_file, to_file):
    r = posixpath.relpath(to_file, posixpath.dirname(from_file) or '.')
    return r


def main():
    ap = argparse.ArgumentParser()
    ap.add_argument('--base', required=True)
    ap.add_argument('--docroot', required=True)
    ap.add_argument('--out', required=True)
    a = ap.parse_args()
    base = a.base.rstrip('/')
    host = urllib.parse.urlparse(base).netloc

    if os.path.isdir(a.out):
        shutil.rmtree(a.out)
    os.makedirs(a.out)

    urls = sitemap_urls(base)
    for extra in ('/pt/', '/en/', '/pt/pesquisa/', '/en/search/'):
        if base + extra not in urls:
            urls.append(base + extra)
    pages, assets = {}, set()
    for u in sorted(set(urls)):
        status, body, ctype = fetch(u)
        if status != 200 or 'html' not in ctype:
            print('skip', status, u, file=sys.stderr)
            continue
        pages[urllib.parse.urlparse(u).path] = body.decode('utf-8')
    # Localised 404 pages.
    for lang in ('pt', 'en'):
        status, body, _ = fetch(base + '/' + lang + '/pagina-inexistente-404/')
        pages['/' + lang + '/404/'] = body.decode('utf-8')

    def local_path(url):
        p = urllib.parse.urlparse(html.unescape(url))
        if p.scheme in ('mailto', 'tel', 'javascript', 'data') or (p.netloc and p.netloc != host):
            return None
        if not p.path.startswith('/'):
            return None
        return p

    def rewrite(doc, from_file):
        def sub_url(url):
            p = local_path(url)
            if not p:
                return url
            path = p.path
            if path.startswith('/wp-json/'):
                return url  # Form endpoint: unreachable in the static preview (demo fallback).
            if re.search(r'\.[a-z0-9]{2,5}$', path, re.I) and not path.endswith('.html'):
                assets.add(urllib.parse.unquote(path))
                target = urllib.parse.unquote(path).lstrip('/')
            else:
                target = page_file(path)
            out = rel(from_file, target)
            if p.query and target.endswith('index.html') is False:
                out += '?' + p.query
            elif p.query:
                out += '?' + p.query
            if p.fragment:
                out += '#' + p.fragment
            return out

        def attr_sub(m):
            attr, q, url = m.group('attr'), m.group('q'), m.group('url')
            if attr == 'content' and not url.startswith(base):
                return m.group(0)
            return f'{attr}={q}{sub_url(url)}{q}'

        doc = ATTR_RE.sub(attr_sub, doc)
        doc = SRCSET_RE.sub(lambda m: 'srcset=' + m.group(1) + ', '.join(
            ' '.join([sub_url(part.strip().split(' ')[0])] + part.strip().split(' ')[1:])
            for part in m.group(2).split(',') if part.strip()) + m.group(1), doc)
        # Inline style url(...)
        doc = CSS_URL_RE.sub(lambda m: 'url(' + m.group(1) + sub_url(m.group(2)) + m.group(1) + ')', doc)
        # Search box needs a base to build result links.
        root_rel = rel(from_file, 'index.html')[:-len('index.html')] or './'
        doc = doc.replace('data-s4u-search ', f'data-s4u-search data-base="{root_rel}" ', 1)
        # Absolute URLs left in metadata (canonical, hreflang, Open Graph, JSON-LD) point to the future domain.
        doc = doc.replace(base + '/', 'https://sea4us.pt/').replace(base.replace('/', '\\/') + '\\/', 'https:\\/\\/sea4us.pt\\/')
        # Drop REST discovery links.
        doc = re.sub(r'<link rel=["\'](?:https://api\.w\.org/|alternate)["\'][^>]*wp-json[^>]*>\s*', '', doc)
        return doc

    for path, doc in pages.items():
        f = page_file(path)
        out = os.path.join(a.out, f)
        os.makedirs(os.path.dirname(out), exist_ok=True)
        with open(out, 'w', encoding='utf-8') as fh:
            fh.write(rewrite(doc, f))

    # Root: the static preview cannot send a server redirect, so it offers both languages (no automatic switching).
    with open(os.path.join(a.out, 'index.html'), 'w', encoding='utf-8') as fh:
        fh.write('<!doctype html><html lang="pt-PT"><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1">'
                 '<meta name="robots" content="noindex,nofollow"><title>Sea4Us · Pré-visualização</title>'
                 '<style>body{margin:0;min-height:100vh;display:grid;place-items:center;background:#071F2B;color:#fff;font:16px/1.5 system-ui,sans-serif}'
                 'main{text-align:center;padding:2rem}img{height:120px}a{display:inline-block;margin:.5rem;padding:.8rem 1.6rem;border-radius:999px;background:#39B8BC;color:#071F2B;font-weight:700;text-decoration:none}'
                 'a:focus-visible{outline:3px solid #fff;outline-offset:3px}p{color:#B9CCD2}</style>'
                 '<main><img src="wp-content/themes/sea4us/assets/img/logo-sea4us-white.png" alt="Sea4Us"><p>Proposta de reformulação · pré-visualização não indexada<br>Redesign proposal · non-indexed preview</p>'
                 '<a href="pt/index.html" hreflang="pt-PT" lang="pt-PT">Português</a><a href="en/index.html" hreflang="en" lang="en">English</a></main></html>')
    assets.add('/wp-content/themes/sea4us/assets/img/logo-sea4us-white.png')
    with open(os.path.join(a.out, 'robots.txt'), 'w') as fh:
        fh.write('User-agent: *\nDisallow: /\n')

    # Copy assets, following url() references inside CSS files.
    done = set()
    queue = list(assets)
    while queue:
        p = queue.pop()
        if p in done:
            continue
        done.add(p)
        src = os.path.join(a.docroot, p.lstrip('/'))
        dst = os.path.join(a.out, p.lstrip('/'))
        if not os.path.isfile(src):
            print('missing asset', p, file=sys.stderr)
            continue
        os.makedirs(os.path.dirname(dst), exist_ok=True)
        shutil.copy2(src, dst)
        if p.endswith('.css'):
            css = open(src, encoding='utf-8', errors='replace').read()
            for _, u in CSS_URL_RE.findall(css):
                if u.startswith('data:') or u.startswith('http'):
                    continue
                queue.append(posixpath.normpath(posixpath.join(posixpath.dirname(p), u.split('?')[0].split('#')[0])))

    # Search indexes: make result URLs relative to the site root, pointing at index.html files.
    for lang in ('pt', 'en'):
        src = os.path.join(a.docroot, f'wp-content/uploads/sea4us/search-{lang}.json')
        if os.path.isfile(src):
            rows = json.load(open(src, encoding='utf-8'))
            for r in rows:
                r['u'] = page_file(urllib.parse.urlparse(r['u']).path)
            dst = os.path.join(a.out, f'wp-content/uploads/sea4us/search-{lang}.json')
            os.makedirs(os.path.dirname(dst), exist_ok=True)
            json.dump(rows, open(dst, 'w', encoding='utf-8'), ensure_ascii=False)

    print(f'pages: {len(pages)}  assets: {len(done)}  out: {a.out}')


if __name__ == '__main__':
    main()
