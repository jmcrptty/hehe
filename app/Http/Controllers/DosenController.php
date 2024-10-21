<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class DosenController extends Controller
{
    public function index()
    {
        $dosenUsers = User::where('Role', 'Dosen')->get();
        return view('layouts.AkunDosen', compact('dosenUsers'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'userid' => 'required|string|max:255|unique:users,userid',
            'phone_number' => 'required|string|max:15', // Tambahkan validasi untuk nomor telepon
            'Role' => 'required|in:Dosen',
            'password' => 'required|string|min:8',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'userid' => $request->userid,
            'phone_number' => $request->phone_number, // Simpan nomor telepon ke database
            'Role' => $request->Role,
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('AkunDosen')->with('success', 'Akun dosen berhasil ditambahkan');
    }
}
