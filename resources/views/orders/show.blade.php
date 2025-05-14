@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="mb-4">
        <a href="{{ route('orders.index') }}" class="btn btn-outline-primary">
            <i class="fas fa-arrow-left"></i> Back to Orders
        </a>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">Order #{{ $order->id }}</h4>
                    <span class="badge bg-{{ $order->status === 'completed' ? 'success' : ($order->status === 'pending' ? 'warning' : 'info') }}">
                        {{ ucfirst($order->status) }}
                    </span>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Product</th>
                                    <th>Quantity</th>
                                    <th>Unit Price</th>
                                    <th>Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($order->items as $item)
                                <tr>
                                    <td>{{ $item->product->name }}</td>
                                    <td>{{ $item->quantity }}</td>
                                    <td>${{ number_format($item->unit_price, 2) }}</td>
                                    <td>${{ number_format($item->subtotal, 2) }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="3" class="text-end"><strong>Total:</strong></td>
                                    <td><strong>${{ number_format($order->total_amount, 2) }}</strong></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">Order Information</h4>
                </div>
                <div class="card-body">
                    <dl class="row mb-0">
                        <dt class="col-sm-4">Order Date:</dt>
                        <dd class="col-sm-8">{{ $order->created_at->format('M d, Y H:i') }}</dd>

                        <dt class="col-sm-4">Status:</dt>
                        <dd class="col-sm-8">{{ ucfirst($order->status) }}</dd>

                        @if($order->notes)
                            <dt class="col-sm-4">Notes:</dt>
                            <dd class="col-sm-8">{{ $order->notes }}</dd>
                        @endif
                    </dl>

                    <div class="alert alert-info mt-3 mb-0">
                        <i class="fas fa-info-circle"></i> 
                        @if($order->status === 'pending')
                            Your order is being processed. We will notify you when it's ready for pickup.
                        @elseif($order->status === 'processing')
                            Your order is being prepared. We will notify you when it's ready for pickup.
                        @elseif($order->status === 'completed')
                            Your order has been completed and is ready for pickup.
                        @else
                            This order has been cancelled.
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 