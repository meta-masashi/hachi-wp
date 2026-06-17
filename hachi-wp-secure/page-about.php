<?php
/**
 * HACHI Theme — About page (Light Monochrome v2)
 * Auto-applied to page with slug "about" via WP template hierarchy
 * Updated: 2026-06-08 corp-refresh — PACE/SaaS/アスリート表現除去 / 現ポジション整合
 */
get_header();
?>
<main id="main-content">

<!-- ===== PAGE HERO ===== -->
<div class="page-hero">
	<div class="container">
		<div class="js-fade"><?php hachi_section_label( 'A b o u t' ); ?></div>
		<h1 class="heading-en js-fade js-fade--delay-1">ABOUT</h1>
		<p class="heading-jp js-fade js-fade--delay-2"><?php _e( 'HACHIについて', 'hachi' ); ?></p>
	</div>
</div>

<!-- ===== MISSION STATEMENT ===== -->
<section class="section about-mission">
	<div class="container">
		<div class="js-fade" style="max-width:980px">
			<?php hachi_section_label( 'M i s s i o n' ); ?>
			<p class="about-mission__statement">
				<?php _e( 'テクノロジーで、<br>人と人の「向き合う時間」を取り戻す。', 'hachi' ); ?>
			</p>
			<p class="about-mission__body">
				<?php _e( '状態の観察と構造化をテクノロジーに委ねることで、管理職や経営者は「目の前の社員」に向き合える時間を取り戻せる。HACHIは現場の実証を積み重ねながら、身体の状態を再現可能な判断知へ変換する基盤をつくります。', 'hachi' ); ?>
			</p>
		</div>
	</div>
</section>

<!-- ===== VISION ===== -->
<section class="section--light" id="vision">
	<div class="container">
		<div class="grid-2">

			<div class="js-fade">
				<?php hachi_section_label( 'V i s i o n' ); ?>
				<h2 class="heading-en heading-en--sm">VISION</h2>
				<p class="heading-jp">
					<?php _e( '誰もが、「健康」で<br>悩まない世界をつくる。', 'hachi' ); ?>
				</p>
				<p class="body-copy" style="margin-top:32px">
					<?php _e( 'もし、病気や怪我の心配をせず、今を思いきり楽しめる世界があったなら。私たちは最新のテクノロジーや科学の力を使いながら、一人ひとりが確かな健康を抱き、人生の可能性を広げる鍵になることを目指しています。', 'hachi' ); ?>
				</p>
			</div>

			<!-- about-concept.svg: HACHI思想の構造図 — 観察→構造化→判断 -->
			<div class="js-fade js-fade--delay-1" style="margin-top:56px">
				<img
					src="<?php echo esc_url( get_template_directory_uri() . '/assets/about-concept.svg' ); ?>"
					alt="<?php esc_attr_e( '観察・構造化・判断 — 身体知を、再現可能な判断知へ。', 'hachi' ); ?>"
					width="800"
					height="400"
					loading="lazy"
					style="display:block;width:100%;max-width:800px;"
				/>
			</div>

		</div>
	</div>
</section>

<!-- ===== VALUES ===== -->
<section class="section" id="values">
	<div class="container">

		<div class="js-fade" style="text-align:center;margin-bottom:72px;max-width:680px;margin-left:auto;margin-right:auto">
			<?php hachi_section_label( 'V a l u e s' ); ?>
			<h2 class="heading-en">VALUES</h2>
			<p class="heading-jp"><?php _e( 'HACHIが大切にする価値観', 'hachi' ); ?></p>
			<p class="body-copy" style="margin:24px auto 0">
				<?php _e( '私たちが日々の判断と行動の拠り所としている、3つの指針。', 'hachi' ); ?>
			</p>
		</div>

		<div class="values-grid js-fade js-fade--delay-1">
			<?php
			$values = [
				[ '一', '真実', 'Truth',     'データと科学に基づいた誠実な観察で、確かな判断を届ける。' ],
				[ '二', '至誠', 'Sincerity', '目の前の一人ひとりに、心から向き合い続ける。' ],
				[ '三', '感謝', 'Gratitude', 'すべての出会いと機会に感謝し、共に成長する。' ],
			];
			foreach ( $values as $v ) :
			?>
				<div class="value-card">
					<div class="value-card__kanji"><?php echo esc_html( $v[0] ); ?></div>
					<div class="value-card__name"><?php echo esc_html( $v[1] ); ?></div>
					<div class="value-card__en"><?php echo esc_html( $v[2] ); ?></div>
					<p class="value-card__desc"><?php echo esc_html( $v[3] ); ?></p>
				</div>
			<?php endforeach; ?>
		</div>

	</div>
</section>

<!-- ===== STORY / TIMELINE ===== -->
<section class="section--light" id="story">
	<div class="container">

		<div class="js-fade" style="max-width:680px;margin-bottom:80px">
			<?php hachi_section_label( 'S t o r y' ); ?>
			<h2 class="heading-en heading-en--sm">OUR STORY</h2>
			<p class="heading-jp"><?php _e( '現場の確信から、テクノロジーへ。', 'hachi' ); ?></p>
			<p class="body-copy" style="margin-top:24px">
				<?php _e( '専門家の暗黙知は、観察と構造化によって再現可能な判断知に変わる——その確信から HACHI は生まれました。', 'hachi' ); ?>
			</p>
		</div>

		<div class="timeline js-fade js-fade--delay-1">
			<?php
			$milestones = [
				[ '2022', '03', '株式会社HACHI 設立',   '吉祥寺本町を拠点に、身体専門知の構造化とテクノロジー実証を開始。' ],
				[ '2023', '—',  'HACHI Fieldwork リリース', 'オフィスワーカー向け On-site Service を展開。初期導入企業と共創。' ],
				[ '2024', '—',  'コンディション・インサイト 開発',  '社員の状態変化を組織として把握するしくみを設計・実証。' ],
			];
			foreach ( $milestones as $i => $m ) :
			?>
				<div class="timeline__row">
					<div class="timeline__year">
						<span class="timeline__year-num"><?php echo esc_html( $m[0] ); ?></span>
						<span class="timeline__year-month"><?php echo esc_html( $m[1] ); ?></span>
					</div>
					<div class="timeline__marker" aria-hidden="true"></div>
					<div class="timeline__body">
						<h3 class="timeline__title"><?php echo esc_html( $m[2] ); ?></h3>
						<p class="timeline__desc"><?php echo esc_html( $m[3] ); ?></p>
					</div>
				</div>
			<?php endforeach; ?>
		</div>

	</div>
</section>


<!-- ===== CEO MESSAGE (strategic dark block) ===== -->
<section id="message">
	<div class="ceo-section js-fade">
		<div class="ceo-section__image">
			<?php
			$ceo_img = get_theme_mod( 'hachi_ceo_image' );
			if ( $ceo_img ) {
				echo wp_get_attachment_image( $ceo_img, 'hachi-portrait', false, [
					'alt'     => __( '代表取締役社長 佐々木 譲崇', 'hachi' ),
					'loading' => 'lazy',
				] );
			}
			// photo placeholder removed — badge is displayed on the dark panel
			?>
			<div class="ceo-section__badge">
				<p class="ceo-section__name">佐々木 譲崇</p>
				<p class="ceo-section__role"><?php _e( '代表取締役社長 / 株式会社HACHI', 'hachi' ); ?></p>
			</div>
		</div>
		<div class="ceo-section__text">
			<?php hachi_section_label( 'C E O   M e s s a g e', 'label--white' ); ?>
			<h2 class="ceo-section__quote">
				ひたむきに<br>向き合う。
				<em class="ceo-section__quote-en">CEO MESSAGE</em>
			</h2>
			<p class="body-copy body-copy--white">
				<?php _e( '現場で長年積み重ねた経験から確信したことがある。テクノロジーは、人と人の間にある「温かさ」を消すためにあるのではない。むしろ、煩雑な作業や曖昧な判断をテクノロジーが担うことで、人はより深く、目の前の人と向き合えるようになる。', 'hachi' ); ?>
			</p>
			<br>
			<p class="body-copy body-copy--white">
				<?php _e( 'データ活用、AI、観察の構造化——最新のテクノロジーを最大限に活かしながら、私たちは「人」を中心に置くことを決して忘れない。それがHACHIの原点です。', 'hachi' ); ?>
			</p>
		</div>
	</div>
</section>

<!-- ===== CTA ===== -->
<section class="section about-cta">
	<div class="container">
		<div class="js-fade" style="text-align:center;max-width:760px;margin:0 auto">
			<h2 class="heading-en heading-en--sm"><?php _e( 'GET IN TOUCH', 'hachi' ); ?></h2>
			<p class="heading-jp" style="margin-top:20px"><?php _e( '私たちと、次の一歩を。', 'hachi' ); ?></p>
			<p class="body-copy" style="margin:28px auto 0">
				<?php _e( '会社情報、サービス導入、取材など、お気軽にお問い合わせください。', 'hachi' ); ?>
			</p>
			<div class="about-cta__buttons">
				<a href="<?php echo esc_url( home_url( '/contact/' ) ); ?>" class="btn btn--teal">
					<?php _e( 'Contact Us', 'hachi' ); ?>
					<?php hachi_arrow_icon(); ?>
				</a>
				<a href="<?php echo esc_url( home_url( '/company/' ) ); ?>" class="btn">
					<?php _e( 'View Company', 'hachi' ); ?>
					<?php hachi_arrow_icon(); ?>
				</a>
			</div>
		</div>
	</div>
</section>

</main>
<?php get_footer(); ?>
