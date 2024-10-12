<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Membuat user untuk setiap peran
        User::create([
            'name' => 'Mahasiswa Contoh',
            'email' => 'mahasiswa@example.com',
            'userid' => 'MHS001',
            'role' => 'Mahasiswa',
            'password' => Hash::make('password'),
        ]);

        User::create([
            'name' => 'Dosen Contoh',
            'email' => 'dosen@example.com',
            'userid' => 'DSN001',
            'role' => 'Dosen',
            'password' => Hash::make('password'),
        ]);

        User::create([
            'name' => 'Admin Contoh',
            'email' => 'admin@example.com',
            'userid' => 'ADM001',
            'role' => 'Admin',
            'password' => Hash::make('password'),
        ]);

        User::create([
            'name' => 'Super Admin Contoh',
            'email' => 'superadmin@example.com',
            'userid' => 'SADM001',
            'role' => 'SuperAdmin',
            'password' => Hash::make('password'),
        ]);
    }
}
