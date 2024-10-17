<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('item_inventory_table_v4', function (Blueprint $table) {
            $table->id(); // Tambahkan id jika belum ada
            $table->string('item_code');
            $table->string('item_name');
            $table->enum('condition_name', ['Baik', 'Rusak'])->default('Baik'); 
            $table->enum('loan_status', ['Dipinjam', 'Tersedia'])->default('Tersedia');
            $table->enum('type_name', ['Barang Baru', 'Barang Lama'])->default('Barang Baru');
            $table->enum('category_name', ['Barang Jangka Panjang', 'Barang Habis Pakai'])->default('Barang Jangka Panjang');
            $table->foreignId('storage_location_id')->constrained('Storage_locations')->onDelete('cascade');
            $table->foreignId('laboratory_id')->constrained('laboratories')->onDelete('cascade');
            $table->integer('quantity');
            $table->string('unit');
            $table->date('date_acquired');
            $table->integer('threshold')->nullable(); // Kolom threshold
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('item_inventory_table_v4');
    }
};
