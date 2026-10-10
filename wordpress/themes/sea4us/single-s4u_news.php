<?php
/**
 * News article: real date, context note for historical items, media credits, attachments
 * and press links. External/third-party pieces keep their nature (link or excerpt).
 */
get_header();
the_post();
$id       = get_the_ID();
$lang     = s4u_current_lang();
$topic    = s4u_meta( $id, 'topic', 'company' );
$author   = s4u_meta( $id, 'author_name' );
$ext      = s4u_meta( $id, 'external_url' );
$ext_src  = s4u_meta( $id, 'external_source' );
$video    = s4u_meta( $id, 'video_url' );
$gallery  = s4u_ids( s4u_meta( $id, 'gallery' ) );
$docs     = s4u_localized_ids( s4u_meta( $id, 'attachments' ) );
$thumb    = get_post_thumbnail_id();
$credit   = s4u_meta( $id, 'image_credit' ) ?: s4u_credit( $thumb );
$is_old   = get_post_time( 'U', true ) < strtotime( '-12 months' );
$news_url = s4u_page_url( 'news', $lang );
?>
<main id="s4u-main" class="s4u-main" tabindex="-1">
	<article class="s4u-article">
		<header class="s4u-article__head">
			<div class="s4u-container s4u-container--narrow">
				<?php echo s4u_render( 'breadcrumbs' ); // phpcs:ignore ?>
				<p class="s4u-meta s4u-meta--lg"><time datetime="<?php echo esc_attr( get_the_date( 'Y-m-d' ) ); ?>"><?php echo esc_html( s4u_date( get_post()->post_date ) ); ?></time><span class="s4u-meta__sep" aria-hidden="true">·</span><span><?php echo esc_html( s4u_option_label( 'news_topic', $topic ) ); ?></span><?php if ( $author ) : ?><span class="s4u-meta__sep" aria-hidden="true">·</span><span><?php echo esc_html( s4u__( 'author' ) . ': ' . $author ); ?></span><?php endif; ?></p>
				<h1 class="s4u-article__title"><?php the_title(); ?></h1>
				<?php if ( has_excerpt() ) : ?><p class="s4u-article__lead"><?php echo esc_html( get_the_excerpt() ); ?></p><?php endif; ?>
				<?php if ( $is_old ) : ?><p class="s4u-note"><?php echo s4u_icon( 'info' ); // phpcs:ignore ?><span><?php s4u_e( 'historical_note' ); ?></span></p><?php endif; ?>
			</div>
		</header>
		<?php if ( $thumb ) : ?>
			<figure class="s4u-article__figure s4u-container">
				<?php echo s4u_img( $thumb, 's4u-wide', array( 'loading' => 'eager', 'fetchpriority' => 'high', 'sizes' => '(min-width: 1320px) 1320px, 100vw' ) ); // phpcs:ignore ?>
				<?php if ( $credit ) : ?><figcaption class="s4u-credit"><?php echo esc_html( s4u__( 'photo_credit' ) . ': ' . $credit ); ?></figcaption><?php endif; ?>
			</figure>
		<?php endif; ?>
		<div class="s4u-container s4u-container--narrow s4u-prose">
			<?php the_content(); ?>

			<?php if ( $gallery ) : ?>
				<div class="s4u-gallery">
					<?php foreach ( $gallery as $g ) : ?>
						<figure><?php echo s4u_img( $g, 'large', array( 'sizes' => '(min-width: 760px) 360px, 100vw' ) ); // phpcs:ignore ?><?php if ( s4u_credit( $g ) ) : ?><figcaption class="s4u-credit"><?php echo esc_html( s4u__( 'photo_credit' ) . ': ' . s4u_credit( $g ) ); ?></figcaption><?php endif; ?></figure>
					<?php endforeach; ?>
				</div>
			<?php endif; ?>

			<?php if ( $video ) : ?>
				<p class="s4u-video-link"><a class="s4u-btn s4u-btn--outline" href="<?php echo esc_url( $video ); ?>" target="_blank" rel="noopener"><?php echo s4u_icon( 'play' ); // phpcs:ignore ?> <?php echo esc_html( s4u_pick( 'Ver vídeo', 'Watch video' ) ); ?><span class="screen-reader-text"> (<?php s4u_e( 'external_link' ); ?>)</span></a></p>
			<?php endif; ?>

			<?php if ( $ext ) : ?>
				<aside class="s4u-press">
					<p class="s4u-press__k"><?php echo esc_html( s4u_pick( 'Artigo original', 'Original article' ) ); ?></p>
					<a href="<?php echo esc_url( $ext ); ?>" target="_blank" rel="noopener"><?php echo esc_html( $ext_src ?: wp_parse_url( $ext, PHP_URL_HOST ) ); ?> <?php echo s4u_icon( 'external' ); // phpcs:ignore ?><span class="screen-reader-text"> (<?php s4u_e( 'external_link' ); ?>)</span></a>
				</aside>
			<?php endif; ?>

			<?php if ( $docs ) : ?>
				<h2 class="s4u-h4"><?php s4u_e( 'documents' ); ?></h2>
				<?php echo s4u_render( 'doc-list', array( 'ids' => $docs ) ); // phpcs:ignore ?>
			<?php endif; ?>
		</div>
		<nav class="s4u-container s4u-container--narrow s4u-article__nav" aria-label="<?php echo esc_attr( s4u_pick( 'Outras notícias', 'More news' ) ); ?>">
			<?php
			$adj = function ( $prev ) use ( $lang ) {
				$p = s4u_query( 's4u_news', array(
					'posts_per_page' => 1,
					'orderby'        => 'date',
					'order'          => $prev ? 'DESC' : 'ASC',
					'date_query'     => array( array( $prev ? 'before' : 'after' => get_post()->post_date, 'inclusive' => false ) ),
				) );
				return $p ? $p[0] : null;
			};
			$older = $adj( true );
			$newer = $adj( false );
			?>
			<?php if ( $older ) : ?><a class="s4u-article__adj s4u-article__adj--prev" href="<?php echo esc_url( get_permalink( $older ) ); ?>"><span class="s4u-article__adj-k"><?php echo esc_html( s4u_pick( 'Anterior', 'Previous' ) ); ?></span><?php echo esc_html( get_the_title( $older ) ); ?></a><?php endif; ?>
			<a class="s4u-article__adj s4u-article__adj--all" href="<?php echo esc_url( $news_url ); ?>"><?php s4u_e( 'back_to_news' ); ?></a>
			<?php if ( $newer ) : ?><a class="s4u-article__adj s4u-article__adj--next" href="<?php echo esc_url( get_permalink( $newer ) ); ?>"><span class="s4u-article__adj-k"><?php echo esc_html( s4u_pick( 'Seguinte', 'Next' ) ); ?></span><?php echo esc_html( get_the_title( $newer ) ); ?></a><?php endif; ?>
		</nav>
	</article>
</main>
<?php
get_footer();
