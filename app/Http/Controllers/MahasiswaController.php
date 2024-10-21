<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use League\Csv\Reader;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class MahasiswaController extends Controller
{
    public function index()
    {
        $mahasiswaUsers = User::where('Role', 'Mahasiswa')->get(); // Tetap menggunakan 'Role' dengan huruf R besar
        return view('layouts.AkunMahasiswa', compact('mahasiswaUsers'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'userid' => 'required|string|max:255|unique:users,userid',
            'password' => 'required|string|min:8',
            'phone_number' => 'nullable|string|max:15', // Tambahkan validasi untuk nomor HP
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'userid' => $request->userid,
            'Role' => 'Mahasiswa', // Tetap menggunakan 'Role' dengan huruf R besar
            'password' => Hash::make($request->password),
            'phone_number' => $request->phone_number, // Tambahkan nomor HP
        ]);

        return redirect()->route('AkunMahasiswa')->with('success', 'Akun mahasiswa berhasil ditambahkan');
    }

    public function importCSV(Request $request)
    {
        $request->validate([
            'csv_file' => 'required|file|mimes:csv,txt',
        ]);

        $file = $request->file('csv_file');
        $csv = Reader::createFromPath($file->getPathname(), 'r');
        $csv->setHeaderOffset(0);

        $records = $csv->getRecords();
        $importCount = 0;

        DB::beginTransaction();

        try {
            foreach ($records as $record) {
                $validator = Validator::make($record, [
                    'name' => 'required|string|max:255',
                    'email' => 'required|string|email|max:255|unique:users',
                    'userid' => 'required|string|max:255|unique:users,userid',
                    'password' => 'required|string|min:8',
                    'phone_number' => 'nullable|string|max:15', // Tambahkan validasi untuk nomor HP
                ]);

                if ($validator->fails()) {
                    Log::warning("Skipping invalid record: ", $record);
                    continue;
                }

                User::create([
                    'name' => $record['name'],
                    'email' => $record['email'],
                    'userid' => $record['userid'],
                    'Role' => 'Mahasiswa', // Tetap menggunakan 'Role' dengan huruf R besar
                    'password' => Hash::make($record['password']),
                    'phone_number' => $record['phone_number'] ?? null, // Tambahkan nomor HP jika ada
                ]);

                $importCount++;
            }

            DB::commit();
            return redirect()->route('AkunMahasiswa')->with('success', "$importCount data mahasiswa berhasil diimpor");
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("CSV import failed: " . $e->getMessage());
            return redirect()->route('AkunMahasiswa')->with('error', 'Terjadi kesalahan saat mengimpor data. Silakan coba lagi.');
        }
    }
}
