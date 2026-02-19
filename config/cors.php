<?php

/*
|--------------------------------------------------------------------------
| Cross-Origin Resource Sharing (CORS) Configuration
|--------------------------------------------------------------------------
|
| Configuración administrable desde .env para controlar CORS.
|
| Variables de entorno disponibles:
|   CORS_ALLOWED_ORIGINS  - Orígenes permitidos (separados por coma, o * para todos)
|   CORS_PATHS            - Rutas que aplican CORS (separadas por coma)
|   CORS_EXPOSED_HEADERS  - Headers expuestos al cliente (separados por coma)
|   CORS_MAX_AGE          - Cache de preflight en segundos (0 = sin cache)
|   CORS_SUPPORTS_CREDENTIALS - Permitir credenciales cross-origin (true/false)
|
| IMPORTANTE: storage/* está incluido por defecto para permitir la descarga
| de archivos de firmware (.bin) desde la app móvil (WebView).
|
| To learn more: https://developer.mozilla.org/en-US/docs/Web/HTTP/CORS
|
*/

$allowedOrigins = env('CORS_ALLOWED_ORIGINS', '*');
$corsOrigins = $allowedOrigins === '*'
    ? ['*']
    : array_map('trim', explode(',', $allowedOrigins));

$corsPaths = array_map('trim', explode(',', env('CORS_PATHS', 'api/*,storage/*,sanctum/csrf-cookie')));

$exposedHeaders = array_filter(
    array_map('trim', explode(',', env('CORS_EXPOSED_HEADERS', 'Content-Disposition,Content-Length')))
);

return [

    'paths' => $corsPaths,

    'allowed_methods' => ['*'],

    'allowed_origins' => $corsOrigins,

    'allowed_origins_patterns' => [],

    'allowed_headers' => ['*'],

    'exposed_headers' => $exposedHeaders,

    'max_age' => (int) env('CORS_MAX_AGE', 0),

    'supports_credentials' => (bool) env('CORS_SUPPORTS_CREDENTIALS', false),

];
