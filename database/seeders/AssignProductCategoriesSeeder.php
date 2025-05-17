<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\Category;

class AssignProductCategoriesSeeder extends Seeder
{
    public function run()
    {
        $categoryIds = Category::pluck('id')->toArray();
        if (empty($categoryIds)) {
            $this->command->info('No categories found. Skipping product assignment.');
            return;
        }
        Product::all()->each(function ($product) use ($categoryIds) {
            $product->category_id = $categoryIds[array_rand($categoryIds)];
            $product->save();
        });
        $this->command->info('Assigned each product to a random category.');
    }
} 