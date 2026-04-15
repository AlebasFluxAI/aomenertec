# Guía de Configuración en Windows

Esta guía documenta los pasos necesarios para ejecutar el proyecto Aomenertec en Windows usando Docker Desktop.

## Requisitos Previos

- **Windows 10/11** con WSL2 (recomendado) o PowerShell
- **Docker Desktop for Windows** instalado y corriendo
- **Git** instalado
- Al menos **4 GB de RAM** asignados a Docker Desktop

---

## Configuración de Docker Desktop

1. Abre **Docker Desktop**
2. Ve a **Settings → Resources → Advanced**
3. Configura:
   - **Memory:** Mínimo 4 GB
   - **CPUs:** Mínimo 2
4. **Apply & Restart**

---

## Instalación Inicial

### 1. Clonar el Repositorio

```powershell
git clone <url-del-repositorio>
cd aomenertec
```

### 2. Copiar el Archivo de Entorno

```powershell
# Si no existe .env, crearlo desde .env.example
Copy-Item .env.example .env -ErrorAction SilentlyContinue
```

Verifica que el `.env` tenga estas credenciales de base de datos:

```env
DB_CONNECTION=pgsql
DB_HOST=pgsql
DB_PORT=5432
DB_DATABASE=enertec
DB_USERNAME=sail
DB_PASSWORD=password
```

### 3. Preparar el Dockerfile Correcto

El proyecto tiene múltiples Dockerfiles. Asegúrate de usar el optimizado:

```powershell
# Hacer backup del Dockerfile actual
Copy-Item "docker\Dockerfile" "docker\Dockerfile.bak" -Force -ErrorAction SilentlyContinue

# Usar el Dockerfile optimizado
Copy-Item "docker\Dockerfile.optimized" "docker\Dockerfile" -Force
```

### 4. Construir las Imágenes Docker

```powershell
docker-compose build --no-cache
```

**Nota:** Este proceso puede tardar 10-15 minutos la primera vez.

---

## Iniciar el Proyecto

### 1. Iniciar los Servicios Docker

```powershell
docker-compose up -d
```

Esto inicia:
- Laravel (Puerto 80)
- PostgreSQL (Puerto 5432)
- Redis (Puerto 6379)
- Mosquitto MQTT (Puerto 1883)
- Laravel Echo Server (Puerto 8443)

### 2. Esperar a que los Servicios Estén Listos

```powershell
# Esperar 30 segundos
Start-Sleep -Seconds 30

# Verificar que los contenedores estén corriendo
docker-compose ps
```

Todos los servicios deberían mostrar estado `Up`.

### 3. Instalar Dependencias de Composer

```powershell
docker-compose exec laravel.test composer install --no-interaction --prefer-dist
```

### 4. Configurar la Aplicación

```powershell
# Generar APP_KEY
docker-compose exec laravel.test php artisan key:generate

# Configurar permisos
docker-compose exec laravel.test chmod -R 777 storage bootstrap/cache
```

### 5. Ejecutar Migraciones y Seeders

```powershell
docker-compose exec laravel.test php artisan migrate:fresh --seed --force
```

**Nota:** Este comando:
- Elimina todas las tablas existentes
- Ejecuta todas las migraciones
- Carga datos de prueba

### 6. Instalar Dependencias NPM (Opcional)

```powershell
docker-compose exec laravel.test npm install
docker-compose exec laravel.test npm run dev
```

---

## Acceder a la Aplicación

### URL Principal
```
http://localhost
```

### Credenciales de Acceso

**Super Administrador:**
- Email: `support@fluxai.solutions`
- Contraseña: `Flux@i2026!Secure`

**Otros Usuarios de Prueba:**
| Tipo | Email | Contraseña |
|------|-------|------------|
| Administrador | `adminprueba@enerteclatam.com` | `666666662` |
| Operador de Red | `patrocinadorprueba@enerteclatam.com` | `999999994` |
| Vendedor | `vendedorprueba@enerteclatam.com` | `999999117` |
| Técnico | `tecnicoprueba@enerteclatam.com` | `2299999910` |

**Importante:** La contraseña por defecto de cada usuario es su **número de identificación**.

---

## Comandos Útiles

### Ver Logs en Tiempo Real

```powershell
# Todos los servicios
docker-compose logs -f

# Solo Laravel
docker-compose logs -f laravel.test

# Solo PostgreSQL
docker-compose logs -f pgsql

# Solo MQTT
docker-compose logs -f mosquitto
```

### Ejecutar Comandos Artisan

```powershell
# Formato general
docker-compose exec laravel.test php artisan <comando>

# Ejemplos
docker-compose exec laravel.test php artisan cache:clear
docker-compose exec laravel.test php artisan config:clear
docker-compose exec laravel.test php artisan migrate
```

### Acceder al Shell del Contenedor

```powershell
# Shell de Laravel
docker-compose exec laravel.test bash

# Shell de PostgreSQL
docker-compose exec pgsql psql -U sail -d enertec

# Tinker (consola interactiva de Laravel)
docker-compose exec laravel.test php artisan tinker
```

### Detener los Servicios

```powershell
# Detener sin eliminar contenedores
docker-compose stop

# Detener y eliminar contenedores
docker-compose down

# Detener y eliminar TODO (contenedores, redes, volúmenes)
docker-compose down -v
```

---

## Solución de Problemas Comunes

### Problema 1: "npm: not found" durante el build

**Causa:** Está usando el Dockerfile incorrecto.

**Solución:**
```powershell
Copy-Item "docker\Dockerfile.optimized" "docker\Dockerfile" -Force
docker-compose build --no-cache
```

---

### Problema 2: Error de autenticación con PostgreSQL

**Causa:** El volumen de PostgreSQL tiene credenciales antiguas.

**Solución:**
```powershell
docker-compose down -v
docker-compose up -d
Start-Sleep -Seconds 30
docker-compose exec laravel.test php artisan migrate:fresh --seed --force
```

---

### Problema 3: "vendor/autoload.php not found"

**Causa:** Faltan las dependencias de Composer.

**Solución:**
```powershell
docker-compose exec laravel.test composer install
docker-compose restart laravel.test
```

---

### Problema 4: La aplicación no carga (página en blanco)

**Solución:**
```powershell
# Limpiar cachés
docker-compose exec laravel.test php artisan cache:clear
docker-compose exec laravel.test php artisan config:clear
docker-compose exec laravel.test php artisan route:clear
docker-compose exec laravel.test php artisan view:clear

# Reiniciar servicios
docker-compose restart laravel.test
```

---

### Problema 5: Error "Duplicate table: seeders already exists"

**Causa:** Conflicto al ejecutar migraciones múltiples veces.

**Solución:**
```powershell
# Eliminar tabla manualmente
docker-compose exec pgsql psql -U sail -d enertec -c "DROP TABLE IF EXISTS seeders CASCADE;"

# Ejecutar migraciones de nuevo
docker-compose exec laravel.test php artisan migrate:fresh --seed --force
```

---

### Problema 6: Procesos de Supervisor en FATAL state

**Causa:** Falta vendor/ o hay errores en la aplicación.

**Solución:**
```powershell
# Verificar logs
docker-compose logs -f laravel.test

# Asegurar que vendor existe
docker-compose exec laravel.test composer install

# Reiniciar supervisor
docker-compose exec laravel.test supervisorctl restart all
```

---

## Reinicio Completo (Empezar desde Cero)

Si algo sale mal y quieres empezar completamente de cero:

```powershell
# 1. Detener y eliminar TODO
docker-compose down -v --rmi all

# 2. Limpiar volúmenes huérfanos
docker volume prune -f

# 3. Verificar que no queden volúmenes
docker volume ls | Select-String -Pattern "pgsql|sail|aomenertec"

# 4. Reconstruir desde cero
docker-compose build --no-cache

# 5. Iniciar servicios
docker-compose up -d

# 6. Esperar 30 segundos
Start-Sleep -Seconds 30

# 7. Instalar dependencias
docker-compose exec laravel.test composer install

# 8. Configurar aplicación
docker-compose exec laravel.test php artisan key:generate

# 9. Ejecutar migraciones y seeders
docker-compose exec laravel.test php artisan migrate:fresh --seed --force

# 10. Verificar
docker-compose logs -f laravel.test
```

---

## Servicios Disponibles

Una vez que todo esté corriendo, tendrás acceso a:

| Servicio | URL/Puerto | Descripción |
|----------|------------|-------------|
| Aplicación Web | http://localhost | Interfaz principal |
| Laravel Echo Server | https://localhost:8443 | WebSockets en tiempo real |
| PostgreSQL | localhost:5432 | Base de datos |
| Redis | localhost:6379 | Caché y Broadcasting |
| MQTT Broker | localhost:1883 | Comunicación con dispositivos IoT |

### Credenciales MQTT

- **Usuario:** `enertec`
- **Contraseña:** `enertec2020**`

---

## Configurar Contraseña MQTT (Primera Vez)

Si necesitas configurar la contraseña de Mosquitto:

```powershell
# Configurar contraseña
docker-compose exec mosquitto mosquitto_passwd -c /mosquitto/config/passwd enertec

# Cuando te pida la contraseña, ingresa: enertec2020**

# Reiniciar Mosquitto
docker-compose restart mosquitto
```

---

## Actualizar el Proyecto

Cuando hagas `git pull` de nuevos cambios:

```powershell
# 1. Pull de cambios
git pull origin master

# 2. Reconstruir imagen si cambió el Dockerfile
docker-compose build --no-cache laravel.test

# 3. Reiniciar servicios
docker-compose down
docker-compose up -d

# 4. Actualizar dependencias
docker-compose exec laravel.test composer install
docker-compose exec laravel.test npm install

# 5. Ejecutar migraciones nuevas
docker-compose exec laravel.test php artisan migrate --force

# 6. Limpiar cachés
docker-compose exec laravel.test php artisan config:clear
docker-compose exec laravel.test php artisan cache:clear
```

---

## Notas Importantes

1. **Line Endings:** Si editas archivos en Windows, asegúrate de que los archivos `.sh` tengan line endings Unix (LF) y no Windows (CRLF). Configura Git:
   ```powershell
   git config core.autocrlf input
   ```

2. **Permisos:** Docker en Windows puede tener problemas con permisos. Si ves errores de permisos, ejecuta:
   ```powershell
   docker-compose exec laravel.test chmod -R 777 storage bootstrap/cache
   ```

3. **Performance:** WSL2 es mucho más rápido que Docker Desktop nativo en Windows. Considera usar WSL2 si tienes problemas de rendimiento.

4. **Hot Reload:** Para desarrollo con hot reload de assets:
   ```powershell
   docker-compose exec laravel.test npm run watch
   ```

---

## Soporte

Si encuentras problemas no documentados aquí:

1. Revisa los logs: `docker-compose logs -f laravel.test`
2. Verifica que todos los servicios estén corriendo: `docker-compose ps`
3. Asegúrate de tener suficiente memoria asignada en Docker Desktop (mínimo 4 GB)

---

**Última actualización:** 2025-12-30
