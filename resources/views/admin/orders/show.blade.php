@extends('layouts.admin')
@section('content')
<div class="main-content-inner">
    <div class="main-content-wrap">
        <div class="flex items-center flex-wrap justify-between gap20 mb-27">
            <h3>Order Details #{{ $order->id }}</h3>
            <ul class="breadcrumbs flex items-center flex-wrap justify-start gap10">
                <li>
                    <a href="{{ route('admin.index') }}">
                        <div class="text-tiny">Dashboard</div>
                    </a>
                </li>
                <li>
                    <i class="icon-chevron-right"></i>
                </li>
                <li>
                    <a href="{{ route('admin.orders.index') }}">
                        <div class="text-tiny">Orders</div>
                    </a>
                </li>
                <li>
                    <i class="icon-chevron-right"></i>
                </li>
                <li>
                    <div class="text-tiny">Order #{{ $order->id }}</div>
                </li>
            </ul>
        </div>

        <div class="row">
            <div class="col-md-8">
                <!-- Order Items -->
                <div class="wg-box mb-4">
                    <h4 class="mb-4">Order Items</h4>
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

                <!-- Order Notes -->
                @if($order->notes)
                <div class="wg-box mb-4">
                    <h4 class="mb-3">Order Notes</h4>
                    <p class="mb-0">{{ $order->notes }}</p>
                </div>
                @endif
            </div>

            <div class="col-md-4">
                <!-- Customer Information -->
                <div class="wg-box mb-4">
                    <h4 class="mb-3">Customer Information</h4>
                    <div class="customer-info">
                        <p class="mb-2"><strong>Name:</strong> {{ $order->user->name }}</p>
                        <p class="mb-2"><strong>Email:</strong> {{ $order->user->email }}</p>
                        <p class="mb-0"><strong>Phone:</strong> {{ $order->user->mobile ?? 'N/A' }}</p>
                    </div>
                </div>

                <!-- Order Summary -->
                <div class="wg-box mb-4">
                    <h4 class="mb-3">Order Summary</h4>
                    <div class="order-summary">
                        <div class="d-flex justify-content-between mb-2">
                            <span>Subtotal:</span>
                            <span>${{ number_format($order->subtotal, 2) }}</span>
                        </div>
                        @if($order->discount > 0)
                        <div class="d-flex justify-content-between mb-2">
                            <span>Discount:</span>
                            <span>-${{ number_format($order->discount, 2) }}</span>
                        </div>
                        @endif
                        <div class="d-flex justify-content-between mb-2">
                            <span>Tax:</span>
                            <span>${{ number_format($order->tax, 2) }}</span>
                        </div>
                        <hr>
                        <div class="d-flex justify-content-between mb-0">
                            <strong>Total:</strong>
                            <strong>${{ number_format($order->total, 2) }}</strong>
                        </div>
                    </div>
                </div>

                <!-- Order Status -->
                <div class="wg-box">
                    <h4 class="mb-3">Order Status</h4>
                    <form action="{{ route('admin.orders.update-status', $order) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="mb-3">
                            <select name="status" class="form-select" onchange="this.form.submit()">
                                @foreach($statusOptions as $value => $label)
                                    <option value="{{ $value }}" {{ $order->status === $value ? 'selected' : '' }}>
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </form>
                    <p class="mb-0 text-muted">
                        <small>Last updated: {{ $order->updated_at->format('M d, Y H:i') }}</small>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 