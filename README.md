# 🎵 Laravel API - Tião Carreiro & Pardinho Songs

Este projeto é uma API RESTful em Laravel para listagem de músicas da dupla **Tião Carreiro & Pardinho**, incluindo sugestões de novos vídeos via YouTube, autenticação com Sanctum, permissões administrativas, ordenações e filtros avançados.

---

## 📦 Tecnologias

- Laravel 10
- Laravel Sail (Docker)
- Sanctum (autenticação)
- MySQL
- Nginx + PHP 8.3-FPM

---

## ⚙️ Pré-requisitos

- [Docker](https://www.docker.com/)
- [WSL 2 (caso esteja no Windows)](https://learn.microsoft.com/pt-br/windows/wsl/install)
- [Git](https://git-scm.com/)

---

## 🚀 Subindo o projeto

```bash
# Clone o repositório
git clone https://github.com/seu-usuario/seu-repo.git
cd seu-repo

# Copie o arquivo de ambiente
cp .env.example .env

# Suba os containers com Laravel Sail
./vendor/bin/sail up -d

# Instale as dependências do projeto
./vendor/bin/sail composer install

# Gere a key da aplicação
./vendor/bin/sail artisan key:generate

# Execute as migrations
./vendor/bin/sail artisan migrate
```

A aplicação estará disponível em: [http://localhost:8080](http://localhost:8080)

---

## 🔐 Autenticação

O projeto utiliza **Laravel Sanctum**. Para autenticar:

1. Crie um usuário via endpoint ou seeder
2. Faça login (endpoint `/login` se implementado)
3. Utilize o token Bearer nos headers das requisições protegidas:

```http
Authorization: Bearer {seu_token}
```

---

## 👑 Middleware de Admin

Adicionamos um middleware chamado `is_admin`. Ele verifica o atributo `is_admin` no modelo `User`.

---

## 📂 Estrutura de Migrations

### ✅ `songs` table

```php
Schema::create('songs', function (Blueprint $table) {
    $table->id();
    $table->string('title');
    $table->string('youtube_link');
    $table->unsignedBigInteger('plays')->default(0); // campo para top músicas
    $table->boolean('is_active')->default(true);
    $table->timestamps();
});
```

### ✅ `suggestions` table

```php
Schema::create('suggestions', function (Blueprint $table) {
    $table->id();
    $table->foreignId('user_id')->constrained()->onDelete('cascade');
    $table->string('youtube_link');
    $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
    $table->timestamps();
});
```

---

## 🔌 Endpoints da API

### 🎵 Songs

| Método | Rota                 | Ação                       | Autenticação | Admin apenas |
|--------|----------------------|----------------------------|--------------|--------------|
| `GET`  | `/api/songs`         | Listar músicas             | ❌ Não       | ❌ Não       |
| `GET`  | `/api/songs/top`     | Listar 5 mais tocadas      | ❌ Não       | ❌ Não       |
| `POST` | `/api/songs`         | Criar música               | ✅ Sim       | ✅ Sim       |
| `PATCH`| `/api/songs/{id}`    | Atualizar música           | ✅ Sim       | ✅ Sim       |
| `DELETE`| `/api/songs/{id}`   | Deletar música             | ✅ Sim       | ✅ Sim       |

#### 🧭 Parâmetros suportados em `/api/songs`

- Paginação: `?page={n}`
- Ordenação: `?sort_by=${n}|created_at&sort_dir=asc|desc`
- Filtros:
  - `search=pagode`
  - `is_active=true|false`

> Exemplo: `/api/songs?search=moda&sort_by=plays&sort_dir=desc&page=2`

---

### 💡 Suggestions

| Método | Rota                                | Ação                        | Autenticação | Admin apenas |
|--------|-------------------------------------|-----------------------------|--------------|--------------|
| `GET`  | `/api/suggestions`                  | Listar sugestões            | ✅ Sim       | ❌ Não       |
| `POST` | `/api/suggestions`                  | Enviar sugestão             | ✅ Sim       | ❌ Não       |
| `PATCH`| `/api/suggestions/{id}/approve`     | Aprovar sugestão            | ✅ Sim       | ✅ Sim       |
| `PATCH`| `/api/suggestions/{id}/reject`      | Rejeitar sugestão           | ✅ Sim       | ✅ Sim       |
| `PATCH`| `/api/suggestions/{id}`             | Editar sugestão             | ✅ Sim       | ✅ Sim       |
| `DELETE`| `/api/suggestions/{id}`            | Deletar sugestão            | ✅ Sim       | ✅ Sim       |

#### 🧭 Parâmetros suportados em `/api/suggestions`

- Paginação: `?page={n}`
- Ordenação: `?sort_by=${n}|created_at&sort_dir=asc|desc`
- Filtros:
  - `status=pending|approved|rejected`

> Exemplo: `/api/suggestions?status=pending&sort_by=created_at&sort_dir=desc`

---

### 👤 User

| Método | Rota         | Descrição                                    |
|--------|--------------|----------------------------------------------|
| GET    | `/api/user`  | Dados do usuário logado (token necessário)   |

---

## 🧪 Testando com Insomnia/Postman

1. Crie um usuário
2. Faça login e receba um token
3. Nas rotas protegidas, envie o token no header:

```http
Authorization: Bearer {seu_token}
```

---

## 📖 Exemplos de uso

### Criar uma música

```http
POST /api/songs
Authorization: Bearer {token}
Content-Type: application/json

{
  "title": "Pagode em Brasília",
  "youtube_link": "https://youtube.com/watch?v=123"
}
```

### Listar músicas com filtro e ordenação

```http
GET /api/songs?search=moda&sort_by=plays&sort_dir=desc&page=1
```

### Top 5 músicas mais tocadas

```http
GET /api/songs/top
```

### Sugerir nova música

```http
POST /api/suggestions
Authorization: Bearer {token}
Content-Type: application/json

{
  "youtube_link": "https://youtube.com/watch?v=abc123"
}
```

### Atualizar sugestão

```http
PATCH /api/suggestions/3
Authorization: Bearer {token}
Content-Type: application/json

{
  "youtube_link": "https://youtube.com/watch?v=456def"
}
```

---

## 👨‍💻 Autor

Feito com ❤️ por [Seu Nome](https://github.com/seu-usuario)

---

## 📝 Licença

Este projeto está licenciado sob a [MIT License](LICENSE).