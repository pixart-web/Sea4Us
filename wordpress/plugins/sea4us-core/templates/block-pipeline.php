<?php
/**
 * Pipeline: one row per programme, stage read from the programme post (single source).
 * Desktop: accessible table. Mobile: the same rows become cards with written stage.
 */
$a        = $args['a'] ?? array();
$programs = s4u_programs();
$compact  = ! empty( $a['compact'] );
if ( $compact ) {
	$programs = array_slice( $programs, 0, 8 );
}
if ( ! $programs ) {
	return;
}
$cols  = s4u_stage_columns();
$dates = array_filter( array_map( fn( $p ) => s4u_meta( $p->ID, 'stage_source_date' ), $programs ) );
rsort( $dates );
?>
<div class="s4u-pipeline<?php echo $compact ? ' is-compact' : ''; ?>">
	<table class="s4u-pipeline__table">
		<caption class="s4u-pipeline__caption">
			<?php echo esc_html( s4u_pick( 'Pipeline de desenvolvimento — estádio confirmado por programa', 'Development pipeline — confirmed stage per programme' ) ); ?>
			<?php if ( $dates ) : ?><span class="s4u-pipeline__asof"><?php echo esc_html( s4u__( 'source_date' ) . ': ' . s4u_date( $dates[0] ) ); ?></span><?php endif; ?>
		</caption>
		<thead>
			<tr>
				<th scope="col" class="s4u-pipeline__h-prog"><?php s4u_e( 'programme' ); ?></th>
				<th scope="col" class="s4u-pipeline__h-ind"><?php s4u_e( 'indication' ); ?></th>
				<?php foreach ( $cols as $c ) : ?>
					<th scope="col" class="s4u-pipeline__h-stage"><?php echo esc_html( s4u_option_label( 'stage', $c ) ); ?></th>
				<?php endforeach; ?>
			</tr>
		</thead>
		<tbody>
			<?php foreach ( $programs as $p ) :
				$stage = s4u_meta( $p->ID, 'stage', 'unconfirmed' );
				$idx   = s4u_stage_index( $stage );
				$code  = s4u_meta( $p->ID, 'code' );
				?>
				<tr class="<?php echo $idx < 0 ? 'is-unconfirmed' : ''; ?>">
					<th scope="row" class="s4u-pipeline__prog">
						<a href="<?php echo esc_url( get_permalink( $p ) ); ?>"><?php echo esc_html( get_the_title( $p ) ); ?></a>
						<?php if ( s4u_meta( $p->ID, 'nature' ) ) : ?><span class="s4u-pipeline__nature"><?php echo esc_html( s4u_meta( $p->ID, 'nature' ) ); ?></span><?php endif; ?>
					</th>
					<td class="s4u-pipeline__ind"><?php echo esc_html( trim( s4u_meta( $p->ID, 'area' ) . ( s4u_meta( $p->ID, 'indication' ) ? ' · ' . s4u_meta( $p->ID, 'indication' ) : '' ), ' ·' ) ); ?></td>
					<td class="s4u-pipeline__stage" colspan="<?php echo count( $cols ); ?>">
						<div class="s4u-bar" style="--s4u-stage: <?php echo (int) max( 0, $idx + 1 ); ?>; --s4u-cols: <?php echo count( $cols ); ?>;">
							<span class="s4u-bar__fill" aria-hidden="true"></span>
							<span class="s4u-bar__text"><span class="s4u-bar__k"><?php s4u_e( 'stage' ); ?>: </span><?php echo esc_html( $idx < 0 ? s4u__( 'stage_unconfirmed' ) : s4u_option_label( 'stage', $stage ) ); ?></span>
						</div>
						<?php echo s4u_render( 'stage-track', array( 'stage' => $stage ) ); // phpcs:ignore ?>
					</td>
				</tr>
			<?php endforeach; ?>
		</tbody>
	</table>
	<?php if ( ! empty( $a['showLegend'] ) ) : ?>
		<div class="s4u-pipeline__legend">
			<p class="s4u-pipeline__legend-title"><?php s4u_e( 'stage_legend' ); ?></p>
			<ul>
				<li><span class="s4u-swatch s4u-swatch--done" aria-hidden="true"></span><?php echo esc_html( s4u_pick( 'Barra preenchida: estádio documentado em fonte pública datada', 'Filled bar: stage documented in a dated public source' ) ); ?></li>
				<li><span class="s4u-swatch s4u-swatch--open" aria-hidden="true"></span><?php echo esc_html( s4u_pick( 'Contorno tracejado: estádio por confirmar com a Sea4Us', 'Dashed outline: stage to be confirmed by Sea4Us' ) ); ?></li>
			</ul>
			<p class="s4u-pipeline__disclaimer"><?php echo esc_html( s4u_pick( 'Todos os programas são de investigação. Nenhum composto da Sea4Us tem autorização de introdução no mercado. A autorização de um ensaio clínico não é uma autorização de comercialização.', 'All programmes are investigational. No Sea4Us compound holds a marketing authorisation. Authorisation of a clinical trial is not a marketing authorisation.' ) ); ?></p>
		</div>
	<?php endif; ?>
</div>
