<?php
$current_keyword = sanitize_text_field(wp_unslash($_GET['keyword'] ?? ''));
$current_sort = sanitize_key(wp_unslash($_GET['sort'] ?? 'ranking'));
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo esc_html(wp_get_document_title()); ?></title>
    <link rel="stylesheet" href="<?php echo esc_url(get_template_directory_uri() . '/css/common.css'); ?>">
    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<header class="site-header">
    <a class="site-logo" href="<?php echo esc_url(home_url('/')); ?>">ESION</a>

    <form class="header-search" action="<?php echo esc_url(esion_theme_page_url('store.php')); ?>" method="get">
        <label class="screen-reader-text" for="header-keyword">ゲームを検索</label>
        <input
            id="header-keyword"
            type="search"
            name="keyword"
            value="<?php echo esc_attr($current_keyword); ?>"
            placeholder="ゲーム名を検索"
        >
        <input type="hidden" name="sort" value="<?php echo esc_attr($current_sort); ?>">
        <button type="submit">検索</button>
    </form>

    <nav class="site-nav" aria-label="メインメニュー">
        <a href="<?php echo esc_url(esion_theme_page_url('store.php')); ?>">ストア</a>
        <a href="<?php echo esc_url(esion_theme_page_url('library.php')); ?>">ライブラリ</a>
        <a href="<?php echo esc_url(esion_theme_page_url('cart.php')); ?>">カート</a>
    </nav>
</header>

