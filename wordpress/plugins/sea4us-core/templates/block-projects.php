<?php
/**
 * Projects and public funding: highlighted selection or complete filterable index.
 */
$a     = $args['a'] ?? array();
$index = ! empty( $a['index'] );
$q     = array( 'orderby' => array( 'menu_order' => 'ASC', 'title' => 'ASC' ) );
if ( ! empty( $a['highlightOnly'] ) ) {
	$q['meta_query'] = array( array( 'key' => 'highlight', 'value' => '1' ) );
}
if ( ! empty( $a['count'] ) ) {
	$q['posts_per_page'] = (int) $a['count'];
}
$posts = s4u_query( 's4u_project', $q );
if ( ! $posts ) {
	echo '<p class="s4u-empty">' . esc_html( s4u__( 'no_items' ) ) . '</p>';
	return;
}
if ( ! $index ) : ?>
	<div class="s4u-cards s4u-cards--3">
		<?php foreach ( $posts as $p ) { echo s4u_render( 'card-project', array( 'post' => $p ) ); } // phpcs:ignore ?>
	</div>
	<?php
	return;
endif;
$funds = array();
foreach ( $posts as $p ) {
	$f = s4u_meta( $p->ID, 'funding_programme' );
	if ( $f ) {
		$funds[ sanitize_title( $f ) ] = $f;
	}
}
asort( $funds );
$statuses = array_values( array_unique( array_map( fn( $p ) => s4u_meta( $p->ID, 'status', 'unknown' ), $posts ) ) );
$uid      = 's4u-proj-' . wp_unique_id();
?>
<div class="s4u-listing" data-s4u-listing data-page-size="0" id="<?php echo esc_attr( $uid ); ?>">
	<form class="s4u-filters" role="search" aria-label="<?php s4u_e( 'filters' ); ?>" data-s4u-filters hidden>
		<div class="s4u-field s4u-field--search">
			<label for="<?php echo esc_attr( $uid ); ?>-q"><?php s4u_e( 'search' ); ?></label>
			<input type="search" id="<?php echo esc_attr( $uid ); ?>-q" data-filter="text" autocomplete="off">
		</div>
		<div class="s4u-field">
			<label for="<?php echo esc_attr( $uid ); ?>-f"><?php s4u_e( 'funding_programme' ); ?></label>
			<select id="<?php echo esc_attr( $uid ); ?>-f" data-filter="funding">
				<option value=""><?php s4u_e( 'all' ); ?></option>
				<?php foreach ( $funds as $k => $label ) : ?><option value="<?php echo esc_attr( $k ); ?>"><?php echo esc_html( $label ); ?></option><?php endforeach; ?>
			</select>
		</div>
		<fieldset class="s4u-chips">
			<legend><?php s4u_e( 'status' ); ?></legend>
			<label class="s4u-chip"><input type="radio" name="<?php echo esc_attr( $uid ); ?>-s" value="" data-filter="status" checked><span><?php s4u_e( 'all' ); ?></span></label>
			<?php foreach ( $statuses as $s ) : ?>
				<label class="s4u-chip"><input type="radio" name="<?php echo esc_attr( $uid ); ?>-s" value="<?php echo esc_attr( $s ); ?>" data-filter="status"><span><?php echo esc_html( s4u_option_label( 'project_status', $s ) ); ?></span></label>
			<?php endforeach; ?>
		</fieldset>
		<button type="reset" class="s4u-btn s4u-btn--text" data-s4u-reset><?php s4u_e( 'clear_filters' ); ?></button>
	</form>
	<p class="s4u-listing__count" role="status" aria-live="polite" data-s4u-count><?php echo esc_html( count( $posts ) . ' ' . s4u__( 'results' ) ); ?></p>
	<div class="s4u-cards s4u-cards--3" data-s4u-items>
		<?php foreach ( $posts as $p ) { echo s4u_render( 'card-project', array( 'post' => $p ) ); } // phpcs:ignore ?>
	</div>
	<p class="s4u-empty" data-s4u-empty hidden><?php s4u_e( 'no_items' ); ?></p>
</div>
