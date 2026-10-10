<?php
/**
 * Editorial "scientific sheet" for a programme. Single source: the programme post.
 */
$id    = (int) ( $args['id'] ?? 0 );
$full  = ! empty( $args['full'] );
if ( ! $id ) {
	return;
}
$stage    = s4u_meta( $id, 'stage', 'unconfirmed' );
$code     = s4u_meta( $id, 'code' );
$nature   = s4u_meta( $id, 'nature' );
$ind      = s4u_meta( $id, 'indication' );
$area     = s4u_meta( $id, 'area' );
$mil      = s4u_meta( $id, 'latest_milestone' );
$mil_date = s4u_meta( $id, 'milestone_date' );
$mil_src  = s4u_meta( $id, 'milestone_source' );
$src_date = s4u_meta( $id, 'stage_source_date' );
$review   = s4u_meta( $id, 'review_status', 'pending' );
$qual     = s4u_meta( $id, 'qualification' );
$title    = get_the_title( $id );
?>
<article class="s4u-sheet" aria-labelledby="s4u-sheet-<?php echo (int) $id; ?>">
	<header class="s4u-sheet__head">
		<p class="s4u-sheet__kicker"><?php s4u_e( 'programme' ); ?></p>
		<h3 class="s4u-sheet__code" id="s4u-sheet-<?php echo (int) $id; ?>"><?php echo esc_html( $code ?: $title ); ?></h3>
		<?php if ( $nature ) : ?><p class="s4u-sheet__nature"><?php echo esc_html( $nature ); ?></p><?php endif; ?>
	</header>
	<dl class="s4u-sheet__rows">
		<?php if ( $area || $ind ) : ?>
			<div><dt><?php s4u_e( 'indication' ); ?></dt><dd><?php echo esc_html( trim( $area . ( $area && $ind ? ' · ' : '' ) . $ind ) ); ?></dd></div>
		<?php endif; ?>
		<?php if ( $mil ) : ?>
			<div><dt><?php s4u_e( 'latest_milestone' ); ?></dt><dd><?php echo esc_html( $mil ); ?><?php if ( $mil_date ) : ?> <time datetime="<?php echo esc_attr( $mil_date ); ?>" class="s4u-sheet__date"><?php echo esc_html( s4u_date( $mil_date ) ); ?></time><?php endif; ?></dd></div>
		<?php endif; ?>
		<?php if ( $mil_src ) : ?>
			<div><dt><?php s4u_e( 'source' ); ?></dt><dd><a href="<?php echo esc_url( $mil_src ); ?>"><?php echo esc_html( s4u_pick( 'Comunicado da Sea4Us', 'Sea4Us announcement' ) ); ?></a><?php if ( $src_date ) : ?> · <?php echo esc_html( s4u__( 'source_date' ) . ': ' . s4u_date( $src_date ) ); ?><?php endif; ?></dd></div>
		<?php endif; ?>
	</dl>
	<?php echo s4u_render( 'stage-track', array( 'stage' => $stage ) ); // phpcs:ignore ?>
	<?php if ( $qual ) : ?>
		<p class="s4u-sheet__note"><?php echo s4u_icon( 'info' ); // phpcs:ignore ?><span><?php echo esc_html( $qual ); ?></span></p>
	<?php endif; ?>
	<?php if ( 'approved' !== $review ) : ?>
		<p class="s4u-review-flag"><?php echo esc_html( s4u_option_label( 'review', $review ) ); ?></p>
	<?php endif; ?>
	<?php if ( ! $full ) : ?>
		<p class="s4u-sheet__more"><a class="s4u-link-arrow" href="<?php echo esc_url( get_permalink( $id ) ); ?>"><?php s4u_e( 'see_programme' ); ?><span class="screen-reader-text"> <?php echo esc_html( $title ); ?></span> <?php echo s4u_icon( 'arrow' ); // phpcs:ignore ?></a></p>
	<?php endif; ?>
</article>
