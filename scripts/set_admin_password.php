<?php
// CLI helper: Update or create the admin user 'jeany' with password 'jeany21'
chdir(dirname(__DIR__));
if (!defined('PREVENT_DIRECT_ACCESS')) define('PREVENT_DIRECT_ACCESS', true);
require_once 'app/config/database.php';

if (!isset($database['main'])) {
    echo "Database config 'main' not found\n";
    exit(1);
}

$cfg = $database['main'];
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

$stmt = $pdo->prepare('SELECT id FROM users WHERE username = :username LIMIT 1');
$stmt->execute([':username' => $username]);
$row = $stmt->fetch(PDO::FETCH_ASSOC);

if ($row) {
    $upd = $pdo->prepare('UPDATE users SET password = :password WHERE id = :id');
    $upd->execute([':password' => $hash, ':id' => $row['id']]);
    echo "✅ Updated password for '{$username}'.\n";
} else {
    $ins = $pdo->prepare('INSERT INTO users (username, password, role) VALUES (:username, :password, :role)');
    $ins->execute([':username' => $username, ':password' => $hash, ':role' => 'admin']);
    echo "✅ Created new admin user '{$username}'.\n";
}
