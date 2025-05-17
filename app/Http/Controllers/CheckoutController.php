<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Transaction;
use App\Models\Product;
use App\Models\Address;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Surfsidemedia\Shoppingcart\Facades\Cart as CartFacade;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

class CheckoutController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        if (!CartFacade::instance('cart')->count()) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty');
        }

        return view('checkout.index', [
            'user' => Auth::user(),
            'cartItems' => CartFacade::instance('cart')->content(),
            'subtotal' => CartFacade::instance('cart')->subtotal(),
            'tax' => CartFacade::instance('cart')->tax(),
            'total' => CartFacade::instance('cart')->total()
        ]);
    }

    public function showConfirmation(Request $request)
    {
        // Get checkout data from session
        $checkoutData = session('checkout_data');
        if (!$checkoutData) {
            return redirect()->route('checkout')->with('error', 'Please fill out the checkout form first');
        }

        $cartItems = CartFacade::instance('cart')->content();
        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty');
        }

        return view('checkout.confirmation', [
            'cartItems' => $cartItems,
            'checkoutData' => $checkoutData,
            'subtotal' => CartFacade::instance('cart')->subtotal(),
            'tax' => CartFacade::instance('cart')->tax(),
            'total' => CartFacade::instance('cart')->total()
        ]);
    }

    public function processConfirmation(Request $request)
    {
        $rules = [
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'pickup_date' => 'required|date|after_or_equal:today',
            'pickup_time' => 'required|string',
            'special_instructions' => 'nullable|string',
            'payment_method' => 'required|string|in:cash,gcash,card',
        ];

        // Conditional validation
        if ($request->payment_method === 'gcash') {
            $rules['gcash_name'] = 'required|string|max:255';
            $rules['gcash_phone'] = 'required|string|max:20';
        }
        if ($request->payment_method === 'card') {
            $rules['card_name'] = 'required|string|max:255';
            $rules['card_number'] = 'required|digits:16';
        }

        $validated = $request->validate($rules);

        $cartItems = CartFacade::instance('cart')->content();
        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty');
        }

        // Check stock availability
        foreach ($cartItems as $item) {
            $product = Product::find($item->id);
            if (!$product || $product->quantity < $item->qty) {
                return redirect()->route('cart.index')->with('error', 'Some items in your cart are no longer available');
            }
        }

        // Store checkout data in session (including extra fields)
        $request->session()->put('checkout_data', $validated);

        // Redirect to confirmation page
        return redirect()->route('checkout.confirm');
    }

    public function placeOrder(Request $request)
    {
        // Get checkout data from session
        $checkoutData = session('checkout_data');
        if (!$checkoutData) {
            return redirect()->route('checkout')->with('error', 'Checkout information not found');
        }

        $cartItems = CartFacade::instance('cart')->content();
        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty');
        }

        Log::info('Checkout data', ['checkoutData' => $checkoutData, 'cartItems' => $cartItems]);

        try {
            DB::beginTransaction();

            // Create the order
            $order = Order::create([
                'user_id' => Auth::id(),
                'name' => $checkoutData['name'],
                'phone' => $checkoutData['phone'],
                'pickup_date' => $checkoutData['pickup_date'],
                'pickup_time' => $checkoutData['pickup_time'],
                'special_instructions' => $checkoutData['special_instructions'] ?? null,
                'payment_method' => $checkoutData['payment_method'],
                'payment_status' => 'pending',
                'subtotal' => floatval(str_replace(',', '', CartFacade::instance('cart')->subtotal())),
                'tax' => floatval(str_replace(',', '', CartFacade::instance('cart')->tax())),
                'total' => floatval(str_replace(',', '', CartFacade::instance('cart')->total())),
                'status' => 'ordered'
            ]);

            // Create order items and update stock
            foreach ($cartItems as $item) {
                $product = Product::find($item->id);
                
                if (!$product || $product->quantity < $item->qty) {
                    throw new \Exception('Product not available in requested quantity');
                }

                $order->items()->create([
                    'product_id' => $item->id,
                    'name' => $item->name,
                    'price' => $item->price,
                    'unit_price' => $item->price,
                    'quantity' => $item->qty,
                    'subtotal' => $item->price * $item->qty
                ]);

                // Update stock
                $product->decrement('quantity', $item->qty);
            }

            DB::commit();

            // Clear cart and checkout data
            CartFacade::instance('cart')->destroy();
            session()->forget('checkout_data');

            return redirect()->route('checkout.success', ['order' => $order->id])
                           ->with('success', 'Order placed successfully!');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Order placement failed', [
                'error' => $e->getMessage(),
                'user_id' => Auth::id(),
                'checkout_data' => $checkoutData
            ]);
            return redirect()->route('checkout')
                           ->with('error', 'There was an error processing your order. Please try again.');
        }
    }

    public function confirmation($orderId)
    {
        $order = Order::with(['items', 'user'])->findOrFail($orderId);
        
        if ($order->user_id !== Auth::id()) {
            abort(403);
        }
        
        return view('checkout.success', compact('order'));
    }

    public function success(Order $order)
    {
        if ($order->user_id !== Auth::id()) {
            abort(403);
        }
        
        return view('checkout.success', compact('order'));
    }
} 