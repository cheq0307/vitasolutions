# VitaSolutions — Sistema de Gestión de Salud Nutraceutica

## Instalación

```bash
# Ya hecho hasta aquí:
# composer create-project laravel/laravel vitasolutions
# composer require livewire/livewire
# composer require laravel/breeze --dev
# php artisan breeze:install blade

# Siguiente paso:
npm install && npm run build

# Luego:
cp .env.example .env
php artisan key:generate
```

### .env mínimo
```
APP_NAME=VitaSolutions
APP_URL=http://localhost/vitasolutions/public

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=vitasolutions
DB_USERNAME=root
DB_PASSWORD=

FILESYSTEM_DISK=local
```

```bash
# Crear DB "vitasolutions" en phpMyAdmin, luego:
php artisan migrate --seed
php artisan serve
```

## Módulos

| Módulo | Ruta | Acceso |
|---|---|---|
| Dashboard admin | `/admin/dashboard` | Admin |
| Clientes | `/admin/clientes` | Admin |
| Perfil de salud | `/perfil-salud` | Cliente + Admin |
| Mediciones | `/mediciones` | Cliente + Admin |
| Suplementos | `/protocolos` | Admin asigna, cliente ve |
| Cuestionarios | `/cuestionarios` | Cliente |
| Archivos | `/archivos` | Cliente sube, admin ve |
