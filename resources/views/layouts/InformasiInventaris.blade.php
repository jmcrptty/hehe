@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <div class="card shadow-sm">
        <div class="card-header bg-white py-3">
            <div class="row align-items-center">
                <div class="col-md-4">
                    <h5 class="m-0 font-weight-bold text-primary">Data Inventaris</h5>
                </div>
                <div class="col-md-5">
                    <div class="input-group">
                        <input type="text" class="form-control" id="searchInput" placeholder="Cari barang...">
                        <div class="input-group-append">
                            <span class="input-group-text bg-primary text-white">
                                <i class="fas fa-search"></i>
                            </span>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 text-right">
                    <a href="{{ route('PeminjamanInventaris') }}" class="btn btn-success">
                        <i class="fas fa-plus-circle mr-2"></i>Peminjaman Barang
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
                                <span class="badge badge-{{ $item->condition_name == 'Baik' ? 'success' : 'danger' }}">
                                    {{ $item->condition_name }}
                                </span>
                            </td>
                            <td>
                                <span class="badge badge-{{ $item->loan_status == 'Tersedia' ? 'success' : 'warning' }}">
                                    {{ $item->loan_status }}
                                </span>
                            </td>
                            <td>{{ $item->quantity }} {{ $item->unit }}</td>
                            <td>
                                <button type="button" class="btn btn-primary btn-sm show-detail" data-id="{{ $item->id }}">
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
<div class="modal fade" id="detailModal" tabindex="-1" role="dialog" aria-labelledby="detailModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="detailModalLabel">Detail Barang</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-12">
                        <div class="form-group mb-3">
                            <label class="font-weight-bold">Nama Barang</label>
                            <p id="item_name" class="text-muted mb-0"></p>
                        </div>
                        <div class="form-group mb-3">
                            <label class="font-weight-bold">Kode Barang</label>
                            <p id="item_code" class="text-muted mb-0"></p>
                        </div>
                        <div class="form-group mb-3">
                            <label class="font-weight-bold">Kondisi Barang</label>
                            <p id="condition_name" class="text-muted mb-0"></p>
                        </div>
                        <div class="form-group mb-3">
                            <label class="font-weight-bold">Status Ketersediaan</label>
                            <p id="loan_status" class="text-muted mb-0"></p>
                        </div>
                        <div class="form-group mb-3">
                            <label class="font-weight-bold">Jumlah Tersedia</label>
                            <p class="text-muted mb-0"><span id="quantity"></span> <span id="unit"></span></p>
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
                </div>
            </div>
            <div class="modal-footer">
                <a href="{{ route('PeminjamanInventaris') }}" class="btn btn-primary">Pinjam Barang</a>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
$(document).ready(function() {
    // Inisialisasi DataTable
    var table = $('#dataTable').DataTable({
        "dom": 'rt<"bottom"p>', // Hanya tampilkan table dan pagination
        "ordering": true,
        "language": {
            "emptyTable": "Tidak ada data yang tersedia"
        }
    });
    
    // Custom search function
    $('#searchInput').on('keyup', function() {
        table.search(this.value).draw();
        
        // Tampilkan pesan jika tidak ada hasil
        if (table.page.info().recordsDisplay === 0) {
            if ($('#noResults').length === 0) {
                $('#dataTable tbody').append(`
                    <tr id="noResults">
                        <td colspan="7" class="text-center py-3">
                            <div class="text-muted">
                                <i class="fas fa-search mb-2"></i>
                                <p class="mb-0">Tidak ada hasil untuk pencarian "${$(this).val()}"</p>
                            </div>
                        </td>
                    </tr>
                `);
            }
        } else {
            $('#noResults').remove();
        }
    });

    // Fungsi untuk menampilkan detail
    $(document).on('click', '.show-detail', function() {
        var itemId = $(this).data('id');
        $.ajax({
            url: '/inventory/detail/' + itemId,
            method: 'GET',
            success: function(response) {
                if (response.success) {
                    const data = response.data;
                    $('#item_name').text(data.item_name);
                    $('#item_code').text(data.item_code);
                    $('#condition_name').text(data.category.status.condition);
                    $('#loan_status').text(data.category.status.loan);
                    $('#quantity').text(data.quantity.current);
                    $('#unit').text(data.quantity.unit);
                    
                    $('#detailModal').modal('show');
                }
            },
            error: function(xhr, status, error) {
                alert('Terjadi kesalahan saat mengambil data');
                console.error(error);
            }
        });
    });

    // Handler untuk menutup modal
    $('.btn-close, .btn-secondary').on('click', function() {
        $('#detailModal').modal('hide');
    });
    
    // Handler untuk membersihkan modal saat ditutup
    $('#detailModal').on('hidden.bs.modal', function () {
        $('#item_name').text('');
        $('#item_code').text('');
        $('#condition_name').text('');
        $('#loan_status').text('');
        $('#quantity').text('');
        $('#unit').text('');
    });
});
</script>

<style>
/* Style yang sudah ada tetap sama */

/* Tambahan style untuk menyembunyikan elemen default DataTables */
.dataTables_filter, .dataTables_info {
    display: none;
}

/* Style untuk input pencarian */
#searchInput {
    border-radius: 4px 0 0 4px;
}

.input-group-text {
    border-radius: 0 4px 4px 0;
}
</style>
@endpush
@endsection
