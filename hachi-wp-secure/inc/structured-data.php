<?php
/**
 * HACHI Theme — inc/structured-data.php
 *
 * JSON-LD 構造化データ（SEO + AEO 対応）
 *   - Organization（拡張）
 *   - WebSite + SearchAction
 *   - WebPage / Article
 *   - Service（REBOOT-WORK / PACE 予定）
 *   - FAQPage
 *   - BreadcrumbList
 *   - SpeakableSpecification
 *
 * @package HACHI
 */

defined( 'ABSPATH' ) || exit;

/**
 * サイトのベース URL を動的に取得（ハードコード回避）
 */
function hachi_site_base_url(): string {
    return untrailingslashit( home_url( '/' ) );
}

add_action( 'wp_head', 'hachi_output_structured_data', 5 );

function hachi_output_structured_data(): void {
    $schemas = [];

    // 1. Organization（全ページ共通）
    $schemas[] = hachi_schema_organization();

    // 2. WebSite + SearchAction（全ページ共通）
    $schemas[] = hachi_schema_website();

    // 3. WebPage / Article
    $schemas[] = hachi_schema_webpage();

    // 4. Service schema（サービスページ + フロント）
    if ( is_front_page() || ( is_page() && get_post_field( 'post_name', get_the_ID() ) === 'service' ) ) {
        $schemas = array_merge( $schemas, hachi_schema_services() );
    }

    // 5. FAQPage（サービスページ + About）
    if ( is_page() && in_array( get_post_field( 'post_name', get_the_ID() ), [ 'service', 'about' ], true ) ) {
        $schemas[] = hachi_schema_faq();
    }

    // 6. BreadcrumbList（トップページ以外）
    if ( ! is_front_page() ) {
        $breadcrumb = hachi_schema_breadcrumb();
        if ( $breadcrumb ) {
            $schemas[] = $breadcrumb;
        }
    }

    // 7. News archive ItemList（v3.3 固定表示記事）
    if ( is_post_type_archive( 'hachi_news' ) ) {
        $schemas[] = hachi_schema_news_item_list();
    }

    foreach ( $schemas as $schema ) {
        if ( empty( $schema ) ) continue;
        printf(
            '<script type="application/ld+json">%s</script>' . "\n",
            wp_json_encode( $schema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES )
        );
    }
}

/* ============================================================
   Organization スキーマ（拡張版）
   ============================================================ */

function hachi_schema_organization(): array {
    $base = hachi_site_base_url();
    return [
        '@context'      => 'https://schema.org',
        '@type'         => 'Organization',
        '@id'           => $base . '/#organization',
        'name'          => '株式会社HACHI',
        'alternateName' => 'HACHI Inc.',
        'legalName'     => '株式会社HACHI',
        'url'           => $base . '/',
        'logo'          => [
            '@type'  => 'ImageObject',
            'url'    => $base . '/wp-content/themes/hachi-wp-secure/assets/logo.png',
            'width'  => 200,
            'height' => 80,
        ],
        'image'         => $base . '/wp-content/themes/hachi-wp-secure/assets/og-image.png',
        'description'   => '株式会社HACHIは、身体領域の専門知を観察・構造化・検証可能な判断知へ変換する会社です。コンディション・インサイトと HACHI Fieldwork を提供しています。',
        'slogan'        => '身体知を、再現可能な判断知へ。',
        'foundingDate'  => '2022-03-25',
        'founder'       => [
            '@type' => 'Person',
            'name'  => '佐々木 譲崇',
            'jobTitle' => '代表取締役社長',
        ],
        'address'       => [
            '@type'           => 'PostalAddress',
            'streetAddress'   => '吉祥寺本町1-13-2 5F',
            'addressLocality' => '武蔵野市',
            'addressRegion'   => '東京都',
            'postalCode'      => '180-0004',
            'addressCountry'  => 'JP',
        ],
        'areaServed'    => [
            [ '@type' => 'Country', 'name' => '日本' ],
        ],
        'knowsAbout'    => [
            '身体知の構造化',
            '状態の観察と記録',
            'コンディショニング',
            '組織の判断材料づくり',
        ],
        'sameAs'        => [
            'https://x.com/hachi_inc',
        ],
        'contactPoint'  => [
            '@type'             => 'ContactPoint',
            'contactType'       => 'customer support',
            'availableLanguage' => [ 'Japanese', 'ja' ],
            'url'               => $base . '/contact/',
            'areaServed'        => 'JP',
        ],
    ];
}

/* ============================================================
   WebSite スキーマ + SearchAction
   ============================================================ */

function hachi_schema_website(): array {
    $base = hachi_site_base_url();
    return [
        '@context'        => 'https://schema.org',
        '@type'           => 'WebSite',
        '@id'             => $base . '/#website',
        'url'             => $base . '/',
        'name'            => 'HACHI',
        'alternateName'   => '株式会社HACHI',
        'description'     => '身体知を、再現可能な判断知へ。HACHIの公式サイト。',
        'inLanguage'      => 'ja-JP',
        'publisher'       => [ '@id' => $base . '/#organization' ],
        'potentialAction' => [
            '@type'       => 'SearchAction',
            'target'      => [
                '@type'       => 'EntryPoint',
                'urlTemplate' => $base . '/?s={search_term_string}',
            ],
            'query-input' => 'required name=search_term_string',
        ],
    ];
}

/* ============================================================
   WebPage / Article スキーマ
   ============================================================ */

function hachi_schema_webpage(): array {
    $base        = hachi_site_base_url();
    $url         = hachi_get_canonical_url();
    $title       = wp_get_document_title();
    $description = hachi_get_page_description();

    $type = 'WebPage';
    if ( is_singular( 'hachi_news' ) ) {
        $type = 'Article';
    } elseif ( is_page() ) {
        $slug = get_post_field( 'post_name', get_the_ID() );
        if ( $slug === 'about' )   $type = 'AboutPage';
        if ( $slug === 'contact' ) $type = 'ContactPage';
    }

    $schema = [
        '@context'    => 'https://schema.org',
        '@type'       => $type,
        '@id'         => $url . '#webpage',
        'url'         => $url,
        'name'        => $title,
        'description' => $description,
        'inLanguage'  => 'ja-JP',
        'isPartOf'    => [ '@id' => $base . '/#website' ],
        'publisher'   => [ '@id' => $base . '/#organization' ],
    ];

    // SpeakableSpecification（AEO: voice assistants 対応）
    if ( is_front_page() || is_page() ) {
        $schema['speakable'] = [
            '@type' => 'SpeakableSpecification',
            'cssSelector' => [ '.v3-hero h1', '.v3-section h2', '.v3-page p' ],
        ];
    }

    if ( is_singular( 'hachi_news' ) ) {
        $schema['datePublished'] = get_the_date( 'c' );
        $schema['dateModified']  = get_the_modified_date( 'c' );
        $schema['author']        = [ '@id' => $base . '/#organization' ];
        $schema['headline']      = get_the_title();
        if ( has_post_thumbnail() ) {
            $thumb = wp_get_attachment_image_src( get_post_thumbnail_id(), 'large' );
            if ( $thumb ) {
                $schema['image'] = [
                    '@type'  => 'ImageObject',
                    'url'    => $thumb[0],
                    'width'  => $thumb[1],
                    'height' => $thumb[2],
                ];
            }
        }
    }

    return $schema;
}

/* ============================================================
   Service スキーマ
   ============================================================ */

function hachi_schema_services(): array {
    $base = hachi_site_base_url();

    $insight = [
        '@context'    => 'https://schema.org',
        '@type'       => 'Service',
        '@id'         => $base . '/service/#condition-insight',
        'name'        => 'コンディション・インサイト',
        'serviceType' => '組織コンディション可視化サービス',
        'description' => '社員の身体・睡眠・集中・疲労の傾向を、組織として観察できる材料に整えるサービス。',
        'provider'    => [ '@id' => $base . '/#organization' ],
        'areaServed'  => [ '@type' => 'Country', 'name' => '日本' ],
        'url'         => $base . '/service/',
        'audience'    => [
            '@type' => 'BusinessAudience',
            'name'  => '中小企業・経営者',
        ],
    ];

    $fieldwork = [
        '@context'    => 'https://schema.org',
        '@type'       => 'Service',
        '@id'         => $base . '/service/#hachi-fieldwork',
        'name'        => 'HACHI Fieldwork',
        'serviceType' => 'オンサイト コンディショニングサポート',
        'description' => '現場でのストレッチコンディショニングを通じて、観察と介入を一続きにするオンサイトサービス。',
        'provider'    => [ '@id' => $base . '/#organization' ],
        'areaServed'  => [ '@type' => 'Country', 'name' => '日本' ],
        'url'         => $base . '/service/',
        'audience'    => [
            '@type' => 'BusinessAudience',
            'name'  => '中小企業・経営者',
        ],
    ];

    return [ $insight, $fieldwork ];
}

/* ============================================================
   FAQPage スキーマ（AEO 重点）
   ============================================================ */

function hachi_schema_faq(): array {
    $faqs = [
        [
            'question' => '株式会社HACHIはどんな会社ですか？',
            'answer'   => '株式会社HACHIは、2022年3月に設立された、身体領域の専門知を観察・構造化・検証可能な判断知へ変換する会社です。東京都武蔵野市吉祥寺を拠点に、コンディション・インサイトと HACHI Fieldwork を提供しています。代表取締役社長は佐々木譲崇。',
        ],
        [
            'question' => 'コンディション・インサイトとはどのようなサービスですか？',
            'answer'   => 'コンディション・インサイトは、社員の身体・睡眠・集中・疲労の傾向を、短いチェックから組織単位で整理するサービスです。状態の観察・記録を通じて、経営者・人事・管理職の日常判断に使える情報を提供します。',
        ],
        [
            'question' => 'HACHI Fieldwork とはどのようなサービスですか？',
            'answer'   => 'HACHI Fieldwork は、現場でコンディションを整えるオンサイトサポートです。観察と声がけ・記録をもとに、社員自身が動くコンディショニングを支援します。',
        ],
        [
            'question' => '導入を検討したい場合、どうすればよいですか？',
            'answer'   => 'お問い合わせページのフォームよりご連絡ください。ヒアリングのうえ、貴社の状況に合わせてご提案いたします。',
        ],
        [
            'question' => '会社の所在地はどこですか？',
            'answer'   => '〒180-0004 東京都武蔵野市吉祥寺本町 1-13-2 5F です。JR中央線・総武線および京王井の頭線「吉祥寺」駅より徒歩圏内です。',
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
    $items = [];
    $pos   = 1;
    $base  = hachi_site_base_url();

    $items[] = [
        '@type'    => 'ListItem',
        'position' => $pos++,
        'name'     => 'ホーム',
        'item'     => $base . '/',
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
   News ItemList スキーマ
   ============================================================ */

function hachi_schema_news_item_list(): array {
    $base  = hachi_site_base_url();
    $items = [
        [ '2026-04-15', 'コンディショニング管理に AI を導入する前に知っておくべき 3 つのこと' ],
        [ '2026-04-15', 'トレーナーの "なんとなく" を AI に翻訳する ── body-part prior という考え方' ],
        [ '2026-03-31', 'AI がスポーツ現場の「評価」を変える｜トレーナーの推論は機械に勝てるのか' ],
        [ '2026-03-25', 'センサーが現場を変えているか？──ウェアラブルデバイスによる傷害リスク評価' ],
    ];

    return [
        '@context'        => 'https://schema.org',
        '@type'           => 'ItemList',
        '@id'             => $base . '/news/#itemlist',
        'name'            => 'HACHI ニュース・知見',
        'itemListElement' => array_map( function ( array $item, int $index ) use ( $base ): array {
            return [
                '@type'    => 'ListItem',
                'position' => $index + 1,
                'item'     => [
                    '@type'         => 'Article',
                    'headline'      => $item[1],
                    'datePublished' => $item[0],
                    'url'           => $base . '/news/',
                    'publisher'     => [ '@id' => $base . '/#organization' ],
                ],
            ];
        }, $items, array_keys( $items ) ),
    ];
}

/* ============================================================
   ヘルパー: ページ description 取得
   ============================================================ */

function hachi_get_page_description(): string {
    if ( is_singular( 'hachi_news' ) ) {
        $excerpt = get_the_excerpt();
        if ( $excerpt ) {
            return wp_strip_all_tags( $excerpt );
        }
    }
    return '株式会社HACHIは、社員のコンディションの変化を組織で見える形にする会社です。コンディション・インサイト提供中、HACHI Fieldwork 提供中。';
}
