<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Gloudemans\Shoppingcart\Facades\Cart;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Auth::user()->orders()->latest()->paginate(10);
        return view('orders.index', compact('orders'));
    }

    public function show(Order $order)
    {
        // Ensure the user can only view their own orders
        if ($order->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $order->load('items.product');
        return view('orders.show', compact('order'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
            'address' => 'required|string',
            'mode' => 'required|in:card,gcash',
            'reference_number' => 'required'
        ]);

        try {
            DB::beginTransaction();

            // Create order
            $order = Order::create([
                'user_id' => auth()->id(),
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'address' => $request->address,
                'notes' => $request->notes,
                'subtotal' => Cart::instance('cart')->subtotal(2, '.', ''),
                'discount' => session()->has('coupon') ? session('discounts')['discount'] : 0,
                'tax' => session()->has('coupon') ? session('discounts')['tax'] : Cart::instance('cart')->tax(2, '.', ''),
                'total' => session()->has('coupon') ? session('discounts')['total'] : Cart::instance('cart')->total(2, '.', ''),
                'status' => 'pending'
            ]);

            // Create order items
            foreach(Cart::instance('cart')->content() as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item->model->id,
                    'name' => $item->name,
                    'price' => $item->price,
                    'quantity' => $item->qty,
                    'subtotal' => $item->subtotal()
                ]);
            }

            // Create transaction record
            Transaction::create([
                'user_id' => auth()->id(),
                'order_id' => $order->id,
                'mode' => $request->mode,
                'status' => 'pending',
            ]);

            // Clear cart and coupon
            Cart::instance('cart')->destroy();
            session()->forget(['coupon', 'discounts']);

            DB::commit();

            return response()->json([
                'success' => true,
                'order_id' => $order->id
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Failed to create order'
            ], 500);
        }
    }
} 