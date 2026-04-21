# GitHub Workflow — Aomenertec

Guía de trabajo con GitHub: issues, labels, PRs y Project board.

---

## 🎯 TL;DR

```
Idea/Bug → Issue (con template) → Branch → PR (con template) → Review → Merge
                 ↓                                                        ↓
           Project: Backlog                                         Project: Done
```

**Todo trabajo empieza con un issue. Sin issue, no hay PR.**

---

## 1. Crear un Issue

👉 https://github.com/AlebasFluxAI/aomenertec/issues/new/choose

Elegí el template:
- **🐛 Reporte de Bug** → algo está roto
- **✨ Solicitud de Feature** → nueva funcionalidad o mejora

Completá el form. **Los campos obligatorios son obligatorios por algo** — no los saltees.

### Labels que se aplican solos

Los templates asignan automáticamente:
- `type: bug` o `type: feature`
- `status: needs-triage`

### Labels adicionales (opcional al crear, obligatorio al tomar)

Agregá según corresponda:

| Categoría | Ejemplos | Cuándo |
|-----------|----------|--------|
| `domain:` | `domain: mqtt`, `domain: billing` | Siempre — identifica el área |
| `priority:` | `priority: critical`, `priority: high` | Si conocés la urgencia |
| Especiales | `breaking change`, `needs-migration`, `production-only` | Si aplica |

---

## 2. Taxonomía de Labels

### `type:` (qué tipo de trabajo)
`bug` · `feature` · `refactor` · `docs` · `chore` · `test` · `security` · `performance`

### `domain:` (qué área del sistema — matchea namespace V1)
`admin` · `client` · `network-operator` · `technician` · `mqtt` · `event-log` · `config-client` · `auth` · `billing` · `broadcasting` · `scheduled-jobs` · `infra`

### `priority:`
`critical` (bloquea prod) · `high` · `medium` · `low`

### `status:` (estado del issue)
`needs-triage` · `blocked` · `in-progress` · `needs-review` · `needs-info` · `wont-fix` · `duplicate`

### Especiales
`good first issue` · `help wanted` · `breaking change` · `production-only` · `needs-migration` · `needs-deployment`

---

## 3. Flujo de trabajo

### a) Tomar un issue

1. Asignate el issue (botón "Assignees" en sidebar)
2. Cambiá label: quitá `status: needs-triage` → agregá `status: in-progress`
3. El Project lo mueve automáticamente a **🏗️ In Progress**

### b) Crear branch

Convención de nombres:

```bash
<tipo>/<issue-number>-<descripcion-corta>
```

Ejemplos:
```bash
feat/123-dashboard-network-operator
fix/145-mqtt-connection-timeout
refactor/156-client-service-layer
docs/161-deployment-guide
```

```bash
git checkout master
git pull aomenertec master
git checkout -b feat/123-dashboard-network-operator
```

### c) Commits

**Conventional Commits en español o inglés (uno o el otro, no mezclar en un mismo commit):**

```
<tipo>(<scope>): descripción corta

<body opcional explicando el POR QUÉ>
```

Tipos: `feat` · `fix` · `refactor` · `docs` · `chore` · `test` · `perf` · `style`

Ejemplos:
```
feat(network-operator): add live consumption dashboard

fix(mqtt): handle disconnection timeout in ConsumerCommand

refactor(client): extract billing logic to dedicated service
```

**❌ NO:**
- Commits con "Co-Authored-By" o atribución a AI
- Mensajes tipo "update", "fix", "changes" sin contexto
- Mezclar múltiples features/fixes en un commit

### d) Abrir Pull Request

```bash
git push origin feat/123-dashboard-network-operator
# Abre el PR desde GitHub o:
gh pr create --base master
```

El template se carga automático. **Completá el checklist**, no lo dejes vacío.

**Campos clave del PR**:
- `Closes #123` en la descripción → cierra el issue al mergear
- Marcar dominio afectado
- Marcar tipo de cambio (bug/feat/refactor/etc)
- Completar checklist técnico (tests, migraciones, cache, etc.)

---

## 4. Project Board (Kanban)

👉 https://github.com/orgs/AlebasFluxAI/projects/2

```
📥 Backlog  →  🏗️ In Progress  →  👀 In Review  →  ✅ Done
```

### Movimiento automático

| Acción | Resultado |
|--------|-----------|
| Issue creado | → 📥 Backlog |
| Abrir PR que linkea el issue | → 👀 In Review |
| Merge PR | → ✅ Done |
| Cerrar issue | → ✅ Done |

### Movimiento manual

Solo cambiá manualmente `🏗️ In Progress` cuando empezás a codear (no al crear el branch, cuando arrancás en serio).

---

## 5. Checklist antes de abrir PR

```bash
# 1. Tests pasan
make test

# 2. Cache limpia si tocaste config/routes
make cache-clear

# 3. Migración en DB limpia si tocaste DB
make migrate-fresh

# 4. Supervisor OK si tocaste MQTT/jobs
make supervisor-status

# 5. Sin dd(), dump(), console.log, código comentado
git diff --cached | grep -E "(dd\(|dump\(|console\.log)"
```

---

## 6. Review de PR

**Como autor del PR:**
- Respondé cada comentario del reviewer (resolve/explicá)
- Si hay cambios: commit nuevo (no squash/force-push durante review)
- Re-solicitá review cuando esté listo

**Como reviewer:**
- Chequeá el checklist del template
- Probá los cambios localmente si afecta algo crítico (MQTT, billing, migrations)
- Aprobá solo si el checklist está completo Y los tests pasan

---

## 7. Casos especiales

### Hotfix a producción
1. Branch desde `master`: `fix/HOTFIX-descripcion`
2. Label: `priority: critical` + `production-only`
3. PR con aprobación mínima 1 persona
4. Merge → seguir `DEPLOYMENT-PRODUCTION.md`

### Cambio con migración
1. Label: `needs-migration`
2. Documentar en PR template sección "Notas de deployment"
3. Probar `make migrate-fresh` localmente antes del merge

### Breaking change
1. Label: `breaking change`
2. Bump de versión mayor en el tag correspondiente
3. Documentar migración path en el PR

---

## 8. Comandos útiles (gh CLI)

```bash
# Ver issues asignados a mí
gh issue list --assignee @me

# Ver mis PRs
gh pr list --author @me

# Crear issue rápido
gh issue create --title "..." --body "..." --label "type: bug,domain: mqtt"

# Ver estado de un PR
gh pr status

# Checkout de un PR para revisarlo local
gh pr checkout 123
```

---

## 9. Links rápidos

| Recurso | URL |
|---------|-----|
| Repo | https://github.com/AlebasFluxAI/aomenertec |
| Issues | https://github.com/AlebasFluxAI/aomenertec/issues |
| PRs | https://github.com/AlebasFluxAI/aomenertec/pulls |
| Project Kanban | https://github.com/orgs/AlebasFluxAI/projects/2 |
| Labels | https://github.com/AlebasFluxAI/aomenertec/labels |
| Docs técnicas | [AGENTS.md](../AGENTS.md) · [DEPLOYMENT-PRODUCTION.md](../DEPLOYMENT-PRODUCTION.md) · [README-DOCKER.md](../README-DOCKER.md) |

---

## 10. Reglas de oro

1. **No hay PR sin issue.** Si es urgente, creá el issue igual (30 segundos).
2. **No mergees tu propio PR** sin review, salvo hotfix crítico documentado.
3. **No commitees `.env`, credenciales, keys.** Nunca.
4. **Tests pasan antes de pedir review.** No hagas perder tiempo al reviewer.
5. **Un PR, un propósito.** No mezcles refactor + feature + bugfix en el mismo PR.
6. **Si bloqueás el trabajo de otro**, poné label `status: blocked` y comentá por qué.

---

## 11. 🚀 Despliegues pendientes a producción

> Commits ya mergeados en `master` que **aún no están desplegados** en el servidor
> de producción (`app.fluxai.solutions`). El desarrollador principal con acceso
> SSH al host debe ejecutar el deploy.

### Pendiente — 2026-04-21

| # | Commit | PR | Descripción |
|---|---|---|---|
| 1 | `6380f340` | [#7](https://github.com/AlebasFluxAI/aomenertec/pull/7) | **feat(ui):** BaseLine rediseñado + tipografía Inter sobria + navegación FluxAI |
| 2 | `8d3db85d` | [#6](https://github.com/AlebasFluxAI/aomenertec/pull/6) | **feat(monitoreo):** dashboard unificado FluxAI con toggle tiempo real |

### Alcance del cambio

Solo archivos `resources/views/**/*.blade.php` y CSS inline. **No hay**:
- ❌ Migraciones de base de datos
- ❌ Cambios de dependencias Composer/NPM
- ❌ Cambios en lógica PHP/Livewire/jobs
- ❌ Variables de entorno nuevas
- ❌ Cambios en Supervisor/queue workers

### Instrucciones para el desarrollador principal

#### Opción A — Deploy mínimo (recomendado, ~30 s, sin downtime)

Son solo vistas + CSS, no hace falta reconstruir assets ni reiniciar contenedores:

```bash
cd /ruta/al/repo
git fetch origin master
git pull origin master
./vendor/bin/sail artisan view:clear
./vendor/bin/sail artisan view:cache
```

#### Opción B — Deploy completo (si prefiere el flujo estándar)

```bash
cd /ruta/al/repo
git pull origin master
make prod-update        # composer install, npm run prod, migrate, view:cache, restart
```

#### Verificación post-deploy

1. Abrir `https://app.fluxai.solutions/v1/admin/client/monitoring/{client_id}`
2. Verificar:
   - [ ] Pestañas visibles: **Dashboard / BaseLine / Reportes y tarifas** (3 pestañas, no 6)
   - [ ] Fuente legible en toda la página (Inter)
   - [ ] Botón **Acciones** abre dropdown con iconos por cada opción
   - [ ] BaseLine muestra 3 cards (Referencia / Comparación / Ahorro-Sobreconsumo)
   - [ ] Sidebar con franja gradiente verde→azul en la parte superior
3. Si algo se ve con fuente genérica (serif) → `php artisan view:clear && php artisan view:cache`

#### Rollback

Como son solo vistas, un rollback es seguro:

```bash
git reset --hard 6a02370   # último commit pre-rediseño
./vendor/bin/sail artisan view:clear
```

> **Cuando se complete el deploy**, marcar los commits como desplegados moviendo
> esta tabla a una sección `## Desplegados` o eliminando la fila. Mantener este
> documento como fuente de verdad de qué está en prod vs qué está en `master`.
