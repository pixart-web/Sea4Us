<?php
/**
 * SEO: titles, descriptions, hreflang, social metadata, breadcrumbs, structured data,
 * sitemaps and the pre-launch noindex switch.
 */

defined( 'ABSPATH' ) || exit;

add_action( 'init', function () {
	foreach ( s4u_translatable_types() as $type ) {
		register_post_meta( $type, '_s4u_seo_title', array( 'type' => 'string', 'single' => true, 'show_in_rest' => true, 'auth_callback' => fn() => current_user_can( 'edit_posts' ), 'sanitize_callback' => 'sanitize_text_field' ) );
		register_post_meta( $type, '_s4u_seo_desc', array( 'type' => 'string', 'single' => true, 'show_in_rest' => true, 'auth_callback' => fn() => current_user_can( 'edit_posts' ), 'sanitize_callback' => 'sanitize_textarea_field' ) );
	}
} );

add_action( 'add_meta_boxes', function () {
	foreach ( s4u_translatable_types() as $type ) {
		add_meta_box( 's4u_seo', 'SEO', function ( $post ) {
			wp_nonce_field( 's4u_seo_save', 's4u_seo_nonce' );
			printf( '<p><label>Título SEO<br><input type="text" class="large-text" name="s4u_seo_title" value="%s"></label></p>', esc_attr( get_post_meta( $post->ID, '_s4u_seo_title', true ) ) );
			printf( '<p><label>Descrição (≈155 caracteres)<br><textarea class="large-text" rows="2" name="s4u_seo_desc">%s</textarea></label></p>', esc_textarea( get_post_meta( $post->ID, '_s4u_seo_desc', true ) ) );
		}, $type, 'normal', 'low' );
	}
} );

add_action( 'save_post', function ( $post_id ) {
	if ( ! isset( $_POST['s4u_seo_nonce'] ) || ! wp_verify_nonce( sanitize_key( $_POST['s4u_seo_nonce'] ), 's4u_seo_save' ) || ! current_user_can( 'edit_post', $post_id ) ) {
		return;
	}
	update_post_meta( $post_id, '_s4u_seo_title', sanitize_text_field( wp_unslash( $_POST['s4u_seo_title'] ?? '' ) ) );
	update_post_meta( $post_id, '_s4u_seo_desc', sanitize_textarea_field( wp_unslash( $_POST['s4u_seo_desc'] ?? '' ) ) );
} );

function s4u_is_lang_home() {
	return is_page() && in_array( get_queried_object_id(), array( s4u_lang_root( 'pt' ), s4u_lang_root( 'en' ) ), true );
}

function s4u_seo_title() {
	$lang = s4u_current_lang();
	if ( is_404() ) {
		return s4u__( 'not_found_title' ) . ' — Sea4Us';
	}
	if ( is_singular() ) {
		$id     = get_queried_object_id();
		$custom = get_post_meta( $id, '_s4u_seo_title', true );
		if ( $custom ) {
			return $custom;
		}
		if ( s4u_is_lang_home() ) {
			return s4u_pick( 'Sea4Us — Biotecnologia de origem marinha', 'Sea4Us — Marine-inspired biotechnology', $lang );
		}
		return wp_strip_all_tags( get_the_title( $id ) ) . ' — Sea4Us';
	}
	return 'Sea4Us';
}

add_filter( 'pre_get_document_title', 's4u_seo_title' );

function s4u_seo_description() {
	if ( ! is_singular() ) {
		return '';
	}
	$id   = get_queried_object_id();
	$desc = get_post_meta( $id, '_s4u_seo_desc', true );
	if ( ! $desc ) {
		$post = get_post( $id );
		$desc = $post->post_excerpt ?: wp_strip_all_tags( strip_shortcodes( excerpt_remove_blocks( $post->post_content ) ) );
	}
	$desc = trim( preg_replace( '/\s+/', ' ', $desc ) );
	return mb_strlen( $desc ) > 158 ? rtrim( mb_substr( $desc, 0, 155 ), " ,.;:" ) . '…' : $desc;
}

/**
 * Breadcrumb trail: array of [label, url].
 */
function s4u_breadcrumbs() {
	$lang  = s4u_current_lang();
	$trail = array( array( s4u__( 'home' ), s4u_home_url( $lang ) ) );
	if ( ! is_singular() || s4u_is_lang_home() ) {
		return $trail;
	}
	$post = get_queried_object();
	if ( 'page' === $post->post_type ) {
		$anc = array_reverse( get_post_ancestors( $post ) );
		array_shift( $anc ); // Language root.
		foreach ( $anc as $a ) {
			$trail[] = array( get_the_title( $a ), get_permalink( $a ) );
		}
	} else {
		$keys = array(
			's4u_news'    => array( 'news' ),
			's4u_project' => array( 'projects' ),
			's4u_program' => array( 'programs' ),
			's4u_person'  => array( 'about', 'team' ),
		);
		foreach ( $keys[ $post->post_type ] ?? array() as $k ) {
			$pid = s4u_page_by_key( $k, $lang );
			if ( $pid ) {
				$trail[] = array( get_the_title( $pid ), get_permalink( $pid ) );
			}
		}
	}
	$trail[] = array( get_the_title( $post ), get_permalink( $post ) );
	return $trail;
}

add_action( 'wp_head', function () {
	$lang = s4u_current_lang();
	$desc = s4u_seo_description();
	if ( $desc ) {
		echo '<meta name="description" content="' . esc_attr( $desc ) . '">' . "\n";
	}

	// hreflang (reciprocal; only when a published translation exists).
	if ( is_singular() ) {
		$id    = get_queried_object_id();
		$alts  = array();
		foreach ( s4u_languages() as $code => $l ) {
			$t = s4u_get_translation( $id, $code );
			if ( $t ) {
				$alts[ $l['hreflang'] ] = get_permalink( $t );
			}
		}
		if ( count( $alts ) > 1 ) {
			foreach ( $alts as $hl => $url ) {
				echo '<link rel="alternate" hreflang="' . esc_attr( $hl ) . '" href="' . esc_url( $url ) . '">' . "\n";
			}
			echo '<link rel="alternate" hreflang="x-default" href="' . esc_url( $alts[ s4u_languages()[ s4u_root_lang() ]['hreflang'] ] ?? reset( $alts ) ) . '">' . "\n";
		}
	}

	// Social.
	$title = s4u_seo_title();
	$url   = is_singular() ? get_permalink( get_queried_object_id() ) : s4u_home_url( $lang );
	$img   = is_singular() && has_post_thumbnail( get_queried_object_id() ) ? wp_get_attachment_image_url( get_post_thumbnail_id( get_queried_object_id() ), 'large' ) : '';
	if ( ! $img ) {
		$img = apply_filters( 's4u_default_og_image', '' );
	}
	printf( '<meta property="og:site_name" content="Sea4Us">' . "\n" );
	printf( '<meta property="og:locale" content="%s">' . "\n", esc_attr( s4u_languages()[ $lang ]['og'] ) );
	printf( '<meta property="og:type" content="%s">' . "\n", is_singular( 's4u_news' ) ? 'article' : 'website' );
	printf( '<meta property="og:title" content="%s">' . "\n", esc_attr( $title ) );
	if ( $desc ) {
		printf( '<meta property="og:description" content="%s">' . "\n", esc_attr( $desc ) );
	}
	printf( '<meta property="og:url" content="%s">' . "\n", esc_url( $url ) );
	if ( $img ) {
		printf( '<meta property="og:image" content="%s">' . "\n", esc_url( $img ) );
	}
	echo '<meta name="twitter:card" content="summary_large_image">' . "\n";

	// Structured data, describing only what is visible on the page.
	$graph = array();
	if ( s4u_is_lang_home() ) {
		$org = array(
			'@type'     => 'Organization',
			'@id'       => home_url( '/#organization' ),
			'name'      => 'Sea4Us',
			'url'       => s4u_home_url( $lang ),
			'email'     => s4u_setting( 'email' ),
			'telephone' => s4u_setting( 'phone_e164' ),
			'sameAs'    => array_values( array_filter( array( s4u_setting( 'linkedin' ), s4u_setting( 'facebook' ), s4u_setting( 'instagram' ), s4u_setting( 'youtube' ) ) ) ),
		);
		$logo = apply_filters( 's4u_logo_url', '' );
		if ( $logo ) {
			$org['logo'] = $logo;
		}
		$graph[] = array_filter( $org );
	}
	if ( is_singular( 's4u_news' ) ) {
		$p       = get_queried_object();
		$article = array(
			'@type'         => 'NewsArticle',
			'headline'      => get_the_title( $p ),
			'datePublished' => get_the_date( 'c', $p ),
			'dateModified'  => get_the_modified_date( 'c', $p ),
			'inLanguage'    => s4u_languages()[ $lang ]['hreflang'],
			'publisher'     => array( '@type' => 'Organization', 'name' => 'Sea4Us' ),
			'mainEntityOfPage' => get_permalink( $p ),
		);
		if ( $img ) {
			$article['image'] = $img;
		}
		$author = s4u_meta( $p->ID, 'author_name' );
		if ( $author ) {
			$article['author'] = array( '@type' => 'Person', 'name' => $author );
		}
		$graph[] = $article;
	}
	if ( is_singular() && ! s4u_is_lang_home() ) {
		$items = array();
		foreach ( s4u_breadcrumbs() as $i => $crumb ) {
			$items[] = array( '@type' => 'ListItem', 'position' => $i + 1, 'name' => wp_strip_all_tags( $crumb[0] ), 'item' => $crumb[1] );
		}
		$graph[] = array( '@type' => 'BreadcrumbList', 'itemListElement' => $items );
	}
	if ( $graph ) {
		echo '<script type="application/ld+json">' . wp_json_encode( array( '@context' => 'https://schema.org', '@graph' => $graph ), JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE ) . '</script>' . "\n";
	}
}, 5 );

/**
 * Pre-launch: noindex everywhere until indexing is explicitly allowed in Settings → Sea4Us.
 * Search results and 404 are always noindex.
 */
add_filter( 'wp_robots', function ( $robots ) {
	$opts = get_option( 's4u_settings', array() );
	if ( empty( $opts['allow_indexing'] ) || is_search() || is_404() || isset( $_GET['q'] ) ) { // phpcs:ignore
		$robots['noindex']  = true;
		$robots['nofollow'] = empty( $opts['allow_indexing'] );
	}
	return $robots;
} );

/**
 * Sitemaps: no user sitemap; non-public types are excluded by WordPress already.
 */
add_filter( 'wp_sitemaps_add_provider', function ( $provider, $name ) {
	// Content lives in the Sea4Us types; WordPress categories/tags and authors are not used.
	return in_array( $name, array( 'users', 'taxonomies' ), true ) ? false : $provider;
}, 10, 2 );

add_filter( 'wp_sitemaps_post_types', function ( $types ) {
	unset( $types['post'], $types['attachment'] );
	return $types;
} );

/**
 * Remove generator/emoji noise.
 */
remove_action( 'wp_head', 'wp_generator' );
remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
remove_action( 'wp_print_styles', 'print_emoji_styles' );
remove_action( 'wp_head', 'wp_shortlink_wp_head' );
remove_action( 'wp_head', 'rsd_link' );
remove_action( 'wp_head', 'feed_links', 2 );
remove_action( 'wp_head', 'feed_links_extra', 3 );
add_filter( 'wp_resource_hints', function ( $urls, $type ) {
	return 'dns-prefetch' === $type ? array() : $urls;
}, 10, 2 );
