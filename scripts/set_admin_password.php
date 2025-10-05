<?php
// Usage: php scripts/set_admin_password.php password
chdir(dirname(__DIR__));
if (!defined('PREVENT_DIRECT_ACCESS')) define('PREVENT_DIRECT_ACCESS', true);
require_once 'app/config/database.php';

$argv0 = $argv[0] ?? '';
if ($argc < 2) {
    echo "Usage: php {$argv0} new_password\n";
    exit(1);
}

$password = $argv[1];
if (!isset($database['main'])) {
    echo "Database config 'main' not found\n";
    exit(1);
}

$cfg = $database['main'];
$dsn = sprintf('mysql:host=%s;port=%s;dbname=%s;charset=%s', $cfg['hostname'], $cfg['port'], $cfg['database'], $cfg['charset'] ?? 'utf8mb4');
try {
    $pdo = new PDO($dsn, $cfg['username'], $cfg['password'], [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
} catch (Exception $e) {
    echo "DB connection failed: " . $e->getMessage() . "\n";
    exit(1);
}

$hash = password_hash($password, PASSWORD_DEFAULT);

// If admin exists, update. Otherwise insert.
$stmt = $pdo->prepare('SELECT id FROM users WHERE username = :username LIMIT 1');
$stmt->execute([':username' => 'admin']);
$row = $stmt->fetch(PDO::FETCH_ASSOC);
if ($row) {
    $upd = $pdo->prepare('UPDATE users SET password = :password WHERE id = :id');
    $upd->execute([':password' => $hash, ':id' => $row['id']]);
    echo "Updated admin password for user 'admin'.\n";
} else {
    $ins = $pdo->prepare('INSERT INTO users (username, password, role) VALUES (:username, :password, :role)');
    $ins->execute([':username' => 'admin', ':password' => $hash, ':role' => 'admin']);
    echo "Created admin user 'admin'.\n";
}

exit(0);
