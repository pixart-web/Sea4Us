<?php
/**
 * Localised 404 (language taken from the URL prefix).
 */
status_header( 404 );
get_header();
$lang = s4u_current_lang();
?>
<main id="s4u-main" class="s4u-main" tabindex="-1">
	<section class="s4u-notfound">
		<div class="s4u-container s4u-container--narrow">
			<p class="s4u-notfound__code" aria-hidden="true">404</p>
			<h1 class="s4u-page-hero__title"><?php s4u_e( 'not_found_title' ); ?></h1>
			<p class="s4u-lead"><?php s4u_e( 'not_found_text' ); ?></p>
			<form class="s4u-search__form" role="search" method="get" action="<?php echo esc_url( s4u_page_url( 'search', $lang ) ); ?>">
				<label for="s4u-404-q" class="s4u-search__label"><?php s4u_e( 'search_site' ); ?></label>
				<div class="s4u-search__row">
					<input type="search" id="s4u-404-q" name="q" placeholder="<?php s4u_e( 'search_placeholder' ); ?>">
					<button type="submit" class="s4u-btn s4u-btn--primary"><?php echo s4u_icon( 'search' ); // phpcs:ignore ?><span><?php s4u_e( 'search' ); ?></span></button>
				</div>
			</form>
			<ul class="s4u-notfound__links">
				<?php foreach ( array( 'science', 'programs', 'projects', 'news', 'contact' ) as $k ) :
					$pid = s4u_page_by_key( $k, $lang );
					if ( $pid ) : ?>
						<li><a class="s4u-link-arrow" href="<?php echo esc_url( get_permalink( $pid ) ); ?>"><?php echo esc_html( get_the_title( $pid ) ); ?> <?php echo s4u_icon( 'arrow' ); // phpcs:ignore ?></a></li>
					<?php endif;
				endforeach; ?>
			</ul>
		</div>
	</section>
</main>
<?php
get_footer();
