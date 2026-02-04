# Laravel-Proyecto 🚀
Proyecto de aprendizaje con Laravel (API + panel). Este README explica cómo arrancar el proyecto desde cero, configurar el entorno con Docker, trabajar con la base de datos PostgreSQL y usar las APIs.

---

## 🧭 Requisitos previos
- Docker (v20+ y Docker Compose v2)
- Git
- Opcional local: Composer y Node.js (si prefieres ejecutar comandos fuera de los contenedores)

---

## 🛠️ Primeros pasos (rápido)
1. Clona el repositorio:

```bash
git clone https://github.com/Signusroot/Laravel-Proyecto.git
cd Laravel-Proyecto
```

2. Copia el archivo de entorno y ajusta variables según necesites:

```bash
cp .env.example .env
# Ajusta APP_URL, DB_* y otras variables en .env
```

3. Genera la llave de aplicación (desde host o dentro del contenedor):

```bash
# desde host (si tienes php y composer)
composer install
php artisan key:generate

# o dentro del contenedor de workspace
docker compose -f compose.dev.yaml exec workspace composer install
docker compose -f compose.dev.yaml exec workspace php artisan key:generate
```

---

## 🐳 Uso con Docker (desarrollo)
Se incluyen archivos `compose.dev.yaml` y `compose.prod.yaml`.

- Levantar entorno de desarrollo:

```bash
docker compose -f compose.dev.yaml build --no-cache
docker compose -f compose.dev.yaml up -d
```

- Alias útil (opcional en tu shell):

```bash
alias dc='docker compose -f compose.dev.yaml'
# Ejemplo:
dc up -d
```

- Comandos habituales dentro de los contenedores:

```bash
# Ejecutar migraciones y seeders
docker compose -f compose.dev.yaml exec php-fpm php artisan migrate --seed --force

# Ejecutar artisan
docker compose -f compose.dev.yaml exec php-fpm php artisan <comando>

# Instalar dependencias en workspace
docker compose -f compose.dev.yaml exec workspace composer install
docker compose -f compose.dev.yaml exec workspace npm install

# Ejecutar Vite (dev)
docker compose -f compose.dev.yaml exec workspace npm run dev

# Ejecutar tests
docker compose -f compose.dev.yaml exec workspace ./vendor/bin/pest
# o
docker compose -f compose.dev.yaml exec workspace php artisan test
```

---

## 🗄️ Base de datos (PostgreSQL)
Este proyecto usa PostgreSQL en los Docker Compose de desarrollo y producción.

- Variables importantes en `.env` (valores por defecto):

```
DB_CONNECTION=pgsql
DB_HOST=postgres
DB_PORT=5432
DB_DATABASE=app
DB_USERNAME=laravel
DB_PASSWORD=secret
```

- Acceso a la base de datos:

```bash
# Desde el servicio postgres (psql)
docker compose -f compose.dev.yaml exec postgres psql -U laravel -d app

# O usando psql desde workspace y apuntando a 'postgres' como host
docker compose -f compose.dev.yaml exec workspace psql -h postgres -U laravel -d app
```

- Reset y seed (seguro para desarrollo):

```bash
docker compose -f compose.dev.yaml exec php-fpm php artisan migrate:fresh --seed
```

- Persistencia: los volúmenes `postgres-data-development` y `postgres-data-production` almacenan los datos entre reinicios.

---

## 🔐 Autenticación y APIs
- Rutas API en `routes/api.php`.
- El proyecto incluye controladores para `products`, `sales` y un `AuthController` que usa **Laravel Sanctum**.

Ejemplo de endpoints:

- POST /api/login → iniciar sesión (recibir token)
- POST /api/logout → cerrar sesión (autenticado)
- GET /api/user → info del usuario (autenticado)
- GET/POST/PUT/DELETE /api/products → CRUD productos (apiResource)
- GET/POST/PUT/DELETE /api/sales → CRUD ventas (apiResource)

Uso básico con curl (login):

```bash
curl -X POST http://localhost/api/login -d '{"email":"user@example.com","password":"password"}' -H 'Content-Type: application/json'
```

Nota: protege las rutas con Sanctum; revisa `config/sanctum.php` si necesitas ajustar tokens/cookies.

---

## ✅ Comandos comunes (resumen)
- Levantar: `docker compose -f compose.dev.yaml up -d`
- Parar: `docker compose -f compose.dev.yaml down`
- Ejecutar migraciones: `docker compose -f compose.dev.yaml exec php-fpm php artisan migrate --seed`
- Ejecutar tests: `docker compose -f compose.dev.yaml exec workspace php artisan test`
- Logs: `docker compose -f compose.dev.yaml logs -f php-fpm`

---

## 🐞 Solución de problemas (rápida)
- Si la aplicación no conecta con la DB, revisa `DB_HOST` y que el servicio `postgres` esté en estado `healthy`.
- Permisos de archivos: revisa `UID` y `GID` en `.env` para evitar problemas con volúmenes.
- Si cambias dependencias, reconstruye la imagen: `docker compose -f compose.dev.yaml build --no-cache`.

---

## ✍️ Contribuciones
Fork, crea una rama y abre un Pull Request. Añade tests cuando corresponda.

---

## 📄 Licencia
MIT

---

Si quieres, puedo añadir ejemplos concretos de uso de la API o un script para inicialización automática (migrar + seed + crear usuario). ¿Te interesa que lo agregue? 💡

