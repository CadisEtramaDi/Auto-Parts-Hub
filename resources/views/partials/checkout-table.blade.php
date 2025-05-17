<table class="cart-table">
  <thead>
    <tr>
      <th>Product</th>
      <th></th>
      <th>Price</th>
      <th>Quantity</th>
      <th>Subtotal</th>
    </tr>
  </thead>
  <tbody>
    @foreach ($items as $item)
    <tr>
      <td>
        <div class="shopping-cart__product-item">
          @if($item->model && $item->model->image && file_exists(public_path('uploads/products/thumbnails/' . $item->model->image)))
            <img loading="lazy" src="{{ asset('uploads/products/thumbnails/' . $item->model->image) }}" width="120" height="120" alt="{{$item->name}}" />
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
        <span>{{$item->qty}}</span>
      </td>
      <td>
        <span class="shopping-cart__subtotal">${{$item->subTotal()}}</span>
      </td>
    </tr>
    @endforeach
  </tbody>
</table> 