<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Support\Str;

class VehiclePartsSeeder extends Seeder
{
    public function run()
    {
        // Get or create Vehicle Parts main category
        $vehicleParts = Category::firstOrCreate(
            ['slug' => 'vehicle-parts'],
            [
                'name' => 'Vehicle Parts',
                'slug' => 'vehicle-parts'
            ]
        );

        $vehicleCategories = [
            'engine-parts' => [
                'name' => 'Engine Parts',
                'products' => [
                    ['name' => 'Spark Plug', 'price' => 19.99],
                    ['name' => 'Oil Filter', 'price' => 9.99],
                    ['name' => 'Timing Belt', 'price' => 49.99],
                ]
            ],
            'brake-system' => [
                'name' => 'Brake System',
                'products' => [
                    ['name' => 'Brake Pad', 'price' => 29.99],
                    ['name' => 'Brake Disc', 'price' => 59.99],
                    ['name' => 'Brake Caliper', 'price' => 89.99],
                ]
            ],
            'suspension' => [
                'name' => 'Suspension',
                'products' => [
                    ['name' => 'Shock Absorber', 'price' => 79.99],
                    ['name' => 'Strut Mount', 'price' => 39.99],
                    ['name' => 'Control Arm', 'price' => 69.99],
                ]
            ],
            'electrical-system' => [
                'name' => 'Electrical System',
                'products' => [
                    ['name' => 'Car Battery', 'price' => 99.99],
                    ['name' => 'Alternator', 'price' => 149.99],
                    ['name' => 'Starter Motor', 'price' => 129.99],
                ]
            ],
            'cooling-system' => [
                'name' => 'Cooling System',
                'products' => [
                    ['name' => 'Radiator', 'price' => 119.99],
                    ['name' => 'Water Pump', 'price' => 89.99],
                    ['name' => 'Thermostat', 'price' => 24.99],
                ]
            ],
        ];

        foreach ($vehicleCategories as $slug => $data) {
            $category = Category::firstOrCreate(
                ['slug' => $slug],
                [
                    'name' => $data['name'],
                    'slug' => $slug,
                    'parent_id' => $vehicleParts->id
                ]
            );

            foreach ($data['products'] as $product) {
                Product::firstOrCreate(
                    ['slug' => Str::slug($product['name'])],
                    [
                        'name' => $product['name'],
                        'slug' => Str::slug($product['name']),
                        'short_description' => 'Quality ' . strtolower($product['name']),
                        'description' => 'High-quality ' . strtolower($product['name']) . ' for optimal performance',
                        'regular_price' => $product['price'],
                        'sale_price' => $product['price'] * 0.9,
                        'SKU' => strtoupper(substr(str_replace([' ', '-'], '', $product['name']), 0, 3)) . '-' . rand(100, 999),
                        'stock_status' => 'instock',
                        'quantity' => rand(10, 50),
                        'category_id' => $category->id,
                        'brand_id' => null,
                        'featured' => 0,
                        'status' => 1
                    ]
                );
            }
        }
    }
} 