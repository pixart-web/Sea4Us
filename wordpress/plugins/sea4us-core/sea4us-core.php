<?php
/**
 * Plugin Name:       Sea4Us Core
 * Description:       Tipos de conteúdo, campos estruturados, idiomas PT/EN, blocos dinâmicos, SEO, formulário de contacto (modo demonstração) e importador do website Sea4Us. Independente do tema.
 * Version:           1.0.0
 * Requires at least: 6.5
 * Requires PHP:      8.1
 * Author:            Pixart
 * Text Domain:       sea4us-core
 * License:           GPL-2.0-or-later
 */

defined( 'ABSPATH' ) || exit;

define( 'S4U_VERSION', '1.0.0' );
define( 'S4U_PATH', plugin_dir_path( __FILE__ ) );
define( 'S4U_URL', plugin_dir_url( __FILE__ ) );

/*
 * Form mode: 'demo' (default — validates, never sends nor stores) or 'production'.
 * Production also requires a confirmed recipient in Settings → Sea4Us.
 * Override in wp-config.php: define( 'SEA4US_FORM_MODE', 'production' );
 */
if ( ! defined( 'SEA4US_FORM_MODE' ) ) {
	define( 'SEA4US_FORM_MODE', 'demo' );
}

require_once S4U_PATH . 'inc/helpers.php';
require_once S4U_PATH . 'inc/i18n.php';
require_once S4U_PATH . 'inc/post-types.php';
require_once S4U_PATH . 'inc/fields.php';
require_once S4U_PATH . 'inc/settings.php';
require_once S4U_PATH . 'inc/render.php';
require_once S4U_PATH . 'inc/blocks.php';
require_once S4U_PATH . 'inc/form.php';
require_once S4U_PATH . 'inc/seo.php';
require_once S4U_PATH . 'inc/search.php';
require_once S4U_PATH . 'inc/redirects.php';

if ( defined( 'WP_CLI' ) && WP_CLI ) {
	require_once S4U_PATH . 'inc/importer.php';
}

register_activation_hook( __FILE__, function () {
	s4u_register_post_types();
	s4u_add_rewrite_rules();
	flush_rewrite_rules();
} );

register_deactivation_hook( __FILE__, 'flush_rewrite_rules' );
