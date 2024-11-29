@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white border-0">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-clipboard-list me-2"></i>Form Peminjaman Barang
                    </h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('peminjaman.store') }}" method="POST" id="peminjamanForm">
                        @csrf
                        <!-- Informasi Peminjam -->
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Nama Peminjam</label>
                                    <input type="text" class="form-control" value="{{ Auth::user()->name }}" readonly>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">NPM</label>
                                    <input type="text" class="form-control" value="{{ Auth::user()->userid }}" readonly>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Tanggal Peminjaman</label>
                                    <input type="text" class="form-control" value="{{ now()->format('d/m/Y') }}" readonly>
                                    <input type="hidden" name="tanggal_pinjam" value="{{ now()->format('Y-m-d') }}">
                                </div>
                                <div class="mb-3" id="tanggalKembaliContainer" style="display: none;">
                                    <label class="form-label">Tanggal Pengembalian <span class="text-danger">*</span></label>
                                    <input type="date" class="form-control" name="tanggal_kembali" id="tanggalKembali" 
                                           min="{{ now()->addDay()->format('Y-m-d') }}">
                                </div>
                            </div>
                        </div>

                        <!-- Pencarian Barang -->
                        <div class="row mb-4">
                            <div class="col-md-8">
                                <div class="mb-3">
                                    <label class="form-label">Cari Barang</label>
                                    <div class="search-container">
                                        <input type="text" class="form-control" id="searchInput" placeholder="Ketik untuk mencari barang..." autocomplete="off">
                                        <div class="search-results" id="searchResults" style="display: none;">
                                            <!-- Hasil pencarian akan muncul di sini -->
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label class="form-label">Jumlah Peminjaman</label>
                                    <input type="number" class="form-control" id="itemQuantity" min="1" value="1">
                                    <small class="text-muted">Stok tersedia: <span id="availableStock">0</span></small>
                                </div>
                            </div>
                        </div>

                        <!-- Hidden Items Container -->
                        <div id="hiddenItemsContainer"></div>

                        <!-- Daftar Barang yang Dipilih -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <div class="card border">
                                    <div class="card-header bg-white">
                                        <h6 class="mb-0">Daftar Barang yang Dipilih</h6>
                                    </div>
                                    <div class="card-body">
                                        <div id="emptyItemsMessage" class="text-center text-muted py-3">
                                            Belum ada barang yang dipilih
                                        </div>
                                        <div id="selectedItemsContainer">
                                            <!-- Daftar barang yang dipilih akan muncul di sini -->
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Tombol Submit -->
                        <div class="mt-4 text-end">
                            <button type="button" class="btn btn-secondary me-2" onclick="window.history.back()">
                                <i class="fas fa-arrow-left me-2"></i>Kembali
                            </button>
                            <button type="submit" class="btn btn-primary" id="submitBtn">
                                <i class="fas fa-paper-plane me-2"></i>Ajukan Peminjaman
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
$(document).ready(function() {
    let selectedItems = [];
    const searchInput = $('#searchInput');
    const searchResults = $('#searchResults');
    const hiddenItemsContainer = $('#hiddenItemsContainer');
    const selectedItemsContainer = $('#selectedItemsContainer');
    const emptyItemsMessage = $('#emptyItemsMessage');
    const tanggalKembaliContainer = $('#tanggalKembaliContainer');

    // Pencarian barang
    searchInput.on('input', function() {
        const query = $(this).val();
        if (query.length > 0) {
            $.ajax({
                url: '{{ route("peminjaman.search") }}',
                type: 'GET',
                data: { search: query },
                success: function(data) {
                    console.log('Search results:', data); // Debugging
                    let html = '';
                    if (data.length > 0) {
                        data.forEach(function(item) {
                            html += `
                                <div class="search-item" 
                                     data-id="${item.id}" 
                                     data-name="${item.item_name}" 
                                     data-stock="${item.quantity}" 
                                     data-category-name="${item.category_name}">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <strong>${item.item_name}</strong>
                                            <br>
                                            <small class="text-muted">
                                                Kategori: <span class="badge ${item.category_name.toLowerCase() === 'barang jangka panjang' ? 'bg-warning' : 'bg-info'}">${item.category_name}</span>
                                            </small>
                                        </div>
                                        <div>
                                            <span class="badge bg-primary">Stok: ${item.quantity}</span>
                                        </div>
                                    </div>
                                </div>
                            `;
                        });
                    } else {
                        html = '<div class="search-item">Barang tidak ditemukan</div>';
                    }
                    searchResults.html(html).show();
                },
                error: function(xhr, status, error) {
                    console.error('Search error:', error); // Debugging
                    searchResults.html('<div class="search-item">Error saat mencari barang</div>').show();
                }
            });
        } else {
            searchResults.hide();
        }
    });

    // Event handler saat memilih barang
    $(document).on('click', '.search-item', function() {
        const id = $(this).data('id');
        const name = $(this).data('name');
        const stock = $(this).data('stock');
        const category_name = $(this).data('category-name');
        const quantity = parseInt($('#itemQuantity').val());

        if (quantity > stock) {
            alert('Jumlah barang melebihi stok tersedia.');
            return;
        }

        // Tambahkan item ke array
        selectedItems.push({
            id,
            name,
            quantity,
            category_name
        });

        updateSelectedItems();
        checkLongTermItems();

        searchResults.hide();
        searchInput.val('');
        $('#itemQuantity').val(1);
    });

    // Fungsi untuk memeriksa barang jangka panjang
    function checkLongTermItems() {
        const hasLongTermItem = selectedItems.some(item => 
            item.category_name && item.category_name.toLowerCase() === 'barang jangka panjang'
        );

        if (hasLongTermItem) {
            tanggalKembaliContainer
                .slideDown()
                .addClass('show')
                .find('input')
                .prop('required', true);
        } else {
            tanggalKembaliContainer
                .slideUp()
                .removeClass('show')
                .find('input')
                .prop('required', false)
                .val('');
        }
    }

    // Update tampilan item yang dipilih
    function updateSelectedItems() {
        hiddenItemsContainer.empty();
        selectedItemsContainer.empty();

        if (selectedItems.length > 0) {
            emptyItemsMessage.hide();
            selectedItems.forEach((item, index) => {
                const isLongTerm = item.category_name && 
                    item.category_name.toLowerCase() === 'barang jangka panjang';
                
                selectedItemsContainer.append(`
                    <div class="selected-item p-2 border rounded mb-2">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <strong>${item.name}</strong> - Jumlah: ${item.quantity}
                                <br>
                                <small class="text-muted">
                                    Kategori: <span class="badge ${isLongTerm ? 'bg-warning' : 'bg-info'}">${item.category_name}</span>
                                    ${isLongTerm ? '<span class="text-danger ms-2">*Wajib isi tanggal pengembalian</span>' : ''}
                                </small>
                            </div>
                            <button type="button" class="btn btn-sm btn-outline-danger" onclick="removeItem(${index})">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </div>
                `);
                hiddenItemsContainer.append(`
                    <input type="hidden" name="items[${index}][id]" value="${item.id}">
                    <input type="hidden" name="items[${index}][quantity]" value="${item.quantity}">
                    <input type="hidden" name="items[${index}][category_name]" value="${item.category_name}">
                `);
            });
        } else {
            emptyItemsMessage.show();
        }
    }

    // Fungsi untuk menghapus item
    window.removeItem = function(index) {
        selectedItems.splice(index, 1);
        updateSelectedItems();
        checkLongTermItems();
    };

    // Validasi form sebelum submit
    $('#peminjamanForm').on('submit', function(e) {
        const hasLongTermItem = selectedItems.some(item => 
            item.category_name && item.category_name.toLowerCase() === 'barang jangka panjang'
        );

        if (hasLongTermItem && !$('#tanggalKembali').val()) {
            e.preventDefault();
            alert('Tanggal pengembalian wajib diisi karena ada barang jangka panjang!');
            $('#tanggalKembali').focus();
            return false;
        }
    });
});
</script>
@endpush

<style>
.search-container {
    position: relative;
}

.search-results {
    position: absolute;
    top: 100%;
    left: 0;
    right: 0;
    background: white;
    border: 1px solid #ddd;
    border-radius: 4px;
    max-height: 300px;
    overflow-y: auto;
    z-index: 1000;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.search-item {
    padding: 12px 15px;
    cursor: pointer;
    border-bottom: 1px solid #eee;
    transition: background-color 0.2s;
}

.search-item:hover {
    background-color: #f8f9fa;
}

.search-item:last-child {
    border-bottom: none;
}

.selected-item {
    background-color: #fff;
    transition: all 0.3s ease;
}

.selected-item:hover {
    background-color: #f8f9fa;
}

.badge {
    font-weight: normal;
    font-size: 0.85em;
}

#tanggalKembaliContainer {
    transition: all 0.3s ease;
    padding: 10px;
    border-radius: 4px;
}

#tanggalKembaliContainer.show {
    background-color: #fff8e1;
}
</style>

@endsection
