<?php
/**
 * Partners and collaborations, keeping the distinction made by the source.
 * Logos are shown in their original colours, on neutral tiles.
 */
$a     = $args['a'] ?? array();
$types = $a['type'] ? array( $a['type'] ) : array_keys( s4u_option_sets()['partner_type'] );
foreach ( $types as $type ) :
	$posts = s4u_query( 's4u_partner', array( 'orderby' => array( 'menu_order' => 'ASC', 'title' => 'ASC' ), 'meta_query' => array( array( 'key' => 'type', 'value' => $type ) ) ) );
	if ( ! $posts ) {
		continue;
	}
	?>
	<section class="s4u-partners" aria-labelledby="s4u-partners-<?php echo esc_attr( $type ); ?>">
		<?php if ( '-' !== ( $a['heading'] ?? '' ) ) : // "-" hides the heading when the section already has one. ?>
		<h2 class="s4u-h3" id="s4u-partners-<?php echo esc_attr( $type ); ?>"><?php echo esc_html( $a['heading'] ?: s4u_option_label( 'partner_type', $type ) ); ?></h2>
		<?php endif; ?>
		<ul class="s4u-partners__grid">
			<?php foreach ( $posts as $p ) :
				$url  = s4u_meta( $p->ID, 'url' );
				$logo = get_post_thumbnail_id( $p );
				$desc = $p->post_excerpt;
				?>
				<li class="s4u-partner">
					<?php if ( $url ) : ?><a class="s4u-partner__link" href="<?php echo esc_url( $url ); ?>" target="_blank" rel="noopener"><?php endif; ?>
						<span class="s4u-partner__logo">
							<?php echo $logo ? s4u_img( $logo, 'medium', array( 'alt' => get_the_title( $p ) ) ) : '<span class="s4u-partner__name-only">' . esc_html( get_the_title( $p ) ) . '</span>'; // phpcs:ignore ?>
						</span>
						<span class="s4u-partner__name"><?php echo esc_html( get_the_title( $p ) ); ?><?php if ( $url ) : ?><span class="screen-reader-text"> (<?php s4u_e( 'external_link' ); ?>)</span><?php endif; ?></span>
					<?php if ( $url ) : ?></a><?php endif; ?>
					<?php if ( $desc ) : ?><p class="s4u-partner__desc"><?php echo esc_html( $desc ); ?></p><?php endif; ?>
				</li>
			<?php endforeach; ?>
		</ul>
	</section>
<?php endforeach;
