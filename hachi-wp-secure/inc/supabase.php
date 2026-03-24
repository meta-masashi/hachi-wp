<?php
/**
 * HACHI Theme — inc/supabase.php
 *
 * Supabase PHP クライアント
 * WordPress HTTP API (wp_remote_*) を使用して
 * Supabase REST API (PostgREST) と通信する。
 *
 * 使用するクレデンシャル:
 *   HACHI_SUPABASE_URL         : https://<project>.supabase.co
 *   HACHI_SUPABASE_SERVICE_KEY : service_role JWT (サーバーサイド専用・公開禁止)
 *
 * @package HACHI
 */

defined( 'ABSPATH' ) || exit;

/* ============================================================
   1. 有効化チェック
   ============================================================ */

/**
 * Supabase 統合が有効かどうかを確認する
 * URL とサービスキーの両方が設定されている場合のみ有効
 *
 * @return bool
 */
function hachi_supabase_is_enabled(): bool {
    return defined( 'HACHI_SUPABASE_URL' )
        && ! empty( HACHI_SUPABASE_URL )
        && defined( 'HACHI_SUPABASE_SERVICE_KEY' )
        && ! empty( HACHI_SUPABASE_SERVICE_KEY );
}

/* ============================================================
   2. コア HTTP リクエスト
   ============================================================ */

/**
 * Supabase REST API へ HTTP リクエストを送信する
 *
 * @param string $method  HTTP メソッド (GET / POST / PATCH / DELETE)
 * @param string $path    エンドポイントパス (例: /contact_submissions)
 * @param array  $body    リクエストボディ (INSERT/UPDATE 時)
 * @param array  $headers 追加ヘッダー (PostgREST クエリ用)
 * @return array{success: bool, data: mixed, error?: string, status: int}
 */
function hachi_supabase_request(
    string $method,
    string $path,
    array  $body    = [],
    array  $headers = []
): array {

    if ( ! hachi_supabase_is_enabled() ) {
        return [
            'success' => false,
            'data'    => null,
            'error'   => 'supabase_not_configured',
            'status'  => 0,
        ];
    }

    $url = rtrim( HACHI_SUPABASE_URL, '/' ) . '/rest/v1' . $path;

    $default_headers = [
        'apikey'        => HACHI_SUPABASE_SERVICE_KEY,
        'Authorization' => 'Bearer ' . HACHI_SUPABASE_SERVICE_KEY,
        'Content-Type'  => 'application/json',
        'Prefer'        => 'return=representation',  // INSERT 後に挿入行を返す
    ];

    $merged_headers = array_merge( $default_headers, $headers );

    $args = [
        'method'  => strtoupper( $method ),
        'timeout' => 10,
        'headers' => $merged_headers,
    ];

    if ( ! empty( $body ) ) {
        $args['body'] = wp_json_encode( $body );
    }

    $response = wp_remote_request( $url, $args );

    if ( is_wp_error( $response ) ) {
        hachi_security_log( 'supabase_request_error', [
            'path'  => $path,
            'error' => $response->get_error_message(),
        ] );
        return [
            'success' => false,
            'data'    => null,
            'error'   => $response->get_error_message(),
            'status'  => 0,
        ];
    }

    $status      = (int) wp_remote_retrieve_response_code( $response );
    $raw_body    = wp_remote_retrieve_body( $response );
    $parsed_body = json_decode( $raw_body, true );

    // 2xx = 成功
    $success = $status >= 200 && $status < 300;

    if ( ! $success ) {
        $error_msg = is_array( $parsed_body ) && isset( $parsed_body['message'] )
            ? $parsed_body['message']
            : "HTTP {$status}";

        hachi_security_log( 'supabase_api_error', [
            'path'    => $path,
            'status'  => $status,
            'message' => $error_msg,
        ] );
    }

    return [
        'success' => $success,
        'data'    => $parsed_body,
        'status'  => $status,
    ];
}

/* ============================================================
   3. INSERT ヘルパー
   ============================================================ */

/**
 * テーブルに 1 行挿入する
 *
 * @param string $table テーブル名
 * @param array  $data  挿入するカラム => 値 の連想配列
 * @return array{success: bool, data: mixed, error?: string, status: int}
 */
function hachi_supabase_insert( string $table, array $data ): array {
    return hachi_supabase_request( 'POST', '/' . $table, $data );
}

/* ============================================================
   4. SELECT ヘルパー
   ============================================================ */

/**
 * テーブルから行を取得する
 *
 * @param string $table   テーブル名
 * @param array  $filters クエリフィルター (例: ['status' => 'eq.new'])
 * @param string $select  取得カラム (デフォルト: *)
 * @param int    $limit   最大取得件数
 * @return array{success: bool, data: mixed, error?: string, status: int}
 */
function hachi_supabase_select(
    string $table,
    array  $filters = [],
    string $select  = '*',
    int    $limit   = 100
): array {
    // クエリパラメータ構築
    $query_params = array_merge( [ 'select' => $select ], $filters );
    $query_string = http_build_query( $query_params );
    $path         = '/' . $table . '?' . $query_string;

    // Range ヘッダーでページング
    $headers = [
        'Range'        => "0-{$limit}",
        'Range-Unit'   => 'items',
        'Prefer'       => 'count=exact',
    ];

    return hachi_supabase_request( 'GET', $path, [], $headers );
}

/* ============================================================
   5. UPDATE ヘルパー
   ============================================================ */

/**
 * 条件に一致する行を更新する
 *
 * @param string $table   テーブル名
 * @param array  $filters PostgREST フィルター (例: ['id' => 'eq.uuid-value'])
 * @param array  $data    更新するカラム => 値
 * @return array{success: bool, data: mixed, error?: string, status: int}
 */
function hachi_supabase_update(
    string $table,
    array  $filters,
    array  $data
): array {
    $query_string = http_build_query( $filters );
    $path         = '/' . $table . '?' . $query_string;

    return hachi_supabase_request( 'PATCH', $path, $data );
}

/* ============================================================
   6. 問い合わせ保存ヘルパー
   ============================================================ */

/**
 * コンタクトフォームの送信データを Supabase に保存する
 *
 * IP アドレスはハッシュ化してプライバシーを保護する。
 *
 * @param array  $contact_data  問い合わせデータ
 * @param string $ga4_event     GA4 イベント名
 * @param float  $recaptcha_score reCAPTCHA スコア
 * @return array{success: bool, id?: string, error?: string}
 */
function hachi_supabase_save_contact(
    array  $contact_data,
    string $ga4_event       = '',
    float  $recaptcha_score = 0.0
): array {
    if ( ! hachi_supabase_is_enabled() ) {
        return [ 'success' => false, 'error' => 'supabase_not_configured' ];
    }

    // IP はハッシュ化（個人情報保護: 原文は保存しない）
    $ip_hash = hash( 'sha256', ( $contact_data['ip'] ?? '' ) . NONCE_SALT );

    $row = [
        'name'             => $contact_data['name']    ?? '',
        'company'          => $contact_data['company'] ?? '',
        'email'            => $contact_data['email']   ?? '',
        'category'         => $contact_data['cat']     ?? '',
        'message'          => $contact_data['message'] ?? '',
        'ip_hash'          => $ip_hash,
        'recaptcha_score'  => $recaptcha_score,
        'ga4_event'        => $ga4_event,
        'status'           => 'new',
    ];

    $result = hachi_supabase_insert( 'contact_submissions', $row );

    if ( $result['success'] && is_array( $result['data'] ) ) {
        $inserted = $result['data'][0] ?? $result['data'];
        return [
            'success' => true,
            'id'      => $inserted['id'] ?? null,
        ];
    }

    return [
        'success' => false,
        'error'   => $result['error'] ?? "HTTP {$result['status']}",
    ];
}

/* ============================================================
   7. セキュリティイベント保存ヘルパー
   ============================================================ */

/**
 * セキュリティイベントを Supabase に非同期的に記録する
 * ファイルログ (hachi_security_log) の補完として使用。
 * Supabase が未設定の場合は何もしない。
 *
 * @param string $event イベント識別子
 * @param array  $data  追加データ
 */
function hachi_supabase_log_event( string $event, array $data = [] ): void {
    if ( ! hachi_supabase_is_enabled() ) {
        return;
    }

    // IP はハッシュ化
    $ip_raw  = $data['ip'] ?? '';
    $ip_hash = $ip_raw ? hash( 'sha256', $ip_raw . NONCE_SALT ) : null;
    unset( $data['ip'] ); // 元データから IP を除去

    $row = [
        'event'   => $event,
        'ip_hash' => $ip_hash,
        'data'    => $data,  // jsonb カラムに配列をそのまま渡す (PostgREST が JSON 変換)
    ];

    // エラーが発生してもサイト動作に影響しないよう、戻り値は無視
    hachi_supabase_insert( 'security_events', $row );
}

/* ============================================================
   8. 管理画面: Supabase 接続ステータス確認
   ============================================================ */

/**
 * Supabase への疎通確認を行う
 * 管理画面のステータスページで使用する。
 *
 * @return array{connected: bool, latency_ms?: int, error?: string}
 */
function hachi_supabase_health_check(): array {
    if ( ! hachi_supabase_is_enabled() ) {
        return [ 'connected' => false, 'error' => 'not_configured' ];
    }

    $start    = microtime( true );
    // contact_submissions から 0 件取得で接続確認 (実データ不要)
    $result   = hachi_supabase_request( 'GET', '/contact_submissions?select=id&limit=1', [], [
        'Range'     => '0-0',
        'Range-Unit'=> 'items',
    ] );
    $latency  = (int) round( ( microtime( true ) - $start ) * 1000 );

    if ( $result['success'] || $result['status'] === 406 ) {
        // 406 = Range not satisfiable (データが 0 件の場合) → 接続自体は成功
        return [ 'connected' => true, 'latency_ms' => $latency ];
    }

    return [
        'connected'  => false,
        'latency_ms' => $latency,
        'error'      => $result['error'] ?? "HTTP {$result['status']}",
    ];
}
