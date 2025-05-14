<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\Admin\SupplierController;
use App\Http\Controllers\Usercontroller;
use App\Http\Controllers\CouponController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\CheckoutController;
use App\Http\Middleware\AuthAdmin;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Auth::routes();

Route::get('/', [HomeController::class, 'index'])->name('home.index');
Route::get('/shop',[ShopController::class,'index'])->name('shop.index');
Route::get('/shop/{product_slug}', [ShopController::class,'product_details'])->name('shop.product.details');

Route::get('/cart',[CartController::class,'index'])->name('cart.index');
Route::post('/cart/add',[CartController::class,'add_to_cart'])->name('cart.add');
Route::put('cart/increase-quantity/{rowId}',[CartController::class,'increase_cart_quantity'])->name('cart.qty.increase');
Route::put('cart/decrease-quantity/{rowId}',[CartController::class,'decrease_cart_quantity'])->name('cart.qty.decrease');
Route::delete('cart/remove/{rowId}', [CartController::class,'remove_item'])->name('cart.item.remove'); 
Route::delete('cart/clear', [CartController::class,'empty_cart'])->name('cart.empty');

Route::post('/cart/apply-coupon',[CartController::class,'apply_coupon_code'])->name('cart.coupon.apply');

Route::middleware(['auth'])->group(function(){
    Route::get('/account-dashboard', [Usercontroller::class, 'index'])->name('user.index');
    Route::post('/cart/place-order', [CartController::class, 'placeOrder'])->name('cart.place.order');
    
    // User Order routes
    Route::get('/my-orders', [App\Http\Controllers\UserOrderController::class, 'index'])->name('user.orders.index');
    Route::get('/my-orders/{order}', [App\Http\Controllers\UserOrderController::class, 'show'])->name('user.orders.show');
});

Route::middleware(['auth',AuthAdmin::class])->group(function(){
    Route::get('/admin', [AdminController::class, 'index'])->name('admin.index');
    
    // Admin Order Routes
    Route::get('/admin/orders', [App\Http\Controllers\Admin\OrderController::class, 'index'])->name('admin.orders.index');
    Route::get('/admin/orders/{order}', [App\Http\Controllers\Admin\OrderController::class, 'show'])->name('admin.orders.show');
    Route::put('/admin/orders/{order}/status', [App\Http\Controllers\Admin\OrderController::class, 'updateStatus'])->name('admin.orders.update-status');
    
    Route::get('/admin/brands',[AdminController::class,'brands'])->name('admin.brands');
    Route::get('/admin/brand/add', [AdminController::class,'add_brand'])->name('admin.brand.add');
    Route::post('/admin/brand/store',[AdminController::class,'brand_store'])->name('admin.brand.store');
    Route::get('/admin/brand/edit/{id}',[AdminController::class,'edit_brand'])->name('admin.brand.edit');
    Route::put('/admin/brand/update',[AdminController::class,'brand_update'])->name('admin.brand.update');
    Route::delete('/admin/brand/{id}/delete',[AdminController::class,'delete_brand'])->name('admin.brand.delete');

    Route::get('/admin/categories',[AdminController::class,'categories'])->name('admin.categories');
    Route::get('/admin/category/add',[AdminController::class,'add_category'])->name('admin.category.add');
    Route::post('/admin/category/store',[AdminController::class,'add_category_store'])->name('admin.category.store');
    Route::get('/admin/category/{id}/edit',[AdminController::class,'edit_category'])->name('admin.category.edit');
    Route::put('/admin/category/update',[AdminController::class,'update_category'])->name('admin.category.update');
    Route::delete('/admin/category/{id}/delete',[AdminController::class,'delete_category'])->name('admin.category.delete');

    Route::get('/admin/products',[AdminController::class,'products'])->name('admin.products');
    Route::get('/admin/product/add',[AdminController::class,'add_product'])->name('admin.product.add');
    Route::post('/admin/product/store',[AdminController::class,'product_store'])->name('admin.product.store');
    Route::get('/admin/product/{id}/edit', [AdminController::class,'edit_product'])->name('admin.product.edit');
    Route::put('/admin/product/update',[AdminController::class,'update_product'])->name('admin.product.update');
    Route::delete('/admin/product/{id}/delete',[AdminController::class,'delete_product'])->name('admin.product.delete');

    Route::get('/inventory', [InventoryController::class, 'index'])->name('admin.inventory.index');
    Route::get('/inventory/create', [InventoryController::class, 'create'])->name('admin.inventory.create');
    Route::post('/inventory', [InventoryController::class, 'store'])->name('admin.inventory.store');
    Route::get('/inventory/{id}', [InventoryController::class, 'show'])->name('admin.inventory.show');
    Route::get('/{id}/edit', [InventoryController::class, 'edit'])->name('admin.inventory.edit');
    Route::put('/{id}', [InventoryController::class, 'update'])->name('admin.inventory.update');
    Route::delete('/{id}', [InventoryController::class, 'destroy'])->name('admin.inventory.destroy');

    Route::get('/inventory/stock-in/form', [InventoryController::class, 'stockInForm'])->name('admin.inventory.stock-in.form');
    Route::post('/inventory/stock-in', [InventoryController::class, 'stockIn'])->name('admin.inventory.stock-in');
    Route::get('/inventory/stock-out/form', [InventoryController::class, 'stockOutForm'])->name('admin.inventory.stock-out.form');
    Route::post('/inventory/stock-out', [InventoryController::class, 'stockOut'])->name('admin.inventory.stock-out');

    Route::get('/suppliers', [SupplierController::class, 'index'])->name('suppliers');
    Route::get('/supplier/add', [SupplierController::class, 'create'])->name('supplier.add');
    Route::post('/supplier/store', [SupplierController::class, 'store'])->name('supplier.store');
    Route::get('/supplier/edit/{id}', [SupplierController::class, 'edit'])->name('supplier.edit');
    Route::put('/supplier/update/{id}', [SupplierController::class, 'update'])->name('supplier.update');
    Route::delete('/supplier/delete/{id}', [SupplierController::class, 'destroy'])->name('supplier.delete');

    Route::get('/admin/coupons',[AdminController::class,'coupons'])->name('admin.coupons');
    Route::get('admin/coupon/add', [AdminController::class, 'coupon_add'])->name('admin.coupon.add');
    Route::post('/admin/coupon/store',[AdminController::class,'coupon_store'])->name('admin.coupon.store');
    Route::get('/admin/coupon/{id}/edit', [AdminController::class, 'coupon_edit'])->name('admin.coupon.edit');
    Route::put('/admin/coupon/update', [AdminController::class, 'coupon_update'])->name('admin.coupon.update');
    Route::delete('/admin/coupon/{id}/delete', [AdminController::class, 'coupon_delete'])->name('admin.coupon.delete');

    Route::get('/admin/add-auto-parts-categories', [AdminController::class, 'add_auto_parts_categories'])->name('admin.add.auto.parts.categories');
    Route::get('/admin/add-auto-parts-products', [AdminController::class, 'add_auto_parts_products'])->name('admin.add.auto.parts.products');

    // Add motor parts categories and products
    Route::get('/admin/add-motor-parts', [App\Http\Controllers\Admin\MotorPartsController::class, 'addMotorPartsCategories'])->name('admin.add.motor.parts');

    Route::get('/admin/quick-add-motor-parts', [App\Http\Controllers\AdminController::class, 'quick_add_motor_parts'])->name('admin.quick_add_motor_parts');
});

// Cart & Checkout Routes
Route::post('/confirm-order', [CheckoutController::class, 'confirmOrder'])->name('confirm-order');
Route::post('/place-order', [CheckoutController::class, 'placeOrder'])->name('place-order');