<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Peminjaman;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function userDashboard()
    {
        // Ambil peminjaman terbaru dari user yang sedang login
        $peminjaman = Peminjaman::where('user_id', Auth::id())
                               ->with(['user', 'items'])
                               ->latest()
                               ->first();

        return view('user.dashboard', compact('peminjaman'));
    }

    public function adminDashboard()
    {
        // Ambil semua peminjaman untuk admin, urutkan berdasarkan yang terbaru
        $peminjaman = Peminjaman::with(['user', 'items'])
                               ->orderBy('created_at', 'desc')
                               ->get();

        return view('admin.dashboard', compact('peminjaman'));
    }
} 