<?php
/**
 * Template Name: Service Page
 * HACHI Theme — Service page
 */
get_header();
?>

<div class="page-hero">
	<div class="page-hero__ghost ghost-text" aria-hidden="true">SERVICE</div>
	<div class="container">
		<div class="js-fade"><?php hachi_section_label( 'S e r v i c e' ); ?></div>
		<h1 class="heading-en js-fade js-fade--delay-1" style="font-size:clamp(52px,9vw,112px)">SERVICES</h1>
		<p class="heading-jp js-fade js-fade--delay-2"><?php _e( 'リアルとデジタル、2つの現場で挑む。', 'hachi' ); ?></p>
	</div>
</div>

<!-- ===== 01: REBOOT-WORK ===== -->
<section class="section" id="reboot">
	<div class="container">
		<div class="grid-2 grid-2--wide">

			<div class="about-image js-fade js-fade--left">
				<div class="about-image__inner" style="aspect-ratio:1/1.1">
					<?php
					$reboot_img = get_theme_mod( 'hachi_reboot_image' );
					if ( $reboot_img ) {
						echo wp_get_attachment_image( $reboot_img, 'hachi-portrait', false, [
							'alt' => 'REBOOT-WORK', 'loading' => 'lazy',
						] );
					} else {
						echo '<div class="about-image__placeholder" style="font-size:72px;writing-mode:vertical-rl;letter-spacing:.1em" aria-hidden="true">REBOOT</div>';
					}
					?>
				</div>
				<div class="about-image__tag">On-site Service</div>
			</div>

			<div class="js-fade js-fade--delay-1">
				<?php hachi_section_label( '0 1   O n - s i t e' ); ?>
				<h2 class="heading-en heading-en--sm">REBOOT<br>WORK</h2>
				<p class="body-copy" style="margin-top:24px">
					<?php _e( 'オフィス内で生じる健康課題を医学的評価をもとに、専門チームがご希望の場所で課題解決のためのサービス提供を行います。', 'hachi' ); ?>
				</p>

				<ul style="margin-top:40px;list-style:none">
					<?php
					$reboot_features = [
						'医学的評価に基づいたオフィスワーカーの健康リスクアセスメント',
						'企業ごとのカスタマイズプログラム設計と定期的な現地訪問',
						'腰痛・肩こり・メンタルヘルスなど多岐にわたる課題への専門対応',
						'継続的なデータ収集とフィードバックレポートの提供',
					];
					foreach ( $reboot_features as $feat ) :
					?>
						<li style="display:flex;gap:14px;align-items:flex-start;padding:14px 0;border-bottom:1px solid var(--gray2);font-size:14px;line-height:1.85">
							<span style="width:6px;height:6px;border-radius:50%;background:var(--teal);flex-shrink:0;margin-top:9px"></span>
							<?php echo esc_html( $feat ); ?>
						</li>
					<?php endforeach; ?>
				</ul>

				<div style="margin-top:48px">
					<a href="<?php echo esc_url( home_url( '/contact/' ) ); ?>" class="btn btn--teal">
						<?php _e( '資料請求・お問い合わせ', 'hachi' ); ?>
						<?php hachi_arrow_icon(); ?>
					</a>
				</div>
			</div>

		</div>
	</div>
</section>

<!-- ===== FLOW ===== -->
<section class="section--muted">
	<div class="container">
		<div class="js-fade">
			<?php hachi_section_label( 'S e r v i c e   F l o w' ); ?>
			<h2 class="heading-en heading-en--xs"><?php _e( 'ご利用の流れ', 'hachi' ); ?></h2>
		</div>
		<div class="flow-grid js-fade js-fade--delay-1">
			<?php
			$flow_steps = [
				[ '01', 'ヒアリング',        '課題・目標をお聞きします' ],
				[ '02', 'アセスメント',      '医学的観点から現場調査と評価を実施' ],
				[ '03', 'プログラム設計',    '貴社専用のケアプランを策定します' ],
				[ '04', '継続支援',          '定期訪問とデータで効果を可視化' ],
			];
			foreach ( $flow_steps as $step ) :
			?>
				<div class="flow-step">
					<div class="flow-step__num"><?php echo esc_html( $step[0] ); ?></div>
					<div class="flow-step__title"><?php echo esc_html( $step[1] ); ?></div>
					<p class="flow-step__desc"><?php echo esc_html( $step[2] ); ?></p>
				</div>
			<?php endforeach; ?>
		</div>
	</div>
</section>

<!-- ===== 02: PACE v3.0 ===== -->
<section class="section--dark pace-section" id="pace">
	<div class="container">

		<!-- PACE Header -->
		<div class="pace-header js-fade">
			<?php hachi_section_label( '0 2   S p o r t s   M e d i c a l   S a a S', 'label--white' ); ?>
			<div class="pace-header__inner">
				<div>
					<h2 class="heading-en heading-en--white pace-title">PACE<span class="pace-version">v3.0</span></h2>
					<p class="heading-jp heading-jp--white" style="font-size:clamp(15px,2vw,20px);margin-top:8px">
						<?php _e( 'Progressive Assessment &amp; Conditioning Engine', 'hachi' ); ?>
					</p>
				</div>
				<p class="body-copy body-copy--white pace-lead">
					<?php _e( '動的ベイズ推論（131ノード）×Computer Vision×Gemini AIが、スポーツ医療チームの傷害評価・リハビリ計画・コンディショニング管理を一元化。チームの「健康」をデータで可視化・最適化するプラットフォームです。', 'hachi' ); ?>
				</p>
			</div>
		</div>

		<!-- PACE Stats -->
		<div class="pace-stats js-fade js-fade--delay-1">
			<?php
			$pace_stats = [
				[ '131', 'ノード', '動的ベイズネットワーク' ],
				[ '75%+', '信頼度閾値', '診断確定の精度基準' ],
				[ '< 200ms', 'ベイズ更新', 'リアルタイム推論速度' ],
				[ '3min', 'CV解析', '動画アップ〜結果出力' ],
			];
			foreach ( $pace_stats as $stat ) :
			?>
				<div class="pace-stat">
					<div class="pace-stat__num"><?php echo esc_html( $stat[0] ); ?></div>
					<div class="pace-stat__label"><?php echo esc_html( $stat[1] ); ?></div>
					<div class="pace-stat__desc"><?php echo esc_html( $stat[2] ); ?></div>
				</div>
			<?php endforeach; ?>
		</div>

		<!-- PACE Features -->
		<div class="feature-cards feature-cards--6 js-fade js-fade--delay-1" style="margin-top:80px">
			<?php
			$pace_features = [
				[
					'badge' => 'Adaptive CAT',
					'num'   => '01',
					'title' => "動的ベイズ推論による\n傷害アセスメント",
					'desc'  => '131ノードのCAT（コンピュータ適応型テスト）が、回答ごとに確率を更新。Red Flagゲートから診断確定まで最短8問で完結します。',
				],
				[
					'badge' => 'Computer Vision',
					'num'   => '02',
					'title' => "動画解析による\n生体力学評価",
					'desc'  => 'SMPL 3Dメッシュ解析で選手の姿勢・キネマティクスを定量化。ビフォーアフター比較で介入効果を可視化します。',
				],
				[
					'badge' => 'Gemini AI',
					'num'   => '03',
					'title' => "AI自律生成\nリハビリ&チームメニュー",
					'desc'  => 'ベイズ推論出力＋CV動作データをGemini APIに注入し、選手個別のリハビリ計画とチームトレーニングメニューを自動生成します。',
				],
				[
					'badge' => 'Mobile App',
					'num'   => '04',
					'title' => "選手向け\nモバイルアプリ",
					'desc'  => 'NRS・HRV・ACWRの日次チェックイン、マイメニュー確認、動画アップロードをiOS/Androidで。HealthKit/Health Connect連携対応。',
				],
				[
					'badge' => 'RTP Management',
					'num'   => '05',
					'title' => "復帰管理\nRTPゲート制御",
					'desc'  => '傷害別のフェーズ管理（Phase 1-4）とゲート基準を自動判定。医師・ATによるHard Lock/Soft Lock制御で安全な競技復帰を実現します。',
				],
				[
					'badge' => 'Multi-tenant',
					'num'   => '06',
					'title' => "マルチテナント\nチーム管理基盤",
					'desc'  => 'クラブ単位でデータを完全分離。Master/AT/PT/S&Cのロール別権限管理、リアルタイムダッシュボード、SOAPノートをワンプラットフォームで提供。',
				],
			];
			foreach ( $pace_features as $feat ) :
			?>
				<div class="feature-card">
					<span class="feature-card__badge"><?php echo esc_html( $feat['badge'] ); ?></span>
					<div class="feature-card__num" aria-hidden="true"><?php echo esc_html( $feat['num'] ); ?></div>
					<h3 class="feature-card__title">
						<?php echo nl2br( esc_html( $feat['title'] ) ); ?>
					</h3>
					<p class="feature-card__desc"><?php echo esc_html( $feat['desc'] ); ?></p>
				</div>
			<?php endforeach; ?>
		</div>

		<!-- PACE Target Roles -->
		<div class="pace-roles js-fade js-fade--delay-2" style="margin-top:80px">
			<p class="pace-roles__label" style="font-family:var(--mono);font-size:11px;letter-spacing:.22em;color:rgba(255,255,255,.4);margin-bottom:28px">
				<?php _e( 'TARGET USERS', 'hachi' ); ?>
			</p>
			<div class="pace-roles__grid">
				<?php
				$roles = [
					[ 'Master', '医師・医療責任者', '全機能アクセス・Hard Lock制御' ],
					[ 'AT / PT', 'アスレティックトレーナー\n理学療法士', 'アセスメント・リハビリ管理' ],
					[ 'S&C', 'S&Cコーチ', 'チームメニュー生成・配信' ],
					[ 'Athlete', '選手', 'モバイルアプリでチェックイン' ],
				];
				foreach ( $roles as $role ) :
				?>
					<div class="pace-role">
						<div class="pace-role__tag"><?php echo esc_html( $role[0] ); ?></div>
						<div class="pace-role__name"><?php echo nl2br( esc_html( $role[1] ) ); ?></div>
						<div class="pace-role__desc"><?php echo esc_html( $role[2] ); ?></div>
					</div>
				<?php endforeach; ?>
			</div>
		</div>

		<!-- CTA -->
		<div class="js-fade js-fade--delay-2" style="text-align:center;margin-top:80px;padding-top:60px;border-top:1px solid rgba(255,255,255,.1)">
			<p style="font-family:var(--serif);font-size:clamp(18px,2.5vw,26px);color:rgba(255,255,255,.85);margin-bottom:36px">
				<?php _e( 'スポーツ医療の現場を、データで革新する。', 'hachi' ); ?>
			</p>
			<div style="display:flex;gap:20px;justify-content:center;flex-wrap:wrap">
				<a href="<?php echo esc_url( home_url( '/contact/' ) ); ?>" class="btn btn--white">
					<?php _e( 'デモを申し込む', 'hachi' ); ?>
					<?php hachi_arrow_icon(); ?>
				</a>
				<a href="<?php echo esc_url( home_url( '/contact/' ) ); ?>" class="btn" style="border-color:rgba(255,255,255,.3);color:rgba(255,255,255,.7)">
					<?php _e( '資料請求', 'hachi' ); ?>
					<?php hachi_arrow_icon(); ?>
				</a>
			</div>
		</div>

	</div>
</section>

<?php get_footer(); ?>
