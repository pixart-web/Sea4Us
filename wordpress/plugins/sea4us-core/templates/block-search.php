<?php
/**
 * Search page: server-rendered results for ?q= (no JavaScript needed), enhanced with
 * instant results from the language's JSON index.
 */
$lang = s4u_current_lang();
$q    = isset( $_GET['q'] ) ? sanitize_text_field( wp_unslash( $_GET['q'] ) ) : ''; // phpcs:ignore WordPress.Security.NonceVerification
$ids  = $q ? s4u_search( $q, $lang ) : array();
$uid  = 's4u-search-' . wp_unique_id();
?>
<div class="s4u-search" data-s4u-search data-index="<?php echo esc_url( s4u_search_index_url( $lang ) ); ?>">
	<form class="s4u-search__form" role="search" method="get" action="<?php echo esc_url( s4u_page_url( 'search', $lang ) ); ?>">
		<label for="<?php echo esc_attr( $uid ); ?>" class="s4u-search__label"><?php s4u_e( 'search_site' ); ?></label>
		<div class="s4u-search__row">
			<input type="search" id="<?php echo esc_attr( $uid ); ?>" name="q" value="<?php echo esc_attr( $q ); ?>" placeholder="<?php s4u_e( 'search_placeholder' ); ?>" autocomplete="off" data-s4u-search-input>
			<button type="submit" class="s4u-btn s4u-btn--primary"><?php echo s4u_icon( 'search' ); // phpcs:ignore ?><span><?php s4u_e( 'search' ); ?></span></button>
		</div>
	</form>
	<p class="s4u-search__status" role="status" aria-live="polite" data-s4u-search-status>
		<?php
		if ( $q ) {
			echo esc_html( count( $ids ) . ' ' . ( 1 === count( $ids ) ? s4u__( 'result' ) : s4u__( 'results' ) ) . ' — ' . s4u__( 'search_results_for' ) . ' “' . $q . '”' );
		}
		?>
	</p>
	<ol class="s4u-search__results" data-s4u-search-results>
		<?php foreach ( $ids as $id ) :
			$p = get_post( $id );
			?>
			<li class="s4u-result">
				<p class="s4u-meta"><span><?php echo esc_html( s4u_type_label( $p->post_type ) ); ?></span><?php if ( 's4u_news' === $p->post_type ) : ?><span class="s4u-meta__sep" aria-hidden="true">·</span><time datetime="<?php echo esc_attr( get_the_date( 'Y-m-d', $p ) ); ?>"><?php echo esc_html( s4u_date( $p->post_date ) ); ?></time><?php endif; ?></p>
				<h2 class="s4u-result__title"><a href="<?php echo esc_url( get_permalink( $p ) ); ?>"><?php echo esc_html( get_the_title( $p ) ); ?></a></h2>
				<p class="s4u-result__text"><?php echo esc_html( $p->post_excerpt ?: wp_trim_words( wp_strip_all_tags( excerpt_remove_blocks( $p->post_content ) ), 30, '…' ) ); ?></p>
			</li>
		<?php endforeach; ?>
	</ol>
	<div class="s4u-search__empty" data-s4u-search-empty<?php echo ( $q && ! $ids ) ? '' : ' hidden'; ?>>
		<p><?php s4u_e( 'search_none' ); ?></p>
		<ul class="s4u-search__suggest">
			<?php foreach ( array( 'science', 'programs', 'projects', 'news', 'partnering' ) as $k ) :
				$pid = s4u_page_by_key( $k, $lang );
				if ( $pid ) : ?>
					<li><a href="<?php echo esc_url( get_permalink( $pid ) ); ?>"><?php echo esc_html( get_the_title( $pid ) ); ?></a></li>
				<?php endif;
			endforeach; ?>
		</ul>
	</div>
	<script type="application/json" data-s4u-search-messages><?php echo wp_json_encode( array( 'results' => s4u__( 'results' ), 'result' => s4u__( 'result' ), 'for' => s4u__( 'search_results_for' ), 'hint' => s4u__( 'search_hint' ) ), JSON_UNESCAPED_UNICODE | JSON_HEX_TAG ); ?></script>
</div>
