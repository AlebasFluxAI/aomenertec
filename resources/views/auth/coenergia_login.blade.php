@extends('layouts.v1.app')

@section('content')
    <div>
        <section class="top-info">
            <div class="container">
                <div class="contenedor-grande">
                    <div class="col-12">
                        <nav class="navbar navbar-expand-lg">
                            <div class="container-fluid">
                                <a class="navbar-brand" href="/"><img class="imagen-logo"
                                                                      src="https://enertedevops.s3.us-east-2.amazonaws.com/images/16517642985208516/1651764298_Coenergia_login.png"
                                                                      alt=""></a>
                            </div>
                        </nav>
                    </div>
                </div>
            </div>
        </section>
        <hr>
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
                    @endif

                </form>
            </div>
    </div>
    </div>
    </section>
    </div>
@endsection
