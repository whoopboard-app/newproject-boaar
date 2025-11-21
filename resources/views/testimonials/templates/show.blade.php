@extends('layouts.inspinia')

@section('title', 'Template Details - ' . $template->name)

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-flex align-items-center justify-content-between">
            <div>
                <h2 class="mb-0">{{ $template->name }}</h2>
                <p class="text-muted fs-14 mb-0">
                    <span class="badge bg-{{ $template->status === 'active' ? 'success' : 'secondary' }}">
                        {{ ucfirst($template->status) }}
                    </span>
                    <span class="ms-2">{{ $template->testimonials_count }} submissions</span>
                </p>
            </div>
            <div>
                <a href="{{ route('testimonial-templates.edit', $template) }}" class="btn btn-primary">
                    <i class="ti ti-edit me-1"></i> Edit Template
                </a>
                <a href="{{ route('testimonials.index', ['tab' => 'templates']) }}" class="btn btn-secondary">
                    <i class="ti ti-arrow-left me-1"></i> Back to Templates
                </a>
            </div>
        </div>
    </div>
</div>

@if(session('show_embed_code') || request('show_embed'))
<!-- Embed Code Section -->
<div class="row">
    <div class="col-12">
        <div class="alert alert-success alert-dismissible fade show">
            <h5 class="alert-heading">
                <i class="ti ti-check-circle me-2"></i> Template Published Successfully!
            </h5>
            <p>Your testimonial template is now live. Use the URL or embed code below to collect testimonials.</p>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="ti ti-link me-2"></i> Unique URL</h5>
            </div>
            <div class="card-body">
                <p class="text-muted">Share this link in your emails or social media</p>
                <div class="input-group">
                    <input type="text" class="form-control" id="unique-url" value="{{ route('testimonials.public.form', $template->unique_url) }}" readonly>
                    <button class="btn btn-primary" type="button" onclick="copyToClipboard('unique-url')">
                        <i class="ti ti-copy me-1"></i> Copy
                    </button>
                </div>
                <div class="mt-3">
                    <a href="{{ route('testimonials.public.form', $template->unique_url) }}" target="_blank" class="btn btn-sm btn-outline-primary">
                        <i class="ti ti-external-link me-1"></i> Preview Form
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-6">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="ti ti-code me-2"></i> Embed Code</h5>
            </div>
            <div class="card-body">
                <p class="text-muted">Add this code to your website to embed the testimonial form</p>
                <div class="position-relative">
                    <textarea class="form-control font-monospace" id="embed-code" rows="6" readonly><iframe src="{{ route('testimonials.public.form', $template->unique_url) }}" width="100%" height="600" frameborder="0" style="border-radius: 8px;"></iframe></textarea>
                    <button class="btn btn-primary btn-sm position-absolute top-0 end-0 m-2" onclick="copyToClipboard('embed-code')">
                        <i class="ti ti-copy me-1"></i> Copy
                    </button>
                </div>
                <div class="mt-3">
                    <small class="text-muted">
                        <i class="ti ti-info-circle me-1"></i>
                        Paste this code into your website's HTML where you want the testimonial form to appear
                    </small>
                </div>
            </div>
        </div>
    </div>
</div>
@endif

<!-- Template Settings Overview -->
<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Template Settings</h5>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <!-- Email Invite -->
                    <div class="col-12">
                        <h6 class="border-bottom pb-2">Email Invite</h6>
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <span>Email Invitations Enabled</span>
                            <span class="badge bg-{{ $template->enable_email_invite ? 'success' : 'secondary' }}">
                                {{ $template->enable_email_invite ? 'Yes' : 'No' }}
                            </span>
                        </div>
                        @if($template->enable_email_invite)
                            <div class="ms-3">
                                <small class="text-muted">Subject:</small> {{ $template->email_subject ?? 'Not set' }}<br>
                                @if($template->promotional_offer)
                                    <small class="text-muted">Offer:</small> {{ $template->promotional_offer }}
                                @endif
                            </div>
                        @endif
                    </div>

                    <!-- Form Fields -->
                    <div class="col-12">
                        <h6 class="border-bottom pb-2">Form Fields</h6>
                        <div class="row">
                            <div class="col-md-6">
                                @foreach([
                                    'field_full_name' => 'Full Name',
                                    'field_first_name' => 'First Name',
                                    'field_last_name' => 'Last Name',
                                    'field_email' => 'Email Address'
                                ] as $field => $label)
                                    <div class="d-flex align-items-center mb-2">
                                        <i class="ti ti-{{ $template->$field ? 'check' : 'x' }} me-2 text-{{ $template->$field ? 'success' : 'muted' }}"></i>
                                        <span>{{ $label }}</span>
                                    </div>
                                @endforeach
                            </div>
                            <div class="col-md-6">
                                @foreach([
                                    'field_company' => 'Company',
                                    'field_position' => 'Position',
                                    'field_social_url' => 'Social Media URL'
                                ] as $field => $label)
                                    <div class="d-flex align-items-center mb-2">
                                        <i class="ti ti-{{ $template->$field ? 'check' : 'x' }} me-2 text-{{ $template->$field ? 'success' : 'muted' }}"></i>
                                        <span>{{ $label }}</span>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <!-- Testimonial Type -->
                    <div class="col-12">
                        <h6 class="border-bottom pb-2">Testimonial Type</h6>
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <span>Text Testimonials</span>
                            <span class="badge bg-{{ $template->collect_text ? 'success' : 'secondary' }}">
                                {{ $template->collect_text ? 'Enabled' : 'Disabled' }}
                            </span>
                        </div>
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <span>Video Testimonials</span>
                            <span class="badge bg-{{ $template->collect_video ? 'success' : 'secondary' }}">
                                {{ $template->collect_video ? 'Enabled' : 'Disabled' }}
                            </span>
                        </div>
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <span>Rating Collection</span>
                            <span class="badge bg-{{ $template->collect_rating ? 'success' : 'secondary' }}">
                                {{ $template->collect_rating ? 'Enabled' : 'Disabled' }}
                                @if($template->collect_rating)
                                    ({{ ucfirst($template->rating_style) }})
                                @endif
                            </span>
                        </div>
                    </div>

                    <!-- Thank You Page -->
                    <div class="col-12">
                        <h6 class="border-bottom pb-2">Thank You Page</h6>
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <span>Custom Thank You Page</span>
                            <span class="badge bg-{{ $template->enable_thankyou ? 'success' : 'secondary' }}">
                                {{ $template->enable_thankyou ? 'Enabled' : 'Disabled' }}
                            </span>
                        </div>
                        @if($template->enable_thankyou)
                            <div class="ms-3">
                                <small class="text-muted">Title:</small> {{ $template->thankyou_title }}<br>
                                @if($template->thankyou_offer)
                                    <small class="text-muted">Offer:</small> {{ $template->thankyou_offer }}
                                @endif
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <!-- Quick Actions -->
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Quick Actions</h5>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="{{ route('testimonials.public.form', $template->unique_url) }}" target="_blank" class="btn btn-outline-primary">
                        <i class="ti ti-external-link me-2"></i> Preview Form
                    </a>
                    <button class="btn btn-outline-secondary" onclick="copyToClipboard('unique-url')">
                        <i class="ti ti-copy me-2"></i> Copy URL
                    </button>
                    <button class="btn btn-outline-secondary" onclick="copyToClipboard('embed-code')">
                        <i class="ti ti-code me-2"></i> Copy Embed Code
                    </button>
                    <a href="{{ route('testimonials.index') }}" class="btn btn-outline-info">
                        <i class="ti ti-messages me-2"></i> View Submissions
                    </a>
                </div>
            </div>
        </div>

        <!-- Statistics -->
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Statistics</h5>
            </div>
            <div class="card-body">
                <div class="text-center mb-3">
                    <h2 class="mb-0">{{ $template->testimonials->count() }}</h2>
                    <small class="text-muted">Total Submissions</small>
                </div>
                <hr>
                <div class="row text-center">
                    <div class="col-4">
                        <h5 class="mb-0 text-success">{{ $template->testimonials->where('status', 'active')->count() }}</h5>
                        <small class="text-muted">Active</small>
                    </div>
                    <div class="col-4">
                        <h5 class="mb-0 text-warning">{{ $template->testimonials->where('status', 'draft')->count() }}</h5>
                        <small class="text-muted">Draft</small>
                    </div>
                    <div class="col-4">
                        <h5 class="mb-0 text-secondary">{{ $template->testimonials->where('status', 'inactive')->count() }}</h5>
                        <small class="text-muted">Inactive</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function copyToClipboard(elementId) {
    const element = document.getElementById(elementId);
    element.select();
    element.setSelectionRange(0, 99999); // For mobile devices

    navigator.clipboard.writeText(element.value).then(function() {
        // Show success message
        const btn = event.target.closest('button');
        const originalHTML = btn.innerHTML;
        btn.innerHTML = '<i class="ti ti-check me-1"></i> Copied!';
        btn.classList.add('btn-success');
        btn.classList.remove('btn-primary');

        setTimeout(function() {
            btn.innerHTML = originalHTML;
            btn.classList.remove('btn-success');
            btn.classList.add('btn-primary');
        }, 2000);
    });
}
</script>
@endpush
