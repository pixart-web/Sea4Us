<?php
/**
 * Team profile: name, photo, role and biography as published, with public links.
 */
get_header();
the_post();
$id     = get_the_ID();
$thumb  = get_post_thumbnail_id();
$links  = array_filter( array_map( 'trim', explode( "\n", s4u_meta( $id, 'links' ) ) ) );
$credit = s4u_meta( $id, 'photo_credit' ) ?: s4u_credit( $thumb );
?>
<main id="s4u-main" class="s4u-main" tabindex="-1">
	<article class="s4u-container s4u-profile">
		<div class="s4u-profile__photo">
			<?php if ( $thumb ) : ?>
				<figure>
					<?php echo s4u_img( $thumb, 'large', array( 'loading' => 'eager', 'alt' => get_the_title(), 'sizes' => '(min-width: 1024px) 420px, 100vw' ) ); // phpcs:ignore ?>
					<?php if ( $credit ) : ?><figcaption class="s4u-credit"><?php echo esc_html( s4u__( 'photo_credit' ) . ': ' . $credit ); ?></figcaption><?php endif; ?>
				</figure>
			<?php endif; ?>
		</div>
		<div class="s4u-profile__body">
			<?php echo s4u_render( 'breadcrumbs' ); // phpcs:ignore ?>
			<h1 class="s4u-profile__name"><?php the_title(); ?></h1>
			<?php if ( s4u_meta( $id, 'role' ) ) : ?><p class="s4u-profile__role"><?php echo esc_html( s4u_meta( $id, 'role' ) ); ?></p><?php endif; ?>
			<?php if ( s4u_meta( $id, 'cofounder' ) ) : ?><p class="s4u-person__tag"><?php echo esc_html( s4u_pick( 'Cofundador(a)', 'Co-founder' ) ); ?></p><?php endif; ?>
			<div class="s4u-prose"><?php the_content(); ?></div>
			<?php if ( $links ) : ?>
				<ul class="s4u-profile__links">
					<?php foreach ( $links as $line ) :
						$parts = array_map( 'trim', explode( '|', $line ) );
						$url   = end( $parts );
						$label = count( $parts ) > 1 ? $parts[0] : wp_parse_url( $url, PHP_URL_HOST );
						?>
						<li><a href="<?php echo esc_url( $url ); ?>" target="_blank" rel="noopener"><?php echo esc_html( $label ); ?> <?php echo s4u_icon( 'external' ); // phpcs:ignore ?><span class="screen-reader-text"> (<?php s4u_e( 'external_link' ); ?>)</span></a></li>
					<?php endforeach; ?>
				</ul>
			<?php endif; ?>
			<p><a class="s4u-link-arrow s4u-link-arrow--back" href="<?php echo esc_url( s4u_page_url( 'team' ) ); ?>"><?php s4u_e( 'back_to_team' ); ?></a></p>
		</div>
	</article>
</main>
<?php
get_footer();
