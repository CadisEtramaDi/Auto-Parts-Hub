<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\Admin\SupplierController;
use App\Http\Controllers\Usercontroller;
use App\Http\Controllers\CouponController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Middleware\AuthAdmin;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;


Auth::routes();

Route::get('/', [HomeController::class, 'index'])->name('home.index');
Route::get('/shop',[ShopController::class,'index'])->name('shop.index');
Route::get('/shop/category/{slug}',[ShopController::class,'category'])->name('shop.category');
Route::get('/shop/{product_slug}', [ShopController::class,'product_details'])->name('shop.product.details');
Route::post('/shop/search', [ShopController::class,'search'])->name('shop.search');

Route::get('/cart',[CartController::class,'index'])->name('cart.index');
Route::post('/cart/add',[CartController::class,'add_to_cart'])->name('cart.add');
Route::put('cart/increase-quantity/{rowId}',[CartController::class,'increase_cart_quantity'])->name('cart.qty.increase');
Route::put('cart/decrease-quantity/{rowId}',[CartController::class,'decrease_cart_quantity'])->name('cart.qty.decrease');
Route::delete('cart/remove/{rowId}', [CartController::class,'remove_item'])->name('cart.item.remove');
Route::delete('cart/clear', [CartController::class,'empty_cart'])->name('cart.empty');

Route::post('/cart/apply-coupon',[CartController::class,'apply_coupon_code'])->name('cart.coupon.apply');
Route::get('/cart/remove-coupon',[CartController::class,'remove_coupon_code'])->name('cart.coupon.remove');

Route::middleware(['auth'])->group(function(){
    Route::get('/account-dashboard', [Usercontroller::class, 'index'])->name('user.index');
    Route::get('/account/profile', [Usercontroller::class, 'profile'])->name('user.profile');
    Route::post('/account/profile/update', [Usercontroller::class, 'updateProfile'])->name('user.profile.update');
    Route::post('/account/contact/store', [Usercontroller::class, 'storeContact'])->name('user.contact.store');
    Route::get('/my-orders', [App\Http\Controllers\UserController::class, 'orders'])->name('user.orders');
    Route::get('/my-orders/{order}/receipt', [App\Http\Controllers\UserController::class, 'receipt'])->name('user.orders.receipt');
});
Route::middleware(['auth',AuthAdmin::class])->group(function(){
    Route::get('/admin', [AdminController::class, 'index'])->name('admin.index');
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

    // Admin Order Routes
    Route::get('/admin/orders', [App\Http\Controllers\Admin\OrderController::class, 'index'])->name('admin.orders.index');
    Route::get('/admin/orders/{order}', [App\Http\Controllers\Admin\OrderController::class, 'show'])->name('admin.orders.show');
    Route::put('/admin/orders/{order}/status', [App\Http\Controllers\Admin\OrderController::class, 'updateStatus'])->name('admin.orders.update-status');
    Route::put('/admin/orders/{order}/payment-status', [App\Http\Controllers\Admin\OrderController::class, 'updatePaymentStatus'])->name('admin.orders.update-payment-status');
    Route::get('/admin/orders/{order}/receipt', [App\Http\Controllers\Admin\OrderController::class, 'receipt'])->name('admin.orders.receipt');

    Route::get('/orders/{order}/receipt', [App\Http\Controllers\Admin\OrderController::class, 'receipt'])->name('orders.receipt');

});

// Checkout Routes
Route::middleware(['auth'])->group(function () {
    // Step 1: Show checkout form
    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout');
    
    // Step 2: Show confirmation page
    Route::get('/checkout/confirm', [CheckoutController::class, 'showConfirmation'])->name('checkout.confirm');
    Route::post('/checkout/confirm', [CheckoutController::class, 'processConfirmation'])->name('checkout.process');
    
    // Step 3: Place order
    Route::post('/checkout/place-order', [CheckoutController::class, 'placeOrder'])->name('place.order');
    
    // Step 4: Show success page
    Route::get('/checkout/success/{order}', [CheckoutController::class, 'success'])->name('checkout.success');
    
    // Orders list
    Route::get('/orders', [App\Http\Controllers\UserController::class, 'orders'])->name('orders.index');
});