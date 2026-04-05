<?php
/**
 * HACHI Theme — Company page (Light Monochrome v2)
 * Auto-applied to page with slug "company"
 */
get_header();
?>

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
					<?php _e( 'テクノロジーで、人と人の「向き合う時間」を取り戻す。', 'hachi' ); ?>
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
						[ '事業内容',       "On-site Service「REBOOT-WORK」の提供\nスポーツ医療AI-SaaS「PACE」の開発（準備中）" ],
						[ 'お問い合わせ',   'info@hachi-wellnesshack.com' ],
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

<!-- ===== ACCESS / LOCATION ===== -->
<section class="section--light" id="access">
	<div class="container">

		<div class="js-fade" style="max-width:680px;margin-bottom:64px">
			<?php hachi_section_label( 'A c c e s s' ); ?>
			<h2 class="heading-en heading-en--sm">ACCESS</h2>
			<p class="heading-jp"><?php _e( '吉祥寺、井の頭の森に近いオフィスから。', 'hachi' ); ?></p>
		</div>

		<div class="access-grid js-fade js-fade--delay-1">

			<div class="access-info">
				<p class="access-info__label">HEAD OFFICE</p>
				<address class="access-info__address">
					〒180-0004<br>
					東京都武蔵野市吉祥寺本町<br>
					1-13-2 5F
				</address>
				<dl class="access-info__dl">
					<div>
						<dt>最寄り駅</dt>
						<dd>JR中央線・総武線<br>京王井の頭線「吉祥寺」駅 徒歩5分</dd>
					</div>
					<div>
						<dt>営業時間</dt>
						<dd>平日 10:00 – 19:00<br>（土日祝休業）</dd>
					</div>
				</dl>
			</div>

			<div class="access-map">
				<iframe
					src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d1618.6!2d139.5785!3d35.7053!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x0!2z5ZCJ56Wl5a-65pys55S6!5e0!3m2!1sja!2sjp!4v1700000000000"
					loading="lazy"
					referrerpolicy="no-referrer-when-downgrade"
					allowfullscreen
					title="HACHI本社 アクセスマップ"
					style="border:0;width:100%;height:100%"></iframe>
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
				<?php _e( '医療・健康情報を守る、妥協なきデータ保護。', 'hachi' ); ?>
			</p>
			<p class="company-security__lead">
				<?php _e( '医療機関水準のセキュリティ設計を全サービスで徹底。お預かりするすべてのデータを、暗号化・分離・マスキングの3層で守ります。', 'hachi' ); ?>
			</p>
		</div>

		<div class="company-security__grid js-fade js-fade--delay-1">
			<?php
			$security_items = [
				[ '01', 'HIPAA準拠設計',          '医療情報取扱の国際基準HIPAAに準拠したシステムアーキテクチャを採用しています。' ],
				[ '02', 'TLS 1.3 暗号化',          '最新のTLS 1.3プロトコルによる通信暗号化で、データの盗聴・改ざんを防止します。' ],
				[ '03', 'テナントデータ完全分離', 'Row Level Security（RLS）により、テナント間のデータを完全分離。情報漏洩リスクを低減します。' ],
				[ '04', '顔データ自動マスキング', '動画解析時に顔データを自動マスキング処理し、プライバシーを確実に保護します。' ],
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
			<p class="heading-jp" style="margin-top:20px"><?php _e( 'お気軽にお問い合わせください。', 'hachi' ); ?></p>
			<div class="about-cta__buttons">
				<a href="<?php echo esc_url( home_url( '/contact/' ) ); ?>" class="btn btn--teal">
					<?php _e( 'Contact Us', 'hachi' ); ?>
					<?php hachi_arrow_icon(); ?>
				</a>
				<a href="<?php echo esc_url( home_url( '/about/' ) ); ?>" class="btn">
					<?php _e( 'About HACHI', 'hachi' ); ?>
					<?php hachi_arrow_icon(); ?>
				</a>
			</div>
		</div>
	</div>
</section>

<?php get_footer(); ?>
