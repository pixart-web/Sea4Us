<?php
/**
 * Programme page: consolidated public information with dated milestones and visible qualifications.
 */
get_header();
the_post();
$id      = get_the_ID();
$related = s4u_localized_ids( s4u_meta( $id, 'related_news' ) );
?>
<main id="s4u-main" class="s4u-main" tabindex="-1">
	<header class="s4u-page-hero s4u-page-hero--dark">
		<div class="s4u-container s4u-page-hero__inner s4u-program-head">
			<div class="s4u-page-hero__copy">
				<?php echo s4u_render( 'breadcrumbs' ); // phpcs:ignore ?>
				<p class="s4u-eyebrow s4u-eyebrow--light"><span class="s4u-eyebrow__rule" aria-hidden="true"></span><?php s4u_e( 'programme' ); ?></p>
				<h1 class="s4u-page-hero__title"><?php the_title(); ?></h1>
				<?php if ( has_excerpt() ) : ?><p class="s4u-page-hero__text"><?php echo esc_html( get_the_excerpt() ); ?></p><?php endif; ?>
			</div>
			<?php echo s4u_render( 'program-sheet', array( 'id' => $id, 'full' => true ) ); // phpcs:ignore ?>
		</div>
	</header>
	<div class="s4u-container s4u-container--narrow s4u-prose s4u-section-pad">
		<?php the_content(); ?>
	</div>
	<?php if ( $related ) : ?>
		<section class="s4u-section s4u-section--mineral" aria-labelledby="s4u-related">
			<div class="s4u-container">
				<h2 id="s4u-related" class="s4u-h3"><?php s4u_e( 'related_news' ); ?></h2>
				<div class="s4u-cards s4u-cards--3">
					<?php foreach ( $related as $nid ) { echo s4u_render( 'card-news', array( 'post' => get_post( $nid ) ) ); } // phpcs:ignore ?>
				</div>
			</div>
		</section>
	<?php endif; ?>
	<?php echo s4u_render( 'block-cta' ); // phpcs:ignore ?>
</main>
<?php
get_footer();
