<?php


$pdo = require_once '../db/connect.php';

require_once '../security/password_security.php';
require_once '../security/input_validation.php';
require_once '../security/session_security.php';

startSecureSession();

$email = $_POST['email'] ?? '';
$password = $_POST['password'] ?? '';

if (
    isEmptyValue($email) ||
    isEmptyValue($password)
) {
    echo 'メールアドレスまたはパスワードが正しくありません。';
    exit;
}

if (!isValidEmail($email)) {
    echo 'メールアドレスまたはパスワードが正しくありません。';
    exit;
}

$sql = 'SELECT id, email, password FROM users WHERE email = :email';

$stmt = $pdo->prepare($sql);
$stmt->bindValue(':email', $email);
$stmt->execute();

$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    echo 'メールアドレスまたはパスワードが正しくありません。';
    exit;
}

if (!verifyPassword($password, $user['password_hash'])) {
    echo 'メールアドレスまたはパスワードが正しくありません。';
    exit;
}

loginUser((int)$user['id']);

echo 'ログイン成功';