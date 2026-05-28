<?php
// config/app.php

return [
    'name'      => 'DSKM Batch Portal',
    'url'       => getenv('APP_URL') ?: 'http://localhost/dskm-portal/public',
    'env'       => getenv('APP_ENV') ?: 'production',
    'debug'     => getenv('APP_DEBUG') === 'true',
    'timezone'  => 'Asia/Dhaka',
    'locale'    => 'bn',
    'key'       => getenv('APP_KEY') ?: 'base64:dskm_portal_secret_key_change_in_production_32ch',

    'upload_path'     => dirname(__DIR__) . '/storage/uploads/',
    'upload_url'      => '/storage/uploads/',
    'max_upload_size' => 5 * 1024 * 1024, // 5MB

    'allowed_image_types' => ['image/jpeg', 'image/png', 'image/webp', 'image/gif'],
    'allowed_doc_types'   => ['application/pdf', 'image/jpeg', 'image/png'],

    'session_lifetime' => 120,  // minutes
    'remember_me_days' => 30,

    'pagination_limit' => 12,

    'smtp' => [
        'host'     => getenv('MAIL_HOST')     ?: 'smtp.gmail.com',
        'port'     => getenv('MAIL_PORT')     ?: 587,
        'username' => getenv('MAIL_USERNAME') ?: '',
        'password' => getenv('MAIL_PASSWORD') ?: '',
        'from'     => getenv('MAIL_FROM')     ?: 'noreply@dskmportal.com',
        'name'     => 'DSKM Batch Portal',
    ],
];
