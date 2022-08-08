@extends("layouts.v1.app")
<x-slot name="logo">
    <x-jet-authentication-card-logo/>
</x-slot>
@include("layouts.menu.v1.header_menu_password")
<x-jet-validation-errors class="mb-4"/>
<div class="col-6 offset-3 mt-8 bg-light p-5">

    <h3>Reiniciar contraseña</h3>
    <hr>

    <br>
    <form method="POST"
          action="{{ route('password.update',["subdomain"=>\Illuminate\Support\Facades\Route::input("subdomain")??\App\Http\Resources\V1\Subdomain::SUBDOMAIN_DEFAULT]) }}">
        @csrf

        <input type="hidden" name="token" value="{{ $request->route('token') }}">

        <div class="block">
            <x-jet-label for="email" value="{{ __('Email') }}"/>
            <x-jet-input id="email" class="block mt-1 w-full" type="email" name="email"
                         :value="old('email', $request->email)" required autofocus/>
        </div>

        <div class="mt-4">
            <x-jet-label for="password" value="Nueva contraseña"/>
            <x-jet-input id="password" class="block mt-1 w-full" type="password" name="password" required
                         autocomplete="new-password"/>
        </div>

        <div class="mt-4">
            <x-jet-label for="password_confirmation" value="Confirmar contraseña"/>
            <x-jet-input id="password_confirmation" class="block mt-1 w-full" type="password"
                         name="password_confirmation" required autocomplete="new-password"/>
        </div>

        <div class="flex items-center justify-end mt-4">
            <x-jet-button>
                Confirmar
            </x-jet-button>
        </div>
    </form>
</div>
