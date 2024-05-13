<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductsLangSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            // Espresso
            [
                'product_id' => 1,
                'language' => 'en',
                'title' => 'Espresso',
                'description' => 'Strong and concentrated coffee shot.',
            ],
            [
                'product_id' => 1,
                'language' => 'nl',
                'title' => 'Espresso',
                'description' => 'Sterke en geconcentreerde koffieshot.',
            ],
            // Americano
            [
                'product_id' => 2,
                'language' => 'en',
                'title' => 'Americano',
                'description' => 'Espresso diluted with hot water.',
            ],
            [
                'product_id' => 2,
                'language' => 'nl',
                'title' => 'Americano',
                'description' => 'Espresso verdund met heet water.',
            ],
            // Caffè Latte
            [
                'product_id' => 3,
                'language' => 'en',
                'title' => 'Caffè Latte',
                'description' => 'Espresso with steamed milk and a small amount of milk foam.',
            ],
            [
                'product_id' => 3,
                'language' => 'nl',
                'title' => 'Caffè Latte',
                'description' => 'Espresso met gestoomde melk en een kleine hoeveelheid melkschuim.',
            ],
            // Cappuccino
            [
                'product_id' => 4,
                'language' => 'en',
                'title' => 'Cappuccino',
                'description' => 'Equal parts espresso, steamed milk, and milk foam.',
            ],
            [
                'product_id' => 4,
                'language' => 'nl',
                'title' => 'Cappuccino',
                'description' => 'Gelijke delen espresso, gestoomde melk en melkschuim.',
            ],
            // Macchiato
            [
                'product_id' => 5,
                'language' => 'en',
                'title' => 'Macchiato',
                'description' => 'Espresso "stained" with a small amount of steamed milk or milk foam.',
            ],
            [
                'product_id' => 5,
                'language' => 'nl',
                'title' => 'Macchiato',
                'description' => 'Espresso "bevlekt" met een kleine hoeveelheid gestoomde melk of melkschuim.',
            ],
        ];

        // Insert data into products_language table
        DB::table('products_language')->insert($data);
    }
}
