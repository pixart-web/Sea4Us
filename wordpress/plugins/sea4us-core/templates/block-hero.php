<?php
/**
 * Homepage hero: real photography (ocean + laboratory), headline and two actions.
 * Works fully with static images; motion is decorative and respects reduced motion.
 */
$a      = $args['a'] ?? array();
$lang   = s4u_current_lang();
$img    = (int) ( $a['imageId'] ?? 0 );
$img2   = (int) ( $a['secondaryImageId'] ?? 0 );
$credit = s4u_credit( $img );
?>
<section class="s4u-hero" aria-labelledby="s4u-hero-title">
	<div class="s4u-hero__media">
		<?php
		if ( $img ) {
			echo s4u_img( $img, 'full', array( 'loading' => 'eager', 'fetchpriority' => 'high', 'class' => 's4u-hero__img', 'sizes' => '100vw', 'alt' => '' ) ); // phpcs:ignore
		}
		?>
	</div>
	<div class="s4u-hero__veil" aria-hidden="true"></div>
	<div class="s4u-container s4u-hero__inner">
		<div class="s4u-hero__copy">
			<?php if ( ! empty( $a['eyebrow'] ) ) : ?>
				<p class="s4u-eyebrow s4u-eyebrow--light"><span class="s4u-eyebrow__rule" aria-hidden="true"></span><?php echo esc_html( $a['eyebrow'] ); ?></p>
			<?php endif; ?>
			<h1 id="s4u-hero-title" class="s4u-hero__title"><?php echo esc_html( $a['title'] ?? '' ); ?></h1>
			<?php if ( ! empty( $a['text'] ) ) : ?>
				<p class="s4u-hero__text"><?php echo esc_html( $a['text'] ); ?></p>
			<?php endif; ?>
			<div class="s4u-actions">
				<?php if ( ! empty( $a['cta1Label'] ) ) : ?>
					<a class="s4u-btn s4u-btn--primary" href="<?php echo esc_url( $a['cta1Url'] ); ?>"><?php echo esc_html( $a['cta1Label'] ); ?> <?php echo s4u_icon( 'arrow' ); // phpcs:ignore ?></a>
				<?php endif; ?>
				<?php if ( ! empty( $a['cta2Label'] ) ) : ?>
					<a class="s4u-btn s4u-btn--ghost" href="<?php echo esc_url( $a['cta2Url'] ); ?>"><?php echo esc_html( $a['cta2Label'] ); ?></a>
				<?php endif; ?>
			</div>
		</div>
		<?php if ( $img2 ) : ?>
			<figure class="s4u-hero__inset" aria-hidden="true">
				<?php echo s4u_img( $img2, 'large', array( 'loading' => 'eager', 'alt' => '', 'sizes' => '(min-width: 1024px) 28vw, 0px' ) ); // phpcs:ignore ?>
				<svg class="s4u-hero__trace" viewBox="0 0 400 240" preserveAspectRatio="none" aria-hidden="true" focusable="false"><path d="M0 230 C 120 230, 150 120, 260 110 S 380 40, 400 8" pathLength="1"/></svg>
			</figure>
		<?php endif; ?>
	</div>
	<?php if ( $credit || ! empty( $a['note'] ) ) : ?>
		<p class="s4u-hero__credit"><?php echo esc_html( trim( ( $credit ? s4u__( 'photo_credit' ) . ': ' . $credit : '' ) . ( ! empty( $a['note'] ) ? ' · ' . $a['note'] : '' ), ' ·' ) ); ?></p>
	<?php endif; ?>
</section>
