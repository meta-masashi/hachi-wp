-- ============================================================
-- HACHI — Supabase Migration 001
-- コンタクトフォーム送信データテーブル
--
-- 適用方法:
--   Supabase ダッシュボード > SQL Editor でこのファイルを実行
--   または: supabase db push (Supabase CLI 使用時)
-- ============================================================

-- 拡張機能の有効化 (uuid_generate_v4 を使用)
create extension if not exists "pgcrypto";

-- ============================================================
-- テーブル作成
-- ============================================================

create table if not exists public.contact_submissions (
  id               uuid        primary key default gen_random_uuid(),
  created_at       timestamptz not null    default now(),

  -- 送信者情報
  name             text        not null,
  company          text,
  email            text        not null,
  category         text        not null    default 'general',
  message          text        not null,

  -- セキュリティ / トラッキング
  ip_hash          text        not null,           -- sha256(IP + NONCE_SALT): 個人情報保護
  recaptcha_score  numeric(3,2),                   -- reCAPTCHA v3 スコア (0.0〜1.0)
  ga4_event        text,                           -- GA4 コンバージョンイベント名

  -- ワークフロー
  status           text        not null    default 'new'
    check (status in ('new', 'replied', 'archived', 'spam')),

  -- 返信メモ (管理者用)
  admin_note       text,
  replied_at       timestamptz
);

-- ============================================================
-- インデックス
-- ============================================================

create index if not exists idx_contact_submissions_created_at
  on public.contact_submissions (created_at desc);

create index if not exists idx_contact_submissions_status
  on public.contact_submissions (status);

create index if not exists idx_contact_submissions_category
  on public.contact_submissions (category);

-- ============================================================
-- Row Level Security (RLS)
-- ============================================================

alter table public.contact_submissions enable row level security;

-- サービスロールのみフルアクセス (anon / authenticated は読み書き不可)
create policy "service_role_full_access" on public.contact_submissions
  using     ( (select auth.role()) = 'service_role' )
  with check ( (select auth.role()) = 'service_role' );

-- ============================================================
-- 更新日時の自動更新 (オプション)
-- ============================================================

-- updated_at カラムを追加する場合:
-- alter table public.contact_submissions
--   add column if not exists updated_at timestamptz;
--
-- create or replace function public.set_updated_at()
-- returns trigger language plpgsql as $$
-- begin
--   new.updated_at = now();
--   return new;
-- end;
-- $$;
--
-- create trigger contact_submissions_updated_at
--   before update on public.contact_submissions
--   for each row execute function public.set_updated_at();

-- ============================================================
-- コメント
-- ============================================================

comment on table  public.contact_submissions               is 'HACHI コンタクトフォーム送信データ';
comment on column public.contact_submissions.ip_hash       is 'SHA-256(IP + NONCE_SALT) — 元の IP は保存しない';
comment on column public.contact_submissions.recaptcha_score is 'Google reCAPTCHA v3 スコア。0.5未満はボット判定済み';
comment on column public.contact_submissions.status        is 'new=未対応 / replied=返信済み / archived=アーカイブ / spam=スパム';
