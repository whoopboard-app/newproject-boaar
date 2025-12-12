@extends('layouts.inspinia-guest')

@section('title', 'Login')

@section('content')
<h4 class="text-dark fs-18 text-center mb-1" style="color: #0b0809 !important;`">Sign in</h4>
<div class="col-12 text-center">
    <p class="sub-title-txt">Need an account? <a href="{{ route('register') }}" class="ms-1">Sign up</a></p>
</div>
<!-- Session Status -->
@if (session('status'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('status') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

<form class="login-form" method="POST" action="{{ route('login') }}">
    @csrf

    <!-- Email Address -->
    <div class="mb-3">
        <label for="email" class="form-label">Email</label>
        <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username" placeholder="Enter your email">
        @error('email')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
        @enderror
    </div>

    <!-- Password -->
    <div class="mb-3">
        <label for="password" class="form-label">Password
            @if (Route::has('password.request'))
            <a href="{{ route('password.request') }}" class="frgt-pwd-text">Forgot password?
            </a>
    @endif
        </label>
        <div class="input-group input-group-merge">
            <input type="password" id="password" name="password" class="form-control @error('password') is-invalid @enderror" placeholder="Enter your password" required autocomplete="current-password">
            <a class="view-pwd-icon" href="javascript:void(0);"><i class="ti ti-eye"></i></a>
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
        <button class="btn btn-primary" type="submit">Sign In</button>
    </div>

    
</form>
@endsection
@push('scripts')
<script>
    $(document).ready(function() {
        $('.view-pwd-icon').click(function() {
            var input = $(this).parent().find('input');
            if (input.attr('type') === 'password') {
                input.attr('type', 'text');
                $(this).find('i').removeClass('ti-eye').addClass('ti-eye-off');
            } else {
                input.attr('type', 'password');
                $(this).find('i').removeClass('ti-eye-off').addClass('ti-eye');
            }
        });
    });
</script>
@endpush