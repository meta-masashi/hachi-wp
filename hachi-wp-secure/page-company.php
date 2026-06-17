<?php
/**
 * HACHI Theme — Company page (Light Monochrome v2)
 * Auto-applied to page with slug "company" via WP template hierarchy
 * Updated: 2026-06-08 corp-refresh — PACE除去 / HIPAA→個情法置換 / 事業内容修正
 */
get_header();
?>
<main id="main-content">

<!-- ===== PAGE HERO ===== -->
<div class="page-hero">
	<div class="container">
		<div class="js-fade"><?php hachi_section_label( 'C o m p a n y' ); ?></div>
		<h1 class="heading-en js-fade js-fade--delay-1">COMPANY</h1>
		<p class="heading-jp js-fade js-fade--delay-2"><?php _e( '会社概要', 'hachi' ); ?></p>
	</div>
</div>

<!-- ===== PROFILE ===== -->
<section class="section company-profile">
	<div class="container">
		<div class="company-profile__grid">

			<!-- Left: Name block -->
			<div class="js-fade">
				<?php hachi_section_label( 'P r o f i l e' ); ?>
				<h2 class="company-profile__name">
					株式会社<br>HACHI
				</h2>
				<p class="company-profile__en">HACHI Inc.</p>
				<p class="company-profile__tagline">
					<?php _e( '身体知を、再現可能な判断知へ。', 'hachi' ); ?>
				</p>
			</div>

			<!-- Right: Profile table -->
			<div class="js-fade js-fade--delay-1">
				<dl class="company-dl">
					<?php
					$rows = [
						[ '会社名',         '株式会社HACHI / HACHI Inc.' ],
						[ '代表取締役社長', '佐々木 譲崇' ],
						[ '設立',           '2022年3月25日' ],
						[ '資本金',         '金100万円' ],
						[ '所在地',         "〒180-0004\n東京都武蔵野市吉祥寺本町 1-13-2 5F" ],
						[ '事業内容',       "On-site Service「HACHI Fieldwork / コンディション・インサイト」の提供\n身体の状態観察・構造化・判断知変換に関する研究開発" ],
					];
					foreach ( $rows as $row ) :
					?>
						<div class="company-dl__row">
							<dt><?php echo esc_html( $row[0] ); ?></dt>
							<dd><?php echo nl2br( esc_html( $row[1] ) ); ?></dd>
						</div>
					<?php endforeach; ?>
				</dl>
			</div>

		</div>
	</div>
</section>

<!-- ===== SECURITY (strategic dark block) ===== -->
<section class="company-security" id="security">
	<div class="container">

		<div class="company-security__header js-fade">
			<?php hachi_section_label( 'S e c u r i t y', 'label--white' ); ?>
			<h2 class="company-security__title">SECURITY</h2>
			<p class="company-security__subtitle">
				<?php _e( '健康情報を守る、妥協なきデータ保護。', 'hachi' ); ?>
			</p>
			<p class="company-security__lead">
				<?php _e( '個人情報保護法・GDPR に準拠したデータ管理設計を全サービスで徹底。お預かりするすべてのデータを、暗号化・分離・マスキングの3層で守ります。', 'hachi' ); ?>
			</p>
		</div>

		<div class="company-security__grid js-fade js-fade--delay-1">
			<?php
			$security_items = [
				[ '01', '個人情報保護法・GDPR 準拠設計', '個人情報保護法および GDPR の要件に準拠したシステムアーキテクチャを採用しています。' ],
				[ '02', 'TLS 1.3 暗号化',                 '最新のTLS 1.3プロトコルによる通信暗号化で、データの盗聴・改ざんを防止します。' ],
				[ '03', 'テナントデータ完全分離',          'Row Level Security（RLS）により、テナント間のデータを完全分離。情報漏洩リスクを低減します。' ],
			];
			foreach ( $security_items as $item ) :
			?>
				<div class="company-security__card">
					<div class="company-security__num"><?php echo esc_html( $item[0] ); ?></div>
					<h3 class="company-security__card-title"><?php echo esc_html( $item[1] ); ?></h3>
					<p class="company-security__card-desc"><?php echo esc_html( $item[2] ); ?></p>
				</div>
			<?php endforeach; ?>
		</div>

	</div>
</section>

<!-- ===== CTA ===== -->
<section class="section about-cta">
	<div class="container">
		<div class="js-fade" style="text-align:center;max-width:760px;margin:0 auto">
			<h2 class="heading-en heading-en--sm"><?php _e( 'GET IN TOUCH', 'hachi' ); ?></h2>
			<p class="heading-jp" style="margin-top:20px"><?php _e( 'まずは、お気軽にご相談ください。', 'hachi' ); ?></p>
			<p class="body-copy" style="margin:28px auto 0">
				<?php _e( 'サービス導入、取材など、お問い合わせはこちらから。', 'hachi' ); ?>
			</p>
			<div class="about-cta__buttons">
				<a href="<?php echo esc_url( home_url( '/contact/' ) ); ?>" class="btn btn--teal">
					<?php _e( 'Contact Us', 'hachi' ); ?>
					<?php hachi_arrow_icon(); ?>
				</a>
				<a href="<?php echo esc_url( home_url( '/service/' ) ); ?>" class="btn">
					<?php _e( 'Service', 'hachi' ); ?>
					<?php hachi_arrow_icon(); ?>
				</a>
			</div>
		</div>
	</div>
</section>

</main>
<?php get_footer(); ?>
