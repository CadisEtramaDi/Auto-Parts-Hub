<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;
use App\Models\Product;
use App\Models\Brand;
use Illuminate\Support\Str;

class MotorPartsSeeder extends Seeder
{
    public function run()
    {
        // Get or create Motor Parts main category
        $motorParts = Category::firstOrCreate(
            ['slug' => 'motor-parts'],
            [
                'name' => 'Motor Parts',
                'slug' => 'motor-parts'
            ]
        );

        // Create or get default brand
        $brand = Brand::firstOrCreate(
            ['slug' => 'motor-parts-brand'],
            [
                'name' => 'Motor Parts Brand',
                'slug' => 'motor-parts-brand'
            ]
        );

        // Basic categories with products
        $categories = [
            'electrical-components' => [
                'name' => 'Electrical Components',
                'products' => [
                    ['name' => 'Stator', 'price' => 299.99],
                    ['name' => 'Rotor', 'price' => 249.99],
                    ['name' => 'Motor Windings', 'price' => 179.99],
                ]
            ],
            'mechanical-components' => [
                'name' => 'Mechanical Components',
                'products' => [
                    ['name' => 'Drive Shaft', 'price' => 399.99],
                    ['name' => 'Bearings Set', 'price' => 89.99],
                    ['name' => 'Motor Housing', 'price' => 299.99],
                ]
            ],
            'cooling-components' => [
                'name' => 'Cooling Components',
                'products' => [
                    ['name' => 'Cooling Fan', 'price' => 79.99],
                    ['name' => 'Heat Sink', 'price' => 59.99],
                    ['name' => 'Thermal Fins', 'price' => 39.99],
                ]
            ],
            'control-components' => [
                'name' => 'Control Components',
                'products' => [
                    ['name' => 'Motor Controller', 'price' => 399.99],
                    ['name' => 'Speed Controller', 'price' => 299.99],
                    ['name' => 'Power Contactor', 'price' => 129.99],
                ]
            ]
        ];

        foreach ($categories as $slug => $data) {
            // Create category
            $category = Category::firstOrCreate(
                ['slug' => $slug],
                [
                    'name' => $data['name'],
                    'slug' => $slug,
                    'parent_id' => $motorParts->id
                ]
            );

            // Add products
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
                        'brand_id' => $brand->id,
                        'featured' => 0,
                        'status' => 1
                    ]
                );
            }
        }
    }
} 