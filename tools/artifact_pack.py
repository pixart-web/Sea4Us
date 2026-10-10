#!/usr/bin/env python3
"""Turn the static export into a lighter preview: one optimised WebP per image (no srcset),
images used on at most N pages inlined as data URIs, unreferenced files dropped.
Keeps the file count under hosting limits. Usage: artifact_pack.py SRC OUT [--inline-max 2]"""
import base64, collections, io, os, re, shutil, sys
from PIL import Image

src, out = sys.argv[1], sys.argv[2]
inline_max = int(sys.argv[sys.argv.index('--inline-max') + 1]) if '--inline-max' in sys.argv else 2
if os.path.isdir(out):
    shutil.rmtree(out)
IMG = ('.jpg', '.jpeg', '.png', '.webp')
REF = re.compile(r'''(\b(?:src|href|poster|data-index)=)(["'])([^"']+)\2''')
pages = {}
for d, _, fs in os.walk(src):
    for f in fs:
        if f.endswith('.html'):
            p = os.path.relpath(os.path.join(d, f), src)
            h = open(os.path.join(d, f), encoding='utf-8').read()
            h = re.sub(r'\s(?:srcset|sizes)=(["\'])[^"\']*\1', '', h)
            pages[p] = h

def resolve(page, u):
    if u.startswith(('http:', 'https:', 'mailto:', 'tel:', 'data:', '#', 'javascript:')):
        return None
    path = u.split('#')[0].split('?')[0]
    if not path:
        return None
    return os.path.normpath(os.path.join(os.path.dirname(page), path))

use = collections.defaultdict(set)
for p, h in pages.items():
    for m in REF.finditer(h):
        r = resolve(p, m.group(3))
        if r and os.path.isfile(os.path.join(src, r)) and not r.endswith('.html'):
            use[r].add(p)

cache = {}
def webp(rel):
    if rel in cache:
        return cache[rel]
    im = Image.open(os.path.join(src, rel))
    im = im.convert('RGBA') if im.mode in ('P', 'LA', 'RGBA') else im.convert('RGB')
    if im.width > 1600:
        im = im.resize((1600, round(im.height * 1600 / im.width)), Image.LANCZOS)
    b = io.BytesIO()
    im.save(b, 'WEBP', quality=78, method=5)
    cache[rel] = b.getvalue()
    return cache[rel]

written = set()
def emit(rel, data=None):
    dst = os.path.join(out, rel)
    os.makedirs(os.path.dirname(dst), exist_ok=True)
    if data is None:
        shutil.copy2(os.path.join(src, rel), dst)
    else:
        open(dst, 'wb').write(data)
    written.add(rel)

for p, h in pages.items():
    def sub(m):
        attr, q, u = m.groups()
        r = resolve(p, u)
        if not r or not os.path.isfile(os.path.join(src, r)) or r.endswith('.html'):
            return m.group(0)
        if r.lower().endswith(IMG) and 'wp-content/themes/' not in r:
            if len(use[r]) <= inline_max:
                return f'{attr}{q}data:image/webp;base64,{base64.b64encode(webp(r)).decode()}{q}'
            nr = os.path.splitext(r)[0] + '.webp'
            if nr not in written:
                emit(nr, webp(r))
            return f'{attr}{q}{os.path.relpath(nr, os.path.dirname(p))}{q}'
        if os.path.getsize(os.path.join(src, r)) > 14_000_000 and '/uploads/' in r:
            # Over the preview host's per-file limit: point to the same file on the current site.
            return f'{attr}{q}https://sea4us.pt/{r[r.index("wp-content/"):]}{q}'
        if r not in written:
            emit(r)
        return m.group(0)
    h = REF.sub(sub, h)
    os.makedirs(os.path.dirname(os.path.join(out, p)) or out, exist_ok=True)
    open(os.path.join(out, p), 'w', encoding='utf-8').write(h)

# Files referenced from CSS (fonts) and other non-HTML assets.
for d, _, fs in os.walk(src):
    for f in fs:
        rel = os.path.relpath(os.path.join(d, f), src)
        if rel.endswith(('.css',)) and rel in written:
            css = open(os.path.join(src, rel), encoding='utf-8', errors='replace').read()
            for u in re.findall(r'url\((["\']?)([^)"\']+)\1\)', css):
                if u[1].startswith(('data:', 'http')):
                    continue
                r = os.path.normpath(os.path.join(os.path.dirname(rel), u[1].split('?')[0].split('#')[0]))
                if os.path.isfile(os.path.join(src, r)) and r not in written:
                    emit(r)
for extra in ('robots.txt', 'wp-content/uploads/sea4us/search-pt.json', 'wp-content/uploads/sea4us/search-en.json'):
    if os.path.isfile(os.path.join(src, extra)):
        emit(extra)
n = sum(len(fs) for _, _, fs in os.walk(out))
size = sum(os.path.getsize(os.path.join(d, f)) for d, _, fs in os.walk(out) for f in fs)
big = max(((os.path.getsize(os.path.join(d, f)), os.path.join(d, f)) for d, _, fs in os.walk(out) for f in fs))
print(f'files: {n}  size: {size/1e6:.1f} MB  largest: {big[0]/1e6:.1f} MB {big[1]}')
