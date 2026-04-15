📊 Arquitectura del Sistema Enertec

  1. Acceso al Panel de Administración

  Credenciales de acceso:
  - URL: http://localhost
   - Email: support@fluxai.solutions
   - Contraseña: Flux@i2026!Secure

  Nota importante: El sistema tiene un UserObserver que sobrescribe la contraseña al crear usuarios y la establece como el
  hash del número de identificación. Por defecto, la contraseña siempre es el número de identificación del usuario.

  2. Sistema MQTT - Arquitectura de Comunicación

  El broker MQTT Mosquitto está funcionando correctamente en el puerto 1883. Puedes verificarlo de varias formas:

  Verificación del Estado MQTT:

  # Ver estado del contenedor Mosquitto
  ./vendor/bin/sail ps

  # Ver logs en tiempo real del broker MQTT
  ./vendor/bin/sail logs mosquitto -f

  # Ver últimas conexiones
  ./vendor/bin/sail logs mosquitto --tail 20

  Los logs muestran que el cliente PHP-MQTT `client_consumer_princi` se está conectando al broker, lo cual es correcto y
  esperado.

  Flujo de Datos MQTT:

  Dispositivos IoT (Medidores)
      ↓
  Mosquitto MQTT Broker (puerto 1883)
      ↓
  PHP-MQTT ConsumerCommand (php artisan mqtt:consume)
      ↓  dispatch() directo a Laravel Jobs
  SaveMicrocontrollerDataJob / SaveAlertDataJob / PushRealTimeMicrocontrollerDataJob
      ↓
  Procesamiento en Laravel + Broadcasting via Laravel Echo Server
      ↓
  Interfaz Web (Tiempo Real via WebSockets)

  3. Servicios en Ejecución

  El contenedor de Laravel ejecuta automáticamente mediante Supervisor:

  1. Laravel Echo Server (Puerto 8443) - WebSockets para actualizaciones en tiempo real
  2. mqtt-consumer (php artisan mqtt:consume) - Se suscribe a topics MQTT:
     - v1/mc/data (datos regulares, QoS 2) → SaveMicrocontrollerDataJob
     - mc/data (datos regulares legacy, QoS 2) → SaveMicrocontrollerDataJob
     - v1/mc/alert (alertas, QoS 0) → SaveAlertDataJob
     - v1/mc/alert_control (alertas de control, QoS 0) → SaveAlertDataJob
     - v1/mc/ack (acknowledgments, QoS 0) → SetConfigJob
     - v1/mc/real_time (datos tiempo real, QoS 0) → PushRealTimeMicrocontrollerDataJob
  3. queue-worker - Procesa jobs de la cola Redis
  4. scheduler - Tareas programadas de Laravel

  Verificar que el consumer MQTT esté corriendo:

  # Ver estado de Supervisor
  make supervisor-status

  # Ver logs del consumer MQTT
  docker exec aomenertec-laravel.test-1 tail -f /var/log/supervisor/mqtt-consumer.out.log

  4. Cómo Monitorear MQTT desde el Admin

  Desde el panel de administración puedes:

  - Usuarios → Gestionar usuarios del sistema
  - Clientes → Ver clientes con medidores activos
  - Equipos → Ver equipos/medidores conectados
  - Configuración → Ajustes del sistema

  Para ver datos en tiempo real de MQTT, necesitarías navegar a la sección de clientes y seleccionar un cliente específico
  que tenga medidores asociados. Allí podrías ver:
  - Consumo en tiempo real (si hay medidores publicando en MQTT)
  - Historial de consumo
  - Alertas configuradas
  - Control remoto de dispositivos

  5. Procesamiento MQTT

  El consumer PHP-MQTT (ConsumerCommand) se conecta directamente al broker Mosquitto
  y despacha jobs de Laravel sin intermediarios HTTP. Los endpoints HTTP legacy
  (/api/v1/mqtt_input y /api/v1/mqtt_input/real-time) siguen existiendo como fallback
  pero ya no son utilizados por el flujo principal.

  Jobs principales:
  - SaveMicrocontrollerDataJob - Procesa frames binarios de datos de medidores
  - SaveAlertDataJob - Procesa alertas de dispositivos
  - PushRealTimeMicrocontrollerDataJob - Reenvía datos en tiempo real via WebSocket
  - SetConfigJob - Procesa acknowledgments de configuración

  6. Problema del WebSocket Error

  Notas que en la consola del navegador aparece:
  WebSocket connection to 'ws://localhost:8443/socket.io/' failed

  Esto indica que Laravel Echo Server no está corriendo completamente. El consumer MQTT sí está funcionando
  (puedes ver las conexiones en los logs de Mosquitto), pero el servidor de WebSockets para el navegador necesita ser
  iniciado.

  Para verificar y solucionar:

  # Verificar si Laravel Echo Server está corriendo
  docker exec aomenertec-laravel.test-1 ps aux | grep "laravel-echo"

  # Ver logs del Echo Server
  docker exec aomenertec-laravel.test-1 cat /var/log/supervisor/laravel-echo-server.out.log

  Resumen Visual:

  ┌─────────────────────────────────────────────────────────┐
  │  DOCKER COMPOSE STACK                                    │
  ├─────────────────────────────────────────────────────────┤
  │                                                           │
  │  ┌──────────────┐  ┌──────────────┐  ┌──────────────┐  │
  │  │ Laravel App  │  │  PostgreSQL  │  │    Redis     │  │
  │  │  (Port 80)   │  │  (Port 5432) │  │  (Port 6379) │  │
  │  │              │  │              │  │              │  │
  │  │ - PHP 8.1    │  │ - Database   │  │ - Cache      │  │
  │  │ - Supervisor │  └──────────────┘  │ - Sessions   │  │
  │  │ - Node.js    │                    │ - Queue      │  │
  │  │              │  ┌──────────────┐  └──────────────┘  │
  │  └──────┬───────┘  │  Mosquitto   │                     │
  │         │          │ MQTT Broker  │                     │
  │  Supervisor:       │  (Port 1883) │                     │
  │  • mqtt-consumer   │              │                     │
  │    (PHP-MQTT)  ◄───│ - IoT Comm   │                     │
  │  • queue-worker    └──────────────┘                     │
  │  • echo-server                                           │
  │  • scheduler                                             │
  │                                                          │
  └──────────────────────────────────────────────────────────┘

  El MQTT está funcionando correctamente - puedes ver las conexiones activas en los logs. Solo necesitas crear clientes y
  medidores en el sistema para ver datos fluyendo en tiempo real.

  Nota: Los scripts Python legacy (receiveMqttEvent.py, receiveMqttRealTimeEvent.py) fueron reemplazados
  por el consumer PHP-MQTT (ConsumerCommand) que se conecta directamente al broker y despacha jobs de
  Laravel sin intermediarios HTTP. Los scripts permanecen en el directorio script/ como referencia pero
  ya no se ejecutan en Supervisor ni están incluidos en la imagen Docker.