#!/usr/bin/env python3
"""Old → new URL map (inventory/url-map.csv, every URL) and content/redirects.csv (301s only, read by the importer)."""
import csv, os
ROOT = os.path.join(os.path.dirname(os.path.abspath(__file__)), '..')
rows = list(csv.DictReader(open(os.path.join(ROOT, 'inventory', 'migration-inventory.csv'), encoding='utf-8')))
by_path = {r['path']: r for r in rows}
out, redirects, seen = [], [], set()
for r in rows:
    if r['path'] in seen:
        continue
    seen.add(r['path'])
    act, new = r['action'], r['proposed_url']
    if act == '301 to parent':
        parent = '/' + '/'.join(r['path'].strip('/').split('/')[:-1]) + '/'
        new = by_path.get(parent, {}).get('proposed_url', '')
        if not new and parent.endswith('/home/'):  # attachments of the old home page
            new = '/' + r['lang'] + '/'
    code = '301' if '301' in act and new and new != r['path'] else ('keep' if act == 'keep path' else 'none')
    out.append({'old_url': r['url'], 'old_path': r['path'], 'lang': r['lang'], 'old_type': r['type'], 'title': r['title'],
                'new_path': new if code != 'none' else '', 'action': code, 'reason': r['notes']})
    if code == '301':
        redirects.append({'old_path': r['path'], 'new_path': new, 'action': '301', 'type': r['type']})
F = ['old_url', 'old_path', 'lang', 'old_type', 'title', 'new_path', 'action', 'reason']
with open(os.path.join(ROOT, 'inventory', 'url-map.csv'), 'w', newline='', encoding='utf-8') as fh:
    w = csv.DictWriter(fh, F); w.writeheader(); w.writerows(out)
with open(os.path.join(ROOT, 'content', 'redirects.csv'), 'w', newline='', encoding='utf-8') as fh:
    w = csv.DictWriter(fh, ['old_path', 'new_path', 'action', 'type']); w.writeheader(); w.writerows(redirects)
import collections
print(collections.Counter(o['action'] for o in out), 'missing parent targets:', sum(1 for o in out if o['action'] == 'none' and 'parent' in o['reason']))
