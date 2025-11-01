# ComerX API

![CI](https://github.com/aglv88/comerx-api/workflows/CI/badge.svg)
![PHP Version](https://img.shields.io/badge/php-%5E8.2-blue)
![Laravel Version](https://img.shields.io/badge/laravel-%5E12.0-red)
![License](https://img.shields.io/badge/license-MIT-green)

API REST desenvolvida em Laravel para o sistema ComerX.

## 🚀 Tecnologias

- **Laravel 12** - Framework PHP
- **JWT Auth** - Autenticação via JSON Web Tokens
- **MySQL 8.0** - Banco de dados
- **Pest** - Framework de testes
- **Laravel Pint** - Code style
- **Scramble** - Documentação automática da API
- **Spatie Packages**:
  - Laravel Permission - Gerenciamento de roles e permissões
  - Laravel Activity Log - Log de atividades

## 📋 Pré-requisitos

- PHP >= 8.2
- Composer
- MySQL >= 8.0
- Node.js >= 18 (para assets)
- Docker (opcional, via Laravel Sail)

## 🔧 Instalação

### Clone o repositório

```bash
git clone https://github.com/seu-usuario/comerx-api.git
cd comerx-api
```

### Com Docker (Laravel Sail)

```bash
# Instalar dependências
docker run --rm \
    -u "$(id -u):$(id -g)" \
    -v "$(pwd):/var/www/html" \
    -w /var/www/html \
    laravelsail/php82-composer:latest \
    composer install --ignore-platform-reqs

# Copiar arquivo de ambiente
cp .env.example .env

# Subir containers
./vendor/bin/sail up -d

# Gerar chave da aplicação
./vendor/bin/sail artisan key:generate

# Gerar chave JWT
./vendor/bin/sail artisan jwt:secret

# Rodar migrations
./vendor/bin/sail artisan migrate

# Instalar assets
./vendor/bin/sail npm install
./vendor/bin/sail npm run build
```

### Sem Docker

```bash
# Instalar dependências
composer install

# Copiar arquivo de ambiente
cp .env.example .env

# Configurar banco de dados no .env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=comerx
DB_USERNAME=seu_usuario
DB_PASSWORD=sua_senha

# Gerar chave da aplicação
php artisan key:generate

# Gerar chave JWT
php artisan jwt:secret

# Rodar migrations
php artisan migrate

# Instalar assets
npm install
npm run build
```

## 🧪 Testes

```bash
# Com Sail
./vendor/bin/sail test

# Sem Sail
php artisan test

# Com coverage
./vendor/bin/sail test --coverage
```

## 🎨 Code Style

```bash
# Verificar código
./vendor/bin/pint --test

# Corrigir código automaticamente
./vendor/bin/pint
```

## 📚 Documentação da API

A documentação completa da API está disponível via Scramble:

```
http://localhost/docs/api
```

### Endpoints principais

#### Autenticação

```http
POST /api/auth/login
Content-Type: application/json

{
    "username": "usuario",
    "password": "senha123"
}
```

```http
POST /api/auth/logout
Authorization: Bearer {token}
```

```http
POST /api/auth/refresh
Authorization: Bearer {token}
```

```http
GET /api/auth/me
Authorization: Bearer {token}
```

## 🔐 Autenticação

A API utiliza JWT (JSON Web Tokens) para autenticação. Após o login, inclua o token no header de todas as requisições protegidas:

```
Authorization: Bearer {seu-token-jwt}
```

## 📦 Estrutura do Projeto

```
comerx-api/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   └── Api/
│   │   │       └── AuthController.php
│   │   ├── Requests/
│   │   │   └── LoginRequest.php
│   │   └── Resources/
│   │       └── UserResource.php
│   └── Models/
│       └── User.php
├── database/
│   ├── factories/
│   ├── migrations/
│   └── seeders/
├── routes/
│   └── api.php
├── tests/
│   └── Feature/
│       └── AuthTest.php
└── .github/
    └── workflows/
        └── ci.yml
```

## 🛠️ Comandos Úteis

```bash
# Ver logs em tempo real
./vendor/bin/sail artisan pail

# Limpar cache
./vendor/bin/sail artisan optimize:clear

# Gerar IDE Helper
./vendor/bin/sail artisan ide-helper:generate

# Ver rotas
./vendor/bin/sail artisan route:list
```

## 🚢 Deploy

### Preparação para produção

```bash
# Otimizar autoload
composer install --optimize-autoloader --no-dev

# Cachear configurações
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Build assets
npm run build
```

### Variáveis de ambiente importantes

```env
APP_ENV=production
APP_DEBUG=false
APP_KEY=base64:...
APP_URL=https://api.comerx.com

DB_CONNECTION=mysql
DB_HOST=seu-host
DB_DATABASE=comerx
DB_USERNAME=usuario
DB_PASSWORD=senha

JWT_SECRET=sua-chave-jwt-secreta
JWT_ALGO=HS256
```

## 🤝 Contribuindo

1. Fork o projeto
2. Crie uma branch para sua feature (`git checkout -b feature/MinhaFeature`)
3. Commit suas mudanças (`git commit -m 'feat: adiciona nova feature'`)
4. Push para a branch (`git push origin feature/MinhaFeature`)
5. Abra um Pull Request

### Padrões de commit

Seguimos o padrão [Conventional Commits](https://www.conventionalcommits.org/):

- `feat:` Nova funcionalidade
- `fix:` Correção de bug
- `docs:` Documentação
- `style:` Formatação
- `refactor:` Refatoração de código
- `test:` Testes
- `chore:` Tarefas de manutenção

## 📝 Licença

Este projeto está sob a licença MIT. Veja o arquivo [LICENSE](LICENSE) para mais detalhes.
