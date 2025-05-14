<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\Brand;
use Illuminate\Support\Str;

class MotorPartsController extends Controller
{
    public function addMotorPartsCategories()
    {
        // Create main Motor Parts category
        $motorParts = Category::firstOrCreate(
            ['slug' => 'motor-parts'],
            [
                'name' => 'Motor Parts',
                'slug' => 'motor-parts'
            ]
        );

        // Create subcategories
        $categories = [
            [
                'name' => 'Electrical Components',
                'products' => ['Stator', 'Rotor', 'Windings']
            ],
            [
                'name' => 'Mechanical Components',
                'products' => ['Shaft', 'Bearings', 'Casing/Housing']
            ],
            [
                'name' => 'Cooling Components',
                'products' => ['Fans', 'Heat Sinks', 'Cooling Fins']
            ],
            [
                'name' => 'Control Components',
                'products' => ['Motor Controllers', 'Variable Frequency Drives', 'Contactor']
            ],
            [
                'name' => 'Power Supply',
                'products' => ['Transformers', 'Rectifiers', 'Capacitors']
            ],
            [
                'name' => 'Mounting Components',
                'products' => ['Motor Brackets', 'Brake/Clutch Reservoir Base', 'Mounting Plates']
            ],
            [
                'name' => 'Transmission Components',
                'products' => ['Transmission Gearbox', 'Belts', 'Pulleys']
            ],
            [
                'name' => 'Safety Components',
                'products' => ['Circuit Breakers', 'Fuses', 'Thermal Protection Devices']
            ],
            [
                'name' => 'Communication Components',
                'products' => ['Communication Protocol Adapters', 'Wiring Harnesses', 'Connectors']
            ],
            [
                'name' => 'Miscellaneous Components',
                'products' => ['Gaskets', 'Seals', 'Vibration Dampeners']
            ]
        ];

        // Create a default brand if it doesn't exist
        $defaultBrand = Brand::firstOrCreate(
            ['slug' => 'motor-parts-brand'],
            [
                'name' => 'Motor Parts Brand',
                'slug' => 'motor-parts-brand'
            ]
        );

        foreach ($categories as $categoryData) {
            $category = Category::firstOrCreate(
                ['slug' => Str::slug($categoryData['name'])],
                [
                    'name' => $categoryData['name'],
                    'slug' => Str::slug($categoryData['name']),
                    'parent_id' => $motorParts->id
                ]
            );

            // Add products for each category
            foreach ($categoryData['products'] as $productName) {
                Product::firstOrCreate(
                    ['slug' => Str::slug($productName)],
                    [
                        'name' => $productName,
                        'slug' => Str::slug($productName),
                        'short_description' => $productName . ' for motor systems',
                        'description' => 'High-quality ' . $productName . ' for motor systems and applications.',
                        'regular_price' => rand(50, 500), // Random price between 50 and 500
                        'SKU' => strtoupper(substr(str_replace([' ', '/'], '-', $productName), 0, 3)) . '-' . rand(100, 999),
                        'stock_status' => 'instock',
                        'quantity' => rand(10, 100), // Random quantity between 10 and 100
                        'category_id' => $category->id,
                        'brand_id' => $defaultBrand->id
                    ]
                );
            }
        }

        return redirect()->route('admin.categories')->with('status', 'Motor parts categories and products have been added successfully!');
    }
} 