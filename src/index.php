<?php
declare(strict_types=1);

require_once __DIR__ . '/bootstrap.php';

use App\Controller\UserController;

header('Content-Type: application/json');

$controller = new UserController($userRepo);

$method = $_SERVER['REQUEST_METHOD'];
$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

if ($path === '/users' && $method === 'POST') {
    $payload = json_decode(file_get_contents('php://input'), true) ?: [];
    $controller->register($payload);
    exit;
}

if ($path === '/users' && $method === 'GET') {
    $controller->list();
    exit;
}

if ($path === '/metrics' && $method === 'GET') {
    $controller->metrics();
    exit;
}

http_response_code(404);
echo json_encode(['error'=>'not_found']);
