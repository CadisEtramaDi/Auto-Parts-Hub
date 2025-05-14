<?php

namespace App\Http\Controllers;

use App\Models\Inventory;
use App\Models\InventoryTransaction;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class InventoryController extends Controller
{
    // List all inventory items
    public function index()
    {
        $inventory = Inventory::with('product')->paginate(10);
        return view('admin.inventory.index', compact('inventory'));
    }

    // Show single inventory item with transactions
    public function show($id)
    {
        $inventory = Inventory::findOrFail($id);
        $transactions = InventoryTransaction::where('inventory_id', $id)->paginate(10);
        
        return view('admin.inventory.show', compact('inventory', 'transactions'));
    }

    // Show form to create new inventory item
    public function create()
    {
        $products = Product::doesntHave('inventory')->get();
        return view('admin.inventory.create', compact('products'));
    }

    // Store new inventory item
    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id|unique:inventories,product_id',
            'quantity' => 'required|integer|min:0',
            'sku' => 'nullable|string|max:255|unique:inventories,sku',
            'location' => 'nullable|string|max:255',
        ]);
        
        $inventory = Inventory::create($request->all());
        
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
        $products = Product::has('inventory')->get();
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
}