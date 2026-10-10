<?php
/**
 * Project sheet: code, objective, timeline, entities, amounts and support as published,
 * funding block with original proportions, documents and original link.
 */
get_header();
the_post();
$id     = get_the_ID();
$status = s4u_meta( $id, 'status', 'unknown' );
$facts  = array(
	'code'           => s4u_meta( $id, 'code' ),
	'funding_programme' => s4u_meta( $id, 'funding_programme' ),
	'period'         => trim( s4u_meta( $id, 'start_date' ) . ' – ' . s4u_meta( $id, 'end_date' ), ' –' ),
	'lead'           => s4u_meta( $id, 'lead' ),
	'total_cost'     => s4u_money( s4u_meta( $id, 'total_cost' ) ),
	'funding_amount' => s4u_money( s4u_meta( $id, 'funding_amount' ) ),
);
$entities  = array_filter( array_map( 'trim', explode( "\n", s4u_meta( $id, 'entities' ) ) ) );
$breakdown = s4u_meta( $id, 'funding_breakdown' );
$logos     = s4u_ids( s4u_meta( $id, 'funding_logos' ) );
$docs      = s4u_localized_ids( s4u_meta( $id, 'documents' ) );
$programs  = s4u_localized_ids( s4u_meta( $id, 'programs' ) );
$source    = s4u_meta( $id, 'source_url' ) ?: s4u_meta( $id, 'legacy_url' );
?>
<main id="s4u-main" class="s4u-main" tabindex="-1">
	<header class="s4u-page-hero s4u-page-hero--light s4u-project-head">
		<div class="s4u-container s4u-page-hero__inner">
			<div class="s4u-page-hero__copy">
				<?php echo s4u_render( 'breadcrumbs' ); // phpcs:ignore ?>
				<p class="s4u-card__top"><span class="s4u-badge s4u-badge--<?php echo esc_attr( $status ); ?>"><?php echo esc_html( s4u_option_label( 'project_status', $status ) ); ?></span><?php if ( s4u_meta( $id, 'status_note' ) ) : ?><span class="s4u-card__fund"><?php echo esc_html( s4u_meta( $id, 'status_note' ) ); ?></span><?php endif; ?></p>
				<h1 class="s4u-page-hero__title"><?php the_title(); ?></h1>
				<?php if ( s4u_meta( $id, 'full_title' ) ) : ?><p class="s4u-page-hero__text"><?php echo esc_html( s4u_meta( $id, 'full_title' ) ); ?></p><?php endif; ?>
			</div>
		</div>
	</header>
	<div class="s4u-container s4u-split">
		<div class="s4u-split__main s4u-prose">
			<h2 class="s4u-h4"><?php s4u_e( 'objective' ); ?></h2>
			<?php the_content(); ?>
			<?php if ( $breakdown ) : ?>
				<h2 class="s4u-h4"><?php s4u_e( 'funding_amount' ); ?></h2>
				<p class="s4u-preline"><?php echo esc_html( $breakdown ); ?></p>
			<?php endif; ?>
			<?php if ( $docs ) : ?>
				<h2 class="s4u-h4"><?php s4u_e( 'documents' ); ?></h2>
				<?php echo s4u_render( 'doc-list', array( 'ids' => $docs ) ); // phpcs:ignore ?>
			<?php endif; ?>
			<?php if ( $logos ) : ?>
				<div class="s4u-funding-block">
					<?php foreach ( $logos as $l ) { echo s4u_img( $l, 'large', array( 'sizes' => '(min-width: 1024px) 720px, 100vw' ) ); } // phpcs:ignore ?>
					<?php if ( s4u_meta( $id, 'display_check' ) ) : ?><p class="s4u-review-flag"><?php echo esc_html( s4u_pick( 'Requisitos de publicitação do financiamento a confirmar com a Sea4Us.', 'Funding publicity requirements to be confirmed by Sea4Us.' ) ); ?></p><?php endif; ?>
				</div>
			<?php endif; ?>
		</div>
		<aside class="s4u-split__side">
			<dl class="s4u-facts">
				<?php foreach ( $facts as $k => $v ) : ?>
					<div><dt><?php s4u_e( $k ); ?></dt><dd><?php echo $v ? esc_html( $v ) : '<span class="s4u-muted">' . esc_html( s4u__( 'not_published' ) ) . '</span>'; ?></dd></div>
				<?php endforeach; ?>
				<?php if ( $entities ) : ?>
					<div><dt><?php s4u_e( 'entities' ); ?></dt><dd><ul><?php foreach ( $entities as $e ) : ?><li><?php echo esc_html( $e ); ?></li><?php endforeach; ?></ul></dd></div>
				<?php endif; ?>
				<?php if ( $programs ) : ?>
					<div><dt><?php s4u_e( 'related' ); ?></dt><dd><ul><?php foreach ( $programs as $pid ) : ?><li><a href="<?php echo esc_url( get_permalink( $pid ) ); ?>"><?php echo esc_html( get_the_title( $pid ) ); ?></a></li><?php endforeach; ?></ul></dd></div>
				<?php endif; ?>
			</dl>
			<?php if ( $source ) : ?><p class="s4u-source"><a href="<?php echo esc_url( $source ); ?>"><?php s4u_e( 'original_page' ); ?> <?php echo s4u_icon( 'external' ); // phpcs:ignore ?></a></p><?php endif; ?>
			<p><a class="s4u-link-arrow s4u-link-arrow--back" href="<?php echo esc_url( s4u_page_url( 'projects' ) ); ?>"><?php s4u_e( 'back_to_projects' ); ?></a></p>
		</aside>
	</div>
</main>
<?php
get_footer();
