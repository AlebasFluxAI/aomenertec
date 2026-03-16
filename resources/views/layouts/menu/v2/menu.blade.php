@php
    $menuIcons = [
        'Usuarios'             => 'fa-users',
        'Clientes'             => 'fa-briefcase',
        'Equipos'              => 'fa-microchip',
        'PQRS'                 => 'fa-comment',
        'Ordenes de servicio'  => 'fa-clipboard-list',
        'Facturación'          => 'fa-receipt',
        'Configuración'        => 'fa-gear',
    ];
    $menuIcon = $menuIcons[$menu['title']] ?? 'fa-circle-dot';

    // Detectar si esta sección o algún hijo está activo
    $isActive = false;
    if (!empty($menu['route']) && request()->routeIs($menu['route'])) {
        $isActive = true;
    }
    if (!$isActive && !empty($menu['submenu'])) {
        foreach ($menu['submenu'] as $sub) {
            if (!empty($sub['route']) && request()->routeIs($sub['route'])) {
                $isActive = true; break;
            }
            if (!empty($sub['submenu'])) {
                foreach ($sub['submenu'] as $subSub) {
                    if (!empty($subSub['route']) && request()->routeIs($subSub['route'])) {
                        $isActive = true; break 2;
                    }
                }
            }
        }
    }
@endphp

<div x-data="{
    open: {{ $isActive ? 'true' : 'false' }},
    hovered: false,
    isActive: {{ $isActive ? 'true' : 'false' }}
}">

    <button
        @click="sidebarCollapsed ? null : (open = !open)"
        @mouseenter="hovered = true"
        @mouseleave="hovered = false"
        class="w-full flex items-stretch transition-colors"
        style="border: none; cursor: pointer;"
    >
        {{-- Columna ícono (izquierda, oscura) — siempre visible --}}
        <div class="py-2 bg-flux-primary-dark">
            <div class="w-12 flex items-center justify-center py-3 shrink-0 transition-colors" :class="(hovered || open || isActive) ? 'bg-flux-accent' : 'bg-flux-primary-dark'">
                <i class="fa-solid {{ $menuIcon }} text-sm transition-colors" :class="(hovered || open || isActive) ? 'text-flux-primary-dark' : 'text-flux-accent'"></i>
            </div>
        </div>

        {{-- Columna texto (derecha) — oculta cuando sidebar colapsado --}}
        <div class="py-2 bg-flux-primary overflow-hidden transition-all duration-300 ease-in-out"
             :style="sidebarCollapsed ? 'width: 0; opacity: 0' : 'width: 100%; opacity: 1'">
            <div class="flex-1 flex items-center justify-between px-4 py-3 transition-colors" :class="(hovered || open || isActive) ? 'bg-flux-accent' : 'bg-flux-primary'">
                <span class="text-sm font-medium whitespace-nowrap transition-colors" :class="(hovered || open || isActive) ? 'text-flux-primary-dark' : 'text-white'">{{ $menu['title'] }}</span>
                <i class="fa-solid fa-chevron-down text-xs transition-transform duration-200"
                   :class="open ? 'rotate-180' : ''"
                   :class="(hovered || open || isActive) ? 'text-flux-primary-dark' : 'text-flux-accent'"></i>
            </div>
        </div>
    </button>

    {{-- Submenú desplegable — oculto cuando sidebar colapsado --}}
    <div x-show="open && !sidebarCollapsed" x-cloak class="flex flex-col gap-px">
        @include("layouts.menu.v2.sub_menu", [
            "menu" => $menu["submenu"],
            "href" => "dropdownSubMenu-" . $key
        ])
    </div>

</div>
