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
@endphp

<div x-data="{ open: false }">

    {{-- Botón principal del item --}}
    <button
        @click="open = !open"
        :class="open
            ? 'bg-blue-50 text-blue-700 font-medium'
            : 'text-slate-500 hover:bg-slate-50 hover:text-slate-800'"
        class="w-full flex items-center justify-between px-3 py-2.5 rounded-xl transition-colors text-sm border-0"
        style="cursor: pointer;"
    >
        <div class="flex items-center gap-3">
            <i class="fa-solid {{ $menuIcon }} w-4 text-center text-sm"></i>
            <span>{{ $menu['title'] }}</span>
        </div>
        <i class="fa-solid fa-chevron-down text-xs opacity-50 transition-transform duration-200"
           :class="open ? 'rotate-180' : ''"></i>
    </button>

    {{-- Submenú desplegable --}}
    <div x-show="open" x-cloak
         class="mt-1 ml-3 pl-3 flex flex-col gap-0.5 border-l border-slate-200">
        @include("layouts.menu.v2.sub_menu", [
            "menu" => $menu["submenu"],
            "href" => "dropdownSubMenu-" . $key
        ])
    </div>

</div>
