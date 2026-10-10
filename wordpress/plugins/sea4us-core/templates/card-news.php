<?php
$p     = $args['post'];
$lang  = s4u_current_lang();
$topic = s4u_meta( $p->ID, 'topic', 'company' );
$ext   = s4u_meta( $p->ID, 'external_url' );
$thumb = get_post_thumbnail_id( $p );
$text  = $p->post_excerpt ?: wp_trim_words( wp_strip_all_tags( excerpt_remove_blocks( $p->post_content ) ), 26, '…' );
$year  = get_the_date( 'Y', $p );
$hay   = mb_strtolower( wp_strip_all_tags( get_the_title( $p ) . ' ' . $text ) );
?>
<article class="s4u-card s4u-card--news<?php echo $thumb ? '' : ' no-media'; ?>" data-year="<?php echo esc_attr( $year ); ?>" data-topic="<?php echo esc_attr( $topic ); ?>" data-text="<?php echo esc_attr( $hay ); ?>">
	<?php if ( $thumb ) : ?>
		<div class="s4u-card__media"><?php echo s4u_img( $thumb, 'medium_large', array( 'alt' => '', 'sizes' => '(min-width: 1024px) 30vw, (min-width: 640px) 45vw, 100vw' ) ); // phpcs:ignore ?></div>
	<?php endif; ?>
	<div class="s4u-card__body">
		<p class="s4u-meta"><time datetime="<?php echo esc_attr( get_the_date( 'Y-m-d', $p ) ); ?>"><?php echo esc_html( s4u_date( $p->post_date ) ); ?></time><span class="s4u-meta__sep" aria-hidden="true">·</span><span><?php echo esc_html( s4u_option_label( 'news_topic', $topic ) ); ?></span></p>
		<h3 class="s4u-card__title"><a class="s4u-card__link" href="<?php echo esc_url( get_permalink( $p ) ); ?>"><?php echo esc_html( get_the_title( $p ) ); ?></a></h3>
		<?php if ( $text ) : ?><p class="s4u-card__excerpt"><?php echo esc_html( $text ); ?></p><?php endif; ?>
		<?php if ( $ext ) : ?><p class="s4u-card__note"><?php echo esc_html( s4u_meta( $p->ID, 'external_source' ) ?: s4u_pick( 'Ligação para a imprensa', 'Press link' ) ); ?></p><?php endif; ?>
	</div>
</article>
