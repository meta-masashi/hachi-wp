<?php
/**
 * Template Name: Contact Page
 * HACHI Theme — Contact page
 */
get_header();
?>

<div class="page-hero">
	<div class="page-hero__ghost ghost-text" aria-hidden="true">CONTACT</div>
	<div class="container">
		<div class="js-fade"><?php hachi_section_label( 'C o n t a c t' ); ?></div>
		<h1 class="heading-en js-fade js-fade--delay-1" style="font-size:clamp(52px,9vw,112px)">CONTACT</h1>
		<p class="heading-jp js-fade js-fade--delay-2"><?php _e( 'お問い合わせ・資料請求', 'hachi' ); ?></p>
	</div>
</div>

<section class="section">
	<div class="container">
		<div class="contact-form-wrap js-fade">

			<p class="body-copy" style="margin-bottom:60px">
				<?php _e( 'お気軽にお問い合わせください。担当者より2営業日以内にご連絡いたします。', 'hachi' ); ?>
			</p>

			<!-- Success message (hidden initially) -->
			<div id="form-success" class="form-success" role="status" aria-live="polite">
				<?php _e( 'お問い合わせを受け付けました。', 'hachi' ); ?><br>
				<?php _e( '担当者より2営業日以内にご連絡いたします。', 'hachi' ); ?>
			</div>

			<!-- Contact form -->
			<form id="contact-form" novalidate aria-label="<?php esc_attr_e( 'お問い合わせフォーム', 'hachi' ); ?>">

				<?php wp_nonce_field( 'hachi_nonce', 'contact_nonce' ); ?>

				<!-- Honeypot (hidden from real users) -->
				<div style="position:absolute;left:-9999px;opacity:0;pointer-events:none" aria-hidden="true">
					<input type="text" name="website" tabindex="-1" autocomplete="off">
				</div>

				<!-- Name -->
				<div class="form-field" id="field-name">
					<label class="form-field__label" for="contact-name">
						<?php _e( 'お名前', 'hachi' ); ?><sup aria-label="必須">必須</sup>
					</label>
					<input
						type="text"
						id="contact-name"
						name="contact_name"
						class="form-field__input"
						placeholder="山田 太郎"
						maxlength="100"
						autocomplete="name"
						aria-required="true"
						aria-describedby="err-name"
					>
					<span class="form-field__error" id="err-name" role="alert">
						<?php _e( 'お名前をご入力ください。', 'hachi' ); ?>
					</span>
				</div>

				<!-- Company -->
				<div class="form-field">
					<label class="form-field__label" for="contact-company">
						<?php _e( '会社名', 'hachi' ); ?>
					</label>
					<input
						type="text"
						id="contact-company"
						name="contact_company"
						class="form-field__input"
						placeholder="株式会社〇〇"
						maxlength="200"
						autocomplete="organization"
					>
				</div>

				<!-- Email -->
				<div class="form-field" id="field-email">
					<label class="form-field__label" for="contact-email">
						<?php _e( 'メールアドレス', 'hachi' ); ?><sup aria-label="必須">必須</sup>
					</label>
					<input
						type="email"
						id="contact-email"
						name="contact_email"
						class="form-field__input"
						placeholder="example@company.com"
						maxlength="254"
						autocomplete="email"
						aria-required="true"
						aria-describedby="err-email"
					>
					<span class="form-field__error" id="err-email" role="alert">
						<?php _e( '正しいメールアドレスを入力してください。', 'hachi' ); ?>
					</span>
				</div>

				<!-- Category -->
				<div class="form-field">
					<label class="form-field__label" for="contact-cat">
						<?php _e( 'お問い合わせ種別', 'hachi' ); ?>
					</label>
					<select id="contact-cat" name="contact_cat" class="form-field__select">
						<option value=""><?php _e( '選択してください', 'hachi' ); ?></option>
						<?php
						if ( function_exists( 'hachi_get_contact_categories' ) ) {
							foreach ( hachi_get_contact_categories() as $key => $data ) {
								printf(
									'<option value="%s">%s</option>',
									esc_attr( $data['label'] ),
									esc_html( $data['label'] )
								);
							}
						} else {
							// フォールバック
							$fallback = [
								'PACE v3.0 デモ申込み',
								'REBOOT-WORK 資料請求',
								'一般お問い合わせ',
							];
							foreach ( $fallback as $label ) {
								printf(
									'<option value="%s">%s</option>',
									esc_attr( $label ),
									esc_html( $label )
								);
							}
						}
						?>
					</select>
				</div>

				<!-- reCAPTCHA v3 hidden token (JS で自動設定) -->
				<input type="hidden" name="recaptcha_token" id="recaptcha-token" value="">

				<!-- Message -->
				<div class="form-field" id="field-message">
					<label class="form-field__label" for="contact-message">
						<?php _e( 'お問い合わせ内容', 'hachi' ); ?><sup aria-label="必須">必須</sup>
					</label>
					<textarea
						id="contact-message"
						name="contact_message"
						class="form-field__textarea"
						placeholder="<?php esc_attr_e( 'ご質問・ご要望をご記入ください。', 'hachi' ); ?>"
						maxlength="2000"
						rows="7"
						aria-required="true"
						aria-describedby="err-message"
					></textarea>
					<span class="form-field__error" id="err-message" role="alert">
						<?php _e( 'お問い合わせ内容をご入力ください。', 'hachi' ); ?>
					</span>
				</div>

				<p class="form-privacy">
					<?php _e( 'ご入力いただいた個人情報は、お問い合わせへの対応のみに使用し、第三者への提供は行いません。', 'hachi' ); ?>
					<a href="<?php echo esc_url( home_url( '/privacy-policy/' ) ); ?>" style="color:var(--teal)">
						<?php _e( 'プライバシーポリシー', 'hachi' ); ?>
					</a>
				</p>

				<button type="submit" class="btn btn--teal" id="form-submit">
					<span id="submit-text"><?php _e( '送信する', 'hachi' ); ?></span>
					<?php hachi_arrow_icon(); ?>
				</button>

				<!-- General error message -->
				<p id="form-general-error" style="display:none;color:var(--teal);margin-top:16px;font-size:14px" role="alert"></p>

			</form>
		</div>
	</div>
</section>

<?php if ( defined( 'HACHI_RECAPTCHA_SITE_KEY' ) && ! empty( HACHI_RECAPTCHA_SITE_KEY ) ) : ?>
<script>
(function () {
    'use strict';
    var siteKey = <?php echo wp_json_encode( HACHI_RECAPTCHA_SITE_KEY ); ?>;
    var form    = document.getElementById('contact-form');
    var tokenEl = document.getElementById('recaptcha-token');
    if (!form || !tokenEl || !siteKey) return;

    // フォーム送信時に reCAPTCHA v3 トークンを取得してから送信
    form.addEventListener('submit', function (e) {
        // トークンが既に設定済みならスキップ（二重送信防止）
        if (tokenEl.value) return;

        e.preventDefault();
        if (typeof grecaptcha === 'undefined') {
            // grecaptcha が未ロードの場合はそのまま送信（フォールバック）
            form.submit();
            return;
        }
        grecaptcha.ready(function () {
            grecaptcha.execute(siteKey, { action: 'contact' }).then(function (token) {
                tokenEl.value = token;
                // ネイティブ送信ではなく、既存の JS ハンドラーが拾えるよう
                // submit イベントを再トリガー
                var event = new Event('submit', { bubbles: true, cancelable: true });
                form.dispatchEvent(event);
            });
        });
    }, { once: true });
}());
</script>
<?php endif; ?>

<?php get_footer(); ?>
