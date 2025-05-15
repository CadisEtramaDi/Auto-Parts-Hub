@extends('layouts.app')

@section('content')
<div class="container py-5">
    <h2 class="mb-4">CHECKOUT</h2>

    <!-- Checkout Steps -->
    <div class="checkout-steps">
        <div class="step completed">
            <div class="step-number">01</div>
            <div class="step-title">SHOPPING BAG</div>
            <div class="step-subtitle">Manage Your Items List</div>
            <div class="step-line"></div>
        </div>
        <div class="step completed">
            <div class="step-number">02</div>
            <div class="step-title">CHECKOUT</div>
            <div class="step-subtitle">Checkout Your Items List</div>
            <div class="step-line"></div>
        </div>
        <div class="step active">
            <div class="step-number">03</div>
            <div class="step-title">CONFIRMATION</div>
            <div class="step-subtitle">Review And Submit Your Order</div>
            <div class="step-line"></div>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-body">
                    <h4 class="mb-4">Please Review Your Order</h4>

                    <!-- Customer Information -->
                    <div class="mb-4">
                        <h5 class="mb-3">Customer Information</h5>
                        <div class="row">
                            <div class="col-md-6">
                                <p><strong>Name:</strong> {{ $checkoutData['name'] }}</p>
                                <p><strong>Phone:</strong> {{ $checkoutData['phone'] }}</p>
                            </div>
                            <div class="col-md-6">
                                <p><strong>Pickup Date:</strong> {{ date('M d, Y', strtotime($checkoutData['pickup_date'])) }}</p>
                                <p><strong>Pickup Time:</strong> {{ date('g:i A', strtotime($checkoutData['pickup_time'])) }}</p>
                            </div>
                        </div>
                        @if(isset($checkoutData['special_instructions']))
                        <p><strong>Special Instructions:</strong> {{ $checkoutData['special_instructions'] }}</p>
                        @endif
                        <p><strong>Payment Method:</strong> {{ ucfirst($checkoutData['payment_method']) }}</p>
                    </div>

                    <!-- Order Items -->
                    <div class="mb-4">
                        <h5 class="mb-3">Order Items</h5>
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Product</th>
                                        <th>Quantity</th>
                                        <th>Price</th>
                                        <th>Subtotal</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($cartItems as $item)
                                    <tr>
                                        <td>{{ $item->name }}</td>
                                        <td>{{ $item->qty }}</td>
                                        <td>${{ number_format($item->price, 2) }}</td>
                                        <td>${{ number_format($item->price * $item->qty, 2) }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td colspan="3" class="text-end"><strong>Subtotal:</strong></td>
                                        <td>${{ $subtotal }}</td>
                                    </tr>
                                    <tr>
                                        <td colspan="3" class="text-end"><strong>Tax:</strong></td>
                                        <td>${{ $tax }}</td>
                                    </tr>
                                    <tr>
                                        <td colspan="3" class="text-end"><strong>Total:</strong></td>
                                        <td><strong>${{ $total }}</strong></td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>

                    <!-- Confirmation Buttons -->
                    <div class="text-center">
                        <form action="{{ route('place.order') }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-primary">Confirm Order</button>
                        </form>
                        <a href="{{ route('checkout') }}" class="btn btn-outline-secondary ms-2">Edit Order</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
.checkout-steps {
    display: flex;
    justify-content: space-between;
    padding: 20px 0 40px;
    position: relative;
}

.step {
    flex: 1;
    text-align: left;
    padding: 0 20px;
    position: relative;
}

.step-number {
    font-size: 24px;
    font-weight: bold;
    margin-bottom: 10px;
    color: #999;
}

.step.active .step-number,
.step.completed .step-number {
    color: #000;
}

.step-title {
    font-weight: bold;
    margin-bottom: 5px;
    color: #999;
}

.step.active .step-title,
.step.completed .step-title {
    color: #000;
}

.step-subtitle {
    font-size: 14px;
    color: #999;
}

.step-line {
    position: absolute;
    bottom: -20px;
    left: 20px;
    right: 20px;
    height: 2px;
    background: #e9ecef;
}

.step.active .step-line,
.step.completed .step-line {
    background: #000;
}
</style>
@endpush
@endsection 