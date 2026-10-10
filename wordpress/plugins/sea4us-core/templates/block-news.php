<?php
/**
 * News: latest N (homepage) or the complete archive with filters (archive=true).
 * Ordered by the real publication date.
 */
$a       = $args['a'] ?? array();
$archive = ! empty( $a['archive'] );
$count   = (int) ( $a['count'] ?? 3 );
$q       = array( 'orderby' => 'date', 'order' => 'DESC' );
if ( ! $archive ) {
	$q['posts_per_page'] = $count ?: 3;
}
if ( ! empty( $a['topic'] ) ) {
	$q['meta_query'] = array( array( 'key' => 'topic', 'value' => $a['topic'] ) );
}
$posts = s4u_query( 's4u_news', $q );
if ( ! $posts ) {
	echo '<p class="s4u-empty">' . esc_html( s4u__( 'no_items' ) ) . '</p>';
	return;
}
if ( ! $archive ) : ?>
	<div class="s4u-cards s4u-cards--3">
		<?php foreach ( $posts as $p ) { echo s4u_render( 'card-news', array( 'post' => $p ) ); } // phpcs:ignore ?>
	</div>
	<?php
	return;
endif;

$years  = array_values( array_unique( array_map( fn( $p ) => get_the_date( 'Y', $p ), $posts ) ) );
$topics = array_values( array_unique( array_map( fn( $p ) => s4u_meta( $p->ID, 'topic', 'company' ), $posts ) ) );
$uid    = 's4u-news-' . wp_unique_id();
?>
<div class="s4u-listing" data-s4u-listing data-page-size="12" id="<?php echo esc_attr( $uid ); ?>">
	<form class="s4u-filters" role="search" aria-label="<?php s4u_e( 'filters' ); ?>" data-s4u-filters hidden>
		<div class="s4u-field s4u-field--search">
			<label for="<?php echo esc_attr( $uid ); ?>-q"><?php s4u_e( 'search' ); ?></label>
			<input type="search" id="<?php echo esc_attr( $uid ); ?>-q" name="q" data-filter="text" autocomplete="off">
		</div>
		<div class="s4u-field">
			<label for="<?php echo esc_attr( $uid ); ?>-y"><?php s4u_e( 'year' ); ?></label>
			<select id="<?php echo esc_attr( $uid ); ?>-y" data-filter="year">
				<option value=""><?php s4u_e( 'all_years' ); ?></option>
				<?php foreach ( $years as $y ) : ?><option value="<?php echo esc_attr( $y ); ?>"><?php echo esc_html( $y ); ?></option><?php endforeach; ?>
			</select>
		</div>
		<fieldset class="s4u-chips">
			<legend><?php s4u_e( 'category' ); ?></legend>
			<label class="s4u-chip"><input type="radio" name="<?php echo esc_attr( $uid ); ?>-t" value="" data-filter="topic" checked><span><?php s4u_e( 'all' ); ?></span></label>
			<?php foreach ( $topics as $t ) : ?>
				<label class="s4u-chip"><input type="radio" name="<?php echo esc_attr( $uid ); ?>-t" value="<?php echo esc_attr( $t ); ?>" data-filter="topic"><span><?php echo esc_html( s4u_option_label( 'news_topic', $t ) ); ?></span></label>
			<?php endforeach; ?>
		</fieldset>
		<button type="reset" class="s4u-btn s4u-btn--text" data-s4u-reset><?php s4u_e( 'clear_filters' ); ?></button>
	</form>
	<p class="s4u-listing__count" role="status" aria-live="polite" data-s4u-count><?php echo esc_html( count( $posts ) . ' ' . ( 1 === count( $posts ) ? s4u__( 'result' ) : s4u__( 'results' ) ) ); ?></p>
	<div class="s4u-cards s4u-cards--3" data-s4u-items>
		<?php foreach ( $posts as $p ) { echo s4u_render( 'card-news', array( 'post' => $p ) ); } // phpcs:ignore ?>
	</div>
	<p class="s4u-empty" data-s4u-empty hidden><?php s4u_e( 'no_items' ); ?></p>
	<div class="s4u-listing__more"><button type="button" class="s4u-btn s4u-btn--outline" data-s4u-more hidden><?php s4u_e( 'load_more' ); ?></button></div>
</div>
