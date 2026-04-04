-- ============================================================
-- HACHI — Supabase Migration 002
-- セキュリティイベントログテーブル
--
-- ファイルベースの hachi-logs/security-YYYY-MM.log の
-- 補完として、Supabase にもセキュリティイベントを記録する。
-- ファイルログと異なり、ダッシュボードから検索・集計が可能。
-- ============================================================

-- ============================================================
-- テーブル作成
-- ============================================================

create table if not exists public.security_events (
  id         uuid        primary key default gen_random_uuid(),
  created_at timestamptz not null    default now(),

  -- イベント識別子 (例: contact_csrf_fail, rate_limit_exceeded)
  event      text        not null,

  -- IP は sha256 ハッシュのみ保存 (個人情報保護)
  ip_hash    text,

  -- 追加データ (jsonb で柔軟に格納)
  data       jsonb       default '{}'::jsonb
);

-- ============================================================
-- インデックス
-- ============================================================

create index if not exists idx_security_events_created_at
  on public.security_events (created_at desc);

create index if not exists idx_security_events_event
  on public.security_events (event);

-- jsonb の GIN インデックス (data フィールド内の検索を高速化)
create index if not exists idx_security_events_data_gin
  on public.security_events using gin (data);

-- ============================================================
-- Row Level Security (RLS)
-- ============================================================

alter table public.security_events enable row level security;

-- サービスロールのみフルアクセス
create policy "service_role_full_access" on public.security_events
  using     ( (select auth.role()) = 'service_role' )
  with check ( (select auth.role()) = 'service_role' );

-- ============================================================
-- 自動パージ: 90日以上経過したログを削除する関数
-- (Supabase Scheduled Functions または pg_cron で定期実行)
-- ============================================================

create or replace function public.purge_old_security_events()
returns void language plpgsql security definer as $$
begin
  delete from public.security_events
  where created_at < now() - interval '90 days';
end;
$$;

comment on function public.purge_old_security_events() is
  '90日以上経過したセキュリティイベントを削除する。pg_cron で定期実行推奨。';

-- ============================================================
-- コメント
-- ============================================================

comment on table  public.security_events          is 'HACHI WordPress テーマのセキュリティイベントログ';
comment on column public.security_events.event    is 'イベント種別 (contact_csrf_fail, rate_limit_exceeded 等)';
comment on column public.security_events.ip_hash  is 'SHA-256(IP + NONCE_SALT) — 元の IP は保存しない';
comment on column public.security_events.data     is 'イベント固有の追加データ (jsonb)';
