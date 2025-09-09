 Estrutura do Projeto
```
case.estuda/
├── app/                
│   ├── src/
│   ├── tests/          
│   └── composer.json
├── docker/             
│   ├── Dockerfile
│   └── docker-compose.yml
├── terraform/         
│   ├── main.tf
│   └── variables.tf
├── k8s/                
│   ├── deployment.yml
│   ├── service.yml
│   └── prometheus-grafana.yml
├── .github/workflows/  
│   └── ci-cd.yml
└── README.md
```

---

## Rodar Localmente (Docker Compose)
1. Clone o repositório:
   ```bash

2. Suba a aplicação com Docker Compose:
   ```bash
   docker-compose up -d
   ```
3. API disponível em:  
   👉 `http://localhost:8080`

4. Endpoints:  
   - **Registrar usuário**  
     `POST /users`  
     ```json
     {
       "name": "Fred",
       "email": "fred@email.com",
       "password": "123456"
     }
     ```
   - **Listar usuários**  
     `GET /users`

---

## ☁️ Deploy com Terraform
1. Configure suas credenciais de nuvem (AWS, GCP, Azure ou OCI).  
2. Ajuste o `variables.tf` com sua região e parâmetros.  
3. Execute:
 
   cd terraform
   terraform init
   terraform apply


Isso criará:  
 Uma VM para rodar a aplicação  
 Um banco MySQL gerenciado

---

## CI/CD (GitHub Actions)
Pipeline definido em `.github/workflows/ci-cd.yml`:  
1. **Build da imagem Docker**  
2. **Execução dos testes unitários**  
3. **Deploy automático** via Terraform

---

## Kubernetes (Minikube)
1. Inicie o Minikube:

   minikube start
 
2. Aplique os manifests:
   
   kubectl apply -f k8s/

3. Exponha a aplicação:
 
   minikube service case.estuda-service
 

---

##  Observabilidade
1. O projeto já possui métricas no endpoint `/metrics`.  
2. Deploy Prometheus + Grafana:
   ```bash
   kubectl apply -f k8s/prometheus-grafana.yml
   ```
3. Acesse o Grafana em:
   👉 `http://localhost:3000` (usuário: `admin` / senha: `VFdsdVlWTmxibk5vWVZObFozVnlaVEV5TXc9PQ==`)  
4. Dashboard exibe:  
   - Número de requisições por endpoint  
   - Latência média  

---

##  Testes Unitários
Rodar os testes:

docker exec -it case.estuda-app vendor/bin/phpunit tests



