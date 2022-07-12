@extends('layouts.v1.app')

@section('header')
    <div>
        <section class="top-info">
            <div class="contenedor-grande ">
                <nav class="navbar navbar-expand-lg navbar-custom-coenergia"
                     style="justify-content: space-between;padding: 2px">
                    <a class="navbar-brand" href="/">
                        <img class="img-fluid imagen-logo"
                             src="https://enertedevops.s3.us-east-2.amazonaws.com/images/16517642985208516/1651764298_Coenergia_login.png"
                             alt=""></a>
                    <button class="navbar-toggler" id="button-menu" type="button" data-toggle="collapse"
                            data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                            aria-expanded="false" aria-label="Toggle navigation">
                        <span class="fas fa-bars"></span>
                    </button>

                    <div class=" collapse navbar-collapse" id="navbarSupportedContent">
                        <ul class="navbar-nav mr-auto">

                            <li class="nav-item">

                                @isset(\App\Http\Resources\V1\Menu::getMenuV3()["submenu"])

                                    <ul class="navbar-nav" style="justify-content: left">

                                        @foreach(\App\Http\Resources\V1\Menu::getMenuV3()["submenu"] as $menu)
                                            @include("layouts.menu.v1.menu",["menu"=>$menu])
                                        @endforeach
                                        <form id="logout-form" action="{{ route('logout') }}" method="POST"
                                              class="hidden">
                                            @csrf
                                        </form>
                                    </ul>

                                @endisset

                            </li>
                        </ul>
                        <div class="mt-4 mb-4">
                            @auth                        @include("layouts.menu.v1.profile")

                            @endauth
                        </div>
                    </div>
                </nav>
            </div>
        </section>
        @endsection
        @section('content')
            <section class="login">
                <div class="container pb-2" data-aos="fade-up">
                    <div class="row d-flex justify-content-center">
                        <div class="col-12">
                            <h2 class="text-center"><b><span class="naranja">Inicio de sesion</span> <span
                                        style="color:#093f96"> Coenergia</span></b>
                            </h2>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 mt-5 offset-3 align-items-center">
                    @if (session('status'))
                        <div class="mb-4 font-medium text-sm text-green-600">
                            {{ session('status') }}
                        </div>
                    @endif
                    <form action="{{ route('login') }}" method="post" role="form" class="contenedor-grande">
                        @csrf

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
                                    href="
{{ route('password.request',["subdomain"=>\Illuminate\Support\Facades\Route::input("subdomain")??"coenergia"]) }}">¿Olvidaste
                                    la
                                    contraseña?</a></div>
                            @include("auth.support_button")
                        @endif

                    </form>
                </div>
    </div>
    </div>
    </section>
    </div>
@endsection
