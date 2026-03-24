<?php
/**
 * HACHI — wp-config.php セキュリティ追加設定
 *
 * ⚠ このファイルの内容を wp-config.php の
 *   "That's all, stop editing!" の行より上に追加してください。
 *
 * @package HACHI
 */

// ────────────────────────────────────────────────────────────
// 1. データベース文字セット / 照合順序
// ────────────────────────────────────────────────────────────
define( 'DB_CHARSET', 'utf8mb4' );
define( 'DB_COLLATE', 'utf8mb4_unicode_ci' );

// ────────────────────────────────────────────────────────────
// 2. データベーステーブルプレフィックス変更
//    デフォルト wp_ から変更 (SQLインジェクション対策)
// ────────────────────────────────────────────────────────────
$table_prefix = 'hachi8_';  // ← ランダムな文字列に変更

// ────────────────────────────────────────────────────────────
// 3. セキュリティキー & ソルト
//    https://api.wordpress.org/secret-key/1.1/salt/ で生成
// ────────────────────────────────────────────────────────────
define( 'AUTH_KEY',         'put your unique phrase here' );
define( 'SECURE_AUTH_KEY',  'put your unique phrase here' );
define( 'LOGGED_IN_KEY',    'put your unique phrase here' );
define( 'NONCE_KEY',        'put your unique phrase here' );
define( 'AUTH_SALT',        'put your unique phrase here' );
define( 'SECURE_AUTH_SALT', 'put your unique phrase here' );
define( 'LOGGED_IN_SALT',   'put your unique phrase here' );
define( 'NONCE_SALT',       'put your unique phrase here' );

// ────────────────────────────────────────────────────────────
// 4. HTTPS 強制
// ────────────────────────────────────────────────────────────
define( 'FORCE_SSL_ADMIN', true );
define( 'FORCE_SSL_LOGIN', true );

// ────────────────────────────────────────────────────────────
// 5. ファイル編集・変更の無効化
// ────────────────────────────────────────────────────────────
define( 'DISALLOW_FILE_EDIT',  true ); // テーマ・プラグインエディタ無効化
define( 'DISALLOW_FILE_MODS',  true ); // プラグイン・テーマのインストール/更新を無効化 (本番のみ)

// ────────────────────────────────────────────────────────────
// 6. デバッグ設定 (本番環境では全て false)
// ────────────────────────────────────────────────────────────
define( 'WP_DEBUG',         false );
define( 'WP_DEBUG_LOG',     false );  // エラーをファイルに記録しない
define( 'WP_DEBUG_DISPLAY', false );  // エラーを画面に表示しない
define( 'SCRIPT_DEBUG',     false );
define( 'SAVEQUERIES',      false );  // クエリキャッシュ無効化
ini_set( 'display_errors', '0' );     // PHPエラー非表示

// ────────────────────────────────────────────────────────────
// 7. 自動更新設定
// ────────────────────────────────────────────────────────────
define( 'AUTOMATIC_UPDATER_DISABLED', false ); // 自動更新は有効推奨
define( 'WP_AUTO_UPDATE_CORE',        'minor' ); // マイナーアップデートのみ自動適用

// ────────────────────────────────────────────────────────────
// 8. リビジョン・ゴミ箱の制限
// ────────────────────────────────────────────────────────────
define( 'WP_POST_REVISIONS', 5 );  // リビジョン保存数を制限
define( 'EMPTY_TRASH_DAYS',  7 );  // 7日でゴミ箱を空に

// ────────────────────────────────────────────────────────────
// 9. wp-cron の無効化 (サーバーcronを使用)
//    サーバー側で: */5 * * * * curl -s https://hachi.co.jp/wp-cron.php
// ────────────────────────────────────────────────────────────
define( 'DISABLE_WP_CRON', true );

// ────────────────────────────────────────────────────────────
// 10. メモリ制限
// ────────────────────────────────────────────────────────────
define( 'WP_MEMORY_LIMIT',       '256M' );
define( 'WP_MAX_MEMORY_LIMIT',   '512M' );

// ────────────────────────────────────────────────────────────
// 11. Cookie パス・ドメイン
// ────────────────────────────────────────────────────────────
define( 'COOKIE_DOMAIN',   'hachi.co.jp' );
define( 'COOKIEPATH',      '/' );
define( 'SITECOOKIEPATH',  '/' );
define( 'ADMIN_COOKIE_PATH', '/wp-admin' );

// ────────────────────────────────────────────────────────────
// 12. セキュリティログの有効化 (inc/security.php で使用)
// ────────────────────────────────────────────────────────────
define( 'HACHI_SECURITY_LOG', true );

// ────────────────────────────────────────────────────────────
// 13. WordPress アドレスとサイトアドレスの固定
//     (wp-options テーブル書き換えによる乗っ取り防止)
// ────────────────────────────────────────────────────────────
define( 'WP_SITEURL', 'https://hachi.co.jp' );
define( 'WP_HOME',    'https://hachi.co.jp' );

// ────────────────────────────────────────────────────────────
// 14. wp-content ディレクトリのカスタムパス
//     (デフォルトパスを変更して攻撃者に場所を隠す)
// ────────────────────────────────────────────────────────────
// define( 'WP_CONTENT_DIR', dirname( __FILE__ ) . '/app' );
// define( 'WP_CONTENT_URL', 'https://hachi.co.jp/app' );

// ────────────────────────────────────────────────────────────
// 15. アップロードサイズ制限
// ────────────────────────────────────────────────────────────
// ※ php.ini / .htaccess での設定を推奨
// @ini_set( 'upload_max_filesize', '10M' );
// @ini_set( 'post_max_size',       '12M' );

// ────────────────────────────────────────────────────────────
// 16. 環境識別
//     HACHI_ENV: 'production' | 'staging' | 'development'
// ────────────────────────────────────────────────────────────
define( 'HACHI_ENV', getenv( 'HACHI_ENV' ) ?: 'production' );

// ────────────────────────────────────────────────────────────
// 17. Google reCAPTCHA v3
//     サーバーサイド検証用シークレットキー
//     本番値は環境変数 RECAPTCHA_SECRET_KEY で渡すことを推奨。
//     フォールバックとしてここに直接記述も可（.gitignore 必須）。
// ────────────────────────────────────────────────────────────
define( 'HACHI_RECAPTCHA_SECRET_KEY', getenv( 'RECAPTCHA_SECRET_KEY' ) ?: '' );
// サイトキー（フロントエンド用、公開しても問題ない）
define( 'HACHI_RECAPTCHA_SITE_KEY', getenv( 'RECAPTCHA_SITE_KEY' ) ?: '' );

// ────────────────────────────────────────────────────────────
// 18. Slack Webhook URL
//     お問い合わせ通知用。環境変数 SLACK_WEBHOOK_URL で渡す。
// ────────────────────────────────────────────────────────────
define( 'HACHI_SLACK_WEBHOOK_URL', getenv( 'SLACK_WEBHOOK_URL' ) ?: '' );

// ────────────────────────────────────────────────────────────
// 19. お問い合わせ通知先メールアドレス
//     未設定時は admin_email (wp_options) を使用。
// ────────────────────────────────────────────────────────────
define( 'HACHI_CONTACT_TO_EMAIL', getenv( 'CONTACT_FORM_TO_EMAIL' ) ?: '' );

// ────────────────────────────────────────────────────────────
// 20. Supabase 接続設定
//
//     SUPABASE_URL         : Supabase プロジェクト URL
//                           例: https://xyzcompany.supabase.co
//     SUPABASE_SERVICE_KEY : service_role JWT キー
//                           ⚠ サーバーサイド専用・絶対に公開しないこと
//                           ⚠ anon キー (公開鍵) ではなく service_role を使用
//
//     設定方法:
//       本番: サーバーの環境変数に設定 (例: /etc/environment または .env)
//       開発: wp-config.php に直接記述 (.gitignore に wp-config.php を追加)
//
//     Supabase ダッシュボード > Settings > API でキーを確認できます。
// ────────────────────────────────────────────────────────────
define( 'HACHI_SUPABASE_URL',         getenv( 'SUPABASE_URL' )         ?: 'https://pprmtzvzztmdatqdwwqs.supabase.co' );
define( 'HACHI_SUPABASE_SERVICE_KEY', getenv( 'SUPABASE_SERVICE_KEY' ) ?: 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJzdXBhYmFzZSIsInJlZiI6InBwcm10enZ6enRtZGF0cWR3d3FzIiwicm9sZSI6InNlcnZpY2Vfcm9sZSIsImlhdCI6MTc3NDM2MTkzMCwiZXhwIjoyMDg5OTM3OTMwfQ.D4C4BP6NrIGxiRs-lwIeQ76V9oLj4TPmo6D3X5_7cbc' );

// ────────────────────────────────────────────────────────────
// 20. セッションセキュリティ強化
//     PHP セッション Cookie を Secure + HttpOnly + SameSite=Strict に設定
// ────────────────────────────────────────────────────────────
if ( PHP_SESSION_NONE === session_status() ) {
    ini_set( 'session.cookie_secure',   '1'      );
    ini_set( 'session.cookie_httponly', '1'      );
    ini_set( 'session.cookie_samesite', 'Strict' );
    ini_set( 'session.use_strict_mode', '1'      );
}

// ────────────────────────────────────────────────────────────
// 21. 本番環境の追加ハードニング
// ────────────────────────────────────────────────────────────
if ( defined( 'HACHI_ENV' ) && HACHI_ENV === 'production' ) {

    // エラー出力を完全に抑制（再定義防止）
    if ( ! defined( 'WP_DEBUG' ) ) {
        define( 'WP_DEBUG', false );
    }

    // CONCATENATE_SCRIPTS: JS を結合してリクエスト数を削減
    if ( ! defined( 'CONCATENATE_SCRIPTS' ) ) {
        define( 'CONCATENATE_SCRIPTS', true );
    }

    // REST API の名前空間露出を最小限に
    if ( ! defined( 'HACHI_REST_EXPOSE_NAMESPACE' ) ) {
        define( 'HACHI_REST_EXPOSE_NAMESPACE', false );
    }
}
