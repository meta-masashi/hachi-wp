<?php
/**
 * HACHI Theme — front-page.php
 * Template for the site front page
 */

get_header();
?>

<!-- ===== HERO ===== -->
<section class="hero" aria-label="<?php esc_attr_e( 'メインビジュアル', 'hachi' ); ?>">

	<div class="hero__bg-ghost ghost-text" aria-hidden="true">HACHI</div>
	<div class="hero__vertical-line" aria-hidden="true"></div>

	<div class="container">
		<div class="hero__content">

			<p class="hero__eyebrow">beyond Wellness</p>

			<div class="hero__headline-line">
				<span>BEYOND</span>
			</div>
			<div class="hero__headline-line">
				<span>THE BODY.</span>
			</div>

			<p class="hero__subheading">
				<span><?php _e( '身体の、その先へ。', 'hachi' ); ?></span>
			</p>
			<p class="hero__lede" style="font-size:15px;line-height:2;color:#6e6e73;max-width:460px;margin-top:24px">
				<?php _e( 'スポーツ医療とウェルネスの現場に、<br>テクノロジーで革新をもたらす。', 'hachi' ); ?>
			</p>

			<div class="hero__cta">
				<a href="<?php echo esc_url( home_url( '/service/' ) ); ?>" class="btn">
					<?php _e( 'Service', 'hachi' ); ?>
					<?php hachi_arrow_icon(); ?>
				</a>
				<a href="<?php echo esc_url( home_url( '/about/' ) ); ?>" class="btn btn--teal">
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
				<div class="about-image__tag">SaaS &times; Agency</div>
			</div>

			<!-- Text -->
			<div class="js-fade js-fade--delay-1">
				<div style="font-family:var(--mono);font-size:clamp(60px,11vw,130px);font-weight:300;color:rgba(0,0,0,0.04);line-height:1;margin-bottom:-24px" aria-hidden="true">A</div>
				<?php hachi_section_label( 'A b o u t' ); ?>
				<h2 class="heading-en heading-en--sm">ABOUT</h2>
				<p class="heading-jp">
					<?php _e( '私たちはスポーツ医療と<br>ウェルネスに挑む、テクノロジー企業です。', 'hachi' ); ?>
				</p>
				<p class="body-copy" style="margin-top:28px">
					<?php _e( '煩雑な評価作業や不確実な意思決定をテクノロジーに委ねることで、医療・トレーニングスタッフは「目の前のアスリート」にひたむきに向き合える。HACHIはOn-site ServiceとSaaSプロダクトの両軸で、健康の現場に革新をもたらします。', 'hachi' ); ?>
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
			<p class="heading-jp"><?php _e( 'リアルとデジタル、2つの現場で挑む。', 'hachi' ); ?></p>
		</div>

		<div class="service-list js-fade js-fade--delay-1">

			<a href="<?php echo esc_url( home_url( '/service/#reboot' ) ); ?>" class="service-item">
				<span class="service-item__num">01</span>
				<div class="service-item__body">
					<p class="service-item__tag">ON-SITE SERVICE</p>
					<h3 class="service-item__title">REBOOT-WORK</h3>
					<p class="service-item__desc">
						<?php _e( 'オフィス内で生じる健康課題を医学的評価をもとに、専門チームがご希望の場所で課題解決のためのサービス提供を行います。', 'hachi' ); ?>
					</p>
				</div>
				<span class="service-item__arrow" aria-hidden="true">↗</span>
			</a>

			<a href="<?php echo esc_url( home_url( '/service/#pace' ) ); ?>" class="service-item">
				<span class="service-item__num">02</span>
				<div class="service-item__body">
					<p class="service-item__tag">SPORTS MEDICAL SaaS</p>
					<h3 class="service-item__title">PACE v3.0</h3>
					<p class="service-item__desc">
						<?php _e( '因果推論AIとデジタルツインで、スポーツ医療チームの意思決定を革新するプラットフォーム。', 'hachi' ); ?>
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
		<div class="culture-band__bg-text" aria-hidden="true">WELLNESS</div>
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
					<?php _e( 'HACHIはチームで挑むことを大切にしています。「目の前の人の健康にこだわり、テクノロジーで創意工夫し続けるチーム」であるために、永遠にβ版の組織づくりを行っています。', 'hachi' ); ?>
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
			'post_type'      => 'hachi_work',
			'posts_per_page' => 3,
			'post_status'    => 'publish',
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
					<p class="work-card__client">
						<?php echo esc_html( get_post_meta( get_the_ID(), '_hachi_work_client', true ) ); ?>
					</p>
					<span class="work-card__num" aria-hidden="true">0<?php echo esc_html( $i ); ?></span>
				</a>
			<?php $i++; endwhile; wp_reset_postdata(); ?>
		</div>
		<?php else : ?>
			<!-- Placeholder works if no posts -->
			<div class="works-grid js-fade js-fade--delay-1">
				<?php
				$placeholder_works = [
					[ 'REBOOT-WORK導入で社員の腰痛訴えが60%減少', '某大手IT企業' ],
					[ 'PACE v3.0でリハビリ期間が平均30%短縮', 'プロサッカークラブ' ],
					[ 'AIアセスメントによりアスリートの怪我予防率が向上', '競技スポーツ協会' ],
				];
				foreach ( $placeholder_works as $idx => $work ) :
					$num = str_pad( $idx + 1, 2, '0', STR_PAD_LEFT );
				?>
					<div class="work-card">
						<p class="work-card__tag">Case Study</p>
						<h3 class="work-card__title"><?php echo esc_html( $work[0] ); ?></h3>
						<p class="work-card__client"><?php echo esc_html( $work[1] ); ?></p>
						<span class="work-card__num" aria-hidden="true"><?php echo esc_html( $num ); ?></span>
					</div>
				<?php endforeach; ?>
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
		$news_query = hachi_get_recent_news( 3 );
		if ( $news_query->have_posts() ) :
		?>
		<div style="border-top:1px solid var(--gray2)" class="js-fade js-fade--delay-1">
			<?php while ( $news_query->have_posts() ) : $news_query->the_post();
				$type = get_post_meta( get_the_ID(), '_hachi_news_type', true ) ?: 'news';
				$is_blog = $type === 'blog';
			?>
				<a href="<?php the_permalink(); ?>" class="post-row<?php echo $is_blog ? ' post-row--blog' : ''; ?>">
					<span class="post-row__date"><?php echo esc_html( hachi_get_date() ); ?></span>
					<span class="post-row__cat"><?php echo esc_html( strtoupper( $type ) ); ?></span>
					<span class="post-row__title"><?php the_title(); ?></span>
					<span class="post-row__arrow" aria-hidden="true">→</span>
				</a>
			<?php endwhile; wp_reset_postdata(); ?>
		</div>
		<?php else : ?>
			<p style="color:var(--gray);padding:40px 0"><?php _e( 'ニュースはありません。', 'hachi' ); ?></p>
		<?php endif; ?>

	</div>
</section>

<?php get_footer(); ?>
