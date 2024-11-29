<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Peminjaman;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function userDashboard()
    {
        $user = Auth::user();
        
        // Ambil peminjaman terkini
        $peminjamanTerkini = Peminjaman::where('user_id', $user->id)
            ->with('items')
            ->latest()
            ->first();
        
        // Ambil semua riwayat peminjaman user
        $riwayatPeminjaman = Peminjaman::where('user_id', $user->id)
            ->with('items')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('user.dashboard', compact('riwayatPeminjaman', 'peminjamanTerkini'));
    }

    public function adminDashboard()
    {
        // Ambil peminjaman aktif (yang belum selesai/ditolak)
        $peminjaman = Peminjaman::with(['user', 'items'])
            ->whereNotIn('status', ['selesai', 'ditolak'])
            ->orderBy('created_at', 'desc')
            ->get();

        // Ambil riwayat peminjaman (yang sudah selesai/ditolak)
        $riwayatPeminjaman = Peminjaman::with(['user', 'items'])
            ->whereIn('status', ['selesai', 'ditolak'])
            ->orderBy('updated_at', 'desc')
            ->get();

        return view('admin.dashboard', compact('peminjaman', 'riwayatPeminjaman'));
    }
} 