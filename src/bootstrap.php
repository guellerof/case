<?php
declare(strict_types=1);

use App\Repository\UserRepository;

require_once __DIR__ . '/../vendor/autoload.php';

$dsn = getenv('DB_DSN') ?: 'mysql:host=mysql;port=3306;dbname=userapi;charset=utf8mb4';
$dbUser = getenv('DB_USER') ?: 'root';
$dbPass = getenv('DB_PASS') ?: 'root';

try {
    $pdo = new PDO($dsn, $dbUser, $dbPass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'DB connection failed', 'msg' => $e->getMessage()]);
    exit;
}

$userRepo = new UserRepository($pdo);
