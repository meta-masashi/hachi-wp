-- ============================================================
-- HACHI — Supabase Seed Data
-- 開発・テスト用サンプルデータ
-- ⚠ 本番環境では実行しないこと
-- ============================================================

-- サンプルコンタクト送信データ (開発環境テスト用)
insert into public.contact_submissions
  (name, company, email, category, message, ip_hash, recaptcha_score, ga4_event, status)
values
  (
    'テスト 太郎',
    '株式会社テスト',
    'test@example.com',
    'PACE v3.0 デモ申込み',
    'PACE v3.0 のデモを見学させてください。',
    'dev_ip_hash_001',
    0.9,
    'demo_request',
    'new'
  ),
  (
    '開発 花子',
    null,
    'hanako@example.com',
    '一般お問い合わせ',
    'サービスについてお聞きしたいことがあります。',
    'dev_ip_hash_002',
    0.8,
    'contact_form_submit',
    'replied'
  );
