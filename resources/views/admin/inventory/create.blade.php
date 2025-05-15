@extends('layouts.admin')

@section('content')
<div class="main-content-inner">
    <div class="main-content-wrap">
        <div class="flex items-center flex-wrap justify-between gap20 mb-27">
            <h3>Add New Inventory</h3>
            <ul class="breadcrumbs flex items-center flex-wrap justify-start gap10">
                <li><a href="{{ route('admin.index') }}" class="text-tiny">Dashboard</a></li>
                <li><i class="icon-chevron-right"></i></li>
                <li><a href="{{ route('admin.inventory.index') }}" class="text-tiny">Inventory</a></li>
                <li><i class="icon-chevron-right"></i></li>
                <li><span class="text-tiny">New Inventory</span></li>
            </ul>
        </div>

        <div class="card">
            <div class="card-body">
                @if ($errors->any())
                    <div class="alert alert-danger mb-4">
                        <ul class="list-disc list-inside">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('admin.inventory.store') }}" method="POST" class="space-y-6">
                    @csrf
                    
                    <div class="form-group">
                        <label for="product_id" class="form-label required">Product</label>
                        <select id="product_id" name="product_id" class="form-select @error('product_id') is-invalid @enderror" required>
                            <option value="">Select a Product</option>
                            @foreach($products as $product)
                                <option value="{{ $product->id }}" 
                                        data-sku="{{ $product->sku }}"
                                        {{ old('product_id') == $product->id ? 'selected' : '' }}>
                                    {{ $product->name }} (SKU: {{ $product->sku }})
                                </option>
                            @endforeach
                        </select>
                        @error('product_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="quantity" class="form-label required">Initial Quantity</label>
                        <div class="input-group">
                            <input type="number" 
                                   id="quantity" 
                                   name="quantity" 
                                   class="form-control @error('quantity') is-invalid @enderror"
                                   value="{{ old('quantity', 0) }}"
                                   min="0"
                                   required>
                            <span class="input-group-text">units</span>
                        </div>
                        @error('quantity')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="sku" class="form-label">Inventory SKU</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="icon-barcode"></i></span>
                            <input type="text" 
                                   id="sku" 
                                   name="sku" 
                                   class="form-control @error('sku') is-invalid @enderror"
                                   value="{{ old('sku') }}"
                                   placeholder="Will be auto-generated if left empty">
                        </div>
                        @error('sku')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="location" class="form-label">Storage Location</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="icon-location"></i></span>
                            <input type="text" 
                                   id="location" 
                                   name="location" 
                                   class="form-control @error('location') is-invalid @enderror"
                                   value="{{ old('location') }}"
                                   placeholder="e.g., Warehouse A, Shelf B3">
                        </div>
                        @error('location')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-actions">
                        <button type="button" onclick="window.history.back()" class="btn btn-secondary">
                            <i class="icon-arrow-left"></i> Cancel
                        </button>
                        <button type="submit" class="btn btn-primary">
                            <i class="icon-save"></i> Save Inventory
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .form-group {
        margin-bottom: 1.5rem;
    }
    .form-label {
        font-weight: 500;
        margin-bottom: 0.5rem;
        display: block;
    }
    .form-label.required::after {
        content: '*';
        color: #dc3545;
        margin-left: 4px;
    }
    .invalid-feedback {
        color: #dc3545;
        font-size: 0.875rem;
        margin-top: 0.25rem;
    }
    .form-actions {
        display: flex;
        justify-content: flex-end;
        gap: 1rem;
        margin-top: 2rem;
        padding-top: 1rem;
        border-top: 1px solid #e5e7eb;
    }
    .input-group {
        display: flex;
        align-items: stretch;
    }
    .input-group-text {
        background-color: #f3f4f6;
        border: 1px solid #d1d5db;
        padding: 0.375rem 0.75rem;
    }
    .form-control, .form-select {
        width: 100%;
        padding: 0.375rem 0.75rem;
        border: 1px solid #d1d5db;
        border-radius: 0.25rem;
        transition: border-color 0.15s ease-in-out;
    }
    .form-control:focus, .form-select:focus {
        border-color: #3b82f6;
        outline: none;
        box-shadow: 0 0 0 2px rgba(59, 130, 246, 0.25);
    }
    .is-invalid {
        border-color: #dc3545 !important;
    }
</style>
@endpush

@push('scripts')
<script>
$(function(){
    function generateInventorySku(productSku) {
        if (!productSku) return '';
        const timestamp = new Date().getTime().toString().slice(-4);
        return `${productSku}-INV${timestamp}`;
    }

    $('#product_id').change(function() {
        const selectedOption = $(this).find('option:selected');
        const productSku = selectedOption.data('sku');
        
        const currentSku = $('#sku').val();
        if (!currentSku || currentSku.includes('-INV')) {
            $('#sku').val(generateInventorySku(productSku));
        }
    });

    $('#quantity').on('input', function() {
        const value = $(this).val();
        if (value < 0) {
            $(this).val(0);
        }
    });

    $('form').submit(function(e) {
        const productId = $('#product_id').val();
        const quantity = $('#quantity').val();

        if (!productId) {
            e.preventDefault();
            alert('Please select a product');
            return false;
        }

        if (quantity < 0) {
            e.preventDefault();
            alert('Quantity cannot be negative');
            return false;
        }
    });
});
</script>
@endpush