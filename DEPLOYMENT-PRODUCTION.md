# Guía de Deployment a Producción - FluxAI

Esta guía documenta el proceso completo para desplegar FluxAI en un servidor Ubuntu 22.04 con Docker.

## Arquitectura

```
Usuario (HTTPS/443)
       ↓
   Cloudflare (SSL termination + proxy)
       ↓ (HTTPS con Origin Cert)
   Nginx Container (puerto 443)
       ├── /  → Laravel App (puerto 80 interno)
       └── /socket.io/ → Laravel Echo Server (puerto 8443 interno)

Puerto adicional abierto:
   └── 1883 → Mosquitto MQTT (para dispositivos IoT)
```

## Requisitos Previos

- Servidor Ubuntu 22.04 con Docker y Docker Compose V2 instalados
- Dominio configurado en Cloudflare (ej: `app.fluxai.solutions`)
- Acceso SSH al servidor
- `make` instalado (`sudo apt install make`)

---

## Fase 1: Configuración de Cloudflare

### 1.1 Generar Origin Certificate

1. Ir a Cloudflare Dashboard → Tu dominio → **SSL/TLS** → **Origin Server**
2. Click **"Create Certificate"**
3. Configurar:
   - Generate private key and CSR with Cloudflare
   - Hostnames: `app.fluxai.solutions` y `*.fluxai.solutions`
   - Validity: 15 years
4. Click **"Create"**
5. **IMPORTANTE**: Guardar el certificado (`.pem`) y la clave privada (`.key`)
   - Solo se muestran UNA vez

### 1.2 Configurar SSL Mode

- SSL/TLS → Overview → Seleccionar **"Full (strict)"**

### 1.3 Habilitar WebSockets

- Network → WebSockets → **ON**

### 1.4 Verificar DNS

- DNS → Records → Verificar que el subdominio apunta a la IP del servidor con **proxy naranja activado**

---

## Fase 2: Preparar el Servidor

### 2.1 Crear usuario dedicado (como root)

```bash
# Crear usuario
adduser fluxai

# Agregar a grupos necesarios
usermod -aG sudo fluxai
usermod -aG docker fluxai

# Cambiar al usuario
su - fluxai

# Verificar Docker (puede requerir reconectar)
docker ps
```

> Si `docker ps` da error de permisos, salir y volver a entrar como fluxai.

### 2.2 Configurar Firewall

```bash
sudo ufw allow 22/tcp    # SSH
sudo ufw allow 80/tcp    # HTTP (redirect)
sudo ufw allow 443/tcp   # HTTPS
sudo ufw allow 1883/tcp  # MQTT
sudo ufw enable
sudo ufw status
```

### 2.3 Instalar Make (si no está instalado)

```bash
sudo apt install make -y
```

### 2.4 Clonar Repositorio

```bash
cd ~
git clone https://github.com/tu-org/tu-repo.git fluxai
cd ~/fluxai
```

### 2.5 Copiar Certificados SSL

Copiar los archivos de Cloudflare al servidor:

```bash
# Desde tu máquina local:
scp fluxai.pem fluxai@IP_SERVIDOR:~/fluxai/docker/ssl/
scp fluxai.key fluxai@IP_SERVIDOR:~/fluxai/docker/ssl/
```

### 2.6 Configurar Variables de Entorno

```bash
cp .env.production.example .env.production
nano .env.production
```

**Variables críticas a configurar:**

```env
APP_KEY=                          # Se genera automáticamente
DB_PASSWORD=TuContraseñaSegura    # Sin caracteres especiales: % # * $ ! &
MQTT_AUTH_PASSWORD=OtraContraseña # Misma que usarás en Mosquitto
```

> **IMPORTANTE**: Evitar caracteres especiales en contraseñas (`%`, `#`, `*`, `$`, `!`, `&`) - causan problemas en Docker.

Crear symlink:

```bash
ln -s .env.production .env
```

---

## Fase 3: Deployment

### 3.1 Ejecutar Deployment con Make

```bash
make prod-deploy
```

Este comando ejecuta automáticamente:
1. ✅ Validación de archivos necesarios
2. ✅ Build de imágenes Docker
3. ✅ Inicio de servicios
4. ✅ Composer install (producción)
5. ✅ Generación de APP_KEY
6. ✅ Migraciones
7. ✅ NPM install y build
8. ✅ Optimización de Laravel
9. ✅ Configuración de permisos

### 3.2 Configurar Contraseña MQTT

Este paso es **manual** y solo se hace una vez:

```bash
make prod-mqtt-password
```

Ingresá la **misma contraseña** que está en `MQTT_AUTH_PASSWORD` de `.env.production`.

### 3.3 Reiniciar Servicios

```bash
make prod-restart
```

---

## Fase 4: Verificación

### 4.1 Verificar Contenedores

```bash
make prod-ps
```

Todos deben estar `Up`:
- nginx
- laravel.test
- pgsql
- redis
- mosquitto

### 4.2 Verificar Procesos Internos

```bash
make prod-status
```

### 4.3 Ver Logs

```bash
# Todos los servicios
make prod-logs

# Solo Laravel
make prod-logs-app

# Solo Nginx
make prod-logs-nginx
```

### 4.4 Verificar en Navegador

1. Abrir `https://tu-dominio.com`
2. Debería cargar la página de login
3. En DevTools → Network → WS → Verificar conexión WebSocket a `/socket.io/`

---

## Comandos de Producción (Makefile)

### Operaciones básicas

| Comando | Descripción |
|---------|-------------|
| `make prod-deploy` | Deployment completo (primera vez) |
| `make prod-up` | Iniciar servicios |
| `make prod-down` | Detener servicios |
| `make prod-restart` | Reiniciar servicios |
| `make prod-ps` | Ver estado de contenedores |
| `make prod-status` | Ver estado completo |

### Logs

| Comando | Descripción |
|---------|-------------|
| `make prod-logs` | Ver todos los logs |
| `make prod-logs-app` | Ver logs de Laravel |
| `make prod-logs-nginx` | Ver logs de Nginx |

### Mantenimiento

| Comando | Descripción |
|---------|-------------|
| `make prod-update` | Actualizar código (después de git pull) |
| `make prod-migrate` | Ejecutar migraciones |
| `make prod-cache-clear` | Limpiar cachés |
| `make prod-shell` | Shell dentro del contenedor |

### Configuración

| Comando | Descripción |
|---------|-------------|
| `make prod-mqtt-password` | Configurar contraseña MQTT |

---

## Actualizar Código en Producción

Después de hacer `git pull`:

```bash
cd ~/fluxai
git pull origin master
make prod-update
```

El comando `make prod-update` ejecuta:
- Composer install
- NPM build
- Migraciones
- Cache de configuración
- Reinicio de Laravel

---

## Troubleshooting

### Error: "could not find driver" (PostgreSQL)

El Dockerfile no tiene la extensión `pdo_pgsql`. Verificar que el Dockerfile incluya:

```dockerfile
RUN docker-php-ext-install -j$(nproc) gd pdo_pgsql pgsql
```

### Error: "nc: command not found"

El script de inicio usa `netcat`. Verificar que el Dockerfile incluya:

```dockerfile
apt-get install -y netcat-openbsd
```

### Error: "password authentication failed" (PostgreSQL)

La contraseña en `.env.production` no coincide con la del volumen de PostgreSQL. Solución:

```bash
make prod-down
docker volume rm fluxai_sail-pgsql
make prod-deploy
```

### Error: "ext-gd is missing"

El Dockerfile no tiene la extensión GD. Verificar que incluya:

```dockerfile
RUN apt-get install -y libpng-dev libjpeg-dev libfreetype6-dev
RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) gd
```

### Laravel Echo Server falla con "ENOTFOUND"

Verificar que `.env.production` NO tenga estas variables:

```env
# BORRAR ESTAS LÍNEAS SI EXISTEN:
LARAVEL_ECHO_SERVER_HOST=...
LARAVEL_ECHO_SERVER_PORT=...
```

El Echo Server se configura via `laravel-echo-server.production.json`, no via variables de entorno.

### Bad Gateway (502)

Nginx no puede conectar con Laravel. Verificar:

```bash
make prod-logs-app
```

Buscar errores en el inicio de supervisor o PHP.

---

## Archivos de Configuración

| Archivo | Propósito |
|---------|-----------|
| `docker-compose.production.yml` | Servicios Docker para producción |
| `.env.production` | Variables de entorno (NO commitear) |
| `laravel-echo-server.production.json` | Config de WebSockets |
| `docker/nginx/nginx.conf` | Reverse proxy con SSL |
| `docker/supervisor/supervisord.conf` | Procesos en background |
| `docker/ssl/fluxai.pem` | Certificado Cloudflare (NO commitear) |
| `docker/ssl/fluxai.key` | Clave privada (NO commitear) |

---

## Checklist Final

- [ ] Cloudflare Origin Certificate generado
- [ ] Cloudflare SSL mode: Full (strict)
- [ ] Cloudflare WebSockets: ON
- [ ] Usuario `fluxai` creado con acceso a Docker
- [ ] Firewall configurado (22, 80, 443, 1883)
- [ ] Repositorio clonado
- [ ] Certificados SSL en `docker/ssl/`
- [ ] `.env.production` configurado
- [ ] `make prod-deploy` ejecutado sin errores
- [ ] `make prod-mqtt-password` configurado
- [ ] `make prod-restart` ejecutado
- [ ] Todos los contenedores `Up` (`make prod-ps`)
- [ ] Web accesible via HTTPS
- [ ] Login funciona
- [ ] WebSocket conecta
