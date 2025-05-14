@extends('layouts.admin')

@section('content')
<div class="container">
    <h1>Inventory Details</h1>
    
    <div class="card mb-4">
        <div class="card-body">
            <h5 class="card-title">{{ $inventory->product->name }}</h5>
            <p class="card-text"> 
                <strong>SKU:</strong> {{ $inventory->sku }}<br>
                <strong>Quantity:</strong> {{ $inventory->quantity }}<br>
                <strong>Location:</strong> {{ $inventory->location }}
            </p>
            <a href="{{ route('admin.inventory.edit', $inventory->id) }}" class="btn btn-primary">Edit</a>
        </div>
    </div>
    
    <h3>Transactions</h3>
    <table class="table">
        <thead>
            <tr>
                <th>Date</th>
                <th>Type</th>
                <th>Quantity</th>
                <th>Reference</th>
                <th>Note</th>
                <th>User</th>
            </tr>
        </thead>
        <tbody>
            @foreach($transactions as $transaction)
            <tr>
                <td>{{ $transaction->created_at->format('Y-m-d H:i') }}</td>
                <td>
                    @if($transaction->type == 'stock_in')
                        <span class="badge bg-success">Stock In</span>
                    @else
                        <span class="badge bg-danger">Stock Out</span>
                    @endif
                </td>
                <td>{{ $transaction->quantity }}</td>
                <td>{{ $transaction->reference }}</td>
                <td>{{ $transaction->note }}</td>
                <td>{{ $transaction->user->name }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    
    {{ $transactions->links() }}
    
    <a href="{{ route('admin.inventory.index') }}" class="btn btn-secondary">Back to Inventory</a>
</div>
@endsection