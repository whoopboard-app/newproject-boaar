@extends('layouts.inspinia')

@section('title', isset($survey) ? 'Edit Survey' : 'Create ' . ucfirst(str_replace('_', ' ', $type)) . ' Survey')

@push('styles')
<style>
    /* Preview Panel Styles */
    .preview-container {
        background: #f8fafc;
        border-radius: 12px;
        padding: 24px;
        min-height: 400px;
        position: sticky;
        top: 100px;
    }
    .preview-widget {
        background: #ffffff;
        border-radius: 12px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        padding: 24px;
        max-width: 400px;
        margin: 0 auto;
    }
    .preview-widget .survey-question {
        font-size: 1.1rem;
        font-weight: 600;
        color: #1f2937;
        margin-bottom: 20px;
        line-height: 1.4;
    }

    /* NPS Scale Preview */
    .nps-scale {
        display: flex;
        gap: 4px;
        justify-content: space-between;
        margin-bottom: 8px;
    }
    .nps-scale .score-btn {
        width: 32px;
        height: 32px;
        border: 1px solid #e5e7eb;
        border-radius: 6px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.85rem;
        font-weight: 500;
        color: #6b7280;
        background: #fff;
        cursor: pointer;
        transition: all 0.15s ease;
    }
    .nps-scale .score-btn:hover {
        border-color: var(--accent-color, #5865F2);
        color: var(--accent-color, #5865F2);
    }
    .nps-scale .score-btn.selected {
        background: var(--accent-color, #5865F2);
        border-color: var(--accent-color, #5865F2);
        color: #fff;
    }
    .scale-labels {
        display: flex;
        justify-content: space-between;
        font-size: 0.75rem;
        color: #9ca3af;
        margin-bottom: 20px;
    }

    /* CSAT Scale */
    .rating-scale {
        display: flex;
        gap: 8px;
        justify-content: center;
        margin-bottom: 8px;
    }
    .rating-scale .rating-btn {
        width: 48px;
        height: 48px;
        border: 2px solid #e5e7eb;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.25rem;
        cursor: pointer;
        transition: all 0.15s ease;
        background: #fff;
    }

    /* Follow-up textarea */
    .preview-textarea {
        width: 100%;
        border: 1px solid #e5e7eb;
        border-radius: 8px;
        padding: 12px;
        font-size: 0.9rem;
        resize: none;
        margin-top: 16px;
    }

    /* Submit button */
    .preview-submit {
        width: 100%;
        padding: 12px;
        border: none;
        border-radius: 8px;
        font-weight: 600;
        color: #fff;
        cursor: pointer;
        margin-top: 16px;
        transition: opacity 0.15s ease;
    }

    /* Accordion Styles */
    .settings-accordion .accordion-item {
        border: 1px solid #e5e7eb;
        border-radius: 12px !important;
        margin-bottom: 16px;
        overflow: hidden;
    }
    .settings-accordion .accordion-header {
        background: #fff;
    }
    .settings-accordion .accordion-button {
        padding: 20px 24px;
        font-weight: 600;
        font-size: 1rem;
        color: #1f2937;
        background: #fff;
        box-shadow: none;
    }
    .settings-accordion .accordion-button:not(.collapsed) {
        background: #f8fafc;
        color: #1f2937;
    }
    .settings-accordion .accordion-button::after {
        background-size: 16px;
    }
    .settings-accordion .accordion-body {
        padding: 24px;
        background: #fff;
        border-top: 1px solid #e5e7eb;
    }
    .accordion-header-content {
        display: flex;
        align-items: center;
        gap: 12px;
        flex: 1;
    }
    .accordion-header-content i {
        font-size: 1.25rem;
        color: #6b7280;
    }
    .accordion-header-content .header-text h6 {
        margin: 0;
        font-weight: 600;
    }
    .accordion-header-content .header-text p {
        margin: 0;
        font-size: 0.85rem;
        color: #6b7280;
        font-weight: 400;
    }
    .accordion-status-badge {
        margin-left: auto;
        margin-right: 16px;
    }

    /* Form section styles */
    .form-section-title {
        font-weight: 600;
        color: #374151;
        margin-bottom: 16px;
        padding-bottom: 8px;
        border-bottom: 1px solid #e5e7eb;
    }

    /* Color picker */
    .color-picker-wrapper {
        display: flex;
        align-items: center;
        gap: 12px;
    }
    .color-picker-wrapper input[type="color"] {
        width: 48px;
        height: 48px;
        border: 2px solid #e5e7eb;
        border-radius: 8px;
        cursor: pointer;
        padding: 4px;
    }

    /* Survey type badge */
    .survey-type-badge-lg {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 8px 16px;
        border-radius: 8px;
        font-weight: 600;
    }
    .type-nps-bg { background-color: #dbeafe; color: #1e40af; }
    .type-csat-bg { background-color: #dcfce7; color: #166534; }
    .type-open_feedback-bg { background-color: #fef3c7; color: #92400e; }
    .type-quick_poll-bg { background-color: #f3e8ff; color: #7c3aed; }
    .type-idea_pool-bg { background-color: #f1f5f9; color: #475569; }

    /* URL List */
    .url-list-item {
        display: flex;
        gap: 12px;
        align-items: center;
        padding: 12px;
        background: #f8fafc;
        border-radius: 8px;
        margin-bottom: 8px;
    }
    .url-list-item .btn-remove {
        color: #ef4444;
        padding: 4px 8px;
    }

    /* Radio Cards */
    .radio-card {
        border: 2px solid #e5e7eb;
        border-radius: 8px;
        padding: 16px;
        cursor: pointer;
        transition: all 0.2s ease;
    }
    .radio-card:hover {
        border-color: #d1d5db;
    }
    .radio-card.selected {
        border-color: #5865F2;
        background: #f8fafc;
    }
    .radio-card input[type="radio"] {
        margin-right: 12px;
    }

    /* Conditional Fields */
    .conditional-field {
        display: none;
        margin-top: 16px;
        padding: 16px;
        background: #f8fafc;
        border-radius: 8px;
        border-left: 3px solid #5865F2;
    }
    .conditional-field.show {
        display: block;
    }
</style>
@endpush

@section('content')
<form method="POST" action="{{ isset($survey) ? route('surveys.update', $survey) : route('surveys.store') }}" id="surveyForm">
    @csrf
    @if(isset($survey))
        @method('PUT')
    @endif
    <input type="hidden" name="type" value="{{ $type }}">

    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center gap-3">
                    <div>
                        <h4 class="page-title mb-1">{{ isset($survey) ? 'Edit Survey' : 'Create Survey' }}</h4>
                        <p class="text-muted fs-14 mb-0">
                            <span class="survey-type-badge-lg type-{{ $type }}-bg">
                                <i class="ti ti-{{ $surveyTypes[$type]['icon'] ?? 'clipboard-list' }}"></i>
                                {{ $surveyTypes[$type]['name'] ?? ucfirst(str_replace('_', ' ', $type)) }}
                            </span>
                        </p>
                    </div>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('surveys.index') }}" class="btn btn-secondary">
                        <i class="ti ti-arrow-left me-1"></i>Back
                    </a>
                    <button type="button" onclick="submitSurveyForm('draft')" class="btn btn-outline-primary">
                        <i class="ti ti-file me-1"></i>Save as Draft
                    </button>
                    <button type="button" onclick="submitSurveyForm('published')" class="btn btn-primary">
                        <i class="ti ti-send me-1"></i>{{ isset($survey) ? 'Update & Publish' : 'Create & Publish' }}
                    </button>
                </div>
                <input type="hidden" name="status" id="surveyStatus" value="draft">
            </div>
        </div>
    </div>

    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="ti ti-alert-circle me-2"></i>
            <strong>Please fix the following errors:</strong>
            <ul class="mb-0 mt-2">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row">
        <!-- Left Column: Form -->
        <div class="col-lg-7">
            <div class="accordion settings-accordion" id="surveySettingsAccordion">

                <!-- General Section -->
                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#generalSection">
                            <div class="accordion-header-content">
                                <i class="ti ti-settings"></i>
                                <div class="header-text">
                                    <h6>General</h6>
                                    <p>Configure your survey</p>
                                </div>
                            </div>
                            <span class="accordion-status-badge badge {{ isset($survey) && $survey->status === 'published' ? 'bg-success' : 'bg-secondary' }}">
                                {{ isset($survey) ? ucfirst($survey->status) : 'Draft' }}
                            </span>
                        </button>
                    </h2>
                    <div id="generalSection" class="accordion-collapse collapse show" data-bs-parent="#surveySettingsAccordion">
                        <div class="accordion-body">
                            <!-- Title -->
                            <div class="mb-4">
                                <label class="form-label">Survey Title <span class="text-danger">*</span></label>
                                <input type="text"
                                       name="title"
                                       class="form-control @error('title') is-invalid @enderror"
                                       value="{{ old('title', $survey->title ?? '') }}"
                                       placeholder="e.g., Post-Purchase NPS Survey"
                                       required>
                                @error('title')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Main Question -->
                            <div class="mb-4">
                                <label class="form-label">Main Question <span class="text-danger">*</span></label>
                                <textarea name="general_settings[question]"
                                          class="form-control"
                                          rows="2"
                                          id="mainQuestion"
                                          placeholder="{{ $defaultSettings['question'] ?? 'Enter your main survey question' }}">{{ old('general_settings.question', $survey->general_settings['question'] ?? $defaultSettings['question'] ?? '') }}</textarea>
                            </div>

                            <!-- Feedback Form Option -->
                            <div class="mb-4">
                                <label class="form-label">Feedback Form</label>
                                <p class="text-muted small mb-2">Ask for feedback after rating</p>
                                <div class="d-flex gap-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="feedback_form_option" id="feedbackOptional" value="optional"
                                            {{ old('feedback_form_option', $survey->feedback_form_option ?? 'optional') === 'optional' ? 'checked' : '' }}>
                                        <label class="form-check-label" for="feedbackOptional">Optional</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="feedback_form_option" id="feedbackRequired" value="required"
                                            {{ old('feedback_form_option', $survey->feedback_form_option ?? '') === 'required' ? 'checked' : '' }}>
                                        <label class="form-check-label" for="feedbackRequired">Required</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="feedback_form_option" id="feedbackDontShow" value="dont_show"
                                            {{ old('feedback_form_option', $survey->feedback_form_option ?? '') === 'dont_show' ? 'checked' : '' }}>
                                        <label class="form-check-label" for="feedbackDontShow">Don't show</label>
                                    </div>
                                </div>
                            </div>

                            <!-- Show Labels -->
                            <div class="mb-4">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" name="show_labels" id="showLabels" value="1"
                                        {{ old('show_labels', $survey->show_labels ?? true) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="showLabels">Show Labels</label>
                                </div>
                            </div>

                            <!-- Label Settings -->
                            <div class="row" id="labelSettings">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Least Likely Label</label>
                                    <input type="text" name="label_least_likely" class="form-control" id="minLabel"
                                           value="{{ old('label_least_likely', $survey->label_least_likely ?? 'Not at all likely') }}"
                                           placeholder="Not at all likely">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Most Likely Label</label>
                                    <input type="text" name="label_most_likely" class="form-control" id="maxLabel"
                                           value="{{ old('label_most_likely', $survey->label_most_likely ?? 'Extremely likely') }}"
                                           placeholder="Extremely likely">
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Back Button</label>
                                    <input type="text" name="label_back_button" class="form-control"
                                           value="{{ old('label_back_button', $survey->label_back_button ?? 'Back') }}"
                                           placeholder="Back">
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Skip Button</label>
                                    <input type="text" name="label_skip_button" class="form-control"
                                           value="{{ old('label_skip_button', $survey->label_skip_button ?? 'Skip') }}"
                                           placeholder="Skip">
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Submit Button</label>
                                    <input type="text" name="label_submit_button" class="form-control"
                                           value="{{ old('label_submit_button', $survey->label_submit_button ?? 'Submit') }}"
                                           placeholder="Submit">
                                </div>
                            </div>

                            <!-- Feedback Fields -->
                            <div class="mb-3">
                                <label class="form-label">Feedback Question</label>
                                <input type="text" name="feedback_question" class="form-control" id="followUpQuestion"
                                       value="{{ old('feedback_question', $survey->feedback_question ?? 'What is the main reason for your score?') }}"
                                       placeholder="What is the main reason for your score?">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Feedback Placeholder</label>
                                <input type="text" name="feedback_placeholder" class="form-control"
                                       value="{{ old('feedback_placeholder', $survey->feedback_placeholder ?? 'Your feedback helps us improve...') }}"
                                       placeholder="Your feedback helps us improve...">
                            </div>
                            <div class="mb-0">
                                <label class="form-label">Feedback Thank You</label>
                                <input type="text" name="feedback_thank_you" class="form-control"
                                       value="{{ old('feedback_thank_you', $survey->feedback_thank_you ?? 'Thank you for your feedback!') }}"
                                       placeholder="Thank you for your feedback!">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Trigger Section -->
                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#triggerSection">
                            <div class="accordion-header-content">
                                <i class="ti ti-clock"></i>
                                <div class="header-text">
                                    <h6>Trigger</h6>
                                    <p>Control when your survey appears</p>
                                </div>
                            </div>
                        </button>
                    </h2>
                    <div id="triggerSection" class="accordion-collapse collapse" data-bs-parent="#surveySettingsAccordion">
                        <div class="accordion-body">
                            <label class="form-label mb-3">When would you like to show the survey?</label>

                            <div class="mb-4">
                                <div class="radio-card mb-2 {{ old('trigger_type', $survey->trigger_type ?? 'page_load') === 'page_load' ? 'selected' : '' }}">
                                    <div class="form-check mb-0">
                                        <input class="form-check-input" type="radio" name="trigger_type" id="triggerPageLoad" value="page_load"
                                            {{ old('trigger_type', $survey->trigger_type ?? 'page_load') === 'page_load' ? 'checked' : '' }}>
                                        <label class="form-check-label" for="triggerPageLoad">
                                            <strong>When the user lands on the page</strong>
                                            <p class="text-muted mb-0 small">Show the survey when the page loads</p>
                                        </label>
                                    </div>
                                </div>
                                <div class="radio-card mb-2 {{ old('trigger_type', $survey->trigger_type ?? '') === 'page_leave' ? 'selected' : '' }}">
                                    <div class="form-check mb-0">
                                        <input class="form-check-input" type="radio" name="trigger_type" id="triggerPageLeave" value="page_leave"
                                            {{ old('trigger_type', $survey->trigger_type ?? '') === 'page_leave' ? 'checked' : '' }}>
                                        <label class="form-check-label" for="triggerPageLeave">
                                            <strong>When a user leaves the page</strong>
                                            <p class="text-muted mb-0 small">Show when user is about to navigate away</p>
                                        </label>
                                    </div>
                                </div>
                                <div class="radio-card mb-2 {{ old('trigger_type', $survey->trigger_type ?? '') === 'code_trigger' ? 'selected' : '' }}">
                                    <div class="form-check mb-0">
                                        <input class="form-check-input" type="radio" name="trigger_type" id="triggerCode" value="code_trigger"
                                            {{ old('trigger_type', $survey->trigger_type ?? '') === 'code_trigger' ? 'checked' : '' }}>
                                        <label class="form-check-label" for="triggerCode">
                                            <strong>When I trigger with code</strong>
                                            <p class="text-muted mb-0 small">Manually trigger via JavaScript</p>
                                        </label>
                                    </div>
                                </div>
                                <div class="radio-card {{ old('trigger_type', $survey->trigger_type ?? '') === 'scroll' ? 'selected' : '' }}">
                                    <div class="form-check mb-0">
                                        <input class="form-check-input" type="radio" name="trigger_type" id="triggerScroll" value="scroll"
                                            {{ old('trigger_type', $survey->trigger_type ?? '') === 'scroll' ? 'checked' : '' }}>
                                        <label class="form-check-label" for="triggerScroll">
                                            <strong>When the user has scrolled the page</strong>
                                            <p class="text-muted mb-0 small">Show after user scrolls down the page</p>
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Delay (seconds)</label>
                                    <input type="number" name="trigger_delay_seconds" class="form-control" min="0"
                                           value="{{ old('trigger_delay_seconds', $survey->trigger_delay_seconds ?? 0) }}"
                                           placeholder="0">
                                    <small class="text-muted">Wait before showing the survey</small>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Show Survey</label>
                                <select name="show_survey_timing" class="form-select" id="showSurveyTiming">
                                    <option value="first_visit" {{ old('show_survey_timing', $survey->show_survey_timing ?? 'first_visit') === 'first_visit' ? 'selected' : '' }}>On first visit</option>
                                    <option value="after_period" {{ old('show_survey_timing', $survey->show_survey_timing ?? '') === 'after_period' ? 'selected' : '' }}>After a period</option>
                                </select>
                            </div>

                            <div class="conditional-field" id="showAfterPeriodField">
                                <label class="form-label">Show after</label>
                                <div class="row">
                                    <div class="col-6">
                                        <input type="number" name="show_after_value" class="form-control" min="1"
                                               value="{{ old('show_after_value', $survey->show_after_value ?? 1) }}"
                                               placeholder="1">
                                    </div>
                                    <div class="col-6">
                                        <select name="show_after_unit" class="form-select">
                                            <option value="hours" {{ old('show_after_unit', $survey->show_after_unit ?? 'days') === 'hours' ? 'selected' : '' }}>Hours</option>
                                            <option value="days" {{ old('show_after_unit', $survey->show_after_unit ?? 'days') === 'days' ? 'selected' : '' }}>Days</option>
                                            <option value="months" {{ old('show_after_unit', $survey->show_after_unit ?? '') === 'months' ? 'selected' : '' }}>Months</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Location Section -->
                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#locationSection">
                            <div class="accordion-header-content">
                                <i class="ti ti-map-pin"></i>
                                <div class="header-text">
                                    <h6>Location</h6>
                                    <p>Control where your survey appears</p>
                                </div>
                            </div>
                        </button>
                    </h2>
                    <div id="locationSection" class="accordion-collapse collapse" data-bs-parent="#surveySettingsAccordion">
                        <div class="accordion-body">
                            <label class="form-label mb-3">Where would you like the survey to load?</label>

                            <div class="mb-4">
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="radio" name="location_type" id="locationAll" value="all_pages"
                                        {{ old('location_type', $survey->location_type ?? 'all_pages') === 'all_pages' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="locationAll">All Pages</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="location_type" id="locationSpecific" value="specific_pages"
                                        {{ old('location_type', $survey->location_type ?? '') === 'specific_pages' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="locationSpecific">Only Certain Pages</label>
                                </div>
                            </div>

                            <!-- Enable in Subdomain Toggle -->
                            <div class="mb-4">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" name="enable_in_subdomain" id="enableInSubdomain" value="1"
                                        {{ old('enable_in_subdomain', $survey->enable_in_subdomain ?? false) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="enableInSubdomain">
                                        <strong>Enable in Subdomain</strong>
                                    </label>
                                </div>
                                <p class="text-muted small mt-1">When enabled, this survey will also be displayed on subdomains of your domain</p>
                            </div>

                            <div class="conditional-field" id="specificPagesField">
                                <p class="text-muted small mb-3">
                                    <i class="ti ti-info-circle me-1"></i>
                                    Show/hide when URL matches. Use <code>*</code> as wildcards, e.g. <code>domain.com/*</code>
                                </p>

                                <div id="urlList">
                                    @if(isset($survey) && $survey->location_urls)
                                        @foreach($survey->location_urls as $index => $urlRule)
                                            <div class="url-list-item">
                                                <select name="location_urls[{{ $index }}][type]" class="form-select" style="width: 120px;">
                                                    <option value="show" {{ ($urlRule['type'] ?? 'show') === 'show' ? 'selected' : '' }}>Show</option>
                                                    <option value="hide" {{ ($urlRule['type'] ?? '') === 'hide' ? 'selected' : '' }}>Hide</option>
                                                </select>
                                                <input type="text" name="location_urls[{{ $index }}][url]" class="form-control"
                                                       value="{{ $urlRule['url'] ?? '' }}" placeholder="https://example.com/*">
                                                <button type="button" class="btn btn-sm btn-remove" onclick="removeUrlRule(this)">
                                                    <i class="ti ti-trash"></i>
                                                </button>
                                            </div>
                                        @endforeach
                                    @else
                                        <div class="url-list-item">
                                            <select name="location_urls[0][type]" class="form-select" style="width: 120px;">
                                                <option value="show">Show</option>
                                                <option value="hide">Hide</option>
                                            </select>
                                            <input type="text" name="location_urls[0][url]" class="form-control" placeholder="https://example.com/*">
                                            <button type="button" class="btn btn-sm btn-remove" onclick="removeUrlRule(this)">
                                                <i class="ti ti-trash"></i>
                                            </button>
                                        </div>
                                    @endif
                                </div>
                                <button type="button" class="btn btn-sm btn-outline-primary mt-2" onclick="addUrlRule()">
                                    <i class="ti ti-plus me-1"></i>Add URL
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Audience Section -->
                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#audienceSection">
                            <div class="accordion-header-content">
                                <i class="ti ti-users"></i>
                                <div class="header-text">
                                    <h6>Audience</h6>
                                    <p>Control who sees your survey</p>
                                </div>
                            </div>
                        </button>
                    </h2>
                    <div id="audienceSection" class="accordion-collapse collapse" data-bs-parent="#surveySettingsAccordion">
                        <div class="accordion-body">
                            <div class="mb-4">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" name="only_identified_users" id="onlyIdentifiedUsers" value="1"
                                        {{ old('only_identified_users', $survey->only_identified_users ?? false) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="onlyIdentifiedUsers">
                                        <strong>Only show to identified users</strong>
                                    </label>
                                </div>
                                <p class="text-muted small mt-1">When enabled, only logged-in users will see this survey</p>
                            </div>

                            <div class="conditional-field" id="segmentSelectionField">
                                <label class="form-label">Select Segments</label>
                                <select name="segment_ids[]" class="form-select" multiple id="segmentSelect">
                                    @php
                                        $segments = \App\Models\UserSegment::where('team_id', auth()->user()->current_team_id)->get();
                                        $selectedSegments = old('segment_ids', $survey->segment_ids ?? []);
                                    @endphp
                                    @foreach($segments as $segment)
                                        <option value="{{ $segment->id }}" {{ in_array($segment->id, $selectedSegments) ? 'selected' : '' }}>
                                            {{ $segment->name }}
                                        </option>
                                    @endforeach
                                </select>
                                <small class="text-muted">Hold Ctrl/Cmd to select multiple segments</small>
                            </div>

                            <div class="alert alert-info mt-3 mb-0">
                                <i class="ti ti-info-circle me-2"></i>
                                <strong>Note:</strong> When a user is logged in, this NPS survey will be shown to them based on the settings above.
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Frequency Section -->
                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#frequencySection">
                            <div class="accordion-header-content">
                                <i class="ti ti-repeat"></i>
                                <div class="header-text">
                                    <h6>Frequency</h6>
                                    <p>Set how many times a user will see this survey</p>
                                </div>
                            </div>
                        </button>
                    </h2>
                    <div id="frequencySection" class="accordion-collapse collapse" data-bs-parent="#surveySettingsAccordion">
                        <div class="accordion-body">
                            <!-- On Response -->
                            <div class="mb-4">
                                <label class="form-label">If the user responds to the survey</label>
                                <select name="on_response_action" class="form-select" id="onResponseAction">
                                    <option value="never_show" {{ old('on_response_action', $survey->on_response_action ?? 'never_show') === 'never_show' ? 'selected' : '' }}>Never show again</option>
                                    <option value="show_after" {{ old('on_response_action', $survey->on_response_action ?? '') === 'show_after' ? 'selected' : '' }}>Show again after</option>
                                </select>
                            </div>

                            <div class="conditional-field" id="onResponseShowAfterField">
                                <div class="row">
                                    <div class="col-6">
                                        <input type="number" name="on_response_show_after_value" class="form-control" min="1"
                                               value="{{ old('on_response_show_after_value', $survey->on_response_show_after_value ?? 30) }}"
                                               placeholder="30">
                                    </div>
                                    <div class="col-6">
                                        <select name="on_response_show_after_unit" class="form-select">
                                            <option value="hours" {{ old('on_response_show_after_unit', $survey->on_response_show_after_unit ?? 'days') === 'hours' ? 'selected' : '' }}>Hours</option>
                                            <option value="days" {{ old('on_response_show_after_unit', $survey->on_response_show_after_unit ?? 'days') === 'days' ? 'selected' : '' }}>Days</option>
                                            <option value="months" {{ old('on_response_show_after_unit', $survey->on_response_show_after_unit ?? '') === 'months' ? 'selected' : '' }}>Months</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <!-- On Close -->
                            <div class="mb-4 mt-4">
                                <label class="form-label">If the user closes the survey</label>
                                <select name="on_close_action" class="form-select" id="onCloseAction">
                                    <option value="never_show" {{ old('on_close_action', $survey->on_close_action ?? '') === 'never_show' ? 'selected' : '' }}>Never show again</option>
                                    <option value="show_after" {{ old('on_close_action', $survey->on_close_action ?? 'show_after') === 'show_after' ? 'selected' : '' }}>Show again after</option>
                                </select>
                            </div>

                            <div class="conditional-field" id="onCloseShowAfterField">
                                <div class="row">
                                    <div class="col-6">
                                        <input type="number" name="on_close_show_after_value" class="form-control" min="1"
                                               value="{{ old('on_close_show_after_value', $survey->on_close_show_after_value ?? 1) }}"
                                               placeholder="1">
                                    </div>
                                    <div class="col-6">
                                        <select name="on_close_show_after_unit" class="form-select">
                                            <option value="hours" {{ old('on_close_show_after_unit', $survey->on_close_show_after_unit ?? 'days') === 'hours' ? 'selected' : '' }}>Hours</option>
                                            <option value="days" {{ old('on_close_show_after_unit', $survey->on_close_show_after_unit ?? 'days') === 'days' ? 'selected' : '' }}>Days</option>
                                            <option value="months" {{ old('on_close_show_after_unit', $survey->on_close_show_after_unit ?? '') === 'months' ? 'selected' : '' }}>Months</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <!-- Skip if answered in session -->
                            <div class="mt-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="skip_if_answered_in_session" id="skipIfAnswered" value="1"
                                        {{ old('skip_if_answered_in_session', $survey->skip_if_answered_in_session ?? false) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="skipIfAnswered">
                                        Don't show this survey if a visitor has answered or closed another survey in the current session
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Appearance Section -->
                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#appearanceSection">
                            <div class="accordion-header-content">
                                <i class="ti ti-palette"></i>
                                <div class="header-text">
                                    <h6>Appearance</h6>
                                    <p>Fine tune your survey's look and feel</p>
                                </div>
                            </div>
                        </button>
                    </h2>
                    <div id="appearanceSection" class="accordion-collapse collapse" data-bs-parent="#surveySettingsAccordion">
                        <div class="accordion-body">
                            <div class="row">
                                <div class="col-md-6 mb-4">
                                    <label class="form-label">Theme</label>
                                    <select name="theme" class="form-select" id="themeSelect">
                                        <option value="inherit" {{ old('theme', $survey->theme ?? 'inherit') === 'inherit' ? 'selected' : '' }}>Inherit from company</option>
                                        <option value="light" {{ old('theme', $survey->theme ?? '') === 'light' ? 'selected' : '' }}>Light</option>
                                        <option value="dark" {{ old('theme', $survey->theme ?? '') === 'dark' ? 'selected' : '' }}>Dark</option>
                                        <option value="purple" {{ old('theme', $survey->theme ?? '') === 'purple' ? 'selected' : '' }}>Purple</option>
                                        <option value="navy" {{ old('theme', $survey->theme ?? '') === 'navy' ? 'selected' : '' }}>Navy</option>
                                    </select>
                                </div>
                                <div class="col-md-6 mb-4">
                                    <label class="form-label">Accent Color</label>
                                    <div class="color-picker-wrapper">
                                        <input type="color" id="accentColorPicker"
                                               value="{{ old('accent_color', $survey->accent_color ?? '#5865F2') }}">
                                        <input type="text" name="accent_color" class="form-control" id="accentColorText"
                                               value="{{ old('accent_color', $survey->accent_color ?? '#5865F2') }}"
                                               placeholder="#5865F2">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-4">
                                    <label class="form-label">Position</label>
                                    <select name="position" class="form-select">
                                        <option value="bottom_right" {{ old('position', $survey->position ?? 'bottom_right') === 'bottom_right' ? 'selected' : '' }}>Bottom Right</option>
                                        <option value="bottom_left" {{ old('position', $survey->position ?? '') === 'bottom_left' ? 'selected' : '' }}>Bottom Left</option>
                                        <option value="bottom_center" {{ old('position', $survey->position ?? '') === 'bottom_center' ? 'selected' : '' }}>Bottom Center</option>
                                        <option value="center" {{ old('position', $survey->position ?? '') === 'center' ? 'selected' : '' }}>Center</option>
                                    </select>
                                </div>
                                <div class="col-md-6 mb-4">
                                    <div class="form-check form-switch mt-4">
                                        <input class="form-check-input" type="checkbox" name="hide_branding" id="hideBranding" value="1"
                                            {{ old('hide_branding', $survey->hide_branding ?? false) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="hideBranding">Hide Branding</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>

        <!-- Right Column: Preview -->
        <div class="col-lg-5">
            <div class="preview-container">
                <h6 class="text-muted mb-3">
                    <i class="ti ti-eye me-2"></i>Live Preview
                </h6>
                <div class="preview-widget" id="previewWidget">
                    <div class="survey-question" id="previewQuestion">
                        {{ $defaultSettings['question'] ?? 'Your question will appear here' }}
                    </div>

                    @if($type === 'nps')
                    <!-- NPS Scale -->
                    <div class="nps-scale" id="npsScale">
                        @for($i = 0; $i <= 10; $i++)
                            <div class="score-btn">{{ $i }}</div>
                        @endfor
                    </div>
                    <div class="scale-labels">
                        <span id="previewMinLabel">{{ $survey->label_least_likely ?? 'Not at all likely' }}</span>
                        <span id="previewMaxLabel">{{ $survey->label_most_likely ?? 'Extremely likely' }}</span>
                    </div>
                    @elseif($type === 'csat')
                    <!-- CSAT Scale -->
                    <div class="rating-scale" id="csatScale">
                        @for($i = 1; $i <= 5; $i++)
                            <div class="rating-btn">{{ $i }}</div>
                        @endfor
                    </div>
                    <div class="scale-labels">
                        <span id="previewMinLabel">{{ $survey->label_least_likely ?? 'Very dissatisfied' }}</span>
                        <span id="previewMaxLabel">{{ $survey->label_most_likely ?? 'Very satisfied' }}</span>
                    </div>
                    @else
                    <!-- Open feedback / other -->
                    <textarea class="preview-textarea" rows="3" placeholder="Share your feedback..." disabled></textarea>
                    @endif

                    <div id="previewFollowUp">
                        <textarea class="preview-textarea" rows="3" id="previewFollowUpText" placeholder="{{ $survey->feedback_question ?? 'What is the main reason for your score?' }}"></textarea>
                    </div>

                    <button type="button" class="preview-submit" id="previewSubmitBtn" style="background-color: {{ $survey->accent_color ?? '#5865F2' }}">
                        Submit
                    </button>
                </div>
            </div>
        </div>
    </div>
</form>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Color picker sync
    const accentColorPicker = document.getElementById('accentColorPicker');
    const accentColorText = document.getElementById('accentColorText');
    const previewSubmitBtn = document.getElementById('previewSubmitBtn');

    function updateAccentColor(color) {
        document.documentElement.style.setProperty('--accent-color', color);
        if (previewSubmitBtn) {
            previewSubmitBtn.style.backgroundColor = color;
        }
    }

    if (accentColorPicker && accentColorText) {
        accentColorPicker.addEventListener('input', function() {
            accentColorText.value = this.value;
            updateAccentColor(this.value);
        });
        accentColorText.addEventListener('input', function() {
            if (/^#[0-9A-Fa-f]{6}$/.test(this.value)) {
                accentColorPicker.value = this.value;
                updateAccentColor(this.value);
            }
        });
        updateAccentColor(accentColorPicker.value);
    }

    // Live preview updates
    const mainQuestion = document.getElementById('mainQuestion');
    const followUpQuestion = document.getElementById('followUpQuestion');
    const previewQuestion = document.getElementById('previewQuestion');
    const previewFollowUpText = document.getElementById('previewFollowUpText');
    const minLabel = document.getElementById('minLabel');
    const maxLabel = document.getElementById('maxLabel');
    const previewMinLabel = document.getElementById('previewMinLabel');
    const previewMaxLabel = document.getElementById('previewMaxLabel');

    if (mainQuestion && previewQuestion) {
        mainQuestion.addEventListener('input', function() {
            previewQuestion.textContent = this.value || 'Your question will appear here';
        });
    }

    if (followUpQuestion && previewFollowUpText) {
        followUpQuestion.addEventListener('input', function() {
            previewFollowUpText.placeholder = this.value || 'Share your feedback...';
        });
    }

    if (minLabel && previewMinLabel) {
        minLabel.addEventListener('input', function() {
            previewMinLabel.textContent = this.value || 'Low';
        });
    }

    if (maxLabel && previewMaxLabel) {
        maxLabel.addEventListener('input', function() {
            previewMaxLabel.textContent = this.value || 'High';
        });
    }

    // Interactive preview - NPS scale
    const npsScale = document.getElementById('npsScale');
    if (npsScale) {
        const scoreBtns = npsScale.querySelectorAll('.score-btn');
        scoreBtns.forEach(btn => {
            btn.addEventListener('click', function() {
                scoreBtns.forEach(b => b.classList.remove('selected'));
                this.classList.add('selected');
            });
        });
    }

    // Radio card selection
    document.querySelectorAll('.radio-card input[type="radio"]').forEach(radio => {
        radio.addEventListener('change', function() {
            const allCards = this.closest('.mb-4').querySelectorAll('.radio-card');
            allCards.forEach(card => card.classList.remove('selected'));
            this.closest('.radio-card').classList.add('selected');
        });
    });

    // Conditional fields
    function toggleConditionalField(trigger, fieldId, showValue) {
        const field = document.getElementById(fieldId);
        if (!field) return;

        if (trigger.value === showValue || (trigger.checked && showValue === 'checked')) {
            field.classList.add('show');
        } else {
            field.classList.remove('show');
        }
    }

    // Show Survey Timing
    const showSurveyTiming = document.getElementById('showSurveyTiming');
    if (showSurveyTiming) {
        showSurveyTiming.addEventListener('change', function() {
            toggleConditionalField(this, 'showAfterPeriodField', 'after_period');
        });
        toggleConditionalField(showSurveyTiming, 'showAfterPeriodField', 'after_period');
    }

    // Location type
    const locationSpecific = document.getElementById('locationSpecific');
    const locationAll = document.getElementById('locationAll');
    if (locationSpecific) {
        [locationSpecific, locationAll].forEach(radio => {
            radio.addEventListener('change', function() {
                const field = document.getElementById('specificPagesField');
                if (locationSpecific.checked) {
                    field.classList.add('show');
                } else {
                    field.classList.remove('show');
                }
            });
        });
        if (locationSpecific.checked) {
            document.getElementById('specificPagesField').classList.add('show');
        }
    }

    // Identified users
    const onlyIdentifiedUsers = document.getElementById('onlyIdentifiedUsers');
    if (onlyIdentifiedUsers) {
        onlyIdentifiedUsers.addEventListener('change', function() {
            const field = document.getElementById('segmentSelectionField');
            if (this.checked) {
                field.classList.add('show');
            } else {
                field.classList.remove('show');
            }
        });
        if (onlyIdentifiedUsers.checked) {
            document.getElementById('segmentSelectionField').classList.add('show');
        }
    }

    // On Response Action
    const onResponseAction = document.getElementById('onResponseAction');
    if (onResponseAction) {
        onResponseAction.addEventListener('change', function() {
            toggleConditionalField(this, 'onResponseShowAfterField', 'show_after');
        });
        toggleConditionalField(onResponseAction, 'onResponseShowAfterField', 'show_after');
    }

    // On Close Action
    const onCloseAction = document.getElementById('onCloseAction');
    if (onCloseAction) {
        onCloseAction.addEventListener('change', function() {
            toggleConditionalField(this, 'onCloseShowAfterField', 'show_after');
        });
        toggleConditionalField(onCloseAction, 'onCloseShowAfterField', 'show_after');
    }
});

// URL List Management
let urlIndex = {{ isset($survey) && $survey->location_urls ? count($survey->location_urls) : 1 }};

function addUrlRule() {
    const urlList = document.getElementById('urlList');
    const newItem = document.createElement('div');
    newItem.className = 'url-list-item';
    newItem.innerHTML = `
        <select name="location_urls[${urlIndex}][type]" class="form-select" style="width: 120px;">
            <option value="show">Show</option>
            <option value="hide">Hide</option>
        </select>
        <input type="text" name="location_urls[${urlIndex}][url]" class="form-control" placeholder="https://example.com/*">
        <button type="button" class="btn btn-sm btn-remove" onclick="removeUrlRule(this)">
            <i class="ti ti-trash"></i>
        </button>
    `;
    urlList.appendChild(newItem);
    urlIndex++;
}

function removeUrlRule(btn) {
    const urlList = document.getElementById('urlList');
    if (urlList.children.length > 1) {
        btn.closest('.url-list-item').remove();
    }
}

// Form submission function
function submitSurveyForm(status) {
    const form = document.getElementById('surveyForm');
    const statusInput = document.getElementById('surveyStatus');
    const titleInput = document.querySelector('input[name="title"]');

    // Validate title
    if (!titleInput.value.trim()) {
        titleInput.focus();
        titleInput.classList.add('is-invalid');

        // Open General accordion if closed
        const generalSection = document.getElementById('generalSection');
        if (!generalSection.classList.contains('show')) {
            const generalButton = document.querySelector('[data-bs-target="#generalSection"]');
            generalButton.click();
        }

        alert('Please enter a survey title.');
        return false;
    }

    // Set the status value
    statusInput.value = status;

    // Submit the form
    form.submit();
}
</script>
@endpush
