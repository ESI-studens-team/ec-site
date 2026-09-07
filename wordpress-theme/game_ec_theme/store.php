<?php
require_once __DIR__ . '/common/bootstrap.php';

$keyword = sanitize_text_field(wp_unslash($_GET['keyword'] ?? ''));
$sort = sanitize_key(wp_unslash($_GET['sort'] ?? 'ranking'));
$games = esion_get_game_list($keyword, $sort);

include __DIR__ . '/common/header.php';
?>

<main class="page-main">
    <section class="page-heading">
        <p class="eyebrow">STORE</p>
        <h1>ゲームストア</h1>
        <p>タイトルを検索し、並び順を変更できます。</p>
    </section>

    <form class="filter-form" method="get" action="<?php echo esc_url(esion_theme_page_url('store.php')); ?>">
        <div class="filter-field filter-field--wide">
            <label for="keyword">商品名</label>
            <input
                id="keyword"
                type="search"
                name="keyword"
                value="<?php echo esc_attr($keyword); ?>"
                placeholder="例：モンハン"
            >
        </div>

        <div class="filter-field">
            <label for="sort">並び替え</label>
            <select id="sort" name="sort">
                <option value="ranking" <?php selected($sort, 'ranking'); ?>>ランキング順</option>
                <option value="price_asc" <?php selected($sort, 'price_asc'); ?>>価格が安い順</option>
                <option value="price_desc" <?php selected($sort, 'price_desc'); ?>>価格が高い順</option>
                <option value="name_asc" <?php selected($sort, 'name_asc'); ?>>名前順</option>
            </select>
        </div>

        <button class="primary-button" type="submit">一覧を更新</button>
    </form>

    <?php if ($keyword !== ''): ?>
        <p class="result-message">
            「<?php echo esc_html($keyword); ?>」の検索結果：<?php echo esc_html((string) count($games)); ?>件
        </p>
    <?php endif; ?>

    <?php if ($games): ?>
        <section class="game-grid" aria-label="ゲーム一覧">
            <?php foreach ($games as $game): ?>
                <article class="game-card">
                    <img
                        src="<?php echo esc_url(esion_game_image_url($game['image'])); ?>"
                        alt="<?php echo esc_attr($game['name']); ?>"
                    >
                    <div class="game-card__body">
                        <p class="ranking">RANK <?php echo esc_html((string) $game['ranking']); ?></p>
                        <h2><?php echo esc_html($game['name']); ?></h2>
                        <p class="price"><?php echo esc_html(number_format($game['price'])); ?>円</p>
                        <a class="secondary-button" href="<?php echo esc_url(add_query_arg('id', $game['id'], esion_theme_page_url('detail.php'))); ?>">
                            商品を見る
                        </a>
                    </div>
                </article>
            <?php endforeach; ?>
        </section>
    <?php else: ?>
        <div class="empty-state">
            <h2>一致するゲームがありません</h2>
            <p>検索文字を短くするか、別のタイトルで検索してください。</p>
        </div>
    <?php endif; ?>
</main>

<?php include __DIR__ . '/common/footer.php'; ?>

