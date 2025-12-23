# Dockerfile Optimizado para build rápido
# Usa la imagen oficial de Sail + agrega solo lo esencial

FROM laravelsail/php81-composer:latest

LABEL maintainer="IMERGI"

USER root
WORKDIR /var/www/html

# OPTIMIZACIÓN 1: Instalar todo en UNA SOLA capa para aprovechar caché de Docker
# Solo instalamos lo que NO viene en la imagen de Sail
RUN --mount=type=cache,target=/var/cache/apt,sharing=locked \
    --mount=type=cache,target=/var/lib/apt,sharing=locked \
    apt-get update && apt-get install -y --no-install-recommends \
    python3-pip \
    supervisor \
    libpq-dev \
    && rm -rf /var/lib/apt/lists/*

# OPTIMIZACIÓN 2: Usar caché de npm para laravel-echo-server
RUN --mount=type=cache,target=/root/.npm \
    npm install -g laravel-echo-server

# OPTIMIZACIÓN 3: Instalar dependencias Python con caché de pip
COPY script/requirements.txt /tmp/requirements.txt
RUN --mount=type=cache,target=/root/.cache/pip \
    pip3 install --no-cache-dir -r /tmp/requirements.txt && rm /tmp/requirements.txt

# Copiar scripts y configuraciones
COPY script/*.py /usr/local/bin/
RUN chmod +x /usr/local/bin/*.py

COPY docker/supervisor/supervisord.conf /etc/supervisor/conf.d/supervisord.conf
RUN mkdir -p /var/log/supervisor

COPY docker/init-scripts/init-db.sh /usr/local/bin/start-container
RUN chmod +x /usr/local/bin/start-container

EXPOSE 8000 8443

ENTRYPOINT ["start-container"]
