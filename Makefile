# Makefile para Proyecto FluxAI (Enertec)
# Desarrollo LOCAL con Laravel Sail + Producción con Docker Compose

.PHONY: help build up down restart logs shell install setup migrate fresh test clean \
        network \
        prod-deploy prod-deploy-fresh prod-up prod-down prod-restart prod-logs prod-ps prod-shell \
        prod-migrate prod-seed prod-mqtt-password prod-update prod-create-db

# Variables - Desarrollo (Sail)
SAIL = ./vendor/bin/sail
DOCKER_COMPOSE = docker-compose

# Variables - Producción
PROD_COMPOSE = docker compose -f docker-compose.production.yml
PROD_EXEC = $(PROD_COMPOSE) exec -T laravel.test

help: ## Mostrar esta ayuda
	@echo "Comandos disponibles:"
	@grep -E '^[a-zA-Z_-]+:.*?## .*$$' $(MAKEFILE_LIST) | sort | awk 'BEGIN {FS = ":.*?## "}; {printf "\033[36m%-20s\033[0m %s\n", $$1, $$2}'

# ============================================
# Comandos de Docker
# ============================================

build: ## Construir las imágenes Docker (versión rápida/simple)
	@echo "🔨 Construyendo imágenes Docker (versión simple)..."
	$(SAIL) build --no-cache

build-full: ## Construir versión completa con MQTT y Echo Server (más lento)
	@echo "🔨 Construyendo imágenes Docker (versión completa)..."
	@mv docker/Dockerfile docker/Dockerfile.bak 2>/dev/null || true
	@mv docker/Dockerfile.optimized docker/Dockerfile
	@$(SAIL) build --no-cache
	@mv docker/Dockerfile docker/Dockerfile.optimized
	@mv docker/Dockerfile.bak docker/Dockerfile 2>/dev/null || true
	@echo "✅ Build completo terminado"

up: ## Iniciar todos los servicios
	@echo "🚀 Iniciando servicios Docker..."
	$(SAIL) up -d
	@echo "✅ Servicios iniciados en http://localhost"

down: ## Detener todos los servicios
	@echo "🛑 Deteniendo servicios Docker..."
	$(SAIL) down

restart: ## Reiniciar todos los servicios
	@echo "🔄 Reiniciando servicios..."
	$(SAIL) restart

logs: ## Ver logs de todos los servicios
	$(SAIL) logs -f

logs-app: ## Ver logs del contenedor Laravel
	$(SAIL) logs -f laravel.test

logs-db: ## Ver logs de PostgreSQL
	$(SAIL) logs -f pgsql

logs-mqtt: ## Ver logs de Mosquitto
	$(SAIL) logs -f mosquitto

ps: ## Ver estado de los contenedores
	$(SAIL) ps

# ============================================
# Red Docker Compartida
# ============================================

network: ## Crear red compartida con la API (enertec-shared)
	@echo "==> Creando red Docker compartida 'enertec-shared'..."
	@docker network create enertec-shared 2>/dev/null || echo "    La red ya existe"

# ============================================
# Configuración Inicial
# ============================================

setup: network ## Configuración inicial completa del proyecto
	@echo "⚙️  Configuración inicial del proyecto..."
	@if [ ! -f .env ]; then \
		echo "📋 Copiando .env.example a .env..."; \
		cp .env.example .env; \
	fi
	@echo "🔨 Construyendo imágenes Docker (versión completa con Node.js)..."
	@$(SAIL) build --no-cache
	@echo "🚀 Iniciando servicios..."
	@$(SAIL) up -d
	@echo "⏳ Esperando que los servicios estén listos..."
	@sleep 10
	@$(MAKE) install
	@echo "🔑 Generando APP_KEY..."
	@$(SAIL) artisan key:generate
	@echo "🔑 Generando JWT_SECRET..."
	@$(SAIL) artisan jwt:secret --force
	@echo "📦 Ejecutando migraciones..."
	@$(SAIL) artisan migrate
	@echo "🔗 Creando symlink storage..."
	@$(SAIL) artisan storage:link 2>/dev/null || echo "    Symlink ya existe"
	@echo "🎨 Compilando assets..."
	@$(SAIL) npm run dev
	@echo "🔐 Configurando contraseña MQTT..."
	@echo "NOTA: Ejecuta manualmente: make mqtt-password"
	@echo "✅ ¡Configuración completada! Accede a http://localhost"

install: ## Instalar dependencias (Composer y NPM)
	@echo "📦 Instalando dependencias de Composer..."
	@$(SAIL) composer install
	@echo "📦 Instalando dependencias de NPM..."
	@$(SAIL) npm install

# ============================================
# Base de Datos
# ============================================

migrate: ## Ejecutar migraciones pendientes
	@echo "📦 Ejecutando migraciones..."
	$(SAIL) artisan migrate

migrate-fresh: ## Resetear base de datos y ejecutar migraciones
	@echo "🔄 Reseteando base de datos..."
	$(SAIL) artisan migrate:fresh

migrate-seed: ## Resetear base de datos, ejecutar migraciones y seeders
	@echo "🔄 Reseteando base de datos con seeders..."
	$(SAIL) artisan migrate:fresh --seed

seed: ## Ejecutar seeders
	@echo "🌱 Ejecutando seeders..."
	$(SAIL) artisan db:seed

db-shell: ## Abrir shell de PostgreSQL
	$(SAIL) psql

# ============================================
# Laravel Artisan
# ============================================

key-generate: ## Generar APP_KEY
	$(SAIL) artisan key:generate

cache-clear: ## Limpiar todas las cachés
	@echo "🧹 Limpiando cachés..."
	$(SAIL) artisan cache:clear
	$(SAIL) artisan config:clear
	$(SAIL) artisan route:clear
	$(SAIL) artisan view:clear

optimize: ## Optimizar aplicación (cache config, routes, views)
	@echo "⚡ Optimizando aplicación..."
	$(SAIL) artisan config:cache
	$(SAIL) artisan route:cache
	$(SAIL) artisan view:cache

# ============================================
# Assets y NPM
# ============================================

dev: ## Compilar assets en modo desarrollo
	$(SAIL) npm run dev

watch: ## Compilar assets y observar cambios
	$(SAIL) npm run watch

prod: ## Compilar assets para producción
	$(SAIL) npm run prod

# ============================================
# Testing
# ============================================

test: ## Ejecutar tests
	$(SAIL) artisan test

test-coverage: ## Ejecutar tests con cobertura
	$(SAIL) artisan test --coverage

# ============================================
# Utilidades
# ============================================

shell: ## Abrir shell en el contenedor Laravel
	$(SAIL) shell

root-shell: ## Abrir shell como root en el contenedor Laravel
	$(SAIL) root-shell

tinker: ## Abrir Laravel Tinker
	$(SAIL) artisan tinker

mqtt-password: ## Configurar contraseña de Mosquitto MQTT
	@echo "🔐 Configurando contraseña MQTT (usuario: enertec)..."
	@echo "Ingresa la contraseña cuando se solicite: enertec2020**"
	$(SAIL) exec mosquitto mosquitto_passwd -c /mosquitto/config/passwd enertec
	@echo "✅ Contraseña configurada. Reiniciando Mosquitto..."
	$(DOCKER_COMPOSE) restart mosquitto

supervisor-status: ## Ver estado de procesos Supervisor
	$(SAIL) exec laravel.test supervisorctl status

supervisor-restart: ## Reiniciar procesos Supervisor
	$(SAIL) exec laravel.test supervisorctl restart all

# ============================================
# Limpieza
# ============================================

clean: ## Limpiar archivos temporales y cachés
	@echo "🧹 Limpiando archivos temporales..."
	@rm -rf bootstrap/cache/*.php
	@rm -rf storage/framework/cache/*
	@rm -rf storage/framework/sessions/*
	@rm -rf storage/framework/views/*
	@rm -rf storage/logs/*.log
	@echo "✅ Limpieza completada"

clean-all: down ## Detener servicios y eliminar volúmenes (¡CUIDADO! Borra la BD)
	@echo "⚠️  ADVERTENCIA: Esto eliminará todos los datos de la base de datos"
	@read -p "¿Estás seguro? [y/N] " -n 1 -r; \
	echo; \
	if [[ $$REPLY =~ ^[Yy]$$ ]]; then \
		echo "🗑️  Eliminando volúmenes..."; \
		$(SAIL) down -v; \
		echo "✅ Volúmenes eliminados"; \
	else \
		echo "❌ Operación cancelada"; \
	fi

# ============================================
# Información
# ============================================

status: ## Ver estado completo del proyecto
	@echo "📊 Estado del proyecto:"
	@echo "\n🐳 Contenedores Docker:"
	@$(SAIL) ps
	@echo "\n📦 Versiones:"
	@echo "PHP: $$($(SAIL) php -v | head -n 1)"
	@echo "Composer: $$($(SAIL) composer --version)"
	@echo "Node: $$($(SAIL) node -v)"
	@echo "NPM: $$($(SAIL) npm -v)"

urls: ## Mostrar URLs de acceso
	@echo "🌐 URLs de acceso:"
	@echo "  • Aplicación Web:    http://localhost"
	@echo "  • Echo Server:       https://localhost:8443"
	@echo "  • PostgreSQL:        localhost:5432"
	@echo "  • Redis:             localhost:6379"
	@echo "  • MQTT Broker:       localhost:1883"

# ============================================
# 🚀 PRODUCCIÓN - Docker Compose
# ============================================
# Estos comandos usan docker-compose.production.yml
# Para usar en el servidor Ubuntu de producción
# ============================================

prod-deploy: network ## [PROD] Deployment completo a producción (sin seeders)
	@echo "🚀 Iniciando deployment a producción..."
	@if [ ! -f docker-compose.production.yml ]; then \
		echo "❌ Error: docker-compose.production.yml no encontrado"; \
		exit 1; \
	fi
	@if [ ! -f .env.production ] && [ ! -f .env ]; then \
		echo "❌ Error: .env.production no encontrado"; \
		echo "   Copia .env.production.example a .env.production y configúralo"; \
		exit 1; \
	fi
	@if [ ! -f docker/ssl/fluxai.pem ] || [ ! -f docker/ssl/fluxai.key ]; then \
		echo "❌ Error: Certificados SSL no encontrados en docker/ssl/"; \
		echo "   Coloca fluxai.pem y fluxai.key de Cloudflare Origin Certificate"; \
		exit 1; \
	fi
	@if [ ! -f .env ] && [ -f .env.production ]; then \
		echo "📋 Creando symlink .env -> .env.production..."; \
		ln -s .env.production .env; \
	fi
	@echo "🔨 Construyendo imágenes Docker..."
	@$(PROD_COMPOSE) build --no-cache
	@echo "🚀 Iniciando servicios..."
	@$(PROD_COMPOSE) up -d
	@echo "⏳ Esperando que la base de datos esté lista..."
	@sleep 15
	@$(MAKE) prod-create-db
	@echo "📦 Instalando dependencias de Composer..."
	@$(PROD_EXEC) composer install --no-dev --optimize-autoloader
	@echo "🔑 Generando APP_KEY..."
	@$(PROD_EXEC) php artisan key:generate --force
	@echo "🔑 Generando JWT_SECRET..."
	@$(PROD_EXEC) php artisan jwt:secret --force
	@echo "📦 Ejecutando migraciones..."
	@$(PROD_EXEC) php artisan migrate --force
	@echo "🔗 Creando symlink storage..."
	@$(PROD_EXEC) php artisan storage:link 2>/dev/null || echo "    Symlink ya existe"
	@echo "📦 Instalando dependencias NPM..."
	@$(PROD_EXEC) npm install
	@echo "🎨 Compilando assets para producción..."
	@$(PROD_EXEC) npm run prod
	@echo "⚡ Optimizando Laravel..."
	@$(PROD_EXEC) php artisan config:cache
	@$(PROD_EXEC) php artisan route:cache
	@echo "🔐 Configurando permisos..."
	@$(PROD_EXEC) chmod -R 775 storage bootstrap/cache
	@echo ""
	@echo "=========================================="
	@echo "   ✅ Deployment completado!"
	@echo "=========================================="
	@echo ""
	@echo "⚠️  IMPORTANTE - Ejecutar manualmente:"
	@echo "   1. Configurar MQTT: make prod-mqtt-password"
	@echo "   2. (Opcional) Cargar datos: make prod-seed"
	@echo ""

prod-deploy-fresh: network ## [PROD] Deployment completo desde cero (con migraciones fresh + seeders)
	@echo "🚀 Iniciando deployment FRESH a producción..."
	@if [ ! -f docker-compose.production.yml ]; then \
		echo "❌ Error: docker-compose.production.yml no encontrado"; \
		exit 1; \
	fi
	@if [ ! -f .env.production ] && [ ! -f .env ]; then \
		echo "❌ Error: .env.production no encontrado"; \
		echo "   Copia .env.production.example a .env.production y configúralo"; \
		exit 1; \
	fi
	@if [ ! -f docker/ssl/fluxai.pem ] || [ ! -f docker/ssl/fluxai.key ]; then \
		echo "❌ Error: Certificados SSL no encontrados en docker/ssl/"; \
		echo "   Coloca fluxai.pem y fluxai.key de Cloudflare Origin Certificate"; \
		exit 1; \
	fi
	@if [ ! -f .env ] && [ -f .env.production ]; then \
		echo "📋 Creando symlink .env -> .env.production..."; \
		ln -s .env.production .env; \
	fi
	@echo "🔨 Construyendo imágenes Docker..."
	@$(PROD_COMPOSE) build --no-cache
	@echo "🚀 Iniciando servicios..."
	@$(PROD_COMPOSE) up -d
	@echo "⏳ Esperando que la base de datos esté lista..."
	@sleep 15
	@$(MAKE) prod-create-db
	@echo "📦 Instalando dependencias de Composer..."
	@$(PROD_EXEC) composer install --no-dev --optimize-autoloader
	@echo "🔑 Generando APP_KEY..."
	@$(PROD_EXEC) php artisan key:generate --force
	@echo "🔑 Generando JWT_SECRET..."
	@$(PROD_EXEC) php artisan jwt:secret --force
	@echo "📦 Ejecutando migraciones fresh + seeders..."
	@$(PROD_EXEC) php artisan migrate:fresh --seed --force
	@echo "🔗 Creando symlink storage..."
	@$(PROD_EXEC) php artisan storage:link 2>/dev/null || echo "    Symlink ya existe"
	@echo "📦 Instalando dependencias NPM..."
	@$(PROD_EXEC) npm install
	@echo "🎨 Compilando assets para producción..."
	@$(PROD_EXEC) npm run prod
	@echo "⚡ Optimizando Laravel..."
	@$(PROD_EXEC) php artisan config:cache
	@$(PROD_EXEC) php artisan route:cache
	@echo "🔐 Configurando permisos..."
	@$(PROD_EXEC) chmod -R 775 storage bootstrap/cache
	@echo ""
	@echo "=========================================="
	@echo "   ✅ Deployment FRESH completado!"
	@echo "=========================================="
	@echo ""
	@echo "⚠️  IMPORTANTE - Ejecutar manualmente:"
	@echo "   Configurar MQTT: make prod-mqtt-password"
	@echo ""

prod-create-db: ## [PROD] Crear base de datos si no existe
	@echo "🗄️  Verificando/creando base de datos..."
	@DB_NAME=$$(grep DB_DATABASE .env.production 2>/dev/null | cut -d '=' -f2 || echo "enertec"); \
	$(PROD_COMPOSE) exec -T pgsql psql -U sail -d postgres -tc "SELECT 1 FROM pg_database WHERE datname = '$$DB_NAME'" | grep -q 1 || \
	$(PROD_COMPOSE) exec -T pgsql psql -U sail -d postgres -c "CREATE DATABASE $$DB_NAME;" && \
	echo "✅ Base de datos '$$DB_NAME' lista"

prod-up: ## [PROD] Iniciar servicios de producción
	@echo "🚀 Iniciando servicios de producción..."
	$(PROD_COMPOSE) up -d
	@echo "✅ Servicios iniciados"

prod-down: ## [PROD] Detener servicios de producción
	@echo "🛑 Deteniendo servicios de producción..."
	$(PROD_COMPOSE) down

prod-restart: ## [PROD] Reiniciar servicios de producción
	@echo "🔄 Reiniciando servicios de producción..."
	$(PROD_COMPOSE) restart

prod-logs: ## [PROD] Ver logs de producción (todos los servicios)
	$(PROD_COMPOSE) logs -f

prod-logs-app: ## [PROD] Ver logs del contenedor Laravel
	$(PROD_COMPOSE) logs -f laravel.test

prod-logs-nginx: ## [PROD] Ver logs de Nginx
	$(PROD_COMPOSE) logs -f nginx

prod-ps: ## [PROD] Ver estado de contenedores de producción
	$(PROD_COMPOSE) ps

prod-shell: ## [PROD] Abrir shell en el contenedor Laravel
	$(PROD_COMPOSE) exec laravel.test bash

prod-migrate: ## [PROD] Ejecutar migraciones en producción
	@echo "📦 Ejecutando migraciones en producción..."
	$(PROD_EXEC) php artisan migrate --force

prod-seed: ## [PROD] Ejecutar seeders en producción
	@echo "🌱 Ejecutando seeders en producción..."
	$(PROD_EXEC) php artisan db:seed --force
	@echo "✅ Seeders ejecutados"

prod-mqtt-password: ## [PROD] Configurar contraseña MQTT en producción (interactivo)
	@echo "🔐 Configurando contraseña MQTT..."
	@echo "   Usuario: enertec"
	@echo "   Usa la misma contraseña que MQTT_AUTH_PASSWORD en .env.production"
	@echo ""
	$(PROD_COMPOSE) exec mosquitto mosquitto_passwd -c /mosquitto/config/passwd enertec
	@echo ""
	@echo "🔧 Ajustando permisos del archivo..."
	@$(PROD_COMPOSE) exec -T mosquitto chmod 0700 /mosquitto/config/passwd
	@$(PROD_COMPOSE) exec -T mosquitto chown root:root /mosquitto/config/passwd
	@echo "🔄 Reiniciando servicios..."
	@$(PROD_COMPOSE) restart
	@sleep 10
	@echo "✅ Contraseña MQTT configurada y servicios reiniciados"

prod-update: ## [PROD] Actualizar código en producción (después de git pull)
	@echo "🔄 Actualizando producción..."
	@$(PROD_EXEC) composer install --no-dev --optimize-autoloader
	@$(PROD_EXEC) npm run prod
	@$(PROD_EXEC) php artisan migrate --force
	@$(PROD_EXEC) php artisan config:cache
	@$(PROD_EXEC) php artisan route:cache
	@echo "🔄 Reiniciando Laravel..."
	@$(PROD_COMPOSE) restart laravel.test
	@echo "✅ Actualización completada"

prod-cache-clear: ## [PROD] Limpiar cachés en producción
	@echo "🧹 Limpiando cachés de producción..."
	$(PROD_EXEC) php artisan cache:clear
	$(PROD_EXEC) php artisan config:clear
	$(PROD_EXEC) php artisan route:clear
	$(PROD_EXEC) php artisan view:clear
	@echo "✅ Cachés limpiadas"

prod-status: ## [PROD] Ver estado completo de producción
	@echo "📊 Estado de producción:"
	@echo ""
	@echo "🐳 Contenedores:"
	@$(PROD_COMPOSE) ps
	@echo ""
	@echo "📋 Procesos Supervisor:"
	@$(PROD_COMPOSE) exec laravel.test ps aux | grep -E "php|node|python" || true
