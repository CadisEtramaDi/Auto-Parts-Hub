<?php

namespace App\Http\Controllers;

use App\Models\Coupon;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Surfsidemedia\Shoppingcart\Facades\Cart;
use Illuminate\Support\Facades\DB;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Transaction;

class CartController extends Controller
{
    public function index()
    {
        $items = Cart::instance('cart')->content();
        return view('cart',compact('items'));
    }

    public function add_to_cart(Request $request)
    {
        try {
            Cart::instance('cart')->add($request->id, $request->name, $request->quantity, $request->price)->associate('App\Models\Product');
            return redirect()->back()->with('success', 'Item added to cart successfully!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to add item to cart.');
        }
    }

    public function increase_cart_quantity($rowId)
    {
        try {
            $product = Cart::instance('cart')->get($rowId);
            if ($product) {
                $qty = $product->qty + 1;
                Cart::instance('cart')->update($rowId, $qty);
                return redirect()->back()->with('success', 'Quantity updated successfully!');
            }
            return redirect()->back()->with('error', 'Item not found in cart.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to update quantity.');
        }
    }

    public function decrease_cart_quantity($rowId)
    {
        try {
            $product = Cart::instance('cart')->get($rowId);
            if ($product) {
                $qty = max(1, $product->qty - 1);
                Cart::instance('cart')->update($rowId, $qty);
                return redirect()->back()->with('success', 'Quantity updated successfully!');
            }
            return redirect()->back()->with('error', 'Item not found in cart.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to update quantity.');
        }
    }

    public function remove_item($rowId)
    {
        try {
            Cart::instance('cart')->remove($rowId);
            return redirect()->back()->with('success', 'Item removed from cart!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to remove item.');
        }
    }

    public function empty_cart()
    {
        try {
            Cart::instance('cart')->destroy();
            Session::forget('coupon');
            Session::forget('discounts');
            return redirect()->back()->with('success', 'Cart cleared successfully!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to clear cart.');
        }
    }

    public function apply_coupon_code(Request $request)
    {
        $coupon_code = $request->coupon_code;
        if (isset($coupon_code)) {
            $coupon = Coupon::where('code', $coupon_code)
                ->where('expiry_date', '>=', Carbon::today())
                ->where('cart_value', '<=', Cart::instance('cart')->subtotal())
                ->first();

            if (!$coupon) {
            return redirect()->back()->with('error', 'Invalid coupon code!'); 

            } else {
                Session::put('coupon', [
                    'code' => $coupon->code,
                    'type' => $coupon->type,
                    'value' => $coupon->value,
                    'cart_value' => $coupon->cart_value
                ]);
                $this->calculateDiscount();
                return redirect()->back()->with('success', 'Coupon has been applied!');
            }
        } else {
        return redirect()->back()->with('error', 'Invalid coupon code!'); // Corrected typo
    }
}

    public function calculateDiscount()
    {
        $discount = 0;
        if(Session::has('coupon'))
        {
            if(Session::get('coupon')['type']=='fixed')
            {
                $discount = Session::get('coupon')['value'];
            }
            else{
                $discount = (Cart::instance('cart')->subtotal() * Session::get('coupon')['value'])/100;
            }

            $subtotalAfterDiscount = Cart::instance('cart')->subtotal() - $discount;
            $taxAfterDiscount = ($subtotalAfterDiscount * config('cart.tax'))/100;
            $totalAfterDiscount = $subtotalAfterDiscount + $taxAfterDiscount;

            Session::put('discounts',[
                'discount' => number_format(floatval($discount),2,'.',''),
                'subtotal' => number_format(floatval($subtotalAfterDiscount),2,'.',''),
                'tax' => number_format(floatval($taxAfterDiscount),2,'.',''),
                'total' => number_format(floatval($totalAfterDiscount),2,'.','')
            ]);
        }
    }

    public function placeOrder(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
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
                'reference_number' => $request->reference_number
            ]);

            // Clear cart and coupon
            Cart::instance('cart')->destroy();
            session()->forget(['coupon', 'discounts']);

            DB::commit();

            return response()->json([
                'success' => true,
                'order_id' => $order->id,
                'message' => 'Order placed successfully!'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Failed to place order. Please try again.'
            ], 500);
        }
    }
}    
