<div>
    <!-- Theme style -->
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
                                        <a href="{{ route('administrar.adduser') }}">
                                            <button>Agregar</button>
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
                                                     fill="currentColor" class="bi bi-box-arrow-right"
                                                     viewBox="0 0 16 16">
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
    <section class="login">
        <div class="container">
            <div class="section-title">
                <h2 class="text-center p3"><b><span class="naranja">Editar </span> <span class="azul">Usuario</span></b>
                </h2>
            </div>
            @if (session()->has('message'))
                <div class="alert alert-success">
                    {{ session('message') }}
                </div>
            @endif
            @if (session()->has('eliminate'))
                <div class="alert alert-danger">
                    {{ session('eliminate') }}
                </div>
            @endif

            <div class="contenedor-grande">
                <div class="row content pt-3">
                    <form wire:submit.prevent="edit" id="formulario" class="needs-validation" role="form">
                        <div>&nbsp;&nbsp;<strong>Ingrese identificacion del usuario a editar</strong></div>
                        <div class="row ">
                            <div class="form-group mb-2 col-md-12 col-sm-12">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span for="user" class="input-group-text">
                                            <i>
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                     fill="currentColor" class="bi bi-person-video2"
                                                     viewBox="0 0 16 16">
                                                    <path d="M10 9.05a2.5 2.5 0 1 0 0-5 2.5 2.5 0 0 0 0 5Z"/>
                                                    <path
                                                        d="M2 1a2 2 0 0 0-2 2v9a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V3a2 2 0 0 0-2-2H2ZM1 3a1 1 0 0 1 1-1h2v2H1V3Zm4 10V2h9a1 1 0 0 1 1 1v9c0 .285-.12.543-.31.725C14.15 11.494 12.822 10 10 10c-3.037 0-4.345 1.73-4.798 3H5Zm-4-2h3v2H2a1 1 0 0 1-1-1v-1Zm3-1H1V8h3v2Zm0-3H1V5h3v2Z"/>
                                                </svg>
                                            </i>
                                        </span>
                                    </div>
                                    <input wire:model="user"
                                           wire:keydown.enter="asisignUserFirst()" type="text" class="form-control"
                                           id="user" autocomplete="off" placeholder="Identificacion" required>
                                    <div class="input-group-append">
                                        <span class="input-group-text">
                                            @if($pickedU)
                                                <span class="badge badge-success">
                                                    <i>
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                             fill="currentColor" class="bi bi-check2"
                                                             viewBox="0 0 16 16">
                                                            <path
                                                                d="M13.854 3.646a.5.5 0 0 1 0 .708l-7 7a.5.5 0 0 1-.708 0l-3.5-3.5a.5.5 0 1 1 .708-.708L6.5 10.293l6.646-6.647a.5.5 0 0 1 .708 0z"/>
                                                        </svg>
                                                    </i>
                                                </span>
                                            @else
                                                <span class="badge badge-danger">
                                                    <i>
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                             fill="currentColor" class="bi bi-x-lg" viewBox="0 0 16 16">
                                                            <path fill-rule="evenodd"
                                                                  d="M13.854 2.146a.5.5 0 0 1 0 .708l-11 11a.5.5 0 0 1-.708-.708l11-11a.5.5 0 0 1 .708 0Z"/>
                                                            <path fill-rule="evenodd"
                                                                  d="M2.146 2.146a.5.5 0 0 0 0 .708l11 11a.5.5 0 0 0 .708-.708l-11-11a.5.5 0 0 0-.708 0Z"/>
                                                        </svg>
                                                    </i>
                                                </span>
                                            @endif
                                        </span>
                                    </div>
                                </div>
                                @error("user")
                                <div class="error-container">
                                    <small class="form-text text-danger">{{$message}}</small>
                                </div>
                                @else
                                    @if(count($users)>0)
                                        @if(!$pickedU)
                                            <ul class="dropdown-menu list-search">
                                                <h6 class="dropdown-header"><b>Seleccione opción</b></h6>
                                                @foreach($users as $usuario)
                                                    <li class="dropdown-item">
                                                        <a wire:click="assignUser('{{ $usuario }}')" type="button">
                                                            {{ $usuario->name }}
                                                        </a>
                                                    </li>
                                                @endforeach
                                            </ul>
                                        @endif
                                    @else
                                        <div class="">
                                            <small class="form-text text-muted">{{ $messageU }}</small>
                                        </div>
                                    @endif
                                    @enderror
                            </div>
                            @if($pickedU)
                                <div> &nbsp;&nbsp; <strong> Actualice informacion requerida</strong></div>
                                <div class="form-group mb-2 col-md-4 col-sm-12">
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                        <span class="input-group-text">
                                            <i>
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                     fill="currentColor" class="bi bi-person-video2"
                                                     viewBox="0 0 16 16">
                                                    <path d="M10 9.05a2.5 2.5 0 1 0 0-5 2.5 2.5 0 0 0 0 5Z"/>
                                                    <path
                                                        d="M2 1a2 2 0 0 0-2 2v9a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V3a2 2 0 0 0-2-2H2ZM1 3a1 1 0 0 1 1-1h2v2H1V3Zm4 10V2h9a1 1 0 0 1 1 1v9c0 .285-.12.543-.31.725C14.15 11.494 12.822 10 10 10c-3.037 0-4.345 1.73-4.798 3H5Zm-4-2h3v2H2a1 1 0 0 1-1-1v-1Zm3-1H1V8h3v2Zm0-3H1V5h3v2Z"/>
                                                </svg>
                                            </i>
                                        </span>
                                        </div>
                                        <input wire:model="identification" type="number" class="form-control"
                                               autocomplete="off" placeholder="Identificacion" required>
                                    </div>
                                    @error('identification')
                                    <div class="error-container">
                                        <small class="form-text text-danger">{{$message}}</small>
                                    </div>
                                    @enderror
                                </div>

                                <div class="form-group mb-2 col-md-8 col-sm-12">
                                    <input wire:model="name" id="name" class="form-control typeahead" autocomplete="off"
                                           name="name" type="text" placeholder="Nombre" required/>
                                    @error('name')
                                    <div class="error-container">
                                        <small class="form-text text-danger">{{$message}}</small>
                                    </div>
                                    @enderror
                                </div>

                                <div class="form-group mb-2 col-md-4 col-sm-12">
                                    <input wire:model="phone" class="form-control" id="phone" name="phone" type="number"
                                           autocomplete="off" placeholder="Celular" value="" required/>
                                    @error('phone')
                                    <div class="error-container">
                                        <small class="form-text text-danger">{{$message}}</small>
                                    </div>
                                    @enderror
                                </div>
                                <div class="form-group mb-2 col-md-5 col-sm-12">
                                    <input wire:model="email" type="email" class="form-control" name="email" id="email"
                                           autocomplete="off" value="" placeholder="Correo electronico" required/>
                                    @error('email')
                                    <div class="error-container">
                                        <small class="form-text text-danger">{{$message}}</small>
                                    </div>
                                    @enderror
                                </div>

                                <div class="input-group mb-2 col-md-3 col-sm-12">
                                    <select wire:model="role" class="custom-select" name="role" id="role" required>
                                        <option value="" selected>Seleccione rol...</option>
                                        @foreach($roles as $item)
                                            <option value="{{$item->name}}">{{$item->display_name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                @if($role == "seller" || $role == "technician" || $role == "consumer")
                                    <div class="form-group mb-2 col-md-12 col-sm-12">
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                            <span for="network_operator" class="input-group-text">
                                                <i>
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                         fill="currentColor" class="bi bi-person-video2"
                                                         viewBox="0 0 16 16">
                                                        <path d="M10 9.05a2.5 2.5 0 1 0 0-5 2.5 2.5 0 0 0 0 5Z"/>
                                                        <path
                                                            d="M2 1a2 2 0 0 0-2 2v9a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V3a2 2 0 0 0-2-2H2ZM1 3a1 1 0 0 1 1-1h2v2H1V3Zm4 10V2h9a1 1 0 0 1 1 1v9c0 .285-.12.543-.31.725C14.15 11.494 12.822 10 10 10c-3.037 0-4.345 1.73-4.798 3H5Zm-4-2h3v2H2a1 1 0 0 1-1-1v-1Zm3-1H1V8h3v2Zm0-3H1V5h3v2Z"/>
                                                    </svg>
                                                </i>
                                            </span>
                                            </div>
                                            <input wire:model="network_operator"
                                                   wire:keydown.enter="assignNetworkOperatorFirst()" type="text"
                                                   class="form-control" id="network_operator" autocomplete="off"
                                                   placeholder="Identificacion" required>
                                            <div class="input-group-append">
                                            <span class="input-group-text">
                                                @if($picked)
                                                    <span class="badge badge-success">
                                                        <i>
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="16"
                                                                 height="16" fill="currentColor" class="bi bi-check2"
                                                                 viewBox="0 0 16 16">
                                                                <path
                                                                    d="M13.854 3.646a.5.5 0 0 1 0 .708l-7 7a.5.5 0 0 1-.708 0l-3.5-3.5a.5.5 0 1 1 .708-.708L6.5 10.293l6.646-6.647a.5.5 0 0 1 .708 0z"/>
                                                            </svg>
                                                        </i>
                                                    </span>
                                                @else
                                                    <span class="badge badge-danger">
                                                        <i>
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="16"
                                                                 height="16" fill="currentColor" class="bi bi-x-lg"
                                                                 viewBox="0 0 16 16">
                                                                <path fill-rule="evenodd"
                                                                      d="M13.854 2.146a.5.5 0 0 1 0 .708l-11 11a.5.5 0 0 1-.708-.708l11-11a.5.5 0 0 1 .708 0Z"/>
                                                                <path fill-rule="evenodd"
                                                                      d="M2.146 2.146a.5.5 0 0 0 0 .708l11 11a.5.5 0 0 0 .708-.708l-11-11a.5.5 0 0 0-.708 0Z"/>
                                                            </svg>
                                                        </i>
                                                    </span>
                                                @endif
                                            </span>
                                            </div>
                                        </div>
                                        @error("network_operator")
                                        <div class="error-container">
                                            <small class="form-text text-danger">{{$message}}</small>
                                        </div>
                                        @else
                                            @if(count($network_operators)>0)
                                                @if(!$picked)
                                                    <ul class="dropdown-menu list-search">
                                                        <h6 class="dropdown-header"><b>Seleccione opción</b></h6>
                                                        @foreach($network_operators as $usuario)
                                                            <li class="dropdown-item">
                                                                <a wire:click="assignNetworkOperator('{{ $usuario }}')"
                                                                   type="button">
                                                                    {{ $usuario->name }}
                                                                </a>
                                                            </li>
                                                        @endforeach
                                                    </ul>
                                                @endif
                                            @else
                                                <div class="">
                                                    <small class="form-text text-muted">{{ $messageP }}</small>
                                                </div>
                                            @endif
                                            @enderror
                                    </div>
                                @endif
                                <hr>
                                <div class="text-center">
                                    <button id="editar" type="submit" class="mb-2 py-2 px-4">
                                        <b>
                                            Editar
                                        </b>
                                    </button>
                                    <a href="#" onclick="document.getElementById('confirmar').showModal()">
                                        <button type="button" class="b-danger mb-2 py-2 px-4">
                                            <b>
                                                Eliminar
                                            </b>
                                        </button>
                                    </a>

                                </div>

                            @endif

                        </div>
                    </form>
                </div>
                <dialog wire:ignore.self id="confirmar" class="px-1 py-0 rounded-md ">
                    <div class=" modal-body">
                        <div class="row section-title">
                            <div class="col-12">
                                <h5 class="text-center"><b><span class="azul">¿Desea eliminar usuario?</span></b></h5>
                                <h6 class="text-center"><b><span class="naranja">Se eliminaran todos los registros relacionados</span></b>
                                </h6>
                            </div>
                        </div>
                        <div>
                            <div class="row content py-1">
                                <div class="text-center contenedor-grande justify-content-between">
                                    <button wire:click="eliminar()" class="button py-2 px-2 mx-4"
                                            onclick="document.getElementById('confirmar').close();" type="button"><b>Aceptar</b>
                                    </button>
                                    <button onclick="document.getElementById('confirmar').close();"
                                            class="b-danger button mx-4 py-2 px-2" type="button"><b>Cancelar</b>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </dialog>
            </div>
        </div>
    </section>
</div>



