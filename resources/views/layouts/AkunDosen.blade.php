@extends('layouts.app')

@extends('tilte','Tambah Akun Dosen')
@section('content')
<div class="container">
    <h2 class="mb-4">Tambah Akun Dosen</h2>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <div class="card mb-4">
        <div class="card-header" id="tambahAkunHeader">
            <h5 class="mb-0">
                <button class="btn" type="button" data-bs-toggle="collapse" data-bs-target="#tambahAkunCollapse" aria-expanded="false" aria-controls="tambahAkunCollapse">
                    Tambah Akun Dosen
                </button>
            </h5>
        </div>
        <div id="tambahAkunCollapse" class="collapse" aria-labelledby="tambahAkunHeader">
            <div class="card-body">
                <form action="{{ route('AkunDosen.store') }}" method="POST">
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
                        <label for="userid">NIP</label>
                        <input type="text" class="form-control" id="userid" name="userid" required>
                    </div>
                    <div class="form-group mb-3">
                        <label for="Role">Role</label>
                        <select class="form-control" id="Role" name="Role" required>
                            <option value="Dosen" selected>Dosen</option>
                        </select>
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

    <h3 class="mb-3">Daftar Akun Dosen</h3>
    <div class="table-responsive">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama</th>
                    <th>Email</th>
                    <th>NIP</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $dosenUsers = \App\Models\User::where('Role', 'Dosen')->get();
                @endphp
                @foreach($dosenUsers as $dosen)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $dosen->name }}</td>
                    <td>{{ $dosen->email }}</td>
                    <td>{{ $dosen->userid }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection