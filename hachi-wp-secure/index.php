<?php
/**
 * HACHI Theme — index.php
 * Fallback template (required by WordPress)
 */
get_header();
?>

<main id="main" class="site-main" role="main">
	<div class="container" style="padding-top: calc(var(--nav-h) + 80px); padding-bottom: 80px; min-height: 60vh;">
		<?php
		if ( have_posts() ) :
			while ( have_posts() ) :
				the_post();
				?>
				<article id="post-<?php the_ID(); ?>" <?php post_class(); ?> style="margin-bottom: 48px; padding-bottom: 48px; border-bottom: 1px solid var(--gray2);">
					<h2 style="font-family: var(--serif); font-size: clamp(18px, 2.5vw, 24px); font-weight: 300; margin-bottom: 16px;">
						<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
					</h2>
					<div class="body-copy"><?php the_excerpt(); ?></div>
				</article>
				<?php
			endwhile;

			the_posts_pagination( [
				'prev_text' => '← Prev',
				'next_text' => 'Next →',
			] );

		else :
			echo '<p style="color: var(--gray); padding: 40px 0;">';
			_e( 'コンテンツが見つかりません。', 'hachi' );
			echo '</p>';
		endif;
		?>
	</div>
</main>

<?php get_footer(); ?>
