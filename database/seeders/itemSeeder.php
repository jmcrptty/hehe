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
        // Seeder untuk tabel item_conditions
        DB::table('item_conditions')->insert([
            ['condition_name' => 'Baik'],
            ['condition_name' => 'Rusak'],
        ]);

        // Seeder untuk tabel loan_statuses
        DB::table('loan_statuses')->insert([
            ['status_name' => 'Dipinjam'],
            ['status_name' => 'Tersedia'],
        ]);

        // Seeder untuk tabel item_types
        DB::table('item_types')->insert([
            ['type_name' => 'Barang Baru'],
            ['type_name' => 'Barang Lama'],
        ]);

        // Seeder untuk tabel item_categories
        DB::table('item_categories')->insert([
            ['category_name' => 'Barang Jangka Panjang'],
            ['category_name' => 'Barang Habis Pakai'],
        ]);

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
    }
}
