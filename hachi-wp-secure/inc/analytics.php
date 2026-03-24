<?php
/**
 * HACHI Theme — inc/analytics.php
 *
 * Google Tag Manager + GA4 実装
 *   - GTM コンテナスニペット（head + body）
 *   - GA4 コンバージョンイベント定義（JS ヘルパー）
 *   - GTM_CONTAINER_ID / GA4_MEASUREMENT_ID 未設定時は出力しない
 *
 * 環境変数:
 *   GTM_CONTAINER_ID=GTM-XXXXXXX
 *   GA4_MEASUREMENT_ID=G-XXXXXXXXXX
 *
 * @package HACHI
 */

defined( 'ABSPATH' ) || exit;

/* ============================================================
   ヘルパー: ID 取得
   ============================================================ */

/**
 * GTM コンテナ ID を返す。
 * 未設定または空の場合は空文字を返す。
 */
function hachi_get_gtm_id(): string {
    // WordPress の定数経由（wp-config.php / wp-config-security.php で define 可能）
    if ( defined( 'HACHI_GTM_CONTAINER_ID' ) && ! empty( HACHI_GTM_CONTAINER_ID ) ) {
        return (string) HACHI_GTM_CONTAINER_ID;
    }
    // PHP 環境変数経由（Docker / サーバー環境変数）
    $env = getenv( 'GTM_CONTAINER_ID' );
    if ( $env !== false && ! empty( $env ) ) {
        return (string) $env;
    }
    return '';
}

/**
 * GA4 Measurement ID を返す。
 * 未設定または空の場合は空文字を返す。
 */
function hachi_get_ga4_id(): string {
    if ( defined( 'HACHI_GA4_MEASUREMENT_ID' ) && ! empty( HACHI_GA4_MEASUREMENT_ID ) ) {
        return (string) HACHI_GA4_MEASUREMENT_ID;
    }
    $env = getenv( 'GA4_MEASUREMENT_ID' );
    if ( $env !== false && ! empty( $env ) ) {
        return (string) $env;
    }
    return '';
}

/* ============================================================
   GTM スニペット（<head> 内）
   ============================================================ */

add_action( 'wp_head', 'hachi_gtm_head_snippet', 2 );

function hachi_gtm_head_snippet(): void {
    $gtm_id = hachi_get_gtm_id();
    if ( empty( $gtm_id ) ) {
        return;
    }
    // GTM ID のバリデーション（GTM-XXXXXXX 形式）
    if ( ! preg_match( '/^GTM-[A-Z0-9]{4,10}$/', $gtm_id ) ) {
        return;
    }
    $gtm_id_escaped = esc_js( $gtm_id );
    ?>
<!-- Google Tag Manager (hachi/inc/analytics.php) -->
<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
})(window,document,'script','dataLayer','<?php echo $gtm_id_escaped; ?>');</script>
<!-- End Google Tag Manager -->
    <?php
}

/* ============================================================
   GTM noscript スニペット（<body> 直後）
   ============================================================ */

add_action( 'wp_body_open', 'hachi_gtm_body_snippet', 1 );

function hachi_gtm_body_snippet(): void {
    $gtm_id = hachi_get_gtm_id();
    if ( empty( $gtm_id ) ) {
        return;
    }
    if ( ! preg_match( '/^GTM-[A-Z0-9]{4,10}$/', $gtm_id ) ) {
        return;
    }
    ?>
<!-- Google Tag Manager (noscript) -->
<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=<?php echo esc_attr( $gtm_id ); ?>"
height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
<!-- End Google Tag Manager (noscript) -->
    <?php
}

/* ============================================================
   GA4 コンバージョンイベント定義（dataLayer push ヘルパー JS）
   ============================================================ */

add_action( 'wp_footer', 'hachi_output_ga4_event_helpers', 5 );

function hachi_output_ga4_event_helpers(): void {
    $gtm_id  = hachi_get_gtm_id();
    $ga4_id  = hachi_get_ga4_id();

    // GTM も GA4 も未設定の場合はスキップ
    if ( empty( $gtm_id ) && empty( $ga4_id ) ) {
        return;
    }

    ?>
<script>
/* HACHI Analytics — コンバージョンイベント定義 */
(function() {
    'use strict';

    window.dataLayer = window.dataLayer || [];

    /**
     * HACHI コンバージョンイベント送信ヘルパー
     *
     * @param {string} eventName - イベント名
     * @param {Object} params    - イベントパラメータ
     */
    function hachiTrackEvent(eventName, params) {
        if (!window.dataLayer) return;
        var payload = Object.assign({ event: eventName }, params || {});
        window.dataLayer.push(payload);
    }

    /* --------------------------------------------------------
       コンバージョンイベント定義
    --------------------------------------------------------- */

    /**
     * demo_request: PACE v3.0 デモ申込み完了
     * @param {string} method - 'form'
     */
    window.hachiTrackDemoRequest = function(method) {
        hachiTrackEvent('demo_request', {
            event_category: 'conversion',
            event_label: 'PACE v3.0 デモ申込み',
            method: method || 'form'
        });
    };

    /**
     * resource_request: REBOOT-WORK 資料請求完了
     * @param {string} method - 'form'
     */
    window.hachiTrackResourceRequest = function(method) {
        hachiTrackEvent('resource_request', {
            event_category: 'conversion',
            event_label: 'REBOOT-WORK 資料請求',
            method: method || 'form'
        });
    };

    /**
     * contact_form_submit: 一般お問い合わせ完了
     * @param {string} category - 問い合わせ種別
     */
    window.hachiTrackContactFormSubmit = function(category) {
        hachiTrackEvent('contact_form_submit', {
            event_category: 'conversion',
            event_label: '一般お問い合わせ',
            contact_category: category || '一般お問い合わせ'
        });
    };

    /* --------------------------------------------------------
       お問い合わせフォーム送信完了の自動計測
       Ajax レスポンス成功時に jQuery カスタムイベントを受信する
    --------------------------------------------------------- */
    document.addEventListener('hachi:contact_success', function(e) {
        var cat = (e.detail && e.detail.category) ? e.detail.category : '一般お問い合わせ';

        if (cat === 'PACE v3.0 デモ申込み') {
            window.hachiTrackDemoRequest('form');
        } else if (cat === 'REBOOT-WORK 資料請求') {
            window.hachiTrackResourceRequest('form');
        } else {
            window.hachiTrackContactFormSubmit(cat);
        }
    });

})();
</script>
    <?php
}

/* ============================================================
   CSP ヘッダーに GTM / GA4 の script-src / connect-src を追加
   （security.php の wp_headers フィルタより後に実行: priority 20）
   ============================================================ */

add_filter( 'wp_headers', 'hachi_analytics_extend_csp', 20 );

function hachi_analytics_extend_csp( array $headers ): array {
    $gtm_id = hachi_get_gtm_id();
    $ga4_id = hachi_get_ga4_id();

    // GTM / GA4 が設定されていない場合は CSP を変更しない
    if ( empty( $gtm_id ) && empty( $ga4_id ) ) {
        return $headers;
    }

    if ( ! isset( $headers['Content-Security-Policy'] ) ) {
        return $headers;
    }

    $csp = $headers['Content-Security-Policy'];

    // script-src に GTM / GA4 ドメインを追加
    $csp = hachi_csp_add_source(
        $csp,
        'script-src',
        'https://www.googletagmanager.com https://www.google-analytics.com'
    );

    // connect-src に GA4 の計測エンドポイントを追加
    $csp = hachi_csp_add_source(
        $csp,
        'connect-src',
        'https://www.google-analytics.com https://analytics.google.com https://region1.google-analytics.com'
    );

    // img-src に GA4 のビーコンを追加（すでに https: が許可済みの場合は不要だが明示）
    $csp = hachi_csp_add_source(
        $csp,
        'img-src',
        'https://www.googletagmanager.com'
    );

    $headers['Content-Security-Policy'] = $csp;
    return $headers;
}

/**
 * CSP 文字列の指定ディレクティブにソースを追加するヘルパー。
 *
 * @param string $csp       既存の CSP ヘッダー文字列
 * @param string $directive ディレクティブ名（例: 'script-src'）
 * @param string $source    追加するソース（スペース区切りで複数可）
 * @return string           更新後の CSP 文字列
 */
function hachi_csp_add_source( string $csp, string $directive, string $source ): string {
    $directive_escaped = preg_quote( $directive, '/' );

    if ( preg_match( '/(' . $directive_escaped . '[^;]*)(;|$)/', $csp, $m ) ) {
        // 既存のディレクティブに追記
        $updated = rtrim( $m[1] ) . ' ' . $source;
        $csp     = str_replace( $m[1], $updated, $csp );
    } else {
        // ディレクティブが存在しない場合は末尾に追加
        $csp = rtrim( $csp, '; ' ) . '; ' . $directive . " 'self' " . $source;
    }

    return $csp;
}
