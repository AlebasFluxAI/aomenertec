@extends('layouts.v1.app')

@section('content')
    <div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-gray-50 to-gray-100 py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-md w-full space-y-8">
            {{-- Logo --}}
            <div class="text-center">
                <a href="/" class="inline-block">
                    <img class="mx-auto h-20 w-auto imagen-logo-login" 
                         src="{{\App\Http\Resources\V1\Subdomain::getHeaderIcon()}}" 
                         alt="FluxAI Logo">
                </a>
            </div>

            {{-- Login Card --}}
            <div class="bg-white rounded-2xl shadow-xl p-8 space-y-6">
                {{-- Title --}}
                <div class="text-center">
                    <h2 class="text-3xl font-bold text-gray-900 tracking-tight">
                        Bienvenido
                    </h2>
                    <p class="mt-2 text-sm text-gray-600">
                        Ingresa tus credenciales para continuar
                    </p>
                </div>

                {{-- Status Messages --}}
                @if (session('status'))
                    <div class="rounded-md bg-green-50 p-4">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-green-400" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-medium text-green-800">{{ session('status') }}</p>
                            </div>
                        </div>
                    </div>
                @endif

                {{-- Form --}}
                <form action="{{ route('login') }}" method="post" class="space-y-6" role="form">
                    @csrf

                    {{-- Email Field --}}
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700">
                            Correo electrónico
                        </label>
                        <div class="mt-1">
                            <input id="email" 
                                   name="email" 
                                   type="email" 
                                   value="{{ old('email') }}"
                                   autocomplete="email" 
                                   required 
                                   autofocus
                                   class="appearance-none block w-full px-3 py-3 border border-gray-300 rounded-lg placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-flux-primary focus:border-transparent transition duration-150 ease-in-out sm:text-sm @error('email') border-red-500 @enderror"
                                   placeholder="tu@email.com">
                        </div>
                        @error('email')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Password Field --}}
                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700">
                            Contraseña
                        </label>
                        <div class="mt-1">
                            <input id="password" 
                                   name="password" 
                                   type="password" 
                                   autocomplete="current-password" 
                                   required
                                   class="appearance-none block w-full px-3 py-3 border border-gray-300 rounded-lg placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-flux-primary focus:border-transparent transition duration-150 ease-in-out sm:text-sm @error('password') border-red-500 @enderror"
                                   placeholder="••••••••">
                        </div>
                        @error('password')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Forgot Password Link --}}
                    <div class="flex items-center justify-end">
                        <a href="{{ route('password.reset.form') }}" 
                           class="text-sm font-medium text-flux-primary hover:text-flux-secondary transition-colors">
                            ¿Olvidaste tu contraseña?
                        </a>
                    </div>

                    {{-- Submit Button --}}
                    <div>
                        <button type="submit" 
                                class="w-full flex justify-center py-3 px-4 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-flux-primary hover:bg-flux-secondary focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-flux-primary transition-all duration-200 transform hover:scale-[1.02]">
                            <span class="mr-2">Ingresar</span>
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                            </svg>
                        </button>
                    </div>
                </form>
            </div>

            {{-- Footer --}}
            <div class="text-center">
                <p class="text-xs text-gray-500">
                    FluxAI &copy; {{ date('Y') }} - Todos los derechos reservados
                </p>
            </div>
        </div>
    </div>
@endsection
