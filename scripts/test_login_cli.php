<?php
// allow including config files that protect against direct access
if (!defined('PREVENT_DIRECT_ACCESS')) define('PREVENT_DIRECT_ACCESS', true);
require_once __DIR__ . '/../app/config/database.php';

$conf = $database['main'];

$dsn = sprintf('%s:host=%s;port=%s;dbname=%s;charset=%s', $conf['driver'], $conf['hostname'], $conf['port'], $conf['database'], $conf['charset']);

try {
    $pdo = new PDO($dsn, $conf['username'], $conf['password'], [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
} catch (Exception $e) {
    echo "DB connection failed: " . $e->getMessage() . PHP_EOL;
    exit(1);
}

$username = $argv[1] ?? 'admin';
$password = $argv[2] ?? 'admin123';

$stmt = $pdo->prepare('SELECT * FROM users WHERE username = ? LIMIT 1');
$stmt->execute([$username]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    echo "User not found\n";
    exit(2);
}

if (!isset($user['password'])) {
    echo "No password column on user record\n";
    exit(3);
}

if (password_verify($password, $user['password'])) {
    echo "Login OK for user: " . $user['username'] . " (role=" . ($user['role'] ?? 'n/a') . ")\n";
    exit(0);
} else {
    echo "Invalid password\n";
    exit(4);
}

?>
