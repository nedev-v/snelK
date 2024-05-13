<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OrdersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                'user_id' => 1, // Assuming user_id 1 exists
                'cup_size' => 12,
                'is_decaf' => false,
                'milk_flavour' => 'Regular',
                'syrup_flavour' => 'Vanilla',
                'pickup_time' => '09:00',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => 2, // Assuming user_id 2 exists
                'cup_size' => 16,
                'is_decaf' => true,
                'milk_flavour' => 'Almond',
                'syrup_flavour' => null,
                'pickup_time' => '10:30',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // Add more sample data as needed
        ];

        // Insert data into orders table
        DB::table('orders')->insert($data);
    }
}
