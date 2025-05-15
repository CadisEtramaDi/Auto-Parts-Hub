@extends('layouts.app')

@section('content')
<div class="container py-4">
    <h2 class="mb-4">Create Purchase Order</h2>
    <form action="{{ route('purchase_orders.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label for="supplier_id" class="form-label">Supplier</label>
            <select name="supplier_id" id="supplier_id" class="form-control" required>
                <option value="">Select Supplier</option>
                @foreach($suppliers as $supplier)
                    <option value="{{ $supplier->id }}">{{ $supplier->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="mb-3">
            <label for="order_date" class="form-label">Order Date</label>
            <input type="date" name="order_date" id="order_date" class="form-control" value="{{ date('Y-m-d') }}" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Order Items</label>
            <table class="table table-bordered" id="items-table">
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>Quantity</th>
                        <th>Unit Cost</th>
                        <th>Subtotal</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>
                            <select name="items[0][product_id]" class="form-control" required>
                                <option value="">Select Product</option>
                                @foreach($products as $product)
                                    <option value="{{ $product->id }}">{{ $product->name }}</option>
                                @endforeach
                            </select>
                        </td>
                        <td><input type="number" name="items[0][quantity]" class="form-control qty" min="1" value="1" required></td>
                        <td><input type="number" name="items[0][unit_cost]" class="form-control unit-cost" min="0" step="0.01" value="0.00" required></td>
                        <td><input type="text" class="form-control subtotal" value="0.00" readonly></td>
                        <td><button type="button" class="btn btn-danger btn-sm remove-row">Remove</button></td>
                    </tr>
                </tbody>
            </table>
            <button type="button" class="btn btn-secondary btn-sm" id="add-row">Add Item</button>
        </div>
        <div class="mb-3">
            <label for="notes" class="form-label">Notes</label>
            <textarea name="notes" id="notes" class="form-control"></textarea>
        </div>
        <button type="submit" class="btn btn-primary">Create Purchase Order</button>
        <a href="{{ route('purchase_orders.index') }}" class="btn btn-secondary">Cancel</a>
    </form>
</div>
<script>
    let rowIdx = 1;
    document.getElementById('add-row').onclick = function() {
        const table = document.getElementById('items-table').getElementsByTagName('tbody')[0];
        const newRow = table.rows[0].cloneNode(true);
        Array.from(newRow.querySelectorAll('input, select')).forEach(function(input) {
            if (input.name.includes('product_id')) input.name = `items[${rowIdx}][product_id]`;
            if (input.name.includes('quantity')) { input.name = `items[${rowIdx}][quantity]`; input.value = 1; }
            if (input.name.includes('unit_cost')) { input.name = `items[${rowIdx}][unit_cost]`; input.value = 0.00; }
            if (input.classList.contains('subtotal')) input.value = 0.00;
        });
        table.appendChild(newRow);
        rowIdx++;
    };
    document.getElementById('items-table').addEventListener('click', function(e) {
        if (e.target.classList.contains('remove-row')) {
            if (this.rows.length > 1) e.target.closest('tr').remove();
        }
    });
    document.getElementById('items-table').addEventListener('input', function(e) {
        if (e.target.classList.contains('qty') || e.target.classList.contains('unit-cost')) {
            const row = e.target.closest('tr');
            const qty = parseFloat(row.querySelector('.qty').value) || 0;
            const unitCost = parseFloat(row.querySelector('.unit-cost').value) || 0;
            row.querySelector('.subtotal').value = (qty * unitCost).toFixed(2);
        }
    });
</script>
@endsection 