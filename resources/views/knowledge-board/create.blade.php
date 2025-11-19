@extends('layouts.inspinia')

@section('title', 'Create Knowledge Board')

@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/choices.js/public/assets/styles/choices.min.css">
@include('components.filepond-styles')
<style>
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
        <div class="page-title-box">
            <h4 class="page-title">Create Knowledge Board</h4>
            <p class="text-muted fs-14 mb-0">Create a new knowledge board with documentation</p>
        </div>
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="ti ti-circle-check me-2"></i>{{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="ti ti-alert-circle me-2"></i>{{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <form action="{{ route('knowledge-board.store') }}" method="POST" enctype="multipart/form-data" id="knowledgeBoardForm">
                    @csrf

                    <!-- Knowledge Board Name -->
                    <div class="mb-3">
                        <label for="name" class="form-label">Knowledge Board Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" placeholder="Enter board name" value="{{ old('name') }}" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Short Description -->
                    <div class="mb-3">
                        <label for="short_description" class="form-label">Short Description <span class="text-danger">*</span></label>
                        <textarea class="form-control @error('short_description') is-invalid @enderror" id="short_description" name="short_description" rows="3" placeholder="Enter a brief description..." required>{{ old('short_description') }}</textarea>
                        @error('short_description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Cover Page -->
                    <div class="mb-3">
                        <label for="cover_page" class="form-label">Cover Page <span class="text-danger">*</span></label>
                        <input type="file" class="filepond" id="cover_page" name="cover_page" accept="image/*">
                        @error('cover_page')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">Upload a cover image for the knowledge board (Max: 2MB, Formats: JPEG, PNG, JPG, GIF)</small>
                    </div>

                    <div class="row">
                        <!-- Document Type -->
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="document_type" class="form-label">Document Type <span class="text-danger">*</span></label>
                                <select class="form-select @error('document_type') is-invalid @enderror" id="document_type" name="document_type" data-choices required>
                                    <option value="">Select Document Type</option>
                                    <option value="manual" {{ old('document_type') == 'manual' ? 'selected' : '' }}>Manual</option>
                                    <option value="help_document" {{ old('document_type') == 'help_document' ? 'selected' : '' }}>Help Document</option>
                                </select>
                                @error('document_type')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Visibility Type -->
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="visibility_type" class="form-label">Visibility Type <span class="text-danger">*</span></label>
                                <select class="form-select @error('visibility_type') is-invalid @enderror" id="visibility_type" name="visibility_type" data-choices required>
                                    <option value="">Select Visibility</option>
                                    <option value="private" {{ old('visibility_type') == 'private' ? 'selected' : '' }}>Private</option>
                                    <option value="public" {{ old('visibility_type') == 'public' ? 'selected' : '' }}>Public</option>
                                </select>
                                @error('visibility_type')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <!-- Status -->
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
                                <select class="form-select @error('status') is-invalid @enderror" id="status" name="status" data-choices required>
                                    <option value="">Select Status</option>
                                    <option value="published" {{ old('status') == 'published' ? 'selected' : '' }}>Published</option>
                                    <option value="unpublished" {{ old('status') == 'unpublished' ? 'selected' : '' }}>Unpublished</option>
                                    <option value="draft" {{ old('status', 'draft') == 'draft' ? 'selected' : '' }}>Draft</option>
                                </select>
                                @error('status')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Board Owner -->
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="board_owner_id" class="form-label">Board Owner <span class="text-danger">*</span></label>
                                <select class="form-select @error('board_owner_id') is-invalid @enderror" id="board_owner_id" name="board_owner_id" data-choices required>
                                    <option value="">Select Board Owner</option>
                                    @foreach($teamMembers as $member)
                                        <option value="{{ $member->id }}" {{ old('board_owner_id', Auth::id()) == $member->id ? 'selected' : '' }}>
                                            {{ $member->name }} ({{ $member->email }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('board_owner_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Generate Public URL -->
                    <div class="mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="has_public_url" name="has_public_url" value="1" {{ old('has_public_url') ? 'checked' : '' }}>
                            <label class="form-check-label" for="has_public_url">
                                Generate Unique Public URL
                            </label>
                        </div>
                        <small class="text-muted">Enable this to generate a unique URL for public access to this board</small>
                    </div>

                    <!-- Submit Buttons -->
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="ti ti-check me-1"></i>Create Knowledge Board
                        </button>
                        <button type="reset" class="btn btn-secondary">
                            <i class="ti ti-refresh me-1"></i>Reset
                        </button>
                        <a href="{{ route('knowledge-board.index') }}" class="btn btn-light">
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
@include('components.filepond-scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize FilePond with same 16:9 aspect ratio as changelog
    const pond = initFilePond('input[type="file"].filepond', {
        imageCropAspectRatio: '16:9',
        imageResizeTargetWidth: 1200,
        imageResizeTargetHeight: 675,
        imageResizeMode: 'cover',
        imageResizeUpscale: false
    });

    // Initialize Choices.js for all dropdowns
    const choicesElements = document.querySelectorAll('[data-choices]');

    choicesElements.forEach(function(element) {
        new Choices(element, {
            searchEnabled: true,
            itemSelectText: '',
            shouldSort: false,
            removeItemButton: false
        });
    });

    // Handle form submission
    const form = document.querySelector('#knowledgeBoardForm');

    form.addEventListener('submit', function(e) {
        e.preventDefault();

        // Check if FilePond has a file
        const files = pond.getFiles();
        if (files.length === 0) {
            alert('Please upload a cover image');
            return false;
        }

        // Create FormData and append all form fields
        const formData = new FormData();

        // Add all text inputs
        formData.append('_token', document.querySelector('input[name="_token"]').value);
        formData.append('name', document.getElementById('name').value);
        formData.append('short_description', document.getElementById('short_description').value);
        formData.append('document_type', document.getElementById('document_type').value);
        formData.append('visibility_type', document.getElementById('visibility_type').value);
        formData.append('status', document.getElementById('status').value);
        formData.append('board_owner_id', document.getElementById('board_owner_id').value);

        // Add checkbox value
        if (document.getElementById('has_public_url').checked) {
            formData.append('has_public_url', '1');
        }

        // Add the file from FilePond
        const file = files[0].file;
        formData.append('cover_page', file);

        // Submit via fetch
        fetch(form.action, {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => {
            if (response.ok) {
                window.location.href = response.url;
            } else {
                return response.text().then(text => {
                    throw new Error('Upload failed');
                });
            }
        })
        .catch(error => {
            alert('Error creating knowledge board. Please try again.');
            console.error('Error:', error);
        });
    });
});
</script>
@endpush
