#!/usr/bin/env python3
"""Editorial content for the new Sea4Us pages (PT-PT and EN), the programmes and site settings.

Writes content/pages.json, content/programs.json and content/site.json.
Every fact used here is traceable in inventory/claims-register.csv.
"""
import json, os, sys
sys.path.insert(0, os.path.dirname(os.path.abspath(__file__)))
from blocks import *  # noqa

ROOT = os.path.join(os.path.dirname(os.path.abspath(__file__)), '..')
OUT = os.path.join(ROOT, 'content')
UP = os.path.join(ROOT, 'source', 'wp-content', 'uploads')

IMG = {
    'hero': '2026/05/diver.sponge-scaled.jpg',
    'inset': '2026/05/Sea4Us_Foto_Nova_3-1-1-scaled-e1777917235170.jpg',
    'team': '2026/05/Sea4UsTeam_Dez2023_3618-scaled-e1777918043177.jpg',
    'team2': '2026/05/Sea4UsTeam_Dez2023_3625-scaled-e1777918163936.jpg',
    'sagres': '2023/05/20221110_165759-scaled.jpg',
    'marbio': '2020/08/Untitled-design-1-scaled.jpg',
    'elec': '2020/08/Untitled-design-scaled.jpg',
    'lab': '2026/05/Sea4UsLab_Apr2024_4440-scaled.jpg',
    'lab2': '2026/05/Sea4UsLab_2023_3526.jpg',
    'newlab': '2026/05/Sagres_202507-3-scaled.jpeg',
    'duo': '2026/05/Sea4Us_Foto_Nova_3-2-scaled.jpg',
    'nudi': '2026/05/luisella-babai--scaled.jpg',
    'urchin': '2026/05/underwater-pictures-1-scaled.jpg',
    'rugulo': '2026/05/Foto-Rugulopteryx_DV1-scaled.jpg',
    'cave': '2021/01/Catedral_Afonos_Michal-scaled-e1777915840359.jpg',
    'pipette': '2026/05/Sea4UsLab_Apr2024_4279.jpg',
}
for k, v in IMG.items():
    assert os.path.isfile(os.path.join(UP, v)), v


def mid(k):
    return '{{media-id:%s}}' % IMG[k]


def L(pt, en):
    return {'pt': pt, 'en': en}


def page(key, parent, order, pt, en, seo=None, legacy=None, image=None):
    d = {'key': key, 'parent': parent, 'order': order}
    for lang, data in (('pt', pt), ('en', en)):
        title, slug, content = data
        e = {'title': title, 'slug': slug, 'content': content}
        if seo:
            e['seo_title'], e['seo_desc'] = seo[lang]
        if legacy and legacy.get(lang):
            e['legacy_url'] = legacy[lang]
        if image:
            e['image'] = IMG[image]
        d[lang] = e
    return d


def link(label, ref):
    return f'<a class="s4u-link-arrow" href="{ref}">{label}</a>'


# ---------------------------------------------------------------- Home
def home(lang):
    T = lambda pt, en: pt if lang == 'pt' else en  # noqa
    return ''.join([
        s4u('hero',
            eyebrow=T('Biotecnologia de origem marinha', 'Marine-derived biotechnology'),
            title=T('Do oceano, novas possibilidades para a saúde.', 'From the ocean, new possibilities for health.'),
            text=T('A Sea4Us investiga compostos inspirados em organismos marinhos para necessidades clínicas ainda sem resposta adequada. O programa mais avançado é um candidato a analgésico, o S072.',
                   'Sea4Us researches compounds inspired by marine organisms for clinical needs that still lack adequate answers. Its most advanced programme is an analgesic candidate, S072.'),
            cta1Label=T('Conheça a nossa ciência', 'Discover our science'), cta1Url='{{page:science}}',
            cta2Label=T('Explore oportunidades de colaboração', 'Explore collaboration opportunities'), cta2Url='{{page:partnering}}',
            imageId=mid('hero'), secondaryImageId=mid('inset')),
        section([
            statement(T('<em>Do oceano à descoberta científica.</em> Partimos da biodiversidade marinha da costa de Sagres e da eletrofisiologia para procurar novas formas de tratar a dor e outras doenças com necessidades por responder.',
                        '<em>From the ocean to scientific discovery.</em> We start from the marine biodiversity of the Sagres coast and from electrophysiology to look for new ways of treating pain and other diseases with unmet needs.')),
            pillars([
                ('01', T('Oceano', 'Ocean'), T('Recolha e identificação de espécies marinhas em Sagres, onde nasceu a estação marinha da empresa.', 'Collection and identification of marine species in Sagres, where the company’s marine station began.')),
                ('02', T('Laboratório', 'Laboratory'), T('Testes eletrofisiológicos da atividade neuronal de cada composto, tendo como alvos canais iónicos.', 'Electrophysiological testing of each compound’s neuronal activity, with ion channels as targets.')),
                ('03', T('Desenvolvimento', 'Development'), T('Otimização química, ensaios pré-clínicos e preparação regulamentar, no Laboratório de Fisiologia em Lisboa.', 'Chemical optimisation, preclinical studies and regulatory preparation, at the Physiology Lab in Lisbon.')),
            ]),
        ]),
        s4u('featured-program', eyebrow=T('Programa principal', 'Lead programme')),
        section([
            head(T('Pipeline', 'Pipeline'), T('Onde está cada programa', 'Where each programme stands'),
                 T('Mostramos apenas estádios documentados em fonte pública datada. O que ainda não está confirmado aparece como tal.',
                   'We only show stages documented in a dated public source. Anything not yet confirmed is shown as such.'),
                 (T('Ver pipeline completo', 'See full pipeline'), '{{page:programs}}')),
            s4u('pipeline', compact=True, showLegend=False),
        ], 'mineral'),
        section([
            head(T('Projetos e financiamento', 'Projects and funding'), T('Investigação com parceiros e financiamento público', 'Research with partners and public funding'), '',
                 (T('Todos os projetos', 'All projects'), '{{page:projects}}')),
            s4u('projects', count=3, index=False, highlightOnly=True),
        ]),
        section([
            head(T('Equipa', 'Team'), T('Ciência feita por pessoas', 'Science made by people'),
                 T('Uma equipa multidisciplinar entre Sagres e Lisboa, com competências em neurofisiologia, biologia marinha, química e biotecnologia.',
                   'A multidisciplinary team between Sagres and Lisbon, with expertise in neurophysiology, marine biology, chemistry and biotechnology.'),
                 (T('Conhecer a equipa', 'Meet the team'), '{{page:team}}')),
            s4u('team', count=4, compact=True),
        ], 'mineral'),
        section([
            head(T('Notícias', 'News'), T('Últimas notícias', 'Latest news'), '', (T('Arquivo de notícias', 'News archive'), '{{page:news}}')),
            s4u('news', count=3),
            h(T('Marcos', 'Milestones'), 3, 's4u-container s4u-h3'),
            s4u('milestones', count=5),
        ]),
        section([
            head(T('Parceiros', 'Partners'), T('Parceiros oficiais', 'Official partners'), '', (T('Parceiros e colaborações', 'Partners and collaborations'), '{{page:partners}}')),
            s4u('partners', type='official', heading='-'),
        ], 'mineral'),
        s4u('cta'),
        section([
            head(T('Onde estamos', 'Where we are'), T('Dois laboratórios, entre o mar e a cidade', 'Two labs, between the sea and the city'), '',
                 (T('Contactos', 'Contact'), '{{page:contact}}')),
            s4u('locations'),
        ]),
    ])


# ---------------------------------------------------------------- About
def about(lang):
    T = lambda pt, en: pt if lang == 'pt' else en  # noqa
    return ''.join([
        s4u('page-hero', eyebrow=T('Sobre nós', 'About us'), title=T('Uma empresa de biotecnologia nascida em Sagres', 'A biotechnology company born in Sagres'),
            text=T('A Sea4Us descobre e desenvolve novos medicamentos a partir de compostos de origem marinha, para condições clínicas que ainda pedem novas abordagens químicas e moleculares.',
                   'Sea4Us discovers and develops new medicines from marine-derived compounds, for clinical conditions that still call for new chemical and molecular approaches.'),
            imageId=mid('team'), tone='dark'),
        section([
            intro(T('História', 'History'), [
                p(T('A Sea4Us foi criada em 2013 como spin-off da Universidade NOVA de Lisboa, por sete cofundadores inspirados pela biodiversidade marinha do Atlântico em Sagres, no sul de Portugal. Foi aí que nasceu a estação marinha da empresa.',
                    'Sea4Us was created in 2013 as a spin-off from NOVA University Lisbon, by seven co-founders inspired by the marine biodiversity of the Atlantic in Sagres, in southern Portugal. That is where the company’s marine station was born.')),
                p(T('Sagres continua a ser o principal local de recolha de amostras marinhas. O desenvolvimento pré-clínico é feito em Lisboa, no Laboratório de Fisiologia. Em 2021, a empresa passou de sociedade por quotas a sociedade anónima.',
                    'Sagres remains the main site for collecting marine samples. Preclinical development is carried out in Lisbon, at the Physiology Lab. In 2021 the company changed from a private limited company to a public limited company (SA).')),
                p(T('<em>«As dores e as feridas adquiridas na terra são curadas no mar.»</em> Ditado antigo de pescadores do sudoeste português, citado no site da Sea4Us.',
                    '<em>“Aches and wounds acquired on land are cured in the sea.”</em> An old saying of fishermen from south-west Portugal, quoted on the Sea4Us website.'), 's4u-muted'),
            ], T('Desde 2013', 'Since 2013')),
        ]),
        section([
            head(T('Missão e visão', 'Mission and vision'), T('O que nos orienta', 'What guides us')),
            pillars([
                ('01', T('Missão', 'Mission'), T('Investigar, desenvolver e inovar em substâncias e moléculas bioativas para necessidades clínicas e científicas, transformando-as em produtos de valor acrescentado.',
                                                  'To research, develop and innovate with bioactive substances and molecules for clinical and scientific needs, turning them into higher value-added products.')),
                ('02', T('Visão', 'Vision'), T('Ser uma empresa de biotecnologia de referência mundial na investigação e desenvolvimento de produtos farmacêuticos de origem marinha, desenvolvendo tratamentos para doenças sem cura a partir dos recursos marinhos portugueses.',
                                                'To be a world-class biotechnology company in the research and development of marine pharmaceutical products, developing treatments for diseases without a cure from Portuguese marine resources.')),
                ('03', T('Visão da inovação', 'Innovation vision'), T('Explorar os recursos marinhos de forma sustentável e investigar o potencial biotecnológico de moléculas marinhas com aplicação clínica, com foco em doenças sem tratamento adequado, contribuindo também para o desenvolvimento do Algarve.',
                                                                    'To explore marine resources sustainably and investigate the biotechnological potential of marine molecules with clinical application, focusing on diseases without adequate treatment, while also contributing to the development of the Algarve.')),
            ]),
        ], 'mineral', 'missao' if lang == 'pt' else 'mission'),
        section([container([
            h(T('Política de inovação', 'Innovation policy'), 2, 's4u-h3'),
            ul([T('Promover a consciencialização de todos os interessados nas atividades da Sea4Us para a realização planeada e monitorizada das atividades de investigação, desenvolvimento e inovação de novas substâncias e moléculas bioativas.',
                  'Promote awareness among everyone involved in Sea4Us’s activities of the planned and monitored implementation of research, development and innovation on new substances and bioactive molecules.'),
                T('Assegurar a adequada operacionalização das atividades, a disseminação e valorização do conhecimento obtido, a sustentabilidade da organização, a melhoria contínua da eficácia do Sistema de Gestão da IDI e o cumprimento dos requisitos aplicáveis e dos compromissos assumidos.',
                  'Ensure the proper implementation of activities, the dissemination and valorisation of the knowledge obtained, the sustainability of the organisation, the continuous improvement of the effectiveness of the RDI Management System and compliance with applicable requirements and commitments.')]),
        ], narrow=True)], anchor='politica-inovacao' if lang == 'pt' else 'innovation-policy'),
        section([
            head(T('Equipa', 'Team'), T('Quem faz a Sea4Us', 'The people behind Sea4Us'), '', (T('Ver equipa completa', 'See full team'), '{{page:team}}')),
            s4u('team', count=6, compact=True),
        ], 'mineral'),
        s4u('cta'),
    ])


def team(lang):
    T = lambda pt, en: pt if lang == 'pt' else en  # noqa
    return ''.join([
        s4u('page-hero', eyebrow=T('Sobre nós', 'About us'), title=T('Equipa', 'Team'),
            text=T('A Sea4Us reúne investigadores e profissionais de neurofisiologia, biotecnologia, bioquímica, biologia e química, entre Sagres e Lisboa.',
                   'Sea4Us brings together researchers and professionals in neurophysiology, biotechnology, biochemistry, biology and chemistry, between Sagres and Lisbon.'),
            imageId=mid('team2'), tone='light'),
        section([s4u('team')]),
        s4u('cta'),
    ])


# ---------------------------------------------------------------- Science
def science(lang):
    T = lambda pt, en: pt if lang == 'pt' else en  # noqa
    jr = T('Fotografia: João Rodrigues | CHIMERAVISUALS', 'Photography: João Rodrigues | CHIMERAVISUALS')
    return ''.join([
        s4u('page-hero', eyebrow=T('Ciência e tecnologia', 'Science and technology'), title=T('Do fundo do mar à bancada do laboratório', 'From the seabed to the lab bench'),
            text=T('A Sea4Us combina biologia marinha e eletrofisiologia: gera bibliotecas de compostos de origem marinha, testa-os contra alvos terapêuticos e leva os mais promissores para desenvolvimento pré-clínico.',
                   'Sea4Us combines marine biology and electrophysiology: it builds libraries of marine-derived compounds, screens them against therapeutic targets and takes the most promising into preclinical development.'),
            imageId=mid('urchin'), tone='dark'),
        section([
            media_text(IMG['marbio'], T('Organismos marinhos fotografados no fundo do mar', 'Marine organisms photographed on the seabed'), [
                p(T('01 · Biologia marinha', '01 · Marine biology'), 's4u-eyebrow'),
                h(T('A descoberta começa no oceano', 'Discovery begins in the ocean'), 2, 's4u-h3'),
                p(T('A interação entre espécies e a bioatividade dos seus compostos são a fase inicial do nosso processo de descoberta de fármacos. Milhões de anos de evolução deram aos animais marinhos soluções muito especializadas de defesa contra predadores, incluindo compostos com uma grande variedade de propriedades farmacológicas descritas.',
                    'The interaction between species and the bioactivity of their compounds are the first stage of our drug discovery process. Millions of years of evolution have given marine animals highly specialised defences against predators, including compounds with a wide range of described pharmacological properties.')),
            ], credit=jr),
            media_text(IMG['elec'], T('Corais moles fotografados no fundo do mar', 'Soft corals photographed on the seabed'), [
                p(T('02 · Eletrofisiologia', '02 · Electrophysiology'), 's4u-eyebrow'),
                h(T('Medir a atividade neuronal de cada composto', 'Measuring each compound’s neuronal activity'), 2, 's4u-h3'),
                p(T('Os testes eletrofisiológicos avaliam a bioatividade neuronal de cada composto da nossa biblioteca, porque os nossos alvos terapêuticos são canais iónicos envolvidos em patologias. Após esta validação, são feitos ensaios <em>in silico</em> e <em>ex vivo</em> para avaliar níveis de toxicidade.',
                    'Electrophysiological tests assess the neuronal bioactivity of each compound in our library, because our therapeutic targets are ion channels involved in disease. After this validation, <em>in silico</em> and <em>ex vivo</em> assays are run to assess toxicity levels.')),
            ], right=True, credit=jr),
        ], anchor='metodologia' if lang == 'pt' else 'methodology'),
        section([
            head(T('Processo', 'Process'), T('Da amostra ao candidato', 'From sample to candidate')),
            pillars([
                ('01', T('Recolha', 'Collection'), T('Identificação e recolha das espécies de interesse, com processamento e armazenamento no local.', 'Identification and collection of species of interest, with processing and storage on site.')),
                ('02', T('Biblioteca', 'Library'), T('Criação de uma biblioteca de compostos de origem marinha.', 'Building a library of marine-derived compounds.')),
                ('03', T('Rastreio', 'Screening'), T('Teste dos compostos contra alvos terapêuticos, com foco em canais iónicos.', 'Screening compounds against therapeutic targets, focusing on ion channels.')),
                ('04', T('Desenvolvimento pré-clínico', 'Preclinical development'), T('Otimização química, segurança e eficácia em modelos pré-clínicos, antes de qualquer ensaio em humanos.', 'Chemical optimisation, safety and efficacy in preclinical models, before any study in humans.')),
            ]),
        ], 'mineral'),
        section([
            head(T('Pipeline', 'Pipeline'), T('Programas e áreas de investigação', 'Programmes and research areas'), '', (T('Ver programas', 'See programmes'), '{{page:programs}}')),
            s4u('pipeline', compact=True, showLegend=True),
        ], anchor='pipeline'),
        section([container([
            h(T('Investigação com animais e princípio dos 3R', 'Animal research and the 3Rs'), 2, 's4u-h3'),
            p(T('A Sea4Us é signatária do Acordo de Transparência sobre Investigação Animal em Portugal e aplica o princípio dos 3R (Substituição, Redução e Refinamento).',
                'Sea4Us is a signatory to the Transparency Agreement on Animal Research in Portugal and applies the 3Rs principle (Replacement, Reduction and Refinement).')),
            p(link(T('Ler a declaração completa e os documentos', 'Read the full statement and documents'), '{{page:transparency}}')),
        ], narrow=True)], 'mineral', 'investigacao-animal' if lang == 'pt' else 'animal-research'),
        s4u('cta'),
    ])


def programs_page(lang):
    T = lambda pt, en: pt if lang == 'pt' else en  # noqa
    return ''.join([
        s4u('page-hero', eyebrow=T('Programas', 'Programmes'), title=T('Pipeline de desenvolvimento', 'Development pipeline'),
            text=T('O estádio de cada programa é editado num único sítio e só é marcado como confirmado quando existe uma fonte pública datada. Os restantes ficam assinalados como «por confirmar».',
                   'Each programme’s stage is edited in one place and only marked as confirmed when there is a dated public source. The others are flagged as “to be confirmed”.'),
            tone='dark'),
        section([s4u('pipeline', showLegend=True)]),
        section([container([
            h(T('Como ler este pipeline', 'How to read this pipeline'), 2, 's4u-h3'),
            p(T('O S072 é o único programa com estádio confirmado por fonte pública datada: a Sea4Us anunciou, a 1 de maio de 2026, a aprovação do pedido de autorização de ensaio clínico (CTA). Uma aprovação de CTA autoriza a realização de um ensaio clínico. Não corresponde ao início nem à conclusão da fase 1 e não é uma autorização de comercialização.',
                'S072 is the only programme whose stage is confirmed by a dated public source: on 1 May 2026 Sea4Us announced the approval of its clinical trial application (CTA). A CTA approval authorises a clinical trial to be run. It is neither the start nor the completion of Phase 1 and it is not a marketing authorisation.')),
            p(T('As restantes linhas correspondem às áreas terapêuticas apresentadas no gráfico de pipeline do site anterior, que não indicava datas nem fontes. O seu estádio aguarda confirmação da Sea4Us.',
                'The other rows are the therapeutic areas shown in the previous website’s pipeline chart, which gave no dates or sources. Their stage is awaiting confirmation from Sea4Us.')),
        ], narrow=True)], 'mineral'),
        s4u('cta'),
    ])


def projects_page(lang):
    T = lambda pt, en: pt if lang == 'pt' else en  # noqa
    return ''.join([
        s4u('page-hero', eyebrow=T('Projetos e financiamento público', 'Projects and public funding'), title=T('Projetos', 'Projects'),
            text=T('Projetos de investigação e desenvolvimento cofinanciados por programas nacionais e europeus. Cada ficha mostra os dados como foram publicados, com códigos, montantes e documentos de operação.',
                   'Research and development projects co-funded by national and European programmes. Each sheet shows the data as published, with codes, amounts and operation documents.'),
            imageId=mid('newlab'), tone='light'),
        section([s4u('projects', index=True)]),
        section([container([
            p(T('Os montantes apresentados são os de cada operação, tal como publicados. Não são somados entre projetos, nem com investimento privado.',
                'The amounts shown belong to each operation, as published. They are not added up across projects, nor with private investment.'), 's4u-muted'),
        ], narrow=True)], extra='s4u-section--tight'),
    ])


def partners_page(lang):
    T = lambda pt, en: pt if lang == 'pt' else en  # noqa
    return ''.join([
        s4u('page-hero', eyebrow=T('Sobre nós', 'About us'), title=T('Parceiros e colaborações', 'Partners and collaborations'),
            text=T('Universidades, centros de investigação, empresas e entidades públicas com quem a Sea4Us trabalha. Distinguimos parceiros oficiais de colaborações, tal como no site anterior.',
                   'Universities, research centres, companies and public bodies Sea4Us works with. We keep official partners and collaborations apart, as on the previous website.'),
            tone='light'),
        section([s4u('partners', type='official', heading=T('Parceiros oficiais', 'Official partners'))]),
        section([s4u('partners', type='collaboration', heading=T('Colaborações', 'Collaborations'))], 'mineral'),
        s4u('cta'),
    ])


def partnering(lang):
    T = lambda pt, en: pt if lang == 'pt' else en  # noqa
    return ''.join([
        s4u('page-hero', eyebrow=T('Parcerias e investimento', 'Partnering and investment'), title=T('Vamos fazer avançar a investigação juntos', 'Let’s move research forward together'),
            text=T('A Sea4Us procura parceiros para expandir os seus programas de descoberta e desenvolvimento, e está aberta a investimento no seu programa de analgésicos não opioides.',
                   'Sea4Us is looking for partners to expand its discovery and development programmes, and is open to investment in its non-opioid analgesic programme.'),
            imageId=mid('duo'), tone='dark'),
        section([
            pillars([
                ('01', T('Indústria farmacêutica e biotecnológica', 'Pharma and biotech industry'),
                 T('Colaboração e codesenvolvimento de projetos novos ou existentes, para expandir os pipelines de descoberta e desenvolvimento.', 'Collaboration and co-development of new or existing projects, to expand the discovery and development pipelines.')),
                ('02', T('Investidores', 'Investors'),
                 T('Investimento no desenvolvimento do programa principal de analgésicos não opioides. Os marcos e documentos verificáveis estão nas fichas de programa e nas notícias.', 'Investment in the development of the lead non-opioid analgesic programme. Verifiable milestones and documents are in the programme sheets and news.')),
                ('03', T('Investigadores e instituições', 'Researchers and institutions'),
                 T('Projetos colaborativos, candidaturas conjuntas a financiamento e partilha de competências em biologia marinha e eletrofisiologia.', 'Collaborative projects, joint funding applications and shared expertise in marine biology and electrophysiology.')),
            ]),
        ]),
        group([], '', anchor='industria' if lang == 'pt' else 'industry'),
        section([
            intro(T('O contexto', 'The context'), [
                p(T('Segundo a Sea4Us, que cita estimativas publicadas, cerca de 1,6 mil milhões de pessoas em todo o mundo vivem com dor crónica. Muitos tratamentos disponíveis são ineficazes para certos tipos de dor ou, no caso dos opioides, têm efeitos secundários importantes, incluindo risco de adição e dependência.',
                    'According to Sea4Us, citing published estimates, around 1.6 billion people worldwide live with chronic pain. Many available treatments are ineffective for some types of pain or, in the case of opioids, have significant side effects, including a risk of addiction and dependence.')),
                p(T('O S072 é um candidato em investigação. O seu perfil em humanos ainda não foi estabelecido.', 'S072 is an investigational candidate. Its profile in humans has not yet been established.'), 's4u-muted'),
                p(link(T('Ver a ficha do S072', 'See the S072 sheet'), '{{item:program:s072}}')),
            ], T('Para investidores', 'For investors')),
        ], 'mineral', 'investidores' if lang == 'pt' else 'investors'),
        section([
            intro(T('Fale connosco', 'Talk to us'), [
                p(T('Conte-nos o que procura: colaboração científica, licenciamento, investimento ou imprensa. Respondemos a partir da equipa de gestão.',
                    'Tell us what you are looking for: scientific collaboration, licensing, investment or press. Our management team will reply.')),
                s4u('contact-details', partnering=True),
            ], T('Contacto', 'Contact')),
            s4u('contact-form', interest='collaboration'),
        ], anchor='investigacao' if lang == 'pt' else 'research'),
    ])


def news_page(lang):
    T = lambda pt, en: pt if lang == 'pt' else en  # noqa
    return ''.join([
        s4u('page-hero', eyebrow=T('Notícias', 'News'), title=T('Notícias', 'News'),
            text=T('Notícias da empresa, presença na imprensa, projetos financiados e eventos, desde 2013. Cada artigo mantém a data original de publicação.',
                   'Company news, press coverage, funded projects and events since 2013. Every article keeps its original publication date.'),
            tone='light'),
        section([s4u('news', archive=True, count=12)]),
    ])


def transparency(lang, text):
    T = lambda pt, en: pt if lang == 'pt' else en  # noqa
    return ''.join([
        s4u('page-hero', eyebrow=T('Transparência na investigação', 'Research transparency'), title=T('Investigação com animais na Sea4Us', 'Animal research at Sea4Us'),
            text=T('A Sea4Us é signatária do Acordo de Transparência sobre Investigação Animal em Portugal, uma iniciativa apoiada pela comunidade científica em colaboração com a European Animal Research Association (EARA).',
                   'Sea4Us is a signatory to the Transparency Agreement on Animal Research in Portugal, an initiative supported by the scientific community in collaboration with the European Animal Research Association (EARA).'),
            tone='dark'),
        section([container([html(text)], narrow=True)]),
        section([
            head(T('Documentos', 'Documents'), T('Resumos não técnicos e ligações', 'Non-technical summaries and links')),
            s4u('documents', docType='transparency'),
        ], 'mineral'),
    ])


def supporters(lang, data):
    T = lambda pt, en: pt if lang == 'pt' else en  # noqa
    names = [n for g in data['groups'] for n in g['names']]
    return ''.join([
        s4u('page-hero', eyebrow=T('Crowdfunding', 'Crowdfunding'), title=T('Apoiantes de crowdfunding', 'Crowdfunding supporters'),
            text=T(f'Os {len(names)} apoiantes da campanha de crowdfunding que permitiu à Sea4Us mergulhar e recolher amostras marinhas, tal como publicados no site da empresa.',
                   f'The {len(names)} supporters of the crowdfunding campaign that allowed Sea4Us to dive and collect marine samples, as published on the company website.'),
            imageId=mid('nudi'), tone='light'),
        section([container([html(data['intro'][lang])], narrow=True)]),
        section([container([h(T('Obrigado a todos', 'Thank you all'), 2, 's4u-h3'), ul(names, 's4u-supporters')])], 'mineral'),
    ])


def useful_links(lang, links):
    T = lambda pt, en: pt if lang == 'pt' else en  # noqa
    items = [f'<a href="{l["url"]}">{l["label"][lang]}</a>' + (f' · {l["description"][lang]}' if l['description'][lang] else '') for l in links]
    return ''.join([
        s4u('page-hero', eyebrow=T('Recursos', 'Resources'), title=T('Links úteis', 'Useful links'),
            text=T('Entidades e programas de financiamento relacionados com a atividade da Sea4Us. Algumas ligações históricas podem já não estar ativas.',
                   'Bodies and funding programmes related to Sea4Us’s activity. Some historical links may no longer be active.'),
            tone='light'),
        section([container([ul(items, 's4u-links')], narrow=True)]),
    ])


def contact(lang):
    T = lambda pt, en: pt if lang == 'pt' else en  # noqa
    return ''.join([
        s4u('page-hero', eyebrow=T('Contactos', 'Contact'), title=T('Fale com a Sea4Us', 'Get in touch with Sea4Us'),
            text=T('Escreva-nos sobre ciência, parcerias, investimento ou imprensa. Também nos encontra em Sagres e em Lisboa.',
                   'Write to us about science, partnerships, investment or press. You can also find us in Sagres and in Lisbon.'),
            imageId=mid('sagres'), tone='light'),
        section([split([s4u('contact-form')], [s4u('contact-details')])]),
        section([head(T('Localizações', 'Locations'), T('Dois laboratórios', 'Two labs')), s4u('locations')], 'mineral'),
    ])


def privacy(lang):
    T = lambda pt, en: pt if lang == 'pt' else en  # noqa
    c = [
        callout([p(T('<strong>Rascunho para validação jurídica.</strong> Este texto descreve o funcionamento técnico deste site e não substitui a política de privacidade que a Sea4Us venha a aprovar. Os campos assinalados [a confirmar] dependem de informação da empresa.',
                     '<strong>Draft for legal review.</strong> This text describes how this website works technically and does not replace the privacy policy Sea4Us will approve. Fields marked [to be confirmed] depend on company information.'))]),
        h(T('Responsável pelo tratamento', 'Data controller'), 3),
        p(T('Sea4Us – Biotecnologia e Recursos Marinhos, S.A., Porto da Baleeira, Armazém 8, 8650-368 Sagres. NIPC: [a confirmar]. Contacto para questões de privacidade: [a confirmar].',
            'Sea4Us – Biotecnologia e Recursos Marinhos, S.A., Porto da Baleeira, Armazém 8, 8650-368 Sagres, Portugal. Company number: [to be confirmed]. Privacy contact: [to be confirmed].')),
        h(T('Formulário de contacto', 'Contact form'), 3),
        p(T('O formulário pede nome, email profissional, organização (opcional), tipo de interesse e mensagem, apenas para responder ao seu pedido. Na versão de demonstração nada é enviado nem guardado. Prazo de conservação e base legal em produção: [a confirmar].',
            'The form asks for your name, work email, organisation (optional), type of interest and message, only to reply to your request. In the demonstration version nothing is sent or stored. Retention period and legal basis in production: [to be confirmed].')),
        h(T('Cookies', 'Cookies'), 3),
        p(T('Este site não usa cookies de análise, publicidade ou redes sociais, por isso não apresenta um banner de consentimento. Os conteúdos externos (por exemplo, vídeos) abrem por ligação no site de origem. Se no futuro forem adicionadas ferramentas de medição, esta secção e o pedido de consentimento terão de ser atualizados antes da sua ativação.',
            'This website does not use analytics, advertising or social media cookies, so it does not show a consent banner. External content (for example, videos) opens by link on its original website. If measurement tools are added in the future, this section and a consent request must be updated before they are switched on.')),
        h(T('Os seus direitos', 'Your rights'), 3),
        p(T('Pode pedir acesso, retificação, apagamento, limitação ou portabilidade dos seus dados e opor-se ao seu tratamento, através do contacto acima. Pode também apresentar reclamação à Comissão Nacional de Proteção de Dados (CNPD).',
            'You may request access to, rectification, erasure, restriction or portability of your data and object to its processing, using the contact above. You may also lodge a complaint with the Portuguese data protection authority (CNPD).')),
    ]
    return ''.join([s4u('page-hero', eyebrow=T('Legal', 'Legal'), title=T('Privacidade e cookies', 'Privacy and cookies'), tone='light'),
                    section([container(c, narrow=True)])])


def search_page(lang):
    T = lambda pt, en: pt if lang == 'pt' else en  # noqa
    return ''.join([s4u('page-hero', eyebrow=T('Pesquisa', 'Search'), title=T('Pesquisar no site', 'Search the site'), tone='light'), section([s4u('search')])])


# ---------------------------------------------------------------- Programmes
def programs():
    src = 'https://sea4us.pt/en/clinical-trial-application-approval/'
    tech = 'https://sea4us.pt/en/technology/#pipeline'
    s072_pt = ''.join([
        h('O que é o S072', 2),
        p('Segundo a Sea4Us, o S072 é uma pequena molécula, uma nova entidade química inspirada numa molécula marinha e otimizada para uso humano. Atua sobre neurónios periféricos hiperativos através da modulação seletiva de canais iónicos. Destina-se a administração oral.'),
        h('Estado regulamentar', 2),
        p('A 1 de maio de 2026, a Sea4Us anunciou a aprovação regulamentar e ética do seu pedido de autorização de ensaio clínico (CTA) e a ativação do centro clínico a 30 de abril de 2026. No mesmo comunicado, a empresa indicou que as atividades <em>first-in-human</em> iriam começar em breve.'),
        callout([p('A aprovação de um CTA autoriza a realização de um ensaio clínico. Não é o início nem a conclusão da fase 1 e não é uma autorização de comercialização. O S072 é um composto em investigação e não está aprovado para uso clínico.')]),
        h('Estratégia clínica anunciada', 2),
        p('A empresa indica que a estratégia clínica começa por uma indicação pós-cirúrgica, a alargar mais tarde a indicações de dor crónica.'),
        h('Dados pré-clínicos comunicados pela empresa', 2),
        p('A Sea4Us refere dados de prova de conceito em seis modelos animais: dor pós-operatória, dor osteoartrítica, nevralgia do trigémio, neuropatia periférica induzida por quimioterapia, dor visceral/doença inflamatória intestinal e dor monoartrítica. Resultados em modelos animais não demonstram eficácia nem segurança em humanos.'),
        h('Propriedade intelectual', 2),
        p('A empresa indica ter submetido três pedidos de patente: dois estão concedidos nos Estados Unidos e o terceiro entrou na fase PCT em 2025, tendo sido publicado em janeiro de 2026. Um pedido PCT publicado não é uma patente concedida.'),
        h('Fontes', 2),
        ul([f'<a href="{{{{item:news:7782}}}}">Comunicado de 1 de maio de 2026: pedido de autorização de ensaio clínico aprovado</a>',
            '<a href="{{item:project:sea4market}}">Projeto Sea4Market (EIC Accelerator, n.º 101145231)</a>']),
    ])
    s072_en = ''.join([
        h('What S072 is', 2),
        p('According to Sea4Us, S072 is a small molecule, a new chemical entity inspired by a marine molecule and optimised for human use. It acts on hyperactive peripheral neurons through selective modulation of ion channels. It is intended for oral dosing.'),
        h('Regulatory status', 2),
        p('On 1 May 2026, Sea4Us announced the regulatory and ethical approval of its clinical trial application (CTA) and the activation of the clinical site on 30 April 2026. In the same announcement the company said first-in-human activities would start soon.'),
        callout([p('A CTA approval authorises a clinical trial to be run. It is neither the start nor the completion of Phase 1 and it is not a marketing authorisation. S072 is an investigational compound and is not approved for clinical use.')]),
        h('Announced clinical strategy', 2),
        p('The company states that its clinical strategy starts with a post-surgical indication, to be extended later to chronic pain indications.'),
        h('Preclinical data reported by the company', 2),
        p('Sea4Us reports proof-of-concept data in six animal models: post-operative pain, osteoarthritic pain, trigeminal neuralgia, chemotherapy-induced peripheral neuropathy, visceral pain/IBD and monoarthritic pain. Results in animal models do not demonstrate efficacy or safety in humans.'),
        h('Intellectual property', 2),
        p('The company states it has filed three patent applications: two are granted in the United States and the third entered the PCT phase in 2025 and was published in January 2026. A published PCT application is not a granted patent.'),
        h('Sources', 2),
        ul(['<a href="{{item:news:7782}}">Announcement of 1 May 2026: clinical trial application approved</a>',
            '<a href="{{item:project:sea4market}}">Sea4Market project (EIC Accelerator, no. 101145231)</a>']),
    ])
    rows = [{
        'id': 'program:s072', 'order': 0,
        'shared': {'code': 'S072', 'stage': 'cta', 'stage_source_date': '2026-05-01', 'stage_source_url': src,
                   'milestone_date': '2026-05-01', 'milestone_source': src, 'featured': True, 'review_status': 'pending',
                   'source_url': src, 'source_date': '2026-05-01', 'thumbnail': IMG['lab'],
                   'related_news': ['news:7782', 'news:7547', 'news:7560', 'news:7447', 'news:7110']},
        'pt': {'title': 'S072', 'slug': 's072',
               'excerpt': 'Candidato a analgésico não opioide de pequena molécula, inspirado numa molécula marinha. Pedido de autorização de ensaio clínico aprovado, anunciado a 1 de maio de 2026.',
               'content': s072_pt,
               'fields': {'nature': 'Candidato a medicamento em investigação', 'area': 'Dor', 'indication': 'Dor pós-cirúrgica (primeira indicação anunciada); dor crónica',
                          'latest_milestone': 'Pedido de autorização de ensaio clínico (CTA) aprovado; centro clínico ativado a 30/04/2026',
                          'qualification': 'Composto em investigação, não aprovado para uso clínico. A aprovação do CTA não é início nem conclusão da fase 1, nem autorização de comercialização.',
                          'legacy_url': src.replace('/en/', '/pt/')}},
        'en': {'title': 'S072', 'slug': 's072',
               'excerpt': 'Small-molecule non-opioid analgesic candidate inspired by a marine molecule. Clinical trial application approved, announced on 1 May 2026.',
               'content': s072_en,
               'fields': {'nature': 'Investigational drug candidate', 'area': 'Pain', 'indication': 'Post-surgical pain (first announced indication); chronic pain',
                          'latest_milestone': 'Clinical trial application (CTA) approved; clinical site activated on 30/04/2026',
                          'qualification': 'Investigational compound, not approved for clinical use. CTA approval is neither the start nor the completion of Phase 1, nor a marketing authorisation.',
                          'legacy_url': src}},
    }]
    areas = [
        ('neuropathic-pain', 'Dor neuropática', 'Neuropathic pain', 'Dor', 'Pain'),
        ('musculoskeletal-pain', 'Dor musculoesquelética', 'Musculoskeletal pain', 'Dor', 'Pain'),
        ('chemotherapy-induced-pain', 'Dor induzida por quimioterapia', 'Chemotherapy-induced pain', 'Dor', 'Pain'),
        ('urology', 'Urologia', 'Urology', 'Urologia', 'Urology'),
        ('cns-epilepsy', 'Sistema nervoso central / epilepsia', 'CNS / epilepsy', 'Neurologia', 'Neurology'),
        ('infectious-diseases', 'Doenças infecciosas', 'Infectious diseases', 'Doenças infecciosas', 'Infectious diseases'),
    ]
    for i, (slug, tpt, ten, apt, aen) in enumerate(areas, 1):
        rows.append({
            'id': f'program:{slug}', 'order': i,
            'shared': {'stage': 'unconfirmed', 'review_status': 'pending', 'source_url': tech, 'source_date': '2026-10-10'},
            'pt': {'title': tpt, 'slug': slug,
                   'excerpt': 'Área terapêutica apresentada no pipeline do site anterior. Estádio por confirmar com a Sea4Us.',
                   'content': p('Esta área terapêutica constava do gráfico de pipeline do site anterior da Sea4Us, sem data nem fonte associadas. O estádio de desenvolvimento e os compostos envolvidos aguardam confirmação da empresa antes de serem publicados.'),
                   'fields': {'nature': 'Área de investigação (pipeline do site anterior)', 'area': apt, 'legacy_url': tech.replace('/en/', '/pt/')}},
            'en': {'title': ten, 'slug': slug,
                   'excerpt': 'Therapeutic area shown in the previous website’s pipeline. Stage to be confirmed by Sea4Us.',
                   'content': p('This therapeutic area appeared in the pipeline chart of the previous Sea4Us website, with no date or source. Its development stage and the compounds involved are awaiting confirmation from the company before publication.'),
                   'fields': {'nature': 'Research area (previous website pipeline)', 'area': aen, 'legacy_url': tech}},
        })
    return rows


# ---------------------------------------------------------------- Site
def site():
    menus = {}
    for lang in ('pt', 'en'):
        T = lambda pt, en: pt if lang == 'pt' else en  # noqa
        menus['primary_' + lang] = [
            {'label': T('Sobre', 'About'), 'page': 'about', 'children': [
                {'label': T('Sobre a Sea4Us', 'About Sea4Us'), 'page': 'about'},
                {'label': T('Equipa', 'Team'), 'page': 'team'},
                {'label': T('Parceiros', 'Partners'), 'page': 'partners'},
                {'label': T('Apoiantes de crowdfunding', 'Crowdfunding supporters'), 'page': 'supporters'}]},
            {'label': T('Ciência', 'Science'), 'page': 'science', 'children': [
                {'label': T('Ciência e tecnologia', 'Science and technology'), 'page': 'science'},
                {'label': T('Programas', 'Programmes'), 'page': 'programs'},
                {'label': 'S072', 'url': '{{item:program:s072}}'},
                {'label': T('Transparência na investigação', 'Research transparency'), 'page': 'transparency'}]},
            {'label': T('Projetos', 'Projects'), 'page': 'projects'},
            {'label': T('Notícias', 'News'), 'page': 'news'},
            {'label': T('Contactos', 'Contact'), 'page': 'contact'},
        ]
        menus['footer_' + lang] = [
            {'label': T('Sobre', 'About'), 'page': 'about'}, {'label': T('Equipa', 'Team'), 'page': 'team'},
            {'label': T('Ciência e tecnologia', 'Science and technology'), 'page': 'science'}, {'label': T('Programas', 'Programmes'), 'page': 'programs'},
            {'label': T('Projetos', 'Projects'), 'page': 'projects'}, {'label': T('Parceiros', 'Partners'), 'page': 'partners'},
            {'label': T('Parcerias e investimento', 'Partnering and investment'), 'page': 'partnering'}, {'label': T('Notícias', 'News'), 'page': 'news'},
            {'label': T('Transparência na investigação', 'Research transparency'), 'page': 'transparency'},
            {'label': T('Apoiantes de crowdfunding', 'Crowdfunding supporters'), 'page': 'supporters'}, {'label': T('Links úteis', 'Useful links'), 'page': 'links'},
        ]
        menus['legal_' + lang] = [{'label': T('Privacidade e cookies', 'Privacy and cookies'), 'page': 'privacy'}, {'label': T('Pesquisa', 'Search'), 'page': 'search'}]
    settings = {
        'email': 'info@sea4us.pt', 'phone': '+351 21 880 3078', 'phone_e164': '+351218803078',
        'hours_pt': 'Segunda a sexta-feira, 09:00–18:00', 'hours_en': 'Monday to Friday, 09:00–18:00', 'contacts_review': '1',
        'loc1_name_pt': 'Sede e Laboratório Marinho', 'loc1_name_en': 'Headquarters and Marine Lab',
        'loc1_role_pt': 'Recolha de amostras marinhas e descoberta', 'loc1_role_en': 'Marine sample collection and discovery',
        'loc1_address': "Porto da Baleeira, Armazém 8\n8650-368 Sagres\nPortugal", 'loc1_map': 'https://www.openstreetmap.org/search?query=Porto%20da%20Baleeira%20Sagres',
        'loc2_name_pt': 'Laboratório de Fisiologia', 'loc2_name_en': 'Physiology Lab',
        'loc2_role_pt': 'Desenvolvimento pré-clínico', 'loc2_role_en': 'Preclinical development',
        'loc2_address': "Campo Mártires da Pátria, 130\n1169-056 Lisboa\nPortugal", 'loc2_map': 'https://www.openstreetmap.org/search?query=Campo%20M%C3%A1rtires%20da%20P%C3%A1tria%20130%20Lisboa',
        'partnering_email_pt': 'partnering@sea4us.pt', 'partnering_email_com': 'partnering@sea4us.com', 'partnering_confirmed': '',
        'form_recipient': '', 'form_recipient_ok': '',
        'linkedin': 'https://www.linkedin.com/company/sea4us', 'facebook': 'https://www.facebook.com/Sea4Us.pt/',
        'instagram': 'https://www.instagram.com/sea4us_biotech/', 'youtube': 'https://www.youtube.com/channel/UCLexW07OH_LsK5Y6uMM6zeQ',
        'funding_strip': ','.join('{{media-id:%s}}' % k for k in ('2020/08/fundos-publicos.png', '2023/05/prr.png', '2024/06/EU.png')),
        'credits_pt': 'Fotografias: João Rodrigues | CHIMERAVISUALS (imagens assinaladas) e © 2010–2026 Pedro A. Lima, conforme o site anterior. Créditos por imagem a confirmar.',
        'credits_en': 'Photography: João Rodrigues | CHIMERAVISUALS (credited images) and © 2010–2026 Pedro A. Lima, as on the previous website. Per-image credits to be confirmed.',
        'allow_indexing': '', 'redirects_enabled': '', 'root_behavior': 'redirect_302', 'root_lang': 'en',
    }
    return {'settings': settings, 'menus': menus}


def main():
    w = lambda f: json.load(open(os.path.join(OUT, '_work', f), encoding='utf-8'))  # noqa
    sup, links, tr = w('supporters.json'), w('useful-links.json'), w('transparency-text.json')
    old = 'https://sea4us.pt/{}/'
    P = []
    P.append(page('home', None, 0, ('Início', 'pt', home('pt')), ('Home', 'en', home('en')),
                  seo={'pt': ('Sea4Us · Biotecnologia de origem marinha', 'A Sea4Us investiga compostos inspirados em organismos marinhos para necessidades clínicas por responder. Programa principal: S072, candidato a analgésico não opioide.'),
                       'en': ('Sea4Us · Marine-derived biotechnology', 'Sea4Us researches compounds inspired by marine organisms for unmet clinical needs. Lead programme: S072, a non-opioid analgesic candidate.')},
                  legacy={'pt': old.format('pt'), 'en': old.format('en')}))
    defs = [
        ('about', 'home', 1, ('Sobre nós', 'sobre', about('pt')), ('About us', 'about', about('en')),
         ('Sobre nós · Sea4Us', 'História, missão, visão e política de inovação da Sea4Us, spin-off da Universidade NOVA de Lisboa criada em Sagres em 2013.'),
         ('About us · Sea4Us', 'History, mission, vision and innovation policy of Sea4Us, a NOVA University Lisbon spin-off founded in Sagres in 2013.'), 'sobre-nos/'),
        ('team', 'about', 1, ('Equipa', 'equipa', team('pt')), ('Team', 'team', team('en')),
         ('Equipa · Sea4Us', 'Conselho de administração, gestão, equipa científica e cofundadores da Sea4Us.'),
         ('Team · Sea4Us', 'Board, management, scientific team and co-founders of Sea4Us.'), 'sobre-nos/team/'),
        ('science', 'home', 2, ('Ciência e tecnologia', 'ciencia-e-tecnologia', science('pt')), ('Science and technology', 'science-technology', science('en')),
         ('Ciência e tecnologia · Sea4Us', 'Biologia marinha e eletrofisiologia: como a Sea4Us descobre e desenvolve compostos de origem marinha.'),
         ('Science and technology · Sea4Us', 'Marine biology and electrophysiology: how Sea4Us discovers and develops marine-derived compounds.'), 'technology/'),
        ('programs', 'home', 3, ('Programas', 'programas', programs_page('pt')), ('Programmes', 'programmes', programs_page('en')),
         ('Pipeline e programas · Sea4Us', 'Estádio de cada programa da Sea4Us, com fonte e data. O S072 tem pedido de autorização de ensaio clínico aprovado.'),
         ('Pipeline and programmes · Sea4Us', 'Stage of each Sea4Us programme, with source and date. S072 has an approved clinical trial application.'), None),
        ('projects', 'home', 4, ('Projetos', 'projetos', projects_page('pt')), ('Projects', 'projects', projects_page('en')),
         ('Projetos e financiamento público · Sea4Us', 'Projetos de I&D da Sea4Us cofinanciados por programas nacionais e europeus, com códigos, montantes e documentos.'),
         ('Projects and public funding · Sea4Us', 'Sea4Us R&D projects co-funded by national and European programmes, with codes, amounts and documents.'), 'sobre-nos/projects/'),
        ('partners', 'home', 5, ('Parceiros', 'parceiros', partners_page('pt')), ('Partners', 'partners', partners_page('en')),
         ('Parceiros e colaborações · Sea4Us', 'Parceiros oficiais e colaborações científicas da Sea4Us.'),
         ('Partners and collaborations · Sea4Us', 'Official partners and scientific collaborations of Sea4Us.'), 'sobre-nos/partners/'),
        ('partnering', 'home', 6, ('Parcerias e investimento', 'parcerias-e-investimento', partnering('pt')), ('Partnering and investment', 'partnering-investment', partnering('en')),
         ('Parcerias e investimento · Sea4Us', 'Colaboração com a indústria, investigação e investimento no programa de analgésicos não opioides da Sea4Us.'),
         ('Partnering and investment · Sea4Us', 'Industry collaboration, research and investment in the Sea4Us non-opioid analgesic programme.'), 'partnering/'),
        ('news', 'home', 7, ('Notícias', 'noticias', news_page('pt')), ('News', 'news', news_page('en')),
         ('Notícias · Sea4Us', 'Notícias, imprensa, projetos e eventos da Sea4Us desde 2013.'),
         ('News · Sea4Us', 'Sea4Us news, press, projects and events since 2013.'), 'news-2/'),
        ('transparency', 'home', 8, ('Transparência na investigação', 'transparencia-na-investigacao', transparency('pt', tr['pt'])), ('Research transparency', 'research-transparency', transparency('en', tr['en'])),
         ('Transparência na investigação animal · Sea4Us', 'Declaração da Sea4Us sobre investigação com animais, princípio dos 3R e resumo não técnico.'),
         ('Animal research transparency · Sea4Us', 'Sea4Us statement on animal research, the 3Rs principle and non-technical summary.'), None),
        ('supporters', 'home', 9, ('Apoiantes de crowdfunding', 'apoiantes-crowdfunding', supporters('pt', sup)), ('Crowdfunding supporters', 'crowdfunding-supporters', supporters('en', sup)),
         ('Apoiantes de crowdfunding · Sea4Us', 'Lista completa dos apoiantes da campanha de crowdfunding da Sea4Us.'),
         ('Crowdfunding supporters · Sea4Us', 'Full list of supporters of the Sea4Us crowdfunding campaign.'), 'crowdfunding-supporters/'),
        ('links', 'home', 10, ('Links úteis', 'links-uteis', useful_links('pt', links)), ('Useful links', 'useful-links', useful_links('en', links)),
         ('Links úteis · Sea4Us', 'Entidades e programas de financiamento relacionados com a Sea4Us.'),
         ('Useful links · Sea4Us', 'Bodies and funding programmes related to Sea4Us.'), 'useful-links/'),
        ('contact', 'home', 11, ('Contactos', 'contactos', contact('pt')), ('Contact', 'contact', contact('en')),
         ('Contactos · Sea4Us', 'Contactos, horário e localizações da Sea4Us em Sagres e Lisboa.'),
         ('Contact · Sea4Us', 'Sea4Us contact details, opening hours and locations in Sagres and Lisbon.'), None),
        ('privacy', 'home', 12, ('Privacidade e cookies', 'privacidade-e-cookies', privacy('pt')), ('Privacy and cookies', 'privacy-cookies', privacy('en')),
         ('Privacidade e cookies · Sea4Us', 'Como este site trata dados pessoais e cookies.'),
         ('Privacy and cookies · Sea4Us', 'How this website handles personal data and cookies.'), None),
        ('search', 'home', 13, ('Pesquisa', 'pesquisa', search_page('pt')), ('Search', 'search', search_page('en')),
         ('Pesquisa · Sea4Us', 'Pesquisar notícias, projetos, equipa e páginas.'), ('Search · Sea4Us', 'Search news, projects, team and pages.'), None),
    ]
    for key, parent, order, pt, en, seo_pt, seo_en, legacy in defs:
        P.append(page(key, parent, order, pt, en, seo={'pt': seo_pt, 'en': seo_en},
                      legacy={'pt': old.format('pt') + legacy, 'en': old.format('en') + legacy} if legacy else None))
    json.dump(P, open(os.path.join(OUT, 'pages.json'), 'w', encoding='utf-8'), ensure_ascii=False, indent=1)
    json.dump(programs(), open(os.path.join(OUT, 'programs.json'), 'w', encoding='utf-8'), ensure_ascii=False, indent=1)
    json.dump(site(), open(os.path.join(OUT, 'site.json'), 'w', encoding='utf-8'), ensure_ascii=False, indent=1)
    print('pages', len(P), 'programs', len(programs()))


main()
