<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::with(['user', 'items'])
            ->latest();

        // Filter by status if provided
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        // Search functionality
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('id', 'like', "%{$search}%")
                  ->orWhereHas('user', function($q) use ($search) {
                      $q->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                  });
            });
        }

        $orders = $query->paginate(10);
        $statusOptions = Order::statusOptions();

        return view('admin.orders.index', compact('orders', 'statusOptions'));
    }

    public function show(Order $order)
    {
        $order->load(['user', 'items.product']);
        $statusOptions = Order::statusOptions();
        
        return view('admin.orders.show', compact('order', 'statusOptions'));
    }

    public function updateStatus(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|in:' . implode(',', array_keys(Order::statusOptions()))
        ]);

        $order->update([
            'status' => $request->status
        ]);

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Order status updated successfully'
            ]);
        }

        return back()->with('status', 'Order status updated successfully');
    }
} 