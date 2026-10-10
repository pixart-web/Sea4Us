<?php
/**
 * Sea4Us theme bootstrap. Presentation only: content types, data and components come from
 * the Sea4Us Core plugin, so switching theme never loses content.
 */

defined( 'ABSPATH' ) || exit;

define( 'S4U_THEME_VERSION', '1.0.0' );

add_action( 'after_setup_theme', function () {
	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'responsive-embeds' );
	add_theme_support( 'html5', array( 'search-form', 'gallery', 'caption', 'style', 'script', 'navigation-widgets' ) );
	add_theme_support( 'editor-styles' );
	add_editor_style( 'assets/css/main.css' );
	remove_theme_support( 'core-block-patterns' );

	register_nav_menus( array(
		'primary_pt' => 'Menu principal (PT)',
		'primary_en' => 'Main menu (EN)',
		'footer_pt'  => 'Rodapé (PT)',
		'footer_en'  => 'Footer (EN)',
		'legal_pt'   => 'Legal (PT)',
		'legal_en'   => 'Legal (EN)',
	) );

	add_image_size( 's4u-wide', 2000, 1200, false );
	add_image_size( 's4u-card', 720, 480, true );
	add_image_size( 's4u-portrait', 600, 750, true );
} );

add_filter( 'image_size_names_choose', function ( $sizes ) {
	return array_merge( $sizes, array( 's4u-wide' => 'Larga' ) );
} );

/**
 * The plugin is required; show a clear notice instead of failing silently.
 */
function s4u_theme_ready() {
	return function_exists( 's4u_render' );
}

add_action( 'admin_notices', function () {
	if ( ! s4u_theme_ready() ) {
		echo '<div class="notice notice-error"><p>O tema Sea4Us requer o plugin <strong>Sea4Us Core</strong> ativo.</p></div>';
	}
} );

add_action( 'wp_enqueue_scripts', function () {
	$dir = get_template_directory_uri();
	wp_enqueue_style( 's4u-main', $dir . '/assets/css/main.css', array(), S4U_THEME_VERSION );
	wp_enqueue_script( 's4u-main', $dir . '/assets/js/main.js', array(), S4U_THEME_VERSION, array( 'strategy' => 'defer', 'in_footer' => true ) );
	// Core block library CSS is only needed for core blocks used in content; keep it, drop global extras.
	wp_dequeue_style( 'classic-theme-styles' );
} );

/**
 * Preload the two most used font files.
 */
add_action( 'wp_head', function () {
	$dir = get_template_directory_uri() . '/assets/fonts/';
	echo '<link rel="preload" href="' . esc_url( $dir . 'manrope-latin-wght-normal.woff2' ) . '" as="font" type="font/woff2" crossorigin>' . "\n";
	echo '<link rel="preload" href="' . esc_url( $dir . 'inter-latin-wght-normal.woff2' ) . '" as="font" type="font/woff2" crossorigin>' . "\n";
}, 1 );

add_filter( 's4u_logo_url', function () {
	return get_template_directory_uri() . '/assets/img/logo-sea4us.png';
} );

add_filter( 's4u_default_og_image', function () {
	return get_template_directory_uri() . '/assets/img/og-default.jpg';
} );

/**
 * Menus per language.
 */
function s4u_theme_menu( $base, $class, $depth = 2 ) {
	$lang     = function_exists( 's4u_current_lang' ) ? s4u_current_lang() : 'pt';
	$location = $base . '_' . $lang;
	if ( ! has_nav_menu( $location ) ) {
		return;
	}
	wp_nav_menu( array(
		'theme_location' => $location,
		'container'      => false,
		'menu_class'     => $class,
		'depth'          => $depth,
		'fallback_cb'    => false,
	) );
}

/**
 * Mark the current section in menus for CPT singles (e.g. a news article highlights "News").
 */
add_filter( 'nav_menu_css_class', function ( $classes, $item ) {
	if ( ! is_singular() || ! function_exists( 's4u_page_by_key' ) ) {
		return $classes;
	}
	$map = array( 's4u_news' => 'news', 's4u_project' => 'projects', 's4u_program' => 'programs', 's4u_person' => 'about' );
	$type = get_post_type();
	if ( isset( $map[ $type ] ) && (int) $item->object_id === s4u_page_by_key( $map[ $type ] ) ) {
		$classes[] = 'current-menu-ancestor';
	}
	return $classes;
}, 10, 2 );

add_filter( 'nav_menu_link_attributes', function ( $atts, $item ) {
	if ( in_array( 'current-menu-item', (array) $item->classes, true ) ) {
		$atts['aria-current'] = 'page';
	}
	return $atts;
}, 10, 2 );

/**
 * Block patterns for editors (Sea4Us category).
 */
add_action( 'init', function () {
	register_block_pattern_category( 'sea4us', array( 'label' => 'Sea4Us' ) );
} );

/**
 * Lighter front-end: no block library CSS for blocks not used; inline small critical bits.
 */
add_filter( 'should_load_separate_core_block_assets', '__return_true' );

/**
 * Wrap core content images: keep explicit dimensions (WordPress adds width/height) and lazy loading.
 */
add_filter( 'wp_img_tag_add_loading_optimization_attrs', '__return_true' );
