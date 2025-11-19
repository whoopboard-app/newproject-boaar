@extends('layouts.inspinia')

@section('title', ($category ? 'Edit Category' : 'Add Category') . ' - ' . $knowledgeBoard->name)

@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/choices.js/public/assets/styles/choices.min.css">
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
            <h4 class="page-title">{{ $category ? 'Edit Category' : 'Add Category' }}</h4>
            <p class="text-muted fs-14 mb-0">{{ $category ? 'Update category for' : 'Create a new category for' }} {{ $knowledgeBoard->name }}</p>
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
                <form action="{{ $category ? route('board-category.update', [$knowledgeBoard, $category]) : route('board-category.store', $knowledgeBoard) }}" method="POST" id="categoryForm">
                    @csrf
                    @if($category)
                        @method('PUT')
                    @endif

                    <!-- Board Name (Pre-populated, editable) -->
                    <div class="mb-3">
                        <label for="board_name" class="form-label">Board Name</label>
                        <input type="text" class="form-control" id="board_name" value="{{ $knowledgeBoard->name }}" readonly>
                        <small class="text-muted">This category will be added to this board</small>
                    </div>

                    <!-- Category Name -->
                    <div class="mb-3">
                        <label for="category_name" class="form-label">Category Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('category_name') is-invalid @enderror" id="category_name" name="category_name" placeholder="Enter category name" value="{{ old('category_name', $category->category_name ?? '') }}" required>
                        @error('category_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Category Icon (Optional) -->
                    <div class="mb-3">
                        <label for="category_icon" class="form-label">Category Icon (Optional)</label>
                        <input type="text" class="form-control @error('category_icon') is-invalid @enderror" id="category_icon" name="category_icon" placeholder="e.g., ti ti-folder" value="{{ old('category_icon', $category->category_icon ?? '') }}">
                        @error('category_icon')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">Leave empty for auto-generated icon (ti ti-folder). Use Tabler Icons classes.</small>
                    </div>

                    <!-- Short Description -->
                    <div class="mb-3">
                        <label for="short_description" class="form-label">Short Description</label>
                        <textarea class="form-control @error('short_description') is-invalid @enderror" id="short_description" name="short_description" rows="3" placeholder="Enter a brief description...">{{ old('short_description', $category->short_description ?? '') }}</textarea>
                        @error('short_description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Is Parent Category -->
                    <div class="mb-3">
                        <label class="form-label">Is this a parent category? <span class="text-danger">*</span></label>
                        <div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="is_parent" id="is_parent_yes" value="1" {{ old('is_parent', $category->is_parent ?? '1') == '1' ? 'checked' : '' }} required>
                                <label class="form-check-label" for="is_parent_yes">
                                    Yes (Parent Category)
                                </label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="is_parent" id="is_parent_no" value="0" {{ old('is_parent', $category->is_parent ?? '1') == '0' ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_parent_no">
                                    No (Sub Category)
                                </label>
                            </div>
                        </div>
                    </div>

                    <!-- Parent Category (shown only if is_parent = No) -->
                    <div class="mb-3" id="parent_category_field" style="display: none;">
                        <label for="parent_category_id" class="form-label">Parent Category <span class="text-danger">*</span></label>
                        <select class="form-select @error('parent_category_id') is-invalid @enderror" id="parent_category_id" name="parent_category_id" data-choices>
                            <option value="">Select Parent Category</option>
                            @foreach($parentCategories as $parent)
                                <optgroup label="{{ $parent->category_name }}">
                                    <option value="{{ $parent->id }}" {{ old('parent_category_id', $category->parent_category_id ?? '') == $parent->id ? 'selected' : '' }}>
                                        {{ $parent->category_name }} (Parent)
                                    </option>
                                    @foreach($parent->childCategories as $child)
                                        <option value="{{ $child->id }}" {{ old('parent_category_id', $category->parent_category_id ?? '') == $child->id ? 'selected' : '' }}>
                                            &nbsp;&nbsp;&nbsp;↳ {{ $child->category_name }} (Subcategory)
                                        </option>
                                        @foreach($child->childCategories as $subChild)
                                            <option value="{{ $subChild->id }}" {{ old('parent_category_id', $category->parent_category_id ?? '') == $subChild->id ? 'selected' : '' }}>
                                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;↳ {{ $subChild->category_name }} (Sub-subcategory)
                                            </option>
                                        @endforeach
                                    @endforeach
                                </optgroup>
                            @endforeach
                        </select>
                        @error('parent_category_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">You can create up to 3 levels of categories</small>
                    </div>

                    <!-- Order -->
                    <div class="mb-3">
                        <label for="order" class="form-label">Order of Listing</label>
                        <input type="number" class="form-control @error('order') is-invalid @enderror" id="order" name="order" placeholder="Auto-incremented" value="{{ old('order', $category->order ?? '') }}">
                        @error('order')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">Leave empty for auto-increment</small>
                    </div>

                    <!-- Status -->
                    <div class="mb-3">
                        <label for="status" class="form-label">Status of Category <span class="text-danger">*</span></label>
                        <select class="form-select @error('status') is-invalid @enderror" id="status" name="status" data-choices required>
                            <option value="">Select Status</option>
                            <option value="active" {{ old('status', $category->status ?? 'active') == 'active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ old('status', $category->status ?? 'active') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                        </select>
                        @error('status')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Submit Buttons -->
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="ti ti-check me-1"></i>{{ $category ? 'Update Category' : 'Create Category' }}
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
<script>
document.addEventListener('DOMContentLoaded', function() {
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

    // Show/hide parent category field based on is_parent selection
    const isParentYes = document.getElementById('is_parent_yes');
    const isParentNo = document.getElementById('is_parent_no');
    const parentCategoryField = document.getElementById('parent_category_field');
    const parentCategorySelect = document.getElementById('parent_category_id');

    function toggleParentCategoryField() {
        if (isParentNo.checked) {
            parentCategoryField.style.display = 'block';
            parentCategorySelect.required = true;
        } else {
            parentCategoryField.style.display = 'none';
            parentCategorySelect.required = false;
            parentCategorySelect.value = '';
        }
    }

    isParentYes.addEventListener('change', toggleParentCategoryField);
    isParentNo.addEventListener('change', toggleParentCategoryField);

    // Initial check
    toggleParentCategoryField();
});
</script>
@endpush
