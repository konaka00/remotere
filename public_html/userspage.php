<?php

require_once(__DIR__ . '/../config/config.php');


$app = new MyApp\Controller\UsersPage();
$userInfo = $app->getUserInfo();

$app->run($userInfo);
$images = $app->getMainImages($userInfo->username);
$nices = $app->getNices();




?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="utf-8">
    <link rel="stylesheet" href="/styles.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.7.1/css/lightbox.css" rel="stylesheet">
    <title>Blog App</title>
</head>
<body>
    <header>
        <?php include(__DIR__ . '/header.php');?>
    </header>
    <div id="file_container">
        <form action="" method="post" enctype="multipart/form-data" id="form">
            <div class="err">
                <?= $app->getErrors('title'); ?>
                <?= $app->getErrors('file'); ?>
            </div>
            <input type="hidden" name="MAX_FILE_SIZE" value="">
            <div id="file_back">
                <input type="file" name="image" id="put_image">
            </div>
            <input type="text" name="title"" id="image_title" placeholder="画像タイトル">
            <div id="submit">新規投稿</div>
            <input type="hidden" name="username" value="<?= h($userInfo->username); ?>">
            <div id="back"><a href="/mainpage.php">Back</a></div>
        </form>
    </div>
    <div>
        <ul id="my_image">
            <?php foreach($images as $title => $image) : ?>
            <li id="box">
                <p id="box_title"><?= h($title); ?><span class="box_delete" data-id="<?= substr($image, 6); ?>">X</span></p>
                <a href="<?= str_replace('thumbs', 'images', $image); ?>" data-lightbox="mainimage" data-title="<?= h($title); ?> Nice:<?= isset($nices[$image]) ? h($nices[$image]) : '0'; ?>">
                    <img id="box_image" src="<?= /*ファイル名だけ取得処理*/h($image); ?>">
                </a>
            </li>
            <?php endforeach; ?>
        </ul>
    </div>
    <footer>
    </footer>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.7.1/js/lightbox.min.js" type="text/javascript"></script>
    <script>
        $(function() {
            'use strict';
            
            $('#submit').on('click', function() {
                $('#form').submit();
            });

            $('.box_delete').on('click', function() {
                var fileName =  $(this).data('id');
                var fileDir =  $(this).parent('#box_title').parent('#box');

                $.ajax({
                    url: '/delete.php',
                    type: 'post',
                    data: {
                        fileName : fileName
                    }
                }).done(function() {
                    fileDir.remove();
                    console.log('success');
                }).fail(function(XMLHttpRequest, textStatus, errorThrown) { 
                    console.log(XMLHttpRequest.status);
                    console.log(textStatus);
                    console.log(errorThrown);
                });          

        });
        });
    </script>
</body>
</html>