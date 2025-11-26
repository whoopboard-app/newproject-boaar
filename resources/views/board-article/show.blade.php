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
    }

    .article-content h1, .article-content h2, .article-content h3 {
        margin-top: 1.5rem;
        margin-bottom: 1rem;
    }

    .article-content p {
        margin-bottom: 1rem;
    }

    .article-content ul, .article-content ol {
        margin-bottom: 1rem;
        padding-left: 2rem;
    }

    .article-content a {
        color: #007bff;
    }

    .article-content blockquote {
        border-left: 4px solid #dee2e6;
        padding-left: 1rem;
        margin-left: 0;
        color: #6c757d;
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
                    {!! $article->detailed_post !!}
                </div>
            </div>
        </div>

        <!-- All Ratings Card -->
        @if(isset($ratings) && $ratings->count() > 0)
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">
                    <i class="ti ti-messages me-2"></i>All Feedback ({{ $ratings->count() }})
                </h5>
            </div>
            <div class="card-body">
                <div class="ratings-list">
                    @foreach($ratings as $rating)
                    <div class="rating-item mb-3 p-3 bg-light rounded">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <div class="rating-value">
                                @if($rating->rating_type === 'yes_no')
                                    @if($rating->rating_value === 'yes')
                                        <span class="badge bg-success">
                                            <i class="ti ti-thumb-up me-1"></i> Yes, helpful
                                        </span>
                                    @else
                                        <span class="badge bg-danger">
                                            <i class="ti ti-thumb-down me-1"></i> Not helpful
                                        </span>
                                    @endif
                                @elseif($rating->rating_type === 'star')
                                    <span class="d-flex align-items-center gap-1">
                                        @for($i = 1; $i <= 5; $i++)
                                            @if($i <= (int)$rating->rating_value)
                                                <i class="ti ti-star-filled" style="color: #ffc107;"></i>
                                            @else
                                                <i class="ti ti-star" style="color: #ddd;"></i>
                                            @endif
                                        @endfor
                                        <span class="ms-2 fw-bold">{{ $rating->rating_value }}/5</span>
                                    </span>
                                @elseif($rating->rating_type === 'emoji')
                                    @php
                                        $emojis = ['😡', '😟', '😐', '😊', '😁'];
                                        $emojiIndex = max(0, min(4, (int)$rating->rating_value - 1));
                                    @endphp
                                    <span class="fs-4">{{ $emojis[$emojiIndex] }}</span>
                                @elseif($rating->rating_type === 'numeric')
                                    <span class="badge bg-primary fs-6">{{ $rating->rating_value }}/10</span>
                                @elseif($rating->rating_type === 'comment_only')
                                    <span class="badge bg-secondary">
                                        <i class="ti ti-message me-1"></i> Feedback
                                    </span>
                                @endif
                            </div>
                            <small class="text-muted">{{ $rating->created_at->diffForHumans() }}</small>
                        </div>
                        @if($rating->comment)
                            <div class="rating-comment mt-2 pt-2 border-top">
                                <p class="mb-0 text-dark">{{ $rating->comment }}</p>
                            </div>
                        @endif
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
        @endif
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

                <!-- Average Rating -->
                @if(isset($ratingStats) && $ratingStats)
                <div class="info-box" style="border-left-color: #ffc107;">
                    <h6>Average Rating</h6>
                    @if($ratingStats['type'] === 'yes_no')
                        <div class="d-flex align-items-center gap-2">
                            <span class="fs-4 fw-bold text-success">{{ $ratingStats['percentage'] }}%</span>
                            <span class="text-muted">found helpful</span>
                        </div>
                        <div class="mt-2">
                            <small class="text-muted">
                                <i class="ti ti-thumb-up text-success"></i> {{ $ratingStats['yes_count'] }} Yes
                                &nbsp;|&nbsp;
                                <i class="ti ti-thumb-down text-danger"></i> {{ $ratingStats['no_count'] }} No
                            </small>
                        </div>
                    @elseif($ratingStats['type'] === 'star')
                        <div class="d-flex align-items-center gap-2">
                            <span class="fs-4 fw-bold" style="color: #ffc107;">{{ $ratingStats['average'] }}</span>
                            <div>
                                @for($i = 1; $i <= 5; $i++)
                                    @if($i <= round($ratingStats['average']))
                                        <i class="ti ti-star-filled" style="color: #ffc107;"></i>
                                    @else
                                        <i class="ti ti-star" style="color: #ddd;"></i>
                                    @endif
                                @endfor
                            </div>
                        </div>
                    @elseif($ratingStats['type'] === 'emoji')
                        @php
                            $emojis = ['😡', '😟', '😐', '😊', '😁'];
                            $avgIndex = max(0, min(4, round($ratingStats['average']) - 1));
                        @endphp
                        <div class="d-flex align-items-center gap-2">
                            <span class="fs-4 fw-bold">{{ $ratingStats['average'] }}</span>
                            <span class="fs-3">{{ $emojis[$avgIndex] }}</span>
                        </div>
                    @elseif($ratingStats['type'] === 'numeric')
                        <div class="d-flex align-items-center gap-2">
                            <span class="fs-4 fw-bold text-primary">{{ $ratingStats['average'] }}</span>
                            <span class="text-muted">/ 10</span>
                        </div>
                        <div class="progress mt-2" style="height: 8px;">
                            <div class="progress-bar bg-primary" style="width: {{ ($ratingStats['average'] / 10) * 100 }}%;"></div>
                        </div>
                    @elseif($ratingStats['type'] === 'comment_only')
                        <p class="mb-0">{{ $ratingStats['total'] }} comments received</p>
                    @endif
                    <small class="text-muted d-block mt-1">{{ $ratingStats['total'] }} total responses</small>
                </div>
                @else
                <div class="info-box" style="border-left-color: #6c757d;">
                    <h6>Average Rating</h6>
                    <p class="text-muted mb-0">No ratings yet</p>
                </div>
                @endif
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
