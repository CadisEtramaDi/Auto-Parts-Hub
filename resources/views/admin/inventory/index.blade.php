@extends('layouts.admin')

@section('content')
<div class="main-content-inner">
    <div class="main-content-wrap">
        <div class="flex items-center flex-wrap justify-between gap20 mb-27">
            <h3>Inventory Management</h3>
            <ul class="breadcrumbs flex items-center flex-wrap justify-start gap10">
                <li>
                    <a href="{{ route('admin.index') }}">
                        <div class="text-tiny">Dashboard</div>
                    </a>
                </li>
                <li>
                    <i class="icon-chevron-right"></i>
                </li>
                <li>
                    <div class="text-tiny">Inventory</div>
                </li>
            </ul>
        </div>

        <div class="wg-box">
            <div class="flex items-center justify-between gap10 flex-wrap">
                <div class="wg-filter flex-grow">
                    <form class="form-search">
                        <fieldset class="name">
                            <input type="text" placeholder="Search inventory..." class="" name="search"
                                tabindex="2" value="{{ request('search') }}" aria-required="true">
                        </fieldset>
                        <div class="button-submit">
                            <button class="" type="submit"><i class="icon-search"></i></button>
                        </div>
                    </form>
                </div>
                <div class="flex gap10">
                    <a class="tf-button style-1 w208" href="{{ route('admin.inventory.create') }}">
                        <i class="icon-plus"></i>Add New
                    </a>
                    <a class="tf-button style-2 w208" href="{{ route('admin.inventory.stock-in.form') }}">
                        <i class="icon-arrow-down"></i>Stock In
                    </a>
                    <a class="tf-button style-3 w208" href="{{ route('admin.inventory.stock-out.form') }}">
                        <i class="icon-arrow-up"></i>Stock Out
                    </a>
                </div>
            </div>

            <div class="table-responsive">
                @if(Session::has('status'))
                    <p class="alert alert-success">{{ Session::get('status') }}</p>
                @endif
                <table class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Product</th>
                            <th>SKU</th>
                            <th>Quantity</th>
                            <th>Location</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($inventory as $item)
                        <tr>
                            <td>{{ $item->id }}</td>
                            <td class="pname">
                                @if($item->product->image)
                                <div class="image">
                                    <img src="{{ asset('uploads/products/thumbnails') }}/{{ $item->product->image }}" alt="{{ $item->product->name }}" class="image">
                                </div>
                                @endif
                                <div class="name">
                                    <a href="{{ route('admin.inventory.show', $item->id) }}" class="body-title-2">{{ $item->product->name }}</a>
                                    <div class="text-tiny mt-3">{{ $item->product->sku }}</div>
                                </div>
                            </td>
                            <td>{{ $item->sku }}</td>
                            <td>
                                <span class="badge {{ $item->quantity > 10 ? 'bg-success' : 'bg-warning' }}">
                                    {{ $item->quantity }}
                                </span>
                            </td>
                            <td>{{ $item->location }}</td>
                            <td>
                                <div class="list-icon-function">
                                    <a href="{{ route('admin.inventory.show', $item->id) }}">
                                        <div class="item eye">
                                            <i class="icon-eye"></i>
                                        </div>
                                    </a>
                                    <a href="{{ route('admin.inventory.edit', $item->id) }}">
                                        <div class="item edit">
                                            <i class="icon-edit-3"></i>
                                        </div>
                                    </a>
                                    <form action="{{ route('admin.inventory.destroy', $item->id) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <div class="item text-danger delete">
                                            <i class="icon-trash-2"></i>
                                        </div>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="divider"></div>
            <div class="flex items-center justify-between flex-wrap gap10 wgp-pagination">
                {{ $inventory->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
    <script>
        $(function(){
            $(".delete").on('click',function(e){
                e.preventDefault();
                var selectedForm = $(this).closest('form');
                swal({
                    title: "Are you sure?",
                    text: "You want to delete this inventory item?",
                    type: "warning",
                    buttons: ["No!", "Yes!"],
                    confirmButtonColor: '#dc3545'
                }).then(function (result) {
                    if (result) {
                        selectedForm.submit();  
                    }
                });                             
            });
        });
    </script>
@endpush