@extends('layouts.inspinia')

@section('title', 'App Configuration')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4 class="font-weight-semibold mb-0">Module Configuration</h4>
            </div>
            <div class="card-body">
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="ti ti-check-circle me-2"></i>{{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @php
                    $activeTab = request('tab', 'feedback');
                @endphp

                <!-- Tabs Navigation -->
                <ul class="nav nav-tabs nav-bordered mb-3" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link {{ $activeTab === 'changelog' ? 'active' : '' }}" id="changelog-tab" data-bs-toggle="tab" data-bs-target="#changelog" type="button" role="tab">
                            <i class="ti ti-timeline me-1"></i> Change Log
                            <span class="badge bg-secondary ms-2">Coming Soon</span>
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link {{ $activeTab === 'feedback' ? 'active' : '' }}" id="feedback-tab" data-bs-toggle="tab" data-bs-target="#feedback" type="button" role="tab">
                            <i class="ti ti-message-dots me-1"></i> Feedback
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="testimonials-tab" data-bs-toggle="tab" data-bs-target="#testimonials" type="button" role="tab">
                            <i class="ti ti-star me-1"></i> Testimonials
                            <span class="badge bg-secondary ms-2">Coming Soon</span>
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="email-tab" data-bs-toggle="tab" data-bs-target="#email" type="button" role="tab">
                            <i class="ti ti-mail me-1"></i> Email Template
                            <span class="badge bg-secondary ms-2">Coming Soon</span>
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="knowledge-tab" data-bs-toggle="tab" data-bs-target="#knowledge" type="button" role="tab">
                            <i class="ti ti-book me-1"></i> Knowledge Board
                            <span class="badge bg-secondary ms-2">Coming Soon</span>
                        </button>
                    </li>
                </ul>

                <!-- Tabs Content -->
                <div class="tab-content">
                    <!-- Change Log Tab -->
                    <div class="tab-pane fade {{ $activeTab === 'changelog' ? 'show active' : '' }}" id="changelog" role="tabpanel">
                        <div class="text-center py-5">
                            <i class="ti ti-clock fs-48 text-muted mb-3"></i>
                            <h5 class="text-muted">Change Log Settings</h5>
                            <p class="text-muted">Configuration options for change log module will be available soon.</p>
                        </div>
                    </div>

                    <!-- Feedback Tab -->
                    <div class="tab-pane fade {{ $activeTab === 'feedback' ? 'show active' : '' }}" id="feedback" role="tabpanel">
                        <div class="row">
                            <div class="col-12">
                                <h5 class="mb-3">
                                    <i class="ti ti-settings me-2"></i>Feedback Settings
                                </h5>
                                <p class="text-muted mb-4">Configure how users interact with feedback items, including voting rules and authentication requirements.</p>

                                <form action="{{ route('configuration.feedback-settings.update') }}" method="POST">
                                    @csrf

                                    <!-- Voting & Authentication Settings -->
                                    <div class="card mb-4">
                                        <div class="card-header">
                                            <h6 class="mb-0">
                                                <i class="ti ti-user-check me-2"></i>Voting & Authentication
                                            </h6>
                                        </div>
                                        <div class="card-body">
                                            <!-- Enable Login for Comment and Voting -->
                                            <div class="form-check form-switch mb-3">
                                                <input class="form-check-input" type="checkbox" id="enable_login_for_voting" name="enable_login_for_voting" {{ $feedbackSettings->enable_login_for_voting ? 'checked' : '' }}>
                                                <label class="form-check-label" for="enable_login_for_voting">
                                                    <strong>Enable Login for Comment and Voting</strong>
                                                    <small class="d-block text-muted">Require users to log in before they can vote or comment on feedback items.</small>
                                                </label>
                                            </div>

                                            <!-- Anonymous Voting -->
                                            <div class="form-check form-switch mb-3">
                                                <input class="form-check-input" type="checkbox" id="enable_anonymous_voting" name="enable_anonymous_voting" {{ $feedbackSettings->enable_anonymous_voting ? 'checked' : '' }}>
                                                <label class="form-check-label" for="enable_anonymous_voting">
                                                    <strong>Anonymous Voting</strong>
                                                    <small class="d-block text-muted">Allow users to vote without providing any identification.</small>
                                                </label>
                                            </div>

                                            <!-- Email-Based Voting -->
                                            <div class="form-check form-switch mb-3">
                                                <input class="form-check-input" type="checkbox" id="enable_email_based_voting" name="enable_email_based_voting" {{ $feedbackSettings->enable_email_based_voting ? 'checked' : '' }} disabled>
                                                <label class="form-check-label" for="enable_email_based_voting">
                                                    <strong>Email-Based Voting</strong>
                                                    <span class="badge bg-secondary ms-2">Coming Soon</span>
                                                    <small class="d-block text-muted">Track votes by email address without requiring full registration.</small>
                                                </label>
                                            </div>

                                            <!-- Internal Voting -->
                                            <div class="form-check form-switch mb-0">
                                                <input class="form-check-input" type="checkbox" id="enable_internal_voting" name="enable_internal_voting" {{ $feedbackSettings->enable_internal_voting ? 'checked' : '' }} disabled>
                                                <label class="form-check-label" for="enable_internal_voting">
                                                    <strong>Internal Voting</strong>
                                                    <span class="badge bg-secondary ms-2">Coming Soon</span>
                                                    <small class="d-block text-muted">Allow team members to vote internally on feedback items.</small>
                                                </label>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Fraud Prevention Settings -->
                                    <div class="card mb-4">
                                        <div class="card-header">
                                            <h6 class="mb-0">
                                                <i class="ti ti-shield-check me-2"></i>Fraud Prevention
                                            </h6>
                                        </div>
                                        <div class="card-body">
                                            <!-- IP Rate Limiting -->
                                            <div class="mb-3">
                                                <label for="ip_rate_limit_per_hour" class="form-label">
                                                    <strong>IP Rate Limit (per hour)</strong>
                                                </label>
                                                <input type="number" class="form-control" id="ip_rate_limit_per_hour" name="ip_rate_limit_per_hour" value="{{ $feedbackSettings->ip_rate_limit_per_hour }}" min="1" max="100">
                                                <small class="text-muted">Maximum number of votes allowed from a single IP address per hour.</small>
                                            </div>

                                            <!-- Email OTP for First Vote -->
                                            <div class="form-check form-switch mb-3">
                                                <input class="form-check-input" type="checkbox" id="require_email_otp_first_vote" name="require_email_otp_first_vote" {{ $feedbackSettings->require_email_otp_first_vote ? 'checked' : '' }}>
                                                <label class="form-check-label" for="require_email_otp_first_vote">
                                                    <strong>Email OTP for First Vote</strong>
                                                    <small class="d-block text-muted">Require email verification via OTP for the first vote from a new user.</small>
                                                </label>
                                            </div>

                                            <!-- Device Fingerprinting -->
                                            <div class="form-check form-switch mb-3">
                                                <input class="form-check-input" type="checkbox" id="enable_device_fingerprinting" name="enable_device_fingerprinting" {{ $feedbackSettings->enable_device_fingerprinting ? 'checked' : '' }}>
                                                <label class="form-check-label" for="enable_device_fingerprinting">
                                                    <strong>Device Fingerprinting</strong>
                                                    <span class="badge bg-secondary ms-2">Optional</span>
                                                    <small class="d-block text-muted">Track and identify devices to prevent duplicate votes from the same device.</small>
                                                </label>
                                            </div>

                                            <!-- Bot Prevention (hCaptcha) -->
                                            <div class="form-check form-switch mb-0">
                                                <input class="form-check-input" type="checkbox" id="enable_captcha" name="enable_captcha" {{ $feedbackSettings->enable_captcha ? 'checked' : '' }}>
                                                <label class="form-check-label" for="enable_captcha">
                                                    <strong>Prevent Bots (hCaptcha)</strong>
                                                    <small class="d-block text-muted">Enable hCaptcha to prevent automated bot voting.</small>
                                                </label>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="d-flex justify-content-end">
                                        <button type="submit" class="btn btn-primary">
                                            <i class="ti ti-check me-2"></i>Save Feedback Settings
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- Testimonials Tab -->
                    <div class="tab-pane fade" id="testimonials" role="tabpanel">
                        <div class="text-center py-5">
                            <i class="ti ti-clock fs-48 text-muted mb-3"></i>
                            <h5 class="text-muted">Testimonials Settings</h5>
                            <p class="text-muted">Configuration options for testimonials module will be available soon.</p>
                        </div>
                    </div>

                    <!-- Email Template Tab -->
                    <div class="tab-pane fade" id="email" role="tabpanel">
                        <div class="text-center py-5">
                            <i class="ti ti-clock fs-48 text-muted mb-3"></i>
                            <h5 class="text-muted">Email Template Settings</h5>
                            <p class="text-muted">Configuration options for email templates will be available soon.</p>
                        </div>
                    </div>

                    <!-- Knowledge Board Tab -->
                    <div class="tab-pane fade" id="knowledge" role="tabpanel">
                        <div class="text-center py-5">
                            <i class="ti ti-clock fs-48 text-muted mb-3"></i>
                            <h5 class="text-muted">Knowledge Board Settings</h5>
                            <p class="text-muted">Configuration options for knowledge board module will be available soon.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .nav-tabs .nav-link {
        color: #6c757d;
    }
    .nav-tabs .nav-link.active {
        color: #495057;
        font-weight: 600;
    }
    .fs-48 {
        font-size: 48px;
    }
    .card-header h6 {
        font-size: 1rem;
        font-weight: 600;
    }
</style>
@endpush
