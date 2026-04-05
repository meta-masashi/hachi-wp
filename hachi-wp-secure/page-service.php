<?php
/**
 * HACHI Theme — Service page (Light Monochrome v2)
 * Auto-applied to page with slug "service"
 */
get_header();
?>

<!-- ===== PAGE HERO ===== -->
<div class="page-hero">
	<div class="container">
		<div class="js-fade"><?php hachi_section_label( 'S e r v i c e' ); ?></div>
		<h1 class="heading-en js-fade js-fade--delay-1">SERVICE</h1>
		<p class="heading-jp js-fade js-fade--delay-2"><?php _e( 'リアルとデジタル、2つの現場で挑む。', 'hachi' ); ?></p>
	</div>
</div>

<!-- ===== INTRO ===== -->
<section class="section service-intro">
	<div class="container">
		<div class="js-fade" style="max-width:820px">
			<p class="service-intro__lead">
				<?php _e( '現場で積み上げてきた経験と、最新のテクノロジー。<br>HACHIはふたつの軸から、健康の現場に変化をもたらします。', 'hachi' ); ?>
			</p>
			<p class="body-copy" style="margin-top:32px;max-width:640px">
				<?php _e( '「オフィスワーカー向けOn-site Service」と「スポーツ医療SaaS」。対象も届け方も違いますが、データと科学に基づいて人に寄り添うという姿勢は、どちらも変わりません。', 'hachi' ); ?>
			</p>
		</div>

		<!-- Service index -->
		<div class="service-index js-fade js-fade--delay-1">
			<a href="#reboot" class="service-index__item">
				<div class="service-index__num">01</div>
				<div class="service-index__body">
					<p class="service-index__tag">ON-SITE SERVICE</p>
					<h3 class="service-index__title">REBOOT-WORK</h3>
					<p class="service-index__desc"><?php _e( 'オフィスワーカー向け / 提供中', 'hachi' ); ?></p>
				</div>
				<span class="service-index__arrow" aria-hidden="true">↓</span>
			</a>
			<a href="#pace" class="service-index__item">
				<div class="service-index__num">02</div>
				<div class="service-index__body">
					<p class="service-index__tag">SPORTS AI-SaaS</p>
					<h3 class="service-index__title">PACE</h3>
					<p class="service-index__desc service-index__desc--accent"><?php _e( 'スポーツ医療チーム向け / 準備中', 'hachi' ); ?></p>
				</div>
				<span class="service-index__arrow" aria-hidden="true">↓</span>
			</a>
		</div>
	</div>
</section>

<!-- ===== 01: REBOOT-WORK ===== -->
<section class="section--light" id="reboot">
	<div class="container">

		<div class="service-detail-header js-fade">
			<span class="service-detail-header__num">01</span>
			<div>
				<?php hachi_section_label( 'O n - s i t e   S e r v i c e' ); ?>
				<h2 class="heading-en heading-en--sm" style="margin-top:8px">REBOOT-WORK</h2>
				<p class="heading-jp" style="margin-top:12px"><?php _e( 'オフィスに、専門チームを。', 'hachi' ); ?></p>
			</div>
		</div>

		<div class="grid-2 js-fade js-fade--delay-1" style="margin-top:72px">

			<div class="service-photo">
				<span class="service-photo__label">PHOTO — Reboot Work (4:5, on-site session)</span>
			</div>

			<div>
				<p class="body-copy">
					<?php _e( 'オフィス内で生じる健康課題を、医学的な評価をもとに、専門チームがご希望の場所で解決する On-site Service。腰痛・肩こり・メンタルヘルスなど、働く人の「なんとなく不調」を、継続的にケアします。', 'hachi' ); ?>
				</p>

				<ul class="service-feature-list" style="margin-top:40px">
					<?php
					$reboot_features = [
						'医学的評価に基づいたオフィスワーカーの健康リスクアセスメント',
						'企業ごとのカスタマイズプログラム設計と定期的な現地訪問',
						'腰痛・肩こり・メンタルヘルスなど多岐にわたる課題への専門対応',
						'継続的なデータ収集とフィードバックレポートの提供',
					];
					foreach ( $reboot_features as $feat ) :
					?>
						<li>
							<span class="service-feature-list__dot" aria-hidden="true"></span>
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

		<!-- FLOW -->
		<div class="js-fade js-fade--delay-2" style="margin-top:120px">
			<div style="max-width:680px;margin-bottom:56px">
				<?php hachi_section_label( 'S e r v i c e   F l o w' ); ?>
				<h3 class="heading-en heading-en--xs" style="margin-top:12px"><?php _e( 'ご利用の流れ', 'hachi' ); ?></h3>
			</div>
			<div class="service-flow">
				<?php
				$flow_steps = [
					[ '01', 'ヒアリング',       '課題・目標をお聞きします。' ],
					[ '02', 'アセスメント',     '医学的観点から現場調査と評価を実施。' ],
					[ '03', 'プログラム設計',   '貴社専用のケアプランを策定します。' ],
					[ '04', '継続支援',         '定期訪問とデータで効果を可視化。' ],
				];
				foreach ( $flow_steps as $step ) :
				?>
					<div class="service-flow__step">
						<div class="service-flow__num"><?php echo esc_html( $step[0] ); ?></div>
						<div class="service-flow__title"><?php echo esc_html( $step[1] ); ?></div>
						<p class="service-flow__desc"><?php echo esc_html( $step[2] ); ?></p>
					</div>
				<?php endforeach; ?>
			</div>
		</div>

	</div>
</section>

<!-- ===== 02: PACE (COMING SOON — strategic dark block) ===== -->
<section class="pace-section" id="pace">
	<div class="container">

		<div class="pace-coming js-fade">
			<span class="pace-coming__tag">COMING SOON</span>
			<?php hachi_section_label( '0 2   S p o r t s   A I - S a a S', 'label--white' ); ?>
			<h2 class="pace-coming__title">PACE<span class="pace-coming__version">by HACHI</span></h2>
			<p class="pace-coming__subtitle"><?php _e( 'Progressive Assessment &amp; Conditioning Engine', 'hachi' ); ?></p>
			<p class="pace-coming__lead">
				<?php _e( '因果推論AIとデジタルツインを活用し、アスレティックトレーナー・理学療法士の意思決定を支援する、スポーツ医療プラットフォーム。論文エビデンスに基づく推論で、チームの健康を守ります。', 'hachi' ); ?>
			</p>
		</div>

		<!-- PACE Features -->
		<div class="pace-features js-fade js-fade--delay-1">
			<?php
			$pace_core = [
				[ '01', "「なぜそう判断したか」が\nわかるAI",              '医学論文のエビデンスをもとに計算するAIなので、なぜそのリスク判定になったかをスタッフが確認・説明できます。' ],
				[ '02', "毎朝、今日対応が\n必要な選手を自動通知",          '前日までのデータをもとに、コンディションが下がっている選手・今日のメニューを調整すべき選手を自動でリストアップ。' ],
				[ '03', "「もし〇〇したら？」を\n試してから決断",          '練習強度や復帰スケジュールの仮定シナリオをシステム上で試し、リスクを数値で確認してから判断できます。' ],
				[ '04', "選手はスマホで\n毎日体調を報告",                  '選手はアプリから痛みや疲労度を入力するだけ。スタッフにリアルタイムで届き、今日のメニュー確認もアプリで完結。' ],
				[ '05', "「いつ復帰できるか」を\nステップで管理",          '怪我の回復段階をステップで管理し、基準をクリアしたかを自動チェック。担当スタッフが安全に復帰判断を下せます。' ],
				[ '06', "役割ごとの\n情報アクセス管理",                    '医師・トレーナー・S&Cコーチ・選手それぞれが必要な情報だけを見られる権限設定。データは他クラブと完全分離。' ],
			];
			foreach ( $pace_core as $feat ) :
			?>
				<div class="pace-feature">
					<div class="pace-feature__num"><?php echo esc_html( $feat[0] ); ?></div>
					<h3 class="pace-feature__title"><?php echo nl2br( esc_html( $feat[1] ) ); ?></h3>
					<p class="pace-feature__desc"><?php echo esc_html( $feat[2] ); ?></p>
				</div>
			<?php endforeach; ?>
		</div>

		<!-- Target Users -->
		<div class="pace-users js-fade js-fade--delay-2">
			<p class="pace-users__label"><?php _e( 'TARGET USERS', 'hachi' ); ?></p>
			<div class="pace-users__grid">
				<?php
				$roles = [
					[ 'Master',  '医師・医療責任者',                      '全機能アクセス・Hard Lock制御' ],
					[ 'AT / PT', 'アスレティックトレーナー / 理学療法士', 'アセスメント・リハビリ管理' ],
					[ 'S & C',   'S&Cコーチ',                             'チームメニュー生成・配信' ],
					[ 'Athlete', '選手',                                  'モバイルアプリでチェックイン' ],
				];
				foreach ( $roles as $role ) :
				?>
					<div class="pace-user">
						<div class="pace-user__tag"><?php echo esc_html( $role[0] ); ?></div>
						<div class="pace-user__name"><?php echo esc_html( $role[1] ); ?></div>
						<div class="pace-user__desc"><?php echo esc_html( $role[2] ); ?></div>
					</div>
				<?php endforeach; ?>
			</div>
		</div>

		<!-- Waitlist CTA -->
		<div class="pace-waitlist js-fade js-fade--delay-2">
			<p class="pace-waitlist__label"><?php _e( 'LAUNCH IS COMING', 'hachi' ); ?></p>
			<h3 class="pace-waitlist__title"><?php _e( '先行案内リストを受付中', 'hachi' ); ?></h3>
			<p class="pace-waitlist__desc">
				<?php _e( 'PACE は現在、プロクラブ・競技団体との協業で実装を進めています。導入にご興味のあるチームの方は、先行案内リストへご登録ください。正式リリース時に優先でご案内いたします。', 'hachi' ); ?>
			</p>
			<div class="pace-waitlist__buttons">
				<a href="<?php echo esc_url( home_url( '/contact/' ) ); ?>" class="btn btn--white">
					<?php _e( '先行案内リストに登録', 'hachi' ); ?>
					<?php hachi_arrow_icon(); ?>
				</a>
			</div>
		</div>

	</div>
</section>

<!-- ===== FINAL CTA ===== -->
<section class="section about-cta">
	<div class="container">
		<div class="js-fade" style="text-align:center;max-width:760px;margin:0 auto">
			<h2 class="heading-en heading-en--sm"><?php _e( 'GET IN TOUCH', 'hachi' ); ?></h2>
			<p class="heading-jp" style="margin-top:20px"><?php _e( 'まずは、お気軽にご相談ください。', 'hachi' ); ?></p>
			<p class="body-copy" style="margin:28px auto 0">
				<?php _e( 'REBOOT-WORK の導入、PACE の先行案内、取材など、お問い合わせはこちらから。', 'hachi' ); ?>
			</p>
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
