<?php
/**
 * HACHI Corporate Theme - functions.php (Security Enhanced v2.0)
 * @package HACHI
 */
defined('ABSPATH') || exit;

define('HACHI_VERSION',   '2.0.0');
define('HACHI_THEME_DIR', get_template_directory());
define('HACHI_THEME_URI', get_template_directory_uri());

// セキュリティモジュール読み込み
foreach (['/inc/security.php', '/inc/rate-limiter.php', '/inc/two-factor.php'] as $mod) {
    $p = HACHI_THEME_DIR . $mod;
    if (file_exists($p)) require_once $p;
}

// Supabase クライアント (バックエンド拡張より先に読み込む)
$supabase_mod = HACHI_THEME_DIR . '/inc/supabase.php';
if (file_exists($supabase_mod)) require_once $supabase_mod;

// バックエンド拡張モジュール読み込み
foreach (['/inc/contact-handler.php', '/inc/rest-api.php'] as $mod) {
    $p = HACHI_THEME_DIR . $mod;
    if (file_exists($p)) require_once $p;
}

// グロース・パフォーマンスモジュール読み込み
foreach (['/inc/seo.php', '/inc/structured-data.php', '/inc/analytics.php', '/inc/performance.php'] as $mod) {
    $p = HACHI_THEME_DIR . $mod;
    if (file_exists($p)) require_once $p;
}

function hachi_setup(): void {
    load_theme_textdomain('hachi', HACHI_THEME_DIR . '/languages');
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_theme_support('html5', ['comment-list','comment-form','search-form','gallery','caption','style','script']);
    add_theme_support('custom-logo', ['height'=>80,'width'=>200,'flex-height'=>true]);
    add_image_size('hachi-hero',    1920, 1080, true);
    add_image_size('hachi-card',     800,  600, true);
    add_image_size('hachi-portrait', 600,  800, true);
    register_nav_menus(['primary'=>__('Primary Navigation','hachi'),'footer-nav'=>__('Footer Navigation','hachi')]);
}
add_action('after_setup_theme', 'hachi_setup');

function hachi_enqueue_assets(): void {
    wp_enqueue_style('hachi-fonts',
        'https://fonts.googleapis.com/css2?family=Noto+Serif+JP:wght@300;400&family=Noto+Sans+JP:wght@300;400;500&family=Montserrat:wght@300;400;500;700&display=swap',
        [], null);
    wp_enqueue_style('hachi-style', HACHI_THEME_URI.'/style.css', ['hachi-fonts'], HACHI_VERSION);
    wp_enqueue_script('hachi-main', HACHI_THEME_URI.'/js/main.js', [], HACHI_VERSION, true);
    wp_localize_script('hachi-main','hachiData',[
        'ajaxUrl'          => admin_url('admin-ajax.php'),
        'nonce'            => wp_create_nonce('hachi_nonce'),
        'homeUrl'          => home_url('/'),
        'recaptchaSiteKey' => defined('HACHI_RECAPTCHA_SITE_KEY') ? HACHI_RECAPTCHA_SITE_KEY : '',
    ]);

    // reCAPTCHA v3 スクリプトの読み込み（サイトキーが設定されている場合のみ）
    if (defined('HACHI_RECAPTCHA_SITE_KEY') && !empty(HACHI_RECAPTCHA_SITE_KEY)) {
        wp_enqueue_script(
            'google-recaptcha',
            'https://www.google.com/recaptcha/api.js?render=' . esc_attr(HACHI_RECAPTCHA_SITE_KEY),
            [],
            null,
            true
        );
    }
}
add_action('wp_enqueue_scripts', 'hachi_enqueue_assets');

function hachi_register_post_types(): void {
    register_post_type('hachi_news',[
        'labels'       => ['name'=>__('News','hachi'),'singular_name'=>__('News','hachi'),'not_found'=>__('No News found','hachi')],
        'public'       => true,'show_in_menu'=>true,'show_in_rest'=>false,
        'menu_icon'    => 'dashicons-megaphone',
        'supports'     => ['title','editor','thumbnail','excerpt','custom-fields'],
        'has_archive'  => true,'rewrite'=>['slug'=>'news','with_front'=>false],'menu_position'=>5,
    ]);
    register_post_type('hachi_work',[
        'labels'       => ['name'=>__('Works','hachi'),'singular_name'=>__('Work','hachi')],
        'public'       => true,'show_in_menu'=>true,'show_in_rest'=>false,
        'menu_icon'    => 'dashicons-portfolio',
        'supports'     => ['title','editor','thumbnail','excerpt','custom-fields'],
        'has_archive'  => true,'rewrite'=>['slug'=>'works','with_front'=>false],'menu_position'=>6,
    ]);
}
add_action('init','hachi_register_post_types');

function hachi_register_taxonomies(): void {
    register_taxonomy('news_category','hachi_news',[
        'labels'            => ['name'=>__('News Categories','hachi'),'singular_name'=>__('News Category','hachi')],
        'hierarchical'      => true,'show_in_rest'=>false,'show_admin_column'=>true,
        'rewrite'           => ['slug'=>'news-category'],
    ]);
}
add_action('init','hachi_register_taxonomies');

function hachi_handle_contact(): void {
    if (!wp_verify_nonce(sanitize_text_field($_POST['nonce']??''),'hachi_nonce')) {
        hachi_security_log('contact_csrf_fail',['ip'=>hachi_get_client_ip()]);
        wp_send_json_error(['message'=>__('不正なリクエストです。','hachi')],403);
    }
    if (!empty($_POST['website'])) {
        hachi_security_log('honeypot_triggered',['ip'=>hachi_get_client_ip()]);
        wp_send_json_success(['message'=>'ok']);
    }
    $name    = sanitize_text_field($_POST['contact_name']??'');
    $company = sanitize_text_field($_POST['contact_company']??'');
    $email   = sanitize_email($_POST['contact_email']??'');
    $cat     = sanitize_text_field($_POST['contact_cat']??'');
    $message = sanitize_textarea_field($_POST['contact_message']??'');
    $errors  = [];
    if (empty($name)||mb_strlen($name)>100) $errors['name'] = __('お名前をご入力ください。','hachi');
    if (empty($email)||!is_email($email))    $errors['email'] = __('正しいメールアドレスを入力してください。','hachi');
    if (empty($message))                     $errors['message'] = __('お問い合わせ内容をご入力ください。','hachi');
    elseif (mb_strlen($message)>2000)        $errors['message'] = __('2000文字以内でご入力ください。','hachi');
    // 問い合わせ種別: contact-handler.php の hachi_get_contact_categories() が利用可能な場合は動的に取得
    if (function_exists('hachi_get_contact_categories')) {
        $allowed_cats = array_merge([''], array_column(hachi_get_contact_categories(), 'label'));
    } else {
        $allowed_cats = ['','PACE v3.0 デモ申込み','REBOOT-WORK 資料請求','一般お問い合わせ'];
    }
    if (!in_array($cat,$allowed_cats,true)) $cat='一般お問い合わせ';
    if (!empty($errors)) { wp_send_json_error(['errors'=>$errors],422); }
    $name  = str_replace(["\r","\n"],'',$name);
    $email = str_replace(["\r","\n"],'',$email);
    $sent  = wp_mail(get_option('admin_email'),
        sprintf('[HACHI お問い合わせ] %s 様より',$name),
        hachi_build_email_body($name,$company,$email,$cat,$message),
        ['Content-Type: text/html; charset=UTF-8',sprintf('Reply-To: %s <%s>',$name,$email)]
    );
    if (!$sent) { wp_send_json_error(['message'=>__('メール送信に失敗しました。','hachi')],500); }
    wp_mail($email,'お問い合わせを受け付けました / 株式会社HACHI',
        hachi_build_autoreply_body($name),['Content-Type: text/html; charset=UTF-8']);
    hachi_security_log('contact_success',['ip'=>hachi_get_client_ip(),'cat'=>$cat]);
    wp_send_json_success(['message'=>__('お問い合わせを受け付けました。','hachi')]);
}
add_action('wp_ajax_hachi_contact','hachi_handle_contact');
add_action('wp_ajax_nopriv_hachi_contact','hachi_handle_contact');

function hachi_build_email_body(string $n,string $co,string $em,string $cat,string $msg): string {
    ob_start(); ?>
    <!DOCTYPE html><html lang="ja"><head><meta charset="UTF-8"></head>
    <body style="font-family:sans-serif;line-height:1.8;color:#333;max-width:600px;margin:0 auto;padding:40px 20px">
    <h1 style="font-size:20px;border-bottom:2px solid #E8622A;padding-bottom:12px">新しいお問い合わせ</h1>
    <table style="width:100%;border-collapse:collapse;margin-top:24px">
        <?php foreach(['名前'=>$n,'会社名'=>$co,'メール'=>$em,'種別'=>$cat,'内容'=>$msg] as $l=>$v): ?>
        <tr><th style="width:100px;padding:12px 0;text-align:left;font-size:12px;color:#888;font-weight:400;vertical-align:top"><?=esc_html($l)?></th>
            <td style="padding:12px 0;border-bottom:1px solid #eee"><?=('内容'===$l)?nl2br(esc_html($v)):esc_html($v)?></td></tr>
        <?php endforeach; ?>
    </table></body></html>
    <?php return ob_get_clean();
}

function hachi_build_autoreply_body(string $name): string {
    ob_start(); ?>
    <!DOCTYPE html><html lang="ja"><head><meta charset="UTF-8"></head>
    <body style="font-family:sans-serif;line-height:1.8;color:#333;max-width:600px;margin:0 auto;padding:40px 20px">
    <h1 style="font-size:20px;font-weight:300">お問い合わせありがとうございます</h1>
    <p style="margin-top:24px"><?=esc_html($name)?> 様</p>
    <p>HACHIへのお問い合わせありがとうございます。<strong>2営業日以内</strong>にご連絡いたします。</p>
    <hr style="border:none;border-top:1px solid #eee;margin:32px 0">
    <p style="font-size:12px;color:#888">株式会社HACHI<br>東京都武蔵野市吉祥寺本町1-13-2 5F<br>
    <a href="https://hachi.co.jp" style="color:#E8622A">https://hachi.co.jp</a></p>
    </body></html>
    <?php return ob_get_clean();
}

function hachi_arrow_icon(): void {
    echo '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true" style="width:14px;height:14px"><path d="M5 12h14M12 5l7 7-7 7"/></svg>';
}
function hachi_get_date(int $id=0): string { return date_i18n('Y.m.d',get_the_date('U',$id)); }
function hachi_section_label(string $text,string $class=''): void {
    printf('<div class="label %s">%s</div>',esc_attr($class),esc_html($text));
}
function hachi_get_recent_news(int $n=3): WP_Query {
    return new WP_Query(['post_type'=>'hachi_news','posts_per_page'=>$n,'post_status'=>'publish','no_found_rows'=>true]);
}
function hachi_get_ticker_news(): ?WP_Post {
    $q=new WP_Query(['post_type'=>'hachi_news','posts_per_page'=>1,'post_status'=>'publish','no_found_rows'=>true]);
    return $q->have_posts()?$q->posts[0]:null;
}

/**
 * Fetch note.com RSS feed for a given username.
 * Returns array of normalized items: [ ['date','title','url','excerpt','thumbnail'], ... ]
 * Results are cached for 1 hour via transient.
 */
function hachi_get_note_posts(int $limit = 10): array {
    $username = trim( (string) get_theme_mod( 'hachi_note_username', '' ) );
    if ( $username === '' ) return [];

    $cache_key = 'hachi_note_feed_' . md5( $username . '_' . $limit );
    $cached    = get_transient( $cache_key );
    if ( is_array( $cached ) ) return $cached;

    if ( ! function_exists( 'fetch_feed' ) ) {
        include_once ABSPATH . WPINC . '/feed.php';
    }
    $feed_url = 'https://note.com/' . rawurlencode( $username ) . '/rss';
    $feed     = fetch_feed( $feed_url );
    if ( is_wp_error( $feed ) ) {
        set_transient( $cache_key, [], 15 * MINUTE_IN_SECONDS );
        return [];
    }

    $max   = $feed->get_item_quantity( $limit );
    $items = $feed->get_items( 0, $max );
    $out   = [];
    foreach ( $items as $item ) {
        $thumb = '';
        $enc   = $item->get_enclosure();
        if ( $enc && $enc->get_link() ) {
            $thumb = esc_url_raw( $enc->get_link() );
        } else {
            // fallback: try media:thumbnail or first <img> in content
            if ( preg_match( '/<img[^>]+src=["\']([^"\']+)["\']/i', (string) $item->get_content(), $m ) ) {
                $thumb = esc_url_raw( $m[1] );
            }
        }
        $out[] = [
            'date'      => $item->get_date( 'U' ) ?: 0,
            'title'     => (string) $item->get_title(),
            'url'       => esc_url_raw( (string) $item->get_permalink() ),
            'excerpt'   => wp_trim_words( wp_strip_all_tags( (string) $item->get_description() ), 36, '…' ),
            'thumbnail' => $thumb,
        ];
    }
    set_transient( $cache_key, $out, HOUR_IN_SECONDS );
    return $out;
}

/* ============================================================
   AUTO CLASSIFIER — News / Work / Blog の自動カテゴリ判定
   ============================================================ */

/**
 * カテゴリ判定辞書
 * 各語に強度スコア（3=強, 2=中, 1=弱）を紐付け。
 * タイトルマッチは本文の 2 倍に重み付けされる。
 */
function hachi_classifier_dict(): array {
    return [
        'work' => [
            3 => [
                '導入事例', 'ケーススタディ', 'case study', 'casestudy', '事例紹介',
                '導入事例集', '活用事例', '利用事例', '事例レポート', 'お客様の声',
                'ユーザーストーリー', '導入企業', '導入クラブ', '導入チーム',
                '導入実績', '採用事例', 'ビフォーアフター', 'before/after',
            ],
            2 => [
                '導入', '導入後', '導入前', '導入検討', '導入支援',
                '実績', '成果', '効果', '改善', '削減', '向上', '達成', '短縮', '増加',
                'クライアント', '顧客事例', 'お客様', 'パートナー事例',
                '共創', '協業', '伴走', 'プロジェクト実績',
                '社様', 'チーム様', 'クラブ様',
            ],
            1 => [
                'プロクラブ', 'プロチーム', '協会', '選手', 'アスリート',
                '対談', 'インタビュー',
            ],
        ],
        'news' => [
            3 => [
                'プレスリリース', 'press release', 'プレスリ', '報道発表',
                'お知らせ', 'ご案内', 'リリース情報',
                '受賞', '採択', '選出', '認定', '認証取得', '許認可',
                '資金調達', 'シード調達', 'シリーズa', '出資', '第三者割当',
                '業務提携', '資本提携', 'アライアンス', 'mou', '締結', '調印',
                'メディア掲載', '取材記事', '報道', '特集', 'インタビュー掲載',
                'イベント開催', '登壇', '出展', '出演', 'カンファレンス',
                '補助金採択', '助成金採択', 'ものづくり補助金', 'it導入補助金',
            ],
            2 => [
                '発表', '公開', '開始', 'ローンチ', 'launch', 'リニューアル',
                '開催', 'セミナー', 'ウェビナー', '説明会',
                '新機能', '新サービス', '新プラン',
                '提携', '連携', '参画',
                '設立', '創業', '周年', '移転', '開設',
                '記者発表', '記者会見',
            ],
            1 => [
                '日時', '会場', '主催', '後援', '協賛',
                'このたび', '本日', '本年度',
            ],
        ],
        'blog' => [
            3 => [
                'とは', 'について解説', '完全ガイド', '徹底解説', '入門',
                '選び方', '使い方', 'やり方', 'ステップ', 'チェックリスト',
                'ロードマップ', '比較', '違い', 'メリット・デメリット',
                '初心者', '基礎知識', '押さえておきたい',
                'まとめ', '振り返り', '知見', '学び', '気づき', 'tips',
                'how to', 'howto',
            ],
            2 => [
                '考察', '解説', '説明', 'レポート', '書評', 'レビュー',
                'トレンド', '動向', '展望', '予測', '課題', '可能性',
                '論文', '研究', 'エビデンス', 'データ分析', 'インサイト',
                'コラム', 'エッセイ', 'ノート', '覚書',
                'ポイント', 'コツ', '秘訣', '原則', 'フレームワーク',
                'アーキテクチャ', '設計思想',
            ],
            1 => [
                'vol.', '第', '連載', 'シリーズ',
                '所感', '感想', '雑感', '私見',
                '最近', 'このごろ',
            ],
        ],
    ];
}

/**
 * タイトル + 本文からコンテンツをカテゴリ分類する。
 * 返値: 'work' | 'news' | 'blog'
 */
function hachi_classify_content( string $title, string $body = '' ): string {
    $title_l = mb_strtolower( wp_strip_all_tags( $title ) );
    $body_l  = mb_strtolower( wp_strip_all_tags( $body ) );

    $scores = [ 'work' => 0, 'news' => 0, 'blog' => 0 ];
    foreach ( hachi_classifier_dict() as $cat => $tiers ) {
        foreach ( $tiers as $weight => $words ) {
            foreach ( $words as $word ) {
                $w = mb_strtolower( $word );
                if ( $w === '' ) continue;
                // Title hit: weight × 2
                if ( mb_strpos( $title_l, $w ) !== false ) {
                    $scores[ $cat ] += $weight * 2;
                }
                // Body hit: weight × 1
                if ( $body_l !== '' && mb_strpos( $body_l, $w ) !== false ) {
                    $scores[ $cat ] += $weight;
                }
            }
        }
    }

    // 判定閾値
    if ( $scores['work'] >= 3 ) return 'work';
    if ( $scores['news'] >= 3 ) return 'news';
    if ( $scores['blog'] >= 2 ) return 'blog';

    // 同点時の優先順位 Work > News > Blog
    $max = max( $scores );
    if ( $max === 0 ) return 'blog';
    if ( $scores['work'] === $max ) return 'work';
    if ( $scores['news'] === $max ) return 'news';
    return 'blog';
}

/**
 * 統合アイテム取得：WP hachi_news + note.com を正規化し、自動分類済みで返す。
 * @param array $args [ 'category' => 'all|work|news|blog|note', 'limit' => int ]
 * @return array 正規化された items 配列（date 降順）
 */
function hachi_get_classified_items( array $args = [] ): array {
    $args = wp_parse_args( $args, [
        'category' => 'all',
        'limit'    => 30,
    ] );
    $cat_filter = $args['category'];

    $items = [];

    // ---- WP hachi_news ----
    if ( $cat_filter !== 'note' ) {
        $q = new WP_Query( [
            'post_type'      => 'hachi_news',
            'posts_per_page' => 60,
            'post_status'    => 'publish',
            'orderby'        => 'date',
            'order'          => 'DESC',
            'no_found_rows'  => true,
        ] );
        while ( $q->have_posts() ) { $q->the_post();
            $id    = get_the_ID();
            // 1 時間キャッシュ（記事編集でクリア）
            $cache_key = 'hachi_cls_' . $id . '_' . get_post_modified_time( 'U', false, $id );
            $cat = get_transient( $cache_key );
            if ( ! $cat ) {
                $cat = hachi_classify_content( get_the_title(), (string) get_the_content() );
                set_transient( $cache_key, $cat, DAY_IN_SECONDS );
            }
            $items[] = [
                'source'    => 'wp',
                'category'  => $cat,
                'date_ts'   => (int) get_the_date( 'U' ),
                'date_str'  => hachi_get_date(),
                'title'     => get_the_title(),
                'url'       => get_permalink(),
                'excerpt'   => wp_trim_words( get_the_excerpt(), 36, '…' ),
                'thumbnail' => has_post_thumbnail() ? get_the_post_thumbnail_url( null, 'medium' ) : '',
            ];
        }
        wp_reset_postdata();
    }

    // ---- note.com RSS ----
    if ( $cat_filter === 'all' || $cat_filter === 'note' || $cat_filter === 'work' || $cat_filter === 'news' || $cat_filter === 'blog' ) {
        foreach ( hachi_get_note_posts( 30 ) as $n ) {
            $cat = hachi_classify_content( $n['title'], $n['excerpt'] );
            $items[] = [
                'source'    => 'note',
                'category'  => $cat,
                'date_ts'   => (int) $n['date'],
                'date_str'  => $n['date'] ? date_i18n( 'Y.m.d', (int) $n['date'] ) : '',
                'title'     => $n['title'],
                'url'       => $n['url'],
                'excerpt'   => $n['excerpt'],
                'thumbnail' => $n['thumbnail'],
            ];
        }
    }

    // カテゴリフィルタ
    if ( $cat_filter !== 'all' ) {
        if ( $cat_filter === 'note' ) {
            $items = array_values( array_filter( $items, fn( $i ) => $i['source'] === 'note' ) );
        } else {
            $items = array_values( array_filter( $items, fn( $i ) => $i['category'] === $cat_filter ) );
        }
    }

    // 日付降順
    usort( $items, fn( $a, $b ) => $b['date_ts'] <=> $a['date_ts'] );

    return array_slice( $items, 0, (int) $args['limit'] );
}

/* ============================================================
   END AUTO CLASSIFIER
   ============================================================ */

/**
 * Customizer: note.com username setting
 */
add_action( 'customize_register', function ( $wp_customize ) {
    $wp_customize->add_setting( 'hachi_note_username', [
        'default'           => '',
        'sanitize_callback' => 'sanitize_text_field',
        'capability'        => 'edit_theme_options',
    ] );
    $wp_customize->add_control( 'hachi_note_username', [
        'label'       => __( 'note.com ユーザー名', 'hachi' ),
        'description' => __( 'https://note.com/{username} の {username} 部分。RSS で記事を取得します。', 'hachi' ),
        'section'     => 'title_tagline',
        'type'        => 'text',
    ] );
} );

add_action('add_meta_boxes',function():void{
    add_meta_box('hachi_news_meta',__('News Details','hachi'),'hachi_news_meta_callback','hachi_news','side','high');
});
function hachi_news_meta_callback(WP_Post $post): void {
    wp_nonce_field('hachi_news_meta_save','hachi_news_meta_nonce');
    $type=get_post_meta($post->ID,'_hachi_news_type',true)?:'news';
    echo '<p><label><strong>Type</strong></label><br><select name="hachi_news_type" style="width:100%;margin-top:6px">';
    foreach(['news'=>'NEWS','press'=>'PRESS RELEASE','media'=>'MEDIA','blog'=>'BLOG'] as $v=>$l)
        echo '<option value="'.esc_attr($v).'"'.selected($type,$v,false).'>'.esc_html($l).'</option>';
    echo '</select></p>';
}
add_action('save_post_hachi_news',function(int $id):void{
    if(!isset($_POST['hachi_news_meta_nonce'])||!wp_verify_nonce($_POST['hachi_news_meta_nonce'],'hachi_news_meta_save')
       ||(defined('DOING_AUTOSAVE')&&DOING_AUTOSAVE)||!current_user_can('edit_post',$id)) return;
    $t=sanitize_key($_POST['hachi_news_type']??'news');
    update_post_meta($id,'_hachi_news_type',in_array($t,['news','press','media','blog'],true)?$t:'news');
});

add_filter('excerpt_length',fn()=>80);
add_filter('excerpt_more',fn()=>'…');
add_filter('body_class',function(array $c):array{if(is_front_page())$c[]='is-front-page';return $c;});

// ─── WordPress 不要機能の無効化 ──────────────────────────────────
// NOTE: xmlrpc・oEmbed・バージョン漏洩は security.php で対応済み。
// ここでは残りの不要機能を無効化する。

// REST API の oEmbed エンドポイントを無効化（フロントエンド埋め込み機能は不要）
remove_action('wp_head', 'wp_oembed_add_discovery_links');
remove_action('wp_head', 'wp_oembed_add_host_js');
remove_filter('oembed_dataparse', 'wp_filter_oembed_result', 10);
remove_action('rest_api_init', 'wp_oembed_register_route');
add_filter('embed_oembed_discover', '__return_false');

// Gutenberg ブロックエディタのフルスクリーンモードをデフォルト無効化（管理画面 UX）
add_action('enqueue_block_editor_assets', function(): void {
    wp_add_inline_script('wp-blocks',
        "wp.data.dispatch('core/edit-post').toggleFeature('fullscreenMode')",
        'after');
});


function hachi_activate():void{hachi_register_post_types();hachi_register_taxonomies();flush_rewrite_rules();}
register_activation_hook(HACHI_THEME_DIR.'/functions.php','hachi_activate');

// wp_body_open のフォールバック (WP 5.2未満との互換性)
if ( ! function_exists( 'wp_body_open' ) ) {
    function wp_body_open(): void { do_action( 'wp_body_open' ); }
}
