# Setup Project

## Clone Project

Gunakan perintah:

`https://github.com/NgurahFajar/Damar-Exchange-Compro-BE.git`

Kemudian buka project di code editor

## Menjalankan Project

Install composer dan npm dengan perintah:

`npm i`

`composer i`

generate key laravel dengan perintah:

`php artisan key:generate`

migrasi schema tabel yang ada ke database dengan perintah:

`php artisan migrate`

jalankan aplikasi dengan perintah:

`php artisan serve`

Secara default aplikasi akan berjalan di http://localhost:8000/


## Menjalankan seeder ke database

`php artisan migrate:fresh --seed`

## Setup .env

Buat file baru .env

copy code ini:

```env
APP_NAME=Damar
APP_ENV=local
APP_KEY=
APP_DEBUG=true
APP_TIMEZONE=Asia/Jakarta
APP_URL=http://localhost:8000

APP_LOCALE=en
APP_FALLBACK_LOCALE=en
APP_FAKER_LOCALE=en_US

APP_MAINTENANCE_DRIVER=file
# APP_MAINTENANCE_STORE=database

PHP_CLI_SERVER_WORKERS=4

BCRYPT_ROUNDS=12

LOG_CHANNEL=stack
LOG_STACK=single
LOG_DEPRECATIONS_CHANNEL=null
LOG_LEVEL=debug

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=damar_exchange
DB_USERNAME=username anda
DB_PASSWORD=password anda

SESSION_DRIVER=database
SESSION_LIFETIME=120
SESSION_ENCRYPT=false
SESSION_PATH=/
SESSION_DOMAIN=null

BROADCAST_CONNECTION=log
QUEUE_CONNECTION=database

CACHE_STORE=database
CACHE_PREFIX=

MEMCACHED_HOST=127.0.0.1

REDIS_CLIENT=phpredis
REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379

AWS_ACCESS_KEY_ID=
AWS_SECRET_ACCESS_KEY=
AWS_DEFAULT_REGION=us-east-1
AWS_BUCKET=
AWS_USE_PATH_STYLE_ENDPOINT=false

VITE_APP_NAME="${APP_NAME}"

SESSION_SECURE_COOKIE=false
FRONTEND_URL=http://localhost:5173

TOKEN_PREFIX=damar_token_
TOKEN_EXPIRATION=1440
SANCTUM_TOKEN_EXPIRATION=1440

CORS_ALLOWED_ORIGINS=http://localhost:5173
SANCTUM_STATEFUL_DOMAINS=http://localhost:5173
FILESYSTEM_DISK=public
