<?php
// config/database.php

return [
    'default' => 'mysql',
    'connections' => [
        'mysql' => [
            'host'      => Env::get('DB_HOST',     'localhost'),
            'port'      => Env::get('DB_PORT',     '3306'),
            'database'  => Env::get('DB_DATABASE', 'dskm_portal'),
            'username'  => Env::get('DB_USERNAME', 'root'),
            'password'  => Env::get('DB_PASSWORD', ''),
            'charset'   => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
        ],
    ],
];
