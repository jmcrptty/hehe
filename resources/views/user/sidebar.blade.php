<div id="layoutSidenav_nav">
    <nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
        <div class="sb-sidenav-menu">
            <div class="nav">
                <div class="sb-sidenav-menu-heading">Menu Utama</div>
                <a class="nav-link" href="{{ route('user.dashboard') }}">
                    <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                    Dashboard
                </a>
                
                <div class="sb-sidenav-menu-heading">Peminjaman</div>
                <a class="nav-link" href="{{ route('InformasiInventaris') }}">
                    <div class="sb-nav-link-icon"><i class="fas fa-box"></i></div>
                    Informasi Inventaris
                </a>
                
                <a class="nav-link" href="{{ route('PeminjamanInventaris') }}">
                    <div class="sb-nav-link-icon"><i class="fas fa-hand-holding"></i></div>
                    Peminjaman Barang
                </a>
                
                <div class="sb-sidenav-menu-heading">Akun</div>
                <a class="nav-link" href="{{ route('profile.edit') }}">
                    <div class="sb-nav-link-icon"><i class="fas fa-user"></i></div>
                    Profil Saya
                </a>
            </div>
        </div>
    </nav>
</div>