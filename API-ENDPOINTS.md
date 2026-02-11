# Documentación de Endpoints HTTP — aomenertec (Laravel)

> **Última actualización**: Febrero 2026
>
> **Base URL (desarrollo local)**: `http://localhost`
>
> **Base URL (producción)**: `https://app.fluxai.solutions`
>
> Todas las rutas definidas en `routes/V1/api.php` tienen el prefijo `/api` aplicado por el `RouteServiceProvider`.

---

## Tabla de Contenidos

1. [Autenticación y Middleware](#1-autenticación-y-middleware)
   - [1.1 JWT (Bearer Token)](#11-jwt-bearer-token)
   - [1.2 API Key Inter-servicio](#12-api-key-inter-servicio)
   - [1.3 Event Queue Validation](#13-event-queue-validation)
2. [Authentication (JWT)](#2-authentication-jwt)
   - [2.1 POST /api/auth/login](#21-post-apiauthlogin)
   - [2.2 POST /api/auth/logout](#22-post-apiauthlogout)
   - [2.3 POST /api/auth/refresh](#23-post-apiauthrefresh)
   - [2.4 POST /api/auth/me](#24-post-apiauthme)
3. [Órdenes de Trabajo (App Técnico)](#3-órdenes-de-trabajo-app-técnico)
   - [3.1 POST /api/auth/job-list](#31-post-apiauthjob-list)
   - [3.2 POST /api/auth/orders-update](#32-post-apiauthorders-update)
   - [3.3 POST /api/auth/order-create](#33-post-apiauthorder-create)
4. [Gestión de Firmware](#4-gestión-de-firmware)
   - [4.1 GET /api/auth/firmwares](#41-get-apiauthfirmwares)
   - [4.2 GET /api/auth/firmware/{id}](#42-get-apiauthfirmwareid)
   - [4.3 POST /api/auth/firmware-create](#43-post-apiauthfirmware-create)
5. [Entrada de Datos MQTT](#5-entrada-de-datos-mqtt)
   - [5.1 POST /api/v1/mqtt_input](#51-post-apiv1mqtt_input)
   - [5.2 POST /api/v1/mqtt_input/real-time](#52-post-apiv1mqtt_inputreal-time)
6. [Gestión de Clientes (Inter-servicio)](#6-gestión-de-clientes-inter-servicio)
   - [6.1 POST /api/v1/clients/client-add](#61-post-apiv1clientsclient-add)
7. [Event Logs](#7-event-logs)
   - [7.1 GET /api/v1/event_logs](#71-get-apiv1event_logs)
   - [7.2 GET /api/v1/event_logs/{eventLog}](#72-get-apiv1event_logseventlog)
   - [7.3 GET /api/v1/event_logs/ack_logs/{ackLog}](#73-get-apiv1event_logsack_logsacklog)
8. [Consulta de Datos por Rango de Fecha](#8-consulta-de-datos-por-rango-de-fecha)
   - [8.1 GET /api/v1/data/date-range](#81-get-apiv1datadate-range)
9. [Webhook de Notificaciones](#9-webhook-de-notificaciones)
   - [9.1 POST /api/v1/config/notification-webhook](#91-post-apiv1confignotification-webhook)
10. [Configuración de Dispositivos IoT (Inter-servicio)](#10-configuración-de-dispositivos-iot-inter-servicio)
    - [10.1 POST /api/v1/config/set-alert-limits](#101-post-apiv1configset-alert-limits)
    - [10.2 POST /api/v1/config/set-control-limits](#102-post-apiv1configset-control-limits)
    - [10.3 POST /api/v1/config/set-status-control-limits](#103-post-apiv1configset-status-control-limits)
    - [10.4 GET /api/v1/config/set-alert-time](#104-get-apiv1configset-alert-time)
    - [10.5 GET /api/v1/config/set-sampling-time](#105-get-apiv1configset-sampling-time)
    - [10.6 GET /api/v1/config/set-wifi-credentials](#106-get-apiv1configset-wifi-credentials)
    - [10.7 GET /api/v1/config/set-broker-credentials](#107-get-apiv1configset-broker-credentials)
    - [10.8 GET /api/v1/config/set-date](#108-get-apiv1configset-date)
    - [10.9 GET /api/v1/config/get-date](#109-get-apiv1configget-date)
    - [10.10 GET /api/v1/config/set-status-coil](#1010-get-apiv1configset-status-coil)
    - [10.11 GET /api/v1/config/get-status-coil](#1011-get-apiv1configget-status-coil)
    - [10.12 GET /api/v1/config/set-config-sensor](#1012-get-apiv1configset-config-sensor)
    - [10.13 GET /api/v1/config/get-config-sensor](#1013-get-apiv1configget-config-sensor)
    - [10.14 GET /api/v1/config/get-status-sensor](#1014-get-apiv1configget-status-sensor)
    - [10.15 GET /api/v1/config/get-status-connection](#1015-get-apiv1configget-status-connection)
    - [10.16 GET /api/v1/config/get-current-readings](#1016-get-apiv1configget-current-readings)
    - [10.17 GET /api/v1/config/set-status-real-time](#1017-get-apiv1configset-status-real-time)
    - [10.18 POST /api/v1/config/ota-update](#1018-post-apiv1configota-update)
    - [10.19 GET /api/v1/config/set-billing-day](#1019-get-apiv1configset-billing-day)
    - [10.20 GET /api/v1/config/set-status-service-coil](#1020-get-apiv1configset-status-service-coil)
    - [10.21 GET /api/v1/config/set-password-meter-app](#1021-get-apiv1configset-password-meter-app)
    - [10.22 GET /api/v1/config/get-password-meter](#1022-get-apiv1configget-password-meter)
11. [Webhook de Pagos (Wompi)](#11-webhook-de-pagos-wompi)
    - [11.1 POST /pagos/wompi/eventos](#111-post-pagoswompieventos)

---

## 1. Autenticación y Middleware

### 1.1 JWT (Bearer Token)

Los endpoints del grupo `/api/auth/*` utilizan autenticación JWT vía el guard `api`.

- **Header**: `Authorization: Bearer <token>`
- **Obtención del token**: `POST /api/auth/login`
- **Excepciones** (no requieren JWT): `login`, `firmwares`, `firmware/{id}`
- **TTL**: Configurable (valor por defecto del framework, generalmente 60 min)

### 1.2 API Key Inter-servicio

Los endpoints protegidos por el middleware `token_api_validation` requieren el header `x-api-key`.

**Middleware**: `TokenValidationMiddleware` (`app/Http/Middleware/V1/Api/TokenValidationMiddleware.php`)

**Lógica de validación**:
1. Verifica que el header `x-api-key` esté presente
2. Busca la key en la tabla `api_keys`
3. Verifica que sea válida (no expirada, status activo) mediante `ApiKey::isValid()`
4. Si falla cualquier paso: `HTTP 401 "Error al validar api key de cliente"`

### 1.3 Event Queue Validation

**Middleware**: `EventQueueValidatorMiddleware` — se aplica junto con `token_api_validation` en todas las rutas inter-servicio.

**Lógica**:
1. Detecta el tipo de evento según la URI del request
2. Busca el cliente asociado al serial
3. Verifica que no exista un evento del mismo tipo en estado "created" con menos de 45 segundos de antigüedad (anti-flood). Si existe: `HTTP 429 "Evento del mismo tipo en proceso"`
4. Crea un `AckLog` y un `EventLog` para trazabilidad
5. Inyecta headers internos: `event_log_header`, `api_event_header`, `ack_log_header`, `serial`

**Headers adicionales requeridos por el sistema de trazabilidad** (automáticos desde aomenertec-api):
- Estos headers son inyectados por el middleware y consumidos internamente; no es necesario enviarlos manualmente.

---

## 2. Authentication (JWT)

### 2.1 POST /api/auth/login

Obtiene un token JWT para autenticación posterior.

| Campo | Valor |
|-------|-------|
| **Método** | `POST` |
| **URL** | `/api/auth/login` |
| **Autenticación** | Ninguna |
| **Content-Type** | `application/json` |

**Body Parameters**:

| Parámetro | Tipo | Requerido | Descripción |
|-----------|------|-----------|-------------|
| `email` | string | Sí | Email del usuario |
| `password` | string | Sí | Contraseña del usuario |

**Ejemplo curl**:
```bash
curl -X POST http://localhost/api/auth/login \
  -H "Content-Type: application/json" \
  -d '{"email": "sadminprueba@fluxai.local", "password": "111111111"}'
```

**Respuesta exitosa (200)**:
```json
{
  "access_token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9...",
  "token_type": "bearer",
  "expires_in": 60
}
```

**Respuesta error (401)**:
```json
{
  "error": "Unauthorized"
}
```

---

### 2.2 POST /api/auth/logout

Invalida el token JWT actual.

| Campo | Valor |
|-------|-------|
| **Método** | `POST` |
| **URL** | `/api/auth/logout` |
| **Autenticación** | JWT Bearer Token |

**Ejemplo curl**:
```bash
curl -X POST http://localhost/api/auth/logout \
  -H "Authorization: Bearer <token>"
```

**Respuesta exitosa (200)**:
```json
{
  "message": "Successfully logged out"
}
```

---

### 2.3 POST /api/auth/refresh

Refresca el token JWT y devuelve uno nuevo.

| Campo | Valor |
|-------|-------|
| **Método** | `POST` |
| **URL** | `/api/auth/refresh` |
| **Autenticación** | JWT Bearer Token |

**Ejemplo curl**:
```bash
curl -X POST http://localhost/api/auth/refresh \
  -H "Authorization: Bearer <token>"
```

**Respuesta exitosa (200)**:
```json
{
  "access_token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9...",
  "token_type": "bearer",
  "expires_in": 60
}
```

---

### 2.4 POST /api/auth/me

Devuelve la información del usuario autenticado.

| Campo | Valor |
|-------|-------|
| **Método** | `POST` |
| **URL** | `/api/auth/me` |
| **Autenticación** | JWT Bearer Token |

**Ejemplo curl**:
```bash
curl -X POST http://localhost/api/auth/me \
  -H "Authorization: Bearer <token>"
```

**Respuesta exitosa (200)**:
```json
{
  "id": 1,
  "name": "Admin",
  "email": "sadminprueba@fluxai.local",
  "email_verified_at": "2024-01-01T00:00:00.000000Z",
  "created_at": "2024-01-01T00:00:00.000000Z",
  "updated_at": "2024-01-01T00:00:00.000000Z"
}
```

---

## 3. Órdenes de Trabajo (App Técnico)

### 3.1 POST /api/auth/job-list

Obtiene la lista de trabajos pendientes para el técnico autenticado. Devuelve los clientes asignados al técnico que tienen órdenes de trabajo abiertas y un equipo tipo "GABINETE".

| Campo | Valor |
|-------|-------|
| **Método** | `POST` |
| **URL** | `/api/auth/job-list` |
| **Autenticación** | JWT Bearer Token |

**Body Parameters**: Ninguno.

**Ejemplo curl**:
```bash
curl -X POST http://localhost/api/auth/job-list \
  -H "Authorization: Bearer <token>"
```

**Respuesta exitosa (200)**:
```json
[
  {
    "uid": "123456789",
    "did": "SN-001234",
    "ssid": "wifi_SN-001234",
    "password": "111222333",
    "nombre": "Cliente Ejemplo",
    "codigo_cliente": "CLT-001",
    "ubicacion": {"lat": 4.6097, "lng": -74.0817},
    "celular": "+573001234567",
    "pass": "jghsdjfg626FFDS5266s",
    "equipments": [
      {"type": "MEDIDOR ELECTRICO", "serial": "SN-001234"},
      {"type": "GABINETE", "serial": "GB-001234"}
    ],
    "orders": [
      {
        "id": 1,
        "status": "open",
        "type": "installation",
        "description": "Instalación de medidor",
        "open_at": "2024-06-01 10:00:00"
      }
    ]
  }
]
```

---

### 3.2 POST /api/auth/orders-update

Actualiza una orden de trabajo existente. Soporta subida de imágenes como evidencia y lectura de datos de microcontrolador para órdenes tipo "lectura".

| Campo | Valor |
|-------|-------|
| **Método** | `POST` |
| **URL** | `/api/auth/orders-update` |
| **Autenticación** | JWT Bearer Token |
| **Content-Type** | `multipart/form-data` |

**Body Parameters**:

| Parámetro | Tipo | Requerido | Descripción |
|-----------|------|-----------|-------------|
| `order` | string (JSON) | Sí | Objeto JSON stringificado con los datos de la orden |
| `order.id` | integer | Sí | ID de la orden a actualizar |
| `order.status` | string | No | Nuevo estado de la orden |
| `order.raw_json` | object | Condicional | Requerido si la orden es tipo "lectura". Contiene datos del microcontrolador con campo `timestamp` |
| `order.images` | array | No | Array de objetos `{name, description}` donde `name` refiere al campo del archivo subido |
| `<image_name>` | file | Condicional | Archivos de imagen (máximo 6MB cada uno) |

**Ejemplo curl**:
```bash
curl -X POST http://localhost/api/auth/orders-update \
  -H "Authorization: Bearer <token>" \
  -F 'order={"id": 1, "status": "closed", "images": [{"name": "foto1", "description": "Medidor instalado"}]}' \
  -F "foto1=@/ruta/a/imagen.jpg"
```

**Respuesta exitosa (200)**:
```json
{
  "success": true,
  "message": "Order updated successfully",
  "order": {
    "status": "closed"
  }
}
```

**Respuesta error — Orden no encontrada (404)**:
```json
{
  "success": false,
  "message": "Order not found"
}
```

**Respuesta error — Orden ya cerrada (409)**:
```json
{
  "success": false,
  "message": "The order is already closed."
}
```

**Respuesta error — raw_json faltante en orden tipo lectura (409)**:
```json
{
  "success": false,
  "message": "raw_json not found"
}
```

**Respuesta error — Imagen faltante (400)**:
```json
{
  "success": false,
  "message": "One or more images are missing in the request."
}
```

**Respuesta error — Imagen excede 6MB (413)**:
```json
{
  "success": false,
  "message": "The image foto1 exceeds the maximum size of 6MB."
}
```

---

### 3.3 POST /api/auth/order-create

Crea una nueva orden de trabajo para un cliente.

| Campo | Valor |
|-------|-------|
| **Método** | `POST` |
| **URL** | `/api/auth/order-create` |
| **Autenticación** | JWT Bearer Token |
| **Content-Type** | `application/json` |

**Body Parameters**:

| Parámetro | Tipo | Requerido | Descripción |
|-----------|------|-----------|-------------|
| `client_id` | integer | Sí | ID del cliente |
| `order_type` | string | Sí | Tipo de orden de trabajo |

**Ejemplo curl**:
```bash
curl -X POST http://localhost/api/auth/order-create \
  -H "Authorization: Bearer <token>" \
  -H "Content-Type: application/json" \
  -d '{"client_id": 1, "order_type": "installation"}'
```

**Respuesta exitosa (200)**:
```json
{
  "success": true,
  "message": "Order created successfully",
  "order": {
    "id": 5,
    "status": "open",
    "type": "installation",
    "open_at": "2024-06-15 14:30:00",
    "description": "orden prueba",
    "technician_id": 1,
    "client_id": 1
  }
}
```

**Respuesta error — Cliente no encontrado (409)**:
```json
{
  "success": false,
  "message": "Client not found"
}
```

---

## 4. Gestión de Firmware

### 4.1 GET /api/auth/firmwares

Lista todos los firmwares disponibles. Requiere contraseña fija de acceso (sin JWT).

| Campo | Valor |
|-------|-------|
| **Método** | `GET` |
| **URL** | `/api/auth/firmwares` |
| **Autenticación** | Contraseña en query param |

**Query Parameters**:

| Parámetro | Tipo | Requerido | Validación | Descripción |
|-----------|------|-----------|------------|-------------|
| `password` | string | Sí | `required` | Contraseña de acceso fija: `123456789` |

**Ejemplo curl**:
```bash
curl -X GET "http://localhost/api/auth/firmwares?password=123456789"
```

**Respuesta exitosa (200)**:
```json
[
  {
    "id": 1,
    "name": "Firmware v2.1",
    "version": "2.1.0",
    "description": "Mejoras de conectividad MQTT",
    "created_at": "2024-06-01T00:00:00.000000Z",
    "updated_at": "2024-06-01T00:00:00.000000Z"
  }
]
```

**Respuesta error — Contraseña inválida (404)**:
```json
{
  "error": "Invalidate password"
}
```

---

### 4.2 GET /api/auth/firmware/{id}

Obtiene la URL del archivo de un firmware específico por su ID. Requiere contraseña fija de acceso (sin JWT).

| Campo | Valor |
|-------|-------|
| **Método** | `GET` |
| **URL** | `/api/auth/firmware/{id}` |
| **Autenticación** | Contraseña en query param |

**Path Parameters**:

| Parámetro | Tipo | Descripción |
|-----------|------|-------------|
| `id` | integer | ID del firmware |

**Query Parameters**:

| Parámetro | Tipo | Requerido | Validación | Descripción |
|-----------|------|-----------|------------|-------------|
| `password` | string | Sí | `required` | Contraseña de acceso fija: `123456789` |

**Ejemplo curl**:
```bash
curl -X GET "http://localhost/api/auth/firmware/1?password=123456789"
```

**Respuesta exitosa (200)**:
```json
{
  "url": "https://s3.amazonaws.com/bucket/firmwares/firmware_v2.1.bin"
}
```

**Respuesta error — Firmware no encontrado (404)**:
```json
{
  "error": "Firmware not found"
}
```

**Respuesta error — Evidencia no encontrada (404)**:
```json
{
  "error": "Evidence not found"
}
```

---

### 4.3 POST /api/auth/firmware-create

Crea un nuevo firmware y sube el archivo binario a S3.

| Campo | Valor |
|-------|-------|
| **Método** | `POST` |
| **URL** | `/api/auth/firmware-create` |
| **Autenticación** | JWT Bearer Token |
| **Content-Type** | `multipart/form-data` |

**Body Parameters**:

| Parámetro | Tipo | Requerido | Validación | Descripción |
|-----------|------|-----------|------------|-------------|
| `name` | string | Sí | `required\|string\|max:255` | Nombre del firmware |
| `version` | string | Sí | `required\|string\|max:255` | Versión del firmware |
| `description` | string | Sí | `required\|string` | Descripción del firmware |
| `file` | file | Sí | `required\|file` | Archivo binario del firmware |

**Ejemplo curl**:
```bash
curl -X POST http://localhost/api/auth/firmware-create \
  -H "Authorization: Bearer <token>" \
  -F "name=Firmware v3.0" \
  -F "version=3.0.0" \
  -F "description=Nueva versión con soporte OTA mejorado" \
  -F "file=@/ruta/a/firmware.bin"
```

**Respuesta exitosa (201)**:
```json
{
  "message": "Firmware created successfully",
  "firmware": {
    "id": 2,
    "name": "Firmware v3.0",
    "version": "3.0.0",
    "description": "Nueva versión con soporte OTA mejorado",
    "created_at": "2024-06-15T14:30:00.000000Z",
    "updated_at": "2024-06-15T14:30:00.000000Z"
  }
}
```

---

## 5. Entrada de Datos MQTT

Estos endpoints son llamados por los scripts Python (`receiveMqttEvent.py`, `receiveMqttRealTimeEvent.py`) que reciben mensajes del broker Mosquitto y los envían al backend Laravel.

### 5.1 POST /api/v1/mqtt_input

Recibe datos de telemetría MQTT y los despacha al job `SaveMicrocontrollerDataJob` para procesamiento asíncrono.

| Campo | Valor |
|-------|-------|
| **Método** | `POST` |
| **URL** | `/api/v1/mqtt_input` |
| **Autenticación** | Ninguna |
| **Content-Type** | `application/json` |

**Body Parameters**:

| Parámetro | Tipo | Requerido | Descripción |
|-----------|------|-----------|-------------|
| `message` | string | Sí | Mensaje MQTT raw (payload del topic `v1/mc/data`) |

**Ejemplo curl**:
```bash
curl -X POST http://localhost/api/v1/mqtt_input \
  -H "Content-Type: application/json" \
  -d '{"message": "<payload_binario_base64_o_hex>"}'
```

**Respuesta exitosa**: No retorna body explícito (HTTP 200 implícito por dispatch).

---

### 5.2 POST /api/v1/mqtt_input/real-time

Recibe datos MQTT de tiempo real y los despacha al job `PushRealTimeMicrocontrollerDataJob` para broadcasting por WebSockets.

| Campo | Valor |
|-------|-------|
| **Método** | `POST` |
| **URL** | `/api/v1/mqtt_input/real-time` |
| **Autenticación** | Ninguna |
| **Content-Type** | `application/json` |

**Body Parameters**:

| Parámetro | Tipo | Requerido | Descripción |
|-----------|------|-----------|-------------|
| `message` | string | Sí | Mensaje MQTT raw (payload del topic de tiempo real) |

**Ejemplo curl**:
```bash
curl -X POST http://localhost/api/v1/mqtt_input/real-time \
  -H "Content-Type: application/json" \
  -d '{"message": "<payload_tiempo_real>"}'
```

**Respuesta exitosa**: No retorna body explícito (HTTP 200 implícito por dispatch).

---

## 6. Gestión de Clientes (Inter-servicio)

### 6.1 POST /api/v1/clients/client-add

Agrega un cliente al sistema. Llamado desde aomenertec-api para sincronizar la creación de clientes. Valida que el serial del medidor eléctrico exista y no esté asignado a otro cliente.

| Campo | Valor |
|-------|-------|
| **Método** | `POST` |
| **URL** | `/api/v1/clients/client-add` |
| **Autenticación** | `x-api-key` header + Event Queue Validation |
| **Content-Type** | `application/json` |

**Headers requeridos**:

| Header | Tipo | Descripción |
|--------|------|-------------|
| `x-api-key` | string | API key válida registrada en tabla `api_keys` |

**Body Parameters**:

| Parámetro | Tipo | Requerido | Validación | Descripción |
|-----------|------|-----------|------------|-------------|
| `serial` | string | Sí | Debe existir como equipo tipo "MEDIDOR ELECTRICO"; no debe estar asignado a otro cliente | Serial del medidor eléctrico |

> **Nota**: El servicio valida el serial contra la tabla `equipments` filtrando por `equipment_type = 'MEDIDOR ELECTRICO'`. Si el equipo ya tiene `assigned = true` o `has_clients = true`, se rechaza.

**Ejemplo curl**:
```bash
curl -X POST http://localhost/api/v1/clients/client-add \
  -H "Content-Type: application/json" \
  -H "x-api-key: <tu_api_key>" \
  -d '{"serial": "SN-001234"}'
```

**Respuesta exitosa (200)**:
```json
{
  "data": [
    {
      "equipments": [
        {"type": "MEDIDOR ELECTRICO", "serial": "SN-001234"},
        {"type": "GABINETE", "serial": "GB-001234"}
      ]
    }
  ]
}
```

**Respuesta error — Validación fallida (200 con error en body)**:
```json
{
  "data": {
    "error": {
      "code": 400,
      "message": "La solicitud enviada al servidor es incorrecta o no se puede procesar",
      "details": {
        "serial": ["El medidor electrico con serial SN-XXXXX no existe"]
      }
    }
  }
}
```

**Respuesta error — API Key inválida (401)**:
```text
Error al validar api key de cliente
```

**Respuesta error — Rate limit de eventos (429)**:
```text
Evento del mismo tipo en proceso
```

---

## 7. Event Logs

Todos los endpoints de esta sección requieren `x-api-key` + Event Queue Validation middleware.

### 7.1 GET /api/v1/event_logs

Obtiene una colección paginada de event logs con filtros opcionales.

| Campo | Valor |
|-------|-------|
| **Método** | `GET` |
| **URL** | `/api/v1/event_logs` |
| **Autenticación** | `x-api-key` header + Event Queue Validation |

**Query Parameters** (del trait `PaginatorTrait` / `FilterTrait`):

| Parámetro | Tipo | Requerido | Descripción |
|-----------|------|-----------|-------------|
| `s_f` | string | No | Campo por el cual buscar (search field) |
| `s` | string | No | Valor de búsqueda (search value) |
| `o_b` | string | No | Dirección del ordenamiento: `ASC` o `DESC` |
| `o_f` | string | No | Campo por el cual ordenar (order field) |
| `serial` | string | Sí | Serial del equipo (requerido por Event Queue middleware) |

**Ejemplo curl**:
```bash
curl -X GET "http://localhost/api/v1/event_logs?serial=SN-001234&s_f=event&s=set-alert&o_b=DESC&o_f=created_at" \
  -H "x-api-key: <tu_api_key>"
```

**Respuesta exitosa (200)**:
```json
{
  "data": [
    {
      "id": 1,
      "transaction_id": 10,
      "request_type": "client_main_server_request",
      "event": "set-alert-limits",
      "request_endpoint": "/api/v1/config/set-alert-limits",
      "webhook": null,
      "request_json": "{\"serial\": \"SN-001234\", ...}",
      "response_json": "{...}",
      "status": "successful",
      "serial": "SN-001234",
      "name": "set-alert-limits_client_main_server_request",
      "request_at": "2024-06-15T14:30:00.000000Z",
      "response_at": "2024-06-15T14:30:05.000000Z"
    }
  ],
  "links": { "..." },
  "meta": { "..." }
}
```

---

### 7.2 GET /api/v1/event_logs/{eventLog}

Obtiene un event log específico por su ID (route model binding).

| Campo | Valor |
|-------|-------|
| **Método** | `GET` |
| **URL** | `/api/v1/event_logs/{eventLog}` |
| **Autenticación** | `x-api-key` header + Event Queue Validation |

**Path Parameters**:

| Parámetro | Tipo | Descripción |
|-----------|------|-------------|
| `eventLog` | integer | ID del event log |

**Query Parameters**:

| Parámetro | Tipo | Requerido | Descripción |
|-----------|------|-----------|-------------|
| `serial` | string | Sí | Serial del equipo (requerido por Event Queue middleware) |

**Ejemplo curl**:
```bash
curl -X GET "http://localhost/api/v1/event_logs/42?serial=SN-001234" \
  -H "x-api-key: <tu_api_key>"
```

**Respuesta exitosa (200)**:
```json
{
  "data": {
    "id": 42,
    "transaction_id": 15,
    "request_type": "client_main_server_request",
    "event": "set-status-coil",
    "request_endpoint": "/api/v1/config/set-status-coil",
    "webhook": null,
    "request_json": "{...}",
    "response_json": "{...}",
    "status": "successful",
    "serial": "SN-001234",
    "name": "set-status-coil_client_main_server_request",
    "request_at": "2024-06-15T14:30:00.000000Z",
    "response_at": "2024-06-15T14:30:05.000000Z"
  }
}
```

---

### 7.3 GET /api/v1/event_logs/ack_logs/{ackLog}

Obtiene todos los event logs asociados a un AckLog específico (una transacción).

| Campo | Valor |
|-------|-------|
| **Método** | `GET` |
| **URL** | `/api/v1/event_logs/ack_logs/{ackLog}` |
| **Autenticación** | `x-api-key` header + Event Queue Validation |

**Path Parameters**:

| Parámetro | Tipo | Descripción |
|-----------|------|-------------|
| `ackLog` | integer | ID del AckLog (transacción) |

**Query Parameters**:

| Parámetro | Tipo | Requerido | Descripción |
|-----------|------|-----------|-------------|
| `serial` | string | Sí | Serial del equipo (requerido por Event Queue middleware) |

**Ejemplo curl**:
```bash
curl -X GET "http://localhost/api/v1/event_logs/ack_logs/15?serial=SN-001234" \
  -H "x-api-key: <tu_api_key>"
```

**Respuesta exitosa (200)**:
```json
{
  "data": [
    {
      "id": 42,
      "transaction_id": 15,
      "request_type": "client_main_server_request",
      "event": "set-status-coil",
      "request_endpoint": "/api/v1/config/set-status-coil",
      "webhook": null,
      "request_json": "{...}",
      "response_json": "{...}",
      "status": "successful",
      "serial": "SN-001234",
      "name": "set-status-coil_client_main_server_request",
      "request_at": "2024-06-15T14:30:00.000000Z",
      "response_at": "2024-06-15T14:30:05.000000Z"
    },
    {
      "id": 43,
      "transaction_id": 15,
      "request_type": "main_server_mc_request",
      "event": "set-status-coil",
      "..."
    }
  ]
}
```

---

## 8. Consulta de Datos por Rango de Fecha

### 8.1 GET /api/v1/data/date-range

Consulta datos de microcontrolador por rango de fechas para un serial específico. Devuelve datos paginados.

| Campo | Valor |
|-------|-------|
| **Método** | `GET` |
| **URL** | `/api/v1/data/date-range` |
| **Autenticación** | `x-api-key` header + Event Queue Validation |

**Query Parameters**:

| Parámetro | Tipo | Requerido | Validación | Descripción |
|-----------|------|-----------|------------|-------------|
| `serial` | string | Sí | Debe existir como medidor eléctrico, pertenecer a la organización del usuario, y estar asignado a un cliente | Serial del medidor eléctrico |
| `fecha_inicio` | string/integer | Sí | Timestamp Unix o formato `Y-m-d H:i:s` | Fecha de inicio del rango |
| `fecha_fin` | string/integer | Sí | Timestamp Unix o formato `Y-m-d H:i:s`. Debe ser diferente y posterior a `fecha_inicio` | Fecha de fin del rango |

**Ejemplo curl** (con timestamps Unix):
```bash
curl -X GET "http://localhost/api/v1/data/date-range?serial=SN-001234&fecha_inicio=1718400000&fecha_fin=1718486400" \
  -H "x-api-key: <tu_api_key>"
```

**Ejemplo curl** (con formato fecha):
```bash
curl -X GET "http://localhost/api/v1/data/date-range?serial=SN-001234&fecha_inicio=2024-06-15%2000:00:00&fecha_fin=2024-06-16%2000:00:00" \
  -H "x-api-key: <tu_api_key>"
```

**Respuesta exitosa (200)**:
```json
{
  "data": [
    {
      "data": {
        "voltage_l1": 120.5,
        "current_l1": 5.2,
        "power_l1": 626.6,
        "energy_l1": 1250.3
      },
      "date": "2024-06-15",
      "hour": "14:30:00"
    },
    {
      "data": { "..." },
      "date": "2024-06-15",
      "hour": "14:32:00"
    }
  ],
  "links": { "..." },
  "meta": { "..." }
}
```

> **Nota**: Los campos `network_operator_id`, `latitude`, `longitude`, `flags`, y `timestamp` son eliminados del `raw_json` en la respuesta.

---

## 9. Webhook de Notificaciones

### 9.1 POST /api/v1/config/notification-webhook

Recibe notificaciones del sistema de eventos (desde aomenertec-api). Publica el evento por MQTT en el canal `aom/chanel` y crea una alerta informativa si no existe.

| Campo | Valor |
|-------|-------|
| **Método** | `POST` |
| **URL** | `/api/v1/config/notification-webhook` |
| **Autenticación** | Ninguna (fuera del grupo de middleware `token_api_validation`) |
| **Content-Type** | `application/json` |

**Body Parameters (JSON)**:

| Parámetro | Tipo | Requerido | Descripción |
|-----------|------|-----------|-------------|
| `id_event` | integer | Sí | ID del EventLog asociado |
| `serial` | string | Sí | Serial del dispositivo |
| `*` | mixed | No | Cualquier dato adicional del evento (se reenvía completo por MQTT) |

**Ejemplo curl**:
```bash
curl -X POST http://localhost/api/v1/config/notification-webhook \
  -H "Content-Type: application/json" \
  -d '{
    "id_event": 42,
    "serial": "SN-001234",
    "status": "successful",
    "event": "set-status-coil"
  }'
```

**Respuesta exitosa (200)**:
```json
{
  "status": "success",
  "message": "Webhook procesado exitosamente",
  "request_json": {
    "id_event": 42,
    "serial": "SN-001234",
    "status": "successful",
    "event": "set-status-coil"
  }
}
```

---

## 10. Configuración de Dispositivos IoT (Inter-servicio)

Todos los endpoints de esta sección comparten las siguientes características:

- **Autenticación**: `x-api-key` header + Event Queue Validation middleware
- **Flujo**: El endpoint recibe la solicitud → empaqueta el mensaje en formato binario según `config/data-frame.php` → lo envía por MQTT al topic `v1/mc/config/{serial}` → retorna respuesta con ID de transacción para tracking
- **Rate limiting**: Un solo evento del mismo tipo por equipo cada 45 segundos (controlado por `EventQueueValidatorMiddleware`)

### Formato de respuesta estándar (éxito)

Todos los endpoints de configuración retornan el mismo formato:

```json
{
  "data": {
    "message": "Se realizo el envio del mensaje",
    "detail": "Se espera respuesta del equipo para confirmar la conexión",
    "serial": "SN-001234",
    "transaction_id": 15,
    "event_id": 42
  }
}
```

### Formato de respuesta estándar (error MQTT)

Si falla la conexión MQTT:

```json
{
  "data": {
    "message": "Falló el envio del mensaje",
    "detail": {
      "status_code": 0,
      "error_message": "Connection refused"
    },
    "serial": "SN-001234",
    "transaction_id": 15,
    "event_id": 42
  }
}
```

### Validación del serial

La regla `ValidateSerialRule` aplicada en la mayoría de estos endpoints verifica:
1. Que exista un equipo tipo "MEDIDOR ELECTRICO" o "GABINETE" con ese serial
2. Que el equipo esté asignado a un cliente

Si falla, retorna error de validación HTTP 422.

---

### 10.1 POST /api/v1/config/set-alert-limits

Configura los límites de alerta para un dispositivo. Los campos dinámicos provienen de `config('data-frame.alert_config_frame')`.

| Campo | Valor |
|-------|-------|
| **Método** | `POST` |
| **URL** | `/api/v1/config/set-alert-limits` |
| **Autenticación** | `x-api-key` + Event Queue |
| **Content-Type** | `application/json` |

**Body Parameters**:

| Parámetro | Tipo | Requerido | Validación | Descripción |
|-----------|------|-----------|------------|-------------|
| `serial` | string | Sí | `ValidateSerialRule` | Serial del equipo |
| `max_*` | numeric | Sí | `required\|numeric` | Valores máximos de alerta (campos dinámicos de `alert_config_frame`) |
| `min_*` | numeric | Sí | `required\|numeric\|lte:max_*` | Valores mínimos de alerta. Debe ser menor o igual al máximo correspondiente |

> **Nota**: Los campos exactos (`max_voltage_l1`, `min_voltage_l1`, `max_current_l1`, etc.) dependen de la configuración en `config/data-frame.php`. Se excluyen: `network_operator_id`, `equipment_id`, `network_operator_new_id`, `equipment_new_id`.

**Ejemplo curl**:
```bash
curl -X POST http://localhost/api/v1/config/set-alert-limits \
  -H "x-api-key: <tu_api_key>" \
  -H "Content-Type: application/json" \
  -d '{
    "serial": "SN-001234",
    "max_voltage_l1": 140,
    "min_voltage_l1": 100,
    "max_current_l1": 30,
    "min_current_l1": 0
  }'
```

---

### 10.2 POST /api/v1/config/set-control-limits

Configura los límites de control para un dispositivo. Misma estructura de campos dinámicos que `set-alert-limits`.

| Campo | Valor |
|-------|-------|
| **Método** | `POST` |
| **URL** | `/api/v1/config/set-control-limits` |
| **Autenticación** | `x-api-key` + Event Queue |
| **Content-Type** | `application/json` |

**Body Parameters**: Idénticos a [set-alert-limits](#101-post-apiv1configset-alert-limits).

**Ejemplo curl**:
```bash
curl -X POST http://localhost/api/v1/config/set-control-limits \
  -H "x-api-key: <tu_api_key>" \
  -H "Content-Type: application/json" \
  -d '{
    "serial": "SN-001234",
    "max_voltage_l1": 135,
    "min_voltage_l1": 105,
    "max_current_l1": 25,
    "min_current_l1": 0
  }'
```

---

### 10.3 POST /api/v1/config/set-status-control-limits

Configura el estado (habilitado/deshabilitado) de cada grupo de control de límites. Los campos son dinámicos basados en `config('data-frame.alert_config_frame')` con prefijo `status_` en lugar de `max_`/`min_`.

| Campo | Valor |
|-------|-------|
| **Método** | `POST` |
| **URL** | `/api/v1/config/set-status-control-limits` |
| **Autenticación** | `x-api-key` + Event Queue |
| **Content-Type** | `application/json` |

**Body Parameters**:

| Parámetro | Tipo | Requerido | Validación | Descripción |
|-----------|------|-----------|------------|-------------|
| `serial` | string | Sí | `ValidateSerialRule` | Serial del equipo |
| `status_*` | numeric | Sí | `required\|numeric` | Estado de cada grupo de control (1 = habilitado, 0 = deshabilitado) |

**Ejemplo curl**:
```bash
curl -X POST http://localhost/api/v1/config/set-status-control-limits \
  -H "x-api-key: <tu_api_key>" \
  -H "Content-Type: application/json" \
  -d '{
    "serial": "SN-001234",
    "status_voltage_l1": 1,
    "status_current_l1": 0
  }'
```

---

### 10.4 GET /api/v1/config/set-alert-time

Configura los tiempos de alerta para un dispositivo. Los campos dinámicos provienen de `config('data-frame.alert_config_time_frame')`.

| Campo | Valor |
|-------|-------|
| **Método** | `GET` |
| **URL** | `/api/v1/config/set-alert-time` |
| **Autenticación** | `x-api-key` + Event Queue |

**Query Parameters**:

| Parámetro | Tipo | Requerido | Validación | Descripción |
|-----------|------|-----------|------------|-------------|
| `serial` | string | Sí | `ValidateSerialRule` | Serial del equipo |
| `*` | mixed | Sí | `required` | Campos dinámicos de `alert_config_time_frame` (se excluyen: `network_operator_id`, `equipment_id`, etc.) |

**Ejemplo curl**:
```bash
curl -X GET "http://localhost/api/v1/config/set-alert-time?serial=SN-001234&time_voltage_l1=30&time_current_l1=60" \
  -H "x-api-key: <tu_api_key>"
```

---

### 10.5 GET /api/v1/config/set-sampling-time

Configura el intervalo de muestreo de datos del dispositivo.

| Campo | Valor |
|-------|-------|
| **Método** | `GET` |
| **URL** | `/api/v1/config/set-sampling-time` |
| **Autenticación** | `x-api-key` + Event Queue |

**Query Parameters**:

| Parámetro | Tipo | Requerido | Validación | Descripción |
|-----------|------|-----------|------------|-------------|
| `serial` | string | Sí | `ValidateSerialRule` | Serial del equipo |
| `time_sampling_choice` | string | Sí | `required\|string\|in:hourly,daily,monthly` | Tipo de intervalo de muestreo |
| `data_per_interval` | numeric | Sí | `required\|numeric`. Valores válidos según `time_sampling_choice`: hourly → `1,2,3,4,6,12,60`; daily → `1,2,4,8`; monthly → `1,2` | Datos por intervalo |
| `data_per_seconds` | numeric | Sí | `required\|numeric\|between:0,254` | Datos por segundo |

**Ejemplo curl**:
```bash
curl -X GET "http://localhost/api/v1/config/set-sampling-time?serial=SN-001234&time_sampling_choice=hourly&data_per_interval=6&data_per_seconds=120" \
  -H "x-api-key: <tu_api_key>"
```

---

### 10.6 GET /api/v1/config/set-wifi-credentials

Configura las credenciales WiFi del dispositivo IoT.

| Campo | Valor |
|-------|-------|
| **Método** | `GET` |
| **URL** | `/api/v1/config/set-wifi-credentials` |
| **Autenticación** | `x-api-key` + Event Queue |

**Query Parameters**:

| Parámetro | Tipo | Requerido | Validación | Descripción |
|-----------|------|-----------|------------|-------------|
| `serial` | string | Sí | `ValidateSerialRule` | Serial del equipo |
| `ssid` | string | Sí | `required\|string` | Nombre de la red WiFi |
| `password` | string | Sí | `required\|string` | Contraseña de la red WiFi |

**Ejemplo curl**:
```bash
curl -X GET "http://localhost/api/v1/config/set-wifi-credentials?serial=SN-001234&ssid=MiRedWifi&password=clave123" \
  -H "x-api-key: <tu_api_key>"
```

---

### 10.7 GET /api/v1/config/set-broker-credentials

Configura las credenciales del broker MQTT en el dispositivo.

| Campo | Valor |
|-------|-------|
| **Método** | `GET` |
| **URL** | `/api/v1/config/set-broker-credentials` |
| **Autenticación** | `x-api-key` + Event Queue |

**Query Parameters**:

| Parámetro | Tipo | Requerido | Validación | Descripción |
|-----------|------|-----------|------------|-------------|
| `serial` | string | Sí | `ValidateSerialRule` | Serial del equipo |
| `host` | string | Sí | `required\|string` | Host del broker MQTT |
| `port` | string | Sí | `required\|string` | Puerto del broker MQTT |
| `user` | string | Sí | `required\|string` | Usuario del broker MQTT |
| `password` | string | Sí | `required\|string` | Contraseña del broker MQTT |

**Ejemplo curl**:
```bash
curl -X GET "http://localhost/api/v1/config/set-broker-credentials?serial=SN-001234&host=mqtt.example.com&port=1883&user=enertec&password=secret123" \
  -H "x-api-key: <tu_api_key>"
```

---

### 10.8 GET /api/v1/config/set-date

Sincroniza la fecha/hora del dispositivo con el servidor. El timestamp Unix se genera automáticamente en el servidor.

| Campo | Valor |
|-------|-------|
| **Método** | `GET` |
| **URL** | `/api/v1/config/set-date` |
| **Autenticación** | `x-api-key` + Event Queue |

**Query Parameters**:

| Parámetro | Tipo | Requerido | Validación | Descripción |
|-----------|------|-----------|------------|-------------|
| `serial` | string | Sí | `ValidateSerialRule` | Serial del equipo |

**Ejemplo curl**:
```bash
curl -X GET "http://localhost/api/v1/config/set-date?serial=SN-001234" \
  -H "x-api-key: <tu_api_key>"
```

---

### 10.9 GET /api/v1/config/get-date

Solicita la fecha/hora actual del dispositivo.

| Campo | Valor |
|-------|-------|
| **Método** | `GET` |
| **URL** | `/api/v1/config/get-date` |
| **Autenticación** | `x-api-key` + Event Queue |

**Query Parameters**:

| Parámetro | Tipo | Requerido | Validación | Descripción |
|-----------|------|-----------|------------|-------------|
| `serial` | string | Sí | `ValidateSerialRule` | Serial del equipo |

**Ejemplo curl**:
```bash
curl -X GET "http://localhost/api/v1/config/get-date?serial=SN-001234" \
  -H "x-api-key: <tu_api_key>"
```

---

### 10.10 GET /api/v1/config/set-status-coil

Enciende o apaga el relé (coil) del dispositivo.

| Campo | Valor |
|-------|-------|
| **Método** | `GET` |
| **URL** | `/api/v1/config/set-status-coil` |
| **Autenticación** | `x-api-key` + Event Queue |

**Query Parameters**:

| Parámetro | Tipo | Requerido | Validación | Descripción |
|-----------|------|-----------|------------|-------------|
| `serial` | string | Sí | `ValidateSerialRule` | Serial del equipo |
| `status` | boolean | Sí | `required\|boolean` | `true` (1) = encender, `false` (0) = apagar |

**Ejemplo curl**:
```bash
curl -X GET "http://localhost/api/v1/config/set-status-coil?serial=SN-001234&status=1" \
  -H "x-api-key: <tu_api_key>"
```

---

### 10.11 GET /api/v1/config/get-status-coil

Consulta el estado actual del relé (coil) del dispositivo.

| Campo | Valor |
|-------|-------|
| **Método** | `GET` |
| **URL** | `/api/v1/config/get-status-coil` |
| **Autenticación** | `x-api-key` + Event Queue |

**Query Parameters**:

| Parámetro | Tipo | Requerido | Validación | Descripción |
|-----------|------|-----------|------------|-------------|
| `serial` | string | Sí | `ValidateSerialRule` | Serial del equipo |

**Ejemplo curl**:
```bash
curl -X GET "http://localhost/api/v1/config/get-status-coil?serial=SN-001234" \
  -H "x-api-key: <tu_api_key>"
```

---

### 10.12 GET /api/v1/config/set-config-sensor

Configura el tipo de sensor del dispositivo.

| Campo | Valor |
|-------|-------|
| **Método** | `GET` |
| **URL** | `/api/v1/config/set-config-sensor` |
| **Autenticación** | `x-api-key` + Event Queue |

**Query Parameters**:

| Parámetro | Tipo | Requerido | Validación | Descripción |
|-----------|------|-----------|------------|-------------|
| `serial` | string | Sí | `ValidateSerialRule` | Serial del equipo |
| `type` | integer | Sí | `required\|in:1,2,3` | Tipo de sensor. 1, 2 o 3 |

**Ejemplo curl**:
```bash
curl -X GET "http://localhost/api/v1/config/set-config-sensor?serial=SN-001234&type=2" \
  -H "x-api-key: <tu_api_key>"
```

---

### 10.13 GET /api/v1/config/get-config-sensor

Consulta la configuración actual del sensor del dispositivo.

| Campo | Valor |
|-------|-------|
| **Método** | `GET` |
| **URL** | `/api/v1/config/get-config-sensor` |
| **Autenticación** | `x-api-key` + Event Queue |

**Query Parameters**:

| Parámetro | Tipo | Requerido | Validación | Descripción |
|-----------|------|-----------|------------|-------------|
| `serial` | string | Sí | `ValidateSerialRule` | Serial del equipo |

**Ejemplo curl**:
```bash
curl -X GET "http://localhost/api/v1/config/get-config-sensor?serial=SN-001234" \
  -H "x-api-key: <tu_api_key>"
```

---

### 10.14 GET /api/v1/config/get-status-sensor

Consulta el estado actual del sensor del dispositivo.

| Campo | Valor |
|-------|-------|
| **Método** | `GET` |
| **URL** | `/api/v1/config/get-status-sensor` |
| **Autenticación** | `x-api-key` + Event Queue |

**Query Parameters**:

| Parámetro | Tipo | Requerido | Validación | Descripción |
|-----------|------|-----------|------------|-------------|
| `serial` | string | Sí | `ValidateSerialRule` | Serial del equipo |

**Ejemplo curl**:
```bash
curl -X GET "http://localhost/api/v1/config/get-status-sensor?serial=SN-001234" \
  -H "x-api-key: <tu_api_key>"
```

---

### 10.15 GET /api/v1/config/get-status-connection

Consulta el estado de conexión del dispositivo.

| Campo | Valor |
|-------|-------|
| **Método** | `GET` |
| **URL** | `/api/v1/config/get-status-connection` |
| **Autenticación** | `x-api-key` + Event Queue |

**Query Parameters**:

| Parámetro | Tipo | Requerido | Validación | Descripción |
|-----------|------|-----------|------------|-------------|
| `serial` | string | Sí | `ValidateSerialRule` | Serial del equipo |

**Ejemplo curl**:
```bash
curl -X GET "http://localhost/api/v1/config/get-status-connection?serial=SN-001234" \
  -H "x-api-key: <tu_api_key>"
```

---

### 10.16 GET /api/v1/config/get-current-readings

Solicita las lecturas actuales del dispositivo.

| Campo | Valor |
|-------|-------|
| **Método** | `GET` |
| **URL** | `/api/v1/config/get-current-readings` |
| **Autenticación** | `x-api-key` + Event Queue |

**Query Parameters**:

| Parámetro | Tipo | Requerido | Validación | Descripción |
|-----------|------|-----------|------------|-------------|
| `serial` | string | Sí | `ValidateSerialRule` | Serial del equipo |

**Ejemplo curl**:
```bash
curl -X GET "http://localhost/api/v1/config/get-current-readings?serial=SN-001234" \
  -H "x-api-key: <tu_api_key>"
```

---

### 10.17 GET /api/v1/config/set-status-real-time

Habilita o deshabilita el streaming de datos en tiempo real del dispositivo.

| Campo | Valor |
|-------|-------|
| **Método** | `GET` |
| **URL** | `/api/v1/config/set-status-real-time` |
| **Autenticación** | `x-api-key` + Event Queue |

**Query Parameters**:

| Parámetro | Tipo | Requerido | Validación | Descripción |
|-----------|------|-----------|------------|-------------|
| `serial` | string | Sí | `ValidateSerialRule` | Serial del equipo |
| `status` | boolean | Sí | `required\|boolean` | `true` (1) = habilitar tiempo real, `false` (0) = deshabilitar |

**Ejemplo curl**:
```bash
curl -X GET "http://localhost/api/v1/config/set-status-real-time?serial=SN-001234&status=1" \
  -H "x-api-key: <tu_api_key>"
```

---

### 10.18 POST /api/v1/config/ota-update

Envía una actualización OTA (Over-The-Air) al dispositivo. El sistema busca el firmware por ID, descarga el archivo desde S3, calcula el tamaño y lo empaqueta en el mensaje MQTT.

| Campo | Valor |
|-------|-------|
| **Método** | `POST` |
| **URL** | `/api/v1/config/ota-update` |
| **Autenticación** | `x-api-key` + Event Queue |
| **Content-Type** | `application/json` |

**Body Parameters**:

| Parámetro | Tipo | Requerido | Validación | Descripción |
|-----------|------|-----------|------------|-------------|
| `serial` | string | Sí | `ValidateSerialRule` | Serial del equipo |
| `version` | string | Sí | `required\|string` | ID del firmware a instalar (referencia al modelo Firmware) |

**Ejemplo curl**:
```bash
curl -X POST http://localhost/api/v1/config/ota-update \
  -H "x-api-key: <tu_api_key>" \
  -H "Content-Type: application/json" \
  -d '{"serial": "SN-001234", "version": "2"}'
```

---

### 10.19 GET /api/v1/config/set-billing-day

Configura el día de facturación del ciclo de cobro en el dispositivo.

| Campo | Valor |
|-------|-------|
| **Método** | `GET` |
| **URL** | `/api/v1/config/set-billing-day` |
| **Autenticación** | `x-api-key` + Event Queue |

**Query Parameters**:

| Parámetro | Tipo | Requerido | Validación | Descripción |
|-----------|------|-----------|------------|-------------|
| `serial` | string | Sí | `ValidateSerialRule` | Serial del equipo |
| `billing_day` | integer | Sí | `required\|integer\|min:1\|max:31` | Día del mes para facturación (1-31) |

**Ejemplo curl**:
```bash
curl -X GET "http://localhost/api/v1/config/set-billing-day?serial=SN-001234&billing_day=15" \
  -H "x-api-key: <tu_api_key>"
```

---

### 10.20 GET /api/v1/config/set-status-service-coil

Configura el estado del relé de servicio del dispositivo.

| Campo | Valor |
|-------|-------|
| **Método** | `GET` |
| **URL** | `/api/v1/config/set-status-service-coil` |
| **Autenticación** | `x-api-key` + Event Queue |

**Query Parameters**:

| Parámetro | Tipo | Requerido | Validación | Descripción |
|-----------|------|-----------|------------|-------------|
| `serial` | string | Sí | `ValidateSerialRule` | Serial del equipo |
| `status_service_coil` | boolean | Sí | `required\|boolean` | `true` (1) = habilitar, `false` (0) = deshabilitar |

**Ejemplo curl**:
```bash
curl -X GET "http://localhost/api/v1/config/set-status-service-coil?serial=SN-001234&status_service_coil=1" \
  -H "x-api-key: <tu_api_key>"
```

---

### 10.21 GET /api/v1/config/set-password-meter-app

Configura la contraseña de acceso al medidor desde la aplicación móvil.

| Campo | Valor |
|-------|-------|
| **Método** | `GET` |
| **URL** | `/api/v1/config/set-password-meter-app` |
| **Autenticación** | `x-api-key` + Event Queue |

**Query Parameters**:

| Parámetro | Tipo | Requerido | Validación | Descripción |
|-----------|------|-----------|------------|-------------|
| `serial` | string | Sí | `ValidateSerialRule` | Serial del equipo |
| `password` | string | Sí | `required\|string\|max:21` | Contraseña para el medidor (máximo 21 caracteres) |

**Ejemplo curl**:
```bash
curl -X GET "http://localhost/api/v1/config/set-password-meter-app?serial=SN-001234&password=newpass123" \
  -H "x-api-key: <tu_api_key>"
```

---

### 10.22 GET /api/v1/config/get-password-meter

Consulta la contraseña actual configurada en el medidor.

| Campo | Valor |
|-------|-------|
| **Método** | `GET` |
| **URL** | `/api/v1/config/get-password-meter` |
| **Autenticación** | `x-api-key` + Event Queue |

**Query Parameters**:

| Parámetro | Tipo | Requerido | Validación | Descripción |
|-----------|------|-----------|------------|-------------|
| `serial` | string | Sí | `ValidateSerialRule` | Serial del equipo |

**Ejemplo curl**:
```bash
curl -X GET "http://localhost/api/v1/config/get-password-meter?serial=SN-001234" \
  -H "x-api-key: <tu_api_key>"
```

---

## 11. Webhook de Pagos (Wompi)

### 11.1 POST /pagos/wompi/eventos

Webhook que recibe notificaciones de eventos de pago desde Wompi (pasarela de pagos colombiana). Esta ruta está definida en `routes/V1/web.php` (NO en api.php), por lo que **no** lleva el prefijo `/api`.

| Campo | Valor |
|-------|-------|
| **Método** | `POST` |
| **URL** | `/pagos/wompi/eventos` |
| **Autenticación** | Verificación de checksum SHA256 con Wompi Secret |
| **Content-Type** | `application/json` |

**Body Parameters (enviados por Wompi)**:

| Parámetro | Tipo | Descripción |
|-----------|------|-------------|
| `signature.properties` | array | Lista de propiedades usadas para generar el checksum |
| `signature.checksum` | string | Checksum SHA256 para verificación |
| `timestamp` | integer | Timestamp del evento |
| `data.transaction.reference` | string | Referencia de la transacción (código de factura en el sistema) |
| `data.transaction.status` | string | Estado de la transacción (`APPROVED`, `DECLINED`, etc.) |
| `data.transaction.*` | mixed | Propiedades adicionales de la transacción |

**Verificación de integridad**:
1. Concatena los valores de `data.transaction[property]` según el orden de `signature.properties`
2. Agrega `timestamp` + `wompiSecret` (del network operator o config default)
3. Calcula SHA256 y compara con `signature.checksum`

**Ejemplo curl** (simulación del webhook de Wompi):
```bash
curl -X POST http://localhost/pagos/wompi/eventos \
  -H "Content-Type: application/json" \
  -d '{
    "signature": {
      "properties": ["data.transaction.id", "data.transaction.status"],
      "checksum": "a1b2c3d4e5f6..."
    },
    "timestamp": 1718400000,
    "data": {
      "transaction": {
        "id": "txn-123",
        "reference": "INV-2024-001",
        "status": "APPROVED",
        "amount_in_cents": 50000
      }
    }
  }'
```

**Respuesta exitosa (200)**:
```text
Evento recibido y validado correctamente
```

**Respuesta error — Checksum inválido (400)**:
```text
Error: Checksum no válido
```

**Respuesta error — JSON inválido (400)**:
```text
Error: JSON no válido
```

**Respuesta error — Sin JSON (400)**:
```text
Error: No se recibió JSON
```

---

## Resumen de Endpoints

| # | Método | URL | Autenticación | Sección |
|---|--------|-----|---------------|---------|
| 1 | POST | `/api/auth/login` | Ninguna | Auth |
| 2 | POST | `/api/auth/logout` | JWT | Auth |
| 3 | POST | `/api/auth/refresh` | JWT | Auth |
| 4 | POST | `/api/auth/me` | JWT | Auth |
| 5 | POST | `/api/auth/job-list` | JWT | Órdenes |
| 6 | POST | `/api/auth/orders-update` | JWT | Órdenes |
| 7 | POST | `/api/auth/order-create` | JWT | Órdenes |
| 8 | GET | `/api/auth/firmwares` | Password | Firmware |
| 9 | GET | `/api/auth/firmware/{id}` | Password | Firmware |
| 10 | POST | `/api/auth/firmware-create` | JWT | Firmware |
| 11 | POST | `/api/v1/mqtt_input` | Ninguna | MQTT |
| 12 | POST | `/api/v1/mqtt_input/real-time` | Ninguna | MQTT |
| 13 | POST | `/api/v1/clients/client-add` | x-api-key | Clientes |
| 14 | GET | `/api/v1/event_logs` | x-api-key | Event Logs |
| 15 | GET | `/api/v1/event_logs/{eventLog}` | x-api-key | Event Logs |
| 16 | GET | `/api/v1/event_logs/ack_logs/{ackLog}` | x-api-key | Event Logs |
| 17 | GET | `/api/v1/data/date-range` | x-api-key | Datos |
| 18 | POST | `/api/v1/config/notification-webhook` | Ninguna | Webhooks |
| 19 | POST | `/api/v1/config/set-alert-limits` | x-api-key | Config IoT |
| 20 | POST | `/api/v1/config/set-control-limits` | x-api-key | Config IoT |
| 21 | POST | `/api/v1/config/set-status-control-limits` | x-api-key | Config IoT |
| 22 | GET | `/api/v1/config/set-alert-time` | x-api-key | Config IoT |
| 23 | GET | `/api/v1/config/set-sampling-time` | x-api-key | Config IoT |
| 24 | GET | `/api/v1/config/set-wifi-credentials` | x-api-key | Config IoT |
| 25 | GET | `/api/v1/config/set-broker-credentials` | x-api-key | Config IoT |
| 26 | GET | `/api/v1/config/set-date` | x-api-key | Config IoT |
| 27 | GET | `/api/v1/config/get-date` | x-api-key | Config IoT |
| 28 | GET | `/api/v1/config/set-status-coil` | x-api-key | Config IoT |
| 29 | GET | `/api/v1/config/get-status-coil` | x-api-key | Config IoT |
| 30 | GET | `/api/v1/config/set-config-sensor` | x-api-key | Config IoT |
| 31 | GET | `/api/v1/config/get-config-sensor` | x-api-key | Config IoT |
| 32 | GET | `/api/v1/config/get-status-sensor` | x-api-key | Config IoT |
| 33 | GET | `/api/v1/config/get-status-connection` | x-api-key | Config IoT |
| 34 | GET | `/api/v1/config/get-current-readings` | x-api-key | Config IoT |
| 35 | GET | `/api/v1/config/set-status-real-time` | x-api-key | Config IoT |
| 36 | POST | `/api/v1/config/ota-update` | x-api-key | Config IoT |
| 37 | GET | `/api/v1/config/set-billing-day` | x-api-key | Config IoT |
| 38 | GET | `/api/v1/config/set-status-service-coil` | x-api-key | Config IoT |
| 39 | GET | `/api/v1/config/set-password-meter-app` | x-api-key | Config IoT |
| 40 | GET | `/api/v1/config/get-password-meter` | x-api-key | Config IoT |
| 41 | POST | `/pagos/wompi/eventos` | Checksum Wompi | Pagos |

**Total: 41 endpoints documentados.**
