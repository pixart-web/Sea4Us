"""Helpers to read the crawled sea4us.pt snapshot (source/) and turn Elementor markup into clean HTML."""
import json, os, re, html as htmlmod, urllib.parse
from bs4 import BeautifulSoup, NavigableString, Comment

ROOT = os.path.join(os.path.dirname(os.path.abspath(__file__)), '..')
SRC = os.path.join(ROOT, 'source')
UPLOADS = os.path.join(SRC, 'wp-content', 'uploads')

def rest(kind, lang):
    return json.load(open(os.path.join(SRC, 'docs', 'wp-json', f'{lang}_{kind}_p1.json'), encoding='utf-8'))

def text(s):
    return htmlmod.unescape(re.sub(r'<[^>]+>', '', s or '')).strip()

_files = None
def upload_files():
    global _files
    if _files is None:
        _files = set()
        for d, _, fs in os.walk(UPLOADS):
            for f in fs:
                _files.add(os.path.relpath(os.path.join(d, f), UPLOADS).replace(os.sep, '/'))
    return _files

def local_upload(url):
    """Map an uploads URL to the best local file (original if the size variant was not kept)."""
    if not url:
        return None
    p = urllib.parse.urlparse(htmlmod.unescape(url))
    if p.netloc and 'sea4us.pt' not in p.netloc:
        return None
    m = re.search(r'/wp-content/uploads/(.+)$', urllib.parse.unquote(p.path))
    if not m:
        return None
    rel = m.group(1)
    files = upload_files()
    if rel in files:
        return rel
    base = re.sub(r'-\d+x\d+(?=\.\w+$)', '', rel)
    for cand in (base, re.sub(r'-scaled(?=\.\w+$)', '', base)):
        if cand in files:
            return cand
    stem, ext = os.path.splitext(base)
    for e in ('.jpg', '.jpeg', '.png', '.webp', ext.upper(), ext.lower()):
        if stem + e in files:
            return stem + e
    if stem + '-scaled' + ext in files:
        return stem + '-scaled' + ext
    return None

KEEP = {'h2', 'h3', 'h4', 'p', 'ul', 'ol', 'li', 'a', 'strong', 'em', 'b', 'i', 'br', 'blockquote', 'img', 'figure', 'figcaption', 'table', 'thead', 'tbody', 'tr', 'td', 'th', 'sup', 'sub'}

def clean(rendered, keep_images=True):
    """Return (html, images, links) from Elementor-rendered content."""
    s = BeautifulSoup(rendered or '', 'lxml')
    for t in s.find_all(['script', 'style', 'noscript', 'svg', 'form', 'button']):
        t.decompose()
    for c in s.find_all(string=lambda x: isinstance(x, Comment)):
        c.extract()
    images, links, embeds = [], [], []
    for f in s.find_all(['iframe', 'video']):
        src = f.get('src') or f.get('data-src') or (f.find('source').get('src') if f.find('source') else '')
        if src:
            embeds.append(src)
        f.decompose()
    # Elementor video widgets keep the URL in data-settings.
    for el in s.find_all(attrs={'data-settings': True}):
        m = re.search(r'"(?:youtube_url|vimeo_url|hosted_url)":\s*"([^"]+)"', htmlmod.unescape(el['data-settings']))
        if m:
            embeds.append(m.group(1).replace('\\/', '/'))
    for h in s.find_all('h1'):
        h.name = 'h2'
    for h in s.find_all(['h5', 'h6']):
        h.name = 'h4'
    for img in s.find_all('img'):
        src = img.get('data-src') or img.get('src') or ''
        if src.startswith('data:'):
            src = img.get('data-lazy-src') or ''
        if keep_images and src:
            images.append({'src': src, 'alt': img.get('alt', '').strip(), 'local': local_upload(src)})
    for a in s.find_all('a'):
        href = a.get('href', '')
        if href:
            links.append({'href': href, 'text': a.get_text(' ', strip=True)})
    # Unwrap everything not in KEEP, strip attributes except href/src/alt.
    for t in list(s.find_all(True)):
        if t.name in ('html', 'body'):
            continue
        if t.name not in KEEP:
            t.unwrap()
            continue
        attrs = {}
        if t.name == 'a' and t.get('href'):
            attrs['href'] = t['href']
        if t.name == 'img':
            attrs['src'] = t.get('data-src') or t.get('src', '')
            attrs['alt'] = t.get('alt', '')
        t.attrs = attrs
    body = s.body or s
    out = ''.join(str(c) for c in body.contents)
    out = re.sub(r'\s+', ' ', out)
    out = re.sub(r'<(p|h2|h3|h4|li|strong|em)>\s*(&nbsp;|\xa0)?\s*</\1>', '', out)
    out = re.sub(r'\s*(<(?:/?(?:p|h2|h3|h4|ul|ol|li|blockquote|figure|table|tr)\b)[^>]*>)\s*', r'\1', out)
    out = strip_boiler(out)
    return out.strip(), images, links, embeds

def strip_boiler(h):
    # Old theme widgets: "Back to news" + recent posts list, gallery arrows, empty italics.
    h = re.split(r'<h2>\s*(?:Voltar para Not[ií]cias|Back to News)\s*</h2>', h)[0]
    h = re.sub(r'<i>\s*</i>\s*(Anterior|Previous)\s*<i>\s*</i>\s*(Seguinte|Next)', '', h)
    h = re.sub(r'<(i|em|strong|p)>\s*</\1>', '', h)
    return h.strip()

def words(htmlstr):
    return len(re.findall(r'\w+', text(htmlstr)))

def page_kind(soup):
    b = soup.body.get('class', []) if soup and soup.body else []
    if 'error404' in b: return '404'
    if 'home' in b: return 'home'
    if 'attachment' in b: return 'attachment'
    if 'single-post' in b: return 'post'
    if 'archive' in b: return 'archive'
    if 'page' in b: return 'page'
    if 'single-elementskit_content' in b: return 'template'
    return 'other'
