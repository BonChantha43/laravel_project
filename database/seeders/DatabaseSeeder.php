<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DataSeeder extends Seeder
{
    public function run()
    {
        // ១. បញ្ចូល Categories (ប្រភេទ)
        DB::table('categories')->insert([
            ['name' => 'ភេសជ្ជៈ (Drinks)', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'អាហារ (Foods)', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'គ្រឿងទេស (Ingredients)', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'សម្ភារៈប្រើប្រាស់ (Supplies)', 'created_at' => now(), 'updated_at' => now()],
        ]);

        // ២. បញ្ចូល Suppliers (អ្នកផ្គត់ផ្គង់)
        DB::table('suppliers')->insert([
            ['name' => 'Coca-Cola Company', 'phone' => '012333444', 'address' => 'Phnom Penh', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Cambodia Beer', 'phone' => '012555666', 'address' => 'Phnom Penh', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Unilever', 'phone' => '012777888', 'address' => 'Phnom Penh', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}