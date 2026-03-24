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

    if ( is_front_page() ) {
        $title['title'] = 'HACHI | 健康経営・ウェルネスソリューション';
        unset( $title['tagline'] );
        return $title;
    }

    if ( is_singular( 'hachi_news' ) ) {
        $title['title'] = get_the_title() . ' | HACHI ニュース';
        return $title;
    }

    if ( is_post_type_archive( 'hachi_news' ) ) {
        $title['title'] = 'ニュース | HACHI';
        return $title;
    }

    if ( is_page( 'service' ) || ( is_page() && get_post_field( 'post_name', get_the_ID() ) === 'service' ) ) {
        $title['title'] = 'サービス（PACE v3.0 / REBOOT-WORK） | HACHI';
        return $title;
    }

    if ( is_page( 'company' ) || ( is_page() && get_post_field( 'post_name', get_the_ID() ) === 'company' ) ) {
        $title['title'] = '会社概要 | 株式会社HACHI';
        return $title;
    }

    if ( is_page( 'about' ) || ( is_page() && get_post_field( 'post_name', get_the_ID() ) === 'about' ) ) {
        $title['title'] = 'HACHIについて | beyond Wellness.';
        return $title;
    }

    if ( is_page( 'contact' ) || ( is_page() && get_post_field( 'post_name', get_the_ID() ) === 'contact' ) ) {
        $title['title'] = 'お問い合わせ・資料請求 | HACHI';
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
    $default_desc = '株式会社HACHIは、健康経営支援プラットフォーム「PACE v3.0」と働き方改革ツール「REBOOT-WORK」を提供するウェルネステック企業です。';
    $default_img  = HACHI_THEME_URI . '/assets/og-image.png';

    // ページ種別ごとの description / OGP 画像 / canonical / robots
    $description = $default_desc;
    $og_image    = $default_img;
    $canonical   = hachi_get_canonical_url();
    $robots      = 'index, follow';
    $og_type     = 'website';
    $og_title    = get_bloginfo( 'name' );

    if ( is_front_page() ) {
        $og_title    = 'HACHI | 健康経営・ウェルネスソリューション';
        $description = '健康経営支援プラットフォーム「PACE v3.0」で従業員のウェルビーイングを可視化。働き方改革ツール「REBOOT-WORK」で生産性向上を実現します。';
    } elseif ( is_page( 'service' ) || ( is_page() && get_post_field( 'post_name', get_the_ID() ) === 'service' ) ) {
        $og_title    = 'サービス（PACE v3.0 / REBOOT-WORK） | HACHI';
        $description = 'PACE v3.0はAI活用の健康経営支援プラットフォーム。REBOOT-WORKは働き方改革を支援するツールです。資料請求・デモ申込受付中。';
    } elseif ( is_page( 'company' ) || ( is_page() && get_post_field( 'post_name', get_the_ID() ) === 'company' ) ) {
        $og_title    = '会社概要 | 株式会社HACHI';
        $description = '株式会社HACHI（東京都武蔵野市）は「beyond Wellness.」をビジョンに掲げ、健康経営・ウェルネス分野のDX推進を支援しています。';
    } elseif ( is_page( 'about' ) || ( is_page() && get_post_field( 'post_name', get_the_ID() ) === 'about' ) ) {
        $og_title    = 'HACHIについて | beyond Wellness.';
        $description = '「beyond Wellness.」を掲げ、テクノロジーで人の健康と働き方を革新する株式会社HACHIのミッション・ビジョン・バリューをご紹介します。';
    } elseif ( is_page( 'contact' ) || ( is_page() && get_post_field( 'post_name', get_the_ID() ) === 'contact' ) ) {
        $og_title    = 'お問い合わせ・資料請求 | HACHI';
        $description = 'PACE v3.0デモ申込み・REBOOT-WORK資料請求・一般お問い合わせはこちらから。2営業日以内にご連絡いたします。';
        $robots      = 'noindex, follow'; // お問い合わせページはインデックス不要
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
        $description = 'HACHIからの最新ニュース・プレスリリース・メディア掲載情報をお届けします。';
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
