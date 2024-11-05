<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ConditionSeeder extends Seeder
{
    public function run()
    {
        DB::table('conditions')->insert([
            ['condition_name' => 'Baik'],
            ['condition_name' => 'Rusak']
        ]);
    }
} 