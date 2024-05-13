<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $prices = [
            2.50, // Espresso
            3.00, // Americano
            4.00, // Caffè Latte
            3.50, // Cappuccino
            3.00, // Macchiato
        ];

        foreach ($prices as $price) {
            DB::table('products')->insert([
                'price' => $price
            ]);
        }
    }
}
