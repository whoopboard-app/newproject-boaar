@extends('layouts.inspinia-new')
@section('title', $changelog ? 'Edit Changelog' : 'Add Changelog')

@section('content')

<!-- PAGE HEADER -->
<div class="kt-container-fixed">
    <div class="flex flex-wrap items-center lg:items-end justify-between gap-5 pb-7.5">
        <div class="flex flex-col justify-center gap-2">
            <h1 class="text-xl font-medium leading-none text-mono">
                Settings - Plain
            </h1>
            <div class="flex items-center gap-2 text-sm font-normal text-secondary-foreground">
                Clean, Efficient User Experience
            </div>
        </div>
        <div class="flex items-center gap-2.5">
            <a class="kt-btn kt-btn-outline" href="#">
                Public Profile
            </a>
            <a class="kt-btn kt-btn-primary" href="#">
                Get Started
            </a>
        </div>
    </div>
</div>

<!-- MAIN FORM -->
<div class="kt-container-fixed">
    <div class="grid gap-5 lg:gap-7.5 xl:w-[45rem] mx-auto">
        <div class="kt-card pb-2.5">

            <!-- HEADER -->
            <div class="kt-card-header">
                <h3 class="kt-card-title">{{ $changelog ? 'Edit Changelog' : 'Add Changelog' }}</h3>
            </div>

            <!-- FORM START -->
            <form action="{{ $changelog ? route('changelog.update', $changelog) : route('changelog.store') }}"
                method="POST" enctype="multipart/form-data" id="changelogForm">
                @csrf
                @if($changelog) @method('PUT') @endif

                <div class="kt-card-content grid gap-5">

                    <!-- TITLE -->
                    <div class="flex flex-col lg:flex-row gap-2.5">
                        <label class="kt-form-label max-w-56">Changelog Title *</label>
                        <input class="kt-input" id="title" name="title"
                            value="{{ old('title', $changelog->title ?? '') }}"
                            placeholder="Enter changelog title" required>
                    </div>

                    <!-- COVER IMAGE -->
                    <div class="flex flex-col gap-2.5">
                        <label class="kt-form-label">Cover Image</label>
                        <input type="file" class="filepond" id="cover_image" name="cover_image" accept="image/*" data-existing-image="{{ $changelog && $changelog->cover_image ? asset('storage/' . $changelog->cover_image) : '' }}">
                        @error('cover_image')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">
                            Max 2MB — JPG, PNG, GIF. Leave empty to keep existing image.
                        </small>
                    </div>



                    <!-- SHORT DESCRIPTION -->
                    <div class="flex flex-col lg:flex-row gap-2.5">
                        <label class="kt-form-label max-w-56">Short Description *</label>
                        <div class="w-full">
                            <textarea class="kt-textarea @error('short_description') is-invalid @enderror" id="short_description" name="short_description" rows="3" maxlength="200" placeholder="Enter short description..." required>{{ old('short_description', $changelog->short_description ?? '') }}</textarea>

                            @error('short_description')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="d-flex justify-content-between">
                                <small class="text-muted">Maximum 200 characters</small>
                                <small class="text-muted"><span id="char-count">0</span> / 200 characters</small>
                            </div>
                        </div>
                    </div>

                    <!-- FULL DESCRIPTION -->
                    <div class="flex flex-col gap-2.5">
                        <label for="description" class="kt-form-label">Enter Descriptions <span class="text-danger">*</span></label>

                        <!-- <div id="quill-editor"></div> -->
                        <div class="quill-editor-wrapper @error('description') is-invalid @enderror">
                            <div id="quill-editor"></div>
                        </div>
                        <input type="hidden" id="description" name="description" value="{{ old('description', $changelog->description ?? '') }}">
                        @error('description')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">Provide detailed information about the changelog using the rich text editor</small>

                    </div>

                    <!-- CATEGORY -->
                    <div class="flex flex-col lg:flex-row gap-2.5">
                        <label class="kt-form-label max-w-56">Category *</label>
                        <select class="kt-select @error('category') is-invalid @enderror" id="category" name="category[]" data-choices multiple required>
                            @php
                            $selectedCategories = old('category', $changelog ? $changelog->categories->pluck('id')->toArray() : []);
                            @endphp
                            @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ in_array($category->id, $selectedCategories) ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                            @endforeach
                        </select>
                        @error('category')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">You can select multiple categories</small>

                    </div>

                    <!-- TAGS -->
                    <div class="flex flex-col lg:flex-row gap-2.5">
                        <label class="kt-form-label max-w-56">Tags</label>

                        <input type="text" class="kt-input" id="tags" name="tags" placeholder="Add tags..." value="{{ old('tags', $changelog && $changelog->tags ? json_encode($changelog->tags) : '') }}">

                    </div>

                    <!-- AUTHOR -->
                    <div class="flex flex-col lg:flex-row gap-2.5">
                        <label class="kt-form-label max-w-56">Author Name *</label>
                        <input class="kt-input" id="author_name" name="author_name"
                            value="{{ old('author_name', $changelog->author_name ?? Auth::user()->name) }}" required>
                    </div>

                    <!-- PUBLISHED DATE -->
                    <div class="flex flex-col lg:flex-row gap-2.5">
                        <label class="kt-form-label max-w-56">Published Date *</label>
                        <input type="date" class="kt-input" id="published_date" name="published_date"
                            value="{{ old('published_date', $changelog ? $changelog->published_date->format('Y-m-d') : date('Y-m-d')) }}"
                            required>
                    </div>

                    <!-- STATUS -->
                    <div class="flex flex-col lg:flex-row gap-2.5">
                        <label class="kt-form-label max-w-56">Status *</label>
                        <div class="w-full">
                            <select class="kt-select @error('status') is-invalid @enderror" id="status" name="status" data-choices required>
                                <option value="">Select Status</option>
                                <option value="published" {{ old('status', $changelog->status ?? 'published') == 'published' ? 'selected' : '' }}>Published</option>
                                <option value="draft" {{ old('status', $changelog->status ?? '') == 'draft' ? 'selected' : '' }}>Draft</option>
                                <option value="scheduled" style="display: none;" {{ old('status', $changelog->status ?? '') == 'scheduled' ? 'selected' : '' }}>Scheduled for Published</option>
                            </select>
                            <small id="status-help" class="text-muted">
                                Current date selected — choose Published or Draft
                            </small>
                        </div>
                    </div>

                    <!-- BUTTONS -->
                    <div class="flex justify-end gap-2">
                        <button class="kt-btn kt-btn-primary">
                            {{ $changelog ? 'Update Changelog' : 'Save Changelog' }}
                        </button>
                        <button type="reset" class="kt-btn kt-btn-secondary">Reset</button>
                        <a href="{{ route('changelog.index') }}" class="kt-btn kt-btn-light">Cancel</a>
                    </div>

                </div>
            </form>

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
        const isEdit = {
            {
                $changelog ? 'true' : 'false'
            }
        };

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
            required: false // Optional field
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

            // Handle multiple categories - get values from Choices.js instance
            const categoryChoicesInstance = choicesInstances['category'];
            const selectedCategories = categoryChoicesInstance.getValue(true); // true returns just the values
            if (Array.isArray(selectedCategories)) {
                selectedCategories.forEach(categoryId => {
                    formData.append('category[]', categoryId);
                });
            } else if (selectedCategories) {
                // Single value selected
                formData.append('category[]', selectedCategories);
            }

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
                removeItemButton: isMultiple // Show remove button for multiselect
            });
        });

        // Initialize Tagify for tags input with team-specific tags from server
        const tagInput = document.querySelector('#tags');

        // Get existing tags for this team from server
        const existingTags = @json($existingTags ?? []);

        const tagify = new Tagify(tagInput, {
            whitelist: existingTags,
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