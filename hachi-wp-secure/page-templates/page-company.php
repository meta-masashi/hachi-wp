<?php
/**
 * Template Name: Company Page
 * HACHI Theme — Company page
 */
get_header();
?>

<div class="page-hero">
	<div class="page-hero__ghost ghost-text" aria-hidden="true">COMPANY</div>
	<div class="container">
		<div class="js-fade"><?php hachi_section_label( 'C o m p a n y' ); ?></div>
		<h1 class="heading-en js-fade js-fade--delay-1" style="font-size:clamp(52px,9vw,112px)">COMPANY</h1>
		<p class="heading-jp js-fade js-fade--delay-2"><?php _e( '会社概要', 'hachi' ); ?></p>
	</div>
</div>

<!-- PROFILE -->
<section class="section">
	<div class="container">
		<div class="grid-2">

			<div class="js-fade">
				<?php hachi_section_label( 'P r o f i l e' ); ?>
				<h2 class="heading-en heading-en--xs" style="margin-bottom:44px">PROFILE</h2>

				<table class="company-table">
					<tbody>
						<?php
						$rows = [
							[ '会社名',         '株式会社HACHI' ],
							[ '代表取締役社長', '佐々木 譲崇' ],
							[ '設立',           '2022年3月25日' ],
							[ '資本金',         '金100万円' ],
							[ '所在地',         "〒180-0004\n東京都武蔵野市吉祥寺本町\n1-13-2 5F" ],
							[ '事業内容',       "On-site Service「REBOOT-WORK」\n次世代スポーツ医療SaaS「PACE v3.0」" ],
						];
						foreach ( $rows as $row ) :
						?>
							<tr>
								<th scope="row"><?php echo esc_html( $row[0] ); ?></th>
								<td><?php echo nl2br( esc_html( $row[1] ) ); ?></td>
							</tr>
						<?php endforeach; ?>
					</tbody>
				</table>
			</div>

			<div class="js-fade js-fade--delay-1">
				<div style="background:var(--bg2);padding:60px;min-height:420px;display:flex;flex-direction:column;justify-content:flex-end;position:relative;overflow:hidden">
					<div style="position:absolute;top:28px;left:28px;font-family:var(--mono);font-size:80px;font-weight:700;color:rgba(0,0,0,.05);line-height:1" aria-hidden="true">HACHI</div>
					<address style="font-style:normal;font-family:var(--mono);font-size:11px;letter-spacing:.2em;color:var(--gray);line-height:2.5">
						〒180-0004<br>
						東京都武蔵野市吉祥寺本町<br>
						1-13-2 5F<br><br>
						Musashino, Tokyo
					</address>
				</div>
			</div>

		</div>
	</div>
</section>

<!-- SECURITY -->
<section class="section--dark" id="security">
	<div class="container">

		<div class="js-fade" style="max-width:700px;margin-bottom:64px">
			<?php hachi_section_label( 'S e c u r i t y', 'label--white' ); ?>
			<h2 class="heading-en heading-en--white heading-en--sm">SECURITY</h2>
			<p class="heading-jp heading-jp--white">
				<?php _e( '医療・健康情報を守る妥協なきデータ保護', 'hachi' ); ?>
			</p>
			<p class="body-copy body-copy--white" style="margin-top:24px">
				<?php _e( 'HIPAA準拠のシステム設計、TLS 1.3暗号化、テナントデータ完全分離（RLS）、および顔データの自動マスキングなど、医療機関レベルのセキュリティ水準を提供します。', 'hachi' ); ?>
			</p>
		</div>

		<div class="security-grid js-fade js-fade--delay-1">
			<?php
			$security_items = [
				[ '🔒', 'HIPAA準拠設計',         '医療情報の取り扱いに関する国際基準HIPAAに準拠したシステムアーキテクチャを採用しています。' ],
				[ '🔐', 'TLS 1.3暗号化',          '最新のTLS 1.3プロトコルによる通信暗号化で、データの盗聴・改ざんを防止します。' ],
				[ '🛡️', 'テナントデータ完全分離', 'Row Level Security（RLS）により、テナント間のデータを完全に分離。情報漏洩リスクを低減します。' ],
				[ '👤', '顔データ自動マスキング', '動画解析時に顔データを自動的にマスキング処理し、プライバシーを確実に保護します。' ],
			];
			foreach ( $security_items as $item ) :
			?>
				<div class="security-card">
					<div class="security-card__icon" aria-hidden="true"><?php echo esc_html( $item[0] ); ?></div>
					<h3 class="security-card__title"><?php echo esc_html( $item[1] ); ?></h3>
					<p class="security-card__desc"><?php echo esc_html( $item[2] ); ?></p>
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

<?php get_footer(); ?>
