<?php
/**
 * HACHI Theme — Service page (v3.2 copy-led / 6 sections)
 * Auto-applied to page with slug "service"
 * Updated: 2026-06-18 PR-C — v3.2 全面書き換え
 */
get_header();
?>
<main id="main-content">

<main id="main-content">

<!-- ============================================================
     セクション 1: ヒーロー
     ============================================================ -->
<section class="service-hero" aria-label="Serviceページ ヒーロー">
  <div class="service-hero-inner">

    <div class="service-hero-copy">
      <span class="eyebrow"><?php esc_html_e( 'SERVICE', 'hachi' ); ?></span>
      <h1><?php esc_html_e( '状態を見える形にする。', 'hachi' ); ?></h1>
      <p class="sub-copy">
        <?php esc_html_e( '社員の状態変化のサインを、組織として早めにつかむ。', 'hachi' ); ?><br>
        <?php esc_html_e( 'コンディション・インサイトと HACHI Fieldwork で、', 'hachi' ); ?><br>
        <?php esc_html_e( '観察と介入を一続きの仕組みにします。', 'hachi' ); ?>
      </p>
    </div>

    <div class="service-hero-illustration" aria-hidden="true">
      <img
        src="<?php echo esc_url( get_template_directory_uri() . '/assets/hero-person.svg' ); ?>"
        alt=""
        width="320"
        height="480"
      >
    </div>

  </div>
</section>

<!-- ============================================================
     セクション 2: 4 つの観察軸
     ============================================================ -->
<section class="observation-axes section" aria-label="4つの観察軸">
  <div class="container">
    <h2><?php esc_html_e( '4 つの観察軸でコンディションを把握する', 'hachi' ); ?></h2>

    <div class="axes-grid" style="margin-top:48px;">

      <div class="axis-item">
        <div class="axis-mark" aria-hidden="true"></div>
        <div class="axis-title"><?php esc_html_e( '身体', 'hachi' ); ?></div>
        <p class="axis-desc"><?php esc_html_e( 'こわばり・重さ・可動域の変化を把握する', 'hachi' ); ?></p>
      </div>

      <div class="axis-item">
        <div class="axis-mark" aria-hidden="true"></div>
        <div class="axis-title"><?php esc_html_e( '睡眠', 'hachi' ); ?></div>
        <p class="axis-desc"><?php esc_html_e( '睡眠の質と回復状態の傾向を整理する', 'hachi' ); ?></p>
      </div>

      <div class="axis-item">
        <div class="axis-mark" aria-hidden="true"></div>
        <div class="axis-title"><?php esc_html_e( '集中', 'hachi' ); ?></div>
        <p class="axis-desc"><?php esc_html_e( '仕事中の集中のしやすさの変化を見る', 'hachi' ); ?></p>
      </div>

      <div class="axis-item">
        <div class="axis-mark" aria-hidden="true"></div>
        <div class="axis-title"><?php esc_html_e( '疲労', 'hachi' ); ?></div>
        <p class="axis-desc"><?php esc_html_e( '疲労の蓄積具合を組織単位で把握する', 'hachi' ); ?></p>
      </div>

    </div>

    <div class="axes-dot" aria-hidden="true">
      <span class="accent-dot"></span>
    </div>

  </div>
</section>

<!-- ============================================================
     セクション 3: 導入の流れ
     ============================================================ -->
<section class="intro-flow section" aria-label="導入の流れ">
  <div class="container">
    <h2><?php esc_html_e( '導入の流れ', 'hachi' ); ?></h2>

    <div class="flow-steps" style="margin-top:48px;">

      <div class="flow-step">
        <div class="flow-step-num">01</div>
        <div class="flow-step-title"><?php esc_html_e( '状態の可視化', 'hachi' ); ?></div>
        <p class="flow-step-desc"><?php esc_html_e( '社員の短いチェックから身体・睡眠・集中・疲労の傾向を組織として整理する', 'hachi' ); ?></p>
      </div>

      <div class="flow-arrow-col" aria-hidden="true">
        <svg width="20" height="16" viewBox="0 0 20 16" fill="none">
          <line x1="0" y1="8" x2="14" y2="8" stroke="#E5E5E7" stroke-width="1"/>
          <polyline points="10,4 16,8 10,12" stroke="#E5E5E7" stroke-width="1" fill="none" stroke-linecap="round" stroke-linejoin="round"/>
        </svg>
      </div>

      <div class="flow-step">
        <div class="flow-step-num">02</div>
        <div class="flow-step-title"><?php esc_html_e( '背景の仮説整理', 'hachi' ); ?></div>
        <p class="flow-step-desc"><?php esc_html_e( '状態のパターンから、背景にある要因を仮説として整理する', 'hachi' ); ?></p>
      </div>

      <div class="flow-arrow-col" aria-hidden="true">
        <svg width="20" height="16" viewBox="0 0 20 16" fill="none">
          <line x1="0" y1="8" x2="14" y2="8" stroke="#E5E5E7" stroke-width="1"/>
          <polyline points="10,4 16,8 10,12" stroke="#E5E5E7" stroke-width="1" fill="none" stroke-linecap="round" stroke-linejoin="round"/>
        </svg>
      </div>

      <div class="flow-step">
        <div class="flow-step-num">03</div>
        <div class="flow-step-title"><?php esc_html_e( 'コンディショニング介入', 'hachi' ); ?></div>
        <p class="flow-step-desc"><?php esc_html_e( 'HACHI Fieldwork によるストレッチコンディショニングで現場に介入する', 'hachi' ); ?></p>
      </div>

      <div class="flow-arrow-col" aria-hidden="true">
        <svg width="20" height="16" viewBox="0 0 20 16" fill="none">
          <line x1="0" y1="8" x2="14" y2="8" stroke="#E5E5E7" stroke-width="1"/>
          <polyline points="10,4 16,8 10,12" stroke="#E5E5E7" stroke-width="1" fill="none" stroke-linecap="round" stroke-linejoin="round"/>
        </svg>
      </div>

      <div class="flow-step">
        <div class="flow-step-num">04</div>
        <div class="flow-step-title"><?php esc_html_e( '変化の確認', 'hachi' ); ?></div>
        <p class="flow-step-desc"><?php esc_html_e( '介入前後の変化を組織レポートとして記録し、次の打ち手に接続する', 'hachi' ); ?></p>
      </div>

    </div>
  </div>
</section>

<!-- ============================================================
     セクション 4: 差別化対比表 — 「実施して終わり」との違い
     ============================================================ -->
<section class="comparison-section section" aria-label="一般的な出張整体との違い — 差別化セクション">
  <div class="container">

    <h2><?php esc_html_e( '「実施して終わり」との違い', 'hachi' ); ?></h2>
    <p class="comparison-sub">
      <?php esc_html_e( '出張整体は施術をゴールにします。コンディション・インサイトは、観察・記録・組織レポートを通じて、次の改善につなげる仕組みです。', 'hachi' ); ?>
    </p>

    <div class="comparison-table">

      <div class="comparison-col">
        <div class="comparison-col-title"><?php esc_html_e( '一般的な出張整体・福利厚生サービス', 'hachi' ); ?></div>
        <div class="comparison-rows">
          <div class="comparison-row">
            <div class="comparison-row-label"><?php esc_html_e( '目的', 'hachi' ); ?></div>
            <div class="comparison-row-value"><?php esc_html_e( '社員のリフレッシュ', 'hachi' ); ?></div>
          </div>
          <div class="comparison-row">
            <div class="comparison-row-label"><?php esc_html_e( '単位', 'hachi' ); ?></div>
            <div class="comparison-row-value"><?php esc_html_e( '個人（その場の体感）', 'hachi' ); ?></div>
          </div>
          <div class="comparison-row">
            <div class="comparison-row-label"><?php esc_html_e( '残るもの', 'hachi' ); ?></div>
            <div class="comparison-row-value"><?php esc_html_e( '体験のみ', 'hachi' ); ?></div>
          </div>
          <div class="comparison-row">
            <div class="comparison-row-label"><?php esc_html_e( '経営の使い方', 'hachi' ); ?></div>
            <div class="comparison-row-value"><?php esc_html_e( '福利厚生コスト', 'hachi' ); ?></div>
          </div>
        </div>
      </div>

      <div class="comparison-col">
        <div class="comparison-col-title"><?php esc_html_e( 'コンディション・インサイト（HACHI）', 'hachi' ); ?></div>
        <div class="comparison-rows">
          <div class="comparison-row">
            <div class="comparison-row-label"><?php esc_html_e( '目的', 'hachi' ); ?></div>
            <div class="comparison-row-value"><?php esc_html_e( '組織として状態を観察・整理する', 'hachi' ); ?></div>
          </div>
          <div class="comparison-row">
            <div class="comparison-row-label"><?php esc_html_e( '単位', 'hachi' ); ?></div>
            <div class="comparison-row-value"><?php esc_html_e( '組織（傾向・パターン）', 'hachi' ); ?></div>
          </div>
          <div class="comparison-row">
            <div class="comparison-row-label"><?php esc_html_e( '残るもの', 'hachi' ); ?></div>
            <div class="comparison-row-value"><?php esc_html_e( '組織レポート + 判断材料', 'hachi' ); ?></div>
          </div>
          <div class="comparison-row">
            <div class="comparison-row-label"><?php esc_html_e( '経営の使い方', 'hachi' ); ?></div>
            <div class="comparison-row-value"><?php esc_html_e( '健康経営施策の実行履歴 + 次の打ち手', 'hachi' ); ?></div>
          </div>
        </div>
        <div class="comparison-accent" aria-hidden="true">
          <span class="accent-dot"></span>
        </div>
      </div>

    </div>
  </div>
</section>

</main>
<!-- ============================================================
     セクション 5: FAQ
     ============================================================ -->
<section class="faq-section section" aria-label="よくある質問">
  <div class="container">
    <h2><?php esc_html_e( 'よくある質問', 'hachi' ); ?></h2>

    <div class="faq-list">

      <div class="faq-item">
        <div class="faq-q"><?php esc_html_e( 'どのような傾向を把握できますか?', 'hachi' ); ?></div>
        <p class="faq-a">
          <?php esc_html_e( '身体のこわばりや重さ、睡眠の質、仕事中の集中のしやすさ、疲労の蓄積具合を、社員の短いチェックから組織単位で整理します。', 'hachi' ); ?>
        </p>
      </div>

      <div class="faq-item">
        <div class="faq-q"><?php esc_html_e( '何名から導入できますか?', 'hachi' ); ?></div>
        <p class="faq-a">
          <?php esc_html_e( '20 名前後から対応しています。企業規模・目的に応じて、スポット実施から月次継続まで設計します。', 'hachi' ); ?>
        </p>
      </div>

      <div class="faq-item">
        <div class="faq-q"><?php esc_html_e( '一般的な出張整体や福利厚生サービスと何が違いますか?', 'hachi' ); ?></div>
        <p class="faq-a">
          <?php esc_html_e( '出張整体はリフレッシュが主目的です。コンディション・インサイトは、状態の観察・記録・組織レポートを提供します。', 'hachi' ); ?>
        </p>
      </div>

    </div>
  </div>
</section>

<!-- ============================================================
     セクション 6: フッター CTA（黒背景）
     ============================================================ -->
<section class="footer-cta" aria-label="お問い合わせ CTA">
  <div class="container">
    <h2><?php esc_html_e( 'サービスの詳細・導入時期をご相談ください。', 'hachi' ); ?></h2>
    <a href="<?php echo esc_url( home_url( '/contact/' ) ); ?>" class="btn-primary">
      <?php esc_html_e( 'お問い合わせ', 'hachi' ); ?>
    </a>
  </div>
</section>

</main>

<?php get_footer(); ?>
