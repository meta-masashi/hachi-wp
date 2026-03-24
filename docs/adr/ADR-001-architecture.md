# ADR-001: HACHI-web アーキテクチャ設計

- **ステータス**: 承認済み
- **作成日**: 2026-03-25
- **決定者**: HACHI 開発チーム

---

## コンテキスト

HACHI コーポレートサイトのバックエンド設計において、以下の要件を満たす必要があった。

1. **コンタクトフォーム送信データの永続化** — WordPress の wp_options/メール転送のみでは検索・集計が困難
2. **セキュリティイベントの構造化ログ** — ファイルログのみでは運用中の監視が難しい
3. **REST API の公開エンドポイント提供** — ヘッドレス利用・外部連携を想定
4. **本番デプロイの自動化** — 手作業デプロイによるミスを防ぐ

---

## 決定内容

### スタック構成

```
┌─────────────────────────────────────────────────────┐
│                   フロントエンド                     │
│              WordPress テーマ (PHP)                  │
│          hachi-wp-secure/ (テーマディレクトリ)       │
└──────────────────────┬──────────────────────────────┘
                       │ WordPress HTTP API
                       │ (wp_remote_post/get)
┌──────────────────────▼──────────────────────────────┐
│                  Supabase (HACHI-web)                │
│           PostgREST API (REST v1)                    │
│  ┌─────────────────────┐  ┌───────────────────────┐ │
│  │ contact_submissions │  │   security_events     │ │
│  │ (問い合わせデータ)   │  │ (セキュリティログ)    │ │
│  └─────────────────────┘  └───────────────────────┘ │
│           Row Level Security (service_role のみ)     │
│           リージョン: 東京 (ap-northeast-1)          │
└─────────────────────────────────────────────────────┘
```

### レイヤー設計

| レイヤー | ファイル | 責務 |
|---|---|---|
| セキュリティ | `inc/security.php` | CSP/HSTS/XSS/SQLi/CSRF/ブルートフォース対策 |
| レートリミット | `inc/rate-limiter.php` | IP ベース、Transients 使用 |
| 2FA | `inc/two-factor.php` | TOTP (RFC 6238)、admin/editor 必須 |
| コンタクト | `inc/contact-handler.php` | reCAPTCHA v3 / Slack / GA4 / Supabase 保存 |
| REST API | `inc/rest-api.php` | `GET /hachi/v1/news`、5分キャッシュ |
| Supabase | `inc/supabase.php` | PostgREST HTTP クライアント |

---

## レートリミット設定

| アクション | 上限 | ウィンドウ | ロック時間 |
|---|---|---|---|
| `contact_form` | 5回 | 15分 | 30分 |
| `login` | 5回 | 10分 | 1時間 |
| `search` | 30回 | 1分 | 5分 |
| `rest_api` | 60回 | 1分 | 10分 |

---

## Supabase 設計判断

### service_role キーをサーバーサイドで使用する理由

- `anon` キーは RLS ポリシーによりアクセスを制限できるが、公開鍵のため漏洩リスクがある
- `service_role` キーは RLS をバイパスするフルアクセスキーであり、**サーバーサイドのみ**で使用
- WordPress サーバー (PHP) から Supabase に直接 HTTP リクエストを送るため、クライアントサイドに公開されない

### IP アドレスのハッシュ化

```php
$ip_hash = hash( 'sha256', $ip . NONCE_SALT );
```

- GDPR / 個人情報保護法への対応
- WordPress の `NONCE_SALT` をソルトに使用（サイトごとに異なる）
- 元の IP は Supabase に保存しない

### RLS ポリシー

```sql
CREATE POLICY "service_role_full_access" ON contact_submissions
  USING ( auth.role() = 'service_role' );
```

- `anon` キーからのアクセスを完全ブロック
- ダッシュボードからの閲覧は Supabase UI 経由でのみ可能

---

## CI/CD パイプライン

```
git push → main
    │
    ├── CI (ci.yml)
    │   ├── PHP 構文チェック (php -l)
    │   ├── WordPress Coding Standards (PHPCS)
    │   ├── Semgrep セキュリティスキャン
    │   └── JWT ハードコード検出
    │
    └── Deploy (deploy.yml) ← CI 通過後
        ├── Supabase db push (マイグレーション)
        └── SFTP デプロイ (テーマファイル同期)
            └── Slack 通知 (成功/失敗)
```

---

## 必要な GitHub Secrets

| Secret 名 | 用途 |
|---|---|
| `SFTP_HOST` | デプロイ先サーバーホスト |
| `SFTP_USER` | SSH ユーザー |
| `SFTP_PRIVATE_KEY` | SSH 秘密鍵 |
| `SFTP_REMOTE_PATH` | テーマのデプロイ先パス |
| `SUPABASE_ACCESS_TOKEN` | Supabase CLI 認証 |
| `SUPABASE_DB_PASSWORD` | マイグレーション用 DB パスワード |
| `SLACK_WEBHOOK_URL` | デプロイ通知 |
| `SEMGREP_APP_TOKEN` | セキュリティスキャン (任意) |

---

## 却下した代替案

| 案 | 却下理由 |
|---|---|
| WordPress カスタムテーブル (wpdb) | 管理画面外からの検索・集計が難しい。Supabase ダッシュボードの方が運用しやすい |
| Firebase Firestore | NoSQL は集計クエリが複雑。PostgreSQL の方がレポート用途に適している |
| サードパーティフォームサービス | データの外部依存が増える。自社管理の方がセキュリティ要件を満たしやすい |
