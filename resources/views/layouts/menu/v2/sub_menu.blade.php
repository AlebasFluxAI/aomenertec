@foreach($menu as $key => $menuDeep)

    @if(empty($menuDeep['submenu']))
        {{-- Enlace final (sin hijos) --}}
        <a
            href="{{ $menuDeep['route'] ? route($menuDeep['route'], array_key_exists('binding', $menuDeep) ? [$menuDeep['binding'] => $menuDeep['binding_value']] : []) : '#' }}"
            class="flex items-center px-3 py-2 text-sm text-slate-500 rounded-lg hover:bg-slate-50 hover:text-slate-800 transition-colors"
            style="text-decoration: none;"
        >
            {{ $menuDeep['title'] }}
        </a>

    @else
        {{-- Item con sub-hijos --}}
        <div x-data="{ open: false }">
            <button
                @click="open = !open"
                :class="open ? 'text-blue-700 font-medium' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-800'"
                class="w-full flex items-center justify-between px-3 py-2 text-sm rounded-lg transition-colors border-0"
                style="cursor: pointer; background: none;"
            >
                <span>{{ $menuDeep['title'] }}</span>
                <i class="fa-solid fa-chevron-down text-xs opacity-50 transition-transform duration-200"
                   :class="open ? 'rotate-180' : ''"></i>
            </button>

            <div x-show="open" x-cloak
                 class="mt-0.5 ml-3 pl-3 flex flex-col gap-0.5 border-l border-slate-200">
                @include('layouts.menu.v2.sub_menu', [
                    'menu' => $menuDeep['submenu'],
                    'href' => $href . "-" . $key
                ])
            </div>
        </div>
    @endif

@endforeach
