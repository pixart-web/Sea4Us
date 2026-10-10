<?php
/**
 * Co-funding logos in their original order, proportions and colours.
 */
$ids = s4u_ids( s4u_setting( 'funding_strip' ) );
if ( ! $ids ) {
	return;
}
?>
<section class="s4u-funding" aria-label="<?php s4u_e( 'funded_by' ); ?>">
	<div class="s4u-container">
		<p class="s4u-funding__title"><?php s4u_e( 'funded_by' ); ?></p>
		<ul class="s4u-funding__logos">
			<?php foreach ( $ids as $id ) : ?>
				<li><?php echo s4u_img( $id, 'medium_large', array( 'sizes' => '(min-width: 1024px) 320px, 80vw' ) ); // phpcs:ignore ?></li>
			<?php endforeach; ?>
		</ul>
	</div>
</section>
