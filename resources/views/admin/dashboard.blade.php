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
    <!-- Informasi Akun Card -->
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-header bg-white border-0">
            <h5 class="card-title mb-0">
                <i class="fas fa-user-circle me-2"></i>Informasi Akun
            </h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <table class="table table-borderless">
                        <tbody>
                            <tr>
                                <th class="text-muted" width="130">Nama</th>
                                <td>: {{ Auth::user()->name }}</td>
                            </tr>
                            <tr>
                                <th class="text-muted">NIP</th>
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

    <!-- List Peminjaman -->
    <div class="card shadow-sm border-0">
        <div class="card-header bg-white border-0">
            <h5 class="card-title mb-0">
                <i class="fas fa-clipboard-list me-2"></i>List Peminjaman
            </h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover" id="peminjamanTable">
                    <thead>
                        <tr>
                            <th class="text-center">No</th>
                            <th>Tanggal</th>
                            <th>Nama</th>
                            <th class="text-center">Status</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($peminjaman ?? [] as $index => $p)
                        <tr>
                            <td class="text-center">{{ $index + 1 }}</td>
                            <td>{{ \Carbon\Carbon::parse($p->tanggal_pinjam)->format('d/m/Y') }}</td>
                            <td>{{ $p->user->name }}</td>
                            <td class="text-center">
                                <span class="badge bg-{{ $p->status == 'menunggu' ? 'warning' : ($p->status == 'disetujui' ? 'success' : 'danger') }}">
                                    {{ ucfirst($p->status) }}
                                </span>
                            </td>
                            <td class="text-center">
                                <button type="button" class="btn btn-sm btn-primary" onclick="showDetail({{ $p->id }})">
                                    Detail
                                </button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Modal Detail -->
    @foreach($peminjaman ?? [] as $p)
    <div class="modal" id="detailModal{{ $p->id }}" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Detail Peminjaman</h5>
                    <button type="button" class="btn-close" onclick="closeModal({{ $p->id }})"></button>
                </div>
                <div class="modal-body">
                    <!-- Informasi Peminjam -->
                    <div class="mb-4">
                        <div class="row mb-2">
                            <div class="col-md-2">Nama</div>
                            <div class="col-md-10">: {{ $p->user->name }}</div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-md-2">UserID</div>
                            <div class="col-md-10">: {{ $p->user->userid }}</div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-md-2">Role</div>
                            <div class="col-md-10">: <span class="badge bg-primary">{{ ucfirst($p->user->Role) }}</span></div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-md-2">Tanggal Pinjam</div>
                            <div class="col-md-10">: {{ \Carbon\Carbon::parse($p->tanggal_pinjam)->format('d/m/Y') }}</div>
                        </div>
                        @php
                            $hasLongTermItem = $p->items->contains(function($item) {
                                return $item->category_name === 'Barang Jangka Panjang';
                            });
                        @endphp
                        @if($hasLongTermItem && $p->tanggal_kembali)
                        <div class="row mb-2">
                            <div class="col-md-2">Tanggal Kembali</div>
                            <div class="col-md-10">: {{ \Carbon\Carbon::parse($p->tanggal_kembali)->format('d/m/Y') }}</div>
                        </div>
                        @endif
                    </div>

                    <!-- Status Peminjaman -->
                    <div class="mb-4">
                        <h6 class="fw-bold">Status Peminjaman</h6>
                        <div class="row mb-2">
                            <div class="col-md-2">Status</div>
                            <div class="col-md-10">: 
                                <span class="badge bg-{{ $p->status == 'menunggu' ? 'warning' : ($p->status == 'disetujui' ? 'success' : 'danger') }}">
                                    {{ ucfirst($p->status) }}
                                </span>
                            </div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-md-2">Keterangan</div>
                            <div class="col-md-10">: {{ $p->keterangan ?: '-' }}</div>
                        </div>
                    </div>

                    <!-- Barang yang Dipinjam -->
                    <div class="mb-4">
                        <h6 class="fw-bold mb-3">Barang yang Dipinjam</h6>
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr class="text-center">
                                        <th>No</th>
                                        <th>Nama Barang</th>
                                        <th>Kategori</th>
                                        <th>Jumlah</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($p->items as $index => $item)
                                    <tr>
                                        <td class="text-center">{{ $index + 1 }}</td>
                                        <td>{{ $item->item_name }}</td>
                                        <td class="text-center">{{ $item->category_name }}</td>
                                        <td class="text-center">{{ $item->pivot->quantity }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Tombol Aksi -->
                    @if($p->status == 'menunggu')
                    <div class="text-end mt-4">
                        <form action="{{ route('admin.peminjaman.approve', $p->id) }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-success">Setujui</button>
                        </form>
                        <button type="button" class="btn btn-danger" onclick="showRejectModal({{ $p->id }})">
                            Tolak
                        </button>
                        <button type="button" class="btn btn-secondary" onclick="closeModal({{ $p->id }})">Tutup</button>
                    </div>
                    @else
                    <div class="text-end mt-4">
                        <button type="button" class="btn btn-secondary" onclick="closeModal({{ $p->id }})">Tutup</button>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Tolak -->
    <div class="modal" id="rejectModal{{ $p->id }}" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form action="{{ route('admin.peminjaman.reject', $p->id) }}" method="POST">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title">Tolak Peminjaman</h5>
                        <button type="button" class="btn-close" onclick="closeRejectModal({{ $p->id }})"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Alasan Penolakan</label>
                            <textarea class="form-control" name="keterangan" rows="3" required></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" onclick="closeRejectModal({{ $p->id }})">Batal</button>
                        <button type="submit" class="btn btn-danger">Konfirmasi</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endforeach
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    $('#peminjamanTable').DataTable({
        "pageLength": 10,
        "ordering": true,
        "order": [[0, 'desc']],
        "language": {
            "url": "//cdn.datatables.net/plug-ins/1.10.24/i18n/Indonesian.json"
        }
    });
});

function showDetail(id) {
    $('#detailModal' + id).show();
}

function closeModal(id) {
    $('#detailModal' + id).hide();
}

function showRejectModal(id) {
    $('#rejectModal' + id).show();
}

function closeRejectModal(id) {
    $('#rejectModal' + id).hide();
}

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
