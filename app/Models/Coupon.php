<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    use HasFactory;

    // Specify the table name if it's not the plural of the model name
    protected $table = 'coupons';

    // Define which attributes are mass assignable
    protected $fillable = [
        'code',
        'type',
        'value',
        'cart_value',
        'expiry_date',
    ];

    // Cast date fields to Carbon instances
    protected $casts = [
        'value' => 'float',
        'cart_value' => 'float',
        'expiry_date' => 'date'
    ];
}
