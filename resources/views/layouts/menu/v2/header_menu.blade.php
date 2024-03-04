<div class="container-fluid">
    <div class="contenedor-grande ">
        <!-- Sidebar -->
        <nav id="sidebarMenu" class="col-md-3 col-lg-2 d-md-block bg-light sidebar">

            <ul class="nav flex-column">
                <li class="nav-item">
                    <div class="mt-5">
                        <a href="{{route("administrar.v1.perfil")}}">
                            <img class="img-fluid imagen-logo"
                                 src='{{\App\Http\Resources\V1\Icon::getIcon()}}'

                                 alt="">
                        </a>
                    </div>
                </li>
                <hr id="sidebar-hr">
                <br>
                @if(\App\Http\Resources\V1\Menu::getMenuV3())
                    @foreach(\App\Http\Resources\V1\Menu::getMenuV3()["submenu"] as $key=> $menu)
                        @include("layouts.menu.v2.menu",["menu"=>$menu,"key"=>$key])
                    @endforeach
                @endif

            </ul>

            <div class="mt-auto">
                <ul class="nav flex-column">
                    <li class="nav-item">
                        @auth
                            @include("layouts.menu.v1.profile")
                        @endauth
                    </li>
                </ul>
            </div>
        </nav>
        <!-- End Sidebar -->

        <!-- Page Content -->
        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
            <div class="table-responsive">
                <br>
                @yield("content")
            </div>
        </main>
        <!-- End Page Content -->
    </div>
</div>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        var dropdowns = document.querySelectorAll('.nav-item.dropdown');
        dropdowns.forEach(function (dropdown) {
            var dropdownMenu = dropdown.querySelector('.dropdown-menu');

            dropdown.addEventListener('click', function (event) {
                var target = event.target;
                if (target.classList.contains('dropdown-toggle')) {
                    if (!dropdownMenu.classList.contains('show')) {
                        dropdownMenu.classList.add('show');
                        dropdownMenu.classList.add('ml-3');
                        return; // Stop here to prevent hiding the submenu immediately
                    }
                }
            });

            dropdown.addEventListener('mouseenter', function (event) {
                if (!dropdownMenu.classList.contains('show')) {
                    dropdownMenu.classList.add('show');
                    dropdownMenu.classList.add('ml-3');
                }
            });

            dropdown.addEventListener('mouseleave', function (event) {
                if (!dropdown.contains(event.relatedTarget) && !dropdownMenu.contains(event.relatedTarget)) {
                    dropdownMenu.classList.remove('show');
                }
            });
        });
    });
</script>
