<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inventory extends Model
{
    use HasFactory;

    protected $table = 'inventory';
    
    protected $fillable = [
        'product_id',
        'quantity',
        'sku',
        'location'
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
    
    public function transactions()
    {
        return $this->hasMany(InventoryTransaction::class, 'product_id', 'product_id');
    }

}
