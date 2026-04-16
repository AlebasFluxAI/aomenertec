# Guia Tecnica de Integracion — API FluxAi / FluxAI

> **Version**: Marzo 2026
>
> **Base URL (produccion)**: `https://app.fluxai.solutions/api`
>
> **Audiencia**: Desarrolladores externos que integran sistemas propios con la plataforma de monitoreo energetico IoT de FluxAi/FluxAI.

---

## Tabla de Contenidos

1. [Vision General](#1-vision-general)
2. [Autenticacion](#2-autenticacion)
    - [2.1 API Key (x-api-key)](#21-api-key-x-api-key)
    - [2.2 JWT Bearer Token](#22-jwt-bearer-token)
3. [Middleware y Control de Flujo](#3-middleware-y-control-de-flujo)
    - [3.1 Token API Validation](#31-token-api-validation)
    - [3.2 Event Queue Validation (anti-flood)](#32-event-queue-validation-anti-flood)
    - [3.3 Rate Limiting Global](#33-rate-limiting-global)
4. [Endpoints — Autenticacion JWT](#4-endpoints--autenticacion-jwt)
    - [4.1 POST /auth/login](#41-post-authlogin)
    - [4.2 POST /auth/logout](#42-post-authlogout)
    - [4.3 POST /auth/refresh](#43-post-authrefresh)
    - [4.4 POST /auth/me](#44-post-authme)
5. [Endpoints — Ordenes de Trabajo (App Tecnico)](#5-endpoints--ordenes-de-trabajo-app-tecnico)
    - [5.1 POST /auth/job-list](#51-post-authjob-list)
    - [5.2 POST /auth/orders-update](#52-post-authorders-update)
    - [5.3 POST /auth/order-create](#53-post-authorder-create)
6. [Endpoints — Gestion de Firmware](#6-endpoints--gestion-de-firmware)
    - [6.1 GET /auth/firmwares](#61-get-authfirmwares)
    - [6.2 GET /auth/firmware/{id}](#62-get-authfirmwareid)
    - [6.3 POST /auth/firmware-create](#63-post-authfirmware-create)
7. [Endpoints — Entrada MQTT (Solo Interno)](#7-endpoints--entrada-mqtt-solo-interno)
8. [Endpoints — Gestion de Clientes](#8-endpoints--gestion-de-clientes)
    - [8.1 POST /v1/clients/client-add](#81-post-v1clientsclient-add)
9. [Endpoints — Event Logs](#9-endpoints--event-logs)
    - [9.1 GET /v1/event_logs](#91-get-v1event_logs)
    - [9.2 GET /v1/event_logs/{id}](#92-get-v1event_logsid)
    - [9.3 GET /v1/event_logs/ack_logs/{ackLog}](#93-get-v1event_logsack_logsacklog)
10. [Endpoints — Consulta de Datos por Rango de Fecha](#10-endpoints--consulta-de-datos-por-rango-de-fecha)
    - [10.1 GET /v1/data/date-range](#101-get-v1datadate-range)
11. [Endpoints — Webhook de Notificaciones (Solo Interno)](#11-endpoints--webhook-de-notificaciones-solo-interno)
12. [Endpoints — Configuracion de Dispositivos IoT](#12-endpoints--configuracion-de-dispositivos-iot)
    - [12.1 POST /v1/config/set-alert-limits](#121-post-v1configset-alert-limits)
    - [12.2 POST /v1/config/set-control-limits](#122-post-v1configset-control-limits)
    - [12.3 POST /v1/config/set-status-control-limits](#123-post-v1configset-status-control-limits)
    - [12.4 GET /v1/config/set-alert-time](#124-get-v1configset-alert-time)
    - [12.5 GET /v1/config/set-sampling-time](#125-get-v1configset-sampling-time)
    - [12.6 GET /v1/config/set-wifi-credentials](#126-get-v1configset-wifi-credentials)
    - [12.7 GET /v1/config/set-broker-credentials](#127-get-v1configset-broker-credentials)
    - [12.8 GET /v1/config/set-date](#128-get-v1configset-date)
    - [12.9 GET /v1/config/get-date](#129-get-v1configget-date)
    - [12.10 GET /v1/config/set-status-coil](#1210-get-v1configset-status-coil)
    - [12.11 GET /v1/config/get-status-coil](#1211-get-v1configget-status-coil)
    - [12.12 GET /v1/config/set-config-sensor](#1212-get-v1configset-config-sensor)
    - [12.13 GET /v1/config/get-config-sensor](#1213-get-v1configget-config-sensor)
    - [12.14 GET /v1/config/get-status-sensor](#1214-get-v1configget-status-sensor)
    - [12.15 GET /v1/config/get-status-connection](#1215-get-v1configget-status-connection)
    - [12.16 GET /v1/config/get-current-readings](#1216-get-v1configget-current-readings)
    - [12.17 GET /v1/config/set-status-real-time](#1217-get-v1configset-status-real-time)
    - [12.18 POST /v1/config/ota-update](#1218-post-v1configota-update)
    - [12.19 GET /v1/config/set-billing-day](#1219-get-v1configset-billing-day)
    - [12.20 GET /v1/config/set-status-service-coil](#1220-get-v1configset-status-service-coil)
    - [12.21 GET /v1/config/set-password-meter-app](#1221-get-v1configset-password-meter-app)
    - [12.22 GET /v1/config/get-password-meter](#1222-get-v1configget-password-meter)
13. [Endpoints — Webhook de Pagos Wompi (Solo Interno)](#13-endpoints--webhook-de-pagos-wompi-solo-interno)
14. [Scoping por Cliente y Restricciones de Seguridad](#14-scoping-por-cliente-y-restricciones-de-seguridad)
15. [Manejo de Errores](#15-manejo-de-errores)
16. [Flujo Tipico de Integracion](#16-flujo-tipico-de-integracion)
17. [Resumen de Todos los Endpoints](#17-resumen-de-todos-los-endpoints)

---

## 1. Vision General

La API de FluxAi/FluxAI es la interfaz HTTP de una plataforma IoT de monitoreo energetico. Permite a integradores externos:

- **Consultar datos historicos** de medidores electricos (voltaje, corriente, potencia, energia activa/reactiva) agregados por hora.
- **Enviar comandos de configuracion y control** a dispositivos IoT via MQTT (relay on/off, limites de alerta, intervalo de muestreo, OTA, etc.).
- **Rastrear el estado de los comandos** mediante un sistema de event logs con confirmacion de ACK del dispositivo.
- **Gestionar ordenes de trabajo** para el app movil de tecnicos.
- **Administrar firmware** para actualizaciones OTA de dispositivos.

La plataforma mantiene datos en cuatro niveles de agregacion temporal: por minuto (`microcontroller_data`), por hora (`hourly_microcontroller_data`), por dia (`daily_microcontroller_data`) y por mes (`monthly_microcontroller_data`). La API de consulta publica devuelve datos horarios.

Los comandos de configuracion son **asincronicos**: la API encola el mensaje via MQTT al dispositivo y devuelve un `transaction_id`. El cliente debe consultar el endpoint de event logs para verificar si el dispositivo confirmo la ejecucion (ACK).

---

## 2. Autenticacion

La API soporta dos metodos de autenticacion segun el tipo de integracion.

### 2.1 API Key (x-api-key)

**Usado por**: integradores externos que consultan datos o envian comandos a dispositivos.

```
x-api-key: <clave-asignada>
```

La clave se valida contra la tabla `api_keys`:

- `status` debe ser `enabled`
- `expiration` debe ser mayor a la fecha actual

Si la validacion falla, la API responde con HTTP `401`.

> **Provision de claves**: Las API keys no se generan por endpoint — se crean manualmente por el administrador de la plataforma y se asignan a una organizacion (`network_operator`). Contactar al equipo de FluxAi para obtener una clave.

**Ejemplo de request con API Key**:

```bash
curl -X GET "https://app.fluxai.solutions/api/v1/data/date-range?serial=SN-001234&fecha_inicio=1718400000&fecha_fin=1718486400" \
  -H "x-api-key: mi-clave-api" \
  -H "Accept: application/json"
```

### 2.2 JWT Bearer Token

**Usado por**: la aplicacion movil de tecnicos (job-list, orders, firmware).

**Header**:

```
Authorization: Bearer <token>
```

**Flujo de autenticacion**:

1. Llamar a `POST /api/auth/login` con `email` y `password`
2. Recibir `access_token` (TTL: 60 minutos por defecto)
3. Incluir el token en el header `Authorization` en cada request subsiguiente
4. Refrescar el token antes de que expire con `POST /api/auth/refresh`
5. Invalidar el token al finalizar la sesion con `POST /api/auth/logout`

> **Excepcion**: `GET /api/auth/firmwares` y `GET /api/auth/firmware/{id}` no usan JWT — usan una contrasena fija en query param.

---

## 3. Middleware y Control de Flujo

### 3.1 Token API Validation

**Clase**: `TokenValidationMiddleware`

Se aplica a todos los endpoints del grupo `/api/v1/*` (excepto `mqtt_input` y `notification-webhook`).

Logica:

1. Verifica que el header `x-api-key` este presente
2. Busca la key en la tabla `api_keys`
3. Verifica que no este expirada y que su `status` sea activo mediante `ApiKey::isValid()`
4. Si falla: `HTTP 401 "Error al validar api key de cliente"`

### 3.2 Event Queue Validation (anti-flood)

**Clase**: `EventQueueValidatorMiddleware`

Se aplica en conjunto con `token_api_validation` en todos los endpoints de configuracion IoT y event logs.

Logica:

1. Detecta el tipo de evento segun la URI del request
2. Busca el cliente asociado al serial recibido
3. Verifica que NO exista un evento del mismo tipo en estado `"created"` con menos de **45 segundos** de antiguedad. Si existe: `HTTP 429 "Evento del mismo tipo en proceso"`
4. Crea un `AckLog` y un `EventLog` para trazabilidad de la transaccion
5. Inyecta headers internos (`event_log_header`, `api_event_header`, `ack_log_header`, `serial`) que son consumidos internamente por los controladores — el cliente externo no necesita enviarlos

> **Importante**: El parametro `serial` es requerido en todos los endpoints que pasan por este middleware, incluyendo los de event_logs.

### 3.3 Rate Limiting Global

**Limite**: 60 requests por minuto por IP o usuario autenticado.

Si se supera el limite: `HTTP 429` con mensaje estandar de Laravel.

---

## 4. Endpoints — Autenticacion JWT

### 4.1 POST /auth/login

Obtiene un token JWT para autenticacion posterior.

| Campo             | Valor                                         |
| ----------------- | --------------------------------------------- |
| **Metodo**        | `POST`                                        |
| **URL**           | `https://app.fluxai.solutions/api/auth/login` |
| **Autenticacion** | Ninguna                                       |
| **Content-Type**  | `application/json`                            |

**Body Parameters**:

| Parametro  | Tipo   | Requerido | Descripcion            |
| ---------- | ------ | --------- | ---------------------- |
| `email`    | string | Si        | Email del usuario      |
| `password` | string | Si        | Contrasena del usuario |

**Ejemplo curl**:

```bash
curl -X POST https://app.fluxai.solutions/api/auth/login \
  -H "Content-Type: application/json" \
  -d '{"email": "tecnico@empresa.com", "password": "mi-contrasena"}'
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

### 4.2 POST /auth/logout

Invalida el token JWT actual.

| Campo             | Valor                                          |
| ----------------- | ---------------------------------------------- |
| **Metodo**        | `POST`                                         |
| **URL**           | `https://app.fluxai.solutions/api/auth/logout` |
| **Autenticacion** | JWT Bearer Token                               |

**Ejemplo curl**:

```bash
curl -X POST https://app.fluxai.solutions/api/auth/logout \
  -H "Authorization: Bearer <token>"
```

**Respuesta exitosa (200)**:

```json
{
    "message": "Successfully logged out"
}
```

---

### 4.3 POST /auth/refresh

Refresca el token JWT y devuelve uno nuevo. El token anterior queda invalidado.

| Campo             | Valor                                           |
| ----------------- | ----------------------------------------------- |
| **Metodo**        | `POST`                                          |
| **URL**           | `https://app.fluxai.solutions/api/auth/refresh` |
| **Autenticacion** | JWT Bearer Token                                |

**Ejemplo curl**:

```bash
curl -X POST https://app.fluxai.solutions/api/auth/refresh \
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

### 4.4 POST /auth/me

Devuelve la informacion del usuario autenticado.

| Campo             | Valor                                      |
| ----------------- | ------------------------------------------ |
| **Metodo**        | `POST`                                     |
| **URL**           | `https://app.fluxai.solutions/api/auth/me` |
| **Autenticacion** | JWT Bearer Token                           |

**Ejemplo curl**:

```bash
curl -X POST https://app.fluxai.solutions/api/auth/me \
  -H "Authorization: Bearer <token>"
```

**Respuesta exitosa (200)**:

```json
{
    "id": 1,
    "name": "Admin",
    "email": "admin@empresa.com",
    "email_verified_at": "2024-01-01T00:00:00.000000Z",
    "created_at": "2024-01-01T00:00:00.000000Z",
    "updated_at": "2024-01-01T00:00:00.000000Z"
}
```

---

## 5. Endpoints — Ordenes de Trabajo (App Tecnico)

Estos endpoints son utilizados exclusivamente por la aplicacion movil de tecnicos de campo. Todos requieren JWT Bearer Token.

### 5.1 POST /auth/job-list

Obtiene la lista de trabajos pendientes para el tecnico autenticado. Devuelve los clientes asignados al tecnico que tienen ordenes de trabajo abiertas y un equipo tipo "GABINETE".

| Campo             | Valor                                            |
| ----------------- | ------------------------------------------------ |
| **Metodo**        | `POST`                                           |
| **URL**           | `https://app.fluxai.solutions/api/auth/job-list` |
| **Autenticacion** | JWT Bearer Token                                 |

**Body Parameters**: Ninguno.

**Ejemplo curl**:

```bash
curl -X POST https://app.fluxai.solutions/api/auth/job-list \
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
        "ubicacion": { "lat": 4.6097, "lng": -74.0817 },
        "celular": "+573001234567",
        "pass": "jghsdjfg626FFDS5266s",
        "equipments": [
            { "type": "MEDIDOR ELECTRICO", "serial": "SN-001234" },
            { "type": "GABINETE", "serial": "GB-001234" }
        ],
        "orders": [
            {
                "id": 1,
                "status": "open",
                "type": "installation",
                "description": "Instalacion de medidor",
                "open_at": "2024-06-01 10:00:00"
            }
        ]
    }
]
```

---

### 5.2 POST /auth/orders-update

Actualiza una orden de trabajo existente. Soporta subida de imagenes como evidencia y lectura de datos de microcontrolador para ordenes tipo "lectura".

| Campo             | Valor                                                 |
| ----------------- | ----------------------------------------------------- |
| **Metodo**        | `POST`                                                |
| **URL**           | `https://app.fluxai.solutions/api/auth/orders-update` |
| **Autenticacion** | JWT Bearer Token                                      |
| **Content-Type**  | `multipart/form-data`                                 |

**Body Parameters**:

| Parametro        | Tipo          | Requerido   | Descripcion                                                                                        |
| ---------------- | ------------- | ----------- | -------------------------------------------------------------------------------------------------- |
| `order`          | string (JSON) | Si          | Objeto JSON stringificado con los datos de la orden                                                |
| `order.id`       | integer       | Si          | ID de la orden a actualizar                                                                        |
| `order.status`   | string        | No          | Nuevo estado de la orden                                                                           |
| `order.raw_json` | object        | Condicional | Requerido si la orden es tipo "lectura". Contiene datos del microcontrolador con campo `timestamp` |
| `order.images`   | array         | No          | Array de objetos `{name, description}` donde `name` refiere al campo del archivo subido            |
| `<image_name>`   | file          | Condicional | Archivos de imagen (maximo 6MB cada uno)                                                           |

**Ejemplo curl**:

```bash
curl -X POST https://app.fluxai.solutions/api/auth/orders-update \
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

**Codigos de error posibles**:

| HTTP | Condicion                                     | Body                                                                                |
| ---- | --------------------------------------------- | ----------------------------------------------------------------------------------- |
| 404  | Orden no encontrada                           | `{"success": false, "message": "Order not found"}`                                  |
| 409  | Orden ya cerrada                              | `{"success": false, "message": "The order is already closed."}`                     |
| 409  | `raw_json` faltante en orden tipo lectura     | `{"success": false, "message": "raw_json not found"}`                               |
| 400  | Imagen referenciada no incluida en el request | `{"success": false, "message": "One or more images are missing in the request."}`   |
| 413  | Imagen supera 6MB                             | `{"success": false, "message": "The image foto1 exceeds the maximum size of 6MB."}` |

---

### 5.3 POST /auth/order-create

Crea una nueva orden de trabajo para un cliente.

| Campo             | Valor                                                |
| ----------------- | ---------------------------------------------------- |
| **Metodo**        | `POST`                                               |
| **URL**           | `https://app.fluxai.solutions/api/auth/order-create` |
| **Autenticacion** | JWT Bearer Token                                     |
| **Content-Type**  | `application/json`                                   |

**Body Parameters**:

| Parametro    | Tipo    | Requerido | Descripcion              |
| ------------ | ------- | --------- | ------------------------ |
| `client_id`  | integer | Si        | ID del cliente           |
| `order_type` | string  | Si        | Tipo de orden de trabajo |

**Ejemplo curl**:

```bash
curl -X POST https://app.fluxai.solutions/api/auth/order-create \
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

## 6. Endpoints — Gestion de Firmware

### 6.1 GET /auth/firmwares

Lista todos los firmwares disponibles. Requiere contrasena fija de acceso (sin JWT).

| Campo             | Valor                                             |
| ----------------- | ------------------------------------------------- |
| **Metodo**        | `GET`                                             |
| **URL**           | `https://app.fluxai.solutions/api/auth/firmwares` |
| **Autenticacion** | Contrasena en query param                         |

**Query Parameters**:

| Parametro  | Tipo   | Requerido | Descripcion                                               |
| ---------- | ------ | --------- | --------------------------------------------------------- |
| `password` | string | Si        | Contrasena de acceso fija (provista por el administrador) |

**Ejemplo curl**:

```bash
curl -X GET "https://app.fluxai.solutions/api/auth/firmwares?password=<contrasena>"
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

**Respuesta error — Contrasena invalida (404)**:

```json
{
    "error": "Invalidate password"
}
```

---

### 6.2 GET /auth/firmware/{id}

Obtiene la URL del archivo de un firmware especifico por su ID. Requiere contrasena fija (sin JWT).

| Campo             | Valor                                                 |
| ----------------- | ----------------------------------------------------- |
| **Metodo**        | `GET`                                                 |
| **URL**           | `https://app.fluxai.solutions/api/auth/firmware/{id}` |
| **Autenticacion** | Contrasena en query param                             |

**Path Parameters**:

| Parametro | Tipo    | Descripcion     |
| --------- | ------- | --------------- |
| `id`      | integer | ID del firmware |

**Query Parameters**:

| Parametro  | Tipo   | Requerido | Descripcion               |
| ---------- | ------ | --------- | ------------------------- |
| `password` | string | Si        | Contrasena de acceso fija |

**Ejemplo curl**:

```bash
curl -X GET "https://app.fluxai.solutions/api/auth/firmware/1?password=<contrasena>"
```

**Respuesta exitosa (200)**:

```json
{
    "url": "https://s3.amazonaws.com/bucket/firmwares/firmware_v2.1.bin"
}
```

**Respuestas de error**:

| HTTP | Condicion                       | Body                              |
| ---- | ------------------------------- | --------------------------------- |
| 404  | Firmware no encontrado          | `{"error": "Firmware not found"}` |
| 404  | Evidencia/archivo no encontrado | `{"error": "Evidence not found"}` |

---

### 6.3 POST /auth/firmware-create

Crea un nuevo firmware y sube el archivo binario a S3.

| Campo             | Valor                                                   |
| ----------------- | ------------------------------------------------------- |
| **Metodo**        | `POST`                                                  |
| **URL**           | `https://app.fluxai.solutions/api/auth/firmware-create` |
| **Autenticacion** | JWT Bearer Token                                        |
| **Content-Type**  | `multipart/form-data`                                   |

**Body Parameters**:

| Parametro     | Tipo   | Requerido | Validacion | Descripcion                  |
| ------------- | ------ | --------- | ---------- | ---------------------------- |
| `name`        | string | Si        | `max:255`  | Nombre del firmware          |
| `version`     | string | Si        | `max:255`  | Version del firmware         |
| `description` | string | Si        | —          | Descripcion del firmware     |
| `file`        | file   | Si        | —          | Archivo binario del firmware |

**Ejemplo curl**:

```bash
curl -X POST https://app.fluxai.solutions/api/auth/firmware-create \
  -H "Authorization: Bearer <token>" \
  -F "name=Firmware v3.0" \
  -F "version=3.0.0" \
  -F "description=Nueva version con soporte OTA mejorado" \
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
        "description": "Nueva version con soporte OTA mejorado",
        "created_at": "2024-06-15T14:30:00.000000Z",
        "updated_at": "2024-06-15T14:30:00.000000Z"
    }
}
```

---

## 7. Endpoints — Entrada MQTT (Solo Interno)

> **SOLO USO INTERNO — NO CONSUMIR DESDE INTEGRACIONES EXTERNAS**

Los siguientes endpoints son consumidos exclusivamente por el sistema interno de procesamiento MQTT de la plataforma. No requieren autenticacion y no estan destinados a integradores externos.

| Metodo | URL                            | Proposito                                                                   |
| ------ | ------------------------------ | --------------------------------------------------------------------------- |
| `POST` | `/api/v1/mqtt_input`           | Recibe telemetria MQTT cruda y despacha al job `SaveMicrocontrollerDataJob` |
| `POST` | `/api/v1/mqtt_input/real-time` | Recibe datos de tiempo real y los transmite via WebSockets                  |

---

## 8. Endpoints — Gestion de Clientes

### 8.1 POST /v1/clients/client-add

Registra un cliente en el sistema. Valida que el serial del medidor electrico exista y no este asignado a otro cliente.

> **Nota**: Este endpoint es llamado principalmente por el sistema aomenertec-api para sincronizar la creacion de clientes. Puede ser usado por integradores que necesitan registrar clientes via API.

| Campo             | Valor                                                    |
| ----------------- | -------------------------------------------------------- |
| **Metodo**        | `POST`                                                   |
| **URL**           | `https://app.fluxai.solutions/api/v1/clients/client-add` |
| **Autenticacion** | `x-api-key` header + Event Queue Validation              |
| **Content-Type**  | `application/json`                                       |

**Headers requeridos**:

| Header      | Descripcion                                |
| ----------- | ------------------------------------------ |
| `x-api-key` | API key valida registrada en la plataforma |

**Body Parameters**:

| Parametro | Tipo   | Requerido | Descripcion                                                                                                        |
| --------- | ------ | --------- | ------------------------------------------------------------------------------------------------------------------ |
| `serial`  | string | Si        | Serial del medidor electrico. Debe existir como equipo tipo "MEDIDOR ELECTRICO" y no estar asignado a otro cliente |

**Ejemplo curl**:

```bash
curl -X POST https://app.fluxai.solutions/api/v1/clients/client-add \
  -H "Content-Type: application/json" \
  -H "x-api-key: mi-clave-api" \
  -d '{"serial": "SN-001234"}'
```

**Respuesta exitosa (200)**:

```json
{
    "data": [
        {
            "equipments": [
                { "type": "MEDIDOR ELECTRICO", "serial": "SN-001234" },
                { "type": "GABINETE", "serial": "GB-001234" }
            ]
        }
    ]
}
```

**Respuesta error — Serial no existe o ya asignado (200 con error en body)**:

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

**Respuesta error — API Key invalida (401)**:

```
Error al validar api key de cliente
```

---

## 9. Endpoints — Event Logs

El sistema registra cada comando enviado a un dispositivo como un `EventLog`. Cada log tiene un `AckLog` (transaccion) asociado que agrupa todos los pasos del ciclo de vida del comando.

Todos los endpoints de esta seccion requieren `x-api-key` + Event Queue Validation middleware. El parametro `serial` es obligatorio (requerido por el middleware).

### Estados de un EventLog

| Estado       | Descripcion                                                       |
| ------------ | ----------------------------------------------------------------- |
| `created`    | Comando encolado, esperando respuesta del dispositivo             |
| `successful` | Dispositivo confirmo la ejecucion del comando (ACK recibido)      |
| `error`      | El dispositivo reporto error o el comando expiro sin confirmacion |

### 9.1 GET /v1/event_logs

Obtiene una coleccion paginada de event logs con filtros opcionales.

| Campo             | Valor                                            |
| ----------------- | ------------------------------------------------ |
| **Metodo**        | `GET`                                            |
| **URL**           | `https://app.fluxai.solutions/api/v1/event_logs` |
| **Autenticacion** | `x-api-key` + Event Queue Validation             |

**Query Parameters**:

| Parametro | Tipo   | Requerido | Descripcion                                     |
| --------- | ------ | --------- | ----------------------------------------------- |
| `serial`  | string | Si        | Serial del equipo (requerido por el middleware) |
| `s_f`     | string | No        | Campo por el cual buscar (search field)         |
| `s`       | string | No        | Valor de busqueda                               |
| `o_b`     | string | No        | Direccion del ordenamiento: `ASC` o `DESC`      |
| `o_f`     | string | No        | Campo por el cual ordenar                       |

> **ADVERTENCIA DE SEGURIDAD**: Este endpoint NO filtra por organizacion. Devuelve event logs de todos los dispositivos del sistema, independientemente de la API key usada. Ver seccion [14. Scoping por Cliente](#14-scoping-por-cliente-y-restricciones-de-seguridad).

**Ejemplo curl**:

```bash
curl -X GET "https://app.fluxai.solutions/api/v1/event_logs?serial=SN-001234&o_b=DESC&o_f=created_at" \
  -H "x-api-key: mi-clave-api"
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
    "links": {
        "first": "...?page=1",
        "next": "...?page=2",
        "last": "...?page=5",
        "prev": null
    },
    "meta": {
        "current_page": 1,
        "from": 1,
        "last_page": 5,
        "per_page": 15,
        "total": 75
    }
}
```

---

### 9.2 GET /v1/event_logs/{id}

Obtiene un event log especifico por su ID.

| Campo             | Valor                                                 |
| ----------------- | ----------------------------------------------------- |
| **Metodo**        | `GET`                                                 |
| **URL**           | `https://app.fluxai.solutions/api/v1/event_logs/{id}` |
| **Autenticacion** | `x-api-key` + Event Queue Validation                  |

**Path Parameters**:

| Parametro | Tipo    | Descripcion      |
| --------- | ------- | ---------------- |
| `id`      | integer | ID del event log |

**Query Parameters**:

| Parametro | Tipo   | Requerido | Descripcion                                     |
| --------- | ------ | --------- | ----------------------------------------------- |
| `serial`  | string | Si        | Serial del equipo (requerido por el middleware) |

**Ejemplo curl**:

```bash
curl -X GET "https://app.fluxai.solutions/api/v1/event_logs/42?serial=SN-001234" \
  -H "x-api-key: mi-clave-api"
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

### 9.3 GET /v1/event_logs/ack_logs/{ackLog}

Obtiene todos los event logs asociados a un AckLog (transaccion) especifico. Cada comando enviado a un dispositivo genera un `AckLog` con un `transaction_id`, y puede tener multiples `EventLog` asociados (uno por cada paso del ciclo: solicitud del cliente, reenvio al dispositivo, confirmacion del dispositivo).

Este es el endpoint clave para verificar si un dispositivo ejecuto exitosamente un comando enviado.

| Campo             | Valor                                                              |
| ----------------- | ------------------------------------------------------------------ |
| **Metodo**        | `GET`                                                              |
| **URL**           | `https://app.fluxai.solutions/api/v1/event_logs/ack_logs/{ackLog}` |
| **Autenticacion** | `x-api-key` + Event Queue Validation                               |

**Path Parameters**:

| Parametro | Tipo    | Descripcion                                                                          |
| --------- | ------- | ------------------------------------------------------------------------------------ |
| `ackLog`  | integer | ID del AckLog (el `transaction_id` retornado por los endpoints de configuracion IoT) |

**Query Parameters**:

| Parametro | Tipo   | Requerido | Descripcion                                     |
| --------- | ------ | --------- | ----------------------------------------------- |
| `serial`  | string | Si        | Serial del equipo (requerido por el middleware) |

**Ejemplo curl**:

```bash
curl -X GET "https://app.fluxai.solutions/api/v1/event_logs/ack_logs/15?serial=SN-001234" \
  -H "x-api-key: mi-clave-api"
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
      "status": "successful",
      "..."
    }
  ]
}
```

> Para verificar si el dispositivo confirmo el comando, buscar en el array el item con `request_type: "main_server_mc_request"` y verificar que su `status` sea `"successful"`.

---

## 10. Endpoints — Consulta de Datos por Rango de Fecha

### 10.1 GET /v1/data/date-range

Consulta datos historicos de un medidor por rango de fechas. Devuelve registros paginados de `HourlyMicrocontrollerData` — datos agregados por hora, no lecturas crudas por minuto.

| Campo             | Valor                                                 |
| ----------------- | ----------------------------------------------------- |
| **Metodo**        | `GET`                                                 |
| **URL**           | `https://app.fluxai.solutions/api/v1/data/date-range` |
| **Autenticacion** | `x-api-key` + Event Queue Validation                  |

**Query Parameters**:

| Parametro      | Tipo           | Requerido | Validacion                                                                                                  | Descripcion                  |
| -------------- | -------------- | --------- | ----------------------------------------------------------------------------------------------------------- | ---------------------------- |
| `serial`       | string         | Si        | Debe existir como medidor electrico, pertenecer a la organizacion del usuario y estar asignado a un cliente | Serial del medidor electrico |
| `fecha_inicio` | string/integer | Si        | Timestamp Unix o formato `Y-m-d H:i:s`                                                                      | Fecha de inicio del rango    |
| `fecha_fin`    | string/integer | Si        | Timestamp Unix o formato `Y-m-d H:i:s`. Debe ser diferente y posterior a `fecha_inicio`                     | Fecha de fin del rango       |

> **Scoping de seguridad**: Este ES el unico endpoint que valida que el serial consultado pertenezca a la organizacion vinculada a la API key. Si el serial pertenece a otro operador de red, la validacion fallara con HTTP 422.

**Ejemplo curl con timestamps Unix**:

```bash
curl -X GET "https://app.fluxai.solutions/api/v1/data/date-range?serial=SN-001234&fecha_inicio=1718400000&fecha_fin=1718486400" \
  -H "x-api-key: mi-clave-api" \
  -H "Accept: application/json"
```

**Ejemplo curl con formato de fecha**:

```bash
curl -X GET "https://app.fluxai.solutions/api/v1/data/date-range?serial=SN-001234&fecha_inicio=2024-06-15%2000:00:00&fecha_fin=2024-06-16%2000:00:00" \
  -H "x-api-key: mi-clave-api" \
  -H "Accept: application/json"
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
            "data": {
                "voltage_l1": 121.0,
                "current_l1": 5.5,
                "power_l1": 665.5,
                "energy_l1": 1261.0
            },
            "date": "2024-06-15",
            "hour": "15:30:00"
        }
    ],
    "links": {
        "first": "...?page=1",
        "next": "...?page=2",
        "last": "...?page=5",
        "prev": null
    },
    "meta": {
        "current_page": 1,
        "from": 1,
        "last_page": 5,
        "per_page": 15,
        "total": 75
    }
}
```

> **Nota**: Los campos internos `network_operator_id`, `latitude`, `longitude`, `flags` y `timestamp` son removidos del `raw_json` antes de retornar la respuesta.

**Respuesta error — Serial no pertenece a la organizacion (422)**:

```json
{
    "code": 422,
    "message": "La solicitud enviada al servidor es incorrecta o no se puede procesar",
    "details": {
        "serial": ["El medidor electrico con serial SN-XXXXX no existe"]
    }
}
```

---

## 11. Endpoints — Webhook de Notificaciones (Solo Interno)

> **SOLO USO INTERNO — NO CONSUMIR DESDE INTEGRACIONES EXTERNAS**

| Metodo | URL                                   | Descripcion                                                                                                                           |
| ------ | ------------------------------------- | ------------------------------------------------------------------------------------------------------------------------------------- |
| `POST` | `/api/v1/config/notification-webhook` | Recibe notificaciones del sistema de eventos (desde aomenertec-api). Publica via MQTT y crea alertas informativas. Sin autenticacion. |

Este endpoint es consumido internamente por el microservicio IoT (`aomenertec-api`) para notificar al sistema principal cuando un dispositivo confirma la ejecucion de un comando. No debe ser llamado por integradores externos.

---

## 12. Endpoints — Configuracion de Dispositivos IoT

Todos los endpoints de esta seccion comparten las siguientes caracteristicas:

- **Autenticacion**: `x-api-key` header + Event Queue Validation middleware
- **Flujo asincrono**: El endpoint recibe la solicitud → empaqueta el mensaje en formato binario segun `config/data-frame.php` → lo envia por MQTT al topic `v1/mc/config/{serial}` → retorna respuesta con `transaction_id` para seguimiento
- **Anti-flood**: Un solo evento del mismo tipo por equipo cada 45 segundos. Si se reintenta antes: `HTTP 429`
- **Scoping**: Estos endpoints solo validan que el serial exista en la base de datos, NO que pertenezca a la organizacion del dueno de la API key. Ver seccion [14. Scoping por Cliente](#14-scoping-por-cliente-y-restricciones-de-seguridad).

### Formato de respuesta estandar (exito)

Todos los endpoints de configuracion retornan:

```json
{
    "data": {
        "message": "Se realizo el envio del mensaje",
        "detail": "Se espera respuesta del equipo para confirmar la conexion",
        "serial": "SN-001234",
        "transaction_id": 15,
        "event_id": 42
    }
}
```

Guardar el `transaction_id` para consultar el estado del comando en `GET /v1/event_logs/ack_logs/{transaction_id}`.

### Formato de respuesta estandar (fallo MQTT)

Si falla la conexion al broker MQTT:

```json
{
    "data": {
        "message": "Fallo el envio del mensaje",
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

### Validacion del serial

La regla `ValidateSerialRule` aplicada en la mayoria de estos endpoints verifica:

1. Que exista un equipo tipo "MEDIDOR ELECTRICO" o "GABINETE" con ese serial
2. Que el equipo este asignado a un cliente

Si falla: `HTTP 422` con detalle del error de validacion.

---

### 12.1 POST /v1/config/set-alert-limits

Configura los limites de alerta para un dispositivo. Los campos dinamicos provienen de `config('data-frame.alert_config_frame')`.

| Campo             | Valor                                                         |
| ----------------- | ------------------------------------------------------------- |
| **Metodo**        | `POST`                                                        |
| **URL**           | `https://app.fluxai.solutions/api/v1/config/set-alert-limits` |
| **Autenticacion** | `x-api-key` + Event Queue                                     |
| **Content-Type**  | `application/json`                                            |

**Body Parameters**:

| Parametro | Tipo    | Requerido | Validacion                     | Descripcion                                                                                                  |
| --------- | ------- | --------- | ------------------------------ | ------------------------------------------------------------------------------------------------------------ |
| `serial`  | string  | Si        | `ValidateSerialRule`           | Serial del equipo                                                                                            |
| `max_*`   | numeric | Si        | `required\|numeric`            | Valores maximos de alerta (campos dinamicos de `alert_config_frame`, ej: `max_voltage_l1`, `max_current_l1`) |
| `min_*`   | numeric | Si        | `required\|numeric\|lte:max_*` | Valores minimos de alerta. Debe ser menor o igual al maximo correspondiente                                  |

> **Nota**: Los campos exactos (`max_voltage_l1`, `min_voltage_l1`, `max_current_l1`, etc.) dependen de la configuracion del tipo de medidor. Consultar al equipo de FluxAi para obtener la lista completa segun el tipo de equipo.

**Ejemplo curl**:

```bash
curl -X POST https://app.fluxai.solutions/api/v1/config/set-alert-limits \
  -H "x-api-key: mi-clave-api" \
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

### 12.2 POST /v1/config/set-control-limits

Configura los limites de control para un dispositivo. Misma estructura de campos dinamicos que `set-alert-limits`.

| Campo             | Valor                                                           |
| ----------------- | --------------------------------------------------------------- |
| **Metodo**        | `POST`                                                          |
| **URL**           | `https://app.fluxai.solutions/api/v1/config/set-control-limits` |
| **Autenticacion** | `x-api-key` + Event Queue                                       |
| **Content-Type**  | `application/json`                                              |

**Body Parameters**: Identicos a [set-alert-limits](#121-post-v1configset-alert-limits).

**Ejemplo curl**:

```bash
curl -X POST https://app.fluxai.solutions/api/v1/config/set-control-limits \
  -H "x-api-key: mi-clave-api" \
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

### 12.3 POST /v1/config/set-status-control-limits

Habilita o deshabilita grupos de limites de control en el dispositivo.

| Campo             | Valor                                                                  |
| ----------------- | ---------------------------------------------------------------------- |
| **Metodo**        | `POST`                                                                 |
| **URL**           | `https://app.fluxai.solutions/api/v1/config/set-status-control-limits` |
| **Autenticacion** | `x-api-key` + Event Queue                                              |
| **Content-Type**  | `application/json`                                                     |

**Body Parameters**:

| Parametro  | Tipo    | Requerido | Validacion           | Descripcion                                                                                                       |
| ---------- | ------- | --------- | -------------------- | ----------------------------------------------------------------------------------------------------------------- |
| `serial`   | string  | Si        | `ValidateSerialRule` | Serial del equipo                                                                                                 |
| `status_*` | numeric | Si        | `required\|numeric`  | Estado de cada grupo de control (1 = habilitado, 0 = deshabilitado). Ej: `status_voltage_l1`, `status_current_l1` |

**Ejemplo curl**:

```bash
curl -X POST https://app.fluxai.solutions/api/v1/config/set-status-control-limits \
  -H "x-api-key: mi-clave-api" \
  -H "Content-Type: application/json" \
  -d '{
    "serial": "SN-001234",
    "status_voltage_l1": 1,
    "status_current_l1": 0
  }'
```

---

### 12.4 GET /v1/config/set-alert-time

Configura los tiempos de alerta para un dispositivo. Los campos son dinamicos basados en `config('data-frame.alert_config_time_frame')`.

| Campo             | Valor                                                       |
| ----------------- | ----------------------------------------------------------- |
| **Metodo**        | `GET`                                                       |
| **URL**           | `https://app.fluxai.solutions/api/v1/config/set-alert-time` |
| **Autenticacion** | `x-api-key` + Event Queue                                   |

**Query Parameters**:

| Parametro | Tipo   | Requerido | Descripcion                                                                                                   |
| --------- | ------ | --------- | ------------------------------------------------------------------------------------------------------------- |
| `serial`  | string | Si        | Serial del equipo                                                                                             |
| `time_*`  | mixed  | Si        | Campos dinamicos de `alert_config_time_frame`. Ej: `time_voltage_l1`, `time_current_l1` (valores en segundos) |

**Ejemplo curl**:

```bash
curl -X GET "https://app.fluxai.solutions/api/v1/config/set-alert-time?serial=SN-001234&time_voltage_l1=30&time_current_l1=60" \
  -H "x-api-key: mi-clave-api"
```

---

### 12.5 GET /v1/config/set-sampling-time

Configura el intervalo de muestreo de datos del dispositivo.

| Campo             | Valor                                                          |
| ----------------- | -------------------------------------------------------------- |
| **Metodo**        | `GET`                                                          |
| **URL**           | `https://app.fluxai.solutions/api/v1/config/set-sampling-time` |
| **Autenticacion** | `x-api-key` + Event Queue                                      |

**Query Parameters**:

| Parametro              | Tipo    | Requerido | Validacion                                                                                                         | Descripcion                   |
| ---------------------- | ------- | --------- | ------------------------------------------------------------------------------------------------------------------ | ----------------------------- |
| `serial`               | string  | Si        | `ValidateSerialRule`                                                                                               | Serial del equipo             |
| `time_sampling_choice` | string  | Si        | `in:hourly,daily,monthly`                                                                                          | Tipo de intervalo de muestreo |
| `data_per_interval`    | numeric | Si        | Valores validos segun `time_sampling_choice`: `hourly` → `1,2,3,4,6,12,60`; `daily` → `1,2,4,8`; `monthly` → `1,2` | Datos por intervalo           |
| `data_per_seconds`     | numeric | Si        | `between:0,254`                                                                                                    | Datos por segundo             |

**Ejemplo curl**:

```bash
curl -X GET "https://app.fluxai.solutions/api/v1/config/set-sampling-time?serial=SN-001234&time_sampling_choice=hourly&data_per_interval=6&data_per_seconds=120" \
  -H "x-api-key: mi-clave-api"
```

---

### 12.6 GET /v1/config/set-wifi-credentials

Configura las credenciales WiFi del dispositivo IoT.

| Campo             | Valor                                                             |
| ----------------- | ----------------------------------------------------------------- |
| **Metodo**        | `GET`                                                             |
| **URL**           | `https://app.fluxai.solutions/api/v1/config/set-wifi-credentials` |
| **Autenticacion** | `x-api-key` + Event Queue                                         |

**Query Parameters**:

| Parametro  | Tipo   | Requerido | Descripcion               |
| ---------- | ------ | --------- | ------------------------- |
| `serial`   | string | Si        | Serial del equipo         |
| `ssid`     | string | Si        | Nombre de la red WiFi     |
| `password` | string | Si        | Contrasena de la red WiFi |

**Ejemplo curl**:

```bash
curl -X GET "https://app.fluxai.solutions/api/v1/config/set-wifi-credentials?serial=SN-001234&ssid=MiRedWifi&password=clave123" \
  -H "x-api-key: mi-clave-api"
```

---

### 12.7 GET /v1/config/set-broker-credentials

Configura las credenciales del broker MQTT en el dispositivo.

| Campo             | Valor                                                               |
| ----------------- | ------------------------------------------------------------------- |
| **Metodo**        | `GET`                                                               |
| **URL**           | `https://app.fluxai.solutions/api/v1/config/set-broker-credentials` |
| **Autenticacion** | `x-api-key` + Event Queue                                           |

**Query Parameters**:

| Parametro  | Tipo   | Requerido | Descripcion                |
| ---------- | ------ | --------- | -------------------------- |
| `serial`   | string | Si        | Serial del equipo          |
| `host`     | string | Si        | Host del broker MQTT       |
| `port`     | string | Si        | Puerto del broker MQTT     |
| `user`     | string | Si        | Usuario del broker MQTT    |
| `password` | string | Si        | Contrasena del broker MQTT |

**Ejemplo curl**:

```bash
curl -X GET "https://app.fluxai.solutions/api/v1/config/set-broker-credentials?serial=SN-001234&host=mqtt.example.com&port=1883&user=enertec&password=secret123" \
  -H "x-api-key: mi-clave-api"
```

---

### 12.8 GET /v1/config/set-date

Sincroniza la fecha/hora del dispositivo con el servidor. El timestamp Unix se genera automaticamente en el servidor al momento de recibir el request.

| Campo             | Valor                                                 |
| ----------------- | ----------------------------------------------------- |
| **Metodo**        | `GET`                                                 |
| **URL**           | `https://app.fluxai.solutions/api/v1/config/set-date` |
| **Autenticacion** | `x-api-key` + Event Queue                             |

**Query Parameters**:

| Parametro | Tipo   | Requerido | Descripcion       |
| --------- | ------ | --------- | ----------------- |
| `serial`  | string | Si        | Serial del equipo |

**Ejemplo curl**:

```bash
curl -X GET "https://app.fluxai.solutions/api/v1/config/set-date?serial=SN-001234" \
  -H "x-api-key: mi-clave-api"
```

---

### 12.9 GET /v1/config/get-date

Solicita la fecha/hora actual configurada en el dispositivo. La respuesta del dispositivo llega de forma asincrona via MQTT.

| Campo             | Valor                                                 |
| ----------------- | ----------------------------------------------------- |
| **Metodo**        | `GET`                                                 |
| **URL**           | `https://app.fluxai.solutions/api/v1/config/get-date` |
| **Autenticacion** | `x-api-key` + Event Queue                             |

**Query Parameters**:

| Parametro | Tipo   | Requerido | Descripcion       |
| --------- | ------ | --------- | ----------------- |
| `serial`  | string | Si        | Serial del equipo |

**Ejemplo curl**:

```bash
curl -X GET "https://app.fluxai.solutions/api/v1/config/get-date?serial=SN-001234" \
  -H "x-api-key: mi-clave-api"
```

---

### 12.10 GET /v1/config/set-status-coil

Enciende o apaga el rele (coil) del dispositivo.

| Campo             | Valor                                                        |
| ----------------- | ------------------------------------------------------------ |
| **Metodo**        | `GET`                                                        |
| **URL**           | `https://app.fluxai.solutions/api/v1/config/set-status-coil` |
| **Autenticacion** | `x-api-key` + Event Queue                                    |

**Query Parameters**:

| Parametro | Tipo    | Requerido | Validacion           | Descripcion                  |
| --------- | ------- | --------- | -------------------- | ---------------------------- |
| `serial`  | string  | Si        | `ValidateSerialRule` | Serial del equipo            |
| `status`  | boolean | Si        | `required\|boolean`  | `1` = encender, `0` = apagar |

**Ejemplo curl — encender rele**:

```bash
curl -X GET "https://app.fluxai.solutions/api/v1/config/set-status-coil?serial=SN-001234&status=1" \
  -H "x-api-key: mi-clave-api"
```

**Respuesta**:

```json
{
    "data": {
        "message": "Se realizo el envio del mensaje",
        "detail": "Se espera respuesta del equipo para confirmar la conexion",
        "serial": "SN-001234",
        "transaction_id": 15,
        "event_id": 42
    }
}
```

---

### 12.11 GET /v1/config/get-status-coil

Consulta el estado actual del rele (coil) del dispositivo.

| Campo             | Valor                                                        |
| ----------------- | ------------------------------------------------------------ |
| **Metodo**        | `GET`                                                        |
| **URL**           | `https://app.fluxai.solutions/api/v1/config/get-status-coil` |
| **Autenticacion** | `x-api-key` + Event Queue                                    |

**Query Parameters**:

| Parametro | Tipo   | Requerido | Descripcion       |
| --------- | ------ | --------- | ----------------- |
| `serial`  | string | Si        | Serial del equipo |

**Ejemplo curl**:

```bash
curl -X GET "https://app.fluxai.solutions/api/v1/config/get-status-coil?serial=SN-001234" \
  -H "x-api-key: mi-clave-api"
```

---

### 12.12 GET /v1/config/set-config-sensor

Configura el tipo de sensor del dispositivo.

| Campo             | Valor                                                          |
| ----------------- | -------------------------------------------------------------- |
| **Metodo**        | `GET`                                                          |
| **URL**           | `https://app.fluxai.solutions/api/v1/config/set-config-sensor` |
| **Autenticacion** | `x-api-key` + Event Queue                                      |

**Query Parameters**:

| Parametro | Tipo    | Requerido | Validacion           | Descripcion                                                 |
| --------- | ------- | --------- | -------------------- | ----------------------------------------------------------- |
| `serial`  | string  | Si        | `ValidateSerialRule` | Serial del equipo                                           |
| `type`    | integer | Si        | `in:1,2,3`           | Tipo de sensor: 1 = monofasico, 2 = bifasico, 3 = trifasico |

**Ejemplo curl**:

```bash
curl -X GET "https://app.fluxai.solutions/api/v1/config/set-config-sensor?serial=SN-001234&type=2" \
  -H "x-api-key: mi-clave-api"
```

---

### 12.13 GET /v1/config/get-config-sensor

Consulta la configuracion actual del sensor del dispositivo.

| Campo             | Valor                                                          |
| ----------------- | -------------------------------------------------------------- |
| **Metodo**        | `GET`                                                          |
| **URL**           | `https://app.fluxai.solutions/api/v1/config/get-config-sensor` |
| **Autenticacion** | `x-api-key` + Event Queue                                      |

**Query Parameters**:

| Parametro | Tipo   | Requerido | Descripcion       |
| --------- | ------ | --------- | ----------------- |
| `serial`  | string | Si        | Serial del equipo |

**Ejemplo curl**:

```bash
curl -X GET "https://app.fluxai.solutions/api/v1/config/get-config-sensor?serial=SN-001234" \
  -H "x-api-key: mi-clave-api"
```

---

### 12.14 GET /v1/config/get-status-sensor

Consulta el estado actual del sensor del dispositivo.

| Campo             | Valor                                                          |
| ----------------- | -------------------------------------------------------------- |
| **Metodo**        | `GET`                                                          |
| **URL**           | `https://app.fluxai.solutions/api/v1/config/get-status-sensor` |
| **Autenticacion** | `x-api-key` + Event Queue                                      |

**Query Parameters**:

| Parametro | Tipo   | Requerido | Descripcion       |
| --------- | ------ | --------- | ----------------- |
| `serial`  | string | Si        | Serial del equipo |

**Ejemplo curl**:

```bash
curl -X GET "https://app.fluxai.solutions/api/v1/config/get-status-sensor?serial=SN-001234" \
  -H "x-api-key: mi-clave-api"
```

---

### 12.15 GET /v1/config/get-status-connection

Consulta el estado de conexion del dispositivo.

| Campo             | Valor                                                              |
| ----------------- | ------------------------------------------------------------------ |
| **Metodo**        | `GET`                                                              |
| **URL**           | `https://app.fluxai.solutions/api/v1/config/get-status-connection` |
| **Autenticacion** | `x-api-key` + Event Queue                                          |

**Query Parameters**:

| Parametro | Tipo   | Requerido | Descripcion       |
| --------- | ------ | --------- | ----------------- |
| `serial`  | string | Si        | Serial del equipo |

**Ejemplo curl**:

```bash
curl -X GET "https://app.fluxai.solutions/api/v1/config/get-status-connection?serial=SN-001234" \
  -H "x-api-key: mi-clave-api"
```

---

### 12.16 GET /v1/config/get-current-readings

Solicita las lecturas actuales del dispositivo. La respuesta del dispositivo llega de forma asincrona via MQTT.

| Campo             | Valor                                                             |
| ----------------- | ----------------------------------------------------------------- |
| **Metodo**        | `GET`                                                             |
| **URL**           | `https://app.fluxai.solutions/api/v1/config/get-current-readings` |
| **Autenticacion** | `x-api-key` + Event Queue                                         |

**Query Parameters**:

| Parametro | Tipo   | Requerido | Descripcion       |
| --------- | ------ | --------- | ----------------- |
| `serial`  | string | Si        | Serial del equipo |

**Ejemplo curl**:

```bash
curl -X GET "https://app.fluxai.solutions/api/v1/config/get-current-readings?serial=SN-001234" \
  -H "x-api-key: mi-clave-api"
```

---

### 12.17 GET /v1/config/set-status-real-time

Habilita o deshabilita el streaming de datos en tiempo real del dispositivo.

| Campo             | Valor                                                             |
| ----------------- | ----------------------------------------------------------------- |
| **Metodo**        | `GET`                                                             |
| **URL**           | `https://app.fluxai.solutions/api/v1/config/set-status-real-time` |
| **Autenticacion** | `x-api-key` + Event Queue                                         |

**Query Parameters**:

| Parametro | Tipo    | Requerido | Validacion           | Descripcion                                     |
| --------- | ------- | --------- | -------------------- | ----------------------------------------------- |
| `serial`  | string  | Si        | `ValidateSerialRule` | Serial del equipo                               |
| `status`  | boolean | Si        | `required\|boolean`  | `1` = habilitar tiempo real, `0` = deshabilitar |

**Ejemplo curl**:

```bash
curl -X GET "https://app.fluxai.solutions/api/v1/config/set-status-real-time?serial=SN-001234&status=1" \
  -H "x-api-key: mi-clave-api"
```

---

### 12.18 POST /v1/config/ota-update

Envia una actualizacion OTA (Over-The-Air) al dispositivo. El sistema busca el firmware por ID, descarga el archivo desde S3, calcula el tamano y lo empaqueta en el mensaje MQTT enviado al dispositivo.

| Campo             | Valor                                                   |
| ----------------- | ------------------------------------------------------- |
| **Metodo**        | `POST`                                                  |
| **URL**           | `https://app.fluxai.solutions/api/v1/config/ota-update` |
| **Autenticacion** | `x-api-key` + Event Queue                               |
| **Content-Type**  | `application/json`                                      |

**Body Parameters**:

| Parametro | Tipo   | Requerido | Descripcion                                                                                      |
| --------- | ------ | --------- | ------------------------------------------------------------------------------------------------ |
| `serial`  | string | Si        | Serial del equipo                                                                                |
| `version` | string | Si        | ID del firmware a instalar (referencia al modelo Firmware, obtenido desde `GET /auth/firmwares`) |

**Ejemplo curl**:

```bash
curl -X POST https://app.fluxai.solutions/api/v1/config/ota-update \
  -H "x-api-key: mi-clave-api" \
  -H "Content-Type: application/json" \
  -d '{"serial": "SN-001234", "version": "2"}'
```

---

### 12.19 GET /v1/config/set-billing-day

Configura el dia de facturacion del ciclo de cobro en el dispositivo.

| Campo             | Valor                                                        |
| ----------------- | ------------------------------------------------------------ |
| **Metodo**        | `GET`                                                        |
| **URL**           | `https://app.fluxai.solutions/api/v1/config/set-billing-day` |
| **Autenticacion** | `x-api-key` + Event Queue                                    |

**Query Parameters**:

| Parametro     | Tipo    | Requerido | Validacion           | Descripcion                         |
| ------------- | ------- | --------- | -------------------- | ----------------------------------- |
| `serial`      | string  | Si        | `ValidateSerialRule` | Serial del equipo                   |
| `billing_day` | integer | Si        | `min:1\|max:31`      | Dia del mes para facturacion (1-31) |

**Ejemplo curl**:

```bash
curl -X GET "https://app.fluxai.solutions/api/v1/config/set-billing-day?serial=SN-001234&billing_day=15" \
  -H "x-api-key: mi-clave-api"
```

---

### 12.20 GET /v1/config/set-status-service-coil

Configura el estado del rele de servicio del dispositivo.

| Campo             | Valor                                                                |
| ----------------- | -------------------------------------------------------------------- |
| **Metodo**        | `GET`                                                                |
| **URL**           | `https://app.fluxai.solutions/api/v1/config/set-status-service-coil` |
| **Autenticacion** | `x-api-key` + Event Queue                                            |

**Query Parameters**:

| Parametro             | Tipo    | Requerido | Validacion           | Descripcion                         |
| --------------------- | ------- | --------- | -------------------- | ----------------------------------- |
| `serial`              | string  | Si        | `ValidateSerialRule` | Serial del equipo                   |
| `status_service_coil` | boolean | Si        | `required\|boolean`  | `1` = habilitar, `0` = deshabilitar |

**Ejemplo curl**:

```bash
curl -X GET "https://app.fluxai.solutions/api/v1/config/set-status-service-coil?serial=SN-001234&status_service_coil=1" \
  -H "x-api-key: mi-clave-api"
```

---

### 12.21 GET /v1/config/set-password-meter-app

Configura la contrasena de acceso al medidor desde la aplicacion movil.

| Campo             | Valor                                                               |
| ----------------- | ------------------------------------------------------------------- |
| **Metodo**        | `GET`                                                               |
| **URL**           | `https://app.fluxai.solutions/api/v1/config/set-password-meter-app` |
| **Autenticacion** | `x-api-key` + Event Queue                                           |

**Query Parameters**:

| Parametro  | Tipo   | Requerido | Validacion           | Descripcion                                       |
| ---------- | ------ | --------- | -------------------- | ------------------------------------------------- |
| `serial`   | string | Si        | `ValidateSerialRule` | Serial del equipo                                 |
| `password` | string | Si        | `max:21`             | Contrasena para el medidor (maximo 21 caracteres) |

**Ejemplo curl**:

```bash
curl -X GET "https://app.fluxai.solutions/api/v1/config/set-password-meter-app?serial=SN-001234&password=newpass123" \
  -H "x-api-key: mi-clave-api"
```

---

### 12.22 GET /v1/config/get-password-meter

Consulta la contrasena actual configurada en el medidor.

| Campo             | Valor                                                           |
| ----------------- | --------------------------------------------------------------- |
| **Metodo**        | `GET`                                                           |
| **URL**           | `https://app.fluxai.solutions/api/v1/config/get-password-meter` |
| **Autenticacion** | `x-api-key` + Event Queue                                       |

**Query Parameters**:

| Parametro | Tipo   | Requerido | Descripcion       |
| --------- | ------ | --------- | ----------------- |
| `serial`  | string | Si        | Serial del equipo |

**Ejemplo curl**:

```bash
curl -X GET "https://app.fluxai.solutions/api/v1/config/get-password-meter?serial=SN-001234" \
  -H "x-api-key: mi-clave-api"
```

---

## 13. Endpoints — Webhook de Pagos Wompi (Solo Interno)

> **SOLO USO INTERNO — NO CONSUMIR DESDE INTEGRACIONES EXTERNAS**

| Metodo | URL                    | Descripcion                                                                                                                                               |
| ------ | ---------------------- | --------------------------------------------------------------------------------------------------------------------------------------------------------- |
| `POST` | `/pagos/wompi/eventos` | Webhook de notificaciones de pago de Wompi (pasarela colombiana). Verifica checksum SHA256. Definido en `routes/V1/web.php` — NO lleva el prefijo `/api`. |

Este endpoint es consumido exclusivamente por Wompi para notificar transacciones de pago. No requiere API key — usa verificacion de integridad via SHA256 con el `wompiSecret` del operador de red. No debe ser invocado por integradores externos.

---

## 14. Scoping por Cliente y Restricciones de Seguridad

Este es un apartado critico para cualquier integrador externo. El nivel de aislamiento por organizacion varia segun el endpoint.

| Endpoint                           | Filtrado por organizacion | Detalle                                                                                                                                                                   |
| ---------------------------------- | ------------------------- | ------------------------------------------------------------------------------------------------------------------------------------------------------------------------- |
| `GET /v1/data/date-range`          | **SI**                    | Valida que el serial pertenezca al `network_operator` vinculado a la API key. Si el serial es de otra organizacion, retorna HTTP 422.                                     |
| `GET /v1/event_logs`               | **NO**                    | Devuelve todos los event logs del sistema, sin filtrar por organizacion. El parametro `serial` es requerido por el middleware pero NO se usa para filtrar los resultados. |
| `GET /v1/event_logs/{id}`          | **NO**                    | Igual que el anterior — cualquier ID es accesible.                                                                                                                        |
| `GET /v1/event_logs/ack_logs/{id}` | **NO**                    | Sin scoping por organizacion.                                                                                                                                             |
| `GET/POST /v1/config/*`            | **NO**                    | Solo valida que el serial exista en la base de datos y este asignado a un cliente, no que pertenezca al dueno de la API key.                                              |

> **ADVERTENCIA**: Los endpoints de configuracion IoT y event logs NO validan que el serial consultado pertenezca a la organizacion del portador de la API key. Un integrador con una API key valida podria enviar comandos a cualquier dispositivo registrado en el sistema. Tener esto en cuenta al disenarar integraciones que accedan a estos endpoints.
>
> **Recomendacion**: Para integraciones de solo lectura de datos de clientes propios, usar exclusivamente `GET /v1/data/date-range` que es el unico endpoint con scoping correcto. Para comandos de configuracion, validar del lado del cliente que el serial objetivo pertenece a su organizacion antes de enviarlo.

---

## 15. Manejo de Errores

### 401 — No autorizado

```json
{
    "code": 401,
    "message": "No autorizado"
}
```

API key invalida, expirada, o no enviada en el header `x-api-key`. Tambien aplica para JWT invalido.

### 422 — Error de validacion

```json
{
    "code": 422,
    "message": "La solicitud enviada al servidor es incorrecta o no se puede procesar",
    "details": {
        "serial": ["El medidor electrico con serial SN-XXXXX no existe"]
    }
}
```

Los parametros enviados no pasan la validacion del backend. El campo `details` indica el campo especifico y el motivo.

### 429 — Demasiadas solicitudes

**Caso 1 — Anti-flood de evento (45 segundos)**:

```json
{
    "message": "Evento del mismo tipo en proceso"
}
```

Se envio el mismo tipo de comando al mismo dispositivo dentro de la ventana de 45 segundos.

**Caso 2 — Rate limit global**:

```json
{
    "message": "Too Many Requests"
}
```

Se superaron los 60 requests por minuto.

### Respuesta de error MQTT

Los endpoints de configuracion retornan HTTP 200 incluso cuando el envio MQTT falla. Verificar el campo `message` en la respuesta:

```json
{
    "data": {
        "message": "Fallo el envio del mensaje",
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

---

## 16. Flujo Tipico de Integracion

### Caso 1: Consultar datos historicos de un medidor

```
1. Obtener API key del administrador de FluxAi

2. Identificar el serial del medidor a consultar (provisto por FluxAi)

3. Consultar datos por rango de fecha:
   GET /api/v1/data/date-range?serial=SN-001234&fecha_inicio=1718400000&fecha_fin=1718486400
   x-api-key: mi-clave-api

4. Procesar la respuesta paginada (campos voltage_l1, current_l1, power_l1, energy_l1, etc.)

5. Si hay mas paginas, iterar usando el campo "links.next" de la respuesta
```

**Ejemplo completo**:

```bash
# Paso 3: Consultar datos del 15 de junio 2024
curl -X GET "https://app.fluxai.solutions/api/v1/data/date-range?serial=SN-001234&fecha_inicio=2024-06-15%2000:00:00&fecha_fin=2024-06-16%2000:00:00" \
  -H "x-api-key: mi-clave-api" \
  -H "Accept: application/json"
```

---

### Caso 2: Enviar un comando y verificar ejecucion en el dispositivo

```
1. Enviar el comando:
   GET /api/v1/config/set-status-coil?serial=SN-001234&status=1
   x-api-key: mi-clave-api
   → Respuesta incluye "transaction_id": 15

2. Esperar entre 5-30 segundos (tiempo variable segun conectividad del dispositivo)

3. Verificar el ACK del dispositivo:
   GET /api/v1/event_logs/ack_logs/15?serial=SN-001234
   x-api-key: mi-clave-api
   → Buscar el item con request_type "main_server_mc_request"
   → Verificar que su "status" sea "successful" o "error"

4. Si el status sigue en "created" despues de varios minutos, el dispositivo
   puede estar offline o con problemas de conectividad MQTT
```

**Ejemplo completo**:

```bash
# Paso 1: Encender el rele
curl -X GET "https://app.fluxai.solutions/api/v1/config/set-status-coil?serial=SN-001234&status=1" \
  -H "x-api-key: mi-clave-api" \
  -H "Accept: application/json"
# Guardar el transaction_id de la respuesta

# Paso 3: Verificar ACK (reemplazar 15 con el transaction_id real)
curl -X GET "https://app.fluxai.solutions/api/v1/event_logs/ack_logs/15?serial=SN-001234" \
  -H "x-api-key: mi-clave-api" \
  -H "Accept: application/json"
```

---

### Caso 3: Flujo completo de la app tecnico

```
1. Login del tecnico:
   POST /api/auth/login
   → Obtener access_token (JWT)

2. Obtener lista de trabajos asignados:
   POST /api/auth/job-list
   Authorization: Bearer <access_token>

3. Ejecutar trabajo en campo (instalacion, lectura, etc.)

4. Actualizar la orden con evidencia fotografica:
   POST /api/auth/orders-update
   Authorization: Bearer <access_token>
   [multipart/form-data con orden y fotos]

5. Si el token expira, refrescarlo antes de continuar:
   POST /api/auth/refresh
   Authorization: Bearer <access_token>
   → Obtener nuevo access_token

6. Al finalizar la jornada, invalidar el token:
   POST /api/auth/logout
   Authorization: Bearer <access_token>
```

---

## 17. Resumen de Todos los Endpoints

| #   | Metodo | URL                                        | Autenticacion  | Uso Externo      | Seccion    |
| --- | ------ | ------------------------------------------ | -------------- | ---------------- | ---------- |
| 1   | POST   | `/api/auth/login`                          | Ninguna        | Si               | Auth JWT   |
| 2   | POST   | `/api/auth/logout`                         | JWT            | Si               | Auth JWT   |
| 3   | POST   | `/api/auth/refresh`                        | JWT            | Si               | Auth JWT   |
| 4   | POST   | `/api/auth/me`                             | JWT            | Si               | Auth JWT   |
| 5   | POST   | `/api/auth/job-list`                       | JWT            | App Tecnico      | Ordenes    |
| 6   | POST   | `/api/auth/orders-update`                  | JWT            | App Tecnico      | Ordenes    |
| 7   | POST   | `/api/auth/order-create`                   | JWT            | App Tecnico      | Ordenes    |
| 8   | GET    | `/api/auth/firmwares`                      | Password       | App Tecnico      | Firmware   |
| 9   | GET    | `/api/auth/firmware/{id}`                  | Password       | App Tecnico      | Firmware   |
| 10  | POST   | `/api/auth/firmware-create`                | JWT            | App Tecnico      | Firmware   |
| 11  | POST   | `/api/v1/mqtt_input`                       | Ninguna        | **INTERNO**      | MQTT       |
| 12  | POST   | `/api/v1/mqtt_input/real-time`             | Ninguna        | **INTERNO**      | MQTT       |
| 13  | POST   | `/api/v1/clients/client-add`               | x-api-key      | Si               | Clientes   |
| 14  | GET    | `/api/v1/event_logs`                       | x-api-key      | Si (sin scoping) | Event Logs |
| 15  | GET    | `/api/v1/event_logs/{id}`                  | x-api-key      | Si (sin scoping) | Event Logs |
| 16  | GET    | `/api/v1/event_logs/ack_logs/{ackLog}`     | x-api-key      | Si (sin scoping) | Event Logs |
| 17  | GET    | `/api/v1/data/date-range`                  | x-api-key      | Si (con scoping) | Datos      |
| 18  | POST   | `/api/v1/config/notification-webhook`      | Ninguna        | **INTERNO**      | Webhooks   |
| 19  | POST   | `/api/v1/config/set-alert-limits`          | x-api-key      | Si (sin scoping) | Config IoT |
| 20  | POST   | `/api/v1/config/set-control-limits`        | x-api-key      | Si (sin scoping) | Config IoT |
| 21  | POST   | `/api/v1/config/set-status-control-limits` | x-api-key      | Si (sin scoping) | Config IoT |
| 22  | GET    | `/api/v1/config/set-alert-time`            | x-api-key      | Si (sin scoping) | Config IoT |
| 23  | GET    | `/api/v1/config/set-sampling-time`         | x-api-key      | Si (sin scoping) | Config IoT |
| 24  | GET    | `/api/v1/config/set-wifi-credentials`      | x-api-key      | Si (sin scoping) | Config IoT |
| 25  | GET    | `/api/v1/config/set-broker-credentials`    | x-api-key      | Si (sin scoping) | Config IoT |
| 26  | GET    | `/api/v1/config/set-date`                  | x-api-key      | Si (sin scoping) | Config IoT |
| 27  | GET    | `/api/v1/config/get-date`                  | x-api-key      | Si (sin scoping) | Config IoT |
| 28  | GET    | `/api/v1/config/set-status-coil`           | x-api-key      | Si (sin scoping) | Config IoT |
| 29  | GET    | `/api/v1/config/get-status-coil`           | x-api-key      | Si (sin scoping) | Config IoT |
| 30  | GET    | `/api/v1/config/set-config-sensor`         | x-api-key      | Si (sin scoping) | Config IoT |
| 31  | GET    | `/api/v1/config/get-config-sensor`         | x-api-key      | Si (sin scoping) | Config IoT |
| 32  | GET    | `/api/v1/config/get-status-sensor`         | x-api-key      | Si (sin scoping) | Config IoT |
| 33  | GET    | `/api/v1/config/get-status-connection`     | x-api-key      | Si (sin scoping) | Config IoT |
| 34  | GET    | `/api/v1/config/get-current-readings`      | x-api-key      | Si (sin scoping) | Config IoT |
| 35  | GET    | `/api/v1/config/set-status-real-time`      | x-api-key      | Si (sin scoping) | Config IoT |
| 36  | POST   | `/api/v1/config/ota-update`                | x-api-key      | Si (sin scoping) | Config IoT |
| 37  | GET    | `/api/v1/config/set-billing-day`           | x-api-key      | Si (sin scoping) | Config IoT |
| 38  | GET    | `/api/v1/config/set-status-service-coil`   | x-api-key      | Si (sin scoping) | Config IoT |
| 39  | GET    | `/api/v1/config/set-password-meter-app`    | x-api-key      | Si (sin scoping) | Config IoT |
| 40  | GET    | `/api/v1/config/get-password-meter`        | x-api-key      | Si (sin scoping) | Config IoT |
| 41  | POST   | `/pagos/wompi/eventos`                     | Checksum Wompi | **INTERNO**      | Pagos      |

**Total: 41 endpoints documentados.**

> **Leyenda**:
>
> - **INTERNO**: No debe ser invocado por integradores externos.
> - **con scoping**: Filtra resultados por la organizacion vinculada a la API key.
> - **sin scoping**: Acceso sin restriccion de organizacion — cualquier serial valido del sistema es accesible.
> - **App Tecnico**: Disenado para el flujo de la aplicacion movil de tecnicos de campo.
