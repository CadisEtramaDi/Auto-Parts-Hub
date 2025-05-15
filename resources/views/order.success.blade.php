@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-success text-white">
                    <h3 class="mb-0">Order Placed Successfully!</h3>
                </div>
                <div class="card-body">
                    <div class="text-center mb-4">
                        <i class="fas fa-check-circle text-success" style="font-size: 48px;"></i>
                        <h4 class="mt-3">Thank you for your order!</h4>
                        <p>Order #{{ $order->id }}</p>
                        <div class="mt-3">
                            <a href="{{ route('user.orders.receipt', $order->id) }}" class="btn btn-primary" target="_blank">
                                <i class="fas fa-print"></i> Print Receipt
                            </a>
                            <a href="{{ route('shop.index') }}" class="btn btn-secondary ms-2">
                                Continue Shopping
                            </a>
                        </div>
                    </div>

                    <div class="order-details mb-4">
                        <h5>Order Details</h5>
                        <hr>
                        <div class="row">
                            <div class="col-md-6">
                                <h6>Shipping Address</h6>
                                <p class="mb-1">{{ $order->firstname }} {{ $order->lastname }}</p>
                                <p class="mb-1">{{ $order->address_line1 }}</p>
                                @if($order->address_line2)
                                    <p class="mb-1">{{ $order->address_line2 }}</p>
                                @endif
                                <p class="mb-1">{{ $order->city }}, {{ $order->state }} {{ $order->zipcode }}</p>
                                <p class="mb-1">Phone: {{ $order->phone }}</p>
                            </div>
                            <div class="col-md-6">
                                <h6>Order Summary</h6>
                                <p class="mb-1">Subtotal: ${{ number_format($order->subtotal, 2) }}</p>
                                @if($order->discount > 0)
                                    <p class="mb-1">Discount: -${{ number_format($order->discount, 2) }}</p>
                                @endif
                                <p class="mb-1">Tax: ${{ number_format($order->tax, 2) }}</p>
                                <p class="mb-1"><strong>Total: ${{ number_format($order->total, 2) }}</strong></p>
                            </div>
                        </div>
                    </div>

                    <div class="order-items">
                        <h5>Order Items</h5>
                        <hr>
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Product</th>
                                        <th>Price</th>
                                        <th>Quantity</th>
                                        <th>Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($order->items as $item)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <img src="{{ asset('uploads/products/thumbnails') }}/{{ $item->product->image ?? 'default.jpg' }}" 
                                                     alt="{{ $item->product->name }}" 
                                                     class="img-thumbnail mr-3" 
                                                     style="width: 50px; height: 50px; object-fit: cover;">
                                                <span>{{ $item->product->name }}</span>
                                            </div>
                                        </td>
                                        <td>${{ number_format($item->price, 2) }}</td>
                                        <td>{{ $item->quantity }}</td>
                                        <td>${{ number_format($item->price * $item->quantity, 2) }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 