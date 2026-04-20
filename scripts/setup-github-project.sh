#!/usr/bin/env bash
# ==============================================================================
# setup-github-project.sh
# ------------------------------------------------------------------------------
# Crea/actualiza el GitHub Project v2 a nivel organización para aomenertec.
# Configura un Kanban puro con 4 columnas y vincula el repo.
#
# Uso:
#   ./scripts/setup-github-project.sh
#
# Requisitos:
#   - gh CLI autenticado con scopes: repo, read:org, project
#     Si faltan: gh auth refresh -s project,read:project
#   - Permisos de admin en la organización AlebasFluxAI
#
# Idempotente: si el Project ya existe, lo reutiliza y solo ajusta columnas.
# ==============================================================================

set -euo pipefail

# ---- Config ------------------------------------------------------------------

ORG="AlebasFluxAI"
REPO="aomenertec"
PROJECT_TITLE="Aomenertec — Roadmap"

# Columnas del Kanban puro (orden importa)
COLUMNS=(
    "📥 Backlog"
    "🏗️ In Progress"
    "👀 In Review"
    "✅ Done"
)

# ---- Colors ------------------------------------------------------------------

GREEN='\033[0;32m'
YELLOW='\033[1;33m'
RED='\033[0;31m'
BLUE='\033[0;34m'
NC='\033[0m'

log()   { echo -e "${BLUE}[INFO]${NC} $*"; }
ok()    { echo -e "${GREEN}[OK]${NC}   $*"; }
warn()  { echo -e "${YELLOW}[WARN]${NC} $*"; }
error() { echo -e "${RED}[ERR]${NC}  $*" >&2; }

# ---- Pre-flight --------------------------------------------------------------

if ! command -v gh &>/dev/null; then
    error "gh CLI no está instalado"
    exit 1
fi

if ! gh auth status &>/dev/null; then
    error "gh CLI no autenticado. Corré: gh auth login"
    exit 1
fi

SCOPES=$(gh auth status 2>&1 | grep -i "scope" || true)
if ! echo "$SCOPES" | grep -q "project"; then
    error "Falta el scope 'project'. Corré: gh auth refresh -s project,read:project"
    exit 1
fi

log "Organización: ${ORG}"
log "Repo:         ${REPO}"
log "Project:      ${PROJECT_TITLE}"
echo ""

# ---- Step 1: Obtener IDs de org y repo ---------------------------------------

log "Obteniendo IDs de org y repo..."
IDS=$(gh api graphql -f query="
    query {
        organization(login: \"${ORG}\") { id }
        repository(owner: \"${ORG}\", name: \"${REPO}\") { id }
    }
")

ORG_ID=$(echo "$IDS" | jq -r '.data.organization.id')
REPO_ID=$(echo "$IDS" | jq -r '.data.repository.id')

if [ -z "$ORG_ID" ] || [ "$ORG_ID" = "null" ]; then
    error "No se pudo obtener el ID de la org"
    exit 1
fi

ok "Org ID:  $ORG_ID"
ok "Repo ID: $REPO_ID"
echo ""

# ---- Step 2: Buscar si el Project ya existe ----------------------------------

log "Buscando si el Project ya existe..."
EXISTING=$(gh api graphql -f query="
    query {
        organization(login: \"${ORG}\") {
            projectsV2(first: 50) {
                nodes { id title number }
            }
        }
    }
")

PROJECT_ID=$(echo "$EXISTING" | jq -r --arg t "$PROJECT_TITLE" '.data.organization.projectsV2.nodes[] | select(.title == $t) | .id' | head -1)
PROJECT_NUMBER=$(echo "$EXISTING" | jq -r --arg t "$PROJECT_TITLE" '.data.organization.projectsV2.nodes[] | select(.title == $t) | .number' | head -1)

if [ -n "$PROJECT_ID" ] && [ "$PROJECT_ID" != "null" ]; then
    warn "Project '${PROJECT_TITLE}' ya existe (ID: $PROJECT_ID, #$PROJECT_NUMBER) — reutilizando"
else
    log "Creando Project nuevo..."
    CREATE_RESULT=$(gh api graphql -f query="
        mutation {
            createProjectV2(input: {
                ownerId: \"${ORG_ID}\",
                title: \"${PROJECT_TITLE}\"
            }) {
                projectV2 { id number url }
            }
        }
    ")

    PROJECT_ID=$(echo "$CREATE_RESULT" | jq -r '.data.createProjectV2.projectV2.id')
    PROJECT_NUMBER=$(echo "$CREATE_RESULT" | jq -r '.data.createProjectV2.projectV2.number')
    PROJECT_URL=$(echo "$CREATE_RESULT" | jq -r '.data.createProjectV2.projectV2.url')

    if [ -z "$PROJECT_ID" ] || [ "$PROJECT_ID" = "null" ]; then
        error "Falló la creación del Project"
        echo "$CREATE_RESULT" | jq
        exit 1
    fi

    ok "Project creado: $PROJECT_URL"
fi

echo ""

# ---- Step 3: Obtener el campo "Status" y sus opciones actuales --------------

log "Obteniendo configuración del campo Status..."
STATUS_FIELD=$(gh api graphql -f query="
    query {
        node(id: \"${PROJECT_ID}\") {
            ... on ProjectV2 {
                fields(first: 50) {
                    nodes {
                        ... on ProjectV2SingleSelectField {
                            id
                            name
                            options { id name }
                        }
                    }
                }
            }
        }
    }
")

STATUS_FIELD_ID=$(echo "$STATUS_FIELD" | jq -r '.data.node.fields.nodes[] | select(.name == "Status") | .id')
CURRENT_OPTIONS=$(echo "$STATUS_FIELD" | jq -r '.data.node.fields.nodes[] | select(.name == "Status") | .options[].name')

ok "Status field ID: $STATUS_FIELD_ID"
log "Opciones actuales del Status:"
echo "$CURRENT_OPTIONS" | sed 's/^/  - /'
echo ""

# ---- Step 4: Configurar las columnas (Status options) ------------------------
# GitHub crea por default: Todo, In Progress, Done. Las reemplazamos con las nuestras.

log "Configurando columnas del Kanban..."

# NOTA: `gh api graphql -f` serializa arrays complejos como strings, lo que rompe
# la mutation updateProjectV2Field. Usamos curl directo con el token de gh para
# enviar un payload JSON real (array de objetos, no string escapado).

# Armar el array de options en JSON real
OPTIONS_ARRAY=$(jq -n --argjson cols "$(printf '%s\n' "${COLUMNS[@]}" | jq -R . | jq -s .)" '
  [
    {name: $cols[0], color: "GRAY",   description: ""},
    {name: $cols[1], color: "YELLOW", description: ""},
    {name: $cols[2], color: "BLUE",   description: ""},
    {name: $cols[3], color: "GREEN",  description: ""}
  ]
')

GRAPHQL_QUERY='mutation UpdateStatusField($fieldId: ID!, $options: [ProjectV2SingleSelectFieldOptionInput!]!) {
  updateProjectV2Field(input: {
    fieldId: $fieldId,
    singleSelectOptions: $options
  }) {
    projectV2Field {
      ... on ProjectV2SingleSelectField {
        id
        options { id name color }
      }
    }
  }
}'

PAYLOAD=$(jq -n \
  --arg query "$GRAPHQL_QUERY" \
  --arg fieldId "$STATUS_FIELD_ID" \
  --argjson options "$OPTIONS_ARRAY" \
  '{query: $query, variables: {fieldId: $fieldId, options: $options}}')

UPDATE_RESULT=$(curl -s -X POST https://api.github.com/graphql \
  -H "Authorization: bearer $(gh auth token)" \
  -H "Content-Type: application/json" \
  -d "$PAYLOAD")

if echo "$UPDATE_RESULT" | jq -e '.data.updateProjectV2Field.projectV2Field' &>/dev/null; then
    ok "Columnas configuradas:"
    echo "$UPDATE_RESULT" | jq -r '.data.updateProjectV2Field.projectV2Field.options[] | "  ✓ \(.name) [\(.color)]"'
else
    error "Falló la actualización de columnas"
    echo "$UPDATE_RESULT" | jq
    exit 1
fi
echo ""

# ---- Step 5: Vincular el repo al Project -------------------------------------

log "Vinculando repo ${REPO} al Project..."
LINK_RESULT=$(gh api graphql -f query="
    mutation {
        linkProjectV2ToRepository(input: {
            projectId: \"${PROJECT_ID}\",
            repositoryId: \"${REPO_ID}\"
        }) {
            repository { name }
        }
    }
" 2>&1) || true

if echo "$LINK_RESULT" | jq -e '.data.linkProjectV2ToRepository' &>/dev/null; then
    ok "Repo vinculado: ${ORG}/${REPO}"
elif echo "$LINK_RESULT" | grep -q "already"; then
    ok "Repo ya estaba vinculado (skip)"
else
    warn "Link response: $LINK_RESULT"
fi
echo ""

# ---- Resumen -----------------------------------------------------------------

PROJECT_URL="https://github.com/orgs/${ORG}/projects/${PROJECT_NUMBER}"
ok "Project listo en: $PROJECT_URL"
log "Próximos pasos sugeridos:"
log "  1. Abrir el Project en el browser y verificar columnas"
log "  2. Configurar workflows (auto-add issues) desde Settings → Workflows"
log "  3. Agregar issues con: gh project item-add $PROJECT_NUMBER --owner $ORG --url <issue-url>"
