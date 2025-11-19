@extends('layouts.inspinia')

@section('title', $segment ? 'Edit User Segment' : 'Add User Segment')

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/@yaireo/tagify/dist/tagify.css" rel="stylesheet" type="text/css" />
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/choices.js/public/assets/styles/choices.min.css">
<style>
    .section-header {
        background-color: #f8f9fa;
        padding: 0.75rem 1rem;
        margin: 1.5rem -1rem 1rem -1rem;
        border-left: 3px solid #007bff;
        font-weight: 600;
        color: #495057;
    }

    .form-section {
        margin-bottom: 2rem;
    }

    .choices__inner {
        min-height: 39.51px !important;
        padding: 0.375rem 0.75rem !important;
        background-color: #fff !important;
        border: 1px solid #dee2e6 !important;
        border-radius: 0.25rem !important;
        font-size: 1rem !important;
        line-height: 1.5 !important;
    }

    .choices__list--single {
        display: flex;
        align-items: center;
        padding: 0;
    }

    .choices__item {
        padding: 0;
        margin: 0;
    }

    .choices__list--dropdown .choices__item--selectable {
        padding: 0.5rem 1rem !important;
    }
</style>
@endpush

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-flex justify-content-between align-items-center">
            <div>
                <h4 class="page-title">{{ $segment ? 'Edit User Segment' : 'Add User Segment' }}</h4>
                <p class="text-muted fs-14 mb-0">{{ $segment ? 'Update segment details' : 'Create a new user segment' }}</p>
            </div>
            <a href="{{ route('user-segment.index') }}" class="btn btn-secondary">
                <i class="ti ti-arrow-left me-1"></i>Back to Segments
            </a>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <form action="{{ $segment ? route('user-segment.update', $segment) : route('user-segment.store') }}" method="POST" id="segmentForm">
                    @csrf
                    @if($segment)
                        @method('PUT')
                    @endif

                    <!-- Basic Information -->
                    <div class="form-section">
                        <div class="row">
                            <div class="col-md-8">
                                <div class="mb-3">
                                    <label for="name" class="form-label">Segmentation Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $segment->name ?? '') }}" required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
                                    <select class="form-select @error('status') is-invalid @enderror" id="status" name="status" data-choices required>
                                        <option value="">Select Status</option>
                                        <option value="active" {{ old('status', $segment->status ?? 'active') == 'active' ? 'selected' : '' }}>Active</option>
                                        <option value="inactive" {{ old('status', $segment->status ?? '') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                                    </select>
                                    @error('status')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Segmentation Description</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="3">{{ old('description', $segment->description ?? '') }}</textarea>
                            <small class="text-muted">Describe the purpose and criteria of this segment</small>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="revenue_ranges" class="form-label">Revenue Ranges</label>
                            <input type="text" class="form-control" id="revenue_ranges" name="revenue_ranges" value="{{ old('revenue_ranges', $segment && $segment->revenue_ranges ? json_encode($segment->revenue_ranges) : '') }}">
                            <small class="text-muted">e.g., $0-$100, $100-$500, $500+</small>
                        </div>
                    </div>

                    <!-- Demographic Attributes -->
                    <div class="section-header">
                        <i class="ti ti-users me-2"></i>Demographic Attributes
                    </div>

                    <div class="form-section">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="locations" class="form-label">Location / Region</label>
                                    <input type="text" class="form-control" id="locations" name="locations" value="{{ old('locations', $segment && $segment->locations ? json_encode($segment->locations) : '') }}">
                                    <small class="text-muted">e.g., North America, Europe, Asia</small>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="age_ranges" class="form-label">Age Range</label>
                                    <input type="text" class="form-control" id="age_ranges" name="age_ranges" value="{{ old('age_ranges', $segment && $segment->age_ranges ? json_encode($segment->age_ranges) : '') }}">
                                    <small class="text-muted">e.g., 18-24, 25-34, 35-44</small>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="genders" class="form-label">Gender</label>
                                    <input type="text" class="form-control" id="genders" name="genders" value="{{ old('genders', $segment && $segment->genders ? json_encode($segment->genders) : '') }}">
                                    <small class="text-muted">e.g., Male, Female, Non-binary</small>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="languages" class="form-label">Language / Locale</label>
                                    <input type="text" class="form-control" id="languages" name="languages" value="{{ old('languages', $segment && $segment->languages ? json_encode($segment->languages) : '') }}">
                                    <small class="text-muted">e.g., English, Spanish, French</small>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Behavioral & Account Attributes -->
                    <div class="section-header">
                        <i class="ti ti-chart-dots me-2"></i>Behavioral & Account Attributes
                    </div>

                    <div class="form-section">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="user_types" class="form-label">User Type / Role</label>
                                    <input type="text" class="form-control" id="user_types" name="user_types" value="{{ old('user_types', $segment && $segment->user_types ? json_encode($segment->user_types) : '') }}">
                                    <small class="text-muted">e.g., Admin, Manager, User</small>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="plan_types" class="form-label">Plan Type / Subscription Tier</label>
                                    <input type="text" class="form-control" id="plan_types" name="plan_types" value="{{ old('plan_types', $segment && $segment->plan_types ? json_encode($segment->plan_types) : '') }}">
                                    <small class="text-muted">e.g., Free, Basic, Premium, Enterprise</small>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="engagement_levels" class="form-label">Engagement Level</label>
                                    <input type="text" class="form-control" id="engagement_levels" name="engagement_levels" value="{{ old('engagement_levels', $segment && $segment->engagement_levels ? json_encode($segment->engagement_levels) : '') }}">
                                    <small class="text-muted">e.g., High, Medium, Low</small>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="usage_frequencies" class="form-label">Usage Frequency</label>
                                    <input type="text" class="form-control" id="usage_frequencies" name="usage_frequencies" value="{{ old('usage_frequencies', $segment && $segment->usage_frequencies ? json_encode($segment->usage_frequencies) : '') }}">
                                    <small class="text-muted">e.g., Daily, Weekly, Monthly</small>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="ti ti-check me-1"></i>{{ $segment ? 'Update Segment' : 'Create Segment' }}
                        </button>
                        <a href="{{ route('user-segment.index') }}" class="btn btn-secondary">
                            <i class="ti ti-x me-1"></i>Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/choices.js/public/assets/scripts/choices.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@yaireo/tagify"></script>
<script src="https://cdn.jsdelivr.net/npm/@yaireo/tagify/dist/tagify.polyfills.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const isEdit = {{ $segment ? 'true' : 'false' }};

    // Initialize Choices.js for the status dropdown
    const choicesElements = document.querySelectorAll('[data-choices]');
    choicesElements.forEach(function(element) {
        new Choices(element, {
            searchEnabled: false,
            itemSelectText: '',
            shouldSort: false,
            removeItemButton: false
        });
    });

    // Initialize Tagify for all tag input fields
    const tagFields = [
        'revenue_ranges', 'locations', 'age_ranges', 'genders',
        'languages', 'user_types', 'plan_types', 'engagement_levels',
        'usage_frequencies'
    ];

    const tagifyInstances = {};

    tagFields.forEach(fieldName => {
        const input = document.getElementById(fieldName);
        if (input) {
            tagifyInstances[fieldName] = new Tagify(input, {
                originalInputValueFormat: valuesArr => JSON.stringify(valuesArr.map(item => item.value))
            });
        }
    });

    // Handle form submission
    const form = document.getElementById('segmentForm');
    form.addEventListener('submit', function(e) {
        e.preventDefault();

        // Prepare FormData
        const formData = new FormData(form);

        // Process tag fields
        tagFields.forEach(fieldName => {
            const tagifyInstance = tagifyInstances[fieldName];
            if (tagifyInstance) {
                const tags = tagifyInstance.value;
                if (tags && tags.length > 0) {
                    formData.set(fieldName, JSON.stringify(tags));
                } else {
                    formData.delete(fieldName);
                }
            }
        });

        // Add method spoofing for edit
        if (isEdit) {
            formData.append('_method', 'PUT');
        }

        // Submit form via fetch
        fetch(form.action, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json'
            },
            body: formData
        })
        .then(response => {
            if (response.redirected) {
                const successMessage = isEdit ? 'User segment updated successfully!' : 'User segment created successfully!';
                sessionStorage.setItem('segment_success', successMessage);
                window.location.href = response.url;
            } else {
                return response.json();
            }
        })
        .then(data => {
            if (data && data.errors) {
                // Handle validation errors
                Object.keys(data.errors).forEach(key => {
                    const input = document.querySelector(`[name="${key}"]`);
                    if (input) {
                        input.classList.add('is-invalid');
                        const feedback = input.parentElement.querySelector('.invalid-feedback');
                        if (feedback) {
                            feedback.textContent = data.errors[key][0];
                        }
                    }
                });
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred. Please try again.');
        });
    });
});
</script>
@endpush
