<?php

require_once(__DIR__ . '/../config/config.php');

$app = new \MyApp\Controller\Signup();
$app->run();


?>


<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="utf-8">
    <link rel="stylesheet" href="styles.css">
    <title>Sign Up</title>
</head>
<body>
        <h1>New Account</h1>
        <div id="container">
            <form action="" method="post" id="form">
                <input type="text" name="username" placeholder="User Name">
                <input type="email" name="email" placeholder="Email">
                <input type="password" name="password" placeholder="Password">
                <div id="submit">Create</div>
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
            });
        </script>
</body>
</html>