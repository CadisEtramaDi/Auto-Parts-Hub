@extends('layouts.app')

@section('content')
<div class="container py-4">
    <h2 class="mb-4">Purchase Orders</h2>
    <a href="{{ route('purchase_orders.create') }}" class="btn btn-primary mb-3">Create Purchase Order</a>
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Supplier</th>
                <th>Date</th>
                <th>Status</th>
                <th>Total</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($orders as $order)
            <tr>
                <td>{{ $order->id }}</td>
                <td>{{ $order->supplier->name }}</td>
                <td>{{ $order->order_date }}</td>
                <td><span class="badge bg-{{ $order->status == 'received' ? 'success' : ($order->status == 'canceled' ? 'danger' : 'warning') }}">{{ ucfirst($order->status) }}</span></td>
                <td>${{ number_format($order->total, 2) }}</td>
                <td>
                    <a href="{{ route('purchase_orders.show', $order->id) }}" class="btn btn-info btn-sm">View</a>
                    @if($order->status != 'received')
                        <form action="{{ route('purchase_orders.receive', $order->id) }}" method="POST" style="display:inline-block;">
                            @csrf
                            <button type="submit" class="btn btn-success btn-sm">Mark as Received</button>
                        </form>
                    @endif
                    <form action="{{ route('purchase_orders.destroy', $order->id) }}" method="POST" style="display:inline-block;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Delete this purchase order?')">Delete</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection 