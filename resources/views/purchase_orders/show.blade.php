@extends('layouts.app')

@section('content')
<div class="container py-4">
    <h2 class="mb-4">Purchase Order #{{ $purchase_order->id }}</h2>
    <div class="mb-3">
        <a href="{{ route('purchase_orders.index') }}" class="btn btn-secondary">Back to List</a>
        @if($purchase_order->status != 'received')
        <form action="{{ route('purchase_orders.receive', $purchase_order->id) }}" method="POST" style="display:inline-block;">
            @csrf
            <button type="submit" class="btn btn-success">Mark as Received</button>
        </form>
        @endif
    </div>
    <div class="card mb-4">
        <div class="card-body">
            <h5>Supplier: {{ $purchase_order->supplier->name }}</h5>
            <p><strong>Date:</strong> {{ $purchase_order->order_date }}</p>
            <p><strong>Status:</strong> <span class="badge bg-{{ $purchase_order->status == 'received' ? 'success' : ($purchase_order->status == 'canceled' ? 'danger' : 'warning') }}">{{ ucfirst($purchase_order->status) }}</span></p>
            <p><strong>Notes:</strong> {{ $purchase_order->notes ?? '-' }}</p>
            <p><strong>Created By:</strong> {{ $purchase_order->creator->name ?? 'N/A' }}</p>
        </div>
    </div>
    <h4>Order Items</h4>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Product</th>
                <th>Quantity</th>
                <th>Unit Cost</th>
                <th>Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @foreach($purchase_order->items as $item)
            <tr>
                <td>{{ $item->product->name }}</td>
                <td>{{ $item->quantity }}</td>
                <td>${{ number_format($item->unit_cost, 2) }}</td>
                <td>${{ number_format($item->subtotal, 2) }}</td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <th colspan="3" class="text-end">Total</th>
                <th>${{ number_format($purchase_order->total, 2) }}</th>
            </tr>
        </tfoot>
    </table>
</div>
@endsection 