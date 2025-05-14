@extends('layouts.admin')
@section('content')
<div class="main-content-inner">
    <div class="main-content-wrap">
        <div class="flex items-center flex-wrap justify-between gap20 mb-27">
            <h3>Orders</h3>
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
                    <div class="text-tiny">Orders</div>
                </li>
            </ul>
        </div>

        <div class="wg-box">
            <div class="flex items-center justify-between gap10 flex-wrap">
                <div class="wg-filter flex-grow">
                    <form class="form-search" action="{{ route('admin.orders.index') }}" method="GET">
                        <fieldset class="name">
                            <input type="text" placeholder="Search by order number or customer name..." 
                                   class="" name="search" value="{{ request('search') }}">
                        </fieldset>
                        <div class="button-submit">
                            <button class="" type="submit"><i class="icon-search"></i></button>
                        </div>
                    </form>
                </div>
                <div class="flex gap10">
                    <select class="form-select" name="status" onchange="window.location.href=this.value">
                        <option value="{{ route('admin.orders.index') }}" {{ !request('status') ? 'selected' : '' }}>All Orders</option>
                        <option value="{{ route('admin.orders.index', ['status' => 'pending']) }}" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="{{ route('admin.orders.index', ['status' => 'processing']) }}" {{ request('status') === 'processing' ? 'selected' : '' }}>Processing</option>
                        <option value="{{ route('admin.orders.index', ['status' => 'completed']) }}" {{ request('status') === 'completed' ? 'selected' : '' }}>Completed</option>
                        <option value="{{ route('admin.orders.index', ['status' => 'cancelled']) }}" {{ request('status') === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                    </select>
                </div>
            </div>

            <div class="wg-table table-all-user">
                <div class="table-responsive">
                    @if(Session::has('status'))
                        <p class="alert alert-success">{{ Session::get('status') }}</p>
                    @endif
                    <table class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th style="width: 80px">Order No</th>
                                <th>Customer</th>
                                <th class="text-center">Items</th>
                                <th class="text-center">Subtotal</th>
                                <th class="text-center">Tax</th>
                                <th class="text-center">Total</th>
                                <th class="text-center">Status</th>
                                <th class="text-center">Order Date</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($orders as $order)
                            <tr>
                                <td>#{{ $order->id }}</td>
                                <td>
                                    <div class="name">
                                        <div class="body-title-2">{{ $order->user->name }}</div>
                                        <div class="text-tiny mt-1">{{ $order->user->email }}</div>
                                    </div>
                                </td>
                                <td class="text-center">{{ $order->items->count() }}</td>
                                <td class="text-center">${{ number_format($order->subtotal, 2) }}</td>
                                <td class="text-center">${{ number_format($order->tax, 2) }}</td>
                                <td class="text-center">${{ number_format($order->total, 2) }}</td>
                                <td class="text-center">
                                    <select class="form-select form-select-sm status-select" 
                                            data-order-id="{{ $order->id }}"
                                            onchange="updateOrderStatus(this, {{ $order->id }})">
                                        @foreach($statusOptions as $value => $label)
                                            <option value="{{ $value }}" {{ $order->status === $value ? 'selected' : '' }}>
                                                {{ $label }}
                                            </option>
                                        @endforeach
                                    </select>
                                </td>
                                <td class="text-center">{{ $order->created_at->format('M d, Y H:i') }}</td>
                                <td class="text-center">
                                    <div class="list-icon-function">
                                        <a href="{{ route('admin.orders.show', $order) }}">
                                            <div class="item eye">
                                                <i class="icon-eye"></i>
                                            </div>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="9" class="text-center">No orders found</td>
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
@endsection

@push('scripts')
<script>
function updateOrderStatus(select, orderId) {
    const status = select.value;
    fetch(`/admin/orders/${orderId}/status`, {
        method: 'PUT',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({ status })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Show success message
            alert('Order status updated successfully');
        } else {
            // Show error message and revert selection
            alert('Failed to update order status');
            select.value = select.getAttribute('data-original-value');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred while updating the order status');
        select.value = select.getAttribute('data-original-value');
    });
}
</script>
@endpush 