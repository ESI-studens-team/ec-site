# セキュリティ設計書

対象：
- 山口
- 中

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
│   └── db/
│       └── connect.php
│
├── database/
│   └── app.sqlite
│
└── SECURITY_RULE.md
```

---

# 各フォルダの役割

| フォルダ | 役割 |
|---|---|
| public | ブラウザ公開領域 |
| src | PHP内部処理 |
| auth | 認証関連 |
| db | DB接続 |
| database | SQLite保存場所 |
| SECURITY_RULE.md | セキュリティ共有資料 |

---

# 1. パスワード保存ルール

## 禁止事項

パスワードをそのままDB保存することは禁止。

## NG例

```php
$password = $_POST['password'];
```

---

# 2. password_hash() を使用する

## register_process.php

ファイル：

```text
src/auth/register_process.php
```

## 実装例

```php
<?php

require_once '../db/connect.php';

$email = $_POST['email'];
$password = $_POST['password'];

$hashedPassword = password_hash($password, PASSWORD_DEFAULT);

$sql = "INSERT INTO users (email, password)
VALUES (:email, :password)";

$stmt = $pdo->prepare($sql);

$stmt->bindValue(':email', $email);
$stmt->bindValue(':password', $hashedPassword);

$stmt->execute();

echo "登録成功";
```

---

# 3. ログイン認証

## login_process.php

ファイル：

```text
src/auth/login_process.php
```

## 実装例

```php
<?php

require_once '../db/connect.php';

$email = $_POST['email'];
$password = $_POST['password'];

$sql = "SELECT * FROM users WHERE email = :email";

$stmt = $pdo->prepare($sql);

$stmt->bindValue(':email', $email);

$stmt->execute();

$user = $stmt->fetch(PDO::FETCH_ASSOC);

if ($user && password_verify($password, $user['password'])) {

    echo "ログイン成功";

} else {

    echo "ログイン失敗";

}
```

---

# 4. DB接続

## connect.php

ファイル：

```text
src/db/connect.php
```

## 実装例

```php
<?php

$pdo = new PDO(
    'sqlite:../../database/app.sqlite'
);

$pdo->setAttribute(
    PDO::ATTR_ERRMODE,
    PDO::ERRMODE_EXCEPTION
);
```

---

# 5. SQLiteファイルの隠蔽

## 危険な配置

```text
public/app.sqlite
```

これはブラウザからDLされる危険がある。

---

# 6. SQLiteの安全な配置

## 推奨

```text
database/app.sqlite
```

public外へ配置する。

---

# 7. .htaccess の設定

## public/.htaccess

ファイル：

```text
public/.htaccess
```

## 内容

```apache
<Files "*.sqlite">
    Require all denied
</Files>
```

---

# 8. Apache旧バージョン対応

```apache
<Files "*.sqlite">
    Order allow,deny
    Deny from all
</Files>
```

---

# 9. チーム共通ルール

| 項目 | ルール |
|---|---|
| パスワード平文保存 | 禁止 |
| password_hash | 必須 |
| password_verify | 必須 |
| SQLite公開配置 | 禁止 |
| .sqlite直アクセス | 禁止 |
| .env公開 | 禁止 |

---

# 10. 備考

password_hash() はPHP公式推奨。

独自暗号化は禁止。