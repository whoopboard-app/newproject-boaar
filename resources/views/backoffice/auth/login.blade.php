@extends('backoffice.layouts.guest')

@section('title', 'Login')

@section('content')
<h4 class="text-dark fs-20 text-center mb-4">Verify & Login</h4>
<p class="text-muted text-center mb-4">Enter the verification code sent to your email along with your password.</p>

<!-- Session Status -->
@if (session('status'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('status') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

<!-- Error Messages -->
@if ($errors->any())
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <ul class="mb-0 list-unstyled">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

<form method="POST" action="{{ route('backoffice.authenticate') }}">
    @csrf

    <!-- Email Address (hidden/readonly) -->
    <div class="mb-3">
        <label for="email" class="form-label">Email Address</label>
        <input type="email" class="form-control bg-light" id="email" name="email" value="{{ $email }}" readonly>
    </div>

    <!-- Verification Code -->
    <div class="mb-3">
        <label for="code" class="form-label">Verification Code</label>
        <input type="text" class="form-control code-input @error('code') is-invalid @enderror" id="code" name="code" value="{{ old('code') }}" required autofocus maxlength="6" placeholder="XXXXXX" autocomplete="off">
        @error('code')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
        @enderror
        <small class="text-muted">Enter the 6-character code sent to your email (valid for 30 minutes)</small>
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

    <div class="mb-0 text-center d-grid">
        <button class="btn btn-dark" type="submit">
            <i class="ti ti-lock me-1"></i> Login to Backoffice
        </button>
    </div>
</form>

<div class="mt-4 text-center">
    <a href="{{ route('backoffice.email') }}" class="text-muted">
        <i class="ti ti-refresh me-1"></i> Request a new code
    </a>
</div>
@endsection

@push('scripts')
<script>
    // Auto-uppercase the code input
    document.getElementById('code').addEventListener('input', function() {
        this.value = this.value.toUpperCase();
    });
</script>
@endpush
