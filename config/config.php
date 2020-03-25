<?php

ini_set('display_errors', '1');
define('DSN', 'mysql:host=localhost;dbname=myblog_db');
define('DB_USERNAME', 'dbuser');
define('DB_PASSWORD', 'myblog');

define('IMAGES_DIR_PATH', __DIR__  . '/../public_html/images');
define('THUMBS_DIR_PATH', __DIR__  . '/../public_html/thumbs');
define('PROFILES_DIR_PATH', __DIR__  . '/../public_html/profiles');
define('PROFTHUMBS_DIR_PATH', __DIR__  . '/../public_html/profthumbs');

function h($s) {
    return htmlspecialchars($s, ENT_QUOTES, 'utf-8');
}

require_once(__DIR__ . '/autoload.php');

session_start();
