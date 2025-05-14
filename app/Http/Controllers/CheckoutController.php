<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Cart;

class CheckoutController extends Controller
{
    public function checkout()
    {
        if(Cart::instance('cart')->count() == 0) {
            return redirect()->route('shop')->with('error', 'Your cart is empty!');
        }
        return view('checkout');
    }

    public function confirmOrder(Request $request)
    {
        // Validate checkout form data
        $request->validate([
            'mode' => 'required|in:cash,gcash,bank_transfer',
            'payment_proof' => 'required_if:mode,gcash,bank_transfer|file|image|max:2048',
            'notes' => 'nullable|string'
        ]);

        $data = $request->all();

        // Handle payment proof upload
        if ($request->hasFile('payment_proof')) {
            $file = $request->file('payment_proof');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/payments'), $filename);
            $data['payment_proof'] = $filename;
        }

        // Store checkout data in session
        session(['checkout' => $data]);

        return response()->json([
            'status' => 'success',
            'message' => 'Order confirmed'
        ]);
    }

    public function placeOrder(Request $request)
    {
        if(Cart::instance('cart')->count() == 0) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty!');
        }

        $checkout = session('checkout');
        if(!$checkout) {
            return redirect()->route('cart.index')->with('error', 'Please complete the checkout form first.');
        }

        try {
            DB::beginTransaction();

            // Create the order
            $order = new Order();
            $order->user_id = Auth::id();
            $order->subtotal = Cart::instance('cart')->subtotal();
            $order->tax = Cart::instance('cart')->tax();
            $order->total = Cart::instance('cart')->total();
            $order->notes = $checkout['notes'] ?? null;
            $order->status = 'pending';
            $order->is_pickup = true;
            $order->save();

            // Create order items
            foreach(Cart::instance('cart')->content() as $item) {
                $orderItem = new OrderItem();
                $orderItem->order_id = $order->id;
                $orderItem->product_id = $item->id;
                $orderItem->price = $item->price;
                $orderItem->quantity = $item->qty;
                $orderItem->save();
            }

            // Create transaction record
            $transaction = new Transaction();
            $transaction->order_id = $order->id;
            $transaction->amount = $order->total;
            $transaction->mode = $checkout['mode'];
            $transaction->status = $checkout['mode'] === 'cash' ? 'pending' : 'processing';
            $transaction->proof = $checkout['payment_proof'] ?? null;
            $transaction->save();

            DB::commit();

            // Clear the cart
            Cart::instance('cart')->destroy();

            // Clear the checkout session
            session()->forget('checkout');

            $successMessage = $transaction->mode === 'cash' 
                ? 'Order placed successfully! We will notify you when your order is ready for pickup.'
                : 'Order placed successfully! We will verify your payment and notify you when your order is ready for pickup.';

            return redirect()->route('user.orders.show', $order)->with('success', $successMessage);

        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->route('cart.index')->with('error', 'An error occurred while placing your order. Please try again.');
        }
    }

    public function thankYou()
    {
        return view('thank-you');
    }
} 