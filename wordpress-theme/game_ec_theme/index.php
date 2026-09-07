<?php
require_once __DIR__ . '/common/bootstrap.php';
include __DIR__ . '/common/header.php';
?>

<main class="page-main hero-main">
    <section class="hero">
        <p class="eyebrow">GAME STORE</p>
        <h1>次に遊ぶゲームを<br>ESIONで見つけよう</h1>
        <p>検索と並び替えを使って、ゲームを探せます。</p>
        <a class="primary-button" href="<?php echo esc_url(esion_theme_page_url('store.php')); ?>">ストアを見る</a>
    </section>
</main>

<?php include __DIR__ . '/common/footer.php'; ?>

