<?php
/**
 * Inner page header: title, short introduction and optional photograph.
 */
$a     = $args['a'] ?? array();
$img   = (int) ( $a['imageId'] ?? 0 );
$tone  = in_array( $a['tone'] ?? 'dark', array( 'dark', 'light' ), true ) ? $a['tone'] : 'dark';
$title = $a['title'] ?? '';
if ( '' === $title && is_singular() ) {
	$title = get_the_title();
}
$credit = s4u_credit( $img );
?>
<header class="s4u-page-hero s4u-page-hero--<?php echo esc_attr( $tone ); ?><?php echo $img ? ' has-image' : ''; ?>">
	<div class="s4u-container s4u-page-hero__inner">
		<div class="s4u-page-hero__copy">
			<?php echo s4u_render( 'breadcrumbs' ); // phpcs:ignore ?>
			<?php if ( ! empty( $a['eyebrow'] ) ) : ?>
				<p class="s4u-eyebrow"><span class="s4u-eyebrow__rule" aria-hidden="true"></span><?php echo esc_html( $a['eyebrow'] ); ?></p>
			<?php endif; ?>
			<h1 class="s4u-page-hero__title"><?php echo esc_html( $title ); ?></h1>
			<?php if ( ! empty( $a['text'] ) ) : ?>
				<p class="s4u-page-hero__text"><?php echo esc_html( $a['text'] ); ?></p>
			<?php endif; ?>
		</div>
		<?php if ( $img ) : ?>
			<figure class="s4u-page-hero__figure">
				<?php echo s4u_img( $img, 'large', array( 'loading' => 'eager', 'fetchpriority' => 'high', 'sizes' => '(min-width: 1024px) 40vw, 100vw' ) ); // phpcs:ignore ?>
				<?php if ( $credit ) : ?>
					<figcaption class="s4u-credit"><?php echo esc_html( s4u__( 'photo_credit' ) . ': ' . $credit ); ?></figcaption>
				<?php endif; ?>
			</figure>
		<?php endif; ?>
	</div>
</header>
