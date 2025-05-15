@extends('layouts.app')

@section('content')
<div class="container py-4">
    <h2 class="mb-4">Low Stock Alerts (Threshold: {{ $threshold }})</h2>
    @if($products->isEmpty())
        <div class="alert alert-success">No products are low on stock!</div>
    @else
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Product</th>
                    <th>SKU</th>
                    <th>Current Stock</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($products as $product)
                <tr>
                    <td>{{ $product->name }}</td>
                    <td>{{ $product->SKU }}</td>
                    <td><span class="badge bg-danger">{{ $product->quantity }}</span></td>
                    <td>
                        <a href="{{ route('admin.inventory.show', $product->id) }}" class="btn btn-info btn-sm">View History</a>
                        <a href="{{ route('admin.inventory.adjust', $product->id) }}" class="btn btn-primary btn-sm">Adjust Stock</a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>
@endsection 