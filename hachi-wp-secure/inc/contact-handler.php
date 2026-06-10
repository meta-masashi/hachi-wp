<?php
/**
 * HACHI Theme — inc/contact-handler.php
 *
 * コンタクトフォーム拡張ハンドラー
 * - reCAPTCHA v3 サーバーサイド検証
 * - Slack Webhook 通知
 * - GA4 Conversion Event レスポンス
 * - 問い合わせ種別管理
 *
 * このファイルは functions.php の hachi_handle_contact() (priority=10) の
 * 前後にフックを追加する設計で、既存の nonce 検証・バリデーション・wp_mail を壊さない。
 *
 * @package HACHI
 */

defined( 'ABSPATH' ) || exit;

/* ============================================================
   問い合わせ種別 (wp_options 管理)
   ============================================================ */

/**
 * 問い合わせ種別の一覧を返す
 * wp_options から取得し、未設定時はデフォルト値を使用する
 *
 * @return array<string, array{label: string, ga4_event: string, emoji: string}>
 */
function hachi_get_contact_categories(): array {
    $defaults = [
        'pace_demo'    => [
            'label'     => 'HACHI Fieldwork のご相談',
            'ga4_event' => 'fieldwork_inquiry',
            'emoji'     => ':office:',
        ],
        'reboot_docs'  => [
            'label'     => 'コンディション・インサイトのご相談',
            'ga4_event' => 'condition_insight_inquiry',
            'emoji'     => ':page_facing_up:',
        ],
        'media'        => [
            'label'     => '取材・メディア',
            'ga4_event' => 'media_inquiry',
            'emoji'     => ':newspaper:',
        ],
        'recruit'      => [
            'label'     => '採用・パートナー',
            'ga4_event' => 'recruit_inquiry',
            'emoji'     => ':handshake:',
        ],
        'general'      => [
            'label'     => '一般お問い合わせ',
            'ga4_event' => 'contact_form_submit',
            'emoji'     => ':speech_balloon:',
        ],
    ];

    $stored = get_option( 'hachi_contact_categories', [] );
    if ( empty( $stored ) ) {
        return $defaults;
    }

    // v2 マイグレーション: media / recruit が未登録の古いデータに自動追加
    // （旧バージョンで保存された 3カテゴリー設定の自動移行）
    $migrated = false;
    foreach ( [ 'media', 'recruit', 'pace_demo', 'reboot_docs', 'general' ] as $required_key ) {
        if ( ! isset( $stored[ $required_key ] ) && isset( $defaults[ $required_key ] ) ) {
            $stored[ $required_key ] = $defaults[ $required_key ];
            $migrated = true;
        }
    }
    if ( $migrated ) {
        update_option( 'hachi_contact_categories', $stored );
    }

    return $stored;
}

/**
 * 問い合わせ種別のラベルからキーを解決し、GA4イベント名を返す
 *
 * @param string $cat_label フォームから送信されたラベル文字列
 * @return array{key: string, ga4_event: string, emoji: string}
 */
function hachi_resolve_contact_category( string $cat_label ): array {
    $categories = hachi_get_contact_categories();

    foreach ( $categories as $key => $data ) {
        if ( $data['label'] === $cat_label ) {
            return [
                'key'       => $key,
                'ga4_event' => $data['ga4_event'],
                'emoji'     => $data['emoji'],
            ];
        }
    }

    // マッチしない場合は general にフォールバック
    return [
        'key'       => 'general',
        'ga4_event' => 'contact_form_submit',
        'emoji'     => ':speech_balloon:',
    ];
}

/* ============================================================
   reCAPTCHA v3 サーバーサイド検証
   ============================================================ */

/**
 * reCAPTCHA v3 トークンを Google API で検証する
 *
 * @param string $token   クライアントから送信された reCAPTCHA トークン
 * @param string $action  期待するアクション名 (例: 'contact')
 * @return array{success: bool, score: float, error?: string}
 */
function hachi_verify_recaptcha( string $token, string $action = 'contact' ): array {
    $secret = defined( 'HACHI_RECAPTCHA_SECRET_KEY' ) ? HACHI_RECAPTCHA_SECRET_KEY : '';

    // シークレットキー未設定の場合は検証をスキップ（開発環境対応）
    if ( empty( $secret ) ) {
        hachi_security_log( 'recaptcha_skip', [ 'reason' => 'secret_key_not_set' ] );
        return [ 'success' => true, 'score' => 1.0 ];
    }

    if ( empty( $token ) ) {
        return [ 'success' => false, 'score' => 0.0, 'error' => 'missing_token' ];
    }

    $response = wp_remote_post( 'https://www.google.com/recaptcha/api/siteverify', [
        'timeout' => 10,
        'body'    => [
            'secret'   => $secret,
            'response' => $token,
            'remoteip' => hachi_get_client_ip(),
        ],
    ] );

    if ( is_wp_error( $response ) ) {
        hachi_security_log( 'recaptcha_api_error', [
            'error' => $response->get_error_message(),
            'ip'    => hachi_get_client_ip(),
        ] );
        // APIエラー時は防壁4: フォールバックとして通過させる
        return [ 'success' => true, 'score' => 0.5, 'error' => 'api_error_fallback' ];
    }

    $body = json_decode( wp_remote_retrieve_body( $response ), true );

    if ( ! is_array( $body ) ) {
        return [ 'success' => false, 'score' => 0.0, 'error' => 'invalid_response' ];
    }

    $success = ! empty( $body['success'] ) && (bool) $body['success'];
    $score   = (float) ( $body['score'] ?? 0.0 );

    // アクション名の検証
    if ( $success && isset( $body['action'] ) && $body['action'] !== $action ) {
        hachi_security_log( 'recaptcha_action_mismatch', [
            'expected' => $action,
            'received' => $body['action'],
            'ip'       => hachi_get_client_ip(),
        ] );
        return [ 'success' => false, 'score' => $score, 'error' => 'action_mismatch' ];
    }

    // スコアが 0.5 未満はボットと判定
    if ( $success && $score < 0.5 ) {
        hachi_security_log( 'recaptcha_low_score', [
            'score' => $score,
            'ip'    => hachi_get_client_ip(),
        ] );
        return [ 'success' => false, 'score' => $score, 'error' => 'low_score' ];
    }

    return [ 'success' => $success, 'score' => $score ];
}

/* ============================================================
   Slack Webhook 通知
   ============================================================ */

/**
 * お問い合わせ受信を Slack に通知する
 *
 * @param array{name: string, company: string, email: string, cat: string, message: string, ip: string} $data
 * @return bool 送信成功したか
 */
function hachi_notify_slack( array $data ): bool {
    $webhook_url = defined( 'HACHI_SLACK_WEBHOOK_URL' ) ? HACHI_SLACK_WEBHOOK_URL : '';

    if ( empty( $webhook_url ) ) {
        return false;
    }

    $cat_info = hachi_resolve_contact_category( $data['cat'] );
    $emoji    = $cat_info['emoji'];
    $site_url = get_bloginfo( 'url' );
    $time_jst = wp_date( 'Y/m/d H:i', null, new DateTimeZone( 'Asia/Tokyo' ) );

    // Slack Block Kit 形式でリッチなメッセージを構築
    $payload = [
        'text'   => "{$emoji} 新しいお問い合わせが届きました",
        'blocks' => [
            [
                'type' => 'header',
                'text' => [
                    'type'  => 'plain_text',
                    'text'  => "{$emoji} 新しいお問い合わせ",
                    'emoji' => true,
                ],
            ],
            [
                'type'   => 'section',
                'fields' => [
                    [
                        'type' => 'mrkdwn',
                        'text' => "*種別*\n" . esc_html( $data['cat'] ?: '未選択' ),
                    ],
                    [
                        'type' => 'mrkdwn',
                        'text' => "*受信時刻 (JST)*\n" . $time_jst,
                    ],
                    [
                        'type' => 'mrkdwn',
                        'text' => "*お名前*\n" . esc_html( $data['name'] ),
                    ],
                    [
                        'type' => 'mrkdwn',
                        'text' => "*会社名*\n" . esc_html( $data['company'] ?: '—' ),
                    ],
                    [
                        'type' => 'mrkdwn',
                        'text' => "*メールアドレス*\n`" . esc_html( $data['email'] ) . '`',
                    ],
                    [
                        'type' => 'mrkdwn',
                        'text' => "*役割 / 規模*\n" . esc_html( ( $data['role'] ?? '' ) ?: '—' ) . ' / ' . esc_html( ( $data['size'] ?? '' ) ?: '—' ),
                    ],
                    [
                        'type' => 'mrkdwn',
                        'text' => "*検討時期 / 電話*\n" . esc_html( ( $data['timeline'] ?? '' ) ?: '—' ) . ' / ' . esc_html( ( $data['phone'] ?? '' ) ?: '—' ),
                    ],
                ],
            ],
            [
                'type' => 'section',
                'text' => [
                    'type' => 'mrkdwn',
                    'text' => "*お問い合わせ内容*\n```" . esc_html( mb_substr( $data['message'], 0, 500 ) ) . '```',
                ],
            ],
            [
                'type'     => 'context',
                'elements' => [
                    [
                        'type' => 'mrkdwn',
                        'text' => "サイト: {$site_url} | IP: `" . esc_html( $data['ip'] ) . '`',
                    ],
                ],
            ],
            [
                'type' => 'divider',
            ],
        ],
    ];

    $response = wp_remote_post( $webhook_url, [
        'timeout'     => 10,
        'headers'     => [ 'Content-Type' => 'application/json' ],
        'body'        => wp_json_encode( $payload ),
    ] );

    if ( is_wp_error( $response ) ) {
        hachi_security_log( 'slack_notify_error', [
            'error' => $response->get_error_message(),
        ] );
        return false;
    }

    $status = wp_remote_retrieve_response_code( $response );
    return $status === 200;
}

/* ============================================================
   コンタクトフォーム拡張フック
   reCAPTCHA 検証 (priority=5) → 既存 hachi_handle_contact (priority=10)
   Slack 通知はコンタクト送信成功後に実施するため、
   functions.php の hachi_handle_contact を override せず
   priority=5 で reCAPTCHA のみ先行チェックし、
   Slack 通知は hachi_handle_contact 内から呼び出せるよう
   グローバルフィルターで結果を渡す設計にする。
   ============================================================ */

/**
 * priority=5: reCAPTCHA v3 検証プレフィルター
 * nonce 検証 (priority=10) より先に実行し、ボットを弾く
 */
add_action( 'wp_ajax_nopriv_hachi_contact', function (): void {
    // reCAPTCHA トークンの検証
    $token  = sanitize_text_field( $_POST['recaptcha_token'] ?? '' );
    $result = hachi_verify_recaptcha( $token, 'contact' );

    if ( ! $result['success'] ) {
        hachi_security_log( 'recaptcha_fail', [
            'ip'    => hachi_get_client_ip(),
            'error' => $result['error'] ?? 'unknown',
            'score' => $result['score'] ?? 0,
        ] );
        wp_send_json_error(
            [ 'message' => __( 'セキュリティ検証に失敗しました。再度お試しください。', 'hachi' ) ],
            403
        );
    }

    // 検証通過: スコアをグローバルに保存 (hachi_handle_contact で利用可能)
    $GLOBALS['hachi_recaptcha_score'] = $result['score'];
}, 5 );

add_action( 'wp_ajax_hachi_contact', function (): void {
    $token  = sanitize_text_field( $_POST['recaptcha_token'] ?? '' );
    $result = hachi_verify_recaptcha( $token, 'contact' );

    if ( ! $result['success'] ) {
        hachi_security_log( 'recaptcha_fail_auth', [
            'ip'    => hachi_get_client_ip(),
            'error' => $result['error'] ?? 'unknown',
            'score' => $result['score'] ?? 0,
        ] );
        wp_send_json_error(
            [ 'message' => __( 'セキュリティ検証に失敗しました。再度お試しください。', 'hachi' ) ],
            403
        );
    }

    $GLOBALS['hachi_recaptcha_score'] = $result['score'];
}, 5 );

/* ============================================================
   GA4 Conversion イベント名付き成功レスポンス
   wp_send_json_success をフィルターで拡張する代わりに、
   output バッファを使って JSON をインターセプトし
   ga4_event フィールドを追加する。
   ============================================================ */

/**
 * hachi_contact AJAX 完了後に ga4_event を JSON レスポンスに付加する
 *
 * wp_send_json_success() は ob_start より後に呼ばれるため、
 * shutdown フックで最終レスポンスを書き換える方式を採用。
 * ただし wp_send_json_* は die() するので直接フックできない。
 *
 * 代わりに priority=20 のフックで wp_send_json_success を
 * 拡張バージョンに差し替える。
 */
add_action( 'wp_ajax_nopriv_hachi_contact', 'hachi_contact_finalize', 20 );
add_action( 'wp_ajax_hachi_contact',        'hachi_contact_finalize', 20 );

/**
 * priority=20: コンタクト送信後の後処理
 * - Slack 通知
 * - GA4 イベント名を含む JSON レスポンスを上書き
 *
 * NOTE: functions.php の hachi_handle_contact (priority=10) が
 * wp_send_json_success() で die() するため、このフックは
 * エラー時のみ到達しない。正常系では priority=10 の直後には到達しない。
 * そのため Slack 通知と GA4 レスポンスは hachi_handle_contact を
 * 以下のフィルターで wrap する方式で実現する。
 */
function hachi_contact_finalize(): void {
    // このアクションは priority=10 で die() される前には実行されない
    // 実際の処理は hachi_override_contact_success() で行う
}

/* ============================================================
   hachi_handle_contact の wp_send_json_success を wrap して
   GA4 イベント名と Slack 通知を追加する
   ============================================================ */

/**
 * コンタクトフォーム成功レスポンスを拡張する
 * priority=8: hachi_handle_contact (priority=10) の直前に
 * バリデーション通過後のデータをキャプチャし、
 * 送信成功後に Slack 通知 + GA4 イベントを付加する。
 *
 * 実装方式: グローバルフィルター hachi_contact_success_data を定義し、
 * functions.php の hachi_handle_contact が wp_send_json_success を
 * 呼ぶ前にデータを差し替える。
 *
 * しかし functions.php は既存コードを変更できないため、
 * output buffering でレスポンスをインターセプトする方式を採用。
 */
add_action( 'wp_ajax_nopriv_hachi_contact', 'hachi_intercept_contact_response', 8 );
add_action( 'wp_ajax_hachi_contact',        'hachi_intercept_contact_response', 8 );

function hachi_intercept_contact_response(): void {
    // POST データをここで保持（priority=10 の実行前）
    $GLOBALS['hachi_contact_post_data'] = [
        'name'     => sanitize_text_field( $_POST['contact_name']    ?? '' ),
        'company'  => sanitize_text_field( $_POST['contact_company'] ?? '' ),
        'email'    => sanitize_email( $_POST['contact_email']         ?? '' ),
        'cat'      => sanitize_text_field( $_POST['contact_cat']      ?? '' ),
        'message'  => sanitize_textarea_field( $_POST['contact_message'] ?? '' ),
        'role'     => sanitize_text_field( $_POST['contact_role']     ?? '' ),
        'size'     => sanitize_text_field( $_POST['contact_size']     ?? '' ),
        'timeline' => sanitize_text_field( $_POST['contact_timeline'] ?? '' ),
        'phone'    => sanitize_text_field( $_POST['contact_phone']    ?? '' ),
        'ip'       => hachi_get_client_ip(),
    ];

    // output buffering 開始: priority=10 の wp_send_json_success をキャプチャ
    ob_start( 'hachi_transform_contact_json_response' );
}

/**
 * output buffer コールバック
 * wp_send_json_success のレスポンスに ga4_event を追加し、
 * Slack 通知をトリガーする
 *
 * @param string $buffer キャプチャされた出力
 * @return string 変換後の出力
 */
function hachi_transform_contact_json_response( string $buffer ): string {
    // JSON でない出力はそのまま返す
    $data = json_decode( $buffer, true );
    if ( ! is_array( $data ) ) {
        return $buffer;
    }

    // 成功レスポンスのみ拡張
    if ( ! empty( $data['success'] ) && $data['success'] === true ) {
        // Honeypot 発動時は Slack / Supabase / GA4 拡張をスキップ（ボット送信のゴミデータ排除）
        if ( ! empty( $GLOBALS['hachi_honeypot_triggered'] ) ) {
            return $buffer;
        }
        $post_data = $GLOBALS['hachi_contact_post_data'] ?? [];
        $cat_info  = hachi_resolve_contact_category( $post_data['cat'] ?? '' );

        // GA4 Conversion Event 名を追加
        $data['data'] = array_merge( $data['data'] ?? [], [
            'ga4_event'  => $cat_info['ga4_event'],
            'ga4_params' => [
                'event_category' => 'contact',
                'contact_type'   => $cat_info['key'],
            ],
        ] );

        // Slack 通知（非同期実行を模倣: 通知失敗でもレスポンスは成功のまま）
        if ( ! empty( $post_data ) ) {
            hachi_notify_slack( $post_data );
        }

        // Supabase に問い合わせデータを保存
        // [DISABLED 2026-06-10] CEO確定: コーポレートお問い合わせはメールのみ。外部DB保存無効化。
        // 再有効化が必要な場合はコメントを外してレン + リツ sign-off を取ること。
        // if ( ! empty( $post_data ) && function_exists( 'hachi_supabase_save_contact' ) ) {
        //     $recaptcha_score = (float) ( $GLOBALS['hachi_recaptcha_score'] ?? 0.0 );
        //     hachi_supabase_save_contact( $post_data, $cat_info['ga4_event'], $recaptcha_score );
        // }

        hachi_security_log( 'contact_with_ga4', [
            'ip'        => $post_data['ip']  ?? '',
            'cat'       => $post_data['cat'] ?? '',
            'ga4_event' => $cat_info['ga4_event'],
            'score'     => $GLOBALS['hachi_recaptcha_score'] ?? null,
            'supabase'  => function_exists( 'hachi_supabase_is_enabled' ) && hachi_supabase_is_enabled() ? 'enabled' : 'disabled',
        ] );
    }

    return (string) wp_json_encode( $data );
}

/* ============================================================
   問い合わせ種別の管理画面設定ページ
   ============================================================ */

/**
 * 管理画面に「お問い合わせ設定」ページを追加
 */
add_action( 'admin_menu', function (): void {
    add_options_page(
        __( 'お問い合わせ設定', 'hachi' ),
        __( 'お問い合わせ設定', 'hachi' ),
        'manage_options',
        'hachi-contact-settings',
        'hachi_render_contact_settings_page'
    );
} );

/**
 * お問い合わせ設定ページの描画
 */
function hachi_render_contact_settings_page(): void {
    if ( ! current_user_can( 'manage_options' ) ) {
        return;
    }

    // 保存処理
    if ( isset( $_POST['hachi_contact_settings_nonce'] ) &&
        wp_verify_nonce( sanitize_text_field( $_POST['hachi_contact_settings_nonce'] ), 'hachi_contact_settings' )
    ) {
        $categories = hachi_get_contact_categories();
        foreach ( array_keys( $categories ) as $key ) {
            if ( isset( $_POST[ "cat_label_{$key}" ] ) ) {
                $categories[ $key ]['label'] = sanitize_text_field( $_POST[ "cat_label_{$key}" ] );
            }
        }
        update_option( 'hachi_contact_categories', $categories );
        echo '<div class="notice notice-success"><p>' . esc_html__( '設定を保存しました。', 'hachi' ) . '</p></div>';
    }

    $categories = hachi_get_contact_categories();
    ?>
    <div class="wrap">
        <h1><?php esc_html_e( 'お問い合わせ設定', 'hachi' ); ?></h1>
        <form method="post">
            <?php wp_nonce_field( 'hachi_contact_settings', 'hachi_contact_settings_nonce' ); ?>
            <table class="form-table">
                <tbody>
                <?php foreach ( $categories as $key => $data ) : ?>
                    <tr>
                        <th scope="row">
                            <label for="cat_label_<?php echo esc_attr( $key ); ?>">
                                <?php echo esc_html( $key ); ?> (GA4: <?php echo esc_html( $data['ga4_event'] ); ?>)
                            </label>
                        </th>
                        <td>
                            <input
                                type="text"
                                id="cat_label_<?php echo esc_attr( $key ); ?>"
                                name="cat_label_<?php echo esc_attr( $key ); ?>"
                                value="<?php echo esc_attr( $data['label'] ); ?>"
                                class="regular-text"
                            >
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
            <?php submit_button( __( '保存', 'hachi' ) ); ?>
        </form>

        <h2><?php esc_html_e( '環境変数ステータス', 'hachi' ); ?></h2>
        <table class="widefat">
            <tbody>
                <tr>
                    <td>RECAPTCHA_SECRET_KEY</td>
                    <td><?php echo ( defined('HACHI_RECAPTCHA_SECRET_KEY') && ! empty( HACHI_RECAPTCHA_SECRET_KEY ) ) ? '<span style="color:green">✓ 設定済み</span>' : '<span style="color:red">✗ 未設定</span>'; ?></td>
                </tr>
                <tr>
                    <td>SLACK_WEBHOOK_URL</td>
                    <td><?php echo ( defined('HACHI_SLACK_WEBHOOK_URL') && ! empty( HACHI_SLACK_WEBHOOK_URL ) ) ? '<span style="color:green">✓ 設定済み</span>' : '<span style="color:red">✗ 未設定</span>'; ?></td>
                </tr>
                <tr>
                    <td>CONTACT_FORM_TO_EMAIL</td>
                    <td><?php echo defined('HACHI_CONTACT_TO_EMAIL') ? esc_html( HACHI_CONTACT_TO_EMAIL ) : '<span style="color:orange">△ 未設定</span>'; ?></td>
                </tr>
                <tr>
                    <td>SUPABASE_URL</td>
                    <td><?php echo ( defined('HACHI_SUPABASE_URL') && ! empty( HACHI_SUPABASE_URL ) ) ? '<span style="color:green">✓ 設定済み</span>' : '<span style="color:orange">△ 未設定（任意）</span>'; ?></td>
                </tr>
                <tr>
                    <td>SUPABASE_SERVICE_KEY</td>
                    <td>
                        <?php if ( defined('HACHI_SUPABASE_SERVICE_KEY') && ! empty( HACHI_SUPABASE_SERVICE_KEY ) ) : ?>
                            <span style="color:green">✓ 設定済み</span>
                            <?php if ( function_exists( 'hachi_supabase_health_check' ) ) :
                                $health = hachi_supabase_health_check(); ?>
                                &nbsp;|&nbsp;
                                <?php if ( $health['connected'] ) : ?>
                                    <span style="color:green">接続OK <?php echo isset( $health['latency_ms'] ) ? "({$health['latency_ms']}ms)" : ''; ?></span>
                                <?php else : ?>
                                    <span style="color:red">接続エラー: <?php echo esc_html( $health['error'] ?? '不明' ); ?></span>
                                <?php endif; ?>
                            <?php endif; ?>
                        <?php else : ?>
                            <span style="color:orange">△ 未設定（任意）</span>
                        <?php endif; ?>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
    <?php
}
