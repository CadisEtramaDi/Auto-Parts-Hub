@extends('layouts.app')

@section('content')
<main class="pt-90">
    <div class="mb-4 pb-4"></div>
    <section class="my-account container">
        <h2 class="page-title">My Orders</h2>
        <div class="row">
            <div class="col-lg-3">
                @include('user.account-nav')
            </div>
            <div class="col-lg-9">
                <div class="page-content">
                    @if($orders->isEmpty())
                        <div class="alert alert-info">
                            <p class="text-center">You haven't placed any orders yet.</p>
                            <div class="text-center mt-4">
                                <a href="{{ route('shop.index') }}" class="tf-button style-1">Start Shopping</a>
                            </div>
                        </div>
                    @else
                        <div class="wg-table table-all-user">
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Order No</th>
                                            <th>Date</th>
                                            <th class="text-center">Items</th>
                                            <th class="text-center">Total</th>
                                            <th class="text-center">Status</th>
                                            <th class="text-center">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($orders as $order)
                                        <tr>
                                            <td>#{{ $order->id }}</td>
                                            <td>{{ $order->created_at->format('M d, Y') }}</td>
                                            <td class="text-center">{{ $order->items->count() }}</td>
                                            <td class="text-center">${{ number_format($order->total, 2) }}</td>
                                            <td class="text-center">
                                                <span class="badge {{ 
                                                    $order->status === 'completed' ? 'bg-success' : 
                                                    ($order->status === 'processing' ? 'bg-info' : 
                                                    ($order->status === 'cancelled' ? 'bg-danger' : 'bg-warning')) 
                                                }}">
                                                    {{ ucfirst($order->status) }}
                                                </span>
                                            </td>
                                            <td class="text-center">
                                                <a href="{{ route('user.orders.show', $order) }}" class="tf-button style-2 h30">
                                                    View Details
                                                </a>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            
                            <div class="divider"></div>
                            <div class="flex items-center justify-between flex-wrap gap10 wgp-pagination">
                                {{ $orders->links('pagination::bootstrap-5') }}
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </section>
</main>
@endsection 