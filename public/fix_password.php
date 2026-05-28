<?php
// TEMPORARY FIX FILE - DELETE AFTER USE

require_once dirname(__DIR__) . '/autoload.php';
require_once dirname(__DIR__) . '/app/Helpers/helpers.php';

$config = require dirname(__DIR__) . '/config/database.php';
$db_config = $config['connections']['mysql'];

try {
    $pdo = new PDO(
        "mysql:host={$db_config['host']};dbname={$db_config['database']};charset=utf8mb4",
        $db_config['username'],
        $db_config['password']
    );

    $hash = password_hash('Admin@1234', PASSWORD_BCRYPT, ['cost' => 12]);

    $stmt = $pdo->prepare("UPDATE users SET password = ? WHERE username = 'superadmin'");
    $stmt->execute([$hash]);

    echo "<h2 style='color:green;font-family:sans-serif'>✅ Password fixed successfully!</h2>";
    echo "<p style='font-family:sans-serif'>Username: <b>superadmin</b><br>Password: <b>Admin@1234</b></p>";
    echo "<p style='font-family:sans-serif'><a href='../login'>Go to Login</a></p>";
    echo "<p style='color:red;font-family:sans-serif'><b>⚠️ DELETE this file now: public/fix_password.php</b></p>";

} catch (Exception $e) {
    echo "<h2 style='color:red'>Error: " . $e->getMessage() . "</h2>";
}
