<ul class="dropdown-menu " aria-labelledby="{{ $href }}">
    @foreach($menu as $key=>$menuDeep)
        <li class="dropdown">
            <a class="dropdown-item  {{$menuDeep['route']?:"dropdown-toggle"}}"
               href="{{ $menuDeep['route'] ? route($menuDeep['route'], array_key_exists('binding', $menuDeep) ? [$menuDeep['binding'] => $menuDeep['binding_value']] : []) : '#' }}"
               id="{{ $href."-".$key }}"
               @if(!$menuDeep['route'])
                   role="button"
               data-bs-toggle="dropdown" aria-expanded="false"
                @endif
            >
                {{ $menuDeep['title'] }}
            </a>
            @isset($menuDeep['submenu'])
                @include('layouts.menu.v2.sub_menu', ['menu' => $menuDeep['submenu'], 'href' => $href."-".$key])
            @endisset
        </li>
    @endforeach
</ul>
