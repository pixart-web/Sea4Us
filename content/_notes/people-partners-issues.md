# People, partners, supporters, links, transparency: issues

Sources: pages 5391-team, 5536-partners, 2473-crowdfunding-supporters, 3537-useful-links, 5472-technology (#animalresearch), 5316-sobre-nos, 4769-home (PT and EN), plus the matching full HTML in source/html/. Build script: scratchpad build.py (not in the project).

## Counts
- people.json: 23 people. The team page lists 19 people across 4 sections (Conselho de Administração 3, Equipa de Gestão 6, Equipa Científica 8, Equipa Administrativa 2). The "Cofundadores" gallery adds 4 people who appear nowhere else on the page (Filipe Vilas-Boas, Marisa Sousa, Hugo Pacheco, Clara Patrício). Order follows the page: the sectioned list first, then the co-founders gallery. The top carousel (19 photos) has a different order and was not used for ordering.
- partners.json: 34 partners. 10 are "Parceiros oficiais / Official partners" (type official) and 24 are "Colaborações / Collaborations" (type collaboration). The two groups are kept apart. No funder or network section exists on the partners page.
- _work/supporters.json: 189 names, in one block with no published group labels. The 5 `<p>` columns are only layout and were merged in order. The PT and EN lists are identical: same 189 entries in the same order, cross-checked in the cleaned JSON and in the full HTML (the HTML parse also picks up the footer phone/e-mail, which was excluded). No duplicates. Some entries hold several people or a company, e.g. "Pedro e Inês Lima, João Lima", "Luís, Diamantino e Emília Lopes", "Abílio Santos / Maria Mília Vieira", "Antiguidades Roque, Mário Roque", "Industrial SYS", "Tutiobra Sociedade Técnica de Construções Unipessoal, Lda". Nicknames are kept as published: "Fernando “O Grande”", "O Maior", "Mariazinha". Each line counts as 1 entry.
- _work/useful-links.json: 24 links. The page has no groups, so all 24 sit under "Links úteis / Useful links". There are no descriptions.
- _work/documents-other.json: 3 entries (RNT PDF, EARA member link, EARA "Be Open" link).
- Every media key in these files exists under source/wp-content/uploads/. No photo or logo is missing.

## People: photos and ambiguities
- photo_check=true only for **Miriã Castanhar**. On the team page her photo is `2026/01/WhatsApp-Image-2026-01-06-at-11.30.24.jpeg`, and in the carousel its alt is "WhatsApp Image 2026-01-06 at 11.30.24" instead of her name. The list item does link it to her LinkedIn. A dedicated file `2026/01/Foto_Miria-Castanhar-e1767625585280.jpg` also exists (attachment page pt__sobre-nos__team__foto_miria-castanhar.html) but the page does not use it. Visually it seems to be the same person. Please confirm which file to use.
- Daniel Vargem: the photo file is named `Historia-para-Instagram-a-Castanho-Claro-e-Branco-Tradicional-com-Citacao-de-Vida-1.png` (an Instagram template name). The carousel alt and attachment title say "Daniel Vargem" and the image is a portrait, so the association is fine. He has no profile link.
- `2026/01/Foto_Filipa-Magalhaes-e1767625603387.jpeg` is attached to the team page, but **Filipa Magalhães is not shown on the page**. She may be a new or former member; not included.
- Carousel alt texts differ from the list: "José Eduardo Moura, PM" vs heading "Eduardo Moura"; "Fernando Barcelos" (alt, wrong) vs heading "Fernanda Barcelos"; "Raquel Barros, PhD" / "Ana Chegão, PhD" / "Patrícia Sequeira, PhD" (alt only, no degree in heading); "Maria João Cabeleira, SGIDI Manager"; "Diego Hartmann, Head of Biotechnology". I used the headings as titles. Degrees go in `fields.credentials`: for the 3 board members and the 4 co-founders they come from headings/captions, and for Ana Chegão and Patrícia Sequeira from carousel alts.
- Extra fields added: `fields.credentials` (post-nominals) and `fields.expertise`. The expertise lines published under scientific team members (e.g. "Biotechnology, Marine Biology") are not roles, so they were kept apart.
- Ana Bernardo, Ana Chegão and Patrícia Sequeira have no published role, so role is "". The 4 co-founders also have no role; their captions carry only degrees.
- Only the 3 board members have bios (the "Saber Mais / Read More" accordion). All other `content` fields are empty.
- cofounder=true for Pedro A. Lima, Nuno Leitão and André Bastos (in the Co-founders gallery, and their bios say founder) and for the 4 other gallery people. Everyone else is false (not listed as a co-founder).
- Former roles in gallery captions: "Pedro A. Lima, CEO", "Nuno Leitão, CFO", "André Bastos, R&D Manager". These match the current roles.
- Group photos: `2026/05/Sea4UsTeam_Dez2023_3618-scaled-e1777918043177.jpg` and `2026/05/Sea4UsTeam_Dez2023_3625-scaled-e1777918163936.jpg` are attached to the team page (attachment pages exist) but do not appear in its markup. They may have been Elementor CSS backgrounds; the post CSS is not in the snapshot. Good candidates for the new team page. The "EQUIPA" tile image `2020/08/1.jpg` is a nudibranch photo, not a team photo. Home page photos: `2026/05/Sea4Us_Foto_Nova_3-1-1-scaled-e1777917235170.jpg` (Sagres HQ) and `2026/05/Sea4UsLab_2023_3526-scaled.jpg` (lab interior). Attachments on home that are not shown: Sagres_202507-3, Sea4Us_Foto_Nova_3-2, Sea4UsLab_Apr2024_4415.

## Photo credits found (and where)
- **"FOTOGRAFIA / PHOTOGRAPHY — João Rodrigues | CHIMERAVISUALS"**:
  - Home PT/EN (https://sea4us.pt/pt/ , https://sea4us.pt/en/): under the hero tagline. It credits the hero background image, which is set in Elementor CSS (post-4769.css, not in the snapshot), so the exact file is unknown.
  - Technology PT/EN (https://sea4us.pt/pt/technology/): caption of the "BIOLOGIA MARINHA / MARINE BIOLOGY" image `2020/08/Untitled-design-1-scaled.jpg` and of the "ELECTROFISIOLOGIA / ELECTROPHYSIOLOGY" image `2020/08/Untitled-design-scaled.jpg`.
- **"All images © 2010-2026 Pedro A. Lima."**: in the site-wide footer, English only even on PT pages (team, about, home, technology, …). It conflicts with the CHIMERAVISUALS credits above, so a blanket credit needs clarifying. The footer also says "Website by Beatriz Szwarc".
- No per-person photo credits are published on the team, about or home pages, so `photo_credit` is "" for all people.

## Partners
- Every logo has a caption name (figcaption) in both languages, so nothing had to be identified from file names. Some logo file names are unclear: `2020/08/logo.png` = P-BIO, `2020/08/LogotipoSI.png` = ICBAS, `2023/05/porto.png` = FMUP, `2023/05/fcy-...png` = FCT-UNL, `2020/08/f203d630...jpg` = iMM, `2020/08/utromo.png` = UiT.
- No link published for P-BIO, Câmara Municipal da Vila do Bispo or Algarve Biomedical Centre Collaborative Laboratory, so url is "".
- Instituto Galénico links to a LinkedIn company page, not a website.
- Names kept as published, in lower case: "necton", "ignae". "Migal" is kept too (the official form may be "MIGAL"). Please check the brand spellings.
- Partner titles are proper names and are identical in PT and EN (EN page uses the same captions).
- Home page logo strip (Vila do Bispo, UALG, MAR, BLUE, NOVA, P-BIO, sagrema) links to the partners page. "sagrema" (`2020/08/sagrema.png`) appears only on home, while the partners page uses `2020/08/Sagremarisco.jpg`.
- Site-wide footer (not the partners page): "Co-Financiado por:" logos (Cofinanciamento, Interreg, fundos públicos, Seal of Excellence – Euroquity), plus EARA member and Be Open badges. These are left for whoever handles funders/footer.

## PT/EN discrepancies
- Team intro: PT lists "neurofisiologia, biotecnologia, bioquímica, biologia e química"; EN omits neurophysiology.
- Pedro's role: PT "Presidente do Conselho de Administração, Director Geral; Diretor Científico" vs EN "President of the Board, CEO, CSO".
- Nuno's role: PT "Diretor Financeiro e de RH" vs EN "CFO" only.
- PT bios link the first name to LinkedIn; EN adds "(LinkedIn)". Both kept as published.
- Home tagline: PT "O mar em resposta a doenças ainda sem cura." vs EN "New marine leads for unmet clinical needs." These are not equivalent.
- Technology intro: PT "buscando terapias alternativas" has no EN counterpart.

## Fixes applied (listed)
- Name "Ana ROsa Abreu" → "Ana Rosa Abreu".
- Group "Conselho Administrativo" → "Conselho de Administração" (matches Pedro's role text).
- PT roles: English left on PT page translated (e.g. "COO, Funding, Business and Innovation Manager" → "COO, Gestora de Financiamento, Negócio e Inovação"; "Lab Technician" → "Técnica de Laboratório"; "PhD Student" → "Estudante de Doutoramento"; "Head of Animal Research" → "Responsável de Investigação Animal"; "R&D PM" → "Gestor(a) de Projeto de I&D"; "Admin, Financial Controller" → "Administrativa, Controladora Financeira"; "Admin Assistant" → "Assistente Administrativa"; "Technical Assistant, Diving" → "Assistente Técnico, Mergulho"). Expertise lists were translated the same way. "Director Geral" → "Diretor-Geral". **Please check the gendered PT job titles.**
- PT bios: "fundador do Sea4Us" → "fundador da Sea4Us"; "I&D ." → "I&D."; "gestão contábil" → "gestão contabilística"; "pesquisas científicas" → "investigação científica"; "relacionados à" → "relacionados com a". Left as is: "autoridades contribuintes" (awkward, means contributory/social-security authorities).
- EN bios: "PhD … by the University" → "from the University"; "Since 2010 he has his laboratory at Nova Medical School" → "has had … NOVA Medical School"; "Masters Degree" → "Master's Degree"; "by the University of Lisbon" → "from".
- Supporters intro: PT "graças a a si" → "graças a si"; EN title typo "Crouwdfunding Supporters" (page h2, not copied) → "Crowdfunding Supporters". The bold "<strong>" in PT was moved off the link text to match the meaning; the text is unchanged.
- Partners: "Insituto Galénico" → "Instituto Galénico".
- Useful links: "Desenvolvimento da Regional" → "Desenvolvimento Regional" (2×); "COMPETE: PFOC" → "COMPETE: POFC"; "Factores" → "Fatores" (PT); EU programme names translated into PT (Comissão Europeia, FEP, FEAMP, FEDER, União Europeia, Horizonte 2020, Ações Marie Skłodowska-Curie) and PT entity names into EN.
- Transparency text (_work/transparency-text.json) is copied **exactly as published**; only h4→h3 and the PDF button became `{{item:doc:rnt-chronic-pain}}`. Errors left in for review: PT "Substituiução" (→ Substituição), "bem estar de humanos" / "bem estar-animal" (→ bem-estar), "O uso humano de animais" (EN says "humane use", so PT should be e.g. "uso ético/humanitário"), "Sea4US" in the heading (→ Sea4Us), "pelo nosso país" (→ no nosso país), "Diretiva nº 63/2010/EU" (the directive is 2010/63/UE); EN "Sea4Us records the vital role" (→ recognises), "least amount of animals" (→ fewest animals), "Directive no. 63/2010/EU" (→ 2010/63/EU). The link label "Resumo Não Técnico Pain Research" mixes PT and EN on both pages.

## Useful links: staleness
Many targets are probably dead or out of date: QREN, POFC (pofc.qren.pt), POR Lisboa (porlisboa.qren.pt), PROMAR 2007-2013, EFF/EMFF pages, Horizon 2020, Ministério do Mar URL (portugal.gov.pt/pt/ministerios/mm.aspx), CRESC Algarve (algarve2020.pt), Lisboa2020, COMPETE2020. Nothing was checked (no network). The page carries no Portugal 2030 / Horizon Europe links.

## Documents / transparency
- `2024/02/RNT_ChronicPain.pdf` (linked from technology #animalresearch, PT and EN): a 2-page "Resumo Não Técnico do Projeto" in Portuguese only, printed from an Excel template (PDF title "Modelo-RNT_versao_1.1_Adenda Projecto Dor Crónica.xlsx", author Beatriz Szwarc, creation date 12/02/2024 from metadata). doc_date is left "" because no publication date is shown. The **project title field is blank** in the PDF. It states a 60-month duration, Wistar rats, 7 chronic-pain models, and the 3Rs measures. It also contains form guidance columns ("Este campo não será publicado…"). Consider asking for a clean or updated RNT, and an EN version.
- The page says Sea4Us is a signatory of the "Acordo de Transparência sobre a Investigação Animal em Portugal", but **no link to the agreement is published** anywhere on the site. The only EARA links are the footer badges: https://www.eara.eu/ ("Membro de EARA", badge `2020/08/EARA.webp`) and https://www.eara.eu/get-on-board22 (Be Open badge `2020/08/EARA-beopen.png`).
- No other PDFs are on the team, partners, supporters, useful-links, about or home pages.

## Claims found (for the claims register)
- https://sea4us.pt/pt/crowdfunding-supporters/ and /en/: "A cada dia que passa, estamos um passo mais próximos de tratar eficazmente dor crónica" / "With each passing day, we are one step closer to effectively treating chronic pain"; "o nosso projeto de desenvolver um novo analgésico" / "our project to develop a new painkiller".
- https://sea4us.pt/pt/sobre-nos/team/ (Co-founders text): "levou à identificação de alvos moleculares para o tratamento da dor crónica" / "led to the identification of the molecular targets for chronic pain treatment".
- https://sea4us.pt/pt/ (home): "O mar em resposta a doenças ainda sem cura." / "New marine leads for unmet clinical needs."; the fishermen's saying "As dores e as feridas adquiridas na terra são curadas no mar" / "Agues and wounds acquired in land are cured in the sea" (a cure claim if read literally).
- https://sea4us.pt/pt/technology/: "compostos que demonstraram uma ampla gama de propriedades farmacológicas" / "compounds that have demonstrated a wide range of pharmacological properties"; "são realizados ensaios in silico e ex vivo para avaliar os níveis de toxicidade". The pipeline uses unlabelled progress bars over stages discovery → Lead Optimization → Preclinical → CTA → Phase 1 CT for 6 indications; the bar values are not in the cleaned text, so check them against the pipeline/claims work and do not infer stages.
- About page (https://sea4us.pt/pt/sobre-nos/): "descoberta e desenvolvimento de novos medicamentos baseados em novos compostos de origem marinha" (descriptive, low risk).
- No financial claims on these pages. The crowdfunding page gives no amount raised.

## Other
- Team page source_url is the PT URL (https://sea4us.pt/pt/sobre-nos/team/); EN is https://sea4us.pt/en/sobre-nos/team/.
- The partners page ends with "Colaborar Connosco / Collaborate with us" text and the e-mail partnering@sea4us.com. Note the domain is **.com** while the general e-mail is info[at]sea4us.pt. Not extracted into these files.
