<nav class="navbar navbar-expand-sm ">
    <ul class="navbar-nav">

        <li class="nav-item dropdown">
            @livewire('v1.admin.user.notification.notification-header')
        </li>
        <li class="nav-item dropdown">
            <a class="btn btn-lg badge badge-light rounded-full"
               data-toggle="tooltip" data-placement="{{$tooltip_position??"top"}}" title="Mi    Perfil"

               href="{{ route('administrar.v1.perfil') }}">
                <i style="color:teal" class="fa-solid fa-user profile-icon"></i>
            </a>

        </li>

    </ul>
</nav>
