<?php
/**
 * Content types. Institutional content stays in Pages.
 */

defined( 'ABSPATH' ) || exit;

function s4u_register_post_types() {
	$common = array(
		'public'        => true,
		'show_in_rest'  => true,
		'has_archive'   => false,
		'rewrite'       => false, // URLs are built per language in i18n.php.
		'menu_position' => 21,
		'supports'      => array( 'title', 'editor', 'excerpt', 'thumbnail', 'revisions', 'custom-fields', 'page-attributes' ),
	);

	$types = array(
		's4u_program'  => array( 'Programas', 'Programa', 'dashicons-chart-line', array() ),
		's4u_project'  => array( 'Projetos', 'Projeto', 'dashicons-portfolio', array() ),
		's4u_news'     => array( 'Notícias', 'Notícia', 'dashicons-megaphone', array( 'supports' => array( 'title', 'editor', 'excerpt', 'thumbnail', 'revisions', 'custom-fields', 'author' ) ) ),
		's4u_person'   => array( 'Pessoas', 'Pessoa', 'dashicons-groups', array() ),
		's4u_partner'  => array( 'Parceiros', 'Parceiro', 'dashicons-networking', array( 'publicly_queryable' => false, 'exclude_from_search' => true ) ),
		's4u_document' => array( 'Documentos', 'Documento', 'dashicons-media-document', array( 'publicly_queryable' => false ) ),
	);

	foreach ( $types as $type => $def ) {
		list( $plural, $singular, $icon, $extra ) = $def;
		register_post_type( $type, array_merge( $common, array(
			'labels'    => array(
				'name'               => $plural,
				'singular_name'      => $singular,
				'add_new_item'       => 'Adicionar ' . strtolower( $singular ),
				'edit_item'          => 'Editar ' . strtolower( $singular ),
				'new_item'           => 'Novo registo',
				'view_item'          => 'Ver ' . strtolower( $singular ),
				'search_items'       => 'Pesquisar ' . strtolower( $plural ),
				'not_found'          => 'Sem resultados',
				'all_items'          => 'Todos',
				'menu_name'          => $plural,
			),
			'menu_icon' => $icon,
		), $extra ) );
	}
}
add_action( 'init', 's4u_register_post_types', 5 );

/**
 * Editorial revisions are kept for structured fields too.
 */
add_filter( 'wp_post_revision_meta_keys', function ( $keys ) {
	return array_merge( $keys, array( 'stage', 'stage_source_date', 'stage_source_url', 'review_status', 'latest_milestone' ) );
} );
