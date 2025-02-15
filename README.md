# Pasos de instalación

## Clonar el respositorio 


```bash
git clone https://github.com/saulmoralespa/booking-laravel-vue.git
cd booking-laravel-vue
```

## Instalar dependencias

```bash
composer install
npm install
```

## Configurar arhivo .env

```bash
cp .env.example .env
```

Agregar variable de entorno VITE_BACKEND_URL=http://localhost Debe ser el mismo valor de APP_URL

## Compilar assets

```bash
npm run build
```

## Construir e iniciar containers de Docker

```bash
docker compose up -d --build
```

## Correr las migraciones
```bash
php artisan migrate:install
php artisan migrate
```

### Correr datos semillas
```bash
php artisan db:seed
```
