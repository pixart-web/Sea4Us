<?php
$a = $args['a'] ?? array();
$q = array( 'orderby' => array( 'menu_order' => 'ASC', 'title' => 'ASC' ) );
if ( ! empty( $a['docType'] ) ) {
	$q['meta_query'] = array( array( 'key' => 'doc_type', 'value' => $a['docType'] ) );
}
$docs = s4u_query( 's4u_document', $q );
if ( ! $docs ) {
	echo '<p class="s4u-empty">' . esc_html( s4u__( 'no_items' ) ) . '</p>';
	return;
}
echo s4u_render( 'doc-list', array( 'ids' => wp_list_pluck( $docs, 'ID' ) ) ); // phpcs:ignore
