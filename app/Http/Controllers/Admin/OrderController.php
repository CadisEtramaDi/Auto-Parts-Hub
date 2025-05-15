<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Carbon\Carbon;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::with(['user', 'items.product', 'transaction'])
            ->latest()
            ->paginate(10);
            
        $totalOrders = Order::count();
        $completedOrders = Order::where('status', 'completed')->count();
        $pendingOrders = Order::where('status', 'pending')->count();
        $processingOrders = Order::where('status', 'processing')->count();
        $cancelledOrders = Order::where('status', 'cancelled')->count();
        
        return view('admin.orders.index', compact(
            'orders', 
            'totalOrders', 
            'completedOrders', 
            'pendingOrders', 
            'processingOrders', 
            'cancelledOrders'
        ));
    }

    public function show(Order $order)
    {
        $order->load([
            'user',
            'items.product',
            'transaction'
        ]);

        return view('admin.orders.show', compact('order'));
    }

    public function updateStatus(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|in:pending,processing,ready,completed,cancelled'
        ]);

        // Store the old status for comparison
        $oldStatus = $order->status;
        
        // Update the order status
        $order->status = $request->status;
        
        // Add status change timestamp
        if ($request->status === 'completed') {
            $order->completed_at = now();
        } elseif ($request->status === 'cancelled') {
            $order->cancelled_at = now();
        }
        
        $order->save();

        // Create a success message based on the status change
        $statusMessage = match($request->status) {
            'pending' => 'Order has been marked as pending.',
            'processing' => 'Order is now being processed.',
            'ready' => 'Order is ready for pickup.',
            'completed' => 'Order has been marked as completed.',
            'cancelled' => 'Order has been cancelled.',
            default => 'Order status has been updated.'
        };

        return redirect()
            ->back()
            ->with('success', $statusMessage);
    }

    public function updatePaymentStatus(Request $request, Order $order)
    {
        $request->validate([
            'payment_status' => 'required|in:paid,pending'
        ]);

        if ($request->payment_status === 'paid' && !$order->transaction) {
            // Create transaction record
            $transaction = new Transaction();
            $transaction->order_id = $order->id;
            $transaction->payment_method = $order->payment_method;
            $transaction->payment_status = 'completed';
            $transaction->transaction_id = 'TXN-' . Str::random(10);
            $transaction->amount = $order->total;
            $transaction->paid_at = Carbon::now();
            $transaction->save();

            $message = 'Payment has been marked as completed.';
        } elseif ($request->payment_status === 'pending' && $order->transaction) {
            // Delete transaction record
            $order->transaction->delete();
            $message = 'Payment has been marked as pending.';
        } else {
            $message = 'Payment status remains unchanged.';
        }

        return redirect()
            ->back()
            ->with('success', $message);
    }

    public function receipt(Order $order)
    {
        $order->load(['user', 'items.product']);
        return view('orders.receipt', compact('order'));
    }
} 