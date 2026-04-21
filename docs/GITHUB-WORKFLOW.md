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

> **Fuente de verdad:** [`PENDING-DEPLOYMENTS.md`](./PENDING-DEPLOYMENTS.md)

Commits ya mergeados en `master` que aún no están desplegados en `app.fluxai.solutions`
se registran en [`docs/PENDING-DEPLOYMENTS.md`](./PENDING-DEPLOYMENTS.md) con:

- Tabla de commits pendientes (SHA, PR, descripción, responsable)
- Alcance del cambio (si toca migraciones, deps, etc.)
- Instrucciones paso a paso de deploy (mínimo / completo)
- Checklist de verificación post-deploy
- Procedimiento de rollback

El desarrollador que ejecuta el deploy debe **mover las filas desplegadas** a la
sección `## ✅ Desplegados` del mismo documento.
