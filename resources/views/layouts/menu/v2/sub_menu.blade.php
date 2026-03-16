@foreach($menu as $key => $menuDeep)

    @php
        $subIsActive = !empty($menuDeep['route']) && request()->routeIs($menuDeep['route']);
    @endphp

    @if(empty($menuDeep['submenu']))

        {{-- Enlace final (sin hijos) --}}
        <div x-data="{ hovered: false, isActive: {{ $subIsActive ? 'true' : 'false' }} }"
             @mouseenter="hovered = true" @mouseleave="hovered = false">
            <a href="{{ $menuDeep['route'] ? route($menuDeep['route'], array_key_exists('binding', $menuDeep) ? [$menuDeep['binding'] => $menuDeep['binding_value']] : []) : '#' }}"
               class="w-full flex items-stretch transition-colors"
               style="text-decoration: none;">

                {{-- Columna ícono vacía (continuidad visual de la franja oscura) --}}
                <div class="w-12 shrink-0 transition-colors"
                     :style="(hovered || isActive)
                         ? 'background-color: #00C781'
                         : 'background-color: #003380'">
                </div>

                {{-- Columna texto — oculta cuando sidebar colapsado --}}
                <div class="flex items-center py-2 overflow-hidden transition-all duration-300 ease-in-out"
                     :style="sidebarCollapsed
                         ? 'width: 0; opacity: 0; padding: 0'
                         : ((hovered || isActive)
                             ? 'width: 100%; opacity: 1; background-color: #00C781; padding-left: 1rem; padding-right: 1rem'
                             : 'width: 100%; opacity: 1; background-color: #0C62DC; padding-left: 1rem; padding-right: 1rem')">
                    <span class="text-sm whitespace-nowrap transition-colors"
                          :style="(hovered || isActive)
                              ? 'color: #003380; font-weight: 600'
                              : 'color: rgba(255,255,255,0.8)'">{{ $menuDeep['title'] }}</span>
                </div>
            </a>
        </div>

    @else

        {{-- Ítem con sub-hijos --}}
        @php
            $subGroupActive = $subIsActive;
            if (!$subGroupActive && !empty($menuDeep['submenu'])) {
                foreach ($menuDeep['submenu'] as $deepSub) {
                    if (!empty($deepSub['route']) && request()->routeIs($deepSub['route'])) {
                        $subGroupActive = true; break;
                    }
                }
            }
        @endphp

        <div x-data="{
            open: {{ $subGroupActive ? 'true' : 'false' }},
            hovered: false,
            isActive: {{ $subGroupActive ? 'true' : 'false' }}
        }">
            <button
                @click="open = !open"
                @mouseenter="hovered = true"
                @mouseleave="hovered = false"
                class="w-full flex items-stretch transition-colors"
                style="border: none; cursor: pointer;"
            >
                {{-- Columna ícono vacía --}}
                <div class="w-12 shrink-0 transition-colors"
                     :style="(hovered || open || isActive)
                         ? 'background-color: #00C781'
                         : 'background-color: #003380'">
                </div>

                {{-- Columna texto — oculta cuando sidebar colapsado --}}
                <div class="flex items-center justify-between py-2 overflow-hidden transition-all duration-300 ease-in-out"
                     :style="sidebarCollapsed
                         ? 'width: 0; opacity: 0; padding: 0'
                         : ((hovered || open || isActive)
                             ? 'width: 100%; opacity: 1; background-color: #00C781; padding-left: 1rem; padding-right: 1rem'
                             : 'width: 100%; opacity: 1; background-color: #0C62DC; padding-left: 1rem; padding-right: 1rem')">
                    <span class="text-sm whitespace-nowrap transition-colors"
                          :style="(hovered || open || isActive)
                              ? 'color: #003380; font-weight: 600'
                              : 'color: rgba(255,255,255,0.8)'">{{ $menuDeep['title'] }}</span>
                    <i class="fa-solid fa-chevron-down text-xs transition-transform duration-200"
                       :class="open ? 'rotate-180' : ''"
                       :style="(hovered || open || isActive)
                           ? 'color: #003380; opacity: 0.6'
                           : 'color: rgba(255,255,255,0.4)'"></i>
                </div>
            </button>

            {{-- Sub-submenú --}}
            <div x-show="open" x-cloak class="flex flex-col gap-px">
                @include('layouts.menu.v2.sub_menu', [
                    'menu' => $menuDeep['submenu'],
                    'href' => $href . "-" . $key
                ])
            </div>
        </div>

    @endif

@endforeach
