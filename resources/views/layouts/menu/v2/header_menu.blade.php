{{-- =========================================================
     LAYOUT PRINCIPAL V2
     Sidebar:
       - Móvil:   overlay completo (sidebarOpen, comportamiento original)
       - Desktop: fixed overlay, siempre visible, colapsable a iconos (sidebarCollapsed)
                  Cuando colapsado: oculta la fila top (logo+X) y el texto de los ítems
     Toggle con Alpine.js
========================================================== --}}
<style>
    /* =========================================================
       FluxAI Navigation Layer
       Aplica el mismo lenguaje visual del menu "Acciones":
       - Tipografia Inter sobria y legible
       - Acento gradiente verde-azul como linea superior
       - Hover/active suaves con elevacion
       Solo CSS: no modifica estructura ni comportamiento.
    ========================================================= --}}
    aside nav { font-family: var(--flux-tech-font, 'Inter', system-ui, sans-serif); }
    aside nav button,
    aside nav a {
        font-family: var(--flux-tech-font, 'Inter', system-ui, sans-serif);
        letter-spacing: 0.005em;
    }
    aside nav span {
        font-weight: 500;
        font-size: 14px;
    }
    aside nav button i,
    aside nav a i { transition: transform 0.2s ease; }
    aside nav button:hover i:not(.fa-chevron-down),
    aside nav a:hover i:not(.fa-chevron-down) { transform: scale(1.08); }

    /* Header — alinear tipografia con el resto de la plataforma */
    header { font-family: var(--flux-tech-font, 'Inter', system-ui, sans-serif); }

    /* Profile icons (notificaciones/rol/perfil/logout) — estilo coherente con Acciones */
    header a[title="Cambiar rol"],
    header a[title="Mi Perfil"],
    header form button[title="Cerrar sesión"] {
        transition: box-shadow .2s ease, border-color .2s ease, color .2s ease, background-color .2s ease, transform .1s ease;
    }
    header a[title="Cambiar rol"]:hover,
    header a[title="Mi Perfil"]:hover {
        border-color: rgba(12, 98, 220, 0.35) !important;
        color: var(--flux-primary, #0044A4) !important;
        background: linear-gradient(90deg, rgba(12, 98, 220, 0.06) 0%, rgba(0, 199, 129, 0.05) 100%) !important;
        box-shadow: 0 4px 12px -6px rgba(12, 98, 220, 0.35);
        transform: translateY(-1px);
    }
    header form button[title="Cerrar sesión"]:hover {
        box-shadow: 0 4px 12px -6px rgba(229, 57, 53, 0.35);
        transform: translateY(-1px);
    }

    /* Accent strip on top of the sidebar (gradiente energético) */
    aside > div:first-child {
        position: relative;
    }
    aside > div:first-child::before {
        content: "";
        position: absolute;
        left: 0; right: 0; top: 0;
        height: 2px;
        background: linear-gradient(90deg, #00C781 0%, #0C62DC 55%, #0044A4 100%);
        z-index: 1;
    }
</style>
<div class="flex flex-col h-screen overflow-hidden"
     x-data="{
         sidebarOpen: false,
         sidebarCollapsed: false,
         toggleSidebar() {
             if (window.innerWidth >= 1024) {
                 this.sidebarCollapsed = !this.sidebarCollapsed;
             } else {
                 this.sidebarOpen = !this.sidebarOpen;
             }
         }
     }">

    {{-- =========================================================
         BACKDROP (overlay oscuro — solo móvil)
    ========================================================== --}}
    <div x-show="sidebarOpen"
         x-cloak
         @click="sidebarOpen = false"
         x-transition:enter="transition-opacity duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition-opacity duration-300"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-40 lg:hidden"
         style="background-color: rgba(0,0,0,0.45);">
    </div>

    {{-- =========================================================
         SIDEBAR MÓVIL: overlay completo (comportamiento original)
         Solo visible en < lg
    ========================================================== --}}
    <aside x-show="sidebarOpen"
           x-cloak
           x-transition:enter="transition-transform duration-300 ease-in-out"
           x-transition:enter-start="-translate-x-full transform"
           x-transition:enter-end="translate-x-0 transform"
           x-transition:leave="transition-transform duration-300 ease-in-out"
           x-transition:leave-start="translate-x-0 transform"
           x-transition:leave-end="-translate-x-full transform"
           class="fixed top-0 left-0 h-full w-[260px] flex flex-col z-50 lg:hidden"
           style="background-color: #0C62DC;">

        {{-- Top: columna ícono (X) + columna texto (Logo) --}}
        <div class="flex items-stretch shrink-0 h-16"
             style="border-bottom: 1px solid rgba(255,255,255,0.12);">

            {{-- Columna ícono: botón X --}}
            <div class="w-12 flex items-center justify-center shrink-0"
                 style="background-color: #003380;">
                <button @click="sidebarOpen = false"
                        class="p-2 rounded-lg transition-colors hover:bg-white/10"
                        style="border: none; cursor: pointer; color: #00C781;"
                        aria-label="Cerrar menú">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24"
                         fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="18" y1="6" x2="6" y2="18"></line>
                        <line x1="6" y1="6" x2="18" y2="18"></line>
                    </svg>
                </button>
            </div>

            {{-- Columna texto: logo --}}
            <div class="flex-1 flex items-center px-4"
                 style="background-color: #0C62DC;">
                <a href="{{ route('administrar.v1.perfil') }}" style="text-decoration: none;">
                    <img src="{{ \App\Http\Resources\V1\Icon::getIconSidebar() }}"
                         alt="FluxAI"
                         class="max-h-8 w-auto object-contain">
                </a>
            </div>

        </div>

        {{-- Navegación --}}
        <nav class="flex-1 overflow-y-auto flex flex-col">
            @if(\App\Http\Resources\V1\Menu::getMenuV3())
                @foreach(\App\Http\Resources\V1\Menu::getMenuV3()["submenu"] as $key => $menu)
                    @include("layouts.menu.v2.menu", ["menu" => $menu, "key" => $key])
                @endforeach
            @endif
        </nav>

        {{-- Cerrar sesión (anclado al fondo) --}}
        <div class="shrink-0" style="border-top: 1px solid rgba(255,255,255,0.12);">
            @auth
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <div x-data="{ hovered: false }"
                         @mouseenter="hovered = true" @mouseleave="hovered = false">
                        <button type="submit"
                                class="w-full flex items-stretch transition-colors"
                                style="border: none; cursor: pointer;">
                            <div class="w-12 flex items-center justify-center py-3 shrink-0 transition-colors"
                                 :style="hovered ? 'background-color: #00C781' : 'background-color: #003380'">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"
                                     fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                     :style="hovered ? 'color: #003380' : 'color: #00C781'">
                                    <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
                                    <polyline points="16 17 21 12 16 7"></polyline>
                                    <line x1="21" y1="12" x2="9" y2="12"></line>
                                </svg>
                            </div>
                            <div class="flex-1 flex items-center px-4 py-3 transition-colors"
                                 :style="hovered ? 'background-color: #00C781' : 'background-color: #003380'">
                                <span class="text-sm font-medium transition-colors"
                                      :style="hovered ? 'color: #003380' : 'color: white'">Cerrar sesión</span>
                            </div>
                        </button>
                    </div>
                </form>
            @endauth
        </div>

    </aside>

    {{-- =========================================================
         SIDEBAR DESKTOP: fixed overlay, siempre presente en lg+
         Expandido:  w-[260px], muestra top con logo y texto en ítems
         Colapsado:  w-12, oculta la fila top (pero mantiene el espacio h-16)
                     y oculta el texto de los ítems
    ========================================================== --}}
    <aside class="hidden lg:flex flex-col fixed top-0 left-0 h-full z-50 overflow-x-hidden transition-all duration-300 ease-in-out"
           :class="sidebarCollapsed ? 'w-12' : 'w-[260px]'"
           style="background-color: #0C62DC;">

        {{-- Fila top: visible cuando expandido, invisible (pero ocupa h-16) cuando colapsado --}}
        <div class="flex items-stretch shrink-0 h-16"
             style="border-bottom: 1px solid rgba(255,255,255,0.12);">

            {{-- Columna ícono: hamburguesa (abre) o X (cierra) según estado --}}
            <div class="w-12 flex items-center justify-center shrink-0 transition-colors"
                 style="background-color: #003380;">

                {{-- Hamburguesa: visible cuando colapsado, abre el sidebar --}}
                <button x-show="sidebarCollapsed"
                        x-cloak
                        @click="sidebarCollapsed = false"
                        class="p-2 rounded-lg transition-colors hover:bg-white/10"
                        style="border: none; cursor: pointer; color: #00C781;"
                        aria-label="Abrir menú">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24"
                         fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="3" y1="6" x2="21" y2="6"></line>
                        <line x1="3" y1="12" x2="21" y2="12"></line>
                        <line x1="3" y1="18" x2="21" y2="18"></line>
                    </svg>
                </button>

                {{-- X: visible cuando expandido, cierra el sidebar --}}
                <button x-show="!sidebarCollapsed"
                        x-cloak
                        @click="sidebarCollapsed = true"
                        class="p-2 rounded-lg transition-colors hover:bg-white/10"
                        style="border: none; cursor: pointer; color: #00C781;"
                        aria-label="Cerrar menú">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24"
                         fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="18" y1="6" x2="6" y2="18"></line>
                        <line x1="6" y1="6" x2="18" y2="18"></line>
                    </svg>
                </button>

            </div>

            {{-- Columna texto: logo — oculta cuando colapsado --}}
            <div class="flex-1 flex items-center px-4 overflow-hidden transition-all duration-300 ease-in-out"
                 :style="sidebarCollapsed ? 'width: 0; opacity: 0; padding: 0' : 'width: 100%; opacity: 1'"
                 style="background-color: #0C62DC;">
                <a href="{{ route('administrar.v1.perfil') }}" style="text-decoration: none;">
                    <img src="{{ \App\Http\Resources\V1\Icon::getIconSidebar() }}"
                         alt="FluxAI"
                         class="max-h-8 w-auto object-contain whitespace-nowrap">
                </a>
            </div>

        </div>

        {{-- Navegación --}}
        <nav class="flex-1 overflow-y-auto flex flex-col overflow-x-hidden">
            @if(\App\Http\Resources\V1\Menu::getMenuV3())
                @foreach(\App\Http\Resources\V1\Menu::getMenuV3()["submenu"] as $key => $menu)
                    @include("layouts.menu.v2.menu", ["menu" => $menu, "key" => $key])
                @endforeach
            @endif
        </nav>

        {{-- Cerrar sesión (anclado al fondo) --}}
        <div class="shrink-0" style="border-top: 1px solid rgba(255,255,255,0.12);">
            @auth
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <div x-data="{ hovered: false }"
                         @mouseenter="hovered = true" @mouseleave="hovered = false">
                        <button type="submit"
                                class="w-full flex items-stretch transition-colors"
                                style="border: none; cursor: pointer;">
                            <div class="w-12 flex items-center justify-center py-3 shrink-0 transition-colors"
                                 :style="hovered ? 'background-color: #00C781' : 'background-color: #003380'">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"
                                     fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                     :style="hovered ? 'color: #003380' : 'color: #00C781'">
                                    <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
                                    <polyline points="16 17 21 12 16 7"></polyline>
                                    <line x1="21" y1="12" x2="9" y2="12"></line>
                                </svg>
                            </div>
                            {{-- Texto — oculto cuando colapsado --}}
                            <div class="flex items-center py-3 overflow-hidden transition-all duration-300 ease-in-out"
                                 :style="sidebarCollapsed
                                     ? 'width: 0; opacity: 0; padding: 0'
                                     : (hovered
                                         ? 'width: 100%; opacity: 1; background-color: #00C781; padding-left: 1rem; padding-right: 1rem'
                                         : 'width: 100%; opacity: 1; background-color: #003380; padding-left: 1rem; padding-right: 1rem')">
                                <span class="text-sm font-medium whitespace-nowrap transition-colors"
                                      :style="hovered ? 'color: #003380' : 'color: white'">Cerrar sesión</span>
                            </div>
                        </button>
                    </div>
                </form>
            @endauth
        </div>

    </aside>

    {{-- =========================================================
         HEADER
         Desktop: margen izquierdo dinámico para no quedar bajo el sidebar fixed
         Móvil:   sin margen (el sidebar es overlay)
    ========================================================== --}}
    <header class="h-16 bg-white border-b border-ui-border flex items-center justify-between px-6 shrink-0 z-30 shadow-sm transition-all duration-300 ease-in-out"
            :class="sidebarCollapsed ? 'lg:pl-16' : 'lg:pl-[272px]'">

        {{-- Izquierda: botón hamburguesa (solo móvil) + logo --}}
        <div class="flex items-center gap-4">
            <button @click="toggleSidebar()"
                    class="lg:hidden text-ui-muted hover:text-flux-primary hover:bg-ui-hover p-2 rounded-lg transition-colors"
                    aria-label="Toggle menú">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"
                     fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="3" y1="6" x2="21" y2="6"></line>
                    <line x1="3" y1="12" x2="21" y2="12"></line>
                    <line x1="3" y1="18" x2="21" y2="18"></line>
                </svg>
            </button>

            <a href="{{ route('administrar.v1.perfil') }}" style="text-decoration: none;"
               :class="{ 'lg:hidden': !sidebarCollapsed }">
                <img src="{{ \App\Http\Resources\V1\Icon::getIcon() }}"
                     alt="FluxAI"
                     class="max-h-10 w-auto object-contain">
            </a>
        </div>

        {{-- Derecha: notificaciones + cambiar rol + perfil --}}
        @auth
            @include("layouts.menu.v1.profile")
        @endauth

    </header>

    {{-- =========================================================
         ÁREA DE CONTENIDO PRINCIPAL
         Desktop: margen izquierdo dinámico para no quedar bajo el sidebar fixed
         Móvil:   sin margen (el sidebar es overlay)
    ========================================================== --}}
    <div class="flex-1 overflow-hidden flex flex-col bg-ui-page transition-all duration-300 ease-in-out"
         :class="sidebarCollapsed ? 'lg:ml-12' : 'lg:ml-[260px]'">

        <div class="flex-1 overflow-y-auto p-8">
            @yield("content")
        </div>

        @include("footer")

    </div>

</div>
