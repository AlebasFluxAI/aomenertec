<?php

namespace App\Http\Services\V1\Admin\User;

use App\Http\Resources\V1\Menu;
use App\Http\Services\Singleton;
use App\Models\Traits\NetworkOperatorPriceTrait;
use App\Models\V1\Admin;
use App\Models\V1\Client;
use App\Models\V1\Consumer;
use App\Models\V1\Equipment;
use App\Models\V1\Invoice;
use App\Models\V1\MicrocontrollerData;
use App\Models\V1\NetworkOperator;
use App\Models\V1\Pqr;
use App\Models\V1\SuperAdmin;
use App\Models\V1\Supervisor;
use App\Models\V1\Support;
use App\Models\V1\Technician;
use App\Models\V1\User;
use App\Models\V1\WorkOrder;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class ProfileUserService extends Singleton
{
    use NetworkOperatorPriceTrait;

    public function mount(Component $component)
    {
        $component->model = $this->getModelByUser();
        if (Auth::user()->hasRole(User::TYPE_SUPER_ADMIN)) {
            $component->admins = Admin::get();
            $component->network_operators = NetworkOperator::get();
            $component->equipment = Equipment::get();
        }
        $component->supervisors = [];
        $this->buildDashboard($component);
    }

    /**
     * Construye el dashboard de entrada segun el rol del usuario.
     * El resultado se deposita en $component->dashboard y es renderizado
     * por el partial `partials.v1.home.flux-shell` de forma uniforme
     * para los 7 roles del sistema.
     */
    private function buildDashboard(Component $component): void
    {
        $user  = Auth::user();
        $model = $component->model;

        if ($user->hasRole(User::TYPE_SUPER_ADMIN)) {
            $component->dashboard = $this->buildSuperAdminDashboard($model);
            return;
        }
        if ($user->hasRole(User::TYPE_ADMIN)) {
            $component->dashboard = $this->buildAdminDashboard($model);
            return;
        }
        if ($user->hasRole(User::TYPE_NETWORK_OPERATOR)) {
            $component->dashboard = $this->buildNetworkOperatorDashboard($model);
            return;
        }
        if ($user->hasRole(User::TYPE_TECHNICIAN)) {
            $component->dashboard = $this->buildTechnicianDashboard($model);
            return;
        }
        if ($user->hasRole(User::TYPE_SUPERVISOR)) {
            $component->dashboard = $this->buildSupervisorDashboard($model);
            return;
        }
        if ($user->hasRole(User::TYPE_SELLER)) {
            $component->dashboard = $this->buildSellerDashboard($model);
            return;
        }
        if ($user->hasRole(User::TYPE_SUPPORT)) {
            $component->dashboard = $this->buildSupportDashboard($model);
            return;
        }
    }

    private function fmt($n): string
    {
        return number_format((int) $n, 0, ',', '.');
    }

    private function pqrBadgeClass(?string $status): string
    {
        return match($status) {
            Pqr::STATUS_RESOLVED, Pqr::STATUS_CLOSED => 'flux-badge--ok',
            Pqr::STATUS_PROCESSING                   => 'flux-badge--warn',
            Pqr::STATUS_CREATED                      => 'flux-badge--info',
            default                                  => 'flux-badge--muted',
        };
    }

    private function invoiceBadgeClass(?string $status): string
    {
        return match($status) {
            Invoice::PAYMENT_STATUS_APPROVED                                                        => 'flux-badge--ok',
            Invoice::PAYMENT_STATUS_PENDING                                                         => 'flux-badge--warn',
            Invoice::PAYMENT_STATUS_VOIDED, Invoice::PAYMENT_STATUS_DECLINED, Invoice::PAYMENT_STATUS_ERROR => 'flux-badge--danger',
            default                                                                                 => 'flux-badge--muted',
        };
    }

    private function mapClients($clients): array
    {
        return collect($clients)->map(fn($c) => [
            "title"       => trim(($c->name ?? '') . ' ' . ($c->last_name ?? '')) ?: 'Cliente #' . $c->id,
            "sub"         => 'Creado ' . (optional($c->created_at)->diffForHumans() ?? '—'),
            "badge"       => '#' . $c->id,
            "badge_class" => 'flux-badge--info',
        ])->values()->all();
    }

    private function mapPqrs($pqrs): array
    {
        return collect($pqrs)->map(fn($p) => [
            "title"       => 'PQR #' . $p->id,
            "sub"         => (optional($p->created_at)->diffForHumans() ?? '—')
                             . ' · ' . str_replace('type_', '', $p->type ?? '—'),
            "badge"       => ucfirst($p->status ?? '—'),
            "badge_class" => $this->pqrBadgeClass($p->status),
        ])->values()->all();
    }

    private function mapInvoices($invoices): array
    {
        return collect($invoices)->map(fn($i) => [
            "title"       => $i->code ?? 'Factura #' . $i->id,
            "sub"         => (optional($i->created_at)->diffForHumans() ?? '—')
                             . ' · $' . number_format((float) ($i->total ?? 0), 0, ',', '.'),
            "badge"       => ucfirst($i->payment_status ?? '—'),
            "badge_class" => $this->invoiceBadgeClass($i->payment_status),
        ])->values()->all();
    }

    private function safeRoute(string $name): ?string
    {
        return \Route::has($name) ? route($name) : null;
    }

    private function buildSuperAdminDashboard($model): array
    {
        $recentClients  = Client::orderByDesc("id")->limit(5)->get(["id", "name", "last_name", "created_at"]);
        $recentPqrs     = Pqr::orderByDesc("id")->limit(5)->get(["id", "status", "type", "created_at"]);
        $recentInvoices = Invoice::orderByDesc("id")->limit(5)->get(["id", "code", "total", "payment_status", "created_at"]);

        $kpis = [
            ["accent" => "clients",   "icon" => "fas fa-users",               "label" => "Clientes activos",
             "value" => $this->fmt(Client::where("status", Client::CLIENT_STATUS_ENABLED)->count()),
             "hint"  => "de " . $this->fmt(Client::count()) . " totales"],
            ["accent" => "equipment", "icon" => "fas fa-microchip",           "label" => "Equipos en campo",
             "value" => $this->fmt(Equipment::count()),
             "hint"  => "medidores registrados"],
            ["accent" => "admins",    "icon" => "fas fa-user-tie",            "label" => "Administradores",
             "value" => $this->fmt(Admin::where("enabled", true)->count()),
             "hint"  => $this->fmt(NetworkOperator::count()) . " operadores de red"],
            ["accent" => "pqrs",      "icon" => "fas fa-clipboard-list",      "label" => "PQRs abiertos",
             "value" => $this->fmt(Pqr::whereNotIn("status", [Pqr::STATUS_RESOLVED, Pqr::STATUS_CLOSED])->count()),
             "hint"  => "en proceso o creados"],
            ["accent" => "invoices",  "icon" => "fas fa-file-invoice-dollar", "label" => "Facturas pendientes",
             "value" => $this->fmt(Invoice::where("payment_status", Invoice::PAYMENT_STATUS_PENDING)->count()),
             "hint"  => "esperando pago"],
            ["accent" => "savings",   "icon" => "fas fa-clipboard-check",     "label" => "Órdenes de servicio",
             "value" => $this->fmt(WorkOrder::count()),
             "hint"  => "histórico total"],
        ];

        $quick = array_values(array_filter([
            $this->safeRoute("administrar.v1.clientes.activos")           ? ["icon_style" => "primary", "icon" => "fas fa-users",               "title" => "Clientes",          "subtitle" => "Gestionar clientes activos", "url" => $this->safeRoute("administrar.v1.clientes.activos")] : null,
            $this->safeRoute("administrar.v1.equipos.listado")            ? ["icon_style" => "accent",  "icon" => "fas fa-microchip",           "title" => "Equipos",           "subtitle" => "Inventario de medidores",    "url" => $this->safeRoute("administrar.v1.equipos.listado")] : null,
            $this->safeRoute("administrar.v1.usuarios.admin.listado")     ? ["icon_style" => "purple",  "icon" => "fas fa-user-tie",            "title" => "Administradores",   "subtitle" => "Usuarios y permisos",        "url" => $this->safeRoute("administrar.v1.usuarios.admin.listado")] : null,
            $this->safeRoute("administrar.v1.usuarios.operadores.listado")? ["icon_style" => "primary", "icon" => "fas fa-network-wired",       "title" => "Operadores de red", "subtitle" => "Gestionar operadores",       "url" => $this->safeRoute("administrar.v1.usuarios.operadores.listado")] : null,
            $this->safeRoute("administrar.v1.facturacion.facturas.listado")? ["icon_style" => "danger", "icon" => "fas fa-file-invoice-dollar", "title" => "Facturación",       "subtitle" => "Facturas e ítems",            "url" => $this->safeRoute("administrar.v1.facturacion.facturas.listado")] : null,
            ["icon_style" => "warn", "icon" => "far fa-bell", "title" => "Notificaciones", "subtitle" => "Centro de avisos", "url" => route("administrar.v1.notificaciones")],
        ]));

        return [
            "welcome_title"     => "Bienvenido, " . ($model->name ?? ''),
            "welcome_subtitle"  => "Panel de control FluxAI — resumen operacional en tiempo real.",
            "welcome_role_chip" => "Super administrador",
            "kpis"              => $kpis,
            "quick_actions"     => $quick,
            "activity_panels"   => [
                ["title" => "Últimos clientes",    "icon" => "fas fa-user-plus",    "rows" => $this->mapClients($recentClients),   "empty_message" => "Sin clientes registrados aún."],
                ["title" => "PQRs recientes",      "icon" => "fas fa-headset",      "rows" => $this->mapPqrs($recentPqrs),         "empty_message" => "Sin PQRs registrados aún."],
                ["title" => "Facturas recientes",  "icon" => "fas fa-file-invoice", "rows" => $this->mapInvoices($recentInvoices), "empty_message" => "Sin facturas registradas aún."],
            ],
        ];
    }

    private function buildAdminDashboard($model): array
    {
        $clients        = $model->getClientsAttribute();
        $clientIds      = collect($clients)->pluck("id");
        $recentClients  = Client::whereIn("id", $clientIds)->orderByDesc("id")->limit(5)->get(["id", "name", "last_name", "created_at"]);
        $recentInvoices = Invoice::whereAdminId($model->id)->orderByDesc("id")->limit(5)->get(["id", "code", "total", "payment_status", "created_at"]);

        $kpis = [
            ["accent" => "clients",   "icon" => "fas fa-users",               "label" => "Mis clientes",
             "value" => $this->fmt(count($clients)),                           "hint" => "activos en mis operadores"],
            ["accent" => "admins",    "icon" => "fas fa-network-wired",       "label" => "Operadores de red",
             "value" => $this->fmt($model->networkOperators()->count()),       "hint" => "bajo mi administración"],
            ["accent" => "equipment", "icon" => "fas fa-microchip",           "label" => "Equipos asignados",
             "value" => $this->fmt($model->equipments()->count()),             "hint" => "en mi red"],
            ["accent" => "invoices",  "icon" => "fas fa-file-invoice-dollar", "label" => "Facturas pendientes",
             "value" => $this->fmt(Invoice::whereAdminId($model->id)->where("payment_status", Invoice::PAYMENT_STATUS_PENDING)->count()),
             "hint"  => "esperando pago"],
        ];

        $quick = array_values(array_filter([
            $this->safeRoute("administrar.v1.clientes.activos")            ? ["icon_style" => "primary", "icon" => "fas fa-users",               "title" => "Mis clientes",       "subtitle" => "Ver todos mis clientes",   "url" => $this->safeRoute("administrar.v1.clientes.activos")] : null,
            $this->safeRoute("administrar.v1.equipos.listado")             ? ["icon_style" => "accent",  "icon" => "fas fa-microchip",           "title" => "Equipos",            "subtitle" => "Inventario de mi red",      "url" => $this->safeRoute("administrar.v1.equipos.listado")] : null,
            $this->safeRoute("administrar.v1.usuarios.operadores.listado") ? ["icon_style" => "purple",  "icon" => "fas fa-network-wired",       "title" => "Operadores de red",  "subtitle" => "Gestionar operadores",      "url" => $this->safeRoute("administrar.v1.usuarios.operadores.listado")] : null,
            $this->safeRoute("administrar.v1.facturacion.facturas.listado")? ["icon_style" => "danger",  "icon" => "fas fa-file-invoice-dollar", "title" => "Facturación",         "subtitle" => "Facturas e ítems",          "url" => $this->safeRoute("administrar.v1.facturacion.facturas.listado")] : null,
            ["icon_style" => "warn", "icon" => "far fa-bell", "title" => "Notificaciones", "subtitle" => "Centro de avisos", "url" => route("administrar.v1.notificaciones")],
        ]));

        return [
            "welcome_title"     => "Hola, " . ($model->name ?? ''),
            "welcome_subtitle"  => "Resumen de tu red, clientes y facturación.",
            "welcome_role_chip" => "Administrador",
            "kpis"              => $kpis,
            "quick_actions"     => $quick,
            "activity_panels"   => [
                ["title" => "Mis clientes recientes",    "icon" => "fas fa-user-plus",    "rows" => $this->mapClients($recentClients),   "empty_message" => "Aún no tienes clientes."],
                ["title" => "Mis facturas recientes",    "icon" => "fas fa-file-invoice", "rows" => $this->mapInvoices($recentInvoices), "empty_message" => "Aún no has emitido facturas."],
            ],
        ];
    }

    private function buildNetworkOperatorDashboard($model): array
    {
        $recentClients = $model->clients()->orderByDesc("id")->limit(5)->get(["id", "name", "last_name", "created_at"]);
        $recentPqrs    = $model->pqrs()->orderByDesc("id")->limit(5)->get(["id", "status", "type", "created_at"]);

        $kpis = [
            ["accent" => "clients",   "icon" => "fas fa-users",         "label" => "Mis clientes",
             "value" => $this->fmt($model->clients()->count()),          "hint" => "en mi red"],
            ["accent" => "equipment", "icon" => "fas fa-microchip",     "label" => "Mis equipos",
             "value" => $this->fmt($model->equipments()->count()),       "hint" => "medidores asignados"],
            ["accent" => "admins",    "icon" => "fas fa-user-hard-hat", "label" => "Mis técnicos",
             "value" => $this->fmt($model->technicians()->count()),      "hint" => "activos en campo"],
            ["accent" => "pqrs",      "icon" => "fas fa-headset",       "label" => "PQRs abiertos",
             "value" => $this->fmt($model->pqrs()->whereNotIn("status", [Pqr::STATUS_RESOLVED, Pqr::STATUS_CLOSED])->count()),
             "hint"  => "en proceso"],
        ];

        $quick = array_values(array_filter([
            $this->safeRoute("administrar.v1.clientes.activos") ? ["icon_style" => "primary", "icon" => "fas fa-users",         "title" => "Mis clientes",  "subtitle" => "Ver y gestionar",        "url" => $this->safeRoute("administrar.v1.clientes.activos")] : null,
            $this->safeRoute("administrar.v1.equipos.listado")  ? ["icon_style" => "accent",  "icon" => "fas fa-microchip",     "title" => "Mis equipos",   "subtitle" => "Inventario de medidores", "url" => $this->safeRoute("administrar.v1.equipos.listado")] : null,
            ["icon_style" => "warn", "icon" => "far fa-bell", "title" => "Notificaciones", "subtitle" => "Centro de avisos", "url" => route("administrar.v1.notificaciones")],
        ]));

        return [
            "welcome_title"     => "Hola, " . ($model->name ?? ''),
            "welcome_subtitle"  => "Panel de tu red: clientes, equipos y equipo técnico.",
            "welcome_role_chip" => "Operador de red",
            "kpis"              => $kpis,
            "quick_actions"     => $quick,
            "activity_panels"   => [
                ["title" => "Clientes recientes", "icon" => "fas fa-user-plus", "rows" => $this->mapClients($recentClients), "empty_message" => "Aún no tienes clientes."],
                ["title" => "PQRs recientes",     "icon" => "fas fa-headset",   "rows" => $this->mapPqrs($recentPqrs),       "empty_message" => "Sin PQRs en tu red."],
            ],
        ];
    }

    private function buildTechnicianDashboard($model): array
    {
        // Technician::clients() es belongsToMany(Client::class, 'client_technicians') y ambos
        // modelos tienen OrderIdScope con orderBy('id') sin calificar tabla. El JOIN con
        // client_technicians hace que "id" sea ambigua en PostgreSQL, por eso calificamos
        // explícitamente con "clients.id" en order y select.
        $recentClients = $model->clients()->orderByDesc("clients.id")->limit(5)->get(["clients.id", "clients.name", "clients.last_name", "clients.created_at"]);
        $equipments    = method_exists($model, 'allEquipments') ? $model->allEquipments() : $model->equipments()->get();

        $kpis = [
            ["accent" => "clients",   "icon" => "fas fa-users",           "label" => "Mis clientes",
             "value" => $this->fmt($model->clients()->count()),            "hint" => "asignados a mí"],
            ["accent" => "equipment", "icon" => "fas fa-microchip",       "label" => "Mis equipos",
             "value" => $this->fmt(is_countable($equipments) ? count($equipments) : 0), "hint" => "bajo mi responsabilidad"],
            ["accent" => "pqrs",      "icon" => "fas fa-tools",           "label" => "En reparación",
             "value" => $this->fmt(Equipment::where("status", Equipment::STATUS_REPAIR)->count()),
             "hint"  => "equipos en proceso"],
        ];

        $quick = array_values(array_filter([
            $this->safeRoute("administrar.v1.clientes.activos") ? ["icon_style" => "primary", "icon" => "fas fa-users",     "title" => "Mis clientes", "subtitle" => "Ver y gestionar",     "url" => $this->safeRoute("administrar.v1.clientes.activos")] : null,
            $this->safeRoute("administrar.v1.equipos.listado")  ? ["icon_style" => "accent",  "icon" => "fas fa-microchip", "title" => "Mis equipos",  "subtitle" => "Equipos de mi zona",  "url" => $this->safeRoute("administrar.v1.equipos.listado")] : null,
            ["icon_style" => "warn", "icon" => "far fa-bell", "title" => "Notificaciones", "subtitle" => "Centro de avisos", "url" => route("administrar.v1.notificaciones")],
        ]));

        return [
            "welcome_title"     => "Hola, " . ($model->name ?? ''),
            "welcome_subtitle"  => "Clientes, equipos y órdenes asignadas a ti.",
            "welcome_role_chip" => "Técnico",
            "kpis"              => $kpis,
            "quick_actions"     => $quick,
            "activity_panels"   => [
                ["title" => "Clientes recientes", "icon" => "fas fa-user-plus", "rows" => $this->mapClients($recentClients), "empty_message" => "Sin clientes asignados."],
            ],
        ];
    }

    private function buildSupervisorDashboard($model): array
    {
        // Supervisor::clients() es belongsToMany(Client::class, 'client_supervisors') y ambos
        // modelos tienen OrderIdScope con orderBy('id') sin calificar tabla. El JOIN con
        // client_supervisors hace que "id" sea ambigua en PostgreSQL, por eso calificamos
        // explícitamente con "clients.id" en order y select.
        $recentClients = $model->clients()->orderByDesc("clients.id")->limit(5)->get(["clients.id", "clients.name", "clients.last_name", "clients.created_at"]);

        $kpis = [
            ["accent" => "clients", "icon" => "fas fa-users", "label" => "Clientes supervisados",
             "value" => $this->fmt($model->clients()->count()),
             "hint"  => "bajo mi seguimiento"],
        ];

        $quick = array_values(array_filter([
            $this->safeRoute("administrar.v1.clientes.activos") ? ["icon_style" => "primary", "icon" => "fas fa-users", "title" => "Mis clientes", "subtitle" => "Ver supervisados", "url" => $this->safeRoute("administrar.v1.clientes.activos")] : null,
            ["icon_style" => "warn", "icon" => "far fa-bell", "title" => "Notificaciones", "subtitle" => "Centro de avisos", "url" => route("administrar.v1.notificaciones")],
        ]));

        return [
            "welcome_title"     => "Hola, " . ($model->name ?? ''),
            "welcome_subtitle"  => "Panel de supervisión de tu cartera de clientes.",
            "welcome_role_chip" => "Supervisor",
            "kpis"              => $kpis,
            "quick_actions"     => $quick,
            "activity_panels"   => [
                ["title" => "Clientes recientes", "icon" => "fas fa-user-plus", "rows" => $this->mapClients($recentClients), "empty_message" => "Sin clientes supervisados."],
            ],
        ];
    }

    private function buildSellerDashboard($model): array
    {
        $kpis = [
            ["accent" => "clients", "icon" => "fas fa-user-plus", "label" => "Clientes gestionados",
             "value" => $this->fmt($model->clientSellers()->count()),
             "hint"  => "registrados por mí"],
            ["accent" => "savings", "icon" => "fas fa-coins", "label" => "Recargas realizadas",
             "value" => $this->fmt($model->clientRecharges()->count()),
             "hint"  => "histórico"],
        ];

        $quick = array_values(array_filter([
            $this->safeRoute("administrar.v1.clientes.activos") ? ["icon_style" => "primary", "icon" => "fas fa-users", "title" => "Clientes", "subtitle" => "Mis clientes y prospectos", "url" => $this->safeRoute("administrar.v1.clientes.activos")] : null,
            ["icon_style" => "warn", "icon" => "far fa-bell", "title" => "Notificaciones", "subtitle" => "Centro de avisos", "url" => route("administrar.v1.notificaciones")],
        ]));

        return [
            "welcome_title"     => "Hola, " . ($model->name ?? ''),
            "welcome_subtitle"  => "Panel de ventas: clientes gestionados y recargas.",
            "welcome_role_chip" => "Vendedor",
            "kpis"              => $kpis,
            "quick_actions"     => $quick,
            "activity_panels"   => [],
        ];
    }

    private function buildSupportDashboard($model): array
    {
        $recentPqrs = $model->pqrs()->orderByDesc("id")->limit(5)->get(["id", "status", "type", "created_at"]);

        $kpis = [
            ["accent" => "pqrs",    "icon" => "fas fa-headset",        "label" => "PQRs asignados",
             "value" => $this->fmt($model->pqrs()->count()),            "hint" => "histórico de tickets"],
            ["accent" => "savings", "icon" => "fas fa-check-circle",   "label" => "PQRs resueltos",
             "value" => $this->fmt($model->pqrs()->whereIn("status", [Pqr::STATUS_RESOLVED, Pqr::STATUS_CLOSED])->count()),
             "hint"  => "cerrados con éxito"],
        ];

        $quick = array_values(array_filter([
            ["icon_style" => "warn", "icon" => "far fa-bell", "title" => "Notificaciones", "subtitle" => "Centro de avisos", "url" => route("administrar.v1.notificaciones")],
        ]));

        return [
            "welcome_title"     => "Hola, " . ($model->name ?? ''),
            "welcome_subtitle"  => "Centro de soporte: tus tickets y seguimiento.",
            "welcome_role_chip" => "Soporte",
            "kpis"              => $kpis,
            "quick_actions"     => $quick,
            "activity_panels"   => [
                ["title" => "Mis PQRs recientes", "icon" => "fas fa-headset", "rows" => $this->mapPqrs($recentPqrs), "empty_message" => "No tienes tickets asignados."],
            ],
        ];
    }

    private function getModelByUser()
    {
        return Menu::getUserModel();
    }

    public function getViewName()
    {
        return Menu::getHome();
    }


    public function conditionalDeleteAdmin(Component $component, $modelId)
    {
        return NetworkOperator::whereAdminId($modelId)->exists();
    }

    public function deleteAdmin(Component $component, $modelId)
    {
        $admin = Admin::find($modelId);
        $admin->user->enabled = false;
        $admin->push();
        foreach ($admin->adminClientTypes()->get() as $type) {
            $type->delete();
        }
        foreach ($admin->priceAdmin()->get() as $type) {
            $type->delete();
        }
        foreach ($admin->adminEquipmentTypes()->get() as $type) {
            $type->delete();
        }
        foreach ($admin->equipments()->get() as $type) {
            $type->admin_id = "";
            $type->save();
        }
        if ($admin->configAdmin()->exists()) {
            $admin->configAdmin()->delete();
        }
        $component->emitTo('livewire-toast', 'show', ['type' => 'success', 'message' => "{$admin->name} eliminado"]);
        $admin->delete();
    }

    public function disableAdmin(Component $component, $modelId)
    {
        $admin = Admin::find($modelId);
        $admin->enabled = !$admin->enabled;
        $admin->user->enabled = !$admin->user->enabled;
        $admin->push();
        if (!$admin->enabled) {
            $component->emitTo('livewire-toast', 'show', ['type' => 'warning', 'message' => "Usuario desactivado"]);
        } else {
            $component->emitTo('livewire-toast', 'show', ['type' => 'warning', 'message' => "Usuario activado"]);
        }
    }

    public function getEnabledAdmin(Component $component, $modelId)
    {
        return !Admin::find($modelId)->enabled;
    }

    public function getEnabledAuxAdmin(Component $component, $modelId)
    {
        if (!Admin::find($modelId)->enabled) {
            return false;
        }
        return true;
    }

    public function conditionalRemoveEquipmentAdmin(Component $component, $id)
    {
        if (Equipment::find($id)->has_clients) {
            return Equipment::find($id)->has_clients;
        } else {
            return !Equipment::find($id)->has_admin;
        }
    }

    public function removeEquipmentAdmin(Component $component, $id)
    {
        $model = User::getUserModel();
        $equipment = Equipment::find($id);
        $equipment->has_technician = false;
        $equipment->technician_id = null;
        $equipment->has_network_operator = false;
        $equipment->network_operator_id = null;
        $equipment->has_admin = false;
        $equipment->admin_id = null;
        $equipment->save();
        $component->emitTo('livewire-toast', 'show', "Equipo {$id} removido exitosamente de {$model->name}");
    }

    public function conditionalDeleteNetworkOperator(Component $component, $modelId)
    {
        return Client::whereNetworkOperatorId($modelId)->exists();
    }

    public function deleteNetworkOperator(Component $component, $networkOperatorId)
    {
        $operator = NetworkOperator::find($networkOperatorId);

        $operator->push();
        foreach ($operator->equipments()->get() as $type) {
            $type->network_operator_id = null;
            $type->save();
        }
        $component->emitTo('livewire-toast', 'show', ['type' => 'success', 'message' => "{$operator->name} eliminado"]);
        $operator->delete();
    }

    public function disableNetworkOperator(Component $component, $modelId)
    {
        $operator = NetworkOperator::find($modelId);
        $operator->enabled = !$operator->enabled;
        $operator->user->enabled = !$operator->user->enabled;
        $operator->push();
        if (!$operator->enabled) {
            $component->emitTo('livewire-toast', 'show', ['type' => 'warning', 'message' => "Usuario desactivado"]);
        } else {
            $component->emitTo('livewire-toast', 'show', ['type' => 'warning', 'message' => "Usuario activado"]);
        }
    }

    public function getEnabledNetworkOperator(Component $component, $modelId)
    {
        return !NetworkOperator::find($modelId)->enabled;
    }

    public function getEnabledAuxNetworkOperator(Component $component, $modelId)
    {
        if (!NetworkOperator::find($modelId)->enabled) {
            return false;
        }
        return true;
    }

    public function conditionalLinkEquipmentNetworkOperator(Component $component, $modelId)
    {
        return !NetworkOperator::find($modelId)->admin->equipments()->exists();
    }

    public function conditionalDeleteTechnician(Component $component, $modelId)
    {
        return Technician::find($modelId)->clientTechnicians()->exists();
    }

    public function conditionalRemoveEquipmentNetworkOperator(Component $component, $id)
    {
        if (Equipment::find($id)->has_clients) {
            return Equipment::find($id)->has_clients;
        } else {
            return !Equipment::find($id)->has_network_operator;
        }
    }

    public function removeEquipmentNetworkOperator(Component $component, $id)
    {
        $model = User::getUserModel();
        $equipment = Equipment::find($id);
        $equipment->has_technician = false;
        $equipment->technician_id = null;
        $equipment->has_network_operator = false;
        $equipment->network_operator_id = null;
        $equipment->save();
        $component->emitTo('livewire-toast', 'show', "Equipo {$id} removido exitosamente de {$model->name}");
    }

    public function deleteTechnician(Component $component, $technicianId)
    {
        $technician = Technician::find($technicianId);
        $technician->user->enabled = false;
        $technician->push();
        foreach ($technician->equipments()->get() as $type) {
            $type->technician_id = "";
            $type->save();
        }
        $component->emitTo('livewire-toast', 'show', ['type' => 'success', 'message' => "{$technician->name} eliminado"]);
        $technician->delete();
    }

    public function disableTechnician(Component $component, $modelId)
    {
        $technician = Technician::find($modelId);
        $technician->enabled = !$technician->enabled;
        $technician->user->enabled = !$technician->user->enabled;
        $technician->push();
        if (!$technician->enabled) {
            $component->emitTo('livewire-toast', 'show', ['type' => 'warning', 'message' => "Usuario desactivado"]);
        } else {
            $component->emitTo('livewire-toast', 'show', ['type' => 'warning', 'message' => "Usuario activado"]);
        }
    }

    public function getEnabledAuxTechnician(Component $component, $modelId)
    {
        if (!Technician::find($modelId)->enabled) {
            return false;
        }
        return true;
    }

    public function getEnabledTechnician(Component $component, $modelId)
    {
        return !Technician::find($modelId)->enabled;
    }

    public function conditionalLinkEquipmentTechnician(Component $component, $modelId)
    {
        return !Technician::find($modelId)->networkOperator->equipments()->exists();
    }

    public function conditionalLinkClientsTechnician(Component $component, $modelId)
    {
        return !Technician::find($modelId)->networkOperator->clients()->exists();
    }

    public function conditionalRemoveEquipmentTechnician(Component $component, $id)
    {
        if (Equipment::find($id)->has_clients) {
            return Equipment::find($id)->has_clients;
        } else {
            return !Equipment::find($id)->has_technician;
        }
    }

    public function removeEquipmentTechnician(Component $component, $id)
    {
        $model = User::getUserModel();
        $equipment = Equipment::find($id);
        $equipment->has_technician = false;
        $equipment->technician_id = null;
        $equipment->save();
        $component->emitTo('livewire-toast', 'show', "Equipo {$id} removido exitosamente de {$model->name}");
    }

    public function conditionalDeleteSupervisor(Component $component, $modelId)
    {
        return Supervisor::find($modelId)->clientSupervisors()->exists();
    }

    public function deleteSupervisor(Component $component, $supervisorId)
    {
        $supervisor = Supervisor::find($supervisorId);
        $supervisor->user->enabled = false;
        $component->emitTo('livewire-toast', 'show', ['type' => 'success', 'message' => "{$supervisor->name} eliminado"]);
        $supervisor->delete();
    }

    public function disableSupervisor(Component $component, $modelId)
    {
        $supervisor = Supervisor::find($modelId);
        $supervisor->enabled = !$supervisor->enabled;
        $supervisor->user->enabled = !$supervisor->user->enabled;
        $supervisor->push();
        if (!$supervisor->enabled) {
            $component->emitTo('livewire-toast', 'show', ['type' => 'warning', 'message' => "Usuario desactivado"]);
        } else {
            $component->emitTo('livewire-toast', 'show', ['type' => 'warning', 'message' => "Usuario activado"]);
        }
    }

    public function conditionalLinkClientsSupervisor(Component $component, $modelId)
    {
        return !Supervisor::find($modelId)->networkOperator->clients()->exists();
    }

    public function getEnabledSupervisor(Component $component, $modelId)
    {
        return !Supervisor::find($modelId)->enabled;
    }

    public function getEnabledAuxSupervisor(Component $component, $modelId)
    {
        if (!Supervisor::find($modelId)->enabled) {
            return false;
        }
        return true;
    }

    public function conditionalDeleteEquipment(Component $component, $id)
    {
        $model = User::getUserModel();
        if ($model::class == SuperAdmin::class) {
            return Equipment::find($id)->has_admin;
        } elseif ($model::class == Admin::class) {
            return Equipment::find($id)->has_network_operator;
        }
        return false;
    }

    public function deleteEquipment(Component $component, $equipmentId)
    {
        Equipment::find($equipmentId)->delete();
        $component->emitTo('livewire-toast', 'show', "Equipo {$equipmentId} eliminado exitosamente");
        $component->reset();
    }

    public function conditionalMonitoring($clientId)
    {
        return !MicrocontrollerData::whereClientId($clientId)->exists();
    }

    public function blinkSupportPqrAvailability($supportId)
    {
        return Support::find($supportId)->blinkPqrAvailability();
    }


    public function conditionalEquipmentDeprecate($id)
    {
        $equipment = Equipment::find($id);
        return !$equipment->canDeprecate();
    }

    public function deprecateEquipment($id)
    {
        $equipment = Equipment::find($id);
        $equipment->deprecate();
    }

    public function conditionalEquipmentRepaired($id)
    {
        $equipment = Equipment::find($id);
        return !($equipment->status == Equipment::STATUS_REPAIR_PENDING or $equipment->status == Equipment::STATUS_REPAIR);
    }

    public function repairEquipment($id)
    {
        $equipment = Equipment::find($id);
        $equipment->repair();
    }

}
