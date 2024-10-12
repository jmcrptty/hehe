@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <div class="card">
        <div class="card-header">
            <h2 class="card-title">Formulir Kerusakan Inventaris</h2>
        </div>
        <div class="card-body">
            <form action="#" method="POST">
                @csrf
                <div class="mb-3">
                    <label for="kode_barang" class="form-label">Kode Barang</label>
                    <div class="input-group">
                        <input type="text" class="form-control" id="kode_barang" name="kode_barang" required>
                        <button class="btn btn-outline-secondary" type="button" id="cari_barang">Cari</button>
                    </div>
                </div>

                <div id="info_barang" style="display: none;">
                    <table class="table table-bordered">
                        <tr>
                            <th>Nama Barang</th>
                            <td id="nama_barang"></td>
                        </tr>
                        <tr>
                            <th>Kategori</th>
                            <td id="kategori"></td>
                        </tr>
                        <tr>
                            <th>Lokasi</th>
                            <td id="lokasi"></td>
                        </tr>
                    </table>
                </div>

                <div class="mb-3">
                    <label for="deskripsi_kerusakan" class="form-label">Deskripsi Kerusakan</label>
                    <textarea class="form-control" id="deskripsi_kerusakan" name="deskripsi_kerusakan" rows="3" required></textarea>
                </div>

                <div class="mb-3">
                    <label for="tanggal_kerusakan" class="form-label">Tanggal Kerusakan</label>
                    <input type="date" class="form-control" id="tanggal_kerusakan" name="tanggal_kerusakan" required>
                </div>

                <button type="submit" class="btn btn-primary">Simpan</button>
            </form>
        </div>
    </div>
</div>

<script>
document.getElementById('cari_barang').addEventListener('click', function() {
    var kodeBarang = document.getElementById('kode_barang').value;
    // Di sini Anda akan menambahkan kode untuk mencari barang di database
    // Contoh sederhana:
    if (kodeBarang) {
        // Simulasi data dari database
        var barang = {
            nama: 'Contoh Barang',
            kategori: 'Contoh Kategori',
            lokasi: 'Contoh Lokasi'
        };
        
        document.getElementById('nama_barang').textContent = barang.nama;
        document.getElementById('kategori').textContent = barang.kategori;
        document.getElementById('lokasi').textContent = barang.lokasi;
        document.getElementById('info_barang').style.display = 'block';
    } else {
        alert('Masukkan kode barang terlebih dahulu');
    }
});
</script>

@endsection