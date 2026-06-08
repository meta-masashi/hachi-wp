<?php
/**
 * HACHI Theme — Service page (Light Monochrome v2)
 * Auto-applied to page with slug "service"
 * Updated: 2026-06-08 corp-refresh — コンディション・インサイト一本化 / PACE section removed
 */
get_header();
?>

<!-- ===== PAGE HERO ===== -->
<div class="page-hero">
	<div class="container">
		<div class="js-fade"><?php hachi_section_label( 'S e r v i c e' ); ?></div>
		<h1 class="heading-en js-fade js-fade--delay-1">SERVICE</h1>
		<p class="heading-jp js-fade js-fade--delay-2"><?php _e( '状態を見える形にする。', 'hachi' ); ?></p>
	</div>
</div>

<!-- ===== INTRO ===== -->
<section class="section service-intro">
	<div class="container">
		<div class="js-fade" style="max-width:820px">
			<p class="service-intro__lead">
				<?php _e( '人は突然、不調になるのではありません。<br>疲れや集中の変化は、じわじわと積み重なります。', 'hachi' ); ?>
			</p>
			<p class="body-copy" style="margin-top:32px;max-width:640px">
				<?php _e( '20〜100名規模の会社では、専任の産業保健スタッフを置けないケースがほとんどです。本人も言い出せない、管理職も気づけない——その構造が、気づきを遅らせています。コンディション・インサイトは、そのサインを組織として早めにつかむしくみです。', 'hachi' ); ?>
			</p>
		</div>

		<!-- Service index -->
		<div class="service-index js-fade js-fade--delay-1">
			<a href="#reboot" class="service-index__item">
				<div class="service-index__num">01</div>
				<div class="service-index__body">
					<p class="service-index__tag">ON-SITE SERVICE</p>
					<h3 class="service-index__title">コンディション・インサイト</h3>
					<p class="service-index__desc"><?php _e( '20〜100名中小企業向け / 提供中', 'hachi' ); ?></p>
				</div>
				<span class="service-index__arrow" aria-hidden="true">↓</span>
			</a>
		</div>
	</div>
</section>

<!-- ===== 01: コンディション・インサイト ===== -->
<section class="section--light" id="reboot">
	<div class="container">

		<div class="service-detail-header js-fade">
			<span class="service-detail-header__num">01</span>
			<div>
				<?php hachi_section_label( 'O n - s i t e   S e r v i c e' ); ?>
				<h2 class="heading-en heading-en--sm" style="margin-top:8px">コンディション・インサイト</h2>
				<p class="heading-jp" style="margin-top:12px"><?php _e( '社員の状態変化のサインを、組織として早めにつかむ。', 'hachi' ); ?></p>
			</div>
		</div>

		<div class="grid-2 js-fade js-fade--delay-1" style="margin-top:72px">

			<div class="service-photo">
				<span class="service-photo__label">PHOTO — On-site conditioning session</span>
			</div>

			<div>
				<p class="body-copy">
					<?php _e( '社員が10分以内で答える短いチェックをもとに、現在の身体・睡眠・集中・疲労の傾向を整理します。組織全体の状態傾向を経営者・管理職が把握しやすい形でまとめ、希望する企業には現場コンディショニング指導を提供します。', 'hachi' ); ?>
				</p>

				<!-- Section 2: 課題 -->
				<div style="margin-top:48px">
					<h3 style="font-size:17px;font-weight:600;margin-bottom:16px"><?php _e( '見えないと、気づきにくい。', 'hachi' ); ?></h3>
					<p class="body-copy">
						<?php _e( '「最近ちょっとしんどくて…」と言えずに出社を続けている社員がいます。管理職は「元気そうに見える」と思ったまま、3か月後に休職の連絡を受けた、というケースがあります。', 'hachi' ); ?>
					</p>
					<ul class="service-feature-list" style="margin-top:24px">
						<?php
						$reboot_scenes = [
							'「最近元気がないな」で終わっていた',
							'産業医の面談日まで待ったら、すでに限界だった',
							'欠勤が続いてから、初めて状態を知った',
							'健康経営施策を導入したが、誰も使っていない',
						];
						foreach ( $reboot_scenes as $scene ) :
						?>
							<li>
								<span class="service-feature-list__dot" aria-hidden="true"></span>
								<?php echo esc_html( $scene ); ?>
							</li>
						<?php endforeach; ?>
					</ul>
				</div>

				<div style="margin-top:48px">
					<a href="<?php echo esc_url( home_url( '/contact/' ) ); ?>" class="btn btn--teal">
						<?php _e( 'サービス資料を請求する', 'hachi' ); ?>
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
					[ '01', '状態の可視化',     '社員が10分以内で答える短いチェックをもとに、身体・睡眠・集中・疲労の傾向を整理します。参加者本人の事前同意を取得のうえ実施します。' ],
					[ '02', '背景の仮説整理',   '個別の回答をそのまま伝えるのではなく、組織全体の傾向として整理します。経営者・管理職が把握しやすい形でまとめます。' ],
					[ '03', 'コンディショニング介入', 'ご希望の企業には、アスレティックトレーナー(AT)または運動指導資格を持つスタッフが現場に入ります。状態に合ったストレッチ・コンディショニング指導とセルフケアの方法をお伝えします。' ],
					[ '04', '変化の確認',       '一定期間後に再評価を行い、傾向の推移を確認します。組織の傾向の変化を経営者向け状態傾向レポートでお届けします。' ],
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

<!-- ===== 提供範囲・成果物 ===== -->
<section class="section" id="deliverables">
	<div class="container">

		<div class="grid-2 js-fade" style="margin-top:0">

			<!-- 提供範囲 -->
			<div>
				<?php hachi_section_label( 'P r o v i d e d' ); ?>
				<h3 class="heading-en heading-en--xs" style="margin-top:12px"><?php _e( '提供範囲', 'hachi' ); ?></h3>
				<ul class="service-feature-list" style="margin-top:24px">
					<?php
					$features = [
						'全社員向け状態評価（チェック実施）',
						'組織全体の傾向分析',
						'コンディショニングセミナー',
						'セルフケア指導',
						'再評価（フォローアップ）',
						'状態傾向レポート（経営者向け・本人向け）',
					];
					foreach ( $features as $f ) :
					?>
						<li>
							<span class="service-feature-list__dot" aria-hidden="true"></span>
							<?php echo esc_html( $f ); ?>
						</li>
					<?php endforeach; ?>
				</ul>
			</div>

			<!-- 届けるレポート -->
			<div>
				<?php hachi_section_label( 'D e l i v e r a b l e s' ); ?>
				<h3 class="heading-en heading-en--xs" style="margin-top:12px"><?php _e( '届けるレポート、2種類。', 'hachi' ); ?></h3>
				<div style="margin-top:24px">
					<p style="font-weight:600;margin-bottom:8px"><?php _e( '組織コンディションレポート（経営者・管理職向け）', 'hachi' ); ?></p>
					<ul class="service-feature-list">
						<?php
						$org_report = [
							'組織全体の状態傾向（睡眠・疲労・集中など）',
							'注意が必要な傾向の概要',
							'ご提案事項（セミナー実施・フォローアップ等）',
						];
						foreach ( $org_report as $r ) :
						?>
							<li><span class="service-feature-list__dot" aria-hidden="true"></span><?php echo esc_html( $r ); ?></li>
						<?php endforeach; ?>
					</ul>
					<p style="font-size:13px;color:var(--gray);margin-top:8px"><?php _e( '個人を特定する情報は含まれません。', 'hachi' ); ?></p>
				</div>
				<div style="margin-top:32px">
					<p style="font-weight:600;margin-bottom:8px"><?php _e( '個人状態レポート（社員本人向け）', 'hachi' ); ?></p>
					<ul class="service-feature-list">
						<?php
						$personal_report = [
							'自分自身の現在の状態整理',
							'セルフケアの提案（ストレッチ・生活習慣の調整等）',
						];
						foreach ( $personal_report as $r ) :
						?>
							<li><span class="service-feature-list__dot" aria-hidden="true"></span><?php echo esc_html( $r ); ?></li>
						<?php endforeach; ?>
					</ul>
					<p style="font-size:13px;color:var(--gray);margin-top:8px"><?php _e( '本人にのみ届きます。個人を特定できる情報は、導入企業を含む第三者には提供しません。', 'hachi' ); ?></p>
				</div>
			</div>

		</div>

	</div>
</section>

<!-- ===== やらないことを、明示。===== -->
<section class="section--light" id="scope">
	<div class="container">

		<div class="js-fade" style="max-width:820px;margin-bottom:56px">
			<?php hachi_section_label( 'S c o p e' ); ?>
			<h2 class="heading-en heading-en--sm" style="margin-top:8px"><?php _e( 'やらないことを、明示。', 'hachi' ); ?></h2>
			<p class="body-copy" style="margin-top:20px">
				<?php _e( 'コンディション・インサイトが提供するのは「状態の可視化」と「コンディショニング指導」です。以下は提供の対象外です。', 'hachi' ); ?>
			</p>
		</div>

		<div class="values-grid js-fade js-fade--delay-1">
			<?php
			$scopes = [
				[
					'医療診断・疾病判定は行いません。',
					'チェックの結果は、医学的な診断や疾患の判定ではありません。疾患が疑われる場合は、医療機関への受診をお勧めします。',
				],
				[
					'精神医学的な評価・メンタルヘルス疾患の判定も行いません。',
					'専門的な判断が必要と感じる場合は、産業医や医療機関へのご相談を推奨します。',
				],
				[
					'個人の情報を会社に渡しません。',
					'個人を特定できる情報は、導入企業を含む第三者には提供しません。会社に届くのは、組織全体の傾向をまとめたレポートのみです。',
				],
				[
					'人事査定・離職予測には使いません。',
					'チェック結果を人事評価や査定に用いることはありません。特定の社員の離職・休職を予測・判定するものではありません。導入企業との契約においても同様の制約を設けています。',
				],
			];
			foreach ( $scopes as $s ) :
			?>
				<div class="value-card">
					<h3 class="value-card__name" style="font-size:15px;line-height:1.5"><?php echo esc_html( $s[0] ); ?></h3>
					<p class="value-card__desc" style="margin-top:12px"><?php echo esc_html( $s[1] ); ?></p>
				</div>
			<?php endforeach; ?>
		</div>

		<!-- AT民間資格注記 -->
		<div class="js-fade js-fade--delay-2" style="margin-top:56px;padding:24px;background:var(--white);border:1px solid var(--gray2);border-radius:4px;max-width:820px">
			<p class="body-copy" style="font-size:13px;color:var(--gray)">
				<?php _e( '【担当スタッフについて】現場に入るアスレティックトレーナー（AT）は民間資格です。医療行為（診察・診断・薬の処方・マッサージ等の施術）は行いません。業務範囲はストレッチ指導・コンディショニング指導・身体の状態をうかがい、声がけ・記録を行うことに限ります。', 'hachi' ); ?>
			</p>
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
				<?php _e( 'コンディション・インサイトのご相談、資料請求はメールまたはこちらのフォームから。', 'hachi' ); ?>
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
