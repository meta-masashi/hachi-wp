# HACHI WordPress セキュリティ実装ガイド

## 実装済みセキュリティ対策一覧

### 🔴 Layer 1: サーバー設定（.htaccess）

| 対策 | 詳細 |
|------|------|
| HTTPS強制 | HTTP→HTTPS 301リダイレクト |
| HSTS | `max-age=31536000; includeSubDomains; preload` |
| セキュリティヘッダー | X-Frame-Options, X-Content-Type-Options, XSS-Protection |
| 危険ファイルブロック | `wp-config.php`, `xmlrpc.php`, `.env` への直接アクセス拒否 |
| アップロードPHP実行禁止 | `wp-content/uploads/*.php` を403 |
| IP制限 | `wp-login.php`, `wp-admin/` をオフィスIPのみ許可 |
| SQL/XSS WAF | クエリストリングの危険パターンを403 |
| 悪意あるBotブロック | `sqlmap`, `nikto`, `havij` 等のUser-Agentを拒否 |
| ディレクトリリスティング無効 | `Options -Indexes` |

---

### 🟠 Layer 2: PHPコード（inc/security.php）

| 対策 | 詳細 |
|------|------|
| CSPヘッダー | `nonce`ベースのContent-Security-Policy |
| バージョン隠蔽 | WPバージョン・PHPバージョンをすべて除去 |
| ユーザー名列挙防止 | `?author=1` クエリを301リダイレクト |
| REST API制限 | 未ログインユーザーへのREST API完全非公開 |
| ログイン保護 | 5回失敗→10分 / 10回→1時間 / 20回→24時間ロック |
| ログインエラー汎用化 | 「ユーザー名が違います」等の情報漏洩を防止 |
| ファイルアップロード制限 | MIMEホワイトリスト + SVGのXSSスキャン |
| XML-RPC完全無効化 | コード＋.htaccessのダブルブロック |
| SQL/パストラバーサル検知 | GETパラメータのパターンマッチング |
| セキュリティログ | `/wp-content/hachi-logs/` へ記録（.htaccessで保護） |
| Cookieセキュリティ | `Secure + HttpOnly + SameSite=Strict` |
| コメント完全無効化 | スパム・トラックバックの排除 |

---

### 🟡 Layer 3: レートリミット（inc/rate-limiter.php）

| エンドポイント | 制限 | ロック時間 |
|----------------|------|-----------|
| コンタクトフォーム | 15分間に5回 | 30分 |
| ログイン | 10分間に5回 | 1時間 |
| 検索 | 1分間に30回 | 5分 |

---

### 🟢 Layer 4: 2要素認証（inc/two-factor.php）

- **RFC 6238 TOTP** 実装（Google Authenticator / Authy 互換）
- **管理者・編集者ロール**は2FA必須
- バックアップコード生成（10個）
- ログイン後に2FAコード入力ページへリダイレクト

---

### 🔵 Layer 5: wp-config.php 設定

```php
define('FORCE_SSL_ADMIN', true);       // HTTPS強制
define('DISALLOW_FILE_EDIT', true);    // テーマエディタ無効
define('DISALLOW_FILE_MODS', true);    // プラグイン更新無効（本番）
define('WP_DEBUG', false);             // デバッグ情報非表示
define('DISABLE_WP_CRON', true);       // WP-Cron無効（サーバーcronを使用）
define('WP_AUTO_UPDATE_CORE', 'minor');// コアの自動更新（マイナーのみ）
$table_prefix = 'hachi8_';             // テーブルプレフィックス変更
```

---

## ⚙️ インストール手順

### 1. テーマをインストール

```bash
# FTP/SSHでアップロード
/wp-content/themes/hachi-wp-secure/

# WordPress管理画面でテーマを有効化
```

### 2. wp-config.php を更新

`wp-config-security.php` の内容を参考に `wp-config.php` に追記

```php
// 必須: テーブルプレフィックス変更（インストール時のみ）
$table_prefix = 'hachi8_';

// 必須: セキュリティキーを公式ジェネレーターで更新
// https://api.wordpress.org/secret-key/1.1/salt/
```

### 3. .htaccess を設置

```bash
# ルートディレクトリに設置（既存の WordPress .htaccess に統合）
```

> ⚠️ `wp-login.php` と `wp-admin/` の IP 制限を**実際のオフィスIP**に変更してください

### 4. wp-cron をサーバーcronに切り替え

```bash
# crontab -e
*/5 * * * * curl -s https://hachi.co.jp/wp-cron.php > /dev/null 2>&1
```

### 5. 管理者の2FAを有効化

1. WordPress管理画面 → ユーザー → プロフィール
2. 「二要素認証 (2FA)」セクションでQRコードをスキャン
3. 6桁コードを入力して有効化

---

## 📋 追加推奨プラグイン

| プラグイン | 用途 |
|-----------|------|
| **Wordfence Security** | WAF・マルウェアスキャン・IPブロック |
| **WPS Hide Login** | ログインURLを `/wp-login.php` から変更 |
| **UpdraftPlus** | 定期バックアップ（S3等の外部ストレージへ） |
| **iThemes Security** | 追加セキュリティ強化（DBバックアップ等） |

---

## 🗓️ 定期セキュリティメンテナンス

| 頻度 | タスク |
|------|--------|
| 毎日 | セキュリティログ確認（`/wp-content/hachi-logs/`） |
| 週次 | WordPress・プラグイン・テーマの更新確認 |
| 月次 | ユーザーアカウントとパーミッションの監査 |
| 四半期 | セキュリティキーのローテーション |
| 年次 | ペネトレーションテスト（外部委託推奨） |

---

## 🚨 インシデント対応チェックリスト

```
[ ] セキュリティログで異常を確認
[ ] 対象IPをサーバーレベルでブロック
[ ] パスワード・APIキーをすべてリセット
[ ] WordPressユーザーのセッションを全削除（wp_sessions テーブルクリア）
[ ] wp-config.php のセキュリティキーを再生成
[ ] バックアップから健全な状態を確認
[ ] ホスティング会社に報告
[ ] 必要に応じて個人情報保護委員会に報告（漏洩時）
```
