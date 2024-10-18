@extends('layouts.app')

@section('content')
<h1 class="mt-4">Dashboard</h1>
            <div class="card">
                <div class="card-header">Informasi Akun</div>

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

@endsection
