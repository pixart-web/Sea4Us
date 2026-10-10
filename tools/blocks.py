"""Tiny helpers that write Gutenberg block markup, so page content stays editable in the block editor."""
import json
import re


def _unquote_ids(a):
    return re.sub(r'"(\{\{media-id:[^}]+\}\})"', r'\1', a)


def _attrs(a):
    return (' ' + json.dumps(a, ensure_ascii=False, separators=(',', ':'))) if a else ''


def s4u(name, **attrs):
    """Dynamic Sea4Us block. Numeric placeholders ({{media-id:..}}) are passed as strings and unquoted."""
    a = _attrs(attrs)
    # Allow "imageId":"{{media-id:KEY}}" to become a bare number after placeholder resolution.
    a = _unquote_ids(a)
    return f'<!-- wp:sea4us/{name}{a} /-->'


def h(text, level=2, cls=''):
    a = {}
    if level != 2:
        a['level'] = level
    if cls:
        a['className'] = cls
    c = f' class="wp-block-heading {cls}"' if cls else ' class="wp-block-heading"'
    return f'<!-- wp:heading{_attrs(a)} --><h{level}{c}>{text}</h{level}><!-- /wp:heading -->'


def p(text, cls=''):
    a = {'className': cls} if cls else {}
    c = f' class="{cls}"' if cls else ''
    return f'<!-- wp:paragraph{_attrs(a)} --><p{c}>{text}</p><!-- /wp:paragraph -->'


def ul(items, cls='', ordered=False):
    a = {}
    if ordered:
        a['ordered'] = True
    if cls:
        a['className'] = cls
    tag = 'ol' if ordered else 'ul'
    lis = ''.join(f'<!-- wp:list-item --><li>{i}</li><!-- /wp:list-item -->' for i in items)
    klass = ('wp-block-list ' + cls).strip()
    return f'<!-- wp:list{_attrs(a)} --><{tag} class="{klass}">{lis}</{tag}><!-- /wp:list -->'


def group(inner, cls='', tag='div', anchor=''):
    a = {}
    if anchor:
        a['anchor'] = anchor
    if tag != 'div':
        a['tagName'] = tag
    if cls:
        a['className'] = cls
    a['layout'] = {'type': 'default'}
    idattr = f' id="{anchor}"' if anchor else ''
    body = ''.join(inner) if isinstance(inner, (list, tuple)) else inner
    klass = ('wp-block-group ' + cls).strip()
    return f'<!-- wp:group{_attrs(a)} --><{tag}{idattr} class="{klass}">{body}</{tag}><!-- /wp:group -->'


def section(inner, tone='', anchor='', extra=''):
    cls = 's4u-section' + (f' s4u-section--{tone}' if tone else '') + (f' {extra}' if extra else '')
    return group(inner, cls, 'section', anchor)


def container(inner, narrow=False):
    return group(inner, 's4u-container' + (' s4u-container--narrow s4u-prose' if narrow else ''))


def head(eyebrow, title, text='', link=None, light=False):
    parts = []
    if eyebrow:
        ecls = 's4u-eyebrow' + (' s4u-eyebrow--light' if light else '')
        parts.append(p(eyebrow, ecls))
    parts.append(h(title, 2, 's4u-head__title s4u-h2'))
    if text:
        parts.append(p(text, 's4u-head__text'))
    if link:
        parts.append(p(f'<a class="s4u-link-arrow" href="{link[1]}">{link[0]}</a>'))
    return group(parts, 's4u-head')


def intro(title, body_parts, eyebrow=''):
    left = ([p(eyebrow, 's4u-eyebrow')] if eyebrow else []) + [h(title, 2, 's4u-h2')]
    return group([group(left, 's4u-intro__title'), group(body_parts, 's4u-intro__body')], 's4u-intro')


def pillars(items):
    """items: [(number, title, text)]"""
    return group([group([p(n, 's4u-pillar__n'), h(t, 3), p(x)], 's4u-pillar') for n, t, x in items], 's4u-pillars')


def statement(text, small=''):
    return group([p(text)] + ([p(small, 's4u-muted')] if small else []), 's4u-statement')


def media_text(img_key, alt, inner, right=False, credit=''):
    a = {'mediaId': '{{media-id:%s}}' % img_key, 'mediaType': 'image', 'className': 's4u-split-media', 'mediaPosition': 'right' if right else 'left'}
    if not right:
        del a['mediaPosition']
    attrs = _unquote_ids(_attrs(a))
    cap = f'<figcaption class="s4u-figure-credit">{credit}</figcaption>' if credit else ''
    cls = 'wp-block-media-text s4u-split-media' + (' has-media-on-the-right' if right else '') + ' is-stacked-on-mobile'
    body = ''.join(inner)
    return (f'<!-- wp:media-text{attrs} --><div class="{cls}"><figure class="wp-block-media-text__media">'
            f'<img src="{{{{media:{img_key}}}}}" alt="{alt}" class="wp-image-{{{{media-id:{img_key}}}}} size-full"/>{cap}</figure>'
            f'<div class="wp-block-media-text__content">{body}</div></div><!-- /wp:media-text -->')


def facts(rows):
    """rows: [(label, value_html)] rendered as a definition list (HTML block, editable as HTML)."""
    dl = ''.join(f'<div><dt>{k}</dt><dd>{v}</dd></div>' for k, v in rows)
    return f'<!-- wp:html --><dl class="s4u-facts">{dl}</dl><!-- /wp:html -->'


def callout(parts):
    return group(parts, 's4u-callout')


def split(main, side):
    return group([group(main, 's4u-split__main'), group(side, 's4u-split__side')], 's4u-container s4u-split')


def html(raw):
    return f'<!-- wp:html -->{raw}<!-- /wp:html -->'
