<?php

namespace Database\Seeders;

use App\Models\RestaurantTable;
use Illuminate\Database\Seeder;

class TableSeeder extends Seeder
{
    public function run(): void
    {
        $tables = [
            ['table_number' => 'T1', 'capacity' => 2, 'location' => 'Main Dining'],
            ['table_number' => 'T2', 'capacity' => 2, 'location' => 'Main Dining'],
            ['table_number' => 'T3', 'capacity' => 2, 'location' => 'Window'],
            ['table_number' => 'T4', 'capacity' => 4, 'location' => 'Main Dining'],
            ['table_number' => 'T5', 'capacity' => 4, 'location' => 'Window'],
            ['table_number' => 'T6', 'capacity' => 4, 'location' => 'Window'],
            ['table_number' => 'T7', 'capacity' => 6, 'location' => 'Main Dining'],
            ['table_number' => 'T8', 'capacity' => 8, 'location' => 'Private Area'],
            ['table_number' => 'T9', 'capacity' => 10, 'location' => 'Private Area'],
        ];

        foreach ($tables as $table) {
            RestaurantTable::firstOrCreate(
                ['table_number' => $table['table_number']],
                $table
            );
        }
    }
}
