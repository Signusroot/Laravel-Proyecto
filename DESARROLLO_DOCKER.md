# Guía de Desarrollo con Docker - Proyecto Laravel

## 📝 Cómo realizar cambios en el proyecto

### 1. Cambios en el código PHP (Controllers, Models, etc.)
Los cambios son **inmediatos** gracias a los volúmenes montados:

```bash
# Edita tus archivos localmente (en VS Code, editor, etc.)
# Los cambios se reflejan automáticamente en el contenedor
nano app/Http/Controllers/UserController.php
```

Los archivos están sincronizados en tiempo real gracias a:
```yaml
volumes:
  - ./:/var/www  # Todo tu proyecto está montado
```

---

### 2. Migraciones y cambios en la BD

```bash
# Crear una nueva migración
docker compose -f compose.dev.yaml exec workspace php artisan make:migration create_posts_table

# Ejecutar migraciones
docker compose -f compose.dev.yaml exec workspace php artisan migrate

# Revertir migraciones
docker compose -f compose.dev.yaml exec workspace php artisan migrate:rollback
```

---

### 3. Cambios en archivos de configuración (config/)

```bash
# Limpiar caché de configuración (los cambios en .env requieren esto)
docker compose -f compose.dev.yaml exec workspace php artisan config:clear

# También puedes limpiar otros cachés
docker compose -f compose.dev.yaml exec workspace php artisan cache:clear
docker compose -f compose.dev.yaml exec workspace php artisan view:clear
```

---

### 4. Instalar nuevos paquetes (Composer)

```bash
# Agregar un paquete
docker compose -f compose.dev.yaml exec workspace composer require laravel/sanctum

# O directamente en el contenedor
docker compose -f compose.dev.yaml exec php-fpm composer require laravel/sanctum
```

---

### 5. Cambios en Dockerfile o docker-compose

Si modificas el Dockerfile o compose.dev.yaml, necesitas **reconstruir**:

```bash
# Reconstruir la imagen
docker compose -f compose.dev.yaml build php-fpm

# Reiniciar los servicios
docker compose -f compose.dev.yaml up -d
```

---

### 6. Cambios en variables de entorno (.env)

```bash
# Edita el archivo .env localmente
nano .env

# Recarga la configuración
docker compose -f compose.dev.yaml exec workspace php artisan config:clear
```

---

### 7. Crear modelos, controladores, etc.

```bash
# Crear un modelo con migración
docker compose -f compose.dev.yaml exec workspace php artisan make:model Post -m

# Crear un controlador
docker compose -f compose.dev.yaml exec workspace php artisan make:controller PostController --resource

# Crear un request
docker compose -f compose.dev.yaml exec workspace php artisan make:request StorePostRequest
```

---

## 🔄 Flujo de desarrollo típico

1. **Edita archivos localmente** (tu editor favorito)
2. **Verifica cambios en el navegador** (http://localhost)
3. **Si necesitas ejecutar comandos Artisan**, usa:
   ```bash
   docker compose -f compose.dev.yaml exec workspace php artisan <comando>
   ```
4. **Si cambias dependencias o Dockerfile**, reconstruye:
   ```bash
   docker compose -f compose.dev.yaml build
   docker compose -f compose.dev.yaml up -d
   ```

---

## 💡 Atajos útiles

Para simplificar, puedes crear alias en tu terminal:

```bash
# En Linux/Mac ~/.zshrc o ~/.bashrc
# En Windows PowerShell: $PROFILE
alias dc='docker compose -f compose.dev.yaml'
alias artisan='docker compose -f compose.dev.yaml exec workspace php artisan'

# Luego usar:
artisan make:model Post -m
dc exec workspace npm run dev
```

---

## 🤝 Compartir el proyecto con otros desarrolladores

### Opción 1: Compartir el repositorio Git (RECOMENDADO)

Es la forma más profesional y limpia:

```bash
# 1. Sube tu proyecto a GitHub/GitLab/Bitbucket
git add .
git commit -m "Initial commit with Docker setup" -m "" -m "Assisted-By: cagent"
git push origin main

# 2. El otro desarrollador clona el repo
git clone https://github.com/tuusuario/proyecto.git
cd proyecto

# 3. Crea su propio archivo .env
cp .env.example .env

# 4. Construye y levanta los contenedores
docker compose -f compose.dev.yaml build
docker compose -f compose.dev.yaml up -d

# 5. Instala dependencias
docker compose -f compose.dev.yaml exec workspace composer install

# 6. Genera la clave de aplicación
docker compose -f compose.dev.yaml exec workspace php artisan key:generate

# 7. Ejecuta migraciones
docker compose -f compose.dev.yaml exec workspace php artisan migrate
```

**Ventajas:**
- ✅ Control de versiones
- ✅ Historial de cambios
- ✅ Cada dev tiene su entorno limpio
- ✅ Fácil sincronización de cambios

---

### Opción 2: Compartir la imagen Docker

#### 2A. Pushear a Docker Hub

```bash
# 1. Crear cuenta en Docker Hub (https://hub.docker.com)

# 2. Loguéate localmente
docker login

# 3. Construir la imagen con tu usuario
docker compose -f compose.dev.yaml build php-fpm
docker tag proyecto_php-fpm tusuario/proyecto-php-fpm:latest

# 4. Pushear a Docker Hub
docker push tusuario/proyecto-php-fpm:latest

# 5. Actualizar compose.dev.yaml
services:
  php-fpm:
    image: tusuario/proyecto-php-fpm:latest  # En lugar de build
```

#### 2B. Exportar imagen localmente

```bash
# 1. Guardar imagen como archivo
docker save proyecto_php-fpm > proyecto-php-fpm.tar

# 2. Enviar al otro dev (por email, Drive, etc.)

# 3. El otro dev carga la imagen
docker load < proyecto-php-fpm.tar

# 4. Levantar con compose
docker compose -f compose.dev.yaml up -d
```

**Desventajas:**
- ❌ Archivos muy grandes
- ❌ Difícil mantener sincronizado
- ❌ No hay control de versiones

---

### Configurar .gitignore correctamente

Lo más importante es que **no compartas ciertos archivos**:

```gitignore
# .gitignore
.env                 # Variables de entorno locales
.env.*.local
node_modules/
vendor/
storage/logs/
bootstrap/cache/
.DS_Store
.idea/
.vscode/

# Docker
docker/data/
docker/logs/
```

**Crea un template de .env:**

```bash
# .env.example (SÍ lo compartes)
APP_NAME=Laravel
APP_ENV=local
DB_CONNECTION=pgsql
DB_HOST=postgres
DB_PORT=5432
DB_DATABASE=app
DB_USERNAME=laravel
DB_PASSWORD=secret
REDIS_HOST=redis
UID=1000
GID=1000
```

---

### Checklist para compartir

```bash
# Archivos que DEBEN estar en el repo:
# ✅ compose.dev.yaml
# ✅ docker/
# ✅ .env.example
# ✅ DESARROLLO.md
# ✅ .gitignore

# Archivos que NO deben estar:
# ❌ .env
# ❌ vendor/
# ❌ node_modules/
# ❌ storage/logs/
```

---

## 🔄 ¿Cuándo necesito reconstruir la imagen?

### ❌ NO necesitas reconstruir (cambios inmediatos)

#### 1. Código PHP (Controllers, Models, Routes, etc.)
```bash
# Editas el archivo localmente
nano app/Http/Controllers/UserController.php

# ¡Listo! Se refleja automáticamente en el contenedor
# Gracias a los volúmenes montados:
volumes:
  - ./:/var/www
```

#### 2. Archivos de configuración (config/)
```bash
# Editas config/app.php o similar

# Solo limpia el caché
docker compose -f compose.dev.yaml exec workspace php artisan config:clear

# ¡No necesitas reconstruir!
```

#### 3. Variables de entorno (.env)
```bash
# Editas .env
nano .env

# Limpias el caché
docker compose -f compose.dev.yaml exec workspace php artisan config:clear

# ¡No necesitas reconstruir!
```

#### 4. Vistas Blade (.blade.php)
```bash
# Editas resources/views/welcome.blade.php

# Se actualizan automáticamente en el navegador
# Si no aparecen cambios:
docker compose -f compose.dev.yaml exec workspace php artisan view:clear
```

#### 5. Assets (CSS, JS, imágenes)
```bash
# Editas resources/css o resources/js

# Reconstruyes con Vite (sin reconstruir Docker)
docker compose -f compose.dev.yaml exec workspace npm run dev
```

#### 6. Base de datos (migraciones)
```bash
# Creas una migración
docker compose -f compose.dev.yaml exec workspace php artisan make:migration create_users_table

# Ejecutas la migración
docker compose -f compose.dev.yaml exec workspace php artisan migrate

# ¡Sin reconstruir imagen!
```

---

### ✅ SÍ necesitas reconstruir (cambios en estructura)

#### 1. Dockerfile (instalación de dependencias del sistema)
```dockerfile
# Si cambias esto en Dockerfile:
RUN apt-get install -y postgresql-client  # Nueva herramienta del sistema
RUN pecl install redis  # Nueva extensión PHP
```

```bash
# TIENES QUE reconstruir
docker compose -f compose.dev.yaml build php-fpm
docker compose -f compose.dev.yaml up -d
```

#### 2. composer.json (nuevos paquetes PHP)
```bash
# Si instalas un nuevo paquete
docker compose -f compose.dev.yaml exec workspace composer require laravel/sanctum

# Esto actualiza composer.lock automáticamente
# NO necesitas reconstruir si Composer está en el contenedor

# PERO si lo especificas en el Dockerfile:
# COPY composer.lock /var/www/
# RUN composer install
# ENTONCES sí necesitas reconstruir
```

#### 3. package.json (nuevos paquetes JavaScript)
```bash
# Nuevo paquete npm
docker compose -f compose.dev.yaml exec workspace npm install tailwindcss

# Actualiza package-lock.json automáticamente
# NO necesitas reconstruir la imagen Docker

# Solo si lo especificas en Dockerfile:
# RUN npm install
# ENTONCES sí necesitas reconstruir
```

#### 4. docker-compose.yaml (cambios de servicios)
```yaml
# Si añades un nuevo servicio o cambias puertos:
services:
  elasticsearch:
    image: docker.elastic.co/elasticsearch/elasticsearch:latest
```

```bash
# Necesitas levantar el nuevo servicio
docker compose -f compose.dev.yaml up -d elasticsearch
```

#### 5. Archivos de configuración Docker (entrypoint.sh, nginx.conf)
```bash
# Si cambias docker/common/php-fpm/entrypoint.sh
# O docker/development/nginx/nginx.conf

# Necesitas reconstruir
docker compose -f compose.dev.yaml build
docker compose -f compose.dev.yaml up -d
```

---

## 📊 Tabla de referencia rápida

| Tipo de cambio | ¿Reconstruir? | Comando |
|---|---|---|
| Controllers, Models, Routes | ❌ No | Cambio inmediato |
| .env, config/ | ❌ No | `artisan config:clear` |
| Vistas Blade | ❌ No | `artisan view:clear` |
| CSS, JS | ❌ No | `npm run dev` |
| Migraciones | ❌ No | `artisan migrate` |
| **Dockerfile** | ✅ **Sí** | `docker compose build` |
| **composer.json** (instalación) | ⚠️ Depende* | Ver abajo |
| **package.json** (instalación) | ⚠️ Depende* | Ver abajo |
| **docker-compose.yaml** | ✅ **Sí** | `docker compose up -d` |
| **nginx.conf, entrypoint.sh** | ✅ **Sí** | `docker compose build` |

*Depende de si lo especificas en el Dockerfile

---

## 💡 Mejores prácticas

### Para Composer (sin reconstruir):
```bash
# Instala en el contenedor directamente
docker compose -f compose.dev.yaml exec workspace composer require paquete/nuevo

# El composer.lock se actualiza automáticamente
# Otros devs pueden hacer:
docker compose -f compose.dev.yaml exec workspace composer install
```

### Para NPM (sin reconstruir):
```bash
# Instala en el contenedor
docker compose -f compose.dev.yaml exec workspace npm install paquete-nuevo

# El package-lock.json se actualiza
# Otros devs pueden hacer:
docker compose -f compose.dev.yaml exec workspace npm install
```

### Para cambios en Dockerfile:
```bash
# Editas el Dockerfile
nano docker/common/php-fpm/Dockerfile

# Reconstruyes la imagen
docker compose -f compose.dev.yaml build php-fpm
docker compose -f compose.dev.yaml up -d

# Verifica que esté correcto
docker compose -f compose.dev.yaml ps
```

---

## 🎯 Resumen

- **99% de los cambios en código**: ❌ NO reconstruyas
- **Cambios en instalaciones del sistema o dependencias base**: ✅ Sí reconstruye
- **Los volúmenes montados (`./:/var/www`) son mágicos**: Sincronizados en tiempo real

---

## 🆘 Comandos útiles de troubleshooting

```bash
# Ver logs de un servicio específico
docker compose -f compose.dev.yaml logs -f php-fpm

# Entrar al contenedor
docker compose -f compose.dev.yaml exec workspace bash

# Ver estado de todos los servicios
docker compose -f compose.dev.yaml ps

# Reiniciar un servicio específico
docker compose -f compose.dev.yaml restart php-fpm

# Detener todos los servicios
docker compose -f compose.dev.yaml down

# Detener y eliminar volúmenes
docker compose -f compose.dev.yaml down -v

# Reconstruir todo desde cero
docker compose -f compose.dev.yaml build --no-cache
docker compose -f compose.dev.yaml up -d
```
