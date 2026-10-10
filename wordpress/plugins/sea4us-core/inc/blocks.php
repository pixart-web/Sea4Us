<?php
/**
 * Dynamic blocks. Editors place and configure them in the block editor; the data comes
 * from the structured content types, so a fact is edited once and shown everywhere.
 */

defined( 'ABSPATH' ) || exit;

function s4u_block_defs() {
	$str  = array( 'type' => 'string', 'default' => '' );
	$bool = fn( $d = false ) => array( 'type' => 'boolean', 'default' => $d );
	$int  = fn( $d = 0 ) => array( 'type' => 'integer', 'default' => $d );

	return array(
		'hero'             => array( 'Herói da página inicial', array(
			'eyebrow' => $str, 'title' => $str, 'text' => $str,
			'cta1Label' => $str, 'cta1Url' => $str, 'cta2Label' => $str, 'cta2Url' => $str,
			'imageId' => $int(), 'secondaryImageId' => $int(), 'note' => $str,
		) ),
		'page-hero'        => array( 'Cabeçalho de página', array(
			'eyebrow' => $str, 'title' => $str, 'text' => $str, 'imageId' => $int(), 'tone' => array( 'type' => 'string', 'default' => 'dark' ),
		) ),
		'featured-program' => array( 'Programa em destaque', array( 'programId' => $int(), 'eyebrow' => $str ) ),
		'pipeline'         => array( 'Pipeline', array( 'compact' => $bool(), 'showLegend' => $bool( true ) ) ),
		'news'             => array( 'Notícias', array( 'count' => $int( 3 ), 'archive' => $bool(), 'topic' => $str ) ),
		'milestones'       => array( 'Marcos', array( 'count' => $int( 5 ) ) ),
		'projects'         => array( 'Projetos', array( 'count' => $int( 0 ), 'index' => $bool( true ), 'highlightOnly' => $bool() ) ),
		'team'             => array( 'Equipa', array( 'count' => $int( 0 ), 'compact' => $bool() ) ),
		'partners'         => array( 'Parceiros', array( 'type' => $str, 'heading' => $str ) ),
		'documents'        => array( 'Documentos', array( 'docType' => $str ) ),
		'locations'        => array( 'Localizações', array() ),
		'contact-details'  => array( 'Contactos', array( 'partnering' => $bool() ) ),
		'contact-form'     => array( 'Formulário de contacto', array( 'interest' => $str ) ),
		'search'           => array( 'Pesquisa', array() ),
		'funding-strip'    => array( 'Bloco de cofinanciamento', array() ),
		'cta'              => array( 'Convite à colaboração', array( 'title' => $str, 'text' => $str, 'cta1Label' => $str, 'cta1Url' => $str, 'cta2Label' => $str, 'cta2Url' => $str ) ),
	);
}

add_action( 'init', function () {
	wp_register_script(
		's4u-blocks-editor',
		S4U_URL . 'assets/blocks-editor.js',
		array( 'wp-blocks', 'wp-element', 'wp-components', 'wp-block-editor', 'wp-server-side-render', 'wp-i18n', 'wp-data' ),
		S4U_VERSION,
		true
	);
	$editor_defs = array();
	foreach ( s4u_block_defs() as $name => $def ) {
		register_block_type( 'sea4us/' . $name, array(
			'api_version'     => 3,
			'title'           => $def[0],
			'category'        => 'sea4us',
			'attributes'      => $def[1],
			'editor_script'   => 's4u-blocks-editor',
			'render_callback' => function ( $attrs ) use ( $name ) {
				return s4u_render( 'block-' . $name, array( 'a' => $attrs ) );
			},
		) );
		$editor_defs[ $name ] = array( 'title' => $def[0], 'attributes' => $def[1] );
	}
	wp_localize_script( 's4u-blocks-editor', 'S4U_BLOCKS', $editor_defs );
} );

add_filter( 'block_categories_all', function ( $cats ) {
	array_unshift( $cats, array( 'slug' => 'sea4us', 'title' => 'Sea4Us' ) );
	return $cats;
} );
