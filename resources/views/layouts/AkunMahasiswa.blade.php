@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="mb-4">Tambah Akun Mahasiswa</h2>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card mb-4">
        <div class="card-header">
            Upload CSV
        </div>
        <div class="card-body">
            <form action="{{ route('AkunMahasiswa.import') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="form-group">
                    <label for="csv_file">Pilih file CSV</label>
                    <input type="file" class="form-control-file" id="csv_file" name="csv_file" required>
                </div>
                <button type="submit" class="btn btn-primary mt-3">Upload</button>
            </form>
        </div>
    </div>
    
    <div class="card mb-4">
        <div class="card-header" id="tambahAkunHeader">
            <h5 class="mb-0">
                <button class="btn" type="button" data-bs-toggle="collapse" data-bs-target="#tambahAkunCollapse" aria-expanded="false" aria-controls="tambahAkunCollapse">
                    Tambah Akun Mahasiswa Satu Persatu
                </button>
            </h5>
        </div>
        <div id="tambahAkunCollapse" class="collapse" aria-labelledby="tambahAkunHeader">
            <div class="card-body">
                <form action="{{ route('AkunMahasiswa.store') }}" method="POST">
                    @csrf
                    <div class="form-group mb-3">
                        <label for="name">Nama</label>
                        <input type="text" class="form-control" id="name" name="name" required>
                    </div>
                    <div class="form-group mb-3">
                        <label for="email">Email</label>
                        <input type="email" class="form-control" id="email" name="email" required>
                    </div>
                    <div class="form-group mb-3">
                        <label for="userid">NPM</label>
                        <input type="text" class="form-control" id="userid" name="userid" required>
                    </div>
                    <div class="form-group mb-3">
                        <label for="password">Password</label>
                        <input type="password" class="form-control" id="password" name="password" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Tambah Akun</button>
                </form>
            </div>
        </div>
    </div>

    <h3 class="mb-3">Daftar Akun Mahasiswa</h3>
    <div class="table-responsive">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama</th>
                    <th>Email</th>
                    <th>NPM</th>
                </tr>
            </thead>
            <tbody>
                @foreach($mahasiswaUsers as $mahasiswa)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $mahasiswa->name }}</td>
                    <td>{{ $mahasiswa->email }}</td>
                    <td>{{ $mahasiswa->userid }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
