@extends('layouts.inspinia')

@section('title', 'Category Management')

@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/choices.js/public/assets/styles/choices.min.css">
<style>
    /* Match Choices.js height and styling with Bootstrap form-control */
    .choices__inner {
        min-height: 39.51px !important;
        height: 39.51px !important;
        padding: 0.375rem 0.75rem !important;
        background-color: #fff !important;
        border: 1px solid #dee2e6 !important;
        border-radius: 0.25rem !important;
        font-size: 1rem !important;
        line-height: 1.5 !important;
        display: flex !important;
        align-items: center !important;
    }

    .choices__list--single {
        padding: 0 !important;
        display: flex !important;
        align-items: center !important;
    }

    .choices[data-type*=select-one] .choices__inner {
        padding-bottom: 0.375rem !important;
        padding-top: 0.375rem !important;
    }

    .choices__list--dropdown .choices__item--selectable {
        padding: 0.5rem 1rem !important;
    }

    .choices__item--selectable {
        line-height: 1.5 !important;
    }
</style>
@endpush

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <h4 class="page-title">Category Management</h4>
            <p class="text-muted fs-14">Manage categories for your change log system</p>
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

<!-- Add New Category Section -->
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="ti ti-tag me-2"></i>Add New Category
                </h5>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('categories.store') }}">
                    @csrf
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="category_name" class="form-label">Category Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('category_name') is-invalid @enderror" id="category_name" name="category_name" placeholder="Enter category name" value="{{ old('category_name') }}" required>
                                @error('category_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">Enter a unique name for this category</div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
                                <select class="form-select @error('status') is-invalid @enderror" id="status" name="status" data-choices required>
                                    <option value="active" {{ old('status', 'active') == 'active' ? 'selected' : '' }}>Active</option>
                                    <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                                </select>
                                @error('status')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">Category visibility status</div>
                            </div>
                        </div>
                        <div class="col-md-2 d-flex align-items-end">
                            <div class="mb-3 w-100">
                                <button type="submit" class="btn btn-primary w-100">
                                    <i class="ti ti-plus me-1"></i>Add Category
                                </button>
                            </div>
                        </div>
                    </div>
                </form>

                <!-- Color Assignment Info -->
                <div class="alert alert-info mt-3">
                    <h6 class="alert-heading mb-2"><i class="ti ti-info-circle me-1"></i>Auto Color Assignment</h6>
                    <p class="mb-0">The system will automatically assign a unique color code to each new category for easy identification in your change log.</p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Categories Listing Section -->
<div class="row mt-3">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">
                    <i class="ti ti-list me-2"></i>All Categories
                </h5>
                <span class="badge badge-soft-success">{{ $categories->count() }} Total</span>
            </div>
            <div class="card-body">
                @if($categories->count() > 0)
                    <div class="row g-3">
                        @foreach($categories as $category)
                            <div class="col-lg-6 col-xl-4">
                                <div class="card border mb-0">
                                    <div class="card-body">
                                        <div class="d-flex align-items-center justify-content-between">
                                            <div class="d-flex align-items-center">
                                                <div class="me-3">
                                                    <div class="avatar-sm">
                                                        <span class="avatar-title rounded-circle" style="background-color: {{ $category->color }};">
                                                            <i class="ti ti-tag text-white"></i>
                                                        </span>
                                                    </div>
                                                </div>
                                                <div>
                                                    <h5 class="mb-1">{{ $category->name }}</h5>
                                                    <div class="d-flex align-items-center gap-2">
                                                        <span class="badge {{ $category->status == 'active' ? 'bg-success' : 'bg-secondary' }}">
                                                            {{ ucfirst($category->status) }}
                                                        </span>
                                                        <small class="text-muted">
                                                            <i class="ti ti-palette me-1"></i>{{ $category->color }}
                                                        </small>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="btn-group" role="group">
                                                <button type="button" class="btn btn-sm btn-soft-primary" title="Edit Category">
                                                    <i class="ti ti-edit"></i>
                                                </button>
                                                <form method="POST" action="{{ route('categories.destroy', $category) }}" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this category?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-soft-danger" title="Delete Category">
                                                        <i class="ti ti-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="ti ti-folder-off fs-1 text-muted mb-3"></i>
                        <h5 class="text-muted">No Categories Found</h5>
                        <p class="text-muted">Start by adding your first category using the form above.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/choices.js/public/assets/scripts/choices.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize all elements with data-choices attribute
        var choicesElements = document.querySelectorAll('[data-choices]');

        choicesElements.forEach(function(element) {
            new Choices(element, {
                searchEnabled: false,
                itemSelectText: '',
                removeItemButton: false,
            });
        });
    });
</script>
@endpush
