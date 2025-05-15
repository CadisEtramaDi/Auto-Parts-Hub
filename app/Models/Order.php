<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\OrderItem;
use App\Models\Transaction;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'phone',
        'pickup_date',
        'pickup_time',
        'special_requests',
        'payment_method',
        'payment_status',
        'subtotal',
        'tax',
        'total',
        'status',
        'completed_at',
        'cancelled_at'
    ];

    protected $casts = [
        'subtotal' => 'decimal:2',
        'tax' => 'decimal:2',
        'total' => 'decimal:2',
        'pickup_date' => 'date',
        'completed_at' => 'datetime',
        'cancelled_at' => 'datetime'
    ];

    protected $attributes = [
        'status' => 'pending',
        'payment_status' => 'pending',
        'payment_method' => 'cash'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function transaction()
    {
        return $this->hasOne(Transaction::class);
    }
}