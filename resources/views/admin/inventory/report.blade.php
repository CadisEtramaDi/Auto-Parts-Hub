@extends('layouts.app')

@section('content')
<div class="container py-4">
    <h2 class="mb-4">Inventory Report</h2>
    <form method="GET" class="mb-4">
        <div class="row">
            <div class="col-md-3">
                <input type="date" name="from" class="form-control" value="{{ $from }}">
            </div>
            <div class="col-md-3">
                <input type="date" name="to" class="form-control" value="{{ $to }}">
            </div>
            <div class="col-md-3">
                <button type="submit" class="btn btn-primary">Filter</button>
            </div>
        </div>
    </form>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Product</th>
                <th>SKU</th>
                <th>Current Stock</th>
                <th>Total Stock In</th>
                <th>Total Stock Out</th>
            </tr>
        </thead>
        <tbody>
            @foreach($products as $product)
            <tr>
                <td>{{ $product->name }}</td>
                <td>{{ $product->SKU }}</td>
                <td>{{ $product->quantity }}</td>
                <td>
                    {{ $product->filteredTransactions->where('type', 'in')->sum('quantity') }}
                </td>
                <td>
                    {{ $product->filteredTransactions->where('type', 'out')->sum('quantity') }}
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection 