{{-- Top Navigation Bar for Public Pages --}}
{{-- Required variables: $settings, optional: $isLoggedIn, $publicUser, $feedbackSettings --}}
@php
    $isLoggedIn = $isLoggedIn ?? false;
    $publicUser = $publicUser ?? null;
@endphp

<header class="public-header pt-0">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center">
            <div class="logo-section">
                @if($settings->logo)
                    <img src="{{ asset('storage/' . $settings->logo) }}" alt="{{ $settings->product_name }}" class="logo-img">
                @else
                    <div class="logo-img" style="background: var(--primary-color); display: flex; align-items: center; justify-content: center; color: white; font-weight: 700; font-size: 1.25rem;">
                        {{ strtoupper(substr($settings->product_name ?? 'F', 0, 1)) }}
                    </div>
                    <h1 class="product-name">{{ $settings->product_name ?? 'Feedback Board' }}</h1>
                @endif
            </div>

            <div class="header-actions">
                {{-- Add Idea Button - Always visible --}}
                <button type="button" class="btn btn-add-idea" data-bs-toggle="offcanvas" data-bs-target="#addIdeaOffcanvas" aria-controls="addIdeaOffcanvas">
                    <i class="ti ti-bulb me-1"></i> Add Idea
                </button>

                {{-- Subscribe Button --}}
                <a href="{{ route('public.subscribe') }}" class="btn btn-subscribe">
                    <i class="ti ti-bell-ringing me-1"></i> Subscribe
                </a>

                {{-- Login/Logout Section --}}
                @if($isLoggedIn)
                    <span class="user-info">
                        <i class="ti ti-user-circle"></i>
                        {{ $publicUser->full_name ?? $publicUser->email }}
                    </span>
                    <a href="{{ route('public.auth.logout') }}" class="btn-logout">
                        <i class="ti ti-logout me-1"></i> Log out
                    </a>
                @else
                    <a href="{{ route('public.auth.login') }}" class="btn-login">
                        <i class="ti ti-login me-1"></i> Log in
                    </a>
                @endif
            </div>
        </div>

        {{-- Include Tab Navigation --}}
        @include('public.partials.navigation')
    </div>
</header>
