@extends('layouts.inspinia')

@section('title', 'View Roadmap Item')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-flex align-items-center justify-content-between">
            <div>
                <h2 class="mb-0">Roadmap Item Details</h2>
                <p class="text-muted fs-14 mb-0">View roadmap item details and comments</p>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('roadmap-items.index') }}" class="btn btn-secondary">
                    <i class="ti ti-arrow-left me-1"></i> Back to List
                </a>
                <a href="{{ route('roadmap-items.kanban') }}" class="btn btn-info">
                    <i class="ti ti-layout-kanban me-1"></i> Kanban View
                </a>
                <a href="{{ route('roadmap-items.edit', $roadmapItem) }}" class="btn btn-warning">
                    <i class="ti ti-edit me-1"></i> Edit
                </a>
            </div>
        </div>
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="ti ti-circle-check me-2"></i>{{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="ti ti-alert-circle me-2"></i>{{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<div class="row">
    <!-- Roadmap Item Details -->
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="ti ti-route me-2"></i>Item Details
                </h5>
            </div>
            <div class="card-body">
                <!-- Idea -->
                <div class="mb-4">
                    <h6 class="text-muted mb-2">Idea</h6>
                    <p class="mb-0 fs-5">{{ $roadmapItem->idea }}</p>
                </div>

                <!-- Notes -->
                @if($roadmapItem->notes)
                    <div class="mb-4">
                        <h6 class="text-muted mb-2">Notes</h6>
                        <div class="border rounded p-3">
                            {!! $roadmapItem->notes !!}
                        </div>
                    </div>
                @endif

                <!-- Status -->
                <div class="row mb-4">
                    <div class="col-md-6">
                        <h6 class="text-muted mb-2">Status</h6>
                        @if($roadmapItem->roadmapStatus)
                            <span class="badge" style="background-color: {{ $roadmapItem->roadmapStatus->color }}; color: white; padding: 8px 16px; font-size: 0.9rem;">
                                {{ $roadmapItem->roadmapStatus->name }}
                            </span>
                        @else
                            <span class="text-muted">Not assigned</span>
                        @endif
                    </div>
                    <div class="col-md-6">
                        <h6 class="text-muted mb-2">External PM Tool ID</h6>
                        @if($roadmapItem->external_pm_tool_id)
                            <code class="text-primary">{{ $roadmapItem->external_pm_tool_id }}</code>
                        @else
                            <span class="text-muted">Not set</span>
                        @endif
                    </div>
                </div>

                <!-- Tags -->
                @if($roadmapItem->tags && count($roadmapItem->tags) > 0)
                    <div class="mb-4">
                        <h6 class="text-muted mb-2">Tags</h6>
                        @foreach($roadmapItem->tags as $tag)
                            <span class="badge bg-info me-1 mb-1">{{ $tag }}</span>
                        @endforeach
                    </div>
                @endif

                <!-- Linked Feedback -->
                @if($roadmapItem->feedback)
                    <div class="mb-4">
                        <h6 class="text-muted mb-2">Linked Feedback</h6>
                        <div class="alert alert-info d-flex align-items-center">
                            <i class="ti ti-link me-3 fs-4"></i>
                            <div>
                                <strong>Feedback #{{ $roadmapItem->feedback->id }}</strong><br>
                                <a href="{{ route('feedback.show', $roadmapItem->feedback) }}" class="text-decoration-none">
                                    {{ $roadmapItem->feedback->idea }}
                                </a>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Timestamps -->
                <div class="row">
                    <div class="col-md-6">
                        <h6 class="text-muted mb-2">Created</h6>
                        <p class="mb-0">{{ $roadmapItem->created_at->format('M d, Y \a\t g:i A') }}</p>
                    </div>
                    <div class="col-md-6">
                        <h6 class="text-muted mb-2">Last Updated</h6>
                        <p class="mb-0">{{ $roadmapItem->updated_at->format('M d, Y \a\t g:i A') }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Comments Section -->
        <div class="card mt-3">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="ti ti-message-circle me-2"></i>Comments ({{ $roadmapItem->comments->count() }})
                </h5>
            </div>
            <div class="card-body">
                <!-- Add Comment Form -->
                <form method="POST" action="{{ route('roadmap-items.comment.store', $roadmapItem) }}" class="mb-4">
                    @csrf
                    <div class="mb-3">
                        <label for="comment" class="form-label">Add a Comment</label>
                        <textarea class="form-control @error('comment') is-invalid @enderror" id="comment" name="comment" rows="3" placeholder="Type your comment here..." required></textarea>
                        @error('comment')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <button type="submit" class="btn btn-primary">
                        <i class="ti ti-send me-1"></i> Post Comment
                    </button>
                </form>

                <hr>

                <!-- Comments List -->
                @forelse($roadmapItem->comments as $comment)
                    <div class="d-flex mb-3">
                        <div class="flex-shrink-0">
                            <div class="avatar-sm">
                                <span class="avatar-title rounded-circle bg-primary">
                                    <i class="ti ti-user text-white"></i>
                                </span>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <h6 class="mb-0">{{ $comment->user->name }}</h6>
                                <div class="d-flex align-items-center gap-2">
                                    <small class="text-muted">{{ $comment->created_at->diffForHumans() }}</small>
                                    @if(Auth::id() === $comment->user_id || Auth::user()->isAdmin())
                                        <form method="POST" action="{{ route('roadmap-items.comment.destroy', $comment) }}" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this comment?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-link text-danger p-0" title="Delete comment">
                                                <i class="ti ti-trash"></i>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </div>
                            <p class="mb-0">{{ $comment->comment }}</p>
                        </div>
                    </div>
                    @if (!$loop->last)
                        <hr>
                    @endif
                @empty
                    <div class="text-center text-muted py-4">
                        <i class="ti ti-message-off fs-1"></i>
                        <p class="mt-2 mb-0">No comments yet. Be the first to comment!</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Sidebar -->
    <div class="col-lg-4">
        <!-- Actions Card -->
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="ti ti-settings me-2"></i>Actions
                </h5>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="{{ route('roadmap-items.edit', $roadmapItem) }}" class="btn btn-warning">
                        <i class="ti ti-edit me-2"></i>Edit Item
                    </a>
                    <a href="{{ route('roadmap-items.kanban') }}" class="btn btn-info">
                        <i class="ti ti-layout-kanban me-2"></i>View Kanban
                    </a>
                    <form action="{{ route('roadmap-items.destroy', $roadmapItem) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this roadmap item? This action cannot be undone.');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger w-100">
                            <i class="ti ti-trash me-2"></i>Delete Item
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Quick Info Card -->
        <div class="card mt-3">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="ti ti-info-circle me-2"></i>Quick Info
                </h5>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <small class="text-muted d-block">Item ID</small>
                    <strong>#{{ $roadmapItem->id }}</strong>
                </div>
                <div class="mb-3">
                    <small class="text-muted d-block">Comments</small>
                    <strong>{{ $roadmapItem->comments->count() }}</strong>
                </div>
                @if($roadmapItem->feedback)
                    <div class="mb-0">
                        <small class="text-muted d-block">Source</small>
                        <span class="badge bg-success">From Feedback</span>
                    </div>
                @else
                    <div class="mb-0">
                        <small class="text-muted d-block">Source</small>
                        <span class="badge bg-primary">Manually Created</span>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
