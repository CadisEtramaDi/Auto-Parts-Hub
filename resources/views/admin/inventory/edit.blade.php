@extends('layouts.admin')

@section('content')
<div class="container">
    <h1>Edit Inventory Item</h1>
    
    <form action="{{ route('admin.inventory.update', $inventory->id) }}" method="POST">
        @csrf
        @method('PUT')
        
        <div class="mb-3">
            <label class="form-label">Product</label>
            <input type="text" class="form-control" value="{{ $inventory->product->name }}" readonly>
        </div>
        
        <div class="mb-3">
            <label class="form-label">Current Quantity</label>
            <input type="text" class="form-control" value="{{ $inventory->quantity }}" readonly>
        </div>
        
        <div class="mb-3">
            <label for="sku" class="form-label">SKU</label>
            <input type="text" class="form-control" id="sku" name="sku" value="{{ $inventory->sku }}">
        </div>
        
        <div class="mb-3">
            <label for="location" class="form-label">Location</label>
            <input type="text" class="form-control" id="location" name="location" value="{{ $inventory->location }}">
        </div>
        
        <button type="submit" class="btn btn-primary">Update Inventory</button>
        <a href="{{ route('admin.inventory.index') }}" class="btn btn-secondary">Cancel</a>
    </form>
</div>
@endsection