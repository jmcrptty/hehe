<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class DosenController extends Controller
{
    public function index()
    {
        $dosenUsers = User::where('Role', 'Dosen')->get();
        return view('layouts.AkunDosen', compact('dosenUsers'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'userid' => 'required|string|max:255|unique:users,userid',
            'password' => 'required|string|min:8',
            'phone_number' => 'nullable|string|max:15',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('error', 'Gagal menambahkan dosen. Silakan cek kembali data yang dimasukkan.');
        }

        try {
            DB::beginTransaction();

            User::create([
                'name' => $request->name,
                'email' => $request->email,
                'userid' => $request->userid,
                'Role' => 'Dosen',
                'password' => Hash::make($request->password),
                'phone_number' => $request->phone_number,
            ]);

            DB::commit();
            return redirect()->route('AkunDosen.index')
                ->with('success', 'Akun dosen berhasil ditambahkan.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error creating dosen: ' . $e->getMessage());
            
            // Jika error adalah duplicate entry, berikan pesan yang lebih spesifik
            if (strpos($e->getMessage(), 'Duplicate entry') !== false) {
                return redirect()->back()
                    ->with('error', 'Email atau NIP sudah terdaftar.')
                    ->withInput();
            }

            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat menambahkan akun.')
                ->withInput();
        }
    }

    public function importCSV(Request $request)
    {
        if (!$request->hasFile('csv_file')) {
            return redirect()->back()
                ->with('error', 'Tidak ada file yang dipilih.');
        }

        $file = $request->file('csv_file');
        
        // Validasi file
        if ($file->getClientOriginalExtension() != 'csv') {
            return redirect()->back()
                ->with('error', 'File harus berformat CSV.');
        }

        try {
            DB::beginTransaction();

            $handle = fopen($file->getPathname(), 'r');
            $header = fgetcsv($handle); // Baca header
            $successCount = 0;
            $errors = [];
            $row = 2; // Mulai dari baris 2 (setelah header)

            while (($data = fgetcsv($handle)) !== false) {
                try {
                    if (count($data) < 5) {
                        $errors[] = "Baris $row: Data tidak lengkap";
                        continue;
                    }

                    // Cek duplikasi
                    $existingUser = User::where('email', trim($data[1]))
                        ->orWhere('userid', trim($data[2]))
                        ->first();

                    if ($existingUser) {
                        $errors[] = "Baris $row: Email atau NIP sudah terdaftar";
                        continue;
                    }

                    User::create([
                        'name' => trim($data[0]),
                        'email' => trim($data[1]),
                        'userid' => trim($data[2]),
                        'Role' => 'Dosen',
                        'password' => Hash::make(trim($data[4])),
                        'phone_number' => isset($data[5]) ? trim($data[5]) : null
                    ]);

                    $successCount++;
                } catch (\Exception $e) {
                    Log::error("Error pada baris $row: " . $e->getMessage());
                    $errors[] = "Baris $row: " . $e->getMessage();
                }
                $row++;
            }

            fclose($handle);
            DB::commit();

            if ($successCount > 0) {
                $message = "$successCount data berhasil diimpor.";
                if (!empty($errors)) {
                    $message .= " Beberapa error ditemukan: " . implode(", ", $errors);
                    return redirect()->route('AkunDosen.index')
                        ->with('warning', $message);
                }
                return redirect()->route('AkunDosen.index')
                    ->with('success', $message);
            } else {
                return redirect()->back()
                    ->with('error', 'Tidak ada data yang berhasil diimpor. ' . implode(", ", $errors));
            }

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('CSV import failed: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Gagal mengimpor data: ' . $e->getMessage());
        }
    }
}
