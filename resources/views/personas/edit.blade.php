@extends('layouts.inspinia')

@section('title', 'Edit Persona')

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/@yaireo/tagify/dist/tagify.css" rel="stylesheet" type="text/css">
<style>
    .tagify {
        min-height: 39.51px !important;
        padding: 0.375rem 0.75rem !important;
        border: 1px solid #dee2e6 !important;
        border-radius: 0.25rem !important;
    }

    /* Ensure native selects are visible and not hidden by Choices.js */
    #persona-form select.form-select {
        display: block !important;
        visibility: visible !important;
        opacity: 1 !important;
        position: relative !important;
        width: 100% !important;
    }

    .section-card {
        border: 1px solid #e9ecef;
        border-radius: 8px;
        margin-bottom: 1.5rem;
    }

    .section-header {
        background-color: #f8f9fa;
        padding: 1rem 1.25rem;
        border-bottom: 1px solid #e9ecef;
        border-radius: 8px 8px 0 0;
    }

    .section-header h5 {
        margin: 0;
        font-weight: 600;
        color: #495057;
    }

    .section-header p {
        margin: 0.25rem 0 0;
        font-size: 0.875rem;
        color: #6c757d;
    }

    .section-body {
        padding: 1.25rem;
    }

    .avatar-upload-container {
        display: flex;
        align-items: center;
        gap: 1.5rem;
    }

    .avatar-preview {
        width: 120px;
        height: 120px;
        border-radius: 50%;
        background-color: #e9ecef;
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
        border: 3px solid #dee2e6;
        position: relative;
    }

    .avatar-preview img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .avatar-preview i {
        font-size: 3rem;
        color: #adb5bd;
    }

    .avatar-remove-btn {
        position: absolute;
        top: 0;
        right: 0;
        background: #dc3545;
        border: none;
        border-radius: 50%;
        width: 24px;
        height: 24px;
        color: white;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 12px;
    }

    .weighted-score-item {
        display: flex;
        gap: 0.5rem;
        margin-bottom: 0.5rem;
        align-items: center;
    }

    .weighted-score-item input[type="text"] {
        flex: 2;
    }

    .weighted-score-item input[type="number"] {
        flex: 1;
        max-width: 100px;
    }

    .weighted-score-item .btn-remove {
        padding: 0.375rem 0.5rem;
    }
</style>
@endpush

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-flex align-items-center justify-content-between">
                <div>
                    <h2 class="mb-0">Edit Persona</h2>
                    <p class="text-muted fs-14 mb-0">Update the persona profile details</p>
                </div>
                <a href="{{ route('personas.index') }}" class="btn btn-secondary">
                    <i class="ti ti-arrow-left me-1"></i> Back
                </a>
            </div>
        </div>
    </div>

    <form action="{{ route('personas.update', $persona) }}" method="POST" enctype="multipart/form-data" id="persona-form">
        @csrf
        @method('PUT')
        <input type="hidden" name="remove_avatar" id="remove_avatar" value="0">

        <div class="row">
            <div class="col-lg-8">
                <!-- SECTION A: Persona Header -->
                <div class="section-card">
                    <div class="section-header">
                        <h5><i class="ti ti-user-circle me-2"></i>Persona Header</h5>
                        <p>Basic identification information for your persona</p>
                    </div>
                    <div class="section-body">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label class="form-label">Avatar Image</label>
                                    <div class="avatar-upload-container flex-column">
                                        <div class="avatar-preview" id="avatarPreview">
                                            @if($persona->avatar)
                                                <img src="{{ asset('storage/' . $persona->avatar) }}" alt="{{ $persona->name }}">
                                                <button type="button" class="avatar-remove-btn" onclick="removeAvatar()">
                                                    <i class="ti ti-x"></i>
                                                </button>
                                            @else
                                                <i class="ti ti-user"></i>
                                            @endif
                                        </div>
                                        <input type="file" name="avatar" id="avatar" accept="image/*" class="form-control mt-2">
                                        <small class="text-muted">JPG, PNG, WebP. Max 2MB</small>
                                    </div>
                                    @error('avatar')
                                        <div class="text-danger mt-1">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-8">
                                <div class="mb-3">
                                    <label for="name" class="form-label">Persona Name <span class="text-danger">*</span></label>
                                    <input type="text" name="name" id="name" value="{{ old('name', $persona->name) }}" required class="form-control" placeholder="e.g., Tech-Savvy Sarah">
                                    @error('name')
                                        <div class="text-danger mt-1">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="tagline" class="form-label">Tagline</label>
                                    <input type="text" name="tagline" id="tagline" value="{{ old('tagline', $persona->tagline) }}" class="form-control" placeholder="e.g., The busy professional who values efficiency">
                                    @error('tagline')
                                        <div class="text-danger mt-1">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="segmentation_tags" class="form-label">Segmentation Tags</label>
                                    <input type="text" class="form-control" id="segmentation_tags" name="segmentation_tags" placeholder="Add segmentation tags...">
                                    <small class="text-muted">Press Enter to add tags (e.g., B2B, Enterprise, Early Adopter)</small>
                                    @error('segmentation_tags')
                                        <div class="text-danger mt-1">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-0">
                                    <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
                                    <select name="status" id="status" required class="form-select" data-choices-disabled="true">
                                        <option value="active" {{ old('status', $persona->status) === 'active' ? 'selected' : '' }}>Active</option>
                                        <option value="inactive" {{ old('status', $persona->status) === 'inactive' ? 'selected' : '' }}>Inactive</option>
                                    </select>
                                    @error('status')
                                        <div class="text-danger mt-1">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Identity Section -->
                <div class="section-card">
                    <div class="section-header">
                        <h5><i class="ti ti-id me-2"></i>Identity</h5>
                        <p>Demographic and professional details</p>
                    </div>
                    <div class="section-body">
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label for="age_range" class="form-label">Age / Age Range</label>
                                <input type="text" name="age_range" id="age_range" value="{{ old('age_range', $persona->age_range) }}" class="form-control" placeholder="e.g., 30-35 or 32">
                                @error('age_range')
                                    <div class="text-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="gender" class="form-label">Gender</label>
                                <select name="gender" id="gender" class="form-select" data-choices-disabled="true">
                                    <option value="">Select Gender</option>
                                    <option value="Male" {{ old('gender', $persona->gender) === 'Male' ? 'selected' : '' }}>Male</option>
                                    <option value="Female" {{ old('gender', $persona->gender) === 'Female' ? 'selected' : '' }}>Female</option>
                                    <option value="Non-binary" {{ old('gender', $persona->gender) === 'Non-binary' ? 'selected' : '' }}>Non-binary</option>
                                    <option value="Other" {{ old('gender', $persona->gender) === 'Other' ? 'selected' : '' }}>Other</option>
                                </select>
                                @error('gender')
                                    <div class="text-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="location" class="form-label">Location</label>
                                <input type="text" name="location" id="location" value="{{ old('location', $persona->location) }}" class="form-control" placeholder="e.g., San Francisco, CA">
                                @error('location')
                                    <div class="text-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label for="occupation" class="form-label">Occupation</label>
                                <input type="text" name="occupation" id="occupation" value="{{ old('occupation', $persona->occupation) }}" class="form-control" placeholder="e.g., Marketing Manager">
                                @error('occupation')
                                    <div class="text-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="education" class="form-label">Education</label>
                                <select name="education" id="education" class="form-select" data-choices-disabled="true">
                                    <option value="">Select Education</option>
                                    <option value="High School" {{ old('education', $persona->education) === 'High School' ? 'selected' : '' }}>High School</option>
                                    <option value="Some College" {{ old('education', $persona->education) === 'Some College' ? 'selected' : '' }}>Some College</option>
                                    <option value="Associate Degree" {{ old('education', $persona->education) === 'Associate Degree' ? 'selected' : '' }}>Associate Degree</option>
                                    <option value="Bachelor's Degree" {{ old('education', $persona->education) === "Bachelor's Degree" ? 'selected' : '' }}>Bachelor's Degree</option>
                                    <option value="Master's Degree" {{ old('education', $persona->education) === "Master's Degree" ? 'selected' : '' }}>Master's Degree</option>
                                    <option value="Doctorate" {{ old('education', $persona->education) === 'Doctorate' ? 'selected' : '' }}>Doctorate</option>
                                    <option value="Professional Certification" {{ old('education', $persona->education) === 'Professional Certification' ? 'selected' : '' }}>Professional Certification</option>
                                </select>
                                @error('education')
                                    <div class="text-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="income" class="form-label">Income Range</label>
                                <select name="income" id="income" class="form-select" data-choices-disabled="true">
                                    <option value="">Select Income Range</option>
                                    <option value="$0 - $25,000" {{ old('income', $persona->income) === '$0 - $25,000' ? 'selected' : '' }}>$0 - $25,000</option>
                                    <option value="$25,000 - $50,000" {{ old('income', $persona->income) === '$25,000 - $50,000' ? 'selected' : '' }}>$25,000 - $50,000</option>
                                    <option value="$50,000 - $75,000" {{ old('income', $persona->income) === '$50,000 - $75,000' ? 'selected' : '' }}>$50,000 - $75,000</option>
                                    <option value="$75,000 - $100,000" {{ old('income', $persona->income) === '$75,000 - $100,000' ? 'selected' : '' }}>$75,000 - $100,000</option>
                                    <option value="$100,000 - $150,000" {{ old('income', $persona->income) === '$100,000 - $150,000' ? 'selected' : '' }}>$100,000 - $150,000</option>
                                    <option value="$150,000 - $200,000" {{ old('income', $persona->income) === '$150,000 - $200,000' ? 'selected' : '' }}>$150,000 - $200,000</option>
                                    <option value="$200,000+" {{ old('income', $persona->income) === '$200,000+' ? 'selected' : '' }}>$200,000+</option>
                                </select>
                                @error('income')
                                    <div class="text-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Goals & Pain Points Section -->
                <div class="section-card">
                    <div class="section-header">
                        <h5><i class="ti ti-target me-2"></i>Goals & Pain Points</h5>
                        <p>What drives this persona and what challenges they face</p>
                    </div>
                    <div class="section-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="goals" class="form-label">Goals & Motivations</label>
                                <input type="text" class="form-control" id="goals" name="goals" placeholder="Add goals...">
                                <small class="text-muted">Press Enter to add each goal</small>
                                @error('goals')
                                    <div class="text-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="motivations" class="form-label">Additional Motivations</label>
                                <input type="text" class="form-control" id="motivations" name="motivations" placeholder="Add motivations...">
                                <small class="text-muted">Press Enter to add each motivation</small>
                                @error('motivations')
                                    <div class="text-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="pain_points" class="form-label">Pain Points</label>
                                <input type="text" class="form-control" id="pain_points" name="pain_points" placeholder="Add pain points...">
                                <small class="text-muted">Press Enter to add each pain point</small>
                                @error('pain_points')
                                    <div class="text-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="frustrations" class="form-label">Frustrations</label>
                                <input type="text" class="form-control" id="frustrations" name="frustrations" placeholder="Add frustrations...">
                                <small class="text-muted">Press Enter to add each frustration</small>
                                @error('frustrations')
                                    <div class="text-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Behavior Section -->
                <div class="section-card">
                    <div class="section-header">
                        <h5><i class="ti ti-activity me-2"></i>Behavior</h5>
                        <p>How this persona interacts with technology and makes decisions</p>
                    </div>
                    <div class="section-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="device_usage" class="form-label">Device Usage</label>
                                <input type="text" class="form-control" id="device_usage" name="device_usage" placeholder="Add devices...">
                                <small class="text-muted">e.g., Desktop, Mobile, Tablet</small>
                                @error('device_usage')
                                    <div class="text-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="channel_preference" class="form-label">Channel Preference</label>
                                <input type="text" class="form-control" id="channel_preference" name="channel_preference" placeholder="Add channels...">
                                <small class="text-muted">e.g., Email, Social Media, Phone</small>
                                @error('channel_preference')
                                    <div class="text-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="workflow" class="form-label">Workflow</label>
                                <textarea name="workflow" id="workflow" rows="3" class="form-control" placeholder="Describe their typical workflow or daily routine...">{{ old('workflow', $persona->workflow) }}</textarea>
                                @error('workflow')
                                    <div class="text-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="buying_behavior" class="form-label">Buying Behavior</label>
                                <textarea name="buying_behavior" id="buying_behavior" rows="3" class="form-control" placeholder="Describe their purchasing patterns and decision-making process...">{{ old('buying_behavior', $persona->buying_behavior) }}</textarea>
                                @error('buying_behavior')
                                    <div class="text-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-0">
                            <label for="behaviors" class="form-label">General Behaviors</label>
                            <input type="text" class="form-control" id="behaviors" name="behaviors" placeholder="Add behaviors...">
                            <small class="text-muted">Press Enter to add each behavior trait</small>
                            @error('behaviors')
                                <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Product Fit Section -->
                <div class="section-card">
                    <div class="section-header">
                        <h5><i class="ti ti-puzzle me-2"></i>Product Fit</h5>
                        <p>How this persona relates to your product</p>
                    </div>
                    <div class="section-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="journey_stage" class="form-label">Journey Stage</label>
                                <select name="journey_stage" id="journey_stage" class="form-select" data-choices-disabled="true">
                                    <option value="">Select Journey Stage</option>
                                    <option value="Awareness" {{ old('journey_stage', $persona->journey_stage) === 'Awareness' ? 'selected' : '' }}>Awareness</option>
                                    <option value="Consideration" {{ old('journey_stage', $persona->journey_stage) === 'Consideration' ? 'selected' : '' }}>Consideration</option>
                                    <option value="Decision" {{ old('journey_stage', $persona->journey_stage) === 'Decision' ? 'selected' : '' }}>Decision</option>
                                    <option value="Retention" {{ old('journey_stage', $persona->journey_stage) === 'Retention' ? 'selected' : '' }}>Retention</option>
                                    <option value="Advocacy" {{ old('journey_stage', $persona->journey_stage) === 'Advocacy' ? 'selected' : '' }}>Advocacy</option>
                                </select>
                                @error('journey_stage')
                                    <div class="text-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="modules_interacted" class="form-label">Modules Interacted</label>
                                <input type="text" class="form-control" id="modules_interacted" name="modules_interacted" placeholder="Add modules...">
                                <small class="text-muted">Which product features/modules they use</small>
                                @error('modules_interacted')
                                    <div class="text-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-0">
                            <label class="form-label">Weighted Impact Scores</label>
                            <div id="weighted-scores-container">
                                @if($persona->weighted_impact_scores && count($persona->weighted_impact_scores) > 0)
                                    @foreach($persona->weighted_impact_scores as $index => $score)
                                        <div class="weighted-score-item">
                                            <input type="text" class="form-control" name="weighted_impact_scores[{{ $index }}][metric]" placeholder="Metric name (e.g., Revenue Impact)" value="{{ $score['metric'] ?? '' }}">
                                            <input type="number" class="form-control" name="weighted_impact_scores[{{ $index }}][score]" placeholder="Score" min="0" max="100" value="{{ $score['score'] ?? '' }}">
                                            <button type="button" class="btn btn-outline-danger btn-remove" onclick="removeWeightedScore(this)" style="{{ count($persona->weighted_impact_scores) <= 1 ? 'display: none;' : '' }}">
                                                <i class="ti ti-x"></i>
                                            </button>
                                        </div>
                                    @endforeach
                                @else
                                    <div class="weighted-score-item">
                                        <input type="text" class="form-control" name="weighted_impact_scores[0][metric]" placeholder="Metric name (e.g., Revenue Impact)">
                                        <input type="number" class="form-control" name="weighted_impact_scores[0][score]" placeholder="Score" min="0" max="100">
                                        <button type="button" class="btn btn-outline-danger btn-remove" onclick="removeWeightedScore(this)" style="display: none;">
                                            <i class="ti ti-x"></i>
                                        </button>
                                    </div>
                                @endif
                            </div>
                            <button type="button" class="btn btn-outline-primary btn-sm mt-2" onclick="addWeightedScore()">
                                <i class="ti ti-plus me-1"></i> Add Score
                            </button>
                            <small class="text-muted d-block mt-1">Assign weighted scores to measure persona impact on various metrics</small>
                        </div>
                    </div>
                </div>

                <!-- Persona Summary Card -->
                <div class="section-card">
                    <div class="section-header">
                        <h5><i class="ti ti-file-description me-2"></i>Persona Summary Card</h5>
                        <p>A brief summary to capture the essence of this persona</p>
                    </div>
                    <div class="section-body">
                        <div class="mb-3">
                            <label for="bio" class="form-label">Bio</label>
                            <textarea name="bio" id="bio" rows="4" class="form-control" placeholder="Write a brief biography that captures who this persona is, their background, and what makes them unique...">{{ old('bio', $persona->bio) }}</textarea>
                            @error('bio')
                                <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-0">
                            <label for="quote" class="form-label">Representative Quote</label>
                            <textarea name="quote" id="quote" rows="2" class="form-control" placeholder='"I need tools that just work without a steep learning curve..."'>{{ old('quote', $persona->quote) }}</textarea>
                            <small class="text-muted">A quote that captures this persona's voice and priorities</small>
                            @error('quote')
                                <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="col-lg-4">
                <div class="card sticky-top" style="top: 20px;">
                    <div class="card-header">
                        <h5 class="mb-0">Related Segments</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <input type="text" class="form-control" id="segments" name="segments" placeholder="Select segments...">
                            <small class="text-muted">Link this persona to existing user segments</small>
                            @error('segments')
                                <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="card-footer bg-white border-top">
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="ti ti-check me-1"></i> Update Persona
                            </button>
                            <a href="{{ route('personas.index') }}" class="btn btn-outline-secondary">Cancel</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/@yaireo/tagify"></script>
<script>
function removeAvatar() {
    if (confirm('Are you sure you want to remove this avatar?')) {
        document.getElementById('avatarPreview').innerHTML = '<i class="ti ti-user"></i>';
        document.getElementById('remove_avatar').value = '1';
    }
}

document.addEventListener('DOMContentLoaded', function() {
    // Force all selects in the persona form to be visible (prevent Choices.js from hiding them)
    document.querySelectorAll('#persona-form select.form-select').forEach(function(selectEl) {
        // Destroy any Choices.js instance if it exists
        if (selectEl.choices) {
            selectEl.choices.destroy();
        }
        selectEl.style.display = 'block';
        selectEl.style.visibility = 'visible';
        selectEl.style.opacity = '1';
        selectEl.classList.remove('hidden');

        // Remove choices wrapper if present
        const choicesWrapper = selectEl.closest('.choices');
        if (choicesWrapper) {
            const parent = choicesWrapper.parentElement;
            parent.insertBefore(selectEl, choicesWrapper);
            choicesWrapper.remove();
        }
    });

    // Avatar preview for new upload
    const avatarInput = document.querySelector('#avatar');
    const avatarPreview = document.querySelector('#avatarPreview');

    avatarInput.addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                avatarPreview.innerHTML = `<img src="${e.target.result}" alt="Avatar Preview">`;
                document.getElementById('remove_avatar').value = '0';
            };
            reader.readAsDataURL(file);
        }
    });

    // Segments
    const allSegments = @json($segments->map(function($segment) {
        return ['value' => (string)$segment->id, 'name' => $segment->name];
    }));

    const existingSegmentNames = @json($persona->segments->pluck('name')->toArray());

    const segmentInput = document.querySelector('#segments');
    const segmentTagify = new Tagify(segmentInput, {
        whitelist: allSegments.map(s => s.name),
        maxTags: allSegments.length,
        dropdown: {
            maxItems: 20,
            enabled: 0,
            closeOnSelect: false
        },
        placeholder: "Select segments...",
        enforceWhitelist: true,
        keepInvalidTags: false
    });

    // Load existing segments
    if (existingSegmentNames.length > 0) {
        segmentTagify.addTags(existingSegmentNames);
    }

    // Existing data from persona
    const existingData = {
        segmentation_tags: @json($persona->segmentation_tags ?? []),
        goals: @json($persona->goals ?? []),
        motivations: @json($persona->motivations ?? []),
        pain_points: @json($persona->pain_points ?? []),
        frustrations: @json($persona->frustrations ?? []),
        behaviors: @json($persona->behaviors ?? []),
        device_usage: @json($persona->device_usage ?? []),
        channel_preference: @json($persona->channel_preference ?? []),
        modules_interacted: @json($persona->modules_interacted ?? []),
    };

    // Tagify for array fields
    const tagifyConfigs = [
        { id: 'segmentation_tags', placeholder: 'Add segmentation tags...' },
        { id: 'goals', placeholder: 'Add goals...' },
        { id: 'motivations', placeholder: 'Add motivations...' },
        { id: 'pain_points', placeholder: 'Add pain points...' },
        { id: 'frustrations', placeholder: 'Add frustrations...' },
        { id: 'behaviors', placeholder: 'Add behaviors...' },
        { id: 'device_usage', placeholder: 'Add devices...' },
        { id: 'channel_preference', placeholder: 'Add channels...' },
        { id: 'modules_interacted', placeholder: 'Add modules...' },
    ];

    const tagifyInstances = {};

    tagifyConfigs.forEach(config => {
        const input = document.querySelector(`#${config.id}`);
        if (input) {
            tagifyInstances[config.id] = new Tagify(input, {
                maxTags: 20,
                dropdown: { enabled: 0 },
                placeholder: config.placeholder,
                enforceWhitelist: false
            });

            // Load existing data
            if (existingData[config.id] && existingData[config.id].length > 0) {
                tagifyInstances[config.id].addTags(existingData[config.id]);
            }
        }
    });

    // Form submission
    document.querySelector('#persona-form').addEventListener('submit', function(e) {
        e.preventDefault();

        // Handle segments
        const selectedSegmentNames = segmentTagify.value.map(tag => tag.value);
        const selectedSegmentIds = allSegments
            .filter(s => selectedSegmentNames.includes(s.name))
            .map(s => s.value);

        segmentInput.removeAttribute('name');

        selectedSegmentIds.forEach(id => {
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'segments[]';
            input.value = id;
            this.appendChild(input);
        });

        // Handle all tagify array fields
        Object.keys(tagifyInstances).forEach(fieldId => {
            const tagify = tagifyInstances[fieldId];
            const values = tagify.value.map(tag => tag.value);
            const originalInput = document.querySelector(`#${fieldId}`);

            originalInput.removeAttribute('name');

            if (values.length > 0) {
                values.forEach(value => {
                    const input = document.createElement('input');
                    input.type = 'hidden';
                    input.name = `${fieldId}[]`;
                    input.value = value;
                    this.appendChild(input);
                });
            }
        });

        // Handle weighted impact scores
        const weightedScores = [];
        document.querySelectorAll('.weighted-score-item').forEach(item => {
            const metric = item.querySelector('input[type="text"]').value;
            const score = item.querySelector('input[type="number"]').value;
            if (metric && score) {
                weightedScores.push({ metric, score: parseInt(score) });
            }
        });

        // Remove old weighted score inputs
        document.querySelectorAll('input[name^="weighted_impact_scores"]').forEach(input => {
            input.removeAttribute('name');
        });

        // Add new weighted score inputs
        weightedScores.forEach((item, index) => {
            const metricInput = document.createElement('input');
            metricInput.type = 'hidden';
            metricInput.name = `weighted_impact_scores[${index}][metric]`;
            metricInput.value = item.metric;
            this.appendChild(metricInput);

            const scoreInput = document.createElement('input');
            scoreInput.type = 'hidden';
            scoreInput.name = `weighted_impact_scores[${index}][score]`;
            scoreInput.value = item.score;
            this.appendChild(scoreInput);
        });

        this.submit();
    });
});

// Weighted scores management
let weightedScoreIndex = {{ ($persona->weighted_impact_scores ? count($persona->weighted_impact_scores) : 1) }};

function addWeightedScore() {
    const container = document.getElementById('weighted-scores-container');
    const newItem = document.createElement('div');
    newItem.className = 'weighted-score-item';
    newItem.innerHTML = `
        <input type="text" class="form-control" name="weighted_impact_scores[${weightedScoreIndex}][metric]" placeholder="Metric name (e.g., Revenue Impact)">
        <input type="number" class="form-control" name="weighted_impact_scores[${weightedScoreIndex}][score]" placeholder="Score" min="0" max="100">
        <button type="button" class="btn btn-outline-danger btn-remove" onclick="removeWeightedScore(this)">
            <i class="ti ti-x"></i>
        </button>
    `;
    container.appendChild(newItem);
    weightedScoreIndex++;

    // Show remove buttons if more than one item
    updateRemoveButtons();
}

function removeWeightedScore(button) {
    button.closest('.weighted-score-item').remove();
    updateRemoveButtons();
}

function updateRemoveButtons() {
    const items = document.querySelectorAll('.weighted-score-item');
    items.forEach((item, index) => {
        const removeBtn = item.querySelector('.btn-remove');
        if (removeBtn) {
            removeBtn.style.display = items.length > 1 ? 'block' : 'none';
        }
    });
}
</script>
@endpush
