<?php

require_once(__DIR__ . '/../config/config.php');


$app = new \MyApp\Controller();
$userInfo = $app->getUserInfo();

$user = new \MyApp\Model\User();
$profile = $user->getProfile($userInfo);





?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset='utf-8'>
    <link rel="stylesheet" href="styles.css">
    <title>Profile</title>
</head>
<body>
    <header>
        <?php include(__DIR__ . '/header.php');?>
    </header>
    <h1>My Profile</h1>
    <div id="">
        <div id="prof_container">
            <div>
                <div id="prof_img"><img src="<?= $profImagePath ?>"></div>
                <div id="prof_name"><?= h($profile['username']); ?></div>
                <div id="prof_edit"><a href="">編集</a></div>
            </div>      
            <div id="prof_intro"><?= h($profile['intro']); ?></div>
        </div>
        <div id="btn_blue" class="hidden"><a href="createprofile.php">Create</a></div>
        <div id="back">Back</div>
    </div>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script>
        $(function() {
            'use strict';

            if (<?= json_encode($profile); ?> === false) {
                $('#btn_blue').removeClass('hidden');
                $('#prof_edit').addClass('hidden');
            }
           
            // HTMLに<a href="userspage.php"> => セッションの内容が取れなかったので
            $('#back').on('click', function() {
             window.location.href = '/userspage.php';
            });

          

        })
    </script>
</body>
</html>