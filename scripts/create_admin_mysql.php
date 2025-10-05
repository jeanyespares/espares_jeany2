<?php
/**
 * CLI helper: Create an admin user in the users table.
 * Usage: php scripts/create_admin_mysql.php
 * Automatically creates 'jeany' with password 'jeany21'
 */
chdir(dirname(__DIR__)); // run from repo root
if (!defined('PREVENT_DIRECT_ACCESS')) define('PREVENT_DIRECT_ACCESS', true);
require_once 'app/config/database.php';

if (!isset($database['main'])) {
    echo "Database config 'main' not found in app/config/database.php\n";
    exit(1);
}

$cfg = $database['main'];
if ($cfg['driver'] !== 'mysql') {
    echo "Only MySQL supported. Detected driver: {$cfg['driver']}\n";
    exit(1);
}

$dsn = sprintf('mysql:host=%s;port=%s;dbname=%s;charset=%s',
    $cfg['hostname'], $cfg['port'], $cfg['database'], $cfg['charset'] ?? 'utf8mb4'
);

try {
    $pdo = new PDO($dsn, $cfg['username'], $cfg['password'], [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);
} catch (Exception $e) {
    echo "DB connection failed: " . $e->getMessage() . "\n";
    exit(1);
}

$username = 'jeany';
$password = 'jeany21';
$hash = password_hash($password, PASSWORD_DEFAULT);

$stmt = $pdo->prepare('INSERT INTO users (username, password, role) VALUES (:username, :password, :role)');
try {
    $stmt->execute([':username' => $username, ':password' => $hash, ':role' => 'admin']);
    echo "✅ Admin user '{$username}' created successfully.\n";
} catch (PDOException $e) {
    echo "⚠️ Error inserting admin user: " . $e->getMessage() . "\n";
    exit(1);
}
