@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="card">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Order Details #{{ $order->id }}</h5>
            <div>
                <a href="{{ route('admin.orders.receipt', $order) }}" class="btn btn-light btn-sm">Print Receipt</a>
                <a href="{{ route('admin.orders.index') }}" class="btn btn-light btn-sm">Back to Orders</a>
            </div>
        </div>

        <div class="card-body">
            <div class="row mb-4">
                <!-- Order Status -->
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <h6 class="card-title">Order Status</h6>
                            <div class="d-flex align-items-center">
                                <span class="badge bg-{{ $order->status == 'pending' ? 'warning' : ($order->status == 'completed' ? 'success' : ($order->status == 'cancelled' ? 'danger' : 'primary')) }} me-2">
                                    {{ ucfirst($order->status) }}
                                </span>
                                <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#updateStatusModal">
                                    Update Status ▼
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Payment Status -->
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <h6 class="card-title">Payment Information</h6>
                            <div class="mb-2">
                                <strong>Method:</strong> {{ ucfirst($order->payment_method) }}
                                @if($order->payment_method == 'cash')
                                    <small class="text-muted">(Pay with cash upon pickup)</small>
                                @elseif($order->payment_method == 'gcash')
                                    <small class="text-muted">(GCash payment)</small>
                                @elseif($order->payment_method == 'paymaya')
                                    <small class="text-muted">(PayMaya payment)</small>
                                @endif
                            </div>
                            <div class="d-flex align-items-center">
                                <span class="badge bg-{{ $order->payment_status == 'paid' ? 'success' : 'warning' }} me-2">
                                    {{ ucfirst($order->payment_status) }}
                                </span>
                                <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#updatePaymentModal">
                                    Update Payment Status ▼
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <!-- Customer Information -->
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <h6 class="card-title">Customer Information</h6>
                            <div class="mb-2">
                                <strong>Customer Name:</strong> {{ $order->name }}
                            </div>
                            <div class="mb-2">
                                <strong>Email:</strong> {{ $order->user->email }}
                            </div>
                            <div class="mb-2">
                                <strong>Phone:</strong> {{ $order->phone }}
                            </div>
                            <div class="mb-2">
                                <strong>Preferred Pickup:</strong> {{ $order->pickup_date->format('M d, Y') }} at {{ date('g:i A', strtotime($order->pickup_time)) }}
                                <small class="text-muted">(Store hours: 9 AM - 5 PM, Lunch: 12 PM - 1 PM)</small>
                            </div>
                            @if($order->special_requests)
                            <div class="mb-2">
                                <strong>Special Instructions:</strong> {{ $order->special_requests }}
                            </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Order Summary -->
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <h6 class="card-title">Order Summary</h6>
                            <div class="mb-2">
                                <strong>Order Date:</strong> {{ $order->created_at->format('M d, Y g:i A') }}
                            </div>
                            <div class="mb-2">
                                <strong>Subtotal:</strong> ${{ number_format($order->subtotal, 2) }}
                            </div>
                            <div class="mb-2">
                                <strong>Tax:</strong> ${{ number_format($order->tax, 2) }}
                            </div>
                            <div class="mb-2">
                                <strong>Total:</strong> ${{ number_format($order->total, 2) }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Order Items -->
            <div class="card mt-4">
                <div class="card-body">
                    <h6 class="card-title">Order Items</h6>
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Product</th>
                                    <th>Price</th>
                                    <th>Quantity</th>
                                    <th class="text-end">Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($order->items as $item)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            @if($item->product && $item->product->image)
                                            <img src="{{ asset('uploads/products/'.$item->product->image) }}" 
                                                 alt="{{ $item->name }}" class="me-2"
                                                 style="width: 50px; height: 50px; object-fit: cover;">
                                            @endif
                                            <div>
                                                <div>{{ $item->name }}</div>
                                                <small class="text-muted">SKU: {{ $item->product->sku ?? 'N/A' }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>${{ number_format($item->price, 2) }}</td>
                                    <td>{{ $item->quantity }}</td>
                                    <td class="text-end">${{ number_format($item->subtotal, 2) }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="3" class="text-end"><strong>Subtotal:</strong></td>
                                    <td class="text-end">${{ number_format($order->subtotal, 2) }}</td>
                                </tr>
                                <tr>
                                    <td colspan="3" class="text-end"><strong>Tax:</strong></td>
                                    <td class="text-end">${{ number_format($order->tax, 2) }}</td>
                                </tr>
                                <tr>
                                    <td colspan="3" class="text-end"><strong>Total:</strong></td>
                                    <td class="text-end"><strong>${{ number_format($order->total, 2) }}</strong></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Update Status Modal -->
<div class="modal fade" id="updateStatusModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('admin.orders.update-status', $order) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-header">
                    <h5 class="modal-title">Update Order Status</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Status</label>
                        <select name="status" class="form-select" required>
                            <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="processing" {{ $order->status == 'processing' ? 'selected' : '' }}>Processing</option>
                            <option value="ready" {{ $order->status == 'ready' ? 'selected' : '' }}>Ready for Pickup</option>
                            <option value="completed" {{ $order->status == 'completed' ? 'selected' : '' }}>Completed</option>
                            <option value="cancelled" {{ $order->status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Update Status</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Update Payment Status Modal -->
<div class="modal fade" id="updatePaymentModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('admin.orders.update-payment-status', $order) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-header">
                    <h5 class="modal-title">Update Payment Status</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Payment Status</label>
                        <select name="payment_status" class="form-select" required>
                            <option value="pending" {{ $order->payment_status == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="paid" {{ $order->payment_status == 'paid' ? 'selected' : '' }}>Paid</option>
                            <option value="failed" {{ $order->payment_status == 'failed' ? 'selected' : '' }}>Failed</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Update Status</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('styles')
<style>
.card-title {
    font-weight: bold;
    margin-bottom: 1rem;
}
.badge {
    padding: 0.5em 1em;
}
</style>
@endpush
@endsection 