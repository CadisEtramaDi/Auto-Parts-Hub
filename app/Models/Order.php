<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'subtotal',
        'discount',
        'tax',
        'total',
        'coupon_code',
        'notes',
        'status'
    ];

    /**
     * Get the user that owns the order.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the items for the order.
     */
    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    /**
     * Get the order status options.
     */
    public static function statusOptions(): array
    {
        return [
            'pending' => 'Pending',
            'processing' => 'Processing',
            'completed' => 'Completed',
            'cancelled' => 'Cancelled',
        ];
    }

    public static function createFromCart($user, $cart, $notes = null)
    {
        $order = self::create([
            'user_id' => $user->id,
            'subtotal' => $cart->subtotal(),
            'discount' => session()->has('discounts') ? session('discounts')['discount'] : 0,
            'tax' => session()->has('discounts') ? session('discounts')['tax'] : 0,
            'total' => session()->has('discounts') ? session('discounts')['total'] : $cart->total(),
            'coupon_code' => session()->has('coupon') ? session('coupon')['code'] : null,
            'notes' => $notes,
            'status' => 'pending'
        ]);

        foreach ($cart->content() as $item) {
            $order->items()->create([
                'product_id' => $item->id,
                'product_name' => $item->name,
                'price' => $item->price,
                'quantity' => $item->qty,
                'subtotal' => $item->subtotal(),
                'color' => 'Yellow', // You might want to make this dynamic
                'size' => 'L' // You might want to make this dynamic
            ]);
        }

        return $order;
    }
}
