# API - Gerenciamento de usuário

Projeto simples feito em Laravel 11 para gerenciamento de usuários, cadastro, listagem, atualização, busca e deleção.

## Pré-requisitos

- Docker

## Instalação

1. Clone o repository:

```sh
  git clone https://github.com/Go-Ingressos/going_back_v2.git
  cd going_back_v2
```

2. Rode docker compose

```sh
  docker compose up -d
```

3. Verifique se os containers estão rodando corretamente

```sh
  docker ps
```

Você deve ver algo parecido com:

```sh 
CONTAINER ID   IMAGE                  COMMAND                  CREATED        STATUS          PORTS                                      NAMES
78044bfc55c9   manage-users-api-api   "docker-php-entrypoi…"   39 hours ago   Up 14 minutes   9000/tcp                                   api
759b03655ea3   nginx:alpine           "/docker-entrypoint.…"   39 hours ago   Up 14 minutes   0.0.0.0:80->80/tcp, 0.0.0.0:443->443/tcp   api_server
94d869f8904c   mysql:8.0              "docker-entrypoint.s…"   39 hours ago   Up 3 minutes    0.0.0.0:3306->3306/tcp, 33060/tcp          database
```

4. Configure o .env

```ssh
  cp .env.example .env
```

Já deixei o .env.example com as credências de desenvolvimento para facilitar o testes e as conexões são locais, então não têm risco.

5. Acesse o container da aplicação

```ssh
  docker exec -it api bash
```

6. Instale as dependencias

```ssh
  composer install
```

7. Gere a chave da applicação

```ssh
  php artisan key:generate
```

8. Utilize o endpoint base para acessar o recursos

[localhost](http://localhost)