<?php
// config/database.php

return [
    'default' => 'mysql',
    'connections' => [
        'mysql' => [
            'host'      => getenv('DB_HOST')     ?: 'localhost',
            'port'      => getenv('DB_PORT')     ?: '3306',
            'database'  => getenv('DB_DATABASE') ?: 'dskm_portal',
            'username'  => getenv('DB_USERNAME') ?: 'root',
            'password'  => getenv('DB_PASSWORD') ?: '',
            'charset'   => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
        ],
    ],
];
