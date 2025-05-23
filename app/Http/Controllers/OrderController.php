<?php

namespace App\Http\Controllers;

use App\Models\Coupon;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Surfsidemedia\Shoppingcart\Facades\Cart;

class CartController extends Controller
{
    public function index()
    {
        $items = Cart::instance('cart')->content();
        return view('cart',compact('items'));
    }

    public function add_to_cart(Request $request)
    {
        Cart::instance('cart')->add($request->id,$request->name,$request->quantity,$request->price)->associate('App\Models\Product');
        return redirect()->back();
    }

    public function increase_cart_quantity($rowId)
    {
        $product = Cart::instance('cart')->get($rowId);
        $qty = $product->qty + 1;
        Cart::instance('cart')->update($rowId,$qty);
        return redirect()->back();
    }

    public function decrease_cart_quantity($rowId)
    {
        $product = Cart::instance('cart')->get($rowId);
        $qty = $product->qty - 1;
        Cart::instance('cart')->update($rowId,$qty);
        return redirect()->back();
    }

    public function remove_item($rowId)
    {
        Cart::instance('cart')->remove($rowId);
        return redirect()->back();
    }

    public function empty_cart()
    {
        Cart::instance('cart')->destroy();
        return redirect()->back();
    }

    public function apply_coupon_code(Request $request)
    {
        $coupon_code = $request->coupon_code;
        if (!isset($coupon_code)) {
            return redirect()->back()->with('error', 'Please enter a coupon code!');
        }

        $coupon = Coupon::where('code', $coupon_code)->first();
        
        if (!$coupon) {
            return redirect()->back()->with('error', 'Coupon code does not exist!');
        }

        if ($coupon->expiry_date < Carbon::today()) {
            return redirect()->back()->with('error', 'This coupon has expired!');
        }

        // Convert cart subtotal to float for comparison
        $cartSubtotal = floatval(str_replace(',', '', Cart::instance('cart')->subtotal()));
        
        if ($cartSubtotal < $coupon->cart_value) {
            return redirect()->back()->with('error', 'Cart value must be at least $' . number_format($coupon->cart_value, 2) . ' to use this coupon!');
        }

        Session::put('coupon', [
            'code' => $coupon->code,
            'type' => $coupon->type,
            'value' => $coupon->value,
            'cart_value' => $coupon->cart_value
        ]);
        
        $this->calculateDiscount();
        return redirect()->back()->with('success', 'Coupon has been applied successfully!');
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

        $subtotalAfterDiscount =Cart::instance('cart')->subtotal() - $discount;
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

    public function remove_coupon_code(Request $request)
    {
        if(Session::has('coupon')) {
            $oldCoupon = Session::get('coupon')['code'];
            Session::forget('coupon');
            Session::forget('discounts');
            
            // Recalculate cart totals without discount
            $subtotal = floatval(str_replace(',', '', Cart::instance('cart')->subtotal()));
            $tax = floatval(str_replace(',', '', Cart::instance('cart')->tax()));
            $total = floatval(str_replace(',', '', Cart::instance('cart')->total()));
            
            return redirect()->route('cart.index')->with('success', "Coupon '{$oldCoupon}' has been removed successfully!");
        }
        return redirect()->route('cart.index')->with('error', 'No coupon to remove!');
    }

    public function checkout()
    {
        $items = Cart::instance('cart')->content();
        $subtotal = Cart::instance('cart')->subtotal();
        $tax = Cart::instance('cart')->tax();
        $total = Cart::instance('cart')->total();
        
        $discount = 0;
        $subtotalAfterDiscount = $subtotal;
        $taxAfterDiscount = $tax;
        $totalAfterDiscount = $total;

        if(Session::has('discounts')) {
            $discount = Session::get('discounts')['discount'];
            $subtotalAfterDiscount = Session::get('discounts')['subtotal'];
            $taxAfterDiscount = Session::get('discounts')['tax'];
            $totalAfterDiscount = Session::get('discounts')['total'];
        }

        return view('checkout', compact('items', 'subtotal', 'tax', 'total', 'discount', 'subtotalAfterDiscount', 'taxAfterDiscount', 'totalAfterDiscount'));
    }
}    
