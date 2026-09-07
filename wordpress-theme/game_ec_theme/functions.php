<?php

if (!defined('ABSPATH')) {
    exit;
}

function esion_register_game_post_type(): void
{
    register_post_type('game', [
        'labels' => [
            'name' => 'ゲーム',
            'singular_name' => 'ゲーム',
            'add_new_item' => 'ゲームを追加',
            'edit_item' => 'ゲームを編集',
        ],
        'public' => true,
        'show_in_rest' => true,
        'menu_icon' => 'dashicons-games',
        'supports' => ['title', 'editor', 'thumbnail', 'page-attributes'],
        'has_archive' => false,
        'rewrite' => ['slug' => 'games'],
    ]);
}
add_action('init', 'esion_register_game_post_type');

function esion_enqueue_assets(): void
{
    $theme_uri = get_template_directory_uri();
    $theme_dir = get_template_directory();

    wp_enqueue_style(
        'esion-common',
        $theme_uri . '/css/common.css',
        [],
        (string) filemtime($theme_dir . '/css/common.css')
    );
}
add_action('wp_enqueue_scripts', 'esion_enqueue_assets');

function esion_demo_games(): array
{
    return [
        [
            'id' => 'demo-1',
            'name' => 'デドバ',
            'image' => 'images/デドバ-本.jpg',
            'price' => 5000,
            'description' => '非対称型対戦ゲーム',
            'ranking' => 1,
        ],
        [
            'id' => 'demo-2',
            'name' => 'モンハン',
            'image' => 'images/モンハン-本.jpg',
            'price' => 7000,
            'description' => '仲間と狩猟を楽しめるアクションゲーム',
            'ranking' => 2,
        ],
        [
            'id' => 'demo-3',
            'name' => 'サイバー',
            'image' => 'images/サイバー-本.jpg',
            'price' => 6000,
            'description' => '未来都市を舞台にしたRPG',
            'ranking' => 3,
        ],
    ];
}

function esion_normalize_game(WP_Post $post): array
{
    $price = (int) get_post_meta($post->ID, 'game_price', true);
    $image = get_the_post_thumbnail_url($post->ID, 'large');

    if (!$image) {
        $image = (string) get_post_meta($post->ID, 'game_image', true);
    }

    return [
        'id' => (string) $post->ID,
        'name' => get_the_title($post),
        'image' => $image,
        'price' => $price,
        'description' => wp_strip_all_tags($post->post_excerpt ?: $post->post_content),
        'ranking' => max(1, (int) $post->menu_order),
    ];
}

function esion_get_game_list(string $keyword = '', string $sort = ''): array
{
    $keyword = trim($keyword);
    $allowed_sorts = ['ranking', 'price_asc', 'price_desc', 'name_asc'];
    $sort = in_array($sort, $allowed_sorts, true) ? $sort : 'ranking';

    $query = new WP_Query([
        'post_type' => 'game',
        'post_status' => 'publish',
        'posts_per_page' => -1,
        's' => $keyword,
        'orderby' => 'menu_order title',
        'order' => 'ASC',
    ]);

    $games = array_map('esion_normalize_game', $query->posts);

    $counts = wp_count_posts('game');
    $published_game_count = isset($counts->publish) ? (int) $counts->publish : 0;

    // DBにゲームが1件もない開発初期だけ、画面確認用データを使用します。
    if ($published_game_count === 0) {
        $games = esion_demo_games();

        if ($keyword !== '') {
            $games = array_values(array_filter(
                $games,
                static fn(array $game): bool => mb_stripos($game['name'], $keyword) !== false
            ));
        }
    }

    usort($games, static function (array $a, array $b) use ($sort): int {
        switch ($sort) {
            case 'price_asc':
                return $a['price'] <=> $b['price'];
            case 'price_desc':
                return $b['price'] <=> $a['price'];
            case 'name_asc':
                return strnatcasecmp($a['name'], $b['name']);
            default:
                return $a['ranking'] <=> $b['ranking'];
        }
    });

    return $games;
}

function esion_get_game(string $id): ?array
{
    if (strpos($id, 'demo-') === 0) {
        foreach (esion_demo_games() as $game) {
            if ($game['id'] === $id) {
                return $game;
            }
        }
        return null;
    }

    $post = get_post((int) $id);
    if (!$post || $post->post_type !== 'game' || $post->post_status !== 'publish') {
        return null;
    }

    return esion_normalize_game($post);
}

function esion_game_image_url(string $image): string
{
    if ($image === '') {
        return get_template_directory_uri() . '/images/no-image.png';
    }

    if (filter_var($image, FILTER_VALIDATE_URL)) {
        return $image;
    }

    return get_template_directory_uri() . '/' . ltrim($image, '/');
}

function esion_theme_page_url(string $filename): string
{
    return get_template_directory_uri() . '/' . ltrim($filename, '/');
}
