@extends('layouts.app')

@section('content')
<div class="container py-5">
    <h2 class="mb-4">CHECKOUT</h2>

    <!-- Checkout Steps -->
    <div class="checkout-steps">
        <div class="step completed">
            <div class="step-number">01</div>
            <div class="step-title">SHOPPING BAG</div>
            <div class="step-subtitle">Manage Your Items List</div>
            <div class="step-line"></div>
        </div>
        <div class="step completed">
            <div class="step-number">02</div>
            <div class="step-title">CHECKOUT</div>
            <div class="step-subtitle">Checkout Your Items List</div>
            <div class="step-line"></div>
        </div>
        <div class="step completed">
            <div class="step-number">03</div>
            <div class="step-title">CONFIRMATION</div>
            <div class="step-subtitle">Review And Submit Your Order</div>
            <div class="step-line"></div>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-body text-center py-5">
                    <div class="mb-4">
                        <i class="fas fa-check-circle text-success" style="font-size: 48px;"></i>
                    </div>
                    <h3 class="mb-3">Thank You For Your Order!</h3>
                    <p class="mb-4">Your order has been placed successfully. Your order number is #{{ $order->id }}</p>
                    
                    <div class="order-details text-start mb-4">
                        <h5 class="mb-3">Order Details</h5>
                        <div class="row">
                            <div class="col-md-6">
                                <p><strong>Name:</strong> {{ $order->name }}</p>
                                <p><strong>Phone:</strong> {{ $order->phone }}</p>
                                <p><strong>Preferred Pickup:</strong> {{ $order->pickup_date->format('M d, Y') }} at {{ date('g:i A', strtotime($order->pickup_time)) }}</p>
                                @if($order->special_instructions)
                                <p><strong>Special Instructions:</strong> {{ $order->special_instructions }}</p>
                                @endif
                                <p><strong>Placed By (Staff):</strong> {{ $order->user->name }}</p>
                            </div>
                            <div class="col-md-6">
                                <p><strong>Payment Method:</strong> {{ ucfirst($order->payment_method) }}</p>
                                <p><strong>Payment Status:</strong> <span class="badge bg-{{ $order->payment_status == 'paid' ? 'success' : 'warning' }}">{{ ucfirst($order->payment_status) }}</span></p>
                                <p><strong>Order Status:</strong> <span class="badge bg-warning">{{ ucfirst($order->status) }}</span></p>
                                <p><strong>Order Date:</strong> {{ $order->created_at->format('M d, Y H:i A') }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="order-summary mb-4">
                        <h5 class="mb-3">Order Summary</h5>
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Product</th>
                                        <th>Quantity</th>
                                        <th>Price</th>
                                        <th>Subtotal</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($order->items as $item)
                                    <tr>
                                        <td>{{ $item->name }}</td>
                                        <td>{{ $item->quantity }}</td>
                                        <td>${{ number_format($item->unit_price, 2) }}</td>
                                        <td>${{ number_format($item->subtotal, 2) }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td colspan="3" class="text-end"><strong>Subtotal:</strong></td>
                                        <td>${{ number_format($order->subtotal, 2) }}</td>
                                    </tr>
                                    <tr>
                                        <td colspan="3" class="text-end"><strong>Tax:</strong></td>
                                        <td>${{ number_format($order->tax, 2) }}</td>
                                    </tr>
                                    <tr>
                                        <td colspan="3" class="text-end"><strong>Total:</strong></td>
                                        <td><strong>${{ number_format($order->total, 2) }}</strong></td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>

                    <div class="payment-instructions mb-4">
                        <h5 class="mb-3">Payment Instructions</h5>
                        @if($order->payment_method == 'cash')
                            <p>Please prepare the exact amount of ${{ number_format($order->total, 2) }} when picking up your order.</p>
                        @elseif($order->payment_method == 'gcash')
                            <p>Please send your payment to our GCash number: 09XX-XXX-XXXX</p>
                            <p>Use your Order #{{ $order->id }} as reference number.</p>
                        @else
                            <p>Please complete your PayMaya payment using the reference number: ORDER-{{ $order->id }}</p>
                        @endif
                    </div>

                    <div class="mt-4">
                        <a href="{{ route('shop.index') }}" class="btn btn-primary">Continue Shopping</a>
                        <a href="{{ route('orders.index') }}" class="btn btn-outline-primary ms-2">View My Orders</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
.checkout-steps {
    display: flex;
    justify-content: space-between;
    padding: 20px 0 40px;
    position: relative;
}

.step {
    flex: 1;
    text-align: left;
    padding: 0 20px;
    position: relative;
}

.step-number {
    font-size: 24px;
    font-weight: bold;
    margin-bottom: 10px;
    color: #999;
}

.step.active .step-number,
.step.completed .step-number {
    color: #000;
}

.step-title {
    font-weight: bold;
    margin-bottom: 5px;
    color: #999;
}

.step.active .step-title,
.step.completed .step-title {
    color: #000;
}

.step-subtitle {
    font-size: 14px;
    color: #999;
}

.step-line {
    position: absolute;
    bottom: -20px;
    left: 20px;
    right: 20px;
    height: 2px;
    background: #e9ecef;
}

.step.active .step-line,
.step.completed .step-line {
    background: #000;
}
</style>
@endpush
@endsection 