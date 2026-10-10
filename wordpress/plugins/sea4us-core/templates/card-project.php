<?php
$p       = $args['post'];
$status  = s4u_meta( $p->ID, 'status', 'unknown' );
$code    = s4u_meta( $p->ID, 'code' );
$fund    = s4u_meta( $p->ID, 'funding_programme' );
$start   = s4u_meta( $p->ID, 'start_date' );
$end     = s4u_meta( $p->ID, 'end_date' );
$text    = $p->post_excerpt ?: wp_trim_words( wp_strip_all_tags( excerpt_remove_blocks( $p->post_content ) ), 22, '…' );
$hay     = mb_strtolower( wp_strip_all_tags( get_the_title( $p ) . ' ' . $code . ' ' . $fund . ' ' . $text . ' ' . s4u_meta( $p->ID, 'full_title' ) ) );
?>
<article class="s4u-card s4u-card--project" data-status="<?php echo esc_attr( $status ); ?>" data-funding="<?php echo esc_attr( sanitize_title( $fund ) ); ?>" data-text="<?php echo esc_attr( $hay ); ?>">
	<div class="s4u-card__body">
		<p class="s4u-card__top">
			<span class="s4u-badge s4u-badge--<?php echo esc_attr( $status ); ?>"><?php echo esc_html( s4u_option_label( 'project_status', $status ) ); ?></span>
			<?php if ( $fund ) : ?><span class="s4u-card__fund"><?php echo esc_html( $fund ); ?></span><?php endif; ?>
		</p>
		<h3 class="s4u-card__title"><a class="s4u-card__link" href="<?php echo esc_url( get_permalink( $p ) ); ?>"><?php echo esc_html( get_the_title( $p ) ); ?></a></h3>
		<?php if ( $text ) : ?><p class="s4u-card__excerpt"><?php echo esc_html( $text ); ?></p><?php endif; ?>
		<dl class="s4u-card__facts">
			<?php if ( $code ) : ?><div><dt><?php s4u_e( 'code' ); ?></dt><dd><?php echo esc_html( $code ); ?></dd></div><?php endif; ?>
			<?php if ( $start || $end ) : ?><div><dt><?php s4u_e( 'period' ); ?></dt><dd><?php echo esc_html( trim( $start . ' – ' . $end, ' –' ) ); ?></dd></div><?php endif; ?>
		</dl>
	</div>
</article>
