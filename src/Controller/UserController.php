<?php
namespace App\Controller;

use App\Repository\UserRepository;
use Prometheus\CollectorRegistry;
use Prometheus\RenderTextFormat;
use Prometheus\Storage\InMemory;

class UserController {
    private UserRepository $repo;
    private CollectorRegistry $registry;

    public function __construct(UserRepository $repo) {
        $this->repo = $repo;
        $this->registry = new CollectorRegistry(new InMemory());
        $this->registry->getOrRegisterCounter('app', 'requests_total', 'Total requests', ['endpoint']);
    }

    public function register(array $data) {
        $this->repo->createTableIfNotExists();

        if (empty($data['name']) || empty($data['email']) || empty($data['password'])) {
            http_response_code(400);
            echo json_encode(['error'=>'name, email and password required']);
            return;
        }

        if ($this->repo->findByEmail($data['email'])) {
            http_response_code(409);
            echo json_encode(['error'=>'email already registered']);
            return;
        }

        $hash = password_hash($data['password'], PASSWORD_DEFAULT);
        $id = $this->repo->addUser($data['name'], $data['email'], $hash);

        $this->registry->getOrRegisterCounter('app','requests_total','Total requests',['endpoint'])
            ->inc(['POST /users']);

        http_response_code(201);
        echo json_encode(['id'=>$id, 'name'=>$data['name'], 'email'=>$data['email']]);
    }

    public function list() {
        $users = $this->repo->listUsers();
        $this->registry->getOrRegisterCounter('app','requests_total','Total requests',['endpoint'])
            ->inc(['GET /users']);
        echo json_encode($users);
    }

    public function metrics() {
        $renderer = new RenderTextFormat();
        $result = $renderer->render($this->registry->getMetricFamilySamples());
        header('Content-Type: ' . RenderTextFormat::MIME_TYPE);
        echo $result;
    }
}
