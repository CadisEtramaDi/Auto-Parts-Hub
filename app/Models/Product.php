<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'short_description',
        'description',
        'regular_price',
        'sale_price',
        'SKU',
        'stock_status',
        'featured',
        'quantity',
        'image',
        'images',
        'category_id',
        'brand_id',
        'status'
    ];

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function category()
    {
        return $this->belongsTo(Category::class,'category_id');
    }  
    public function brand()
    {
        return $this->belongsTo(Brand::class,'brand_id');
    }

        public function inventory()
    {
        return $this->hasOne(Inventory::class);
    }

        public function inventoryTransactions()
    {
        return $this->hasMany(InventoryTransaction::class);
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }
}