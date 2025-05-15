@extends('layouts.app')

@section('content')
<div class="container py-4">
    <h2 class="mb-4">Inventory History for {{ $product->name }}</h2>
    <div class="mb-3">
        <a href="{{ route('admin.inventory.adjust', $product->id) }}" class="btn btn-primary">Adjust Stock</a>
        <a href="{{ route('admin.inventory.index') }}" class="btn btn-secondary">Back to Inventory</a>
    </div>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Date</th>
                <th>Type</th>
                <th>Quantity</th>
                <th>Reason</th>
                <th>By</th>
            </tr>
        </thead>
        <tbody>
            @foreach($transactions as $tx)
            <tr>
                <td>{{ $tx->created_at->format('M d, Y H:i') }}</td>
                <td>{{ ucfirst($tx->type) }}</td>
                <td>{{ $tx->quantity }}</td>
                <td>{{ $tx->reason }}</td>
                <td>{{ $tx->user ? $tx->user->name : 'System' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection