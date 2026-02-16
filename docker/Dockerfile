# Dockerfile Optimizado para build rápido
# Usa la imagen oficial de Sail + agrega solo lo esencial
#
# MQTT data ingestion is handled by PHP-MQTT (ConsumerCommand / mqtt:consume)
# Python scripts are legacy and no longer needed in production.

FROM laravelsail/php81-composer:latest

LABEL maintainer="IMERGI"

USER root
WORKDIR /var/www/html

# Instalar Node.js y dependencias del sistema en UNA SOLA capa con caché
RUN --mount=type=cache,target=/var/cache/apt,sharing=locked \
    --mount=type=cache,target=/var/lib/apt,sharing=locked \
    curl -fsSL https://deb.nodesource.com/setup_18.x | bash - && \
    apt-get install -y --no-install-recommends \
    nodejs \
    supervisor \
    libpq-dev \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    netcat-openbsd \
    && rm -rf /var/lib/apt/lists/*

# Instalar extensiones PHP requeridas
RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) gd pdo_pgsql pgsql

# Crear usuario y grupo sail (requerido por Laravel Sail)
RUN groupadd --force -g 1000 sail && \
    useradd -ms /bin/bash --no-user-group -g 1000 -u 1337 sail

# Instalar Laravel Echo Server con caché de npm
RUN --mount=type=cache,target=/root/.npm \
    npm install -g laravel-echo-server

# Copiar configuraciones
COPY docker/supervisor/supervisord.conf /etc/supervisor/conf.d/supervisord.conf
RUN mkdir -p /var/log/supervisor

COPY docker/init-scripts/init-db.sh /usr/local/bin/start-container
RUN chmod +x /usr/local/bin/start-container

EXPOSE 8000 8443

ENTRYPOINT ["start-container"]
