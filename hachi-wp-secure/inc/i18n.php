<?php
/**
 * HACHI Corporate Theme - i18n / Localization Module
 *
 * ロケール判定・言語切替・翻訳ヘルパー
 * 対応言語: 日本語 (ja) / 英語 (en)
 *
 * @package HACHI
 */
defined('ABSPATH') || exit;

// ─── 定数定義 ────────────────────────────────────────────────────────────────

define('HACHI_SUPPORTED_LOCALES', ['ja', 'en']);
define('HACHI_DEFAULT_LOCALE',    'ja');

// ─── ロケール判定 ─────────────────────────────────────────────────────────────

/**
 * 現在のロケールを返す。
 *
 * 優先度:
 *   1. URL パスのプレフィックス（/en/）
 *   2. Cookie（hachi_locale）
 *   3. Accept-Language ヘッダー
 *   4. デフォルト（ja）
 *
 * @return string 'ja' | 'en'
 */
function hachi_get_locale(): string {
    // 優先度1: URL パスプレフィックス（例: /en/ または /en）
    $request_uri = isset($_SERVER['REQUEST_URI']) ? sanitize_text_field(wp_unslash($_SERVER['REQUEST_URI'])) : '/';
    $path_parts  = explode('/', ltrim($request_uri, '/'));
    if (!empty($path_parts[0]) && in_array($path_parts[0], HACHI_SUPPORTED_LOCALES, true)) {
        return $path_parts[0];
    }

    // 優先度2: Cookie
    if (!empty($_COOKIE['hachi_locale']) && in_array($_COOKIE['hachi_locale'], HACHI_SUPPORTED_LOCALES, true)) {
        return sanitize_key($_COOKIE['hachi_locale']);
    }

    // 優先度3: Accept-Language ヘッダー
    $accept_lang = isset($_SERVER['HTTP_ACCEPT_LANGUAGE'])
        ? sanitize_text_field(wp_unslash($_SERVER['HTTP_ACCEPT_LANGUAGE']))
        : '';
    if (!empty($accept_lang)) {
        $parsed = hachi_parse_accept_language($accept_lang);
        foreach ($parsed as $lang) {
            $primary = strtolower(substr($lang, 0, 2));
            if (in_array($primary, HACHI_SUPPORTED_LOCALES, true)) {
                return $primary;
            }
        }
    }

    return HACHI_DEFAULT_LOCALE;
}

/**
 * Accept-Language ヘッダーをパースして言語コードの配列を返す（q値順）。
 *
 * @param  string $header Accept-Language ヘッダー文字列
 * @return string[]
 */
function hachi_parse_accept_language(string $header): array {
    $languages = [];
    foreach (explode(',', $header) as $part) {
        $part = trim($part);
        if (strpos($part, ';q=') !== false) {
            [$lang, $q_str] = explode(';q=', $part, 2);
            $q = (float) $q_str;
        } else {
            $lang = $part;
            $q    = 1.0;
        }
        $languages[trim($lang)] = $q;
    }
    arsort($languages);
    return array_keys($languages);
}

// ─── 翻訳テキスト取得 ─────────────────────────────────────────────────────────

/**
 * 翻訳テキストを返す。
 *
 * @param  string $key    翻訳キー（ドット区切りも可: "nav.home"）
 * @param  string $locale ロケール（省略時は hachi_get_locale()）
 * @return string 翻訳テキスト（見つからなければキーをそのまま返す）
 */
function hachi_t(string $key, string $locale = ''): string {
    static $messages = [];

    if (empty($locale)) {
        $locale = hachi_get_locale();
    }

    // 翻訳ファイルをキャッシュ
    if (!isset($messages[$locale])) {
        $file = HACHI_THEME_DIR . "/languages/{$locale}.php";
        $messages[$locale] = file_exists($file) ? (require $file) : [];
    }

    $translations = $messages[$locale];

    // ドット区切りのネストキーをサポート
    foreach (explode('.', $key) as $segment) {
        if (!is_array($translations) || !array_key_exists($segment, $translations)) {
            return $key; // フォールバック: キーそのまま
        }
        $translations = $translations[$segment];
    }

    return is_string($translations) ? $translations : $key;
}

// ─── 言語切替リンク生成 ───────────────────────────────────────────────────────

/**
 * 言語切替リンクの HTML を返す。
 *
 * @param  string $current_locale 現在のロケール（省略時は hachi_get_locale()）
 * @return string HTML
 */
function hachi_language_switcher(string $current_locale = ''): string {
    if (empty($current_locale)) {
        $current_locale = hachi_get_locale();
    }

    $labels = [
        'ja' => '日本語',
        'en' => 'English',
    ];

    $current_url = hachi_current_url_without_locale();

    $html = '<nav class="hachi-lang-switcher" aria-label="' . esc_attr(hachi_t('nav.language_switcher', $current_locale)) . '">';
    $html .= '<ul class="hachi-lang-list">';

    foreach (HACHI_SUPPORTED_LOCALES as $locale) {
        $is_current = ($locale === $current_locale);
        $url        = hachi_locale_url($locale, $current_url);

        $html .= '<li class="hachi-lang-item' . ($is_current ? ' is-active' : '') . '">';
        if ($is_current) {
            $html .= '<span class="hachi-lang-current" aria-current="true">' . esc_html($labels[$locale]) . '</span>';
        } else {
            $html .= '<a href="' . esc_url($url) . '" hreflang="' . esc_attr($locale) . '" class="hachi-lang-link">'
                   . esc_html($labels[$locale]) . '</a>';
        }
        $html .= '</li>';
    }

    $html .= '</ul></nav>';
    return $html;
}

/**
 * 現在の URL からロケールプレフィックスを除いたパスを返す。
 *
 * @return string
 */
function hachi_current_url_without_locale(): string {
    $request_uri = isset($_SERVER['REQUEST_URI']) ? sanitize_text_field(wp_unslash($_SERVER['REQUEST_URI'])) : '/';
    $parts       = explode('/', ltrim($request_uri, '/'));

    if (!empty($parts[0]) && in_array($parts[0], HACHI_SUPPORTED_LOCALES, true)) {
        array_shift($parts);
    }

    return '/' . implode('/', $parts);
}

/**
 * 指定ロケールの URL を生成する。
 *
 * @param  string $locale ロケール
 * @param  string $path   ロケールプレフィックスなしのパス
 * @return string
 */
function hachi_locale_url(string $locale, string $path = '/'): string {
    $base = home_url('/');

    if ($locale === HACHI_DEFAULT_LOCALE) {
        // デフォルトロケール（ja）はプレフィックスなし
        return $base . ltrim($path, '/');
    }

    return $base . $locale . '/' . ltrim($path, '/');
}

// ─── hreflang タグ出力 ────────────────────────────────────────────────────────

/**
 * <head> 内に hreflang リンクタグを出力するフック。
 */
function hachi_output_hreflang_tags(): void {
    $current_path = hachi_current_url_without_locale();

    foreach (HACHI_SUPPORTED_LOCALES as $locale) {
        $url = hachi_locale_url($locale, $current_path);
        printf(
            '<link rel="alternate" hreflang="%s" href="%s">' . PHP_EOL,
            esc_attr($locale),
            esc_url($url)
        );
    }
    // x-default は日本語（デフォルトロケール）を指す
    printf(
        '<link rel="alternate" hreflang="x-default" href="%s">' . PHP_EOL,
        esc_url(hachi_locale_url(HACHI_DEFAULT_LOCALE, $current_path))
    );
}
add_action('wp_head', 'hachi_output_hreflang_tags');

// ─── 言語メタデータ定義 ───────────────────────────────────────────────────────

/**
 * サポート言語のメタデータを返す。
 *
 * @return array<string, array{label: string, native: string, dir: string, locale_code: string}>
 */
function hachi_get_language_meta(): array {
    return [
        'ja' => [
            'label'       => '日本語',
            'native'      => '日本語',
            'dir'         => 'ltr',
            'locale_code' => 'ja_JP',
        ],
        'en' => [
            'label'       => 'English',
            'native'      => 'English',
            'dir'         => 'ltr',
            'locale_code' => 'en_US',
        ],
    ];
}

/**
 * 現在のロケールの HTML lang 属性値を返す。
 *
 * @return string 例: 'ja', 'en'
 */
function hachi_html_lang(): string {
    return hachi_get_locale();
}

// ─── WordPress ロケール連携 ───────────────────────────────────────────────────

/**
 * URL ベースのロケールを WordPress のロケールに反映する。
 * `locale` フィルターにフックする。
 *
 * @param  string $locale WordPress ロケール文字列
 * @return string
 */
function hachi_filter_locale(string $locale): string {
    $meta = hachi_get_language_meta();
    $lang = hachi_get_locale();

    return isset($meta[$lang]) ? $meta[$lang]['locale_code'] : $locale;
}
add_filter('locale', 'hachi_filter_locale');

// ─── Cookie 保存（JS から呼び出せるよう REST エンドポイントを登録） ────────────

/**
 * ロケール切替用 REST API エンドポイント。
 * POST /wp-json/hachi/v1/locale { locale: 'en' }
 */
function hachi_register_locale_endpoint(): void {
    register_rest_route('hachi/v1', '/locale', [
        'methods'             => 'POST',
        'callback'            => 'hachi_rest_set_locale',
        'permission_callback' => '__return_true',
        'args'                => [
            'locale' => [
                'required'          => true,
                'sanitize_callback' => 'sanitize_key',
                'validate_callback' => fn($v) => in_array($v, HACHI_SUPPORTED_LOCALES, true),
            ],
        ],
    ]);
}
add_action('rest_api_init', 'hachi_register_locale_endpoint');

/**
 * ロケール Cookie を設定してレスポンスを返す。
 *
 * @param  WP_REST_Request $request
 * @return WP_REST_Response
 */
function hachi_rest_set_locale(WP_REST_Request $request): WP_REST_Response {
    $locale = $request->get_param('locale');
    setcookie('hachi_locale', $locale, [
        'expires'  => time() + 365 * DAY_IN_SECONDS,
        'path'     => '/',
        'secure'   => is_ssl(),
        'httponly' => false, // JS からも読み取れるようにする
        'samesite' => 'Lax',
    ]);
    return new WP_REST_Response(['success' => true, 'locale' => $locale], 200);
}
