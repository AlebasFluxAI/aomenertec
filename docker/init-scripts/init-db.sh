#!/bin/bash

echo "🚀 Iniciando configuración de la aplicación..."

# Esperar a que PostgreSQL esté listo
echo "⏳ Esperando a que PostgreSQL esté listo..."
until nc -z pgsql 5432; do
    echo "PostgreSQL no está listo aún - esperando..."
    sleep 2
done
echo "✅ PostgreSQL está listo!"

# Generar APP_KEY si no existe
if [ -z "$APP_KEY" ] || [ "$APP_KEY" = "base64:CHANGEME" ]; then
    echo "🔑 Generando APP_KEY..."
    php artisan key:generate --force
else
    echo "✅ APP_KEY ya está configurado"
fi

# Ejecutar migraciones
echo "📦 Ejecutando migraciones de base de datos..."
php artisan migrate --force

# Ejecutar seeders (comentado por defecto)
# echo "🌱 Ejecutando seeders..."
# php artisan db:seed --force

# Configurar permisos
echo "🔐 Configurando permisos..."
chmod -R 777 storage bootstrap/cache

# Limpiar y cachear configuración
echo "🧹 Limpiando caché..."
php artisan config:clear
php artisan route:clear
php artisan view:clear

echo "✨ Configuración completada!"

# Iniciar supervisor (si está disponible)
if command -v supervisord &> /dev/null; then
    echo "🎯 Iniciando Supervisor..."
    exec /usr/bin/supervisord -c /etc/supervisor/conf.d/supervisord.conf
else
    echo "⚠️  Supervisor no encontrado, usando PHP-FPM directamente"
    exec php-fpm
fi
