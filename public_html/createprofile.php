<?php

require_once(__DIR__ . '/../config/config.php');


$app = new MyApp\Controller\CreateProfile();

$app->run();
$userInfo = $app->getUserInfo();


?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset='utf-8'>
    <link rel="stylesheet" href="styles.css">
    <title>Profile</title>
</head>
<body>
    <div id="profile">
    <form action="" method="post" enctype="multipart/form-data">
        <input type="hidden" name="MAX_FILE_SIZE" value="">
        <input type="file" name="image">
        <p>USER NAME</p>
        <p><?= h($userInfo->username); ?></p>
        <label><p>SELF INTR</p> <textarea name="p_introduction" rows="5"></textarea></label>
        <input type="hidden" name="username" value="<?= h($userInfo->username); ?>">
        <input type="submit" value="新規作成">
        <div id="back">Back</div>
    </form>
    </div>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script>
        $(function() {
            'use strict';
            // HTMLに<a href="userspage.php"> => セッションの内容が取れなかったので
            $('#back').on('click', function() {
             window.location.href = '/profile.php';
            });

          

        })
    </script>
</body>
</html>