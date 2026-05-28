<?php
// public/index.php

// Error reporting
error_reporting(E_ALL & ~E_DEPRECATED & ~E_USER_DEPRECATED);
ini_set('display_errors', 0);
ini_set('log_errors', 1);

// Load .env file FIRST before anything else
require_once dirname(__DIR__) . '/core/Env.php';
Env::load(dirname(__DIR__) . '/.env');

// Define base path for subfolder installs (e.g. /dskm-portal/public)
// Automatically detected - works on localhost AND live domain
$scriptDir = dirname($_SERVER['SCRIPT_NAME']);
define('BASE_PATH', rtrim($scriptDir, '/'));
define('BASE_URL', (isset($_SERVER['HTTPS']) ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . BASE_PATH);

// Start session
session_start();

// Load autoloader
require_once dirname(__DIR__) . '/autoload.php';

// Load helper functions
require_once dirname(__DIR__) . '/app/Helpers/helpers.php';

// Start session management
\Core\Session::start();

// Load routes
$router = require_once dirname(__DIR__) . '/routes/web.php';

// Get request URI and method
$uri = $_SERVER['REQUEST_URI'];
$method = $_SERVER['REQUEST_METHOD'];

// Handle PUT and DELETE via _method field
if ($method === 'POST' && isset($_POST['_method'])) {
    $method = strtoupper($_POST['_method']);
}

// Dispatch the request
try {
    $router->dispatch($uri, $method);
} catch (Exception $e) {
    error_log('Application Error: ' . $e->getMessage());
    http_response_code(500);
    echo '<h1>500 - Internal Server Error</h1>';
    if (ini_get('display_errors')) {
        echo '<pre>' . $e->getMessage() . "\n" . $e->getTraceAsString() . '</pre>';
    }
}
