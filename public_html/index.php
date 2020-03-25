<?php

require_once(__DIR__ . '/../config/config.php');


$app = new MyApp\Controller();
$app->isLoggedIn();
$userInfo = $app->getUserInfo();

list($images, $username, $time) = $app->getMainImages();

$nices = $app->getNices();

// nice数順
arsort($nices);
// キーと値反転
$fImages = array_flip($images);

$aImages = [];
// 降順に並べ換えた$nicesに沿って$fImagesを並べ換え
foreach ($nices as $dir => $nice) {
    $aImages[$fImages[$dir]] = $dir;
}
$images = $aImages;


?>


<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="utf-8">
    <link rel="stylesheet" href="styles.css">
    <!-- CDNサーバからLightbox2を読み込む -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.7.1/css/lightbox.css" rel="stylesheet">
    <title>Blog App</title>
</head>
<body>
    <header>
        <?php include(__DIR__ . '/header.php');?>
    </header>
    <div>
        <h2>Popular</h2>
        <ul id="popular">
            <?php foreach($images as $title => $image): ?>
            <li id="box">
                <p id="box_title">「 <?= h($title); ?> 」</p>
                <a href="<?= str_replace('thumbs', 'images', $image); ?>" data-lightbox="mainimage" data-title="<?= h($title); ?> Nice:<?= isset($nices[$image]) ? h($nices[$image]) : '0'; ?>">
                    <img id="box_image" src="<?= h($image); ?>" data-id="<?= h($image); ?>">
                </a>
                <div id="box_user">
                    <p id="user"><a href="usersImage.php?username=<?= h($username[$image]); ?>"><?= h($username[$image]); ?></a></p>
                    <p id="date">(<?= date('Y/m', strtotime($time[$image])); ?>)</p><br>
                </div>
                <div id="box_react">
                    <img  id="nice" src="source_image/nice_logo.png" width="20px" height="20px">
                    <p id="nice_count"><?= isset($nices[$image]) ? h($nices[$image]) : '0'; ?></p>
                    <a id="comment_link" href="comment.php?image=<?= h($image); ?>">
                        <img src="source_image/comment_logo.png" width="20px" height="20px" >
                    </a>
                    <a id="text" href="comment.php?image=<?= h($image); ?>">
                        コメント
                    </a>
                </div>
            </li>
            <?php endforeach; ?>
        </ul>
    </div>
    <footer>
    </footer>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<!-- CDNサーバからLightbox2のスクリプト本体読み込む -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.7.1/js/lightbox.min.js" type="text/javascript"></script>
</body>
</html>