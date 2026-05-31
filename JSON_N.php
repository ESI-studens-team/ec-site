<?php
// 1. 「これはJSONデータですよ」と画面側に教えてあげる設定
header('Content-Type: application/json; charset=utf-8');

// 2. フロント（画面）が受け取りたい「本物のゲームEC用」の仮データを準備
$mockData = [
    "status" => "success",
    "message" => "ゲーム一覧（ライブラリ・ストア用）のモックデータ取得成功",
    "result" => [
        [
            "game_id"   => 101,
            "title"     => "エルデンリング (ELDEN RING)",
            "price"     => 9240,
            "image_url" => "assets/images/elden_ring.jpg",
            "developer" => "FromSoftware"
        ],
        [
            "game_id"   => 102,
            "title"     => "モンスターハンターワイルズ",
            "price"     => 9900,
            "image_url" => "assets/images/mh_wilds.jpg",
            "developer" => "CAPCOM"
        ]
    ]
];

// 3. データをJSONのテキストに変換して、画面（アクセス元）にパッと返します
echo json_encode($mockData, JSON_UNESCAPED_UNICODE);