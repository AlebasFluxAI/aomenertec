#!/usr/bin/env bash
# ==============================================================================
# setup-github-labels.sh
# ------------------------------------------------------------------------------
# Crea/actualiza el set base de labels en el repo de GitHub.
# Es idempotente: si un label existe, lo actualiza (color/descripción).
#
# Uso:
#   ./scripts/setup-github-labels.sh                    # repo actual (remote origin)
#   REPO=AlebasFluxAI/aomenertec ./scripts/setup-github-labels.sh
#   ./scripts/setup-github-labels.sh --delete-defaults  # borra labels default de GitHub
#
# Requisitos:
#   - gh CLI autenticado (gh auth login)
#   - Permisos de admin o maintain en el repo
# ==============================================================================

set -euo pipefail

# ---- Config ------------------------------------------------------------------

REPO="${REPO:-AlebasFluxAI/aomenertec}"
DELETE_DEFAULTS=false

for arg in "$@"; do
    case "$arg" in
        --delete-defaults) DELETE_DEFAULTS=true ;;
        --help|-h)
            grep '^#' "$0" | sed 's/^# \?//'
            exit 0
            ;;
    esac
done

# ---- Colors (para output) ----------------------------------------------------

GREEN='\033[0;32m'
YELLOW='\033[1;33m'
RED='\033[0;31m'
BLUE='\033[0;34m'
NC='\033[0m'

log()   { echo -e "${BLUE}[INFO]${NC} $*"; }
ok()    { echo -e "${GREEN}[OK]${NC}   $*"; }
warn()  { echo -e "${YELLOW}[WARN]${NC} $*"; }
error() { echo -e "${RED}[ERR]${NC}  $*" >&2; }

# ---- Pre-flight checks -------------------------------------------------------

if ! command -v gh &>/dev/null; then
    error "gh CLI no está instalado. Instalalo desde https://cli.github.com/"
    exit 1
fi

if ! gh auth status &>/dev/null; then
    error "gh CLI no está autenticado. Corré: gh auth login"
    exit 1
fi

log "Repo destino: ${REPO}"
log "Usuario gh:   $(gh api user --jq '.login')"
echo ""

# ---- Helpers -----------------------------------------------------------------

# upsert_label <name> <color-hex-sin-#> <descripcion>
upsert_label() {
    local name="$1"
    local color="$2"
    local desc="$3"

    # --force hace upsert: crea si no existe, actualiza color/desc si ya existe.
    # Es idempotente y seguro contra race conditions del cache de gh.
    if gh label create "$name" --repo "$REPO" --color "$color" --description "$desc" --force >/dev/null 2>&1; then
        ok "Upsert OK:   $name"
    else
        error "Falló:       $name"
        return 1
    fi
}

delete_label_if_exists() {
    local name="$1"
    # gh label delete devuelve error si no existe — lo ignoramos silenciosamente.
    # No dependemos de grep previo para evitar race conditions con set -e.
    if gh label delete "$name" --repo "$REPO" --yes >/dev/null 2>&1; then
        warn "Borrado:     $name"
    else
        log "No existía:  $name (skip)"
    fi
}

# ---- Labels por TIPO ---------------------------------------------------------
# Paleta: rojos/azules/verdes según acción

log "=== Labels por TIPO ==="
upsert_label "type: bug"         "d73a4a" "Algo no está funcionando como debería"
upsert_label "type: feature"     "0e8a16" "Nueva funcionalidad o mejora"
upsert_label "type: refactor"    "fbca04" "Refactor sin cambio de comportamiento"
upsert_label "type: docs"        "0075ca" "Cambios en documentación"
upsert_label "type: chore"       "c5def5" "Tareas de mantenimiento, build, CI, deps"
upsert_label "type: test"        "bfdadc" "Agregar o corregir tests"
upsert_label "type: security"    "b60205" "Vulnerabilidad o mejora de seguridad"
upsert_label "type: performance" "ee9944" "Mejora de performance"
echo ""

# ---- Labels por DOMINIO ------------------------------------------------------
# Paleta: morados/violetas — matchea arquitectura V1 del proyecto

log "=== Labels por DOMINIO ==="
upsert_label "domain: admin"             "5319e7" "Dominio Admin / Super Admin"
upsert_label "domain: client"            "6f42c1" "Dominio Cliente final"
upsert_label "domain: network-operator"  "8a63d2" "Dominio Operador de Red"
upsert_label "domain: technician"        "a371f7" "Dominio Técnico"
upsert_label "domain: mqtt"              "4527a0" "MQTT / IoT / MqttInput"
upsert_label "domain: event-log"         "7e57c2" "EventLog / auditoría"
upsert_label "domain: config-client"     "9575cd" "ConfigurationClient"
upsert_label "domain: auth"              "512da8" "Autenticación (JWT / Jetstream)"
upsert_label "domain: billing"           "673ab7" "Facturación / invoicing"
upsert_label "domain: broadcasting"      "7b1fa2" "Echo Server / WebSockets / tiempo real"
upsert_label "domain: scheduled-jobs"    "9c27b0" "Cron / jobs programados"
upsert_label "domain: infra"             "311b92" "Docker / Supervisor / infraestructura"
echo ""

# ---- Labels por PRIORIDAD ----------------------------------------------------
# Paleta: rojos degradados

log "=== Labels por PRIORIDAD ==="
upsert_label "priority: critical" "b60205" "Crítica — bloquea operación o producción"
upsert_label "priority: high"     "d93f0b" "Alta — resolver pronto"
upsert_label "priority: medium"   "fbca04" "Media — planificar en siguiente iteración"
upsert_label "priority: low"      "0e8a16" "Baja — nice to have"
echo ""

# ---- Labels por ESTADO -------------------------------------------------------
# Paleta: grises/neutros

log "=== Labels por ESTADO ==="
upsert_label "status: needs-triage"  "ededed" "Sin revisar / pendiente de clasificar"
upsert_label "status: blocked"       "b60205" "Bloqueado por dependencia externa"
upsert_label "status: in-progress"   "1d76db" "En desarrollo activo"
upsert_label "status: needs-review"  "fbca04" "Listo para review"
upsert_label "status: needs-info"    "d4c5f9" "Falta información del reporter"
upsert_label "status: wont-fix"      "7f8c8d" "No se va a resolver (cerrado intencionalmente)"
upsert_label "status: duplicate"     "cccccc" "Duplicado de otro issue"
echo ""

# ---- Labels ESPECIALES -------------------------------------------------------

log "=== Labels ESPECIALES ==="
upsert_label "good first issue"   "7057ff" "Ideal para nuevos contribuidores"
upsert_label "help wanted"        "008672" "Se busca ayuda externa / colaboración"
upsert_label "breaking change"    "b60205" "Cambio que rompe compatibilidad"
upsert_label "production-only"    "e11d48" "Solo se reproduce en producción"
upsert_label "needs-migration"    "f9d0c4" "Requiere migración de base de datos"
upsert_label "needs-deployment"   "fef2c0" "Requiere pasos especiales de deployment"
echo ""

# ---- Limpieza de labels default de GitHub (opcional) ------------------------

if [ "$DELETE_DEFAULTS" = true ]; then
    log "=== Borrando labels default de GitHub ==="
    delete_label_if_exists "bug"
    delete_label_if_exists "documentation"
    delete_label_if_exists "duplicate"
    delete_label_if_exists "enhancement"
    delete_label_if_exists "invalid"
    delete_label_if_exists "question"
    delete_label_if_exists "wontfix"
    echo ""
fi

# ---- Resumen -----------------------------------------------------------------

TOTAL=$(gh label list --repo "$REPO" --limit 200 --json name --jq '. | length')
ok "Listo. Total de labels en ${REPO}: ${TOTAL}"
log "Ver todos: gh label list --repo ${REPO}"
