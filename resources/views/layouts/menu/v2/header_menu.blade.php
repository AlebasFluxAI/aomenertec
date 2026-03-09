{{-- =========================================================
     LAYOUT PRINCIPAL V2 (Desktop)
     Estructura: Header top + (Sidebar colapsable | Main Content)
     Toggle sidebar con Alpine.js
========================================================== --}}
<div class="flex flex-col h-screen overflow-hidden" x-data="{ sidebarOpen: true }">

    {{-- =========================================================
         HEADER
    ========================================================== --}}
    <header class="h-16 bg-white border-b border-ui-border flex items-center justify-between px-6 shrink-0 z-30 shadow-sm"
            >

        {{-- Izquierda: botón hamburguesa + logo --}}
        <div class="flex items-center gap-4">

            {{-- Botón hamburguesa --}}
            <button @click="sidebarOpen = !sidebarOpen"
                    class="text-ui-muted hover:text-flux-primary hover:bg-ui-hover p-2 rounded-lg transition-colors"
                    aria-label="Toggle menú">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"
                     fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="3" y1="6" x2="21" y2="6"></line>
                    <line x1="3" y1="12" x2="21" y2="12"></line>
                    <line x1="3" y1="18" x2="21" y2="18"></line>
                </svg>
            </button>

            {{-- Logo --}}
            <a href="{{ route('administrar.v1.perfil') }}" style="text-decoration: none;">
                <img src="{{ \App\Http\Resources\V1\Icon::getIcon() }}"
                     alt="FluxAI"
                     class="max-h-12 w-auto object-contain">
            </a>

        </div>

        {{-- Derecha: notificaciones + cambiar rol + perfil --}}
        @auth
            @include("layouts.menu.v1.profile")
        @endauth

    </header>

    {{-- =========================================================
         CUERPO: SIDEBAR + CONTENIDO PRINCIPAL
    ========================================================== --}}
    <div class="flex flex-1 overflow-hidden">

        {{-- =========================================================
             SIDEBAR (colapsable)
        ========================================================== --}}
        <aside :class="sidebarOpen ? 'w-[260px]' : 'w-0'"
               class="bg-white border-r border-ui-border flex flex-col shrink-0 overflow-hidden transition-all duration-300"
               style="box-shadow: 2px 0 8px -4px rgba(0,0,0,0.05);">

            {{-- Navegación --}}
            <nav class="p-3 flex flex-col gap-1 overflow-y-auto flex-1 min-w-[260px]">
                @if(\App\Http\Resources\V1\Menu::getMenuV3())
                    @foreach(\App\Http\Resources\V1\Menu::getMenuV3()["submenu"] as $key => $menu)
                        @include("layouts.menu.v2.menu", ["menu" => $menu, "key" => $key])
                    @endforeach
                @endif
            </nav>

            {{-- Cerrar sesión (anclado en la parte inferior) --}}
            <div class="p-3 border-t border-ui-border shrink-0 min-w-[260px]">
                @auth
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit"
                                class="w-full flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm text-ui-muted hover:bg-red-50 hover:text-red-600 transition-colors"
                                style="border: none; background: transparent; cursor: pointer;">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"
                                 fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                 class="shrink-0">
                                <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
                                <polyline points="16 17 21 12 16 7"></polyline>
                                <line x1="21" y1="12" x2="9" y2="12"></line>
                            </svg>
                            <span class="whitespace-nowrap">Cerrar sesión</span>
                        </button>
                    </form>
                @endauth
            </div>

        </aside>

        {{-- =========================================================
             ÁREA DE CONTENIDO PRINCIPAL
        ========================================================== --}}
        <main class="flex-1 min-w-0 flex flex-col overflow-hidden bg-ui-page">

            {{-- Contenido scrolleable --}}
            <div class="flex-1 overflow-y-auto p-8">
                @yield("content")
            </div>

            {{-- Footer pegado al fondo de la columna principal --}}
            @include("footer")

        </main>

    </div>

</div>
