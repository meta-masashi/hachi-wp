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
        'description'   => '株式会社HACHIは、スポーツ医療とウェルネスの現場にテクノロジーで革新をもたらす企業です。オフィスワーカー向けOn-site Service「REBOOT-WORK」を提供し、スポーツ医療AI-SaaS「PACE」を開発しています。',
        'slogan'        => 'beyond the Body. / 身体の、その先へ。',
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
            'スポーツ医療',
            'アスリートケア',
            '因果推論AI',
            'デジタルツイン',
            'オフィスワーカー健康管理',
            '健康経営',
            'ウェルネステクノロジー',
            'リハビリテーション',
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
        'description'     => 'スポーツ医療とウェルネスの現場にテクノロジーで革新をもたらす、HACHIの公式サイト。',
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
            'cssSelector' => [ '.heading-en', '.heading-jp', '.hero__lede', '.body-copy' ],
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

    $reboot = [
        '@context'    => 'https://schema.org',
        '@type'       => 'Service',
        '@id'         => $base . '/service/#reboot-work',
        'name'        => 'REBOOT-WORK',
        'serviceType' => 'On-site Wellness Service',
        'description' => 'オフィス内で生じる健康課題を医学的評価をもとに、専門チームがご希望の場所で課題解決のためのサービスを提供するOn-site Service。腰痛・肩こり・メンタルヘルスなど、働く人の不調を継続的にケアします。',
        'provider'    => [ '@id' => $base . '/#organization' ],
        'areaServed'  => [ '@type' => 'Country', 'name' => '日本' ],
        'url'         => $base . '/service/#reboot',
        'category'    => '健康経営 / 産業医学 / ウェルネス',
        'audience'    => [
            '@type' => 'BusinessAudience',
            'name'  => '企業・オフィスワーカー',
        ],
    ];

    $pace = [
        '@context'       => 'https://schema.org',
        '@type'          => 'SoftwareApplication',
        '@id'            => $base . '/service/#pace',
        'name'           => 'PACE',
        'alternateName'  => 'Progressive Assessment & Conditioning Engine',
        'applicationCategory' => 'HealthApplication',
        'operatingSystem'     => 'Web, iOS, Android',
        'description'    => '因果推論AIとデジタルツインを活用し、アスレティックトレーナー・理学療法士の意思決定を支援するスポーツ医療AI-SaaS。現在開発中・先行案内リスト受付中。',
        'provider'       => [ '@id' => $base . '/#organization' ],
        'url'            => $base . '/service/#pace',
        'releaseNotes'   => 'Coming Soon — 先行案内リスト受付中',
        'audience'       => [
            '@type' => 'Audience',
            'name'  => 'スポーツ医療チーム / プロクラブ / 競技団体',
        ],
    ];

    return [ $reboot, $pace ];
}

/* ============================================================
   FAQPage スキーマ（AEO 重点）
   ============================================================ */

function hachi_schema_faq(): array {
    $faqs = [
        [
            'question' => '株式会社HACHIはどんな会社ですか？',
            'answer'   => '株式会社HACHIは、2022年3月に設立されたスポーツ医療・ウェルネステック企業です。東京都武蔵野市吉祥寺を拠点に、オフィスワーカー向けOn-site Service「REBOOT-WORK」を提供し、スポーツ医療AI-SaaS「PACE」を開発しています。代表取締役社長は佐々木譲崇。',
        ],
        [
            'question' => 'REBOOT-WORKとはどのようなサービスですか？',
            'answer'   => 'REBOOT-WORKは、企業オフィス内で生じる健康課題を医学的評価に基づいて解決するOn-site Serviceです。専門チームがオフィスを訪問し、腰痛・肩こり・メンタルヘルスなどの課題に対して、アセスメントからプログラム設計、継続支援までを一貫して提供します。',
        ],
        [
            'question' => 'PACE はどんなプロダクトですか？いつ利用できますか？',
            'answer'   => 'PACEは因果推論AIとデジタルツインを活用した、スポーツ医療チーム向けのAI-SaaSプラットフォームです。アスレティックトレーナー・理学療法士の意思決定を支援し、論文エビデンスに基づく判断を可能にします。現在はローンチ準備中で、先行案内リストを受付中です。',
        ],
        [
            'question' => 'REBOOT-WORKの導入を検討したい場合、どうすればよいですか？',
            'answer'   => 'お問い合わせページのフォームよりご連絡ください。現場訪問によるヒアリング、アセスメント、プログラム設計の流れでご提案いたします。',
        ],
        [
            'question' => 'HACHIのセキュリティ対策は？',
            'answer'   => '医療機関水準のデータ保護を全サービスで徹底しています。HIPAA準拠のシステム設計、TLS 1.3暗号化、Row Level Security（RLS）によるテナントデータ完全分離、動画解析時の顔データ自動マスキングを実装しています。',
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
   ヘルパー: ページ description 取得
   ============================================================ */

function hachi_get_page_description(): string {
    if ( is_singular( 'hachi_news' ) ) {
        $excerpt = get_the_excerpt();
        if ( $excerpt ) {
            return wp_strip_all_tags( $excerpt );
        }
    }
    return '株式会社HACHIは、スポーツ医療とウェルネスの現場にテクノロジーで革新をもたらす企業です。オフィスワーカー向けOn-site Service「REBOOT-WORK」提供中、スポーツ医療AI-SaaS「PACE」開発中。';
}
