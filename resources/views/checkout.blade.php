@extends('layouts.app')

@section('content')
<div class="container py-5">
    @if(session()->has('error'))
        <div class="alert alert-danger">
            {{ session()->get('error') }}
        </div>
    @endif

    <form action="{{ route('place.order') }}" method="POST">
        @csrf
        <div class="row">
            <div class="col-md-8">
                <div class="card mb-4">
                    <div class="card-header">
                        <h3 class="mb-0">Pickup Information</h3>
                    </div>
                    <div class="card-body">
                        @if($pickupInfo)
                            <div class="mb-3">
                                <h5>Pickup Details</h5>
                                <p class="mb-1"><strong>Name:</strong> {{ Auth::user()->name }}</p>
                                <p class="mb-1"><strong>Phone:</strong> {{ $pickupInfo->phone }}</p>
                                <div class="mb-3">
                                    <label for="pickup_time" class="form-label"><strong>Pickup Time:</strong></label>
                                    <select name="pickup_time" id="pickup_time" class="form-select" required>
                                        <option value="morning" {{ old('pickup_time', $pickupInfo->pickup_time) == 'morning' ? 'selected' : '' }}>Morning (9:00 AM - 12:00 PM)</option>
                                        <option value="afternoon" {{ old('pickup_time', $pickupInfo->pickup_time) == 'afternoon' ? 'selected' : '' }}>Afternoon (1:00 PM - 5:00 PM)</option>
                                        <option value="evening" {{ old('pickup_time', $pickupInfo->pickup_time) == 'evening' ? 'selected' : '' }}>Evening (6:00 PM - 8:00 PM)</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="special_instructions" class="form-label"><strong>Special Instructions:</strong></label>
                                    <textarea name="special_instructions" id="special_instructions" class="form-control" rows="3" placeholder="Any special instructions for pickup?">{{ old('special_instructions', $pickupInfo->special_instructions) }}</textarea>
                                </div>
                                <div class="mb-3">
                                    <label for="payment_method" class="form-label"><strong>Payment Method:</strong></label>
                                    <select name="payment_method" id="payment_method" class="form-select" required>
                                        <option value="cash" {{ old('payment_method') == 'cash' ? 'selected' : '' }}>Cash on Pickup</option>
                                        <option value="gcash" {{ old('payment_method') == 'gcash' ? 'selected' : '' }}>GCash</option>
                                        <option value="paymaya" {{ old('payment_method') == 'paymaya' ? 'selected' : '' }}>PayMaya</option>
                                    </select>
                                </div>
                            </div>
                        @else
                            <div class="alert alert-warning">
                                Please add your contact information for pickup.
                                <a href="{{ route('user.profile') }}" class="btn btn-primary btn-sm ms-2">Add Contact Info</a>
                            </div>
                        @endif
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">
                        <h3 class="mb-0">Order Items</h3>
                    </div>
                    <div class="card-body">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Product</th>
                                    <th>Price</th>
                                    <th>Quantity</th>
                                    <th>Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($items as $item)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <img src="{{ asset('storage/products/'.$item->model->image) }}" 
                                                 alt="{{ $item->name }}" 
                                                 class="img-thumbnail mr-3" 
                                                 style="width: 50px; height: 50px; object-fit: cover;">
                                            <div class="ms-3">
                                                <h6 class="mb-0">{{ $item->name }}</h6>
                                            </div>
                                        </div>
                                    </td>
                                    <td>${{ number_format($item->price, 2) }}</td>
                                    <td>{{ $item->qty }}</td>
                                    <td>${{ number_format($item->subtotal, 2) }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        <h3 class="mb-0">Order Summary</h3>
                    </div>
                    <div class="card-body">
                        <div class="d-flex justify-content-between mb-2">
                            <span>Subtotal</span>
                            <span>${{ $subtotal }}</span>
                        </div>
                        
                        @if($discount > 0)
                        <div class="d-flex justify-content-between mb-2">
                            <span>Discount</span>
                            <span>-${{ $discount }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Subtotal after discount</span>
                            <span>${{ $subtotalAfterDiscount }}</span>
                        </div>
                        @endif

                        <div class="d-flex justify-content-between mb-2">
                            <span>Tax</span>
                            <span>${{ $discount > 0 ? $taxAfterDiscount : $tax }}</span>
                        </div>
                        <hr>
                        <div class="d-flex justify-content-between mb-3">
                            <strong>Total</strong>
                            <strong>${{ $discount > 0 ? $totalAfterDiscount : $total }}</strong>
                        </div>

                        <button type="submit" class="btn btn-primary w-100">Place Order</button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection 