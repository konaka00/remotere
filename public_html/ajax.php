<?php

namespace MyApp;

require_once(__DIR__ . '/../config/config.php');

$nice;
$user = new \MyApp\Model\User();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $niceDir = $_POST['nicedir'];
    $userName = $_POST['username'];
    $state = $_POST['state'];
    if ($state === 'clear') {
        $nice = $user->nice($niceDir, $userName);
    } else {
        $nice = $user->deleteNice($niceDir, $userName);
    }
    
}
    header('Content-Type: application/json');
    echo json_encode($nice);
    exit;