@extends('layouts.admin')

@section('content')
<div class="main-content-inner">
    <div class="main-content-wrap">
        <div class="flex items-center flex-wrap justify-between gap20 mb-27">
            <h3>Stock Out</h3>
            <ul class="breadcrumbs flex items-center flex-wrap justify-start gap10">
                <li>
                    <a href="{{ route('admin.index') }}"><div class="text-tiny">Dashboard</div></a>
                </li>
                <li>
                    <i class="icon-chevron-right"></i>
                </li>
                <li>
                    <a href="{{ route('admin.inventory.index') }}"><div class="text-tiny">Inventory</div></a>
                </li>
                <li>
                    <i class="icon-chevron-right"></i>
                </li>
                <li>
                    <div class="text-tiny">Stock Out</div>
                </li>
            </ul>
        </div>

        <div class="wg-box">
            <form class="form-style-1" action="{{ route('admin.inventory.stock-out') }}" method="POST">
                @csrf
                <fieldset class="name">
                    <div class="body-title">Product <span class="tf-color-1">*</span></div>
                    <div class="select-wrapper">
                        <select class="form-select flex-grow" id="product_id" name="product_id" required>
                            <option value="">Select Product</option>
                            @foreach($products as $product)
                                <option value="{{ $product->id }}">
                                    {{ $product->name }} (Current: {{ $product->inventory->quantity ?? 0 }})
                                </option>
                            @endforeach
                        </select>
                        <i class="icon-chevron-down select-arrow"></i>
                    </div>
                </fieldset>
                @error('product_id') <span class="alert alert-danger text-center">{{ $message }}</span> @enderror

                <fieldset class="name">
                    <div class="body-title">Quantity to Remove <span class="tf-color-1">*</span></div>
                    <input class="flex-grow" type="number" id="quantity" name="quantity" 
                           placeholder="Enter quantity" min="1" required>
                </fieldset>
                @error('quantity') <span class="alert alert-danger text-center">{{ $message }}</span> @enderror

                <fieldset class="name">
                    <div class="body-title">Note (Optional)</div>
                    <textarea class="flex-grow ht-150" id="note" name="note" 
                              placeholder="Enter reason for stock out"></textarea>
                </fieldset>
                @error('note') <span class="alert alert-danger text-center">{{ $message }}</span> @enderror

                <div class="bot">
                    <div></div>
                    <button class="tf-button style-3 w208" type="submit">
                        <i class="icon-arrow-up"></i> Remove Stock
                    </button>
                    <a href="{{ route('admin.inventory.index') }}" class="tf-button style-2 w208">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .select-wrapper {
        position: relative;
        display: inline-block;
        width: 100%;
    }
    .form-select {
        appearance: none;
        -webkit-appearance: none;
        -moz-appearance: none;
        padding-right: 30px;
        width: 100%;
    }
    .select-arrow {
        position: absolute;
        right: 10px;
        top: 50%;
        transform: translateY(-50%);
        pointer-events: none;
    }
    .ht-150 {
        height: 150px;
    }
    .tf-button.style-3 {
        background-color: #f44336;
        color: white;
    }
    .tf-button.style-3:hover {
        background-color: #d32f2f;
    }
</style>
@endpush

@push('scripts')
<script>
    $(document).ready(function() {
        // Validate quantity doesn't exceed available stock
        $('form').submit(function(e) {
            const productId = $('#product_id').val();
            const quantity = parseInt($('#quantity').val());
            const currentStock = parseInt($('#product_id option:selected').text().match(/Current: (\d+)/)[1]);
            
            if (quantity > currentStock) {
                e.preventDefault();
                alert('Cannot remove more stock than available! Current stock: ' + currentStock);
            }
        });
    });
</script>
@endpush