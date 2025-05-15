@extends('layouts.app')

@section('content')
<div class="container py-5">
    @if(session()->has('success'))
        <div class="alert alert-success">
            {{ session()->get('success') }}
        </div>
    @endif

    @if(session()->has('error'))
        <div class="alert alert-danger">
            {{ session()->get('error') }}
        </div>
    @endif

    <div class="row">
        <div class="col-md-6 mb-4">
            <div class="card">
                <div class="card-header">
                    <h3 class="mb-0">Profile Information</h3>
                </div>
                <div class="card-body">
                    <form action="{{ route('user.profile.update') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="name" class="form-label">Name</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                   id="name" name="name" value="{{ old('name', $user->name) }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                   id="email" name="email" value="{{ old('email', $user->email) }}" required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <button type="submit" class="btn btn-primary">Update Profile</button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="mb-0">Contact Information</h3>
                </div>
                <div class="card-body">
                    <form action="{{ route('user.contact.store') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="contact_name" class="form-label">Name</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                   id="contact_name" name="name" 
                                   value="{{ old('name', $contact->name ?? '') }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="phone" class="form-label">Phone Number</label>
                            <input type="tel" class="form-control @error('phone') is-invalid @enderror" 
                                   id="phone" name="phone" 
                                   value="{{ old('phone', $contact->phone ?? '') }}" required>
                            @error('phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="pickup_time" class="form-label">Preferred Pickup Time</label>
                            <select name="pickup_time" id="pickup_time" class="form-select @error('pickup_time') is-invalid @enderror">
                                <option value="">Select preferred time</option>
                                <option value="morning" {{ (old('pickup_time', $contact->pickup_time ?? '') == 'morning') ? 'selected' : '' }}>
                                    Morning (9:00 AM - 12:00 PM)
                                </option>
                                <option value="afternoon" {{ (old('pickup_time', $contact->pickup_time ?? '') == 'afternoon') ? 'selected' : '' }}>
                                    Afternoon (1:00 PM - 5:00 PM)
                                </option>
                                <option value="evening" {{ (old('pickup_time', $contact->pickup_time ?? '') == 'evening') ? 'selected' : '' }}>
                                    Evening (6:00 PM - 8:00 PM)
                                </option>
                            </select>
                            @error('pickup_time')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="special_instructions" class="form-label">Special Instructions</label>
                            <textarea class="form-control @error('special_instructions') is-invalid @enderror" 
                                      id="special_instructions" name="special_instructions" rows="3">{{ old('special_instructions', $contact->special_instructions ?? '') }}</textarea>
                            @error('special_instructions')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <button type="submit" class="btn btn-primary">Save Contact Information</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 