<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class MahasiswaController extends Controller
{
    public function index()
    {
        $mahasiswaUsers = User::where('Role', 'Mahasiswa')->get();
        return view('layouts.AkunMahasiswa', compact('mahasiswaUsers'));
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
                ->with('error', 'Gagal menambahkan mahasiswa. Silakan cek kembali data yang dimasukkan.');
        }

        try {
            User::create([
                'name' => $request->name,
                'email' => $request->email,
                'userid' => $request->userid,
                'Role' => 'Mahasiswa',
                'password' => Hash::make($request->password),
                'phone_number' => $request->phone_number,
            ]);

            return redirect()->route('AkunMahasiswa.index')
                ->with('success', 'Akun mahasiswa berhasil ditambahkan.');
        } catch (\Exception $e) {
            Log::error('Error creating user: ' . $e->getMessage());
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
            $handle = fopen($file->getPathname(), 'r');
            
            // Debug: Print isi file
            $fileContent = file_get_contents($file->getPathname());
            Log::info("Isi file CSV:", ['content' => $fileContent]);

            $header = fgetcsv($handle); // Baca header
            Log::info("Header CSV:", ['header' => $header]); // Debug header

            $successCount = 0;
            $errors = [];
            $row = 2; // Mulai dari baris 2 (setelah header)

            while (($data = fgetcsv($handle)) !== false) {
                try {
                    // Debug: Print data per baris
                    Log::info("Data baris $row:", ['data' => $data]);

                    if (count($data) < 5) {
                        $errors[] = "Baris $row: Data tidak lengkap (kolom: " . count($data) . ")";
                        continue;
                    }

                    // Pastikan semua field required terisi
                    if (empty($data[0]) || empty($data[1]) || empty($data[2]) || empty($data[4])) {
                        $errors[] = "Baris $row: Data wajib tidak boleh kosong";
                        Log::warning("Data kosong pada baris $row:", [
                            'name' => $data[0],
                            'email' => $data[1],
                            'userid' => $data[2],
                            'password' => $data[4]
                        ]);
                        continue;
                    }

                    // Cek duplikasi
                    $existingUser = User::where('email', trim($data[1]))
                        ->orWhere('userid', trim($data[2]))
                        ->first();

                    if ($existingUser) {
                        $errors[] = "Baris $row: Email atau NPM sudah terdaftar";
                        continue;
                    }

                    // Buat array data user
                    $userData = [
                        'name' => trim($data[0]),
                        'email' => trim($data[1]),
                        'userid' => trim($data[2]),
                        'Role' => 'Mahasiswa',
                        'password' => Hash::make(trim($data[4])),
                    ];

                    // Tambahkan phone_number jika ada
                    if (isset($data[5]) && !empty($data[5])) {
                        $userData['phone_number'] = trim($data[5]);
                    }

                    // Debug: Print data yang akan diinsert
                    Log::info("Mencoba membuat user dengan data:", $userData);

                    // Coba create user
                    $user = User::create($userData);
                    Log::info("User berhasil dibuat:", ['user_id' => $user->id]);

                    $successCount++;

                } catch (\Exception $e) {
                    Log::error("Error pada baris $row:", [
                        'message' => $e->getMessage(),
                        'data' => $data,
                        'trace' => $e->getTraceAsString()
                    ]);
                    $errors[] = "Baris $row: " . $e->getMessage();
                }
                $row++;
            }

            fclose($handle);

            $message = "$successCount data berhasil diimpor.";
            if (!empty($errors)) {
                $message .= " Beberapa error ditemukan: " . implode(", ", $errors);
                Log::warning("Import selesai dengan error:", ['errors' => $errors]);
                return redirect()->route('AkunMahasiswa.index')
                    ->with('warning', $message);
            }

            Log::info("Import berhasil:", ['success_count' => $successCount]);
            return redirect()->route('AkunMahasiswa.index')
                ->with('success', $message);

        } catch (\Exception $e) {
            Log::error('CSV import failed:', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return redirect()->back()
                ->with('error', 'Gagal mengimpor data: ' . $e->getMessage());
        }
    }
}