<?php
/**
 * HACHI Theme — page-contact.php (v3 / 2026-06-18)
 * Contact page: 3-section minimal layout.
 * Section 1: Hero / Section 2: Form / Section 3: Direct mail
 *
 * Slug `contact` で自動適用（WP template hierarchy）。
 */
get_header();
?>

<main id="main-content">

<!-- ===== Section 1: Hero ===== -->
<section class="cv3-hero">
	<div class="container">
		<div class="cv3-hero__inner">
			<p class="cv3-eyebrow"><?php esc_html_e( 'CONTACT', 'hachi' ); ?></p>
			<h1 class="cv3-hero__heading"><?php esc_html_e( 'お問い合わせ', 'hachi' ); ?></h1>
			<p class="cv3-hero__sub">
				<?php esc_html_e( 'コンディション・インサイト / HACHI Fieldwork について、お気軽にご連絡ください。担当より 2 営業日以内にご返信します。', 'hachi' ); ?>
			</p>
		</div>
	</div>
</section>

<!-- ===== Section 2: Form ===== -->
<section class="cv3-form-section">
	<div class="container">
		<div class="cv3-form-wrap">

			<!-- 送信成功メッセージ（初期非表示） -->
			<div id="cv3-form-success" class="cv3-form-success" role="status" aria-live="polite" style="display:none">
				<strong><?php esc_html_e( 'お問い合わせを受け付けました。', 'hachi' ); ?></strong><br>
				<span class="cv3-form-success__sub">
					<?php esc_html_e( '確認メールをお送りしました。2 営業日以内にご連絡いたします。', 'hachi' ); ?>
				</span>
			</div>

			<form id="cv3-contact-form" novalidate aria-label="<?php esc_attr_e( 'お問い合わせフォーム', 'hachi' ); ?>">

				<?php wp_nonce_field( 'hachi_nonce', 'nonce' ); ?>

				<!-- Honeypot -->
				<div style="position:absolute;left:-9999px;opacity:0;pointer-events:none" aria-hidden="true">
					<label>Website<input type="text" name="website" tabindex="-1" autocomplete="off"></label>
				</div>

				<!-- カテゴリー: general 固定 hidden（handler の allowlist チェック通過用） -->
				<input type="hidden" name="contact_cat" value="一般お問い合わせ">

				<!-- 会社名（必須） -->
				<div class="cv3-field" id="cv3-field-company">
					<label class="cv3-label" for="cv3-company">
						<?php esc_html_e( '会社名', 'hachi' ); ?>
						<span class="cv3-badge cv3-badge--required"><?php esc_html_e( '必須', 'hachi' ); ?></span>
					</label>
					<input
						class="cv3-input"
						type="text"
						id="cv3-company"
						name="contact_company"
						placeholder="<?php esc_attr_e( '株式会社○○', 'hachi' ); ?>"
						maxlength="200"
						required
						autocomplete="organization"
						aria-required="true"
						aria-describedby="cv3-err-company"
					>
					<span class="cv3-error" id="cv3-err-company" role="alert" style="display:none">
						<?php esc_html_e( '会社名をご入力ください。', 'hachi' ); ?>
					</span>
				</div>

				<!-- お名前（必須） -->
				<div class="cv3-field" id="cv3-field-name">
					<label class="cv3-label" for="cv3-name">
						<?php esc_html_e( 'お名前', 'hachi' ); ?>
						<span class="cv3-badge cv3-badge--required"><?php esc_html_e( '必須', 'hachi' ); ?></span>
					</label>
					<input
						class="cv3-input"
						type="text"
						id="cv3-name"
						name="contact_name"
						placeholder="<?php esc_attr_e( '山田 太郎', 'hachi' ); ?>"
						maxlength="100"
						required
						autocomplete="name"
						aria-required="true"
						aria-describedby="cv3-err-name"
					>
					<span class="cv3-error" id="cv3-err-name" role="alert" style="display:none">
						<?php esc_html_e( 'お名前をご入力ください。', 'hachi' ); ?>
					</span>
				</div>

				<!-- メールアドレス（必須） -->
				<div class="cv3-field" id="cv3-field-email">
					<label class="cv3-label" for="cv3-email">
						<?php esc_html_e( 'メールアドレス', 'hachi' ); ?>
						<span class="cv3-badge cv3-badge--required"><?php esc_html_e( '必須', 'hachi' ); ?></span>
					</label>
					<input
						class="cv3-input"
						type="email"
						id="cv3-email"
						name="contact_email"
						placeholder="<?php esc_attr_e( 'name@example.com', 'hachi' ); ?>"
						maxlength="254"
						required
						autocomplete="email"
						aria-required="true"
						aria-describedby="cv3-err-email"
					>
					<span class="cv3-error" id="cv3-err-email" role="alert" style="display:none">
						<?php esc_html_e( '正しいメールアドレスを入力してください。', 'hachi' ); ?>
					</span>
				</div>

				<!-- 電話番号（任意） -->
				<div class="cv3-field">
					<label class="cv3-label" for="cv3-phone">
						<?php esc_html_e( '電話番号', 'hachi' ); ?>
						<span class="cv3-badge cv3-badge--optional"><?php esc_html_e( '任意', 'hachi' ); ?></span>
					</label>
					<input
						class="cv3-input"
						type="tel"
						id="cv3-phone"
						name="contact_phone"
						placeholder="<?php esc_attr_e( '03-1234-5678', 'hachi' ); ?>"
						maxlength="30"
						autocomplete="tel"
					>
				</div>

				<!-- 従業員数（select / 任意） -->
				<div class="cv3-field">
					<label class="cv3-label" for="cv3-employee-count">
						<?php esc_html_e( '従業員数', 'hachi' ); ?>
					</label>
					<div class="cv3-select-wrap">
						<select
							class="cv3-select"
							id="cv3-employee-count"
							name="contact_size"
						>
							<option value="" disabled selected><?php esc_html_e( '選択してください', 'hachi' ); ?></option>
							<option value="〜20名"><?php esc_html_e( '〜20 名', 'hachi' ); ?></option>
							<option value="20〜50名"><?php esc_html_e( '20〜50 名', 'hachi' ); ?></option>
							<option value="50〜100名"><?php esc_html_e( '50〜100 名', 'hachi' ); ?></option>
							<option value="100名以上"><?php esc_html_e( '100 名以上', 'hachi' ); ?></option>
						</select>
					</div>
				</div>

				<!-- お問い合わせ内容（必須） -->
				<div class="cv3-field" id="cv3-field-message">
					<label class="cv3-label" for="cv3-message">
						<?php esc_html_e( 'お問い合わせ内容', 'hachi' ); ?>
						<span class="cv3-badge cv3-badge--required"><?php esc_html_e( '必須', 'hachi' ); ?></span>
					</label>
					<textarea
						class="cv3-textarea"
						id="cv3-message"
						name="contact_message"
						rows="6"
						maxlength="1000"
						placeholder="<?php esc_attr_e( 'ご質問・ご要望をご記入ください（最大 1000 字）', 'hachi' ); ?>"
						required
						aria-required="true"
						aria-describedby="cv3-err-message"
					></textarea>
					<span class="cv3-error" id="cv3-err-message" role="alert" style="display:none">
						<?php esc_html_e( 'お問い合わせ内容をご入力ください。', 'hachi' ); ?>
					</span>
				</div>

				<!-- プライバシーポリシー同意 -->
				<div class="cv3-field cv3-field--privacy">
					<label class="cv3-checkbox-label">
						<input
							class="cv3-checkbox"
							type="checkbox"
							name="privacy_agreed"
							id="cv3-privacy"
							required
							aria-required="true"
							aria-describedby="cv3-err-privacy"
						>
						<span class="cv3-checkbox-text">
							<a href="<?php echo esc_url( home_url( '/privacy-policy/' ) ); ?>" class="cv3-privacy-link" target="_blank" rel="noopener">
								<?php esc_html_e( 'プライバシーポリシー', 'hachi' ); ?>
							</a><?php esc_html_e( 'をご確認ください', 'hachi' ); ?>
						</span>
					</label>
					<span class="cv3-error" id="cv3-err-privacy" role="alert" style="display:none">
						<?php esc_html_e( 'プライバシーポリシーへの同意が必要です。', 'hachi' ); ?>
					</span>
				</div>

				<!-- 送信ボタン -->
				<div class="cv3-submit">
					<button class="cv3-btn" type="submit" id="cv3-submit-btn">
						<span id="cv3-submit-text"><?php esc_html_e( '送信する', 'hachi' ); ?></span>
					</button>
				</div>

				<!-- 汎用エラー -->
				<p id="cv3-general-error" class="cv3-general-error" role="alert" style="display:none"></p>

			</form>
		</div>
	</div>
</section>

<!-- ===== Section 3: 直接メール案内 ===== -->
<section class="cv3-direct">
	<div class="container">
		<div class="cv3-direct__inner">
			<p class="cv3-direct__label"><?php esc_html_e( '直接メールでもご連絡いただけます', 'hachi' ); ?></p>
			<p class="cv3-direct__email">
				<a href="mailto:<?php echo esc_attr( 'info@hachi-wellnesshack.com' ); ?>" class="cv3-direct__email-link">
					<?php esc_html_e( 'info@hachi-wellnesshack.com', 'hachi' ); ?>
				</a>
			</p>
		</div>
	</div>
</section>

</main>

<!-- ===== Inline JS: フォーム送信 ===== -->
<script>
(function () {
	'use strict';

	var form      = document.getElementById('cv3-contact-form');
	var submitBtn = document.getElementById('cv3-submit-btn');
	var submitTxt = document.getElementById('cv3-submit-text');
	var successEl = document.getElementById('cv3-form-success');
	var genErr    = document.getElementById('cv3-general-error');

	if (!form) return;

	/* --- クライアントサイドバリデーション --- */
	function validateForm() {
		var ok = true;

		// 会社名
		var company = form.querySelector('[name="contact_company"]');
		var errCo   = document.getElementById('cv3-err-company');
		if (company && !company.value.trim()) {
			errCo.style.display = 'block';
			ok = false;
		} else if (errCo) {
			errCo.style.display = 'none';
		}

		// お名前
		var name    = form.querySelector('[name="contact_name"]');
		var errName = document.getElementById('cv3-err-name');
		if (name && !name.value.trim()) {
			errName.style.display = 'block';
			ok = false;
		} else if (errName) {
			errName.style.display = 'none';
		}

		// メールアドレス
		var email    = form.querySelector('[name="contact_email"]');
		var errEmail = document.getElementById('cv3-err-email');
		var emailRe  = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
		if (email && !emailRe.test(email.value.trim())) {
			errEmail.style.display = 'block';
			ok = false;
		} else if (errEmail) {
			errEmail.style.display = 'none';
		}

		// お問い合わせ内容
		var msg    = form.querySelector('[name="contact_message"]');
		var errMsg = document.getElementById('cv3-err-message');
		if (msg && !msg.value.trim()) {
			errMsg.style.display = 'block';
			ok = false;
		} else if (errMsg) {
			errMsg.style.display = 'none';
		}

		// プライバシー同意
		var privacy    = document.getElementById('cv3-privacy');
		var errPrivacy = document.getElementById('cv3-err-privacy');
		if (privacy && !privacy.checked) {
			errPrivacy.style.display = 'block';
			ok = false;
		} else if (errPrivacy) {
			errPrivacy.style.display = 'none';
		}

		return ok;
	}

	/* --- フォーム送信 --- */
	form.addEventListener('submit', function (e) {
		e.preventDefault();

		if (!validateForm()) {
			// 最初のエラーフィールドへスクロール
			var firstErr = form.querySelector('[style*="display: block"], [style*="display:block"]');
			if (firstErr) firstErr.scrollIntoView({ behavior: 'smooth', block: 'center' });
			return;
		}

		// 二重送信防止
		submitBtn.disabled  = true;
		submitTxt.textContent = '送信中…';
		if (genErr) genErr.style.display = 'none';

		var data = new FormData(form);
		data.append('action', 'hachi_contact');

		fetch(
			(typeof ajaxurl !== 'undefined') ? ajaxurl : '/wp-admin/admin-ajax.php',
			{ method: 'POST', body: data, credentials: 'same-origin' }
		)
		.then(function (res) { return res.json(); })
		.then(function (json) {
			if (json && json.success) {
				form.style.display    = 'none';
				if (successEl) successEl.style.display = 'block';
				window.scrollTo({ top: 0, behavior: 'smooth' });
			} else {
				var msg = (json && json.data && json.data.message)
					? json.data.message
					: 'エラーが発生しました。お手数ですが、直接メールにてご連絡ください。';
				if (genErr) {
					genErr.textContent    = msg;
					genErr.style.display  = 'block';
				}
				submitBtn.disabled    = false;
				submitTxt.textContent = '送信する';
			}
		})
		.catch(function () {
			if (genErr) {
				genErr.textContent   = 'ネットワークエラーが発生しました。お手数ですが、直接メールにてご連絡ください。';
				genErr.style.display = 'block';
			}
			submitBtn.disabled    = false;
			submitTxt.textContent = '送信する';
		});
	});
}());
</script>

<?php get_footer(); ?>
