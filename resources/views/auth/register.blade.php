@extends('layouts.inspinia-guest')

@section('title', 'Register')

@section('content')
<h4 class="text-dark fs-20 text-center mb-4">Create Account</h4>

<form method="POST" action="{{ route('register') }}">
    @csrf

    <!-- Name -->
    <div class="mb-3">
        <label for="name" class="form-label">Full Name</label>
        <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" required autofocus autocomplete="name" placeholder="Enter your name">
        @error('name')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
        @enderror
    </div>

    <!-- Email Address -->
    <div class="mb-3">
        <label for="email" class="form-label">Email Address</label>
        <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}" required autocomplete="username" placeholder="Enter your email">
        @error('email')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
        @enderror
    </div>

    <!-- Password -->
    <div class="mb-3">
        <label for="password" class="form-label">Password</label>
        <div class="input-group input-group-merge">
            <input type="password" id="password" name="password" class="form-control @error('password') is-invalid @enderror" placeholder="Enter your password" required autocomplete="new-password">
            @error('password')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
        </div>
    </div>

    <!-- Confirm Password -->
    <div class="mb-3">
        <label for="password_confirmation" class="form-label">Confirm Password</label>
        <div class="input-group input-group-merge">
            <input type="password" id="password_confirmation" name="password_confirmation" class="form-control @error('password_confirmation') is-invalid @enderror" placeholder="Confirm your password" required autocomplete="new-password">
            @error('password_confirmation')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
        </div>
    </div>

    <div class="mb-3">
        <div class="form-check">
            <input type="checkbox" class="form-check-input" id="checkbox-signup" required>
            <label class="form-check-label" for="checkbox-signup">
                I accept <a href="#" class="text-muted">Terms and Conditions</a>
            </label>
        </div>
    </div>

    <div class="mb-0 text-center d-grid">
        <button class="btn btn-primary" type="submit">Sign Up</button>
    </div>
</form>
@endsection

@section('extra-content')
<div class="row mt-3">
    <div class="col-12 text-center">
        <p class="text-muted">Already have an account? <a href="{{ route('login') }}" class="text-primary fw-semibold ms-1">Sign In</a></p>
    </div>
</div>
@endsection
