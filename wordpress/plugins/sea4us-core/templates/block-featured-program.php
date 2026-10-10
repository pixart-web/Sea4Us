<?php
$a  = $args['a'] ?? array();
$id = (int) ( $a['programId'] ?? 0 );
if ( $id ) {
	$id = s4u_get_translation( $id, s4u_current_lang() ) ?: $id;
} else {
	$featured = s4u_query( 's4u_program', array( 'posts_per_page' => 1, 'meta_query' => array( array( 'key' => 'featured', 'value' => '1' ) ), 'orderby' => 'menu_order', 'order' => 'ASC' ) );
	$id       = $featured ? $featured[0]->ID : 0;
}
if ( ! $id ) {
	return;
}
$post = get_post( $id );
?>
<section class="s4u-section s4u-section--deep s4u-feature" aria-labelledby="s4u-feature-title">
	<div class="s4u-container s4u-feature__grid">
		<div class="s4u-feature__intro">
			<p class="s4u-eyebrow s4u-eyebrow--light"><span class="s4u-eyebrow__rule" aria-hidden="true"></span><?php echo esc_html( $a['eyebrow'] ?: s4u_pick( 'Programa em destaque', 'Featured programme' ) ); ?></p>
			<h2 id="s4u-feature-title" class="s4u-h2"><?php echo esc_html( get_the_title( $post ) ); ?></h2>
			<p class="s4u-lead"><?php echo esc_html( $post->post_excerpt ); ?></p>
			<a class="s4u-btn s4u-btn--primary" href="<?php echo esc_url( get_permalink( $post ) ); ?>"><?php s4u_e( 'see_programme' ); ?> <?php echo s4u_icon( 'arrow' ); // phpcs:ignore ?></a>
		</div>
		<?php echo s4u_render( 'program-sheet', array( 'id' => $id, 'full' => true ) ); // phpcs:ignore ?>
	</div>
</section>
