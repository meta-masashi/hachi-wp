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

<!-- ===== LOCATION ===== -->
<section class="section company-location">
	<div class="container">
		<div class="js-fade" style="text-align:center;max-width:760px;margin:0 auto">
			<?php hachi_section_label( 'L o c a t i o n' ); ?>
			<h2 class="heading-en heading-en--sm"><?php _e( 'HEAD OFFICE', 'hachi' ); ?></h2>
			<address class="company-location__address">
				〒180-0004<br>
				東京都武蔵野市吉祥寺本町 1-13-2 5F
			</address>
			<p class="company-location__en">
				1-13-2 5F, Kichijoji-Honcho,<br>
				Musashino-shi, Tokyo 180-0004, Japan
			</p>
		</div>
	</div>
</section>

<?php get_footer(); ?>
