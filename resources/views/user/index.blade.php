@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row">
        <div class="col-md-3">
            <div class="card">
                <div class="card-header">
                    <h3 class="mb-0">Account Menu</h3>
                </div>
                <div class="list-group list-group-flush">
                    <a href="{{ route('user.index') }}" class="list-group-item list-group-item-action active">Dashboard</a>
                    <a href="{{ route('user.profile') }}" class="list-group-item list-group-item-action">Profile</a>
                    <a href="#" class="list-group-item list-group-item-action">Orders</a>
                </div>
            </div>
        </div>

        <div class="col-md-9">
            <div class="card">
                <div class="card-header">
                    <h3 class="mb-0">Dashboard</h3>
                </div>
                <div class="card-body">
                    <h5>Welcome, {{ Auth::user()->name }}!</h5>
                    <p>From your account dashboard you can:</p>
                    <ul>
                        <li>View your recent orders</li>
                        <li>Manage your contact information</li>
                        <li>Update your profile details</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection