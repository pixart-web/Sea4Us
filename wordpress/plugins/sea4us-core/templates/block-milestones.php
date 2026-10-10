<?php
/**
 * Short sequence of confirmed historical milestones (news posts flagged as milestones).
 */
$a     = $args['a'] ?? array();
$posts = s4u_query( 's4u_news', array(
	'posts_per_page' => (int) ( $a['count'] ?? 5 ) ?: 5,
	'orderby'        => 'date',
	'order'          => 'DESC',
	'meta_query'     => array( array( 'key' => 'milestone', 'value' => '1' ) ),
) );
if ( ! $posts ) {
	return;
}
$posts = array_reverse( $posts );
?>
<ol class="s4u-milestones">
	<?php foreach ( $posts as $p ) : ?>
		<li class="s4u-milestones__item">
			<time class="s4u-milestones__date" datetime="<?php echo esc_attr( get_the_date( 'Y-m-d', $p ) ); ?>"><span class="s4u-milestones__year"><?php echo esc_html( get_the_date( 'Y', $p ) ); ?></span> <span class="s4u-milestones__month"><?php echo esc_html( s4u_date( $p->post_date, null, 'month' ) ); ?></span></time>
			<p class="s4u-milestones__label"><a href="<?php echo esc_url( get_permalink( $p ) ); ?>"><?php echo esc_html( s4u_meta( $p->ID, 'milestone_label' ) ?: get_the_title( $p ) ); ?></a></p>
		</li>
	<?php endforeach; ?>
</ol>
