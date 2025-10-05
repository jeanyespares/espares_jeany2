<?php
/**
 * CLI helper: Create an admin user in the `users` table.
 * Usage (PowerShell):
 *   php scripts\create_admin_mysql.php username password
 *
 * This script reads DB config from app/config/database.php (the 'main' connection)
 * and inserts a new user with a bcrypt-hashed password and role 'admin'.
 */
chdir(dirname(__DIR__)); // run from repo root

// Some config files in the app guard direct access with PREVENT_DIRECT_ACCESS.
// Define it here so the CLI helper can load those config files.
if (!defined('PREVENT_DIRECT_ACCESS')) define('PREVENT_DIRECT_ACCESS', true);

require_once 'app/config/database.php';

$argv0 = $argv[0] ?? '';
if ($argc < 3) {
    echo "Usage: php {$argv0} username password\n";
    exit(1);
}

$username = $argv[1];
$password = $argv[2];

if (!isset($database['main'])) {
    echo "Database config 'main' not found in app/config/database.php\n";
    exit(1);
}

$cfg = $database['main'];
if ($cfg['driver'] !== 'mysql') {
    echo "This helper currently only supports MySQL. Detected driver: {$cfg['driver']}\n";
    exit(1);
}

$dsn = sprintf('mysql:host=%s;port=%s;dbname=%s;charset=%s', $cfg['hostname'], $cfg['port'], $cfg['database'], $cfg['charset'] ?? 'utf8mb4');
try {
    $pdo = new PDO($dsn, $cfg['username'], $cfg['password'], [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
} catch (Exception $e) {
    echo "DB connection failed: " . $e->getMessage() . "\n";
    exit(1);
}

// Basic users table schema expected:
// CREATE TABLE users (
//   id INT AUTO_INCREMENT PRIMARY KEY,
//   username VARCHAR(150) NOT NULL UNIQUE,
//   password VARCHAR(255) NOT NULL,
//   role VARCHAR(50) DEFAULT 'user',
//   created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
// );

$hash = password_hash($password, PASSWORD_DEFAULT);

$stmt = $pdo->prepare('INSERT INTO users (username, password, role) VALUES (:username, :password, :role)');
try {
    $stmt->execute([':username' => $username, ':password' => $hash, ':role' => 'admin']);
    echo "Admin user '{$username}' created successfully.\n";
} catch (PDOException $e) {
    echo "Error inserting admin user: " . $e->getMessage() . "\n";
    exit(1);
}

exit(0);
