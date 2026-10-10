# Relatório de testes

Data: 10/10/2026. Ambiente: WordPress local com o tema e o plugin 1.0.0 e o conteúdo importado (420 objetos, 228 media). Browser: Chromium (Playwright). Os scripts estão em `sea4us/tools/qa.mjs` e podem ser repetidos.

## Resumo

| Área | Resultado |
|---|---|
| Layout em 360, 390, 768, 1024 e 1440 px (26 páginas × 5 larguras) | 0 páginas com scroll horizontal, 0 textos cortados |
| Respostas HTTP das páginas testadas | 0 erros (a 404 devolve 404, como esperado) |
| Erros de consola | Só o 404 esperado na página de teste de erro |
| Acessibilidade automática (axe-core 4.10.2, regras WCAG 2.0/2.1/2.2 A e AA) | 0 violações em 334 páginas × 2 larguras (668 análises) |
| Teclado | Foco visível em todas as paragens; submenu e menu móvel com Enter e Escape |
| Formulário (PT e EN) | Estados vazio, inválido e sucesso de demonstração corretos; nada enviado nem guardado |
| Filtros e pesquisa | Estados sem resultados corretos; pesquisa "S072" devolve 6 resultados |
| Pré-visualização estática | 335 páginas, 395 ficheiros, navegação, idioma, pesquisa e formulário verificados |

## Detalhe

**Acessibilidade.** A primeira análise encontrou três tipos de problema, todos corrigidos e verificados numa segunda análise completa:
- Contraste insuficiente dos números decorativos nos blocos de acesso (3,6:1, passou a 5,4:1) e do sobretítulo no cabeçalho escuro de Parcerias (2,9:1, passou a 7,1:1).
- Ligações só com imagem e sem texto alternativo (76 casos em notícias antigas, como galerias e o logótipo UE). Passaram a ter texto que descreve o destino. Também foram removidos textos alternativos que eram só nomes de ficheiro.
- Botões de submenu parcialmente tapados (20 px de área útil). A área útil passou a 24 px, o mínimo do critério 2.5.8.

**Teclado.** A primeira paragem é "Saltar para o conteúdo". O menu, o seletor de idioma e os botões de ação têm foco visível. O submenu abre com Enter e fecha com Escape. No menu móvel, Escape fecha o menu e devolve o foco ao botão.

**Formulário.** O envio vazio mostra um resumo de erros com foco e 5 erros de campo. Email inválido, mensagem curta e consentimento em falta dão mensagens específicas. O envio válido mostra a confirmação de demonstração. A base de dados foi verificada depois dos testes: nenhum registo com os dados de teste. O único dado guardado é o hash com sal do IP, durante 10 minutos, usado para limitar envios repetidos. Este limite funcionou durante os testes, ao bloquear envios seguidos do mesmo dispositivo.

**Capturas.** Estão na pasta `capturas/`: início PT nas cinco larguras, início EN, equipa, ciência, pipeline PT/EN, S072, projetos e ficha de projeto, parcerias, notícias e artigo, contactos, 404, menu móvel, estados do formulário e estado sem resultados.

## O que não foi testado

- Firefox e Safari. Só foi usado Chromium.
- Leitores de ecrã (NVDA, VoiceOver). Os testes automáticos não substituem esta verificação manual.
- Desempenho em alojamento real (Lighthouse, Core Web Vitals) e cache.
- Envio real de email, porque o formulário está em modo de demonstração por decisão do projeto.
- Redirecionamentos 301 no domínio real. O mapa está validado contra o inventário, mas só fica ativo no lançamento.

Estes pontos devem ser feitos no ambiente de pré-produção, antes do lançamento.
