@extends('layouts.app')

@section('content')

<div class="container">
    <form action="{{ route('items.store') }}" method="POST">
        @csrf
        <!-- Input untuk Kode Barang -->
        <div class="mb-3">
            <label for="item_code" class="form-label">Kode Barang</label>
            <input type="text" class="form-control" id="item_code" name="item_code" required>
        </div>
        
        <!-- Input untuk Nama Barang -->
        <div class="mb-3">
            <label for="item_name" class="form-label">Nama Barang</label>
            <input type="text" class="form-control" id="item_name" name="item_name" required>
        </div>

        <!-- Pilihan Status Peminjaman -->
        <div class="mb-3">
            <label for="loan_status_id" class="form-label">Status Peminjaman</label>
            <select class="form-select" id="loan_status_id" name="loan_status_id">
                @foreach($loanStatuses as $status)
                    <option value="{{ $status->id }}">{{ $status->name }}</option>
                @endforeach
            </select>
        </div>

        <!-- Pilihan Jenis Barang -->
        <div class="mb-3">
            <label for="item_type_id" class="form-label">Jenis Barang</label>
            <select class="form-select" id="item_type_id" name="item_type_id" onchange="checkConsumable()">
                @foreach($itemTypes as $type)
                    <option value="{{ $type->id }}" data-consumable="{{ $type->is_consumable }}">{{ $type->name }}</option>
                @endforeach
            </select>
        </div>

        <!-- Pilihan Kategori Barang -->
        <div class="mb-3">
            <label for="item_category_id" class="form-label">Kategori Barang</label>
            <select class="form-select" id="item_category_id" name="item_category_id">
                @foreach($itemCategories as $category)
                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                @endforeach
            </select>
        </div>

        <!-- Input Jumlah Barang -->
        <div class="mb-3">
            <label for="quantity" class="form-label">Jumlah Barang</label>
            <input type="number" class="form-control" id="quantity" name="quantity" required>
        </div>

        <!-- Input Unit Barang -->
        <div class="mb-3">
            <label for="unit" class="form-label">Satuan Barang</label>
            <input type="text" class="form-control" id="unit" name="unit" required>
        </div>

        <!-- Input Lokasi Penyimpanan -->
        <div class="mb-3">
            <label for="storage_location_id" class="form-label">Lokasi Penyimpanan</label>
            <select class="form-select" id="storage_location_id" name="storage_location_id">
                @foreach($storageLocations as $location)
                    <option value="{{ $location->id }}">{{ $location->name }}</option>
                @endforeach
            </select>
        </div>

        <!-- Input Tanggal Akuisisi -->
        <div class="mb-3">
            <label for="date_acquired" class="form-label">Tanggal Akuisisi</label>
            <input type="date" class="form-control" id="date_acquired" name="date_acquired" required>
        </div>

        <!-- Input untuk Kondisi Barang -->
        <div class="mb-3">
            <label for="item_condition_id" class="form-label">Kondisi Barang</label>
            <select class="form-select" id="item_condition_id" name="item_condition_id">
                @foreach($itemConditions as $condition)
                    <option value="{{ $condition->id }}">{{ $condition->name }}</option>
                @endforeach
            </select>
        </div>

        <!-- Pilihan Laboratorium -->
        <div class="mb-3">
            <label for="laboratory_id" class="form-label">Laboratorium</label>
            <select class="form-select" id="laboratory_id" name="laboratory_id">
                @foreach($laboratories as $lab)
                    <option value="{{ $lab->id }}">{{ $lab->name }}</option>
                @endforeach
            </select>
        </div>

        <!-- Tombol Submit -->
        <button type="submit" class="btn btn-primary">Simpan Barang</button>
    </form>

    <!-- Peringatan jika barang hampir habis -->
    <div id="consumableWarning" style="display: none;" class="alert alert-warning mt-4">
        <strong>Peringatan!</strong> Barang ini termasuk barang habis pakai. Jika jumlah barang sudah mencapai batas minimal, perlu dilakukan pengadaan ulang.
    </div>
</div>

{{-- <script>
function checkConsumable() {
    var itemTypeSelect = document.getElementById('item_type_id');
    var selectedOption = itemTypeSelect.options[itemTypeSelect.selectedIndex];
    var isConsumable = selectedOption.getAttribute('data-consumable') === 'true';
    var warningDiv = document.getElementById('consumableWarning');

    if (isConsumable) {
        warningDiv.style.display = 'block';
    } else {
        warningDiv.style.display = 'none';
    }
}
</script> --}}
@endsection


