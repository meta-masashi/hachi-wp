<?php
/**
 * HACHI Theme — page-contact.php (Light Monochrome v2)
 * Contact page with category cards, chip-based optional fields,
 * reCAPTCHA v3, honeypot, and category-aware auto-reply.
 *
 * Slug `contact` で自動適用（WP template hierarchy）。
 * Updated: 2026-06-08 corp-refresh — PACE先行案内カード削除 / 従業員規模最適化 / ヘッダーコピー修正
 */
get_header();

// カテゴリー一覧（contact-handler.php の hachi_get_contact_categories() と連動）
$categories = function_exists( 'hachi_get_contact_categories' ) ? hachi_get_contact_categories() : [
	'reboot_docs' => [ 'label' => 'コンディション・インサイト 導入相談', 'emoji' => '📄' ],
	'general'     => [ 'label' => '一般お問い合わせ', 'emoji' => '💬' ],
];

// カード表示用メタ（ラベル／キャッチ／アイコン）
$card_meta = [
	'reboot_docs' => [ 'title' => 'コンディション・インサイト 導入相談', 'sub' => '資料請求・導入ご相談・サービス詳細のご確認', 'icon' => '◆' ],
	'media'       => [ 'title' => '取材・メディア', 'sub' => 'プレス・取材・メディア掲載のお問い合わせ', 'icon' => '◇' ],
	'recruit'     => [ 'title' => '採用・パートナー', 'sub' => '採用、業務委託、協業パートナー募集', 'icon' => '◎' ],
	'general'     => [ 'title' => 'その他', 'sub' => '上記以外のご相談、ご質問はこちら', 'icon' => '○' ],
];
?>
<main id="main-content">

<!-- ===== PAGE HERO ===== -->
<div class="page-hero">
	<div class="container">
		<div class="js-fade"><?php hachi_section_label( 'C o n t a c t' ); ?></div>
		<h1 class="heading-en js-fade js-fade--delay-1">CONTACT</h1>
		<p class="heading-jp js-fade js-fade--delay-2"><?php _e( 'お問い合わせ・導入相談', 'hachi' ); ?></p>
	</div>
</div>

<!-- ===== INTRO ===== -->
<section class="section">
	<div class="container">
		<div class="contact-intro js-fade">
			<p class="body-copy">
				<?php _e( 'HACHI へのお問い合わせはこちらから。ご用件に合ったカテゴリーを選んでいただくと、最適な担当者から 2 営業日以内にご返信いたします。', 'hachi' ); ?>
			</p>
		</div>

		<div class="contact-form-wrap js-fade js-fade--delay-1">

			<!-- Success message (hidden initially) -->
			<div id="form-success" class="form-success" role="status" aria-live="polite">
				<strong><?php _e( 'お問い合わせを受け付けました。', 'hachi' ); ?></strong><br>
				<span style="font-size:14px;color:var(--gray);display:inline-block;margin-top:8px;font-family:var(--sans)">
					<?php _e( '確認メールをお送りしました。2 営業日以内にご連絡いたします。', 'hachi' ); ?>
				</span>
			</div>

			<!-- Contact form -->
			<form id="contact-form" novalidate aria-label="<?php esc_attr_e( 'お問い合わせフォーム', 'hachi' ); ?>">

				<?php wp_nonce_field( 'hachi_nonce', 'contact_nonce' ); ?>

				<!-- Honeypot -->
				<div style="position:absolute;left:-9999px;opacity:0;pointer-events:none" aria-hidden="true">
					<label>Website<input type="text" name="website" tabindex="-1" autocomplete="off"></label>
				</div>

				<!-- ===== STEP 1: Category Cards ===== -->
				<div class="contact-step">
					<div class="contact-step__num">01</div>
					<div class="contact-step__label"><?php _e( 'ご用件を選択', 'hachi' ); ?><sup aria-label="必須">必須</sup></div>

					<div class="contact-cards" role="radiogroup" aria-label="<?php esc_attr_e( 'お問い合わせ種別', 'hachi' ); ?>">
						<?php foreach ( $card_meta as $key => $meta ) :
							// ラベル解決: カテゴリー登録がある場合はそのラベルを、なければカードメタを使用
							$form_value = isset( $categories[ $key ]['label'] ) ? $categories[ $key ]['label'] : $meta['title'];
						?>
							<label class="contact-card">
								<input type="radio" name="contact_cat" value="<?php echo esc_attr( $form_value ); ?>" data-cat-key="<?php echo esc_attr( $key ); ?>">
								<span class="contact-card__inner">
									<span class="contact-card__icon"><?php echo esc_html( $meta['icon'] ); ?></span>
									<span class="contact-card__title"><?php echo esc_html( $meta['title'] ); ?></span>
									<span class="contact-card__sub"><?php echo esc_html( $meta['sub'] ); ?></span>
								</span>
							</label>
						<?php endforeach; ?>
					</div>
					<span class="form-field__error" id="err-cat" role="alert" style="display:none">
						<?php _e( 'ご用件をお選びください。', 'hachi' ); ?>
					</span>
				</div>

				<!-- ===== STEP 2: Basic info (required) ===== -->
				<div class="contact-step">
					<div class="contact-step__num">02</div>
					<div class="contact-step__label"><?php _e( '基本情報', 'hachi' ); ?></div>

					<div class="form-field" id="field-name">
						<label class="form-field__label" for="contact-name">
							<?php _e( 'お名前', 'hachi' ); ?><sup aria-label="必須">必須</sup>
						</label>
						<input type="text" id="contact-name" name="contact_name" class="form-field__input"
							placeholder="山田 太郎" maxlength="100" autocomplete="name" aria-required="true" aria-describedby="err-name">
						<span class="form-field__error" id="err-name" role="alert"><?php _e( 'お名前をご入力ください。', 'hachi' ); ?></span>
					</div>

					<div class="form-field" id="field-company">
						<label class="form-field__label" for="contact-company">
							<?php _e( '会社名・団体名', 'hachi' ); ?>
						</label>
						<input type="text" id="contact-company" name="contact_company" class="form-field__input"
							placeholder="株式会社〇〇" maxlength="200" autocomplete="organization">
					</div>

					<div class="form-field" id="field-email">
						<label class="form-field__label" for="contact-email">
							<?php _e( 'メールアドレス', 'hachi' ); ?><sup aria-label="必須">必須</sup>
						</label>
						<input type="email" id="contact-email" name="contact_email" class="form-field__input"
							placeholder="example@company.com" maxlength="254" autocomplete="email" aria-required="true" aria-describedby="err-email">
						<span class="form-field__error" id="err-email" role="alert"><?php _e( '正しいメールアドレスを入力してください。', 'hachi' ); ?></span>
					</div>
				</div>

				<!-- ===== STEP 3: Optional meta (chips — minimal friction) ===== -->
				<div class="contact-step contact-step--optional">
					<div class="contact-step__num">03</div>
					<div class="contact-step__label">
						<?php _e( '詳細情報', 'hachi' ); ?>
						<span class="contact-step__optional"><?php _e( '任意・クリックのみで選択可', 'hachi' ); ?></span>
					</div>

					<!-- Role -->
					<div class="form-chip-field">
						<div class="form-chip-field__label"><?php _e( 'ご担当の役割', 'hachi' ); ?></div>
						<div class="form-chips" data-field="contact_role">
							<?php foreach ( [ '経営層', '部門責任者', 'マネージャー', '担当者', 'その他' ] as $v ) : ?>
								<button type="button" class="form-chip" data-value="<?php echo esc_attr( $v ); ?>"><?php echo esc_html( $v ); ?></button>
							<?php endforeach; ?>
						</div>
						<input type="hidden" name="contact_role" value="">
					</div>

					<!-- Company size — 20-100名中小企業ターゲットに最適化 -->
					<div class="form-chip-field">
						<div class="form-chip-field__label"><?php _e( '従業員規模', 'hachi' ); ?></div>
						<div class="form-chips" data-field="contact_size">
							<?php foreach ( [ '20名未満', '20〜50名', '51〜100名', '101〜300名', '301名以上' ] as $v ) : ?>
								<button type="button" class="form-chip" data-value="<?php echo esc_attr( $v ); ?>"><?php echo esc_html( $v ); ?></button>
							<?php endforeach; ?>
						</div>
						<input type="hidden" name="contact_size" value="">
					</div>

					<!-- Timeline -->
					<div class="form-chip-field">
						<div class="form-chip-field__label"><?php _e( '検討時期', 'hachi' ); ?></div>
						<div class="form-chips" data-field="contact_timeline">
							<?php foreach ( [ 'すぐに', '1〜3ヶ月以内', '半年以内', '情報収集段階', '未定' ] as $v ) : ?>
								<button type="button" class="form-chip" data-value="<?php echo esc_attr( $v ); ?>"><?php echo esc_html( $v ); ?></button>
							<?php endforeach; ?>
						</div>
						<input type="hidden" name="contact_timeline" value="">
					</div>

					<!-- Phone (optional, text) -->
					<div class="form-field">
						<label class="form-field__label" for="contact-phone">
							<?php _e( '電話番号（任意）', 'hachi' ); ?>
						</label>
						<input type="tel" id="contact-phone" name="contact_phone" class="form-field__input"
							placeholder="03-XXXX-XXXX" maxlength="20" autocomplete="tel">
					</div>
				</div>

				<!-- ===== STEP 4: Message ===== -->
				<div class="contact-step">
					<div class="contact-step__num">04</div>
					<div class="contact-step__label"><?php _e( 'お問い合わせ内容', 'hachi' ); ?></div>

					<div class="form-field" id="field-message">
						<label class="form-field__label" for="contact-message" style="position:absolute;left:-9999px">
							<?php _e( 'お問い合わせ内容', 'hachi' ); ?>
						</label>
						<textarea id="contact-message" name="contact_message" class="form-field__textarea"
							placeholder="<?php esc_attr_e( 'ご質問・ご要望をご記入ください。（具体的な課題や背景をお書きいただけると、より的確にご返信できます）', 'hachi' ); ?>"
							maxlength="2000" rows="7" aria-required="true" aria-describedby="err-message"></textarea>
						<span class="form-field__error" id="err-message" role="alert">
							<?php _e( 'お問い合わせ内容をご入力ください。', 'hachi' ); ?>
						</span>
					</div>
				</div>

				<!-- reCAPTCHA v3 hidden token -->
				<input type="hidden" name="recaptcha_token" id="recaptcha-token" value="">

				<p class="form-privacy">
					<?php _e( '送信ボタンを押すことで', 'hachi' ); ?><a href="<?php echo esc_url( home_url( '/privacy-policy/' ) ); ?>" style="color:var(--accent)"><?php _e( 'プライバシーポリシー', 'hachi' ); ?></a><?php _e( 'に同意したものとみなします。いただいた個人情報はお問い合わせ対応のみに使用し、第三者への提供は行いません。', 'hachi' ); ?>
					<br>
					<span style="font-size:11px;opacity:0.7;display:inline-block;margin-top:6px"><?php _e( 'このフォームは reCAPTCHA v3 で保護されています。', 'hachi' ); ?></span>
				</p>

				<button type="submit" class="btn btn--teal" id="form-submit">
					<span id="submit-text"><?php _e( '送信する', 'hachi' ); ?></span>
					<?php hachi_arrow_icon(); ?>
				</button>

				<p id="form-general-error" style="display:none;color:var(--accent);margin-top:16px;font-size:14px" role="alert"></p>

			</form>
		</div>

		<!-- ===== Direct contact info ===== -->
		<div class="contact-direct js-fade js-fade--delay-2">
			<div class="contact-direct__item">
				<div class="contact-direct__label">EMAIL</div>
				<p class="contact-direct__text">
					info@hachi-wellnesshack.com
				</p>
			</div>
			<div class="contact-direct__item">
				<div class="contact-direct__label">OFFICE</div>
				<p class="contact-direct__text">
					〒180-0004<br>
					東京都武蔵野市吉祥寺本町 1-13-2 5F
				</p>
			</div>
			<div class="contact-direct__item">
				<div class="contact-direct__label">BUSINESS HOURS</div>
				<p class="contact-direct__text">
					平日 10:00 – 18:00<br>
					<span style="font-size:12px;color:var(--gray)"><?php _e( '土日祝を除く', 'hachi' ); ?></span>
				</p>
			</div>
		</div>

	</div>
</section>

<!-- ===== Inline JS: card selection, chip toggles, reCAPTCHA v3 ===== -->
<script>
(function () {
	'use strict';

	// ---------- Category card selection ----------
	var cards = document.querySelectorAll('.contact-card input[type="radio"]');
	cards.forEach(function (input) {
		input.addEventListener('change', function () {
			document.querySelectorAll('.contact-card').forEach(function (el) {
				el.classList.remove('is-selected');
			});
			if (input.checked) {
				input.closest('.contact-card').classList.add('is-selected');
				var errCat = document.getElementById('err-cat');
				if (errCat) errCat.style.display = 'none';
			}
		});
	});

	// ---------- Chip toggle (single-select) ----------
	document.querySelectorAll('.form-chips').forEach(function (group) {
		var hidden = group.parentElement.querySelector('input[type="hidden"]');
		group.querySelectorAll('.form-chip').forEach(function (chip) {
			chip.addEventListener('click', function () {
				var val = chip.getAttribute('data-value');
				var alreadyActive = chip.classList.contains('is-active');
				group.querySelectorAll('.form-chip').forEach(function (c) { c.classList.remove('is-active'); });
				if (!alreadyActive) {
					chip.classList.add('is-active');
					if (hidden) hidden.value = val;
				} else {
					if (hidden) hidden.value = '';
				}
			});
		});
	});

	// ---------- Client-side category requirement ----------
	var form = document.getElementById('contact-form');
	if (form) {
		form.addEventListener('submit', function (e) {
			var hasCat = !!form.querySelector('input[name="contact_cat"]:checked');
			if (!hasCat) {
				e.preventDefault();
				e.stopImmediatePropagation();
				var errCat = document.getElementById('err-cat');
				if (errCat) errCat.style.display = 'block';
				var firstCard = document.querySelector('.contact-cards');
				if (firstCard) firstCard.scrollIntoView({ behavior: 'smooth', block: 'center' });
			}
		}, true); // capture phase so runs before main.js handler
	}
}());
</script>

<?php if ( defined( 'HACHI_RECAPTCHA_SITE_KEY' ) && ! empty( HACHI_RECAPTCHA_SITE_KEY ) ) : ?>
<script>
(function () {
	'use strict';
	var siteKey = <?php echo wp_json_encode( HACHI_RECAPTCHA_SITE_KEY ); ?>;
	var form    = document.getElementById('contact-form');
	var tokenEl = document.getElementById('recaptcha-token');
	if (!form || !tokenEl || !siteKey) return;

	form.addEventListener('submit', function (e) {
		if (tokenEl.value) return;
		e.preventDefault();
		if (typeof grecaptcha === 'undefined') { form.submit(); return; }
		grecaptcha.ready(function () {
			grecaptcha.execute(siteKey, { action: 'contact' }).then(function (token) {
				tokenEl.value = token;
				var evt = new Event('submit', { bubbles: true, cancelable: true });
				form.dispatchEvent(evt);
			});
		});
	}, { once: true });
}());
</script>
<?php endif; ?>

</main>
<?php get_footer(); ?>
