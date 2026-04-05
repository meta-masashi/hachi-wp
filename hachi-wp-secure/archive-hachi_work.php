<?php
/**
 * HACHI Theme — archive-hachi_work.php (Light Monochrome v2)
 * Works archive with auto-classified Work items from News feed + CPT
 */
get_header();

// Fetch items auto-classified as Work from unified feed (WP news + note.com)
$work_items = hachi_get_classified_items( [ 'category' => 'work', 'limit' => 60 ] );
?>

<!-- ===== PAGE HERO ===== -->
<div class="page-hero">
	<div class="container">
		<div class="js-fade"><?php hachi_section_label( 'W o r k s' ); ?></div>
		<h1 class="heading-en js-fade js-fade--delay-1">WORKS</h1>
		<p class="heading-jp js-fade js-fade--delay-2"><?php _e( '導入事例・プロジェクト実績', 'hachi' ); ?></p>
	</div>
</div>

<!-- ===== WORKS LIST ===== -->
<section class="section news-archive">
	<div class="container">

		<div class="js-fade" style="max-width:680px;margin-bottom:48px">
			<p class="body-copy">
				<?php _e( 'HACHI が現場と向き合い、取り組んできたプロジェクトの記録です。News や Blog から自動的に「Work 親和性の高い」コンテンツを集約して表示しています。', 'hachi' ); ?>
			</p>
		</div>

		<?php if ( ! empty( $work_items ) ) : ?>
			<div class="news-list js-fade js-fade--delay-1">
				<?php foreach ( $work_items as $it ) :
					$is_external = $it['source'] === 'note';
				?>
					<a
						href="<?php echo esc_url( $it['url'] ); ?>"
						class="news-card news-card--work"
						<?php echo $is_external ? 'target="_blank" rel="noopener noreferrer"' : ''; ?>
					>
						<div class="news-card__media">
							<?php if ( $it['thumbnail'] ) : ?>
								<img src="<?php echo esc_url( $it['thumbnail'] ); ?>" alt="" loading="lazy">
							<?php else : ?>
								<span class="news-card__media-placeholder">WORK</span>
							<?php endif; ?>
						</div>
						<div class="news-card__body">
							<div class="news-card__meta">
								<span class="news-card__cat news-card__cat--work">WORK</span>
								<span class="news-card__date"><?php echo esc_html( $it['date_str'] ); ?></span>
								<?php if ( $is_external ) : ?>
									<span class="news-card__external" aria-hidden="true">↗ note</span>
								<?php endif; ?>
							</div>
							<h3 class="news-card__title"><?php echo esc_html( $it['title'] ); ?></h3>
							<?php if ( $it['excerpt'] ) : ?>
								<p class="news-card__excerpt"><?php echo esc_html( $it['excerpt'] ); ?></p>
							<?php endif; ?>
						</div>
					</a>
				<?php endforeach; ?>
			</div>
		<?php else : ?>
			<div class="news-empty-block js-fade js-fade--delay-1">
				<p class="news-empty"><?php _e( '現在、公開されている導入事例はありません。', 'hachi' ); ?></p>
				<p class="news-empty-sub"><?php _e( 'News や note で「事例」「導入」「実績」などのキーワードを含む記事が投稿されると、自動的にこちらに集約されます。', 'hachi' ); ?></p>
				<div style="text-align:center;margin-top:40px">
					<a href="<?php echo esc_url( home_url( '/contact/' ) ); ?>" class="btn btn--teal">
						<?php _e( '導入相談・お問い合わせ', 'hachi' ); ?>
						<?php hachi_arrow_icon(); ?>
					</a>
				</div>
			</div>
		<?php endif; ?>

	</div>
</section>

<?php get_footer(); ?>
