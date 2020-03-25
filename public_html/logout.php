<?php

require_once(__DIR__ . '/../config/config.php');

if (isset($_SESSION['userInfo'])) {
    $_SESSION = [];
    if(isset($_COOKIE[session_name()])) {
        setcookie(session_name(), '', time() - 86400, '/');
    }
    session_destroy();
}

header('Location: http://' . $_SERVER['HTTP_HOST']);