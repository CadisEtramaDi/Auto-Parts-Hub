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
        <div class="step active">
            <div class="step-number">02</div>
            <div class="step-title">CHECKOUT</div>
            <div class="step-subtitle">Checkout Your Items List</div>
            <div class="step-line"></div>
        </div>
        <div class="step">
            <div class="step-number">03</div>
            <div class="step-title">CONFIRMATION</div>
            <div class="step-subtitle">Review And Submit Your Order</div>
            <div class="step-line"></div>
        </div>
    </div>

    @if(session()->has('error'))
        <div class="alert alert-danger">
            {{ session()->get('error') }}
        </div>
    @endif

    <div class="row">
        <!-- Checkout Form -->
        <div class="col-md-8">
            <div class="card">
                <div class="card-body">
                    <h5 class="mb-4">CONTACT DETAILS</h5>
                    <form action="{{ route('checkout.process') }}" method="POST" id="checkout-form">
                        @csrf
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Full Name *</label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                       name="name" value="{{ old('name', $checkoutData['name'] ?? $user->name) }}" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Phone Number *</label>
                                <input type="tel" class="form-control @error('phone') is-invalid @enderror" 
                                       name="phone" value="{{ old('phone', $checkoutData['phone'] ?? $user->phone) }}" required>
                                @error('phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Pickup Date *</label>
                                <input type="date" class="form-control @error('pickup_date') is-invalid @enderror" 
                                       name="pickup_date" value="{{ old('pickup_date', $checkoutData['pickup_date'] ?? date('Y-m-d')) }}" required>
                                @error('pickup_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Pickup Time *</label>
                                <select class="form-select @error('pickup_time') is-invalid @enderror" 
                                        name="pickup_time" required>
                                    <option value="">Select a time slot</option>
                                    <option value="09:00" {{ old('pickup_time', $checkoutData['pickup_time'] ?? '') == '09:00' ? 'selected' : '' }}>9:00 AM</option>
                                    <option value="10:00" {{ old('pickup_time', $checkoutData['pickup_time'] ?? '') == '10:00' ? 'selected' : '' }}>10:00 AM</option>
                                    <option value="11:00" {{ old('pickup_time', $checkoutData['pickup_time'] ?? '') == '11:00' ? 'selected' : '' }}>11:00 AM</option>
                                    <option value="13:00" {{ old('pickup_time', $checkoutData['pickup_time'] ?? '') == '13:00' ? 'selected' : '' }}>1:00 PM</option>
                                    <option value="14:00" {{ old('pickup_time', $checkoutData['pickup_time'] ?? '') == '14:00' ? 'selected' : '' }}>2:00 PM</option>
                                    <option value="15:00" {{ old('pickup_time', $checkoutData['pickup_time'] ?? '') == '15:00' ? 'selected' : '' }}>3:00 PM</option>
                                    <option value="16:00" {{ old('pickup_time', $checkoutData['pickup_time'] ?? '') == '16:00' ? 'selected' : '' }}>4:00 PM</option>
                                </select>
                                @error('pickup_time')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label">Special Instructions</label>
                            <textarea class="form-control @error('special_instructions') is-invalid @enderror" 
                                      name="special_instructions" rows="3">{{ old('special_instructions', $checkoutData['special_instructions'] ?? '') }}</textarea>
                            @error('special_instructions')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="payment-methods mt-4">
                            <h5 class="mb-3">PAYMENT METHOD</h5>
                            <div class="payment-method">
                                <input type="radio" name="payment_method" id="cash" value="cash" 
                                       {{ old('payment_method', $checkoutData['payment_method'] ?? 'cash') == 'cash' ? 'checked' : '' }}>
                                <label for="cash">
                                    <span class="method-title">Cash</span>
                                    <span class="method-description">
                                        Pay with cash upon pickup of your order.
                                    </span>
                                </label>
                            </div>
                            <div class="payment-method">
                                <input type="radio" name="payment_method" id="gcash" value="gcash"
                                       {{ old('payment_method', $checkoutData['payment_method'] ?? '') == 'gcash' ? 'checked' : '' }}>
                                <label for="gcash">
                                    <span class="method-title">GCash</span>
                                    <span class="method-description">
                                        Pay securely using your GCash wallet. Scan QR code or enter mobile number.
                                    </span>
                                </label>
                            </div>
                            <div class="payment-method">
                                <input type="radio" name="payment_method" id="card" value="card"
                                       {{ old('payment_method', $checkoutData['payment_method'] ?? '') == 'card' ? 'checked' : '' }}>
                                <label for="card">
                                    <span class="method-title">Card</span>
                                    <span class="method-description">
                                        Pay using your credit or debit card. Enter your card number below.
                                    </span>
                                </label>
                            </div>
                            @error('payment_method')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- GCash Fields -->
                        <div id="gcash-fields" style="display: none;">
                            <div class="mb-3">
                                <label for="gcash_name" class="form-label">GCash Account Name</label>
                                <input type="text" class="form-control @error('gcash_name') is-invalid @enderror" name="gcash_name" id="gcash_name" value="{{ old('gcash_name', $checkoutData['gcash_name'] ?? '') }}">
                                @error('gcash_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="gcash_phone" class="form-label">GCash Phone Number</label>
                                <input type="text" class="form-control @error('gcash_phone') is-invalid @enderror" name="gcash_phone" id="gcash_phone" value="{{ old('gcash_phone', $checkoutData['gcash_phone'] ?? '') }}">
                                @error('gcash_phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Card Fields -->
                        <div id="card-fields" style="display: none;">
                            <div class="mb-3">
                                <label for="card_name" class="form-label">Cardholder Name</label>
                                <input type="text" class="form-control @error('card_name') is-invalid @enderror" name="card_name" id="card_name" value="{{ old('card_name', $checkoutData['card_name'] ?? '') }}">
                                @error('card_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="card_number" class="form-label">Card Number</label>
                                <input type="text" class="form-control @error('card_number') is-invalid @enderror" name="card_number" id="card_number" value="{{ old('card_number', $checkoutData['card_number'] ?? '') }}" maxlength="16" pattern="\d{16}">
                                @error('card_number')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <button type="submit" class="btn btn-dark btn-lg w-100 mt-4">
                            PLACE ORDER
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Order Summary -->
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="mb-4">YOUR ORDER</h5>
                    <div class="order-summary">
                        <div class="d-flex justify-content-between mb-3">
                            <span>PRODUCT</span>
                            <span>SUBTOTAL</span>
                        </div>
                        <hr>
                        @foreach($cartItems as $item)
                        <div class="d-flex justify-content-between mb-2">
                            <span>{{ $item->name }} × {{ $item->qty }}</span>
                            <span>${{ number_format($item->subtotal, 2) }}</span>
                        </div>
                        @endforeach
                        <hr>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Subtotal</span>
                            <span>${{ $subtotal }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Tax</span>
                            <span>${{ $tax }}</span>
                        </div>
                        <hr>
                        <div class="d-flex justify-content-between mb-0">
                            <span class="fw-bold">Total</span>
                            <span class="fw-bold">${{ $total }}</span>
                        </div>
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

.payment-method {
    border: 1px solid #dee2e6;
    border-radius: 4px;
    padding: 15px;
    margin-bottom: 10px;
}

.payment-method input[type="radio"] {
    margin-right: 10px;
}

.payment-method label {
    display: inline-block;
    margin-bottom: 0;
    width: calc(100% - 30px);
    cursor: pointer;
}

.method-title {
    display: block;
    font-weight: bold;
    margin-bottom: 5px;
}

.method-description {
    display: block;
    font-size: 14px;
    color: #6c757d;
}

.form-control {
    border-radius: 0;
    border: 1px solid #ced4da;
}

.form-control:focus {
    box-shadow: none;
    border-color: #000;
}

.form-label {
    font-weight: 500;
}
</style>
@endpush

@push('scripts')
<script>
    function togglePaymentFields() {
        var method = document.querySelector('input[name="payment_method"]:checked').value;
        document.getElementById('gcash-fields').style.display = (method === 'gcash') ? 'block' : 'none';
        document.getElementById('card-fields').style.display = (method === 'card') ? 'block' : 'none';
    }
    document.querySelectorAll('input[name="payment_method"]').forEach(function(el) {
        el.addEventListener('change', togglePaymentFields);
    });
    // On page load
    togglePaymentFields();
</script>
@endpush
@endsection 