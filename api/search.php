<?php
header('Content-Type: application/json');

try {
    // SQLite接続
    $db = new PDO('sqlite:database.db');
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // パラメータ取得
    $keyword   = $_GET['keyword'] ?? '';
    $category  = $_GET['category'] ?? '';
    $sale      = isset($_GET['sale']) ? (int)$_GET['sale'] : 0;
    $free      = isset($_GET['free']) ? (int)$_GET['free'] : 0;
    $ranking   = isset($_GET['ranking']) ? (int)$_GET['ranking'] : 0;
    $sort      = $_GET['sort'] ?? '';
    $minPrice  = $_GET['min_price'] ?? null;
    $maxPrice  = $_GET['max_price'] ?? null;

    // ベースSQL
    $sql = "
        SELECT DISTINCT
            g.id,
            g.title,
            g.description,
            g.price,
            g.rating,
            g.review_count,
            g.release_date,
            s.discount_rate,
            c.category_name
        FROM games g
        LEFT JOIN categories c ON g.id = c.game_id
        LEFT JOIN sales s ON g.id = s.game_id
        WHERE 1=1
    ";

    $params = [];

    // #49 絞り込み（キーワード）
    if (!empty($keyword)) {
        $sql .= " AND (
            g.title LIKE :keyword
            OR g.description LIKE :keyword
        )";
        $params[':keyword'] = "%{$keyword}%";
    }

    // #49 絞り込み（カテゴリ）
    if (!empty($category)) {
        $sql .= " AND c.category_name = :category";
        $params[':category'] = $category;
    }

    // #64 検索・値段
    if ($minPrice !== null && $minPrice !== '') {
        $sql .= " AND g.price >= :min_price";
        $params[':min_price'] = (int)$minPrice;
    }

    if ($maxPrice !== null && $maxPrice !== '') {
        $sql .= " AND g.price <= :max_price";
        $params[':max_price'] = (int)$maxPrice;
    }

    // #65 検索・セール別
    if ($sale === 1) {
        $sql .= " AND s.discount_rate > 0";
    }

    // #67 検索・無料
    if ($free === 1) {
        $sql .= " AND g.price = 0";
    }

    // #48 ソート
    $allowedSorts = [
        'newest'     => 'g.release_date DESC',
        'rating'     => 'g.rating DESC',
        'price_low'  => 'g.price ASC',
        'price_high' => 'g.price DESC',
        'popular'    => 'g.review_count DESC'
    ];

    $orderBy = 'g.title ASC';

    if (!empty($sort) && isset($allowedSorts[$sort])) {
        $orderBy = $allowedSorts[$sort];
    }

    // #63 検索・ランキング
    if ($ranking === 1) {
        $orderBy = 'g.review_count DESC';
    }

    // #66 検索・評価順
    if ($sort === 'rating') {
        $orderBy = 'g.rating DESC';
    }

    $sql .= " ORDER BY {$orderBy}";

    // 実行
    $stmt = $db->prepare($sql);
    $stmt->execute($params);

    $games = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode([
        'status' => true,
        'count'  => count($games),
        'data'   => $games
    ], JSON_UNESCAPED_UNICODE);

} catch (PDOException $e) {

    http_response_code(500);

    echo json_encode([
        'status' => false,
        'message' => 'サーバーエラー'
    ], JSON_UNESCAPED_UNICODE);
}