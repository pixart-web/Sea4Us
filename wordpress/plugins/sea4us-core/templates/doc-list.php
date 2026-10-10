<?php
$ids = $args['ids'] ?? array();
if ( ! $ids ) {
	return;
}
?>
<ul class="s4u-docs">
	<?php foreach ( $ids as $id ) :
		$d = s4u_document_data( $id );
		if ( ! $d['url'] ) {
			continue;
		}
		$meta = array_filter( array( $d['ext'], $d['size'], $d['date'] ? s4u_date( $d['date'] ) : '' ) );
		?>
		<li class="s4u-doc">
			<?php echo s4u_icon( 'doc' ); // phpcs:ignore ?>
			<div class="s4u-doc__body">
				<a class="s4u-doc__link" href="<?php echo esc_url( $d['url'] ); ?>"<?php echo $d['ext_link'] ? ' target="_blank" rel="noopener"' : ' download'; ?>><?php echo esc_html( $d['title'] ); ?></a>
				<?php if ( $meta ) : ?><span class="s4u-doc__meta"><?php echo esc_html( implode( ' · ', $meta ) ); ?></span><?php endif; ?>
				<?php if ( $d['desc'] ) : ?><span class="s4u-doc__desc"><?php echo esc_html( $d['desc'] ); ?></span><?php endif; ?>
			</div>
			<span class="s4u-doc__action" aria-hidden="true"><?php echo s4u_icon( $d['ext_link'] ? 'external' : 'download' ); // phpcs:ignore ?></span>
		</li>
	<?php endforeach; ?>
</ul>
