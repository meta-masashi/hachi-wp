<?php
/**
 * HACHI Theme — archive-hachi_news.php (Light Monochrome v2)
 * 統合フィード: WP hachi_news + note.com RSS
 * 自動分類: News / Work / Blog （hachi_classify_content）
 */
get_header();

$tabs = [
	'all'  => 'ALL',
	'news' => 'NEWS',
	'work' => 'WORK',
	'blog' => 'BLOG',
];
$active = sanitize_key( $_GET['type'] ?? 'all' );
if ( ! array_key_exists( $active, $tabs ) ) $active = 'all';

$items = hachi_get_classified_items( [ 'category' => $active, 'limit' => 60 ] );
?>

<!-- ===== PAGE HERO ===== -->
<div class="page-hero">
	<div class="container">
		<div class="js-fade"><?php hachi_section_label( 'N e w s' ); ?></div>
		<h1 class="heading-en js-fade js-fade--delay-1">NEWS</h1>
		<p class="heading-jp js-fade js-fade--delay-2"><?php _e( 'お知らせ・導入事例・ブログ', 'hachi' ); ?></p>
	</div>
</div>

<!-- ===== NEWS LIST ===== -->
<section class="section news-archive">
	<div class="container">

		<!-- Filter tabs -->
		<div class="news-filter js-fade" role="tablist" aria-label="<?php esc_attr_e( 'コンテンツフィルター', 'hachi' ); ?>">
			<?php foreach ( $tabs as $key => $label ) :
				$is_active = $key === $active;
				$url       = add_query_arg( 'type', $key, get_post_type_archive_link( 'hachi_news' ) );
			?>
				<a
					href="<?php echo esc_url( $url ); ?>"
					class="news-filter__btn<?php echo $is_active ? ' is-active' : ''; ?>"
					role="tab"
					aria-selected="<?php echo $is_active ? 'true' : 'false'; ?>"
				>
					<?php echo esc_html( $label ); ?>
				</a>
			<?php endforeach; ?>
		</div>

		<div class="news-list js-fade js-fade--delay-1" role="tabpanel">
			<?php if ( ! empty( $items ) ) : ?>
				<?php foreach ( $items as $it ) :
					$is_external = $it['source'] === 'note';
					$cat         = $it['category'];
				?>
					<a
						href="<?php echo esc_url( $it['url'] ); ?>"
						class="news-card news-card--<?php echo esc_attr( $cat ); ?>"
						<?php echo $is_external ? 'target="_blank" rel="noopener noreferrer"' : ''; ?>
					>
						<div class="news-card__media">
							<?php if ( $it['thumbnail'] ) : ?>
								<img src="<?php echo esc_url( $it['thumbnail'] ); ?>" alt="" loading="lazy">
							<?php else : ?>
								<span class="news-card__media-placeholder"><?php echo esc_html( strtoupper( $cat ) ); ?></span>
							<?php endif; ?>
						</div>
						<div class="news-card__body">
							<div class="news-card__meta">
								<span class="news-card__cat news-card__cat--<?php echo esc_attr( $cat ); ?>">
									<?php echo esc_html( strtoupper( $cat ) ); ?>
								</span>
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
			<?php else : ?>
				<p class="news-empty"><?php _e( '該当する記事がありません。', 'hachi' ); ?></p>
			<?php endif; ?>
		</div>

	</div>
</section>

<?php get_footer(); ?>
