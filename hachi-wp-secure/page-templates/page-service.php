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
					<?php _e( '因果推論AIとデジタルツインを活用し、アスレティックトレーナー・理学療法士の意思決定を支援するスポーツ医療プラットフォーム。ブラックボックスではない、論文エビデンスに基づく推論でチームの健康を守ります。', 'hachi' ); ?>
				</p>
			</div>
		</div>

		<!-- PACE Stats -->
		<div class="pace-stats js-fade js-fade--delay-1">
			<?php
			$pace_stats = [
				[ '¥29,800', '/月〜', 'Starterプラン' ],
				[ '6ノード', 'パイプライン', '因果推論エンジン' ],
				[ '< 200ms', '推論速度', 'リアルタイム処理' ],
				[ '14日間', '無料トライアル', '全機能お試し可能' ],
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

		<!-- PACE Core Technologies -->
		<div class="feature-cards feature-cards--3 js-fade js-fade--delay-1" style="margin-top:80px">
			<?php
			$pace_core = [
				[
					'badge' => '因果推論 AI',
					'num'   => '01',
					'title' => "エビデンスベースの\n意思決定支援",
					'desc'  => 'ブラックボックスではない、Oxford CEBM Level 2以上の論文エビデンスに基づくリスク推論。各予測の根拠を論文レベルで追跡可能。',
				],
				[
					'badge' => '7 AM Monopoly',
					'num'   => '02',
					'title' => "毎朝、介入すべき選手を\n自動特定",
					'desc'  => 'コンディションスコア・フィットネス・疲労を統合解析し、朝のミーティングまでに介入優先順位と修正済みメニューを自動生成します。',
				],
				[
					'badge' => 'Digital Twin',
					'num'   => '03',
					'title' => "「もし〇〇したら？」を\n事前シミュレーション",
					'desc'  => 'デジタルツインで介入の効果を事前に可視化。トレーニング負荷変更・リハビリ計画修正のリスクを数値で評価し、最善の判断を導きます。',
				],
			];
			foreach ( $pace_core as $feat ) :
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

		<!-- PACE Features (secondary) -->
		<div class="feature-cards feature-cards--3 js-fade js-fade--delay-2" style="margin-top:32px">
			<?php
			$pace_features = [
				[
					'badge' => 'Mobile PWA',
					'num'   => '04',
					'title' => "選手向け\nモバイルアプリ",
					'desc'  => '日次コンディションチェックイン、マイメニュー確認、動画アップロードをiOS/Androidで。オフライン対応PWA。',
				],
				[
					'badge' => 'RTP Gate',
					'num'   => '05',
					'title' => "段階的な\n競技復帰管理",
					'desc'  => 'Phase 1-4の復帰プロトコルを自動判定。医師・ATによるHard Lock/Soft Lock制御で安全な競技復帰を実現します。',
				],
				[
					'badge' => 'Multi-tenant',
					'num'   => '06',
					'title' => "チーム管理\nマルチテナント基盤",
					'desc'  => 'クラブ単位でデータを完全分離。Master/AT/PT/S&Cのロール別権限管理とSOAPノートをワンプラットフォームで。',
				],
			];
			foreach ( $pace_features as $feat ) :
			?>
				<div class="feature-card feature-card--muted">
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
					[ 'AT / PT', 'アスレティックトレーナー / 理学療法士', 'アセスメント・リハビリ管理' ],
					[ 'S&C', 'S&Cコーチ', 'チームメニュー生成・配信' ],
					[ 'Athlete', '選手', 'モバイルアプリでチェックイン' ],
				];
				foreach ( $roles as $role ) :
				?>
					<div class="pace-role">
						<div class="pace-role__tag"><?php echo esc_html( $role[0] ); ?></div>
						<div class="pace-role__name"><?php echo esc_html( $role[1] ); ?></div>
						<div class="pace-role__desc"><?php echo esc_html( $role[2] ); ?></div>
					</div>
				<?php endforeach; ?>
			</div>
		</div>

		<!-- PACE Pricing -->
		<div class="pace-pricing js-fade js-fade--delay-2" style="margin-top:80px">
			<p class="pace-roles__label" style="font-family:var(--mono);font-size:11px;letter-spacing:.22em;color:rgba(255,255,255,.4);margin-bottom:40px;text-align:center">
				<?php _e( 'PLANS &amp; PRICING', 'hachi' ); ?>
			</p>
			<div class="pace-pricing__grid">

				<div class="pace-plan">
					<h3 class="pace-plan__name">Starter</h3>
					<div class="pace-plan__price">
						<span class="pace-plan__amount">¥29,800</span>
						<span class="pace-plan__unit">/月</span>
					</div>
					<p class="pace-plan__desc"><?php _e( '小規模チーム向け。基本機能をすべて利用可能。', 'hachi' ); ?></p>
					<ul class="pace-plan__list">
						<?php
						$starter_items = [
							'1チーム',
							'コンディショニングスコア',
							'7 AM Monopoly（朝のアジェンダ）',
							'メール通知',
							'基本レポート',
						];
						foreach ( $starter_items as $item ) :
						?>
							<li><?php echo esc_html( $item ); ?></li>
						<?php endforeach; ?>
					</ul>
					<a href="<?php echo esc_url( 'https://hachi-riskon.com/login' ); ?>" class="btn pace-plan__btn" target="_blank" rel="noopener noreferrer">
						<?php _e( '無料トライアルを開始', 'hachi' ); ?>
						<?php hachi_arrow_icon(); ?>
					</a>
				</div>

				<div class="pace-plan pace-plan--featured">
					<span class="pace-plan__badge"><?php _e( 'おすすめ', 'hachi' ); ?></span>
					<h3 class="pace-plan__name">Pro</h3>
					<div class="pace-plan__price">
						<span class="pace-plan__amount">¥79,800</span>
						<span class="pace-plan__unit">/月</span>
					</div>
					<p class="pace-plan__desc"><?php _e( 'プロフェッショナルチーム向け。全機能をフル活用。', 'hachi' ); ?></p>
					<ul class="pace-plan__list">
						<?php
						$pro_items = [
							'無制限チーム',
							'因果推論AIエンジン（フル）',
							'デジタルツインシミュレーション',
							'デバイス連携（S2S）',
							'Slack / Web Push通知',
							'高度なレポート・分析',
							'優先サポート',
						];
						foreach ( $pro_items as $item ) :
						?>
							<li><?php echo esc_html( $item ); ?></li>
						<?php endforeach; ?>
					</ul>
					<a href="<?php echo esc_url( 'https://hachi-riskon.com/login' ); ?>" class="btn btn--white pace-plan__btn" target="_blank" rel="noopener noreferrer">
						<?php _e( '無料トライアルを開始', 'hachi' ); ?>
						<?php hachi_arrow_icon(); ?>
					</a>
				</div>

			</div>
		</div>

		<!-- CTA -->
		<div class="js-fade js-fade--delay-2" style="text-align:center;margin-top:80px;padding-top:60px;border-top:1px solid rgba(255,255,255,.1)">
			<p style="font-family:var(--serif);font-size:clamp(18px,2.5vw,26px);color:rgba(255,255,255,.85);margin-bottom:36px">
				<?php _e( 'スポーツ医療の新しいスタンダードを、あなたのチームに。', 'hachi' ); ?>
			</p>
			<div style="display:flex;gap:20px;justify-content:center;flex-wrap:wrap">
				<a href="<?php echo esc_url( 'https://hachi-riskon.com/login' ); ?>" class="btn btn--white" target="_blank" rel="noopener noreferrer">
					<?php _e( '無料トライアルを開始', 'hachi' ); ?>
					<?php hachi_arrow_icon(); ?>
				</a>
				<a href="<?php echo esc_url( home_url( '/contact/' ) ); ?>" class="btn" style="border-color:rgba(255,255,255,.3);color:rgba(255,255,255,.7)">
					<?php _e( '資料請求・デモ申込', 'hachi' ); ?>
					<?php hachi_arrow_icon(); ?>
				</a>
			</div>
		</div>

	</div>
</section>

<?php get_footer(); ?>
