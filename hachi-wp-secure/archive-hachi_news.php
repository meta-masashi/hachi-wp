<?php
/**
 * HACHI Theme — archive-hachi_news.php (v3 Light Monochrome)
 * 3 セクション: ヒーロー / 記事一覧 / フッター CTA
 * フィルタタブ・ページネーション・サムネイル・物体アイコン 全削除
 */
get_header();
?>
<main id="main-content">

<main id="main-content">

<!-- ===== Section 1: ヒーロー ===== -->
<section class="news-hero">
	<div class="news-container">
		<div class="news-hero__inner">
			<p class="news-eyebrow">NEWS</p>
			<h1 class="news-hero__h1">ニュース・知見</h1>
			<p class="news-hero__sub"><?php esc_html_e( 'サービス更新と現場で気づいたことを記録します。', 'hachi' ); ?></p>
		</div>
	</div>
</section>

</main>
<!-- ===== Section 2: 記事一覧 ===== -->
<section class="news-list-section">
	<div class="news-container">
		<div class="news-content-narrow">
			<?php
			$news_query = new WP_Query( [
				'post_type'      => 'hachi_news',
				'posts_per_page' => 4,
				'post_status'    => 'publish',
				'orderby'        => 'date',
				'order'          => 'DESC',
			] );
			?>
			<?php if ( $news_query->have_posts() ) : ?>
				<ol class="news-post-list">
					<?php while ( $news_query->have_posts() ) : $news_query->the_post(); ?>
						<?php
						$news_type = get_post_meta( get_the_ID(), '_hachi_news_type', true );
						if ( empty( $news_type ) ) {
							$terms = get_the_terms( get_the_ID(), 'hachi_news_category' );
							if ( $terms && ! is_wp_error( $terms ) ) {
								$news_type = strtoupper( $terms[0]->name );
							} else {
								$news_type = 'BLOG';
							}
						} else {
							$news_type = strtoupper( $news_type );
						}
						?>
						<li class="news-post-item">
							<div class="news-post-item__meta">
								<time class="news-post-item__date" datetime="<?php echo esc_attr( get_the_date( 'Y-m-d' ) ); ?>">
									<?php echo esc_html( get_the_date( 'Y.m.d' ) ); ?>
								</time>
								<span class="news-post-item__category"><?php echo esc_html( $news_type ); ?></span>
							</div>
							<h2 class="news-post-item__title">
								<a href="<?php echo esc_url( get_permalink() ); ?>"><?php echo esc_html( get_the_title() ); ?></a>
							</h2>
							<a href="<?php echo esc_url( get_permalink() ); ?>" class="news-post-item__read-more"><?php esc_html_e( '続きを読む →', 'hachi' ); ?></a>
						</li>
					<?php endwhile; ?>
					<?php wp_reset_postdata(); ?>
				</ol>
			<?php else : ?>
				<p class="news-empty"><?php esc_html_e( '現在、記事はありません。', 'hachi' ); ?></p>
			<?php endif; ?>
		</div>
	</div>
</section>

<!-- ===== Section 3: フッター CTA ===== -->
<section class="news-footer-cta">
	<h2 class="news-footer-cta__heading"><?php esc_html_e( '導入のご相談はこちらから。', 'hachi' ); ?></h2>
	<a href="<?php echo esc_url( home_url( '/contact/' ) ); ?>" class="news-footer-cta__btn"><?php esc_html_e( 'お問い合わせ', 'hachi' ); ?></a>
</section>

</main>

<?php get_footer(); ?>
