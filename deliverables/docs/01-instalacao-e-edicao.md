# Sea4Us · Instalação e edição

Versão 1.0.0 do tema `sea4us` e do plugin `sea4us-core`. Ambiente alvo: WordPress 6.5 ou superior, PHP 8.1 ou superior, MySQL/MariaDB, WP-CLI.

> **Estado:** proposta para validação. O site **não está pronto para publicação** enquanto os itens bloqueantes do [registo de pendentes](02-registo-de-pendentes.md) não estiverem fechados.

## 1. Pacotes

| Ficheiro | Conteúdo |
|---|---|
| `sea4us-theme-1.0.0.zip` | Tema híbrido (PHP + `theme.json`), fontes Manrope e Inter alojadas localmente, logótipo original. |
| `sea4us-core-1.0.0.zip` | Tipos de conteúdo (Pessoas, Programas, Projetos, Notícias, Parceiros, Documentos), campos estruturados, blocos dinâmicos `sea4us/*`, camada bilingue PT/EN, SEO (hreflang, canónicos, sitemaps), formulário, redirecionamentos e importador WP-CLI. |
| `sea4us-content-media.zip` | Conteúdo importável (`*.json`), `redirects.csv` e a pasta `media/` com os 228 ficheiros usados, nos caminhos originais de `wp-content/uploads`. |
| `sea4us-preview-estatica.zip` | Exportação estática da pré-visualização (abre com qualquer servidor estático). |
| `inventario/` | Inventário de migração, mapa de URLs antigo → novo, registo de alegações. |

Não há segredos, chaves nem palavras-passe em nenhum pacote.

## 2. Instalação num ambiente protegido

1. Criar um WordPress limpo **num domínio de testes protegido** (autenticação HTTP ou IP restrito). Não instalar sobre o site atual.
2. Em **Definições → Leitura**, marcar "Desencorajar os motores de busca". O plugin acrescenta `noindex, nofollow` em todas as páginas enquanto a opção "Permitir indexação" estiver desligada.
3. Em **Definições → Ligações permanentes**, escolher "Nome do artigo".
4. Instalar e ativar o plugin `sea4us-core-1.0.0.zip` e depois o tema `sea4us-theme-1.0.0.zip` (Plugins/Aspeto → Adicionar → Carregar).
5. Descompactar `sea4us-content-media.zip` no servidor e importar:

```bash
wp sea4us import --dir=/caminho/sea4us-content --dry-run   # só valida
wp sea4us import --dir=/caminho/sea4us-content              # importa
wp sea4us reindex                                            # reconstrói o índice de pesquisa
wp rewrite flush
```

Opcional: `--media=/outra/pasta` se os ficheiros de media estiverem noutro sítio.

A importação é **idempotente**: cada objeto guarda uma chave `_s4u_source`, por isso voltar a correr o comando atualiza o mesmo conteúdo em vez de duplicar. Os media mantêm os caminhos `wp-content/uploads/AAAA/MM/…` originais, por isso as ligações antigas a PDFs e imagens continuam válidas após o lançamento.

Resultado esperado numa instalação limpa: 420 objetos de conteúdo, 228 media e 554 entradas de redirecionamento (inativas até ao lançamento).

## 3. Estrutura de URLs

| Página | PT | EN |
|---|---|---|
| Início | `/pt/` | `/en/` |
| Sobre / Equipa | `/pt/sobre/`, `/pt/sobre/equipa/` | `/en/about/`, `/en/about/team/` |
| Ciência e tecnologia | `/pt/ciencia-e-tecnologia/` | `/en/science-technology/` |
| Programas, S072 | `/pt/programas/`, `/pt/programas/s072/` | `/en/programmes/`, `/en/programmes/s072/` |
| Projetos | `/pt/projetos/` | `/en/projects/` |
| Parceiros | `/pt/parceiros/` | `/en/partners/` |
| Parcerias e investimento | `/pt/parcerias-e-investimento/` | `/en/partnering-investment/` |
| Notícias | `/pt/noticias/` | `/en/news/` |
| Transparência / 3Rs | `/pt/transparencia-na-investigacao/` | `/en/research-transparency/` |
| Apoiantes crowdfunding | `/pt/apoiantes-crowdfunding/` | `/en/crowdfunding-supporters/` |
| Links úteis | `/pt/links-uteis/` | `/en/useful-links/` |
| Contactos | `/pt/contactos/` | `/en/contact/` |
| Privacidade e cookies | `/pt/privacidade-e-cookies/` | `/en/privacy-cookies/` |
| Pesquisa | `/pt/pesquisa/` | `/en/search/` |

Os prefixos `/pt/` e `/en/` mantêm-se. Não há mudança automática de idioma (nem por navegador nem por geolocalização); o seletor de idioma liga sempre à página equivalente.

**Raiz `/`:** no site atual, `/` serve a versão inglesa e o `x-default` aponta para `/en/`. Por isso a predefinição é "Idioma da raiz = Inglês" com redirecionamento **temporário (302)**. Só passar a 301 depois de a Sea4Us confirmar que quer manter este comportamento.

## 4. Edição no dia a dia

- **Páginas** (Páginas): editor de blocos. As secções usam blocos nativos (grupo, título, parágrafo, media e texto) e blocos dinâmicos `Sea4Us` (pipeline, equipa, projetos, notícias, parceiros, documentos, faixa de cofinanciamento, formulário), que se atualizam sozinhos a partir dos conteúdos.
- **Tradução:** cada conteúdo tem um campo de idioma e uma ligação à sua tradução (caixa "Idioma e tradução" na barra lateral do editor). Para criar a versão EN de uma página PT, duplicar, mudar o idioma para EN e ligar as duas.
- **Notícias:** data real de publicação, imagem de destaque, tipo (empresa ou imprensa), ligação ao original e "Marco histórico confirmado" (aparece na cronologia). Os campos estão na caixa "Dados estruturados".
- **Programas:** o campo **Estádio confirmado** só aceita valores de uma lista fixa e tem campos próprios para a data e a fonte da informação. O valor por defeito é "Por confirmar". Não há percentagens nem barras de progresso. Qualquer alteração de estádio deve passar pela direção científica (ver registo de alegações).
- **Projetos:** referência, programa de financiamento, datas, montante, parceiros e documentos. A listagem tem filtros por estado e por programa de financiamento, e pesquisa por texto. Montantes desconhecidos ficam vazios, nunca a zero.
- **Pessoas e parceiros:** ordem manual pelo campo "Ordem" (Atributos da página).
- **Créditos de fotografia:** campo "Crédito" em cada imagem da Biblioteca de media; aparece por baixo da imagem. Pessoas têm também "Crédito da fotografia".
- **Contactos, moradas, redes e cofinanciamento:** um só sítio, em **Definições → Sea4Us**. Alterar aí atualiza o cabeçalho, o rodapé, a página de contactos e os dados estruturados.

## 5. Formulário de contacto

Por defeito o formulário está em **modo demonstração**: valida os campos (incluindo consentimento), mostra a confirmação e **não envia nem guarda nada**. A proteção anti-spam guarda apenas um hash com sal do IP durante 10 minutos, para limitar pedidos repetidos.

Para produção são precisos os três passos, de propósito:

1. Em `wp-config.php`: `define( 'SEA4US_FORM_MODE', 'production' );`
2. Em **Definições → Sea4Us**, indicar o destinatário e marcar "Destinatário confirmado pela Sea4Us".
3. Configurar um envio de email fiável (SMTP transacional) e validar o texto de privacidade com o jurídico.

## 6. Lista de verificação de lançamento

Nada disto deve ser feito antes de a Sea4Us validar os conteúdos científicos e legais.

- [ ] Itens bloqueantes do registo de pendentes fechados e aprovados por escrito.
- [ ] Email de parcerias confirmado (`.pt` ou `.com`) em Definições → Sea4Us. Até lá, o site mostra o email geral.
- [ ] Política de privacidade e cookies validada pelo jurídico (retirar os marcadores "[a confirmar]").
- [ ] Créditos de fotografia confirmados.
- [ ] Formulário em produção (secção 5) e testado com um destinatário interno.
- [ ] Cópia de segurança completa do site atual.
- [ ] "Ativar redirecionamentos 301 do site antigo" ligado. Rever antes `inventario/url-map.csv`.
- [ ] "Permitir indexação" ligado e "Desencorajar os motores de busca" desligado.
- [ ] Comportamento da raiz `/` confirmado (302 ou 301).
- [ ] Submeter `/wp-sitemap.xml` na Google Search Console e verificar hreflang.
- [ ] Repetir o teste de acessibilidade e de URLs no domínio final.

## 7. Regenerar conteúdos (equipa técnica)

As ferramentas em `sea4us/tools/` reconstroem tudo a partir da cópia do site atual:

```bash
python3 tools/inventory.py        # inventário de migração
python3 tools/extract_dump.py     # extração de páginas e artigos
python3 tools/build_pages.py      # páginas, programas, definições
python3 tools/build_content.py    # notícias, documentos, media
python3 tools/build_claims.py     # registo de alegações
python3 tools/build_redirects.py  # mapa de URLs e redirects.csv
sh tools/dev-up.sh                # WordPress local para testes
node tools/qa.mjs http://localhost:8080 ./qa
python3 tools/export_static.py --base http://localhost:8080 --docroot <wp> --out ./preview
```
