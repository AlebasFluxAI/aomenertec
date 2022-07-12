@extends("layouts.v1.app")
@include("layouts.menu.v1.header_menu_password")
@section("content")
    <br>

    <div class="col-md-12 mt-8">
        <div class="col-md-6 offset-3">

            <form method="POST" action="{{ route('password.email') }}">
                @csrf

                <div class="text-left">
                    <label for="email">{{ __('login.email') }}</label>
                    <li>
                        Ingrese el correo de registro para reestablecer la contraseña.
                    </li>
                    <br>
                    <input id="email" class="form-control" type="email" name="email" :value="old('email')" required
                           autofocus/>
                </div>
                @if (session('status'))
                    <div class="font-medium text-sm text-green-600">
                        {{ session('status') }}
                    </div>
                @endif
                <div class="flex items-center justify-end mt-4">
                    <button class="btn btn-info">
                        {{ __('login.forgot password button') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
