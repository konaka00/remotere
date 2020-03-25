<?php

spl_autoload_register(function($class) {
    $prefix = 'MyApp\\';
    if (strpos($class, $prefix) === 0) {
        $className = substr($class, strlen($prefix));
        $classPath = __DIR__ . '/../lib/' . str_replace('\\', '/', $className) . '.php';
        if(file_exists($classPath)) {
            require $classPath;
        }
    }
});