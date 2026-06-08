<?php
/**
 * HACHI Theme — front-page.php
 * Template for the site front page
 * Updated: 2026-06-08 corp-refresh — copy aligned to コンディション・インサイト / compliance fixes
 */

get_header();
?>

<!-- ===== HERO ===== -->
<section class="hero" aria-label="<?php esc_attr_e( 'メインビジュアル', 'hachi' ); ?>">

	<div class="hero__bg-ghost ghost-text" aria-hidden="true">HACHI</div>
	<div class="hero__vertical-line" aria-hidden="true"></div>

	<div class="container">
		<div class="hero__content">

			<p class="hero__eyebrow">Condition Insight</p>

			<h1 class="hero__headline">
				変化のサインを、<br>見逃さない。
			</h1>

			<p class="hero__subheading">
				<span><?php _e( '人は突然、不調になるのではありません。', 'hachi' ); ?></span>
			</p>
			<p class="hero__lede" style="font-size:15px;line-height:2;color:#6e6e73;max-width:460px;margin-top:24px">
				<?php _e( '疲れや集中の変化は、じわじわと積み重なります。<br>コンディション・インサイトは、そのサインを組織として早めにつかみ、<br>本人と管理職が動けるきっかけをつくります。', 'hachi' ); ?>
			</p>

			<div class="hero__cta">
				<a href="<?php echo esc_url( home_url( '/contact/' ) ); ?>" class="btn btn--teal">
					<?php _e( 'サービス資料を請求する', 'hachi' ); ?>
					<?php hachi_arrow_icon(); ?>
				</a>
				<a href="<?php echo esc_url( home_url( '/about/' ) ); ?>" class="btn">
					<?php _e( 'About HACHI', 'hachi' ); ?>
					<?php hachi_arrow_icon(); ?>
				</a>
			</div>

		</div>
	</div>

	<p class="scroll-indicator" aria-hidden="true">SCROLL</p>

</section>

<!-- ===== NEWS TICKER ===== -->
<?php
$ticker = hachi_get_ticker_news();
if ( $ticker ) :
	?>
	<a href="<?php echo esc_url( get_permalink( $ticker->ID ) ); ?>" class="news-ticker js-fade">
		<div class="news-ticker__inner">
			<span class="news-ticker__tag">
				<?php echo esc_html( strtoupper( get_post_meta( $ticker->ID, '_hachi_news_type', true ) ?: 'NEWS' ) ); ?>
			</span>
			<span class="news-ticker__date">
				<?php echo esc_html( hachi_get_date( $ticker->ID ) ); ?>
			</span>
			<span class="news-ticker__text">
				<?php echo esc_html( get_the_title( $ticker->ID ) ); ?>
			</span>
			<span class="news-ticker__arrow" aria-hidden="true">→</span>
		</div>
	</a>
<?php endif; ?>

<!-- ===== ABOUT ===== -->
<section class="section" id="about">
	<div class="container">
		<div class="grid-2">

			<!-- Image -->
			<div class="about-image js-fade js-fade--left">
				<div class="about-image__inner">
					<?php
					$about_img_id = get_theme_mod( 'hachi_about_image' );
					if ( $about_img_id ) {
						echo wp_get_attachment_image( $about_img_id, 'hachi-portrait', false, [
							'alt'     => __( 'HACHIについて', 'hachi' ),
							'loading' => 'lazy',
						] );
					} else {
						echo '<div class="about-image__placeholder" aria-hidden="true">8</div>';
					}
					?>
				</div>
				<div class="about-image__tag">On-site Service</div>
			</div>

			<!-- Text -->
			<div class="js-fade js-fade--delay-1">
				<?php hachi_section_label( 'A b o u t' ); ?>
				<h2 class="heading-en heading-en--sm">ABOUT</h2>
				<p class="heading-jp">
					<?php _e( '身体の状態を観察し、<br>組織に見える形にする。', 'hachi' ); ?>
				</p>
				<p class="body-copy" style="margin-top:28px">
					<?php _e( '20〜100名規模の会社では、専任の産業保健スタッフを置けないケースがほとんどです。本人も言い出せない、管理職も気づけない——その構造が、気づきを遅らせています。HACHIは、社員の状態を組織として把握できる仕組みをつくります。', 'hachi' ); ?>
				</p>
				<div style="margin-top:40px">
					<a href="<?php echo esc_url( home_url( '/about/' ) ); ?>" class="btn btn--teal">
						View About <?php hachi_arrow_icon(); ?>
					</a>
				</div>
			</div>

		</div>
	</div>
</section>

<!-- ===== SERVICES ===== -->
<section class="section--light" id="services">
	<div class="container">

		<div class="js-fade">
			<?php hachi_section_label( 'S e r v i c e s' ); ?>
			<h2 class="heading-en">SERVICES</h2>
			<p class="heading-jp"><?php _e( '状態を見える形にする。', 'hachi' ); ?></p>
		</div>

		<div class="service-list js-fade js-fade--delay-1">

			<a href="<?php echo esc_url( home_url( '/service/' ) ); ?>" class="service-item">
				<span class="service-item__num">01</span>
				<div class="service-item__body">
					<p class="service-item__tag">ON-SITE SERVICE</p>
					<h3 class="service-item__title">コンディション・インサイト</h3>
					<p class="service-item__desc">
						<?php _e( '社員の状態変化のサインを、組織として早めにつかむ。10分チェックと現場コンディショニング指導で、気づきのしくみをつくります。', 'hachi' ); ?>
					</p>
				</div>
				<span class="service-item__arrow" aria-hidden="true">↗</span>
			</a>

		</div>
	</div>
</section>

<!-- ===== CULTURE PARALLAX BAND ===== -->
<div class="culture-band" id="culture">
	<div class="culture-band__bg" data-parallax="0.14">
		<div class="culture-band__bg-text" aria-hidden="true">HACHI</div>
	</div>
	<div class="culture-band__content">
		<div class="container">
			<div class="js-fade">
				<?php hachi_section_label( 'C u l t u r e', 'label--white' ); ?>
				<h2 class="heading-en heading-en--white">CULTURE</h2>
				<p class="heading-jp heading-jp--white">
					<?php _e( '永遠にβ版の、企業文化。', 'hachi' ); ?>
				</p>
				<p class="body-copy body-copy--white" style="margin-top:24px;max-width:460px">
					<?php _e( 'HACHIはチームで挑むことを大切にしています。「目の前の人の状態にこだわり、観察と構造化で創意工夫し続けるチーム」であるために、永遠にβ版の組織づくりを行っています。', 'hachi' ); ?>
				</p>
				<div style="margin-top:40px">
					<a href="<?php echo esc_url( home_url( '/about/#culture' ) ); ?>" class="btn btn--white">
						View Culture <?php hachi_arrow_icon(); ?>
					</a>
				</div>
			</div>
		</div>
	</div>
</div>

<!-- ===== WORKS ===== -->
<section class="section" id="works">
	<div class="container">

		<div style="display:flex;justify-content:space-between;align-items:flex-end;margin-bottom:0" class="js-fade">
			<div>
				<?php hachi_section_label( 'W o r k s' ); ?>
				<h2 class="heading-en">WORKS</h2>
			</div>
			<a href="<?php echo esc_url( home_url( '/works/' ) ); ?>" class="btn" style="margin-bottom:8px">
				View All <?php hachi_arrow_icon(); ?>
			</a>
		</div>

		<?php
		$works_query = new WP_Query( [
			'post_type'      => 'hachi_news',
			'posts_per_page' => 3,
			'post_status'    => 'publish',
			'meta_query'     => [ [ 'key' => '_hachi_news_type', 'value' => 'work' ] ],
			'orderby'        => 'date',
			'order'          => 'DESC',
		] );

		if ( $works_query->have_posts() ) :
			$i = 1;
		?>
		<div class="works-grid js-fade js-fade--delay-1">
			<?php while ( $works_query->have_posts() ) : $works_query->the_post(); ?>
				<a href="<?php the_permalink(); ?>" class="work-card">
					<?php if ( has_post_thumbnail() ) : ?>
						<?php the_post_thumbnail( 'hachi-card', [ 'class' => 'work-card__image', 'loading' => 'lazy' ] ); ?>
					<?php endif; ?>
					<p class="work-card__tag">Case Study</p>
					<h3 class="work-card__title"><?php the_title(); ?></h3>
					<span class="work-card__num" aria-hidden="true"><?php echo esc_html( str_pad( $i, 2, '0', STR_PAD_LEFT ) ); ?></span>
				</a>
			<?php $i++; endwhile; wp_reset_postdata(); ?>
		</div>
		<?php endif; ?>

	</div>
</section>

<!-- ===== NEWS ===== -->
<section class="section--light" id="news">
	<div class="container">

		<div style="display:flex;justify-content:space-between;align-items:flex-end;margin-bottom:48px" class="js-fade">
			<div>
				<?php hachi_section_label( 'N e w s' ); ?>
				<h2 class="heading-en">NEWS</h2>
			</div>
			<a href="<?php echo esc_url( home_url( '/news/' ) ); ?>" class="btn">
				View All <?php hachi_arrow_icon(); ?>
			</a>
		</div>

		<?php
		$items = hachi_get_classified_items( [ 'category' => 'all', 'limit' => 5 ] );
		if ( ! empty( $items ) ) :
		?>
		<div style="border-top:1px solid var(--gray2)" class="js-fade js-fade--delay-1">
			<?php foreach ( $items as $it ) :
				$is_external = $it['source'] === 'note';
				$cat         = strtoupper( $it['category'] );
				$is_blog     = $it['category'] === 'blog';
			?>
				<a href="<?php echo esc_url( $it['url'] ); ?>" class="post-row<?php echo $is_blog ? ' post-row--blog' : ''; ?>"
					<?php echo $is_external ? 'target="_blank" rel="noopener noreferrer nofollow"' : ''; ?>>
					<span class="post-row__date"><?php echo esc_html( $it['date_str'] ); ?></span>
					<span class="post-row__cat"><?php echo esc_html( $cat ); ?><?php if ( $is_external ) echo ' ↗'; ?></span>
					<span class="post-row__title"><?php echo esc_html( $it['title'] ); ?></span>
					<span class="post-row__arrow" aria-hidden="true">→</span>
				</a>
			<?php endforeach; ?>
		</div>
		<?php else : ?>
			<p style="color:var(--gray);padding:40px 0"><?php _e( 'ニュースはありません。', 'hachi' ); ?></p>
		<?php endif; ?>

	</div>
</section>

<?php get_footer(); ?>
