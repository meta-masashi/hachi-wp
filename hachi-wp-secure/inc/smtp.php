<?php
/**
 * HACHI Theme — inc/smtp.php
 *
 * wp_mail() を SMTP 経由に切り替える軽量モジュール。
 * プラグイン不要。wp-config.php に定数を追加するだけで有効化。
 *
 * ── wp-config.php に追加する設定（4行） ──────────────
 *
 *   define( 'HACHI_SMTP_HOST', '初期ドメイン.sakura.ne.jp' );
 *   define( 'HACHI_SMTP_PORT', 587 );
 *   define( 'HACHI_SMTP_USER', 'info@hachi-wellnesshack.com' );
 *   define( 'HACHI_SMTP_PASS', 'メールアカウントのパスワード' );
 *
 * ── オプション（省略可） ──────────────────────────
 *
 *   define( 'HACHI_SMTP_FROM',      'info@hachi-wellnesshack.com' ); // 省略時は SMTP_USER
 *   define( 'HACHI_SMTP_FROM_NAME', '株式会社HACHI' );               // 省略時は 'HACHI'
 *   define( 'HACHI_SMTP_SECURE',    'tls' );                         // 'tls' | 'ssl' | '' (省略時 tls)
 *   define( 'HACHI_SMTP_DEBUG',     0 );                             // 0=off, 2=verbose (省略時 0)
 *
 * ── 未設定の場合 ────────────────────────────────
 *   何もしない（PHP mail() フォールバック）。既存動作を壊さない。
 *
 * @package HACHI
 */

defined( 'ABSPATH' ) || exit;

/**
 * SMTP が設定されているか
 */
function hachi_smtp_is_configured(): bool {
    return defined( 'HACHI_SMTP_HOST' )
        && defined( 'HACHI_SMTP_USER' )
        && defined( 'HACHI_SMTP_PASS' )
        && ! empty( HACHI_SMTP_HOST )
        && ! empty( HACHI_SMTP_USER )
        && ! empty( HACHI_SMTP_PASS );
}

if ( ! hachi_smtp_is_configured() ) {
    return; // 未設定 → 何もしない
}

/**
 * PHPMailer を SMTP モードに切り替え
 */
add_action( 'phpmailer_init', function ( $phpmailer ): void {

    $phpmailer->isSMTP();
    $phpmailer->Host       = HACHI_SMTP_HOST;
    $phpmailer->Port       = defined( 'HACHI_SMTP_PORT' ) ? (int) HACHI_SMTP_PORT : 587;
    $phpmailer->SMTPAuth   = true;
    $phpmailer->Username   = HACHI_SMTP_USER;
    $phpmailer->Password   = HACHI_SMTP_PASS;
    $phpmailer->SMTPSecure = defined( 'HACHI_SMTP_SECURE' ) ? HACHI_SMTP_SECURE : 'tls';
    $phpmailer->SMTPDebug  = defined( 'HACHI_SMTP_DEBUG' )  ? (int) HACHI_SMTP_DEBUG : 0;
    $phpmailer->CharSet    = 'UTF-8';

    // From（wp_mail ヘッダーで指定されていない場合のデフォルト）
    if ( empty( $phpmailer->From ) || $phpmailer->From === 'wordpress@' . gethostname() ) {
        $phpmailer->From     = defined( 'HACHI_SMTP_FROM' ) ? HACHI_SMTP_FROM : HACHI_SMTP_USER;
        $phpmailer->FromName = defined( 'HACHI_SMTP_FROM_NAME' ) ? HACHI_SMTP_FROM_NAME : 'HACHI';
    }

}, 10 );

/**
 * wp_mail の From / FromName をフィルターで統一
 * （wp_mail のヘッダーで From を指定しても WordPress がデフォルトで上書きするのを防止）
 */
add_filter( 'wp_mail_from', function ( string $email ): string {
    if ( hachi_smtp_is_configured() ) {
        return defined( 'HACHI_SMTP_FROM' ) ? HACHI_SMTP_FROM : HACHI_SMTP_USER;
    }
    return $email;
} );

add_filter( 'wp_mail_from_name', function ( string $name ): string {
    if ( hachi_smtp_is_configured() ) {
        return defined( 'HACHI_SMTP_FROM_NAME' ) ? HACHI_SMTP_FROM_NAME : 'HACHI';
    }
    return $name;
} );
