<ul class="dropdown-menu menu-option-ul">
    @foreach($menu as $menuDeep)
        <li class="menu-option-li nav-item {{$menuDeep["submenu"]!=[]?"dropdown":''}}">
            <a class="menu-option-a" href="{{$menuDeep["route"]?route($menuDeep["route"],
               array_key_exists("binding",$menuDeep)?[$menuDeep["binding"]=>$menuDeep["binding_value"]]:[]
                  ):"#"}}">
                <p>{{$menuDeep["title"]}} @if($menuDeep["submenu"]!=[])
                        <i
                            class="fa-solid fa-bars"></i>
                    @endif</p>
            </a>
            @isset($menuDeep["submenu"])
                @include("layouts.menu.v1.sub_menu",[
                        "menu"=>$menuDeep["submenu"]
                ])
            @endisset
        </li>

    @endforeach

</ul>
