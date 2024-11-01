<div id="layoutSidenav_nav">
    <nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
        <div class="sb-sidenav-menu">
            <div class="nav">
                <div class="sb-sidenav-menu-heading">Core</div>
                <a class="nav-link" href="{{ route('user.dashboard') }}">
                    <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                    Dashboard
                </a>
                
                <a class="nav-link" href="{{ route('PeminjamanInventaris') }}">
                    <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                    Peminjaman Inventaris
                </a>
                
                <div class="sb-sidenav-menu-heading">Interface</div>
                
                <!-- Penambahan Akun -->
                <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapseAkun" aria-expanded="false" aria-controls="collapseAkun">
                    <div class="sb-nav-link-icon"><i class="fas fa-columns"></i></div>
                    Penambahan Akun
                    <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                </a>
                <div class="collapse" id="collapseAkun" aria-labelledby="headingAkun" data-bs-parent="#sidenavAccordion">
                    <nav class="sb-sidenav-menu-nested nav">
                        <a class="nav-link" href="{{ route('AkunMahasiswa') }}">Mahasiswa</a>
                        <a class="nav-link" href="{{ route('AkunDosen') }}">Dosen</a>
                    </nav>
                </div>
                
                <!-- Inventaris -->
                <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapseInventaris" aria-expanded="false" aria-controls="collapseInventaris">
                    <div class="sb-nav-link-icon"><i class="fas fa-columns"></i></div>
                    Inventaris
                    <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                </a>
                <div class="collapse" id="collapseInventaris" aria-labelledby="headingInventaris" data-bs-parent="#sidenavAccordion">
                    <nav class="sb-sidenav-menu-nested nav">
                        <a class="nav-link" href="{{ route('InputInventaris') }}">Input Inventaris</a>
                        <a class="nav-link" href="/KetersediaanBarang">Ketersediaan Barang</a>
                        <a class="nav-link" href="/KerusakanInventaris">Kerusakan Barang</a>
                    </nav>
                </div>
                
                <!-- Pelaporan -->
                <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapsePelaporan" aria-expanded="false" aria-controls="collapsePelaporan">
                    <div class="sb-nav-link-icon"><i class="fas fa-columns"></i></div>
                    Pelaporan
                    <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                </a>
                <div class="collapse" id="collapsePelaporan" aria-labelledby="headingPelaporan" data-bs-parent="#sidenavAccordion">
                    <nav class="sb-sidenav-menu-nested nav">
                        <a class="nav-link" href="/laporan-keseluruhan-inventaris">Laporan Keseluruhan Inventaris</a>
                        <a class="nav-link" href="/laporan-barang-keluar-masuk">Laporan Barang Keluar Masuk</a>
                        <a class="nav-link" href="/laporan-barang-sekali-pakai">Laporan Barang Sekali Pakai</a>
                        <a class="nav-link" href="/laporan-barang-baru">Laporan Barang Baru</a>
                        <a class="nav-link" href="/laporan-kerusakan-barang">Laporan Kerusakan Barang</a>
                    </nav>
                </div>
            </div>
        </div>
    </nav>
</div>