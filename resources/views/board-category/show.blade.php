@extends('layouts.inspinia')

@section('title', $category->category_name . ' - ' . $knowledgeBoard->name)

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-flex justify-content-between align-items-center">
            <div>
                <h4 class="page-title">{{ $category->category_name }}</h4>
                <p class="text-muted fs-14 mb-0">Category Details</p>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('knowledge-board.show', $knowledgeBoard) }}" class="btn btn-secondary">
                    <i class="ti ti-arrow-left me-1"></i>Back to Board
                </a>
                <a href="{{ route('board-category.edit', [$knowledgeBoard, $category]) }}" class="btn btn-primary">
                    <i class="ti ti-edit me-1"></i>Edit
                </a>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="{{ $category->category_icon ?: 'ti ti-folder' }} me-2"></i>{{ $category->category_name }}
                </h5>
            </div>
            <div class="card-body">
                @if($category->short_description)
                    <p class="text-muted">{{ $category->short_description }}</p>
                @else
                    <p class="text-muted">No description available.</p>
                @endif
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="ti ti-info-circle me-2"></i>Category Information
                </h5>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <h6 class="text-muted">Type</h6>
                    <p>{{ $category->is_parent ? 'Parent Category' : 'Sub Category' }}</p>
                </div>

                @if($category->parentCategory)
                    <div class="mb-3">
                        <h6 class="text-muted">Parent Category</h6>
                        <p>{{ $category->parentCategory->category_name }}</p>
                    </div>
                @endif

                <div class="mb-3">
                    <h6 class="text-muted">Status</h6>
                    <p>
                        @if($category->status == 'active')
                            <span class="badge bg-success">Active</span>
                        @else
                            <span class="badge bg-secondary">Inactive</span>
                        @endif
                    </p>
                </div>

                <div class="mb-3">
                    <h6 class="text-muted">Order</h6>
                    <p>{{ $category->order }}</p>
                </div>

                @if($category->childCategories && $category->childCategories->count() > 0)
                    <div class="mb-3">
                        <h6 class="text-muted">Sub Categories</h6>
                        <ul class="list-unstyled">
                            @foreach($category->childCategories as $child)
                                <li class="mb-1">
                                    <i class="{{ $child->category_icon ?: 'ti ti-folder' }} me-1"></i>
                                    {{ $child->category_name }}
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="mb-3">
                    <h6 class="text-muted">Created At</h6>
                    <p>{{ $category->created_at->format('M d, Y h:i A') }}</p>
                </div>

                <div>
                    <h6 class="text-muted">Last Updated</h6>
                    <p>{{ $category->updated_at->format('M d, Y h:i A') }}</p>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="ti ti-settings me-2"></i>Actions
                </h5>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="{{ route('board-category.edit', [$knowledgeBoard, $category]) }}" class="btn btn-primary">
                        <i class="ti ti-edit me-2"></i>Edit Category
                    </a>
                    <form action="{{ route('board-category.destroy', [$knowledgeBoard, $category]) }}" method="POST" id="deleteForm">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger w-100">
                            <i class="ti ti-trash me-2"></i>Delete Category
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const deleteForm = document.getElementById('deleteForm');
    if (deleteForm) {
        deleteForm.addEventListener('submit', function(e) {
            e.preventDefault();
            if (confirm('Are you sure you want to delete this category? This action cannot be undone.')) {
                this.submit();
            }
        });
    }
});
</script>
@endpush
