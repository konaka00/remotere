<?php

require_once(__DIR__ . '/../config/config.php');


$app = new \MyApp\Controller\Comment();
$userInfo = $app->getUserInfo();
$app->run();

$image = $_GET['image'];
list($username, $comments, $count) = $app->getComments($image);

?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset='utf-8'>
    <link rel="stylesheet" href="styles.css">
    <title>Comments</title>
</head>
<body>
    <h1>Comments</h1>
    <div>
        <img src="<?= h($image); ?>" data-id="<?= h($image); ?>">
    <div>
    <p id="count"><?= h($count); ?>comment</p>
    <?php foreach($comments as $comment) : ?>
    <div id="comments">
        <div id="comment_user"><?= h($username[$comment]); ?></div>
        <div id="symbol"><</div>
        <div id="comment"><?= h($comment); ?></div>
    </div>
    <?php endforeach; ?>
    <form action="" method="post" id="comment_form">
        <input type="hidden" name="filepath" value="<?= h($image); ?>">
        <input type="hidden" name="username" value="<?= h($userInfo->username); ?>">
        <div id="comments" class="commenter_box">
            <div id="commenter"><?= isset($userInfo) ? $userInfo->username : ''; ?></div>
            <div id="symbol"></div>
            <div><input type="text" name="comment" maxlength="20"></div>
        </div>
        <div class="btn">コメント投稿</div>
    </form>
    <div id="btn_blue" class="gray hidden" ><a href="login.php">Login</a></div>
    <div id="back"><a href="<?= isset($userInfo) ? '/mainpage.php' : '/index.php'; ?>">Back</a></div>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script>
        $(function() {
            'use strict';
            
            if ($('#commenter').text() === '') {
                $('.btn').hide();
                $('.commenter_box').hide();
                $('#btn_blue').show();
            }


            $('.btn').on('click', function() {
                $('#comment_form').submit();
            });
        })
    </script>
</body>
</html>