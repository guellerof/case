

## Rodando localmente (Docker Compose)
1. `docker-compose up --build`
2. API disponível em `http://localhost:9000`
   - `POST /users` (body JSON: `{ "name": "X", "email": "x@x.com", "password": "123" }`)
   - `GET /users`
   - `GET /metrics`

## Testes
```bash
composer install
./vendor/bin/phpunit
```

## Estrutura
- PHP API simples (PDO + PHPUnit)
- Docker + docker-compose
- Terraform para provisionamento 
- GitHub Actions para CI/CD
- Kubernetes manifests (Minikube)
- Observabilidade: Prometheus + Grafana
