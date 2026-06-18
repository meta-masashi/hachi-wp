<?php
/**
 * HACHI Theme — front-page.php
 * Template for the site front page
 * Updated: 2026-06-18 corp-v3-pr-b-home — Home v3.2 全面書き換え
 * 6 セクション構成: ヒーロー / 価値命題 / 差別化プロセス / サービス 2 本柱 / 直近のお知らせ / フッター CTA
 */

get_header();
?>

<main id="main-content">

  <!-- ============================================================
       セクション 1: ヒーロー
       H1 / サブコピー / CTA (一次行動) / 立位人物 line-art
       ============================================================ -->
  <section class="hero" aria-label="<?php esc_attr_e( 'ヒーロー', 'hachi' ); ?>">
    <div class="hero-inner">

      <div class="hero-copy">
        <h1><?php esc_html_e( '身体知を、再現可能な判断知へ。', 'hachi' ); ?></h1>
        <p class="sub-copy">
          <?php esc_html_e( '経験と勘で動く現場を、組織が引き継げる判断の体系に変える。', 'hachi' ); ?><br>
          <?php esc_html_e( '社員の身体・睡眠・集中・疲労を、観察できる材料にする。', 'hachi' ); ?>
        </p>
        <a href="<?php echo esc_url( home_url( '/service/' ) ); ?>" class="btn btn-accent" aria-label="<?php esc_attr_e( 'サービス詳細ページへ', 'hachi' ); ?>">
          <?php esc_html_e( 'サービスを見る', 'hachi' ); ?>
        </a>
      </div>

      <div class="hero-illustration" aria-hidden="true">
        <img
          src="<?php echo esc_url( get_template_directory_uri() . '/assets/hero-person.svg' ); ?>"
          alt=""
          width="340"
          height="510"
          loading="eager"
        >
      </div>

    </div>
  </section>

  <!-- ============================================================
       セクション 2: 価値命題
       「経営判断に、観察できる材料を。」
       ============================================================ -->
  <section class="value-prop section" aria-label="<?php esc_attr_e( '価値命題', 'hachi' ); ?>">
    <div class="container">
      <div class="content-narrow">
        <h2><?php esc_html_e( '経営判断に、観察できる材料を。', 'hachi' ); ?></h2>
        <p style="margin-top: 40px;">
          <?php esc_html_e( 'コンディション・インサイトは、社員の短いチェックから身体・睡眠・集中・疲労の傾向を組織として整理する法人向けサービスです。経営者・人事・管理職の日常判断に使える情報を提供します。', 'hachi' ); ?>
        </p>
      </div>
    </div>
  </section>

  <!-- ============================================================
       セクション 3: 4 段プロセス図 (差別化セクション / v3.2 新設)
       「経験と勘を、引き継げる体系へ。」
       観察 → 整理 → 記録 → 判断
       ============================================================ -->
  <section class="process-section" aria-label="<?php esc_attr_e( '差別化セクション — 4 段プロセス', 'hachi' ); ?>">
    <div class="container">

      <div class="process-section__header" style="text-align:center; margin-bottom:56px;">
        <h2><?php esc_html_e( '経験と勘を、引き継げる体系へ。', 'hachi' ); ?></h2>
        <p class="section-sub" style="margin-top:24px; max-width:560px; margin-inline:auto; line-height:1.85;">
          <?php esc_html_e( 'HACHI は身体知を 4 段のプロセスで再現可能にします。', 'hachi' ); ?><br>
          <?php esc_html_e( '一回限りの施策ではなく、組織として積み上がる判断の体系です。', 'hachi' ); ?>
        </p>
      </div>

      <div class="content-full">
        <div class="process-flow">

          <!-- Step 1: 観察 -->
          <div class="process-step">
            <div class="process-step-marker" aria-hidden="true">
              <div class="step-circle"></div>
            </div>
            <div class="process-step-title"><?php esc_html_e( '観察', 'hachi' ); ?></div>
            <p class="process-step-desc">
              <?php esc_html_e( '現場の身体・睡眠・集中・疲労のサインを取り出す', 'hachi' ); ?>
            </p>
          </div>

          <!-- 矢印 1 -->
          <div class="process-arrow" aria-hidden="true">
            <svg class="process-arrow-svg" width="24" height="16" viewBox="0 0 24 16" fill="none" xmlns="http://www.w3.org/2000/svg">
              <line x1="0" y1="8" x2="18" y2="8" stroke="#E5E5E7" stroke-width="1"/>
              <polyline points="14,4 20,8 14,12" stroke="#E5E5E7" stroke-width="1" fill="none" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
          </div>

          <!-- Step 2: 整理 -->
          <div class="process-step">
            <div class="process-step-marker" aria-hidden="true">
              <div class="step-circle"></div>
            </div>
            <div class="process-step-title"><?php esc_html_e( '整理', 'hachi' ); ?></div>
            <p class="process-step-desc">
              <?php esc_html_e( 'サインを傾向として組織単位で整理する', 'hachi' ); ?>
            </p>
          </div>

          <!-- 矢印 2 -->
          <div class="process-arrow" aria-hidden="true">
            <svg class="process-arrow-svg" width="24" height="16" viewBox="0 0 24 16" fill="none" xmlns="http://www.w3.org/2000/svg">
              <line x1="0" y1="8" x2="18" y2="8" stroke="#E5E5E7" stroke-width="1"/>
              <polyline points="14,4 20,8 14,12" stroke="#E5E5E7" stroke-width="1" fill="none" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
          </div>

          <!-- Step 3: 記録 -->
          <div class="process-step">
            <div class="process-step-marker" aria-hidden="true">
              <div class="step-circle"></div>
            </div>
            <div class="process-step-title"><?php esc_html_e( '記録', 'hachi' ); ?></div>
            <p class="process-step-desc">
              <?php esc_html_e( '経営判断と面談に使える組織レポートとして残す', 'hachi' ); ?>
            </p>
          </div>

          <!-- 矢印 3 -->
          <div class="process-arrow" aria-hidden="true">
            <svg class="process-arrow-svg" width="24" height="16" viewBox="0 0 24 16" fill="none" xmlns="http://www.w3.org/2000/svg">
              <line x1="0" y1="8" x2="18" y2="8" stroke="#E5E5E7" stroke-width="1"/>
              <polyline points="14,4 20,8 14,12" stroke="#E5E5E7" stroke-width="1" fill="none" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
          </div>

          <!-- Step 4: 判断 (アクセントドット) -->
          <div class="process-step">
            <div class="process-step-marker" aria-hidden="true">
              <div class="step-circle"></div>
            </div>
            <div class="process-step-title"><?php esc_html_e( '判断', 'hachi' ); ?></div>
            <p class="process-step-desc">
              <?php esc_html_e( '次の打ち手を組織として再現可能にする', 'hachi' ); ?>
            </p>
          </div>

        </div><!-- /.process-flow -->
      </div><!-- /.content-full -->
    </div><!-- /.container -->
  </section>

  <!-- ============================================================
       セクション 4: サービス 2 本柱
       コンディション・インサイト + HACHI Fieldwork (2 列)
       ============================================================ -->
  <section class="services-nav section" aria-label="<?php esc_attr_e( 'サービス一覧', 'hachi' ); ?>">
    <div class="container">
      <h2><?php esc_html_e( '2 つのサービスで、組織のコンディションを整える', 'hachi' ); ?></h2>

      <div class="services-grid">
        <!-- コンディション・インサイト -->
        <div class="service-card">
          <h3><?php esc_html_e( 'コンディション・インサイト', 'hachi' ); ?></h3>
          <p><?php esc_html_e( '社員の状態変化のサインを、組織として早めにつかむ。', 'hachi' ); ?></p>
          <a href="<?php echo esc_url( home_url( '/service/' ) ); ?>" class="text-link"><?php esc_html_e( '詳細を見る →', 'hachi' ); ?></a>
        </div>

        <!-- HACHI Fieldwork -->
        <div class="service-card">
          <h3><?php esc_html_e( 'HACHI Fieldwork', 'hachi' ); ?></h3>
          <p><?php esc_html_e( '現場でのストレッチコンディショニングを通じて、組織の状態を直接整える。', 'hachi' ); ?></p>
          <a href="<?php echo esc_url( home_url( '/service/' ) ); ?>" class="text-link"><?php esc_html_e( '詳細を見る →', 'hachi' ); ?></a>
        </div>
      </div>
    </div>
  </section>

  <!-- ============================================================
       セクション 5: 直近のお知らせ
       WP_Query で hachi_news 最新 2 件を取得
       ============================================================ -->
  <section class="news-section section" aria-label="<?php esc_attr_e( '直近のお知らせ', 'hachi' ); ?>">
    <div class="container">
      <h2><?php esc_html_e( '直近のお知らせ', 'hachi' ); ?></h2>

      <?php
      $news_query = new WP_Query( [
        'post_type'      => 'hachi_news',
        'posts_per_page' => 2,
        'post_status'    => 'publish',
        'orderby'        => 'date',
        'order'          => 'DESC',
      ] );
      ?>

      <?php if ( $news_query->have_posts() ) : ?>
        <div class="news-list">
          <?php while ( $news_query->have_posts() ) : $news_query->the_post(); ?>
            <article class="news-item">
              <div class="news-meta">
                <time class="news-date" datetime="<?php echo esc_attr( get_the_date( 'Y-m-d' ) ); ?>">
                  <?php echo esc_html( get_the_date( 'Y.m.d' ) ); ?>
                </time>
                <?php
                $news_type = get_post_meta( get_the_ID(), '_hachi_news_type', true );
                $tag_label = $news_type ? strtoupper( $news_type ) : 'NEWS';
                ?>
                <span class="news-tag"><?php echo esc_html( $tag_label ); ?></span>
              </div>
              <h3 class="news-title">
                <a href="<?php the_permalink(); ?>" style="color:inherit;text-decoration:none;">
                  <?php the_title(); ?>
                </a>
              </h3>
              <a href="<?php the_permalink(); ?>" class="news-readmore">
                <?php esc_html_e( '続きを読む →', 'hachi' ); ?>
              </a>
            </article>
          <?php endwhile; wp_reset_postdata(); ?>
        </div>
      <?php else : ?>
        <p style="color:var(--color-caption, #3a3a3c); padding:40px 0; text-align:center;">
          <?php esc_html_e( 'お知らせはありません。', 'hachi' ); ?>
        </p>
      <?php endif; ?>

    </div>
  </section>

  <!-- ============================================================
       セクション 6: フッター CTA (黒背景)
       ============================================================ -->
  <section class="footer-cta" aria-label="<?php esc_attr_e( 'お問い合わせ CTA', 'hachi' ); ?>">
    <div class="container">
      <h2><?php esc_html_e( '組織のコンディションを、整理してみませんか。', 'hachi' ); ?></h2>
      <a href="<?php echo esc_url( home_url( '/contact/' ) ); ?>" class="btn-primary">
        <?php esc_html_e( 'お問い合わせ', 'hachi' ); ?>
      </a>
    </div>
  </section>

</main>

<?php get_footer(); ?>
