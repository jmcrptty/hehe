@extends('layouts.app')

@section('title', 'Informasi Inventaris')

@section('content')
<div class="container-fluid mt-4">
    <div class="card shadow">
        <div class="card-header bg-white py-3">
            <div class="row align-items-center">
                <div class="col-md-4">
                    <h5 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-boxes mr-2"></i>Data Inventaris
                    </h5>
                </div>
                <div class="col-md-5">
                    <div class="input-group">
                        <input type="text" class="form-control" id="searchInput" placeholder="Cari barang...">
                        <div class="input-group-append">
                            <button class="btn btn-primary">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 text-right">
                    <a href="{{ route('PeminjamanInventaris') }}" class="btn btn-success">
                        <i class="fas fa-plus-circle"></i> Peminjaman Barang
                    </a>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table" id="dataTable">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Kode Barang</th>
                            <th>Nama Barang</th>
                            <th>Kondisi</th>
                            <th>Status</th>
                            <th>Jumlah</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($items as $index => $item)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $item->item_code }}</td>
                            <td>{{ $item->item_name }}</td>
                            <td>
                                @if($item->condition_name == 'Baik')
                                    <span class="badge bg-success text-white">
                                        <i class="fas fa-check-circle"></i> Baik
                                    </span>
                                @else
                                    <span class="badge bg-danger text-white">
                                        <i class="fas fa-times-circle"></i> Rusak
                                    </span>
                                @endif
                            </td>
                            <td>
                                @if($item->loan_status == 'Tersedia')
                                    <span class="badge bg-success text-white">
                                        <i class="fas fa-check"></i> Tersedia
                                    </span>
                                @else
                                    <span class="badge bg-warning text-dark">
                                        <i class="fas fa-clock"></i> Dipinjam
                                    </span>
                                @endif
                            </td>
                            <td>{{ $item->quantity }} {{ $item->unit }}</td>
                            <td>
                                <button type="button" 
                                        class="btn btn-primary btn-sm detail-btn"
                                        data-id="{{ $item->id }}">
                                    <i class="fas fa-info-circle"></i> Detail
                                </button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal Detail -->
<div class="modal fade" id="detailModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">Detail Barang</h5>
                <button type="button" class="close text-white" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label class="font-weight-bold">Nama Barang</label>
                    <p id="modal_item_name"></p>
                </div>
                <div class="form-group">
                    <label class="font-weight-bold">Kode Barang</label>
                    <p id="modal_item_code"></p>
                </div>
                <div class="form-group">
                    <label class="font-weight-bold">Kondisi</label>
                    <p id="modal_condition"></p>
                </div>
                <div class="form-group">
                    <label class="font-weight-bold">Status</label>
                    <p id="modal_status"></p>
                </div>
                <div class="form-group">
                    <label class="font-weight-bold">Jumlah</label>
                    <p id="modal_quantity"></p>
                </div>
                <hr>
                <div class="alert alert-info">
                    <h6 class="font-weight-bold">Syarat Peminjaman:</h6>
                    <ul class="mb-0">
                        <li>Membawa KTM yang masih aktif</li>
                        <li>Maksimal peminjaman 7 hari</li>
                        <li>Wajib mengembalikan dalam kondisi baik</li>
                    </ul>
                </div>
            </div>
            <div class="modal-footer">
                <a href="{{ route('PeminjamanInventaris') }}" class="btn btn-primary">
                    <i class="fas fa-hand-holding"></i> Pinjam Barang
                </a>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">
                    <i class="fas fa-times"></i> Tutup
                </button>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
    .badge {
        padding: 8px 12px;
        font-size: 12px;
    }
    .bg-success {
        background-color: #28a745 !important;
    }
    .bg-danger {
        background-color: #dc3545 !important;
    }
    .bg-warning {
        background-color: #ffc107 !important;
    }
    .table td {
        vertical-align: middle;
    }
</style>
@endpush

@push('scripts')
<script>
$(document).ready(function() {
    // Inisialisasi DataTable dengan konfigurasi yang disederhanakan
    var table = $('#dataTable').DataTable({
        "pageLength": 10,
        "ordering": true,
        "searching": true,
        "dom": '<"top"f>rt<"bottom"p>', // Menghilangkan tombol panah atas bawah
        "language": {
            "search": "Pencarian:",
            "paginate": {
                "next": "Selanjutnya",
                "previous": "Sebelumnya"
            },
            "zeroRecords": "Data tidak ditemukan",
            "info": "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
            "infoEmpty": "Tidak ada data",
            "infoFiltered": "(difilter dari _MAX_ total data)"
        }
    });

    // Pencarian menggunakan input custom
    $('#searchInput').on('keyup', function() {
        table.search(this.value).draw();
    });

    // Event handler untuk tombol detail
    $('.detail-btn').on('click', function() {
        const id = $(this).data('id');
        
        $.ajax({
            url: '/inventory/detail/' + id,
            type: 'GET',
            success: function(response) {
                if(response.success) {
                    const item = response.data;
                    
                    // Update modal content
                    $('#modal_item_name').text(item.item_name);
                    $('#modal_item_code').text(item.item_code);
                    $('#modal_condition').html(`<span class="badge bg-${item.condition_name == 'Baik' ? 'success' : 'danger'} text-white">
                        <i class="fas fa-${item.condition_name == 'Baik' ? 'check-circle' : 'times-circle'}"></i> 
                        ${item.condition_name}
                    </span>`);
                    $('#modal_status').html(`<span class="badge bg-${item.loan_status == 'Tersedia' ? 'success' : 'warning'} ${item.loan_status == 'Tersedia' ? 'text-white' : 'text-dark'}">
                        <i class="fas fa-${item.loan_status == 'Tersedia' ? 'check' : 'clock'}"></i> 
                        ${item.loan_status}
                    </span>`);
                    $('#modal_quantity').text(`${item.quantity} ${item.unit}`);
                    
                    // Tampilkan modal
                    $('#detailModal').modal('show');
                } else {
                    alert('Gagal mengambil detail barang');
                }
            },
            error: function(xhr, status, error) {
                console.error('Error:', error);
                alert('Terjadi kesalahan saat mengambil detail barang');
            }
        });
    });

    // Event handler untuk tombol tutup dan X di modal
    $('.close, .btn-secondary').on('click', function() {
        $('#detailModal').modal('hide');
    });

    // Event handler untuk reset modal saat ditutup
    $('#detailModal').on('hidden.bs.modal', function () {
        $('#modal_item_name').text('');
        $('#modal_item_code').text('');
        $('#modal_condition').html('');
        $('#modal_status').html('');
        $('#modal_quantity').text('');
    });
});
</script>
@endpush
@endsection
