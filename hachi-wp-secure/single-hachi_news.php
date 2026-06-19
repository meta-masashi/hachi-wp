<?php
/**
 * HACHI Theme — single.php / single-hachi_news.php
 * Single news post template
 */

if ( have_posts() ) {
	the_post();
	$note_url = function_exists( 'hachi_cd_note_url_for_post' ) ? hachi_cd_note_url_for_post( get_the_ID() ) : '';
	if ( $note_url ) {
		wp_redirect( $note_url, 301 );
		exit;
	}
	rewind_posts();
}

get_header();
?>

<main id="main-content" class="cd-page">
<section class="cd-page-hero">
	<div class="cd-container cd-narrow">
		<?php if ( have_posts() ) : the_post(); ?>

			<?php
			$type = get_post_meta( get_the_ID(), '_hachi_news_type', true ) ?: 'news';
			$is_blog = $type === 'blog';
			?>

			<div class="cd-news-single-meta">
				<span class="cd-news-label">
					<?php echo esc_html( strtoupper( $type ) ); ?>
				</span>
				<time
					datetime="<?php echo esc_attr( get_the_date( 'Y-m-d' ) ); ?>"
					class="cd-news-date"
				>
					<?php echo esc_html( hachi_get_date() ); ?>
				</time>
			</div>

			<h1>
				<?php the_title(); ?>
			</h1>

		<?php endif; ?>
	</div>
</section>

<section class="cd-section cd-section--flush">
	<div class="cd-container cd-narrow">

			<?php if ( has_post_thumbnail() ) : ?>
				<div class="cd-news-single-thumb">
					<?php the_post_thumbnail( 'hachi-hero', [
						'style'   => 'width:100%;height:auto;',
						'loading' => 'eager',
					] ); ?>
				</div>
			<?php endif; ?>

			<div class="cd-news-single-body">
				<?php
				// Safe content output
				the_content();
				?>
			</div>

			<!-- Navigation -->
			<div class="cd-post-nav">
				<?php
				$prev = get_previous_post();
				$next = get_next_post();
				if ( $prev ) :
				?>
					<a href="<?php echo esc_url( get_permalink( $prev ) ); ?>" class="cd-btn cd-btn--outline">
						Prev
					</a>
				<?php else: ?>
					<span></span>
				<?php endif; ?>

				<a href="<?php echo esc_url( get_post_type_archive_link( 'hachi_news' ) ); ?>" class="cd-btn cd-btn--outline">
					<?php _e( '一覧へ戻る', 'hachi' ); ?>
				</a>

				<?php if ( $next ) : ?>
					<a href="<?php echo esc_url( get_permalink( $next ) ); ?>" class="cd-btn cd-btn--outline">
						Next
					</a>
				<?php else: ?>
					<span></span>
				<?php endif; ?>
			</div>

	</div>
</section>
</main>

<?php get_footer(); ?>
