<?php
// autoload.php

spl_autoload_register(function ($class) {
    $baseDir = __DIR__ . '/';

    // Map of namespace prefixes to directories
    $namespaceMap = [
        'Core\\'          => 'core/',
        'App\Controllers\\' => 'app/Controllers/',
        'App\Models\\'    => 'app/Models/',
        'App\Middleware\\' => 'app/Middleware/',
        'Controllers\\'   => 'app/Controllers/',
        'Models\\'        => 'app/Models/',
        'Middleware\\'    => 'app/Middleware/',
    ];

    foreach ($namespaceMap as $prefix => $dir) {
        $len = strlen($prefix);
        if (strncmp($prefix, $class, $len) !== 0) {
            continue;
        }
        $relativeClass = substr($class, $len);
        $file = $baseDir . $dir . str_replace('\\', '/', $relativeClass) . '.php';
        if (file_exists($file)) {
            require $file;
            return;
        }
    }

    // Fallback: try direct mapping (handles plain class names)
    $file = $baseDir . str_replace('\\', '/', $class) . '.php';
    if (file_exists($file)) {
        require $file;
        return;
    }

    // Try lowercase first segment (Core\ -> core/)
    $parts = explode('\\', $class);
    $parts[0] = strtolower($parts[0]);
    $file = $baseDir . implode('/', $parts) . '.php';
    if (file_exists($file)) {
        require $file;
        return;
    }
});
