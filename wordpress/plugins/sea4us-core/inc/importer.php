<?php
/**
 * Reproducible, idempotent content import.
 *
 *   wp sea4us import --dir=/path/to/content [--dry-run]
 *   wp sea4us reindex
 *
 * Every imported object carries a stable `_s4u_source` key, so running the import again
 * updates the same objects instead of creating duplicates. Media keep their original
 * wp-content/uploads relative paths, so existing PDF/image URLs stay valid after launch.
 *
 * Placeholders in content: {{media:KEY}} (URL), {{media-id:KEY}} (attachment ID),
 * {{page:KEY}} (page URL in the same language), {{item:SOURCE_ID}} (CPT permalink, same language).
 * All generated links are root-relative: no test domain is written into content.
 */

defined( 'ABSPATH' ) || exit;

class S4U_Import_Command {

	private $dir;
	private $media_dir;
	private $dry = false;
	private $media = array();   // key => attachment id.
	private $items = array();   // source id + lang => post id.
	private $log = array( 'created' => 0, 'updated' => 0, 'media' => 0, 'skipped' => 0 );

	/**
	 * Import content from a directory.
	 *
	 * ## OPTIONS
	 *
	 * --dir=<dir>
	 * : Content directory (site.json, pages.json, … and media/).
	 *
	 * [--media=<dir>]
	 * : Folder holding the media files (default: <dir>/media). Paths in media.json are relative to it.
	 *
	 * [--dry-run]
	 * : Validate only.
	 */
	public function import( $args, $assoc ) {
		$this->dir = rtrim( $assoc['dir'], '/' );
		$this->dry = isset( $assoc['dry-run'] );
		$this->media_dir = rtrim( $assoc['media'] ?? ( $this->dir . '/media' ), '/' );
		if ( ! is_dir( $this->dir ) ) {
			WP_CLI::error( 'Directory not found: ' . $this->dir );
		}
		wp_defer_term_counting( true );

		$this->import_media();
		$site = $this->json( 'site.json' );
		if ( ! empty( $site['settings'] ) && ! $this->dry ) {
			$current  = get_option( 's4u_settings', array() );
			$settings = array_map( fn( $v ) => is_string( $v ) ? $this->placeholders( $v, 'pt' ) : $v, $site['settings'] );
			update_option( 's4u_settings', array_merge( $current, $settings ) );
		}

		// Pass 1: create/update all posts (content placeholders resolved in pass 2).
		$pages = $this->json( 'pages.json' );
		$this->import_pages( $pages );
		$collections = array(
			'documents.json' => 's4u_document',
			'partners.json'  => 's4u_partner',
			'people.json'    => 's4u_person',
			'programs.json'  => 's4u_program',
			'projects.json'  => 's4u_project',
			'news.json'      => 's4u_news',
		);
		$data = array();
		foreach ( $collections as $file => $type ) {
			$data[ $type ] = $this->json( $file );
			$this->import_items( $data[ $type ], $type );
		}

		// Pass 2: references, placeholders, translations.
		$this->resolve_pages( $pages );
		foreach ( $data as $type => $rows ) {
			$this->resolve_items( $rows, $type );
		}

		if ( ! empty( $site['menus'] ) ) {
			$this->import_menus( $site['menus'] );
		}
		$redirects = $this->dir . '/redirects.csv';
		if ( is_readable( $redirects ) && ! $this->dry ) {
			$map = array();
			$fh  = fopen( $redirects, 'r' ); // phpcs:ignore
			$hdr = fgetcsv( $fh );
			while ( ( $row = fgetcsv( $fh ) ) !== false ) {
				$r = array_combine( $hdr, $row );
				if ( ! empty( $r['old_path'] ) && ! empty( $r['new_path'] ) && '301' === ( $r['action'] ?? '' ) ) {
					$map[ strtolower( '/' . trim( $r['old_path'], '/' ) . '/' ) ] = $r['new_path'];
				}
			}
			fclose( $fh ); // phpcs:ignore
			update_option( 's4u_redirects', $map, false );
			WP_CLI::log( 'Redirect map entries: ' . count( $map ) );
		}

		if ( ! $this->dry ) {
			update_option( 'show_on_front', 'posts' );
			flush_rewrite_rules();
			s4u_write_search_indexes();
		}
		wp_defer_term_counting( false );
		WP_CLI::success( sprintf( 'Created %d, updated %d, media %d, skipped %d.', $this->log['created'], $this->log['updated'], $this->log['media'], $this->log['skipped'] ) );
	}

	/**
	 * Rebuild the search indexes.
	 */
	public function reindex() {
		s4u_write_search_indexes();
		WP_CLI::success( 'Search indexes written.' );
	}

	private function json( $file ) {
		$path = $this->dir . '/' . $file;
		if ( ! is_readable( $path ) ) {
			return array();
		}
		$data = json_decode( file_get_contents( $path ), true ); // phpcs:ignore
		if ( null === $data ) {
			WP_CLI::error( "Invalid JSON: $file" );
		}
		return $data;
	}

	private function find_by_source( $source, $type ) {
		$ids = get_posts( array(
			'post_type'      => $type,
			'post_status'    => 'any',
			'meta_key'       => '_s4u_source',
			'meta_value'     => $source,
			'fields'         => 'ids',
			'posts_per_page' => 1,
		) );
		return $ids ? (int) $ids[0] : 0;
	}

	/* ---------- Media ---------- */

	private function import_media() {
		$rows = $this->json( 'media.json' );
		require_once ABSPATH . 'wp-admin/includes/image.php';
		$up = wp_upload_dir();
		foreach ( $rows as $m ) {
			$key = $m['key'];
			$src = $this->media_dir . '/' . $m['file'];
			$existing = s4u_attachment_by_source( $key );
			if ( ! is_readable( $src ) ) {
				WP_CLI::warning( "Missing media file: {$m['file']}" );
				$this->log['skipped']++;
				if ( $existing ) {
					$this->media[ $key ] = $existing;
				}
				continue;
			}
			if ( $this->dry ) {
				continue;
			}
			// Keep original relative upload path (e.g. 2024/02/file.pdf).
			$rel  = preg_replace( '#^wp-content/uploads/#', '', $m['file'] );
			$dest = trailingslashit( $up['basedir'] ) . $rel;
			wp_mkdir_p( dirname( $dest ) );
			if ( ! file_exists( $dest ) || filesize( $dest ) !== filesize( $src ) ) {
				copy( $src, $dest );
			}
			$type = wp_check_filetype( $dest );
			if ( $existing ) {
				$id = $existing;
			} else {
				$id = wp_insert_attachment( array(
					'post_title'     => $m['title'] ?? pathinfo( $dest, PATHINFO_FILENAME ),
					'post_mime_type' => $type['type'],
					'post_status'    => 'inherit',
				), $dest );
				if ( str_starts_with( (string) $type['type'], 'image/' ) ) {
					wp_update_attachment_metadata( $id, wp_generate_attachment_metadata( $id, $dest ) );
				}
				$this->log['media']++;
			}
			update_post_meta( $id, '_s4u_source', $key );
			update_post_meta( $id, '_wp_attachment_image_alt', $m['alt_pt'] ?? '' );
			update_post_meta( $id, '_s4u_alt_en', $m['alt_en'] ?? '' );
			update_post_meta( $id, '_s4u_credit', $m['credit'] ?? '' );
			update_post_meta( $id, '_s4u_source_url', $m['source_url'] ?? '' );
			$this->media[ $key ] = (int) $id;
		}
	}

	private function media_id( $key ) {
		$key = preg_replace( '/^media:/', '', (string) $key );
		if ( isset( $this->media[ $key ] ) ) {
			return $this->media[ $key ];
		}
		return s4u_attachment_by_source( $key );
	}

	/* ---------- Pages ---------- */

	private function import_pages( $pages ) {
		// Roots first, then children (pages.json is ordered parent-first).
		foreach ( $pages as $p ) {
			foreach ( array( 'pt', 'en' ) as $lang ) {
				if ( empty( $p[ $lang ] ) ) {
					continue;
				}
				$d      = $p[ $lang ];
				$source = 'page:' . $p['key'] . ':' . $lang;
				$parent = 0;
				if ( 'home' === $p['key'] ) {
					$d['slug'] = $lang;
				} else {
					$parent = $this->items[ 'page:' . ( $p['parent'] ?? 'home' ) . ':' . $lang ] ?? 0;
				}
				$postarr = array(
					'post_type'    => 'page',
					'post_status'  => $d['status'] ?? 'publish',
					'post_title'   => $d['title'],
					'post_name'    => $d['slug'],
					'post_parent'  => $parent,
					'post_excerpt' => $d['excerpt'] ?? '',
					'menu_order'   => $p['order'] ?? 0,
					'post_content' => '',
				);
				$id = $this->upsert( $source, 'page', $postarr );
				if ( $id ) {
					update_post_meta( $id, '_s4u_key', $p['key'] );
					update_post_meta( $id, '_s4u_lang', $lang );
					update_post_meta( $id, '_s4u_seo_title', $d['seo_title'] ?? '' );
					update_post_meta( $id, '_s4u_seo_desc', $d['seo_desc'] ?? '' );
					if ( ! empty( $d['template'] ) ) {
						update_post_meta( $id, '_wp_page_template', $d['template'] );
					}
					if ( ! empty( $d['legacy_url'] ) ) {
						update_post_meta( $id, 'legacy_url', $d['legacy_url'] );
					}
				}
			}
		}
	}

	private function resolve_pages( $pages ) {
		foreach ( $pages as $p ) {
			$ids = array();
			foreach ( array( 'pt', 'en' ) as $lang ) {
				$id = $this->items[ 'page:' . $p['key'] . ':' . $lang ] ?? 0;
				if ( ! $id || empty( $p[ $lang ] ) ) {
					continue;
				}
				$ids[ $lang ] = $id;
				if ( ! $this->dry ) {
					wp_update_post( array( 'ID' => $id, 'post_content' => $this->placeholders( $p[ $lang ]['content'] ?? '', $lang ) ) );
					if ( ! empty( $p[ $lang ]['image'] ) ) {
						set_post_thumbnail( $id, $this->media_id( $p[ $lang ]['image'] ) );
					}
				}
			}
			if ( count( $ids ) === 2 && ! $this->dry ) {
				s4u_link_translations( $ids['pt'], $ids['en'] );
			}
		}
	}

	/* ---------- Content types ---------- */

	private function import_items( $rows, $type ) {
		global $s4u_saving_lang;
		foreach ( $rows as $row ) {
			foreach ( array( 'pt', 'en' ) as $lang ) {
				if ( empty( $row[ $lang ] ) ) {
					continue;
				}
				$d               = $row[ $lang ];
				$s4u_saving_lang = $lang;
				$postarr         = array(
					'post_type'    => $type,
					'post_status'  => $d['status'] ?? ( $row['status'] ?? 'publish' ),
					'post_title'   => $d['title'],
					'post_name'    => $d['slug'] ?? sanitize_title( $d['title'] ),
					'post_excerpt' => $d['excerpt'] ?? '',
					'post_content' => '',
					'menu_order'   => $row['order'] ?? 0,
				);
				if ( ! empty( $row['date'] ) ) {
					// Real publication date of the source, never the migration date.
					$postarr['post_date']     = gmdate( 'Y-m-d H:i:s', strtotime( $row['date'] ) );
					$postarr['post_date_gmt'] = get_gmt_from_date( $postarr['post_date'] );
				}
				$id = $this->upsert( $row['id'] . ':' . $lang, $type, $postarr );
				if ( $id && ! $this->dry ) {
					update_post_meta( $id, '_s4u_lang', $lang );
				}
			}
		}
		$s4u_saving_lang = null;
	}

	private function resolve_items( $rows, $type ) {
		$defs = s4u_fields()[ $type ] ?? array();
		foreach ( $rows as $row ) {
			$ids = array();
			foreach ( array( 'pt', 'en' ) as $lang ) {
				$id = $this->items[ $row['id'] . ':' . $lang ] ?? 0;
				if ( ! $id || empty( $row[ $lang ] ) ) {
					continue;
				}
				$ids[ $lang ] = $id;
				if ( $this->dry ) {
					continue;
				}
				$d = $row[ $lang ];
				wp_update_post( array( 'ID' => $id, 'post_content' => $this->placeholders( $d['content'] ?? '', $lang ) ) );
				$fields = array_merge( $row['shared'] ?? array(), $d['fields'] ?? array() );
				foreach ( $fields as $key => $value ) {
					if ( 'thumbnail' === $key ) {
						$mid = $this->media_id( $value );
						if ( $mid ) {
							set_post_thumbnail( $id, $mid );
						}
						continue;
					}
					$def = $defs[ $key ] ?? array( 'type' => 'text' );
					if ( 'attachment' === $def['type'] ) {
						$value = $this->media_id( $value );
					} elseif ( 'attachments' === $def['type'] ) {
						$value = implode( ',', array_filter( array_map( array( $this, 'media_id' ), (array) $value ) ) );
					} elseif ( 'posts' === $def['type'] ) {
						$refs = array();
						foreach ( (array) $value as $ref ) {
							$refs[] = $this->items[ $ref . ':' . $lang ] ?? ( $this->items[ $ref . ':pt' ] ?? 0 );
						}
						$value = implode( ',', array_filter( $refs ) );
					} elseif ( is_array( $value ) ) {
						$value = implode( "\n", $value );
					}
					update_post_meta( $id, $key, s4u_sanitize_field( $value, $def ) );
				}
			}
			if ( count( $ids ) === 2 && ! $this->dry ) {
				s4u_link_translations( $ids['pt'], $ids['en'] );
			}
		}
	}

	private function upsert( $source, $type, $postarr ) {
		$existing = $this->find_by_source( $source, $type );
		if ( $this->dry ) {
			$this->items[ $source ] = $existing ?: -1;
			return $existing;
		}
		if ( $existing ) {
			$postarr['ID'] = $existing;
			wp_update_post( wp_slash( $postarr ) );
			$id = $existing;
			$this->log['updated']++;
		} else {
			$id = wp_insert_post( wp_slash( $postarr ), true );
			if ( is_wp_error( $id ) ) {
				WP_CLI::warning( "Failed $source: " . $id->get_error_message() );
				return 0;
			}
			update_post_meta( $id, '_s4u_source', $source );
			$this->log['created']++;
		}
		$this->items[ $source ] = (int) $id;
		return (int) $id;
	}

	private function placeholders( $html, $lang ) {
		return preg_replace_callback( '/\{\{(media|media-id|page|item):([^}]+)\}\}/', function ( $m ) use ( $lang ) {
			switch ( $m[1] ) {
				case 'media':
					$id = $this->media_id( $m[2] );
					return $id ? wp_make_link_relative( wp_get_attachment_url( $id ) ) : '#missing-media';
				case 'media-id':
					return (string) $this->media_id( $m[2] );
				case 'page':
					$id = $this->items[ 'page:' . $m[2] . ':' . $lang ] ?? 0;
					return $id ? wp_make_link_relative( get_permalink( $id ) ) : '#';
				case 'item':
					$id = $this->items[ $m[2] . ':' . $lang ] ?? 0;
					return $id ? wp_make_link_relative( get_permalink( $id ) ) : '#';
			}
			return $m[0];
		}, (string) $html );
	}

	/* ---------- Menus ---------- */

	private function import_menus( $menus ) {
		if ( $this->dry ) {
			return;
		}
		$locations = get_theme_mod( 'nav_menu_locations', array() );
		foreach ( $menus as $location => $items ) {
			$name = 'Sea4Us · ' . $location;
			$menu = wp_get_nav_menu_object( $name );
			$mid  = $menu ? $menu->term_id : wp_create_nav_menu( $name );
			foreach ( wp_get_nav_menu_items( $mid ) ?: array() as $old ) {
				wp_delete_post( $old->ID, true );
			}
			$lang = str_ends_with( $location, '_en' ) ? 'en' : 'pt';
			$this->menu_items( $mid, $items, 0, $lang );
			$locations[ $location ] = $mid;
		}
		set_theme_mod( 'nav_menu_locations', $locations );
	}

	private function menu_items( $menu_id, $items, $parent, $lang ) {
		foreach ( $items as $i => $item ) {
			$args = array(
				'menu-item-title'     => $item['label'],
				'menu-item-status'    => 'publish',
				'menu-item-parent-id' => $parent,
				'menu-item-position'  => $i + 1,
				'menu-item-classes'   => $item['class'] ?? '',
			);
			if ( ! empty( $item['page'] ) && ! empty( $this->items[ 'page:' . $item['page'] . ':' . $lang ] ) ) {
				$args['menu-item-type']      = 'post_type';
				$args['menu-item-object']    = 'page';
				$args['menu-item-object-id'] = $this->items[ 'page:' . $item['page'] . ':' . $lang ];
			} else {
				$args['menu-item-type'] = 'custom';
				$args['menu-item-url']  = $this->placeholders( $item['url'] ?? '#', $lang );
			}
			$id = wp_update_nav_menu_item( $menu_id, 0, $args );
			if ( ! empty( $item['children'] ) ) {
				$this->menu_items( $menu_id, $item['children'], $id, $lang );
			}
		}
	}
}

WP_CLI::add_command( 'sea4us', 'S4U_Import_Command' );
