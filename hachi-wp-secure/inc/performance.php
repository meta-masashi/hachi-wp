<?php
/**
 * HACHI Theme — inc/performance.php
 *
 * Core Web Vitals / Lighthouse スコア向上施策
 *   - ヒーロー画像 LCP preload ヒント
 *   - 画像 width / height 属性強制（CLS 防止）
 *   - メインスレッドブロッキングスクリプトの defer 化（FID 改善）
 *   - 不要な WordPress クエリストリング除去
 *   - Gravatar 無効化（プライバシー + パフォーマンス）
 *   - フォント表示最適化（font-display: swap）
 *   - DNS prefetch: Google Fonts / GTM / GA4 / reCAPTCHA
 *
 * @package HACHI
 */

defined( 'ABSPATH' ) || exit;

/* ============================================================
   1. LCP 最適化: ヒーロー画像の preload ヒント
   ============================================================ */

add_action( 'wp_head', 'hachi_preload_lcp_resources', 1 );

function hachi_preload_lcp_resources(): void {
    // ヒーロー画像 preload は不要(コード化 SVG インライン化により 404 解消済み)

    // フォントのプリロード（LCP テキストの FOIT 防止）
    $font_urls = [
        'https://fonts.gstatic.com/s/notosansjp/v52/-F6jfjtqLzI2JPCgQBnw7HFyzSD-AsregP8VFBEi75vY0rw-oME.woff2',
        'https://fonts.gstatic.com/s/montserrat/v29/JTUHjIg1_i6t8kCHKm4532VJOt5-QNFgpCuM73w5aXp-obK4.woff2',
    ];
    foreach ( $font_urls as $url ) {
        printf(
            '<link rel="preload" as="font" type="font/woff2" href="%s" crossorigin="anonymous">' . "\n",
            esc_url( $url )
        );
    }
}

/* ============================================================
   2. DNS Prefetch / Preconnect
   ============================================================ */

add_action( 'wp_head', 'hachi_dns_prefetch', 1 );

function hachi_dns_prefetch(): void {
    $preconnect = [
        'https://fonts.googleapis.com',
        'https://fonts.gstatic.com',
    ];

    $prefetch = [
        'https://www.googletagmanager.com',
        'https://www.google-analytics.com',
        'https://analytics.google.com',
        'https://www.google.com', // reCAPTCHA
        'https://www.gstatic.com', // reCAPTCHA
    ];

    foreach ( $preconnect as $url ) {
        printf( '<link rel="preconnect" href="%s" crossorigin>' . "\n", esc_url( $url ) );
    }
    foreach ( $prefetch as $url ) {
        printf( '<link rel="dns-prefetch" href="%s">' . "\n", esc_url( $url ) );
    }
}

/* ============================================================
   3. CLS 防止: 画像に width / height 属性を強制する filter
   ============================================================ */

add_filter( 'the_content', 'hachi_force_image_dimensions', 10 );
add_filter( 'post_thumbnail_html', 'hachi_force_image_dimensions', 10 );

function hachi_force_image_dimensions( string $html ): string {
    if ( empty( $html ) ) {
        return $html;
    }

    // width / height が両方ない img タグを対象に処理
    return preg_replace_callback(
        '/<img([^>]+)>/i',
        function ( array $matches ): string {
            $tag   = $matches[0];
            $attrs = $matches[1];

            // すでに width と height が両方設定されている場合はスキップ
            $has_width  = preg_match( '/\bwidth\s*=/i', $attrs );
            $has_height = preg_match( '/\bheight\s*=/i', $attrs );
            if ( $has_width && $has_height ) {
                return $tag;
            }

            // src を抽出して画像サイズを取得
            if ( ! preg_match( '/\bsrc\s*=\s*["\']([^"\']+)["\']/', $attrs, $src_match ) ) {
                return $tag;
            }

            $src = $src_match[1];

            // 外部 URL はスキップ（パフォーマンス上 getimagesize は不可）
            if ( strpos( $src, home_url() ) === false && strpos( $src, '/' ) !== 0 ) {
                return $tag;
            }

            // WordPress メディアライブラリの attachment ID から取得
            $attachment_id = attachment_url_to_postid( $src );
            if ( $attachment_id ) {
                $meta = wp_get_attachment_metadata( $attachment_id );
                if ( $meta && isset( $meta['width'], $meta['height'] ) ) {
                    // width / height を注入
                    if ( ! $has_width ) {
                        $attrs .= ' width="' . (int) $meta['width'] . '"';
                    }
                    if ( ! $has_height ) {
                        $attrs .= ' height="' . (int) $meta['height'] . '"';
                    }
                    return '<img' . $attrs . '>';
                }
            }

            return $tag;
        },
        $html
    );
}

/* ============================================================
   4. FID 改善: スクリプトの defer 化
   ============================================================ */

add_filter( 'script_loader_tag', 'hachi_defer_scripts', 10, 3 );

function hachi_defer_scripts( string $tag, string $handle, string $src ): string {
    // 管理画面・ログイン画面では適用しない
    if ( is_admin() || $GLOBALS['pagenow'] === 'wp-login.php' ) {
        return $tag;
    }

    // defer 対象のハンドル（フッターに出力されないスクリプトのみ）
    $defer_handles = [
        'hachi-main',
        'google-recaptcha',
    ];

    if ( in_array( $handle, $defer_handles, true ) ) {
        // すでに defer / async が付いている場合はスキップ
        if ( strpos( $tag, ' defer' ) !== false || strpos( $tag, ' async' ) !== false ) {
            return $tag;
        }
        $tag = str_replace( ' src=', ' defer src=', $tag );
    }

    return $tag;
}

/* ============================================================
   5. 不要な WordPress クエリストリング除去（キャッシュ効率改善）
   ============================================================ */

add_filter( 'style_loader_src',  'hachi_remove_query_strings', 15 );
add_filter( 'script_loader_src', 'hachi_remove_query_strings', 15 );

function hachi_remove_query_strings( string $src ): string {
    // 外部 CDN の URL はそのまま（Google Fonts 等）
    if ( strpos( $src, home_url() ) === false ) {
        return $src;
    }

    // テーマ独自アセットはバージョンバスターを維持（キャッシュ破棄に必要）
    if ( strpos( $src, '/themes/hachi-wp-secure/' ) !== false ) {
        return $src;
    }

    // ?ver= のみのクエリを除去（WP コア / プラグインのデフォルト ver）
    if ( strpos( $src, '?ver=' ) !== false ) {
        $src = remove_query_arg( 'ver', $src );
    }

    return $src;
}

/* ============================================================
   6. Gravatar 無効化（プライバシー保護 + DNS ルックアップ削減）
   ============================================================ */

add_filter( 'get_avatar', '__return_empty_string' );
add_filter( 'get_avatar_url', fn() => '' );

// コメントアバター無効化
add_filter( 'show_avatars', '__return_false' );

/* ============================================================
   7. フォント表示最適化（font-display: swap）
   ============================================================ */

add_filter( 'style_loader_tag', 'hachi_font_display_swap', 10, 4 );

function hachi_font_display_swap( string $tag, string $handle, string $href, string $media ): string {
    // Google Fonts URL にのみ適用
    if ( $handle !== 'hachi-fonts' || strpos( $href, 'fonts.googleapis.com' ) === false ) {
        return $tag;
    }

    // &display=swap が未設定の場合は追加
    if ( strpos( $href, 'display=swap' ) === false ) {
        $href = add_query_arg( 'display', 'swap', $href );
        $tag  = str_replace(
            'href=\'' . get_stylesheet_directory_uri(), // ダミー（下記で置換）
            'href=\'' . get_stylesheet_directory_uri(),
            $tag
        );
        // href を置換
        $tag = preg_replace( '/href=[\'"][^\'"]+[\'"]/', 'href=\'' . esc_url( $href ) . '\'', $tag );
    }

    return $tag;
}

/* ============================================================
   8. WordPress 絵文字スクリプトの無効化（不要リソース削減）
   ============================================================ */

remove_action( 'wp_head',             'print_emoji_detection_script', 7 );
remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
remove_action( 'wp_print_styles',     'print_emoji_styles' );
remove_action( 'admin_print_styles',  'print_emoji_styles' );
remove_filter( 'the_content_feed',    'wp_staticize_emoji' );
remove_filter( 'comment_text_rss',    'wp_staticize_emoji' );
remove_filter( 'wp_mail',             'wp_staticize_emoji_for_email' );
add_filter( 'tiny_mce_plugins', function ( array $plugins ): array {
    return array_diff( $plugins, [ 'wpemoji' ] );
} );

/* ============================================================
   9. Heartbeat API の間隔調整（管理画面負荷軽減）
   ============================================================ */

add_filter( 'heartbeat_settings', function ( array $settings ): array {
    // フロントエンドでは Heartbeat を無効化
    if ( ! is_admin() ) {
        $settings['interval'] = 300; // 5分
    }
    return $settings;
} );
