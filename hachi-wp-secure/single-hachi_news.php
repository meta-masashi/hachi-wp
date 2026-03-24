<?php
/**
 * HACHI Theme — single.php / single-hachi_news.php
 * Single news post template
 */
get_header();
?>

<div class="page-hero" style="padding-bottom: 60px;">
	<div class="container">
		<?php if ( have_posts() ) : the_post(); ?>

			<?php
			$type = get_post_meta( get_the_ID(), '_hachi_news_type', true ) ?: 'news';
			$is_blog = $type === 'blog';
			?>

			<div class="js-fade" style="margin-bottom: 20px; display: flex; align-items: center; gap: 20px;">
				<span style="
					font-family: var(--mono);
					font-size: 10px;
					letter-spacing: 0.22em;
					border: 1px solid var(--teal);
					color: var(--teal);
					padding: 3px 14px;
				">
					<?php echo esc_html( strtoupper( $type ) ); ?>
				</span>
				<time
					datetime="<?php echo esc_attr( get_the_date( 'Y-m-d' ) ); ?>"
					style="font-family: var(--mono); font-size: 12px; letter-spacing: 0.12em; color: var(--gray);"
				>
					<?php echo esc_html( hachi_get_date() ); ?>
				</time>
			</div>

			<h1 class="heading-jp js-fade js-fade--delay-1" style="font-size: clamp(20px, 3vw, 36px); margin-top: 0;">
				<?php the_title(); ?>
			</h1>

		<?php endif; ?>
	</div>
</div>

<section class="section" style="padding-top: 60px;">
	<div class="container">
		<div style="max-width: 760px; margin: 0 auto;">

			<?php if ( has_post_thumbnail() ) : ?>
				<div class="js-fade" style="margin-bottom: 60px; overflow: hidden;">
					<?php the_post_thumbnail( 'hachi-hero', [
						'style'   => 'width:100%;height:auto;',
						'loading' => 'eager',
					] ); ?>
				</div>
			<?php endif; ?>

			<div class="js-fade" style="
				font-size: 15px;
				line-height: 2.2;
				color: #444;
			">
				<?php
				// Safe content output
				the_content();
				?>
			</div>

			<!-- Navigation -->
			<div style="
				display: flex;
				justify-content: space-between;
				margin-top: 80px;
				padding-top: 40px;
				border-top: 1px solid var(--gray2);
				gap: 20px;
			">
				<?php
				$prev = get_previous_post();
				$next = get_next_post();
				if ( $prev ) :
				?>
					<a href="<?php echo esc_url( get_permalink( $prev ) ); ?>" class="btn" style="font-size: 10px;">
						<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" style="width:14px;height:14px;transform:rotate(180deg)"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
						Prev
					</a>
				<?php else: ?>
					<span></span>
				<?php endif; ?>

				<a href="<?php echo esc_url( get_post_type_archive_link( 'hachi_news' ) ); ?>" class="btn">
					<?php _e( '一覧へ戻る', 'hachi' ); ?>
				</a>

				<?php if ( $next ) : ?>
					<a href="<?php echo esc_url( get_permalink( $next ) ); ?>" class="btn">
						Next
						<?php hachi_arrow_icon(); ?>
					</a>
				<?php else: ?>
					<span></span>
				<?php endif; ?>
			</div>

		</div>
	</div>
</section>

<?php get_footer(); ?>
