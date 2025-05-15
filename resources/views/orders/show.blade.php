@extends('layouts.admin')

@section('content')
<div class="main-content-inner">
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">Order Details #{{ $order->id }}</h4>
                    <div>
                        <a href="{{ route('orders.receipt', $order->id) }}" class="btn btn-light me-2" target="_blank">
                            <i class="icon-printer"></i> Print Receipt
                        </a>
                        <a href="{{ route('admin.orders.index') }}" class="btn btn-light">
                            <i class="icon-arrow-left"></i> Back to Orders
                        </a>
                    </div>
                </div>

                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <!-- Order Status Section -->
                    <div class="row mb-4">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <h5 class="card-title mb-3">Order Status</h5>
                                            <div class="d-flex align-items-center">
                                                <span class="badge bg-{{ $order->status == 'completed' ? 'success' : ($order->status == 'cancelled' ? 'danger' : ($order->status == 'processing' ? 'info' : ($order->status == 'ready' ? 'primary' : 'warning'))) }} fs-6">
                                                    {{ ucfirst($order->status) }}
                                                </span>
                                                @if($order->completed_at)
                                                    <span class="ms-2 text-muted">
                                                        <small>Completed on {{ $order->completed_at->format('M d, Y h:i A') }}</small>
                                                    </span>
                                                @elseif($order->cancelled_at)
                                                    <span class="ms-2 text-muted">
                                                        <small>Cancelled on {{ $order->cancelled_at->format('M d, Y h:i A') }}</small>
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="btn-group">
                                            <button type="button" class="btn btn-primary dropdown-toggle" data-bs-toggle="dropdown">
                                                Update Status
                                            </button>
                                            <ul class="dropdown-menu">
                                                <li>
                                                    <form action="{{ route('admin.orders.update-status', $order->id) }}" method="POST">
                                                        @csrf
                                                        @method('PUT')
                                                        <input type="hidden" name="status" value="pending">
                                                        <button type="submit" class="dropdown-item {{ $order->status === 'pending' ? 'active' : '' }}">
                                                            <i class="icon-clock me-2"></i> Pending
                                                        </button>
                                                    </form>
                                                </li>
                                                <li>
                                                    <form action="{{ route('admin.orders.update-status', $order->id) }}" method="POST">
                                                        @csrf
                                                        @method('PUT')
                                                        <input type="hidden" name="status" value="processing">
                                                        <button type="submit" class="dropdown-item {{ $order->status === 'processing' ? 'active' : '' }}">
                                                            <i class="icon-refresh me-2"></i> Processing
                                                        </button>
                                                    </form>
                                                </li>
                                                <li>
                                                    <form action="{{ route('admin.orders.update-status', $order->id) }}" method="POST">
                                                        @csrf
                                                        @method('PUT')
                                                        <input type="hidden" name="status" value="ready">
                                                        <button type="submit" class="dropdown-item {{ $order->status === 'ready' ? 'active' : '' }}">
                                                            <i class="icon-check me-2"></i> Ready for Pickup
                                                        </button>
                                                    </form>
                                                </li>
                                                <li>
                                                    <form action="{{ route('admin.orders.update-status', $order->id) }}" method="POST">
                                                        @csrf
                                                        @method('PUT')
                                                        <input type="hidden" name="status" value="completed">
                                                        <button type="submit" class="dropdown-item {{ $order->status === 'completed' ? 'active' : '' }}">
                                                            <i class="icon-check-circle me-2"></i> Completed
                                                        </button>
                                                    </form>
                                                </li>
                                                <li><hr class="dropdown-divider"></li>
                                                <li>
                                                    <form action="{{ route('admin.orders.update-status', $order->id) }}" method="POST">
                                                        @csrf
                                                        @method('PUT')
                                                        <input type="hidden" name="status" value="cancelled">
                                                        <button type="submit" 
                                                                class="dropdown-item text-danger {{ $order->status === 'cancelled' ? 'active' : '' }}"
                                                                onclick="return confirm('Are you sure you want to cancel this order?')">
                                                            <i class="icon-x-circle me-2"></i> Cancel Order
                                                        </button>
                                                    </form>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>

                                    <!-- Status Timeline -->
                                    <div class="mt-4">
                                        <div class="status-timeline">
                                            <div class="d-flex justify-content-between position-relative">
                                                <div class="status-point {{ in_array($order->status, ['pending', 'processing', 'ready', 'completed']) ? 'active' : '' }}">
                                                    <div class="status-label">Pending</div>
                                                </div>
                                                <div class="status-point {{ in_array($order->status, ['processing', 'ready', 'completed']) ? 'active' : '' }}">
                                                    <div class="status-label">Processing</div>
                                                </div>
                                                <div class="status-point {{ in_array($order->status, ['ready', 'completed']) ? 'active' : '' }}">
                                                    <div class="status-label">Ready</div>
                                                </div>
                                                <div class="status-point {{ $order->status === 'completed' ? 'active' : '' }}">
                                                    <div class="status-label">Completed</div>
                                                </div>
                                                @if($order->status === 'cancelled')
                                                    <div class="status-point cancelled">
                                                        <div class="status-label text-danger">Cancelled</div>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <!-- Customer Information -->
                        <div class="col-md-6 mb-4">
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title">Customer Information</h5>
                                    <table class="table table-borderless">
                                        <tr>
                                            <td><strong>Name:</strong></td>
                                            <td>{{ $order->user->name }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Email:</strong></td>
                                            <td>{{ $order->user->email }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Phone:</strong></td>
                                            <td>{{ $order->user->mobile }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Pickup Time:</strong></td>
                                            <td>{{ ucfirst($order->pickup_time) }}</td>
                                        </tr>
                                        @if($order->special_instructions)
                                        <tr>
                                            <td><strong>Special Instructions:</strong></td>
                                            <td>{{ $order->special_instructions }}</td>
                                        </tr>
                                        @endif
                                    </table>
                                </div>
                            </div>
                        </div>

                        <!-- Payment Information -->
                        <div class="col-md-6 mb-4">
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title">Payment Information</h5>
                                    <table class="table table-borderless">
                                        <tr>
                                            <td><strong>Payment Method:</strong></td>
                                            <td>{{ ucfirst($order->payment_method) }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Subtotal:</strong></td>
                                            <td>${{ number_format($order->subtotal, 2) }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Tax:</strong></td>
                                            <td>${{ number_format($order->tax, 2) }}</td>
                                        </tr>
                                        @if($order->discount > 0)
                                        <tr>
                                            <td><strong>Discount:</strong></td>
                                            <td>${{ number_format($order->discount, 2) }}</td>
                                        </tr>
                                        @endif
                                        <tr>
                                            <td><strong>Total:</strong></td>
                                            <td><strong>${{ number_format($order->total, 2) }}</strong></td>
                                        </tr>
                                        @if($order->transaction)
                                        <tr>
                                            <td><strong>Transaction ID:</strong></td>
                                            <td>{{ $order->transaction->transaction_id }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Payment Status:</strong></td>
                                            <td>
                                                <span class="badge bg-success">
                                                    {{ ucfirst($order->transaction->payment_status) }}
                                                </span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><strong>Paid At:</strong></td>
                                            <td>{{ $order->transaction->paid_at->format('M d, Y h:i A') }}</td>
                                        </tr>
                                        @endif
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Order Items -->
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Order Items</h5>
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>Product</th>
                                            <th>SKU</th>
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
                                                    @if($item->product->image)
                                                        <img src="{{ asset('storage/' . $item->product->image) }}" 
                                                             alt="{{ $item->product->name }}"
                                                             class="me-2"
                                                             style="width: 50px; height: 50px; object-fit: cover;">
                                                    @endif
                                                    <div>
                                                        <h6 class="mb-0">{{ $item->product->name }}</h6>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>{{ $item->product->SKU }}</td>
                                            <td>${{ number_format($item->price, 2) }}</td>
                                            <td>{{ $item->quantity }}</td>
                                            <td class="text-end">${{ number_format($item->price * $item->quantity, 2) }}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <td colspan="4" class="text-end"><strong>Subtotal:</strong></td>
                                            <td class="text-end">${{ number_format($order->subtotal, 2) }}</td>
                                        </tr>
                                        <tr>
                                            <td colspan="4" class="text-end"><strong>Tax:</strong></td>
                                            <td class="text-end">${{ number_format($order->tax, 2) }}</td>
                                        </tr>
                                        @if($order->discount > 0)
                                        <tr>
                                            <td colspan="4" class="text-end"><strong>Discount:</strong></td>
                                            <td class="text-end">-${{ number_format($order->discount, 2) }}</td>
                                        </tr>
                                        @endif
                                        <tr>
                                            <td colspan="4" class="text-end"><strong>Total:</strong></td>
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
    </div>
</div>

<style>
.status-timeline {
    padding: 20px 0;
}

.status-point {
    width: 30px;
    height: 30px;
    border-radius: 50%;
    background-color: #e9ecef;
    border: 2px solid #dee2e6;
    position: relative;
    z-index: 1;
}

.status-point.active {
    background-color: #28a745;
    border-color: #28a745;
}

.status-point.cancelled {
    background-color: #dc3545;
    border-color: #dc3545;
}

.status-label {
    position: absolute;
    top: 35px;
    left: 50%;
    transform: translateX(-50%);
    white-space: nowrap;
    font-size: 0.875rem;
}

.status-point::before {
    content: '';
    position: absolute;
    height: 2px;
    background-color: #dee2e6;
    width: 100%;
    left: 50%;
    top: 50%;
    transform: translateY(-50%);
    z-index: -1;
}

.status-point:last-child::before {
    display: none;
}

.dropdown-item.active {
    background-color: #e9ecef;
    color: #212529;
}
</style>
@endsection 