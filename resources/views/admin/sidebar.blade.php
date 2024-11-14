<div id="layoutSidenav_nav">
    <nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
        <div class="sb-sidenav-menu">
            <div class="nav">
                <div class="sb-sidenav-menu-heading">Core</div>
                <a class="nav-link" href="{{ route('admin.dashboard') }}">
                    <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                    Dashboard Admin
                </a>
                
                <div class="sb-sidenav-menu-heading">Manajemen</div>
                
                <!-- Penambahan Akun -->
                <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapseAkun" aria-expanded="false" aria-controls="collapseAkun">
                    <div class="sb-nav-link-icon"><i class="fas fa-users"></i></div>
                    Manajemen Akun
                    <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                </a>
                <div class="collapse" id="collapseAkun" aria-labelledby="headingAkun" data-bs-parent="#sidenavAccordion">
                    <nav class="sb-sidenav-menu-nested nav">
                        <a class="nav-link" href="{{ route('AkunMahasiswa.index') }}">Mahasiswa</a>
                        <a class="nav-link" href="{{ route('AkunDosen.index') }}">Dosen</a>
                    </nav>
                </div>
                
                <!-- Inventaris -->
                <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapseInventaris" aria-expanded="false" aria-controls="collapseInventaris">
                    <div class="sb-nav-link-icon"><i class="fas fa-boxes"></i></div>
                    Inventaris
                    <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                </a>
                <div class="collapse" id="collapseInventaris" aria-labelledby="headingInventaris" data-bs-parent="#sidenavAccordion">
                    <nav class="sb-sidenav-menu-nested nav">
                        <a class="nav-link" href="{{ route('items.create') }}">Input Inventaris</a>
                        <a class="nav-link" href="{{ route('items.store') }}">Ketersediaan Barang</a>
                        <a class="nav-link" href="/KerusakanInventaris">Kerusakan Barang</a>
                    </nav>
                </div>
                
                <!-- Pelaporan -->
                <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapsePelaporan" aria-expanded="false" aria-controls="collapsePelaporan">
                    <div class="sb-nav-link-icon"><i class="fas fa-file-alt"></i></div>
                    Pelaporan
                    <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                </a>
                <div class="collapse" id="collapsePelaporan" aria-labelledby="headingPelaporan" data-bs-parent="#sidenavAccordion">
                    <nav class="sb-sidenav-menu-nested nav">
                        <a class="nav-link" href="/laporan-keseluruhan-inventaris">Laporan Keseluruhan</a>
                        <a class="nav-link" href="/laporan-barang-keluar-masuk">Barang Keluar Masuk</a>
                        <a class="nav-link" href="/laporan-barang-sekali-pakai">Barang Sekali Pakai</a>
                        <a class="nav-link" href="/laporan-barang-baru">Barang Baru</a>
                        <a class="nav-link" href="/laporan-kerusakan-barang">Kerusakan Barang</a>
                    </nav>
                </div>
            </div>
        </div>
    </nav>
</div>