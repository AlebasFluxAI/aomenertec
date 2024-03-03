<li class="nav-item dropdown" class="sidebar-main-menu">
    <a class="nav-link dropdown-toggle" href="#" id="dropdownSubMenu-{{$key}}" role="button"
       data-bs-toggle="dropdown" aria-expanded="false">
        <button
            class="ms-1 d-none d-sm-inline menu-option">{{$menu["title"]}}</button>
    </a>

    @include("layouts.menu.v2.sub_menu",[
            "menu"=>$menu["submenu"],
            "href"=>"dropdownSubMenu-".$key
    ])
</li>
