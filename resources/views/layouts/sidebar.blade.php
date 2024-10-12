<aside id="sidebar" class="js-sidebar">
    <!-- Content For Sidebar -->
    <div class="h-100">
        <div class="sidebar-logo">
            <a href="#">Dashboard </a>
        </div>
        <ul class="sidebar-nav">
            <li class="sidebar-header">Menu</li>
            <li class="sidebar-item">
                <a href="/dashboard" class="sidebar-link">
                    <i class="bi bi-house-gear-fill"></i>
                    Dashboard
                </a>
            </li>
            <li class="sidebar-item">
                <a href="" class="sidebar-link collapsed" data-bs-target="#peminjaman" data-bs-toggle="collapse"
                    aria-expanded="false">
                    <i class="bi bi-person-plus"></i>
                    Peminjaman
                </a>
                <ul id="peminjaman" class="sidebar-dropdown list-unstyled collapse" data-bs-parent="#sidebar">
                    <li class="sidebar-item">
                        <a href="{{ route("InformasiInventaris") }}" class="sidebar-link">
                            <i class="bi bi-person-square"></i>
                            Informasi Inventaris
                        </a>
                    </li>
                    <li class="sidebar-item">
                        <a href="{{ route('PeminjamanInventaris') }}" class="sidebar-link">
                            <i class="bi bi-person-square"></i>
                            Peminjaman
                        </a>
                    </li>
                </ul>
            
            <li class="sidebar-item">
                <a href="#" class="sidebar-link collapsed" data-bs-target="#pages" data-bs-toggle="collapse"
                    aria-expanded="false">
                    <i class="bi bi-person-plus"></i>
                    Penambahan Akun
                </a>
                <ul id="pages" class="sidebar-dropdown list-unstyled collapse" data-bs-parent="#sidebar">
                    <li class="sidebar-item">
                        <a href="{{ route('AkunMahasiswa') }}" class="sidebar-link">
                            <i class="bi bi-person-square"></i>
                            Mahasiswa
                        </a>
                    </li>
                    <li class="sidebar-item">
                        <a href="{{ route('AkunDosen') }}" class="sidebar-link">
                            <i class="bi bi-person-square"></i>
                            Dosen
                        </a>
                    </li>
                </ul>
            </li>
            <li class="sidebar-item">
                <a href="#" class="sidebar-link collapsed" data-bs-target="#inventaris" data-bs-toggle="collapse"
                    aria-expanded="false">
                    <i class="bi bi-database-fill"></i>
                    Inventaris
                </a>
                <ul id="inventaris" class="sidebar-dropdown list-unstyled collapse" data-bs-parent="#sidebar">
                    <li class="sidebar-item">
                        <a href="#" class="sidebar-link">
                            <i class="bi bi-bag-check"></i>
                            Ketersediaan Barang
                        </a>
                        <a href="{{ route ('InputInventaris')}}" class="sidebar-link">
                            <i class="bi bi-bag-check"></i>
                            Input Barang
                        </a>
                        <a href="/InputKategori" class="sidebar-link">
                            <i class="bi bi-bag-check"></i>
                            Input Kategori
                        </a>
                    </li>
                    <li class="sidebar-item">
                        <a href="{{ route ('KerusakanInventaris')}}" class="sidebar-link">
                            <i class="bi bi-bag-check"></i>
                            Kerusakan Barang
                        </a>
                    </li>
                </ul>
            </li>
            <li class="sidebar-item">
                <a href="#" class="sidebar-link collapsed" data-bs-target="#posts" data-bs-toggle="collapse"
                    aria-expanded="false">
                    <i class="bi bi-journal-bookmark-fill"></i>
                    Pelaporan
                </a>
                <ul id="posts" class="sidebar-dropdown list-unstyled collapse" data-bs-parent="#sidebar">
                    <li class="sidebar-item">
                        <a href="#" class="sidebar-link">
                            <i class="bi bi-journal"></i>
                            Laporan Keseluruhan Inventaris
                        </a>
                    </li>
                    <li class="sidebar-item">
                        <a href="#" class="sidebar-link">
                            <i class="bi bi-journal"></i>
                            Laporan Barang Keluar Masuk
                        </a>
                    </li>
                    <li class="sidebar-item">
                        <a href="#" class="sidebar-link">
                            <i class="bi bi-journal"></i>
                            Laporan Barang Sekali Pakai
                        </a>
                    </li>
                    <li class="sidebar-item">
                        <a href="#" class="sidebar-link">
                            <i class="bi bi-journal"></i>
                            Laporan Barang Baru
                        </a>
                    </li>
                    <li class="sidebar-item">
                        <a href="#" class="sidebar-link">
                            <i class="bi bi-journal"></i>
                            Laporan Kerusakan Barang
                        </a>
                    </li>
                </ul>
            </li>
        </ul>
    </div>
</aside>