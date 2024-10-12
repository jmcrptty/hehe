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
            $table->foreignId('loan_status_id')->constrained('loan_statuses')->onDelete('cascade');
            $table->foreignId('item_type_id')->constrained('item_types')->onDelete('cascade');
            $table->foreignId('item_category_id')->constrained('item_categories')->onDelete('cascade');
            $table->foreignId('item_condition_id')->constrained('item_conditions')->onDelete('cascade');
            $table->foreignId('storage_location_id')->constrained('Storage_locations')->onDelete('cascade');
            $table->foreignId('laboratory_id')->constrained('laboratories')->onDelete('cascade');
            $table->integer('quantity');
            $table->string('unit');
            $table->date('date_acquired');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('item_inventory_table_v4');
    }
};
