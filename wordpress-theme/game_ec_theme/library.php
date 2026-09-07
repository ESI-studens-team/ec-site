<?php
require_once __DIR__ . '/common/bootstrap.php';

$keyword = sanitize_text_field(wp_unslash($_GET['keyword'] ?? ''));
$games = esion_get_game_list($keyword, 'name_asc');

include __DIR__ . '/common/header.php';
?>

<main class="page-main library-main">
    <section class="page-heading">
        <p class="eyebrow">LIBRARY</p>
        <h1>ゲームライブラリ</h1>
    </section>

    <?php if ($games): ?>
        <div class="library-list">
            <?php foreach ($games as $game): ?>
                <article class="library-card">
                    <img
                        src="<?php echo esc_url(esion_game_image_url($game['image'])); ?>"
                        alt="<?php echo esc_attr($game['name']); ?>"
                    >
                    <div>
                        <h2><?php echo esc_html($game['name']); ?></h2>
                        <p><?php echo esc_html($game['description'] ?: 'ゲームの詳細を確認できます。'); ?></p>
                        <a class="secondary-button" href="<?php echo esc_url(add_query_arg('id', $game['id'], esion_theme_page_url('detail.php'))); ?>">
                            商品を見る
                        </a>
                    </div>
                </article>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <div class="empty-state"><p>ライブラリにゲームがありません。</p></div>
    <?php endif; ?>
</main>

<?php include __DIR__ . '/common/footer.php'; ?>

