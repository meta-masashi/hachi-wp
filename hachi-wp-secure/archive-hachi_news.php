<?php
/**
 * HACHI Theme — archive-hachi_news.php
 * News archive with filter tabs
 */
get_header();
?>

<div class="page-hero">
	<div class="page-hero__ghost ghost-text" aria-hidden="true">NEWS</div>
	<div class="container">
		<div class="js-fade"><?php hachi_section_label( 'N e w s' ); ?></div>
		<h1 class="heading-en js-fade js-fade--delay-1" style="font-size:clamp(52px,9vw,112px)">NEWS</h1>
		<p class="heading-jp js-fade js-fade--delay-2"><?php _e( 'お知らせ・ブログ', 'hachi' ); ?></p>
	</div>
</div>

<section class="section">
	<div class="container js-fade">

		<!-- Filter tabs -->
		<div class="post-filter-bar" role="tablist" aria-label="<?php esc_attr_e( 'ニュースフィルター', 'hachi' ); ?>">
			<?php
			$filter_tabs = [
				'all'   => 'ALL',
				'news'  => 'NEWS',
				'press' => 'PRESS RELEASE',
				'media' => 'MEDIA',
				'blog'  => 'BLOG',
			];

			$active_filter = sanitize_key( $_GET['type'] ?? 'all' );
			if ( ! array_key_exists( $active_filter, $filter_tabs ) ) {
				$active_filter = 'all';
			}

			foreach ( $filter_tabs as $key => $label ) :
				$is_active = $key === $active_filter;
				$url = add_query_arg( 'type', $key, get_post_type_archive_link( 'hachi_news' ) );
			?>
				<a
					href="<?php echo esc_url( $url ); ?>"
					class="post-filter-btn<?php echo $is_active ? ' is-active' : ''; ?>"
					role="tab"
					aria-selected="<?php echo $is_active ? 'true' : 'false'; ?>"
				>
					<?php echo esc_html( $label ); ?>
				</a>
			<?php endforeach; ?>
		</div>

		<!-- News list -->
		<div style="border-top:1px solid var(--gray2)" role="tabpanel">
			<?php
			// Build query filtered by type if selected
			$args = [
				'post_type'      => 'hachi_news',
				'posts_per_page' => 20,
				'post_status'    => 'publish',
				'orderby'        => 'date',
				'order'          => 'DESC',
				'paged'          => max( 1, get_query_var( 'paged' ) ),
			];

			if ( $active_filter !== 'all' ) {
				$args['meta_key']   = '_hachi_news_type';
				$args['meta_value'] = sanitize_key( $active_filter );
			}

			$news_q = new WP_Query( $args );

			if ( $news_q->have_posts() ) :
				while ( $news_q->have_posts() ) : $news_q->the_post();
					$type    = get_post_meta( get_the_ID(), '_hachi_news_type', true ) ?: 'news';
					$is_blog = $type === 'blog';
				?>
					<a href="<?php the_permalink(); ?>" class="post-row<?php echo $is_blog ? ' post-row--blog' : ''; ?>">
						<span class="post-row__date"><?php echo esc_html( hachi_get_date() ); ?></span>
						<span class="post-row__cat"><?php echo esc_html( strtoupper( $type ) ); ?></span>
						<span class="post-row__title"><?php the_title(); ?></span>
						<span class="post-row__arrow" aria-hidden="true">→</span>
					</a>
				<?php endwhile;

				// Pagination
				echo '<div style="margin-top:60px;text-align:center">';
				echo paginate_links( [
					'total'     => $news_q->max_num_pages,
					'current'   => max( 1, get_query_var( 'paged' ) ),
					'prev_text' => '← Prev',
					'next_text' => 'Next →',
				] );
				echo '</div>';

				wp_reset_postdata();
			else :
			?>
				<p style="padding:64px 0;text-align:center;color:var(--gray);font-size:14px">
					<?php _e( '該当する記事がありません。', 'hachi' ); ?>
				</p>
			<?php endif; ?>
		</div>

	</div>
</section>

<?php get_footer(); ?>
