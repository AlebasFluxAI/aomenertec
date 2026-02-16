# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Información del Proyecto

Este es un proyecto Laravel 8.75 con Livewire 2.5 y Jetstream, que funciona como un sistema de gestión energética y monitoreo con comunicación MQTT. El proyecto utiliza PostgreSQL como base de datos y está configurado para despliegue en AWS mediante Bitbucket Pipelines.

**El proyecto está completamente dockerizado con Laravel Sail** para facilitar el desarrollo local.

## Desarrollo con Docker (Laravel Sail)

Este proyecto utiliza Laravel Sail para proporcionar un entorno de desarrollo Docker completo y consistente.

### Red Docker Compartida

Los contenedores del proyecto se conectan a una red Docker externa `enertec-shared` que permite la comunicación directa con los contenedores de `aomenertec-api`. La API puede alcanzar PostgreSQL, Redis y Mosquitto por nombre de contenedor a través de esta red.

- `make network` crea la red `enertec-shared` (idempotente, no falla si ya existe)
- `make setup`, `make prod-deploy` y `make prod-deploy-fresh` ejecutan `make network` automáticamente como dependencia

### Servicios Incluidos

El proyecto incluye los siguientes servicios Docker:

1. **Laravel App** (PHP 8.1)
   - Puerto: 80 (HTTP)
   - Puerto: 8443 (HTTPS - Laravel Echo Server)
   - Incluye: PHP-FPM, Supervisor, Node.js, Laravel Echo Server

2. **PostgreSQL 14**
   - Puerto: 5432
   - Base de datos: enertec
   - Usuario: sail

3. **Redis**
   - Puerto: 6379
   - Usado para: Broadcasting, Cache, Laravel Echo Server

4. **Mosquitto MQTT Broker**
   - Puerto: 1883
   - Usuario: enertec / enertec2020**
   - Para comunicación con dispositivos IoT

### Comandos Docker Principales

```bash
# Iniciar todos los servicios (primera vez)
./vendor/bin/sail up -d

# Detener todos los servicios
./vendor/bin/sail down

# Ver logs en tiempo real
./vendor/bin/sail logs -f

# Ver logs de un servicio específico
./vendor/bin/sail logs -f laravel.test
./vendor/bin/sail logs -f pgsql
./vendor/bin/sail logs -f mosquitto

# Reconstruir contenedores (después de cambiar Dockerfile)
./vendor/bin/sail build --no-cache
./vendor/bin/sail up -d
```

### Comandos Artisan con Sail

```bash
# Ejecutar migraciones
./vendor/bin/sail artisan migrate

# Resetear base de datos y ejecutar seeders
./vendor/bin/sail artisan migrate:fresh --seed

# Generar key de aplicación
./vendor/bin/sail artisan key:generate

# Limpiar cachés
./vendor/bin/sail artisan cache:clear
./vendor/bin/sail artisan config:clear
./vendor/bin/sail artisan route:clear

# Ejecutar comandos programados manualmente
./vendor/bin/sail artisan schedule:run

# Cualquier otro comando artisan
./vendor/bin/sail artisan [comando]
```

### Comandos Composer y NPM con Sail

```bash
# Composer
./vendor/bin/sail composer install
./vendor/bin/sail composer update
./vendor/bin/sail composer require [paquete]

# NPM
./vendor/bin/sail npm install
./vendor/bin/sail npm run dev
./vendor/bin/sail npm run watch
./vendor/bin/sail npm run prod
```

### Configuración Inicial del Proyecto

Al clonar el proyecto por primera vez:

```bash
# 1. Copiar archivo de entorno (si no existe)
cp .env.example .env

# 2. Instalar dependencias de Composer (necesario antes de usar Sail)
composer install --ignore-platform-reqs

# 3. Iniciar servicios Docker
./vendor/bin/sail up -d

# 4. Generar APP_KEY
./vendor/bin/sail artisan key:generate

# 5. Ejecutar migraciones
./vendor/bin/sail artisan migrate

# 6. Instalar dependencias NPM
./vendor/bin/sail npm install

# 7. Compilar assets
./vendor/bin/sail npm run dev

# 8. Configurar contraseña MQTT en Mosquitto
./vendor/bin/sail exec mosquitto mosquitto_passwd -c /mosquitto/config/passwd enertec
# Cuando te pida la contraseña, ingresa: enertec2020**
```

### Acceder a la Aplicación

Una vez iniciados los servicios:

- **Aplicación web**: http://localhost
- **Laravel Echo Server (WebSockets)**: https://localhost:8443
- **PostgreSQL**: localhost:5432
- **Redis**: localhost:6379
- **MQTT Broker**: localhost:1883

### Procesos en Background

El contenedor de Laravel ejecuta automáticamente (vía Supervisor):

1. **Laravel Echo Server** - WebSockets para broadcasting en tiempo real
2. **mqtt-consumer** - PHP-MQTT consumer (`php artisan mqtt:consume`) que se suscribe a los topics MQTT (`v1/mc/data`, `mc/data`, `v1/mc/alert`, `v1/mc/alert_control`, `v1/mc/ack`, `v1/mc/real_time`) y despacha jobs de procesamiento
3. **queue-worker** - Procesa jobs de la cola (Redis/sync)
4. **scheduler** - Ejecuta tareas programadas de Laravel

> **Nota**: Los scripts Python legacy (`receiveMqttEvent.py`, `receiveMqttRealTimeEvent.py`) fueron reemplazados por el consumer PHP-MQTT que se conecta directamente al broker y despacha jobs sin intermediarios HTTP.

Para ver los logs de estos procesos:

```bash
./vendor/bin/sail exec laravel.test supervisorctl status
./vendor/bin/sail exec laravel.test tail -f /var/log/supervisor/laravel-echo-server.out.log
./vendor/bin/sail exec laravel.test tail -f /var/log/supervisor/mqtt-consumer.out.log
```

### Ejecutar Comandos en Contenedores

```bash
# Shell en el contenedor de Laravel
./vendor/bin/sail shell

# Shell en PostgreSQL
./vendor/bin/sail psql

# Shell en Redis
./vendor/bin/sail redis

# Ejecutar comando bash en cualquier servicio
./vendor/bin/sail exec [servicio] [comando]
```

### Hot Reload de Código

Los cambios en el código se reflejan automáticamente sin necesidad de reiniciar contenedores:

- ✅ **PHP/Blade**: Cambios reflejados inmediatamente
- ✅ **Assets (CSS/JS)**: Usar `sail npm run watch` para compilación automática
- ✅ **Configuración**: Ejecutar `sail artisan config:clear` después de cambios
- ⚠️ **Docker/Supervisor**: Requiere `sail build` y `sail up -d` para aplicar cambios

### Volúmenes Persistentes

El proyecto usa volúmenes Docker para persistir datos:

- `sail-pgsql`: Datos de PostgreSQL (persisten entre reinicios)
- `sail-redis`: Datos de Redis
- `sail-mosquitto-data`: Mensajes MQTT persistentes
- `sail-vendor`: Cache de dependencias Composer (mejor performance)
- `sail-node-modules`: Cache de dependencias NPM (mejor performance)

### Solución de Problemas

```bash
# Reiniciar todos los servicios
./vendor/bin/sail restart

# Limpiar volúmenes y empezar desde cero (¡CUIDADO! Borra todos los datos)
./vendor/bin/sail down -v
./vendor/bin/sail up -d

# Ver uso de recursos
docker stats

# Verificar estado de servicios
./vendor/bin/sail ps
```

### Alias Útil

Para evitar escribir `./vendor/bin/sail` cada vez, agrega este alias a tu `~/.bashrc` o `~/.zshrc`:

```bash
alias sail='./vendor/bin/sail'
```

Luego podrás usar simplemente: `sail up -d`, `sail artisan migrate`, etc.

## Arquitectura del Sistema

### Estructura de Versiones
El código está organizado en una arquitectura versionada donde la mayoría de la lógica de negocio reside en el namespace `V1`:
- **Rutas**: `/routes/V1/` contiene `api.php`, `web.php`, `channels.php`, `console.php`
- **Modelos**: `/app/Models/V1/` - más de 90 modelos de dominio
- **Controladores**: `/app/Http/Controllers/V1/`
- **Livewire**: `/app/Http/Livewire/V1/` - componentes organizados por dominio (Admin, Client, etc.)
- **Commands**: `/app/Console/Commands/V1/` - comandos artisan versionados

### Dominios Principales
1. **Cliente (Client)**: Gestión de clientes, facturación, alertas, configuraciones, lecturas manuales
2. **Monitoreo en Tiempo Real**: Recepción y procesamiento de datos MQTT
3. **Facturación**: Generación de facturas, pagos manuales, recargas
4. **Datos de Consumo**: Procesamiento por hora, día y mes con comandos programados
5. **Alertas**: Sistema configurable de alertas por cliente
6. **Administración**: Usuarios, permisos (Spatie), tipos de equipo, precios

### Sistema MQTT
- **Entrada de datos**: `/app/Http/Controllers/V1/MqttInput/` - endpoints para recibir datos MQTT
- **Procesamiento en tiempo real**: Broadcasting de eventos con Socket.io
- **Configuración**: `/config/mqtt-client.php` - cliente Laravel MQTT
- **Comandos**: Procesamiento y ordenamiento de datos cada 2 minutos

## Comandos de Desarrollo

### Backend (Laravel)

```bash
# Instalar dependencias
composer install

# Configuración inicial
cp .env.example .env
php artisan key:generate

# Migraciones y seeders
php artisan migrate
php artisan db:seed

# Ejecutar servidor de desarrollo
php artisan serve

# Ejecutar tests
vendor/bin/phpunit
# o solo tests unitarios
vendor/bin/phpunit --testsuite=Unit
# o solo tests de feature
vendor/bin/phpunit --testsuite=Feature
```

### Frontend (NPM/Mix)

```bash
# Instalar dependencias
npm install

# Compilar assets en desarrollo
npm run dev

# Watch mode para desarrollo
npm run watch

# Hot reload
npm run hot

# Compilar para producción
npm run prod
```

### Comandos Artisan Importantes

```bash
# Ejecutar comandos programados manualmente
php artisan schedule:run

# Procesar datos de consumo (se ejecuta automáticamente cada 2 minutos)
php artisan update:data-consumption
php artisan update:timestamp-data-consumption

# Promedios (programados por hora/día/mes)
php artisan average:hourly-consumption
php artisan average:daily-consumption
php artisan average:monthly-consumption

# Generación de reportes e facturas
php artisan client:report {rate}
php artisan client:invoice-generation

# Limpiar datos detenidos
php artisan delete:stop-unpack-data
```

### Laravel Echo Server (WebSockets)
```bash
# Iniciar servidor de broadcasting (configuración en laravel-echo-server.json)
laravel-echo-server start
```

## Programación de Tareas (Cron)

El sistema tiene múltiples tareas programadas en `/app/Console/Kernel.php`:
- **Cada 2 minutos**: Procesamiento de datos de consumo
- **Cada minuto**: Actualización de timestamps
- **Cada hora (35 min)**: Promedio de consumo por hora
- **Diariamente (01:05)**: Promedio de consumo diario
- **Diariamente (02:05)**: Promedio de consumo mensual
- **Mensual (día 1)**: Reporte de clientes con tarifa mensual e facturación

## Tecnologías Clave

### Backend
- Laravel 8.75 con Jetstream para autenticación
- Livewire 2.5 para componentes reactivos
- Spatie Laravel Permission para roles y permisos
- JWT Auth (tymon/jwt-auth) para API tokens
- MQTT Client (php-mqtt/laravel-client) para comunicación con dispositivos
- Maatwebsite Excel para exportación de datos
- DomPDF para generación de PDFs
- Pusher para broadcasting

### Frontend
- Alpine.js 3.0
- Tailwind CSS 3.0 con plugins (@tailwindcss/forms, @tailwindcss/typography)
- Vue.js 2.6 (limitado)
- ApexCharts para gráficos
- Flatpickr para selección de fechas
- Socket.io client para tiempo real

### Base de Datos
- PostgreSQL (configuración por defecto en .env.example)
- Redis para caché y colas (opcional)
- Session almacenado en base de datos

## Middleware Personalizado

El proyecto tiene varios middlewares personalizados en `/app/Http/Middleware/V1/`:
- `token_api_validation`: Validación de tokens para API
- `event_queue_validation`: Validación de colas de eventos
- `permission`, `custom_permissions`: Control de permisos
- `enable_user`: Validación de usuarios habilitados
- `role_selection`: Selección de roles

## Deployment

### Producción (Ubuntu + Docker)

El método principal de deployment es con Docker en un servidor Ubuntu 22.04:

```bash
# En el servidor de producción
make prod-deploy       # Deployment completo (primera vez)
make prod-update       # Actualizar código (después de git pull)
make prod-restart      # Reiniciar servicios
make prod-logs         # Ver logs
```

**📖 Ver [DEPLOYMENT-PRODUCTION.md](DEPLOYMENT-PRODUCTION.md) para la guía completa.**

### Legacy: AWS CodeDeploy

El proyecto también tiene configuración legacy para Bitbucket Pipelines con AWS CodeDeploy:
- Pipeline configurado en `bitbucket-pipelines.yml`
- Solo se ejecuta en la rama `master`
- Comprime el código y lo despliega a AWS S3 y CodeDeploy
- La configuración de deployment está en `appspec.yml`

## Configuraciones Especiales

### Configuración de API URL (self-calling)
La web app se llama a sí misma para los endpoints de configuración IoT (`/api/v1/config/*`, `/api/v1/clients/*`). La URL base y paths están centralizados en `/config/aom.php`:
- `config('aom.api_url')` — URL base de la propia web app (env: `AOM_API_URL`, default: `http://localhost`)
  - **Local**: `http://localhost` (la web app en Docker, puerto 80)
  - **Producción**: `https://app.fluxai.solutions`
- `config('aom.api_config_path')` — Path de configuración (env: `AOM_API_CONFIG_PATH`, default: `/api/v1/config`)
- `config('aom.api_clients_path')` — Path de clientes (env: `AOM_API_CLIENTS_PATH`, default: `/api/v1/clients`)

**IMPORTANTE**: Usar siempre `config('aom.*')` en el código, NUNCA `env()` directamente. Esto permite que `php artisan config:cache` funcione correctamente en producción.
**NOTA**: `AOM_API_URL` apunta a la propia web app (mismo dominio), NO a un servidor API externo.

### Data Frames
El sistema tiene múltiples archivos de configuración para diferentes tipos de medidores:
- `/config/data-frame-*.php` - Configuraciones para medidores monofásicos, bifásicos y trifásicos
- Incluyen mapeo de datos activos, reactivos y totales

### Permisos
- Archivo extenso de permisos en `/config/permissions.php`
- Sistema de permisos basado en Spatie con roles y permisos personalizados

## Convenciones de Código

- Los modelos principales están en `App\Models\V1`
- Los controladores siguen el patrón `App\Http\Controllers\V1\{Dominio}\{Nombre}Controller`
- Los componentes Livewire siguen `App\Http\Livewire\V1\{Dominio}\{Acción}{Entidad}`
- Los comandos están en `App\Console\Commands\V1\{Nombre}Command` o `{Nombre}`
- Usar middleware personalizado para validaciones de negocio específicas
- Broadcasting de eventos para actualizaciones en tiempo real

## Notas Importantes

- El sistema procesa grandes volúmenes de datos de medidores eléctricos
- Los datos se procesan y agregan en múltiples niveles temporales (minutos, horas, días, meses)
- La comunicación MQTT es crítica para el funcionamiento del sistema
- El sistema maneja facturación automática y manual
- Importante mantener la programación de tareas (cron) funcionando correctamente
- Los eventos de broadcasting permiten monitoreo en tiempo real
