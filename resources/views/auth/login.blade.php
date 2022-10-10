@extends('layouts.v1.app')
@section("footer")
@endsection
<div class="row d-flex justify-content-between" style="height: 100%">

    <div class="col-3 slide-login">
        <a class="navbar-brand" href="/">
            <img class="img-fluid imagen-logo-login"
                 src="{{\App\Http\Resources\V1\Subdomain::getHeaderIcon()}}"
                 alt=""></a>

        <div class="service_list align-content-between">
            @foreach([
                        [
                            "text"=>"Pagar factura",
                            "image_url"=>"assets/images/icons/icons-aom-03.svg"
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
    <div class="col-9 justify-content-center" style="background-color: #f2f2f2">
        <div class="col-6  offset-3 align-middle login-container">
            @if (session('status'))
                <div>
                    {{ session('status') }}
                </div>
            @endif

            <div class="col-md-7 mb-2">
                <p class="login-title"> Conectate</p>
            </div>
            <div class="col-md-12 mb-3">
                <p class="login-subtitle"> Usa el correo electronico y contraseña que te proporcionaron al
                    crear tu cuenta, si olvidaste tu
                    contraseña puedes reestablecerla usando tu correo.</p>
            </div>
            <div class="col-md-12">
                <form action="{{ route('login') }}" method="post" role="form">
                    @csrf
                    <div class="form-group">
                        <label for="email">Correo electrónico</label>
                        <input type="email" class="form-control @error('email') is-invalid @enderror"
                               name="email" value="{{ old('email') }}" autocomplete="email" autofocus required>
                        @error('email')
                        <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="password">Contraseña </label>

                        <a class="login-forgot-pass" href="
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
                    <div class="text-right">
                        <button class="login-button" type="submit">Ingresar</button>
                    </div>

                    @include("auth.support_button")
                </form>
            </div>
        </div>
    </div>
</div>





