#!/usr/bin/env python3
"""Dump cleaned content of every old page and post (PT and EN) to source/extracted/ for editorial work."""
import os, sys, json, re
sys.path.insert(0, os.path.dirname(os.path.abspath(__file__)))
import source_lib as L
out = os.path.join(L.SRC, 'extracted')
os.makedirs(os.path.join(out, 'pages'), exist_ok=True)
os.makedirs(os.path.join(out, 'posts'), exist_ok=True)
for kind in ('pages', 'posts'):
    for lang in ('pt', 'en'):
        for x in L.rest(kind, lang):
            h, imgs, links, emb = L.clean(x['content']['rendered'])
            rec = {'id': x['id'], 'lang': lang, 'slug': x['slug'], 'link': x['link'], 'parent': x.get('parent'),
                   'date': x['date'], 'modified': x['modified'], 'title': L.text(x['title']['rendered']),
                   'featured_media': x.get('featured_media'), 'categories': x.get('categories'), 'tags': x.get('tags'),
                   'html': h, 'images': imgs, 'links': links, 'embeds': sorted(set(emb))}
            json.dump(rec, open(os.path.join(out, kind, f"{x['id']}-{x['slug'][:60]}.{lang}.json"), 'w', encoding='utf-8'), ensure_ascii=False, indent=1)
print('ok')
