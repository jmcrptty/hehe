@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <div class="card shadow-sm rounded-lg">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h3 class="card-title">Daftar Inventaris</h3>
            <a href="#" class="btn btn-primary">Peminjaman</a> <!-- Tombol biru di sebelah kanan -->
        </div>
        <div class="card-body table-responsive"> <!-- Table responsive for better mobile view -->
            <table class="table table-bordered table-striped">
                <form class="d-none d-md-inline-block form-inline ms-auto me-0 me-md-3 my-2 my-md-0 mt">
                    <div class="input-group">
                        <input class="form-control" type="text" placeholder="Search for..." aria-label="Search for..." aria-describedby="btnNavbarSearch" />
                        <button class="btn btn-primary" id="btnNavbarSearch" type="button"><i class="fas fa-search"></i></button>
                    </div>
                </form>
                <thead>
                    <tr>
                        <th>No.</th>
                        <th>Nama Barang</th>
                        <th>Kode Barang</th>
                        <th>Kategori</th>
                        <th>Kondisi</th>
                        <th>Status Peminjaman</th>
                        <th>Jumlah</th>
                        <th>Detail</th>
                    </tr>
                </thead>
                <tbody>
                    {{-- @foreach($inventaris as $index => $item) --}}
                    <tr>
                        <td>1</td>
                        <td>Barang Contoh</td>
                        <td>KD-123</td>
                        <td>Elektronik</td>
                        <td>Baik</td>
                        <td>Dipinjam</td>
                        <td>10</td>
                        <td><a href="#" class="btn btn-info btn-sm">Lihat Detail</a></td>
                    </tr>
                    <tr>
                        <td>2</td>
                        <td>Barang Contoh 2</td>
                        <td>KD-456</td>
                        <td>Alat Tulis</td>
                        <td>Rusak</td>
                        <td>Tersedia</td>
                        <td>5</td>
                        <td><a href="#" class="btn btn-info btn-sm">Lihat Detail</a></td>
                    </tr>
                    {{-- @endforeach --}}
                </tbody>
            </table>
        </div>
    </div>
</div>


@endsection