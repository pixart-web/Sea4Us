<?php
$trail = s4u_breadcrumbs();
if ( count( $trail ) < 2 ) {
	return;
}
?>
<nav class="s4u-breadcrumbs" aria-label="<?php s4u_e( 'breadcrumbs' ); ?>">
	<ol>
		<?php foreach ( $trail as $i => $crumb ) : ?>
			<li>
				<?php if ( $i === count( $trail ) - 1 ) : ?>
					<span aria-current="page"><?php echo esc_html( wp_strip_all_tags( $crumb[0] ) ); ?></span>
				<?php else : ?>
					<a href="<?php echo esc_url( $crumb[1] ); ?>"><?php echo esc_html( wp_strip_all_tags( $crumb[0] ) ); ?></a>
				<?php endif; ?>
			</li>
		<?php endforeach; ?>
	</ol>
</nav>
