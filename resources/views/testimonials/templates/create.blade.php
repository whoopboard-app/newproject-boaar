@extends('layouts.inspinia')

@section('title', 'Create Testimonial Template')

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/quill@2.0.0/dist/quill.snow.css" rel="stylesheet">
<style>
    .wizard-steps {
        display: flex;
        justify-content: space-between;
        margin-bottom: 2rem;
        position: relative;
    }

    .wizard-steps::before {
        content: '';
        position: absolute;
        top: 20px;
        left: 0;
        right: 0;
        height: 2px;
        background: #e9ecef;
        z-index: 0;
    }

    .wizard-step {
        flex: 1;
        text-align: center;
        position: relative;
        z-index: 1;
    }

    .wizard-step-circle {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background: white;
        border: 2px solid #e9ecef;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 0.5rem;
        font-weight: 600;
        transition: all 0.3s;
    }

    .wizard-step.active .wizard-step-circle {
        background: #5865F2;
        color: white;
        border-color: #5865F2;
    }

    .wizard-step.completed .wizard-step-circle {
        background: #28a745;
        color: white;
        border-color: #28a745;
    }

    .wizard-step-title {
        font-size: 0.875rem;
        color: #6c757d;
    }

    .wizard-step.active .wizard-step-title {
        color: #5865F2;
        font-weight: 600;
    }

    .step-content {
        display: none;
    }

    .step-content.active {
        display: block;
    }

    .preview-panel {
        background: #f8f9fa;
        border-radius: 8px;
        padding: 2rem;
        position: sticky;
        top: 20px;
        max-height: calc(100vh - 100px);
        overflow-y: auto;
    }

    .preview-card {
        background: white;
        border-radius: 12px;
        padding: 2rem;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }

    .form-toggle {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 1rem;
        background: #f8f9fa;
        border-radius: 6px;
        margin-bottom: 1rem;
    }

    .rating-preview {
        display: flex;
        gap: 0.5rem;
        font-size: 1.5rem;
        color: #ffc107;
    }

    .smile-rating {
        display: flex;
        gap: 0.5rem;
        font-size: 2rem;
    }

    .buttons-panel {
        position: sticky;
        bottom: 0;
        background: white;
        padding: 1.5rem;
        border-top: 1px solid #e9ecef;
        margin: 0 -1.5rem -1.5rem;
        display: flex;
        justify-content: space-between;
        gap: 1rem;
    }
</style>
@endpush

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-flex align-items-center justify-content-between">
            <div>
                <h2 class="mb-0">Create Testimonial Template</h2>
                <p class="text-muted fs-14 mb-0">Set up your testimonial collection form</p>
            </div>
            <a href="{{ route('testimonials.index', ['tab' => 'templates']) }}" class="btn btn-secondary">
                <i class="ti ti-arrow-left me-1"></i> Back to Templates
            </a>
        </div>
    </div>
</div>

<form action="{{ route('testimonial-templates.store') }}" method="POST" enctype="multipart/form-data" id="template-form">
    @csrf

    <div class="row">
        <!-- Left Column - Form -->
        <div class="col-lg-7">
            <div class="card">
                <div class="card-body">
                    <!-- Wizard Steps -->
                    <div class="wizard-steps">
                        <div class="wizard-step active" data-step="1">
                            <div class="wizard-step-circle">1</div>
                            <div class="wizard-step-title">Email Invite</div>
                        </div>
                        <div class="wizard-step" data-step="2">
                            <div class="wizard-step-circle">2</div>
                            <div class="wizard-step-title">Form Fields</div>
                        </div>
                        <div class="wizard-step" data-step="3">
                            <div class="wizard-step-circle">3</div>
                            <div class="wizard-step-title">Testimonial Type</div>
                        </div>
                        <div class="wizard-step" data-step="4">
                            <div class="wizard-step-circle">4</div>
                            <div class="wizard-step-title">Thank You Page</div>
                        </div>
                    </div>

                    <!-- Template Name (Always Visible) -->
                    <div class="mb-4">
                        <label for="name" class="form-label">Template Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" required value="{{ old('name') }}" placeholder="e.g., Customer Feedback Form">
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Step 1: Email Invite -->
                    <div class="step-content active" data-step="1">
                        <h5 class="mb-3">Step 1: Email Invite Settings</h5>

                        <div class="form-toggle">
                            <div>
                                <strong>Enable Email Invite Template</strong>
                                <p class="text-muted mb-0 small">Send automated email invitations</p>
                            </div>
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="enable_email_invite" name="enable_email_invite" value="1" onchange="toggleEmailFields()">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="page_background_color" class="form-label">Page Background Color</label>
                            <input type="color" class="form-control form-control-color" id="page_background_color" name="page_background_color" value="#667eea" title="Choose page background color">
                            <small class="text-muted">This color will be used as the background for your testimonial collection form</small>
                        </div>

                        <div id="email-fields" style="display: none;">
                            <div class="mb-3">
                                <label for="email_background_color" class="form-label">Email Header Background Color</label>
                                <input type="color" class="form-control form-control-color" id="email_background_color" name="email_background_color" value="#667eea" title="Choose email header background color">
                            </div>

                            <div class="mb-3">
                                <label for="cta_button_color" class="form-label">Button Background Color</label>
                                <input type="color" class="form-control form-control-color" id="cta_button_color" name="cta_button_color" value="#667eea" title="Choose button background color">
                            </div>

                            <div class="mb-3">
                                <label for="email_title" class="form-label">Email Title</label>
                                <input type="text" class="form-control" id="email_title" name="email_title" placeholder="Your Feedback Matters!" value="Your Feedback Matters!">
                            </div>

                            <div class="mb-3">
                                <label for="email_subject" class="form-label">Email Subject Line</label>
                                <input type="text" class="form-control" id="email_subject" name="email_subject" placeholder="We'd love to hear your feedback!">
                            </div>

                            <div class="mb-3">
                                <label for="email_content" class="form-label">Email Content</label>
                                <div id="email-editor" style="height: 200px;"></div>
                                <textarea name="email_content" id="email_content" style="display:none;"></textarea>
                            </div>

                            <div class="mb-3">
                                <label for="cta_button_text" class="form-label">Call to Action Button Text</label>
                                <input type="text" class="form-control" id="cta_button_text" name="cta_button_text" placeholder="Share Your Feedback" value="Share Your Feedback">
                            </div>

                            <div class="mb-3">
                                <label for="email_logo" class="form-label">Email Template Logo</label>
                                <input type="file" class="form-control" id="email_logo" name="email_logo" accept="image/*">
                            </div>

                            <div class="mb-3">
                                <label for="promotional_offer" class="form-label">Promotional Offers</label>
                                <input type="text" class="form-control" id="promotional_offer" name="promotional_offer" placeholder="Get 10% off your next purchase!">
                            </div>
                        </div>
                    </div>

                    <!-- Step 2: Form Fields -->
                    <div class="step-content" data-step="2">
                        <h5 class="mb-3">Step 2: Enable Form Fields</h5>
                        <p class="text-muted">Select which fields to display in your testimonial form</p>

                        <div class="mb-3">
                            <label for="form_background_color" class="form-label">Form Page Background Color</label>
                            <input type="color" class="form-control form-control-color" id="form_background_color" name="form_background_color" value="#667eea" title="Choose form page background color">
                            <small class="text-muted">This color will be used as the background for your testimonial form page</small>
                        </div>

                        <div class="form-toggle mb-3">
                            <strong>Full Name</strong>
                            <div class="d-flex align-items-center gap-3">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="field_full_name" name="field_full_name" value="1" onchange="toggleNameFields()">
                                </div>
                                <div class="form-check" id="full_name_required_container" style="display: none;">
                                    <input class="form-check-input" type="checkbox" id="required_full_name" name="required_full_name" value="1">
                                    <label class="form-check-label" for="required_full_name">
                                        <small>Required</small>
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="form-toggle mb-3">
                            <strong>First Name</strong>
                            <div class="d-flex align-items-center gap-3">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="field_first_name" name="field_first_name" value="1" onchange="toggleRequiredOption('first_name')">
                                </div>
                                <div class="form-check" id="first_name_required_container" style="display: none;">
                                    <input class="form-check-input" type="checkbox" id="required_first_name" name="required_first_name" value="1">
                                    <label class="form-check-label" for="required_first_name">
                                        <small>Required</small>
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="form-toggle mb-3">
                            <strong>Last Name</strong>
                            <div class="d-flex align-items-center gap-3">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="field_last_name" name="field_last_name" value="1" onchange="toggleRequiredOption('last_name')">
                                </div>
                                <div class="form-check" id="last_name_required_container" style="display: none;">
                                    <input class="form-check-input" type="checkbox" id="required_last_name" name="required_last_name" value="1">
                                    <label class="form-check-label" for="required_last_name">
                                        <small>Required</small>
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="form-toggle mb-3">
                            <strong>Email Address</strong>
                            <div class="d-flex align-items-center gap-3">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="field_email" name="field_email" value="1" checked onchange="toggleRequiredOption('email')">
                                </div>
                                <div class="form-check" id="email_required_container" style="display: none;">
                                    <input class="form-check-input" type="checkbox" id="required_email" name="required_email" value="1">
                                    <label class="form-check-label" for="required_email">
                                        <small>Required</small>
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="form-toggle mb-3">
                            <strong>Company</strong>
                            <div class="d-flex align-items-center gap-3">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="field_company" name="field_company" value="1" onchange="toggleRequiredOption('company')">
                                </div>
                                <div class="form-check" id="company_required_container" style="display: none;">
                                    <input class="form-check-input" type="checkbox" id="required_company" name="required_company" value="1">
                                    <label class="form-check-label" for="required_company">
                                        <small>Required</small>
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="form-toggle mb-3">
                            <strong>Position/Title</strong>
                            <div class="d-flex align-items-center gap-3">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="field_position" name="field_position" value="1" onchange="toggleRequiredOption('position')">
                                </div>
                                <div class="form-check" id="position_required_container" style="display: none;">
                                    <input class="form-check-input" type="checkbox" id="required_position" name="required_position" value="1">
                                    <label class="form-check-label" for="required_position">
                                        <small>Required</small>
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="form-toggle mb-3">
                            <strong>Social Media URL</strong>
                            <div class="d-flex align-items-center gap-3">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="field_social_url" name="field_social_url" value="1" onchange="toggleRequiredOption('social_url')">
                                </div>
                                <div class="form-check" id="social_url_required_container" style="display: none;">
                                    <input class="form-check-input" type="checkbox" id="required_social_url" name="required_social_url" value="1">
                                    <label class="form-check-label" for="required_social_url">
                                        <small>Required</small>
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="form-toggle mb-3">
                            <strong>Email Signature</strong>
                            <div class="d-flex align-items-center gap-3">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="field_email_signature" name="field_email_signature" value="1" onchange="toggleRequiredOption('email_signature')">
                                </div>
                                <div class="form-check" id="email_signature_required_container" style="display: none;">
                                    <input class="form-check-input" type="checkbox" id="required_email_signature" name="required_email_signature" value="1">
                                    <label class="form-check-label" for="required_email_signature">
                                        <small>Required</small>
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="alert alert-info mt-3">
                            <i class="ti ti-info-circle me-2"></i>
                            <small>Note: If you select "Full Name", the "First Name" and "Last Name" fields will be hidden.</small>
                        </div>
                    </div>

                    <!-- Step 3: Testimonial Type -->
                    <div class="step-content" data-step="3">
                        <h5 class="mb-3">Step 3: Testimonial Capture Type</h5>
                        <p class="text-muted">Choose what type of testimonials to collect</p>

                        <div class="mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="collect_text" name="collect_text" value="1" checked>
                                <label class="form-check-label" for="collect_text">
                                    <strong>Text Testimonials</strong>
                                    <p class="text-muted mb-0 small">Allow users to write their testimonial</p>
                                </label>
                            </div>
                        </div>

                        <div class="mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="collect_video" name="collect_video" value="1" disabled>
                                <label class="form-check-label" for="collect_video">
                                    <strong>Video Testimonials <span class="badge bg-warning">Coming Soon</span></strong>
                                    <p class="text-muted mb-0 small">Allow users to submit video testimonials</p>
                                </label>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="rating_style" class="form-label">Select Rating Style</label>
                            <select class="form-select" id="rating_style" name="rating_style" onchange="updatePreview()">
                                <option value="star">Star Rating ⭐</option>
                                <option value="smile">Smile Rating 😊</option>
                            </select>
                        </div>

                        <div class="form-toggle">
                            <strong>Enable Rating</strong>
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="collect_rating" name="collect_rating" value="1" checked onchange="updatePreview()">
                            </div>
                        </div>
                    </div>

                    <!-- Step 4: Thank You Page -->
                    <div class="step-content" data-step="4">
                        <h5 class="mb-3">Step 4: Thank You Page</h5>

                        <div class="form-toggle">
                            <div>
                                <strong>Enable Thank You Page</strong>
                                <p class="text-muted mb-0 small">Show custom message after submission</p>
                            </div>
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="enable_thankyou" name="enable_thankyou" value="1" onchange="toggleThankYouFields()">
                            </div>
                        </div>

                        <div id="thankyou-fields" style="display: none;">
                            <div class="mb-3">
                                <label for="thankyou_title" class="form-label">Title</label>
                                <input type="text" class="form-control" id="thankyou_title" name="thankyou_title" placeholder="Thank you for your feedback!" value="Thank you for your feedback!">
                            </div>

                            <div class="mb-3">
                                <label for="thankyou_description" class="form-label">Description</label>
                                <textarea class="form-control" id="thankyou_description" name="thankyou_description" rows="3" placeholder="We appreciate you taking the time to share your experience with us.">We appreciate you taking the time to share your experience with us.</textarea>
                            </div>

                            <div class="mb-3">
                                <label for="thankyou_offer" class="form-label">Offer / Coupons</label>
                                <input type="text" class="form-control" id="thankyou_offer" name="thankyou_offer" placeholder="Use code THANKS10 for 10% off!">
                            </div>
                        </div>
                    </div>

                    <!-- Navigation Buttons -->
                    <div class="d-flex justify-content-between mt-4">
                        <button type="button" class="btn btn-secondary" id="prev-btn" onclick="previousStep()" style="display: none;">
                            <i class="ti ti-arrow-left me-1"></i> Previous
                        </button>
                        <button type="button" class="btn btn-primary ms-auto" id="next-btn" onclick="nextStep()">
                            Next <i class="ti ti-arrow-right ms-1"></i>
                        </button>
                    </div>
                </div>

                <!-- Buttons Panel -->
                <div class="buttons-panel">
                    <div>
                        <button type="button" class="btn btn-outline-secondary" onclick="window.location.href='{{ route('testimonials.index', ['tab' => 'templates']) }}'">
                            <i class="ti ti-x me-1"></i> Cancel
                        </button>
                    </div>
                    <div class="d-flex gap-2">
                        <input type="hidden" name="status" id="status-field" value="active">
                        <button type="submit" class="btn btn-secondary" onclick="document.getElementById('status-field').value='inactive'">
                            <i class="ti ti-device-floppy me-1"></i> Save as Draft
                        </button>
                        <button type="button" class="btn btn-primary" onclick="openTestModal()">
                            <i class="ti ti-check me-1"></i> Publish Template
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Column - Preview -->
        <div class="col-lg-5">
            <div class="preview-panel">
                <h6 class="mb-3 text-muted">Live Preview</h6>

                <!-- Email Preview (Step 1) -->
                <div class="preview-card" id="email-preview" style="display: none;">
                    <div id="preview-logo-container" style="padding: 0 2rem 0; text-align: center; background: white; border-radius: 12px 12px 0 0; display: none;">
                        <img id="preview-logo" src="" alt="Logo" style="max-width: 150px; max-height: 60px; object-fit: contain;">
                    </div>

                    <div id="preview-email-header" style="background: #667eea; padding: 2rem; text-align: center;">
                        <h3 id="preview-email-title" style="color: white; margin: 0; background: none;">Your Feedback Matters!</h3>
                    </div>

                    <div style="padding: 2rem; background: white;">
                        <div style="margin-bottom: 1.5rem;">
                            <strong style="color: #667eea;">Subject:</strong>
                            <div id="preview-email-subject" style="margin-top: 0.5rem; color: #333;">
                                We'd love to hear your feedback!
                            </div>
                        </div>

                        <div style="border-top: 1px solid #e9ecef; padding-top: 1.5rem; margin-bottom: 1.5rem;">
                            <div id="preview-email-content" style="color: #555; line-height: 1.6;">
                                <p>Hi there!</p>
                                <p>We hope you're enjoying our product. We'd love to hear about your experience!</p>
                            </div>
                        </div>

                        <div id="preview-promotional-offer-container" style="background: #fff3cd; border-left: 4px solid #ffc107; padding: 1rem; margin-bottom: 1.5rem; border-radius: 4px; display: none;">
                            <strong style="color: #856404;">Special Offer:</strong>
                            <div id="preview-promotional-offer" style="margin-top: 0.5rem; color: #856404;"></div>
                        </div>

                        <div style="text-align: center; margin-top: 2rem;">
                            <a href="#" id="preview-cta-button" style="background: #667eea; color: white; padding: 12px 32px; text-decoration: none; border-radius: 6px; display: inline-block; font-weight: 600;">
                                Share Your Feedback
                            </a>
                        </div>

                        <div style="margin-top: 2rem; padding-top: 1.5rem; border-top: 1px solid #e9ecef; text-align: center; color: #999; font-size: 0.875rem;">
                            Thank you for your time!
                        </div>
                    </div>
                </div>

                <!-- Thank You Page Preview (Step 4) -->
                <div class="preview-card" id="thankyou-preview" style="display: none;">
                    <div style="text-align: center; padding: 3rem 2rem;">
                        <div style="width: 80px; height: 80px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 50%; margin: 0 auto 2rem; display: flex; align-items: center; justify-content: center;">
                            <i class="ti ti-check" style="font-size: 3rem; color: white;"></i>
                        </div>

                        <h2 id="preview-thankyou-title" style="color: #333; margin-bottom: 1rem; font-weight: 600;">
                            Thank you for your feedback!
                        </h2>

                        <p id="preview-thankyou-description" style="color: #666; font-size: 1.125rem; line-height: 1.6; margin-bottom: 2rem;">
                            We appreciate you taking the time to share your experience with us.
                        </p>

                        <div id="preview-thankyou-offer-container" style="display: none; background: linear-gradient(135deg, #ffd89b 0%, #19547b 100%); color: white; padding: 1.5rem; border-radius: 12px; margin-bottom: 2rem;">
                            <div style="font-weight: 600; font-size: 1.125rem; margin-bottom: 0.5rem;">
                                🎁 Special Offer
                            </div>
                            <div id="preview-thankyou-offer" style="font-size: 1rem;"></div>
                        </div>

                        <button type="button" class="btn btn-primary" disabled style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border: none; padding: 12px 32px; border-radius: 6px; color: white; font-weight: 600;">
                            Close
                        </button>
                    </div>
                </div>

                <!-- Form Preview (Steps 2-3) -->
                <div class="preview-card" id="form-preview">
                    <div class="text-center mb-4">
                        <h4 id="preview-title">Testimonial Form</h4>
                        <p class="text-muted" id="preview-subtitle">Share your experience with us</p>
                    </div>

                    <!-- Rating Preview -->
                    <div class="mb-4 text-center" id="preview-rating" style="display: none;">
                        <label class="form-label">Rate your experience</label>
                        <div class="rating-preview" id="star-rating-preview">
                            <i class="ti ti-star-filled"></i>
                            <i class="ti ti-star-filled"></i>
                            <i class="ti ti-star-filled"></i>
                            <i class="ti ti-star-filled"></i>
                            <i class="ti ti-star-filled"></i>
                        </div>
                        <div class="smile-rating" id="smile-rating-preview" style="display: none;">
                            😢 🙁 😐 🙂 😊
                        </div>
                    </div>

                    <!-- Form Fields Preview -->
                    <div id="preview-fields">
                        <div class="mb-3" id="preview-field-full-name" style="display: none;">
                            <label class="form-label">Full Name *</label>
                            <input type="text" class="form-control" placeholder="Enter your full name" disabled>
                        </div>

                        <div class="mb-3" id="preview-field-first-name" style="display: none;">
                            <label class="form-label">First Name *</label>
                            <input type="text" class="form-control" placeholder="Enter your first name" disabled>
                        </div>

                        <div class="mb-3" id="preview-field-last-name" style="display: none;">
                            <label class="form-label">Last Name *</label>
                            <input type="text" class="form-control" placeholder="Enter your last name" disabled>
                        </div>

                        <div class="mb-3" id="preview-field-email">
                            <label class="form-label">Email Address</label>
                            <input type="email" class="form-control" placeholder="your@email.com" disabled>
                        </div>

                        <div class="mb-3" id="preview-field-company" style="display: none;">
                            <label class="form-label">Company</label>
                            <input type="text" class="form-control" placeholder="Your company" disabled>
                        </div>

                        <div class="mb-3" id="preview-field-position" style="display: none;">
                            <label class="form-label">Position</label>
                            <input type="text" class="form-control" placeholder="Your position" disabled>
                        </div>

                        <div class="mb-3" id="preview-field-social-url" style="display: none;">
                            <label class="form-label">Social Media URL</label>
                            <input type="url" class="form-control" placeholder="https://..." disabled>
                        </div>

                        <div class="mb-3" id="preview-field-email-signature" style="display: none;">
                            <label class="form-label">Email Signature</label>
                            <input type="text" class="form-control" placeholder="Enter your email signature" disabled>
                        </div>

                        <!-- Type Selector Preview -->
                        <div id="preview-type-selector" style="display: none; margin-bottom: 1rem;">
                            <div style="display: flex; gap: 1rem;">
                                <div style="flex: 1; padding: 1rem; border: 2px solid #667eea; border-radius: 8px; text-align: center; background: #667eea; color: white;">
                                    <i class="ti ti-file-text" style="font-size: 1.5rem; display: block; margin-bottom: 0.5rem;"></i>
                                    <div style="font-weight: 600;">Text</div>
                                </div>
                                <div id="preview-video-option" style="flex: 1; padding: 1rem; border: 2px solid #e9ecef; border-radius: 8px; text-align: center; opacity: 0.5;">
                                    <i class="ti ti-video" style="font-size: 1.5rem; display: block; margin-bottom: 0.5rem;"></i>
                                    <div style="font-weight: 600;">Video</div>
                                </div>
                            </div>
                        </div>

                        <!-- Text Testimonial -->
                        <div class="mb-3" id="preview-text-testimonial">
                            <label class="form-label">Your Testimonial *</label>
                            <textarea class="form-control" rows="4" placeholder="Share your experience..." disabled></textarea>
                        </div>

                        <!-- Video Testimonial -->
                        <div class="mb-3" id="preview-video-testimonial" style="display: none;">
                            <label class="form-label">Video Testimonial URL *</label>
                            <input type="url" class="form-control" placeholder="Paste your video URL (YouTube, Vimeo, etc.)" disabled>
                            <small class="text-muted">Upload your video to YouTube or Vimeo and paste the link here</small>
                        </div>

                        <button type="button" class="btn btn-primary w-100" disabled>
                            Submit Testimonial
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Test Template Modal -->
    <div class="modal fade" id="testTemplateModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="ti ti-mail-check me-2"></i> Test Your Template
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-info">
                        <i class="ti ti-info-circle me-2"></i>
                        <small>Send a test email to verify your template looks perfect before publishing!</small>
                    </div>

                    <div class="mb-3">
                        <label for="test_email" class="form-label">Please enter your email address</label>
                        <input type="email" class="form-control" id="test_email" placeholder="your@email.com" value="{{ Auth::user()->email }}">
                        <small class="text-muted">We'll send a test invitation email to this address</small>
                    </div>

                    <div id="test-email-result" class="alert" style="display: none;"></div>

                    <div class="d-grid gap-2">
                        <button type="button" class="btn btn-outline-primary" onclick="sendTestEmail()" id="send-test-btn">
                            <i class="ti ti-send me-1"></i> Send Test Email
                        </button>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        Cancel
                    </button>
                    <button type="button" class="btn btn-primary" onclick="publishTemplate()">
                        <i class="ti ti-check me-1"></i> Publish Template
                    </button>
                </div>
            </div>
        </div>
    </div>
</form>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/quill@2.0.0/dist/quill.js"></script>
<script>
    let currentStep = 1;
    let quill;

    // Initialize Quill editor
    document.addEventListener('DOMContentLoaded', function() {
        quill = new Quill('#email-editor', {
            theme: 'snow',
            placeholder: 'Write your email content here...',
            modules: {
                toolbar: [
                    ['bold', 'italic', 'underline'],
                    ['link'],
                    [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                ]
            }
        });

        // Update hidden field on form submit
        document.getElementById('template-form').addEventListener('submit', function() {
            document.getElementById('email_content').value = quill.root.innerHTML;
        });

        // Email preview live updates
        document.getElementById('email_title').addEventListener('input', updateEmailPreview);
        document.getElementById('email_subject').addEventListener('input', updateEmailPreview);
        document.getElementById('cta_button_text').addEventListener('input', updateEmailPreview);
        document.getElementById('email_background_color').addEventListener('input', updateEmailPreview);
        document.getElementById('cta_button_color').addEventListener('input', updateEmailPreview);
        document.getElementById('promotional_offer').addEventListener('input', updateEmailPreview);

        // Update email preview when Quill content changes
        quill.on('text-change', function() {
            updateEmailPreview();
        });

        // Page background color live update
        document.getElementById('page_background_color').addEventListener('input', updatePageBackgroundColor);
        document.getElementById('form_background_color').addEventListener('input', updatePageBackgroundColor);

        // Form fields preview live updates
        document.getElementById('field_full_name').addEventListener('change', function() {
            toggleNameFields();
            updateFormFieldsPreview();
        });
        document.getElementById('field_first_name').addEventListener('change', updateFormFieldsPreview);
        document.getElementById('field_last_name').addEventListener('change', updateFormFieldsPreview);
        document.getElementById('field_email').addEventListener('change', updateFormFieldsPreview);
        document.getElementById('field_company').addEventListener('change', updateFormFieldsPreview);
        document.getElementById('field_position').addEventListener('change', updateFormFieldsPreview);
        document.getElementById('field_social_url').addEventListener('change', updateFormFieldsPreview);
        document.getElementById('field_email_signature').addEventListener('change', updateFormFieldsPreview);

        // Required fields preview live updates
        document.getElementById('required_full_name').addEventListener('change', updateFormFieldsPreview);
        document.getElementById('required_first_name').addEventListener('change', updateFormFieldsPreview);
        document.getElementById('required_last_name').addEventListener('change', updateFormFieldsPreview);
        document.getElementById('required_email').addEventListener('change', updateFormFieldsPreview);
        document.getElementById('required_company').addEventListener('change', updateFormFieldsPreview);
        document.getElementById('required_position').addEventListener('change', updateFormFieldsPreview);
        document.getElementById('required_social_url').addEventListener('change', updateFormFieldsPreview);
        document.getElementById('required_email_signature').addEventListener('change', updateFormFieldsPreview);

        // Testimonial type preview live updates
        document.getElementById('collect_text').addEventListener('change', updateTestimonialTypePreview);
        document.getElementById('collect_video').addEventListener('change', updateTestimonialTypePreview);

        // Thank you page preview live updates
        document.getElementById('thankyou_title').addEventListener('input', updateThankYouPreview);
        document.getElementById('thankyou_description').addEventListener('input', updateThankYouPreview);
        document.getElementById('thankyou_offer').addEventListener('input', updateThankYouPreview);

        // Handle logo upload preview
        document.getElementById('email_logo').addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('preview-logo').src = e.target.result;
                    document.getElementById('preview-logo-container').style.display = 'block';
                };
                reader.readAsDataURL(file);
            } else {
                document.getElementById('preview-logo-container').style.display = 'none';
            }
        });

        updatePreview();
        updatePreviewPanel(); // Show initial preview
        updateFormFieldsPreview(); // Initialize form fields preview
        updateTestimonialTypePreview(); // Initialize testimonial type preview
        updateThankYouPreview(); // Initialize thank you page preview
        updatePageBackgroundColor(); // Initialize page background color preview
    });

    function nextStep() {
        if (currentStep < 4) {
            currentStep++;
            updateStepDisplay();
        }
    }

    function previousStep() {
        if (currentStep > 1) {
            currentStep--;
            updateStepDisplay();
        }
    }

    function updateStepDisplay() {
        // Update step indicators
        document.querySelectorAll('.wizard-step').forEach((step, index) => {
            step.classList.remove('active', 'completed');
            if (index + 1 < currentStep) {
                step.classList.add('completed');
            } else if (index + 1 === currentStep) {
                step.classList.add('active');
            }
        });

        // Update step content
        document.querySelectorAll('.step-content').forEach(content => {
            content.classList.remove('active');
        });
        document.querySelector(`.step-content[data-step="${currentStep}"]`).classList.add('active');

        // Update navigation buttons
        document.getElementById('prev-btn').style.display = currentStep === 1 ? 'none' : 'block';
        document.getElementById('next-btn').style.display = currentStep === 4 ? 'none' : 'block';

        // Update preview panel based on step
        updatePreviewPanel();

        // Update background color based on current step
        updatePageBackgroundColor();
    }

    function updatePreviewPanel() {
        const emailPreview = document.getElementById('email-preview');
        const formPreview = document.getElementById('form-preview');
        const thankyouPreview = document.getElementById('thankyou-preview');
        const ratingPreview = document.getElementById('preview-rating');

        // Form field elements
        const formFields = [
            'preview-field-full-name',
            'preview-field-first-name',
            'preview-field-last-name',
            'preview-field-email',
            'preview-field-company',
            'preview-field-position',
            'preview-field-social-url',
            'preview-field-email-signature'
        ];

        if (currentStep === 1) {
            // Show email preview in step 1
            emailPreview.style.display = 'block';
            formPreview.style.display = 'none';
            thankyouPreview.style.display = 'none';
        } else if (currentStep === 2) {
            // Show form preview in step 2 (with form fields, without rating)
            emailPreview.style.display = 'none';
            formPreview.style.display = 'block';
            thankyouPreview.style.display = 'none';
            ratingPreview.style.display = 'none';

            // Show form fields based on user selection
            updateFormFieldsPreview();
        } else if (currentStep === 3) {
            // Show form preview in step 3 (only rating and testimonial, no form fields)
            emailPreview.style.display = 'none';
            formPreview.style.display = 'block';
            thankyouPreview.style.display = 'none';

            // Hide all form fields in step 3
            formFields.forEach(fieldId => {
                const field = document.getElementById(fieldId);
                if (field) field.style.display = 'none';
            });

            updatePreview(); // Update rating visibility based on settings
        } else if (currentStep === 4) {
            // Show thank you page preview in step 4
            emailPreview.style.display = 'none';
            formPreview.style.display = 'none';
            thankyouPreview.style.display = 'block';
        }
    }

    function toggleEmailFields() {
        const enabled = document.getElementById('enable_email_invite').checked;
        document.getElementById('email-fields').style.display = enabled ? 'block' : 'none';
    }

    function toggleThankYouFields() {
        const enabled = document.getElementById('enable_thankyou').checked;
        document.getElementById('thankyou-fields').style.display = enabled ? 'block' : 'none';
    }

    function toggleRequiredOption(fieldName) {
        const fieldCheckbox = document.getElementById('field_' + fieldName);
        const requiredContainer = document.getElementById(fieldName + '_required_container');

        if (fieldCheckbox && requiredContainer) {
            requiredContainer.style.display = fieldCheckbox.checked ? 'block' : 'none';
            if (!fieldCheckbox.checked) {
                // Uncheck required checkbox when field is disabled
                const requiredCheckbox = document.getElementById('required_' + fieldName);
                if (requiredCheckbox) {
                    requiredCheckbox.checked = false;
                }
            }
        }

        // Update preview for email_signature
        if (fieldName === 'email_signature') {
            updateFormFieldsPreview();
        }
    }

    function toggleNameFields() {
        const fullNameEnabled = document.getElementById('field_full_name').checked;
        const fullNameRequiredContainer = document.getElementById('full_name_required_container');
        const firstNameRequiredContainer = document.getElementById('first_name_required_container');
        const lastNameRequiredContainer = document.getElementById('last_name_required_container');

        if (fullNameEnabled) {
            document.getElementById('field_first_name').checked = false;
            document.getElementById('field_last_name').checked = false;
            document.getElementById('field_first_name').disabled = true;
            document.getElementById('field_last_name').disabled = true;
            fullNameRequiredContainer.style.display = 'block';
            firstNameRequiredContainer.style.display = 'none';
            lastNameRequiredContainer.style.display = 'none';
        } else {
            document.getElementById('field_first_name').disabled = false;
            document.getElementById('field_last_name').disabled = false;
            fullNameRequiredContainer.style.display = 'none';
        }
    }

    function updateFormFieldsPreview() {
        // Update form field visibility in preview
        const fields = [
            'full_name', 'first_name', 'last_name', 'email',
            'company', 'position', 'social_url', 'email_signature'
        ];

        fields.forEach(field => {
            const checkbox = document.getElementById('field_' + field);
            const previewField = document.getElementById('preview-field-' + field);

            if (checkbox && previewField) {
                previewField.style.display = checkbox.checked ? 'block' : 'none';

                // Update label to show required indicator
                const requiredCheckbox = document.getElementById('required_' + field);
                const label = previewField.querySelector('.form-label');
                if (label && requiredCheckbox) {
                    const fieldName = label.textContent.replace(' *', '');
                    label.textContent = requiredCheckbox.checked ? fieldName + ' *' : fieldName;
                }
            }
        });
    }

    function updateTestimonialTypePreview() {
        const collectText = document.getElementById('collect_text').checked;
        const collectVideo = document.getElementById('collect_video').checked;

        const typeSelector = document.getElementById('preview-type-selector');
        const textField = document.getElementById('preview-text-testimonial');
        const videoField = document.getElementById('preview-video-testimonial');
        const videoOption = document.getElementById('preview-video-option');

        // Show type selector if both are enabled
        if (collectText && collectVideo) {
            typeSelector.style.display = 'block';
            textField.style.display = 'block';
            videoField.style.display = 'none';
            videoOption.style.opacity = '1';
            videoOption.style.border = '2px solid #e9ecef';
        }
        // Show only text if only text is enabled
        else if (collectText && !collectVideo) {
            typeSelector.style.display = 'none';
            textField.style.display = 'block';
            videoField.style.display = 'none';
        }
        // Show only video if only video is enabled (when it becomes available)
        else if (!collectText && collectVideo) {
            typeSelector.style.display = 'none';
            textField.style.display = 'none';
            videoField.style.display = 'block';
        }
        // Show text by default if neither is checked (fallback)
        else {
            typeSelector.style.display = 'none';
            textField.style.display = 'block';
            videoField.style.display = 'none';
        }
    }

    function updateThankYouPreview() {
        // Update thank you title
        const title = document.getElementById('thankyou_title').value;
        document.getElementById('preview-thankyou-title').textContent = title || 'Thank you for your feedback!';

        // Update thank you description
        const description = document.getElementById('thankyou_description').value;
        document.getElementById('preview-thankyou-description').textContent = description || 'We appreciate you taking the time to share your experience with us.';

        // Update thank you offer
        const offer = document.getElementById('thankyou_offer').value;
        if (offer) {
            document.getElementById('preview-thankyou-offer').textContent = offer;
            document.getElementById('preview-thankyou-offer-container').style.display = 'block';
        } else {
            document.getElementById('preview-thankyou-offer-container').style.display = 'none';
        }
    }

    function updateEmailPreview() {
        // Update email title
        const title = document.getElementById('email_title').value;
        document.getElementById('preview-email-title').textContent = title || 'Your Feedback Matters!';

        // Update email header background color
        const bgColor = document.getElementById('email_background_color').value;
        document.getElementById('preview-email-header').style.background = bgColor;

        // Update CTA button background color (separate from header)
        const buttonColor = document.getElementById('cta_button_color').value;
        document.getElementById('preview-cta-button').style.background = buttonColor;

        // Update email subject
        const subject = document.getElementById('email_subject').value;
        document.getElementById('preview-email-subject').textContent = subject || 'We\'d love to hear your feedback!';

        // Update CTA button text
        const ctaText = document.getElementById('cta_button_text').value;
        document.getElementById('preview-cta-button').textContent = ctaText || 'Share Your Feedback';

        // Update email content from Quill editor
        const content = quill.root.innerHTML;
        if (content && content.trim() !== '<p><br></p>') {
            document.getElementById('preview-email-content').innerHTML = content;
        } else {
            document.getElementById('preview-email-content').innerHTML = '<p>Hi there!</p><p>We hope you\'re enjoying our product. We\'d love to hear about your experience!</p>';
        }

        // Update promotional offer
        const offer = document.getElementById('promotional_offer').value;
        if (offer) {
            document.getElementById('preview-promotional-offer').textContent = offer;
            document.getElementById('preview-promotional-offer-container').style.display = 'block';
        } else {
            document.getElementById('preview-promotional-offer-container').style.display = 'none';
        }
    }

    function updatePreview() {
        // Update rating preview
        const ratingEnabled = document.getElementById('collect_rating').checked;
        const ratingStyle = document.getElementById('rating_style').value;

        document.getElementById('preview-rating').style.display = ratingEnabled ? 'block' : 'none';

        if (ratingStyle === 'star') {
            document.getElementById('star-rating-preview').style.display = 'flex';
            document.getElementById('smile-rating-preview').style.display = 'none';
        } else {
            document.getElementById('star-rating-preview').style.display = 'none';
            document.getElementById('smile-rating-preview').style.display = 'flex';
        }
    }

    function updatePageBackgroundColor() {
        const previewPanel = document.querySelector('.preview-panel');
        if (previewPanel) {
            let bgColor;
            // Use different background color based on current step
            if (currentStep === 1) {
                bgColor = document.getElementById('page_background_color').value;
            } else {
                bgColor = document.getElementById('form_background_color').value;
            }
            previewPanel.style.background = bgColor;
        }
    }

    // Test Template Modal Functions
    let testModal;

    function openTestModal() {
        testModal = new bootstrap.Modal(document.getElementById('testTemplateModal'));
        testModal.show();
    }

    function sendTestEmail() {
        const email = document.getElementById('test_email').value;
        const sendBtn = document.getElementById('send-test-btn');
        const resultDiv = document.getElementById('test-email-result');

        // Validate email
        if (!email) {
            resultDiv.className = 'alert alert-danger';
            resultDiv.textContent = 'Please enter an email address';
            resultDiv.style.display = 'block';
            return;
        }

        // Basic email validation
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailRegex.test(email)) {
            resultDiv.className = 'alert alert-danger';
            resultDiv.textContent = 'Please enter a valid email address';
            resultDiv.style.display = 'block';
            return;
        }

        // Hide previous results
        resultDiv.style.display = 'none';

        // Disable button and show loading
        sendBtn.disabled = true;
        sendBtn.innerHTML = '<i class="ti ti-loader me-1"></i> Sending...';

        // Prepare form data
        const formData = {
            email: email,
            name: document.getElementById('name').value,
            email_title: document.getElementById('email_title').value,
            email_subject: document.getElementById('email_subject').value,
            email_content: quill.root.innerHTML,
            email_background_color: document.getElementById('email_background_color').value,
            cta_button_color: document.getElementById('cta_button_color').value,
            cta_button_text: document.getElementById('cta_button_text').value,
            promotional_offer: document.getElementById('promotional_offer').value,
            page_background_color: document.getElementById('page_background_color').value,
            form_background_color: document.getElementById('form_background_color').value,
            // Form field settings
            field_full_name: document.getElementById('field_full_name').checked,
            field_first_name: document.getElementById('field_first_name').checked,
            field_last_name: document.getElementById('field_last_name').checked,
            field_email: document.getElementById('field_email').checked,
            field_company: document.getElementById('field_company').checked,
            field_position: document.getElementById('field_position').checked,
            field_social_url: document.getElementById('field_social_url').checked,
            field_email_signature: document.getElementById('field_email_signature').checked,
            // Required field settings
            required_full_name: document.getElementById('required_full_name').checked,
            required_first_name: document.getElementById('required_first_name').checked,
            required_last_name: document.getElementById('required_last_name').checked,
            required_email: document.getElementById('required_email').checked,
            required_company: document.getElementById('required_company').checked,
            required_position: document.getElementById('required_position').checked,
            required_social_url: document.getElementById('required_social_url').checked,
            required_email_signature: document.getElementById('required_email_signature').checked,
            // Testimonial type settings
            collect_text: document.getElementById('collect_text').checked,
            collect_video: document.getElementById('collect_video').checked,
            collect_rating: document.getElementById('collect_rating').checked,
            rating_style: document.getElementById('rating_style').value,
            _token: '{{ csrf_token() }}'
        };

        // Get logo as base64 if uploaded
        const logoImg = document.getElementById('preview-logo');
        if (logoImg && logoImg.src && logoImg.src.startsWith('data:')) {
            formData.email_logo_data = logoImg.src;
        }

        // Send AJAX request
        fetch('{{ route("testimonial-templates.send-test-email") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            },
            body: JSON.stringify(formData)
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                resultDiv.className = 'alert alert-success';
                resultDiv.innerHTML = '<i class="ti ti-check-circle me-2"></i>' + data.message;
            } else {
                resultDiv.className = 'alert alert-danger';
                resultDiv.innerHTML = '<i class="ti ti-alert-circle me-2"></i>' + (data.message || 'Failed to send test email');
            }
            resultDiv.style.display = 'block';
            sendBtn.disabled = false;
            sendBtn.innerHTML = '<i class="ti ti-send me-1"></i> Send Test Email';
        })
        .catch(error => {
            resultDiv.className = 'alert alert-danger';
            resultDiv.innerHTML = '<i class="ti ti-alert-circle me-2"></i>An error occurred while sending the test email. Please try again.';
            resultDiv.style.display = 'block';
            sendBtn.disabled = false;
            sendBtn.innerHTML = '<i class="ti ti-send me-1"></i> Send Test Email';
        });
    }

    function publishTemplate() {
        // Update hidden status field and submit the form
        document.getElementById('status-field').value = 'active';

        // Update email content from Quill before submitting
        document.getElementById('email_content').value = quill.root.innerHTML;

        // Submit the form
        document.getElementById('template-form').submit();
    }
</script>
@endpush
