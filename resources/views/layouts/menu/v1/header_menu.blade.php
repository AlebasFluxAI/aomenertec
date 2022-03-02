<section class="top-info">
    <div class="contenedor-grande">
        <div class="col-12 container-fluid bg-secondary">
            <div class="col-12">
                <nav class="navbar navbar-expand-lg navbar-secondary ">
                    <div class="container-fluid shadow-lg ">
                        <a class="navbar-brand" href="/"><img class="imagen-logo"
                                                              src="https://aom.enerteclatam.com/images/logo-horizontal.svg"
                                                              alt=""></a>
                        <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                                data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false"
                                aria-label="Toggle navigation">
                            <span class="navbar-toggler-icon"></span>
                        </button>
                        <div class="collapse navbar-collapse">
                            <ul class="navbar-nav">

                                @foreach(\App\Http\Resources\V1\Menu::getMenu()[0]->menus as $menu)
                                    @include("layouts.menu.v1.menu",["menu"=>$menu])
                                @endforeach
                                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
                                    @csrf
                                </form>
                            </ul>
                        </div>
                    </div>
                </nav>
            </div>
        </div>
    </div>

</section>

