@extends('layouts.v1.app')

@section('content')
    <div>
        <section class="top-info">
            <div class="container">
                <div class="contenedor-grande">
                    <div class="col-12">
                        <nav class="navbar navbar-expand-lg navbar-light">
                            <div class="container-fluid">
                                <a class="navbar-brand" href="/"><img class="img-fluid imagen-logo"
                                                                      src="https://aom.enerteclatam.com/images/logo-horizontal.svg"
                                                                      alt=""></a>
                            </div>
                        </nav>
                    </div>
                </div>
            </div>
        </section>
        <section class="login">
            <div class="container pb-2" data-aos="fade-up">
                <div class="row d-flex justify-content-center">
                    <div class="col-12">
                        <h2 class="text-center"><b><span class="naranja">Servicios</span> <span class="azul"> AOM</span></b>
                        </h2>
                    </div>
                </div>
            </div>
            <br>
            <div class="container " data-aos="fade-up">
                <div class="row d-flex justify-content-center">
                    <div class="col-lg-8">
                        <div class="row d-flex mt-2 justify-content-center">
                            @include("partials.v1.home_card",[
                            "tittle"=>"Pagar factura",
                            "image_alt"=>"Gestión de proyectos energéticos y de telecomunicaciones",
                            "image_url"=>"assets/images/icons/icons-aom-03.svg"
                            ])

                            @include("partials.v1.home_card",[
                            "tittle"=>"Historial consumo",
                            "image_alt"=>"Gestión de proyectos energéticos y de telecomunicaciones",
                            "image_url"=>"assets/images/icons/icons-aom-10.svg"
                            ])

                            @include("partials.v1.home_card",[
                            "tittle"=>"Telemetría",
                            "image_alt"=>"Gestión de proyectos energéticos y de telecomunicaciones",
                            "image_url"=>"assets/images/icons/icons-aom-06.svg"
                            ])

                            @include("partials.v1.home_card",[
                           "tittle"=>"Recarga online",
                           "redirect"=>"guest.add-purchase",
                           "image_alt"=>"Gestión de proyectos energéticos y de telecomunicaciones",
                           "image_url"=>"assets/images/icons/icons-aom-03.svg"
                           ])
                            @include("partials.v1.home_card",[
                           "tittle"=>"Historial recargas",
                           "image_alt"=>"Gestión de proyectos energéticos y de telecomunicaciones",
                           "image_url"=>"assets/images/icons/icons-aom-04.svg"
                           ])
                            @include("partials.v1.home_card",[
                           "tittle"=>"Deudas",
                           "image_alt"=>"Gestión de proyectos energéticos y de telecomunicaciones",
                           "image_url"=>"assets/images/icons/icons-aom-07.svg"
                           ])

                        </div>
                    </div>
                    <div class="col-lg-4 mt-5 mt-lg-0 d-flex align-items-center">
                        @if (session('status'))
                            <div class="mb-4 font-medium text-sm text-green-600">
                                {{ session('status') }}
                            </div>
                        @endif
                        <form action="{{ route('login') }}" method="post" role="form" class="contenedor-grande">
                            @csrf
                            <h2 class="text-center p3"><b><span class="naranja">Iniciar</span> <span
                                        class="azul">Sesión</span></b></h2>
                            <hr>

                            <div class="form-group">
                                <label for="email">{{ __('E-MAIL') }}</label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror"
                                       name="email" value="{{ old('email') }}" autocomplete="email" autofocus required>
                                @error('email')
                                <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="password">{{ __('CONTRASEÑA') }}</label>
                                <input type="password" class="form-control @error('password') is-invalid @enderror"
                                       name="password" autocomplete="current-password" required>
                                @error('password')
                                <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="text-center">
                                <button type="submit">Ingresar</button>
                            </div>
                            @if (Route::has('password.request'))
                                <div class="text-center"><a
                                        href="{{ route('password.request',["subdomain"=>\Illuminate\Support\Facades\Route::input("subdomain")??"enertec"]) }}">¿Olvidaste
                                        la
                                        contraseña?</a></div>
                            @endif
                            @include("auth.support_button")
                        </form>

                    </div>

                </div>
            </div>
        </section>
    </div>
@endsection
