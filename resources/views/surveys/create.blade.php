@extends('layouts.inspinia')

@section('title', 'Create Survey')

@push('styles')
<style>
    .survey-type-card {
        border: 2px solid #e5e7eb;
        border-radius: 12px;
        padding: 24px;
        cursor: pointer;
        transition: all 0.2s ease;
        height: 100%;
        background: #ffffff;
    }
    .survey-type-card:hover {
        border-color: #6366f1;
        box-shadow: 0 4px 12px rgba(99, 102, 241, 0.15);
        transform: translateY(-2px);
    }
    .survey-type-card .icon-wrapper {
        width: 56px;
        height: 56px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 16px;
    }
    .survey-type-card .icon-wrapper i {
        font-size: 28px;
    }
    .survey-type-card h5 {
        font-weight: 600;
        margin-bottom: 8px;
        color: #1f2937;
    }
    .survey-type-card p {
        color: #6b7280;
        font-size: 0.9rem;
        margin-bottom: 0;
        line-height: 1.5;
    }
    .survey-type-card .badge {
        font-size: 0.7rem;
        padding: 4px 8px;
        border-radius: 4px;
    }

    /* Type-specific colors */
    .type-nps .icon-wrapper { background-color: #dbeafe; }
    .type-nps .icon-wrapper i { color: #1e40af; }

    .type-csat .icon-wrapper { background-color: #dcfce7; }
    .type-csat .icon-wrapper i { color: #166534; }

    .type-ces .icon-wrapper, .type-open_feedback .icon-wrapper { background-color: #fef3c7; }
    .type-ces .icon-wrapper i, .type-open_feedback .icon-wrapper i { color: #92400e; }

    .type-pmf .icon-wrapper, .type-quick_poll .icon-wrapper { background-color: #f3e8ff; }
    .type-pmf .icon-wrapper i, .type-quick_poll .icon-wrapper i { color: #7c3aed; }

    .type-custom .icon-wrapper, .type-idea_pool .icon-wrapper { background-color: #f1f5f9; }
    .type-custom .icon-wrapper i, .type-idea_pool .icon-wrapper i { color: #475569; }
</style>
@endpush

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-flex justify-content-between align-items-center">
            <div>
                <h4 class="page-title">Create Survey</h4>
                <p class="text-muted fs-14 mb-0">Choose a survey type to get started</p>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('surveys.index') }}" class="btn btn-secondary">
                    <i class="ti ti-arrow-left me-1"></i>Back to Surveys
                </a>
            </div>
        </div>
    </div>
</div>

@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="ti ti-alert-circle me-2"></i>{{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="ti ti-clipboard-list me-2"></i>Select Survey Type
                </h5>
            </div>
            <div class="card-body">
                <div class="row g-4">
                    <!-- NPS Survey -->
                    <div class="col-md-6 col-lg-4">
                        <a href="{{ route('surveys.builder', 'nps') }}" class="text-decoration-none">
                            <div class="survey-type-card type-nps">
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <div class="icon-wrapper">
                                        <i class="ti ti-chart-bar"></i>
                                    </div>
                                    <span class="badge bg-primary">Popular</span>
                                </div>
                                <h5>NPS Survey</h5>
                                <p>Net Promoter Score - Measure customer loyalty and likelihood to recommend your product to others.</p>
                                <div class="mt-3">
                                    <small class="text-muted"><i class="ti ti-scale me-1"></i>Scale: 0-10</small>
                                </div>
                            </div>
                        </a>
                    </div>

                    <!-- CSAT Survey -->
                    <div class="col-md-6 col-lg-4">
                        <a href="{{ route('surveys.builder', 'csat') }}" class="text-decoration-none">
                            <div class="survey-type-card type-csat">
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <div class="icon-wrapper">
                                        <i class="ti ti-mood-happy"></i>
                                    </div>
                                </div>
                                <h5>CSAT Survey</h5>
                                <p>Customer Satisfaction - Measure how satisfied customers are with your product or service experience.</p>
                                <div class="mt-3">
                                    <small class="text-muted"><i class="ti ti-scale me-1"></i>Scale: 1-5</small>
                                </div>
                            </div>
                        </a>
                    </div>

                    <!-- Open Feedback Survey -->
                    <div class="col-md-6 col-lg-4">
                        <a href="{{ route('surveys.builder', 'open_feedback') }}" class="text-decoration-none">
                            <div class="survey-type-card type-custom">
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <div class="icon-wrapper">
                                        <i class="ti ti-message-dots"></i>
                                    </div>
                                </div>
                                <h5>Open Feedback</h5>
                                <p>Collect open-ended feedback from your users to understand their thoughts and suggestions.</p>
                                <div class="mt-3">
                                    <small class="text-muted"><i class="ti ti-writing me-1"></i>Free text</small>
                                </div>
                            </div>
                        </a>
                    </div>

                    <!-- Quick Poll Survey -->
                    <div class="col-md-6 col-lg-4">
                        <a href="{{ route('surveys.builder', 'quick_poll') }}" class="text-decoration-none">
                            <div class="survey-type-card type-pmf">
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <div class="icon-wrapper">
                                        <i class="ti ti-list-check"></i>
                                    </div>
                                </div>
                                <h5>Quick Poll</h5>
                                <p>Create quick polls with multiple choice options to gather instant feedback from users.</p>
                                <div class="mt-3">
                                    <small class="text-muted"><i class="ti ti-list me-1"></i>Multiple choice</small>
                                </div>
                            </div>
                        </a>
                    </div>

                    <!-- Idea Pool Survey -->
                    <div class="col-md-6 col-lg-4">
                        <a href="{{ route('surveys.builder', 'idea_pool') }}" class="text-decoration-none">
                            <div class="survey-type-card type-ces">
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <div class="icon-wrapper">
                                        <i class="ti ti-bulb"></i>
                                    </div>
                                </div>
                                <h5>Idea Pool</h5>
                                <p>Collect and manage ideas from your users to understand what features they want most.</p>
                                <div class="mt-3">
                                    <small class="text-muted"><i class="ti ti-settings me-1"></i>Idea collection</small>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Help Section -->
<div class="row mt-4">
    <div class="col-12">
        <div class="card bg-light border-0">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-auto">
                        <div class="avatar-sm">
                            <span class="avatar-title bg-primary-subtle text-primary rounded-circle fs-4">
                                <i class="ti ti-help"></i>
                            </span>
                        </div>
                    </div>
                    <div class="col">
                        <h6 class="mb-1">Need help choosing?</h6>
                        <p class="text-muted mb-0 small">
                            <strong>NPS</strong> is best for measuring overall loyalty.
                            <strong>CSAT</strong> measures satisfaction after specific interactions.
                            <strong>Open Feedback</strong> collects free-form suggestions.
                            <strong>Quick Poll</strong> gets instant multiple-choice feedback.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Auto-dismiss alerts after 3 seconds
    const dismissibleAlerts = document.querySelectorAll('.alert.alert-dismissible');
    dismissibleAlerts.forEach(function(alert) {
        setTimeout(function() {
            alert.classList.remove('show');
            setTimeout(function() {
                alert.remove();
            }, 150);
        }, 3000);
    });
});
</script>
@endpush
