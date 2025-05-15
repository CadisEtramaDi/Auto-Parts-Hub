<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Product;
use App\Models\Category;

return new class extends Migration
{
    public function up(): void
    {
        $products = Product::with('category')->get();
        
        foreach ($products as $product) {
            $category = $product->category;
            if (!$category) continue;

            // Update main image
            if ($product->image) {
                $oldImage = basename($product->image); // Get just the filename
                $product->image = "Motor Parts/{$category->name}/{$oldImage}";
            }
            
            // Update gallery images
            if ($product->images) {
                $galleryImages = explode(',', $product->images);
                $updatedGalleryImages = array_map(function($img) use ($category) {
                    $oldImage = basename(trim($img)); // Get just the filename
                    return "Motor Parts/{$category->name}/{$oldImage}";
                }, $galleryImages);
                $product->images = implode(',', $updatedGalleryImages);
            }
            
            $product->save();
        }
    }

    public function down(): void
    {
        $products = Product::all();
        
        foreach ($products as $product) {
            // Revert main image to just filename
            if ($product->image) {
                $product->image = basename($product->image);
            }
            
            // Revert gallery images to just filenames
            if ($product->images) {
                $galleryImages = explode(',', $product->images);
                $revertedGalleryImages = array_map(function($img) {
                    return basename(trim($img));
                }, $galleryImages);
                $product->images = implode(',', $revertedGalleryImages);
            }
            
            $product->save();
        }
    }
}; 