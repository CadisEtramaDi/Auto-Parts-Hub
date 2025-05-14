@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="mb-0">Supplier Management</h3>
                    <a href="{{ route('supplier.add') }}" class="btn btn-primary btn-sm">
                        <i class="fas fa-plus mr-2"></i>Add New Supplier
                    </a>
                </div>

                @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show mx-3 mt-3">
                    {{ session('success') }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                @endif

                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="thead-light">
                                <tr>
                                    <th width="5%">ID</th>
                                    <th width="20%">Name</th>
                                    <th width="20%">Email</th>
                                    <th width="15%">Phone</th>
                                    <th width="15%">Contact Person</th>
                                    <th width="10%">Status</th>
                                    <th width="15%" class="text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($suppliers as $supplier)
                                <tr>
                                    <td>{{ $supplier->id }}</td>
                                    <td>
                                        <strong>{{ $supplier->name }}</strong>
                                    </td>
                                    <td>{{ $supplier->email ?? 'N/A' }}</td>
                                    <td>{{ $supplier->phone ?? 'N/A' }}</td>
                                    <td>{{ $supplier->contact_person ?? 'N/A' }}</td>
                                    <td>
                                        <span class="badge badge-pill {{ $supplier->active ? 'badge-success' : 'badge-secondary' }}">
                                            {{ $supplier->active ? 'Active' : 'Inactive' }}
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('supplier.edit', $supplier->id) }}" 
                                               class="btn btn-sm btn-outline-primary" 
                                               data-toggle="tooltip" 
                                               title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ route('supplier.delete', $supplier->id) }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" 
                                                        class="btn btn-sm btn-outline-danger" 
                                                        onclick="return confirm('Are you sure you want to delete this supplier?')"
                                                        data-toggle="tooltip" 
                                                        title="Delete">
                                                    <i class="fas fa-trash-alt"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7" class="text-center text-muted py-4">No suppliers found. Click "Add New Supplier" to get started.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                
                @if($suppliers instanceof \Illuminate\Pagination\LengthAwarePaginator && $suppliers->hasPages())
                <div class="card-footer">
                    {{ $suppliers->links() }}
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .card {
        border: none;
        box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
    }
    
    .card-header {
        background-color: #f8f9fc;
        border-bottom: 1px solid #e3e6f0;
    }
    
    .table thead th {
        border-top: none;
        border-bottom: 1px solid #e3e6f0;
        font-weight: 600;
        text-transform: uppercase;
        font-size: 0.75rem;
        letter-spacing: 0.5px;
    }
    
    .table td, .table th {
        vertical-align: middle;
        padding: 1rem;
    }
    
    .badge-pill {
        padding: 0.35em 0.65em;
        font-weight: 500;
    }
    
    .btn-sm {
        padding: 0.25rem 0.5rem;
        font-size: 0.75rem;
    }
    
    .btn-group .btn {
        margin-right: 5px;
    }
    
    .btn-group .btn:last-child {
        margin-right: 0;
    }
</style>
@endpush

@push('scripts')
<script>
    $(function () {
        $('[data-toggle="tooltip"]').tooltip();
    });
</script>
@endpush