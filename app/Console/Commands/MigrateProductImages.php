<?php

namespace App\Console\Commands;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class MigrateProductImages extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'products:migrate-images';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Migrate product images to category-specific folders';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting product image migration...');
        
        // Get all products
        $products = Product::with('category.parent')->get();
        $this->info('Found ' . $products->count() . ' products to process');
        
        $bar = $this->output->createProgressBar($products->count());
        $bar->start();
        
        $manager = new ImageManager(new Driver());
        
        foreach ($products as $product) {
            if (!$product->image || !$product->category) {
                $bar->advance();
                continue;
            }
            
            $category = $product->category;
            $parentCategory = $category->parent;
            
            if (!$parentCategory) {
                $bar->advance();
                continue;
            }
            
            // Determine destination path based on category
            $destinationPath = null;
            $destinationPathThumbnail = null;
            
            if ($parentCategory->slug == 'motor-parts') {
                $categoryFolder = str_replace('-', ' ', ucwords($category->slug, '-'));
                $destinationPath = public_path('images/Motor Parts/' . $categoryFolder);
                $destinationPathThumbnail = public_path('images/Motor Parts/' . $categoryFolder . '/thumbnails');
            } elseif ($parentCategory->slug == 'vehicle-systems') {
                $categoryFolder = str_replace('-', ' ', ucwords($category->slug, '-'));
                $destinationPath = public_path('images/Vehicle System/' . $categoryFolder);
                $destinationPathThumbnail = public_path('images/Vehicle System/' . $categoryFolder . '/thumbnails');
            } else {
                $bar->advance();
                continue;
            }
            
            // Create directories if they don't exist
            if (!File::isDirectory($destinationPath)) {
                File::makeDirectory($destinationPath, 0755, true);
            }
            
            if (!File::isDirectory($destinationPathThumbnail)) {
                File::makeDirectory($destinationPathThumbnail, 0755, true);
            }
            
            // Copy main image
            $sourcePath = public_path('uploads/products/' . $product->image);
            $targetPath = $destinationPath . '/' . $product->image;
            
            if (File::exists($sourcePath)) {
                // Copy and resize the image
                try {
                    $img = $manager->read($sourcePath);
                    $img->cover(540, 689, "top");
                    $img->resize(540, 689, function($constraint) {
                        $constraint->aspectRatio();
                    })->save($targetPath);
                    
                    // Create thumbnail
                    $img->resize(104, 104, function($constraint) {
                        $constraint->aspectRatio();
                    })->save($destinationPathThumbnail . '/' . $product->image);
                } catch (\Exception $e) {
                    $this->error("Error processing image for product ID {$product->id}: " . $e->getMessage());
                }
            }
            
            // Process gallery images if any
            if ($product->images) {
                $galleryImages = explode(',', $product->images);
                foreach ($galleryImages as $galleryImage) {
                    $galleryImage = trim($galleryImage);
                    if (empty($galleryImage)) continue;
                    
                    $gallerySourcePath = public_path('uploads/products/' . $galleryImage);
                    $galleryTargetPath = $destinationPath . '/' . $galleryImage;
                    
                    if (File::exists($gallerySourcePath)) {
                        try {
                            $img = $manager->read($gallerySourcePath);
                            $img->cover(540, 689, "top");
                            $img->resize(540, 689, function($constraint) {
                                $constraint->aspectRatio();
                            })->save($galleryTargetPath);
                            
                            // Create thumbnail
                            $img->resize(104, 104, function($constraint) {
                                $constraint->aspectRatio();
                            })->save($destinationPathThumbnail . '/' . $galleryImage);
                        } catch (\Exception $e) {
                            $this->error("Error processing gallery image {$galleryImage} for product ID {$product->id}: " . $e->getMessage());
                        }
                    }
                }
            }
            
            $bar->advance();
        }
        
        $bar->finish();
        $this->newLine();
        $this->info('Product image migration completed!');
        
        return Command::SUCCESS;
    }
} 