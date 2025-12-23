# Makefile para Proyecto Enertec
# Desarrollo con Docker usando Laravel Sail

.PHONY: help build up down restart logs shell install setup migrate fresh test clean

# Variables
SAIL = ./vendor/bin/sail
DOCKER_COMPOSE = docker-compose

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
# Configuración Inicial
# ============================================

setup: ## Configuración inicial completa del proyecto
	@echo "⚙️  Configuración inicial del proyecto..."
	@if [ ! -f .env ]; then \
		echo "📋 Copiando .env.example a .env..."; \
		cp .env.example .env; \
	fi
	@echo "🔨 Construyendo imágenes Docker..."
	@$(SAIL) build
	@echo "🚀 Iniciando servicios..."
	@$(SAIL) up -d
	@echo "⏳ Esperando que los servicios estén listos..."
	@sleep 10
	@$(MAKE) install
	@echo "🔑 Generando APP_KEY..."
	@$(SAIL) artisan key:generate
	@echo "📦 Ejecutando migraciones..."
	@$(SAIL) artisan migrate
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
