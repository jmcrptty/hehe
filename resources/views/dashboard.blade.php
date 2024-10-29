@extends('layouts.app')

@section('title', 'Dashboard')
@section('content')
<h1 class="mt-4">Dashboard</h1>

<div class="row justify-content-center">
    <!-- Welcome Message -->
    <div class="col-md-12">
        <div class="alert alert-primary" role="alert">
            Selamat datang, <strong>{{ Auth::user()->name }}</strong>! Semoga harimu menyenangkan.
        </div>
    </div>

    <!-- Informasi Akun -->
    <div class="col-md-12 mt-3">
        <div class="card shadow-sm border-0 rounded-lg" style="background: linear-gradient(135deg, #f3f4f6 0%, #e0e0e0 100%);">
            <div class="card-header bg-primary text-white">Informasi Akun</div>
            <div class="card-body">
                <table class="table">
                    <tr>
                        <th>Nama:</th>
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
                        <td>{{ Auth::user()->Role }}</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>




@endsection
