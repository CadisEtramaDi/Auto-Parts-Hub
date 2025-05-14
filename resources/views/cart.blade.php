@extends('layouts.app')
@section('content')

<style>
  .text-success{
    color:#278c04 !important
  }
  .checkout-steps {
    display: flex;
    justify-content: space-between;
    margin-bottom: 2rem;
    position: relative;
  }
  .checkout-steps:after {
    content: '';
    position: absolute;
    top: 50%;
    left: 0;
    right: 0;
    height: 2px;
    background: #e4e4e4;
    z-index: 1;
  }
  .checkout-steps__item {
    position: relative;
    background: white;
    z-index: 2;
    padding: 0 1rem;
    cursor: pointer;
  }
  .checkout-steps__item.active .checkout-steps__item-number {
    background: #222;
    color: white;
  }
  .checkout-steps__item:not(.active) {
    opacity: 0.7;
    transition: opacity 0.3s ease;
  }
  .checkout-steps__item:not(.active):hover {
    opacity: 1;
  }
  .checkout-steps__item-number {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background: #e4e4e4;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 0.5rem;
    transition: all 0.3s ease;
  }
  .checkout-steps__item.completed .checkout-steps__item-number {
    background: #278c04;
    color: white;
  }
  .section-wrapper {
    display: none;
  }
  .section-wrapper.active {
    display: block !important;
  }
  .shopping-cart {
    display: flex;
    gap: 2rem;
  }
  .cart-section, .checkout-section, .confirmation-section {
    display: none;
  }
  .cart-section.active, .checkout-section.active, .confirmation-section.active {
    display: block;
  }
  .shopping-cart__totals-wrapper {
    width: 380px;
    flex-shrink: 0;
  }
  .shopping-cart__totals {
    background: white;
    padding: 2rem;
    border: 1px solid #e4e4e4;
    border-radius: 4px;
    position: sticky;
    top: 20px;
  }
  .shopping-cart__totals h3 {
    margin: 0 0 1.5rem;
    font-size: 1.25rem;
    font-weight: 500;
  }
  .shopping-cart__product-item {
    display: flex;
    align-items: center;
    gap: 1rem;
  }
  .shopping-cart__product-item img {
    width: 120px;
    height: 120px;
    object-fit: cover;
  }
  .shopping-cart__product-item__detail {
    padding-right: 1rem;
  }
  .shopping-cart__product-item__detail h4 {
    margin: 0;
    font-size: 1rem;
    font-weight: 500;
  }
  .shopping-cart__product-item__options {
    list-style: none;
    padding: 0;
    margin: 0.5rem 0 0;
    font-size: 0.875rem;
    color: #666;
  }
  .cart-table {
    width: 100%;
    margin-bottom: 2rem;
  }
  .cart-table th {
    padding: 1rem;
    font-weight: 500;
    text-align: left;
    border-bottom: 1px solid #e4e4e4;
  }
  .cart-table td {
    padding: 1rem;
    vertical-align: middle;
    border-bottom: 1px solid #e4e4e4;
  }
  .cart-table td:last-child {
    text-align: right;
  }
  .qty-control {
    display: flex;
    align-items: center;
    border: 1px solid #e4e4e4;
    border-radius: 4px;
    width: 120px;
  }
  .qty-control__number {
    width: 60px;
    text-align: center;
    border: none;
    padding: 0.5rem;
  }
  .qty-control__reduce,
  .qty-control__increase {
    width: 30px;
    height: 30px;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    user-select: none;
    font-size: 1.2rem;
  }
  .cart-totals {
    width: 100%;
    margin-bottom: 1.5rem;
  }
  .cart-totals th {
    font-weight: 500;
    text-align: left;
    padding: 0.75rem 0;
  }
  .cart-totals td {
    text-align: right;
    padding: 0.75rem 0;
  }
  .cart-table__wrapper {
    flex: 1;
  }
  .confirmation-details {
    background: #f8f9fa;
    padding: 20px;
    border-radius: 5px;
    margin-bottom: 20px;
  }
  .confirmation-details h4 {
    margin-bottom: 15px;
    color: #333;
  }
  .shipping-info {
    margin-bottom: 20px;
  }
  .shipping-info p {
    margin-bottom: 5px;
  }
  .form-section {
    margin-bottom: 30px;
  }
  .form-section label {
    font-weight: 500;
  }
  .form-control {
    border-radius: 4px;
    border: 1px solid #ddd;
    padding: 8px 12px;
  }
</style>

<main class="pt-90">
    <div class="mb-4 pb-4"></div>
    <section class="shop-checkout container">
      <h2 class="page-title">Cart</h2>
      
      <div class="checkout-steps">
        <div class="checkout-steps__item active" data-step="cart">
          <div class="checkout-steps__item-number">01</div>
          <div class="checkout-steps__item-title">
            <span>Shopping Bag</span>
            <em>Manage Your Items List</em>
          </div>
        </div>
        <div class="checkout-steps__item" data-step="checkout">
          <div class="checkout-steps__item-number">02</div>
          <div class="checkout-steps__item-title">
            <span>Checkout</span>
            <em>Review Your Order</em>
          </div>
        </div>
        <div class="checkout-steps__item" data-step="confirmation">
          <div class="checkout-steps__item-number">03</div>
          <div class="checkout-steps__item-title">
            <span>Confirmation</span>
            <em>Order Complete</em>
          </div>
        </div>
      </div>

      <!-- Cart Section -->
      <div class="section-wrapper cart-section active">
        <div class="shopping-cart">
          <div class="cart-table__wrapper">
            @if(Session::has('success_message'))
              <div class="alert alert-success">
                <strong>Success</strong> {{Session::get('success_message')}}
              </div>
            @endif
            @if(Cart::instance('cart')->count() > 0)
              <table class="cart-table">
                <thead>
                  <tr>
                    <th>Product</th>
                    <th>Price</th>
                    <th>Quantity</th>
                    <th>Total</th>
                    <th></th>
                  </tr>
                </thead>
                <tbody>
                  @foreach(Cart::instance('cart')->content() as $item)
                    <tr>
                      <td>
                        <div class="shopping-cart__product-item">
                          <img src="{{ asset('uploads/products/thumbnails') }}/{{$item->model->image}}" alt="{{$item->model->name}}">
                          <div class="shopping-cart__product-item__detail">
                            <h4>{{$item->model->name}}</h4>
                          </div>
                        </div>
                      </td>
                      <td>${{$item->model->regular_price}}</td>
                      <td>
                        <div class="qty-control">
                          <span class="qty-control__reduce" wire:click.prevent="decreaseQuantity('{{$item->rowId}}')">-</span>
                          <input type="text" class="qty-control__number" value="{{$item->qty}}" readonly>
                          <span class="qty-control__increase" wire:click.prevent="increaseQuantity('{{$item->rowId}}')">+</span>
                        </div>
                      </td>
                      <td>${{$item->subtotal}}</td>
                      <td>
                        <a href="#" wire:click.prevent="destroy('{{$item->rowId}}')" class="remove-item">×</a>
                      </td>
                    </tr>
                  @endforeach
                </tbody>
              </table>
            @else
              <p>No item in cart</p>
            @endif
          </div>

          <div class="shopping-cart__totals-wrapper">
            <div class="shopping-cart__totals">
              <h3>Cart Totals</h3>
              <table class="cart-totals">
                <tr>
                  <th>Subtotal</th>
                  <td>${{Cart::instance('cart')->subtotal()}}</td>
                </tr>
                <tr>
                  <th>Tax</th>
                  <td>${{Cart::instance('cart')->tax()}}</td>
                </tr>
                <tr>
                  <th>Total</th>
                  <td>${{Cart::instance('cart')->total()}}</td>
                </tr>
              </table>
              @if(Cart::instance('cart')->count() > 0)
                <button class="btn btn-primary w-100" onclick="showCheckoutSection()">Proceed to Checkout</button>
              @endif
            </div>
          </div>
        </div>
      </div>

      <!-- Checkout Section -->
      <div class="section-wrapper checkout-section">
        <form id="checkout-form" action="{{ route('confirm-order') }}" method="POST">
          @csrf
          <div class="row">
            <div class="col-md-8">
              <div class="form-section">
                <div class="alert alert-info">
                  <strong>Note:</strong> This is a pickup-only service. Once your order is ready, you will be notified to pick up your items at our store.
                </div>
                <h4>Contact Information</h4>
                <div class="mb-3">
                  <label>Name</label>
                  <input type="text" class="form-control" name="name" value="{{ Auth::user()->name }}" readonly>
                </div>
                <div class="mb-3">
                  <label>Email</label>
                  <input type="email" class="form-control" name="email" value="{{ Auth::user()->email }}" readonly>
                </div>
                <div class="mb-3">
                  <label>Phone</label>
                  <input type="text" class="form-control" name="mobile" value="{{ Auth::user()->mobile }}" readonly>
                </div>

                <h4 class="mt-4">Payment Method</h4>
                <div class="mb-3">
                  <div class="form-check mb-2">
                    <input class="form-check-input" type="radio" name="mode" id="cash" value="cash" required checked>
                    <label class="form-check-label" for="cash">
                      Cash on Pickup
                    </label>
                  </div>
                  <div class="form-check mb-2">
                    <input class="form-check-input" type="radio" name="mode" id="gcash" value="gcash" required>
                    <label class="form-check-label" for="gcash">
                      GCash
                    </label>
                  </div>
                  <div class="form-check">
                    <input class="form-check-input" type="radio" name="mode" id="bank_transfer" value="bank_transfer" required>
                    <label class="form-check-label" for="bank_transfer">
                      Bank Transfer
                    </label>
                  </div>
                </div>

                <div id="gcash_details" class="payment-details" style="display: none;">
                  <div class="alert alert-info">
                    <p><strong>GCash Number:</strong> 09XX-XXX-XXXX</p>
                    <p>Please send the payment to the GCash number above and upload your payment screenshot below.</p>
                  </div>
                  <div class="mb-3">
                    <label>Upload Payment Screenshot</label>
                    <input type="file" class="form-control" name="payment_proof" accept="image/*">
                  </div>
                </div>

                <div id="bank_details" class="payment-details" style="display: none;">
                  <div class="alert alert-info">
                    <p><strong>Bank:</strong> BDO</p>
                    <p><strong>Account Number:</strong> XXXX-XXXX-XXXX</p>
                    <p><strong>Account Name:</strong> Auto Parts Hub</p>
                    <p>Please transfer the payment to the bank account above and upload your payment receipt below.</p>
                  </div>
                  <div class="mb-3">
                    <label>Upload Payment Receipt</label>
                    <input type="file" class="form-control" name="payment_proof" accept="image/*">
                  </div>
                </div>

                <div class="mb-3">
                  <label>Additional Notes (Optional)</label>
                  <textarea class="form-control" name="notes" rows="3" placeholder="Any special instructions or notes for your order"></textarea>
                </div>
              </div>
            </div>
            <div class="col-md-4">
              <div class="shopping-cart__totals">
                <h3>Order Summary</h3>
                <table class="cart-totals">
                  <tr>
                    <th>Subtotal</th>
                    <td>${{Cart::instance('cart')->subtotal()}}</td>
                  </tr>
                  <tr>
                    <th>Tax</th>
                    <td>${{Cart::instance('cart')->tax()}}</td>
                  </tr>
                  <tr>
                    <th>Total</th>
                    <td>${{Cart::instance('cart')->total()}}</td>
                  </tr>
                </table>
                <button type="submit" class="btn btn-primary w-100">Review Order</button>
              </div>
            </div>
          </div>
        </form>
      </div>

      <!-- Confirmation Section -->
      <div class="section-wrapper confirmation-section">
        <div class="row">
          <div class="col-md-8">
            <div class="confirmation-details">
              <div class="alert alert-info">
                <strong>Pickup Information:</strong> Your order will be available for pickup at our store once it's ready. We will notify you via email and phone when your order is ready for pickup.
              </div>
              
              <h4>Contact Details</h4>
              <div class="shipping-info">
                <p><strong>Name:</strong> <span id="confirm-name"></span></p>
                <p><strong>Email:</strong> <span id="confirm-email"></span></p>
                <p><strong>Phone:</strong> <span id="confirm-phone"></span></p>
              </div>

              <h4>Payment Details</h4>
              <div class="payment-info">
                <p><strong>Payment Method:</strong> <span id="confirm-payment-mode"></span></p>
                <div id="confirm-payment-proof" style="display: none;">
                  <p><strong>Payment Proof:</strong> Uploaded</p>
                </div>
              </div>

              <p><strong>Notes:</strong> <span id="confirm-notes"></span></p>

              <h4>Order Items</h4>
              <table class="cart-table">
                <thead>
                  <tr>
                    <th>Product</th>
                    <th>Price</th>
                    <th>Quantity</th>
                    <th>Total</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach(Cart::instance('cart')->content() as $item)
                    <tr>
                      <td>{{$item->name}}</td>
                      <td>${{$item->price}}</td>
                      <td>{{$item->qty}}</td>
                      <td>${{$item->subtotal}}</td>
                    </tr>
                  @endforeach
                </tbody>
              </table>
            </div>
          </div>
          <div class="col-md-4">
            <div class="shopping-cart__totals">
              <h3>Order Summary</h3>
              <table class="cart-totals">
                <tr>
                  <th>Subtotal</th>
                  <td>${{Cart::instance('cart')->subtotal()}}</td>
                </tr>
                <tr>
                  <th>Tax</th>
                  <td>${{Cart::instance('cart')->tax()}}</td>
                </tr>
                <tr>
                  <th>Total</th>
                  <td>${{Cart::instance('cart')->total()}}</td>
                </tr>
              </table>
              <form action="{{ route('place-order') }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-primary w-100">Place Order</button>
              </form>
              <button class="btn btn-secondary w-100 mt-2" onclick="showCheckoutSection()">Back to Checkout</button>
            </div>
          </div>
        </div>
      </div>
    </section>
</main>

@push('scripts')
<script>
  function showCartSection() {
    document.querySelector('.cart-section').classList.add('active');
    document.querySelector('.checkout-section').classList.remove('active');
    document.querySelector('.confirmation-section').classList.remove('active');
    
    document.querySelector('[data-step="cart"]').classList.add('active');
    document.querySelector('[data-step="checkout"]').classList.remove('active');
    document.querySelector('[data-step="confirmation"]').classList.remove('active');
  }

  function showCheckoutSection() {
    document.querySelector('.cart-section').classList.remove('active');
    document.querySelector('.checkout-section').classList.add('active');
    document.querySelector('.confirmation-section').classList.remove('active');
    
    document.querySelector('[data-step="cart"]').classList.remove('active');
    document.querySelector('[data-step="checkout"]').classList.add('active');
    document.querySelector('[data-step="confirmation"]').classList.remove('active');
  }

  function showConfirmationSection() {
    document.querySelector('.cart-section').classList.remove('active');
    document.querySelector('.checkout-section').classList.remove('active');
    document.querySelector('.confirmation-section').classList.add('active');
    
    document.querySelector('[data-step="cart"]').classList.remove('active');
    document.querySelector('[data-step="checkout"]').classList.remove('active');
    document.querySelector('[data-step="confirmation"]').classList.add('active');
  }

  // Add payment mode toggle functionality
  document.querySelectorAll('input[name="mode"]').forEach(radio => {
    radio.addEventListener('change', function() {
      document.querySelectorAll('.payment-details').forEach(div => {
        div.style.display = 'none';
      });
      
      if(this.value === 'gcash') {
        document.getElementById('gcash_details').style.display = 'block';
      } else if(this.value === 'bank_transfer') {
        document.getElementById('bank_details').style.display = 'block';
      }
    });
  });

  // Handle checkout form submission
  document.getElementById('checkout-form').addEventListener('submit', function(e) {
    e.preventDefault();
    
    // Get form data
    const formData = new FormData(this);
    
    // Update confirmation section with form data
    document.getElementById('confirm-name').textContent = formData.get('name');
    document.getElementById('confirm-email').textContent = formData.get('email');
    document.getElementById('confirm-phone').textContent = formData.get('mobile');
    document.getElementById('confirm-payment-mode').textContent = formData.get('mode').replace('_', ' ').toUpperCase();
    document.getElementById('confirm-payment-proof').style.display = 
      (formData.get('payment_proof') && formData.get('payment_proof').size > 0) ? 'block' : 'none';
    document.getElementById('confirm-notes').textContent = formData.get('notes') || 'No additional notes';
    
    // Store form data in session
    fetch('{{ route("confirm-order") }}', {
      method: 'POST',
      body: formData,
      headers: {
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
      }
    })
    .then(response => response.json())
    .then(data => {
      if(data.status === 'success') {
        // Show confirmation section
        showConfirmationSection();
      }
    });
  });
</script>
@endpush

@endsection