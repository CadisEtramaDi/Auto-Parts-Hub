<table class="cart-table">
  <thead>
    <tr>
      <th>Product</th>
      <th></th>
      <th>Price</th>
      <th>Quantity</th>
      <th>Subtotal</th>
      <th></th>
    </tr>
  </thead>
  <tbody>
    @foreach ($items as $item)
    <tr>
      <td>
        <div class="shopping-cart__product-item">
          @if($item->model && $item->model->image)
            <img loading="lazy" src="{{ asset('uploads/products/thumbnails/' . trim($item->model->image)) }}" width="120" height="120" alt="{{$item->name}}" />
          @else
            <img loading="lazy" src="{{ asset('images/default-product.png') }}" width="120" height="120" alt="{{$item->name}}" />
          @endif
        </div>
      </td>
      <td>
        <div class="shopping-cart__product-item__detail">
          <h4>{{$item->name}}</h4>
          <ul class="shopping-cart__product-item__options">
            <li>Color: Yellow</li>
            <li>Size: L</li>
          </ul>
        </div>
      </td>
      <td>
        <span class="shopping-cart__product-price">${{$item->price}}</span>
      </td>
      <td>
        <div class="qty-control position-relative">
          <input type="number" name="quantity" value="{{ $item->qty }}" min="1" class="qty-control__number text-center">
          <form method="POST" action="{{ route('cart.qty.decrease', ['rowId'=>$item->rowId]) }}">
            @csrf
            @method('PUT')
            <div class="qty-control__reduce">-</div>
          </form>

          <form method="POST" action="{{ route('cart.qty.increase', ['rowId'=>$item->rowId]) }}">
            @csrf
            @method('PUT')
            <div class="qty-control__increase">+</div>
          </form>
        </div>
      </td>
      <td>
        <span class="shopping-cart__subtotal">${{$item->subTotal()}}</span>
      </td>
      <td>
        <form method="POST" action="{{ route('cart.item.remove',['rowId'=>$item->rowId]) }}">
          @csrf
          @method('DELETE')
          <a href="javascript:void(0)" class="remove-cart">
            <svg width="10" height="10" viewBox="0 0 10 10" fill="#767676" xmlns="http://www.w3.org/2000/svg">
              <path d="M0.259435 8.85506L9.11449 0L10 0.885506L1.14494 9.74056L0.259435 8.85506Z" />
              <path d="M0.885506 0.0889838L9.74057 8.94404L8.85506 9.82955L0 0.97449L0.885506 0.0889838Z" />
            </svg>
          </a>
        </form>
      </td>
    </tr>
    @endforeach
  </tbody>
</table>
<div class="cart-table-footer">
  <form action="{{ route('cart.coupon.apply')}}" method="POST" class="position-relative bg-body">
    @csrf
    <input class="form-control" type="text" name="coupon_code" placeholder="Coupon Code" value="@if(Session::has('coupon')) {{ Session::get('coupon')['code']}} Applied! @endif">
    <input class="btn-link fw-medium position-absolute top-0 end-0 h-100 px-4" type="submit" value="APPLY COUPON">
  </form>

  <form action="{{ route('cart.empty') }}" method="POST">
    @csrf
    @method("DELETE")
    <button class="btn btn-light" type="submit">Clear CART</button>
  </form>
</div>
<div>
  @if(Session::has('success'))
    <p class="text-success">{{ Session::get('success') }}</p> 
  @elseif(Session::has('error'))
    <p class="text-danger">{{ Session::get('error')}}</p>
  @endif
</div> 