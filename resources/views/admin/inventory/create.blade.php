@extends('layouts.admin')

@section('content')
<div class="main-content-inner">
    <div class="main-content-wrap">
        <div class="flex items-center flex-wrap justify-between gap20 mb-27">
            <h3>Inventory Information</h3>
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
                    <div class="text-tiny">New Inventory</div>
                </li>
            </ul>
        </div>

        <div class="wg-box">
            <form class="form-new-product form-style-1" action="{{ route('admin.inventory.store') }}" method="POST">
                @csrf
                <fieldset class="name">
                    <div class="body-title">Product <span class="tf-color-1">*</span></div>
                    <div class="select">
                        <select class="flex-grow" name="product_id" tabindex="0" aria-required="true" required>
                            <option value="">Select Product</option>
                            @foreach($products as $product)
                                <option value="{{ $product->id }}" {{ old('product_id') == $product->id ? 'selected' : '' }}>
                                    {{ $product->name }} ({{ $product->sku }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                </fieldset>
                @error('product_id') <span class="alert alert-danger text-center">{{ $message }}</span> @enderror

                <fieldset class="name">
                    <div class="body-title">Initial Quantity <span class="tf-color-1">*</span></div>
                    <input class="flex-grow" type="number" placeholder="Enter initial quantity" 
                           name="quantity" tabindex="0" value="{{ old('quantity', 0) }}" 
                           min="0" aria-required="true" required>
                </fieldset>
                @error('quantity') <span class="alert alert-danger text-center">{{ $message }}</span> @enderror

                <fieldset class="name">
                    <div class="body-title">SKU</div>
                    <input class="flex-grow" type="text" placeholder="Enter SKU (optional)" 
                           name="sku" tabindex="0" value="{{ old('sku') }}">
                </fieldset>
                @error('sku') <span class="alert alert-danger text-center">{{ $message }}</span> @enderror

                <fieldset class="name">
                    <div class="body-title">Location</div>
                    <input class="flex-grow" type="text" placeholder="Enter location (optional)" 
                           name="location" tabindex="0" value="{{ old('location') }}">
                </fieldset>
                @error('location') <span class="alert alert-danger text-center">{{ $message }}</span> @enderror

                <div class="bot">
                    <div></div>
                    <button class="tf-button w208" type="submit">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(function(){
        // Auto-generate SKU when product is selected
        $('select[name="product_id"]').change(function() {
            const productId = $(this).val();
            if (productId && !$('input[name="sku"]').val()) {
                const product = @json($products->keyBy('id'));
                $('input[name="sku"]').val(product[productId].sku + '-INV');
            }
        });
    });
</script>
@endpush