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
					'badge' => '根拠ある AI 判断',
					'num'   => '01',
					'title' => "「なぜそう判断したか」が\nわかるAI",
					'desc'  => '医学論文のエビデンスをもとに計算するAIなので、なぜそのリスク判定になったかをスタッフが確認・説明できます。ブラックボックスな機械学習とは異なります。',
				],
				[
					'badge' => '朝の自動通知',
					'num'   => '02',
					'title' => "毎朝7時に「今日対応が\n必要な選手」を自動通知",
					'desc'  => '前日までのデータをもとに、コンディションが下がっている選手・今日のメニューを調整すべき選手を自動でリストアップ。朝のミーティング前に準備が整います。',
				],
				[
					'badge' => 'シミュレーション',
					'num'   => '03',
					'title' => "「もし〇〇したら？」を\n試してから決断",
					'desc'  => '「今日、練習強度を下げたら来週の試合に間に合うか？」などの仮定シナリオをシステム上で試せます。リスクを数値で確認してから判断できます。',
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
					'badge' => '選手アプリ',
					'num'   => '04',
					'title' => "選手がスマホで\n毎日体調を報告",
					'desc'  => '選手はスマホアプリから痛みや疲労度を入力するだけ。スタッフにリアルタイムで届き、今日のメニュー確認もアプリ上で完結します。',
				],
				[
					'badge' => '復帰管理',
					'num'   => '05',
					'title' => "「いつ復帰できるか」を\nステップで管理",
					'desc'  => '怪我の回復段階をステップで管理し、各段階の基準をクリアしたかをシステムが自動チェック。担当スタッフが安全に復帰判断を下せます。',
				],
				[
					'badge' => 'チーム管理',
					'num'   => '06',
					'title' => "役割ごとの\n情報アクセス管理",
					'desc'  => '医師・トレーナー・S&Cコーチ・選手それぞれが必要な情報だけを見られる権限設定。チームのデータは他のクラブと完全に分離されています。',
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
			<div class="pace-pricing__grid pace-pricing__grid--4">

				<!-- Standard -->
				<div class="pace-plan">
					<h3 class="pace-plan__name">Standard</h3>
					<div class="pace-plan__price">
						<span class="pace-plan__amount">¥100,000</span>
						<span class="pace-plan__unit">/月</span>
					</div>
					<p class="pace-plan__desc"><?php _e( '傷害リスクの評価・毎日の状態確認・ケア記録をデジタル化。小規模チームに最適。', 'hachi' ); ?></p>
					<ul class="pace-plan__list">
						<?php
						$standard_items = [
							'傷害リスク評価（AIアセスメント）',
							'選手の毎日の体調チェック',
							'コンディションスコアの可視化',
							'復帰シミュレーション（2パターン）',
							'リハビリ進捗管理',
							'スタッフ5名・選手50名まで',
						];
						foreach ( $standard_items as $item ) :
						?>
							<li><?php echo esc_html( $item ); ?></li>
						<?php endforeach; ?>
					</ul>
					<a href="<?php echo esc_url( 'https://hachi-riskon.com/login' ); ?>" class="btn pace-plan__btn" target="_blank" rel="noopener noreferrer">
						<?php _e( 'トライアルを開始', 'hachi' ); ?>
						<?php hachi_arrow_icon(); ?>
					</a>
				</div>

				<!-- Pro -->
				<div class="pace-plan pace-plan--featured">
					<span class="pace-plan__badge"><?php _e( 'おすすめ', 'hachi' ); ?></span>
					<h3 class="pace-plan__name">Pro</h3>
					<div class="pace-plan__price">
						<span class="pace-plan__amount">¥300,000</span>
						<span class="pace-plan__unit">/月</span>
					</div>
					<p class="pace-plan__desc"><?php _e( 'AIが毎朝「今日対応すべき選手」を自動特定。記録・計画・報告をまるごと自動化。', 'hachi' ); ?></p>
					<ul class="pace-plan__list">
						<?php
						$pro_items = [
							'Standard 全機能',
							'AIによる診療記録の自動下書き',
							'今週のトレーニングメニューをAIが自動作成',
							'毎朝7時に優先対応選手リストを自動通知',
							'怪我リスクの増加傾向レポート',
							'スケジュール・カレンダー連携',
							'スタッフ20名・選手200名まで',
						];
						foreach ( $pro_items as $item ) :
						?>
							<li><?php echo esc_html( $item ); ?></li>
						<?php endforeach; ?>
					</ul>
					<a href="<?php echo esc_url( 'https://hachi-riskon.com/login' ); ?>" class="btn btn--white pace-plan__btn" target="_blank" rel="noopener noreferrer">
						<?php _e( 'トライアルを開始', 'hachi' ); ?>
						<?php hachi_arrow_icon(); ?>
					</a>
				</div>

				<!-- Pro + 動画解析 -->
				<div class="pace-plan">
					<h3 class="pace-plan__name">Pro + 動画解析</h3>
					<div class="pace-plan__price">
						<span class="pace-plan__amount">¥500,000</span>
						<span class="pace-plan__unit">/月</span>
					</div>
					<p class="pace-plan__desc"><?php _e( 'Proの全機能に加え、選手の動作を動画で撮影するだけで姿勢・動きの問題点をAIが自動分析。', 'hachi' ); ?></p>
					<ul class="pace-plan__list">
						<?php
						$cv_items = [
							'Pro 全機能',
							'動画をアップロードするだけで動作を自動分析',
							'姿勢・関節の問題点をスコアで表示',
							'介入前後の動作変化を数値で比較',
							'月50回まで動画解析可能',
							'スタッフ20名・選手200名まで',
						];
						foreach ( $cv_items as $item ) :
						?>
							<li><?php echo esc_html( $item ); ?></li>
						<?php endforeach; ?>
					</ul>
					<a href="<?php echo esc_url( home_url( '/contact/' ) ); ?>" class="btn pace-plan__btn">
						<?php _e( 'お問い合わせ', 'hachi' ); ?>
						<?php hachi_arrow_icon(); ?>
					</a>
				</div>

				<!-- Enterprise -->
				<div class="pace-plan">
					<h3 class="pace-plan__name">Enterprise</h3>
					<div class="pace-plan__price">
						<span class="pace-plan__amount pace-plan__amount--sm"><?php _e( '要お問合せ', 'hachi' ); ?></span>
					</div>
					<p class="pace-plan__desc"><?php _e( '複数のチーム・クラブをひとつのシステムで一括管理。規模・運用に合わせた専用構成が可能。', 'hachi' ); ?></p>
					<ul class="pace-plan__list">
						<?php
						$enterprise_items = [
							'Pro + 動画解析 全機能',
							'複数チーム・クラブの一括管理',
							'推論ロジックのカスタマイズ対応',
							'スタッフ・選手 人数無制限',
							'専任サポート・導入支援',
							'セキュリティ・契約条件のカスタマイズ',
						];
						foreach ( $enterprise_items as $item ) :
						?>
							<li><?php echo esc_html( $item ); ?></li>
						<?php endforeach; ?>
					</ul>
					<a href="<?php echo esc_url( home_url( '/contact/' ) ); ?>" class="btn pace-plan__btn">
						<?php _e( 'お問い合わせ', 'hachi' ); ?>
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
