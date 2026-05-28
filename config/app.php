<?php
// config/app.php

return [
    'name'      => 'DSKM Batch Portal',
    'url'       => Env::get('APP_URL',   'http://localhost/dskm-portal/public'),
    'env'       => Env::get('APP_ENV',   'production'),
    'debug'     => Env::get('APP_DEBUG', 'false') === 'true',
    'timezone'  => 'Asia/Dhaka',
    'locale'    => 'bn',
    'key'       => Env::get('APP_KEY',   'base64:change_this_key'),

    'upload_path'     => dirname(__DIR__) . '/storage/uploads/',
    'upload_url'      => '/storage/uploads/',
    'max_upload_size' => (int) Env::get('MAX_UPLOAD_SIZE', 5242880),

    'allowed_image_types' => ['image/jpeg', 'image/png', 'image/webp', 'image/gif'],
    'allowed_doc_types'   => ['application/pdf', 'image/jpeg', 'image/png'],

    'session_lifetime' => 120,
    'remember_me_days' => 30,
    'pagination_limit' => 12,

    'smtp' => [
        'host'     => Env::get('MAIL_HOST',     'smtp.gmail.com'),
        'port'     => Env::get('MAIL_PORT',     587),
        'username' => Env::get('MAIL_USERNAME', ''),
        'password' => Env::get('MAIL_PASSWORD', ''),
        'from'     => Env::get('MAIL_FROM',     'noreply@dskmportal.com'),
        'name'     => 'DSKM Batch Portal',
    ],
];
