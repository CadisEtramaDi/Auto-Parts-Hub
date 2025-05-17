@extends('layouts.app')

@section('content')
<div class="container py-4">
    <h2 class="mb-4">Adjust Stock for {{ $product->name }}</h2>
    <form action="{{ route('admin.inventory.adjust.store', $product->id) }}" method="POST">
        @csrf
        <div class="mb-3">
            <label for="type" class="form-label">Type</label>
            <select name="type" id="type" class="form-control" required>
                <option value="in">Stock In</option>
                <option value="out">Stock Out</option>
            </select>
        </div>
        <div class="mb-3">
            <label for="quantity" class="form-label">Quantity</label>
            <input type="number" name="quantity" id="quantity" class="form-control" min="1" required>
        </div>
        <div class="mb-3">
            <label for="reason" class="form-label">Reason (optional)</label>
            <input type="text" name="reason" id="reason" class="form-control">
        </div>
        <button type="submit" class="btn btn-success">Submit</button>
        <a href="{{ route('admin.inventory.adjust', $product->id) }}" class="btn btn-secondary ms-2">Cancel</a>
    </form>
</div>
@endsection 