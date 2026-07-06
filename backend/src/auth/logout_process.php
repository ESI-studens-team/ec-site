<?php

require_once '../security/session_security.php';

startSecureSession();

logoutUser();

echo 'ログアウトしました。';