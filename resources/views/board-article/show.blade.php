@extends('layouts.inspinia')

@section('title', $article->article_title . ' - ' . $knowledgeBoard->name)

@push('styles')
<style>
    .article-cover-large {
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

    .article-content {
        line-height: 1.8;
        font-size: 1rem;
        white-space: pre-wrap;
    }
</style>
@endpush

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-flex justify-content-between align-items-center">
            <div>
                <h4 class="page-title">{{ $article->article_title }}</h4>
                <p class="text-muted fs-14 mb-0">Article Details</p>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('knowledge-board.show', $knowledgeBoard) }}" class="btn btn-secondary">
                    <i class="ti ti-arrow-left me-1"></i>Back to Board
                </a>
                <a href="{{ route('board-article.edit', [$knowledgeBoard, $article]) }}" class="btn btn-primary">
                    <i class="ti ti-edit me-1"></i>Edit
                </a>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Main Content -->
    <div class="col-lg-8">
        <div class="card">
            <div class="card-body">
                <!-- Cover Image -->
                @if($article->cover_image)
                    <img src="{{ asset('storage/' . $article->cover_image) }}" alt="{{ $article->article_title }}" class="article-cover-large">
                @endif

                <!-- Title -->
                <h2 class="mb-3">{{ $article->article_title }}</h2>

                <!-- Detailed Post -->
                <div class="article-content">
                    {!! nl2br(e($article->detailed_post)) !!}
                </div>
            </div>
        </div>
    </div>

    <!-- Sidebar -->
    <div class="col-lg-4">
        <!-- Article Information -->
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="ti ti-info-circle me-2"></i>Article Information
                </h5>
            </div>
            <div class="card-body">
                <!-- Status -->
                <div class="info-box">
                    <h6>Status</h6>
                    <p>
                        @if($article->status == 'published')
                            <span class="badge bg-success">Published</span>
                        @elseif($article->status == 'draft')
                            <span class="badge bg-secondary">Draft</span>
                        @else
                            <span class="badge bg-warning">Unpublished</span>
                        @endif
                    </p>
                </div>

                <!-- Board Category -->
                <div class="info-box">
                    <h6>Category</h6>
                    <p>
                        <i class="{{ $article->boardCategory->category_icon ?: 'ti ti-folder' }} me-1"></i>
                        {{ $article->boardCategory->category_name }}
                    </p>
                </div>

                <!-- Author -->
                <div class="info-box">
                    <h6>Author</h6>
                    <p>{{ $article->author->name }}</p>
                </div>

                <!-- Display as Popular -->
                <div class="info-box">
                    <h6>Display as Popular</h6>
                    <p>
                        @if($article->display_as_popular)
                            <span class="badge bg-info">Yes</span>
                        @else
                            <span class="badge bg-secondary">No</span>
                        @endif
                    </p>
                </div>

                <!-- Tags -->
                @if($article->tags && count($article->tags) > 0)
                    <div class="info-box">
                        <h6>Tags</h6>
                        <div class="mt-2">
                            @foreach($article->tags as $tag)
                                <span class="tag-badge">{{ $tag }}</span>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- Changelog Categories -->
                @if($article->changelogCategories && $article->changelogCategories->count() > 0)
                    <div class="info-box">
                        <h6>Changelog Categories</h6>
                        <div class="mt-2">
                            @foreach($article->changelogCategories as $category)
                                <span class="badge mb-1" style="background-color: {{ $category->color }};">
                                    {{ $category->name }}
                                </span>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- Timestamps -->
                <div class="info-box">
                    <h6>Created At</h6>
                    <p>{{ $article->created_at->format('M d, Y h:i A') }}</p>
                </div>

                <div class="info-box">
                    <h6>Last Updated</h6>
                    <p>{{ $article->updated_at->format('M d, Y h:i A') }}</p>
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
                    <a href="{{ route('board-article.edit', [$knowledgeBoard, $article]) }}" class="btn btn-primary">
                        <i class="ti ti-edit me-2"></i>Edit Article
                    </a>
                    <form action="{{ route('board-article.destroy', [$knowledgeBoard, $article]) }}" method="POST" id="deleteForm">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger w-100">
                            <i class="ti ti-trash me-2"></i>Delete Article
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
            if (confirm('Are you sure you want to delete this article? This action cannot be undone.')) {
                this.submit();
            }
        });
    }
});
</script>
@endpush
