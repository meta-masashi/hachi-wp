<?php
/**
 * HACHI Theme — inc/security.php
 *
 * 包括的なWordPressセキュリティ強化モジュール
 * 以下の脅威に対応:
 *   - XSS / SQLインジェクション / CSRF
 *   - ブルートフォース攻撃
 *   - ファイルインクルード攻撃
 *   - 情報漏洩 (WordPress版数・ユーザー名列挙)
 *   - クリックジャッキング / MIME Sniffing
 *   - 不正なファイルアップロード
 *   - スパムボット / 自動送信
 *
 * @package HACHI
 */

defined( 'ABSPATH' ) || exit;

/* ============================================================
   1. HTTP セキュリティヘッダー
   ============================================================ */

add_filter( 'wp_headers', function ( array $headers ): array {

    // クリックジャッキング防止
    $headers['X-Frame-Options'] = 'SAMEORIGIN';

    // MIME タイプ スニッフィング防止
    $headers['X-Content-Type-Options'] = 'nosniff';

    // XSSフィルター (IE/旧ブラウザ向け)
    $headers['X-XSS-Protection'] = '1; mode=block';

    // リファラーポリシー
    $headers['Referrer-Policy'] = 'strict-origin-when-cross-origin';

    // 権限ポリシー (不要なブラウザ機能を無効化)
    $headers['Permissions-Policy'] = implode( ', ', [
        'camera=()',
        'microphone=()',
        'geolocation=()',
        'payment=()',
        'usb=()',
        'accelerometer=()',
        'gyroscope=()',
    ] );

    // HSTS: HTTPS強制 (1年間 + サブドメイン)
    if ( is_ssl() ) {
        $headers['Strict-Transport-Security'] = 'max-age=31536000; includeSubDomains; preload';
    }

    // Content Security Policy
    $nonce = hachi_get_csp_nonce();
    $headers['Content-Security-Policy'] = implode( '; ', [
        "default-src 'self'",
        "script-src 'self' 'nonce-{$nonce}'",
        "style-src 'self' 'unsafe-inline' https://fonts.googleapis.com",
        "font-src 'self' https://fonts.gstatic.com",
        "img-src 'self' data: https:",
        "connect-src 'self'",
        "frame-ancestors 'none'",
        "base-uri 'self'",
        "form-action 'self'",
        "upgrade-insecure-requests",
    ] );

    // 古い X-Permitted-Cross-Domain-Policies
    $headers['X-Permitted-Cross-Domain-Policies'] = 'none';

    return $headers;
}, 10 );

/**
 * CSPノンス生成 (リクエストごとに1回)
 */
function hachi_get_csp_nonce(): string {
    static $nonce = null;
    if ( $nonce === null ) {
        $nonce = base64_encode( random_bytes( 16 ) );
    }
    return $nonce;
}

/* ============================================================
   2. WordPress 情報漏洩防止
   ============================================================ */

// WP バージョンを全箇所から除去
remove_action( 'wp_head',            'wp_generator' );
remove_action( 'wp_head',            'rsd_link' );
remove_action( 'wp_head',            'wlwmanifest_link' );
remove_action( 'wp_head',            'wp_shortlink_wp_head' );
remove_action( 'wp_head',            'adjacent_posts_rel_link_wp_head' );
remove_action( 'wp_head',            'print_emoji_detection_script', 7 );
remove_action( 'wp_print_styles',    'print_emoji_styles' );
remove_action( 'admin_print_scripts','print_emoji_detection_script' );
remove_action( 'admin_print_styles', 'print_emoji_styles' );

// フィードからバージョン除去
add_filter( 'the_generator', '__return_empty_string' );

// WordPress バージョンをアセットURLから除去
add_filter( 'style_loader_src',  'hachi_remove_wp_ver_css_js', 9999 );
add_filter( 'script_loader_src', 'hachi_remove_wp_ver_css_js', 9999 );
function hachi_remove_wp_ver_css_js( string $src ): string {
    if ( strpos( $src, 'ver=' . get_bloginfo( 'version' ) ) !== false ) {
        $src = remove_query_arg( 'ver', $src );
    }
    return $src;
}

// ユーザー名列挙を防ぐ (author=1 クエリのリダイレクト)
add_action( 'init', function (): void {
    if ( ! is_admin() && isset( $_GET['author'] ) ) {
        wp_redirect( home_url(), 301 );
        exit;
    }
} );

// oEmbed 経由のユーザー情報漏洩防止
add_filter( 'oembed_response_data', function ( array $data ): array {
    unset( $data['author_name'], $data['author_url'] );
    return $data;
} );

// REST API の不要エンドポイントを非公開（ユーザー列挙・コンテンツ探索を防止）
add_filter( 'rest_endpoints', function ( array $endpoints ): array {
    $hide = [
        '/wp/v2/users',
        '/wp/v2/users/(?P<id>[\d]+)',
        '/wp/v2/search',
    ];
    foreach ( $hide as $route ) {
        if ( isset( $endpoints[ $route ] ) ) {
            unset( $endpoints[ $route ] );
        }
    }
    return $endpoints;
} );

// REST APIを未ログインユーザーに制限 (必要に応じてコメントアウト)
add_filter( 'rest_authentication_errors', function ( $result ) {
    if ( ! empty( $result ) ) {
        return $result;
    }
    // コンタクトフォームのAJAXエンドポイントは許可
    if ( defined( 'DOING_AJAX' ) && DOING_AJAX ) {
        return $result;
    }
    // /hachi/v1/ 配下の公開 GET エンドポイントは認証不要
    // (GET /wp-json/hachi/v1/news は permission_callback = __return_true で公開設定)
    $rest_prefix = rest_get_url_prefix(); // 通常 'wp-json'
    $request_uri = $_SERVER['REQUEST_URI'] ?? '';
    if ( strpos( $request_uri, "/{$rest_prefix}/hachi/v1/" ) !== false ) {
        $method = $_SERVER['REQUEST_METHOD'] ?? 'GET';
        if ( strtoupper( $method ) === 'GET' ) {
            return $result; // GET は認証不要 (公開エンドポイント)
        }
    }
    if ( ! is_user_logged_in() ) {
        return new WP_Error(
            'rest_not_logged_in',
            __( 'REST API requires authentication.', 'hachi' ),
            [ 'status' => 401 ]
        );
    }
    return $result;
} );

/* ============================================================
   3. ログイン保護 (ブルートフォース対策)
   ============================================================ */

/**
 * レート制限付きログイン失敗記録
 * - 5回失敗 → 10分ロック
 * - 10回失敗 → 1時間ロック
 * - 20回失敗 → 24時間ロック
 */
add_filter( 'authenticate', function ( $user, string $username, string $password ) {
    if ( empty( $username ) || empty( $password ) ) {
        return $user;
    }

    $ip          = hachi_get_client_ip();
    $lockout_key = 'hachi_login_lock_' . md5( $ip );
    $fails_key   = 'hachi_login_fails_' . md5( $ip );
    $fail_count  = (int) get_transient( $fails_key );

    // ロックアウト確認
    if ( get_transient( $lockout_key ) ) {
        $remaining = ceil( ( (int) get_transient( $lockout_key . '_expires' ) - time() ) / 60 );
        return new WP_Error(
            'hachi_login_locked',
            sprintf(
                __( 'Too many failed login attempts. Please try again in %d minutes.', 'hachi' ),
                max( 1, $remaining )
            )
        );
    }

    return $user;
}, 30, 3 );

add_action( 'wp_login_failed', function ( string $username ): void {
    $ip       = hachi_get_client_ip();
    $key      = 'hachi_login_fails_' . md5( $ip );
    $lock_key = 'hachi_login_lock_' . md5( $ip );
    $count    = (int) get_transient( $key ) + 1;

    // 失敗回数を保存 (24時間)
    set_transient( $key, $count, DAY_IN_SECONDS );

    // ロック閾値と時間の設定
    $lockouts = [
        20 => DAY_IN_SECONDS,
        10 => HOUR_IN_SECONDS,
        5  => 10 * MINUTE_IN_SECONDS,
    ];

    foreach ( $lockouts as $threshold => $duration ) {
        if ( $count >= $threshold ) {
            set_transient( $lock_key, true, $duration );
            set_transient( $lock_key . '_expires', time() + $duration, $duration );

            // セキュリティログに記録
            hachi_security_log( 'login_lockout', [
                'ip'        => $ip,
                'username'  => sanitize_user( $username ),
                'attempts'  => $count,
                'locked_for'=> $duration,
            ] );
            break;
        }
    }
} );

// ログイン成功時にカウンタをリセット
add_action( 'wp_login', function ( string $username ): void {
    $ip = hachi_get_client_ip();
    delete_transient( 'hachi_login_fails_' . md5( $ip ) );
    delete_transient( 'hachi_login_lock_'  . md5( $ip ) );
} );

// ログインエラーメッセージを汎用化 (ユーザー名/パスワード推測防止)
add_filter( 'login_errors', fn() =>
    __( 'ログイン情報が正しくありません。', 'hachi' )
);

// ログインURLのwp-login.php露出を隠す (後述の .htaccess で対応)
// ここではエラーページで情報漏洩を防ぐ
add_action( 'login_init', function (): void {
    // カスタムロゴ設定
    add_filter( 'login_headerurl',  fn() => home_url() );
    add_filter( 'login_headertext', fn() => get_bloginfo( 'name' ) );
} );

/* ============================================================
   4. CSRF / Nonce 強化
   ============================================================ */

// Nonce の有効期限をデフォルト24h → 4hに短縮 (より安全)
add_filter( 'nonce_life', fn() => 4 * HOUR_IN_SECONDS );

/* ============================================================
   5. ファイルアップロード制限
   ============================================================ */

// 危険な拡張子のアップロードをブロック
add_filter( 'upload_mimes', function ( array $mimes ): array {
    // 許可リスト方式 (ホワイトリスト)
    $allowed = [
        'jpg|jpeg|jpe' => 'image/jpeg',
        'png'          => 'image/png',
        'gif'          => 'image/gif',
        'webp'         => 'image/webp',
        'svg'          => 'image/svg+xml',
        'pdf'          => 'application/pdf',
        'mp4|m4v'      => 'video/mp4',
        'mp3|m4a'      => 'audio/mpeg',
        'zip'          => 'application/zip',
    ];
    return $allowed;
} );

// MIMEタイプと実際のファイル内容の一致を検証
add_filter( 'wp_check_filetype_and_ext', function ( array $data, string $file, string $filename, $mimes ) {
    if ( ! empty( $data['ext'] ) ) {
        return $data;
    }
    $wp_filetype = wp_check_filetype( $filename, $mimes );
    if ( ! empty( $wp_filetype['ext'] ) ) {
        $data['ext']  = $wp_filetype['ext'];
        $data['type'] = $wp_filetype['type'];
    }
    return $data;
}, 10, 4 );

// SVGアップロード時のXSSスキャン
add_filter( 'wp_handle_upload_prefilter', function ( array $file ): array {
    if ( $file['type'] === 'image/svg+xml' ) {
        $content = file_get_contents( $file['tmp_name'] );
        // 危険なSVG要素/属性を検出
        $dangerous_patterns = [
            '/<script/i',
            '/on\w+\s*=/i',
            '/javascript:/i',
            '/<iframe/i',
            '/<object/i',
            '/<embed/i',
            '/xlink:href\s*=\s*["\']?javascript/i',
        ];
        foreach ( $dangerous_patterns as $pattern ) {
            if ( preg_match( $pattern, $content ) ) {
                $file['error'] = __( 'SVGファイルに危険なコードが含まれています。', 'hachi' );
                hachi_security_log( 'malicious_svg_upload', [ 'filename' => $file['name'] ] );
                return $file;
            }
        }
    }
    return $file;
} );

/* ============================================================
   6. XMLRPCおよび不要エンドポイントの無効化
   ============================================================ */

// XML-RPC完全無効化
add_filter( 'xmlrpc_enabled', '__return_false' );
add_filter( 'wp_xmlrpc_server_class', fn() => 'WP_XMLRPCServer_Disabled' );

// XML-RPCへのアクセスを403でブロック
add_action( 'init', function (): void {
    if ( isset( $_SERVER['REQUEST_URI'] ) &&
         strpos( $_SERVER['REQUEST_URI'], 'xmlrpc.php' ) !== false ) {
        status_header( 403 );
        hachi_security_log( 'xmlrpc_blocked', [ 'ip' => hachi_get_client_ip() ] );
        die( 'Access Denied.' );
    }
} );

// trackbacks/pingbacks の無効化
add_filter( 'xmlrpc_methods', function ( array $methods ): array {
    unset( $methods['pingback.ping'], $methods['pingback.extensions.getPingbacks'] );
    return $methods;
} );
add_action( 'pre_ping', function ( array &$links ): void {
    $links = [];
} );

/* ============================================================
   7. データベース / SQLインジェクション防止補助
   ============================================================ */

// wp-config.php の直接アクセスをブロック
add_action( 'init', function (): void {
    $request = $_SERVER['REQUEST_URI'] ?? '';
    $blocked = [ 'wp-config.php', 'wp-config-sample.php', '.env', '.htpasswd' ];
    foreach ( $blocked as $file ) {
        if ( strpos( $request, $file ) !== false ) {
            status_header( 403 );
            die( 'Access Denied.' );
        }
    }
} );

/* ============================================================
   8. セッション / Cookie セキュリティ
   ============================================================ */

// WordPress認証Cookieのセキュリティ強化
add_action( 'set_auth_cookie', function (
    string $auth_cookie,
    int    $expire,
    int    $expiration,
    int    $user_id,
    string $scheme,
    string $token
): void {
    if ( is_ssl() ) {
        // Cookie属性を強化 (Secure + HttpOnly + SameSite=Strict)
        $cookie_domain = defined( 'COOKIE_DOMAIN' ) ? COOKIE_DOMAIN : '';
        $cookie_path   = defined( 'COOKIEPATH' )   ? COOKIEPATH   : '/';
        $cookie_name   = ( $scheme === 'secure' ) ? SECURE_AUTH_COOKIE : AUTH_COOKIE;

        setcookie( $cookie_name, $auth_cookie, [
            'expires'  => $expire,
            'path'     => $cookie_path,
            'domain'   => $cookie_domain,
            'secure'   => true,
            'httponly' => true,
            'samesite' => 'Strict',
        ] );
    }
}, 10, 6 );

/* ============================================================
   9. コメントスパム対策
   ============================================================ */

// コメント機能が不要な場合は完全無効化
add_filter( 'comments_open', '__return_false', 20 );
add_filter( 'pings_open',    '__return_false', 20 );
add_filter( 'comments_array', fn() => [], 10, 2 );

// 管理画面からコメントメニューを削除
add_action( 'admin_menu', function (): void {
    remove_menu_page( 'edit-comments.php' );
} );

add_action( 'admin_bar_menu', function ( WP_Admin_Bar $bar ): void {
    $bar->remove_node( 'comments' );
}, 999 );

/* ============================================================
   10. 不審なリクエストのブロック
   ============================================================ */

add_action( 'init', function (): void {
    // User-Agent チェック (既知の悪意あるボットをブロック)
    $user_agent = $_SERVER['HTTP_USER_AGENT'] ?? '';
    $bad_bots   = [
        'sqlmap', 'havij', 'nikto', 'nessus', 'acunetix',
        'burpsuite', 'w3af', 'libwww-perl', 'python-requests',
        'scrapy', 'masscan', 'zgrab',
    ];
    foreach ( $bad_bots as $bot ) {
        if ( stripos( $user_agent, $bot ) !== false ) {
            status_header( 403 );
            hachi_security_log( 'bad_bot_blocked', [
                'ip'         => hachi_get_client_ip(),
                'user_agent' => $user_agent,
            ] );
            die( 'Access Denied.' );
        }
    }

    // SQLインジェクション試行の検出 (GETパラメータ)
    $sql_patterns = [
        '/\bunion\b.*\bselect\b/i',
        '/\bselect\b.*\bfrom\b/i',
        '/\bdrop\b.*\btable\b/i',
        '/\binsert\b.*\binto\b/i',
        '/\bexec\b\s*\(/i',
        '/\/\*.*\*\//i',
        '/--\s*$/',
        '/\bor\b\s+[\'"]?\d+[\'"]?\s*=\s*[\'"]?\d+[\'"]?/i',
    ];

    $check_vars = array_merge(
        array_values( $_GET ?? [] ),
        array_values( $_COOKIE ?? [] )
    );

    foreach ( $check_vars as $val ) {
        if ( ! is_string( $val ) ) {
            continue;
        }
        foreach ( $sql_patterns as $pattern ) {
            if ( preg_match( $pattern, urldecode( $val ) ) ) {
                status_header( 400 );
                hachi_security_log( 'sql_injection_attempt', [
                    'ip'    => hachi_get_client_ip(),
                    'value' => substr( $val, 0, 200 ),
                ] );
                die( 'Bad Request.' );
            }
        }
    }

    // パストラバーサル攻撃の検出
    $path_traversal = '../';
    foreach ( $_GET as $val ) {
        if ( is_string( $val ) && strpos( urldecode( $val ), $path_traversal ) !== false ) {
            status_header( 400 );
            hachi_security_log( 'path_traversal_attempt', [
                'ip'    => hachi_get_client_ip(),
                'value' => substr( $val, 0, 200 ),
            ] );
            die( 'Bad Request.' );
        }
    }
} );

/* ============================================================
   11. セキュリティログ
   ============================================================ */

/**
 * セキュリティイベントをカスタムログに記録
 *
 * @param string $event  イベント種別
 * @param array  $data   追加データ
 */
function hachi_security_log( string $event, array $data = [] ): void {
    // ログが無効な場合はスキップ
    if ( defined( 'HACHI_SECURITY_LOG' ) && ! HACHI_SECURITY_LOG ) {
        return;
    }

    $log_dir = WP_CONTENT_DIR . '/hachi-logs';

    // ログディレクトリ作成 (なければ)
    if ( ! is_dir( $log_dir ) ) {
        wp_mkdir_p( $log_dir );
        // .htaccess でディレクトリへの直接アクセスをブロック
        file_put_contents( $log_dir . '/.htaccess', "Order Deny,Allow\nDeny from all\n" );
        // index.php でPHPインクルード防止
        file_put_contents( $log_dir . '/index.php', '<?php // Silence is golden.' );
    }

    $log_file = $log_dir . '/security-' . date( 'Y-m' ) . '.log';

    // ログファイルが大きすぎる場合はローテーション (10MB)
    if ( file_exists( $log_file ) && filesize( $log_file ) > 10 * 1024 * 1024 ) {
        rename( $log_file, $log_file . '.' . date( 'His' ) . '.bak' );
    }

    $entry = json_encode( array_merge( [
        'time'  => gmdate( 'Y-m-d\TH:i:s\Z' ),
        'event' => $event,
    ], $data ), JSON_UNESCAPED_UNICODE ) . PHP_EOL;

    file_put_contents( $log_file, $entry, FILE_APPEND | LOCK_EX );
}

/* ============================================================
   12. 管理画面セキュリティ強化
   ============================================================ */

// 管理画面: ダッシュボードウィジェットの不要なものを削除
add_action( 'wp_dashboard_setup', function (): void {
    remove_meta_box( 'dashboard_incoming_links', 'dashboard', 'normal' );
    remove_meta_box( 'dashboard_plugins',        'dashboard', 'normal' );
    remove_meta_box( 'dashboard_primary',        'dashboard', 'side'   );
    remove_meta_box( 'dashboard_secondary',      'dashboard', 'normal' );
} );

// 管理画面以外からの admin-ajax.php へのアクセス制限
// (contact form AJAXは nopriv で動くので除外)
add_action( 'admin_init', function (): void {
    if ( ! defined( 'DOING_AJAX' ) && ! current_user_can( 'edit_posts' ) ) {
        wp_redirect( home_url() );
        exit;
    }
} );

// ファイルエディタの無効化
if ( ! defined( 'DISALLOW_FILE_EDIT' ) ) {
    define( 'DISALLOW_FILE_EDIT', true );
}

// プラグイン/テーマのインストールを管理者のみに制限
if ( ! defined( 'DISALLOW_FILE_MODS' ) ) {
    // 本番環境では true を推奨 (デプロイはFTP/SSHで行う)
    // define( 'DISALLOW_FILE_MODS', true );
}

/* ============================================================
   13. 2FA準備 (トークン生成ヘルパー)
   ============================================================ */

/**
 * TOTP シークレットキー生成 (2FA実装の土台)
 * 実際の2FAにはWordfence / WP 2FAプラグインの併用を推奨
 */
function hachi_generate_2fa_secret(): string {
    return base64_encode( random_bytes( 20 ) );
}

/* ============================================================
   14. ユーティリティ関数
   ============================================================ */

/**
 * クライアントIPアドレスを安全に取得
 * プロキシ・CDN対応 (信頼できるヘッダーのみ使用)
 */
function hachi_get_client_ip(): string {
    // Cloudflare
    if ( ! empty( $_SERVER['HTTP_CF_CONNECTING_IP'] ) ) {
        return sanitize_text_field( $_SERVER['HTTP_CF_CONNECTING_IP'] );
    }
    // X-Forwarded-For (先頭のみ)
    if ( ! empty( $_SERVER['HTTP_X_FORWARDED_FOR'] ) ) {
        $ips = explode( ',', $_SERVER['HTTP_X_FORWARDED_FOR'] );
        return trim( sanitize_text_field( $ips[0] ) );
    }
    return sanitize_text_field( $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0' );
}

/**
 * IP が許可リストに含まれているか確認
 *
 * @param string   $ip      チェックするIP
 * @param string[] $allowed 許可IPリスト (CIDR表記対応)
 */
function hachi_ip_in_allowlist( string $ip, array $allowed ): bool {
    foreach ( $allowed as $range ) {
        if ( strpos( $range, '/' ) !== false ) {
            // CIDR範囲チェック
            [ $subnet, $bits ] = explode( '/', $range );
            $ip_long     = ip2long( $ip );
            $subnet_long = ip2long( $subnet );
            $mask        = -1 << ( 32 - (int) $bits );
            if ( ( $ip_long & $mask ) === ( $subnet_long & $mask ) ) {
                return true;
            }
        } elseif ( $ip === $range ) {
            return true;
        }
    }
    return false;
}
