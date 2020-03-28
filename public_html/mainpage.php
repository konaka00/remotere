<?php

require_once(__DIR__ . '/../config/config.php');


$app = new MyApp\Controller\Index();
list($images, $username, $time) = $app->getMainImages();

$userInfo = $app->getUserInfo();

$nices = $app->getNices();
$user = new \MyApp\Model\User();
$myNice = $user->addDone($userInfo->username);






?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="utf-8">
    <link rel="stylesheet" href="styles.css">
    <!-- Lightbox2を読み込み -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.7.1/css/lightbox.css" rel="stylesheet">
    <title>Blog App</title>
</head>
<body>
    <header>
        <?php include(__DIR__ . '/header.php');?>
    </header>
    <div>
        <h2>All Image</h2>
        <form action="" method="post"  class="vote">
        <ul id="all_image">
            <?php foreach($images as $title => $image): ?>
            <li id="box">
                <p id="box_title">「 <?= h($title); ?> 」</p>
                <a id="light_box" href="<?= str_replace('thumbs', 'images', $image); ?>" data-lightbox="mainimage" data-title="<?= h($title); ?><?= isset($nices[$image]) ? h($nices[$image]) : '0'; ?>">
                    <img id="box_image" src="<?= h($image); ?>" data-id="<?= h($image); ?>">
                </a>
                <div id="box_user">
                    <p id="user"><?= h($username[$image]); ?></p>
                    <p id="date">(<?= date('Y/m', strtotime($time[$image])); ?>)</p>
                </div>
                <div class="btn <?= isset($myNice[$image]) ? $myNice[$image] : ''; ?>">Nice Image</div>
                <div id="box_react">
                    <img  id="nice" src="source_image/nice_logo.png" width="20px" height="20px">
                    <p id="nice_count"><?= isset($nices[$image]) ? h($nices[$image]) : '0'; ?></p>
                    <a id="comment_link" href="<?= isset($userInfo) ? 'comment.php?image=' . $image : 'login.php?image=' . $image; ?>">
                        <img src="source_image/comment_logo.png" width="20px" height="20px" >
                    </a>
                    <a id="text" href="comment.php?image=<?= h($image); ?>">
                        コメント
                    </a>
                </div>
            </li>
            <?php endforeach; ?>
            <input type="hidden" value="" id="nice_dir" name="votedir">
        </ul>
        </form>
    </div>
    <footer>
    </footer>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<!-- Lightbox2のスクリプト本体 -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.7.1/js/lightbox.min.js" type="text/javascript"></script>
<script>
    $(function() {
        'use strict';
        var username = <?= json_encode($userInfo->username); ?>;

        $('.btn').on('click', function() {
            $('#nice_dir').val($(this).siblings('#light_box').children('#box_image').data('id'));
            var nice = $(this).siblings('#box_react').children('#nice_count');
            var nice_btn = $(this);
            var state = '';
            if ($(this).hasClass('done')) {
                var state = 'done';
            } else {
                var state = 'clear';
            }
            console.log(state);

            if ($('#nice_dir').val() ==='') {
                alert('Error');
            } else {
                //ajaxでDBの値を変更・取得
                $.ajax({
                    url: 'ajax.php',
                    type: 'POST',
                    data: {
                        nicedir: $('#nice_dir').val(),
                        username: username,
                        state: state
                    }
                }).done(function(res) {
                    nice.text(res.count);
                    nice_btn.toggleClass('done');

                }).fail(function(XMLHttpRequest, textStatus, errorThrown) { 
                    console.log(XMLHttpRequest.status);
                    console.log(textStatus);
                    console.log(errorThrown);
                });          
                
                $('#nice_dir').val() ==='';
            }
        });
        
    });
</script>
</body>
</html>