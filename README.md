# Proyecto Enertec - Sistema de Gestión de Energía

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

**📖 Para instrucciones detalladas de Docker, consulta [README-DOCKER.md](README-DOCKER.md)**

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
- Integración MQTT para dispositivos
- Scripts Python para procesamiento de eventos
- Monitoreo en tiempo real
- Procesamiento de datos de sensores

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
├── script/               # Scripts Python MQTT
│   ├── receiveMqttEvent.py
│   ├── receiveMqttRealTimeEvent.py
│   └── requirements.txt
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

- **[README-DOCKER.md](README-DOCKER.md)** - Guía completa de Docker
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
