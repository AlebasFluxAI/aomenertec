<!--
  Gracias por contribuir al proyecto Enertec.
  Completá esta plantilla para que el review sea rápido y claro.
-->

## 📝 Descripción

<!-- Explicá QUÉ hace este PR y, sobre todo, POR QUÉ. -->



## 🔗 Issue relacionado

<!-- Usá "Closes #123" o "Fixes #123" para cerrar el issue automáticamente al mergear. -->

Closes #

## 🎯 Tipo de cambio

<!-- Marcá con [x] lo que aplique -->

- [ ] 🐛 Bug fix (cambio no-breaking que arregla un problema)
- [ ] ✨ Nueva feature (cambio no-breaking que agrega funcionalidad)
- [ ] 💥 Breaking change (cambio que rompe compatibilidad)
- [ ] ♻️ Refactor (cambio de código que no modifica comportamiento externo)
- [ ] 📝 Docs (solo documentación)
- [ ] 🔧 Chore (build, config, CI, dependencias)
- [ ] ✅ Tests (agregar o corregir tests)

## 🏷️ Dominio afectado

<!-- Marcá los dominios que toca este PR -->

- [ ] Admin
- [ ] Client
- [ ] NetworkOperator
- [ ] Technician
- [ ] MqttInput (IoT / MQTT)
- [ ] EventLog
- [ ] ConfigurationClient
- [ ] Auth (JWT / Jetstream)
- [ ] Billing / Invoicing
- [ ] Broadcasting (Echo Server)
- [ ] Scheduled Jobs / Cron
- [ ] Infraestructura (Docker / Supervisor)

## ✅ Checklist técnico

### General
- [ ] Mi código sigue las convenciones del proyecto (ver `AGENTS.md`)
- [ ] Todo el código nuevo está bajo el namespace **V1**
- [ ] Hice self-review de mi propio código
- [ ] Agregué comentarios en partes complejas / no obvias
- [ ] No dejé `dd()`, `dump()`, `console.log`, ni código comentado

### Tests
- [ ] Corrí los tests localmente (`make test`) y pasan
- [ ] Agregué tests para los cambios (cuando aplica)
- [ ] Los tests nuevos fallan ANTES de mi fix (para bugs)

### Base de datos
- [ ] Los cambios de DB están en **migraciones** (NO modificaciones manuales)
- [ ] Los datos de referencia nuevos están en **seeders**
- [ ] Probé la migración en DB limpia (`make migrate-fresh`)
- [ ] La migración es reversible (tiene `down()` correcto)

### Configuración & Deployment
- [ ] Si agregué config, usé `config('aom.xxx')` (NO `env()` directo en código)
- [ ] Documenté nuevas variables de entorno en `.env.example`
- [ ] Corrí `make cache-clear` después de cambios en config/routes
- [ ] Si afecta producción, documenté pasos extra en el PR

### MQTT & Background Jobs
- [ ] Si toqué MQTT, verifiqué el consumer (`make supervisor-status`)
- [ ] Si agregué jobs programados, los registré en `routes/V1/console.php`
- [ ] Si toqué broadcasting, probé con Echo Server corriendo

### Permisos & Seguridad
- [ ] Si agregué permisos nuevos, los documenté en `config/permissions.php`
- [ ] No expuse secretos, tokens ni credenciales
- [ ] Validé inputs de usuario (Request validation / Livewire rules)
- [ ] Respeté los middlewares de auth en las rutas

## 📸 Screenshots / Videos

<!-- Si hay cambios visuales, adjuntá capturas antes/después -->

## 🚀 Notas de deployment

<!-- ¿Hay que hacer algo especial al deployar? Ej: correr seeder, clear cache, reiniciar supervisor, configurar password MQTT, etc. -->

- [ ] No requiere pasos extra
- [ ] Requiere `make prod-update` normal
- [ ] Requiere pasos adicionales (describir abajo):

```bash
# comandos a correr en producción después del pull
```

## 🧪 Cómo probar este PR

<!-- Pasos concretos para que el reviewer pueda probar los cambios -->

1.
2.
3.
