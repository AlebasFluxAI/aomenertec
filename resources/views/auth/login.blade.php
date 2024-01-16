@extends('layouts.v1.app')
@section("footer")
@endsection

<div class="row d-flex justify-content-between">

    <div class="col-12 slide-login flex justify-center">
        <a class="navbar-brand col-md-2 col-sm-12" href="/">
            <img class="img-fluid imagen-logo-login"
                 src="{{\App\Http\Resources\V1\Subdomain::getHeaderIcon()}}"
                 alt="">
        </a>
    </div>
    <div class="col-12 d-flex justify-content-center">
        <div class="col-sm-10 col-md-4 login-container">
            @if (session('status'))
                <div>
                    {{ session('status') }}
                </div>
            @endif

            <div class="col-12 mb-2">
                <h3 class="login-title text-lg pb-4 text-center" style="color: #1c9599;"> Conectate</h3>
            </div>
            <div class="col-md-12 mb-3">
                <p class="login-subtitle leading-tight text-slate-500 text-base"> Usa el correo electronico y contraseña
                    que te proporcionaron al crear tu cuenta, si olvidaste tu contraseña puedes reestablecerla usando tu
                    correo.</p>
            </div>
            <div class="col-md-12">
                <form action="{{ route('login') }}" method="post" role="form">
                    @csrf
                    <div class="form-group">
                        <label class="block text-sm text-slate-400" for="email">Correo electrónico</label>
                        <input type="email"
                               class="border-slate-200 placeholder-slate-400 contrast-more:border-slate-400 contrast-more:placeholder-slate-500 form-control @error('email') is-invalid @enderror"
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
                        @include("auth.wiki_button")
                        <button class="login-button drop-shadow-xl hover:drop-shadow-none rounded" type="submit">
                            Ingresar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="container">
        <div class="row mb-5 pb-5">
            <div class="col-12">
                <h1 class="text-center pt-4 text-bold" style="color: #1c9599;">Servicios</h1>
            </div>
            <div class="col-12" style="display: flex; flex-wrap: wrap;">
                @foreach([
                            [
                                "text"=>"Pagar factura",
                                "image_url"=>"assets/images/icons/icons-aom-03.svg",
                                "card_route"=>"guest.invoice-payment"

                            ],
                            [
                                "text"=>"Historial de consumo",
                                "image_url"=>"assets/images/icons/icons-aom-10.svg"
                            ],
                            [
                                "text"=>"Telemetria",
                                "image_url"=>"assets/images/icons/icons-aom-06.svg"
                            ],
                            [
                                "text"=>"Recarga online",
                                "image_url"=>"assets/images/icons/icons-aom-03.svg"
                            ],
                            [
                                "text"=>"Historial de recargas",
                                "image_url"=>"assets/images/icons/icons-aom-04.svg"
                            ],
                            [
                                "text"=>"Deudas",
                                "image_url"=>"assets/images/icons/icons-aom-07.svg"
                            ]
                        ] as $item)
                    @include("auth.service_item",$item)
                @endforeach

            </div>
        </div>
    </div>
</div>
