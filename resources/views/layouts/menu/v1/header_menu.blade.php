<section class="top-info">
    <div class="contenedor-grande ">
        <nav class="navbar navbar-expand-lg navbar-custom " style="justify-content: space-between;padding:0px">
            <div>
                <a href="{{route("administrar.v1.perfil")}}">
                    <img class="img-fluid imagen-logo"
                         src='{{\App\Http\Resources\V1\Icon::getIcon()}}'

                         alt="">
                </a>
            </div>
            <button class="navbar-toggler" id="button-menu" type="button" data-toggle="collapse"
                    data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                    aria-expanded="false" aria-label="Toggle navigation">
                <span class="fas fa-bars"></span>
            </button>

            <div class=" collapse navbar-collapse" id="navbarSupportedContent"
                 style="visibility: inherit !important;">
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
            </div>
            <div class="">
                @auth
                    @include("layouts.menu.v1.profile")

                @endauth
            </div>
        </nav>
    </div>


</section>

