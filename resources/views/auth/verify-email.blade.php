@extends('layouts.inspinia-guest')

@section('title', 'Verify Email')

@section('content')
<div class="text-center mb-4">
    <h4 class="text-dark-50 text-center mt-0 fw-bold">Email Verification</h4>
    <p class="text-muted mb-4">We've sent you a verification link</p>
</div>

<div class="alert alert-info" role="alert">
    <i class="ti ti-mail me-2"></i>
    Thanks for signing up! Before getting started, could you verify your email address by clicking on the link we just emailed to you? If you didn't receive the email, we will gladly send you another.
</div>

@if (session('status') == 'verification-link-sent')
    <div class="alert alert-success" role="alert">
        <i class="ti ti-circle-check me-2"></i>
        A new verification link has been sent to the email address you provided during registration.
    </div>
@endif

<div class="mb-0 text-center">
    <form method="POST" action="{{ route('verification.send') }}" class="d-inline">
        @csrf
        <button type="submit" class="btn btn-primary">
            <i class="ti ti-refresh me-1"></i>Resend Verification Email
        </button>
    </form>
</div>

<div class="mt-3 text-center">
    <form method="POST" action="{{ route('logout') }}" class="d-inline">
        @csrf
        <button type="submit" class="btn btn-link text-muted">
            <i class="ti ti-logout me-1"></i>Log Out
        </button>
    </form>
</div>
@endsection
