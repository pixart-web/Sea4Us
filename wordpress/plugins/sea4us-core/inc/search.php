<?php
/**
 * Search: server-side results (works without JavaScript) plus a small JSON index per
 * language for instant results in the browser.
 */

defined( 'ABSPATH' ) || exit;

function s4u_type_label( $type, $lang = null ) {
	$labels = array(
		'page'        => array( 'Página', 'Page' ),
		's4u_news'    => array( 'Notícia', 'News' ),
		's4u_project' => array( 'Projeto', 'Project' ),
		's4u_program' => array( 'Programa', 'Programme' ),
		's4u_person'  => array( 'Equipa', 'Team' ),
	);
	return isset( $labels[ $type ] ) ? s4u_pick( $labels[ $type ][0], $labels[ $type ][1], $lang ) : '';
}

function s4u_searchable_types() {
	return array( 'page', 's4u_program', 's4u_project', 's4u_news', 's4u_person' );
}

/**
 * Server-side search scoped to a language.
 */
function s4u_search( $q, $lang ) {
	$q = trim( $q );
	if ( mb_strlen( $q ) < 2 ) {
		return array();
	}
	$ids = get_posts( array(
		'post_type'      => s4u_searchable_types(),
		'post_status'    => 'publish',
		's'              => $q,
		'posts_per_page' => 200,
		'fields'         => 'ids',
	) );
	// Also match structured fields such as project codes and roles.
	$meta_ids = get_posts( array(
		'post_type'      => s4u_searchable_types(),
		'post_status'    => 'publish',
		'posts_per_page' => 50,
		'fields'         => 'ids',
		'meta_query'     => array(
			'relation' => 'OR',
			array( 'key' => 'code', 'value' => $q, 'compare' => 'LIKE' ),
			array( 'key' => 'role', 'value' => $q, 'compare' => 'LIKE' ),
			array( 'key' => 'funding_programme', 'value' => $q, 'compare' => 'LIKE' ),
		),
	) );
	$out = array();
	foreach ( array_unique( array_merge( $ids, $meta_ids ) ) as $id ) {
		if ( s4u_post_lang( $id ) === $lang && ! in_array( (int) $id, array( s4u_lang_root( 'pt' ), s4u_lang_root( 'en' ) ), true ) ) {
			$out[] = (int) $id;
		}
	}
	return $out;
}

function s4u_search_index( $lang ) {
	$posts = get_posts( array(
		'post_type'      => s4u_searchable_types(),
		'post_status'    => 'publish',
		'posts_per_page' => -1,
	) );
	$rows  = array();
	foreach ( $posts as $p ) {
		if ( s4u_post_lang( $p ) !== $lang || in_array( (int) $p->ID, array( s4u_lang_root( 'pt' ), s4u_lang_root( 'en' ) ), true ) ) {
			continue;
		}
		$text = wp_strip_all_tags( strip_shortcodes( excerpt_remove_blocks( $p->post_content ) ) );
		$text = trim( preg_replace( '/\s+/', ' ', $text ) );
		$extra = trim( implode( ' ', array_filter( array( s4u_meta( $p->ID, 'code' ), s4u_meta( $p->ID, 'role' ), s4u_meta( $p->ID, 'funding_programme' ), s4u_meta( $p->ID, 'indication' ) ) ) ) );
		$rows[] = array(
			't' => html_entity_decode( get_the_title( $p ), ENT_QUOTES ),
			'u' => wp_make_link_relative( get_permalink( $p ) ),
			'k' => s4u_type_label( $p->post_type, $lang ),
			'd' => 's4u_news' === $p->post_type ? s4u_date( $p->post_date, $lang ) : '',
			'x' => mb_substr( $p->post_excerpt ?: $text, 0, 220 ),
			'b' => mb_substr( $extra . ' ' . $text, 0, 4000 ),
		);
	}
	return $rows;
}

function s4u_write_search_indexes() {
	$up  = wp_upload_dir();
	$dir = trailingslashit( $up['basedir'] ) . 'sea4us';
	wp_mkdir_p( $dir );
	foreach ( array_keys( s4u_languages() ) as $lang ) {
		file_put_contents( $dir . '/search-' . $lang . '.json', wp_json_encode( s4u_search_index( $lang ), JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES ) ); // phpcs:ignore WordPress.WP.AlternativeFunctions
	}
}

function s4u_search_index_url( $lang ) {
	$up = wp_upload_dir();
	return wp_make_link_relative( trailingslashit( $up['baseurl'] ) . 'sea4us/search-' . $lang . '.json' );
}

add_action( 'save_post', function ( $post_id, $post ) {
	if ( wp_is_post_revision( $post_id ) || ! in_array( $post->post_type, s4u_searchable_types(), true ) ) {
		return;
	}
	if ( ! wp_next_scheduled( 's4u_rebuild_search' ) ) {
		wp_schedule_single_event( time() + 30, 's4u_rebuild_search' );
	}
}, 50, 2 );
add_action( 's4u_rebuild_search', 's4u_write_search_indexes' );
