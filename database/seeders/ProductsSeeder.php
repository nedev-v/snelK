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
        $hasMilk = [
            false, // Espresso
            false, // Americano
            true, // Caffè Latte
            true, // Cappuccino
            true, // Macchiato
        ];
        $imagePath = [
            'espresso.jpg', // Espresso
            'americano.jpg', // Americano
            'latte.jpg', // Caffè Latte
            'cappuccino.jpg', // Cappuccino
            'macchiato.jpg', // Macchiato
        ];

        for ($i = 0; $i <= 4; $i++) {
            DB::table('products')->insert([
                'price' => $prices[$i],
                'has_milk' => $hasMilk[$i],
                'image_path' => $imagePath[$i]
            ]);
        }
    }
}
