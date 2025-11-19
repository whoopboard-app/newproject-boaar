@extends('layouts.inspinia')

@section('title', 'Add Article - ' . $knowledgeBoard->name)

@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/choices.js/public/assets/styles/choices.min.css">
<link href="https://cdn.jsdelivr.net/npm/@yaireo/tagify/dist/tagify.css" rel="stylesheet" type="text/css">
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
</style>
@endpush

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <h4 class="page-title">Add Article</h4>
            <p class="text-muted fs-14 mb-0">Create a new article for {{ $knowledgeBoard->name }}</p>
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
                <form action="{{ route('board-article.store', $knowledgeBoard) }}" method="POST" id="articleForm" enctype="multipart/form-data">
                    @csrf

                    <!-- Board Name (Pre-populated, readonly) -->
                    <div class="mb-3">
                        <label for="board_name" class="form-label">Board Name</label>
                        <input type="text" class="form-control" id="board_name" value="{{ $knowledgeBoard->name }}" readonly>
                        <small class="text-muted">This article will be added to this board</small>
                    </div>

                    <!-- Article Title -->
                    <div class="mb-3">
                        <label for="article_title" class="form-label">Article Title <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('article_title') is-invalid @enderror" id="article_title" name="article_title" placeholder="Enter article title" value="{{ old('article_title') }}" required>
                        @error('article_title')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Select Board Category -->
                    <div class="mb-3">
                        <label for="board_category_id" class="form-label">Select Board Category <span class="text-danger">*</span></label>
                        <select class="form-select @error('board_category_id') is-invalid @enderror" id="board_category_id" name="board_category_id" data-choices required>
                            <option value="">Select Category</option>
                            @foreach($boardCategories as $parent)
                                <optgroup label="{{ $parent->category_name }}">
                                    <option value="{{ $parent->id }}" {{ old('board_category_id') == $parent->id ? 'selected' : '' }}>
                                        {{ $parent->category_name }} (Parent)
                                    </option>
                                    @foreach($parent->childCategories as $child)
                                        <option value="{{ $child->id }}" {{ old('board_category_id') == $child->id ? 'selected' : '' }}>
                                            &nbsp;&nbsp;&nbsp;↳ {{ $child->category_name }} (Subcategory)
                                        </option>
                                        @foreach($child->childCategories as $subChild)
                                            <option value="{{ $subChild->id }}" {{ old('board_category_id') == $subChild->id ? 'selected' : '' }}>
                                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;↳ {{ $subChild->category_name }} (Sub-subcategory)
                                            </option>
                                        @endforeach
                                    @endforeach
                                </optgroup>
                            @endforeach
                        </select>
                        @error('board_category_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Detailed Post -->
                    <div class="mb-3">
                        <label for="detailed_post" class="form-label">Detailed Post <span class="text-danger">*</span></label>
                        <textarea class="form-control @error('detailed_post') is-invalid @enderror" id="detailed_post" name="detailed_post" rows="10" placeholder="Write your detailed article content here..." required>{{ old('detailed_post') }}</textarea>
                        @error('detailed_post')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">You can use HTML formatting in your content</small>
                    </div>

                    <!-- Cover Image -->
                    <div class="mb-3">
                        <label for="cover_image" class="form-label">Cover Image <span class="text-danger">*</span></label>
                        <input type="file" class="filepond @error('cover_image') is-invalid @enderror" id="cover_image" name="cover_image" accept="image/*" required>
                        @error('cover_image')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Tags -->
                    <div class="mb-3">
                        <label for="tags" class="form-label">Tags</label>
                        <input type="text" class="form-control" id="tags" name="tags" placeholder="Add tags..." value="{{ old('tags') }}">
                        <small class="text-muted">Press Enter to add multiple tags. Previously used tags will appear as suggestions.</small>
                    </div>

                    <!-- Changelog Categories (Multiple Select) -->
                    <div class="mb-3">
                        <label for="changelog_categories" class="form-label">Changelog Categories (Optional)</label>
                        <select class="form-select @error('changelog_categories') is-invalid @enderror" id="changelog_categories" name="changelog_categories[]" data-choices-multiple multiple>
                            @foreach($changelogCategories as $category)
                                <option value="{{ $category->id }}" {{ (is_array(old('changelog_categories')) && in_array($category->id, old('changelog_categories'))) ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('changelog_categories')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">Select related changelog categories</small>
                    </div>

                    <!-- Author -->
                    <div class="mb-3">
                        <label for="author_id" class="form-label">Author <span class="text-danger">*</span></label>
                        <select class="form-select @error('author_id') is-invalid @enderror" id="author_id" name="author_id" data-choices required>
                            <option value="">Select Author</option>
                            @foreach($authors as $author)
                                <option value="{{ $author->id }}" {{ old('author_id', auth()->id()) == $author->id ? 'selected' : '' }}>
                                    {{ $author->name }} ({{ $author->email }})
                                </option>
                            @endforeach
                        </select>
                        @error('author_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Display as Popular Article -->
                    <div class="mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="display_as_popular" name="display_as_popular" value="1" {{ old('display_as_popular') ? 'checked' : '' }}>
                            <label class="form-check-label" for="display_as_popular">
                                Display as Popular Article
                            </label>
                        </div>
                        <small class="text-muted">Check this to feature this article in the popular section</small>
                    </div>

                    <!-- Status -->
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

                    <!-- Submit Buttons -->
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="ti ti-check me-1"></i>Create Article
                        </button>
                        <button type="reset" class="btn btn-secondary">
                            <i class="ti ti-refresh me-1"></i>Reset
                        </button>
                        <a href="{{ route('knowledge-board.show', $knowledgeBoard) }}" class="btn btn-light">
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
@include('components.filepond-scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize Choices.js for single select dropdowns
    const singleChoices = document.querySelectorAll('[data-choices]:not([data-choices-multiple])');
    singleChoices.forEach(function(element) {
        new Choices(element, {
            searchEnabled: true,
            itemSelectText: '',
            shouldSort: false,
            removeItemButton: false
        });
    });

    // Initialize Choices.js for multiple select dropdowns
    const multipleChoices = document.querySelectorAll('[data-choices-multiple]');
    multipleChoices.forEach(function(element) {
        new Choices(element, {
            searchEnabled: true,
            removeItemButton: true,
            shouldSort: false,
            itemSelectText: 'Press to select',
        });
    });

    // Initialize Tagify for tags input with localStorage whitelist
    const tagInput = document.querySelector('#tags');

    // Get previously used tags from localStorage (shared with changelog)
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
        editTags: 1,
        backspace: true
    });

    // Save tags to localStorage when form is submitted
    document.getElementById('articleForm').addEventListener('submit', function() {
        const currentTags = tagify.value.map(tag => tag.value);
        const allTags = [...new Set([...savedTags, ...currentTags])];
        localStorage.setItem('changelog_tags', JSON.stringify(allTags));
    });

    // Initialize FilePond with same 16:9 aspect ratio as changelog
    const pond = initFilePond('.filepond', {
        imageCropAspectRatio: '16:9',
        imageResizeTargetWidth: 1200,
        imageResizeTargetHeight: 675,
        imageResizeMode: 'cover',
        imageResizeUpscale: false
    });

    // Handle form submission with FilePond
    const form = document.getElementById('articleForm');
    form.addEventListener('submit', function(e) {
        e.preventDefault();

        const files = pond.getFiles();
        if (files.length === 0) {
            alert('Please upload a cover image');
            return false;
        }

        // Create FormData and append all form fields
        const formData = new FormData();

        // Add CSRF token
        formData.append('_token', document.querySelector('input[name="_token"]').value);

        // Add text inputs
        formData.append('article_title', document.getElementById('article_title').value);
        formData.append('board_category_id', document.getElementById('board_category_id').value);
        formData.append('detailed_post', document.getElementById('detailed_post').value);
        formData.append('tags', document.getElementById('tags').value);
        formData.append('author_id', document.getElementById('author_id').value);
        formData.append('status', document.getElementById('status').value);

        // Add display_as_popular checkbox
        if (document.getElementById('display_as_popular').checked) {
            formData.append('display_as_popular', '1');
        }

        // Add changelog categories (multiple select)
        const changelogCategoriesSelect = document.getElementById('changelog_categories');
        if (changelogCategoriesSelect) {
            const selectedOptions = Array.from(changelogCategoriesSelect.selectedOptions);
            selectedOptions.forEach((option, index) => {
                formData.append('changelog_categories[]', option.value);
            });
        }

        // Add the file from FilePond
        const file = files[0].file;
        formData.append('cover_image', file);

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
            alert('Error creating article. Please try again.');
            console.error('Error:', error);
        });
    });
});
</script>
@endpush
