@extends('layouts.v1.app')

@section('header')
    <section class="top-info" style="background-color: #f2f2f2; height: 100%;">
        <div class="flex-column">
            <nav class="navbar navbar-expand-lg d-flex justify-content-center"
                 style="{{\App\Http\Resources\V1\Subdomain::getHeaderColor()}}">
                <a class="navbar-brand" href="/">
                    <img class="img-fluid imagen-logo"
                         src="{{\App\Http\Resources\V1\Subdomain::getHeaderIcon()}}"
                         alt=""></a>
                <button class="navbar-toggler" id="button-menu" type="button" data-toggle="collapse"
                        data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                        aria-expanded="false" aria-label="Toggle navigation">
                    <span class="fas fa-bars"></span>
                </button>

                <!-- <div class=" collapse navbar-collapse" id="navbarSupportedContent"
                     style="width: 0px;">
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
@auth
                    @include("layouts.menu.v1.profile")

                @endauth
                </div>
            </div> -->
            </nav>
        </div>
    </section>
@endsection
@section('content')
    <div class="col-md-4"
         style="margin:auto;padding: 20px;border-radius: 15px;
         background-color: #f2f2f2; -webkit-box-shadow: 5px 5px 14px 5px rgb(0 0 0 / 23%);
         box-shadow: 5px 5px 14px 5px rgb(0 0 0 / 23%);">
        @if (session('status'))
            <div>
                {{ session('status') }}
            </div>
        @endif

        <div class="col-12 mb-2">
            <h3 class="login-title text-lg pb-4 text-center font-weight-bold" style="color: #1c9599;">Conectate</h3>
        </div>
        <div class="col-md-12 mb-3">
            <p class="login-subtitle leading-tight text-slate-500 text-base"> Usa el correo electronico y contraseña que
                te proporcionaron al
                crear tu cuenta, si olvidaste tu
                contraseña puedes reestablecerla usando tu correo.</p>
        </div>
        <div class="col-md-12">
            <form action="{{ route('login') }}" method="post" role="form">
                @csrf
                <div class="form-group">
                    <label class="block text-sm text-slate-400" for="email">Correo electrónico</label>
                    <input type="email" class="form-control @error('email') is-invalid @enderror"
                           name="email" value="{{ old('email') }}" autocomplete="email" autofocus required>
                    @error('email')
                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                    @enderror
                </div>
                <div class="form-group">
                    <label class="block text-sm text-slate-400" for="password">Contraseña </label>
                    <a class="login-forgot-pass" style="font-size: 12px; margin-left: 10px;" href="
                                    {{route('password.reset.form')}}
                            ">
                        ¿La olvidaste?</a>
                    <input type="password"
                           class="form-control @error('password') is-invalid @enderror"
                           name="password" autocomplete="current-password" required>
                    @error('password')
                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                    @enderror
                </div>
                <div class="flex justify-between">
                    @include("auth.support_button")
                    <button class="login-button drop-shadow-xl hover:drop-shadow-none rounded"
                            style="color: white; padding: 6px 12px; margin: 3px; background-color: var(--style_primary); transition: 0.4s; font-size: 0.9rem;"
                            type="submit">Ingresar
                    </button>
                </div>

            </form>
        </div>
    </div>

@endsection
