@extends('layouts.inspinia-guest')

@section('title', 'Login')

@section('content')
<h4 class="text-dark fs-20 text-center mb-4">Sign In</h4>

<!-- Session Status -->
@if (session('status'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('status') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

<form method="POST" action="{{ route('login') }}">
    @csrf

    <!-- Email Address -->
    <div class="mb-3">
        <label for="email" class="form-label">Email Address</label>
        <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username" placeholder="Enter your email">
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
            <input type="password" id="password" name="password" class="form-control @error('password') is-invalid @enderror" placeholder="Enter your password" required autocomplete="current-password">
            @error('password')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
        </div>
    </div>

    <!-- Remember Me -->
    <div class="mb-3">
        <div class="form-check">
            <input type="checkbox" class="form-check-input" id="remember_me" name="remember">
            <label class="form-check-label" for="remember_me">Remember Me</label>
        </div>
    </div>

    <div class="mb-0 text-center d-grid">
        <button class="btn btn-primary" type="submit">Log In</button>
    </div>

    @if (Route::has('password.request'))
        <div class="mt-3 text-center">
            <a href="{{ route('password.request') }}" class="text-muted">
                <i class="ti ti-lock"></i> Forgot your password?
            </a>
        </div>
    @endif
</form>
@endsection

@section('extra-content')
<div class="row mt-3">
    <div class="col-12 text-center">
        <p class="text-muted">Don't have an account? <a href="{{ route('register') }}" class="text-primary fw-semibold ms-1">Create new account</a></p>
    </div>
</div>
@endsection
