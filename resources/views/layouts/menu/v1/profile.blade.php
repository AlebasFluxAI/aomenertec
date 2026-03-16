<div class="flex gap-2">

    {{-- Item 1: Notificaciones (contador dinámico, redirige a notificaciones) --}}
    <div class="flex-1 min-w-[56px]">
        @livewire('v1.admin.user.notification.notification-header')
    </div>

    {{-- Item 2 (condicional): Cambiar de rol --}}
    @if(\Illuminate\Support\Facades\Request::session()->get(\App\Models\V1\User::SESSION_MULTI_ROLE) == true)
        <a href="{{ route('administrar.v1.seleccionar_role') }}"
           class="flex-1 min-w-[56px] border border-ui-border text-ui-muted rounded-xl flex items-center justify-center py-2 hover:bg-ui-hover transition-colors"
           style="text-decoration: none;"
           title="Cambiar rol">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                <path d="M8 3L4 7l4 4"></path>
                <path d="M4 7h16"></path>
                <path d="M16 21l4-4-4-4"></path>
                <path d="M20 17H4"></path>
            </svg>
        </a>
    @endif

    {{-- Item 2 o 3: Mi Perfil --}}
    <a href="{{ route('administrar.v1.perfil') }}"
       class="flex-1 min-w-[56px] border border-ui-border text-ui-muted rounded-xl flex items-center justify-center py-2 hover:bg-ui-hover transition-colors"
       style="text-decoration: none;"
       title="Mi Perfil">
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
            <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"></path>
            <path d="M16 3.128a4 4 0 0 1 0 7.744"></path>
            <path d="M22 21v-2a4 4 0 0 0-3-3.87"></path>
            <circle cx="9" cy="7" r="4"></circle>
        </svg>
    </a>

    {{-- Cerrar sesión --}}
    <form method="POST" action="{{ route('logout') }}" class="flex-1 min-w-[56px] hidden sm:flex">
        @csrf
        <button type="submit"
                class="w-full h-full border border-ui-border text-ui-muted rounded-xl flex items-center justify-center py-2 hover:bg-red-50 hover:text-red-600 hover:border-red-200 transition-colors"
                style="background: transparent; cursor: pointer;"
                title="Cerrar sesión">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
                <polyline points="16 17 21 12 16 7"></polyline>
                <line x1="21" y1="12" x2="9" y2="12"></line>
            </svg>
        </button>
    </form>

</div>
