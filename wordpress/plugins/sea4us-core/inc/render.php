<?php
/**
 * Component renderer. Markup lives in templates/*.php; a theme may override any component
 * by shipping sea4us/{name}.php. Content and data stay in the plugin.
 */

defined( 'ABSPATH' ) || exit;

function s4u_render( $name, $args = array() ) {
	$name = sanitize_file_name( $name );
	$file = locate_template( 'sea4us/' . $name . '.php' );
	if ( ! $file ) {
		$file = S4U_PATH . 'templates/' . $name . '.php';
	}
	if ( ! is_readable( $file ) ) {
		return '';
	}
	ob_start();
	( static function ( $__file, $args ) {
		include $__file;
	} )( $file, $args );
	return ob_get_clean();
}

/**
 * Queries scoped to the current language.
 */
function s4u_query( $type, $args = array() ) {
	$lang     = $args['lang'] ?? s4u_current_lang();
	unset( $args['lang'] );
	$defaults = array(
		'post_type'      => $type,
		'post_status'    => 'publish',
		'posts_per_page' => -1,
		'no_found_rows'  => true,
		'meta_query'     => array(
			array(
				'key'   => '_s4u_lang',
				'value' => $lang,
			),
		),
	);
	if ( isset( $args['meta_query'] ) ) {
		$args['meta_query'] = array_merge( $defaults['meta_query'], $args['meta_query'] );
	}
	return get_posts( array_merge( $defaults, $args ) );
}

/**
 * Programs ordered for pipeline display.
 */
function s4u_programs( $lang = null ) {
	return s4u_query( 's4u_program', array( 'lang' => $lang, 'orderby' => array( 'menu_order' => 'ASC', 'title' => 'ASC' ) ) );
}

function s4u_stage_index( $stage ) {
	$i = array_search( $stage, s4u_stage_columns(), true );
	return false === $i ? -1 : (int) $i;
}

/**
 * Icons (inline SVG, decorative).
 */
function s4u_icon( $name ) {
	$icons = array(
		'arrow'    => '<path d="M5 12h14M13 6l6 6-6 6"/>',
		'external' => '<path d="M14 4h6v6M20 4l-9 9M18 14v5a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1V7a1 1 0 0 1 1-1h5"/>',
		'download' => '<path d="M12 4v11M7 10l5 5 5-5M5 20h14"/>',
		'search'   => '<circle cx="11" cy="11" r="6.5"/><path d="M16 16l4.5 4.5"/>',
		'close'    => '<path d="M6 6l12 12M18 6L6 18"/>',
		'menu'     => '<path d="M4 7h16M4 12h16M4 17h16"/>',
		'pin'      => '<path d="M12 21s-7-6.2-7-11.5a7 7 0 0 1 14 0C19 14.8 12 21 12 21z"/><circle cx="12" cy="9.5" r="2.5"/>',
		'phone'    => '<path d="M5 4h4l2 5-2.5 1.5a11 11 0 0 0 5 5L15 13l5 2v4a1 1 0 0 1-1 1A16 16 0 0 1 4 5a1 1 0 0 1 1-1z"/>',
		'mail'     => '<rect x="3" y="5" width="18" height="14" rx="1.5"/><path d="M3.5 6l8.5 7 8.5-7"/>',
		'clock'    => '<circle cx="12" cy="12" r="8.5"/><path d="M12 7.5V12l3 2"/>',
		'doc'      => '<path d="M7 3h7l5 5v12a1 1 0 0 1-1 1H7a1 1 0 0 1-1-1V4a1 1 0 0 1 1-1z"/><path d="M14 3v5h5"/>',
		'play'     => '<circle cx="12" cy="12" r="9"/><path d="M10 8.5v7l6-3.5z"/>',
		'linkedin' => '<rect x="3.5" y="3.5" width="17" height="17" rx="2"/><path d="M8 10.5V16M8 7.8v.1M11.5 16v-3.2a2.3 2.3 0 0 1 4.6 0V16M11.5 10.5V16"/>',
		'facebook' => '<path d="M14 8h2.5V4.5H14A3.5 3.5 0 0 0 10.5 8v2.5H8V14h2.5v6.5H14V14h2.5l.5-3.5h-3V8.5A.5.5 0 0 1 14 8z"/>',
		'instagram'=> '<rect x="4" y="4" width="16" height="16" rx="4.5"/><circle cx="12" cy="12" r="3.6"/><path d="M16.8 7.2v.1"/>',
		'youtube'  => '<rect x="3" y="6" width="18" height="12" rx="3.5"/><path d="M10.5 9.5v5l4.2-2.5z"/>',
		'check'    => '<path d="M5 12.5l4.5 4.5L19 7.5"/>',
		'info'     => '<circle cx="12" cy="12" r="9"/><path d="M12 11v5.5M12 7.6v.1"/>',
	);
	if ( ! isset( $icons[ $name ] ) ) {
		return '';
	}
	return '<svg class="s4u-icon s4u-icon--' . esc_attr( $name ) . '" viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false">' . $icons[ $name ] . '</svg>';
}

/**
 * Document data for a s4u_document post.
 */
function s4u_document_data( $doc_id ) {
	$file = (int) s4u_meta( $doc_id, 'file' );
	$url  = $file ? wp_get_attachment_url( $file ) : s4u_meta( $doc_id, 'external_url' );
	$path = $file ? get_attached_file( $file ) : '';
	$ext  = $url ? strtoupper( pathinfo( wp_parse_url( $url, PHP_URL_PATH ), PATHINFO_EXTENSION ) ) : '';
	return array(
		'id'    => $doc_id,
		'title' => get_the_title( $doc_id ),
		'url'   => $url,
		'ext'   => $ext,
		'size'  => ( $path && file_exists( $path ) ) ? s4u_filesize( filesize( $path ) ) : '',
		'date'  => s4u_meta( $doc_id, 'doc_date' ),
		'type'  => s4u_meta( $doc_id, 'doc_type', 'other' ),
		'desc'  => get_the_excerpt( $doc_id ),
		'ext_link' => ! $file,
	);
}

function s4u_ids( $csv ) {
	return array_values( array_filter( array_map( 'absint', explode( ',', (string) $csv ) ) ) );
}

/**
 * Translate referenced post IDs to the current language where a translation exists.
 */
function s4u_localized_ids( $csv, $lang = null ) {
	$lang = $lang ?: s4u_current_lang();
	$out  = array();
	foreach ( s4u_ids( $csv ) as $id ) {
		$t = s4u_get_translation( $id, $lang );
		if ( $t ) {
			$out[] = $t;
		} elseif ( 'publish' === get_post_status( $id ) ) {
			$out[] = $id;
		}
	}
	return array_values( array_unique( $out ) );
}
