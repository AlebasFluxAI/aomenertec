<nav class="navbar navbar-expand-sm notification-nav">
    <ul class="navbar-nav">

        <li class="nav-item dropdown">
            <form id="logout-form" action="{{ route('logout') }}" method="POST">
                <button type="submit" class="btn btn-lg">
                    <i class="fa-solid fa-right-from-bracket"></i>
                </button>
                {{ csrf_field() }}
            </form>
        </li>

    </ul>
</nav>
