<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('peminjaman_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('peminjaman_id')->constrained('peminjaman')->onDelete('cascade');
            $table->foreignId('item_id')->constrained('item_inventory_table_v4')->onDelete('cascade');
            $table->integer('quantity');
            $table->enum('status', ['dipinjam', 'dikembalikan'])->default('dipinjam');
            $table->timestamps();

            // Tambahkan indeks untuk query yang lebih cepat
            $table->index(['peminjaman_id', 'item_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('peminjaman_items');
    }
};
