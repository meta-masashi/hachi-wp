<?php
/**
 * HACHI Theme — Privacy Policy page
 *
 * DB ページ不要。functions.php の template_redirect フックから直接 require される。
 * get_header() / get_footer() を使用するが $post / the_content() 等のループ依存なし。
 *
 * Content: コーポレートサイト用プライバシーポリシー v1.0
 * Legal sign-off: ritsu-legal (2026-06-10)
 * Cookie条項: なし版（GTM/GA4 本番設定状態が確認不能のため安全側を採用）
 *              本番で GTM_CONTAINER_ID / GA4_MEASUREMENT_ID を設定した際は
 *              第6条を Cookie条項あり版に差し替えること（リツに再確認を依頼）
 *
 * @package HACHI
 */
get_header();
?>

<!-- ===== PAGE HERO ===== -->
<div class="page-hero">
	<div class="container">
		<div class="js-fade"><?php hachi_section_label( 'L e g a l' ); ?></div>
		<h1 class="heading-en js-fade js-fade--delay-1">PRIVACY POLICY</h1>
		<p class="heading-jp js-fade js-fade--delay-2"><?php _e( 'プライバシーポリシー', 'hachi' ); ?></p>
	</div>
</div>

<!-- ===== PRIVACY POLICY BODY ===== -->
<section class="section">
	<div class="container">
		<div class="privacy-policy js-fade" style="max-width:800px;margin:0 auto">

			<p class="privacy-policy__date" style="font-size:12px;letter-spacing:0.1em;color:var(--gray);margin-bottom:56px">
				<?php _e( '制定日：2026年6月10日', 'hachi' ); ?>
			</p>

			<!-- 第1条 -->
			<div class="privacy-policy__section">
				<h2 class="privacy-policy__heading"><?php _e( '第1条　事業者情報', 'hachi' ); ?></h2>
				<p class="privacy-policy__body">
					<?php _e( '株式会社HACHI（以下「当社」といいます）は、当社ウェブサイト（https://hachi-wellnesshack.com）における個人情報の取扱いについて、以下のとおりプライバシーポリシー（以下「本ポリシー」といいます）を定めます。', 'hachi' ); ?>
				</p>
				<table class="privacy-policy__table">
					<tbody>
						<tr>
							<th><?php _e( '事業者名', 'hachi' ); ?></th>
							<td><?php _e( '株式会社HACHI', 'hachi' ); ?></td>
						</tr>
						<tr>
							<th><?php _e( '代表者', 'hachi' ); ?></th>
							<td><?php _e( '佐々木譲崇（ささきまさし）', 'hachi' ); ?></td>
						</tr>
						<tr>
							<th><?php _e( '所在地', 'hachi' ); ?></th>
							<td><?php _e( '〒180-0004 東京都武蔵野市吉祥寺本町1-13-2 5F', 'hachi' ); ?></td>
						</tr>
						<tr>
							<th><?php _e( 'お問い合わせ・開示請求窓口', 'hachi' ); ?></th>
							<td><a href="mailto:info@hachi-wellnesshack.com" class="privacy-policy__link">info@hachi-wellnesshack.com</a></td>
						</tr>
					</tbody>
				</table>
			</div>

			<!-- 第2条 -->
			<div class="privacy-policy__section">
				<h2 class="privacy-policy__heading"><?php _e( '第2条　取得する個人情報の種類', 'hachi' ); ?></h2>
				<p class="privacy-policy__body">
					<?php _e( '当社は、当社ウェブサイトのお問い合わせフォームを通じて、以下の情報を取得します。', 'hachi' ); ?>
				</p>
				<table class="privacy-policy__table">
					<thead>
						<tr>
							<th><?php _e( '情報項目', 'hachi' ); ?></th>
							<th><?php _e( '取得の根拠', 'hachi' ); ?></th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td><?php _e( 'お名前', 'hachi' ); ?></td>
							<td><?php _e( 'フォームへの入力', 'hachi' ); ?></td>
						</tr>
						<tr>
							<td><?php _e( '会社名', 'hachi' ); ?></td>
							<td><?php _e( 'フォームへの入力', 'hachi' ); ?></td>
						</tr>
						<tr>
							<td><?php _e( 'メールアドレス', 'hachi' ); ?></td>
							<td><?php _e( 'フォームへの入力', 'hachi' ); ?></td>
						</tr>
						<tr>
							<td><?php _e( '用件（選択肢）', 'hachi' ); ?></td>
							<td><?php _e( 'フォームへの選択', 'hachi' ); ?></td>
						</tr>
						<tr>
							<td><?php _e( 'お問い合わせ内容', 'hachi' ); ?></td>
							<td><?php _e( 'フォームへの入力', 'hachi' ); ?></td>
						</tr>
					</tbody>
				</table>
				<p class="privacy-policy__body">
					<?php _e( '上記以外の個人情報（健康情報・病歴・その他の要配慮個人情報）は、本ウェブサイトでは取得しません。', 'hachi' ); ?>
				</p>
			</div>

			<!-- 第3条 -->
			<div class="privacy-policy__section">
				<h2 class="privacy-policy__heading"><?php _e( '第3条　利用目的', 'hachi' ); ?></h2>
				<p class="privacy-policy__body">
					<?php _e( '取得した個人情報は、以下の目的にのみ使用します（個人情報の保護に関する法律第17条）。', 'hachi' ); ?>
				</p>
				<ol class="privacy-policy__list">
					<li><?php _e( 'お問い合わせへの回答・ご相談対応', 'hachi' ); ?></li>
					<li><?php _e( 'お問い合わせ内容の記録・管理', 'hachi' ); ?></li>
					<li><?php _e( '法令に基づく対応および行政機関への報告', 'hachi' ); ?></li>
				</ol>
				<p class="privacy-policy__body">
					<?php _e( '上記以外の目的では使用しません。', 'hachi' ); ?>
				</p>
			</div>

			<!-- 第4条 -->
			<div class="privacy-policy__section">
				<h2 class="privacy-policy__heading"><?php _e( '第4条　第三者への提供', 'hachi' ); ?></h2>
				<p class="privacy-policy__body">
					<?php _e( '当社は、以下の場合を除き、取得した個人情報を第三者に提供しません（個人情報の保護に関する法律第27条）。', 'hachi' ); ?>
				</p>
				<ul class="privacy-policy__list">
					<li><?php _e( '法令に基づく場合（裁判所・捜査機関等からの適法な請求）', 'hachi' ); ?></li>
				</ul>
			</div>

			<!-- 第5条 -->
			<div class="privacy-policy__section">
				<h2 class="privacy-policy__heading"><?php _e( '第5条　業務委託（外部サービスの利用）', 'hachi' ); ?></h2>
				<p class="privacy-policy__body">
					<?php _e( '当社は、お問い合わせフォームから送信された情報の受信・保存に際して、以下の外部事業者のサービスを利用しています。当社は、個人情報の保護に関する法律第24条に基づき、委託先の安全管理状況を監督します。', 'hachi' ); ?>
				</p>
				<table class="privacy-policy__table">
					<thead>
						<tr>
							<th><?php _e( '委託先', 'hachi' ); ?></th>
							<th><?php _e( '業務内容', 'hachi' ); ?></th>
							<th><?php _e( 'データ保存場所', 'hachi' ); ?></th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td><?php _e( 'Supabase, Inc.', 'hachi' ); ?></td>
							<td><?php _e( 'フォーム送信データの受信・保存', 'hachi' ); ?></td>
							<td><?php _e( '日本国内（東京リージョン）', 'hachi' ); ?></td>
						</tr>
					</tbody>
				</table>
				<p class="privacy-policy__body">
					<?php _e( '本サービスのデータは日本国内に保存されます。ただし、Supabase, Inc.（米国）がサービス運用・管理目的でデータにアクセスする場合があります。当社は、Supabaseとのデータ処理契約（DPA）に基づき、適切な保護措置を確保します。', 'hachi' ); ?>
				</p>
			</div>

			<!-- 第6条（Cookie条項なし版） -->
			<div class="privacy-policy__section">
				<h2 class="privacy-policy__heading"><?php _e( '第6条　Cookieおよびアクセス解析', 'hachi' ); ?></h2>
				<p class="privacy-policy__body">
					<?php _e( '当社ウェブサイトでは、現在アクセス解析ツールを使用していません。将来的に導入する際は、本ポリシーを改定のうえお知らせします。', 'hachi' ); ?>
				</p>
			</div>

			<!-- 第7条 -->
			<div class="privacy-policy__section">
				<h2 class="privacy-policy__heading"><?php _e( '第7条　保有期間', 'hachi' ); ?></h2>
				<p class="privacy-policy__body">
					<?php _e( 'お問い合わせにより取得した個人データは、お問い合わせ対応の完了後3年を目安に削除します。', 'hachi' ); ?>
				</p>
				<p class="privacy-policy__body">
					<?php _e( 'ご本人から削除のご請求があった場合は、対応完了後速やかに削除します。', 'hachi' ); ?>
				</p>
			</div>

			<!-- 第8条 -->
			<div class="privacy-policy__section">
				<h2 class="privacy-policy__heading"><?php _e( '第8条　個人情報に関するご請求', 'hachi' ); ?></h2>
				<p class="privacy-policy__body">
					<?php _e( 'ご本人またはその代理人は、当社が保有する個人データについて、以下の請求を行うことができます（個人情報の保護に関する法律第32条〜第35条）。', 'hachi' ); ?>
				</p>
				<ul class="privacy-policy__list">
					<li><?php _e( '開示の請求', 'hachi' ); ?></li>
					<li><?php _e( '訂正・追加・削除の請求', 'hachi' ); ?></li>
					<li><?php _e( '利用停止・消去の請求', 'hachi' ); ?></li>
					<li><?php _e( '第三者提供の停止の請求', 'hachi' ); ?></li>
				</ul>
				<div class="privacy-policy__note">
					<p class="privacy-policy__note-heading"><?php _e( '請求窓口', 'hachi' ); ?></p>
					<p class="privacy-policy__body">
						<?php _e( '電子メール：', 'hachi' ); ?>
						<a href="mailto:info@hachi-wellnesshack.com" class="privacy-policy__link">info@hachi-wellnesshack.com</a>
					</p>
					<p class="privacy-policy__body">
						<?php _e( '件名に「個人情報に関するご請求」とご記載ください。本人確認のうえ、原則として請求受領後2週間以内に対応いたします。', 'hachi' ); ?>
					</p>
				</div>
			</div>

			<!-- 第9条 -->
			<div class="privacy-policy__section">
				<h2 class="privacy-policy__heading"><?php _e( '第9条　苦情・相談', 'hachi' ); ?></h2>
				<p class="privacy-policy__body">
					<?php _e( '個人情報の取扱いに関するご意見・苦情は、まず当社窓口（info@hachi-wellnesshack.com）にお申し出ください。当社での解決が困難な場合は、個人情報保護委員会（https://www.ppc.go.jp/）にご相談いただくことができます。', 'hachi' ); ?>
				</p>
			</div>

			<!-- 第10条 -->
			<div class="privacy-policy__section">
				<h2 class="privacy-policy__heading"><?php _e( '第10条　本ポリシーの改定', 'hachi' ); ?></h2>
				<p class="privacy-policy__body">
					<?php _e( '当社は、法令の改正またはサービス内容の変更により、本ポリシーを改定することがあります。改定後のポリシーは、当社ウェブサイトへの掲載をもって効力を生じます。重要な変更については、ウェブサイト上でお知らせします。', 'hachi' ); ?>
				</p>
			</div>

			<p class="privacy-policy__company">
				<?php _e( '株式会社HACHI', 'hachi' ); ?>
			</p>

		</div>
	</div>
</section>

<?php get_footer(); ?>
