@extends('layouts.app')

@section('content')
<div class="container py-4">
    <h2 class="mb-4">Inventory Management</h2>
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif
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
                <td>{{ $product->quantity }}</td>
                <td>
                    <a href="{{ route('admin.inventory.show', $product->id) }}" class="btn btn-info btn-sm">View History</a>
                    <a href="{{ route('admin.inventory.adjust', $product->id) }}" class="btn btn-primary btn-sm">Adjust Stock</a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection