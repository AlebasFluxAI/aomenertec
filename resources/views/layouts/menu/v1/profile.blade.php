<nav class="navbar navbar-expand-sm ">
    <ul class="navbar-nav">

        <li class="nav-item dropdown">
            @livewire('v1.admin.user.notification.notification-header')
        </li>

        @if(\Illuminate\Support\Facades\Request::session()->get(\App\Models\V1\User::SESSION_MULTI_ROLE)==true)
            <li class="nav-item dropdown">
                <a class="btn btn-lg badge badge-light rounded-full"
                   data-toggle="tooltip" data-placement="{{$tooltip_position??"top"}}" title="Cambiar de rol"

                   href="{{ route('administrar.v1.seleccionar_role') }}">
                    <i style="color:teal" class="fas fa-arrow-right-arrow-left"></i>
                </a>
            </li>
        @endif
        <li class="nav-item dropdown">
            <a class="btn btn-lg badge badge-light rounded-full"
               data-toggle="tooltip" data-placement="{{$tooltip_position??"top"}}" title="Mi    Perfil"

               href="{{ route('administrar.v1.perfil') }}">
                <i style="color:teal" class="fa-solid fa-user profile-icon"></i>
            </a>

        </li>

    </ul>
</nav>
