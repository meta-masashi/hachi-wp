<?php
/**
 * HACHI Corporate Site v3.3 render helpers.
 *
 * @package HACHI
 */

defined( 'ABSPATH' ) || exit;

function hachi_v3_asset( string $path ): string {
    return HACHI_THEME_URI . '/' . ltrim( $path, '/' );
}

function hachi_v3_arrow(): string {
    return '<svg width="24" height="16" viewBox="0 0 24 16" fill="none" aria-hidden="true"><line x1="0" y1="8" x2="18" y2="8" stroke="currentColor" stroke-width="1"/><polyline points="14,4 20,8 14,12" stroke="currentColor" stroke-width="1" fill="none" stroke-linecap="round" stroke-linejoin="round"/></svg>';
}

function hachi_v3_person(): void {
    ?>
    <div class="v3-hero-figure" aria-hidden="true">
        <img src="<?php echo esc_url( hachi_v3_asset( 'assets/images/v3-person-lineart.png' ) ); ?>" alt="" width="260" height="460">
    </div>
    <?php
}

function hachi_v3_blue_mark(): string {
    return '<span class="v3-blue-mark" aria-hidden="true"><span></span></span>';
}

function hachi_v3_footer_cta( string $title ): void {
    ?>
    <section class="v3-footer-cta" aria-label="お問い合わせ CTA">
        <div class="v3-container">
            <h2><?php echo esc_html( $title ); ?></h2>
            <a href="<?php echo esc_url( home_url( '/contact/' ) ); ?>" class="v3-btn v3-btn--light">お問い合わせ</a>
        </div>
    </section>
    <?php
}

function hachi_v3_news_items(): array {
    return [
        [
            'date' => '2026.04.15',
            'datetime' => '2026-04-15',
            'category' => 'BLOG',
            'title' => 'コンディショニング管理に AI を導入する前に知っておくべき 3 つのこと',
        ],
        [
            'date' => '2026.04.15',
            'datetime' => '2026-04-15',
            'category' => 'BLOG',
            'title' => 'トレーナーの "なんとなく" を AI に翻訳する ── body-part prior という考え方',
        ],
        [
            'date' => '2026.03.31',
            'datetime' => '2026-03-31',
            'category' => 'BLOG',
            'title' => 'AI がスポーツ現場の「評価」を変える｜トレーナーの推論は機械に勝てるのか',
        ],
        [
            'date' => '2026.03.25',
            'datetime' => '2026-03-25',
            'category' => 'BLOG',
            'title' => 'センサーが現場を変えているか？──ウェアラブルデバイスによる傷害リスク評価',
        ],
    ];
}

function hachi_v3_render_home(): void {
    $news = array_slice( hachi_v3_news_items(), 0, 2 );
    ?>
    <main id="main-content" class="v3-page v3-home">
        <section class="v3-hero v3-hero--split v3-hero--home" aria-label="ヒーロー">
            <div class="v3-hero__inner">
                <div class="v3-hero__copy">
                    <h1>身体の暗黙知を、<br>再現可能な判断知へ。</h1>
                    <p>経験と勘で動く現場を、組織が引き継げる判断の体系に変える。社員の身体・睡眠・集中・疲労を、観察できる材料にする。</p>
                    <a href="<?php echo esc_url( home_url( '/service/' ) ); ?>" class="v3-btn v3-btn--accent">サービスを見る</a>
                </div>
                <?php hachi_v3_person(); ?>
            </div>
        </section>

        <section class="v3-section v3-section--center v3-home-statement">
            <div class="v3-container v3-narrow">
                <h2>株式会社 HACHI は、<br>「変化を見抜き、判断を支える」会社です。</h2>
                <p>コンディション・インサイトは、社員の短いチェックから身体・睡眠・集中・疲労の傾向を組織として整理する法人向けサービスです。経営者・人事・管理職の日常判断に使える情報を提供します。</p>
            </div>
        </section>

        <section class="v3-section v3-service-split">
            <div class="v3-container">
                <div class="v3-two-col">
                    <article class="v3-line-card">
                        <p class="v3-card-kicker">SERVICE 01</p>
                        <h3>コンディション・インサイト</h3>
                        <p>心身のコンディションを多面的に可視化し、変化の兆しを早期につかむためのインサイトを提供します。</p>
                        <a href="<?php echo esc_url( home_url( '/service/' ) ); ?>">詳細を見る →</a>
                    </article>
                    <article class="v3-line-card">
                        <p class="v3-card-kicker">SERVICE 02</p>
                        <h3>HACHI Fieldwork</h3>
                        <p>専門スタッフが現場に入り、観察・対話・記録を通じて、次の一手につながる情報へ変換します。</p>
                        <a href="<?php echo esc_url( home_url( '/service/' ) ); ?>">詳細を見る →</a>
                    </article>
                </div>
            </div>
        </section>

        <section class="v3-section v3-news-mini">
            <div class="v3-container v3-narrow">
                <h2 class="v3-center">直近のお知らせ</h2>
                <?php foreach ( $news as $item ) : ?>
                    <article class="v3-news-row">
                        <div class="v3-news-meta">
                            <time datetime="<?php echo esc_attr( $item['datetime'] ); ?>"><?php echo esc_html( $item['date'] ); ?></time>
                            <span><?php echo esc_html( $item['category'] ); ?></span>
                        </div>
                        <h3><?php echo esc_html( $item['title'] ); ?></h3>
                        <a href="<?php echo esc_url( home_url( '/news/' ) ); ?>">続きを読む →</a>
                    </article>
                <?php endforeach; ?>
            </div>
        </section>

        <?php hachi_v3_footer_cta( '組織のコンディションを、整理してみませんか。' ); ?>
    </main>
    <?php
}

function hachi_v3_render_service(): void {
    ?>
    <main id="main-content" class="v3-page v3-service">
        <section class="v3-hero v3-hero--split v3-hero--compact v3-hero--center-figure" aria-label="Service ヒーロー">
            <div class="v3-hero__inner">
                <div class="v3-hero__copy">
                    <p class="v3-eyebrow">SERVICE</p>
                    <h1>状態を見える形にする。</h1>
                    <p>社員の状態変化のサインを、組織として早めにつかむ。コンディション・インサイトと HACHI Fieldwork で、観察と介入を一続きの仕組みにします。</p>
                </div>
                <?php hachi_v3_person(); ?>
            </div>
        </section>

        <section class="v3-section v3-section--center">
            <div class="v3-container">
                <h2><span>4</span> つの観察軸でコンディションを把握する</h2>
                <div class="v3-axis-grid">
                    <?php
                    $axes = [
                        [ '身体', 'こわばり・重さ・可動域の変化を把握する' ],
                        [ '睡眠', '睡眠の質と回復状態の傾向を整理する' ],
                        [ '集中', '仕事中の集中のしやすさの変化を見る' ],
                        [ '疲労', '疲労の蓄積具合を組織単位で把握する' ],
                    ];
                    foreach ( $axes as $axis ) :
                        ?>
                        <article class="v3-axis">
                            <span></span>
                            <h3><?php echo esc_html( $axis[0] ); ?></h3>
                            <p><?php echo esc_html( $axis[1] ); ?></p>
                        </article>
                    <?php endforeach; ?>
                </div>
                <span class="v3-dot" aria-hidden="true"></span>
            </div>
        </section>

        <section class="v3-section v3-section--center">
            <div class="v3-container">
                <h2>導入の流れ</h2>
                <div class="v3-flow v3-flow--four">
                    <?php
                    $steps = [
                        [ '01', '状態の可視化', '社員の短いチェックから身体・睡眠・集中・疲労の傾向を組織として整理する' ],
                        [ '02', '背景の仮説整理', '状態のパターンから、背景にある要因を仮説として整理する' ],
                        [ '03', 'コンディショニング介入', 'HACHI Fieldwork によるストレッチコンディショニングで現場に介入する' ],
                        [ '04', '変化の確認', '介入前後の変化を組織レポートとして記録し、次の打ち手に接続する' ],
                    ];
                    foreach ( $steps as $index => $step ) :
                        if ( $index > 0 ) echo '<div class="v3-flow__arrow">' . hachi_v3_arrow() . '</div>';
                        ?>
                        <div class="v3-flow__step v3-flow__step--numbered">
                            <span><?php echo esc_html( $step[0] ); ?></span>
                            <h3><?php echo esc_html( $step[1] ); ?></h3>
                            <p><?php echo esc_html( $step[2] ); ?></p>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </section>

        <section class="v3-section">
            <div class="v3-container v3-narrow">
                <h2 class="v3-center">よくある質問</h2>
                <div class="v3-faq">
                    <article><h3>Q1. どのような傾向を把握できますか?</h3><p>身体のこわばりや重さ、睡眠の質、仕事中の集中のしやすさ、疲労の蓄積具合を、社員の短いチェックから組織単位で整理します。</p></article>
                    <article><h3>Q2. 何名から導入できますか?</h3><p>20 名前後から対応しています。企業規模・目的に応じて、スポット実施から月次継続まで設計します。</p></article>
                    <article><h3>Q3. 一般的な出張整体や福利厚生サービスと何が違いますか?</h3><p>出張整体はリフレッシュが主目的です。コンディション・インサイトは、状態の観察・記録・組織レポートを提供します。</p></article>
                </div>
            </div>
        </section>

        <?php hachi_v3_footer_cta( 'サービスの詳細・導入時期をご相談ください。' ); ?>
    </main>
    <?php
}

function hachi_v3_render_about(): void {
    ?>
    <main id="main-content" class="v3-page v3-about">
        <section class="v3-hero v3-hero--split v3-hero--tall v3-hero--center-figure" aria-label="About Us ヒーロー">
            <div class="v3-hero__inner">
                <div class="v3-hero__copy">
                    <p class="v3-eyebrow">ABOUT</p>
                    <h1>身体の暗黙知を、<br>再現可能な判断知へ。</h1>
                    <p class="v3-subthesis">経験と勘を、引き継げる体系に。</p>
                </div>
                <?php hachi_v3_person(); ?>
            </div>
        </section>

        <section class="v3-section">
            <div class="v3-container v3-narrow v3-stack">
                <article>
                    <p class="v3-label">ミッション</p>
                    <h2>テクノロジーで、人と人の「向き合う時間」を取り戻す。</h2>
                    <p>状態の観察と構造化をテクノロジーに委ねることで、管理職や経営者は「目の前の社員」に向き合える時間を取り戻せる。HACHIは現場の実証を積み重ねながら、身体の状態を再現可能な判断知へ変換する基盤をつくります。</p>
                </article>
                <article>
                    <p class="v3-label">ビジョン</p>
                    <h2>誰もが、「健康」で悩まない世界をつくる。</h2>
                    <p>もし、病気や怪我の心配をせず、今を思いきり楽しめる世界があったなら。私たちは最新のテクノロジーや科学の力を使いながら、一人ひとりが確かな健康を抱き、人生の可能性を広げる鍵になることを目指しています。</p>
                </article>
            </div>
        </section>

        <section class="v3-section v3-section--center v3-about-flow-section">
            <div class="v3-container">
                <div class="v3-section-head">
                    <h2>観察・構造化・判断</h2>
                    <p>身体の暗黙知を、再現可能な判断知へ変換する 3 つのプロセス</p>
                </div>
                <div class="v3-flow v3-flow--three">
                    <?php
                    $steps = [
                        [ '観察', '現場の身体・睡眠・集中・疲労のサインを取り出す' ],
                        [ '構造化', 'サインを傾向として組織単位で整理・体系化する' ],
                        [ '判断', '次の打ち手を組織として再現可能にする' ],
                    ];
                    foreach ( $steps as $index => $step ) :
                        if ( $index > 0 ) echo '<div class="v3-flow__arrow">' . hachi_v3_arrow() . '</div>';
                        ?>
                        <div class="v3-flow__step">
                            <span class="v3-flow__mark<?php echo $index === 2 ? ' is-accent' : ''; ?>"></span>
                            <h3><?php echo esc_html( $step[0] ); ?></h3>
                            <p><?php echo esc_html( $step[1] ); ?></p>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </section>

        <section class="v3-section v3-rep">
            <div class="v3-container v3-narrow">
                <h2 class="v3-center">代表より</h2>
                <div class="v3-rep__photo" role="img" aria-label="代表取締役社長 佐々木譲崇 写真プレースホルダ"></div>
                <p class="v3-rep__name">代表取締役社長 佐々木譲崇</p>
                <p>現場で長年積み重ねた経験から確信したことがある。テクノロジーは、人と人の間にある「温かさ」を消すためにあるのではない。むしろ、煩雑な作業や曖昧な判断をテクノロジーが担うことで、人はより深く、目の前の人と向き合えるようになる。</p>
            </div>
        </section>

        <?php hachi_v3_footer_cta( 'HACHI と話す' ); ?>
    </main>
    <?php
}

function hachi_v3_render_company(): void {
    ?>
    <main id="main-content" class="v3-page v3-company">
        <section class="v3-hero v3-hero--simple v3-hero--decorated" aria-label="Company ヒーロー">
            <div class="v3-container v3-narrow">
                <p class="v3-eyebrow">COMPANY</p>
                <h1>会社情報</h1>
                <p>HACHI Inc.</p>
                <?php echo hachi_v3_blue_mark(); ?>
            </div>
        </section>

        <section class="v3-section v3-ledger-section">
            <span class="v3-side-ornament v3-side-ornament--left" aria-hidden="true"></span>
            <span class="v3-side-ornament v3-side-ornament--right" aria-hidden="true"></span>
            <div class="v3-container v3-narrow">
                <h2 class="v3-center">会社概要</h2>
                <dl class="v3-company-table">
                    <div><dt>会社名</dt><dd>株式会社 HACHI / HACHI Inc.</dd></div>
                    <div><dt>代表者</dt><dd>佐々木 譲崇（代表取締役社長）</dd></div>
                    <div><dt>設立</dt><dd>2022 年 3 月 25 日</dd></div>
                    <div><dt>資本金</dt><dd>100 万円</dd></div>
                    <div><dt>所在地</dt><dd>〒180-0004 東京都武蔵野市吉祥寺本町 1-13-2 5F</dd></div>
                </dl>
            </div>
        </section>

        <section class="v3-section v3-business-section">
            <div class="v3-container v3-narrow">
                <h2 class="v3-center">事業内容</h2>
                <p>On-site Service「HACHI Fieldwork / コンディション・インサイト」の提供、身体の状態観察・構造化・判断知変換に関する研究開発</p>
                <a href="<?php echo esc_url( home_url( '/service/' ) ); ?>" class="v3-text-link">サービス詳細を見る →</a>
            </div>
        </section>

        <?php hachi_v3_footer_cta( '導入のご相談はお気軽に。' ); ?>
    </main>
    <?php
}

function hachi_v3_render_contact(): void {
    ?>
    <main id="main-content" class="v3-page v3-contact">
        <section class="v3-hero v3-hero--simple v3-hero--decorated" aria-label="Contact ヒーロー">
            <div class="v3-container v3-narrow">
                <p class="v3-eyebrow">CONTACT</p>
                <h1>お問い合わせ</h1>
                <p>コンディション・インサイト / HACHI Fieldwork について、お気軽にご連絡ください。担当より 2 営業日以内にご返信します。</p>
                <?php echo hachi_v3_blue_mark(); ?>
            </div>
        </section>

        <section class="v3-section v3-contact-section">
            <span class="v3-side-ornament v3-side-ornament--left" aria-hidden="true"></span>
            <span class="v3-side-ornament v3-side-ornament--right v3-side-ornament--low" aria-hidden="true"></span>
            <div class="v3-container v3-form-wrap">
                <div id="form-success" class="form-success v3-form-success" role="status" aria-live="polite" tabindex="-1">
                    <strong>お問い合わせを受け付けました。</strong><br>
                    <span>担当より 2 営業日以内にご連絡いたします。</span>
                </div>
                <form id="contact-form" class="v3-contact-form" novalidate aria-label="お問い合わせフォーム">
                    <?php wp_nonce_field( 'hachi_nonce', 'contact_nonce' ); ?>
                    <input type="hidden" name="contact_cat" value="コンディション・インサイトのご相談">
                    <input type="hidden" name="contact_timeline" value="">
                    <div style="position:absolute;left:-9999px;opacity:0;pointer-events:none" aria-hidden="true">
                        <label>Website<input type="text" name="website" tabindex="-1" autocomplete="off"></label>
                    </div>

                    <div class="v3-form-field" id="field-company">
                        <label for="contact-company">会社名 <span>必須</span></label>
                        <input type="text" id="contact-company" name="contact_company" placeholder="入力してください" maxlength="200" autocomplete="organization" required>
                    </div>
                    <div class="v3-form-field" id="field-name">
                        <label for="contact-name">お名前 <span>必須</span></label>
                        <input type="text" id="contact-name" name="contact_name" placeholder="入力してください" maxlength="100" autocomplete="name" required aria-required="true" aria-describedby="err-name">
                        <span class="form-field__error" id="err-name" role="alert">お名前をご入力ください。</span>
                    </div>
                    <div class="v3-form-field" id="field-email">
                        <label for="contact-email">メールアドレス <span>必須</span></label>
                        <input type="email" id="contact-email" name="contact_email" placeholder="入力してください" maxlength="254" autocomplete="email" required aria-required="true" aria-describedby="err-email">
                        <span class="form-field__error" id="err-email" role="alert">正しいメールアドレスを入力してください。</span>
                    </div>
                    <div class="v3-form-field">
                        <label for="contact-phone">電話番号 <em>任意</em></label>
                        <input type="tel" id="contact-phone" name="contact_phone" placeholder="入力してください" maxlength="20" autocomplete="tel">
                    </div>
                    <div class="v3-form-field">
                        <label for="contact-size">従業員数</label>
                        <select id="contact-size" name="contact_size">
                            <option value="">選択してください</option>
                            <option value="〜50名">〜20 名</option>
                            <option value="〜50名">20〜50 名</option>
                            <option value="51〜300名">50〜100 名</option>
                            <option value="51〜300名">100 名以上</option>
                        </select>
                    </div>
                    <div class="v3-form-field" id="field-message">
                        <label for="contact-message">お問い合わせ内容</label>
                        <textarea id="contact-message" name="contact_message" rows="6" maxlength="1000" placeholder="ご質問・ご要望をご記入ください（最大 1000 字）" aria-required="true" aria-describedby="err-message"></textarea>
                        <span class="form-field__error" id="err-message" role="alert">お問い合わせ内容をご入力ください。</span>
                    </div>
                    <label class="v3-form-privacy" id="field-privacy">
                        <input type="checkbox" name="privacy" required>
                        <span><a href="<?php echo esc_url( home_url( '/privacy-policy/' ) ); ?>" target="_blank" rel="noopener">プライバシーポリシー</a>をご確認ください</span>
                        <span class="form-field__error" id="err-privacy" role="alert">プライバシーポリシーをご確認ください。</span>
                    </label>
                    <button type="submit" class="v3-btn v3-btn--accent" id="form-submit"><span id="submit-text">送信する</span></button>
                    <p id="form-general-error" class="v3-form-error" role="alert"></p>
                </form>
            </div>
        </section>
    </main>
    <?php
}

function hachi_v3_render_news(): void {
    ?>
    <main id="main-content" class="v3-page v3-news">
        <section class="v3-hero v3-hero--simple v3-news-hero" aria-label="News ヒーロー">
            <div class="v3-container v3-narrow">
                <p class="v3-eyebrow">NEWS</p>
                <h1>ニュース・知見</h1>
                <p>サービス更新と現場で気づいたことを記録します。</p>
            </div>
        </section>

        <section class="v3-section">
            <div class="v3-container v3-narrow">
                <ol class="v3-news-list">
                    <?php foreach ( hachi_v3_news_items() as $item ) : ?>
                        <li>
                            <article class="v3-news-row">
                                <div class="v3-news-meta">
                                    <time datetime="<?php echo esc_attr( $item['datetime'] ); ?>"><?php echo esc_html( $item['date'] ); ?></time>
                                    <span><?php echo esc_html( $item['category'] ); ?></span>
                                </div>
                                <h2><?php echo esc_html( $item['title'] ); ?></h2>
                                <a href="<?php echo esc_url( home_url( '/news/' ) ); ?>">続きを読む →</a>
                            </article>
                        </li>
                    <?php endforeach; ?>
                </ol>
            </div>
        </section>

        <?php hachi_v3_footer_cta( '導入のご相談はこちらから。' ); ?>
    </main>
    <?php
}
