<?php
/**
 * HACHI Theme — inc/structured-data.php
 *
 * JSON-LD 構造化データ
 *   - Organization スキーマ（hachi.co.jp）
 *   - WebPage スキーマ（全ページ）
 *   - FAQPage スキーマ（サービスページ用）
 *   - BreadcrumbList スキーマ
 *
 * JSON-LD 形式のみ使用（Microdata / RDFa 禁止）。
 *
 * @package HACHI
 */

defined( 'ABSPATH' ) || exit;

add_action( 'wp_head', 'hachi_output_structured_data', 5 );

function hachi_output_structured_data(): void {
    $schemas = [];

    // 1. Organization（全ページ共通）
    $schemas[] = hachi_schema_organization();

    // 2. WebPage（全ページ共通）
    $schemas[] = hachi_schema_webpage();

    // 3. FAQPage（サービスページのみ）
    if ( is_page() && get_post_field( 'post_name', get_the_ID() ) === 'service' ) {
        $schemas[] = hachi_schema_faq();
    }

    // 4. BreadcrumbList（トップページ以外）
    if ( ! is_front_page() ) {
        $breadcrumb = hachi_schema_breadcrumb();
        if ( $breadcrumb ) {
            $schemas[] = $breadcrumb;
        }
    }

    foreach ( $schemas as $schema ) {
        if ( empty( $schema ) ) {
            continue;
        }
        printf(
            '<script type="application/ld+json">%s</script>' . "\n",
            wp_json_encode( $schema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT )
        );
    }
}

/* ============================================================
   Organization スキーマ
   ============================================================ */

function hachi_schema_organization(): array {
    return [
        '@context' => 'https://schema.org',
        '@type'    => 'Organization',
        '@id'      => 'https://hachi.co.jp/#organization',
        'name'     => '株式会社HACHI',
        'alternateName' => 'HACHI Inc.',
        'url'      => 'https://hachi.co.jp',
        'logo'     => [
            '@type'  => 'ImageObject',
            'url'    => HACHI_THEME_URI . '/assets/logo.png',
            'width'  => 200,
            'height' => 80,
        ],
        'description' => '健康経営支援プラットフォーム「PACE v3.0」と働き方改革ツール「REBOOT-WORK」を提供するウェルネステック企業。',
        'address'  => [
            '@type'           => 'PostalAddress',
            'streetAddress'   => '吉祥寺本町1-13-2 5F',
            'addressLocality' => '武蔵野市',
            'addressRegion'   => '東京都',
            'postalCode'      => '180-0001',
            'addressCountry'  => 'JP',
        ],
        'sameAs'   => [
            'https://x.com/hachi_inc',
        ],
        'contactPoint' => [
            '@type'             => 'ContactPoint',
            'contactType'       => 'customer support',
            'availableLanguage' => 'Japanese',
            'url'               => 'https://hachi.co.jp/contact/',
        ],
    ];
}

/* ============================================================
   WebPage スキーマ
   ============================================================ */

function hachi_schema_webpage(): array {
    $url         = hachi_get_canonical_url();
    $title       = wp_get_document_title();
    $description = hachi_get_page_description();
    $modified    = is_singular() ? get_the_modified_date( 'c' ) : get_bloginfo( 'url' );

    $schema = [
        '@context'        => 'https://schema.org',
        '@type'           => is_singular( 'hachi_news' ) ? 'Article' : 'WebPage',
        '@id'             => $url . '#webpage',
        'url'             => $url,
        'name'            => $title,
        'description'     => $description,
        'inLanguage'      => 'ja',
        'isPartOf'        => [ '@id' => 'https://hachi.co.jp/#website' ],
        'publisher'       => [ '@id' => 'https://hachi.co.jp/#organization' ],
    ];

    if ( is_singular() ) {
        $schema['datePublished'] = get_the_date( 'c' );
        $schema['dateModified']  = get_the_modified_date( 'c' );
    }

    return $schema;
}

/* ============================================================
   FAQPage スキーマ（サービスページ用）
   ============================================================ */

function hachi_schema_faq(): array {
    $faqs = [
        [
            'question' => 'PACE v3.0 はどのような企業向けですか？',
            'answer'   => 'PACE v3.0 は、従業員の健康データを AI で分析し、健康経営を推進したい中堅〜大企業向けの SaaS プラットフォームです。導入社数・業種は問いません。まずはデモをお申し込みください。',
        ],
        [
            'question' => 'REBOOT-WORK の資料請求はどのように行いますか？',
            'answer'   => 'お問い合わせページの「REBOOT-WORK 資料請求」フォームよりお申し込みください。2 営業日以内に PDF 資料をメールでお送りします。',
        ],
        [
            'question' => '導入にあたってシステム要件はありますか？',
            'answer'   => 'PACE v3.0 はクラウド型 SaaS のためブラウザがあれば利用可能です。社内システムとの API 連携が必要な場合は個別にご相談ください。',
        ],
        [
            'question' => '料金・プランを教えてください。',
            'answer'   => 'ご利用人数・機能に応じたプランをご用意しています。詳細はデモ申込み後にご案内します。',
        ],
        [
            'question' => 'セキュリティ・個人情報保護の取り組みは？',
            'answer'   => 'HACHI は ISO 27001 に準拠したセキュリティポリシーのもとデータを管理します。詳細は会社概要ページの Security セクションをご覧ください。',
        ],
    ];

    $entities = array_map( function ( array $faq ): array {
        return [
            '@type'          => 'Question',
            'name'           => $faq['question'],
            'acceptedAnswer' => [
                '@type' => 'Answer',
                'text'  => $faq['answer'],
            ],
        ];
    }, $faqs );

    return [
        '@context'   => 'https://schema.org',
        '@type'      => 'FAQPage',
        'mainEntity' => $entities,
    ];
}

/* ============================================================
   BreadcrumbList スキーマ
   ============================================================ */

function hachi_schema_breadcrumb(): ?array {
    $items   = [];
    $pos     = 1;

    // 第1階層: トップページ
    $items[] = [
        '@type'    => 'ListItem',
        'position' => $pos++,
        'name'     => 'ホーム',
        'item'     => home_url( '/' ),
    ];

    if ( is_page() ) {
        $items[] = [
            '@type'    => 'ListItem',
            'position' => $pos++,
            'name'     => get_the_title(),
            'item'     => (string) get_permalink(),
        ];
    } elseif ( is_singular( 'hachi_news' ) ) {
        $archive_link = get_post_type_archive_link( 'hachi_news' );
        if ( $archive_link ) {
            $items[] = [
                '@type'    => 'ListItem',
                'position' => $pos++,
                'name'     => 'ニュース',
                'item'     => $archive_link,
            ];
        }
        $items[] = [
            '@type'    => 'ListItem',
            'position' => $pos++,
            'name'     => get_the_title(),
            'item'     => (string) get_permalink(),
        ];
    } elseif ( is_post_type_archive( 'hachi_news' ) ) {
        $items[] = [
            '@type'    => 'ListItem',
            'position' => $pos++,
            'name'     => 'ニュース',
            'item'     => (string) get_post_type_archive_link( 'hachi_news' ),
        ];
    } else {
        return null;
    }

    return [
        '@context'        => 'https://schema.org',
        '@type'           => 'BreadcrumbList',
        'itemListElement' => $items,
    ];
}

/* ============================================================
   ヘルパー: ページ description 取得
   ============================================================ */

function hachi_get_page_description(): string {
    if ( is_singular() ) {
        $excerpt = get_the_excerpt();
        if ( $excerpt ) {
            return wp_strip_all_tags( $excerpt );
        }
    }
    return '株式会社HACHIは、健康経営支援プラットフォーム「PACE v3.0」と働き方改革ツール「REBOOT-WORK」を提供するウェルネステック企業です。';
}
