@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3">
        <h1 class="h2">Dashboard User</h1>
    </div>

    <!-- Welcome Message -->
    <div class="alert alert-primary" role="alert">
        Selamat datang, <strong>{{ Auth::user()->name }}</strong>! Semoga harimu menyenangkan.
    </div>

    <!-- Informasi Akun -->
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-header bg-primary text-white">
            <h5 class="card-title mb-0">Informasi Akun</h5>
        </div>
        <div class="card-body">
            <table class="table table-hover">
                <tr>
                    <th width="200">Nama:</th>
                    <td>{{ Auth::user()->name }}</td>
                </tr>
                <tr>
                    <th>Npm:</th>
                    <td>{{ Auth::user()->userid }}</td>
                </tr>
                <tr>
                    <th>Email:</th>
                    <td>{{ Auth::user()->email }}</td>
                </tr>
                <tr>
                    <th>Role:</th>
                    <td>{{ ucfirst(Auth::user()->Role) }}</td>
                </tr>
            </table>
        </div>
    </div>
</div>
@endsection
