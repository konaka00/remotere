<?php

require_once(__DIR__ . '/../config/config.php');

$app = new \MyApp\Controller();
$user = new \MyApp\Model\User();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fileName = $_POST['fileName'];
    $app->deleteFile($fileName);
    $user->deleteImage($fileName);
}


