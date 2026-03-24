<?php
/**
 * Template Name: About Page
 * HACHI Theme — About page
 */
get_header();
?>

<div class="page-hero">
	<div class="page-hero__ghost ghost-text" aria-hidden="true">ABOUT</div>
	<div class="container">
		<div class="js-fade"><?php hachi_section_label( 'A b o u t' ); ?></div>
		<h1 class="heading-en js-fade js-fade--delay-1" style="font-size:clamp(52px,9vw,112px)">ABOUT</h1>
		<p class="heading-jp js-fade js-fade--delay-2"><?php _e( 'HACHIについて', 'hachi' ); ?></p>
	</div>
</div>

<!-- VISION -->
<section class="section">
	<div class="container">
		<div class="grid-2">
			<div class="js-fade">
				<div style="font-family:var(--mono);font-size:min(18vw,160px);font-weight:300;color:rgba(0,0,0,0.04);line-height:1;margin-bottom:-36px" aria-hidden="true">V</div>
				<?php hachi_section_label( 'V i s i o n' ); ?>
				<h2 class="heading-en heading-en--sm">VISION</h2>
				<p class="heading-jp"><?php _e( '誰もが、「健康」で<br>悩まない世界をつくる。', 'hachi' ); ?></p>
			</div>
			<div class="js-fade js-fade--delay-1">
				<blockquote style="border-left:3px solid var(--teal);padding-left:40px;margin:0">
					<p style="font-family:var(--serif);font-size:17px;line-height:2.4;color:#444">
						<?php _e( 'もし、病気や怪我の心配をせず、今を思いきり楽しめる世界があったなら。私たちは最新のテクノロジーや科学の力を使いながら、一人ひとりが確かな健康を抱き、人生の可能性を広げる鍵になることを目指しています。', 'hachi' ); ?>
					</p>
				</blockquote>
			</div>
		</div>
	</div>
</section>

<!-- VALUES -->
<section class="section--muted" id="values">
	<div class="container">
		<div class="js-fade" style="text-align:center;margin-bottom:64px">
			<?php hachi_section_label( 'V a l u e s', 'label--center' ); ?>
			<h2 class="heading-en">VALUES</h2>
			<p class="heading-jp"><?php _e( 'HACHIが大切にする価値観', 'hachi' ); ?></p>
		</div>
		<div class="values-grid js-fade js-fade--delay-1">
			<?php
			$values = [
				[ '一', '真実', 'Truth',     'データと科学に基づいた誠実なケアで、確かな答えを届ける。' ],
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

<!-- CEO MESSAGE -->
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
			} else {
				echo '<div class="ceo-section__placeholder" aria-hidden="true">佐</div>';
			}
			?>
			<div class="ceo-section__badge">
				<p class="ceo-section__name">佐々木 譲崇</p>
				<p class="ceo-section__role"><?php _e( '代表取締役社長 / 株式会社HACHI', 'hachi' ); ?></p>
			</div>
		</div>
		<div class="ceo-section__text">
			<?php hachi_section_label( 'C E O   M e s s a g e', 'label--white' ); ?>
			<h2 class="ceo-section__quote">
				ひたむきに向き合う。
				<em class="ceo-section__quote-en">CEO MESSAGE</em>
			</h2>
			<p class="body-copy body-copy--white">
				<?php _e( 'スポーツ医療の現場で長年積み重ねた経験から確信したことがある。テクノロジーは、人と人の間にある「温かさ」を消すためにあるのではない。むしろ、煩雑な作業や曖昧な判断をテクノロジーが担うことで、人はより深く、目の前の人と向き合えるようになる。', 'hachi' ); ?>
			</p>
			<br>
			<p class="body-copy body-copy--white">
				<?php _e( 'データ活用、AI、ウェアラブル——最新のテクノロジーを最大限に活かしながら、私たちは「人」を中心に置くことを決して忘れない。それがHACHIの原点です。', 'hachi' ); ?>
			</p>
		</div>
	</div>
</section>

<?php get_footer(); ?>
