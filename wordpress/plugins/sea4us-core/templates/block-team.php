<?php
/**
 * Team grid grouped as published (group field), in editorial order.
 */
$a     = $args['a'] ?? array();
$posts = s4u_query( 's4u_person', array( 'orderby' => array( 'menu_order' => 'ASC', 'title' => 'ASC' ), 'posts_per_page' => (int) ( $a['count'] ?? 0 ) ?: -1 ) );
if ( ! $posts ) {
	return;
}
if ( ! empty( $a['compact'] ) ) : ?>
	<div class="s4u-team s4u-team--compact">
		<?php foreach ( $posts as $p ) { echo s4u_render( 'card-person', array( 'post' => $p ) ); } // phpcs:ignore ?>
	</div>
	<?php
	return;
endif;
$groups = array();
foreach ( $posts as $p ) {
	$groups[ s4u_meta( $p->ID, 'group' ) ?: '' ][] = $p;
}
foreach ( $groups as $label => $people ) : ?>
	<section class="s4u-team-group">
		<?php if ( $label ) : ?><h2 class="s4u-h3 s4u-team-group__title"><?php echo esc_html( $label ); ?></h2><?php endif; ?>
		<div class="s4u-team">
			<?php foreach ( $people as $p ) { echo s4u_render( 'card-person', array( 'post' => $p ) ); } // phpcs:ignore ?>
		</div>
	</section>
<?php endforeach;
