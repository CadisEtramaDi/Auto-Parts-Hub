<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\Coupon;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;
use Intervention\Image\Laravel\Facades\Image;

class AdminController extends Controller
{
    public function index()
    {
        return view('admin.index');
    }

    public function brands()
    {
        $brands = Brand::orderBy('id','DESC')->paginate(10);
        return view('admin.brands',compact('brands'));
    }

    public function add_brand()
    {
        return view('admin.brand-add');
    }

    public function brand_store(Request $request){
        $request->validate([
            'name'=>'required',
            'slug'=>'required|unique:brands,slug',
            'image' => 'mimes:png,jpg,jpeg|max:2048'
        ]);

        $brand = new Brand();
        $brand->name = $request->name;
        $brand->slug = Str::slug($request->name);
        $image = $request->file('image');
        $file_extension = $request->file('image')->extension();
        $file_name = Carbon::now()->timestamp.'.'.$file_extension;
        $this->GenerateBrandThumbnailImage($image,$file_name);
        $brand->image = $file_name;
        $brand->save();
        return redirect()->route('admin.brands')->with('status','Brand has been added successfully');
    }

    public function edit_brand($id)
    {
        $brand = Brand::find($id);
        return view('admin.brand-edit', compact('brand'));
    }

    public function brand_update(Request $request)
    {
        $request->validate([
            'name'=>'required',
            'slug'=>'required|unique:brands,slug,'.$request->id,
            'image' => 'mimes:png,jpg,jpeg|max:2048'
        ]);

        $brand = Brand::find($request->id);
        $brand->name = $request->name;
        $brand->slug = Str::slug($request->name);
        if ($request->hasFile('image')){
            if(File::exists(public_path('uploads/brands').'/'.$brand->image))
            {
                File::delete(public_path('uploads/brands').'/'.$brand->image);
            }
            $image = $request->file('image');
            $file_extention = $request->file('image')->extension();
            $file_name = Carbon::now()->timestamp.'.'.$file_extention;
            $this->GenerateBrandThumbnailImage($image,$file_name);
            $brand->image = $file_name;
        }
        $brand->save();
        return redirect()->route('admin.brands')->with('status','Brand has been updated successfully');

    }

    public function GenerateBrandThumbnailImage($image, $imageName)
    {
        $destinationPath = public_path('uploads/brands');
        $img = Image::read($image->path());
        $img->cover(124,124,"top");
        $img->resize(124,124,function($constraint){
            $constraint->aspectRatio();
        })->save($destinationPath.'/'.$imageName);
    }

        public function delete_brand($id)
    {
        $brand = Brand::find($id);
        if (File::exists(public_path('uploads/brands').'/'.$brand->image)) {
            File::delete(public_path('uploads/brands').'/'.$brand->image);
        }
        $brand->delete();
        return redirect()->route('admin.brands')->with('status','Record has been deleted successfully !');
    }

    public function categories()
    {
           $categories = Category::orderBy('id','DESC')->paginate(10);
           return view("admin.categories",compact('categories'));
    }

        public function add_category()
    {
        $categories = Category::all();
        return view("admin.category-add", compact('categories'));
    }

        public function add_category_store(Request $request)
    {        
        $request->validate([
            'name' => 'required',
            'slug' => 'required|unique:categories,slug',
            'image' => 'mimes:png,jpg,jpeg|max:2048',
            'parent_id' => 'nullable|exists:categories,id'
        ]);
        $category = new Category();
        $category->name = $request->name;
        $category->slug = Str::slug($request->name);
        $category->parent_id = $request->parent_id;
        $image = $request->file('image');
        $file_extention = $request->file('image')->extension();
        $file_name = Carbon::now()->timestamp . '.' . $file_extention;
        $this->GenerateCategoryThumbnailImage($image,$file_name);
        $category->image = $file_name;        
        $category->save();
        return redirect()->route('admin.categories')->with('status','Category has been added successfully !');
    }

    public function GenerateCategoryThumbnailImage($image, $imageName)
    {
        $destinationPath = public_path('uploads/categories');
        $img = Image::read($image->path());
        $img->cover(124,124,"top");
        $img->resize(124,124,function($constraint){
            $constraint->aspectRatio();
        })->save($destinationPath.'/'.$imageName);
    }

        public function edit_category($id)
    {
        $category = Category::find($id);
        $categories = Category::all();
        return view('admin.category-edit',compact('category', 'categories'));
    }

        public function update_category(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'slug' => 'required|unique:categories,slug,'.$request->id,
            'image' => 'mimes:png,jpg,jpeg|max:2048',
            'parent_id' => 'nullable|exists:categories,id'
        ]);
        $category = Category::find($request->id);
        $category->name = $request->name;
        $category->slug = $request->slug;
        $category->parent_id = $request->parent_id;
        if($request->hasFile('image'))
        {            
            if (File::exists(public_path('uploads/categories').'/'.$category->image)) {
                File::delete(public_path('uploads/categories').'/'.$category->image);
            }
            $image = $request->file('image');
            $file_extention = $request->file('image')->extension();
            $file_name = Carbon::now()->timestamp . '.' . $file_extention;

            $this->GenerateCategoryThumbnailImage($image,$file_name);   
            $category->image = $file_name;
        }        
        $category->save();    
        return redirect()->route('admin.categories')->with('status','Category has been updated successfully !');
    }

        public function delete_category($id)
    {
        $category = Category::find($id);
        if (File::exists(public_path('uploads/categories').'/'.$category->image)) 
        {
            File::delete(public_path('uploads/categories').'/'.$category->image);
        }
        $category->delete();
        return redirect()->route('admin.categories')->with('status','Category has been deleted successfully !');
    }

    public function products()
    {
        $products = Product::orderBy('created_at', 'DESC')->paginate(10);
        return view('admin.products', compact('products'));
    }

    public function add_product()
    {
        $categories = Category::select('id','name')->orderBy('name')->get();
        $brands = Brand::select('id','name')->orderBy('name')->get();
        return view('admin.product-add', compact('categories','brands'));
    }

    public function product_store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'slug'=> 'required|unique:products,slug',
            'short_description'=> 'required',
            'description'=> 'required',
            'regular_price'=> 'required',
            'sale_price'=> 'required',
            'SKU'=> 'required',
            'stock_status'=> 'required',
            'featured'=> 'required',
            'quantity'=> 'required',
            'image'=> 'required|mimes:png,jpg,jpeg|max:2048',
            'category_id'=> 'required',
            'brand_id'=> 'required'
        ]);

        $product = new Product();
        $product->name = $request->name;
        $product->slug = Str::slug($request->name);
        $product->short_description = $request->short_description;
        $product->description = $request->description;
        $product->regular_price = $request->regular_price;
        $product->sale_price = $request->sale_price;
        $product->SKU = $request->SKU;
        $product->stock_status = $request->stock_status;
        $product->featured = $request->featured;
        $product->quantity = $request->quantity;
        $product->image = $request->image;
        $product->category_id = $request->category_id;
        $product->brand_id = $request->brand_id;

        $current_timestamp = Carbon::now()->timestamp;

        if($request->hasFile('image'))
        {
            $image = $request->file('image');
            $imageName = $current_timestamp. '.'. $image->extension();
            $this->GenerateProductThumbnailImage($image, $imageName);
            $product->image = $imageName;
        }

        $gallery_arr = array();
        $gallery_images = "";
        $counter = 1;

        if($request->hasFile('images'))
        {
            $allowedfileExtion = ['jpg','png', 'jpeg'];
            $files = $request->file('images');
            foreach($files as $file)
            {
                $gextension = $file->getClientOriginalExtension();
                $gcheck = in_array($gextension,$allowedfileExtion);
                if($gcheck)
                {
                    $gfileName = $current_timestamp . "-" . $counter . ".". $gextension;
                    $this->GenerateProductThumbnailImage($file, $gfileName);
                    array_push($gallery_arr,$gfileName);
                    $counter = $counter + 1;
                }
            }
            $gallery_images = implode(',',$gallery_arr);
        }
        $product->images = $gallery_images;
        $product->save();
        return redirect()->route('admin.products')->with('status','Product has been added successfully!');
    }

    public function GenerateProductThumbnailImage($image, $imageName)
    {
        $destinationPathThumbnail = public_path('uploads/products/thumbnails');
        $destinationPath = public_path('uploads/products');
        $img = Image::read($image->path());
        
        $img->cover(540,689,"top");
        $img->resize(540,689,function($constraint){
            $constraint->aspectRatio();
        })->save($destinationPath.'/'.$imageName);

        $img->resize(104,104,function($constraint){
            $constraint->aspectRatio();
        })->save($destinationPathThumbnail.'/'.$imageName);
    }
    
    public function edit_product($id)
    {
        $product = Product::find($id);
        $categories = Category::select('id','name')->orderBy('name')->get();
        $brands = Brand::select('id','name')->orderBy('name')->get();
        return view('admin.product-edit',compact('product','categories','brands'));
    }

    public function update_product(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'slug'=> 'required|unique:products,slug,'.$request->id,
            'short_description'=> 'required',
            'description'=> 'required',
            'regular_price'=> 'required',
            'sale_price'=> 'required',
            'SKU'=> 'required',
            'stock_status'=> 'required',
            'featured'=> 'required',
            'quantity'=> 'required',
            'image'=> 'mimes:png,jpg,jpeg|max:2048',
            'category_id'=> 'required',
            'brand_id'=> 'required'
        ]);

        $product = Product::find($request->id);
        $product->name = $request->name;
        $product->slug = Str::slug($request->name);
        $product->short_description = $request->short_description;
        $product->description = $request->description;
        $product->regular_price = $request->regular_price;
        $product->sale_price = $request->sale_price;
        $product->SKU = $request->SKU;
        $product->stock_status = $request->stock_status;
        $product->featured = $request->featured;
        $product->quantity = $request->quantity;
        $product->image = $request->image;
        $product->category_id = $request->category_id;
        $product->brand_id = $request->brand_id;

        $current_timestamp = Carbon::now()->timestamp;

        if($request->hasFile('image'))
        {
            if(File::exists(public_path('uploads/products').'/'.$product->image))
            {
                File::delete(public_path('uploads/products').'/'.$product->image);
            }
            if(File::exists(public_path('uploads/products/thumbnails').'/'.$product->image))
            {
                File::delete(public_path('uploads/products/thumbnails').'/'.$product->image);
            }
            $image = $request->file('image');
            $imageName = $current_timestamp. '.'. $image->extension();
            $this->GenerateProductThumbnailImage($image, $imageName);
            $product->image = $imageName;
        }

        $gallery_arr = array();
        $gallery_images = "";
        $counter = 1;

        if($request->hasFile('images'))
        {
            foreach(explode(',',$product->images) as $ofile)
            {
                if(File::exists(public_path('uploads/products').'/'.$ofile))
                {
                    File::delete(public_path('uploads/products').'/'.$ofile);
                }
                if(File::exists(public_path('uploads/products/thumbnails').'/'.$ofile))
                {
                    File::delete(public_path('uploads/products/thumbnails').'/'.$ofile);
                }
            }
            $allowedfileExtion = ['jpg','png', 'jpeg'];
            $files = $request->file('images');
            foreach($files as $file)
            {
                $gextension = $file->getClientOriginalExtension();
                $gcheck = in_array($gextension,$allowedfileExtion);
                if($gcheck)
                {
                    $gfileName = $current_timestamp . "-" . $counter . ".". $gextension;
                    $this->GenerateProductThumbnailImage($file, $gfileName);
                    array_push($gallery_arr,$gfileName);
                    $counter = $counter + 1;
                }
            }
            $gallery_images = implode(',',$gallery_arr);
        }
        $product->images = $gallery_images;
        $product->save();
        return redirect()->route('admin.products')->with('status','Product has been updated successfully!');
    }

    public function delete_product($id)
    {
        $product = Product::find($id);
        if(File::exists(public_path('uploads/products').'/'.$product->image))
        {
            File::delete(public_path('uploads/products').'/'.$product->image);
        }
        if(File::exists(public_path('uploads/products/thumbnails').'/'.$product->image))
        {
            File::delete(public_path('uploads/products/thumbnails').'/'.$product->image);
        }
        
        foreach(explode(',',$product->images) as $ofile)
        {
            if(File::exists(public_path('uploads/products').'/'.$ofile))
            {
                File::delete(public_path('uploads/products').'/'.$ofile);
            }
            if(File::exists(public_path('uploads/products/thumbnails').'/'.$ofile))
            {
                File::delete(public_path('uploads/products/thumbnails').'/'.$ofile);
            }
        }
        $product->delete();
        return redirect()->route('admin.products')->with('status','Product has been deleted successfully!');
    }

    public function coupons()
    {
        $coupons = Coupon::orderBy('expiry_date','DESC')->paginate(12);
        return view('admin.coupons',compact('coupons'));
    } 

    public function coupon_add()
    {
        return view('admin.coupon-add');
    }

    public function coupon_store(Request $request)
    {
        $request->validate([
            'code' => 'required|unique:coupons,code,'.$request->id.',id',
            'type'=> 'required',
            'value' => 'required|numeric',
            'cart_value' => 'required|numeric',
            'expiry_date' => 'required|date'
        ]);
        $coupon = new Coupon();
        $coupon->code = $request->code; 
        $coupon->type = $request->type; 
        $coupon->value = $request->value; 
        $coupon->cart_value = $request->cart_value; 
        $coupon->expiry_date = $request->expiry_date; 
        $coupon->save();
        return redirect()->route('admin.coupons')->with('status','Coupon has been added successfully');
    }

    public function coupon_edit($id)
    {
        $coupon = Coupon::find($id);
        return view('admin.coupon-edit',compact('coupon'));
    }

    public function coupon_update(Request $request)
    {
        $request->validate([
            'code' => 'required|unique:coupons,code,'.$request->id.',id',
            'type'=> 'required',
            'value' => 'required|numeric',
            'cart_value' => 'required|numeric',
            'expiry_date' => 'required|date'
        ]);

        $coupon = Coupon::find($request->id);
        $coupon->code = $request->code; 
        $coupon->type = $request->type; 
        $coupon->value = $request->value; 
        $coupon->cart_value = $request->cart_value; 
        $coupon->expiry_date = $request->expiry_date; 
        $coupon->save();
        return redirect()->route('admin.coupons')->with('status','Coupon has been updated successfully');

    }

    public function coupon_delete($id)
    {
        $coupon = Coupon::find($id);
        $coupon->delete();
        return redirect()->route('admin.coupons')->with('status','Coupon has beed deleted successfully!');
    }

    public function add_auto_parts_categories()
    {
        // Main Categories
        $motorParts = Category::firstOrCreate(
            ['slug' => 'motor-parts'],
            [
                'name' => 'Motor Parts',
                'slug' => 'motor-parts',
                'parent_id' => null
            ]
        );

        $vehicleSystems = Category::firstOrCreate(
            ['slug' => 'vehicle-systems'],
            [
                'name' => 'Vehicle Systems',
                'slug' => 'vehicle-systems',
                'parent_id' => null
            ]
        );

        // Motor Parts Subcategories
        $electricalComponents = Category::firstOrCreate(
            ['slug' => 'electrical-components'],
            [
                'name' => 'Electrical Components',
                'slug' => 'electrical-components',
                'parent_id' => $motorParts->id
            ]
        );

        $mechanicalComponents = Category::firstOrCreate(
            ['slug' => 'mechanical-components'],
            [
                'name' => 'Mechanical Components',
                'slug' => 'mechanical-components',
                'parent_id' => $motorParts->id
            ]
        );

        $coolingComponents = Category::firstOrCreate(
            ['slug' => 'cooling-components'],
            [
                'name' => 'Cooling Components',
                'slug' => 'cooling-components',
                'parent_id' => $motorParts->id
            ]
        );

        $controlComponents = Category::firstOrCreate(
            ['slug' => 'control-components'],
            [
                'name' => 'Control Components',
                'slug' => 'control-components',
                'parent_id' => $motorParts->id
            ]
        );

        $powerSupply = Category::firstOrCreate(
            ['slug' => 'power-supply'],
            [
                'name' => 'Power Supply',
                'slug' => 'power-supply',
                'parent_id' => $motorParts->id
            ]
        );

        $mountingComponents = Category::firstOrCreate(
            ['slug' => 'mounting-components'],
            [
                'name' => 'Mounting Components',
                'slug' => 'mounting-components',
                'parent_id' => $motorParts->id
            ]
        );

        $transmissionComponents = Category::firstOrCreate(
            ['slug' => 'transmission-components'],
            [
                'name' => 'Transmission Components',
                'slug' => 'transmission-components',
                'parent_id' => $motorParts->id
            ]
        );

        $safetyComponents = Category::firstOrCreate(
            ['slug' => 'safety-components'],
            [
                'name' => 'Safety Components',
                'slug' => 'safety-components',
                'parent_id' => $motorParts->id
            ]
        );

        $communicationComponents = Category::firstOrCreate(
            ['slug' => 'communication-components'],
            [
                'name' => 'Communication Components',
                'slug' => 'communication-components',
                'parent_id' => $motorParts->id
            ]
        );

        $miscellaneousComponents = Category::firstOrCreate(
            ['slug' => 'miscellaneous-components'],
            [
                'name' => 'Miscellaneous Components',
                'slug' => 'miscellaneous-components',
                'parent_id' => $motorParts->id
            ]
        );

        // Vehicle Systems Subcategories
        $engineParts = Category::firstOrCreate(
            ['slug' => 'engine-parts'],
            [
                'name' => 'Engine Parts',
                'slug' => 'engine-parts',
                'parent_id' => $vehicleSystems->id
            ]
        );

        $brakeSystem = Category::firstOrCreate(
            ['slug' => 'brake-system'],
            [
                'name' => 'Brake System',
                'slug' => 'brake-system',
                'parent_id' => $vehicleSystems->id
            ]
        );

        $vehicleTransmission = Category::firstOrCreate(
            ['slug' => 'vehicle-transmission'],
            [
                'name' => 'Transmission Components',
                'slug' => 'vehicle-transmission',
                'parent_id' => $vehicleSystems->id
            ]
        );

        $suspensionSystem = Category::firstOrCreate(
            ['slug' => 'suspension-system'],
            [
                'name' => 'Suspension System',
                'slug' => 'suspension-system',
                'parent_id' => $vehicleSystems->id
            ]
        );

        $electricalSystem = Category::firstOrCreate(
            ['slug' => 'electrical-system'],
            [
                'name' => 'Electrical System',
                'slug' => 'electrical-system',
                'parent_id' => $vehicleSystems->id
            ]
        );

        $coolingSystem = Category::firstOrCreate(
            ['slug' => 'cooling-system'],
            [
                'name' => 'Cooling System',
                'slug' => 'cooling-system',
                'parent_id' => $vehicleSystems->id
            ]
        );

        $fuelSystem = Category::firstOrCreate(
            ['slug' => 'fuel-system'],
            [
                'name' => 'Fuel System',
                'slug' => 'fuel-system',
                'parent_id' => $vehicleSystems->id
            ]
        );

        $exhaustSystem = Category::firstOrCreate(
            ['slug' => 'exhaust-system'],
            [
                'name' => 'Exhaust System',
                'slug' => 'exhaust-system',
                'parent_id' => $vehicleSystems->id
            ]
        );

        $steeringSystem = Category::firstOrCreate(
            ['slug' => 'steering-system'],
            [
                'name' => 'Steering System',
                'slug' => 'steering-system',
                'parent_id' => $vehicleSystems->id
            ]
        );

        $tireAndWheel = Category::firstOrCreate(
            ['slug' => 'tire-and-wheel'],
            [
                'name' => 'Tire and Wheel Components',
                'slug' => 'tire-and-wheel',
                'parent_id' => $vehicleSystems->id
            ]
        );

        return redirect()->route('admin.categories')->with('status', 'Auto parts categories have been added successfully!');
    }

    public function add_auto_parts_products()
    {
        // Create a default brand if it doesn't exist
        $defaultBrand = Brand::firstOrCreate(
            ['slug' => 'auto-parts-hub'],
            [
                'name' => 'Auto Parts Hub',
                'slug' => 'auto-parts-hub'
            ]
        );

        // Create a default placeholder image if it doesn't exist
        $defaultImageName = 'default-product.jpg';
        $defaultImagePath = public_path('uploads/products/' . $defaultImageName);
        $defaultImageThumbPath = public_path('uploads/products/thumbnails/' . $defaultImageName);
        
        if (!file_exists($defaultImagePath)) {
            // Create directories if they don't exist
            if (!file_exists(public_path('uploads/products'))) {
                mkdir(public_path('uploads/products'), 0755, true);
            }
            if (!file_exists(public_path('uploads/products/thumbnails'))) {
                mkdir(public_path('uploads/products/thumbnails'), 0755, true);
            }
            
            // Copy default image from public assets if it exists, or create a blank one
            if (file_exists(public_path('assets/images/' . $defaultImageName))) {
                copy(public_path('assets/images/' . $defaultImageName), $defaultImagePath);
                copy(public_path('assets/images/' . $defaultImageName), $defaultImageThumbPath);
            } else {
                // Create a simple blank image
                $img = Image::canvas(540, 689, '#ffffff');
                $img->save($defaultImagePath);
                $img->resize(104, 104)->save($defaultImageThumbPath);
            }
        }

        $products = [
            // Engine Parts Products
            ['category_slug' => 'engine-parts', 'products' => [
                [
                    'name' => 'High Performance Piston Set',
                    'short_description' => 'Forged aluminum pistons for maximum durability',
                    'description' => 'Professional-grade forged aluminum pistons designed for high-performance engines. Includes wrist pins and rings.',
                    'regular_price' => 399.99,
                    'sale_price' => 359.99,
                    'SKU' => 'EP-PST-001',
                    'stock_status' => 'instock',
                    'quantity' => 30
                ],
                [
                    'name' => 'Premium Crankshaft Assembly',
                    'short_description' => 'Forged steel crankshaft for enhanced durability',
                    'description' => 'Precision-balanced forged steel crankshaft with hardened journals for maximum strength and longevity.',
                    'regular_price' => 899.99,
                    'sale_price' => 849.99,
                    'SKU' => 'EP-CRK-001',
                    'stock_status' => 'instock',
                    'quantity' => 20
                ],
                [
                    'name' => 'Performance Cylinder Head',
                    'short_description' => 'CNC-machined cylinder head for optimal flow',
                    'description' => 'High-flow cylinder head with larger valves and improved port design for better engine breathing.',
                    'regular_price' => 799.99,
                    'sale_price' => 749.99,
                    'SKU' => 'EP-CYH-001',
                    'stock_status' => 'instock',
                    'quantity' => 15
                ]
            ]],

            // Brake System Products
            ['category_slug' => 'brake-system', 'products' => [
                [
                    'name' => 'Ceramic Brake Pads Set',
                    'short_description' => 'High-performance ceramic brake pads',
                    'description' => 'Premium ceramic brake pads offering superior stopping power with minimal brake dust and noise.',
                    'regular_price' => 89.99,
                    'sale_price' => 79.99,
                    'SKU' => 'BS-PAD-001',
                    'stock_status' => 'instock',
                    'quantity' => 100
                ],
                [
                    'name' => 'Performance Brake Rotors',
                    'short_description' => 'Cross-drilled and slotted rotors',
                    'description' => 'High-performance brake rotors with cross-drilling and slotting for improved heat dissipation.',
                    'regular_price' => 129.99,
                    'sale_price' => 119.99,
                    'SKU' => 'BS-ROT-001',
                    'stock_status' => 'instock',
                    'quantity' => 80
                ],
                [
                    'name' => 'Performance Brake Calipers',
                    'short_description' => 'Aluminum brake calipers',
                    'description' => 'Lightweight aluminum brake calipers with stainless steel pistons for improved braking performance.',
                    'regular_price' => 249.99,
                    'sale_price' => 229.99,
                    'SKU' => 'BS-CAL-001',
                    'stock_status' => 'instock',
                    'quantity' => 60
                ]
            ]],

            // Transmission Components Products
            ['category_slug' => 'vehicle-transmission', 'products' => [
                [
                    'name' => 'Heavy-Duty Gearbox',
                    'short_description' => 'Reinforced manual transmission gearbox',
                    'description' => 'High-strength manual transmission gearbox with hardened gears for improved durability.',
                    'regular_price' => 1299.99,
                    'sale_price' => 1199.99,
                    'SKU' => 'TC-GBX-001',
                    'stock_status' => 'instock',
                    'quantity' => 15
                ],
                [
                    'name' => 'Performance Clutch Kit',
                    'short_description' => 'Heavy-duty clutch assembly',
                    'description' => 'Complete clutch kit including pressure plate, disc, and bearing for high-torque applications.',
                    'regular_price' => 499.99,
                    'sale_price' => 459.99,
                    'SKU' => 'TC-CLT-001',
                    'stock_status' => 'instock',
                    'quantity' => 25
                ],
                [
                    'name' => 'High-Capacity Torque Converter',
                    'short_description' => 'Performance torque converter',
                    'description' => 'Heavy-duty torque converter designed for high-horsepower automatic transmissions.',
                    'regular_price' => 699.99,
                    'sale_price' => 649.99,
                    'SKU' => 'TC-TRQ-001',
                    'stock_status' => 'instock',
                    'quantity' => 20
                ]
            ]],

            // Suspension System Products
            ['category_slug' => 'suspension-system', 'products' => [
                [
                    'name' => 'Gas Shock Absorbers Set',
                    'short_description' => 'High-pressure gas shock absorbers',
                    'description' => 'Premium gas-charged shock absorbers for improved handling and comfort.',
                    'regular_price' => 299.99,
                    'sale_price' => 279.99,
                    'SKU' => 'SS-SHK-001',
                    'stock_status' => 'instock',
                    'quantity' => 40
                ],
                [
                    'name' => 'Performance Struts',
                    'short_description' => 'Adjustable performance struts',
                    'description' => 'Height-adjustable performance struts with heavy-duty springs.',
                    'regular_price' => 399.99,
                    'sale_price' => 369.99,
                    'SKU' => 'SS-STR-001',
                    'stock_status' => 'instock',
                    'quantity' => 30
                ],
                [
                    'name' => 'Lowering Springs Set',
                    'short_description' => 'Performance lowering springs',
                    'description' => 'Progressive rate lowering springs for improved handling and appearance.',
                    'regular_price' => 199.99,
                    'sale_price' => 179.99,
                    'SKU' => 'SS-SPR-001',
                    'stock_status' => 'instock',
                    'quantity' => 50
                ]
            ]],

            // Electrical System Products
            ['category_slug' => 'electrical-system', 'products' => [
                [
                    'name' => 'High Output Alternator',
                    'short_description' => '200 Amp high-output alternator',
                    'description' => 'Heavy-duty alternator designed for high-current electrical systems.',
                    'regular_price' => 299.99,
                    'sale_price' => 279.99,
                    'SKU' => 'ES-ALT-001',
                    'stock_status' => 'instock',
                    'quantity' => 35
                ],
                [
                    'name' => 'High Torque Starter Motor',
                    'short_description' => 'Gear reduction starter motor',
                    'description' => 'High-torque starter motor for reliable starting in all conditions.',
                    'regular_price' => 199.99,
                    'sale_price' => 179.99,
                    'SKU' => 'ES-STR-001',
                    'stock_status' => 'instock',
                    'quantity' => 45
                ],
                [
                    'name' => 'AGM Performance Battery',
                    'short_description' => 'Absorbed Glass Mat battery',
                    'description' => 'High-performance AGM battery with enhanced cranking power and deep cycle capability.',
                    'regular_price' => 249.99,
                    'sale_price' => 229.99,
                    'SKU' => 'ES-BAT-001',
                    'stock_status' => 'instock',
                    'quantity' => 55
                ]
            ]],

            // Cooling System Products
            ['category_slug' => 'cooling-system', 'products' => [
                [
                    'name' => 'Aluminum Performance Radiator',
                    'short_description' => 'All-aluminum racing radiator',
                    'description' => 'High-efficiency aluminum radiator with increased cooling capacity.',
                    'regular_price' => 399.99,
                    'sale_price' => 369.99,
                    'SKU' => 'CS-RAD-001',
                    'stock_status' => 'instock',
                    'quantity' => 25
                ],
                [
                    'name' => 'High Flow Water Pump',
                    'short_description' => 'Performance water pump',
                    'description' => 'High-flow water pump with improved impeller design for better cooling.',
                    'regular_price' => 149.99,
                    'sale_price' => 139.99,
                    'SKU' => 'CS-WTP-001',
                    'stock_status' => 'instock',
                    'quantity' => 40
                ],
                [
                    'name' => 'Electric Cooling Fan Kit',
                    'short_description' => 'Dual electric fan kit',
                    'description' => 'High-performance electric cooling fan kit with temperature controller.',
                    'regular_price' => 199.99,
                    'sale_price' => 179.99,
                    'SKU' => 'CS-FAN-001',
                    'stock_status' => 'instock',
                    'quantity' => 35
                ]
            ]],

            // Fuel System Products
            ['category_slug' => 'fuel-system', 'products' => [
                [
                    'name' => 'High Flow Fuel Pump',
                    'short_description' => '255 LPH fuel pump',
                    'description' => 'High-flow electric fuel pump for performance applications.',
                    'regular_price' => 199.99,
                    'sale_price' => 179.99,
                    'SKU' => 'FS-PMP-001',
                    'stock_status' => 'instock',
                    'quantity' => 45
                ],
                [
                    'name' => 'Performance Fuel Injectors',
                    'short_description' => 'High-flow fuel injectors set',
                    'description' => 'Matched set of high-flow fuel injectors for increased horsepower.',
                    'regular_price' => 399.99,
                    'sale_price' => 369.99,
                    'SKU' => 'FS-INJ-001',
                    'stock_status' => 'instock',
                    'quantity' => 30
                ],
                [
                    'name' => 'Premium Fuel Filter',
                    'short_description' => 'High-flow fuel filter',
                    'description' => 'High-capacity fuel filter with micron rating for maximum protection.',
                    'regular_price' => 49.99,
                    'sale_price' => 44.99,
                    'SKU' => 'FS-FLT-001',
                    'stock_status' => 'instock',
                    'quantity' => 100
                ]
            ]],

            // Exhaust System Products
            ['category_slug' => 'exhaust-system', 'products' => [
                [
                    'name' => 'Performance Exhaust Manifold',
                    'short_description' => 'Stainless steel header',
                    'description' => 'Stainless steel performance header for improved exhaust flow.',
                    'regular_price' => 399.99,
                    'sale_price' => 369.99,
                    'SKU' => 'EX-MNF-001',
                    'stock_status' => 'instock',
                    'quantity' => 25
                ],
                [
                    'name' => 'High Flow Catalytic Converter',
                    'short_description' => 'Performance catalytic converter',
                    'description' => 'High-flow catalytic converter meeting emissions requirements.',
                    'regular_price' => 299.99,
                    'sale_price' => 279.99,
                    'SKU' => 'EX-CAT-001',
                    'stock_status' => 'instock',
                    'quantity' => 35
                ],
                [
                    'name' => 'Stainless Steel Exhaust System',
                    'short_description' => 'Complete performance exhaust',
                    'description' => 'Full stainless steel performance exhaust system with polished tips.',
                    'regular_price' => 599.99,
                    'sale_price' => 549.99,
                    'SKU' => 'EX-SYS-001',
                    'stock_status' => 'instock',
                    'quantity' => 20
                ]
            ]],

            // Steering System Products
            ['category_slug' => 'steering-system', 'products' => [
                [
                    'name' => 'Sport Steering Wheel',
                    'short_description' => 'Leather-wrapped sport wheel',
                    'description' => 'Premium leather sport steering wheel with ergonomic grip.',
                    'regular_price' => 199.99,
                    'sale_price' => 179.99,
                    'SKU' => 'ST-WHL-001',
                    'stock_status' => 'instock',
                    'quantity' => 40
                ],
                [
                    'name' => 'Quick Ratio Steering Column',
                    'short_description' => 'Performance steering column',
                    'description' => 'Quick-ratio steering column for improved response.',
                    'regular_price' => 299.99,
                    'sale_price' => 279.99,
                    'SKU' => 'ST-COL-001',
                    'stock_status' => 'instock',
                    'quantity' => 25
                ],
                [
                    'name' => 'High Flow Power Steering Pump',
                    'short_description' => 'Performance power steering pump',
                    'description' => 'High-flow power steering pump for precise steering control.',
                    'regular_price' => 159.99,
                    'sale_price' => 149.99,
                    'SKU' => 'ST-PMP-001',
                    'stock_status' => 'instock',
                    'quantity' => 45
                ]
            ]],

            // Tire and Wheel Components Products
            ['category_slug' => 'tire-and-wheel', 'products' => [
                [
                    'name' => 'Performance All-Season Tires',
                    'short_description' => 'High-performance all-season tires',
                    'description' => 'Premium all-season tires with excellent grip and longevity.',
                    'regular_price' => 199.99,
                    'sale_price' => 179.99,
                    'SKU' => 'TW-TIR-001',
                    'stock_status' => 'instock',
                    'quantity' => 60
                ],
                [
                    'name' => 'Lightweight Alloy Rims',
                    'short_description' => 'Forged aluminum wheels',
                    'description' => 'Lightweight forged aluminum wheels for reduced unsprung weight.',
                    'regular_price' => 299.99,
                    'sale_price' => 279.99,
                    'SKU' => 'TW-RIM-001',
                    'stock_status' => 'instock',
                    'quantity' => 40
                ],
                [
                    'name' => 'Chrome Wheel Lug Nuts Set',
                    'short_description' => 'Premium chrome lug nuts',
                    'description' => 'High-strength chrome-plated lug nuts with security key.',
                    'regular_price' => 49.99,
                    'sale_price' => 44.99,
                    'SKU' => 'TW-LUG-001',
                    'stock_status' => 'instock',
                    'quantity' => 150
                ]
            ]]
        ];

        foreach ($products as $categoryProducts) {
            $category = Category::where('slug', $categoryProducts['category_slug'])->first();
            
            if ($category) {
                foreach ($categoryProducts['products'] as $productData) {
                    $product = new Product();
                    $product->name = $productData['name'];
                    $product->slug = Str::slug($productData['name']);
                    $product->short_description = $productData['short_description'];
                    $product->description = $productData['description'];
                    $product->regular_price = $productData['regular_price'];
                    $product->sale_price = $productData['sale_price'];
                    $product->SKU = $productData['SKU'];
                    $product->stock_status = $productData['stock_status'];
                    $product->featured = false;
                    $product->quantity = $productData['quantity'];
                    $product->category_id = $category->id;
                    $product->brand_id = $defaultBrand->id;
                    $product->image = $defaultImageName;
                    $product->save();
                }
            }
        }

        return redirect()->route('admin.products')->with('status', 'Vehicle Systems products have been added successfully!');
    }

    public function add_motor_parts()
    {
        // Create Motor Parts main category
        $motorParts = Category::firstOrCreate(
            ['slug' => 'motor-parts'],
            [
                'name' => 'Motor Parts',
                'slug' => 'motor-parts'
            ]
        );

        // Create a default brand if it doesn't exist
        $defaultBrand = Brand::firstOrCreate(
            ['slug' => 'motor-parts-brand'],
            [
                'name' => 'Motor Parts Brand',
                'slug' => 'motor-parts-brand'
            ]
        );

        $products = [
            ['category_slug' => 'electrical-components', 'name' => 'Electrical Components', 'products' => [
                [
                    'name' => 'Stator',
                    'short_description' => 'High-quality motor stator',
                    'description' => 'Premium quality stator for electric motors with excellent durability.',
                    'regular_price' => 299.99,
                    'sale_price' => 279.99,
                    'SKU' => 'STA-001',
                    'stock_status' => 'instock',
                    'quantity' => 50
                ],
                [
                    'name' => 'Rotor',
                    'short_description' => 'Precision-engineered rotor',
                    'description' => 'High-performance rotor designed for optimal motor efficiency.',
                    'regular_price' => 249.99,
                    'sale_price' => 229.99,
                    'SKU' => 'ROT-001',
                    'stock_status' => 'instock',
                    'quantity' => 45
                ],
                [
                    'name' => 'Windings',
                    'short_description' => 'Copper motor windings',
                    'description' => 'Premium copper windings for enhanced motor performance.',
                    'regular_price' => 179.99,
                    'sale_price' => 159.99,
                    'SKU' => 'WIN-001',
                    'stock_status' => 'instock',
                    'quantity' => 60
                ]
            ]],
            ['category_slug' => 'mechanical-components', 'name' => 'Mechanical Components', 'products' => [
                [
                    'name' => 'Shaft',
                    'short_description' => 'Hardened steel motor shaft',
                    'description' => 'Precision-machined steel shaft for reliable motor operation.',
                    'regular_price' => 149.99,
                    'sale_price' => 139.99,
                    'SKU' => 'SHA-001',
                    'stock_status' => 'instock',
                    'quantity' => 40
                ],
                [
                    'name' => 'Bearings',
                    'short_description' => 'High-performance bearings',
                    'description' => 'Premium quality bearings for smooth motor operation.',
                    'regular_price' => 89.99,
                    'sale_price' => 79.99,
                    'SKU' => 'BEA-001',
                    'stock_status' => 'instock',
                    'quantity' => 100
                ],
                [
                    'name' => 'Casing/Housing',
                    'short_description' => 'Durable motor housing',
                    'description' => 'Heavy-duty motor casing for protection and heat dissipation.',
                    'regular_price' => 199.99,
                    'sale_price' => 189.99,
                    'SKU' => 'CAS-001',
                    'stock_status' => 'instock',
                    'quantity' => 30
                ]
            ]],
            ['category_slug' => 'cooling-components', 'name' => 'Cooling Components', 'products' => [
                [
                    'name' => 'Fans',
                    'short_description' => 'High-efficiency cooling fans',
                    'description' => 'Powerful cooling fans for optimal motor temperature control.',
                    'regular_price' => 79.99,
                    'sale_price' => 69.99,
                    'SKU' => 'FAN-001',
                    'stock_status' => 'instock',
                    'quantity' => 75
                ],
                [
                    'name' => 'Heat Sinks',
                    'short_description' => 'Aluminum heat sinks',
                    'description' => 'Advanced heat sink design for maximum heat dissipation.',
                    'regular_price' => 59.99,
                    'sale_price' => 49.99,
                    'SKU' => 'HSK-001',
                    'stock_status' => 'instock',
                    'quantity' => 85
                ],
                [
                    'name' => 'Cooling Fins',
                    'short_description' => 'Thermal cooling fins',
                    'description' => 'Engineered cooling fins for enhanced heat transfer.',
                    'regular_price' => 39.99,
                    'sale_price' => 34.99,
                    'SKU' => 'CFN-001',
                    'stock_status' => 'instock',
                    'quantity' => 90
                ]
            ]],
            ['category_slug' => 'control-components', 'name' => 'Control Components', 'products' => [
                [
                    'name' => 'Motor Controllers',
                    'short_description' => 'Advanced motor controller',
                    'description' => 'Smart motor controller with multiple protection features.',
                    'regular_price' => 399.99,
                    'sale_price' => 379.99,
                    'SKU' => 'MCT-001',
                    'stock_status' => 'instock',
                    'quantity' => 35
                ],
                [
                    'name' => 'Variable Frequency Drives',
                    'short_description' => 'VFD controller',
                    'description' => 'High-performance VFD for precise motor speed control.',
                    'regular_price' => 499.99,
                    'sale_price' => 469.99,
                    'SKU' => 'VFD-001',
                    'stock_status' => 'instock',
                    'quantity' => 25
                ],
                [
                    'name' => 'Contactor',
                    'short_description' => 'Heavy-duty contactor',
                    'description' => 'Reliable contactor for motor switching applications.',
                    'regular_price' => 129.99,
                    'sale_price' => 119.99,
                    'SKU' => 'CON-001',
                    'stock_status' => 'instock',
                    'quantity' => 55
                ]
            ]],
            ['category_slug' => 'power-supply', 'name' => 'Power Supply', 'products' => [
                [
                    'name' => 'Transformers',
                    'short_description' => 'Power transformers',
                    'description' => 'High-efficiency transformers for motor power supply.',
                    'regular_price' => 299.99,
                    'sale_price' => 279.99,
                    'SKU' => 'TRF-001',
                    'stock_status' => 'instock',
                    'quantity' => 40
                ],
                [
                    'name' => 'Rectifiers',
                    'short_description' => 'Power rectifiers',
                    'description' => 'Heavy-duty rectifiers for DC power conversion.',
                    'regular_price' => 159.99,
                    'sale_price' => 149.99,
                    'SKU' => 'REC-001',
                    'stock_status' => 'instock',
                    'quantity' => 60
                ],
                [
                    'name' => 'Capacitors',
                    'short_description' => 'Power capacitors',
                    'description' => 'High-capacity capacitors for power factor correction.',
                    'regular_price' => 89.99,
                    'sale_price' => 79.99,
                    'SKU' => 'CAP-001',
                    'stock_status' => 'instock',
                    'quantity' => 80
                ]
            ]],
            ['category_slug' => 'mounting-components', 'name' => 'Mounting Components', 'products' => [
                [
                    'name' => 'Motor Brackets',
                    'short_description' => 'Steel mounting brackets',
                    'description' => 'Heavy-duty brackets for secure motor mounting.',
                    'regular_price' => 79.99,
                    'sale_price' => 69.99,
                    'SKU' => 'MBR-001',
                    'stock_status' => 'instock',
                    'quantity' => 70
                ],
                [
                    'name' => 'Brake/Clutch Reservoir Base',
                    'short_description' => 'Reservoir mounting base',
                    'description' => 'Sturdy base for brake and clutch fluid reservoirs.',
                    'regular_price' => 59.99,
                    'sale_price' => 54.99,
                    'SKU' => 'BCR-001',
                    'stock_status' => 'instock',
                    'quantity' => 65
                ],
                [
                    'name' => 'Mounting Plates',
                    'short_description' => 'Universal mounting plates',
                    'description' => 'Versatile plates for various mounting applications.',
                    'regular_price' => 49.99,
                    'sale_price' => 44.99,
                    'SKU' => 'MPL-001',
                    'stock_status' => 'instock',
                    'quantity' => 85
                ]
            ]],
            ['category_slug' => 'transmission-components', 'name' => 'Transmission Components', 'products' => [
                [
                    'name' => 'Transmission Gearbox',
                    'short_description' => 'Complete gearbox assembly',
                    'description' => 'Precision-engineered gearbox for power transmission.',
                    'regular_price' => 599.99,
                    'sale_price' => 569.99,
                    'SKU' => 'TGB-001',
                    'stock_status' => 'instock',
                    'quantity' => 20
                ],
                [
                    'name' => 'Belts',
                    'short_description' => 'Drive belts',
                    'description' => 'High-performance drive belts for power transmission.',
                    'regular_price' => 39.99,
                    'sale_price' => 34.99,
                    'SKU' => 'BLT-001',
                    'stock_status' => 'instock',
                    'quantity' => 100
                ],
                [
                    'name' => 'Pulleys',
                    'short_description' => 'Drive pulleys',
                    'description' => 'Precision-machined pulleys for belt drives.',
                    'regular_price' => 69.99,
                    'sale_price' => 64.99,
                    'SKU' => 'PUL-001',
                    'stock_status' => 'instock',
                    'quantity' => 75
                ]
            ]],
            ['category_slug' => 'safety-components', 'name' => 'Safety Components', 'products' => [
                [
                    'name' => 'Circuit Breakers',
                    'short_description' => 'Motor circuit breakers',
                    'description' => 'Reliable circuit protection for motor circuits.',
                    'regular_price' => 129.99,
                    'sale_price' => 119.99,
                    'SKU' => 'CBR-001',
                    'stock_status' => 'instock',
                    'quantity' => 50
                ],
                [
                    'name' => 'Fuses',
                    'short_description' => 'High-speed fuses',
                    'description' => 'Fast-acting fuses for motor protection.',
                    'regular_price' => 19.99,
                    'sale_price' => 17.99,
                    'SKU' => 'FUS-001',
                    'stock_status' => 'instock',
                    'quantity' => 150
                ],
                [
                    'name' => 'Thermal Protection Devices',
                    'short_description' => 'Thermal overload protection',
                    'description' => 'Advanced thermal protection for motors.',
                    'regular_price' => 89.99,
                    'sale_price' => 79.99,
                    'SKU' => 'TPD-001',
                    'stock_status' => 'instock',
                    'quantity' => 60
                ]
            ]],
            ['category_slug' => 'communication-components', 'name' => 'Communication Components', 'products' => [
                [
                    'name' => 'Communication Protocol Adapters',
                    'short_description' => 'Protocol converters',
                    'description' => 'Versatile protocol adapters for motor control systems.',
                    'regular_price' => 199.99,
                    'sale_price' => 189.99,
                    'SKU' => 'CPA-001',
                    'stock_status' => 'instock',
                    'quantity' => 40
                ],
                [
                    'name' => 'Wiring Harnesses',
                    'short_description' => 'Custom wiring harnesses',
                    'description' => 'Pre-assembled wiring harnesses for motor systems.',
                    'regular_price' => 149.99,
                    'sale_price' => 139.99,
                    'SKU' => 'WHA-001',
                    'stock_status' => 'instock',
                    'quantity' => 55
                ],
                [
                    'name' => 'Connectors',
                    'short_description' => 'Industrial connectors',
                    'description' => 'Heavy-duty connectors for motor wiring.',
                    'regular_price' => 29.99,
                    'sale_price' => 24.99,
                    'SKU' => 'CON-001',
                    'stock_status' => 'instock',
                    'quantity' => 120
                ]
            ]],
            ['category_slug' => 'miscellaneous-components', 'name' => 'Miscellaneous Components', 'products' => [
                [
                    'name' => 'Gaskets',
                    'short_description' => 'Motor gaskets',
                    'description' => 'High-quality gaskets for motor sealing.',
                    'regular_price' => 19.99,
                    'sale_price' => 17.99,
                    'SKU' => 'GSK-001',
                    'stock_status' => 'instock',
                    'quantity' => 200
                ],
                [
                    'name' => 'Seals',
                    'short_description' => 'Motor seals',
                    'description' => 'Durable seals for motor protection.',
                    'regular_price' => 24.99,
                    'sale_price' => 22.99,
                    'SKU' => 'SEA-001',
                    'stock_status' => 'instock',
                    'quantity' => 150
                ],
                [
                    'name' => 'Vibration Dampeners',
                    'short_description' => 'Anti-vibration mounts',
                    'description' => 'Effective vibration dampening for motors.',
                    'regular_price' => 34.99,
                    'sale_price' => 29.99,
                    'SKU' => 'VDA-001',
                    'stock_status' => 'instock',
                    'quantity' => 100
                ]
            ]],
            ['category_slug' => 'base-plates', 'name' => 'Base Plates', 'products' => [
                [
                    'name' => 'Base Plates',
                    'short_description' => 'Motor base plates',
                    'description' => 'Reinforced base plates for stable motor installation.',
                    'regular_price' => 69.99,
                    'sale_price' => 64.99,
                    'SKU' => 'BPL-001',
                    'stock_status' => 'instock',
                    'quantity' => 55
                ]
            ]],
            ['category_slug' => 'protection-components', 'name' => 'Protection Components', 'products' => [
                [
                    'name' => 'Circuit Breakers',
                    'short_description' => 'Motor circuit breakers',
                    'description' => 'Reliable circuit breakers for motor overload protection.',
                    'regular_price' => 149.99,
                    'sale_price' => 139.99,
                    'SKU' => 'CBR-001',
                    'stock_status' => 'instock',
                    'quantity' => 45
                ],
                [
                    'name' => 'Thermal Protectors',
                    'short_description' => 'Thermal protection devices',
                    'description' => 'Advanced thermal protection for motor overheating prevention.',
                    'regular_price' => 89.99,
                    'sale_price' => 79.99,
                    'SKU' => 'TPR-001',
                    'stock_status' => 'instock',
                    'quantity' => 65
                ],
                [
                    'name' => 'Fuses',
                    'short_description' => 'Motor protection fuses',
                    'description' => 'Fast-acting fuses for short circuit protection.',
                    'regular_price' => 29.99,
                    'sale_price' => 24.99,
                    'SKU' => 'FUS-001',
                    'stock_status' => 'instock',
                    'quantity' => 120
                ]
            ]],
            ['category_slug' => 'transmission-components', 'name' => 'Transmission Components', 'products' => [
                [
                    'name' => 'Gears',
                    'short_description' => 'Precision motor gears',
                    'description' => 'High-strength gears for reliable power transmission.',
                    'regular_price' => 199.99,
                    'sale_price' => 189.99,
                    'SKU' => 'GER-001',
                    'stock_status' => 'instock',
                    'quantity' => 40
                ],
                [
                    'name' => 'Pulleys',
                    'short_description' => 'Motor drive pulleys',
                    'description' => 'Balanced pulleys for smooth power transfer.',
                    'regular_price' => 79.99,
                    'sale_price' => 69.99,
                    'SKU' => 'PUL-001',
                    'stock_status' => 'instock',
                    'quantity' => 55
                ],
                [
                    'name' => 'Belts',
                    'short_description' => 'Drive belts',
                    'description' => 'Durable drive belts for efficient power transmission.',
                    'regular_price' => 49.99,
                    'sale_price' => 44.99,
                    'SKU' => 'BLT-001',
                    'stock_status' => 'instock',
                    'quantity' => 80
                ]
            ]],
            ['category_slug' => 'sensors-and-feedback', 'name' => 'Sensors and Feedback', 'products' => [
                [
                    'name' => 'Speed Sensors',
                    'short_description' => 'Motor speed sensors',
                    'description' => 'Precise speed monitoring sensors for motor control.',
                    'regular_price' => 129.99,
                    'sale_price' => 119.99,
                    'SKU' => 'SPD-001',
                    'stock_status' => 'instock',
                    'quantity' => 45
                ],
                [
                    'name' => 'Temperature Sensors',
                    'short_description' => 'Motor temperature sensors',
                    'description' => 'Accurate temperature monitoring for motor protection.',
                    'regular_price' => 69.99,
                    'sale_price' => 59.99,
                    'SKU' => 'TMP-001',
                    'stock_status' => 'instock',
                    'quantity' => 60
                ],
                [
                    'name' => 'Position Encoders',
                    'short_description' => 'Motor position encoders',
                    'description' => 'High-resolution encoders for precise position feedback.',
                    'regular_price' => 179.99,
                    'sale_price' => 169.99,
                    'SKU' => 'ENC-001',
                    'stock_status' => 'instock',
                    'quantity' => 35
                ]
            ]],
            ['category_slug' => 'maintenance-components', 'name' => 'Maintenance Components', 'products' => [
                [
                    'name' => 'Lubricants',
                    'short_description' => 'Motor lubricants',
                    'description' => 'High-performance lubricants for motor maintenance.',
                    'regular_price' => 39.99,
                    'sale_price' => 34.99,
                    'SKU' => 'LUB-001',
                    'stock_status' => 'instock',
                    'quantity' => 100
                ],
                [
                    'name' => 'Cleaning Kits',
                    'short_description' => 'Motor cleaning kit',
                    'description' => 'Complete cleaning kit for motor maintenance.',
                    'regular_price' => 59.99,
                    'sale_price' => 54.99,
                    'SKU' => 'CLN-001',
                    'stock_status' => 'instock',
                    'quantity' => 70
                ],
                [
                    'name' => 'Repair Tools',
                    'short_description' => 'Motor repair toolkit',
                    'description' => 'Essential tools for motor maintenance and repair.',
                    'regular_price' => 149.99,
                    'sale_price' => 139.99,
                    'SKU' => 'TLS-001',
                    'stock_status' => 'instock',
                    'quantity' => 40
                ]
            ]]
        ];

        // Create categories and products
        foreach ($products as $categoryData) {
            // Create category
            $category = Category::firstOrCreate(
                ['slug' => $categoryData['category_slug']],
                [
                    'name' => $categoryData['name'],
                    'slug' => $categoryData['category_slug'],
                    'parent_id' => $motorParts->id
                ]
            );

            // Create products for this category
            foreach ($categoryData['products'] as $productData) {
                $product = Product::firstOrCreate(
                    ['SKU' => $productData['SKU']],
                    array_merge($productData, [
                        'slug' => Str::slug($productData['name']),
                        'brand_id' => $defaultBrand->id,
                        'category_id' => $category->id,
                        'featured' => 0,
                        'status' => 1
                    ])
                );
            }
        }

        return redirect()->route('admin.products')->with('status', 'Motor parts categories and products have been added successfully!');
    }

    public function quick_add_motor_parts()
    {
        // Create or get the Motor Parts category
        $motorParts = Category::firstOrCreate(
            ['slug' => 'motor-parts'],
            ['name' => 'Motor Parts', 'slug' => 'motor-parts']
        );

        // Create or get the brand
        $brand = Brand::firstOrCreate(
            ['slug' => 'motor-parts-brand'],
            ['name' => 'Motor Parts Brand', 'slug' => 'motor-parts-brand']
        );

        // Define all categories and their products
        $categories = [
            'electrical-components' => [
                'name' => 'Electrical Components',
                'products' => [
                    [
                        'name' => 'Stator',
                        'price' => 299.99,
                        'description' => 'Premium quality stator for electric motors with excellent durability'
                    ],
                    [
                        'name' => 'Rotor',
                        'price' => 249.99,
                        'description' => 'High-performance rotor designed for optimal motor efficiency'
                    ],
                    [
                        'name' => 'Motor Windings',
                        'price' => 179.99,
                        'description' => 'Premium copper windings for enhanced motor performance'
                    ]
                ]
            ],
            'mechanical-components' => [
                'name' => 'Mechanical Components',
                'products' => [
                    [
                        'name' => 'Drive Shaft',
                        'price' => 399.99,
                        'description' => 'Precision machined drive shaft for reliable motor operation'
                    ],
                    [
                        'name' => 'Bearings Set',
                        'price' => 89.99,
                        'description' => 'High-performance bearings for smooth motor operation'
                    ],
                    [
                        'name' => 'Motor Housing',
                        'price' => 299.99,
                        'description' => 'Heavy-duty motor casing for protection and heat dissipation'
                    ]
                ]
            ],
            'cooling-components' => [
                'name' => 'Cooling Components',
                'products' => [
                    [
                        'name' => 'Cooling Fan',
                        'price' => 79.99,
                        'description' => 'Powerful cooling fans for optimal motor temperature control'
                    ],
                    [
                        'name' => 'Heat Sink',
                        'price' => 59.99,
                        'description' => 'Advanced heat sink design for maximum heat dissipation'
                    ],
                    [
                        'name' => 'Thermal Fins',
                        'price' => 39.99,
                        'description' => 'Engineered cooling fins for enhanced heat transfer'
                    ]
                ]
            ]
        ];

        // Create categories and products
        foreach ($categories as $slug => $categoryData) {
            // Create category
            $category = Category::firstOrCreate(
                ['slug' => $slug],
                [
                    'name' => $categoryData['name'],
                    'slug' => $slug,
                    'parent_id' => $motorParts->id
                ]
            );

            // Create products for this category
            foreach ($categoryData['products'] as $productData) {
                $slug = Str::slug($productData['name']);
                Product::firstOrCreate(
                    ['slug' => $slug],
                    [
                        'name' => $productData['name'],
                        'slug' => $slug,
                        'short_description' => 'Quality ' . strtolower($productData['name']),
                        'description' => $productData['description'],
                        'regular_price' => $productData['price'],
                        'sale_price' => $productData['price'] * 0.9,
                        'SKU' => strtoupper(substr(str_replace([' ', '-'], '', $productData['name']), 0, 3)) . '-' . rand(100, 999),
                        'stock_status' => 'instock',
                        'quantity' => rand(20, 100),
                        'category_id' => $category->id,
                        'brand_id' => $brand->id,
                        'featured' => 0
                    ]
                );
            }
        }

        return redirect()->route('admin.products')->with('status', 'All motor parts categories and products have been added successfully!');
    }
}