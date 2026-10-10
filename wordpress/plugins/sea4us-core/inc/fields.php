<?php
/**
 * Structured fields per content type, with a small generic metabox UI.
 *
 * `shared` fields are language-neutral (dates, codes, stage, amounts, files): they are kept
 * identical in both translations, so the S072 stage (for instance) is edited in one place.
 */

defined( 'ABSPATH' ) || exit;

function s4u_fields() {
	$review = array(
		'review_status' => array( 'label' => 'Estado de revisão', 'type' => 'select', 'options' => 'review', 'shared' => true, 'default' => 'pending' ),
		'source_url'    => array( 'label' => 'Fonte (URL)', 'type' => 'url', 'shared' => true ),
		'source_date'   => array( 'label' => 'Data da fonte', 'type' => 'date', 'shared' => true ),
		'legacy_url'    => array( 'label' => 'URL antigo (migração)', 'type' => 'url', 'help' => 'Usado no mapa de redirecionamentos.' ),
	);

	return apply_filters( 's4u_fields', array(
		's4u_program'  => array_merge( array(
			'code'               => array( 'label' => 'Código', 'type' => 'text', 'shared' => true ),
			'nature'             => array( 'label' => 'Natureza do programa', 'type' => 'text', 'help' => 'Ex.: “Candidato em desenvolvimento pré-clínico”.' ),
			'area'               => array( 'label' => 'Área terapêutica', 'type' => 'text' ),
			'indication'         => array( 'label' => 'Indicação (texto público)', 'type' => 'text' ),
			'stage'              => array( 'label' => 'Estádio confirmado', 'type' => 'select', 'options' => 'stage', 'shared' => true, 'default' => 'unconfirmed', 'help' => 'Fonte única: usado no pipeline, na homepage e na ficha.' ),
			'stage_source_date'  => array( 'label' => 'Data da informação do estádio', 'type' => 'date', 'shared' => true ),
			'stage_source_url'   => array( 'label' => 'Fonte do estádio', 'type' => 'url', 'shared' => true ),
			'latest_milestone'   => array( 'label' => 'Último marco confirmado', 'type' => 'textarea' ),
			'milestone_date'     => array( 'label' => 'Data do marco', 'type' => 'date', 'shared' => true ),
			'milestone_source'   => array( 'label' => 'Fonte do marco', 'type' => 'url', 'shared' => true ),
			'qualification'      => array( 'label' => 'Qualificação científica visível', 'type' => 'textarea', 'help' => 'Ex.: “Composto em investigação; não aprovado para uso clínico.”' ),
			'featured'           => array( 'label' => 'Destacar na homepage', 'type' => 'checkbox', 'shared' => true ),
			'related_news'       => array( 'label' => 'Notícias relacionadas', 'type' => 'posts', 'post_type' => 's4u_news' ),
		), $review ),
		's4u_project'  => array_merge( array(
			'code'               => array( 'label' => 'Código do projeto', 'type' => 'text', 'shared' => true ),
			'full_title'         => array( 'label' => 'Designação completa', 'type' => 'textarea' ),
			'status'             => array( 'label' => 'Estado', 'type' => 'select', 'options' => 'project_status', 'shared' => true, 'default' => 'unknown' ),
			'status_note'        => array( 'label' => 'Nota sobre o estado', 'type' => 'text', 'help' => 'Ex.: “Estado conforme a fonte, recolhido a 09/10/2026”.' ),
			'funding_programme'  => array( 'label' => 'Programa de financiamento', 'type' => 'text', 'shared' => true, 'help' => 'Valor real usado no filtro (ex.: “Portugal 2020 · CRESC Algarve 2020”).' ),
			'start_date'         => array( 'label' => 'Início', 'type' => 'text', 'shared' => true ),
			'end_date'           => array( 'label' => 'Fim', 'type' => 'text', 'shared' => true ),
			'lead'               => array( 'label' => 'Promotor / líder', 'type' => 'text' ),
			'entities'           => array( 'label' => 'Entidades (uma por linha)', 'type' => 'textarea' ),
			'total_cost'         => array( 'label' => 'Custo total elegível (€)', 'type' => 'number', 'shared' => true, 'help' => 'Deixar vazio se não publicado. Nunca usar 0 como “desconhecido”.' ),
			'funding_amount'     => array( 'label' => 'Apoio financeiro (€)', 'type' => 'number', 'shared' => true, 'help' => 'Deixar vazio se não publicado.' ),
			'funding_breakdown'  => array( 'label' => 'Detalhe do apoio (como publicado)', 'type' => 'textarea' ),
			'funding_logos'      => array( 'label' => 'Bloco de financiamento (imagens)', 'type' => 'attachments', 'shared' => true, 'help' => 'Manter proporções e ordem originais; não recolorir.' ),
			'display_check'      => array( 'label' => 'Requisitos de publicitação a confirmar', 'type' => 'checkbox', 'shared' => true ),
			'documents'          => array( 'label' => 'Documentos', 'type' => 'posts', 'post_type' => 's4u_document', 'shared' => true ),
			'programs'           => array( 'label' => 'Programas relacionados', 'type' => 'posts', 'post_type' => 's4u_program', 'shared' => true ),
			'highlight'          => array( 'label' => 'Destacar na homepage', 'type' => 'checkbox', 'shared' => true ),
		), $review ),
		's4u_news'     => array_merge( array(
			'author_name'        => array( 'label' => 'Autoria (se publicada)', 'type' => 'text', 'shared' => true ),
			'topic'              => array( 'label' => 'Tipo', 'type' => 'select', 'options' => 'news_topic', 'shared' => true, 'default' => 'company' ),
			'external_url'       => array( 'label' => 'Ligação para o original (imprensa)', 'type' => 'url', 'shared' => true ),
			'external_source'    => array( 'label' => 'Meio / fonte externa', 'type' => 'text', 'shared' => true ),
			'video_url'          => array( 'label' => 'Vídeo (URL)', 'type' => 'url', 'shared' => true ),
			'gallery'            => array( 'label' => 'Galeria (imagens)', 'type' => 'attachments', 'shared' => true ),
			'attachments'        => array( 'label' => 'Anexos', 'type' => 'posts', 'post_type' => 's4u_document', 'shared' => true ),
			'milestone'          => array( 'label' => 'Marco histórico confirmado', 'type' => 'checkbox', 'shared' => true ),
			'milestone_label'    => array( 'label' => 'Texto curto do marco', 'type' => 'text' ),
			'image_credit'       => array( 'label' => 'Crédito da imagem principal', 'type' => 'text', 'shared' => true ),
		), $review ),
		's4u_person'   => array_merge( array(
			'role'               => array( 'label' => 'Função (como publicada)', 'type' => 'text' ),
			'group'              => array( 'label' => 'Grupo', 'type' => 'text', 'help' => 'Ex.: “Equipa”, “Cofundadores”.' ),
			'cofounder'          => array( 'label' => 'Cofundador(a)', 'type' => 'checkbox', 'shared' => true, 'help' => 'Histórico — não implica cargo executivo atual.' ),
			'links'              => array( 'label' => 'Ligações públicas (Rótulo | URL, uma por linha)', 'type' => 'textarea', 'shared' => true ),
			'photo_credit'       => array( 'label' => 'Crédito da fotografia', 'type' => 'text', 'shared' => true ),
			'photo_check'        => array( 'label' => 'Associação foto/nome a confirmar', 'type' => 'checkbox', 'shared' => true ),
		), $review ),
		's4u_partner'  => array(
			'type'               => array( 'label' => 'Tipo', 'type' => 'select', 'options' => 'partner_type', 'shared' => true, 'default' => 'official' ),
			'url'                => array( 'label' => 'Website', 'type' => 'url', 'shared' => true ),
			'source_url'         => array( 'label' => 'Fonte (URL)', 'type' => 'url', 'shared' => true ),
		),
		's4u_document' => array(
			'file'               => array( 'label' => 'Ficheiro', 'type' => 'attachment', 'shared' => true ),
			'doc_type'           => array( 'label' => 'Tipo de documento', 'type' => 'select', 'options' => 'doc_type', 'shared' => true, 'default' => 'other' ),
			'doc_date'           => array( 'label' => 'Data', 'type' => 'date', 'shared' => true ),
			'external_url'       => array( 'label' => 'URL externo (se não houver ficheiro)', 'type' => 'url', 'shared' => true ),
			'source_url'         => array( 'label' => 'Página de origem', 'type' => 'url', 'shared' => true ),
		),
	) );
}

add_filter( 's4u_option_sets', function ( $sets ) {
	$sets['news_topic'] = array(
		'company' => array( 'Notícias da empresa', 'Company news' ),
		'press'   => array( 'Na imprensa', 'In the press' ),
		'funding' => array( 'Projetos e financiamento', 'Projects and funding' ),
		'events'  => array( 'Eventos e prémios', 'Events and awards' ),
	);
	$sets['doc_type']   = array(
		'funding'      => array( 'Ficha de projeto / financiamento', 'Project / funding sheet' ),
		'transparency' => array( 'Transparência na investigação', 'Research transparency' ),
		'press'        => array( 'Imprensa', 'Press' ),
		'legal'        => array( 'Legal', 'Legal' ),
		'other'        => array( 'Documento', 'Document' ),
	);
	return $sets;
} );

function s4u_field_is_shared( $type, $key ) {
	$f = s4u_fields();
	return ! empty( $f[ $type ][ $key ]['shared'] );
}

/**
 * Register meta for REST/editor use.
 */
add_action( 'init', function () {
	foreach ( s4u_fields() as $type => $fields ) {
		foreach ( $fields as $key => $def ) {
			$is_num = in_array( $def['type'], array( 'attachment' ), true );
			register_post_meta( $type, $key, array(
				'type'              => $is_num ? 'integer' : 'string',
				'single'            => true,
				'show_in_rest'      => true,
				'sanitize_callback' => function ( $v ) use ( $def ) {
					return s4u_sanitize_field( $v, $def );
				},
				'auth_callback'     => function () {
					return current_user_can( 'edit_posts' );
				},
			) );
		}
	}
	foreach ( s4u_translatable_types() as $type ) {
		register_post_meta( $type, '_s4u_lang', array( 'type' => 'string', 'single' => true, 'show_in_rest' => false ) );
		register_post_meta( $type, '_s4u_tr', array( 'type' => 'integer', 'single' => true, 'show_in_rest' => false ) );
	}
}, 30 );

function s4u_sanitize_field( $v, $def ) {
	switch ( $def['type'] ) {
		case 'url':
			return esc_url_raw( trim( (string) $v ) );
		case 'number':
			$v = trim( str_replace( array( ' ', ',' ), array( '', '.' ), (string) $v ) );
			return ( '' === $v || ! is_numeric( $v ) ) ? '' : (string) (float) $v;
		case 'checkbox':
			return $v ? '1' : '';
		case 'attachment':
			return absint( $v );
		case 'attachments':
		case 'posts':
			$ids = array_filter( array_map( 'absint', is_array( $v ) ? $v : explode( ',', (string) $v ) ) );
			return implode( ',', $ids );
		case 'select':
			$sets = s4u_option_sets();
			return isset( $sets[ $def['options'] ][ $v ] ) ? $v : ( $def['default'] ?? '' );
		case 'date':
			$v = trim( (string) $v );
			return preg_match( '/^\d{4}(-\d{2}(-\d{2})?)?$/', $v ) ? $v : '';
		case 'textarea':
			return sanitize_textarea_field( (string) $v );
		default:
			return sanitize_text_field( (string) $v );
	}
}

/**
 * Metabox UI.
 */
add_action( 'add_meta_boxes', function () {
	foreach ( array_keys( s4u_fields() ) as $type ) {
		add_meta_box( 's4u_fields', __( 'Dados estruturados', 'sea4us-core' ), 's4u_fields_metabox', $type, 'normal', 'high' );
	}
} );

add_action( 'admin_enqueue_scripts', function ( $hook ) {
	if ( in_array( $hook, array( 'post.php', 'post-new.php' ), true ) ) {
		wp_enqueue_media();
		wp_add_inline_script( 'jquery-core', "jQuery(function($){\$(document).on('click','.s4u-pick',function(e){e.preventDefault();var t=\$(this),inp=\$('#'+t.data('target')),multi=!!t.data('multiple');var f=wp.media({multiple:multi});f.on('select',function(){var ids=f.state().get('selection').map(function(a){return a.id});inp.val(multi?(inp.val()?inp.val()+',':'')+ids.join(','):ids[0]);});f.open();});});" );
	}
} );

function s4u_fields_metabox( $post ) {
	wp_nonce_field( 's4u_fields_save', 's4u_fields_nonce' );
	$fields = s4u_fields()[ $post->post_type ] ?? array();
	$sets   = s4u_option_sets();
	echo '<table class="form-table" role="presentation"><tbody>';
	foreach ( $fields as $key => $def ) {
		$id    = 's4u_f_' . $key;
		$value = get_post_meta( $post->ID, $key, true );
		if ( '' === $value && isset( $def['default'] ) ) {
			$value = $def['default'];
		}
		echo '<tr><th scope="row"><label for="' . esc_attr( $id ) . '">' . esc_html( $def['label'] ) . '</label>';
		if ( ! empty( $def['shared'] ) ) {
			echo '<br><small style="font-weight:normal;color:#646970">' . esc_html__( 'partilhado PT/EN', 'sea4us-core' ) . '</small>';
		}
		echo '</th><td>';
		$name = 's4u_fields[' . $key . ']';
		switch ( $def['type'] ) {
			case 'textarea':
				printf( '<textarea class="large-text" rows="4" id="%s" name="%s">%s</textarea>', esc_attr( $id ), esc_attr( $name ), esc_textarea( $value ) );
				break;
			case 'select':
				printf( '<select id="%s" name="%s">', esc_attr( $id ), esc_attr( $name ) );
				foreach ( $sets[ $def['options'] ] as $k => $labels ) {
					printf( '<option value="%s"%s>%s</option>', esc_attr( $k ), selected( $value, $k, false ), esc_html( $labels[0] ) );
				}
				echo '</select>';
				break;
			case 'checkbox':
				printf( '<input type="checkbox" id="%s" name="%s" value="1"%s>', esc_attr( $id ), esc_attr( $name ), checked( $value, '1', false ) );
				break;
			case 'attachment':
			case 'attachments':
				printf( '<input type="text" class="regular-text" id="%s" name="%s" value="%s"> <button class="button s4u-pick" data-target="%s" data-multiple="%d">%s</button>', esc_attr( $id ), esc_attr( $name ), esc_attr( $value ), esc_attr( $id ), 'attachments' === $def['type'] ? 1 : 0, esc_html__( 'Selecionar', 'sea4us-core' ) );
				break;
			case 'posts':
				$selected = array_filter( array_map( 'absint', explode( ',', (string) $value ) ) );
				$opts     = get_posts( array( 'post_type' => $def['post_type'], 'posts_per_page' => 500, 'post_status' => 'any', 'orderby' => 'title', 'order' => 'ASC' ) );
				printf( '<select multiple size="6" style="min-width:60%%" id="%s" name="%s[]">', esc_attr( $id ), esc_attr( $name ) );
				foreach ( $opts as $o ) {
					printf( '<option value="%d"%s>[%s] %s</option>', (int) $o->ID, in_array( (int) $o->ID, $selected, true ) ? ' selected' : '', esc_html( strtoupper( s4u_post_lang( $o ) ) ), esc_html( $o->post_title ) );
				}
				echo '</select>';
				break;
			default:
				$t = 'url' === $def['type'] ? 'url' : 'text';
				printf( '<input type="%s" class="regular-text" id="%s" name="%s" value="%s"%s>', esc_attr( $t ), esc_attr( $id ), esc_attr( $name ), esc_attr( $value ), 'date' === $def['type'] ? ' placeholder="AAAA-MM-DD"' : '' );
		}
		if ( ! empty( $def['help'] ) ) {
			echo '<p class="description">' . esc_html( $def['help'] ) . '</p>';
		}
		echo '</td></tr>';
	}
	echo '</tbody></table>';
}

add_action( 'save_post', function ( $post_id, $post ) {
	if ( ! isset( $_POST['s4u_fields_nonce'] ) || ! wp_verify_nonce( sanitize_key( $_POST['s4u_fields_nonce'] ), 's4u_fields_save' ) ) {
		return;
	}
	if ( ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) || ! current_user_can( 'edit_post', $post_id ) ) {
		return;
	}
	$fields = s4u_fields()[ $post->post_type ] ?? array();
	$input  = isset( $_POST['s4u_fields'] ) && is_array( $_POST['s4u_fields'] ) ? wp_unslash( $_POST['s4u_fields'] ) : array(); // phpcs:ignore -- sanitised per field below.
	foreach ( $fields as $key => $def ) {
		$raw = $input[ $key ] ?? '';
		update_post_meta( $post_id, $key, s4u_sanitize_field( $raw, $def ) );
	}
	s4u_sync_shared_fields( $post_id );
}, 20, 2 );

/**
 * Copy language-neutral fields to the linked translation.
 * `posts` fields are mapped to the translations of the referenced posts.
 */
function s4u_sync_shared_fields( $post_id ) {
	$tr = (int) get_post_meta( $post_id, '_s4u_tr', true );
	if ( ! $tr ) {
		return;
	}
	$type     = get_post_type( $post_id );
	$fields   = s4u_fields()[ $type ] ?? array();
	$tr_lang  = s4u_post_lang( $tr );
	foreach ( $fields as $key => $def ) {
		if ( empty( $def['shared'] ) ) {
			continue;
		}
		$value = get_post_meta( $post_id, $key, true );
		if ( 'posts' === $def['type'] ) {
			$mapped = array();
			foreach ( array_filter( array_map( 'absint', explode( ',', (string) $value ) ) ) as $ref ) {
				$mapped[] = s4u_get_translation( $ref, $tr_lang ) ?: $ref;
			}
			$value = implode( ',', $mapped );
		}
		update_post_meta( $tr, $key, $value );
	}
	if ( has_post_thumbnail( $post_id ) && ! has_post_thumbnail( $tr ) ) {
		set_post_thumbnail( $tr, get_post_thumbnail_id( $post_id ) );
	}
}

/**
 * Admin list: show the program stage and review status where it matters.
 */
add_filter( 'manage_s4u_program_posts_columns', function ( $cols ) {
	$cols['s4u_stage'] = 'Estádio';
	$cols['s4u_rev']   = 'Revisão';
	return $cols;
} );
add_action( 'manage_s4u_program_posts_custom_column', function ( $col, $id ) {
	if ( 's4u_stage' === $col ) {
		echo esc_html( s4u_option_label( 'stage', s4u_meta( $id, 'stage', 'unconfirmed' ), 'pt' ) );
	}
	if ( 's4u_rev' === $col ) {
		echo esc_html( s4u_option_label( 'review', s4u_meta( $id, 'review_status', 'pending' ), 'pt' ) );
	}
}, 10, 2 );

/**
 * Photo credit on each image, editable in the Media Library ("Crédito").
 */
add_filter( 'attachment_fields_to_edit', function ( $fields, $post ) {
	$fields['s4u_credit'] = array(
		'label' => __( 'Crédito', 'sea4us-core' ),
		'input' => 'text',
		'value' => (string) get_post_meta( $post->ID, '_s4u_credit', true ),
		'helps' => __( 'Ex.: © João Rodrigues / CHIMERAVISUALS. Mostrado por baixo da imagem.', 'sea4us-core' ),
	);
	return $fields;
}, 10, 2 );

add_filter( 'attachment_fields_to_save', function ( $post, $attachment ) {
	if ( isset( $attachment['s4u_credit'] ) && current_user_can( 'edit_post', $post['ID'] ) ) {
		update_post_meta( $post['ID'], '_s4u_credit', sanitize_text_field( $attachment['s4u_credit'] ) );
	}
	return $post;
}, 10, 2 );
