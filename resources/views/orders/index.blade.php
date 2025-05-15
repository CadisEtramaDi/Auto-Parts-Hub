@extends('layouts.admin')

@section('content')
<div class="container py-4">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">Orders Management</h4>
                </div>
                <div class="card-body">
                    <!-- Order Statistics -->
                    <div class="row mb-4">
                        <div class="col-md-3">
                            <div class="card bg-info text-white">
                                <div class="card-body">
                                    <h5 class="card-title">Total Orders</h5>
                                    <h3>{{ $totalOrders }}</h3>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-success text-white">
                                <div class="card-body">
                                    <h5 class="card-title">Completed Orders</h5>
                                    <h3>{{ $completedOrders }}</h3>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="card bg-warning text-white">
                                <div class="card-body">
                                    <h5 class="card-title">Pending</h5>
                                    <h3>{{ $pendingOrders }}</h3>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="card bg-info text-white">
                                <div class="card-body">
                                    <h5 class="card-title">Processing</h5>
                                    <h3>{{ $processingOrders }}</h3>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="card bg-danger text-white">
                                <div class="card-body">
                                    <h5 class="card-title">Cancelled</h5>
                                    <h3>{{ $cancelledOrders }}</h3>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Orders Table -->
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Order ID</th>
                                    <th>Customer</th>
                                    <th>Items</th>
                                    <th>Total</th>
                                    <th>Payment Method</th>
                                    <th>Payment Status</th>
                                    <th>Order Status</th>
                                    <th>Date</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($orders as $order)
                                <tr>
                                    <td>#{{ $order->id }}</td>
                                    <td>{{ $order->user->name }}</td>
                                    <td>{{ $order->items->count() }}</td>
                                    <td>${{ number_format($order->total, 2) }}</td>
                                    <td>
                                        <span class="badge bg-info">
                                            {{ ucfirst($order->payment_method) }}
                                        </span>
                                    </td>
                                    <td>
                                        @if($order->transaction)
                                            <span class="badge bg-success">
                                                {{ ucfirst($order->transaction->payment_status) }}
                                            </span>
                                        @else
                                            <span class="badge bg-warning">
                                                Pending
                                            </span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge bg-{{ $order->status == 'completed' ? 'success' : ($order->status == 'cancelled' ? 'danger' : ($order->status == 'processing' ? 'info' : 'warning')) }}">
                                            {{ ucfirst($order->status) }}
                                        </span>
                                    </td>
                                    <td>{{ $order->created_at->format('M d, Y h:i A') }}</td>
                                    <td>
                                        <div class="btn-group">
                                            <a href="{{ route('admin.orders.show', $order->id) }}" 
                                               class="btn btn-sm btn-info">
                                                <i class="fas fa-eye"></i> View
                                            </a>
                                            <button type="button" 
                                                    class="btn btn-sm btn-primary dropdown-toggle" 
                                                    data-bs-toggle="dropdown">
                                                Status
                                            </button>
                                            <ul class="dropdown-menu">
                                                <li>
                                                    <form action="{{ route('admin.orders.update-status', $order->id) }}" method="POST">
                                                        @csrf
                                                        @method('PUT')
                                                        <input type="hidden" name="status" value="pending">
                                                        <button type="submit" class="dropdown-item">Pending</button>
                                                    </form>
                                                </li>
                                                <li>
                                                    <form action="{{ route('admin.orders.update-status', $order->id) }}" method="POST">
                                                        @csrf
                                                        @method('PUT')
                                                        <input type="hidden" name="status" value="processing">
                                                        <button type="submit" class="dropdown-item">Processing</button>
                                                    </form>
                                                </li>
                                                <li>
                                                    <form action="{{ route('admin.orders.update-status', $order->id) }}" method="POST">
                                                        @csrf
                                                        @method('PUT')
                                                        <input type="hidden" name="status" value="ready">
                                                        <button type="submit" class="dropdown-item">Ready</button>
                                                    </form>
                                                </li>
                                                <li>
                                                    <form action="{{ route('admin.orders.update-status', $order->id) }}" method="POST">
                                                        @csrf
                                                        @method('PUT')
                                                        <input type="hidden" name="status" value="completed">
                                                        <button type="submit" class="dropdown-item">Completed</button>
                                                    </form>
                                                </li>
                                                <li>
                                                    <form action="{{ route('admin.orders.update-status', $order->id) }}" method="POST">
                                                        @csrf
                                                        @method('PUT')
                                                        <input type="hidden" name="status" value="cancelled">
                                                        <button type="submit" class="dropdown-item text-danger">Cancelled</button>
                                                    </form>
                                                </li>
                                            </ul>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="9" class="text-center">No orders found.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="d-flex justify-content-center mt-4">
                        {{ $orders->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Add any JavaScript for order management here
</script>
@endpush 