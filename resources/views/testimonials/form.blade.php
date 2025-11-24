@extends('layouts.inspinia')

@section('title', isset($testimonial) ? 'Edit Testimonial' : 'Add Testimonial')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-flex align-items-center justify-content-between">
            <div>
                <h2 class="mb-0">{{ isset($testimonial) ? 'Edit Testimonial' : 'Add Testimonial' }}</h2>
                <p class="text-muted fs-14 mb-0">{{ isset($testimonial) ? 'Update testimonial information' : 'Create a new testimonial' }}</p>
            </div>
            <div>
                <a href="{{ route('testimonials.index') }}" class="btn btn-secondary">
                    <i class="ti ti-arrow-left me-1"></i> Back to Testimonials
                </a>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Testimonial Information</h5>
            </div>
            <div class="card-body">
                <form action="{{ isset($testimonial) ? route('testimonials.update', $testimonial) : route('testimonials.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @if(isset($testimonial))
                        @method('PUT')
                    @endif

                    <!-- Type Selection -->
                    <div class="mb-3">
                        <label class="form-label">Type <span class="text-danger">*</span></label>
                        <div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="type" id="type_text" value="text"
                                    {{ old('type', $testimonial->type ?? 'text') === 'text' ? 'checked' : '' }} required>
                                <label class="form-check-label" for="type_text">
                                    <i class="ti ti-file-text me-1"></i> Text
                                </label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="type" id="type_video" value="video"
                                    {{ old('type', $testimonial->type ?? '') === 'video' ? 'checked' : '' }} required>
                                <label class="form-check-label" for="type_video">
                                    <i class="ti ti-video me-1"></i> Video
                                </label>
                            </div>
                        </div>
                        @error('type')
                            <div class="text-danger small">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Text Content (shown when type is text) -->
                    <div class="mb-3" id="text_content_field" style="display: {{ old('type', $testimonial->type ?? 'text') === 'text' ? 'block' : 'none' }};">
                        <label for="text_content" class="form-label">Testimonial Text <span class="text-danger">*</span></label>
                        <textarea class="form-control" id="text_content" name="text_content" rows="5" placeholder="Enter the testimonial text...">{{ old('text_content', $testimonial->text_content ?? '') }}</textarea>
                        @error('text_content')
                            <div class="text-danger small">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Video URL (shown when type is video) -->
                    <div class="mb-3" id="video_url_field" style="display: {{ old('type', $testimonial->type ?? 'text') === 'video' ? 'block' : 'none' }};">
                        <label for="video_url" class="form-label">Video URL <span class="text-danger">*</span></label>
                        <input type="url" class="form-control" id="video_url" name="video_url" value="{{ old('video_url', $testimonial->video_url ?? '') }}" placeholder="https://youtube.com/watch?v=...">
                        @error('video_url')
                            <div class="text-danger small">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Rating -->
                    <div class="mb-3">
                        <label for="rating" class="form-label">Rating</label>
                        <select class="form-select" id="rating" name="rating">
                            <option value="">No Rating</option>
                            @for($i = 1; $i <= 5; $i++)
                                <option value="{{ $i }}" {{ old('rating', $testimonial->rating ?? '') == $i ? 'selected' : '' }}>
                                    {{ $i }} Star{{ $i > 1 ? 's' : '' }}
                                </option>
                            @endfor
                        </select>
                        @error('rating')
                            <div class="text-danger small">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Author Name -->
                    <div class="mb-3">
                        <label for="name" class="form-label">Author Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="name" name="name" value="{{ old('name', $testimonial->name ?? '') }}" required>
                        @error('name')
                            <div class="text-danger small">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Email -->
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" name="email" value="{{ old('email', $testimonial->email ?? '') }}">
                        @error('email')
                            <div class="text-danger small">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Position -->
                    <div class="mb-3">
                        <label for="position" class="form-label">Position/Title</label>
                        <input type="text" class="form-control" id="position" name="position" value="{{ old('position', $testimonial->position ?? '') }}" placeholder="e.g., CEO, Marketing Manager">
                        @error('position')
                            <div class="text-danger small">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Company -->
                    <div class="mb-3">
                        <label for="company" class="form-label">Company</label>
                        <input type="text" class="form-control" id="company" name="company" value="{{ old('company', $testimonial->company ?? '') }}">
                        @error('company')
                            <div class="text-danger small">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Avatar -->
                    <div class="mb-3">
                        <label for="avatar" class="form-label">Avatar/Photo</label>
                        @if(isset($testimonial) && $testimonial->avatar)
                            <div class="mb-2">
                                <img src="{{ asset('storage/' . $testimonial->avatar) }}" alt="Current Avatar" class="rounded-circle" style="width: 60px; height: 60px; object-fit: cover;">
                                <small class="text-muted ms-2">Current avatar</small>
                            </div>
                        @endif
                        <input type="file" class="form-control" id="avatar" name="avatar" accept="image/*">
                        <small class="text-muted">Upload a new avatar image (optional)</small>
                        @error('avatar')
                            <div class="text-danger small">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Template -->
                    <div class="mb-3">
                        <label for="template_id" class="form-label">Template</label>
                        <select class="form-select" id="template_id" name="template_id">
                            <option value="">No Template</option>
                            @foreach($templates as $template)
                                <option value="{{ $template->id }}" {{ old('template_id', $testimonial->template_id ?? '') == $template->id ? 'selected' : '' }}>
                                    {{ $template->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('template_id')
                            <div class="text-danger small">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Source -->
                    <div class="mb-3">
                        <label for="source" class="form-label">Source <span class="text-danger">*</span></label>
                        <select class="form-select" id="source" name="source" required>
                            <option value="manual" {{ old('source', $testimonial->source ?? 'manual') === 'manual' ? 'selected' : '' }}>Manual</option>
                            <option value="email" {{ old('source', $testimonial->source ?? '') === 'email' ? 'selected' : '' }}>Email</option>
                            <option value="website" {{ old('source', $testimonial->source ?? '') === 'website' ? 'selected' : '' }}>Website</option>
                            <option value="script" {{ old('source', $testimonial->source ?? '') === 'script' ? 'selected' : '' }}>Script</option>
                        </select>
                        @error('source')
                            <div class="text-danger small">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Status -->
                    <div class="mb-3">
                        <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
                        <select class="form-select" id="status" name="status" required>
                            <option value="draft" {{ old('status', $testimonial->status ?? 'draft') === 'draft' ? 'selected' : '' }}>Draft</option>
                            <option value="pending_review" {{ old('status', $testimonial->status ?? '') === 'pending_review' ? 'selected' : '' }}>Pending Review</option>
                            <option value="under_review" {{ old('status', $testimonial->status ?? '') === 'under_review' ? 'selected' : '' }}>Under Review</option>
                            <option value="published" {{ old('status', $testimonial->status ?? '') === 'published' ? 'selected' : '' }}>Published</option>
                            <option value="active" {{ old('status', $testimonial->status ?? '') === 'active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ old('status', $testimonial->status ?? '') === 'inactive' ? 'selected' : '' }}>Inactive</option>
                        </select>
                        @error('status')
                            <div class="text-danger small">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Submit Buttons -->
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="ti ti-check me-1"></i> {{ isset($testimonial) ? 'Update' : 'Create' }} Testimonial
                        </button>
                        <a href="{{ route('testimonials.index') }}" class="btn btn-secondary">
                            Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Preview Card -->
    <div class="col-lg-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Preview</h5>
            </div>
            <div class="card-body">
                <div class="text-center mb-3">
                    <div class="rounded-circle bg-primary text-white d-inline-flex align-items-center justify-content-center" style="width: 80px; height: 80px; font-size: 2rem;" id="preview_avatar">
                        ?
                    </div>
                </div>
                <h6 class="text-center mb-1" id="preview_name">Author Name</h6>
                <p class="text-center text-muted small mb-2" id="preview_position">Position</p>
                <p class="text-center text-muted small mb-3" id="preview_company">Company</p>
                <div class="text-center mb-3" id="preview_rating">
                    <span class="text-muted">No rating</span>
                </div>
                <div class="alert alert-light" id="preview_content">
                    <em>Testimonial content will appear here...</em>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Toggle between text and video fields
    const typeRadios = document.querySelectorAll('input[name="type"]');
    const textField = document.getElementById('text_content_field');
    const videoField = document.getElementById('video_url_field');

    typeRadios.forEach(radio => {
        radio.addEventListener('change', function() {
            if (this.value === 'text') {
                textField.style.display = 'block';
                videoField.style.display = 'none';
            } else {
                textField.style.display = 'none';
                videoField.style.display = 'block';
            }
        });
    });

    // Live Preview
    const nameInput = document.getElementById('name');
    const positionInput = document.getElementById('position');
    const companyInput = document.getElementById('company');
    const ratingSelect = document.getElementById('rating');
    const textContentInput = document.getElementById('text_content');

    const previewName = document.getElementById('preview_name');
    const previewPosition = document.getElementById('preview_position');
    const previewCompany = document.getElementById('preview_company');
    const previewRating = document.getElementById('preview_rating');
    const previewContent = document.getElementById('preview_content');
    const previewAvatar = document.getElementById('preview_avatar');

    nameInput.addEventListener('input', function() {
        previewName.textContent = this.value || 'Author Name';
        previewAvatar.textContent = this.value ? this.value.charAt(0).toUpperCase() : '?';
    });

    positionInput.addEventListener('input', function() {
        previewPosition.textContent = this.value || 'Position';
    });

    companyInput.addEventListener('input', function() {
        previewCompany.textContent = this.value || 'Company';
    });

    ratingSelect.addEventListener('change', function() {
        const rating = parseInt(this.value);
        if (rating) {
            let stars = '';
            for (let i = 1; i <= 5; i++) {
                stars += `<i class="ti ti-star${i <= rating ? '-filled' : ''} text-warning"></i>`;
            }
            previewRating.innerHTML = stars;
        } else {
            previewRating.innerHTML = '<span class="text-muted">No rating</span>';
        }
    });

    textContentInput.addEventListener('input', function() {
        previewContent.innerHTML = this.value ? `<em>"${this.value}"</em>` : '<em>Testimonial content will appear here...</em>';
    });

    // Initialize preview with existing values
    if (nameInput.value) nameInput.dispatchEvent(new Event('input'));
    if (positionInput.value) positionInput.dispatchEvent(new Event('input'));
    if (companyInput.value) companyInput.dispatchEvent(new Event('input'));
    if (ratingSelect.value) ratingSelect.dispatchEvent(new Event('change'));
    if (textContentInput.value) textContentInput.dispatchEvent(new Event('input'));
});
</script>
@endpush
@endsection
