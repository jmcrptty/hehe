@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h3 class="card-title">Daftar Inventaris</h3>
            <a href="#" class="btn btn-primary">Peminjaman</a> <!-- Tombol biru di sebelah kanan -->
        </div>
        <div class="card-body">
            <table class="table table-bordered table-striped">
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
                    {{-- @foreach($inventaris as $index => $item)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $item->nama_barang }}</td>
                        <td>{{ $item->kode_barang }}</td>
                        <td>{{ $item->kategori }}</td>
                        <td>{{ $item->kondisi }}</td>
                        <td>{{ $item->status_peminjaman }}</td>
                        <td>{{ $item->jumlah }}</td>
                    </tr>
                    @endforeach --}}
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection