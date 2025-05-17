<?php

namespace App\Http\Controllers;

use App\Models\Inventory;
use App\Models\InventoryTransaction;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class InventoryController extends Controller
{
    // List all products and their stock
    public function index()
    {
        $products = Product::all();
        return view('admin.inventory.index', compact('products'));
    }

    // Show inventory transactions for a product
    public function show(Product $product)
    {
        $transactions = $product->inventoryTransactions()->latest()->get();
        return view('admin.inventory.show', compact('product', 'transactions'));
    }

    // Show form for stock in/out or adjustment
    public function create(Product $product)
    {
        return view('admin.inventory.adjust', compact('product'));
    }

    // Process stock in/out or adjustment
    public function store(Request $request, Product $product)
    {
        $request->validate([
            'type' => 'required|in:in,out',
            'quantity' => 'required|integer|min:1',
            'reason' => 'nullable|string|max:255',
        ]);

        $quantity = $request->input('quantity');
        $type = $request->input('type');
        $reason = $request->input('reason');

        // Find inventory for this product
        $inventory = Inventory::where('product_id', $product->id)->first();
        if (!$inventory) {
            return redirect()->back()->with('error', 'No inventory record for this product.');
        }

        if ($type === 'out' && $inventory->quantity < $quantity) {
            return redirect()->back()->with('error', 'Not enough stock available.');
        }

        // Update inventory and product quantity
        if ($type === 'in') {
            $inventory->quantity += $quantity;
        } else {
            $inventory->quantity -= $quantity;
        }
        $inventory->save();
        $product->quantity = $inventory->quantity;
        $product->save();

        // Log the transaction
        InventoryTransaction::create([
            'product_id' => $product->id,
            'type' => $type,
            'quantity' => $quantity,
            'reason' => $reason,
            'user_id' => Auth::id(),
        ]);

        return redirect()->route('admin.inventory.show', $product->id)
            ->with('success', 'Inventory updated successfully.');
    }

    // Show form to create new inventory item
    public function createInventory()
    {
        $products = Product::doesntHave('inventory')->get();
        return view('admin.inventory.create', compact('products'));
    }

    // Store new inventory item
    public function storeInventory(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id|unique:inventories,product_id',
            'quantity' => 'required|integer|min:0',
            'sku' => 'nullable|string|max:255|unique:inventories,sku',
            'location' => 'nullable|string|max:255',
        ]);
        
        $inventory = Inventory::create($request->all());
        // Sync product quantity
        $product = $inventory->product;
        $product->quantity = $inventory->quantity;
        $product->save();
        
        // Create stock in transaction
        if ($request->quantity > 0) {
            InventoryTransaction::create([
                'inventory_id' => $inventory->id,
                'product_id' => $request->product_id,
                'type' => 'stock_in',
                'quantity' => $request->quantity,
                'note' => 'Initial inventory',
                'user_id' => Auth::id(),
                'reference' => 'INV-'.time(),
            ]);
        }
        
        return redirect()->route('admin.inventory.index')
            ->with('success', 'Inventory added successfully');
    }

    // Show form to edit inventory item
    public function edit($id)
    {
        $inventory = Inventory::findOrFail($id);
        return view('admin.inventory.edit', compact('inventory'));
    }

    // Update inventory item
    public function update(Request $request, $id)
    {
        $inventory = Inventory::findOrFail($id);
        
        $request->validate([
            'sku' => 'nullable|string|max:255|unique:inventories,sku,'.$id,
            'location' => 'nullable|string|max:255',
        ]);
        
        $inventory->update($request->only(['sku', 'location']));
        
        return redirect()->route('admin.inventory.index')
            ->with('success', 'Inventory updated successfully');
    }

    // Delete inventory item
    public function destroy($id)
    {
        $inventory = Inventory::findOrFail($id);
        
        // Delete associated transactions first
        InventoryTransaction::where('inventory_id', $id)->delete();
        
        $inventory->delete();
        
        return redirect()->route('admin.inventory.index')
            ->with('success', 'Inventory deleted successfully');
    }

    // Show stock in form
    public function stockInForm()
    {
        $products = Product::all();
        return view('admin.inventory.stock-in', compact('products'));
    }

    // Process stock in
    public function stockIn(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
            'note' => 'nullable|string',
        ]);
        
        $inventory = Inventory::where('product_id', $request->product_id)->first();
        
        if (!$inventory) {
            return back()->withErrors(['product_id' => 'Product not found in inventory']);
        }
        
        // Update inventory
        $inventory->quantity += $request->quantity;
        $inventory->save();
        // Sync product quantity
        $product = $inventory->product;
        $product->quantity = $inventory->quantity;
        $product->save();
        
        // Create transaction record
        InventoryTransaction::create([
            'inventory_id' => $inventory->id,
            'product_id' => $request->product_id,
            'type' => 'stock_in',
            'quantity' => $request->quantity,
            'note' => $request->note,
            'user_id' => Auth::id(),
            'reference' => 'IN-'.time(),
        ]);
        
        return redirect()->route('admin.inventory.index')
            ->with('success', 'Stock added successfully');
    }

    // Show stock out form
    public function stockOutForm()
    {
        $products = Product::has('inventory')->get();
        return view('admin.inventory.stock-out', compact('products'));
    }

    // Process stock out
    public function stockOut(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
            'note' => 'nullable|string',
        ]);
        
        $inventory = Inventory::where('product_id', $request->product_id)->first();
        
        if (!$inventory) {
            return back()->withErrors(['product_id' => 'Product not found in inventory']);
        }
        
        if ($inventory->quantity < $request->quantity) {
            return back()->withErrors(['quantity' => 'Not enough stock available']);
        }
        
        // Update inventory
        $inventory->quantity -= $request->quantity;
        $inventory->save();
        // Sync product quantity
        $product = $inventory->product;
        $product->quantity = $inventory->quantity;
        $product->save();
        
        // Create transaction record
        InventoryTransaction::create([
            'inventory_id' => $inventory->id,
            'product_id' => $request->product_id,
            'type' => 'stock_out',
            'quantity' => $request->quantity,
            'note' => $request->note,
            'user_id' => Auth::id(),
            'reference' => 'OUT-'.time(),
        ]);
        
        return redirect()->route('admin.inventory.index')
            ->with('success', 'Stock removed successfully');
    }

    public function lowStock()
    {
        $threshold = 10; // You can make this configurable
        $products = Product::where('quantity', '<=', $threshold)->get();
        return view('admin.inventory.low_stock', compact('products', 'threshold'));
    }

    public function report(Request $request)
    {
        $products = Product::with('inventoryTransactions')->get();
        $from = $request->input('from');
        $to = $request->input('to');

        foreach ($products as $product) {
            $query = $product->inventoryTransactions();
            if ($from && $to) {
                $query->whereBetween('created_at', [$from, $to]);
            }
            $product->filteredTransactions = $query->get();
        }

        return view('admin.inventory.report', compact('products', 'from', 'to'));
    }
}