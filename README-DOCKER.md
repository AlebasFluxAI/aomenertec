# 🐳 Guía Completa de Docker - Proyecto Enertec

Este proyecto utiliza **Laravel Sail** con Docker para crear un entorno de desarrollo completo y aislado.

## 📋 Tabla de Contenidos

- [Inicio Rápido](#-inicio-rápido-primera-vez)
- [Comandos del Makefile](#-comandos-del-makefile)
- [Servicios](#-servicios-incluidos)
- [Versiones de Build](#-versiones-de-build)
- [Configuración Avanzada](#-configuración-avanzada)
- [Solución de Problemas](#-solución-de-problemas)

## 🚀 Inicio Rápido (Primera Vez)

### Opción 1: Usando Makefile (Recomendado)

```bash
# Configuración inicial automática
make setup
```

Este comando ejecuta automáticamente:
- ✅ Copia `.env.example` a `.env`
- ✅ Construye las imágenes Docker
- ✅ Inicia todos los servicios
- ✅ Instala dependencias de Composer y NPM
- ✅ Genera la clave de aplicación
- ✅ Ejecuta las migraciones
- ✅ Compila los assets

### Opción 2: Paso a Paso Manual

```bash
# 1. Copiar archivo de entorno
cp .env.example .env

# 2. Construir imágenes Docker (versión simple - más rápido)
make build

# 3. Iniciar servicios
make up

# 4. Instalar dependencias
make install

# 5. Generar clave de aplicación
./vendor/bin/sail artisan key:generate

# 6. Ejecutar migraciones
make migrate

# 7. Compilar assets
make dev

# 8. Configurar MQTT (opcional)
make mqtt-password
```

## 📦 Servicios Incluidos

| Servicio | Versión | Puerto | Healthcheck | Descripción |
|----------|---------|--------|-------------|-------------|
| **Laravel** | PHP 8.1 | 80 | ✅ | Aplicación principal con Livewire |
| **PostgreSQL** | 14 | 5432 | ✅ | Base de datos relacional |
| **Redis** | Alpine | 6379 | ✅ | Cache, sessions y broadcasting |
| **Mosquitto** | 2.0 | 1883 | ✅ | MQTT Broker para dispositivos IoT |
| **Echo Server** | Latest | 8443 | - | WebSockets sobre HTTPS |
| **Supervisor** | 4.2.2 | - | - | Gestor de procesos (Laravel server, Echo, MQTT scripts) |

### Procesos Gestionados por Supervisor

- **laravel-server**: PHP development server (puerto 80)
- **laravel-echo-server**: WebSocket server (puerto 8443)
- **mqtt-receiver**: Script Python para eventos MQTT
- **mqtt-realtime-receiver**: Script Python para eventos MQTT en tiempo real

## 🎛️ Comandos del Makefile

### Comandos de Docker

| Comando | Descripción | Uso |
|---------|-------------|-----|
| `make build` | Build rápido (5-10 segundos) | Para desarrollo normal |
| `make build-full` | Build completo con MQTT/Echo (3-5 min) | Primera vez o cambios en Dockerfile |
| `make up` | Iniciar todos los servicios | Después de `make down` |
| `make down` | Detener todos los servicios | Al terminar de trabajar |
| `make restart` | Reiniciar servicios | Cuando hay problemas |
| `make ps` | Ver estado de contenedores | Diagnóstico |
| `make logs` | Ver logs de todos los servicios | Debugging general |
| `make logs-app` | Ver logs de Laravel | Debugging de aplicación |
| `make logs-db` | Ver logs de PostgreSQL | Debugging de BD |
| `make logs-mqtt` | Ver logs de Mosquitto | Debugging de MQTT |

### Configuración y Base de Datos

| Comando | Descripción | Cuándo usar |
|---------|-------------|-------------|
| `make setup` | Configuración inicial completa | Primera vez |
| `make install` | Instalar Composer + NPM | Después de `git pull` |
| `make migrate` | Ejecutar migraciones pendientes | Nueva migración |
| `make migrate-fresh` | Resetear BD y migrar | Desarrollo (⚠️ borra datos) |
| `make migrate-seed` | Resetear BD, migrar y seeders | Testing con datos |
| `make seed` | Ejecutar seeders | Poblar BD con datos |
| `make key-generate` | Generar APP_KEY | Si falta en .env |

### Laravel Artisan

| Comando | Descripción | Ejemplo |
|---------|-------------|---------|
| `make cache-clear` | Limpiar todas las cachés | Cambios en config |
| `make optimize` | Cachear config/routes/views | Antes de deployment |
| `make tinker` | Abrir Laravel Tinker | Probar código PHP |
| `make db-shell` | Shell de PostgreSQL | Queries SQL directas |

### Desarrollo de Assets

| Comando | Descripción | Uso |
|---------|-------------|-----|
| `make dev` | Compilar assets en desarrollo | Una vez |
| `make watch` | Compilar y observar cambios | Durante desarrollo |
| `make prod` | Compilar para producción | Build final |

### Testing

| Comando | Descripción |
|---------|-------------|
| `make test` | Ejecutar todos los tests |
| `make test-coverage` | Tests con reporte de cobertura |

### Utilidades

| Comando | Descripción | Cuándo usar |
|---------|-------------|-------------|
| `make shell` | Shell como usuario `sail` | Ejecutar comandos |
| `make root-shell` | Shell como `root` | Tareas de sistema |
| `make supervisor-status` | Ver estado de procesos | Verificar servicios |
| `make supervisor-restart` | Reiniciar procesos | Problemas con servicios |
| `make mqtt-password` | Configurar contraseña MQTT | Primera vez |
| `make status` | Estado completo del sistema | Diagnóstico general |
| `make urls` | Mostrar URLs de acceso | Referencia rápida |
| `make help` | Ver todos los comandos | Ayuda |

### Limpieza

| Comando | Descripción | ⚠️ Advertencia |
|---------|-------------|----------------|
| `make clean` | Limpiar archivos temporales | Seguro |
| `make clean-all` | Eliminar TODO incluyendo BD | ⚠️ DESTRUYE DATOS |

## 🔧 Versiones de Build

### Build Simple (Recomendado para desarrollo)

```bash
make build
```

**Incluye:**
- ✅ PHP 8.1 con extensiones
- ✅ Composer
- ✅ Configuración básica
- ⏱️ Tiempo: 5-10 segundos

**Usa cuando:**
- Desarrollas solo la aplicación web
- No necesitas MQTT o WebSockets
- Quieres builds rápidos

### Build Completo

```bash
make build-full
```

**Incluye todo lo anterior más:**
- ✅ Node.js 18.x y npm
- ✅ Laravel Echo Server
- ✅ Scripts Python MQTT
- ✅ Supervisor para gestión de procesos
- ✅ Todas las extensiones PHP (pdo_pgsql, pgsql)
- ⏱️ Tiempo: 3-5 minutos

**Usa cuando:**
- Primera vez configurando el proyecto
- Necesitas WebSockets (Echo Server)
- Trabajas con dispositivos IoT (MQTT)
- Cambios en `docker/Dockerfile.optimized`

## 🌐 URLs de Acceso

Una vez iniciados los servicios:

```
🌐 Aplicación Web:       http://localhost
🔌 Laravel Echo Server:  https://localhost:8443
🗄️  PostgreSQL:          localhost:5432
   └─ Usuario:           sail
   └─ Contraseña:        password
   └─ Base de datos:     enertec

💾 Redis:                localhost:6379
📡 MQTT Broker:          localhost:1883
   └─ Usuario:           enertec
   └─ Contraseña:        enertec2020**
```

## 🔄 Flujo de Trabajo Típico

### Comenzar a trabajar

```bash
make up      # Iniciar servicios
make watch   # Compilar assets con hot-reload (opcional)
```

### Durante el desarrollo

```bash
# Cambios en PHP/Blade - se reflejan automáticamente ✅

# Cambios en JS/CSS - con watch activo se recompilan automáticamente ✅

# Nueva migración
make migrate

# Limpiar cachés después de cambios en config
make cache-clear

# Ver logs si algo falla
make logs-app
```

### Terminar de trabajar

```bash
make down    # Detener servicios
```

## 🔧 Configuración Avanzada

### Configurar MQTT Broker

```bash
make mqtt-password
# Cuando pregunte, ingresa: enertec2020**
```

Esto configura:
- Usuario: `enertec`
- Contraseña: `enertec2020**`
- Archivo: `/mosquitto/config/passwd`

### Verificar Estado de Supervisor

```bash
make supervisor-status
```

Deberías ver:
```
laravel-server           RUNNING
laravel-echo-server      RUNNING
mqtt-receiver            RUNNING (o FATAL si no hay conexión MQTT)
mqtt-realtime-receiver   RUNNING (o FATAL si no hay conexión MQTT)
```

### Ejecutar Comandos Personalizados

```bash
# Con sail (recomendado)
./vendor/bin/sail artisan <comando>
./vendor/bin/sail composer <comando>
./vendor/bin/sail npm <comando>

# Desde el shell del contenedor
make shell
php artisan <comando>
```

### Hot Reload

#### Código PHP/Blade
✅ **Automático** - Los cambios se reflejan inmediatamente

#### Assets (CSS/JS)
```bash
make watch   # Recompila automáticamente en cada cambio
```

## 🐛 Solución de Problemas

### La aplicación no responde en http://localhost

```bash
# 1. Verificar que los servicios están corriendo
make ps

# 2. Ver logs
make logs-app

# 3. Reiniciar servicios
make restart
```

### Error "could not find driver" (PostgreSQL)

```bash
# Reconstruir con el build completo
make build-full
make restart
```

### Supervisor no inicia

```bash
# Ver logs del contenedor
make logs-app | grep supervisor

# Verificar que el contenedor está corriendo
docker ps | grep laravel.test
```

### Scripts MQTT fallan (estado FATAL)

Esto es normal si:
- El broker Mosquitto no está configurado
- No hay dispositivos IoT conectados
- La contraseña MQTT no está configurada

**Solución:**
```bash
make mqtt-password
make supervisor-restart
```

### Puerto 80 ya en uso

```bash
# Opción 1: Detener otro servicio en puerto 80
# Opción 2: Cambiar puerto en docker-compose.yml
ports:
  - '8080:80'  # Cambiar primer número
```

### Cambios en .env no se reflejan

```bash
make cache-clear
make restart
```

### Reiniciar desde cero

```bash
# ⚠️ ESTO BORRARÁ TODA LA BASE DE DATOS
make clean-all
make setup
```

### Error de permisos en storage/

```bash
make shell
chmod -R 777 storage bootstrap/cache
```

### Build muy lento

```bash
# Limpiar caché de Docker
docker system prune -a

# Usar build simple en lugar de completo
make build
```

## 📊 Monitoreo y Logs

### Ver logs en tiempo real

```bash
# Todos los servicios
make logs

# Solo Laravel
make logs-app

# Solo Base de datos
make logs-db

# Solo MQTT
make logs-mqtt
```

### Ver logs de Supervisor

```bash
make shell
cat /var/log/supervisor/laravel-server.out.log
cat /var/log/supervisor/mqtt-receiver.err.log
```

### Estado del sistema

```bash
make status
```

Muestra:
- Contenedores activos
- Versiones de PHP, Composer, Node, NPM
- Estado general

## 🔒 Consideraciones de Seguridad

### Desarrollo

- ✅ Usar contraseñas del `.env.example`
- ✅ Certificados SSL auto-firmados OK
- ✅ Puertos expuestos a localhost OK

### Producción

- ⚠️ CAMBIAR todas las contraseñas
- ⚠️ Usar certificados SSL válidos (Cloudflare Origin Certificate)
- ⚠️ NO exponer puertos directamente
- ⚠️ Usar reverse proxy (nginx incluido en docker-compose.production.yml)
- ⚠️ Habilitar firewall
- ⚠️ Configurar MQTT con `make prod-mqtt-password` después del deploy

**Comandos de producción:**
```bash
# Primera vez (desde cero)
make prod-deploy-fresh     # Deploy + migrate:fresh --seed
make prod-mqtt-password    # Configurar MQTT (REQUERIDO)

# Actualización
make prod-update           # Después de git pull

# Mantenimiento
make prod-restart          # Reiniciar servicios
make prod-logs             # Ver logs
```

## 📚 Recursos Adicionales

- **[README.md](README.md)** - Documentación general del proyecto
- **[CLAUDE.md](CLAUDE.md)** - Documentación técnica completa
- **[Laravel Sail Docs](https://laravel.com/docs/8.x/sail)** - Documentación oficial de Sail

## 💡 Tips y Trucos

### Alias de Sail

Agrega a `~/.bashrc` o `~/.zshrc`:

```bash
alias sail='./vendor/bin/sail'
```

Luego usa:
```bash
sail up -d
sail artisan migrate
sail composer install
```

### Ejecutar comandos sin entrar al shell

```bash
# En lugar de:
make shell
php artisan cache:clear
exit

# Hacer:
./vendor/bin/sail artisan cache:clear
```

### Usar múltiples terminales

Terminal 1:
```bash
make up       # Servicios en background
```

Terminal 2:
```bash
make watch    # Assets con hot-reload
```

Terminal 3:
```bash
make logs-app # Logs en tiempo real
```

## 🎯 Próximos Pasos

Después de la configuración inicial:

1. ✅ Verificar que la app funciona en http://localhost
2. ✅ Ejecutar `make mqtt-password` si usarás IoT
3. ✅ Revisar `make supervisor-status` para ver todos los servicios
4. ✅ Ejecutar `make test` para verificar que los tests pasan
5. ✅ Comenzar a desarrollar

---

**Desarrollado por IMERGI**

💡 **Tip**: Ejecuta `make help` en cualquier momento para ver todos los comandos disponibles.
