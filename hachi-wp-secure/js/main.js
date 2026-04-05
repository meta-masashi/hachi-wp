/**
 * HACHI Corporate Theme — main.js
 * Handles: Loader, page curtain, scroll progress,
 *          nav scroll-state, mobile drawer, parallax,
 *          IntersectionObserver fade-ins, contact form AJAX
 */

'use strict';

/* ============================================================
   UTILITY
   ============================================================ */
const $ = (sel, ctx = document) => ctx.querySelector(sel);
const $$ = (sel, ctx = document) => [...ctx.querySelectorAll(sel)];

// hachiData が wp_localize_script で未定義の場合のフォールバック
// （キャッシュ・拡張機能・CSP 等で inline script が実行されないケース対策）
if (typeof window.hachiData === 'undefined') {
  window.hachiData = {
    ajaxUrl: '/wp-admin/admin-ajax.php',
    nonce:   '',
    homeUrl: '/',
    recaptchaSiteKey: '',
  };
  // inline script が DOM にあれば再パース
  const extraEl = document.getElementById('hachi-main-js-extra');
  if (extraEl) {
    try {
      const m = extraEl.textContent.match(/var\s+hachiData\s*=\s*(\{[\s\S]*?\})\s*;/);
      if (m) window.hachiData = JSON.parse(m[1]);
    } catch (_) { /* JSON パース失敗時はフォールバック値を使用 */ }
  }
}
const hachiData = window.hachiData;

/* ============================================================
   DOM READY
   ============================================================ */
document.addEventListener('DOMContentLoaded', () => {
  initLoader();
  initScrollProgress();
  initNavScroll();
  initHamburger();
  initPageCurtain();
  initFadeObserver();
  initParallax();
  initContactForm();
});

/* ============================================================
   PAGE LOADER
   ============================================================ */
function initLoader() {
  const loader = $('#hachi-loader');
  if (!loader) return;

  // Hide after animation completes (bar animation = 1.6s)
  const delay = Math.max(1700, performance.now() < 800 ? 1700 : 400);

  window.addEventListener('load', () => {
    setTimeout(() => {
      loader.classList.add('is-hidden');
      loader.addEventListener('transitionend', () => {
        loader.style.display = 'none';
      }, { once: true });
    }, delay);
  });
}

/* ============================================================
   SCROLL PROGRESS BAR
   ============================================================ */
function initScrollProgress() {
  const bar = $('#scroll-progress');
  if (!bar) return;

  let ticking = false;

  window.addEventListener('scroll', () => {
    if (!ticking) {
      requestAnimationFrame(() => {
        const scrolled = window.scrollY;
        const total = document.documentElement.scrollHeight - window.innerHeight;
        const pct = total > 0 ? (scrolled / total) * 100 : 0;
        bar.style.width = `${Math.min(100, pct)}%`;
        ticking = false;
      });
      ticking = true;
    }
  }, { passive: true });
}

/* ============================================================
   NAV SCROLL STATE
   ============================================================ */
function initNavScroll() {
  const header = $('#site-header');
  if (!header) return;

  let ticking = false;

  const update = () => {
    header.classList.toggle('is-scrolled', window.scrollY > 60);
    ticking = false;
  };

  window.addEventListener('scroll', () => {
    if (!ticking) {
      requestAnimationFrame(update);
      ticking = true;
    }
  }, { passive: true });
}

/* ============================================================
   MOBILE HAMBURGER / DRAWER
   ============================================================ */
function initHamburger() {
  const btn    = $('#hamburger-btn');
  const drawer = $('#nav-drawer');
  if (!btn || !drawer) return;

  let isOpen = false;

  function open() {
    isOpen = true;
    btn.setAttribute('aria-expanded', 'true');
    btn.setAttribute('aria-label', 'メニューを閉じる');
    btn.classList.add('is-active');
    drawer.classList.add('is-open');
    document.body.style.overflow = 'hidden';
  }

  function close() {
    isOpen = false;
    btn.setAttribute('aria-expanded', 'false');
    btn.setAttribute('aria-label', 'メニューを開く');
    btn.classList.remove('is-active');
    drawer.classList.remove('is-open');
    document.body.style.overflow = '';
  }

  btn.addEventListener('click', () => (isOpen ? close() : open()));

  // Close drawer on nav link click
  $$('a', drawer).forEach(a => {
    a.addEventListener('click', () => {
      if (isOpen) {
        close();
      }
    });
  });

  // Close on Escape key
  document.addEventListener('keydown', e => {
    if (e.key === 'Escape' && isOpen) close();
  });

  // Resize: close drawer above 900px
  window.addEventListener('resize', () => {
    if (window.innerWidth > 900 && isOpen) close();
  });
}

/* ============================================================
   PAGE TRANSITION CURTAIN
   ============================================================ */
function initPageCurtain() {
  const curtain = $('#page-curtain');
  if (!curtain) return;

  // Handle internal links with curtain transition
  document.addEventListener('click', e => {
    const anchor = e.target.closest('a[href]');
    if (!anchor) return;

    const href = anchor.getAttribute('href');
    if (!href) return;

    // Skip: external, anchor-only, javascript:, mailto:, tel:, target=_blank
    if (
      href.startsWith('#') ||
      href.startsWith('javascript:') ||
      href.startsWith('mailto:') ||
      href.startsWith('tel:') ||
      anchor.target === '_blank' ||
      anchor.hostname !== location.hostname
    ) return;

    // Skip admin links
    if (href.includes('/wp-admin/') || href.includes('/wp-login.php')) return;

    e.preventDefault();

    curtain.className = 'curtain--in';

    setTimeout(() => {
      window.location.href = href;
    }, 480);
  });

  // On page show (including back/forward cache)
  window.addEventListener('pageshow', e => {
    curtain.className = 'curtain--out';
    setTimeout(() => {
      curtain.className = '';
    }, 520);
  });
}

/* ============================================================
   INTERSECTION OBSERVER — FADE IN
   ============================================================ */
function initFadeObserver() {
  const elements = $$('.js-fade');
  if (!elements.length) return;

  // Respect prefers-reduced-motion
  const prefersReduced = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
  if (prefersReduced) {
    elements.forEach(el => el.classList.add('is-visible'));
    return;
  }

  const observer = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
      if (entry.isIntersecting) {
        entry.target.classList.add('is-visible');
        observer.unobserve(entry.target);
      }
    });
  }, {
    threshold: 0.08,
    rootMargin: '0px 0px -32px 0px',
  });

  elements.forEach(el => observer.observe(el));
}

/* ============================================================
   PARALLAX — CULTURE BAND
   ============================================================ */
function initParallax() {
  const elements = $$('[data-parallax]');
  if (!elements.length) return;

  const prefersReduced = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
  if (prefersReduced) return;

  let ticking = false;

  const update = () => {
    const scrollY = window.scrollY;
    elements.forEach(el => {
      const speed  = parseFloat(el.dataset.parallax) || 0.2;
      const rect   = el.closest('.culture-band')?.getBoundingClientRect();
      if (!rect) return;
      // Only animate when in viewport
      if (rect.bottom < 0 || rect.top > window.innerHeight) return;
      const offset = scrollY * speed;
      el.style.transform = `translateY(${offset}px)`;
    });
    ticking = false;
  };

  window.addEventListener('scroll', () => {
    if (!ticking) {
      requestAnimationFrame(update);
      ticking = true;
    }
  }, { passive: true });
}

/* ============================================================
   CONTACT FORM — AJAX SUBMISSION
   ============================================================ */
function initContactForm() {
  const form    = $('#contact-form');
  const success = $('#form-success');
  if (!form || !success) return;

  const submitBtn  = $('#form-submit');
  const submitText = $('#submit-text');
  const genError   = $('#form-general-error');

  // Field references
  const fields = {
    name:    { input: form.querySelector('[name="contact_name"]'),    wrapper: $('#field-name'),    errEl: $('#err-name') },
    email:   { input: form.querySelector('[name="contact_email"]'),   wrapper: $('#field-email'),   errEl: $('#err-email') },
    message: { input: form.querySelector('[name="contact_message"]'), wrapper: $('#field-message'), errEl: $('#err-message') },
  };

  // Sanitize display value
  function sanitize(str) {
    const div = document.createElement('div');
    div.textContent = str;
    return div.innerHTML;
  }

  // Validate email
  function isValidEmail(email) {
    return /^[^\s@]+@[^\s@]+\.[^\s@]{2,}$/.test(email);
  }

  // Clear all errors
  function clearErrors() {
    Object.values(fields).forEach(({ wrapper }) => {
      wrapper.classList.remove('has-error');
    });
    if (genError) {
      genError.style.display = 'none';
      genError.textContent = '';
    }
  }

  // Validate and return boolean
  function validate() {
    let valid = true;
    const { name, email, message } = fields;

    if (!name.input.value.trim()) {
      name.wrapper.classList.add('has-error');
      valid = false;
    }

    if (!email.input.value.trim() || !isValidEmail(email.input.value.trim())) {
      email.wrapper.classList.add('has-error');
      valid = false;
    }

    const msgVal = message.input.value.trim();
    if (!msgVal) {
      message.wrapper.classList.add('has-error');
      message.errEl.textContent = 'お問い合わせ内容をご入力ください。';
      valid = false;
    } else if (msgVal.length > 2000) {
      message.wrapper.classList.add('has-error');
      message.errEl.textContent = '2000文字以内でご入力ください。';
      valid = false;
    }

    return valid;
  }

  // Clear error on input
  Object.values(fields).forEach(({ input, wrapper }) => {
    if (!input) return;
    input.addEventListener('input', () => {
      wrapper.classList.remove('has-error');
    });
  });

  form.addEventListener('submit', async (e) => {
    e.preventDefault();
    clearErrors();

    if (!validate()) return;

    // Disable submit
    submitBtn.disabled = true;
    if (submitText) submitText.textContent = '送信中...';

    // Build FormData (honeypot フィールド 'website' も自動含まれる)
    const data = new FormData(form);
    // パスワードフィールドが含まれないことを確認（セキュリティ）
    data.delete('password'); // autocomplete="new-password" フィールドの保護
    data.append('action', 'hachi_contact');
    // nonce: hachiData → フォーム内 contact_nonce hidden field のフォールバック
    const nonce = hachiData?.nonce
      || form.querySelector('[name="contact_nonce"]')?.value
      || '';
    data.append('nonce', nonce);

    // 20 秒で自動キャンセル（wp_mail 遅延時の無限待機防止）
    const controller = new AbortController();
    const timeoutId  = setTimeout(() => controller.abort(), 20000);

    try {
      const res  = await fetch(hachiData?.ajaxUrl || '/wp-admin/admin-ajax.php', {
        method:      'POST',
        credentials: 'same-origin',
        body:        data,
        signal:      controller.signal,
      });
      clearTimeout(timeoutId);

      const json = await res.json();

      if (json.success) {
        // Show success
        form.style.display   = 'none';
        success.classList.add('is-visible');
        success.focus();
      } else {
        // Server-side field errors
        if (json.data?.errors) {
          Object.entries(json.data.errors).forEach(([key, msg]) => {
            if (fields[key]) {
              fields[key].wrapper.classList.add('has-error');
              fields[key].errEl.textContent = msg;
            } else if (key === 'cat') {
              // カテゴリーカードエラーは専用表示
              const errCat = document.getElementById('err-cat');
              if (errCat) { errCat.textContent = msg; errCat.style.display = 'block'; }
              const cards = document.querySelector('.contact-cards');
              if (cards) cards.scrollIntoView({ behavior: 'smooth', block: 'center' });
            }
          });
        } else if (json.data?.message) {
          genError.textContent = json.data.message;
          genError.style.display = 'block';
        }

        // Re-enable submit
        submitBtn.disabled = false;
        if (submitText) submitText.textContent = '送信する';
      }
    } catch (err) {
      clearTimeout(timeoutId);
      console.error('[HACHI] Contact form error:', err);
      if (err.name === 'AbortError') {
        genError.textContent = '送信がタイムアウトしました。しばらく経ってから再試行してください。';
      } else {
        genError.textContent = 'ネットワークエラーが発生しました。しばらく経ってから再試行してください。';
      }
      genError.style.display = 'block';
      submitBtn.disabled = false;
      if (submitText) submitText.textContent = '送信する';
    }
  });
}
