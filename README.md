# Task Manager

## Descrição
API backend para gerenciamento de tarefas, com suporte a filtros por status e registro de logs em banco não-relacional.

## 💻 Pré-requisitos
* **Docker** `^24.0`
* **Docker Compose** `^2.0`

## 🐋 Instalação

1. Copie o arquivo .env
```bash
cp .env.example .env
```

2. Instalar dependências PHP
```bash
#Não é necessário ter PHP instalado localmente, pois o comando roda dentro de um container Docker.
docker run --rm \
    -u "$(id -u):$(id -g)" \
    -v $(pwd):/var/www/html \
    -w /var/www/html \
    laravelsail/php84-composer \
    composer install
```

3. Construa as imagens Docker
```bash
docker compose build
```

4. Inicie os containers
```bash
docker compose up -d
```

5. Acesse o container da aplicação
```bash
docker exec -it task-manager-app bash
```

6. Gere a chave do Laravel
```bash
php artisan key:generate
```

7. Execute as migrações
```bash
php artisan migrate
```

8. Acesse a aplicação
```bash
# Abra seu navegador e navegue para:
http://localhost:8080
```

9. Acesse a documentação
```bash
# Abra seu navegador e navegue para:
http://localhost:8000/api/documentation
```