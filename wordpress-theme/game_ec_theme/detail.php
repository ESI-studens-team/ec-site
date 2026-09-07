<?php
require_once __DIR__ . '/common/bootstrap.php';

$id = sanitize_text_field(wp_unslash($_GET['id'] ?? ''));
$game = esion_get_game($id);

include __DIR__ . '/common/header.php';
?>

<main class="page-main detail-main">
    <button class="back-button" type="button" onclick="history.back()">← 戻る</button>

    <?php if ($game): ?>
        <article class="detail-card">
            <img
                class="detail-image"
                src="<?php echo esc_url(esion_game_image_url($game['image'])); ?>"
                alt="<?php echo esc_attr($game['name']); ?>"
            >

            <div class="detail-content">
                <p class="eyebrow">GAME DETAIL</p>
                <h1><?php echo esc_html($game['name']); ?></h1>
                <p><?php echo esc_html($game['description'] ?: 'ゲームの詳細情報です。'); ?></p>
                <p class="detail-price"><?php echo esc_html(number_format($game['price'])); ?>円</p>

                <form action="<?php echo esc_url(esion_theme_page_url('cart.php')); ?>" method="post">
                    <?php wp_nonce_field('esion_add_to_cart', 'esion_cart_nonce'); ?>
                    <input type="hidden" name="action" value="add">
                    <input type="hidden" name="game_id" value="<?php echo esc_attr($game['id']); ?>">
                    <button class="primary-button" type="submit">カートに入れる</button>
                </form>
            </div>
        </article>
    <?php else: ?>
        <div class="empty-state">
            <h1>商品が見つかりません</h1>
            <a class="secondary-button" href="<?php echo esc_url(esion_theme_page_url('store.php')); ?>">ストアへ戻る</a>
        </div>
    <?php endif; ?>
</main>

<?php include __DIR__ . '/common/footer.php'; ?>

