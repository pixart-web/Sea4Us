<?php
/**
 * Two locations with their role. Map links open externally (no embedded map before consent).
 */
$lang = s4u_current_lang();
?>
<div class="s4u-locations">
	<?php foreach ( array( 1, 2 ) as $n ) :
		$name = s4u_setting( "loc{$n}_name_{$lang}" );
		if ( ! $name ) {
			continue;
		}
		$addr = s4u_setting( "loc{$n}_address" );
		$map  = s4u_setting( "loc{$n}_map" );
		?>
		<section class="s4u-location">
			<p class="s4u-location__index" aria-hidden="true">0<?php echo (int) $n; ?></p>
			<h3 class="s4u-location__name"><?php echo esc_html( $name ); ?></h3>
			<p class="s4u-location__role"><?php echo esc_html( s4u_setting( "loc{$n}_role_{$lang}" ) ); ?></p>
			<address class="s4u-location__addr"><?php echo nl2br( esc_html( $addr ) ); // phpcs:ignore ?></address>
			<?php if ( $map ) : ?>
				<a class="s4u-link-arrow" href="<?php echo esc_url( $map ); ?>" target="_blank" rel="noopener"><?php echo esc_html( s4u_pick( 'Ver no mapa', 'View on map' ) ); ?><span class="screen-reader-text"> — <?php echo esc_html( $name ); ?> (<?php s4u_e( 'external_link' ); ?>)</span> <?php echo s4u_icon( 'external' ); // phpcs:ignore ?></a>
			<?php endif; ?>
		</section>
	<?php endforeach; ?>
</div>
