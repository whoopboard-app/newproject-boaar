@extends('layouts.inspinia')

@section('title', 'Profile')

@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="card top-area">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">
                    <i class="ti ti-list me-2"></i>Profile Settings
                    <p class="text-muted fs-14 mb-0">Manage user profile</p>
                </h5>
                <div class="d-flex justify-content-between align-items-center">
                    @if(Auth::user()->canAccessAppSettings())
                    <a href="{{ route('settings.index') }}" class="btn btn-secondary me-1">
                        Back to Settings
                    </a>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                @include('profile.partials.update-profile-information-form')
            </div>
        </div>
    </div>
</div>

<div class="row mt-3">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                @include('profile.partials.update-password-form')
            </div>
        </div>
    </div>
</div>

<div class="row mt-3">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                @include('profile.partials.delete-user-form')
            </div>
        </div>
    </div>
</div>
@endsection
