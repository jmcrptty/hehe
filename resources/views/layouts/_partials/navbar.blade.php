<nav class="sb-topnav navbar navbar-expand navbar-dark">
    <!-- Navbar Brand dengan Logo -->
    <a class="navbar-brand ps-3 d-flex align-items-center" href="{{ url('/') }}">
        <img src="/img/logo.png" alt="Logo" height="40" class="me-3">
        <span class="fs-5 fw-bold">SIMLAB ELEKTRO</span>
    </a>
    
    <!-- Spacer untuk memberikan jarak -->
    <div class="me-4"></div>
    
    <!-- Sidebar Toggle-->
    <button class="btn btn-link btn-sm order-1 order-lg-0 me-4 me-lg-0" id="sidebarToggle" href="#!">
        <i class="fas fa-bars"></i>
    </button>
    
    <!-- Navbar-->
    <ul class="navbar-nav ms-auto me-3">
        <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" id="navbarDropdown" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                <i class="fas fa-user fa-fw"></i> {{ Auth::user()->name }}
            </a>
            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                <li><a class="dropdown-item" href="{{ route('profile.edit') }}">Profile</a></li>
                <li><hr class="dropdown-divider" /></li>
                <li>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="dropdown-item">Logout</button>
                    </form>
                </li>
            </ul>
        </li>
    </ul>
</nav>
