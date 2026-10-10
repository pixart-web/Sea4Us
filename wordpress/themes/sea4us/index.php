<?php
/**
 * Fallback template.
 */
get_header();
?>
<main id="s4u-main" class="s4u-main" tabindex="-1">
	<?php
	while ( have_posts() ) :
		the_post();
		$content = get_the_content();
		// Pages without a header block get a default one, so every page has a coherent H1.
		if ( ! has_block( 'sea4us/page-hero' ) && ! has_block( 'sea4us/hero' ) ) {
			echo s4u_render( 'block-page-hero', array( 'a' => array( 'title' => get_the_title(), 'tone' => 'light' ) ) ); // phpcs:ignore
		}
		?>
		<div class="s4u-flow">
			<?php the_content(); ?>
		</div>
	<?php endwhile; ?>
</main>
<?php
get_footer();
