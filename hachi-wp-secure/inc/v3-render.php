<?php
/**
 * HACHI Corporate Site render helpers.
 *
 * @package HACHI
 */

defined( 'ABSPATH' ) || exit;

function hachi_cd_arrow(): string {
    return '<span aria-hidden="true">&rarr;</span>';
}

function hachi_cd_eyebrow( string $label ): void {
    ?>
    <div class="cd-eyebrow"><span></span><p><?php echo esc_html( $label ); ?></p></div>
    <?php
}

function hachi_cd_more( string $url, string $label ): void {
    printf(
        '<a class="cd-more" href="%s">%s %s</a>',
        esc_url( $url ),
        esc_html( $label ),
        hachi_cd_arrow()
    );
}

function hachi_cd_contact_dark(): void {
    ?>
    <section class="cd-section cd-contact-dark" id="contact">
        <div class="cd-container">
            <?php hachi_cd_eyebrow( 'Contact' ); ?>
            <div class="cd-deco">CONTACT</div>
            <h2>お問い合わせ</h2>
            <p>コンディション・インサイトのご相談、資料請求、取材・協業のご連絡はメールにてお気軽にどうぞ。</p>
            <p class="cd-contact-email">Email: <a href="mailto:info@hachi-wellnesshack.com">info@hachi-wellnesshack.com</a></p>
            <div class="cd-btn-group">
                <a class="cd-btn cd-btn--light" href="<?php echo esc_url( home_url( '/contact/' ) ); ?>">お問い合わせフォーム <?php echo hachi_cd_arrow(); ?></a>
                <a class="cd-btn cd-btn--ghost" href="<?php echo esc_url( home_url( '/service/' ) ); ?>">サービスを見る <?php echo hachi_cd_arrow(); ?></a>
            </div>
        </div>
    </section>
    <?php
}

function hachi_cd_footer_note(): string {
    return 'コンディション・インサイトは医療行為ではありません。健康状態に不安がある場合は医療機関へご相談ください。';
}

function hachi_cd_note_url_for_post( int $post_id ): string {
    $url = trim( (string) get_post_meta( $post_id, '_hachi_note_url', true ) );
    if ( $url === '' || ! wp_http_validate_url( $url ) ) {
        return '';
    }
    $host = wp_parse_url( $url, PHP_URL_HOST );
    if ( ! is_string( $host ) || ! preg_match( '/(^|\.)note\.com$/i', $host ) ) {
        return '';
    }
    return esc_url_raw( $url );
}

function hachi_cd_news_items( int $limit = 30 ): array {
    if ( function_exists( 'hachi_get_classified_items' ) ) {
        return hachi_get_classified_items( [ 'category' => 'all', 'limit' => $limit ] );
    }
    return [];
}

function hachi_v3_render_home(): void {
    ?>
    <main id="main-content" class="cd-page cd-home">
        <section class="cd-hero">
            <div class="cd-container cd-hero-grid">
                <div class="cd-hero-copy">
                    <?php hachi_cd_eyebrow( 'Condition Insight' ); ?>
                    <h1>変化のサインを、<br>見逃さない。</h1>
                    <p class="cd-hero-sub">社員のコンディションの変化を、組織で早めに気づける形に。</p>
                    <div class="cd-btn-group">
                        <a class="cd-btn cd-btn--dark" href="<?php echo esc_url( home_url( '/service/' ) ); ?>">サービスを見る <?php echo hachi_cd_arrow(); ?></a>
                        <a class="cd-btn cd-btn--outline" href="<?php echo esc_url( home_url( '/contact/' ) ); ?>">お問い合わせ <?php echo hachi_cd_arrow(); ?></a>
                    </div>
                </div>
                <p class="cd-hero-tagline">身体の暗黙知を、再現可能な判断知へ。</p>
            </div>
            <span class="cd-scroll">Scroll</span>
        </section>

        <section class="cd-section cd-section--gray">
            <div class="cd-container">
                <?php hachi_cd_eyebrow( 'About' ); ?>
                <div class="cd-deco">ABOUT</div>
                <h2>HACHIとは</h2>
                <div class="cd-copy">
                    <p>HACHIは、社員のコンディションの変化を組織で見える形にする会社です。20〜100名規模の中小企業で「なんとなく不調」のサインが見えづらい構造に、状態の可視化とコンディショニング指導で向き合います。</p>
                    <p class="cd-serif-copy">身体の専門知を、観察・記録・再現できる形にする。それが、HACHIの事業の土台にある考え方です。</p>
                </div>
                <?php hachi_cd_more( home_url( '/about/' ), 'HACHIについて' ); ?>
            </div>
        </section>

        <section class="cd-section">
            <div class="cd-container">
                <?php hachi_cd_eyebrow( 'Service' ); ?>
                <div class="cd-deco">SERVICE</div>
                <h2>状態を、見える形にする。</h2>
                <div class="cd-copy">
                    <p>人は突然、不調になるのではありません。疲れや集中の変化は、じわじわと積み重なります。コンディション・インサイトは、そのサインを4つのステップで組織に見える形にします。</p>
                </div>
                <div class="cd-step-grid cd-step-grid--compact">
                    <div class="cd-step-compact"><span>01</span><p>状態の可視化</p></div>
                    <div class="cd-step-compact"><span>02</span><p>背景の仮説整理</p></div>
                    <div class="cd-step-compact"><span>03</span><p>コンディショニング介入</p></div>
                    <div class="cd-step-compact"><span>04</span><p>変化の確認</p></div>
                </div>
                <?php hachi_cd_more( home_url( '/service/' ), 'サービス詳細' ); ?>
            </div>
        </section>

        <?php hachi_cd_contact_dark(); ?>
    </main>
    <?php
}

function hachi_v3_render_about(): void {
    ?>
    <main id="main-content" class="cd-page">
        <section class="cd-page-hero">
            <div class="cd-container">
                <?php hachi_cd_eyebrow( 'About' ); ?>
                <h1>HACHIとは</h1>
                <p class="cd-page-lead">身体の専門知を、属人化させない。<br>HACHIは、観察・記録・再現できる判断知へと変換することを事業の土台に置いています。</p>
            </div>
        </section>

        <section class="cd-section">
            <div class="cd-container cd-narrow">
                <h2>「なんとなく不調」を、組織で扱える形に。</h2>
                <p>多くの中小企業では、社員の身体の不調が「個人の問題」として扱われ、見えないまま進行します。HACHIは、社員のコンディションの変化を組織で見える形にし、本人と管理職が早めに動けるきっかけをつくります。</p>
                <p>私たちが大切にするのは、決めつけではありません。状態を観察し、傾向を整理し、次に確認すべきことを明らかにする。その積み重ねを、再現できる形で残していくことです。</p>
                <div class="cd-thesis"><p>身体の専門知を、観察・記録・再現できる形にする。それが、HACHIの事業の土台にある考え方です。</p></div>
            </div>
        </section>

        <section class="cd-section cd-section--gray">
            <div class="cd-container cd-narrow">
                <?php hachi_cd_eyebrow( 'Values' ); ?>
                <h2>私たちの姿勢</h2>
                <div class="cd-values">
                    <article><span>01</span><div><h3>観察する</h3><p>結論を急がず、身体・睡眠・集中・疲労の状態を丁寧に観る。</p></div></article>
                    <article><span>02</span><div><h3>構造化する</h3><p>個別の訴えを、組織全体の傾向として整理し、判断できる情報に変える。</p></div></article>
                    <article><span>03</span><div><h3>再現できる形にする</h3><p>属人的な勘に閉じず、誰が見ても辿れる記録として残す。</p></div></article>
                </div>
            </div>
        </section>
    </main>
    <?php
}

function hachi_v3_render_service(): void {
    ?>
    <main id="main-content" class="cd-page">
        <section class="cd-page-hero">
            <div class="cd-container">
                <?php hachi_cd_eyebrow( 'Condition Insight' ); ?>
                <h1>状態を、見える形にする。</h1>
                <p class="cd-page-lead">コンディション・インサイトは、社員の身体・睡眠・集中・疲労の傾向を組織で見える形にし、現場のコンディショニング指導までを一貫して支えるサービスです。</p>
            </div>
        </section>

        <section class="cd-section">
            <div class="cd-container cd-narrow">
                <h2>4つのステップ</h2>
                <div class="cd-steps">
                    <article><span>Step 1</span><h3>状態の可視化</h3><p>社員が10分以内で答える短いチェックをもとに、現在の身体・睡眠・集中・疲労の傾向を整理します。参加者本人の事前同意を取得のうえ実施します。</p></article>
                    <article><span>Step 2</span><h3>背景の仮説整理</h3><p>個別の回答をそのまま伝えるのではなく、組織全体の傾向として整理します。どのような状態の社員が多いか、経営者・管理職が把握しやすい形でまとめます。</p></article>
                    <article><span>Step 3</span><h3>コンディショニング介入</h3><p>希望する企業には、有資格スタッフが現場に入り、状態に合ったストレッチ・コンディショニング指導とセルフケアの方法をお伝えします。</p></article>
                    <article><span>Step 4</span><h3>変化の確認</h3><p>一定期間後に再評価を行い、傾向の推移を確認します。組織の傾向の変化を、経営者向けの状態傾向レポートでお届けします。</p></article>
                </div>
            </div>
        </section>

        <section class="cd-section cd-section--gray">
            <div class="cd-container cd-narrow">
                <?php hachi_cd_eyebrow( 'Scope' ); ?>
                <h2>提供する範囲</h2>
                <p>コンディション・インサイトが提供するのは「状態の可視化」と「コンディショニング指導」です。</p>
                <div class="cd-scope">
                    <div>身体・睡眠・集中・疲労の傾向の可視化</div>
                    <div>組織全体の状態傾向レポート</div>
                    <div>有資格スタッフによる現場指導</div>
                    <div>セルフケア・ストレッチの指導</div>
                </div>
                <p class="cd-note">対象：20〜100名規模の中小企業・法人。個人を特定できる情報を会社に渡すことはありません。</p>
            </div>
        </section>

        <?php hachi_cd_contact_dark(); ?>
    </main>
    <?php
}

function hachi_v3_render_company(): void {
    ?>
    <main id="main-content" class="cd-page">
        <section class="cd-page-hero">
            <div class="cd-container">
                <?php hachi_cd_eyebrow( 'Company' ); ?>
                <h1>会社概要</h1>
            </div>
        </section>

        <section class="cd-section cd-section--flush">
            <div class="cd-container cd-narrow">
                <table class="cd-company-table">
                    <tbody>
                        <tr><th>会社名</th><td>株式会社HACHI<br>HACHI Inc.</td></tr>
                        <tr><th>代表者</th><td>佐々木 譲崇（代表取締役社長）</td></tr>
                        <tr><th>設立</th><td>2022年3月25日</td></tr>
                        <tr><th>資本金</th><td>100万円</td></tr>
                        <tr><th>所在地</th><td>〒180-0004 東京都武蔵野市吉祥寺本町 1-13-2 5F</td></tr>
                        <tr><th>事業内容</th><td>コンディション・インサイトの提供<br>身体領域の専門知の構造化・判断支援に関する研究開発</td></tr>
                        <tr><th>お問い合わせ</th><td><a href="<?php echo esc_url( home_url( '/contact/' ) ); ?>">お問い合わせフォーム</a></td></tr>
                    </tbody>
                </table>
            </div>
        </section>
    </main>
    <?php
}

function hachi_v3_render_contact(): void {
    ?>
    <main id="main-content" class="cd-page">
        <section class="cd-page-hero">
            <div class="cd-container">
                <?php hachi_cd_eyebrow( 'Contact' ); ?>
                <h1>お問い合わせ</h1>
            </div>
        </section>

        <section class="cd-section cd-section--flush">
            <div class="cd-container cd-form-wrap">
                <p>コンディション・インサイトのご相談、サービス資料の請求、取材・協業のご連絡は、下記フォームよりお気軽にお問い合わせください。<br>対象：20〜100名規模の中小企業・法人のご担当者様。</p>
                <p class="cd-contact-email cd-contact-email--light">Email: <a href="mailto:info@hachi-wellnesshack.com">info@hachi-wellnesshack.com</a></p>

                <div id="form-success" class="form-success cd-form-success" role="status" aria-live="polite" tabindex="-1">
                    <strong>お問い合わせを受け付けました。</strong><br>
                    <span>担当よりご連絡いたします。</span>
                </div>

                <form id="contact-form" class="cd-form" novalidate aria-label="お問い合わせフォーム">
                    <?php wp_nonce_field( 'hachi_nonce', 'contact_nonce' ); ?>
                    <input type="hidden" name="contact_cat" value="コンディション・インサイトのご相談">
                    <input type="hidden" name="contact_timeline" value="">
                    <div class="cd-honeypot" aria-hidden="true">
                        <label>Website<input type="text" name="website" tabindex="-1" autocomplete="off"></label>
                    </div>

                    <div class="cd-field" id="field-company">
                        <label for="contact-company">会社名 <span>必須</span></label>
                        <input type="text" id="contact-company" name="contact_company" placeholder="株式会社〇〇" maxlength="200" autocomplete="organization" required>
                    </div>
                    <div class="cd-field" id="field-name">
                        <label for="contact-name">お名前 <span>必須</span></label>
                        <input type="text" id="contact-name" name="contact_name" placeholder="山田 太郎" maxlength="100" autocomplete="name" required aria-required="true" aria-describedby="err-name">
                        <span class="form-field__error" id="err-name" role="alert">お名前をご入力ください。</span>
                    </div>
                    <div class="cd-field" id="field-email">
                        <label for="contact-email">メールアドレス <span>必須</span></label>
                        <input type="email" id="contact-email" name="contact_email" placeholder="you@example.com" maxlength="254" autocomplete="email" required aria-required="true" aria-describedby="err-email">
                        <span class="form-field__error" id="err-email" role="alert">正しいメールアドレスを入力してください。</span>
                    </div>
                    <div class="cd-field">
                        <label for="contact-phone">電話番号 <em>任意</em></label>
                        <input type="tel" id="contact-phone" name="contact_phone" placeholder="入力してください" maxlength="20" autocomplete="tel">
                    </div>
                    <div class="cd-field">
                        <label for="contact-size">従業員数</label>
                        <select id="contact-size" name="contact_size">
                            <option value="">選択してください</option>
                            <option value="〜50名">〜20名</option>
                            <option value="〜50名">20〜50名</option>
                            <option value="51〜300名">50〜100名</option>
                            <option value="51〜300名">100名以上</option>
                        </select>
                    </div>
                    <div class="cd-field" id="field-message">
                        <label for="contact-message">お問い合わせ内容</label>
                        <textarea id="contact-message" name="contact_message" rows="6" maxlength="1000" placeholder="ご相談内容をご記入ください" aria-required="true" aria-describedby="err-message"></textarea>
                        <span class="form-field__error" id="err-message" role="alert">お問い合わせ内容をご入力ください。</span>
                    </div>

                    <p class="cd-privacy-purpose">いただいた情報はお問い合わせ対応の目的にのみ利用します。詳しくはプライバシーポリシーをご確認ください。</p>
                    <label class="cd-form-privacy" id="field-privacy">
                        <input type="checkbox" name="privacy" required>
                        <span><a href="<?php echo esc_url( home_url( '/privacy-policy/' ) ); ?>" target="_blank" rel="noopener">プライバシーポリシー</a>をご確認ください</span>
                        <span class="form-field__error" id="err-privacy" role="alert">プライバシーポリシーをご確認ください。</span>
                    </label>
                    <button type="submit" class="cd-btn cd-btn--dark cd-submit" id="form-submit"><span id="submit-text">送信する <?php echo hachi_cd_arrow(); ?></span></button>
                    <p id="form-general-error" class="cd-form-error" role="alert"></p>
                </form>
            </div>
        </section>
    </main>
    <?php
}

function hachi_v3_render_news(): void {
    $items = hachi_cd_news_items( 30 );
    ?>
    <main id="main-content" class="cd-page">
        <section class="cd-page-hero">
            <div class="cd-container">
                <?php hachi_cd_eyebrow( 'News' ); ?>
                <h1>お知らせ</h1>
            </div>
        </section>

        <section class="cd-section cd-section--flush">
            <div class="cd-container cd-news-wrap">
                <?php if ( $items ) : ?>
                    <ol class="cd-news-list">
                        <?php foreach ( $items as $item ) : ?>
                            <?php
                            $is_note = ( $item['source'] ?? '' ) === 'note';
                            $url     = $item['url'] ?? '';
                            ?>
                            <li>
                                <a class="cd-news-item" href="<?php echo esc_url( $url ); ?>"<?php echo $is_note ? ' target="_blank" rel="noopener noreferrer"' : ''; ?>>
                                    <time class="cd-news-date" datetime="<?php echo esc_attr( $item['date_ts'] ? date_i18n( 'Y-m-d', (int) $item['date_ts'] ) : '' ); ?>"><?php echo esc_html( $item['date_str'] ?? '' ); ?></time>
                                    <span class="cd-news-label"><?php echo esc_html( $is_note ? 'note' : strtoupper( $item['category'] ?? 'news' ) ); ?></span>
                                    <span class="cd-news-title"><?php echo esc_html( $item['title'] ?? '' ); ?></span>
                                    <span class="cd-news-arrow" aria-hidden="true"><?php echo $is_note ? '&#8599;' : '&rarr;'; ?></span>
                                </a>
                            </li>
                        <?php endforeach; ?>
                    </ol>
                <?php else : ?>
                    <p class="cd-empty">公開中のお知らせはまだありません。</p>
                <?php endif; ?>
            </div>
        </section>
    </main>
    <?php
}

function hachi_v3_render_privacy(): void {
    ?>
    <main id="main-content" class="cd-page">
        <section class="cd-page-hero">
            <div class="cd-container">
                <?php hachi_cd_eyebrow( 'Privacy' ); ?>
                <h1>プライバシーポリシー</h1>
            </div>
        </section>

        <section class="cd-section cd-section--flush">
            <div class="cd-container cd-policy">
                <p class="cd-policy-intro">株式会社HACHI（以下「当社」）は、お客様の個人情報の重要性を認識し、個人情報の保護に関する法律および関連法令を遵守するとともに、以下の方針に基づき個人情報を適切に取り扱います。</p>

                <article><h2>1. 取得する情報</h2><p>当社は、お問い合わせやサービスのご利用にあたり、以下の情報を取得することがあります。</p><ul><li>会社名・お名前・メールアドレス等、お問い合わせフォームにご入力いただく情報</li><li>サービス提供の過程で取得する、業務上必要な情報</li><li>ウェブサイトのアクセス情報（Cookie・アクセス解析により取得する利用状況）</li></ul></article>
                <article><h2>2. 利用目的</h2><ul><li>お問い合わせへの対応およびご連絡のため</li><li>サービスのご提供・ご案内・品質向上のため</li><li>ウェブサイトの改善および利用状況の把握のため</li></ul></article>
                <article><h2>3. 第三者提供</h2><p>当社は、法令に基づく場合を除き、ご本人の同意なく個人情報を第三者に提供することはありません。サービスの提供にあたり業務委託を行う場合は、適切な管理のもとで必要な範囲に限り情報を取り扱います。</p></article>
                <article><h2>4. 安全管理措置</h2><p>当社は、取得した個人情報の漏えい・滅失・毀損を防止するため、必要かつ適切な安全管理措置を講じ、取り扱う従業者・委託先に対して必要な監督を行います。</p></article>
                <article><h2>5. 開示・訂正・削除等のご請求</h2><p>ご本人からの個人情報の開示・訂正・利用停止・削除等のご請求については、<a href="<?php echo esc_url( home_url( '/contact/' ) ); ?>">お問い合わせフォーム</a>よりご連絡ください。ご本人であることを確認のうえ、法令に従い対応いたします。</p></article>
                <article><h2>6. 本ポリシーの改定</h2><p>当社は、法令の変更やサービス内容の変更に応じて、本ポリシーを予告なく改定することがあります。改定後の内容は本ページに掲載した時点から適用されます。</p></article>
                <article><h2>7. お問い合わせ窓口</h2><p>本ポリシーおよび個人情報の取り扱いに関するお問い合わせは、<a href="<?php echo esc_url( home_url( '/contact/' ) ); ?>">お問い合わせフォーム</a>よりご連絡ください。</p></article>
            </div>
        </section>
    </main>
    <?php
}
