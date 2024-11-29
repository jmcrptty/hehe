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
                        <tr class="{{ 
                            $p->user->peminjaman()
                                ->whereIn('status', ['menunggu_pengembalian', 'terlambat'])
                                ->exists() ? 'table-warning' : '' 
                        }}">
                            <td class="text-center">{{ $index + 1 }}</td>
                            <td>{{ \Carbon\Carbon::parse($p->tanggal_pinjam)->format('d/m/Y') }}</td>
                            <td>{{ $p->user->name }}</td>
                            <td class="text-center">
                                <span class="badge bg-{{ 
                                    $p->status == 'menunggu' ? 'warning' : 
                                    ($p->status == 'disetujui' ? 'success' : 
                                    ($p->status == 'menunggu_pengembalian' ? 'info' : 
                                    ($p->status == 'terlambat' ? 'danger' : 
                                    ($p->status == 'selesai' ? 'success' : 'secondary')))) 
                                }}">
                                    {{ ucfirst(str_replace('_', ' ', $p->status)) }}
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

    <!-- Setelah List Peminjaman, tambahkan section History -->
    <div class="card shadow-sm border-0 mt-4">
        <div class="card-header bg-white border-0">
            <h5 class="card-title mb-0">
                <i class="fas fa-history me-2"></i>Riwayat Peminjaman
            </h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover" id="historyTable">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Tanggal</th>
                            <th>Peminjam</th>
                            <th>Barang</th>
                            <th>Status</th>
                            <th>Keterangan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($riwayatPeminjaman ?? [] as $index => $p)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>
                                Pinjam: {{ \Carbon\Carbon::parse($p->tanggal_pinjam)->format('d/m/Y') }}<br>
                                @if($p->tanggal_kembali)
                                Kembali: {{ \Carbon\Carbon::parse($p->tanggal_kembali)->format('d/m/Y') }}
                                @endif
                            </td>
                            <td>{{ $p->user->name }}</td>
                            <td>
                                @foreach($p->items as $item)
                                    - {{ $item->item_name }} ({{ $item->pivot->quantity }})<br>
                                @endforeach
                            </td>
                            <td class="text-center">
                                <span class="badge bg-{{ 
                                    $p->status == 'selesai' ? 'success' : 
                                    ($p->status == 'ditolak' ? 'danger' : 'secondary') 
                                }}">
                                    {{ ucfirst(str_replace('_', ' ', $p->status)) }}
                                </span>
                            </td>
                            <td>
                                @if($p->status == 'ditolak')
                                    <small class="text-danger">{{ $p->keterangan }}</small>
                                @else
                                    -
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center">Belum ada riwayat peminjaman</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Modal Detail -->
    @foreach($peminjaman ?? [] as $p)
    <div class="modal fade" id="detailModal{{ $p->id }}" tabindex="-1" aria-labelledby="detailModalLabel{{ $p->id }}" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="detailModalLabel{{ $p->id }}">Detail Peminjaman</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
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
                </div>
                <div class="modal-footer">
                    @if($p->status == 'menunggu')
                        <button type="button" class="btn btn-success" onclick="showApproveModal({{ $p->id }})">
                            Setujui
                        </button>
                        <button type="button" class="btn btn-danger" onclick="showRejectModal({{ $p->id }})">
                            Tolak
                        </button>
                    @elseif(in_array($p->status, ['menunggu_pengembalian', 'terlambat']))
                        <form action="{{ route('admin.peminjaman.return', $p->id) }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-primary">Selesaikan Peminjaman</button>
                        </form>
                    @endif
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Tolak -->
    <div class="modal fade" id="rejectModal{{ $p->id }}" tabindex="-1" aria-labelledby="rejectModalLabel{{ $p->id }}" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="rejectModalLabel{{ $p->id }}">Tolak Peminjaman</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('admin.peminjaman.reject', $p->id) }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="keterangan{{ $p->id }}" class="form-label">Keterangan Penolakan <span class="text-danger">*</span></label>
                            <textarea class="form-control" id="keterangan{{ $p->id }}" name="keterangan" rows="3" required></textarea>
                            <small class="text-muted">Berikan alasan penolakan peminjaman</small>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-danger">Tolak Peminjaman</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Konfirmasi Setujui -->
    <div class="modal fade" id="approveModal{{ $p->id }}" tabindex="-1" aria-labelledby="approveModalLabel{{ $p->id }}" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="approveModalLabel{{ $p->id }}">Konfirmasi Persetujuan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('admin.peminjaman.approve', $p->id) }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <p>Apakah Anda yakin ingin menyetujui peminjaman ini?</p>
                        <div class="mt-3">
                            <h6>Detail Peminjaman:</h6>
                            <table class="table table-sm">
                                <tr>
                                    <td>Peminjam</td>
                                    <td>: {{ $p->user->name }}</td>
                                </tr>
                                <tr>
                                    <td>Tanggal Pinjam</td>
                                    <td>: {{ \Carbon\Carbon::parse($p->tanggal_pinjam)->format('d/m/Y') }}</td>
                                </tr>
                                @if($p->tanggal_kembali)
                                <tr>
                                    <td>Tanggal Kembali</td>
                                    <td>: {{ \Carbon\Carbon::parse($p->tanggal_kembali)->format('d/m/Y') }}</td>
                                </tr>
                                @endif
                            </table>
                            <h6>Barang yang dipinjam:</h6>
                            <ul class="list-unstyled">
                                @foreach($p->items as $item)
                                    <li>- {{ $item->item_name }} ({{ $item->pivot->quantity }})</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-success">Ya, Setujui</button>
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
    // DataTable untuk peminjaman aktif
    $('#peminjamanTable').DataTable({
        "pageLength": 10,
        "ordering": true,
        "order": [[0, 'desc']],
        "language": {
            "url": "//cdn.datatables.net/plug-ins/1.10.24/i18n/Indonesian.json"
        }
    });

    // DataTable untuk history
    $('#historyTable').DataTable({
        "pageLength": 10,
        "ordering": true,
        "order": [[1, 'desc']],
        "language": {
            "url": "//cdn.datatables.net/plug-ins/1.10.24/i18n/Indonesian.json"
        }
    });

    // Fungsi untuk menampilkan modal detail
    window.showDetail = function(id) {
        $('#detailModal' + id).modal('show');
    }

    // Fungsi untuk menutup modal detail
    window.closeModal = function(id) {
        $('#detailModal' + id).modal('hide');
    }

    // Fungsi untuk menampilkan modal approve
    window.showApproveModal = function(id) {
        $('#detailModal' + id).modal('hide'); // Tutup modal detail
        setTimeout(function() {
            $('#approveModal' + id).modal('show');
        }, 500);
    }

    // Fungsi untuk menampilkan modal reject
    window.showRejectModal = function(id) {
        $('#detailModal' + id).modal('hide'); // Tutup modal detail
        setTimeout(function() {
            $('#rejectModal' + id).modal('show');
        }, 500);
    }
});

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

@push('styles')
<style>
/* Card Styling */
.card {
    border: none;
    box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
    transition: all 0.3s ease;
}

.card:hover {
    box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
}

.card-header {
    background-color: #fff;
    border-bottom: 1px solid rgba(0,0,0,.05);
    padding: 1.25rem 1.5rem;
}

/* Table Styling */
.table {
    margin-bottom: 0;
}

.table thead th {
    border-top: none;
    border-bottom: 2px solid #e9ecef;
    font-weight: 600;
    color: #495057;
}

.table td, .table th {
    padding: 1rem;
    vertical-align: middle;
}

/* Badge Styling */
.badge {
    padding: 0.5em 0.75em;
    font-weight: 500;
    letter-spacing: 0.3px;
}

/* Button Styling */
.btn {
    font-weight: 500;
    padding: 0.5rem 1rem;
    border-radius: 0.35rem;
    transition: all 0.2s;
}

.btn-sm {
    padding: 0.25rem 0.75rem;
}

.btn:hover {
    transform: translateY(-1px);
}

/* Modal Styling */
.modal-content {
    border: none;
    border-radius: 0.5rem;
    box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
}

.modal-header {
    border-bottom: 1px solid rgba(0,0,0,.05);
    padding: 1.25rem 1.5rem;
}

.modal-footer {
    border-top: 1px solid rgba(0,0,0,.05);
    padding: 1.25rem 1.5rem;
}

/* Welcome Message Styling */
.welcome-message .card {
    border-radius: 0.5rem;
    background: linear-gradient(45deg, #4e73df 0%, #224abe 100%);
}

/* Table Warning State */
.table-warning {
    background-color: #fff8e1 !important;
}

/* DataTables Styling */
.dataTables_wrapper .dataTables_length select,
.dataTables_wrapper .dataTables_filter input {
    border: 1px solid #e9ecef;
    border-radius: 0.35rem;
    padding: 0.375rem 0.75rem;
}

.dataTables_wrapper .dataTables_paginate .paginate_button.current {
    background: #4e73df !important;
    border-color: #4e73df !important;
    color: white !important;
}

.dataTables_wrapper .dataTables_paginate .paginate_button:hover {
    background: #eaecf4 !important;
    border-color: #eaecf4 !important;
}

/* List Styling */
.list-unstyled li {
    padding: 0.25rem 0;
}

/* Responsive Table */
.table-responsive {
    border-radius: 0.35rem;
}

/* Custom Scrollbar */
::-webkit-scrollbar {
    width: 8px;
    height: 8px;
}

::-webkit-scrollbar-track {
    background: #f1f1f1;
}

::-webkit-scrollbar-thumb {
    background: #888;
    border-radius: 4px;
}

::-webkit-scrollbar-thumb:hover {
    background: #555;
}

/* Status Badge Colors */
.badge.bg-warning {
    color: #856404;
    background-color: #fff3cd !important;
}

.badge.bg-success {
    color: #155724;
    background-color: #d4edda !important;
}

.badge.bg-danger {
    color: #721c24;
    background-color: #f8d7da !important;
}

.badge.bg-info {
    color: #0c5460;
    background-color: #d1ecf1 !important;
}

/* Animation */
@keyframes fadeIn {
    from { opacity: 0; }
    to { opacity: 1; }
}

.modal.show {
    animation: fadeIn 0.3s ease;
}
</style>
@endpush