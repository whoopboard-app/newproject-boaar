@extends('layouts.inspinia')

@section('title', $changelog ? 'Edit Changelog' : 'Add Changelog')

@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/choices.js/public/assets/styles/choices.min.css">
<link href="https://cdn.jsdelivr.net/npm/@yaireo/tagify/dist/tagify.css" rel="stylesheet" type="text/css">
@include('components.filepond-styles')
@include('components.quill-styles')
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

    .tagify {
        min-height: 39.51px !important;
        padding: 0.375rem 0.75rem !important;
        border: 1px solid #dee2e6 !important;
        border-radius: 0.25rem !important;
    }

    .tagify__input {
        margin: 0 !important;
        padding: 0 !important;
    }

    .tagify__tag {
        margin: 2px !important;
    }

    .filepond--root {
        margin-bottom: 0;
    }
</style>
@endpush

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <h4 class="page-title">{{ $changelog ? 'Edit Changelog' : 'Add Changelog' }}</h4>
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
                <form action="{{ $changelog ? route('changelog.update', $changelog) : route('changelog.store') }}" method="POST" enctype="multipart/form-data" id="changelogForm">
                    @csrf
                    @if($changelog)
                        @method('PUT')
                    @endif

                    <!-- Changelog Title -->
                    <div class="mb-3">
                        <label for="title" class="form-label">Changelog Title <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('title') is-invalid @enderror" id="title" name="title" placeholder="Enter changelog title" value="{{ old('title', $changelog->title ?? '') }}" required>
                        @error('title')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">Provide a clear and concise title for this changelog entry</small>
                    </div>

                    <!-- Cover Image -->
                    <div class="mb-3">
                        <label for="cover_image" class="form-label">Cover Image</label>
                        <input type="file" class="filepond" id="cover_image" name="cover_image" accept="image/*" data-existing-image="{{ $changelog && $changelog->cover_image ? asset('storage/' . $changelog->cover_image) : '' }}">
                        @error('cover_image')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">Upload a cover image for the changelog (Optional, Max: 2MB, Formats: JPEG, PNG, JPG, GIF)@if($changelog) - <strong>Leave empty to keep existing image</strong>@endif</small>
                    </div>

                    <!-- Short Description -->
                    <div class="mb-3">
                        <label for="short_description" class="form-label">Short Description <span class="text-danger">*</span></label>
                        <textarea class="form-control @error('short_description') is-invalid @enderror" id="short_description" name="short_description" rows="3" maxlength="200" placeholder="Enter short description..." required>{{ old('short_description', $changelog->short_description ?? '') }}</textarea>
                        @error('short_description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="d-flex justify-content-between">
                            <small class="text-muted">Maximum 200 characters</small>
                            <small class="text-muted"><span id="char-count">0</span> / 200 characters</small>
                        </div>
                    </div>

                    <!-- Full Description -->
                    <div class="mb-3">
                        <label for="description" class="form-label">Enter Descriptions <span class="text-danger">*</span></label>
                        <div class="quill-editor-wrapper @error('description') is-invalid @enderror">
                            <div id="quill-editor"></div>
                        </div>
                        <input type="hidden" id="description" name="description" value="{{ old('description', $changelog->description ?? '') }}">
                        @error('description')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">Provide detailed information about the changelog using the rich text editor</small>
                    </div>

                    <!-- Category -->
                    <div class="mb-3">
                        <label for="category" class="form-label">Category <span class="text-danger">*</span></label>
                        <select class="form-select @error('category') is-invalid @enderror" id="category" name="category[]" data-choices multiple required>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ in_array($category->id, old('category', $changelog && $changelog->category_id ? [$changelog->category_id] : [])) ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('category')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">You can select multiple categories</small>
                    </div>

                    <!-- Tags -->
                    <div class="mb-3">
                        <label for="tags" class="form-label">Tags</label>
                        <input type="text" class="form-control" id="tags" name="tags" placeholder="Add tags..." value="{{ old('tags', $changelog && $changelog->tags ? json_encode($changelog->tags) : '') }}">
                        <small class="text-muted">Press Enter to add multiple tags. Previously used tags will appear as suggestions.</small>
                    </div>

                    <!-- Author Name -->
                    <div class="mb-3">
                        <label for="author_name" class="form-label">Author Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('author_name') is-invalid @enderror" id="author_name" name="author_name" placeholder="Enter author name" value="{{ old('author_name', $changelog->author_name ?? Auth::user()->name) }}" required>
                        @error('author_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Published Date -->
                    <div class="mb-3">
                        <label for="published_date" class="form-label">Published Date <span class="text-danger">*</span></label>
                        <input type="date" class="form-control @error('published_date') is-invalid @enderror" id="published_date" name="published_date" value="{{ old('published_date', $changelog ? $changelog->published_date->format('Y-m-d') : date('Y-m-d')) }}" required>
                        @error('published_date')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">Select a future date to schedule publication</small>
                    </div>

                    <!-- Changelog Status -->
                    <div class="mb-3">
                        <label for="status" class="form-label">Changelog Status <span class="text-danger">*</span></label>
                        <select class="form-select @error('status') is-invalid @enderror" id="status" name="status" data-choices required>
                            <option value="">Select Status</option>
                            <option value="published" {{ old('status', $changelog->status ?? 'published') == 'published' ? 'selected' : '' }}>Published</option>
                            <option value="draft" {{ old('status', $changelog->status ?? '') == 'draft' ? 'selected' : '' }}>Draft</option>
                            <option value="scheduled" style="display: none;" {{ old('status', $changelog->status ?? '') == 'scheduled' ? 'selected' : '' }}>Scheduled for Published</option>
                        </select>
                        @error('status')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted" id="status-help">Current date selected - choose Published or Draft</small>
                    </div>

                    <!-- Submit Buttons -->
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="ti ti-check me-1"></i>{{ $changelog ? 'Update Changelog' : 'Save Changelog' }}
                        </button>
                        <button type="reset" class="btn btn-secondary">
                            <i class="ti ti-refresh me-1"></i>Reset
                        </button>
                        <a href="{{ $changelog ? route('changelog.index') : route('dashboard') }}" class="btn btn-light">
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
@include('components.filepond-scripts')
@include('components.quill-scripts')
<script>

document.addEventListener('DOMContentLoaded', function() {
    const isEdit = {{ $changelog ? 'true' : 'false' }};

    // Initialize Quill editor
    const descriptionInput = document.getElementById('description');
    const quillEditor = initQuill('#quill-editor', {
        placeholder: 'Enter detailed description...',
        initialContent: descriptionInput.value
    });

    // Sync Quill content to hidden input on text change
    quillEditor.on('text-change', function() {
        descriptionInput.value = quillEditor.root.innerHTML;
    });

    // Initialize FilePond with custom 16:9 aspect ratio for changelog
    const pond = initFilePond('input[type="file"].filepond', {
        imageCropAspectRatio: '16:9',
        imageResizeTargetWidth: 1200,
        imageResizeTargetHeight: 675,
        imageResizeMode: 'cover',
        imageResizeUpscale: false,
        required: false  // Optional field
    });

    // Handle form submission - ensure FilePond processes the file
    const form = document.querySelector('#changelogForm');
    const shortDescTextarea = document.getElementById('short_description');

    form.addEventListener('submit', function(e) {
        e.preventDefault();

        // Check if FilePond has a file (optional field, no validation needed)
        const files = pond.getFiles();

        // Create FormData and append all form fields
        const formData = new FormData();

        // Add all text inputs
        formData.append('_token', document.querySelector('input[name="_token"]').value);

        // Add _method for PUT when editing
        if (isEdit) {
            formData.append('_method', 'PUT');
        }

        formData.append('title', document.getElementById('title').value);
        formData.append('short_description', shortDescTextarea.value);

        // Get description from Quill editor
        const descriptionHtml = quillEditor.root.innerHTML;
        formData.append('description', descriptionHtml);

        // Handle multiple categories
        const categorySelect = document.getElementById('category');
        const selectedCategories = Array.from(categorySelect.selectedOptions).map(option => option.value);
        selectedCategories.forEach(categoryId => {
            formData.append('category[]', categoryId);
        });

        formData.append('tags', document.getElementById('tags').value);
        formData.append('author_name', document.getElementById('author_name').value);
        formData.append('published_date', document.getElementById('published_date').value);
        formData.append('status', document.getElementById('status').value);

        // Add the file from FilePond (only if a new file is selected)
        if (files.length > 0) {
            const file = files[0].file;
            formData.append('cover_image', file);
        }

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
                // Store success message in sessionStorage
                const successMessage = isEdit ? 'Changelog updated successfully!' : 'Changelog created successfully!';
                sessionStorage.setItem('changelog_success', successMessage);

                // Redirect to listing page
                window.location.href = '{{ route("changelog.index") }}';
            } else {
                return response.text().then(text => {
                    console.error('Server response:', text);
                    alert('Failed to save changelog. Please try again.');
                    throw new Error('Upload failed: ' + response.status);
                });
            }
        })
        .catch(error => {
            alert('Error uploading changelog. Please check the console for details.');
            console.error('Error:', error);
        });
    });

    // Initialize Choices.js for all dropdowns
    const choicesElements = document.querySelectorAll('[data-choices]');
    const choicesInstances = {};

    choicesElements.forEach(function(element) {
        const isMultiple = element.hasAttribute('multiple');
        choicesInstances[element.id] = new Choices(element, {
            searchEnabled: true,
            itemSelectText: '',
            shouldSort: false,
            removeItemButton: isMultiple  // Show remove button for multiselect
        });
    });

    // Initialize Tagify for tags input with localStorage whitelist
    const tagInput = document.querySelector('#tags');

    // Get previously used tags from localStorage
    let savedTags = [];
    try {
        const storedTags = localStorage.getItem('changelog_tags');
        savedTags = storedTags ? JSON.parse(storedTags) : [];
    } catch (e) {
        savedTags = [];
    }

    const tagify = new Tagify(tagInput, {
        whitelist: savedTags,
        maxTags: 10,
        dropdown: {
            maxItems: 20,
            classname: "tags-dropdown",
            enabled: 0,
            closeOnSelect: false
        },
        placeholder: "Add tags...",
        delimiters: ",|;",
        enforceWhitelist: false
    });

    // Save tags to localStorage when form is submitted
    document.querySelector('#changelogForm').addEventListener('submit', function(e) {
        const currentTags = tagify.value.map(tag => tag.value);
        const allTags = [...new Set([...savedTags, ...currentTags])];
        localStorage.setItem('changelog_tags', JSON.stringify(allTags));
    });

    // Character counter for short description
    const shortDesc = document.getElementById('short_description');
    const charCount = document.getElementById('char-count');

    function updateCharCount() {
        charCount.textContent = shortDesc.value.length;

        if (shortDesc.value.length > 200) {
            charCount.parentElement.classList.add('text-danger');
            charCount.parentElement.classList.remove('text-muted');
        } else {
            charCount.parentElement.classList.remove('text-danger');
            charCount.parentElement.classList.add('text-muted');
        }
    }

    shortDesc.addEventListener('input', updateCharCount);
    updateCharCount(); // Initial count

    // Status logic based on published date
    const publishedDateInput = document.getElementById('published_date');
    const statusSelect = document.getElementById('status');
    const statusHelp = document.getElementById('status-help');
    const scheduledOption = statusSelect.querySelector('option[value="scheduled"]');

    function updateStatusOptions() {
        const selectedDate = new Date(publishedDateInput.value);
        const today = new Date();
        today.setHours(0, 0, 0, 0);
        selectedDate.setHours(0, 0, 0, 0);

        if (selectedDate > today) {
            // Future date - show only "Scheduled for Published" (read-only)
            scheduledOption.style.display = 'block';
            statusSelect.querySelector('option[value="published"]').style.display = 'none';
            statusSelect.querySelector('option[value="draft"]').style.display = 'none';
            statusSelect.querySelector('option[value=""]').style.display = 'none';

            // Set to scheduled and disable the select
            choicesInstances['status'].setChoiceByValue('scheduled');
            statusSelect.disabled = true;
            choicesInstances['status'].disable();

            statusHelp.textContent = 'Future date selected - Status automatically set to Scheduled';
            statusHelp.classList.add('text-info');
        } else {
            // Current or past date - show Published and Draft options
            scheduledOption.style.display = 'none';
            statusSelect.querySelector('option[value="published"]').style.display = 'block';
            statusSelect.querySelector('option[value="draft"]').style.display = 'block';
            statusSelect.querySelector('option[value=""]').style.display = 'block';

            // Enable the select
            statusSelect.disabled = false;
            choicesInstances['status'].enable();

            // If it was scheduled or empty, set to published
            if (statusSelect.value === 'scheduled' || statusSelect.value === '') {
                choicesInstances['status'].setChoiceByValue('published');
            }

            statusHelp.textContent = 'Current date selected - choose Published or Draft';
            statusHelp.classList.remove('text-info');
        }
    }

    // Listen for date changes
    publishedDateInput.addEventListener('change', updateStatusOptions);

    // Initial check
    updateStatusOptions();
});
</script>
@endpush
