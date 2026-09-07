<?php
require_once __DIR__ . '/common/bootstrap.php';

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

$_SESSION['esion_cart'] ??= [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nonce = sanitize_text_field(wp_unslash($_POST['esion_cart_nonce'] ?? ''));
    $action = sanitize_key(wp_unslash($_POST['action'] ?? ''));
    $game_id = sanitize_text_field(wp_unslash($_POST['game_id'] ?? ''));

    if (wp_verify_nonce($nonce, 'esion_add_to_cart') && $action === 'add' && esion_get_game($game_id)) {
        $_SESSION['esion_cart'][] = $game_id;
        $_SESSION['esion_cart'] = array_values(array_unique($_SESSION['esion_cart']));
    }
}

$cart_games = array_values(array_filter(array_map('esion_get_game', $_SESSION['esion_cart'])));
$total = array_sum(array_column($cart_games, 'price'));

include __DIR__ . '/common/header.php';
?>

<main class="page-main">
    <section class="page-heading">
        <p class="eyebrow">CART</p>
        <h1>ショッピングカート</h1>
    </section>

    <?php if ($cart_games): ?>
        <div class="cart-list">
            <?php foreach ($cart_games as $game): ?>
                <div class="cart-row">
                    <span><?php echo esc_html($game['name']); ?></span>
                    <strong><?php echo esc_html(number_format($game['price'])); ?>円</strong>
                </div>
            <?php endforeach; ?>
        </div>
        <p class="cart-total">合計：<?php echo esc_html(number_format($total)); ?>円</p>
    <?php else: ?>
        <div class="empty-state"><p>カートは空です。</p></div>
    <?php endif; ?>
</main>

<?php include __DIR__ . '/common/footer.php'; ?>

