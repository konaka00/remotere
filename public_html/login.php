<?php

require_once(__DIR__ . '/../config/config.php');

$app = new MyApp\Controller\Login();
$app->run();

$image;
if (isset($_GET['image'])) {
    $image = $_GET['image'];
} else {
    $image = null;
}



?>


<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="utf-8">
    <link rel="stylesheet" href="styles.css">
    <title>Login</title>
</head>
<body>
        <h1>Login Page</h1>
        <div id="container">
            <form action="" method="post" id="form">
                <input type="email" name="email" placeholder="Email">
                <input type="password" name="password" placeholder="Password">
                <!-- 条件分岐　表示非表示 -->
                <input type="hidden" name="image" value="<?= $image ?>" id="hidden">
                <div id="submit">Login</div>
            </form>
        </div>
        <div id="back"><a href="/">Back</a></div>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
        <script>
            $(function() {
                'use strict';

                $('#submit').on('click', function() {
                    $('#form').submit();
                });

                if (typeof <?= json_encode($image) ?> === "string") {
                    $('h1').text('Please Login To Post Your Comment');
                } else {
                    $('#hidden').remove();
                }
            });
        </script>
</body>
</html>