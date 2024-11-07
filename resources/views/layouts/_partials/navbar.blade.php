<nav class="sb-topnav navbar navbar-expand navbar-dark">
    <!-- Navbar Brand dengan Logo -->
    <a class="navbar-brand ps-3 d-flex align-items-center" href="{{ url('/') }}">
        <img src="/img/logo.png" alt="Logo" height="40" class="me-2 d-none d-md-block">
        <img src="/img/logo.png" alt="Logo" height="30" class="me-2 d-block d-md-none">
        <span class="fs-5 fw-bold d-none d-md-block">SIMLAB ELEKTRO</span>
        <span class="fs-6 fw-bold d-block d-md-none">SIMLAB</span>
    </a>
    
    <!-- Sidebar Toggle dengan margin yang lebih kecil -->
    <button class="btn btn-link btn-sm ms-3" id="sidebarToggle" href="#!">
        <i class="fas fa-bars"></i>
    </button>
    
    <!-- Spacer untuk memberikan jarak -->
    <div class="flex-grow-1"></div>
    
    <!-- Navbar-->
    <ul class="navbar-nav me-3 d-flex align-items-center">
        <!-- User Profile Dropdown -->
        <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle d-flex align-items-center" id="navbarDropdown" href="#" 
               role="button" data-bs-toggle="dropdown" aria-expanded="false">
                <div class="d-flex align-items-center">
                    <div class="ms-2 d-none d-md-block">
                        <div class="fw-bold text-white" style="font-size: 0.9rem;">
                            {{ Auth::user()->name }}
                        </div>
                    </div>
                </div>
            </a>
            <ul class="dropdown-menu dropdown-menu-end shadow-sm" aria-labelledby="navbarDropdown">
                <li>
                    <div class="dropdown-item-text d-block d-md-none">
                        <div class="fw-bold">{{ Auth::user()->name }}</div>
                    </div>
                </li>
                <li><hr class="dropdown-divider d-block d-md-none"></li>
                <li>
                    <a class="dropdown-item" href="{{ route('profile.edit') }}">
                        <i class="fas fa-user-circle fa-fw me-2"></i>Profile
                    </a>
                </li>
                <li><hr class="dropdown-divider" /></li>
                <li>
                    <form method="POST" action="{{ route('logout') }}" onclick="clearWelcomeState()">
                        @csrf
                        <button type="submit" class="dropdown-item text-danger">
                            <i class="fas fa-sign-out-alt fa-fw me-2"></i>Logout
                        </button>
                    </form>
                </li>
            </ul>
        </li>
    </ul>
</nav>

<style>
/* Tambahan CSS untuk animasi dan responsivitas */
.navbar-brand {
    transition: all 0.3s ease;
}

.dropdown-menu {
    border: none;
    box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
}

.dropdown-item {
    padding: 0.5rem 1.5rem;
    transition: all 0.2s ease;
}

.dropdown-item:hover {
    background-color: #f8f9fa;
    padding-left: 1.75rem;
}

.dropdown-item.text-danger:hover {
    background-color: #dc3545;
    color: white !important;
}

@media (max-width: 768px) {
    .navbar-brand {
        font-size: 1.1rem;
    }
    
    .nav-link {
        padding: 0.5rem 0.75rem;
    }
}
</style>
