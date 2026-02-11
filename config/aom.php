<?php

return [
    'api_url' => env('AOM_API_URL', 'http://localhost'),
    'api_config_path' => env('AOM_API_CONFIG_PATH', '/api/v1/config'),
    'api_clients_path' => env('AOM_API_CLIENTS_PATH', '/api/v1/clients'),

    // Password para acceso a endpoints de firmware desde la app móvil
    'firmware_password' => env('FIRMWARE_API_PASSWORD', '123456789'),

    // Valores por defecto al crear ClientConfiguration para un nuevo cliente
    'client_defaults' => [
        'mqtt_host' => env('DEFAULT_MQTT_HOST', '3.12.98.178'),
        'mqtt_port' => env('DEFAULT_MQTT_PORT', '1883'),
        'mqtt_user' => env('DEFAULT_MQTT_USER', 'enertec'),
        'mqtt_password' => env('DEFAULT_MQTT_PASSWORD', 'enertec2020**'),
        'real_time_latency' => env('DEFAULT_REAL_TIME_LATENCY', 30),
        'storage_latency' => env('DEFAULT_STORAGE_LATENCY', 1),
        'billing_day' => env('DEFAULT_BILLING_DAY', 1),
    ],
];
