@extends('backoffice.layouts.guest')

@section('title', 'Access Request')

@section('content')
<h4 class="text-dark fs-20 text-center mb-4">Request Access</h4>
<p class="text-muted text-center mb-4">Enter your email address to receive a verification code.</p>

<!-- Error Messages -->
@if ($errors->any())
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        @foreach ($errors->all() as $error)
            {{ $error }}
        @endforeach
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

<form method="POST" action="{{ route('backoffice.send-code') }}">
    @csrf

    <!-- Email Address -->
    <div class="mb-3">
        <label for="email" class="form-label">Email Address</label>
        <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="email" placeholder="Enter your email address">
        @error('email')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
        @enderror
    </div>

    <div class="mb-0 text-center d-grid">
        <button class="btn btn-dark" type="submit">
            <i class="ti ti-mail me-1"></i> Send Verification Code
        </button>
    </div>
</form>

<div class="mt-4 text-center">
    <a href="{{ route('login') }}" class="text-muted">
        <i class="ti ti-arrow-left me-1"></i> Back to main login
    </a>
</div>
@endsection
