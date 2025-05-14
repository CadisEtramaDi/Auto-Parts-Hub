@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">Order Confirmation</h4>
                </div>
                <div class="card-body">
                    <h5 class="card-title mb-4">Please review your order details</h5>
                    
                    <!-- Shipping Details -->
                    <div class="shipping-details mb-4">
                        <h6 class="text-muted">Shipping Details</h6>
                        <div class="row">
                            <div class="col-md-6">
                                <p><strong>Name:</strong> {{ session('checkout_data.firstname') }} {{ session('checkout_data.lastname') }}</p>
                                <p><strong>Email:</strong> {{ session('checkout_data.email') }}</p>
                                <p><strong>Phone:</strong> {{ session('checkout_data.mobile') }}</p>
                            </div>
                            <div class="col-md-6">
                                <p><strong>Address:</strong> {{ session('checkout_data.address') }}</p>
                                <p><strong>City:</strong> {{ session('checkout_data.city') }}</p>
                                <p><strong>Country:</strong> {{ session('checkout_data.country') }}</p>
                                <p><strong>Zipcode:</strong> {{ session('checkout_data.zipcode') }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Order Items -->
                    <div class="order-items mb-4">
                        <h6 class="text-muted">Order Items</h6>
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
                                    @foreach($cartItems as $item)
                                    <tr>
                                        <td>{{ $item->name }}</td>
                                        <td>${{ $item->price }}</td>
                                        <td>{{ $item->qty }}</td>
                                        <td>${{ $item->price * $item->qty }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Order Summary -->
                    <div class="order-summary mb-4">
                        <h6 class="text-muted">Order Summary</h6>
                        <div class="row">
                            <div class="col-md-6 offset-md-6">
                                <table class="table">
                                    <tr>
                                        <td><strong>Subtotal:</strong></td>
                                        <td class="text-right">${{ $subtotal }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Tax:</strong></td>
                                        <td class="text-right">${{ $tax }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Total:</strong></td>
                                        <td class="text-right">${{ $total }}</td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="text-right">
                        <a href="{{ route('checkout') }}" class="btn btn-secondary">Back to Checkout</a>
                        <form action="{{ route('place-order') }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-primary">Place Order</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 