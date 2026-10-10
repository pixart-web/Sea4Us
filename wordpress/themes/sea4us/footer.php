<?php
/**
 * Footer: co-funding strip, statement, navigation, contacts, locations, documents and credits.
 */
$lang = s4u_current_lang();
echo s4u_render( 'block-funding-strip' ); // phpcs:ignore
?>
<footer class="s4u-footer">
	<div class="s4u-container s4u-footer__grid">
		<div class="s4u-footer__brand">
			<img src="<?php echo esc_url( get_template_directory_uri() . '/assets/img/logo-sea4us-white.png' ); ?>" width="80" height="86" alt="Sea4Us" loading="lazy">
			<p class="s4u-footer__statement"><?php echo esc_html( s4u_pick( 'Biotecnologia de origem marinha dedicada à descoberta e desenvolvimento de novos compostos para necessidades clínicas não satisfeitas.', 'Marine-inspired biotechnology dedicated to the discovery and development of new compounds for unmet clinical needs.' ) ); ?></p>
			<ul class="s4u-social" aria-label="<?php s4u_e( 'follow' ); ?>">
				<?php foreach ( array( 'linkedin' => 'LinkedIn', 'facebook' => 'Facebook', 'instagram' => 'Instagram', 'youtube' => 'YouTube' ) as $k => $label ) :
					$url = s4u_setting( $k );
					if ( ! $url ) {
						continue;
					}
					?>
					<li><a href="<?php echo esc_url( $url ); ?>" target="_blank" rel="noopener"><?php echo s4u_icon( $k ); // phpcs:ignore ?><span class="screen-reader-text"><?php echo esc_html( $label . ' (' . s4u__( 'external_link' ) . ')' ); ?></span></a></li>
				<?php endforeach; ?>
			</ul>
		</div>
		<nav class="s4u-footer__col" aria-label="<?php s4u_e( 'footer_nav' ); ?>">
			<h2 class="s4u-footer__h"><?php echo esc_html( s4u_pick( 'Explorar', 'Explore' ) ); ?></h2>
			<?php s4u_theme_menu( 'footer', 's4u-footer__list', 1 ); ?>
		</nav>
		<div class="s4u-footer__col">
			<h2 class="s4u-footer__h"><?php s4u_e( 'contacts' ); ?></h2>
			<?php echo s4u_render( 'block-contact-details' ); // phpcs:ignore ?>
		</div>
		<div class="s4u-footer__col">
			<h2 class="s4u-footer__h"><?php echo esc_html( s4u_pick( 'Localizações', 'Locations' ) ); ?></h2>
			<ul class="s4u-footer__locs">
				<?php foreach ( array( 1, 2 ) as $n ) :
					$name = s4u_setting( "loc{$n}_name_{$lang}" );
					if ( ! $name ) {
						continue;
					}
					?>
					<li><strong><?php echo esc_html( $name ); ?></strong><address><?php echo nl2br( esc_html( s4u_setting( "loc{$n}_address" ) ) ); // phpcs:ignore ?></address></li>
				<?php endforeach; ?>
			</ul>
		</div>
	</div>
	<div class="s4u-container s4u-footer__bottom">
		<?php s4u_theme_menu( 'legal', 's4u-footer__legal', 1 ); ?>
		<p class="s4u-footer__credits"><?php echo esc_html( s4u__( 'image_credits' ) . ': ' . s4u_setting( 'credits' ) ); ?></p>
		<p class="s4u-footer__copy">© <?php echo esc_html( gmdate( 'Y' ) ); ?> Sea4Us. <?php s4u_e( 'rights' ); ?></p>
	</div>
</footer>
<?php wp_footer(); ?>
</body>
</html>
