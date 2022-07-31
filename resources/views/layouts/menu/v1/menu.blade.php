<li class="nav-item {{$menu["submenu"]==[]?"":"dropdown" }}">
    <a href="{{$menu["route"]?route($menu["route"],
            array_key_exists("binding",$menu)?[$menu["binding"]=>$menu["binding_value"]]:[]
          ):"#"}}">
        <button
            class="menu-option">{{$menu["title"]}} {{--@if($menu->menus!=[])<i class="fa-solid fa-bars"></i>@endif--}}
        </button>
    </a>
    @include("layouts.menu.v1.sub_menu",[
            "menu"=>$menu["submenu"]
    ])
</li>
