# 🚀 Despliegues pendientes a producción

> Fuente de verdad de commits que ya están en `master` pero **aún no están desplegados**
> en `app.fluxai.solutions`. El desarrollador principal con acceso SSH al host ejecuta
> el deploy y actualiza este documento moviendo las filas a la sección `## ✅ Desplegados`.

---

## ⏳ Pendiente

### 2026-04-21

| # | Commit     | PR                                                          | Descripción                                                                                      | Responsable    |
|---|------------|-------------------------------------------------------------|--------------------------------------------------------------------------------------------------|----------------|
| 1 | `6380f340` | [#7](https://github.com/AlebasFluxAI/aomenertec/pull/7)     | feat(ui): BaseLine rediseñado + tipografía Inter sobria + navegación FluxAI                      | *sin asignar*  |
| 2 | `8d3db85d` | [#6](https://github.com/AlebasFluxAI/aomenertec/pull/6)     | feat(monitoreo): dashboard unificado FluxAI con toggle tiempo real                               | *sin asignar*  |

---

## 🎯 Alcance de los cambios pendientes

Solo archivos `resources/views/**/*.blade.php` y CSS inline. **No hay**:
- ❌ Migraciones de base de datos
- ❌ Cambios de dependencias Composer/NPM
- ❌ Cambios en lógica PHP/Livewire/jobs
- ❌ Variables de entorno nuevas
- ❌ Cambios en Supervisor/queue workers

Esto permite deploy rápido sin downtime ni `composer install` ni `npm run prod`.

---

## 📋 Instrucciones de deploy

### Opción A — Deploy mínimo ✨ recomendado

**~30 segundos, sin downtime.** Como son solo vistas + CSS, no hace falta reconstruir
assets ni reiniciar contenedores.

```bash
cd /ruta/al/repo
git fetch origin master
git pull origin master
./vendor/bin/sail artisan view:clear
./vendor/bin/sail artisan view:cache
```

### Opción B — Deploy completo (flujo estándar)

Si prefieres el flujo documentado en `DEPLOYMENT-PRODUCTION.md`:

```bash
cd /ruta/al/repo
git pull origin master
make prod-update        # composer install, npm run prod, migrate, view:cache, restart
```

---

## ✅ Verificación post-deploy

1. Abrir `https://app.fluxai.solutions/v1/admin/client/monitoring/{client_id}`
2. Verificar el checklist:
   - [ ] Pestañas visibles: **Dashboard / BaseLine / Reportes y tarifas** (3 pestañas, no 6)
   - [ ] Fuente Inter legible en toda la página
   - [ ] Botón **Acciones** abre dropdown con icono en cada opción y gradiente corporativo
   - [ ] BaseLine muestra 3 cards reactivas (Referencia / Comparación / Ahorro-Sobreconsumo)
   - [ ] Sidebar con franja gradiente verde→azul en la parte superior
   - [ ] Hover en iconos del header (perfil, rol, logout) muestra elevación sutil
3. Si algo se ve con fuente genérica (serif):
   ```bash
   ./vendor/bin/sail artisan view:clear
   ./vendor/bin/sail artisan view:cache
   ```

---

## 🔙 Rollback

Como son solo vistas, el rollback es seguro e inmediato:

```bash
git reset --hard 6a02370          # último commit pre-rediseño
./vendor/bin/sail artisan view:clear
```

El SHA `6a02370` corresponde a `chore(assets): build producción – gráfica real-time dinámica`.

---

## 📝 Proceso de actualización de este documento

**Quien ejecuta el deploy** debe:

1. Verificar que todos los checks post-deploy pasen.
2. Mover las filas desplegadas de `## ⏳ Pendiente` a `## ✅ Desplegados` con la fecha real del deploy.
3. Commitear el cambio a `master` con mensaje tipo:
   ```
   docs(deployment): marcar #6 y #7 como desplegados en prod 2026-04-XX
   ```

---

## ✅ Desplegados

*Mover aquí las filas una vez desplegadas y verificadas en producción.*

| Fecha deploy | Commit | PR | Descripción | Desplegado por |
|--------------|--------|----|-|----|
| *pendiente* | — | — | — | — |

---

## 🔗 Referencias

- [GITHUB-WORKFLOW.md](./GITHUB-WORKFLOW.md) — flujo de desarrollo
- [DEPLOYMENT-PRODUCTION.md](../DEPLOYMENT-PRODUCTION.md) — guía completa de deploy
- [AGENTS.md](../AGENTS.md) — arquitectura del sistema
