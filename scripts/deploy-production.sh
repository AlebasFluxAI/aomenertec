#!/bin/bash

# =============================================================================
# FluxAI Production Deployment Script
# =============================================================================
# Este script automatiza el deployment en el servidor Ubuntu 22.04
# Ejecutar desde el directorio raíz del proyecto: /var/www/fluxai
# =============================================================================

set -e  # Exit on error

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

echo -e "${GREEN}========================================${NC}"
echo -e "${GREEN}   FluxAI Production Deployment${NC}"
echo -e "${GREEN}========================================${NC}"

# Check if we're in the right directory
if [ ! -f "docker-compose.production.yml" ]; then
    echo -e "${RED}Error: docker-compose.production.yml not found.${NC}"
    echo -e "${RED}Run this script from the project root directory.${NC}"
    exit 1
fi

# Check if .env.production exists
if [ ! -f ".env.production" ]; then
    echo -e "${RED}Error: .env.production not found.${NC}"
    echo -e "${YELLOW}Copy .env.production.example to .env.production and configure it.${NC}"
    exit 1
fi

# Check if SSL certificates exist
if [ ! -f "docker/ssl/fluxai.pem" ] || [ ! -f "docker/ssl/fluxai.key" ]; then
    echo -e "${RED}Error: SSL certificates not found.${NC}"
    echo -e "${YELLOW}Place fluxai.pem and fluxai.key in docker/ssl/${NC}"
    exit 1
fi

# Create .env symlink if it doesn't exist
if [ ! -f ".env" ]; then
    echo -e "${YELLOW}Creating .env symlink to .env.production...${NC}"
    ln -s .env.production .env
fi

echo -e "${GREEN}Step 1: Building Docker images...${NC}"
docker compose -f docker-compose.production.yml build --no-cache

echo -e "${GREEN}Step 2: Starting services...${NC}"
docker compose -f docker-compose.production.yml up -d

echo -e "${GREEN}Step 3: Waiting for database to be ready...${NC}"
sleep 10

echo -e "${GREEN}Step 4: Installing Composer dependencies...${NC}"
docker compose -f docker-compose.production.yml exec -T laravel.test composer install --no-dev --optimize-autoloader

echo -e "${GREEN}Step 5: Generating application key (if not set)...${NC}"
docker compose -f docker-compose.production.yml exec -T laravel.test php artisan key:generate --force

echo -e "${GREEN}Step 6: Running migrations...${NC}"
docker compose -f docker-compose.production.yml exec -T laravel.test php artisan migrate --force

echo -e "${GREEN}Step 7: Installing NPM dependencies...${NC}"
docker compose -f docker-compose.production.yml exec -T laravel.test npm install

echo -e "${GREEN}Step 8: Building frontend assets...${NC}"
docker compose -f docker-compose.production.yml exec -T laravel.test npm run prod

echo -e "${GREEN}Step 9: Optimizing Laravel for production...${NC}"
docker compose -f docker-compose.production.yml exec -T laravel.test php artisan config:cache
docker compose -f docker-compose.production.yml exec -T laravel.test php artisan route:cache
docker compose -f docker-compose.production.yml exec -T laravel.test php artisan view:cache

echo -e "${GREEN}Step 10: Setting file permissions...${NC}"
docker compose -f docker-compose.production.yml exec -T laravel.test chmod -R 775 storage bootstrap/cache
docker compose -f docker-compose.production.yml exec -T laravel.test chown -R www-data:www-data storage bootstrap/cache

echo -e "${GREEN}Step 11: Verifying services...${NC}"
docker compose -f docker-compose.production.yml ps

echo ""
echo -e "${GREEN}========================================${NC}"
echo -e "${GREEN}   Deployment Complete!${NC}"
echo -e "${GREEN}========================================${NC}"
echo ""
echo -e "${YELLOW}Next steps:${NC}"
echo "1. Configure MQTT password: docker compose -f docker-compose.production.yml exec mosquitto mosquitto_passwd -c /mosquitto/config/passwd enertec"
echo "2. Restart services: docker compose -f docker-compose.production.yml restart"
echo "3. Test the application: https://app.fluxai.solutions"
echo ""
echo -e "${YELLOW}Useful commands:${NC}"
echo "- View logs: docker compose -f docker-compose.production.yml logs -f"
echo "- View supervisor status: docker compose -f docker-compose.production.yml exec laravel.test supervisorctl status"
echo "- Restart Laravel: docker compose -f docker-compose.production.yml restart laravel.test"
