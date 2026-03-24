<?php
/**
 * HACHI Theme — inc/two-factor.php
 *
 * 管理者向け TOTP 2要素認証 (Time-based One-Time Password)
 * RFC 6238 準拠 / Google Authenticator 互換
 *
 * @package HACHI
 */

defined( 'ABSPATH' ) || exit;

/**
 * 2FAを強制するロール (管理者は必須)
 */
const HACHI_2FA_REQUIRED_ROLES = [ 'administrator', 'editor' ];

/* ============================================================
   TOTP コア実装
   ============================================================ */

/**
 * Base32エンコード
 */
function hachi_base32_encode( string $data ): string {
    $chars   = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ234567';
    $binary  = '';
    foreach ( str_split( $data ) as $char ) {
        $binary .= str_pad( decbin( ord( $char ) ), 8, '0', STR_PAD_LEFT );
    }
    $binary   = str_pad( $binary, (int) ( ceil( strlen( $binary ) / 5 ) * 5 ), '0' );
    $result   = '';
    foreach ( str_split( $binary, 5 ) as $chunk ) {
        $result .= $chars[ bindec( $chunk ) ];
    }
    return str_pad( $result, (int) ( ceil( strlen( $result ) / 8 ) * 8 ), '=' );
}

/**
 * Base32デコード
 */
function hachi_base32_decode( string $data ): string {
    $chars  = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ234567';
    $data   = strtoupper( rtrim( $data, '=' ) );
    $binary = '';
    foreach ( str_split( $data ) as $char ) {
        $pos     = strpos( $chars, $char );
        $binary .= $pos !== false ? str_pad( decbin( $pos ), 5, '0', STR_PAD_LEFT ) : '';
    }
    $result = '';
    foreach ( str_split( $binary, 8 ) as $chunk ) {
        if ( strlen( $chunk ) === 8 ) {
            $result .= chr( bindec( $chunk ) );
        }
    }
    return $result;
}

/**
 * TOTP コード生成
 *
 * @param string $secret  Base32エンコードされたシークレット
 * @param int    $time    Unixタイムスタンプ (省略時は現在時刻)
 * @param int    $window  許容ウィンドウ数 (±N×30秒)
 */
function hachi_generate_totp( string $secret, int $time = 0, int $window = 1 ): array {
    if ( $time === 0 ) {
        $time = time();
    }
    $step     = 30;
    $codes    = [];
    $key      = hachi_base32_decode( $secret );

    for ( $i = -$window; $i <= $window; $i++ ) {
        $timestamp = (int) floor( ( $time + ( $i * $step ) ) / $step );
        $msg       = pack( 'N*', 0 ) . pack( 'N*', $timestamp );
        $hash      = hash_hmac( 'sha1', $msg, $key, true );
        $offset    = ord( $hash[19] ) & 0xf;
        $code      = (
            ( ( ord( $hash[ $offset ] )     & 0x7f ) << 24 ) |
            ( ( ord( $hash[ $offset + 1 ] ) & 0xff ) << 16 ) |
            ( ( ord( $hash[ $offset + 2 ] ) & 0xff ) << 8  ) |
            ( ( ord( $hash[ $offset + 3 ] ) & 0xff )        )
        ) % 1000000;
        $codes[] = str_pad( (string) $code, 6, '0', STR_PAD_LEFT );
    }

    return $codes;
}

/**
 * TOTP検証
 *
 * @param string $secret  ユーザーのシークレット
 * @param string $input   入力された6桁コード
 */
function hachi_verify_totp( string $secret, string $input ): bool {
    $input     = preg_replace( '/\D/', '', $input );
    $valid     = hachi_generate_totp( $secret, time(), 1 );
    return in_array( $input, $valid, true );
}

/**
 * QRコード用 otpauth URI を生成
 *
 * @param string $email   ユーザーのメールアドレス
 * @param string $secret  シークレット
 * @param string $issuer  発行者名
 */
function hachi_get_totp_uri( string $email, string $secret, string $issuer = 'HACHI' ): string {
    return sprintf(
        'otpauth://totp/%s:%s?secret=%s&issuer=%s&algorithm=SHA1&digits=6&period=30',
        rawurlencode( $issuer ),
        rawurlencode( $email ),
        $secret,
        rawurlencode( $issuer )
    );
}

/* ============================================================
   ユーザー設定 UI
   ============================================================ */

/**
 * プロフィールページに2FA設定を追加
 */
add_action( 'show_user_profile',    'hachi_2fa_profile_fields' );
add_action( 'edit_user_profile',    'hachi_2fa_profile_fields' );

function hachi_2fa_profile_fields( WP_User $user ): void {
    if ( ! current_user_can( 'edit_user', $user->ID ) ) {
        return;
    }

    $secret  = get_user_meta( $user->ID, '_hachi_2fa_secret', true );
    $enabled = get_user_meta( $user->ID, '_hachi_2fa_enabled', true );
    $is_req  = hachi_2fa_is_required_for_user( $user );

    ?>
    <h2><?php _e( '二要素認証 (2FA)', 'hachi' ); ?></h2>
    <table class="form-table" role="presentation">
        <tr>
            <th scope="row"><?php _e( '2FA 状態', 'hachi' ); ?></th>
            <td>
                <?php if ( $enabled ) : ?>
                    <span style="color:#00a32a;font-weight:600">✓ <?php _e( '有効', 'hachi' ); ?></span>
                    <?php if ( $is_req ) : ?>
                        <span style="color:#d63638;margin-left:8px"><?php _e( '（必須）', 'hachi' ); ?></span>
                    <?php endif; ?>
                <?php else : ?>
                    <span style="color:#d63638;font-weight:600">✗ <?php _e( '無効', 'hachi' ); ?></span>
                    <?php if ( $is_req ) : ?>
                        <span style="color:#d63638;margin-left:8px;font-weight:700">
                            <?php _e( '⚠ このロールでは2FAが必須です', 'hachi' ); ?>
                        </span>
                    <?php endif; ?>
                <?php endif; ?>
            </td>
        </tr>

        <?php if ( ! $enabled ) : ?>
        <tr>
            <th scope="row"><?php _e( '2FA セットアップ', 'hachi' ); ?></th>
            <td>
                <?php
                // 新しいシークレットを生成
                if ( empty( $secret ) ) {
                    $secret = hachi_base32_encode( random_bytes( 16 ) );
                    update_user_meta( $user->ID, '_hachi_2fa_secret', $secret );
                }
                $uri = hachi_get_totp_uri( $user->user_email, $secret, get_bloginfo( 'name' ) );
                ?>
                <p><?php _e( '1. Google Authenticator / Authy などのアプリをインストールしてください。', 'hachi' ); ?></p>
                <p>
                    <?php _e( '2. 以下のQRコードをスキャンするか、シークレットキーを手動入力してください。', 'hachi' ); ?>
                </p>
                <!-- QRコード (Google Charts API) -->
                <img
                    src="https://chart.googleapis.com/chart?chs=200x200&chld=M|0&cht=qr&chl=<?php echo rawurlencode( $uri ); ?>"
                    alt="<?php esc_attr_e( '2FA QRコード', 'hachi' ); ?>"
                    width="200"
                    height="200"
                    style="border:1px solid #ccc;padding:4px"
                >
                <p>
                    <?php _e( 'シークレットキー:', 'hachi' ); ?>
                    <code style="background:#f0f0f1;padding:4px 8px;font-size:14px"><?php echo esc_html( $secret ); ?></code>
                </p>
                <p><?php _e( '3. 6桁のコードを入力して有効化:', 'hachi' ); ?></p>
                <input type="text" name="hachi_2fa_verify" maxlength="6" pattern="\d{6}"
                       placeholder="000000" style="width:100px;letter-spacing:4px;font-size:18px"
                       autocomplete="one-time-code">
                <input type="hidden" name="hachi_2fa_secret_confirm" value="<?php echo esc_attr( $secret ); ?>">
                <?php wp_nonce_field( 'hachi_2fa_setup_' . $user->ID, 'hachi_2fa_nonce' ); ?>
            </td>
        </tr>
        <?php else : ?>
        <tr>
            <th scope="row"><?php _e( '2FA 無効化', 'hachi' ); ?></th>
            <td>
                <?php if ( ! $is_req ) : ?>
                    <label>
                        <input type="checkbox" name="hachi_2fa_disable" value="1">
                        <?php _e( '2FAを無効化する', 'hachi' ); ?>
                    </label>
                    <?php wp_nonce_field( 'hachi_2fa_disable_' . $user->ID, 'hachi_2fa_disable_nonce' ); ?>
                <?php else : ?>
                    <em><?php _e( 'このロールでは2FAは必須のため無効化できません。', 'hachi' ); ?></em>
                <?php endif; ?>
            </td>
        </tr>
        <?php endif; ?>
    </table>
    <?php
}

/**
 * プロフィール保存時に2FA設定を処理
 */
add_action( 'personal_options_update',  'hachi_2fa_save_profile' );
add_action( 'edit_user_profile_update', 'hachi_2fa_save_profile' );

function hachi_2fa_save_profile( int $user_id ): void {
    if ( ! current_user_can( 'edit_user', $user_id ) ) {
        return;
    }

    // 有効化処理
    if (
        isset( $_POST['hachi_2fa_nonce'] ) &&
        wp_verify_nonce( sanitize_text_field( $_POST['hachi_2fa_nonce'] ?? '' ), 'hachi_2fa_setup_' . $user_id ) &&
        ! empty( $_POST['hachi_2fa_verify'] ) &&
        ! empty( $_POST['hachi_2fa_secret_confirm'] )
    ) {
        $secret = sanitize_text_field( $_POST['hachi_2fa_secret_confirm'] );
        $code   = preg_replace( '/\D/', '', sanitize_text_field( $_POST['hachi_2fa_verify'] ) );

        if ( hachi_verify_totp( $secret, $code ) ) {
            update_user_meta( $user_id, '_hachi_2fa_secret',  $secret );
            update_user_meta( $user_id, '_hachi_2fa_enabled', true );

            // バックアップコード生成 (10個)
            $backup_codes = [];
            for ( $i = 0; $i < 10; $i++ ) {
                $backup_codes[] = strtoupper( bin2hex( random_bytes( 4 ) ) );
            }
            update_user_meta( $user_id, '_hachi_2fa_backup_codes', $backup_codes );

            add_action( 'user_profile_update_errors', function ( WP_Error $errors ) {
                $errors->add( 'hachi_2fa_ok', __( '2FAが正常に有効化されました。', 'hachi' ), 'message' );
            } );

            hachi_security_log( '2fa_enabled', [ 'user_id' => $user_id ] );
        } else {
            add_action( 'user_profile_update_errors', function ( WP_Error $errors ) {
                $errors->add( 'hachi_2fa_fail', __( '2FAコードが正しくありません。再試行してください。', 'hachi' ) );
            } );
        }
    }

    // 無効化処理
    if (
        isset( $_POST['hachi_2fa_disable_nonce'] ) &&
        wp_verify_nonce( sanitize_text_field( $_POST['hachi_2fa_disable_nonce'] ?? '' ), 'hachi_2fa_disable_' . $user_id ) &&
        ! empty( sanitize_key( $_POST['hachi_2fa_disable'] ?? '' ) ) &&
        ! hachi_2fa_is_required_for_user( get_userdata( $user_id ) )
    ) {
        update_user_meta( $user_id, '_hachi_2fa_enabled', false );
        delete_user_meta( $user_id, '_hachi_2fa_secret' );
        delete_user_meta( $user_id, '_hachi_2fa_backup_codes' );
        hachi_security_log( '2fa_disabled', [ 'user_id' => $user_id ] );
    }
}

/* ============================================================
   ログイン時の2FA検証
   ============================================================ */

/**
 * ログイン後に2FAコード入力ページにリダイレクト
 */
add_action( 'wp_login', function ( string $user_login, WP_User $user ): void {
    $enabled = get_user_meta( $user->ID, '_hachi_2fa_enabled', true );
    if ( ! $enabled ) {
        return;
    }

    // セッションに一時トークンを保存
    $temp_token = bin2hex( random_bytes( 32 ) );
    set_transient( 'hachi_2fa_temp_' . $user->ID, $temp_token, 5 * MINUTE_IN_SECONDS );

    // 一旦ログアウトして2FA検証ページへ
    wp_logout();
    wp_redirect( add_query_arg( [
        'hachi_2fa'  => 1,
        'uid'        => $user->ID,
        'token'      => $temp_token,
    ], wp_login_url() ) );
    exit;
}, 10, 2 );

/**
 * 2FAコード入力フォームをログインページに追加
 */
add_action( 'login_form', function (): void {
    if ( ! isset( $_GET['hachi_2fa'] ) ) {
        return;
    }
    $uid   = (int) ( $_GET['uid']   ?? 0 );
    $token = sanitize_text_field( $_GET['token'] ?? '' );

    // トークン検証
    $stored = get_transient( 'hachi_2fa_temp_' . $uid );
    if ( ! hash_equals( $stored ?: '', $token ) ) {
        wp_redirect( wp_login_url() );
        exit;
    }
    ?>
    <p style="background:#fff;border:1px solid #c3c4c7;padding:16px;margin-bottom:16px">
        <strong><?php _e( '2要素認証コードを入力してください', 'hachi' ); ?></strong><br>
        <small><?php _e( '認証アプリに表示されている6桁のコード', 'hachi' ); ?></small>
    </p>
    <input type="hidden" name="hachi_2fa_uid"   value="<?php echo esc_attr( $uid ); ?>">
    <input type="hidden" name="hachi_2fa_token" value="<?php echo esc_attr( $token ); ?>">
    <?php wp_nonce_field( 'hachi_2fa_verify', 'hachi_2fa_verify_nonce' ); ?>
    <?php
} );

/**
 * ユーザーがロールで2FA必須か確認
 */
function hachi_2fa_is_required_for_user( WP_User $user ): bool {
    return (bool) array_intersect( (array) $user->roles, HACHI_2FA_REQUIRED_ROLES );
}
