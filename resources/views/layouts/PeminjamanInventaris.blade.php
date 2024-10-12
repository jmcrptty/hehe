@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <div class="card">
        <div class="card-header">
            <h2 class="card-title">Formulir Peminjaman Inventaris</h2>
        </div>
        <div class="card-body">
            <form action="#" method="POST">
                @csrf
                <div class="mb-3">
                    <label for="barang" class="form-label">Pilih Barang</label>
                    <select class="form-select" id="barang" name="barang_id" required>
                        <option value="">Pilih barang yang akan dipinjam</option>
                        {{-- @foreach($barangInventaris as $barang)
                            <option value="{{ $barang->id }}">{{ $barang->nama_barang }} - {{ $barang->kode_barang }}</option>
                        @endforeach --}}
                    </select>
                </div>
                <div class="mb-3">
                    <label for="jumlah" class="form-label">Jumlah</label>
                    <input type="number" class="form-control" id="jumlah" name="jumlah" min="1" required>
                </div>
                <div class="mb-3">
                    <label for="tanggal_pinjam" class="form-label">Tanggal Pinjam</label>
                    <input type="date" class="form-control" id="tanggal_pinjam" name="tanggal_pinjam" required>
                </div>
                <div class="mb-3">
                    <label for="tanggal_kembali" class="form-label">Tanggal Kembali</label>
                    <input type="date" class="form-control" id="tanggal_kembali" name="tanggal_kembali" required>
                </div>
                <div class="mb-3">
                    <label for="tujuan" class="form-label">Tujuan Peminjaman</label>
                    <textarea class="form-control" id="tujuan" name="tujuan" rows="3" required></textarea>
                </div>
                <button type="submit" class="btn btn-primary">Ajukan Peminjaman</button>
            </form>
        </div>
    </div>
</div>
<div class="container mt-4">
    <div class="card">
        <div class="card-header">
            <h2 class="card-title">Status Peminjaman</h2>
        </div>
        <div class="card-body">
            {{-- @if(session('status'))
                <div class="alert alert-info">
                    {{ session('status') }}
                </div>
            @endif

            @if($peminjaman && $peminjaman->status == 'menunggu')
                <div class="alert alert-warning">
                    Peminjaman Anda sedang menunggu persetujuan laboran.
                </div>
            @elseif($peminjaman && $peminjaman->status == 'disetujui')
                <div class="alert alert-success">
                    Peminjaman Anda sudah disetujui. Silakan mengambil barang pada laboran.
                </div>
            @endif --}}
        </div>
    </div>
</div>



@endsection