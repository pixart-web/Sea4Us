<?php
/**
 * Lightweight PT-PT / EN layer.
 *
 * - Pages: language comes from the root ancestor page, whose slug is the language code
 *   ("pt" or "en"). The page tree therefore produces /pt/... and /en/... paths natively.
 * - Custom post types: `_s4u_lang` meta, URLs /{lang}/{base}/{slug}/ (bases below).
 * - Translations are linked both ways through `_s4u_tr` (post ID of the counterpart).
 * - Visitors are never redirected by location or browser language.
 */

defined( 'ABSPATH' ) || exit;

function s4u_languages() {
	return array(
		'pt' => array(
			'label'    => 'Português',
			'short'    => 'PT',
			'locale'   => 'pt_PT',
			'hreflang' => 'pt-PT',
			'og'       => 'pt_PT',
		),
		'en' => array(
			'label'    => 'English',
			'short'    => 'EN',
			'locale'   => 'en_GB',
			'hreflang' => 'en',
			'og'       => 'en_GB',
		),
	);
}

function s4u_default_lang() {
	return 'pt';
}

/**
 * Language served at the site root (/) and used as hreflang x-default.
 * The old site sent / to /en/ and declared /en/ as x-default (verified 10/10/2026), so EN is the default here.
 */
function s4u_root_lang() {
	$lang = get_option( 's4u_settings' )['root_lang'] ?? 'en';
	return s4u_is_lang( $lang ) ? $lang : 'en';
}

function s4u_is_lang( $lang ) {
	return is_string( $lang ) && isset( s4u_languages()[ $lang ] );
}

/**
 * URL bases for custom post type singles, per language. They mirror the slugs of the
 * listing pages so that breadcrumbs and URLs read naturally.
 */
function s4u_bases() {
	return apply_filters( 's4u_bases', array(
		's4u_news'    => array( 'pt' => 'noticias', 'en' => 'news' ),
		's4u_project' => array( 'pt' => 'projetos', 'en' => 'projects' ),
		's4u_program' => array( 'pt' => 'programas', 'en' => 'programmes' ),
		's4u_person'  => array( 'pt' => 'sobre/equipa', 'en' => 'about/team' ),
	) );
}

function s4u_translatable_types() {
	return array( 'page', 's4u_news', 's4u_project', 's4u_program', 's4u_person', 's4u_partner', 's4u_document' );
}

/**
 * Language of a post.
 */
function s4u_post_lang( $post ) {
	$post = get_post( $post );
	if ( ! $post ) {
		return s4u_default_lang();
	}
	if ( 'page' === $post->post_type ) {
		$ancestors = get_post_ancestors( $post );
		$root      = $ancestors ? end( $ancestors ) : $post->ID;
		$slug      = get_post_field( 'post_name', $root );
		if ( s4u_is_lang( $slug ) ) {
			return $slug;
		}
	}
	$lang = get_post_meta( $post->ID, '_s4u_lang', true );
	return s4u_is_lang( $lang ) ? $lang : s4u_default_lang();
}

/**
 * Language of the current request.
 */
function s4u_current_lang() {
	global $s4u_forced_lang;
	if ( s4u_is_lang( $s4u_forced_lang ) ) {
		return $s4u_forced_lang;
	}
	$q = get_query_var( 's4u_lang' );
	if ( s4u_is_lang( $q ) ) {
		return $q;
	}
	if ( did_action( 'wp' ) && is_singular() ) {
		return s4u_post_lang( get_queried_object_id() );
	}
	$path  = trim( (string) wp_parse_url( $_SERVER['REQUEST_URI'] ?? '', PHP_URL_PATH ), '/' );
	$home  = trim( (string) wp_parse_url( home_url( '/' ), PHP_URL_PATH ), '/' );
	if ( $home && str_starts_with( $path, $home ) ) {
		$path = trim( substr( $path, strlen( $home ) ), '/' );
	}
	$first = strtok( $path, '/' );
	if ( s4u_is_lang( $first ) ) {
		return $first;
	}
	if ( defined( 'REST_REQUEST' ) && REST_REQUEST && ! empty( $_GET['post_id'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification -- read-only preview context.
		return s4u_post_lang( absint( $_GET['post_id'] ) ); // phpcs:ignore WordPress.Security.NonceVerification
	}
	if ( isset( $_GET['lang'] ) && s4u_is_lang( $_GET['lang'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification
		return sanitize_key( $_GET['lang'] ); // phpcs:ignore WordPress.Security.NonceVerification
	}
	return s4u_default_lang();
}

/**
 * Root page (home) of a language.
 */
function s4u_lang_root( $lang ) {
	static $cache = array();
	if ( isset( $cache[ $lang ] ) ) {
		return $cache[ $lang ];
	}
	$page            = get_page_by_path( $lang );
	$cache[ $lang ] = $page ? (int) $page->ID : 0;
	return $cache[ $lang ];
}

function s4u_home_url( $lang = null ) {
	$lang = $lang ?: s4u_current_lang();
	return home_url( '/' . $lang . '/' );
}

/**
 * Find a page of a language by a stable key (`_s4u_key` meta, e.g. "news", "contact").
 */
function s4u_page_by_key( $key, $lang = null ) {
	$lang = $lang ?: s4u_current_lang();
	$ids  = get_posts( array(
		'post_type'      => 'page',
		'post_status'    => 'publish',
		'meta_key'       => '_s4u_key',
		'meta_value'     => $key,
		'fields'         => 'ids',
		'posts_per_page' => 5,
	) );
	foreach ( $ids as $id ) {
		if ( s4u_post_lang( $id ) === $lang ) {
			return (int) $id;
		}
	}
	return 0;
}

function s4u_page_url( $key, $lang = null ) {
	$id = s4u_page_by_key( $key, $lang );
	return $id ? get_permalink( $id ) : s4u_home_url( $lang );
}

/**
 * Linked translation of a post in a language (0 when none is published).
 */
function s4u_get_translation( $post_id, $lang ) {
	if ( s4u_post_lang( $post_id ) === $lang ) {
		return (int) $post_id;
	}
	$tr = (int) get_post_meta( $post_id, '_s4u_tr', true );
	if ( $tr && 'publish' === get_post_status( $tr ) && s4u_post_lang( $tr ) === $lang ) {
		return $tr;
	}
	// Language roots are each other's translation.
	$root_from = s4u_lang_root( s4u_post_lang( $post_id ) );
	if ( (int) $post_id === $root_from ) {
		return s4u_lang_root( $lang );
	}
	return 0;
}

/**
 * Equivalent URL of the current view in another language. Falls back to that language's home.
 */
function s4u_switcher_url( $lang ) {
	if ( is_singular() ) {
		$tr = s4u_get_translation( get_queried_object_id(), $lang );
		return $tr ? get_permalink( $tr ) : s4u_home_url( $lang );
	}
	if ( is_search() || isset( $_GET['q'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification
		$url = s4u_page_url( 'search', $lang );
		$q   = isset( $_GET['q'] ) ? sanitize_text_field( wp_unslash( $_GET['q'] ) ) : get_search_query(); // phpcs:ignore
		return $q ? add_query_arg( 'q', rawurlencode( $q ), $url ) : $url;
	}
	return s4u_home_url( $lang );
}

/**
 * Query vars and rewrite rules for CPT singles.
 */
add_filter( 'query_vars', function ( $vars ) {
	$vars[] = 's4u_lang';
	return $vars;
} );

function s4u_add_rewrite_rules() {
	foreach ( s4u_bases() as $type => $bases ) {
		foreach ( $bases as $lang => $base ) {
			add_rewrite_rule(
				'^' . $lang . '/' . preg_quote( $base, '#' ) . '/([^/]+)/?$',
				'index.php?post_type=' . $type . '&name=$matches[1]&s4u_lang=' . $lang,
				'top'
			);
		}
	}
}
add_action( 'init', 's4u_add_rewrite_rules', 20 );

/**
 * Restrict CPT single lookups to the language in the URL (slugs may repeat across languages).
 */
add_action( 'pre_get_posts', function ( WP_Query $q ) {
	if ( is_admin() || ! $q->is_main_query() ) {
		return;
	}
	$lang = $q->get( 's4u_lang' );
	$type = $q->get( 'post_type' );
	if ( $lang && is_string( $type ) && isset( s4u_bases()[ $type ] ) ) {
		$q->set( 'meta_query', array(
			array(
				'key'   => '_s4u_lang',
				'value' => $lang,
			),
		) );
	}
} );

/**
 * Permalinks for CPT singles.
 */
add_filter( 'post_type_link', function ( $link, $post ) {
	$bases = s4u_bases();
	if ( ! isset( $bases[ $post->post_type ] ) ) {
		return $link;
	}
	$lang = s4u_post_lang( $post );
	$slug = $post->post_name ?: sanitize_title( $post->post_title ) ?: (string) $post->ID;
	return home_url( '/' . $lang . '/' . $bases[ $post->post_type ][ $lang ] . '/' . $slug . '/' );
}, 10, 2 );

/**
 * Allow the same slug in PT and EN for a custom post type.
 */
add_filter( 'wp_unique_post_slug', function ( $slug, $post_id, $status, $type, $parent, $original ) {
	if ( ! isset( s4u_bases()[ $type ] ) || $slug === $original ) {
		return $slug;
	}
	global $s4u_saving_lang;
	$lang = $s4u_saving_lang ?: ( $post_id ? get_post_meta( $post_id, '_s4u_lang', true ) : '' );
	if ( isset( $_POST['s4u_lang'] ) && s4u_is_lang( $_POST['s4u_lang'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification -- verified in the save handler.
		$lang = sanitize_key( $_POST['s4u_lang'] ); // phpcs:ignore WordPress.Security.NonceVerification
	}
	if ( ! s4u_is_lang( $lang ) ) {
		return $slug;
	}
	$clash = get_posts( array(
		'post_type'      => $type,
		'name'           => $original,
		'post_status'    => 'any',
		'post__not_in'   => array( (int) $post_id ),
		'meta_key'       => '_s4u_lang',
		'meta_value'     => $lang,
		'fields'         => 'ids',
		'posts_per_page' => 1,
	) );
	return $clash ? $slug : $original;
}, 10, 6 );

/**
 * Root URL behaviour. The current site's root behaviour must be verified before launch
 * (see docs). Default: temporary redirect to /pt/. Never by geolocation or Accept-Language.
 */
add_action( 'template_redirect', function () {
	if ( ! is_front_page() && ! is_home() ) {
		return;
	}
	$path = trim( (string) wp_parse_url( $_SERVER['REQUEST_URI'] ?? '', PHP_URL_PATH ), '/' );
	$home = trim( (string) wp_parse_url( home_url( '/' ), PHP_URL_PATH ), '/' );
	if ( $path !== $home ) {
		return;
	}
	if ( isset( $_GET['s'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification
		$q = sanitize_text_field( wp_unslash( $_GET['s'] ) ); // phpcs:ignore WordPress.Security.NonceVerification
		wp_safe_redirect( add_query_arg( 'q', rawurlencode( $q ), s4u_page_url( 'search', s4u_root_lang() ) ), 302 );
		exit;
	}
	$mode = get_option( 's4u_settings' )['root_behavior'] ?? 'redirect_302';
	$code = 'redirect_301' === $mode ? 301 : 302;
	wp_safe_redirect( s4u_home_url( s4u_root_lang() ), $code );
	exit;
} );

/**
 * Language root pages are homes: avoid WordPress canonical redirects on them.
 */
add_filter( 'redirect_canonical', function ( $redirect ) {
	if ( is_page() && in_array( get_queried_object_id(), array( s4u_lang_root( 'pt' ), s4u_lang_root( 'en' ) ), true ) ) {
		return false;
	}
	return $redirect;
} );

/**
 * <html lang> attribute.
 */
add_filter( 'language_attributes', function ( $output ) {
	$lang = s4u_current_lang();
	$code = s4u_languages()[ $lang ]['hreflang'];
	return preg_replace( '/lang="[^"]*"/', 'lang="' . esc_attr( $code ) . '"', $output );
} );

/**
 * Admin: language + translation metabox, list column and filter.
 */
add_action( 'add_meta_boxes', function () {
	foreach ( s4u_translatable_types() as $type ) {
		add_meta_box( 's4u_lang_box', __( 'Idioma e tradução', 'sea4us-core' ), 's4u_lang_metabox', $type, 'side', 'high' );
	}
} );

function s4u_lang_metabox( $post ) {
	wp_nonce_field( 's4u_lang_save', 's4u_lang_nonce' );
	$lang = s4u_post_lang( $post );
	$tr   = (int) get_post_meta( $post->ID, '_s4u_tr', true );
	if ( 'page' === $post->post_type ) {
		echo '<p>' . esc_html__( 'Idioma (definido pela página-raiz):', 'sea4us-core' ) . ' <strong>' . esc_html( strtoupper( $lang ) ) . '</strong></p>';
		echo '<input type="hidden" name="s4u_lang" value="' . esc_attr( $lang ) . '">';
	} else {
		echo '<p><label for="s4u_lang">' . esc_html__( 'Idioma', 'sea4us-core' ) . '</label><br><select name="s4u_lang" id="s4u_lang">';
		foreach ( s4u_languages() as $code => $l ) {
			printf( '<option value="%s"%s>%s</option>', esc_attr( $code ), selected( $lang, $code, false ), esc_html( $l['label'] ) );
		}
		echo '</select></p>';
	}
	$other   = 'pt' === $lang ? 'en' : 'pt';
	$options = get_posts( array(
		'post_type'      => $post->post_type,
		'post_status'    => array( 'publish', 'draft', 'pending', 'private' ),
		'posts_per_page' => 500,
		'orderby'        => 'title',
		'order'          => 'ASC',
		'post__not_in'   => array( $post->ID ),
	) );
	echo '<p><label for="s4u_tr">' . esc_html__( 'Tradução ligada', 'sea4us-core' ) . '</label><br><select name="s4u_tr" id="s4u_tr" style="max-width:100%"><option value="0">—</option>';
	foreach ( $options as $o ) {
		if ( s4u_post_lang( $o ) !== $other ) {
			continue;
		}
		printf( '<option value="%d"%s>[%s] %s</option>', (int) $o->ID, selected( $tr, $o->ID, false ), esc_html( strtoupper( $other ) ), esc_html( $o->post_title ) );
	}
	echo '</select></p>';
	if ( $tr ) {
		printf( '<p><a href="%s">%s</a></p>', esc_url( get_edit_post_link( $tr ) ), esc_html__( 'Editar tradução', 'sea4us-core' ) );
	}
	echo '<p class="description">' . esc_html__( 'Os campos estruturados partilhados (datas, estádio, códigos, montantes) são sincronizados com a tradução ao guardar.', 'sea4us-core' ) . '</p>';
}

add_action( 'save_post', function ( $post_id, $post ) {
	if ( ! isset( $_POST['s4u_lang_nonce'] ) || ! wp_verify_nonce( sanitize_key( $_POST['s4u_lang_nonce'] ), 's4u_lang_save' ) ) {
		return;
	}
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}
	if ( ! current_user_can( 'edit_post', $post_id ) ) {
		return;
	}
	$lang = isset( $_POST['s4u_lang'] ) ? sanitize_key( $_POST['s4u_lang'] ) : '';
	if ( s4u_is_lang( $lang ) && 'page' !== $post->post_type ) {
		update_post_meta( $post_id, '_s4u_lang', $lang );
	}
	$new_tr = isset( $_POST['s4u_tr'] ) ? absint( $_POST['s4u_tr'] ) : 0;
	s4u_link_translations( $post_id, $new_tr );
}, 10, 2 );

/**
 * Link two posts as translations (and unlink previous partners).
 */
function s4u_link_translations( $a, $b ) {
	$old = (int) get_post_meta( $a, '_s4u_tr', true );
	if ( $old && $old !== $b ) {
		delete_post_meta( $old, '_s4u_tr' );
	}
	if ( $b ) {
		$b_old = (int) get_post_meta( $b, '_s4u_tr', true );
		if ( $b_old && $b_old !== $a ) {
			delete_post_meta( $b_old, '_s4u_tr' );
		}
		update_post_meta( $a, '_s4u_tr', $b );
		update_post_meta( $b, '_s4u_tr', $a );
	} else {
		delete_post_meta( $a, '_s4u_tr' );
	}
}

foreach ( array( 'page', 's4u_news', 's4u_project', 's4u_program', 's4u_person', 's4u_partner', 's4u_document' ) as $s4u_type ) {
	add_filter( "manage_{$s4u_type}_posts_columns", function ( $cols ) {
		$cols['s4u_lang'] = __( 'Idioma', 'sea4us-core' );
		return $cols;
	} );
	add_action( "manage_{$s4u_type}_posts_custom_column", function ( $col, $post_id ) {
		if ( 's4u_lang' !== $col ) {
			return;
		}
		$tr = (int) get_post_meta( $post_id, '_s4u_tr', true );
		echo esc_html( strtoupper( s4u_post_lang( $post_id ) ) );
		echo $tr ? ' ↔ <a href="' . esc_url( get_edit_post_link( $tr ) ) . '">' . esc_html( strtoupper( s4u_post_lang( $tr ) ) ) . '</a>' : ' <span style="color:#b32d2e">' . esc_html__( 'sem tradução', 'sea4us-core' ) . '</span>';
	}, 10, 2 );
}
unset( $s4u_type );

add_action( 'restrict_manage_posts', function ( $type ) {
	if ( 'page' === $type || ! in_array( $type, s4u_translatable_types(), true ) ) {
		return;
	}
	$cur = isset( $_GET['s4u_lang_filter'] ) ? sanitize_key( $_GET['s4u_lang_filter'] ) : ''; // phpcs:ignore
	echo '<select name="s4u_lang_filter"><option value="">' . esc_html__( 'Todos os idiomas', 'sea4us-core' ) . '</option>';
	foreach ( s4u_languages() as $code => $l ) {
		printf( '<option value="%s"%s>%s</option>', esc_attr( $code ), selected( $cur, $code, false ), esc_html( $l['label'] ) );
	}
	echo '</select>';
} );

add_action( 'pre_get_posts', function ( WP_Query $q ) {
	if ( ! is_admin() || ! $q->is_main_query() || empty( $_GET['s4u_lang_filter'] ) ) { // phpcs:ignore
		return;
	}
	$lang = sanitize_key( $_GET['s4u_lang_filter'] ); // phpcs:ignore
	if ( s4u_is_lang( $lang ) ) {
		$q->set( 'meta_key', '_s4u_lang' );
		$q->set( 'meta_value', $lang );
	}
} );
