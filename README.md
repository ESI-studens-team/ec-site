# GitHub 入門ガイド（ESI用）

このREADMEでは、GitHubの基本的な仕組みと、
ESIのグループ開発で使うGit操作を解説します。

---

# 📌 GitHubとは？

GitHubは、プログラムのコードを保存・共有できるサービスです。

できること：

* コード保存
* バックアップ
* チーム開発
* 変更履歴の管理

---

# 🔧 Gitとは？

Gitは「変更履歴を管理するシステム」です。

例えば：

* 誰が変更したか
* いつ変更したか
* 前の状態へ戻す

などを管理できます。

---

# 📂 リポジトリ(repository)

リポジトリは、
「プロジェクトを保存する場所」です。

例：

```text
MyProject/
├── frontend/
├── backend/
├── database/
├── README.md
└── .gitignore
```

---

# 🌳 ESI ブランチ構成

```text
main
└── develop
    ├── frontend
    ├── backend
    ├── api
    └── database
```

---

# 🔀 ブランチの役割

| ブランチ     | 役割       |
| -------- | -------- |
| main     | 完成版      |
| develop  | 開発統合用    |
| frontend | フロント開発   |
| backend  | バックエンド開発 |
| api      | API関連    |
| database | DB関連     |

---

# 🚀 Gitの基本コマンド

## 1. git init

Gitを開始するコマンド。

### コマンド

```bash
git init
```

### 解説

現在のフォルダをGit管理できるようにします。

---

# 2. git status

現在の状態を確認するコマンド。

### コマンド

```bash
git status
```

### 解説

確認できる内容：

* 変更されたファイル
* 保存されていないファイル
* commit済みかどうか

---

# 3. git add

変更したファイルを保存対象に追加します。

### コマンド

```bash
git add .
```

### 解説

`.` は「すべてのファイル」を意味します。

特定ファイルだけ追加する場合：

```bash
git add index.html
```

---

# 4. git commit

変更内容を記録します。

### コマンド

```bash
git commit -m "初回コミット"
```

### 解説

`-m` はメッセージ(message)です。

例：

```bash
git commit -m "ログイン機能追加"
```

---

# 5. git remote add

GitHubと接続します。

### コマンド

```bash
git remote add origin https://github.com/ユーザー名/sample.git
```

### 解説

| 用語     | 意味             |
| ------ | -------------- |
| origin | GitHubの保存先名    |
| URL    | GitHubリポジトリURL |

---

# 6. git push

GitHubへアップロードします。

### コマンド

```bash
git push origin develop
```

### 解説

* origin → GitHub
* develop → 開発ブランチ

---

# 7. git pull

GitHubの最新データを取得します。

### コマンド

```bash
git pull origin develop
```

### 解説

他の人が更新した内容を取得できます。

push前に実行推奨です。

---

# 🌳 ブランチ(branch)

ブランチは「作業を分ける機能」です。

例：

* main → 本番
* develop → 開発用
* feature → 新機能

---

# 🌱 ブランチ作成

### コマンド

```bash
git checkout -b feature/login
```

### 解説

新しいブランチを作成し、
そのブランチへ移動します。

---

# 🔀 Pull Request(PR)

Pull Requestは、

「変更内容を反映してください」

と依頼する機能です。

### 流れ

```text
feature/login
   ↓
Pull Request
   ↓
developへ反映
```

---

# 🔄 Git操作の流れ

```text
ファイル編集
   ↓
git add .
   ↓
git commit -m "変更内容"
   ↓
git pull origin develop
   ↓
git push origin develop
   ↓
GitHubへ保存
```

---

# 📄 .gitignore

Git管理しないファイルを書く設定ファイルです。

### 例

```text
node_modules/
.env
```

---

# 💡 よくあるミス

## pushできない

### 原因

* pullしていない
* ログインしていない
* branchが違う

### 対処

```bash
git pull origin develop
```

---

## add忘れ

### NG例

```bash
git commit -m "更新"
```

### 正しい流れ

```bash
git add .
git commit -m "更新"
```

---

# 🎯 最低限覚えるコマンド

```bash
git status
git add .
git commit -m "更新内容"
git pull origin develop
git push origin develop
```

---

# 📚 用語まとめ

| 用語           | 意味           |
| ------------ | ------------ |
| Git          | 履歴管理システム     |
| GitHub       | Gitを保存するサービス |
| repository   | 保存場所         |
| commit       | 変更記録         |
| push         | GitHubへ送信    |
| pull         | GitHubから取得   |
| branch       | 作業の分岐        |
| Pull Request | 変更反映依頼       |
