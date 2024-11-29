<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('peminjaman', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->date('tanggal_pinjam');
            $table->date('tanggal_kembali')->nullable();
            $table->enum('status', ['menunggu', 'disetujui', 'dipinjam', 'menunggu pengembalian', 'terlambat', 'selesai', 'ditolak'])->default('menunggu');
            $table->text('keterangan')->nullable();

            // Administrator actions
            $table->foreignId('approved_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('approved_at')->nullable();
            $table->foreignId('rejected_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('rejected_at')->nullable();

            // User actions
            $table->foreignId('returned_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('returned_at')->nullable();

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('peminjaman');
    }
};
