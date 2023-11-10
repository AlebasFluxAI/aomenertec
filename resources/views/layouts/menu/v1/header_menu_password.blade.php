<section class="top-info" wire:ignore>
    <div class="contenedor-grande">
        <nav class="navbar navbar-expand-lg "
             style="justify-content: space-between;padding: 5px;{{\App\Http\Resources\V1\Subdomain::getHeaderColor()}}">
            <a href="/">
                <img class="imagen-logo"
                     src='{{\App\Http\Resources\V1\Subdomain::getHeaderIcon()}}'

                     alt="">
            </a>
            <button class="navbar-toggler" id="button-menu" type="button" data-toggle="collapse"
                    data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                    aria-expanded="false" aria-label="Toggle navigation">
                <span class="fas fa-bars"></span>
            </button>

            <div class=" collapse navbar-collapse" id="navbarSupportedContent" style="visibility: inherit !important;">
                <ul class="navbar-nav mr-auto">

                    <li class="nav-item">

                        @isset(\App\Http\Resources\V1\Menu::getMenuV3()["submenu"])

                            <ul class="navbar-nav" style="justify-content: left">

                                @foreach(\App\Http\Resources\V1\Menu::getMenuV3()["submenu"] as $menu)
                                    @include("layouts.menu.v1.menu",["menu"=>$menu])
                                @endforeach
                                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
                                    @csrf
                                </form>
                            </ul>

                        @endisset

                    </li>
                </ul>
                <div class="mt-4 mb-4">
                    @auth
                        @include("layouts.menu.v1.profile")

                    @endauth
                </div>
            </div>
        </nav>
    </div>


</section>

