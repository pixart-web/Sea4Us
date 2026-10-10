<?php
$p     = $args['post'];
$role  = s4u_meta( $p->ID, 'role' );
$thumb = get_post_thumbnail_id( $p );
?>
<article class="s4u-person">
	<div class="s4u-person__photo">
		<?php if ( $thumb ) : ?>
			<?php echo s4u_img( $thumb, 'medium_large', array( 'alt' => '', 'sizes' => '(min-width: 1024px) 22vw, (min-width: 640px) 30vw, 50vw' ) ); // phpcs:ignore ?>
		<?php else : ?>
			<span class="s4u-person__initials" aria-hidden="true"><?php echo esc_html( mb_substr( get_the_title( $p ), 0, 1 ) ); ?></span>
		<?php endif; ?>
	</div>
	<h3 class="s4u-person__name"><a class="s4u-card__link" href="<?php echo esc_url( get_permalink( $p ) ); ?>"><?php echo esc_html( get_the_title( $p ) ); ?></a></h3>
	<?php if ( $role ) : ?><p class="s4u-person__role"><?php echo esc_html( $role ); ?></p><?php endif; ?>
	<?php if ( s4u_meta( $p->ID, 'cofounder' ) ) : ?><p class="s4u-person__tag"><?php echo esc_html( s4u_pick( 'Cofundador(a)', 'Co-founder' ) ); ?></p><?php endif; ?>
</article>
