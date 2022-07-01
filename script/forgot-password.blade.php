@extends("layouts.v1.app")
@include("layouts.menu.v1.header_menu")
<x-jet-authentication-card>
    <x-slot name="logo">
        <x-jet-authentication-card-logo/>
    </x-slot>

    <div class="mb-1 text-sm text-blue-700">
        {{ __("login.forgot password") }}
    </div>

    @if (session('status'))
        <div class="mb-4 font-medium text-sm text-green-600">
            {{ session('status') }}
        </div>
    @endif

    <x-jet-validation-errors class="mb-4"/>

    <form method="POST" action="{{ route('password.email') }}">
        @csrf

        <div class="block">
            <x-jet-label for="email" value="{{ __('login.email') }}"/>
            <x-jet-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required
                         autofocus/>
        </div>

        <div class="flex items-center justify-end mt-4">
            <x-jet-button>
                {{ __('login.forgot password button') }}
            </x-jet-button>
        </div>
    </form>
</x-jet-authentication-card>
