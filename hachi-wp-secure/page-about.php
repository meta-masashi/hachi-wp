<?php
/**
 * HACHI Theme — About Us page (v3.2 copy-led / 5 sections)
 * Auto-applied to page with slug "about"
 * Updated: 2026-06-18 PR-C — v3.2 全面書き換え
 */
get_header();
?>
<main id="main-content">

<main id="main-content">

<!-- ============================================================
     セクション 1: ヒーロー
     ============================================================ -->
<section class="about-hero" aria-label="About Us ヒーロー">
  <div class="about-hero-inner">

    <div class="about-hero-copy">
      <span class="eyebrow"><?php esc_html_e( 'ABOUT', 'hachi' ); ?></span>
      <h1><?php esc_html_e( '身体知を、再現可能な判断知へ。', 'hachi' ); ?></h1>
      <p class="about-subthesis"><?php esc_html_e( '経験と勘を、引き継げる体系に。', 'hachi' ); ?></p>
    </div>

    <div class="about-hero-illustration" aria-hidden="true">
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
     セクション 2: ミッション・ビジョン
     ============================================================ -->
<section class="mission-vision section" aria-label="ミッションとビジョン">
  <div class="container">
    <div class="mission-vision-inner">

      <div class="mv-block">
        <div class="mv-block-label"><?php esc_html_e( 'ミッション', 'hachi' ); ?></div>
        <div class="mv-block-lead"><?php esc_html_e( 'テクノロジーで、人と人の「向き合う時間」を取り戻す。', 'hachi' ); ?></div>
        <p class="mv-block-body">
          <?php esc_html_e( '状態の観察と構造化をテクノロジーに委ねることで、管理職や経営者は「目の前の社員」に向き合える時間を取り戻せる。HACHIは現場の実証を積み重ねながら、身体の状態を再現可能な判断知へ変換する基盤をつくります。', 'hachi' ); ?>
        </p>
      </div>

      <div class="mv-block">
        <div class="mv-block-label"><?php esc_html_e( 'ビジョン', 'hachi' ); ?></div>
        <div class="mv-block-lead"><?php esc_html_e( '誰もが、「健康」で悩まない世界をつくる。', 'hachi' ); ?></div>
        <p class="mv-block-body">
          <?php esc_html_e( 'もし、病気や怪我の心配をせず、今を思いきり楽しめる世界があったなら。私たちは最新のテクノロジーや科学の力を使いながら、一人ひとりが確かな健康を抱き、人生の可能性を広げる鍵になることを目指しています。', 'hachi' ); ?>
        </p>
      </div>

    </div>
  </div>
</section>

<!-- ============================================================
     セクション 3: 思想の構造図 — 観察・構造化・判断
     ============================================================ -->
<section class="philosophy-flow section" aria-label="思想の構造図 — 観察・構造化・判断">
  <div class="container">

    <div style="text-align:center; margin-bottom:56px;">
      <h2><?php esc_html_e( '観察・構造化・判断', 'hachi' ); ?></h2>
      <p class="caption" style="margin-top:16px; line-height:1.8; color:var(--color-caption); font-size:16px;">
        <?php esc_html_e( '身体知を、再現可能な判断知へ変換する 3 つのプロセス', 'hachi' ); ?>
      </p>
    </div>

    <div class="flow-3">

      <div class="flow-3-step">
        <div class="flow-3-circle" aria-hidden="true"></div>
        <div class="flow-3-title"><?php esc_html_e( '観察', 'hachi' ); ?></div>
        <p class="flow-3-desc"><?php esc_html_e( '現場の身体・睡眠・集中・疲労のサインを取り出す', 'hachi' ); ?></p>
      </div>

      <div class="flow-3-arrow" aria-hidden="true">
        <svg width="32" height="16" viewBox="0 0 32 16" fill="none">
          <line x1="0" y1="8" x2="24" y2="8" stroke="#E5E5E7" stroke-width="1"/>
          <polyline points="20,4 28,8 20,12" stroke="#E5E5E7" stroke-width="1" fill="none" stroke-linecap="round" stroke-linejoin="round"/>
        </svg>
      </div>

      <div class="flow-3-step">
        <div class="flow-3-circle" aria-hidden="true"></div>
        <div class="flow-3-title"><?php esc_html_e( '構造化', 'hachi' ); ?></div>
        <p class="flow-3-desc"><?php esc_html_e( 'サインを傾向として組織単位で整理・体系化する', 'hachi' ); ?></p>
      </div>

      <div class="flow-3-arrow" aria-hidden="true">
        <svg width="32" height="16" viewBox="0 0 32 16" fill="none">
          <line x1="0" y1="8" x2="24" y2="8" stroke="#E5E5E7" stroke-width="1"/>
          <polyline points="20,4 28,8 20,12" stroke="#E5E5E7" stroke-width="1" fill="none" stroke-linecap="round" stroke-linejoin="round"/>
        </svg>
      </div>

      <div class="flow-3-step">
        <div class="flow-3-circle accent" aria-hidden="true"></div>
        <div class="flow-3-title"><?php esc_html_e( '判断', 'hachi' ); ?></div>
        <p class="flow-3-desc"><?php esc_html_e( '次の打ち手を組織として再現可能にする', 'hachi' ); ?></p>
      </div>

    </div>

  </div>
</section>

<!-- ============================================================
     セクション 4: 代表メッセージ
     ============================================================ -->
<section class="rep-message section" aria-label="代表メッセージ">
  <div class="container">
    <h2><?php esc_html_e( '代表より', 'hachi' ); ?></h2>

    <div class="rep-message-inner">
      <div class="rep-photo" role="img" aria-label="<?php esc_attr_e( '代表取締役社長 佐々木譲崇 写真プレースホルダ', 'hachi' ); ?>"></div>
      <p class="rep-name"><?php esc_html_e( '代表取締役社長 佐々木譲崇', 'hachi' ); ?></p>
      <p class="rep-body">
        <?php esc_html_e( '現場で長年積み重ねた経験から確信したことがある。テクノロジーは、人と人の間にある「温かさ」を消すためにあるのではない。むしろ、煩雑な作業や曖昧な判断をテクノロジーが担うことで、人はより深く、目の前の人と向き合えるようになる。', 'hachi' ); ?>
      </p>
    </div>
  </div>
</section>

<!-- ============================================================
     セクション 5: フッター CTA（黒背景）
     ============================================================ -->
<section class="footer-cta" aria-label="お問い合わせ CTA">
  <div class="container">
    <h2><?php esc_html_e( 'HACHI と話す', 'hachi' ); ?></h2>
    <a href="<?php echo esc_url( home_url( '/contact/' ) ); ?>" class="btn-primary">
      <?php esc_html_e( 'お問い合わせ', 'hachi' ); ?>
    </a>
  </div>
</section>

</main>

</main>
<?php get_footer(); ?>
