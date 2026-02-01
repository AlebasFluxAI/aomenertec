# 🚀 Guía de Deployment - Desarrollo vs Producción

## ⚠️ CAMBIOS CRÍTICOS PARA PRODUCCIÓN

### 1. **Laravel Echo Server - WebSocket Configuration**

**DESARROLLO (Local/Docker):**
- Usa `laravel-echo-server.local.json` con `"protocol": "http"`
- WebSocket sin SSL (ws://localhost:8443)
- Certificado auto-firmado ignorado

**PRODUCCIÓN:**
- Usa `laravel-echo-server.json` con `"protocol": "https"`
- WebSocket con SSL (wss://domain.com:8443)
- Certificado SSL válido REQUERIDO

#### Cómo funciona:

El archivo `docker/supervisor/supervisord.conf` contiene esta lógica:

```ini
[program:laravel-echo-server]
command=bash -c "if [ -f laravel-echo-server.local.json ]; then laravel-echo-server start laravel-echo-server.local.json; else laravel-echo-server start; fi"
```

**En desarrollo:**
- Existe `laravel-echo-server.local.json` → Usa HTTP
- ✅ Funciona sin problemas de certificado SSL

**En producción:**
- NO existe `laravel-echo-server.local.json` (está en `.gitignore`)
- Usa `laravel-echo-server.json` por defecto → HTTPS
- ✅ Conexiones seguras y encriptadas

---

### 2. **Certificado SSL para Producción**

En producción, necesitás actualizar estos paths en `laravel-echo-server.json`:

```json
{
  "protocol": "https",
  "sslCertPath": "/ruta/real/certificado.pem",
  "sslKeyPath": "/ruta/real/private.key",
  "sslPassphrase": ""
}
```

**Opciones:**
1. **Let's Encrypt** (Gratis) - Recomendado
2. **Certificado comercial** (Comodo, DigiCert, etc.)
3. **Proxy reverso** (Nginx/Apache) que maneje SSL y reenvíe a HTTP interno

---

### 3. **Variables de Entorno Críticas**

Verificá que `.env` de producción tenga:

```bash
# URLs para API externa (configuración remota de medidores)
AOM_API_URL=https://aom.enerteclatam.com
AOM_API_CONFIG_PATH=/api/v1/config
AOM_API_CLIENTS_PATH=/api/v1/clients

# MQTT Broker (puede ser diferente en producción)
DEFAULT_MQTT_HOST=tu-servidor-mqtt-produccion.com
DEFAULT_MQTT_PORT=1883
DEFAULT_MQTT_USER=enertec
DEFAULT_MQTT_PASSWORD=tu-password-seguro-aqui
```

---

## 📋 CHECKLIST PRE-DEPLOYMENT

### Antes de deployar a producción:

- [ ] **NO existe** `laravel-echo-server.local.json` en el servidor
- [ ] `laravel-echo-server.json` tiene `"protocol": "https"`
- [ ] Certificado SSL válido instalado y configurado
- [ ] Paths de SSL correctos en `laravel-echo-server.json`
- [ ] Variables de entorno de producción configuradas en `.env`
- [ ] MQTT Broker accesible desde servidor de producción
- [ ] Puerto 8443 abierto en firewall (para WebSockets)
- [ ] Supervisor configurado y corriendo todos los procesos

### Procesos que DEBEN correr en Supervisor:

```bash
✅ laravel-echo-server       # WebSocket server
✅ kafka-consumer            # Recibe datos MQTT
✅ queue-worker              # Procesa jobs (colas: spot,spot1,spot4,default)
✅ scheduler                 # Comandos programados (cada 1-2 min)
✅ mqtt-receiver             # Legacy MQTT receiver (Python)
✅ mqtt-realtime-receiver    # Legacy realtime receiver (Python)
✅ mqtt-realtime-forwarder   # Forwarder v1/mc/data → v1/mc/real_time
✅ laravel-server            # Servidor PHP
```

---

## 🔧 ARQUITECTURA RECOMENDADA PARA PRODUCCIÓN

### Opción 1: Echo Server con HTTPS directo

```
Internet
    ↓ HTTPS (443)
Nginx/Apache (Proxy Reverso)
    ↓ HTTP (80) → Laravel App
    ↓ WSS (8443) → Echo Server (HTTPS)
```

### Opción 2: Proxy completo (RECOMENDADO)

```
Internet
    ↓ HTTPS (443)
Nginx/Apache con SSL
    ├─ HTTP (80) → Laravel App
    └─ WS → Echo Server (HTTP interno en 8443)
```

Configuración Nginx para Opción 2:

```nginx
# WebSocket upgrade
location /socket.io {
    proxy_pass http://localhost:8443;
    proxy_http_version 1.1;
    proxy_set_header Upgrade $http_upgrade;
    proxy_set_header Connection "upgrade";
    proxy_set_header Host $host;
}
```

Con esta configuración:
- ✅ Echo Server corre en HTTP internamente (simple, sin certificado)
- ✅ Nginx maneja TODO el SSL
- ✅ Cliente conecta vía WSS (seguro)
- ✅ Más fácil de mantener y renovar certificados

---

## 📁 ARCHIVOS QUE NO VAN A PRODUCCIÓN

Agregados a `.gitignore`:

```
laravel-echo-server.local.json  # Solo desarrollo
laravel-echo-server.lock        # Runtime file
*.local.*                        # Cualquier archivo .local
```

---

## 🧪 TESTING WEBSOCKET EN PRODUCCIÓN

Una vez deployed, probá la conexión:

```bash
# Desde consola del navegador en la página
window.Echo.connector.socket.connected
// true = ✅ Conectado
// false = ❌ Problema

# Ver ID de conexión
window.Echo.socketId()
```

---

## ⚡ TROUBLESHOOTING PRODUCCIÓN

### WebSocket no conecta:

1. Verificar puerto 8443 abierto: `telnet tu-servidor.com 8443`
2. Verificar certificado SSL válido
3. Verificar logs: `tail -f /var/log/supervisor/laravel-echo-server.err.log`
4. Verificar que Echo Server está corriendo: `supervisorctl status`

### Datos en tiempo real no aparecen:

1. Verificar que `kafka-consumer` está corriendo
2. Verificar que `queue-worker` procesa jobs
3. Verificar que medidor envía a `v1/mc/real_time`
4. Verificar logs de broadcasting en Laravel

---

## 📞 CONTACTO

Para dudas sobre deployment, consultá AGENTS.md o la documentación técnica.

---

**Última actualización:** 2026-01-31
**Versión:** 1.0
