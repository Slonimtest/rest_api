
# 📦 Laravel Prices API (Docker + MySQL + NGINX)

Проект на Laravel для работы с API цен (Prices API).  
Сборка и запуск осуществляются через Docker (PHP, MySQL, NGINX).

---

## ⚙️ Стек технологий

- **PHP 8.x** (Laravel)
- **MySQL 8.x**
- **NGINX**
- **Docker / Docker Compose**

---

## 🚀 Быстрый старт

### 1. Клонирование репозитория

```bash
git clone https://github.com/your-username/laravel-prices.git
cd laravel-prices
```

### 2. Создание .env

Скопируй `.env.example`:

```bash
cp .env.example .env
```

Пример содержимого `.env`:

```
APP_NAME=Laravel
APP_ENV=local
APP_KEY=
APP_DEBUG=true
APP_URL=http://localhost:8089

LOG_CHANNEL=stack
LOG_LEVEL=debug

DB_CONNECTION=mysql
DB_HOST=db
DB_PORT=3306
DB_DATABASE=prices_db
DB_USERNAME=laravel
DB_PASSWORD=secret
```

---

### 3. Запуск Docker-контейнеров

```bash
sudo docker compose up --build
```

#### Контейнеры:

- `laravel_app` — PHP + Laravel
- `laravel_mysql` — MySQL база данных
- `laravel_nginx` — NGINX веб-сервер

---

### 4. Миграции и сиды

При первом старте контейнер `laravel_app` автоматически выполнит:

- Миграции
- Сидеры

Если нужно вручную:

```bash
docker exec -it laravel_app bash

php artisan migrate --force
php artisan db:seed --force
```

---

## 📡 API Endpoints

Пример запроса к API после запуска:

```
GET http://localhost:8089/api/prices?currency=USD
```

Порты можно менять в `docker-compose.yml` и `.env`.

---

## API Документация Swagger
```
http://localhost:8089/api/documentation
```

## 🛠️ Работа с Базой Данных MySQL

### Подключение с хоста (если проброшен порт, например 3307):

```bash
mysql -h127.0.0.1 -P3307 -ularavel -psecret prices_db
```

### Или войти прямо в Docker-контейнер:

```bash
docker exec -it laravel_mysql bash
mysql -ularavel -psecret prices_db
```

---

## 🐳 Полезные команды Docker

```bash
# Список контейнеров
docker ps

# Войти внутрь контейнера с Laravel
docker exec -it laravel_app bash

# Перезапустить проект с пересборкой
sudo docker compose up --build

# Остановить и удалить контейнеры
docker compose down
```

---

## ❗️ Возможные ошибки

| Ошибка | Причина | Решение |
|------|------|------|
| `SQLSTATE[HY000] [2002] Connection refused` | Laravel не может подключиться к MySQL | Проверь DB_HOST, DB_PORT, доступность контейнера MySQL |
| `Permission denied: /var/www/storage/logs/laravel.log` | Нет прав на запись | Выполни: `docker exec -it laravel_app bash` → `chmod -R 777 storage` |

---

## 🧑‍💻 Автор

**Александр**  
Telegram: [@SlonimInvest](https://t.me/SlonimInvest)

---

## 📄 Лицензия

MIT
