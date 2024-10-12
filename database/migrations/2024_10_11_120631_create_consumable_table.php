<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIsConsumableToItemTypesTable extends Migration
{
    public function up()
    {
        Schema::table('item_types', function (Blueprint $table) {
            $table->boolean('is_consumable')->default(false);
        });
    }

    public function down()
    {
        Schema::table('item_types', function (Blueprint $table) {
            $table->dropColumn('is_consumable');
        });
    }
}
