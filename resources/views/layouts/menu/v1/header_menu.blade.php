<section class="top-info">
    <div class="contenedor-grande">
        <nav class="navbar navbar-expand-lg navbar-custom ">
            <div class="container-fluid ">
                <div class="col-md-2">
                    <a class="navbar-brand" href="/">
                        <img class="imagen-logo"
                             src="https://aom.enerteclatam.com/images/logo-horizontal.svg"
                             alt="">
                    </a>
                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                            data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false"
                            aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                </div>
                <div class="collapse navbar-collapse">
                    <ul class="navbar-nav">

                        @foreach(\App\Http\Resources\V1\Menu::getMenuV2()["submenu"] as $menu)
                            @include("layouts.menu.v1.menu",["menu"=>$menu])
                        @endforeach
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
                            @csrf
                        </form>
                    </ul>
                </div>
                @include("layouts.menu.v1.notifications")
                @include("layouts.menu.v1.profile")

            </div>
        </nav>
    </div>


</section>

