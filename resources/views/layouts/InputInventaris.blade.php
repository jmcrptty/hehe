@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-header">
            <h5>Tambah Item</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('items.store') }}" method="POST">
                @csrf

                <div class="form-group">
                    <label for="item_code">Kode Item</label>
                    <input type="text" class="form-control" id="item_code" name="item_code" required>
                </div>

                <div class="form-group">
                    <label for="item_name">Nama Item</label>
                    <input type="text" class="form-control" id="item_name" name="item_name" required>
                </div>

                <div class="form-group">
                    <label for="condition_name">Kondisi Item</label>
                    <select class="form-control" id="condition_name" name="condition_name" required>
                        <option value="Baik">Baik</option>
                        <option value="Rusak">Rusak</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="loan_status">Status Peminjaman</label>
                    <select class="form-control" id="loan_status" name="loan_status" required>
                        <option value="Dipinjam">Dipinjam</option>
                        <option value="Tersedia">Tersedia</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="type_name">Tipe Item</label>
                    <select class="form-control" id="type_name" name="type_name" required>
                        <option value="Barang Baru">Barang Baru</option>
                        <option value="Barang Lama">Barang Lama</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="category_name">Kategori Item</label>
                    <select class="form-control" id="category_name" name="category_name" required onchange="toggleThreshold()">
                        <option value="Barang Jangka Panjang">Barang Jangka Panjang</option>
                        <option value="Barang Habis Pakai">Barang Habis Pakai</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="storage_location_id">Lokasi Penyimpanan</label>
                    <select class="form-control" id="storage_location_id" name="storage_location_id" required>
                        @foreach($storageLocations as $storageLocation)
                            <option value="{{ $storageLocation->id }}">{{ $storageLocation->location_name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label for="laboratory_id">Laboratorium</label>
                    <select class="form-control" id="laboratory_id" name="laboratory_id" required>
                        @foreach($laboratories as $laboratory)
                            <option value="{{ $laboratory->id }}">{{ $laboratory->laboratory_name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label for="quantity">Jumlah</label>
                    <input type="number" class="form-control" id="quantity" name="quantity" required min="1">
                </div>

                <div class="form-group">
                    <label for="unit">Satuan</label>
                    <input type="text" class="form-control" id="unit" name="unit" required>
                </div>

                <div class="form-group">
                    <label for="date_acquired">Tanggal Diperoleh</label>
                    <input type="date" class="form-control" id="date_acquired" name="date_acquired" required>
                </div>

                <div class="form-group" id="threshold_field" style="display: none;">
                    <label for="threshold">Threshold</label>
                    <input type="number" class="form-control" id="threshold" name="threshold" min="1">
                </div>

                <button type="submit" class="btn btn-primary">Simpan</button>
            </form>
        </div>
    </div>
</div>

<script>
    function toggleThreshold() {
        const category = document.getElementById('category_name').value;
        const thresholdField = document.getElementById('threshold_field');

        if (category === 'Barang Habis Pakai') {
            thresholdField.style.display = 'block';
        } else {
            thresholdField.style.display = 'none';
        }
    }
</script>
@endsection