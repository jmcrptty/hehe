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
                                </div>
                                <div class="mb-3" id="tanggalKembaliContainer" style="display: none;">
                                    <label class="form-label">Tanggal Pengembalian</label>
                                    <input type="date" class="form-control" name="tanggal_kembali" 
                                           min="{{ now()->addDay()->format('Y-m-d') }}">
                                </div>
                            </div>
                        </div>

                        <!-- Ganti bagian pencarian barang -->
                        <div class="row mb-4">
                            <div class="col-md-8">
                                <div class="mb-3">
                                    <label class="form-label">Cari Barang</label>
                                    <div class="search-container">
                                        <input type="text" class="form-control" id="searchInput" placeholder="Ketik untuk mencari barang..." autocomplete="off">
                                        <input type="hidden" name="item_id" id="selectedItemId" required>
                                        <div class="search-results" id="searchResults" style="display: none;">
                                            <!-- Hasil pencarian akan muncul di sini -->
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label class="form-label">Jumlah Peminjaman</label>
                                    <input type="number" class="form-control" name="quantity" min="1" value="1">
                                    <small class="text-muted">Stok tersedia: <span id="availableStock">0</span></small>
                                </div>
                            </div>
                        </div>

                        <!-- Setelah bagian pencarian barang, tambahkan card barang terpilih -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <div id="selectedItemCard" style="display: none;">
                                    <div class="card border">
                                        <div class="card-body">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <div>
                                                    <h6 class="mb-0">Barang yang dipilih:</h6>
                                                    <p class="mb-0 mt-2" id="selectedItemInfo"></p>
                                                </div>
                                                <button type="button" class="btn btn-sm btn-outline-danger" id="clearSelection">
                                                    <i class="fas fa-times"></i> Hapus
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Tambahkan setelah card barang terpilih -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <div id="selectedItemsList">
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
                        </div>

                        <!-- Tambahkan setelah daftar barang yang dipilih dan sebelum tombol submit -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <div class="card border">
                                    <div class="card-header bg-white">
                                        <h6 class="mb-0">Keterangan Peminjaman</h6>
                                    </div>
                                    <div class="card-body">
                                        <textarea class="form-control" 
                                                  name="keterangan" 
                                                  rows="3" 
                                                  placeholder="Tuliskan keterangan atau tujuan peminjaman barang..."></textarea>
                                        <small class="text-muted">
                                            Contoh: Untuk praktikum Elektronika Dasar, Untuk project akhir, dll.
                                        </small>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Informasi Barang -->
                        <div id="itemInfo" class="mb-4" style="display: none;">
                            <div class="alert alert-info">
                                <div class="row">
                                    <div class="col-md-4">
                                        <strong>Kategori:</strong> <span id="itemCategory"></span>
                                    </div>
                                    <div class="col-md-4">
                                        <strong>Kondisi:</strong> <span id="itemCondition"></span>
                                    </div>
                                    <div class="col-md-4">
                                        <strong>Tipe:</strong> <span id="itemType"></span>
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

@push('styles')
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
    border: 1px solid #dee2e6;
    border-radius: 4px;
    margin-top: 2px;
    max-height: 200px;
    overflow-y: auto;
    z-index: 1000;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.search-item {
    padding: 8px 12px;
    cursor: pointer;
    border-bottom: 1px solid #dee2e6;
    background: white;
}

.search-item:hover {
    background-color: #f8f9fa;
}

.search-item.selected {
    background-color: #e9ecef;
}

.search-item:last-child {
    border-bottom: none;
}

#selectedItemCard {
    transition: all 0.3s ease;
}

#selectedItemCard .card {
    border-color: #dee2e6;
}

#selectedItemCard .card-body {
    padding: 1rem;
}

#selectedItemInfo strong {
    color: #495057;
}

.selected-item {
    background-color: #fff;
    transition: all 0.3s ease;
}

.selected-item:hover {
    background-color: #f8f9fa;
}
</style>
@endpush

@push('scripts')
<script>
$(document).ready(function() {
    const searchInput = $('#searchInput');
    const searchResults = $('#searchResults');
    const selectedItemId = $('#selectedItemId');
    let selectedItems = [];
    let debounceTimer;

    // Fungsi pencarian
    searchInput.on('input', function() {
        clearTimeout(debounceTimer);
        const query = $(this).val();

        debounceTimer = setTimeout(function() {
            if (query.length > 0) {
                $.ajax({
                    url: '{{ route("peminjaman.search") }}',
                    data: { search: query },
                    success: function(data) {
                        let html = '';
                        if (data.length > 0) {
                            data.forEach(function(item) {
                                console.log('Item data:', item);
                                html += `
                                    <div class="search-item" 
                                         data-id="${item.id}" 
                                         data-max="${item.quantity}"
                                         data-category="${item.category_name}">
                                        [${item.item_code}] ${item.item_name} - Stok: ${item.quantity} ${item.unit}
                                    </div>
                                `;
                            });
                        } else {
                            html = '<div class="search-item">Barang tidak ditemukan</div>';
                        }
                        searchResults.html(html).show();
                    }
                });
            } else {
                searchResults.hide();
            }
        }, 300);
    });

    // Update fungsi pilih item
    $(document).on('click', '.search-item', function() {
        if ($(this).data('id')) {
            if (selectedItems.length >= 3) {
                alert('Maksimal 3 barang yang dapat dipinjam!');
                return;
            }

            const itemId = $(this).data('id');
            
            if (selectedItems.some(item => item.id === itemId)) {
                alert('Barang ini sudah dipilih!');
                return;
            }

            const itemCode = $(this).text().match(/\[(.*?)\]/)[1];
            const itemName = $(this).text().split(']')[1].split('-')[0].trim();
            const maxStock = $(this).data('max');
            const itemCategory = $(this).data('category');

            console.log('Selected item data:', {
                id: itemId,
                code: itemCode,
                name: itemName,
                maxStock: maxStock,
                category: itemCategory
            });

            selectedItems.push({
                id: itemId,
                code: itemCode,
                name: itemName,
                maxStock: maxStock,
                quantity: 1,
                category: itemCategory
            });

            updateSelectedItemsList();
            checkItemCategories();
            searchInput.val('');
            searchResults.hide();
        }
    });

    // Tambah fungsi untuk hapus pilihan
    $('#clearSelection').click(function() {
        selectedItemId.val('');
        $('#selectedItemCard').slideUp();
        $('#availableStock').text('0');
        $('input[name="quantity"]').val(1);
    });

    // Tutup hasil pencarian saat klik di luar
    $(document).on('click', function(e) {
        if (!$(e.target).closest('.search-container').length) {
            searchResults.hide();
        }
    });

    // Fungsi untuk update tampilan daftar barang
    function updateSelectedItemsList() {
        const container = $('#selectedItemsContainer');
        const emptyMessage = $('#emptyItemsMessage');

        if (selectedItems.length > 0) {
            let html = '';
            selectedItems.forEach((item, index) => {
                html += `
                    <div class="selected-item mb-3 p-3 border rounded">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <strong>[${item.code}]</strong> ${item.name}
                                <br>
                                <small class="text-muted">Kategori: ${item.category}</small>
                            </div>
                            <div class="d-flex align-items-center">
                                <div class="me-3">
                                    <label class="me-2">Jumlah:</label>
                                    <input type="number" 
                                           class="form-control form-control-sm d-inline-block" 
                                           style="width: 80px"
                                           name="items[${item.id}]" 
                                           value="${item.quantity}"
                                           min="1"
                                           max="${item.maxStock}"
                                           onchange="updateQuantity(${index}, this.value)">
                                    <small class="text-muted ms-2">Stok: ${item.maxStock}</small>
                                </div>
                                <button type="button" class="btn btn-sm btn-outline-danger" onclick="removeItem(${index})">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                `;
            });
            container.html(html);
            emptyMessage.hide();
            container.show();
        } else {
            container.hide();
            emptyMessage.show();
        }
    }

    // Fungsi untuk update quantity
    window.updateQuantity = function(index, value) {
        value = parseInt(value);
        const maxStock = selectedItems[index].maxStock;
        
        if (value > maxStock) {
            alert('Jumlah melebihi stok tersedia!');
            value = maxStock;
        }
        if (value < 1) value = 1;
        
        selectedItems[index].quantity = value;
        updateSelectedItemsList();
    }

    // Fungsi untuk hapus item
    window.removeItem = function(index) {
        selectedItems.splice(index, 1);
        updateSelectedItemsList();
        checkItemCategories();
    }

    // Update fungsi checkItemCategories
    function checkItemCategories() {
        const hasLongTermItem = selectedItems.some(item => 
            item.category === 'Barang Jangka Panjang'
        );
        
        console.log('Selected items:', selectedItems);
        console.log('Has long term item:', hasLongTermItem);

        const tanggalKembaliContainer = $('#tanggalKembaliContainer');
        const tanggalKembaliInput = $('input[name="tanggal_kembali"]');

        if (hasLongTermItem) {
            tanggalKembaliContainer.show();
            tanggalKembaliInput.prop('required', true);
        } else {
            tanggalKembaliContainer.hide();
            tanggalKembaliInput.prop('required', false);
            tanggalKembaliInput.val('');
        }
    }

    // Update form validation
    $('#peminjamanForm').on('submit', function(e) {
        e.preventDefault();

        if (selectedItems.length === 0) {
            alert('Silakan pilih minimal satu barang!');
            return;
        }

        const hasLongTermItem = selectedItems.some(item => 
            item.category.toLowerCase() === 'barang jangka panjang'
        );
        if (hasLongTermItem) {
            let tanggalKembali = new Date($('input[name="tanggal_kembali"]').val());
            let today = new Date();
            
            if (!$('input[name="tanggal_kembali"]').val()) {
                alert('Tanggal pengembalian harus diisi untuk barang jangka panjang!');
                return;
            }
            
            if (tanggalKembali <= today) {
                alert('Tanggal pengembalian harus lebih dari hari ini!');
                return;
            }
        }

        // Submit form jika validasi berhasil
        this.submit();
    });
});
</script>
@endpush
@endsection