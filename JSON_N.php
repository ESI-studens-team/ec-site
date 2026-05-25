<?php
// 1. 「これはJSONデータですよ」と画面側に教えてあげる設定
header('Content-Type: application/json; charset=utf-8');

// 2. フロント（画面）が受け取りたい「仮のデータ」を準備
// ※もし指定の項目があれば、ここの英語（"status"など）を書き換えます
$mockData = [
    "status" => "success",
    "message" => "チケット#24用のモックデータが正しく返ってきました！",
    "result" => [
        [
            "id" => 1,
            "title" => "ライブラリテスト本 A",
            "author" => "テスト太郎"
        ],
        [
            "id" => 2,
            "title" => "ライブラリテスト本 B",
            "author" => "テスト次郎"
        ]
    ]
];

// 3. データをJSONのテキストに変換して、画面（アクセス元）にパッと返します
echo json_encode($mockData, JSON_UNESCAPED_UNICODE);