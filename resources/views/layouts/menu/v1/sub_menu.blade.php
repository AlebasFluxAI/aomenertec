<ul class="dropdown-menu menu-option-ul">
    @foreach($menu as $menuDeep)
        <li class="menu-option-li nav-item {{$menuDeep->menus!=[]?"dropdown":''}}">
            <a class="menu-option-a" href="{{$menuDeep->route?route($menuDeep->route):"#"}}">
                <p>{{$menuDeep->title}} @if($menuDeep->menus!=[])
                        <i
                            class="fa-solid fa-bars"></i>@endif</p>
            </a>
            @isset($menuDeep->menus)
                @include("layouts.menu.v1.sub_menu",[
                        "menu"=>$menuDeep->menus
                ])
            @endisset
        </li>

    @endforeach

</ul>
