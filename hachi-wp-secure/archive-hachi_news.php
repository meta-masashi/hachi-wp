<?php
/**
 * HACHI Theme — archive-hachi_news.php (Light Monochrome v2)
 * News archive with WP posts + note.com RSS integration
 */
get_header();

$filter_tabs = [
	'all'   => 'ALL',
	'news'  => 'NEWS',
	'press' => 'PRESS',
	'media' => 'MEDIA',
	'blog'  => 'BLOG',
	'note'  => 'NOTE',
];
$active_filter = sanitize_key( $_GET['type'] ?? 'all' );
if ( ! array_key_exists( $active_filter, $filter_tabs ) ) {
	$active_filter = 'all';
}
?>

<!-- ===== PAGE HERO ===== -->
<div class="page-hero">
	<div class="container">
		<div class="js-fade"><?php hachi_section_label( 'N e w s' ); ?></div>
		<h1 class="heading-en js-fade js-fade--delay-1">NEWS</h1>
		<p class="heading-jp js-fade js-fade--delay-2"><?php _e( 'お知らせ・メディア掲載・ブログ', 'hachi' ); ?></p>
	</div>
</div>

<!-- ===== NEWS LIST ===== -->
<section class="section news-archive">
	<div class="container">

		<!-- Filter tabs -->
		<div class="news-filter js-fade" role="tablist" aria-label="<?php esc_attr_e( 'ニュースフィルター', 'hachi' ); ?>">
			<?php foreach ( $filter_tabs as $key => $label ) :
				$is_active = $key === $active_filter;
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

		<?php
		// ====== Build unified list: WP hachi_news + note.com RSS ======
		$items = [];

		// 1) WordPress hachi_news
		if ( $active_filter !== 'note' ) {
			$wp_args = [
				'post_type'      => 'hachi_news',
				'posts_per_page' => 30,
				'post_status'    => 'publish',
				'orderby'        => 'date',
				'order'          => 'DESC',
				'no_found_rows'  => true,
			];
			if ( $active_filter !== 'all' ) {
				$wp_args['meta_key']   = '_hachi_news_type';
				$wp_args['meta_value'] = sanitize_key( $active_filter );
			}
			$wp_q = new WP_Query( $wp_args );
			while ( $wp_q->have_posts() ) { $wp_q->the_post();
				$type = get_post_meta( get_the_ID(), '_hachi_news_type', true ) ?: 'news';
				$items[] = [
					'source'    => 'wp',
					'type'      => $type,
					'date_ts'   => (int) get_the_date( 'U' ),
					'date_str'  => hachi_get_date(),
					'title'     => get_the_title(),
					'url'       => get_permalink(),
					'excerpt'   => wp_trim_words( get_the_excerpt(), 30, '…' ),
					'thumbnail' => has_post_thumbnail() ? get_the_post_thumbnail_url( null, 'medium' ) : '',
				];
			}
			wp_reset_postdata();
		}

		// 2) note.com RSS
		if ( $active_filter === 'all' || $active_filter === 'note' ) {
			$note_items = hachi_get_note_posts( 20 );
			foreach ( $note_items as $n ) {
				$items[] = [
					'source'    => 'note',
					'type'      => 'note',
					'date_ts'   => (int) $n['date'],
					'date_str'  => $n['date'] ? date_i18n( 'Y.m.d', (int) $n['date'] ) : '',
					'title'     => $n['title'],
					'url'       => $n['url'],
					'excerpt'   => $n['excerpt'],
					'thumbnail' => $n['thumbnail'],
				];
			}
		}

		// Sort combined by date desc
		usort( $items, fn( $a, $b ) => $b['date_ts'] <=> $a['date_ts'] );
		?>

		<div class="news-list js-fade js-fade--delay-1" role="tabpanel">
			<?php if ( ! empty( $items ) ) : ?>
				<?php foreach ( $items as $it ) :
					$is_external = $it['source'] === 'note';
				?>
					<a
						href="<?php echo esc_url( $it['url'] ); ?>"
						class="news-card news-card--<?php echo esc_attr( $it['type'] ); ?>"
						<?php echo $is_external ? 'target="_blank" rel="noopener noreferrer"' : ''; ?>
					>
						<div class="news-card__media">
							<?php if ( $it['thumbnail'] ) : ?>
								<img src="<?php echo esc_url( $it['thumbnail'] ); ?>" alt="" loading="lazy">
							<?php else : ?>
								<span class="news-card__media-placeholder"><?php echo esc_html( strtoupper( $it['type'] ) ); ?></span>
							<?php endif; ?>
						</div>
						<div class="news-card__body">
							<div class="news-card__meta">
								<span class="news-card__cat news-card__cat--<?php echo esc_attr( $it['type'] ); ?>">
									<?php echo esc_html( strtoupper( $it['type'] ) ); ?>
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
