<link rel="stylesheet" href="/css/adminlte.css">
<section class="top-info">
    <div class="container">
        <div class="contenedor-grande">
            <div class="col-12">
                <nav class="navbar navbar-expand-lg navbar-light">
                    <div class="container-fluid">
                        <a class="navbar-brand" href="/"><img class="imagen-logo"
                                                              src="https://enertedevops.s3.us-east-2.amazonaws.com/images/logotipo-enerteclatam.png"
                                                              alt=""></a>
                        <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                                data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false"
                                aria-label="Toggle navigation">
                            <span class="navbar-toggler-icon"></span>
                        </button>
                        <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
                            <ul class="navbar-nav">
                                <li class="nav-item">
                                    <a href="{{ route('administrar.v1.usuarios.agregar') }}">
                                        <button>Editar</button>
                                    </a>
                                </li>
                                <li class="nav-item dropdown">
                                    <button class="nav-item " id="DropdownMenuClientes" role="button"
                                            data-bs-toggle="dropdown" aria-expanded="false">
                                        Clientes ▼
                                    </button>
                                    <ul class="dropdown-menu" aria-labelledby="DropdownMenuClientes">
                                        <li class="nav-item">
                                            <a href="#">
                                                <button title="" class="px-5">Agregar</button>
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a href="#">
                                                <button title="" class="px-5">Editar</button>
                                            </a>
                                        </li>

                                    </ul>
                                </li>
                                <li class="nav-item dropdown">
                                    <button class="nav-item " id="DropdownMenuPrecio" role="button"
                                            data-bs-toggle="dropdown" aria-expanded="false">
                                        Precios ▼
                                    </button>
                                    <ul class="dropdown-menu" aria-labelledby="DropdownMenuPrecio">
                                        <li class="nav-item">
                                            <a href="#">
                                                <button title="Solucion fotovoltaica" class="px-5">SFVI</button>
                                            </a>
                                        </li>

                                        <li class="nav-item dropdown">
                                            <a href="#" id="navbarDropdownMenuLink" role="button"
                                               data-bs-toggle="dropdown" aria-expanded="false">
                                                <button>
                                                    Red Convencional ▼
                                                </button>
                                            </a>
                                            <ul class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                                                <li class="nav-item">
                                                    <a title="Componentes costo unitario" href="#">
                                                        <button>Costo unitario</button>
                                                    </a>
                                                </li>
                                                <li class="nav-item">
                                                    <a title="Contribucion-Subsidio-Impuesto" href="#">
                                                        <button>Otros conceptos</button>
                                                    </a>
                                                </li>
                                            </ul>
                                        </li>
                                    </ul>
                                </li>
                                <li class="nav-item">
                                    <a>
                                        <button>Formatos</button>
                                    </a>

                                </li>
                                <li class="nav-item">
                                    <button>
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                             fill="currentColor" class="bi bi-bell" viewBox="0 0 16 16">
                                            <path
                                                d="M8 16a2 2 0 0 0 2-2H6a2 2 0 0 0 2 2zM8 1.918l-.797.161A4.002 4.002 0 0 0 4 6c0 .628-.134 2.197-.459 3.742-.16.767-.376 1.566-.663 2.258h10.244c-.287-.692-.502-1.49-.663-2.258C12.134 8.197 12 6.628 12 6a4.002 4.002 0 0 0-3.203-3.92L8 1.917zM14.22 12c.223.447.481.801.78 1H1c.299-.199.557-.553.78-1C2.68 10.2 3 6.88 3 6c0-2.42 1.72-4.44 4.005-4.901a1 1 0 1 1 1.99 0A5.002 5.002 0 0 1 13 6c0 .88.32 4.2 1.22 6z"/>
                                        </svg>
                                    </button>
                                </li>
                                <li class="nav-item">
                                    <a title="Cerrar sesion" href="{{ route('logout') }}"
                                       onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                        <button>
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                 fill="currentColor" class="bi bi-box-arrow-right" viewBox="0 0 16 16">
                                                <path fill-rule="evenodd"
                                                      d="M10 12.5a.5.5 0 0 1-.5.5h-8a.5.5 0 0 1-.5-.5v-9a.5.5 0 0 1 .5-.5h8a.5.5 0 0 1 .5.5v2a.5.5 0 0 0 1 0v-2A1.5 1.5 0 0 0 9.5 2h-8A1.5 1.5 0 0 0 0 3.5v9A1.5 1.5 0 0 0 1.5 14h8a1.5 1.5 0 0 0 1.5-1.5v-2a.5.5 0 0 0-1 0v2z"/>
                                                <path fill-rule="evenodd"
                                                      d="M15.854 8.354a.5.5 0 0 0 0-.708l-3-3a.5.5 0 0 0-.708.708L14.293 7.5H5.5a.5.5 0 0 0 0 1h8.793l-2.147 2.146a.5.5 0 0 0 .708.708l3-3z"/>
                                            </svg>
                                        </button>
                                    </a>
                                </li>
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
