@extends('layouts.inspinia')

@section('title', $changelog->title)

@push('styles')
<style>
    .changelog-cover-large {
        width: 100%;
        max-height: 400px;
        object-fit: cover;
        border-radius: 8px;
        margin-bottom: 2rem;
    }

    .tag-badge {
        display: inline-block;
        padding: 4px 12px;
        margin: 4px;
        font-size: 0.875rem;
        border-radius: 4px;
        background-color: #e9ecef;
        color: #495057;
    }

    .info-box {
        background-color: #f8f9fa;
        border-left: 3px solid #007bff;
        padding: 1rem;
        margin-bottom: 1rem;
        border-radius: 4px;
    }

    .info-box h6 {
        color: #6c757d;
        font-size: 0.875rem;
        text-transform: uppercase;
        margin-bottom: 0.5rem;
        font-weight: 600;
    }

    .info-box p {
        margin-bottom: 0;
        font-size: 1rem;
        color: #212529;
    }

    .changelog-content {
        line-height: 1.8;
        font-size: 1rem;
    }
</style>
@endpush

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-flex justify-content-between align-items-center">
            <div>
                <h4 class="page-title">{{ $changelog->title }}</h4>
                <p class="text-muted fs-14 mb-0">Changelog Details</p>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('changelog.index') }}" class="btn btn-secondary">
                    <i class="ti ti-arrow-left me-1"></i>Back to List
                </a>
                <a href="{{ route('changelog.edit', $changelog) }}" class="btn btn-primary">
                    <i class="ti ti-edit me-1"></i>Edit
                </a>
            </div>
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
    <!-- Main Content -->
    <div class="col-lg-8">
        <div class="card">
            <div class="card-body">
                <!-- Cover Image -->
                @if($changelog->cover_image)
                    <img src="{{ asset('storage/' . $changelog->cover_image) }}" alt="{{ $changelog->title }}" class="changelog-cover-large">
                @endif

                <!-- Title -->
                <h2 class="mb-3">{{ $changelog->title }}</h2>

                <!-- Short Description -->
                <div class="alert alert-info">
                    <i class="ti ti-info-circle me-2"></i>{{ $changelog->short_description }}
                </div>

                <!-- Full Description -->
                <div class="changelog-content">
                    <h5 class="mb-3">Description</h5>
                    <div>
                        {!! nl2br(e($changelog->description)) !!}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Sidebar -->
    <div class="col-lg-4">
        <!-- Status Card -->
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="ti ti-info-circle me-2"></i>Changelog Information
                </h5>
            </div>
            <div class="card-body">
                <!-- Status -->
                <div class="info-box">
                    <h6>Status</h6>
                    <p>
                        @if($changelog->status == 'published')
                            <span class="badge bg-success">Published</span>
                        @elseif($changelog->status == 'draft')
                            <span class="badge bg-secondary">Draft</span>
                        @else
                            <span class="badge bg-info">Scheduled</span>
                        @endif
                    </p>
                </div>

                <!-- Category -->
                <div class="info-box">
                    <h6>Category</h6>
                    <p>
                        <span class="badge" style="background-color: {{ $changelog->category->color }};">
                            {{ $changelog->category->name }}
                        </span>
                    </p>
                </div>

                <!-- Author -->
                <div class="info-box">
                    <h6>Author</h6>
                    <p>{{ $changelog->author_name }}</p>
                </div>

                <!-- Published Date -->
                <div class="info-box">
                    <h6>Published Date</h6>
                    <p>{{ $changelog->published_date->format('F d, Y') }}</p>
                </div>

                <!-- Tags -->
                @if($changelog->tags && count($changelog->tags) > 0)
                    <div class="info-box">
                        <h6>Tags</h6>
                        <div class="mt-2">
                            @foreach($changelog->tags as $tag)
                                <span class="tag-badge">{{ $tag }}</span>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- Timestamps -->
                <div class="info-box">
                    <h6>Created At</h6>
                    <p>{{ $changelog->created_at->format('M d, Y h:i A') }}</p>
                </div>

                <div class="info-box">
                    <h6>Last Updated</h6>
                    <p>{{ $changelog->updated_at->format('M d, Y h:i A') }}</p>
                </div>
            </div>
        </div>

        <!-- Actions Card -->
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="ti ti-settings me-2"></i>Actions
                </h5>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="{{ route('changelog.edit', $changelog) }}" class="btn btn-primary">
                        <i class="ti ti-edit me-2"></i>Edit Changelog
                    </a>
                    <form action="{{ route('changelog.destroy', $changelog) }}" method="POST" id="deleteForm">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger w-100">
                            <i class="ti ti-trash me-2"></i>Delete Changelog
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
    // Delete confirmation
    const deleteForm = document.getElementById('deleteForm');
    if (deleteForm) {
        deleteForm.addEventListener('submit', function(e) {
            e.preventDefault();
            if (confirm('Are you sure you want to delete this changelog? This action cannot be undone.')) {
                this.submit();
            }
        });
    }
});
</script>
@endpush
