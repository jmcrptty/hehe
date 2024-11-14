<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
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
            'phone_number' => '081234567890', // Tambahkan nomor HP
        ]);

        User::create([
            'name' => 'Dosen Contoh',
            'email' => 'dosen@example.com',
            'userid' => 'DSN001',
            'role' => 'Dosen',
            'password' => Hash::make('password'),
            'phone_number' => '082345678901', // Tambahkan nomor HP
        ]);

        User::create([
            'name' => 'Admin Contoh',
            'email' => 'admin@example.com',
            'userid' => 'ADM001',
            'role' => 'Admin',
            'password' => Hash::make('password'),
            'phone_number' => '083456789012', // Tambahkan nomor HP
        ]);

        User::create([
            'name' => 'Super Admin Contoh',
            'email' => 'superadmin@example.com',
            'userid' => 'SADM001',
            'role' => 'SuperAdmin',
            'password' => Hash::make('password'),
            'phone_number' => '084567890123', // Tambahkan nomor HP
        ]);

        // Menambahkan data untuk tabel laboratories
        DB::table('laboratories')->insert([
            ['laboratory_name' => 'Lab 1'],
            ['laboratory_name' => 'Lab 2'],
        ]);

        // tambah data tabel gudang
        DB::table('storage_locations')->insert([
            ['location_name' => 'Gudang 1'],
            ['location_name' => 'Gudang 2'],
        ]);

        // Menambahkan data tabel item_inventory_table_v4
        DB::table('item_inventory_table_v4')->insert([
            ['item_code' => 'BRG0001', 'item_name' => 'Multimeter', 'condition_name' => 'Baik', 'loan_status' => 'Tersedia', 'type_name' => 'Barang Baru', 'category_name' => 'Barang jangka Panjang', 'storage_location_id' => 1, 'laboratory_id' => 1, 'quantity' => 5, 'unit' => 'Unit', 'date_acquired' => '2024-11-07'],
            ['item_code' => 'BRG0002', 'item_name' => 'Soldering Iron', 'condition_name' => 'Baik', 'loan_status' => 'Tersedia', 'type_name' => 'Barang Lama', 'category_name' => 'Barang Habis Pakai', 'storage_location_id' => 1, 'laboratory_id' => 2, 'quantity' => 7, 'unit' => 'Unit', 'date_acquired' => '2024-11-07'],
            ['item_code' => 'BRG0003', 'item_name' => 'Oscilloscope', 'condition_name' => 'Rusak', 'loan_status' => 'Dipinjam', 'type_name' => 'Barang Lama', 'category_name' => 'Barang jangka Panjang', 'storage_location_id' => 2, 'laboratory_id' => 1, 'quantity' => 2, 'unit' => 'Unit', 'date_acquired' => '2024-10-05'],
            ['item_code' => 'BRG0004', 'item_name' => 'Power Supply', 'condition_name' => 'Baik', 'loan_status' => 'Tersedia', 'type_name' => 'Barang Baru', 'category_name' => 'Barang jangka Panjang', 'storage_location_id' => 2, 'laboratory_id' => 2, 'quantity' => 3, 'unit' => 'Unit', 'date_acquired' => '2024-11-07'],
            ['item_code' => 'BRG0005', 'item_name' => 'Breadboard', 'condition_name' => 'Baik', 'loan_status' => 'Dipinjam', 'type_name' => 'Barang Lama', 'category_name' => 'Barang Habis Pakai', 'storage_location_id' => 1, 'laboratory_id' => 1, 'quantity' => 10, 'unit' => 'Unit', 'date_acquired' => '2024-11-01'],
            ['item_code' => 'BRG0006', 'item_name' => 'Resistor Set', 'condition_name' => 'Baik', 'loan_status' => 'Tersedia', 'type_name' => 'Barang Lama', 'category_name' => 'Barang Habis Pakai', 'storage_location_id' => 2, 'laboratory_id' => 2, 'quantity' => 15, 'unit' => 'Set', 'date_acquired' => '2024-11-07'],
            ['item_code' => 'BRG0007', 'item_name' => 'Capacitor Set', 'condition_name' => 'Baik', 'loan_status' => 'Tersedia', 'type_name' => 'Barang Baru', 'category_name' => 'Barang Habis Pakai', 'storage_location_id' => 1, 'laboratory_id' => 1, 'quantity' => 5, 'unit' => 'Set', 'date_acquired' => '2024-11-07'],
            ['item_code' => 'BRG0008', 'item_name' => 'Transistor Set', 'condition_name' => 'Rusak', 'loan_status' => 'Dipinjam', 'type_name' => 'Barang Lama', 'category_name' => 'Barang Habis Pakai', 'storage_location_id' => 2, 'laboratory_id' => 1, 'quantity' => 2, 'unit' => 'Set', 'date_acquired' => '2024-10-10'],
            ['item_code' => 'BRG0009', 'item_name' => 'Inductor Set', 'condition_name' => 'Baik', 'loan_status' => 'Tersedia', 'type_name' => 'Barang Lama', 'category_name' => 'Barang Habis Pakai', 'storage_location_id' => 1, 'laboratory_id' => 2, 'quantity' => 6, 'unit' => 'Set', 'date_acquired' => '2024-11-05'],
            ['item_code' => 'BRG0010', 'item_name' => 'LED Light Set', 'condition_name' => 'Baik', 'loan_status' => 'Dipinjam', 'type_name' => 'Barang Baru', 'category_name' => 'Barang Habis Pakai', 'storage_location_id' => 2, 'laboratory_id' => 1, 'quantity' => 12, 'unit' => 'Set', 'date_acquired' => '2024-11-07']
        ]);
    }
}
