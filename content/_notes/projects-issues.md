# Projects & public funding: extraction issues

Sources: `source/extracted/pages` (parent 3444, plus 3444-projects, 8210-public-funding and 4856-alg-02-0752-feder-036043), `source/html/{pt,en}__sobre-nos__projects__*.html`, the PDFs in uploads and the posts in `source/extracted/posts`. Snapshot date: 2026-10-10.
Outputs: `content/projects.json` (25 sheets) and `content/_work/documents-projects.json` (7 documents).

## 1. Coverage

| Expected | Found? | Slug / note |
|---|---|---|
| Blue4Pain | yes (5070) | `blue4pain` |
| Blue4CancerPain | yes (5076) | `blue4cancerpain` |
| Mar4Pain2 | yes (5082) | `mar4pain2` |
| SEA4PAININTERNACIONAL | yes (6044) | `sea4paininternational` |
| BLUEBIOTECH4PAIN | yes (6070) | `bluebiotech4pain` |
| RHAQ | yes, 2 duplicates (6084, 6386) | merged into `highly-qualified-human-resources`. 6386 is the base because it has the ficha PDFs. 6084 has a dead "For more information follow this link" with no href, and a PT sentence on the EN page. |
| SEABIOCIDE | yes (6591, old slug seabiocide-2) | `seabiocide` |
| Algae4IBD | yes (7173) | `algae4ibd` |
| Pacto Bioeconomia Azul | yes (7257) | `blue-bioeconomy-innovation-pact` |
| Sea4Market | yes (7476) | `sea4market` |
| Medida Emprego | yes (7617) | `medida-emprego` |
| Mar4Health | yes (7652) | `mar4health` |
| MarineBiotech | yes (7680) | `marinebiotech` |
| BlueFlow | yes (8143) | `blueflow` |
| ATIVAR.PT | yes (7591) | `ativar-pt` |
| ALGARED+ | yes (4041) | `algared`. The Spanish page **algared-es (4050) exists** with the same data in Spanish. Merged and mentioned in status_note; the Spanish text is not imported. |
| SAGRES4US | **yes, it is the SI2E page** (4503 "SI2E", heading SAGRES4US; the index link "SAGRES4US" points to /our-projects/si2e/) | `si2e`, title "SAGRES4US (SI2E)" |
| SI2E | same as above | `si2e` |
| SEA4CIPN | yes (5943) | `sea4cipn` |
| SEACHEM / Química | yes (3476 chemistry) | `chemistry`. A thematic page, but it describes a funded QREN Vale Inovação, which status_note says. |
| SEACULTURE / Aquacultura | yes (3469 aquaculture) | `aquaculture`. A thematic page (PROMAR 31-04-01-FEP-308), which status_note says. |
| Patentes | yes (3484 patents, heading SEAPATENTS) | `patents`. A thematic page (QREN Vale Empreendedorismo), which status_note says. |
| MAR4PAIN | yes (3449) | `mar4pain` |
| PREPARATION4MARKET | **only as a label on the index**: "PREPARATION4MARKET – PORTUGAL 2020 \| ALG-02-0752-FEDER-036043" links to /our-projects/internacionalizacao/ | merged into `internacionalizacao` |
| Internacionalização | yes (4431) | `internacionalizacao`, title "Internacionalização (PREPARATION4MARKET)" |
| ALG-02-0752-FEDER-036043 page (4856) | yes. It only contains a `[su_document]` shortcode pointing to `2019/05/Se4us_Ficha-de-Projeto_vf.pdf` | carries the same code as Internacionalização, so it is merged there as `doc:ficha-projeto-alg-02-0752-feder-036043` |
| SEA4PAIN | yes (4413) | `sea4pain` |
| Ocean Medicines | yes (3458) | `ocean-medicines` |

No expected project is missing. The pages that were not imported as sheets are the attachment pages (`*__mar4health__ficha_de_operacao_mar4health`, `*__marinebiotech__picture1`, `captura-de-ecra-…`, etc.), which are WordPress media pages, and `8210-public-funding`, which duplicates the index but is truncated after "Medida Emprego". It has no Mar4Health, MarineBiotech or BlueFlow cards, no completed list, and no thumbnail for the Pacto card.

Order is by published start date, newest first. Medida Emprego is ordered by its latest start (26/06/2024) and ATIVAR.PT by its latest internship start (04/09/2023).

## 2. Conflicts and data problems (to confirm with Sea4Us)

### Dates: PT and EN pages disagree
| Project | PT end | EN end | Other source | Handling |
|---|---|---|---|---|
| Blue4Pain | 31/03/2021 | 31/01/2024 | – | end_date left empty, status completed (both are in the past) |
| Blue4CancerPain | 31/08/2021 | 31/08/2022 | – | same |
| Mar4Pain2 | 31/08/2021 | 31/12/2022 | – | same |
| SEA4PAINTERNACIONAL | 12/02/2023 | 30/06/2023 | – | same |
| BLUEBIOTECH4PAIN | 31/10/2022 | 30/04/2024 | final event announced for 30/04/2024 (post 7426) | same |
| RHAQ | 29/10/2022 | 31/12/2023 (6386) / 29/10/2022 (6084) | PT and EN fichas: 29-10-2022 | same |
| Internacionalização | 13/02/2019 | 13/02/2019 | ficha: start 15/02/2018, end 14/02/2019 (page: start 14/02/2018) | page dates kept; difference noted |
| RHAQ start | 2020-10-30 | 2020-10-30 | PT ficha 2021-10-30, EN ficha 2020-10-30 | page date kept |
| Algae4IBD | 01/07/2020 – 31/05/2022 | same | post 6961 (01/07/2021): project "has officially started… for the next 4 years" | page dates kept; contradiction noted |

### Codes
- Blue4CancerPain: the EN page says **FA_05_2017_008**, while the PT page and the index say **FA_05_2017_037**. FA_05_2017_037 is used; the EN value looks like a copy of Blue4Pain's FA_03_2017_008.
- RHAQ: the index says **ALG-05-3559-FSR-000017** (typo); the pages and fichas say **ALG-05-3559-FSE-000017**. The FSE code is used.
- ATIVAR.PT: internship **0288/TE/23** has exactly the same dates (28/09/2022 – 27/06/2023) as 0327/TE/22 despite its "/23" suffix. This may be a copy error.
- Mar4Health: the page shows "Projeto 14315" and "MPr-2023-9 – SIID…" (a call reference), but **no operation code** (LISBOA-…). The ficha PDF has no code text either. The operation code is missing.
- MARINEBiotech: the index card and the page give the operation code, but there is no separate project number.

### Amounts
- **Algae4IBD**: "EU financial support 7.499.520, 01 €" is greater than the "Total eligible cost 398.750,00 €". It is probably the consortium figure. `funding_amount` was left empty and the published figure is kept in funding_breakdown with a warning. Sea4Us must confirm Sea4Us's own grant.
- Pacto Bioeconomia Azul: the support is "634.839,3 €" (one decimal). Stored as 634839.30. Consortium figures (133 M€ / 94 M€) are kept only in funding_breakdown.
- Blue4Pain and Blue4CancerPain have amounts with no cents ("1.242.383€"). Stored as published (no decimals added).
- Medida Emprego EN shows "€10.642,74€" (double symbol, PT separators), corrected to €10,642.74.
- No amounts are published for: SEABIOCIDE, BLUEBIOTECH4PAIN, Sea4Pain, SAGRES4US/SI2E, ALGARED+, Ocean Medicines, SEACULTURE, SEACHEM, Patentes. Amounts for Mar4Health, MARINEBiotech, BlueFlow, RHAQ and Internacionalização come **only from the ficha PDFs**, not from the pages.
- ATIVAR.PT and Medida Emprego have per-measure incentive amounts only. These were not summed, and total_cost/funding_amount were left empty.

### Publicity elements (logo strip / ficha de operação), display_check
- **No logo strip at all**: Blue4Pain, Blue4CancerPain (Fundo Azul), Internacionalização (it has a ficha on a separate page, but no strip on the sheet). display_check=true.
- **The strip on MARINEBiotech and BlueFlow (`2025/12/Picture1.png`) shows only "Portugal 2030 + Algarve 2030", with no EU emblem** ("Cofinanciado pela União Europeia"). The fichas do contain the full strip. display_check=true.
- **MARINEBiotech ficha**: the header says Algarve 2030 but the footer URL says **lisboa.portugal2030.pt**. It looks like it was built from the Mar4Health template (identical embedded images). It should be corrected.
- **RHAQ**: the operation is FSE (code …-FSE-…), but both the page strip (`POAlgarve_PT2020_FEDER_barra…`) and the fichas say **FEDER – Fundo Europeu de Desenvolvimento Regional**. display_check=true.
- PT2020 projects with a strip but **no ficha de operação**: Mar4Pain, Mar4Pain2, SEABIOCIDE, SEA4PAINTERNACIONAL, SAGRES4US/SI2E. display_check=true.
- SEA4CIPN: only the INNOWWIDE logo, with no EU emblem. display_check=true.
- Pacto Bioeconomia Azul: it has the PRR / República Portuguesa / NextGenerationEU strip, but no project sheet. Also, its title links wrongly to algae4ibd.eu (the link was dropped). display_check=true (unclear).
- ATIVAR.PT and Medida Emprego: the strip `2020/08/fundos-publicos.png` shows IEFP + Algarve 2030 + Portugal 2030 + "Cofinanciado pela UE", but the pages give **no PT2030 operation code or ficha**. Confirm whether the PT2030 co-funding applies. display_check=true.
- display_check=false (strip/emblem present and appropriate for the programme): Mar4Health (strip + ficha), Sea4Market, Algae4IBD, Sea4Pain, Ocean Medicines (EU/H2020 emblems), BLUEBIOTECH4PAIN (EEA Grants + programme operator logos), ALGARED+ (Interreg logo), and SEACULTURE/SEACHEM/Patentes (PROMAR/QREN strips; historic, no ficha required in that period, which should be verified).
- `2017/05/algared.png` is a **partners strip** (Univ. Huelva, UAlg, CTAQUA, ICMAN, Necton, Univ. Córdoba, IPMA, IFAPA), not a funding strip. It is kept as a content figure. The ALGARED+ entities list comes from that image and post 4027; the page itself does not list them.

### PDFs
All 6 PDFs on sea4us.pt that are linked from project pages exist locally and open:
- `2019/05/Se4us_Ficha-de-Projeto_vf.pdf` (Internacionalização; it was embedded via the `[su_document]` shortcode, which would not render in the new site)
- `2021/08/Ficha_pagina_web-feder_RHAQ_PT.pdf`, `2021/08/Ficha_pagina_web-feder_RHAQ_EN.pdf`
- `2025/06/Ficha_de_Operacao_Mar4Health.pdf`
- `2025/12/Ficha_de_Operacao_Marinebiotech.pdf`
- `2026/05/PT2030_FichaOperacao_A4_Algarve2030_BlueFlow_versao-final.pdf` (pdftotext gives font warnings, but the text extracts)

There is one external PDF (IEFP Ficha Síntese Compromisso Emprego Sustentável), recorded with external_url and no file. The fichas have no date, so doc_date is empty. `2024/04/BlueBioTech4Pain.pdf` (final event programme, 30/04/2024) is linked from a post, not from the project page, so it is not in documents-projects.json.

### Other
- Several index links use old paths (/our-projects/…, /pt/our-projects/AQUACULTURE/, blueflow/%20 with a trailing encoded space). Add redirects for these.
- The SI2E page starts with the Elementor placeholder "Add Your Heading Text Here", which was removed.
- Sea4Market, Mar4Pain2, Blue4Pain, Blue4CancerPain, BLUEBIOTECH4PAIN and others are listed under "Active Projects" on the old index although their published end dates are in the past. Statuses follow the published dates.
- `lead` is unknown for consortium projects (Algae4IBD, Pacto, ALGARED+, BLUEBIOTECH4PAIN, Ocean Medicines). It is stated as "not stated on the page".
- The Algae4IBD and Pacto pages name the beneficiary "Sea4Us … SA", while older pages say "Lda.". Each is kept as published.

## 3. Language fixes made

PT:
- AO90 spelling: Projecto→Projeto, Objectivo→Objetivo, Actividades/actividade→Atividades/atividade, extracção/extractos→extração/extratos, bioactiva(s)/bioactividade→bioativa(s)/bioatividade, Acção→Ação, efectuadas→efetuadas, infra-estruturas→infraestruturas, anti-cancerígenos→anticancerígenos, anti-infecciosos→anti-infeciosos, microorganismos→microrganismos, opióides→opioides, sector→setor, prevêem→preveem, bio-guiado→bioguiado, infecciosas→infeciosas.
- Brazilian forms: iônicos→iónicos (Sea4Market, Mar4Health); bilhões→mil milhões (Sea4Pain); econômica→económica, ônus→encargo (Algae4IBD); pesquisado/pesquisa→investigado/investigação (SEA4CIPN, Algae4IBD); "Quebrando/Extraindo/Testando Compostos" (gerunds)→"Rutura das células das algas / Extração de compostos / Teste de compostos".
- Algae4IBD PT: the heading "Testando Compostos" over the pharmaceuticals block was wrong → "Produtos farmacêuticos". "probióticos ou antibióticos"→"prebióticos ou antibióticos" to match the EN "prebiotics". Typos: "determinara"→"determinar a", "interligaos"→"interligados". The PT NMR paragraph is shorter than the EN one and was kept as published.
- English left in PT: "Partner"→"parceiro", "Project Manager"→"gestora de projeto", "Official Website / Facebook Page"→"Website oficial / Página de Facebook" (Ocean Medicines); Mar4Health PT metadata lines "Competition | Lisboa 2030" and "Código do Project | 14315" (mixed EN/PT) were not copied into content; their data went to shared.code/funding_programme; "exit-strategy"→"estratégia de saída" (Blue4Pain); "freedom-to-operate"→"liberdade de operação (freedom to operate, FTO)" (SEA4CIPN, Mar4Pain).
- Grammar/typos: "tratamento do doenças"→"tratamento de doenças", "Intervençao"→"Intervenção", "da empresas"→"das empresas" (SEABIOCIDE); "nas nossos modelos"→"nos nossos modelos" (Química); "localizado em ambos os lados"→"localizadas" (ALGARED+); "mais ligados a neurónios"→"mais expressos nos neurónios" to match the EN "most expressed" (SEA4CIPN); "Determinação os análogos"→"Determinar os análogos" (Mar4Pain2); "vesícula hiper inflamada"→"vesícula hiperinflamada" (Mar4Health); "co-infligem efeitos nocivos"→"acarretam efeitos nocivos importantes" (Sea4Pain).
- Pacto PT: the bullet list mis-split the 7 sectors ("Biomateriais; novo paradigma…" in one bullet), now 7 bullets. "A Sea4Us é Líder das 'Aplicações de Alto Valor' Subverticais dentro das 'Algas' Verticais"→"A Sea4Us lidera a subvertical “Aplicações de Alto Valor”, na vertical “Algas”."
- Sea4Market PT: "mercado de Dor Crónica, avaliado em mil milhões de Euros" mistranslates the EN "multi-billion Euro". Changed to "avaliado em vários milhares de milhões de euros".
- MARINEBiotech PT: a paragraph was broken mid-sentence ("extração</p><p>de compostos"). It is now joined.
- SEA4PAINTERNACIONAL PT: the last EN sentence was missing from PT. It was added as a translation: "Com este projeto de internacionalização, a empresa pretende iniciar a sua atividade comercial nos mercados internacionais."

EN:
- Spanish/PT left in EN: "Dezembro de 2015 – Dezembro de 2019"→"December 2015 – December 2019" (Ocean Medicines status note); the EN aquaculture page title was "Aquacultura"→"Aquaculture".
- Typos: improvment, acces, humna (Aquaculture); sociaty, eludication, indentified (Chemistry); Internacionalization (SEA4PAINTERNACIONAL, Internacionalização); Biotechnoloy (BLUEBIOTECH4PAIN); "it’s in vitro"→"its in vitro" (Blue4CancerPain); Elegible→Eligible (Algae4IBD, Pacto, Sea4Market); portuguese→Portuguese; "Operacional Program"→"Operational Programme"; "CIPN.."; "peripheric"→"peripheral"; stray "." paragraph (MARINEBiotech).
- Spelling was changed to UK-neutral throughout (optimisation, commercialisation, valorisation, programme). "Chemistry" EN: "Sea4Us will go with Innovation Valley to focus…" was a literal translation of "Vale Inovação", now "Sea4Us will use the Innovation Voucher to focus…". "legal entities" became "regulatory authorities" (PT: "entidades legais").
- EN Internacionalização: the EN page only has a one-sentence summary, while the PT page has the full description. The EN text was kept as published and was not expanded. Sea4Us could provide a full translation.
- The old EN page of the Pacto had the heading "Blue Bioeconomy Innovation Pact" linking to algae4ibd.eu. The link was dropped.

## 4. Claims to register (verbatim, with source)

| Claim | Source URL |
|---|---|
| "considerably reduces side effects of usual therapies (e.g. opioids)" (Mar4Pain) | https://sea4us.pt/en/sobre-nos/projects/mar4pain/ |
| "unique, high efficacy analgesic (ion channel blocker), with reduced toxicity and limited side-effects"; "new class of pharmaceutical drugs with no expected adverse effects on the brain (currently being patented)"; "ease the suffering of one fifth of the World’s population"; market $38bn (2015) → $51bn (2022) | https://sea4us.pt/en/sobre-nos/projects/sea4pain/ |
| "new revolutionary analgesic of marine origin"; PT: preclinical tests done "to… obtain approval for clinical trials in humans" | https://sea4us.pt/pt/sobre-nos/projects/internacionalizacao/ , ficha 2019/05/Se4us_Ficha-de-Projeto_vf.pdf |
| "chronic pain, an untreated disease"; compound "passed the proof of concept phase" (Blue4Pain) | https://sea4us.pt/en/sobre-nos/projects/blue4pain/ |
| "S4U is very similar to two molecules approved by the FDA and EMA"; "new molecule of great analgesic value with an innovative mode of action" | https://sea4us.pt/en/sobre-nos/projects/blue4cancerpain/ |
| two FDA/EMA-approved molecules "can reduce and, in some cases, eliminate CIPN completely in animal models"; "side effects are few or non-existent"; CIPN "afflicts up to 40% of patients"; "disruptive and definitive solution" | https://sea4us.pt/en/sobre-nos/projects/sea4cipn/ |
| "proof of concept revealed good levels of efficacy in animal models" (marine bacteria molecule) | https://sea4us.pt/en/sobre-nos/projects/bluebiotech4pain/ |
| "world-class biotechnology company"; "ground-breaking therapeutic drug"; "highly effective at treating chronic pain and without any known side effects"; "non-opioid analgesic"; "First-in-Human trials"; "8 new positions… net profits of €24.1 m by 2028"; chronic pain affects "100 million people across Europe, 1.6bn globally" | https://sea4us.pt/en/sobre-nos/projects/sea4market/ |
| post: "first in class non-opioid analgesic… to clinical trials in 2025"; "allows safe and effective treatment, without the associated serious side-effects" | https://sea4us.pt/en/taking-our-non-opioid-analgesic-to-clinical-trials-sea4market-has-started/ |
| "more than 130 analogous compounds… ten stand out"; "unprecedented efficacy in modulating aberrantly hyperactive neurons" (ficha says "elevada eficácia"); "TRL 4" | https://sea4us.pt/en/sobre-nos/projects/mar4health/ |
| post: three candidates with "proof of concept… established in disease models: chemotherapy-induced neuropathic pain, osteoarthritis pain, and epilepsy"; "advance three new therapeutic programs" | https://sea4us.pt/en/news-mar4health/ |
| natural compounds "tested in pre-clinical **and clinical** trials, resulting in patented drugs"; "scientific validation that is rare"; "proven quality and efficacy" (MARINEBiotech) | https://sea4us.pt/en/sobre-nos/projects/marinebiotech/ |
| S072 "innovative marine-derived analgesic with a non-opioid mechanism of action"; methodologies "validated in a pre-clinical context and with strong potential" | https://sea4us.pt/en/sobre-nos/projects/blueflow/ |
| "From nature to stores and pharmacies in less than 4 years"; "functional food and medication delivered by mid-2025"; "can prevent the onset of IBD or relapses"; "reduce… hospitalised" | https://sea4us.pt/en/sobre-nos/projects/algae4ibd/ |
| extracts "bioactive against… species resistant to existing drugs"; teams "fully suited to all its needs" | https://sea4us.pt/en/sobre-nos/projects/seabiocide-2/ |
| "drug discovery… will significantly contribute to advance… to the top level" (Ocean Medicines) | https://sea4us.pt/en/sobre-nos/projects/ocean-medicines/ |
| "vast compound library" (RHAQ) | https://sea4us.pt/en/sobre-nos/projects/highly-qualified-human-resources/ |
| bioactivity in "chronic pain and dermatitis/eczema" cell models (Chemistry, Patents); Patents page describes patent **applications to be filed** (no grants stated) | https://sea4us.pt/en/sobre-nos/projects/chemistry/ , https://sea4us.pt/en/sobre-nos/projects/patents/ |
