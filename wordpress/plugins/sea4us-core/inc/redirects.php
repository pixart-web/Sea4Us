<?php
/**
 * Legacy URL map (old sea4us.pt paths → new paths). Stored in option `s4u_redirects`
 * by the importer from data/url-map.csv. Applied only when enabled at launch, and only
 * for requests that would otherwise be 404 — never a blanket redirect to the homepage.
 */

defined( 'ABSPATH' ) || exit;

add_action( 'template_redirect', function () {
	$opts = get_option( 's4u_settings', array() );
	if ( empty( $opts['redirects_enabled'] ) || ! is_404() ) {
		return;
	}
	$map  = get_option( 's4u_redirects', array() );
	$path = '/' . trim( (string) wp_parse_url( $_SERVER['REQUEST_URI'] ?? '', PHP_URL_PATH ), '/' ) . '/';
	$path = strtolower( rawurldecode( $path ) );
	if ( isset( $map[ $path ] ) && $map[ $path ] ) {
		wp_safe_redirect( home_url( $map[ $path ] ), 301 );
		exit;
	}
}, 1 );
