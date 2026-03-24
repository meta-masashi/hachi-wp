<?php
/**
 * HACHI Theme — inc/rest-api.php
 *
 * WordPress REST API セキュリティ強化 & カスタムエンドポイント
 *
 * 担当範囲:
 * - 未認証ユーザーへのユーザー一覧エンドポイント無効化（security.php と重複しないよう管理）
 * - カスタムエンドポイント: GET /wp-json/hachi/v1/news
 * - CSRFトークン検証（書き込み系エンドポイント用）
 * - REST API リクエストへのレートリミット適用
 *
 * NOTE: ユーザー列挙の無効化・未認証ブロックは security.php 実装済み。
 * このファイルではカスタムエンドポイントと追加セキュリティを担当する。
 *
 * @package HACHI
 */

defined( 'ABSPATH' ) || exit;

/* ============================================================
   1. REST API 共通セキュリティヘッダー
   ============================================================ */

add_filter( 'rest_post_dispatch', function ( WP_REST_Response $response ): WP_REST_Response {
    // REST API レスポンスにセキュリティヘッダーを追加
    $response->header( 'X-Content-Type-Options', 'nosniff' );
    $response->header( 'X-Frame-Options', 'DENY' );
    $response->header( 'Cache-Control', 'no-store, no-cache, must-revalidate' );

    return $response;
}, 10, 1 );

/* ============================================================
   2. REST API へのレートリミット適用
   ============================================================ */

add_action( 'rest_api_init', function (): void {
    add_filter( 'rest_pre_dispatch', function ( $result, WP_REST_Server $server, WP_REST_Request $request ) {
        // hachi/v1 名前空間のみにレートリミットを適用
        $route = $request->get_route();
        if ( strpos( $route, '/hachi/v1' ) === 0 ) {
            $rate = hachi_check_rate_limit( 'rest_api' ); // REST API 専用: 60req/min
            if ( ! $rate['allowed'] ) {
                return new WP_REST_Response(
                    [
                        'code'    => 'too_many_requests',
                        'message' => __( 'リクエストが多すぎます。しばらく経ってから再試行してください。', 'hachi' ),
                        'data'    => [ 'status' => 429, 'retry_after' => $rate['retry_after'] ],
                    ],
                    429,
                    [ 'Retry-After' => (string) $rate['retry_after'] ]
                );
            }
        }
        return $result;
    }, 10, 3 );
} );

/* ============================================================
   3. カスタム REST API エンドポイント
   GET /wp-json/hachi/v1/news
   ============================================================ */

add_action( 'rest_api_init', function (): void {

    register_rest_route( 'hachi/v1', '/news', [
        'methods'             => WP_REST_Server::READABLE,
        'callback'            => 'hachi_rest_get_news',
        'permission_callback' => '__return_true', // 公開エンドポイント (認証不要)
        'args'                => hachi_rest_news_args(),
    ] );

} );

/**
 * /hachi/v1/news エンドポイントのパラメータ定義
 *
 * @return array
 */
function hachi_rest_news_args(): array {
    return [
        'per_page' => [
            'default'           => 10,
            'sanitize_callback' => function ( $val ): int {
                return max( 1, min( 50, (int) $val ) ); // 1〜50 件に制限
            },
            'validate_callback' => function ( $val ): bool {
                return is_numeric( $val );
            },
            'description'       => __( '1ページあたりの件数 (1〜50)', 'hachi' ),
        ],
        'page' => [
            'default'           => 1,
            'sanitize_callback' => function ( $val ): int {
                return max( 1, (int) $val );
            },
            'validate_callback' => function ( $val ): bool {
                return is_numeric( $val );
            },
            'description'       => __( 'ページ番号', 'hachi' ),
        ],
        'category' => [
            'default'           => '',
            'sanitize_callback' => 'sanitize_text_field',
            'description'       => __( 'ニュースカテゴリスラッグ', 'hachi' ),
        ],
        'type' => [
            'default'           => '',
            'sanitize_callback' => function ( $val ): string {
                return sanitize_key( $val );
            },
            'description'       => __( 'ニュースタイプ (news/press/media/blog)', 'hachi' ),
        ],
    ];
}

/**
 * GET /wp-json/hachi/v1/news コールバック
 *
 * @param WP_REST_Request $request
 * @return WP_REST_Response
 */
function hachi_rest_get_news( WP_REST_Request $request ): WP_REST_Response {
    $per_page = $request->get_param( 'per_page' );
    $page     = $request->get_param( 'page' );
    $category = $request->get_param( 'category' );
    $type     = $request->get_param( 'type' );

    // キャッシュキー（パラメータ込み）
    $cache_key = 'hachi_rest_news_' . md5( "{$per_page}_{$page}_{$category}_{$type}" );
    $cached    = get_transient( $cache_key );

    if ( $cached !== false ) {
        return new WP_REST_Response( $cached, 200, [
            'X-Cache'         => 'HIT',
            'Cache-Control'   => 'public, max-age=300',
        ] );
    }

    // クエリ構築
    $query_args = [
        'post_type'      => 'hachi_news',
        'post_status'    => 'publish',
        'posts_per_page' => $per_page,
        'paged'          => $page,
        'no_found_rows'  => false, // ページネーション用に count 必要
        'orderby'        => 'date',
        'order'          => 'DESC',
    ];

    // カテゴリフィルター
    if ( ! empty( $category ) ) {
        $query_args['tax_query'] = [
            [
                'taxonomy' => 'news_category',
                'field'    => 'slug',
                'terms'    => $category,
            ],
        ];
    }

    // タイプフィルター（カスタムフィールド）
    $allowed_types = [ 'news', 'press', 'media', 'blog' ];
    if ( ! empty( $type ) && in_array( $type, $allowed_types, true ) ) {
        $query_args['meta_query'] = [
            [
                'key'     => '_hachi_news_type',
                'value'   => $type,
                'compare' => '=',
            ],
        ];
    }

    $query = new WP_Query( $query_args );

    // レスポンス構築
    $items = [];
    if ( $query->have_posts() ) {
        while ( $query->have_posts() ) {
            $query->the_post();
            $post_id    = get_the_ID();
            $news_type  = get_post_meta( $post_id, '_hachi_news_type', true ) ?: 'news';

            // カテゴリ情報
            $terms = get_the_terms( $post_id, 'news_category' );
            $cats  = [];
            if ( is_array( $terms ) ) {
                foreach ( $terms as $term ) {
                    $cats[] = [
                        'id'   => $term->term_id,
                        'name' => $term->name,
                        'slug' => $term->slug,
                    ];
                }
            }

            // サムネイル
            $thumbnail = null;
            if ( has_post_thumbnail( $post_id ) ) {
                $img_id    = get_post_thumbnail_id( $post_id );
                $img_data  = wp_get_attachment_image_src( $img_id, 'hachi-card' );
                $thumbnail = $img_data ? [
                    'url'    => esc_url( $img_data[0] ),
                    'width'  => (int) $img_data[1],
                    'height' => (int) $img_data[2],
                    'alt'    => esc_attr( get_post_meta( $img_id, '_wp_attachment_image_alt', true ) ),
                ] : null;
            }

            $items[] = [
                'id'        => (int) $post_id,
                'title'     => esc_html( get_the_title() ),
                'excerpt'   => esc_html( get_the_excerpt() ),
                'date'      => get_the_date( 'Y-m-d' ),
                'date_gmt'  => get_the_date( 'c' ),
                'slug'      => get_post_field( 'post_name', $post_id ),
                'link'      => esc_url( get_permalink() ),
                'type'      => esc_html( $news_type ),
                'thumbnail' => $thumbnail,
                'categories'=> $cats,
            ];
        }
        wp_reset_postdata();
    }

    $response_data = [
        'items'       => $items,
        'total'       => (int) $query->found_posts,
        'total_pages' => (int) $query->max_num_pages,
        'page'        => (int) $page,
        'per_page'    => (int) $per_page,
    ];

    // 5分間キャッシュ（公開データなのでキャッシュ可）
    set_transient( $cache_key, $response_data, 5 * MINUTE_IN_SECONDS );

    return new WP_REST_Response( $response_data, 200, [
        'X-Cache'       => 'MISS',
        'Cache-Control' => 'public, max-age=300',
    ] );
}

/**
 * 投稿・更新・削除時にニュースキャッシュをクリアする
 */
add_action( 'save_post_hachi_news',   'hachi_flush_news_rest_cache' );
add_action( 'delete_post',            'hachi_flush_news_rest_cache' );
add_action( 'trash_post',             'hachi_flush_news_rest_cache' );

function hachi_flush_news_rest_cache(): void {
    global $wpdb;
    // transients のキャッシュを一括削除
    $wpdb->query(
        "DELETE FROM {$wpdb->options}
         WHERE option_name LIKE '_transient_hachi_rest_news_%'
            OR option_name LIKE '_transient_timeout_hachi_rest_news_%'"
    );
}

/* ============================================================
   4. REST API 書き込み系エンドポイントの CSRF トークン検証
   (将来の管理系エンドポイント実装時の土台)
   ============================================================ */

/**
 * カスタム CSRF トークンを生成して wp_localize_script 経由でフロントエンドに渡す
 * フォームや fetch リクエスト時に X-WP-Nonce ヘッダーに付与する。
 */
add_action( 'wp_enqueue_scripts', function (): void {
    // REST API nonce をフロントエンドに公開（既存の hachiData に追加）
    wp_localize_script( 'hachi-main', 'hachiRestData', [
        'restUrl'   => esc_url_raw( rest_url( 'hachi/v1/' ) ),
        'restNonce' => wp_create_nonce( 'wp_rest' ),
    ] );
}, 20 ); // priority=20 で hachi-main のエンキュー(priority=10)より後に実行

/**
 * hachi/v1 の POST/PUT/DELETE リクエストに nonce 検証を強制する
 * GET リクエスト（ニュース取得）は公開エンドポイントなので対象外。
 */
add_filter( 'rest_pre_dispatch', function ( $result, WP_REST_Server $server, WP_REST_Request $request ) {
    $route  = $request->get_route();
    $method = $request->get_method();

    // hachi/v1 の書き込みメソッドにのみ適用
    if ( strpos( $route, '/hachi/v1' ) === 0 &&
        in_array( $method, [ 'POST', 'PUT', 'PATCH', 'DELETE' ], true )
    ) {
        $nonce = $request->get_header( 'X-WP-Nonce' );
        if ( empty( $nonce ) || ! wp_verify_nonce( $nonce, 'wp_rest' ) ) {
            hachi_security_log( 'rest_csrf_fail', [
                'ip'    => hachi_get_client_ip(),
                'route' => $route,
                'method'=> $method,
            ] );
            return new WP_REST_Response(
                [
                    'code'    => 'rest_csrf_fail',
                    'message' => __( 'CSRF 検証に失敗しました。', 'hachi' ),
                    'data'    => [ 'status' => 403 ],
                ],
                403
            );
        }
    }

    return $result;
}, 10, 3 );
