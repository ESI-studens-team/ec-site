<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>JSON表示テスト</title>
    <style>
        body{
            font-family:sans-serif;
            max-width:1200px;
            margin:auto;
            padding:20px;
        }

        .cards{
            display:grid;
            grid-template-columns:repeat(auto-fit,minmax(250px,1fr));
            gap:20px;
        }

        .card{
            border:1px solid #ddd;
            border-radius:10px;
            padding:15px;
        }

        .card img{
            width:100%;
            height:200px;
            object-fit:cover;
        }
    </style>
</head>
<body>

<h1>商品一覧</h1>

<?php

$json = file_get_contents(get_template_directory() . '/data.json');

$items = json_decode($json, true);

?>

<div class="cards">

<?php foreach($items as $item): ?>

    <div class="card">

        <img src="<?php echo $item['image']; ?>" alt="">

        <h2>
            <?php echo htmlspecialchars($item['title']); ?>
        </h2>

        <p>
            ¥<?php echo number_format($item['price']); ?>
        </p>

    </div>

<?php endforeach; ?>

</div>

</body>
</html>