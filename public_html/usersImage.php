<?php

require_once(__DIR__ . '/../config/config.php');
$state = 1;

$imageUser = $_GET['username'];

$app = new MyApp\Controller\UsersPage();
$values = $app->getMainImages($imageUser);


?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="utf-8">
    <link rel="stylesheet" href="styles.css">
    <title>Blog App</title>
</head>
<body>
    <header>
        <?php include(__DIR__ . '/header.php'); ?>
    </header>
    <div id="line_space">
        <div id="back"><a href="/index.php">Back</a></div>
    </div>
        <div id="image_container">
            <?php foreach($values as $title => $value) : ?>
            <div class="grid_item">
                <img src="<?= h($value); ?>">
            </div>
            <?php endforeach; ?>
        </div>
    <footer>
    </footer>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
        <script>
            $(function() {
                'use strict';

            });
        </script>
</body>
</html>