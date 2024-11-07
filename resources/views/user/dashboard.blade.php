@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <!-- Welcome Message (Dismissible) -->
    <div class="welcome-message mb-4" id="welcomeMessage">
        <div class="card bg-primary text-white shadow-sm border-0">
            <div class="card-body py-4 position-relative">
                <button type="button" class="btn-close btn-close-white position-absolute top-0 end-0 mt-3 me-3" 
                        data-bs-dismiss="alert" aria-label="Close" 
                        onclick="dismissWelcome()">
                </button>
                <h1 class="display-6 mb-2">Selamat datang, {{ Auth::user()->name }}!</h1>
                <p class="lead mb-0">Selamat datang di sistem manajemen inventaris laboratorium.</p>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="row">
        <!-- Informasi Akun Card -->
        <div class="col-md-6 mb-4">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header bg-white border-0">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-user-circle me-2"></i>Informasi Akun
                    </h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-borderless">
                            <tbody>
                                <tr>
                                    <th class="text-muted" width="130">Nama</th>
                                    <td>: {{ Auth::user()->name }}</td>
                                </tr>
                                <tr>
                                    <th class="text-muted">NPM</th>
                                    <td>: {{ Auth::user()->userid }}</td>
                                </tr>
                                <tr>
                                    <th class="text-muted">Email</th>
                                    <td>: {{ Auth::user()->email }}</td>
                                </tr>
                                <tr>
                                    <th class="text-muted">Role</th>
                                    <td>: <span class="badge bg-primary">{{ ucfirst(Auth::user()->Role) }}</span></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Status Peminjaman Card -->
        <div class="col-md-6 mb-4">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header bg-white border-0">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-clipboard-list me-2"></i>Status Peminjaman Terkini
                    </h5>
                </div>
                <div class="card-body">
                    @if(session('status'))
                        <div class="alert alert-info alert-dismissible fade show" role="alert">
                            {{ session('status') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    @if(isset($peminjaman))
                        @switch($peminjaman->status)
                            @case('menunggu')
                                <div class="d-flex align-items-center p-3 bg-warning bg-opacity-10 rounded-3 border border-warning">
                                    <i class="fas fa-clock text-warning me-3 fs-4"></i>
                                    <div>
                                        <h6 class="mb-1">Menunggu Konfirmasi Laboran</h6>
                                        <p class="mb-0 text-muted">Peminjaman Anda sedang ditinjau oleh laboran</p>
                                        <small class="text-muted">Diajukan pada: {{ \Carbon\Carbon::parse($peminjaman->created_at)->format('d/m/Y H:i') }}</small>
                                    </div>
                                </div>
                                @break

                            @case('disetujui')
                                <div class="d-flex align-items-center p-3 bg-success bg-opacity-10 rounded-3 border border-success">
                                    <i class="fas fa-check-circle text-success me-3 fs-4"></i>
                                    <div>
                                        <h6 class="mb-1">Peminjaman Disetujui</h6>
                                        <p class="mb-0 text-muted">Silakan ambil barang di laboratorium</p>
                                        <small class="text-muted">Disetujui pada: {{ \Carbon\Carbon::parse($peminjaman->updated_at)->format('d/m/Y H:i') }}</small>
                                    </div>
                                </div>
                                @break

                            @case('ditolak')
                                <div class="d-flex align-items-center p-3 bg-danger bg-opacity-10 rounded-3 border border-danger">
                                    <i class="fas fa-times-circle text-danger me-3 fs-4"></i>
                                    <div>
                                        <h6 class="mb-1">Peminjaman Ditolak</h6>
                                        <p class="mb-0 text-muted">{{ $peminjaman->keterangan ?: 'Tidak ada keterangan' }}</p>
                                        <small class="text-muted">Ditolak pada: {{ \Carbon\Carbon::parse($peminjaman->updated_at)->format('d/m/Y H:i') }}</small>
                                    </div>
                                </div>
                                @break

                            @default
                                <div class="text-center text-muted py-4">
                                    <i class="fas fa-inbox fs-4 mb-3 d-block"></i>
                                    <p class="mb-0">Tidak ada peminjaman aktif saat ini</p>
                                    <a href="{{ route('peminjaman.create') }}" class="btn btn-primary mt-3">
                                        <i class="fas fa-plus me-2"></i>Buat Peminjaman Baru
                                    </a>
                                </div>
                        @endswitch
                    @else
                        <div class="text-center text-muted py-4">
                            <i class="fas fa-inbox fs-4 mb-3 d-block"></i>
                            <p class="mb-0">Tidak ada peminjaman aktif saat ini</p>
                            <a href="{{ route('peminjaman.create') }}" class="btn btn-primary mt-3">
                                <i class="fas fa-plus me-2"></i>Buat Peminjaman Baru
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Check if welcome message was previously dismissed
    document.addEventListener('DOMContentLoaded', function() {
        if (localStorage.getItem('welcomeDismissed')) {
            document.getElementById('welcomeMessage').style.display = 'none';
        }
    });

    // Function to dismiss welcome message
    function dismissWelcome() {
        localStorage.setItem('welcomeDismissed', 'true');
        document.getElementById('welcomeMessage').style.display = 'none';
    }

    // Clear localStorage when user logs out (add this to your logout logic)
    function clearWelcomeState() {
        localStorage.removeItem('welcomeDismissed');
    }
</script>
@endpush
@endsection
