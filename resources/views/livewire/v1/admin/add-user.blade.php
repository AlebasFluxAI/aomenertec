<div>
    <!-- Theme style -->
    <section class="login">
        <div class="container">
            <div class="section-title">
                <h2 class="text-center p3"><b><span class="naranja">Agregar </span> <span
                            class="azul">Usuario</span></b></h2>
            </div>
            @if (session()->has('message'))
                <div class="alert alert-success">
                    {{ session('message') }}
                </div>
            @endif
            <div class="contenedor-grande">
                <div class="row content pt-3">
                    <form wire:submit.prevent="save" id="formulario" class="needs-validation" role="form">
                        <div class="row ">
                            <div class="form-group mb-2 col-md-4 col-sm-12">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                    <span class="input-group-text">
                                        <i>
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                 fill="currentColor" class="bi bi-person-video2" viewBox="0 0 16 16">
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
                                    <option value="" selected>Seleccione role...</option>
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
                                               placeholder="Identificación" required>
                                        <div class="input-group-append">
                                        <span class="input-group-text">
                                            @if($picked)
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
                                                <small class="form-text text-muted">{{$message}}</small>
                                            </div>
                                        @endif
                                        @enderror
                                </div>
                            @endif
                            <hr>
                            <div class="text-center">
                                <button id="add" type="submit" class="mb-2 py-2 px-4">
                                    <b>
                                        Agregar
                                    </b>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
</div>


