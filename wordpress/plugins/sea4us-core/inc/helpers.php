<?php
/**
 * Shared helpers and the interface string dictionary (PT-PT / EN).
 */

defined( 'ABSPATH' ) || exit;

/**
 * Interface strings. Content lives in posts; these are only labels and controls.
 * Themes or child plugins can extend/override via the `s4u_strings` filter.
 */
function s4u_strings() {
	static $strings = null;
	if ( null !== $strings ) {
		return $strings;
	}
	$strings = apply_filters( 's4u_strings', array(
		'skip'               => array( 'Saltar para o conteúdo', 'Skip to content' ),
		'menu'               => array( 'Menu', 'Menu' ),
		'close'              => array( 'Fechar', 'Close' ),
		'main_nav'           => array( 'Navegação principal', 'Main navigation' ),
		'footer_nav'         => array( 'Navegação de rodapé', 'Footer navigation' ),
		'language'           => array( 'Idioma', 'Language' ),
		'switch_to'          => array( 'Ver esta página em', 'View this page in' ),
		'search'             => array( 'Pesquisar', 'Search' ),
		'search_site'        => array( 'Pesquisar no site', 'Search the site' ),
		'search_placeholder' => array( 'Programas, projetos, notícias…', 'Programmes, projects, news…' ),
		'search_results_for' => array( 'Resultados para', 'Results for' ),
		'search_none'        => array( 'Não encontrámos resultados. Experimente outros termos ou explore as secções abaixo.', 'No results found. Try other terms or explore the sections below.' ),
		'search_hint'        => array( 'Escreva pelo menos duas letras.', 'Type at least two letters.' ),
		'results'            => array( 'resultados', 'results' ),
		'result'             => array( 'resultado', 'result' ),
		'home'               => array( 'Início', 'Home' ),
		'breadcrumbs'        => array( 'Localização na página', 'Breadcrumb' ),
		'read_more'          => array( 'Ler mais', 'Read more' ),
		'see_all_news'       => array( 'Ver todas as notícias', 'See all news' ),
		'see_all_projects'   => array( 'Ver todos os projetos', 'See all projects' ),
		'see_team'           => array( 'Conhecer a equipa', 'Meet the team' ),
		'see_pipeline'       => array( 'Ver o pipeline completo', 'See the full pipeline' ),
		'see_programme'      => array( 'Ver o programa', 'View the programme' ),
		'load_more'          => array( 'Mostrar mais', 'Show more' ),
		'filter'             => array( 'Filtrar', 'Filter' ),
		'filters'            => array( 'Filtros', 'Filters' ),
		'all'                => array( 'Todos', 'All' ),
		'all_years'          => array( 'Todos os anos', 'All years' ),
		'clear_filters'      => array( 'Limpar filtros', 'Clear filters' ),
		'status'             => array( 'Estado', 'Status' ),
		'funding_programme'  => array( 'Programa de financiamento', 'Funding programme' ),
		'year'               => array( 'Ano', 'Year' ),
		'category'           => array( 'Categoria', 'Category' ),
		'showing'            => array( 'A mostrar', 'Showing' ),
		'of'                 => array( 'de', 'of' ),
		'no_items'           => array( 'Não há conteúdos que correspondam a estes filtros.', 'No items match these filters.' ),
		'code'               => array( 'Código', 'Code' ),
		'objective'          => array( 'Objetivo', 'Objective' ),
		'period'             => array( 'Calendário', 'Timeline' ),
		'start'              => array( 'Início', 'Start' ),
		'end'                => array( 'Fim', 'End' ),
		'lead'               => array( 'Promotor', 'Lead' ),
		'entities'           => array( 'Entidades', 'Entities' ),
		'total_cost'         => array( 'Custo total elegível', 'Total eligible cost' ),
		'funding_amount'     => array( 'Apoio financeiro', 'Financial support' ),
		'not_published'      => array( 'Não publicado na fonte', 'Not published in source' ),
		'documents'          => array( 'Documentos', 'Documents' ),
		'download'           => array( 'Descarregar', 'Download' ),
		'original_page'      => array( 'Página original', 'Original page' ),
		'source'             => array( 'Fonte', 'Source' ),
		'source_date'        => array( 'Data da informação', 'Information date' ),
		'review_pending'     => array( 'A confirmar com a Sea4Us', 'To be confirmed by Sea4Us' ),
		'review_approved'    => array( 'Validado', 'Validated' ),
		'programme'          => array( 'Programa', 'Programme' ),
		'indication'         => array( 'Área / indicação', 'Area / indication' ),
		'stage'              => array( 'Estádio confirmado', 'Confirmed stage' ),
		'latest_milestone'   => array( 'Último marco confirmado', 'Latest confirmed milestone' ),
		'stage_unconfirmed'  => array( 'Estádio por confirmar', 'Stage to be confirmed' ),
		'stage_legend'       => array( 'Legenda dos estádios', 'Stage legend' ),
		'related'            => array( 'Relacionado', 'Related' ),
		'related_news'       => array( 'Notícias relacionadas', 'Related news' ),
		'published'          => array( 'Publicado a', 'Published on' ),
		'author'             => array( 'Autoria', 'Author' ),
		'photo_credit'       => array( 'Fotografia', 'Photo' ),
		'image_credits'      => array( 'Créditos de imagem', 'Image credits' ),
		'historical_note'    => array( 'Notícia histórica: o conteúdo reflete a informação disponível à data de publicação.', 'Historical news: the content reflects the information available at the date of publication.' ),
		'back_to_news'       => array( 'Voltar às notícias', 'Back to news' ),
		'back_to_projects'   => array( 'Voltar aos projetos', 'Back to projects' ),
		'back_to_team'       => array( 'Voltar à equipa', 'Back to the team' ),
		'external_link'      => array( 'abre num novo separador', 'opens in a new tab' ),
		'contacts'           => array( 'Contactos', 'Contacts' ),
		'phone'              => array( 'Telefone', 'Phone' ),
		'email'              => array( 'Email', 'Email' ),
		'hours'              => array( 'Horário', 'Opening hours' ),
		'follow'             => array( 'Acompanhe a Sea4Us', 'Follow Sea4Us' ),
		'funded_by'          => array( 'Cofinanciamento e apoios', 'Co-funding and support' ),
		'legal'              => array( 'Informação legal', 'Legal' ),
		'rights'             => array( 'Todos os direitos reservados.', 'All rights reserved.' ),
		'not_found_title'    => array( 'Página não encontrada', 'Page not found' ),
		'not_found_text'     => array( 'O endereço pode ter mudado com a nova versão do site. Pesquise ou siga para uma das secções principais.', 'The address may have changed with the new version of the site. Search or go to one of the main sections.' ),
		'demo_badge'         => array( 'Pré-visualização · demonstração', 'Preview · demonstration' ),
		// Form.
		'form_name'          => array( 'Nome', 'Name' ),
		'form_email'         => array( 'Email profissional', 'Work email' ),
		'form_org'           => array( 'Organização', 'Organisation' ),
		'optional'           => array( 'opcional', 'optional' ),
		'form_interest'      => array( 'Assunto', 'Topic' ),
		'interest_collab'    => array( 'Colaboração científica ou industrial', 'Scientific or industry collaboration' ),
		'interest_invest'    => array( 'Investimento', 'Investment' ),
		'interest_press'     => array( 'Imprensa', 'Press' ),
		'interest_general'   => array( 'Pedido geral', 'General enquiry' ),
		'form_message'       => array( 'Mensagem', 'Message' ),
		'form_notice'        => array( 'Por favor, não inclua dados clínicos, informação de saúde nem documentos confidenciais neste primeiro contacto.', 'Please do not include clinical data, health information or confidential documents in this first contact.' ),
		'form_privacy'       => array( 'Li a política de privacidade e aceito que os meus dados sejam usados para responder a este pedido.', 'I have read the privacy policy and agree that my data is used to answer this request.' ),
		'form_submit'        => array( 'Enviar mensagem', 'Send message' ),
		'form_required'      => array( 'obrigatório', 'required' ),
		'err_required'       => array( 'Este campo é obrigatório.', 'This field is required.' ),
		'err_email'          => array( 'Indique um endereço de email válido.', 'Enter a valid email address.' ),
		'err_email_personal' => array( 'Pedimos um email profissional sempre que possível; pode continuar com este endereço.', 'We ask for a work email where possible; you can continue with this address.' ),
		'err_short'          => array( 'A mensagem deve ter pelo menos 20 caracteres.', 'The message must be at least 20 characters long.' ),
		'err_long'           => array( 'A mensagem não pode exceder 3000 caracteres.', 'The message cannot exceed 3000 characters.' ),
		'err_consent'        => array( 'É necessário aceitar para podermos responder.', 'Consent is required so we can reply.' ),
		'err_summary'        => array( 'Corrija os campos assinalados.', 'Please correct the highlighted fields.' ),
		'err_rate'           => array( 'Recebemos vários pedidos seguidos deste dispositivo. Tente novamente dentro de alguns minutos.', 'We received several requests in a row from this device. Please try again in a few minutes.' ),
		'err_server'         => array( 'Não foi possível processar o pedido. Tente novamente ou use o email indicado nesta página.', 'The request could not be processed. Please try again or use the email shown on this page.' ),
		'demo_success_title' => array( 'Demonstração: pedido validado', 'Demonstration: request validated' ),
		'demo_success_text'  => array( 'Esta é uma pré-visualização. Os dados foram validados mas nenhum pedido foi enviado nem guardado.', 'This is a preview. The data was validated but no request was sent or stored.' ),
		'prod_success_title' => array( 'Mensagem enviada', 'Message sent' ),
		'prod_success_text'  => array( 'Obrigado pelo contacto. A equipa da Sea4Us irá analisar o seu pedido.', 'Thank you for getting in touch. The Sea4Us team will review your request.' ),
		'chars_left'         => array( 'caracteres disponíveis', 'characters left' ),
	) );
	return $strings;
}

/**
 * Translate an interface string key into the current (or given) language.
 */
function s4u__( $key, $lang = null ) {
	$lang    = $lang ?: s4u_current_lang();
	$strings = s4u_strings();
	if ( ! isset( $strings[ $key ] ) ) {
		return $key;
	}
	return 'en' === $lang ? $strings[ $key ][1] : $strings[ $key ][0];
}

function s4u_e( $key, $lang = null ) {
	echo esc_html( s4u__( $key, $lang ) );
}

/**
 * Pick the PT or EN variant of an inline pair.
 */
function s4u_pick( $pt, $en, $lang = null ) {
	$lang = $lang ?: s4u_current_lang();
	return 'en' === $lang ? $en : $pt;
}

/**
 * Read a structured field. Language-neutral fields fall back to the linked translation.
 */
function s4u_meta( $post_id, $key, $default = '' ) {
	$value = get_post_meta( $post_id, $key, true );
	if ( ( '' === $value || null === $value || array() === $value ) && s4u_field_is_shared( get_post_type( $post_id ), $key ) ) {
		$tr = (int) get_post_meta( $post_id, '_s4u_tr', true );
		if ( $tr ) {
			$value = get_post_meta( $tr, $key, true );
		}
	}
	return ( '' === $value || null === $value ) ? $default : $value;
}

/**
 * Localised date, e.g. "1 de maio de 2026" / "1 May 2026".
 */
function s4u_date( $date, $lang = null, $format = 'long' ) {
	if ( ! $date ) {
		return '';
	}
	$lang = $lang ?: s4u_current_lang();
	$ts   = is_numeric( $date ) ? (int) $date : strtotime( $date );
	if ( ! $ts ) {
		return esc_html( $date );
	}
	$months_pt = array( 'janeiro', 'fevereiro', 'março', 'abril', 'maio', 'junho', 'julho', 'agosto', 'setembro', 'outubro', 'novembro', 'dezembro' );
	$months_en = array( 'January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December' );
	$d         = (int) gmdate( 'j', $ts );
	$m         = (int) gmdate( 'n', $ts ) - 1;
	$y         = gmdate( 'Y', $ts );
	if ( 'month' === $format ) {
		return 'en' === $lang ? $months_en[ $m ] . ' ' . $y : $months_pt[ $m ] . ' de ' . $y;
	}
	if ( 'short' === $format ) {
		return gmdate( 'd.m.Y', $ts );
	}
	return 'en' === $lang ? "$d {$months_en[$m]} $y" : "$d de {$months_pt[$m]} de $y";
}

/**
 * Human file size.
 */
function s4u_filesize( $bytes ) {
	$bytes = (int) $bytes;
	if ( $bytes <= 0 ) {
		return '';
	}
	$units = array( 'B', 'KB', 'MB', 'GB' );
	$i     = (int) floor( log( $bytes, 1024 ) );
	return number_format_i18n( $bytes / pow( 1024, $i ), $i ? 1 : 0 ) . ' ' . $units[ $i ];
}

/**
 * Format a money amount stored as a plain number (never shows 0 for "unknown").
 */
function s4u_money( $value, $lang = null ) {
	if ( '' === $value || null === $value ) {
		return '';
	}
	$lang = $lang ?: s4u_current_lang();
	$n    = (float) $value;
	return 'en' === $lang
		? '€' . number_format( $n, 2, '.', ',' )
		: number_format( $n, 2, ',', ' ' ) . ' €';
}

/**
 * Attachment ID for a stored source key (importer media mapping).
 */
function s4u_attachment_by_source( $source ) {
	if ( ! $source ) {
		return 0;
	}
	$q = get_posts( array(
		'post_type'      => 'attachment',
		'post_status'    => 'inherit',
		'meta_key'       => '_s4u_source',
		'meta_value'     => $source,
		'fields'         => 'ids',
		'posts_per_page' => 1,
	) );
	return $q ? (int) $q[0] : 0;
}

/**
 * Responsive <img> for an attachment with explicit dimensions.
 */
function s4u_img( $attachment_id, $size = 'large', $attr = array() ) {
	if ( ! $attachment_id ) {
		return '';
	}
	$lang = s4u_current_lang();
	if ( ! isset( $attr['alt'] ) ) {
		$alt = get_post_meta( $attachment_id, 'en' === $lang ? '_s4u_alt_en' : '_wp_attachment_image_alt', true );
		if ( '' === $alt && 'en' === $lang ) {
			$alt = get_post_meta( $attachment_id, '_wp_attachment_image_alt', true );
		}
		$attr['alt'] = $alt;
	}
	if ( ! isset( $attr['loading'] ) ) {
		$attr['loading'] = 'lazy';
	}
	if ( ! isset( $attr['decoding'] ) ) {
		$attr['decoding'] = 'async';
	}
	return wp_get_attachment_image( $attachment_id, $size, false, $attr );
}

/**
 * Credit line stored on an attachment ("João Rodrigues | CHIMERAVISUALS").
 */
function s4u_credit( $attachment_id ) {
	return $attachment_id ? (string) get_post_meta( $attachment_id, '_s4u_credit', true ) : '';
}

/**
 * Translate a list of option keys (status etc.) into labels.
 */
function s4u_option_label( $group, $key, $lang = null ) {
	$lang = $lang ?: s4u_current_lang();
	$sets = s4u_option_sets();
	if ( isset( $sets[ $group ][ $key ] ) ) {
		return 'en' === $lang ? $sets[ $group ][ $key ][1] : $sets[ $group ][ $key ][0];
	}
	return $key;
}

function s4u_option_sets() {
	return apply_filters( 's4u_option_sets', array(
		'project_status' => array(
			'active'    => array( 'Em curso', 'Ongoing' ),
			'completed' => array( 'Concluído', 'Completed' ),
			'unknown'   => array( 'Estado por confirmar', 'Status to be confirmed' ),
		),
		// Development stages exactly as published on the source pipeline (Discovery → Phase 1 CT).
		'stage'          => array(
			'discovery'   => array( 'Descoberta', 'Discovery' ),
			'lead_opt'    => array( 'Otimização de líderes', 'Lead optimisation' ),
			'preclinical' => array( 'Desenvolvimento pré-clínico', 'Preclinical development' ),
			'cta'         => array( 'Pedido de autorização de ensaio clínico (CTA)', 'Clinical trial application (CTA)' ),
			'phase1'      => array( 'Ensaio clínico de fase 1', 'Phase 1 clinical trial' ),
			'unconfirmed' => array( 'Por confirmar', 'To be confirmed' ),
		),
		'review'         => array(
			'pending'  => array( 'Revisão pendente', 'Review pending' ),
			'approved' => array( 'Validado pela Sea4Us', 'Validated by Sea4Us' ),
			'source'   => array( 'Transcrito da fonte pública', 'Transcribed from public source' ),
		),
		'partner_type'   => array(
			'official'      => array( 'Parceiros', 'Partners' ),
			'collaboration' => array( 'Colaborações', 'Collaborations' ),
			'funder'        => array( 'Financiadores e apoios', 'Funders and support' ),
			'network'       => array( 'Redes e membros', 'Networks and memberships' ),
		),
	) );
}

/**
 * Ordered list of pipeline stage keys shown as columns.
 */
function s4u_stage_columns() {
	return array( 'discovery', 'lead_opt', 'preclinical', 'cta', 'phase1' );
}
