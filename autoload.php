<?php
// autoload.php

spl_autoload_register(function ($class) {
    $prefix = '';
    $baseDir = __DIR__ . '/';

    $len = strlen($prefix);
    if (strncmp($prefix, $class, $len) !== 0) {
        // Class doesn't use our namespace
    }

    $relativeClass = substr($class, $len);
    
    // Replace namespace separators with directory separators
    $file = $baseDir . str_replace('\\', '/', $relativeClass) . '.php';

    if (file_exists($file)) {
        require $file;
    }
});
