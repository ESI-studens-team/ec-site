# セキュリティ設計書（WordPress対応版）

対象：

* 山口
* 中

更新担当：

* （あなたの名前）

更新理由：

* AWS上のWordPress共通環境利用に伴い、独自認証方式からWordPress認証方式へ変更

---

# フォルダ構成

```text
backend/
├── public/
│   ├── index.php
│   ├── login.php
│   ├── register.php
│   └── .htaccess
│
├── src/
│   ├── auth/
│   │   ├── login_process.php
│   │   └── register_process.php
│   │
│   └── wordpress/
│       └── wp-load.php
│
└── SECURITY_RULE.md
```

## 変更コメント

* SQLite利用前提のため `database/app.sqlite` を削除
* WordPress標準DBを利用するためDB接続処理を独自実装しない
* WordPress機能利用のため `wp-load.php` を読み込む

---

# 1. パスワード管理ルール

## 禁止事項

パスワードを平文で保存することは禁止。

## 禁止事項

独自暗号化ロジックの実装を禁止。

## 変更コメント

旧設計では

```php
password_hash()
```

による独自管理を想定していた。

WordPress導入後はWordPress標準のパスワード管理機能を利用する。

---

# 2. ユーザー登録

## register_process.php

役割

```text
独自ログイン画面から送信された登録情報を受け取り
WordPressユーザーとして登録する
```

入力項目

```text
ユーザー名
メールアドレス
パスワード
```

登録先

```text
wp_users
wp_usermeta
```

## 変更コメント

旧設計

```sql
INSERT INTO users
```

↓

新設計

```text
WordPressユーザー登録機能を利用
```

独自SQLによるユーザー作成は禁止。

---

# 3. ログイン認証

## login_process.php

役割

```text
独自ログイン画面から送信された認証情報を
WordPress認証機能へ渡す
```

認証情報

```text
メールアドレス
または
ユーザー名

パスワード
```

認証先

```text
wp_users
```

## 認証成功時

```text
ログイン状態を保持

マイページへ遷移
```

## 認証失敗時

```text
エラーメッセージ表示
```

## 変更コメント

旧設計

```sql
SELECT * FROM users
```

および

```php
password_verify()
```

による認証

↓

新設計

```text
WordPress標準認証を利用
```

独自認証は禁止。

---

# 4. ログアウト

役割

```text
WordPressログイン状態を破棄する
```

処理内容

```text
セッション終了
認証情報削除
ログイン画面へ遷移
```

## 変更コメント

旧設計には未記載。

WordPressログアウト機能を利用する。

---

# 5. データベース管理

利用DB

```text
WordPress標準データベース
(MySQL)
```

利用テーブル

```text
wp_users
wp_usermeta
```

## 変更コメント

旧設計

```text
SQLite
usersテーブル
```

↓

新設計

```text
WordPress標準DB
```

---

# 6. DB直接操作ルール

禁止事項

```sql
INSERT INTO wp_users
UPDATE wp_users
DELETE FROM wp_users
```

を直接実行すること。

理由

```text
WordPress内部整合性が崩れる可能性があるため
```

ユーザー管理はWordPress機能を経由する。

---

# 7. .env管理

禁止事項

```text
GitHubへのコミット
```

許可

```text
サーバー内のみ保持
```

---

# 8. チーム共通ルール

| 項目            | ルール |
| ------------- | --- |
| パスワード平文保存     | 禁止  |
| 独自暗号化         | 禁止  |
| WordPress認証利用 | 必須  |
| wp_users直接更新  | 禁止  |
| .env公開        | 禁止  |
| 認証処理の独自実装     | 禁止  |

---

# 9. 今回の主な変更点まとめ

| 旧設計               | 新設計             |
| ----------------- | --------------- |
| SQLite            | WordPress MySQL |
| usersテーブル         | wp_users        |
| password_hash()   | WordPress管理     |
| password_verify() | WordPress管理     |
| 独自認証              | WordPress認証     |
| 独自ユーザー管理          | WordPressユーザー管理 |
| SQLで認証            | WordPress認証機能利用 |

---
