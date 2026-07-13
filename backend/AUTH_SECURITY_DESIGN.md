# 認証・暗号化バックエンド設計書

対象チケット

- #37 ログイン（汎用）
- #38 ログイン入力
- #39 ログイン認証

関連チケット

- #40 ログアウト（汎用）
- #41 ログアウト
- #117 パスワード暗号化手順ドキュメント化＆DB隠蔽リサーチ

---

# 1. 目的

AWS上のWordPress共通環境に合わせて、ECサイト独自のログイン・ユーザー登録・認証処理を安全に設計する。

本プロジェクトでは、データベースはプロジェクト方針に従いSQLiteを継続使用する。

また、本設計書は、ログイン認証機能を他メンバーが接続・実装できる状態にすることを目的とする。

---

# 2. 前提

WordPressは共通インフラとして利用する。

ECサイトの認証処理はPHPで実装し、SQLiteへ接続する。

認証処理全体の流れ

```text
ログイン画面
    ↓
login_process.php
    ↓
入力値チェック
    ↓
SQLite(usersテーブル)
    ↓
password_verify()
    ↓
認証成功 / 認証失敗
```

---

# 3. フォルダ構成

```text
backend/
├── public/
│   ├── index.php
│   ├── login.php
│   ├── register.php
│   ├── logout.php
│   └── .htaccess
│
├── src/
│   ├── auth/
│   │   ├── login_process.php
│   │   ├── register_process.php
│   │   └── logout_process.php
│   │
│   ├── db/
│   │   └── connect.php
│   │
│   └── security/
│       ├── password_security.php
│       ├── input_validation.php
│       └── session_security.php
│
├── database/
│   └── app.sqlite
│
└── SECURITY_RULE.md
```

## 設計方針

- SQLiteを継続使用する
- database/app.sqlite は public ディレクトリの外へ配置する
- 認証処理は auth フォルダへまとめる
- セキュリティ関連処理は security フォルダへまとめる
- データベース接続は connect.php に集約する

---

# 4. 使用テーブル

## users テーブル

| カラム名 | 内容 |
|----------|------|
| id | ユーザーID |
| username | ユーザー名 |
| email | メールアドレス |
| password_hash | ハッシュ化済みパスワード |
| created_at | 登録日時 |

## テーブル利用方針

- パスワードは必ずハッシュ化して保存する
- 平文のパスワードは保存しない
- メールアドレスの重複登録を禁止する

---

# 5. ユーザー登録処理

対象ファイル

```text
src/auth/register_process.php
```

## 処理フロー

```text
register.php

↓

ユーザー名
メールアドレス
パスワード入力

↓

register_process.php

↓

入力値チェック

↓

メールアドレス形式チェック

↓

メールアドレス重複チェック

↓

password_hash()

↓

users.password_hashへ保存

↓

登録完了
```

## register_process.php の役割

- POSTされたデータを取得する
- 未入力チェックを行う
- メールアドレス形式を確認する
- メールアドレスの重複を確認する
- password_hash()でハッシュ化したパスワードをusers.password_hashへ保存する
- 登録結果を画面へ返す

---

# 6. ログイン認証処理

対象ファイル

```text
src/auth/login_process.php
```

## 処理フロー

```text
login.php

↓

メールアドレス
または
ユーザー名入力

↓

パスワード入力

↓

login_process.php

↓

入力値チェック

↓

usersテーブル検索

↓

password_verify()

↓

認証成功

↓

セッション開始

↓

ログインユーザー情報保持

↓

マイページ
または
トップページへリダイレクト
```## login_process.php の役割

- POSTされたログイン情報を取得する
- 未入力チェックを行う
- メールアドレスまたはユーザー名から users テーブルを検索する
- 入力されたパスワードと users.password_hash に保存されているハッシュ化済みパスワードを password_verify() で照合する
- 認証成功時はログイン状態を保持する
- 認証失敗時はエラーメッセージを表示する

### 認証成功時

```text
ログイン状態を保持する

↓

セッションを開始する

↓

ログインユーザー情報を保存する

↓

マイページ
または
トップページへリダイレクトする
```

### 認証失敗時

表示メッセージ

```text
メールアドレスまたはパスワードが正しくありません
```

### 理由

以下のような表示はしない。

```text
メールアドレスが存在しません
```

```text
パスワードが違います
```

攻撃者へ情報を与えないため、認証失敗時は共通メッセージのみ表示する。

---

# 7. ログアウト処理

対象ファイル

```text
src/auth/logout_process.php
```

## 処理フロー

```text
logout.php

↓

logout_process.php

↓

セッション情報を削除

↓

ログイン状態を解除

↓

ログイン画面へリダイレクト
```

## logout_process.php の役割

- セッション情報を削除する
- ログイン状態を解除する
- セッションIDを破棄する
- ログイン画面へ遷移させる

---

# 8. パスワード管理

## 登録時

```text
入力パスワード

↓

password_hash()

↓

ハッシュ化されたパスワード

↓

users.password_hashへ保存
```

## ログイン時

```text
入力パスワード

↓

password_verify()

↓

users.password_hashと照合

↓

認証成功
または
認証失敗
```

## パスワード管理ルール

- パスワードを平文で保存しない
- password_hash() を利用する
- password_verify() を利用する
- 独自の暗号化処理は行わない
- ハッシュ化された値のみをデータベースへ保存する

---

# 9. DB接続

対象ファイル

```text
src/db/connect.php
```

## 役割

- SQLiteへ接続する
- PDOを利用する
- SQL実行時の例外を取得する

処理イメージ

```text
PHP

↓

PDO

↓

database/app.sqlite
```

---

# 10. SQLiteファイル管理

## 禁止

```text
public/app.sqlite
```

理由

ブラウザから直接アクセスされる危険があるため。

## 推奨

```text
database/app.sqlite
```

理由

publicディレクトリの外へ配置することで、直接ダウンロードされる危険を防ぐ。

---

# 11. .htaccess

対象ファイル

```text
public/.htaccess
```

設定

```apache
<Files "*.sqlite">
    Require all denied
</Files>
```

## 目的

万が一SQLiteファイルが公開ディレクトリ内へ配置された場合でも、ブラウザから直接アクセスされることを防ぐ。
# 12. エラー表示ルール

ログイン認証では、攻撃者へ余計な情報を与えないため、エラーメッセージを統一する。

## NG例

```text
メールアドレスが存在しません
```

```text
パスワードが間違っています
```

```text
ユーザーが登録されていません
```

これらは、攻撃者が登録済みユーザーを推測できる可能性があるため表示しない。

## OK例

```text
メールアドレスまたはパスワードが正しくありません
```

## 理由

認証に失敗した原因を利用者へ必要以上に公開しないことで、不正アクセスやアカウント探索のリスクを軽減する。

---

# 13. チケット対応表

| チケット | 内容 | 対応ファイル |
|----------|------|-------------|
| #37 | ログイン画面（汎用） | login.php |
| #38 | ログイン入力 | login_process.php |
| #39 | ログイン認証 | login_process.php |
| #40 | ログアウト（汎用） | logout.php |
| #41 | ログアウト処理 | logout_process.php |
| #117 | パスワード暗号化・SQLite隠蔽 | password_security.php・connect.php・.htaccess |

---

# 14. 今週のDone条件への対応

## Done条件

- ひろとさんからAWS・WordPress共通環境の引き継ぎを受ける
- 作成済みの暗号化ロジックをログイン認証機能へ組み込むためのバックエンド設計を開始する
- 他メンバーがログイン処理を接続できる状態にする
- ログイン成功後の遷移先・認証失敗時の表示・パスワード確認方法を整理し共有する

---

## 本設計で対応している内容

### AWS・WordPress共通環境

- AWS・WordPress共通環境上で動作することを前提としたフォルダ構成を整理
- 接続先や環境設定は、インフラ担当から引き継ぐ内容に合わせて設定する

### 認証処理

- #37〜#39 を対象としたログイン認証フローを整理
- POSTデータ取得から認証完了までの処理を明確化

### 暗号化

- password_hash() により users.password_hash へ保存
- password_verify() により users.password_hash と照合

### ログイン成功後

- セッション開始
- ログイン状態保持
- マイページ（またはトップページ）へリダイレクト

### ログイン失敗時

表示メッセージ

```text
メールアドレスまたはパスワードが正しくありません
```

に統一する。

### セキュリティ

- SQLiteを public 外へ配置
- .htaccess によるアクセス制御
- パスワード平文保存禁止

---

# 15. 今回の変更点

| 変更前 | 変更後 |
|---------|--------|
| 認証処理のみ | 認証フロー全体を整理 |
| ログアウト未整理 | ログアウト設計を追加 |
| セキュリティ処理が各ファイルに分散 | securityフォルダへ集約 |
| パスワードカラム名が曖昧 | users.password_hash に統一 |
| ログイン成功後の遷移未記載 | セッション開始・リダイレクトを追加 |
| 認証失敗時の表示未整理 | 共通メッセージへ統一 |
| AWS・WordPress環境との関係未記載 | 共通環境前提の設計を追加 |
| Done条件との対応が不明確 | Done条件との対応項目を追加 |

---

# 16. 今後の実装予定

本設計をもとに、以下のバックエンド実装を進める。

1. connect.php
    - SQLite接続
    - PDO設定

2. input_validation.php
    - 未入力チェック
    - メールアドレス形式チェック
    - パスワード文字数チェック

3. password_security.php
    - password_hash()
    - password_verify()

4. session_security.php
    - セッション開始
    - セッションID再生成
    - ログイン状態確認

5. register_process.php
    - ユーザー登録処理

6. login_process.php
    - ログイン認証処理

7. logout_process.php
    - ログアウト処理

---

# 17. 共有事項

本設計は、#37〜#39 のログイン認証機能を中心として作成した。

#40〜#41 のログアウト機能は認証機能と密接に関係するため、関連機能として設計へ含めている。

データベースはプロジェクト方針に従いSQLiteを継続利用する。

パスワードは users.password_hash カラムへ保存し、password_hash() と password_verify() を利用することで、安全な認証を実現する。

また、本設計はAWS・WordPress共通環境で動作することを前提としており、インフラ担当から引き継ぐ環境設定に合わせて接続情報などを調整する。

本資料を認証・暗号化機能の共通設計資料として、山口・中をはじめとするバックエンド担当者へ共有する。