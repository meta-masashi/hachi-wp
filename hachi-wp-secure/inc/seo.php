<?php
/**
 * HACHI Theme — inc/seo.php
 *
 * SEO メタデータ（タイトル最適化・OGP・Twitter Card・カノニカルURL・robots meta）
 * wp_head に自動出力。
 *
 * @package HACHI
 */

defined( 'ABSPATH' ) || exit;

/* ============================================================
   1. タイトルタグ最適化
   ============================================================ */

/**
 * ページ種別ごとのタイトル文字列を返す。
 * add_theme_support('title-tag') が有効な場合に document_title_parts フィルタで制御する。
 */
add_filter( 'document_title_parts', function ( array $title ): array {
    $path = trim( parse_url( $_SERVER['REQUEST_URI'] ?? '', PHP_URL_PATH ), '/' );

    if ( $path === 'privacy-policy' ) {
        $title['title'] = 'プライバシーポリシー | HACHI';
        unset( $title['tagline'] );
        return $title;
    }

    if ( is_front_page() ) {
        $title['title'] = 'HACHI | 身体知を、再現可能な判断知へ。';
        unset( $title['tagline'] );
        return $title;
    }

    if ( is_singular( 'hachi_news' ) ) {
        $title['title'] = get_the_title() . ' | HACHI ニュース';
        return $title;
    }

    if ( is_post_type_archive( 'hachi_news' ) ) {
        $title['title'] = 'ニュース・知見 | HACHI';
        return $title;
    }

    if ( is_page( 'service' ) || ( is_page() && get_post_field( 'post_name', get_the_ID() ) === 'service' ) ) {
        $title['title'] = 'サービス｜コンディション・インサイト / HACHI Fieldwork | HACHI';
        return $title;
    }

    if ( is_page( 'company' ) || ( is_page() && get_post_field( 'post_name', get_the_ID() ) === 'company' ) ) {
        $title['title'] = '会社概要｜株式会社HACHI（吉祥寺）';
        return $title;
    }

    if ( is_page( 'about' ) || ( is_page() && get_post_field( 'post_name', get_the_ID() ) === 'about' ) ) {
        $title['title'] = 'About Us｜身体知を、再現可能な判断知へ。 | HACHI';
        return $title;
    }

    if ( is_page( 'contact' ) || ( is_page() && get_post_field( 'post_name', get_the_ID() ) === 'contact' ) ) {
        $title['title'] = 'お問い合わせ | HACHI';
        return $title;
    }

    // その他のページ: タイトル | HACHI
    if ( ! empty( $title['title'] ) ) {
        $title['site'] = 'HACHI';
    }

    return $title;
}, 10 );

add_filter( 'document_title_separator', fn() => '|' );

/* ============================================================
   2. OGP / Twitter Card / カノニカルURL / robots meta
   ============================================================ */

add_action( 'wp_head', 'hachi_output_seo_meta', 1 );

function hachi_output_seo_meta(): void {

    // --- 基本情報の収集 ---
    $site_name    = get_bloginfo( 'name' ) ?: 'HACHI';
    $site_url     = home_url( '/' );
    $default_desc = '株式会社HACHIは、身体領域の専門知を観察・構造化・検証可能な判断知へ変換する会社です。コンディション・インサイトと HACHI Fieldwork を提供しています。';
    $default_img  = HACHI_THEME_URI . '/assets/og-image.png';

    // ページ種別ごとの description / OGP 画像 / canonical / robots
    $description = $default_desc;
    $og_image    = $default_img;
    $canonical   = hachi_get_canonical_url();
    $robots      = 'index, follow';
    $og_type     = 'website';
    $og_title    = get_bloginfo( 'name' );

    if ( is_front_page() ) {
        $og_title    = 'HACHI | 身体知を、再現可能な判断知へ。';
        $description = '経験と勘で動く現場を、組織が引き継げる判断の体系に変える。社員の身体・睡眠・集中・疲労を、観察できる材料にします。';
    } elseif ( is_page( 'service' ) || ( is_page() && get_post_field( 'post_name', get_the_ID() ) === 'service' ) ) {
        $og_title    = 'サービス｜コンディション・インサイト / HACHI Fieldwork | HACHI';
        $description = '社員の状態変化のサインを、組織として早めにつかむ。コンディション・インサイトと HACHI Fieldwork で、観察と介入を一続きの仕組みにします。';
    } elseif ( is_page( 'company' ) || ( is_page() && get_post_field( 'post_name', get_the_ID() ) === 'company' ) ) {
        $og_title    = '会社概要｜株式会社HACHI（吉祥寺）';
        $description = '株式会社HACHI｜2022年3月設立、代表取締役社長 佐々木譲崇。東京都武蔵野市吉祥寺本町を拠点に、社員のコンディションを組織で見える形にする事業を展開しています。';
    } elseif ( is_page( 'about' ) || ( is_page() && get_post_field( 'post_name', get_the_ID() ) === 'about' ) ) {
        $og_title    = 'About Us｜身体知を、再現可能な判断知へ。 | HACHI';
        $description = '身体知を、再現可能な判断知へ。経験と勘を、引き継げる体系に。HACHI の思想と専門性をご紹介します。';
    } elseif ( is_page( 'contact' ) || ( is_page() && get_post_field( 'post_name', get_the_ID() ) === 'contact' ) ) {
        $og_title    = 'お問い合わせ | HACHI';
        $description = 'コンディション・インサイト / HACHI Fieldwork についてのお問い合わせはこちらから。';
        $robots      = 'noindex, follow';
    } elseif ( is_singular( 'hachi_news' ) ) {
        $og_title = get_the_title();
        $og_type  = 'article';
        $excerpt  = get_the_excerpt();
        if ( $excerpt ) {
            $description = wp_strip_all_tags( $excerpt );
        }
        if ( has_post_thumbnail() ) {
            $thumb = wp_get_attachment_image_src( get_post_thumbnail_id(), 'large' );
            if ( $thumb ) {
                $og_image = $thumb[0];
            }
        }
    } elseif ( is_post_type_archive( 'hachi_news' ) ) {
        $og_title    = 'ニュース | HACHI';
        $description = 'HACHI のサービス更新と現場で気づいたことを記録します。';
    } elseif ( is_tag() || is_search() ) {
        $robots = 'noindex, follow'; // タグ・検索結果ページはインデックス不要
    }

    // --- 出力 ---
    ?>
<!-- SEO Meta (hachi/inc/seo.php) -->
<meta name="description" content="<?php echo esc_attr( $description ); ?>">
<meta name="robots" content="<?php echo esc_attr( $robots ); ?>">
<link rel="canonical" href="<?php echo esc_url( $canonical ); ?>">

<!-- Open Graph -->
<meta property="og:type"        content="<?php echo esc_attr( $og_type ); ?>">
<meta property="og:site_name"   content="<?php echo esc_attr( $site_name ); ?>">
<meta property="og:title"       content="<?php echo esc_attr( $og_title ); ?>">
<meta property="og:description" content="<?php echo esc_attr( $description ); ?>">
<meta property="og:url"         content="<?php echo esc_url( $canonical ); ?>">
<meta property="og:image"       content="<?php echo esc_url( $og_image ); ?>">
<meta property="og:image:width" content="1200">
<meta property="og:image:height" content="630">
<meta property="og:image:alt"   content="<?php echo esc_attr( $og_title ); ?>">
<meta property="og:locale"      content="ja_JP">
<?php if ( $og_type === 'article' && is_singular() ) : ?>
<meta property="article:published_time" content="<?php echo esc_attr( get_the_date( 'c' ) ); ?>">
<meta property="article:modified_time"  content="<?php echo esc_attr( get_the_modified_date( 'c' ) ); ?>">
<?php endif; ?>

<!-- Twitter Card -->
<meta name="twitter:card"        content="summary_large_image">
<meta name="twitter:site"        content="@hachi_inc">
<meta name="twitter:title"       content="<?php echo esc_attr( $og_title ); ?>">
<meta name="twitter:description" content="<?php echo esc_attr( $description ); ?>">
<meta name="twitter:image"       content="<?php echo esc_url( $og_image ); ?>">
<!-- /SEO Meta -->
    <?php
}

/* ============================================================
   3. カノニカルURL 生成ヘルパー
   ============================================================ */

function hachi_get_canonical_url(): string {
    if ( is_singular() ) {
        return (string) get_permalink();
    }
    if ( is_front_page() ) {
        return home_url( '/' );
    }
    if ( is_post_type_archive( 'hachi_news' ) ) {
        return (string) get_post_type_archive_link( 'hachi_news' );
    }
    if ( is_page() ) {
        return (string) get_permalink();
    }
    // ページネーション等: クエリなしのパス
    global $wp;
    return home_url( add_query_arg( [], $wp->request ) );
}

/* ============================================================
   4. WordPress デフォルトの SEO 競合タグ除去
   ============================================================ */

// デフォルトの description（出力されない場合が多いが念のため除去）
remove_action( 'wp_head', 'wp_generator' ); // バージョン情報（security.php でも対応済みだが二重確認）

/* ============================================================
   5. hreflang（単一言語でも自己参照を出すと Google 推奨）
   ============================================================ */
add_action( 'wp_head', function (): void {
    $url = hachi_get_canonical_url();
    printf( '<link rel="alternate" hreflang="ja-JP" href="%s">' . "\n", esc_url( $url ) );
    printf( '<link rel="alternate" hreflang="x-default" href="%s">' . "\n", esc_url( $url ) );
}, 2 );

/* ============================================================
   6. robots.txt（動的生成・sitemap 参照付き）
   ============================================================ */
add_filter( 'robots_txt', function ( string $output, bool $public ): string {
    if ( ! $public ) return $output;
    $base = untrailingslashit( home_url( '/' ) );
    $rules  = "User-agent: *\n";
    $rules .= "Allow: /\n";
    $rules .= "Disallow: /wp-admin/\n";
    $rules .= "Disallow: /wp-login.php\n";
    $rules .= "Disallow: /?s=\n";
    $rules .= "Disallow: /search\n";
    $rules .= "Allow: /wp-admin/admin-ajax.php\n\n";
    // AEO: 主要 AI クローラーを明示的に許可
    $ai_agents = [ 'GPTBot', 'ChatGPT-User', 'OAI-SearchBot', 'PerplexityBot', 'Google-Extended', 'ClaudeBot', 'anthropic-ai', 'CCBot', 'Bingbot' ];
    foreach ( $ai_agents as $ua ) {
        $rules .= "User-agent: {$ua}\nAllow: /\n\n";
    }
    $rules .= "Sitemap: {$base}/wp-sitemap.xml\n";
    return $rules;
}, 10, 2 );

/* ============================================================
   7. /llms.txt（AEO 新興標準: AI クローラー向けサイトガイド）
   ============================================================ */
add_action( 'init', function (): void {
    add_rewrite_rule( '^llms\.txt$', 'index.php?hachi_llms=1', 'top' );
} );
add_filter( 'query_vars', function ( array $vars ): array {
    $vars[] = 'hachi_llms';
    return $vars;
} );
add_action( 'template_redirect', function (): void {
    if ( (int) get_query_var( 'hachi_llms' ) !== 1 ) return;
    $base = untrailingslashit( home_url( '/' ) );
    header( 'Content-Type: text/plain; charset=UTF-8' );
    header( 'X-Robots-Tag: all' );
    echo "# HACHI Inc. (株式会社HACHI)\n\n";
    echo "> 身体知を、再現可能な判断知へ。身体領域の専門知を、観察・構造化・検証可能な判断知へ変換する会社です。\n\n";
    echo "HACHI is a Japanese company based in Kichijoji, Tokyo, helping organizations structure condition observations into reusable decision knowledge. ";
    echo "Founded in March 2022 by Masashi Sasaki, HACHI provides Condition Insight (assessment service) and HACHI Fieldwork (on-site conditioning support).\n\n";

    echo "## 主要情報\n";
    echo "- 会社名: 株式会社HACHI / HACHI Inc.\n";
    echo "- 代表: 佐々木 譲崇（Joso Sasaki）\n";
    echo "- 設立: 2022年3月25日\n";
    echo "- 所在地: 〒180-0004 東京都武蔵野市吉祥寺本町 1-13-2 5F\n";
    echo "- ポジショニング: 身体知を、再現可能な判断知へ。\n";
    echo "- 考え方: 身体の専門知を、観察・構造化・記録できる形にする。\n\n";

    echo "## サービス\n";
    echo "- [コンディション・インサイト]({$base}/service/) — 社員の状態変化を組織で見える形にするアセスメント。提供中。\n";
    echo "- [HACHI Fieldwork]({$base}/service/) — 現場でコンディションを整えるオンサイトサポート。提供中。\n\n";

    echo "## 主要ページ\n";
    echo "- [トップページ]({$base}/)\n";
    echo "- [HACHIについて]({$base}/about/)\n";
    echo "- [サービス]({$base}/service/)\n";
    echo "- [会社概要]({$base}/company/)\n";
    echo "- [ニュース]({$base}/news/)\n";
    echo "- [お問い合わせ]({$base}/contact/)\n\n";

    echo "## 取り扱い領域\n";
    echo "社員コンディションの可視化 / 状態の観察と記録 / 現場コンディショニング / 組織の判断材料づくり\n\n";

    echo "## サイトマップ\n";
    echo "- {$base}/wp-sitemap.xml\n";
    exit;
} );
