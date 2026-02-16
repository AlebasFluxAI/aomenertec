# Proyecto FluxAI - Sistema de Gestión de Energía

Sistema de gestión y monitoreo de energía desarrollado con Laravel 8.75, que integra comunicación MQTT para dispositivos IoT, WebSockets en tiempo real y gestión avanzada de usuarios.

## 🚀 Inicio Rápido

Este proyecto está completamente dockerizado. Para comenzar:

```bash
# Configuración inicial (primera vez)
make setup

# Iniciar servicios
make up

# Ver estado
make status
```

**📖 Documentación:**
- [README-DOCKER.md](README-DOCKER.md) - Guía completa de Docker para desarrollo
- [DEPLOYMENT-PRODUCTION.md](DEPLOYMENT-PRODUCTION.md) - Guía de deployment a producción

## 🏗️ Stack Tecnológico

- **Framework**: Laravel 8.75
- **Frontend**: Livewire 2.5 + Jetstream + TailwindCSS
- **Base de Datos**: PostgreSQL 14
- **Cache/Broadcasting**: Redis
- **WebSockets**: Laravel Echo Server
- **IoT**: Mosquitto MQTT Broker
- **Contenedores**: Docker + Laravel Sail

## 📦 Servicios Incluidos

| Servicio | Versión | Puerto | Descripción |
|----------|---------|--------|-------------|
| **PHP** | 8.1 | 80 | Aplicación Laravel con Livewire |
| **PostgreSQL** | 14 | 5432 | Base de datos principal |
| **Redis** | Alpine | 6379 | Cache y Broadcasting |
| **Mosquitto** | 2.0 | 1883 | MQTT Broker para IoT |
| **Echo Server** | Latest | 8443 | WebSockets (HTTPS) |

## 🎯 Características Principales

### Sistema de Usuarios
- Autenticación con Laravel Jetstream
- Roles y permisos
- Gestión de equipos (Teams)
- Perfiles de usuario personalizables

### Comunicación IoT
- Integración MQTT para dispositivos IoT (medidores eléctricos)
- PHP-MQTT consumer directo (`php artisan mqtt:consume`) — sin intermediarios
- Monitoreo en tiempo real vía WebSockets
- Procesamiento y agregación de datos de sensores (minuto/hora/día/mes)

### Sistema V1
- Gestión de clientes y equipos
- Monitoreo de consumo energético
- Dashboard con métricas en tiempo real
- Reportes y analíticas

## 📋 Comandos Disponibles (Makefile)

### Comandos de Docker
```bash
make build         # Build rápido (versión simple)
make build-full    # Build completo con MQTT y Echo Server
make up            # Iniciar servicios
make down          # Detener servicios
make restart       # Reiniciar servicios
make logs          # Ver logs de todos los servicios
make ps            # Ver estado de contenedores
```

### Configuración y Base de Datos
```bash
make setup         # Configuración inicial completa
make install       # Instalar dependencias (Composer + NPM)
make migrate       # Ejecutar migraciones
make migrate-fresh # Resetear base de datos
make migrate-seed  # Resetear con seeders
make seed          # Ejecutar seeders
```

### Desarrollo
```bash
make dev           # Compilar assets en desarrollo
make watch         # Compilar y observar cambios
make prod          # Compilar assets para producción
make cache-clear   # Limpiar cachés
make optimize      # Optimizar aplicación
```

### Utilidades
```bash
make shell         # Shell del contenedor
make tinker        # Laravel Tinker
make test          # Ejecutar tests
make status        # Ver estado completo
make urls          # Mostrar URLs de acceso
make help          # Ver todos los comandos disponibles
```

### Producción (Servidor Ubuntu)
```bash
# Primera instalación (desde cero con seeders)
make prod-deploy-fresh     # Deployment completo + migrate:fresh --seed
make prod-mqtt-password    # Configurar MQTT (interactivo, REQUERIDO)

# Deployment normal (base de datos existente)
make prod-deploy           # Deployment con migraciones
make prod-mqtt-password    # Configurar MQTT (interactivo, REQUERIDO)

# Actualización después de git pull
make prod-update           # Actualizar código, assets, migraciones

# Mantenimiento
make prod-up               # Iniciar servicios
make prod-down             # Detener servicios
make prod-restart          # Reiniciar servicios
make prod-logs             # Ver logs
make prod-ps               # Estado de contenedores
make prod-seed             # Ejecutar seeders
make prod-create-db        # Crear base de datos si no existe
```

> ⚠️ **IMPORTANTE**: Después de `prod-deploy` o `prod-deploy-fresh`, DEBES ejecutar `make prod-mqtt-password` para configurar la autenticación MQTT. Ingresa la misma contraseña que `MQTT_AUTH_PASSWORD` en `.env.production`.

> 📖 Ver [DEPLOYMENT-PRODUCTION.md](DEPLOYMENT-PRODUCTION.md) para la guía completa de deployment.

## 🌐 URLs de Acceso

- **Aplicación Web**: http://localhost
- **Laravel Echo Server**: https://localhost:8443
- **PostgreSQL**: localhost:5432
- **Redis**: localhost:6379
- **MQTT Broker**: localhost:1883

## 📁 Estructura del Proyecto

```
aomenertec/
├── app/
│   ├── Actions/           # Jetstream actions
│   ├── Http/
│   │   ├── Controllers/   # Controladores
│   │   └── Livewire/      # Componentes Livewire
│   ├── Models/            # Modelos Eloquent
│   └── V1/                # Sistema V1 (namespace principal)
│       ├── Config/
│       ├── Models/
│       ├── Repositories/
│       └── Services/
├── database/
│   ├── migrations/        # Migraciones
│   └── seeders/          # Seeders
├── docker/               # Configuración Docker
│   ├── Dockerfile        # Dockerfile simple
│   ├── Dockerfile.optimized # Dockerfile completo
│   ├── init-scripts/     # Scripts de inicialización
│   ├── mosquitto/        # Config MQTT
│   ├── ssl/              # Certificados SSL
│   └── supervisor/       # Configuración Supervisor
├── resources/
│   ├── views/            # Vistas Blade
│   └── js/               # JavaScript/Vue
├── routes/
│   ├── web.php           # Rutas web
│   └── api.php           # Rutas API
├── script/               # Scripts legacy (deshabilitados, ver ConsumerCommand.php)
├── Makefile              # Comandos simplificados
├── docker-compose.yml    # Orquestación de servicios
└── laravel-echo-server.json # Config Echo Server
```

## 🔧 Configuración Adicional

### MQTT Broker
Para configurar la contraseña de Mosquitto:

```bash
make mqtt-password
# Usuario: enertec
# Contraseña: enertec2020**
```

### Supervisor
Ver estado de procesos en background:

```bash
make supervisor-status
make supervisor-restart
```

### Hot Reload
Los cambios en código PHP/Blade se reflejan automáticamente. Para assets:

```bash
make watch
```

## 🧪 Testing

```bash
make test              # Ejecutar todos los tests
make test-coverage     # Tests con cobertura
```

## 🐛 Solución de Problemas

### Reiniciar completamente
```bash
make clean-all  # ⚠️ Elimina volúmenes (base de datos)
make setup      # Configurar de nuevo
```

### Ver logs específicos
```bash
make logs-app      # Laravel
make logs-db       # PostgreSQL
make logs-mqtt     # Mosquitto
```

### Limpiar cachés
```bash
make cache-clear
make clean
```

## 📚 Documentación Adicional

- **[README-DOCKER.md](README-DOCKER.md)** - Guía completa de Docker para desarrollo
- **[DEPLOYMENT-PRODUCTION.md](DEPLOYMENT-PRODUCTION.md)** - Guía de deployment a producción
- **[CLAUDE.md](CLAUDE.md)** - Documentación técnica del proyecto
- **Makefile** - Ejecuta `make help` para ver todos los comandos

## 🔒 Seguridad

- Nunca commitear archivos `.env` o `credentials.json`
- Cambiar las contraseñas por defecto en producción
- Los certificados SSL en `docker/ssl` son auto-firmados (solo desarrollo)

## 🤝 Contribución

Este proyecto usa:
- **PSR-4** para autoloading
- **Laravel best practices**
- **Conventional commits** para mensajes de commit

## 📄 Licencia

Desarrollado por **IMERGI**

---

**Nota**: Este proyecto utiliza Laravel Sail para desarrollo. Todos los comandos están disponibles a través del Makefile para facilitar su uso.
