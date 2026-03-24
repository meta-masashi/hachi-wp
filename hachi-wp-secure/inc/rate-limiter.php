<?php
/**
 * HACHI Theme — inc/rate-limiter.php
 *
 * レートリミッター
 * コンタクトフォーム・APIへの過剰リクエストをTransientsで制御
 *
 * @package HACHI
 */

defined( 'ABSPATH' ) || exit;

/**
 * レートリミット設定
 */
const HACHI_RATE_LIMITS = [
    'contact_form' => [
        'max'    => 5,              // 最大リクエスト数
        'window' => 15 * MINUTE_IN_SECONDS, // ウィンドウ時間 (15分)
        'lockout'=> 30 * MINUTE_IN_SECONDS, // 超過時のロック時間 (30分)
    ],
    'login' => [
        'max'    => 5,
        'window' => 10 * MINUTE_IN_SECONDS,
        'lockout'=> HOUR_IN_SECONDS,
    ],
    'search' => [
        'max'    => 30,
        'window' => MINUTE_IN_SECONDS,
        'lockout'=> 5 * MINUTE_IN_SECONDS,
    ],
];

/**
 * レートリミットチェック
 *
 * @param string $action  アクション識別子 (contact_form / login / search)
 * @param string $ip      クライアントIP (省略時は自動取得)
 * @return array{allowed: bool, remaining: int, retry_after: int}
 */
function hachi_check_rate_limit( string $action, string $ip = '' ): array {
    if ( empty( $ip ) ) {
        $ip = hachi_get_client_ip();
    }

    $config     = HACHI_RATE_LIMITS[ $action ] ?? [ 'max' => 10, 'window' => MINUTE_IN_SECONDS, 'lockout' => 5 * MINUTE_IN_SECONDS ];
    $ip_hash    = md5( $ip . $action );
    $count_key  = "hachi_rl_{$ip_hash}_count";
    $lock_key   = "hachi_rl_{$ip_hash}_lock";

    // ロック中チェック
    $lock_expires = (int) get_transient( $lock_key );
    if ( $lock_expires > time() ) {
        return [
            'allowed'     => false,
            'remaining'   => 0,
            'retry_after' => $lock_expires - time(),
        ];
    }

    // リクエストカウント取得
    $current = (int) get_transient( $count_key );
    $current++;

    if ( $current > $config['max'] ) {
        // ロック設定
        $lock_until = time() + $config['lockout'];
        set_transient( $lock_key, $lock_until, $config['lockout'] );
        delete_transient( $count_key );

        hachi_security_log( 'rate_limit_exceeded', [
            'action'      => $action,
            'ip'          => $ip,
            'count'       => $current,
            'locked_until'=> gmdate( 'Y-m-d H:i:s', $lock_until ),
        ] );

        return [
            'allowed'     => false,
            'remaining'   => 0,
            'retry_after' => $config['lockout'],
        ];
    }

    // カウントを更新
    set_transient( $count_key, $current, $config['window'] );

    return [
        'allowed'     => true,
        'remaining'   => $config['max'] - $current,
        'retry_after' => 0,
    ];
}

/**
 * レートリミット超過時のJSONレスポンスを返して終了
 *
 * @param int $retry_after 再試行までの秒数
 */
function hachi_rate_limit_response( int $retry_after = 0 ): void {
    status_header( 429 );
    header( 'Content-Type: application/json; charset=utf-8' );
    if ( $retry_after > 0 ) {
        header( "Retry-After: {$retry_after}" );
    }
    wp_send_json_error( [
        'message'     => __( 'リクエストが多すぎます。しばらく経ってから再試行してください。', 'hachi' ),
        'retry_after' => $retry_after,
    ], 429 );
}

/**
 * コンタクトフォームAJAXへのレートリミット適用
 *
 * NOTE: このフックは priority=1 で動作するレートリミット専用プレフィルター。
 * CSRF nonce 検証は functions.php の hachi_handle_contact (priority=10) で行う。
 * 設計上 nonce 検証前にレート制限をチェックすることで、
 * nonce 偽造による DoS 攻撃も防止できる。
 */
// nonce検証はpriority=10のメインハンドラー(functions.php)で wp_verify_nonce() を実行
// ここはpriority=1のレートリミット専用プレフィルター
add_action( 'wp_ajax_nopriv_hachi_contact', function (): void {
    $result = hachi_check_rate_limit( 'contact_form' );
    if ( ! $result['allowed'] ) {
        hachi_rate_limit_response( $result['retry_after'] );
    }
}, 1 ); // priority 1 = セキュリティチェックより先に実行

add_action( 'wp_ajax_hachi_contact', function (): void {
    $result = hachi_check_rate_limit( 'contact_form' );
    if ( ! $result['allowed'] ) {
        hachi_rate_limit_response( $result['retry_after'] );
    }
}, 1 );

/**
 * 検索リクエストへのレートリミット適用
 */
add_action( 'init', function (): void {
    if ( is_search() || ( isset( $_GET['s'] ) && ! is_admin() ) ) {
        $result = hachi_check_rate_limit( 'search' );
        if ( ! $result['allowed'] ) {
            status_header( 429 );
            wp_die(
                __( 'リクエストが多すぎます。しばらく経ってから再試行してください。', 'hachi' ),
                'Too Many Requests',
                [ 'response' => 429 ]
            );
        }
    }
} );
