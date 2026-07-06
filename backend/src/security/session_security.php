<?php
// ログイン成功時にセッションIDを作り直す、セッション固定化攻撃対策
function startSecureSession(): void
{
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
}

function regenerateSession(): void
{
    session_regenerate_id(true);
}

function loginUser(int $userId): void
{
    regenerateSession();

    $_SESSION['user_id'] = $userId;
    $_SESSION['logged_in'] = true;
}

function logoutUser(): void
{
    $_SESSION = [];

    if (ini_get('session.use_cookies')) {
        $params = session_get_cookie_params();

        setcookie(
            session_name(),
            '',
            time() - 42000,
            $params['path'],
            $params['domain'],
            $params['secure'],
            $params['httponly']
        );
    }

    session_destroy();
}