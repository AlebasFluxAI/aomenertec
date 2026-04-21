@extends('layouts.v1.app')

@section('content')
    {{-- =========================================================
         FluxAI Login — alineado con la app móvil (fluxai-app).
         Paleta corporativa + Inter + card redondeada + labels en
         mayusculas sobrias + inputs con icono + boton azul solido.
    ========================================================== --}}
    <style>
        .flux-login-bg {
            min-height: calc(100vh - 2rem);
            background:
                radial-gradient(1200px 600px at 0% 0%, rgba(12, 98, 220, 0.08) 0%, transparent 55%),
                radial-gradient(1200px 600px at 100% 100%, rgba(0, 199, 129, 0.08) 0%, transparent 55%),
                linear-gradient(180deg, #F8FBFF 0%, #F4FBF8 100%);
            font-family: var(--flux-body-font, 'Inter', system-ui, sans-serif);
        }
        .flux-login-card {
            background: #fff;
            border: 1px solid var(--flux-border, #E4E9F0);
            border-radius: 18px;
            box-shadow: 0 20px 60px -30px rgba(0, 68, 164, 0.25);
            padding: 2rem 1.75rem;
        }
        .flux-login-tagline {
            color: var(--flux-muted, #7A869A);
            font-size: 0.95rem;
            letter-spacing: 0.02em;
            font-weight: 400;
        }
        .flux-login-label {
            display: block;
            text-transform: uppercase;
            letter-spacing: 0.12em;
            font-size: 0.72rem;
            font-weight: 600;
            color: #7A869A;
            margin-bottom: 0.45rem;
        }
        .flux-login-input-wrap {
            position: relative;
        }
        .flux-login-input-wrap .flux-login-icon {
            position: absolute;
            top: 50%; left: 14px; transform: translateY(-50%);
            color: #94A3B8;
            font-size: 0.95rem;
            pointer-events: none;
        }
        .flux-login-input-wrap .flux-login-icon-right {
            position: absolute;
            top: 50%; right: 14px; transform: translateY(-50%);
            color: var(--flux-secondary, #0C62DC);
            font-size: 1rem;
            background: transparent;
            border: none;
            cursor: pointer;
            padding: 0;
            line-height: 1;
        }
        .flux-login-input {
            width: 100%;
            padding: 0.85rem 1rem 0.85rem 2.6rem;
            border: 1px solid var(--flux-border, #E4E9F0);
            border-radius: 12px;
            background: #fff;
            font-family: var(--flux-body-font, 'Inter', system-ui, sans-serif);
            font-size: 0.92rem;
            color: var(--flux-graphite, #2D3748);
            transition: border-color .18s ease, box-shadow .18s ease;
            outline: none;
        }
        .flux-login-input::placeholder { color: #B0BBCC; }
        .flux-login-input:focus {
            border-color: var(--flux-secondary, #0C62DC);
            box-shadow: 0 0 0 4px rgba(12, 98, 220, 0.10);
        }
        .flux-login-input--error {
            border-color: #E53935;
            box-shadow: 0 0 0 4px rgba(229, 57, 53, 0.08);
        }
        .flux-login-forgot {
            display: block;
            text-align: right;
            color: var(--flux-secondary, #0C62DC);
            font-size: 0.82rem;
            font-weight: 500;
            text-decoration: none;
            margin-top: 0.5rem;
        }
        .flux-login-forgot:hover { color: var(--flux-primary, #0044A4); }

        .flux-login-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            width: 100%;
            padding: 0.95rem 1rem;
            background: var(--flux-primary, #0044A4);
            color: #fff;
            border: none;
            border-radius: 12px;
            font-family: var(--flux-body-font, 'Inter', system-ui, sans-serif);
            font-size: 0.95rem;
            font-weight: 600;
            letter-spacing: 0.01em;
            cursor: pointer;
            transition: background-color .2s ease, transform .1s ease, box-shadow .2s ease;
            box-shadow: 0 8px 20px -10px rgba(0, 68, 164, 0.45);
        }
        .flux-login-btn:hover {
            background: var(--flux-primary-dark, #003380);
            transform: translateY(-1px);
            box-shadow: 0 12px 26px -12px rgba(0, 68, 164, 0.55);
        }

        .flux-login-divider {
            display: flex; align-items: center; gap: 0.75rem;
            color: #94A3B8;
            font-size: 0.72rem;
            text-transform: uppercase;
            letter-spacing: 0.14em;
            margin: 1.75rem 0 1rem;
        }
        .flux-login-divider::before,
        .flux-login-divider::after {
            content: "";
            flex: 1;
            height: 1px;
            background: linear-gradient(90deg, transparent, #E4E9F0, transparent);
        }

        .flux-login-footer {
            margin-top: 1.5rem;
            text-align: center;
            color: #94A3B8;
            font-size: 0.75rem;
            letter-spacing: 0.12em;
            text-transform: uppercase;
        }

        .flux-login-alert {
            border-radius: 12px;
            padding: 0.8rem 1rem;
            font-size: 0.85rem;
        }
        .flux-login-alert--ok {
            background: rgba(0, 199, 129, 0.08);
            border: 1px solid rgba(0, 199, 129, 0.25);
            color: #0A8D5B;
        }
        .flux-login-alert--err {
            background: rgba(229, 57, 53, 0.06);
            border: 1px solid rgba(229, 57, 53, 0.25);
            color: #C62828;
        }

        @media (max-width: 480px) {
            .flux-login-card { padding: 1.5rem 1.25rem; border-radius: 14px; }
        }
    </style>

    <div class="flux-login-bg flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-md w-full">
            {{-- Logo + tagline --}}
            <div class="text-center" style="margin-bottom: 1.75rem;">
                <a href="/" class="inline-block">
                    <img class="mx-auto h-20 w-auto imagen-logo-login"
                         src="{{\App\Http\Resources\V1\Subdomain::getHeaderIcon()}}"
                         alt="FluxAI">
                </a>
                <p class="flux-login-tagline" style="margin-top: 0.75rem;">
                    Gestión profesional de la energía
                </p>
            </div>

            {{-- Login card --}}
            <div class="flux-login-card">
                @if (session('status'))
                    <div class="flux-login-alert flux-login-alert--ok" style="margin-bottom: 1rem;">
                        <i class="fas fa-check-circle" style="margin-right: 0.4rem;"></i>
                        {{ session('status') }}
                    </div>
                @endif

                <form action="{{ route('login') }}" method="post" role="form"
                      x-data="{ showPassword: false }">
                    @csrf

                    {{-- Email --}}
                    <div style="margin-bottom: 1.15rem;">
                        <label for="email" class="flux-login-label">Correo</label>
                        <div class="flux-login-input-wrap">
                            <i class="far fa-envelope flux-login-icon"></i>
                            <input id="email"
                                   name="email"
                                   type="email"
                                   value="{{ old('email') }}"
                                   autocomplete="email"
                                   required
                                   autofocus
                                   placeholder="tecnico@fluxai.com"
                                   class="flux-login-input @error('email') flux-login-input--error @enderror">
                        </div>
                        @error('email')
                            <p style="color: #C62828; font-size: 0.8rem; margin-top: 0.4rem;">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Password --}}
                    <div>
                        <label for="password" class="flux-login-label">Contraseña</label>
                        <div class="flux-login-input-wrap">
                            <i class="fas fa-lock flux-login-icon"></i>
                            <input id="password"
                                   name="password"
                                   :type="showPassword ? 'text' : 'password'"
                                   autocomplete="current-password"
                                   required
                                   placeholder="••••••••"
                                   class="flux-login-input @error('password') flux-login-input--error @enderror">
                            <button type="button"
                                    class="flux-login-icon-right"
                                    @click="showPassword = !showPassword"
                                    :aria-label="showPassword ? 'Ocultar contraseña' : 'Mostrar contraseña'">
                                <i :class="showPassword ? 'far fa-eye-slash' : 'far fa-eye'"></i>
                            </button>
                        </div>
                        @error('password')
                            <p style="color: #C62828; font-size: 0.8rem; margin-top: 0.4rem;">{{ $message }}</p>
                        @enderror

                        <a href="{{ route('password.reset.form') }}" class="flux-login-forgot">
                            ¿Olvidaste tu contraseña?
                        </a>
                    </div>

                    {{-- Submit --}}
                    <div style="margin-top: 1.6rem;">
                        <button type="submit" class="flux-login-btn">
                            Iniciar sesión
                        </button>
                    </div>
                </form>
            </div>

            {{-- Footer --}}
            <div class="flux-login-footer">
                FluxAI Web · {{ date('Y') }}
            </div>
        </div>
    </div>
@endsection
