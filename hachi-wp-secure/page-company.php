<?php
/**
 * HACHI Theme — Company page v3
 * Auto-applied to page with slug "company" via WP template hierarchy
 * Updated: 2026-06-17 corp-v3 — 登記住所確定 / SECURITY セクション削除 / 沿革・アクセス独立セクション削除
 */
get_header();
?>

<main id="main-content">

<!-- ===== Section 1: ヒーロー ===== -->
<section class="company-hero">
	<div class="container">
		<div class="content-narrow company-hero__inner">
			<p class="eyebrow">COMPANY</p>
			<h1 class="company-hero__h1"><?php _e( '会社情報', 'hachi' ); ?></h1>
			<p class="company-hero__sub">HACHI Inc.</p>
		</div>
	</div>
</section>

<!-- ===== Section 2: 会社概要 ===== -->
<section class="company-overview">
	<div class="container">
		<div class="content-narrow">
			<h2 class="company-overview__heading"><?php _e( '会社概要', 'hachi' ); ?></h2>
			<dl class="company-table">
				<div class="company-table__row company-table__row--first">
					<dt class="company-table__label"><?php _e( '会社名', 'hachi' ); ?></dt>
					<dd class="company-table__value">株式会社 HACHI / HACHI Inc.</dd>
				</div>
				<div class="company-table__row company-table__row--divide">
					<dt class="company-table__label"><?php _e( '代表者', 'hachi' ); ?></dt>
					<dd class="company-table__value">佐々木 譲崇（代表取締役社長）</dd>
				</div>
				<div class="company-table__row">
					<dt class="company-table__label"><?php _e( '設立', 'hachi' ); ?></dt>
					<dd class="company-table__value">2022 年 3 月 25 日</dd>
				</div>
				<div class="company-table__row company-table__row--divide">
					<dt class="company-table__label"><?php _e( '資本金', 'hachi' ); ?></dt>
					<dd class="company-table__value">100 万円</dd>
				</div>
				<div class="company-table__row company-table__row--last">
					<dt class="company-table__label"><?php _e( '所在地', 'hachi' ); ?></dt>
					<dd class="company-table__value">〒180-0004 東京都武蔵野市吉祥寺本町 1-13-2 5F</dd>
				</div>
			</dl>
		</div>
	</div>
</section>

<!-- ===== Section 3: 事業内容 ===== -->
<section class="company-business">
	<div class="container">
		<div class="content-narrow">
			<h2 class="company-business__heading"><?php _e( '事業内容', 'hachi' ); ?></h2>
			<p class="company-business__body">
				<?php _e( 'On-site Service「HACHI Fieldwork / コンディション・インサイト」の提供、身体の状態観察・構造化・判断知変換に関する研究開発', 'hachi' ); ?>
			</p>
			<a href="<?php echo esc_url( home_url( '/service/' ) ); ?>" class="text-link company-business__link">
				<?php _e( 'サービス詳細を見る', 'hachi' ); ?> &rarr;
			</a>
		</div>
	</div>
</section>

<!-- ===== Section 4: フッター CTA ===== -->
<section class="company-footer-cta">
	<div class="container">
		<div class="company-footer-cta__inner">
			<h2 class="company-footer-cta__heading"><?php _e( 'お問い合わせ', 'hachi' ); ?></h2>
			<a href="<?php echo esc_url( home_url( '/contact/' ) ); ?>" class="btn btn--white">
				<?php _e( 'Contact Us', 'hachi' ); ?>
				<?php hachi_arrow_icon(); ?>
			</a>
		</div>
	</div>
</section>

</main>

<?php get_footer(); ?>
