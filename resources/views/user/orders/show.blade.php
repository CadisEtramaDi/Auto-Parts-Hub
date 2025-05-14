@extends('layouts.app')

@section('content')
<main class="pt-90">
    <div class="mb-4 pb-4"></div>
    <section class="my-account container">
        <div class="row">
            <div class="col-lg-3">
                @include('user.account-nav')
            </div>
            <div class="col-lg-9">
                <div class="page-content">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h2 class="page-title">Order #{{ $order->id }}</h2>
                        <a href="{{ route('user.orders.index') }}" class="tf-button style-2">← Back to Orders</a>
                    </div>

                    <!-- Order Status and Date -->
                    <div class="wg-box mb-4">
                        <div class="row">
                            <div class="col-md-6">
                                <p class="mb-2">
                                    <strong>Order Date:</strong> 
                                    {{ $order->created_at->format('M d, Y h:i A') }}
                                </p>
                                <p class="mb-0">
                                    <strong>Status:</strong> 
                                    <span class="badge {{ 
                                        $order->status === 'completed' ? 'bg-success' : 
                                        ($order->status === 'processing' ? 'bg-info' : 
                                        ($order->status === 'cancelled' ? 'bg-danger' : 'bg-warning')) 
                                    }}">
                                        {{ ucfirst($order->status) }}
                                    </span>
                                </p>
                            </div>
                            @if($order->notes)
                            <div class="col-md-6">
                                <p class="mb-0">
                                    <strong>Order Notes:</strong><br>
                                    {{ $order->notes }}
                                </p>
                            </div>
                            @endif
                        </div>
                    </div>

                    <!-- Order Items -->
                    <div class="wg-box mb-4">
                        <h3 class="mb-3">Order Items</h3>
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered">
                                <thead>
                                    <tr>
                                        <th>Product</th>
                                        <th class="text-center">Price</th>
                                        <th class="text-center">Quantity</th>
                                        <th class="text-center">Subtotal</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($order->items as $item)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                @if($item->product && $item->product->image)
                                                    <img src="{{ asset('storage/' . $item->product->image) }}" 
                                                         alt="{{ $item->product_name }}" 
                                                         class="img-thumbnail" 
                                                         style="width: 60px; height: 60px; object-fit: cover;">
                                                @endif
                                                <div class="ms-3">
                                                    <div class="font-medium">{{ $item->product_name }}</div>
                                                    @if($item->color || $item->size)
                                                        <div class="text-muted small">
                                                            @if($item->color) Color: {{ $item->color }}@endif
                                                            @if($item->size) Size: {{ $item->size }}@endif
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-center">${{ number_format($item->price, 2) }}</td>
                                        <td class="text-center">{{ $item->quantity }}</td>
                                        <td class="text-center">${{ number_format($item->subtotal, 2) }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Order Summary -->
                    <div class="wg-box">
                        <h3 class="mb-3">Order Summary</h3>
                        <div class="row justify-content-end">
                            <div class="col-md-5">
                                <div class="table-responsive">
                                    <table class="table table-borderless">
                                        <tr>
                                            <td>Subtotal:</td>
                                            <td class="text-end">${{ number_format($order->subtotal, 2) }}</td>
                                        </tr>
                                        @if($order->discount > 0)
                                        <tr>
                                            <td>Discount:</td>
                                            <td class="text-end text-danger">-${{ number_format($order->discount, 2) }}</td>
                                        </tr>
                                        @endif
                                        <tr>
                                            <td>Tax:</td>
                                            <td class="text-end">${{ number_format($order->tax, 2) }}</td>
                                        </tr>
                                        <tr class="fw-bold">
                                            <td>Total:</td>
                                            <td class="text-end">${{ number_format($order->total, 2) }}</td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>
@endsection 