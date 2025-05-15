<?php

namespace App\Http\Controllers;

use App\Models\Address;
use App\Models\Order;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    public function index()
    {
        return view('user.index');
    }

    public function profile()
    {
        $user = Auth::user();
        $contact = Address::where('user_id', Auth::id())
                         ->where('isdefault', 1)
                         ->first();
        
        return view('user.profile', compact('user', 'contact'));
    }

    public function updateProfile(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . Auth::id(),
        ]);

        DB::table('users')
            ->where('id', Auth::id())
            ->update([
                'name' => $request->name,
                'email' => $request->email
            ]);

        return redirect()->back()->with('success', 'Profile updated successfully');
    }

    public function storeContact(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:15',
            'pickup_time' => 'nullable|string|in:morning,afternoon,evening',
            'special_instructions' => 'nullable|string',
        ]);

        // Set all existing addresses to non-default
        Address::where('user_id', Auth::id())
              ->where('isdefault', 1)
              ->update(['isdefault' => 0]);

        // Create new contact info
        Address::create([
            'user_id' => Auth::id(),
            'name' => $request->name,
            'phone' => $request->phone,
            'pickup_time' => $request->pickup_time,
            'special_instructions' => $request->special_instructions,
            'type' => 'pickup',
            'isdefault' => 1
        ]);

        return redirect()->back()->with('success', 'Contact information saved successfully');
    }

    public function orders()
    {
        $orders = Order::where('user_id', Auth::id())
                      ->with(['items.product'])
                      ->latest()
                      ->paginate(10);
        return view('user.orders', compact('orders'));
    }

    public function receipt(Order $order)
    {
        // Check if the order belongs to the authenticated user
        if ($order->user_id !== Auth::id()) {
            return redirect()->route('user.orders')->with('error', 'Unauthorized access');
        }

        $order->load(['user', 'items.product']);
        return view('orders.receipt', compact('order'));
    }
}
