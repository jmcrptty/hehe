<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Seeder untuk tabel laboratories
        DB::table('laboratories')->insert([
            ['laboratory_name' => 'Laboratorium komputer'],
            ['laboratory_name' => 'Laboratorium instalasi'],
        ]);

        // Seeder untuk tabel storage_locations
        DB::table('storage_locations')->insert([
            ['location_name' => 'lemari 1'],
            ['location_name' => 'lemari 2'],
            ['location_name' => 'lemari 3'],
            ['location_name' => 'lemari 4'],
        ]);

        DB::table('item_inventory_table_v4')->insert([
            [
                'item_code' => 'ABC123',
                'item_name' => 'Kabel Listrik',
                'condition_name' => 'Baik',
                'loan_status' => 'Tersedia',
                'type_name' => 'Barang Baru',
                'category_name' => 'Barang Habis Pakai',
                'storage_location_id' => 1, // Ganti dengan id lokasi penyimpanan yang sesuai
                'laboratory_id' => 1, // Ganti dengan id laboratorium yang sesuai
                'quantity' => 100,
                'unit' => 'Meter',
                'date_acquired' => '2023-10-01',
                'threshold' => 10,
            ],
            [
                'item_code' => 'DEF456',
                'item_name' => 'Komputer',
                'condition_name' => 'Baik',
                'loan_status' => 'Dipinjam',
                'type_name' => 'Barang Lama',
                'category_name' => 'Barang Jangka Panjang',
                'storage_location_id' => 2, // Ganti dengan id lokasi penyimpanan yang sesuai
                'laboratory_id' => 2, // Ganti dengan id laboratorium yang sesuai
                'quantity' => 5,
                'unit' => 'Unit',
                'date_acquired' => '2023-05-15',
                'threshold' => null,
            ]
        ]);
    }
}
