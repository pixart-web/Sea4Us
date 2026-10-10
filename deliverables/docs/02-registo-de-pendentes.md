# Registo de pendentes: novo site Sea4Us

Data de referência: 10/10/2026.

O site **não está pronto para publicação** enquanto os itens marcados como **Bloqueante** não estiverem fechados. Os itens de prioridade Alta devem ficar resolvidos antes ou logo após o lançamento. Os redirecionamentos (P70) devem estar ativos no dia da mudança de DNS. Os itens Média e Baixa podem ser tratados depois do lançamento.

Este registo junta as notas de extração (pessoas e parceiros, projetos, notícias, montagem), o registo de alegações (`inventario/claims-register.csv`, C01–C28), o inventário de migração e os marcadores «[a confirmar]» do conteúdo. Os pendentes repetidos em várias fontes aparecem uma só vez.

Quem decide:
- **Sea4Us direção científica:** alegações científicas, regulamentares e de saúde.
- **Sea4Us comunicação:** factos da empresa, textos, traduções, imagens e contactos.
- **Jurídico:** privacidade, dados pessoais e redação legal.
- **Pixart:** execução técnica, depois da decisão da Sea4Us quando for necessária.

---

## 1. Ciência e alegações

| ID | Item | Onde | Prioridade | Quem decide | Ação proposta |
|---|---|---|---|---|---|
| P01 | Fase do S072. A aprovação do pedido de autorização de ensaio clínico (CTA) de 01/05/2026 não é o início da Fase 1 nem uma autorização de introdução no mercado. O post 8271 (RTP «Sociedade Civil», 22/06/2026) diz que o S072 «iniciou recentemente os ensaios clínicos de Fase 1a» (C04, não usado). As notícias também não coincidem na data de início dos ensaios: T3 2025 (7547), 2025 (7560), 2026 (7881) e «em breve» (7782). | Ficha S072; pipeline; destaque na home; notícia 8271 | Bloqueante | Sea4Us direção científica | Confirmar a fase atual com a data da primeira administração e o registo do ensaio. Até lá, o site mantém a fase «CTA aprovado». Se a Fase 1 for confirmada, a fase muda num só sítio. |
| P02 | As alegações usadas no site estão todas com `review_status = pending`: C01–C03, C05–C07, C11–C15, C18, C20, C23 e C24. | Ficha S072; pipeline; home; página Programas; Sobre; Parcerias; descrição SEO | Bloqueante | Sea4Us direção científica | Rever e aprovar por escrito cada alegação do registo e marcar `review_status` como validado. |
| P03 | No C01, a autoridade competente que aprovou o CTA não é nomeada e falta o número do ensaio. | Ficha S072 | Alta | Sea4Us direção científica | Indicar a autoridade (por exemplo INFARMED/CTIS) e o número EU CT para citar uma fonte independente. |
| P04 | Há seis áreas de investigação do pipeline antigo: dor neuropática, dor musculoesquelética, quimioterapia, urologia, SNC/epilepsia e doenças infecciosas. No novo site aparecem com «estádio por confirmar». As percentagens das barras antigas (83/40/46/27/46/20) não foram usadas como fases (C22). | Página Programas / pipeline | Alta | Sea4Us direção científica | Para cada linha, indicar a fase, o composto e a data. Confirmar também se a linha de dor neuropática corresponde ao S072. |
| P05 | O aviso «nenhum composto da Sea4Us tem autorização de introdução no mercado» foi inferido (C23): nenhuma fonte anuncia uma AIM. | Aviso do pipeline | Alta | Sea4Us direção científica | Confirmar o texto do aviso. |
| P06 | O termo «não opioide» / «non-opioid» é usado como descrição da classe do mecanismo (C12). | Resumo do S072; Parcerias; SEO | Alta | Sea4Us direção científica | Validar a formulação com o consultor regulamentar. |
| P07 | Patentes (C11): dois pedidos concedidos nos EUA e um terceiro em fase PCT, publicado em janeiro de 2026. Faltam os números. | Ficha S072 | Média | Sea4Us direção científica | Enviar os números (US…, WO…) para criar ligações ao Espacenet/USPTO. |
| P08 | O número «1,6 mil milhões de pessoas com dor crónica» (C20) vem de uma fonte secundária: o link original tinha `utm_source=chatgpt`. | Página Parcerias | Média | Sea4Us direção científica | Substituir pela citação da fonte primária. |
| P09 | Prova de conceito em seis modelos animais (C07): são dados pré-clínicos não publicados. | Ficha S072 | Média | Sea4Us direção científica | Enviar as publicações ou os posters que possam ser citados. |
| P10 | Alegações deixadas de fora do site de propósito: C08 (eficácia superior à morfina), C09 (ausência de risco de adição, sedação ou défice cognitivo), C10 (perfil de segurança GLP), C16 (20 pessoas; 6,8 M€ de capital e subvenções somados), C21 (mercado de 72,6 mil milhões de euros e «1 em cada 5» sem fonte) e C04 (Fase 1a). As razões são: alegações comparativas ou de segurança em humanos sem dados clínicos publicados; valores somados ou sem fonte e data; fase não confirmada. | Registo de alegações | Baixa | Sea4Us direção científica | Só é preciso decidir se a Sea4Us quiser usá-las. Nesse caso, enviar os dados publicados, a repartição entre capital e subvenções com data, ou a fonte do valor de mercado. |
| P11 | Alegações só usadas nas notícias históricas: C17 (EIC Accelerator: 2,5 M€ de subvenção e «até 5,96 M€» do EIF) e C19 (lead escolhido entre mais de 120 análogos). Nenhuma fonte diz que o lead do post 7110 é o S072. | Arquivo de notícias (7447, 7110) | Baixa | Sea4Us direção científica | Confirmar se o lead é o S072 antes de ligar os dois. Nunca somar o investimento «até» à subvenção. |
| P12 | As notícias históricas foram migradas tal como publicadas e têm alegações de saúde fortes, por exemplo: 7782 («superior efficacy», «eliminating risks of addiction»), 7881 («segurança excecional»), 7447 («altamente eficaz», «mudar a vida de milhares de milhões»), 7560, 7890 («alternativa melhor» a opioides, fentanil ou morfina), 7957, 8122, 8271, 3994 («alternativa à morfina»), 4423 e 57. | Notícias (lista completa em `news-issues.md` §4) | Alta | Sea4Us direção científica; Jurídico | Decidir entre manter como arquivo com uma nota de contexto histórico, editar ou despublicar os posts mais fortes. |
| P13 | O comunicado 7782 inclui imagens promocionais com texto em inglês: «Phase 1 clinical trials May 2026», «Promising efficacy», «without central nervous system side effects» (C28). | Notícia 7782 | Alta | Sea4Us direção científica | Decidir se as imagens ficam no artigo. A referência «Phase 1 … May 2026» depende de P01. |
| P14 | Os textos migrados das fichas de projeto têm alegações que não constam do registo C01–C28. Exemplos: Sea4Market («without any known side effects», «net profits of €24.1 m by 2028»), SEA4CIPN («eliminate CIPN completely», «disruptive and definitive solution»), Sea4Pain («no expected adverse effects on the brain»), MARINEBiotech («pre-clinical and clinical trials»), Algae4IBD («to stores and pharmacies in less than 4 years»), Blue4CancerPain («very similar to two molecules approved by the FDA and EMA») e Mar4Health («unprecedented efficacy»). | `projects.json`; fichas de projeto | Alta | Sea4Us direção científica | Rever as alegações listadas em `projects-issues.md` §4. Atenuar ou atribuir cada uma, ou acrescentá-las ao registo de alegações. |
| P15 | Há alegações nas páginas institucionais migradas: na Equipa, «identificação de alvos moleculares para o tratamento da dor crónica»; nos Apoiantes, «um passo mais próximos de tratar eficazmente a dor crónica»; na Tecnologia, «ampla gama de propriedades farmacológicas». | Equipa; Apoiantes do crowdfunding; Ciência/Tecnologia | Média | Sea4Us direção científica | Validar ou atenuar. |
| P16 | O ditado dos pescadores «as dores e as feridas … são curadas no mar» foi retirado da home e mantido só na página Sobre, como citação popular (C27). | Sobre | Baixa | Sea4Us comunicação | Confirmar que pode ficar como citação atribuída. |
| P17 | O post 7951 (SIC Notícias) fala de analgésicos não opioides, mas o endereço da SIC descreve um medicamento «à base de algas». | Notícia 7951 | Média | Sea4Us direção científica | Confirmar a que produto se refere a reportagem. |

## 2. Contactos e legal

| ID | Item | Onde | Prioridade | Quem decide | Ação proposta |
|---|---|---|---|---|---|
| P18 | O endereço de parcerias tem duas versões: partnering@sea4us.pt na página Partnering e partnering@sea4us.com na página Parceiros (C26). Por isso o site não mostra nenhum endereço de parcerias até `partnering_confirmed` estar preenchido. Os blocos de contacto mostram info@sea4us.pt. | `site.json` (`partnering_confirmed`); Parcerias; Parceiros | Bloqueante | Sea4Us comunicação | Confirmar qual dos endereços está ativo. Depois, a Pixart preenche `partnering_confirmed`. |
| P19 | A página «Privacidade e cookies» é um rascunho técnico. O próprio texto diz que não substitui a política aprovada pela Sea4Us. | `pages.json`, página de privacidade (PT/EN) | Bloqueante | Jurídico | Fazer a validação jurídica completa da página, incluindo a secção de cookies. |
| P20 | Faltam dados na página de privacidade: NIPC «[a confirmar]» e contacto para questões de privacidade «[a confirmar]». | Página de privacidade (PT/EN) | Bloqueante | Sea4Us comunicação; Jurídico | Enviar o NIPC e o contacto de privacidade (ou do encarregado de proteção de dados, se existir). |
| P21 | Formulário de contacto: o prazo de conservação dos dados e a base legal em produção estão «[a confirmar]». | Página de privacidade; formulário de contacto | Bloqueante | Jurídico | Definir o prazo de conservação e a base legal do tratamento. |
| P22 | O anti-spam do formulário guarda um hash salgado do IP como transient de 10 minutos. | Formulário de contacto; política de privacidade | Alta | Jurídico | Confirmar que o tratamento é aceitável e descrevê-lo na política de privacidade. |
| P23 | A denominação legal aparece como «…, SA» (Algae4IBD, Pacto, texto de privacidade) e como «…, Lda.» em conteúdos antigos. A transformação em SA data de 2021 (C15). | Sobre; fichas de projeto; privacidade | Média | Sea4Us comunicação | Confirmar a denominação legal atual e se os textos históricos se mantêm como publicados. |
| P24 | Factos institucionais da página Sobre: spin-off da Universidade NOVA de Lisboa criada em 2013 (C13), 7 cofundadores, Sagres como local de recolha e trabalho pré-clínico em Lisboa (C14). | Sobre; SEO | Média | Sea4Us comunicação | Confirmar. |

## 3. Conteúdos e tradução

| ID | Item | Onde | Prioridade | Quem decide | Ação proposta |
|---|---|---|---|---|---|
| P25 | No site antigo havia 42 notícias só numa língua: 39 só em EN e 3 só em PT. Foram traduzidas para a outra língua, incluindo o 3598 (marcado como só PT mas escrito em inglês) e o 3656 (traduzido de PT para EN). | Notícias (lista em `news-issues.md` §1) | Alta | Sea4Us comunicação | Rever as traduções, com prioridade para as que têm citações e alegações (57, 3598, 3648, 3696). |
| P26 | Há traduções parciais: no 7455, citações e testemunhos traduzidos em cada versão; no 3940, um bloco bilingue foi separado em PT e EN. | Notícias 7455, 3940 | Média | Sea4Us comunicação | Rever. |
| P27 | Foram corrigidos 25 títulos de notícias, por exemplo: 7782 «Pedido de autorização de ensaio clínico aprovado!», 7040 reformulado e 5819 diferenciado do 4341. | Notícias (`news-issues.md` §2) | Média | Sea4Us comunicação | Aprovar os títulos novos. |
| P28 | O PT e o EN não dizem o mesmo em vários sítios. A frase da home «O mar em resposta a doenças ainda sem cura» não equivale a «New marine leads for unmet clinical needs». A introdução da Equipa em EN omite a neurofisiologia. A introdução da Tecnologia em PT tem «buscando terapias alternativas», sem equivalente em EN. | Home; Equipa; Tecnologia | Média | Sea4Us comunicação | Escolher uma formulação equivalente nas duas línguas. A frase da home deve passar também por P02. |
| P29 | A página de transparência sobre investigação animal foi copiada tal como publicada, com erros: «Substituiução», «bem estar», «O uso humano de animais» (o EN diz «humane use»), «Sea4US», «pelo nosso país», «Diretiva nº 63/2010/EU» (deve ser 2010/63/UE), e no EN «records the vital role» e «least amount of animals». O rótulo «Resumo Não Técnico Pain Research» mistura as duas línguas. | Página de transparência; Ciência (C24) | Alta | Sea4Us comunicação; Sea4Us direção científica | Aprovar as correções propostas em `people-partners-issues.md`. |
| P30 | Algumas correções linguísticas nas fichas de projeto mudam o sentido: Sea4Market «avaliado em vários milhares de milhões de euros»; Algae4IBD «probióticos» passou a «prebióticos» e «Testando Compostos» passou a «Produtos farmacêuticos»; SEA4CIPN «mais expressos nos neurónios»; Chemistry EN «Innovation Voucher»; frase acrescentada em PT no SEA4PAINTERNACIONAL; Pacto, frase sobre a subvertical «Aplicações de Alto Valor». | `projects.json` | Média | Sea4Us direção científica | Confirmar que o sentido foi mantido. |
| P31 | A versão EN de Internacionalização (PREPARATION4MARKET) tem uma só frase. A versão PT tem a descrição completa. | Ficha Internacionalização (EN) | Baixa | Sea4Us comunicação | Enviar ou aprovar uma tradução completa. |
| P32 | Ficou por corrigir na biografia em PT a expressão «autoridades contribuintes» (pouco clara). | Equipa, biografias do Conselho de Administração | Baixa | Sea4Us comunicação | Reformular. |

## 4. Equipa, parceiros e apoiantes

| ID | Item | Onde | Prioridade | Quem decide | Ação proposta |
|---|---|---|---|---|---|
| P33 | Foto de Miriã Castanhar: a página usa `WhatsApp-Image-2026-01-06-at-11.30.24.jpeg`, mas existe também `Foto_Miria-Castanhar-e1767625585280.jpg`. | Equipa (`people.json`) | Média | Sea4Us comunicação | Indicar qual das fotos usar. |
| P34 | A foto `Foto_Filipa-Magalhaes-e1767625603387.jpeg` está anexada à página Equipa, mas Filipa Magalhães não aparece na página e não foi incluída. | Equipa | Média | Sea4Us comunicação | Confirmar se faz parte da equipa e, se sim, enviar o cargo. |
| P35 | Faltam cargos: Ana Bernardo, Ana Chegão e Patrícia Sequeira não têm cargo publicado. Os 4 cofundadores da galeria (Filipe Vilas-Boas, Marisa Sousa, Hugo Pacheco, Clara Patrício) só têm graus académicos. | Equipa | Média | Sea4Us comunicação | Enviar os cargos ou confirmar que ficam sem cargo. |
| P36 | Os cargos em PT foram traduzidos do inglês, com a forma de género escolhida por nós. Exemplos: «Gestora de Financiamento, Negócio e Inovação», «Técnica de Laboratório», «Gestor(a) de Projeto de I&D», «Controladora Financeira». | Equipa (PT) | Média | Sea4Us comunicação | Rever os cargos e o género de cada um. |
| P37 | Os cargos de Pedro A. Lima e Nuno Leitão diferem entre línguas. Pedro: PT «Presidente do CA, Diretor-Geral; Diretor Científico» e EN «President of the Board, CEO, CSO». Nuno: PT «Diretor Financeiro e de RH» e EN só «CFO». | Equipa | Média | Sea4Us comunicação | Harmonizar. |
| P38 | Os nomes nos textos alternativos (alt) do carrossel antigo não coincidem com os títulos usados: «Fernando Barcelos» em vez de Fernanda Barcelos e «José Eduardo Moura» em vez de Eduardo Moura. Os graus «PhD» de Raquel Barros, Ana Chegão e Patrícia Sequeira só aparecem nos alt. | Equipa | Baixa | Sea4Us comunicação | Confirmar a forma do nome e os graus a mostrar. |
| P39 | Nomes de parceiros publicados em minúsculas ou com grafia a confirmar: «necton», «ignae», «Migal» (poderá ser «MIGAL»). | Parceiros (`partners.json`) | Baixa | Sea4Us comunicação | Confirmar as marcas. |
| P40 | Faltam ligações de três parceiros: P-BIO, Câmara Municipal de Vila do Bispo e Algarve Biomedical Centre Collaborative Laboratory. O Instituto Galénico tem ligação para o LinkedIn em vez de site. Na home aparece o logótipo «sagrema» e na página Parceiros «Sagremarisco». | Parceiros; faixa de logótipos da home | Baixa | Sea4Us comunicação | Enviar os URL e o logótipo a usar. |
| P41 | Há duas fotos de grupo da equipa, `Sea4UsTeam_Dez2023_3618` e `_3625`, que não aparecem na página antiga. | Equipa | Baixa | Sea4Us comunicação | Decidir se são usadas na nova página Equipa. |
| P42 | A lista de links úteis (24) parece desatualizada: QREN, POFC, POR Lisboa, PROMAR 2007-2013, FEP/FEAMP, Horizonte 2020, URL do Ministério do Mar, CRESC Algarve, Lisboa2020, COMPETE2020. Não tem Portugal 2030 nem Horizonte Europa. Nenhuma ligação foi verificada. | Links úteis (`_work/useful-links.json`) | Média | Sea4Us comunicação | A Pixart verifica as ligações. A Sea4Us decide o que remover ou acrescentar. |

## 5. Projetos e documentos

| ID | Item | Onde | Prioridade | Quem decide | Ação proposta |
|---|---|---|---|---|---|
| P43 | As datas de fim diferem entre PT e EN em seis projetos: Blue4Pain (31/03/2021 vs 31/01/2024), Blue4CancerPain (31/08/2021 vs 31/08/2022), Mar4Pain2 (31/08/2021 vs 31/12/2022), SEA4PAINTERNACIONAL (12/02/2023 vs 30/06/2023), BLUEBIOTECH4PAIN (31/10/2022 vs 30/04/2024) e RHAQ (29/10/2022 vs 31/12/2023). Nestes projetos o campo `end_date` ficou vazio. | `projects.json`; fichas de projeto | Alta | Sea4Us comunicação | Confirmar a data de fim de cada projeto. |
| P44 | Outras datas em conflito. Internacionalização: a página indica início a 14/02/2018 e a ficha 15/02/2018–14/02/2019. RHAQ: início a 30/10/2020 na página e na ficha EN, 30/10/2021 na ficha PT. Algae4IBD: 01/07/2020–31/05/2022 na página, mas o post 6961 (01/07/2021) anuncia o arranque «para os próximos 4 anos». | Fichas Internacionalização, RHAQ, Algae4IBD | Média | Sea4Us comunicação | Confirmar as datas. |
| P45 | O montante do Algae4IBD não bate certo: o apoio da UE publicado (7.499.520,01 €) é maior do que o custo elegível (398.750,00 €). O campo `funding_amount` ficou vazio. | Ficha Algae4IBD | Alta | Sea4Us comunicação | Confirmar o apoio da própria Sea4Us. O valor maior será provavelmente o do consórcio. |
| P46 | Mar4Health não tem o código de operação (LISBOA-…), nem na página nem na ficha PDF. Só aparecem «Projeto 14315» e a referência do aviso. | Ficha Mar4Health | Alta | Sea4Us comunicação | Enviar o código de operação. |
| P47 | Faltam elementos de publicitação: a barra `Picture1.png` do MARINEBiotech e do BlueFlow não tem o emblema «Cofinanciado pela União Europeia». A ficha PDF do MARINEBiotech diz Algarve 2030 no cabeçalho e lisboa.portugal2030.pt no rodapé. O RHAQ é uma operação FSE, mas a barra e as fichas dizem FEDER. | Fichas MARINEBiotech, BlueFlow, RHAQ; PDF da ficha MARINEBiotech | Alta | Sea4Us comunicação | Enviar barras e fichas corrigidas. A Sea4Us corrige o PDF do MARINEBiotech. |
| P48 | Há projetos com publicitação incompleta. Sem barra de logótipos: Blue4Pain, Blue4CancerPain e Internacionalização. Com barra PT2020 mas sem ficha de operação: Mar4Pain, Mar4Pain2, SEABIOCIDE, SEA4PAINTERNACIONAL e SAGRES4US/SI2E. Só com o logótipo INNOWWIDE, sem emblema da UE: SEA4CIPN. | Fichas de projeto | Média | Sea4Us comunicação | Enviar as fichas e barras em falta, ou confirmar que não são exigidas. |
| P49 | ATIVAR.PT e Medida Emprego mostram a barra IEFP + Algarve 2030 + Portugal 2030 + UE, mas não têm código de operação PT2030 nem ficha. | Fichas ATIVAR.PT, Medida Emprego | Média | Sea4Us comunicação | Confirmar se o cofinanciamento PT2030 se aplica. |
| P50 | Códigos a confirmar. Blue4CancerPain: FA_05_2017_037 em PT e no índice, FA_05_2017_008 em EN; foi usado o _037. RHAQ: FSR no índice e FSE nas fichas; foi usado o FSE. ATIVAR.PT: o estágio 0288/TE/23 tem as mesmas datas que o 0327/TE/22. | Fichas Blue4CancerPain, RHAQ, ATIVAR.PT | Média | Sea4Us comunicação | Confirmar os códigos e as datas do estágio 0288/TE/23. |
| P51 | Faltam montantes de vários projetos: SEABIOCIDE, BLUEBIOTECH4PAIN, Sea4Pain, SAGRES4US/SI2E, ALGARED+, Ocean Medicines, SEACULTURE, SEACHEM e Patentes. Nos de consórcio (Algae4IBD, Pacto, ALGARED+, BLUEBIOTECH4PAIN, Ocean Medicines) também não se sabe quem é o líder. | `projects.json` | Baixa | Sea4Us comunicação | Completar se quiserem. Não é obrigatório para publicar. |
| P52 | O Pacto Bioeconomia Azul não tem ficha de projeto própria, e o título antigo ligava por engano a algae4ibd.eu (a ligação foi retirada). | Ficha Pacto | Baixa | Sea4Us comunicação | Enviar o URL correto do Pacto, se existir. |
| P53 | O RNT (`RNT_ChronicPain.pdf`) existe só em PT. O campo do título do projeto está em branco e o PDF inclui as colunas de instruções do formulário («Este campo não será publicado…»). | Página de transparência / investigação animal | Alta | Sea4Us direção científica | Enviar um RNT limpo e atualizado e, se possível, uma versão EN. |
| P54 | A página diz que a Sea4Us é signatária do Acordo de Transparência sobre a Investigação Animal em Portugal, mas não há ligação para o acordo. | Página de transparência | Média | Sea4Us comunicação | Enviar o URL do acordo. |
| P55 | O PDF do BlueFlow (`PT2030_FichaOperacao_A4_Algarve2030_BlueFlow_versao-final.pdf`, 18,4 MB) só tem ligação para o URL original e apenas na pré-visualização estática. | Ficha BlueFlow; documentos | Alta | Pixart | Carregar o PDF na biblioteca de media de produção, se possível numa versão otimizada, e atualizar a ligação. |

## 6. Notícias

| ID | Item | Onde | Prioridade | Quem decide | Ação proposta |
|---|---|---|---|---|---|
| P56 | Faltam imagens de galerias: os shortcodes não renderizados foram removidos. No 3882 faltam as imagens 3932 e 3936. No 4160 (Noite Europeia dos Investigadores 2017) foram removidos 7 carrosséis. No 4947 (MaNaPro) faltam as imagens 4951, 4949 e 4950. | Notícias 3882, 4160, 4947 | Média | Pixart | Voltar a inserir as imagens a partir da biblioteca de media original. |
| P57 | O vídeo mp4 local do post 7447 tem de ficar associado no `video_url` e a uma chave de media. | Notícia 7447 | Média | Pixart | Fazer esta associação na importação. |
| P58 | O post 7426 (evento final do BLUEBIOTECH4PAIN) tem uma ligação para a vista de administração do LinkedIn. | Notícia 7426 | Média | Sea4Us comunicação | Enviar o URL público da publicação. |
| P59 | Há ligações internas para a língua errada: no 7755 (EN), para a página do projeto em /pt/; no 5819 (PT), para /en/. No 4160, a ligação http://sea4us.pt/jogos/ ficou sem correspondência. | Notícias 7755, 5819, 4160 | Baixa | Pixart | Apontar as ligações para a página na língua certa (`{{page:…}}`) e decidir o destino de /jogos/. |
| P60 | Parâmetros de tracking (`utm_source=chatgpt.com`, `fbclid`): o relatório de montagem diz que 14 foram removidos. | Notícias 7782, 7881 e outras | Baixa | Pixart | Verificar no conteúdo final que nenhum ficou. |
| P61 | Datas a confirmar: 7080, 7057 e 7105 parecem antedatadas (IDs criados em 2023); o 7040 tem data de 01/01/2023, mas as fotos são de 15/05/2023. | Notícias 7080, 7057, 7105, 7040 | Média | Sea4Us comunicação | Confirmar as datas dos eventos. |
| P62 | Incoerências menores de datas e tempos verbais: 3598 («late last year»), 4126 («desde abril de 2017», mas o arranque foi a 12/05/2017), 7139 (futuro para a BIO-Europe 2022, já realizada), 7144 (presente para a Conferência dos Oceanos da ONU), 7560 (ligação a uma publicação de novembro de 2023), 7913 (imagem de 2025 para um evento de 2026), 2647 («três dias», de 3 a 6 de maio). O 7045 diz «10 parceiros» mas só lista 9. | Notícias listadas | Baixa | Sea4Us comunicação | Corrigir ou manter como publicado. |
| P63 | O post 3708 (27/01/2016) refere fundos Portugal 2020 já recebidos, antes do anúncio da aprovação (post 3838, de 18/04/2016). A citação em PT é uma retroversão do texto do Expresso. | Notícia 3708 | Média | Sea4Us comunicação | Comparar com o artigo original do Expresso. |
| P64 | Alegações financeiras nas notícias: 7080 («10 novos investidores»), 7105 («3 novos investidores») e 7930 (Goparity: 1.153.335,14 €, 3.963 investidores, 128 % do objetivo, «maior campanha de sempre»). | Notícias 7080, 7105, 7930 | Média | Sea4Us comunicação | Confirmar os valores ou manter como arquivo histórico. |

## 7. Media e créditos

| ID | Item | Onde | Prioridade | Quem decide | Ação proposta |
|---|---|---|---|---|---|
| P65 | Créditos fotográficos (C25). João Rodrigues / CHIMERAVISUALS está confirmado para as fotos `Untitled-design*.jpg` (Biologia Marinha e Eletrofisiologia). As restantes fotos aparecem como «© Pedro A. Lima (a confirmar)». O rodapé diz «Créditos por imagem a confirmar». | Créditos no rodapé (`site.json`); créditos de media | Alta | Sea4Us comunicação | Enviar o autor de cada imagem usada. Depois a Pixart retira «(a confirmar)» e a nota do rodapé. |
| P66 | O crédito CHIMERAVISUALS da home refere-se à imagem de fundo do hero, definida no CSS do Elementor. Não se sabe qual é o ficheiro. | Home | Média | Sea4Us comunicação | Identificar a imagem e confirmar se é usada no novo site. |
| P67 | O ficheiro da foto de Daniel Vargem tem o nome de um modelo de Instagram (`Historia-para-Instagram-…-1.png`) e falta-lhe a ligação de perfil. | Equipa | Baixa | Pixart | Renomear na importação. A Sea4Us envia a ligação de perfil, se existir. |
| P68 | Faltam as variantes redimensionadas (`-WxH`) das imagens das notícias, mas todos os originais existem. Os `src` estão como URL de origem. | Notícias (`news-editorial.json`) | Média | Pixart | Na importação, associar cada imagem ao `images[].local` e regenerar os tamanhos. |

## 8. Técnico e lançamento

| ID | Item | Onde | Prioridade | Quem decide | Ação proposta |
|---|---|---|---|---|---|
| P69 | O formulário de contacto está em modo de demonstração: nada é enviado nem guardado. Para produção faltam o destinatário, o transporte SMTP e a validação de privacidade (P21, P22). | Formulário de contacto; tema/plugin | Bloqueante | Pixart | A Sea4Us indica o endereço de destino. A Pixart configura o SMTP e testa o envio real. |
| P70 | Redirecionamentos: o inventário prevê 262 «migrar + 301», 86 «301», 120 «301 para ficheiro», 78 «301 para página-mãe», 8 «fundir + 301» e 92 «sem redirecionamento (404)». Há ainda caminhos antigos do índice de projetos: /our-projects/…, /pt/our-projects/AQUACULTURE/ e blueflow/%20. | `inventory/url-map.csv` | Alta | Pixart | Carregar e testar os 301 antes de mudar o DNS. A Sea4Us confirma que os 92 URL podem devolver 404. |
| P71 | Valores dependentes de decisões: `partnering_confirmed`, marcadores «[a confirmar]» e «[to be confirmed]» (4 em PT e os equivalentes em EN) e créditos «(a confirmar)». | `site.json`; `pages.json`; créditos | Alta | Pixart | Antes do lançamento, procurar todos os marcadores e confirmar que nenhum fica visível. |

---

## Resumo

O registo tem **71 pendentes**:
- **7 Bloqueantes:** P01, P02, P18, P19, P20, P21 e P69. Têm de estar fechados antes da publicação.
- **19 de prioridade Alta.**
- **30 de prioridade Média.**
- **15 de prioridade Baixa.**

Os bloqueantes concentram-se em três frentes:
- **Ciência:** a fase real do S072 e a validação das alegações usadas.
- **Legal:** o endereço de parcerias, a página de privacidade e os dados em falta.
- **Técnico:** o formulário de contacto em produção.

A maior parte dos itens Média e Baixa pode ser fechada depois do lançamento, sem risco legal nem científico.
