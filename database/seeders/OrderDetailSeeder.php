<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OrderDetailSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                'order_id' => 1, // Assuming order_id 1 exists
                'product_id' => 1, // Assuming product_id 1 exists
                'cup_size' => 12,
                'is_decaf' => false,
                'milk_flavour' => 'Regular',
                'syrup_flavour' => 'Vanilla',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'order_id' => 2, // Assuming order_id 2 exists
                'product_id' => 2, // Assuming product_id 2 exists
                'cup_size' => 16,
                'is_decaf' => true,
                'milk_flavour' => 'Almond',
                'syrup_flavour' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // Add more sample data as needed
        ];

        // Insert data into order_details table
        DB::table('order_details')->insert($data);
    }
}
