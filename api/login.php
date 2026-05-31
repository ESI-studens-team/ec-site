<?php
header('Content-Type: application/json');

echo json_encode([
    "status" => true,
    "message" => "ログイン成功（仮）",
    "token" => "sample_token_123",
    "user" => [
        "id" => 1,
        "email" => "test@example.com"
    ]
], JSON_UNESCAPED_UNICODE);