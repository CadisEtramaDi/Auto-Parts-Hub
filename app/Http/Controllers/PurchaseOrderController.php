<?php

namespace App\Http\Controllers;

use App\Models\PurchaseOrder;
use App\Models\PurchaseOrderItem;
use App\Models\Supplier;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PurchaseOrderController extends Controller
{
    // List all purchase orders
    public function index()
    {
        $orders = PurchaseOrder::with('supplier', 'creator')->latest()->get();
        return view('purchase_orders.index', compact('orders'));
    }

    // Show form to create a new purchase order
    public function create()
    {
        $suppliers = Supplier::all();
        $products = Product::all();
        return view('purchase_orders.create', compact('suppliers', 'products'));
    }

    // Store a new purchase order
    public function store(Request $request)
    {
        $request->validate([
            'supplier_id' => 'required|exists:suppliers,id',
            'order_date' => 'required|date',
            'items' => 'required|array',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.unit_cost' => 'required|numeric|min:0',
            'notes' => 'nullable|string',
        ]);

        $total = 0;
        foreach ($request->items as $item) {
            $total += $item['quantity'] * $item['unit_cost'];
        }

        $order = PurchaseOrder::create([
            'supplier_id' => $request->supplier_id,
            'order_date' => $request->order_date,
            'status' => 'pending',
            'total' => $total,
            'notes' => $request->notes,
            'created_by' => Auth::id(),
        ]);

        foreach ($request->items as $item) {
            PurchaseOrderItem::create([
                'purchase_order_id' => $order->id,
                'product_id' => $item['product_id'],
                'quantity' => $item['quantity'],
                'unit_cost' => $item['unit_cost'],
                'subtotal' => $item['quantity'] * $item['unit_cost'],
            ]);
        }

        return redirect()->route('purchase_orders.index')->with('success', 'Purchase order created successfully.');
    }

    // Show a purchase order
    public function show(PurchaseOrder $purchase_order)
    {
        $purchase_order->load('supplier', 'items.product', 'creator');
        return view('purchase_orders.show', compact('purchase_order'));
    }

    // Mark a purchase order as received
    public function receive(PurchaseOrder $purchase_order)
    {
        $purchase_order->status = 'received';
        $purchase_order->save();
        // Update product stock for each item
        foreach ($purchase_order->items as $item) {
            $product = $item->product;
            $product->quantity += $item->quantity;
            $product->save();
        }
        return redirect()->route('purchase_orders.show', $purchase_order->id)->with('success', 'Purchase order marked as received and stock updated.');
    }

    // Delete a purchase order
    public function destroy(PurchaseOrder $purchase_order)
    {
        $purchase_order->delete();
        return redirect()->route('purchase_orders.index')->with('success', 'Purchase order deleted.');
    }
} 