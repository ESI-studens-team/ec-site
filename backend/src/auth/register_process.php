<?php


$pdo = require_once '../db/connect.php';
require_once '../security/password_security.php';
require_once '../security/input_validation.php';

$username = $_POST['username'] ?? '';
$email = $_POST['email'] ?? '';
$password = $_POST['password'] ?? '';

if (
    isEmptyValue($username) ||
    isEmptyValue($email) ||
    isEmptyValue($password)
) {
    echo '未入力の項目があります。';
    exit;
}

if (!isValidEmail($email)) {
    echo 'メールアドレスの形式が正しくありません。';
    exit;
}

if (!isValidPasswordLength($password)) {
    echo 'パスワードは8文字以上で入力してください。';
    exit;
}

$sql = 'SELECT id FROM users WHERE email = :email';
$stmt = $pdo->prepare($sql);
$stmt->bindValue(':email', $email);
$stmt->execute();

$existingUser = $stmt->fetch(PDO::FETCH_ASSOC);

if ($existingUser) {
    echo 'このメールアドレスは既に登録されています。';
    exit;
}

$hashedPassword = hashPassword($password);

$sql = '
    INSERT INTO users (username, email, password)
    VALUES (:username, :email, :password)
';

$stmt = $pdo->prepare($sql);
$stmt->bindValue(':username', $username);
$stmt->bindValue(':email', $email);
$stmt->bindValue(':password', $hashedPassword);
$stmt->execute();

echo '登録成功';