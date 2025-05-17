@extends('layouts.admin')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                </div>
                <div class="card-body">
                    <div class="main-content-inner">
                        <div class="main-content-wrap">
                            <h3 class="mb-4">Orders Management</h3>
                            <div class="row mb-4">
                                <div class="col-md-2">
                                    <div class="card shadow-sm border-0 rounded-3 text-center py-3" style="background:#fff;">
                                        <div class="fw-bold text-secondary">Total Orders</div>
                                        <div class="fs-3 fw-bold">{{ $totalOrders }}</div>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="card shadow-sm border-0 rounded-3 text-center py-3" style="background:#fff;">
                                        <div class="fw-bold text-secondary">Completed Orders</div>
                                        <div class="fs-3 fw-bold">{{ $completedOrders }}</div>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="card shadow-sm border-0 rounded-3 text-center py-3" style="background:#fff;">
                                        <div class="fw-bold text-secondary">Pending</div>
                                        <div class="fs-3 fw-bold">{{ $pendingOrders }}</div>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="card shadow-sm border-0 rounded-3 text-center py-3" style="background:#fff;">
                                        <div class="fw-bold text-secondary">Processing</div>
                                        <div class="fs-3 fw-bold">{{ $processingOrders }}</div>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="card shadow-sm border-0 rounded-3 text-center py-3" style="background:#fff;">
                                        <div class="fw-bold text-secondary">Cancelled</div>
                                        <div class="fs-3 fw-bold">{{ $cancelledOrders }}</div>
                                    </div>
                                </div>
                            </div>

                            <!-- Orders Table -->
                            <div class="wg-box">
                                <div class="flex items-center justify-between gap10 flex-wrap">
                                    <div class="wg-filter flex-grow">
                                        <form class="form-search">
                                            <fieldset class="name">
                                                <input type="text" placeholder="Search here..." class="" name="search" tabindex="2" value="" aria-required="true">
                                            </fieldset>
                                            <div class="button-submit">
                                                <button class="" type="submit"><i class="icon-search"></i></button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                                <div class="table-responsive">
                                    <table class="table table-striped table-bordered">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Customer</th>
                                                <th>Items</th>
                                                <th>Total</th>
                                                <th>Payment Method</th>
                                                <th>Payment Status</th>
                                                <th>Order Status</th>
                                                <th>Date</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($orders as $order)
                                            <tr>
                                                <td>#{{ $order->id }}</td>
                                                <td>{{ $order->name }}</td>
                                                <td>{{ $order->items->count() }}</td>
                                                <td>${{ number_format($order->total, 2) }}</td>
                                                <td>{{ ucfirst($order->payment_method) }}</td>
                                                <td>{{ $order->transaction ? 'Paid' : 'Pending' }}</td>
                                                <td>{{ ucfirst($order->status) }}</td>
                                                <td>{{ $order->created_at->format('M d, Y') }}</td>
                                                <td>
                                                    <a href="{{ route('admin.orders.show', $order->id) }}" target="_blank" class="btn btn-outline-info btn-lg px-4 py-2">
                                                        <i class="icon-eye" style="font-size: 1.5rem;"></i> <span class="fw-bold">View</span>
                                                    </a>
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
                                <div class="divider"></div>
                                <div class="flex items-center justify-between flex-wrap gap10 wgp-pagination">
                                    {{ $orders->links('pagination::bootstrap-5') }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 