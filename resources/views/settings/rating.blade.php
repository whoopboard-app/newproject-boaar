@extends('layouts.inspinia')

@section('title', 'Rating Settings')

@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="card top-area">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">
                    <i class="ti ti-list me-2"></i>Rating Settings
                    <p class="text-muted fs-14 mb-0">Configure how users can rate your articles and content</p>
                </h5>
                <div class="d-flex justify-content-between align-items-center">
                    <a href="{{ route('settings.index') }}" class="btn btn-secondary">
                        <i class="ti ti-arrow-left me-1"></i> Back to Settings
                    </a>                    
                </div>
            </div>
        </div>
    </div>
</div>
@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="ti ti-circle-check me-2"></i>{{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

@if($errors->any())
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="ti ti-alert-circle me-2"></i>
        <ul class="mb-0">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="ti ti-star me-2" style="color: #fd7e14;"></i>Rating Configuration
                </h5>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('settings.rating.update') }}">
                    @csrf
                    @method('PUT')

                    <!-- Question Text -->
                    <div class="row mb-4">
                        <div class="col-md-12">
                            <label for="question_text" class="form-label">Question Title <span class="text-danger">*</span></label>
                            <input type="text"
                                   class="form-control @error('question_text') is-invalid @enderror"
                                   id="question_text"
                                   name="question_text"
                                   placeholder="Did this answer your question?"
                                   value="{{ old('question_text', $settings->question_text ?? 'Did this answer your question?') }}"
                                   required>
                            <small class="text-muted">This is the prompt shown to users when asking for their rating.</small>
                            @error('question_text')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Rating Type -->
                    <div class="row mb-4">
                        <div class="col-md-12">
                            <label for="rating_type" class="form-label">Rating Type <span class="text-danger">*</span></label>
                            <select class="form-select @error('rating_type') is-invalid @enderror"
                                    id="rating_type"
                                    name="rating_type"
                                    required>
                                <option value="yes_no" {{ old('rating_type', $settings->rating_type ?? 'yes_no') == 'yes_no' ? 'selected' : '' }}>
                                    Yes / No
                                </option>
                                <option value="emoji" {{ old('rating_type', $settings->rating_type ?? '') == 'emoji' ? 'selected' : '' }}>
                                    Emoji Rating
                                </option>
                                <option value="star" {{ old('rating_type', $settings->rating_type ?? '') == 'star' ? 'selected' : '' }}>
                                    Star Rating
                                </option>
                                <option value="numeric" {{ old('rating_type', $settings->rating_type ?? '') == 'numeric' ? 'selected' : '' }}>
                                    Numeric Count Rating
                                </option>
                                <option value="comment_only" {{ old('rating_type', $settings->rating_type ?? '') == 'comment_only' ? 'selected' : '' }}>
                                    Comment Only (No Rating)
                                </option>
                            </select>
                            <small class="text-muted">Choose how users will rate the content.</small>
                            @error('rating_type')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Rating Type Preview -->
                    <div class="row mb-4">
                        <div class="col-md-12">
                            <label class="form-label">Preview</label>
                            <div class="card bg-light border">
                                <div class="card-body text-center py-4">
                                    <p class="mb-3 fw-medium" id="preview-question">{{ $settings->question_text ?? 'Did this answer your question?' }}</p>

                                    <!-- Yes/No Preview -->
                                    <div id="preview-yes_no" class="rating-preview">
                                        <button type="button" class="btn btn-outline-success me-2">
                                            <i class="ti ti-thumb-up me-1"></i> Yes
                                        </button>
                                        <button type="button" class="btn btn-outline-danger">
                                            <i class="ti ti-thumb-down me-1"></i> No
                                        </button>
                                    </div>

                                    <!-- Emoji Preview -->
                                    <div id="preview-emoji" class="rating-preview" style="display: none;">
                                        <span class="fs-2 me-2 cursor-pointer" title="Very Unhappy">&#128545;</span>
                                        <span class="fs-2 me-2 cursor-pointer" title="Unhappy">&#128577;</span>
                                        <span class="fs-2 me-2 cursor-pointer" title="Neutral">&#128528;</span>
                                        <span class="fs-2 me-2 cursor-pointer" title="Happy">&#128578;</span>
                                        <span class="fs-2 cursor-pointer" title="Very Happy">&#128512;</span>
                                    </div>

                                    <!-- Star Preview -->
                                    <div id="preview-star" class="rating-preview" style="display: none;">
                                        <i class="ti ti-star-filled fs-2 me-1" style="color: #ffc107;"></i>
                                        <i class="ti ti-star-filled fs-2 me-1" style="color: #ffc107;"></i>
                                        <i class="ti ti-star-filled fs-2 me-1" style="color: #ffc107;"></i>
                                        <i class="ti ti-star fs-2 me-1" style="color: #ffc107;"></i>
                                        <i class="ti ti-star fs-2" style="color: #ffc107;"></i>
                                    </div>

                                    <!-- Numeric Preview -->
                                    <div id="preview-numeric" class="rating-preview" style="display: none;">
                                        <div class="btn-group" role="group">
                                            @for($i = 1; $i <= 10; $i++)
                                                <button type="button" class="btn btn-outline-primary">{{ $i }}</button>
                                            @endfor
                                        </div>
                                    </div>

                                    <!-- Comment Only Preview -->
                                    <div id="preview-comment_only" class="rating-preview" style="display: none;">
                                        <textarea class="form-control" rows="3" placeholder="Share your feedback..." readonly></textarea>
                                        <button type="button" class="btn btn-primary mt-2">Submit Feedback</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Apply To Section -->
                    <div class="row mb-4">
                        <div class="col-md-12">
                            <label class="form-label">Applies To</label>
                            <div class="card bg-light border">
                                <div class="card-body">
                                    <div class="form-check form-switch mb-3">
                                        <input class="form-check-input"
                                               type="checkbox"
                                               id="apply_to_changelog"
                                               name="apply_to_changelog"
                                               value="1"
                                               {{ old('apply_to_changelog', $settings->apply_to_changelog ?? true) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="apply_to_changelog">
                                            <i class="ti ti-news me-1"></i> Change Log
                                            <small class="text-muted d-block">Show rating widget on changelog entries</small>
                                        </label>
                                    </div>

                                    <div class="form-check form-switch">
                                        <input class="form-check-input"
                                               type="checkbox"
                                               id="apply_to_knowledge_board"
                                               name="apply_to_knowledge_board"
                                               value="1"
                                               {{ old('apply_to_knowledge_board', $settings->apply_to_knowledge_board ?? true) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="apply_to_knowledge_board">
                                            <i class="ti ti-book me-1"></i> Knowledge Board
                                            <small class="text-muted d-block">Show rating widget on knowledge base articles</small>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="row">
                        <div class="col-md-12">
                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-primary">
                                    <i class="ti ti-device-floppy me-1"></i> Save Settings
                                </button>
                                <a href="{{ route('settings.index') }}" class="btn btn-secondary">
                                    Cancel
                                </a>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Info Sidebar -->
    <div class="col-lg-4">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="ti ti-info-circle me-2"></i>About Ratings
                </h5>
            </div>
            <div class="card-body">
                <p class="text-muted mb-3">
                    Ratings help you understand how useful your content is to users. Choose a rating type that best fits your audience.
                </p>

                <h6 class="fw-semibold">Rating Types:</h6>
                <ul class="text-muted small">
                    <li class="mb-2"><strong>Yes/No:</strong> Simple binary feedback, great for quick responses.</li>
                    <li class="mb-2"><strong>Emoji:</strong> Visual and engaging, allows nuanced feedback.</li>
                    <li class="mb-2"><strong>Star Rating:</strong> Classic 5-star system, familiar to most users.</li>
                    <li class="mb-2"><strong>Numeric:</strong> 1-10 scale for detailed feedback granularity.</li>
                    <li class="mb-2"><strong>Comment Only:</strong> Open-ended feedback without rating.</li>
                </ul>

                <div class="alert alert-info mb-0">
                    <i class="ti ti-bulb me-2"></i>
                    <small>Tip: Yes/No ratings tend to have higher response rates due to their simplicity.</small>
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
    const alerts = document.querySelectorAll('.alert.alert-dismissible');
    alerts.forEach(function(alert) {
        setTimeout(function() {
            alert.classList.remove('show');
            setTimeout(function() {
                alert.remove();
            }, 150);
        }, 3000);
    });

    // Rating type preview toggle
    const ratingTypeSelect = document.getElementById('rating_type');
    const previews = document.querySelectorAll('.rating-preview');

    function showPreview(type) {
        previews.forEach(function(preview) {
            preview.style.display = 'none';
        });
        const activePreview = document.getElementById('preview-' + type);
        if (activePreview) {
            activePreview.style.display = 'block';
        }
    }

    // Show initial preview
    showPreview(ratingTypeSelect.value);

    // Change preview on select change
    ratingTypeSelect.addEventListener('change', function() {
        showPreview(this.value);
    });

    // Update preview question text
    const questionInput = document.getElementById('question_text');
    const previewQuestion = document.getElementById('preview-question');

    questionInput.addEventListener('input', function() {
        previewQuestion.textContent = this.value || 'Did this answer your question?';
    });
});
</script>
@endpush
