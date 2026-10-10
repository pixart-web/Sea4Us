<?php
/**
 * Five-step stage track with a written label. Colour is never the only signal.
 */
$stage = $args['stage'] ?? 'unconfirmed';
$idx   = s4u_stage_index( $stage );
$label = s4u_option_label( 'stage', $stage );
?>
<div class="s4u-track<?php echo $idx < 0 ? ' is-unconfirmed' : ''; ?>">
	<p class="s4u-track__label"><span class="s4u-track__k"><?php s4u_e( 'stage' ); ?>:</span> <strong><?php echo esc_html( $idx < 0 ? s4u__( 'stage_unconfirmed' ) : $label ); ?></strong></p>
	<ol class="s4u-track__steps" aria-hidden="true">
		<?php foreach ( s4u_stage_columns() as $i => $col ) : ?>
			<li class="<?php echo $idx >= 0 && $i <= $idx ? 'is-done' : ''; ?><?php echo $i === $idx ? ' is-current' : ''; ?>"><span><?php echo esc_html( s4u_option_label( 'stage', $col ) ); ?></span></li>
		<?php endforeach; ?>
	</ol>
</div>
