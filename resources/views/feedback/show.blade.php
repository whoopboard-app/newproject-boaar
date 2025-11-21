@extends('layouts.inspinia')

@section('title', 'View Feedback')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-flex align-items-center justify-content-between">
            <div>
                <h2 class="mb-0">Feedback Details</h2>
                <p class="text-muted fs-14 mb-0">View feedback details and comments</p>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('feedback.index') }}" class="btn btn-secondary">
                    <i class="ti ti-arrow-left me-1"></i> Back to List
                </a>
                @if(Auth::user()->canManageFeedback())
                <a href="{{ route('feedback.edit', $feedback) }}" class="btn btn-warning">
                    <i class="ti ti-edit me-1"></i> Edit
                </a>
                @endif
            </div>
        </div>
    </div>
</div>

@if(Auth::user()->isReadOnly())
    <div class="alert alert-info alert-dismissible fade show" role="alert">
        <i class="ti ti-info-circle me-2"></i>You are in <strong>read-only mode</strong>. You can view but not modify feedback.
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="ti ti-circle-check me-2"></i>{{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<div class="row">
    <!-- Feedback Details -->
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="ti ti-bulb me-2"></i>Idea Details
                </h5>
            </div>
            <div class="card-body">
                <!-- Idea -->
                <div class="mb-4">
                    <h6 class="text-muted mb-2">Tell about your idea</h6>
                    <p class="mb-0">{{ $feedback->idea }}</p>
                </div>

                <!-- Value Description -->
                @if($feedback->value_description)
                    <div class="mb-4">
                        <h6 class="text-muted mb-2">Why this idea will have more value to product</h6>
                        <p class="mb-0">{{ $feedback->value_description }}</p>
                    </div>
                @endif

                <!-- Category & Status -->
                <div class="row mb-4">
                    <div class="col-md-6">
                        <h6 class="text-muted mb-2">Category</h6>
                        @if($feedback->category)
                            <span class="badge" style="background-color: {{ $feedback->category->color }}; color: white; padding: 8px 16px;">
                                {{ $feedback->category->name }}
                            </span>
                        @else
                            <span class="text-muted">Not assigned</span>
                        @endif
                    </div>
                    <div class="col-md-6">
                        <h6 class="text-muted mb-2">Status</h6>
                        @if($feedback->roadmap)
                            <span class="badge" style="background-color: {{ $feedback->roadmap->color }}; color: white; padding: 8px 16px;">
                                {{ $feedback->roadmap->name }}
                            </span>
                        @else
                            <span class="text-muted">Not assigned</span>
                        @endif
                    </div>
                </div>

                <!-- Tags -->
                @if($feedback->tags)
                    <div class="mb-4">
                        <h6 class="text-muted mb-2">Tags</h6>
                        @foreach($feedback->tags as $tag)
                            <span class="badge bg-secondary me-1">{{ $tag }}</span>
                        @endforeach
                    </div>
                @endif

                <!-- Submitter Info -->
                <div class="mb-4">
                    <h6 class="text-muted mb-2">Submitter Information</h6>
                    <p class="mb-1"><strong>Name:</strong> {{ $feedback->name }}</p>
                    <p class="mb-1"><strong>Email:</strong> {{ $feedback->email }}</p>
                    <p class="mb-0">
                        <strong>Login Access:</strong>
                        @if($feedback->login_access_enabled)
                            <span class="badge bg-success">Enabled</span>
                        @else
                            <span class="badge bg-secondary">Disabled</span>
                        @endif
                    </p>
                </div>
            </div>
        </div>

        <!-- Comments Section -->
        <div class="card mt-3">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="ti ti-message-circle me-2"></i>Comments ({{ $feedback->comments()->count() }})
                </h5>
            </div>
            <div class="card-body">
                <!-- Add Comment Form -->
                <form method="POST" action="{{ route('feedback.comment.store', $feedback) }}" class="mb-4">
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
                @forelse($feedback->comments as $comment)
                    <div class="d-flex mb-3">
                        <div class="flex-shrink-0">
                            <div class="avatar-sm">
                                <span class="avatar-title rounded-circle bg-primary">
                                    <i class="ti ti-user text-white"></i>
                                </span>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h6 class="mb-1">{{ $comment->user ? $comment->user->name : 'Anonymous' }}</h6>
                                    <small class="text-muted">{{ $comment->created_at->diffForHumans() }}</small>
                                </div>
                                @auth
                                    @if(auth()->id() === $comment->user_id)
                                        <form method="POST" action="{{ route('feedback.comment.destroy', [$feedback, $comment]) }}" onsubmit="return confirm('Are you sure you want to delete this comment?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-soft-danger">
                                                <i class="ti ti-trash"></i>
                                            </button>
                                        </form>
                                    @endif
                                @endauth
                            </div>
                            <p class="mb-0 mt-2">{{ $comment->comment }}</p>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-4">
                        <i class="ti ti-message-off fs-1 text-muted mb-2"></i>
                        <p class="text-muted mb-0">No comments yet. Be the first to comment!</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Sidebar -->
    <div class="col-lg-4">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="ti ti-info-circle me-2"></i>Additional Information
                </h5>
            </div>
            <div class="card-body">
                <!-- Persona -->
                <div class="mb-3">
                    <h6 class="text-muted mb-2">Persona</h6>
                    @if($feedback->persona)
                        <p class="mb-0">{{ $feedback->persona->name }}</p>
                    @else
                        <span class="text-muted">Not assigned</span>
                    @endif
                </div>

                <!-- Source -->
                <div class="mb-3">
                    <h6 class="text-muted mb-2">Source of Idea</h6>
                    <span class="badge bg-info">{{ $feedback->source }}</span>
                </div>

                <!-- Created Date -->
                <div class="mb-3">
                    <h6 class="text-muted mb-2">Created</h6>
                    <p class="mb-0">{{ $feedback->created_at->format('M d, Y h:i A') }}</p>
                    <small class="text-muted">{{ $feedback->created_at->diffForHumans() }}</small>
                </div>

                <!-- Updated Date -->
                <div class="mb-0">
                    <h6 class="text-muted mb-2">Last Updated</h6>
                    <p class="mb-0">{{ $feedback->updated_at->format('M d, Y h:i A') }}</p>
                    <small class="text-muted">{{ $feedback->updated_at->diffForHumans() }}</small>
                </div>
            </div>
        </div>

        <!-- Actions -->
        <div class="card mt-3">
            <div class="card-header">
                <h5 class="card-title mb-0">Actions</h5>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="{{ route('feedback.edit', $feedback) }}" class="btn btn-warning">
                        <i class="ti ti-edit me-1"></i> Edit Feedback
                    </a>
                    <form method="POST" action="{{ route('feedback.destroy', $feedback) }}" onsubmit="return confirm('Are you sure you want to delete this feedback?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger w-100">
                            <i class="ti ti-trash me-1"></i> Delete Feedback
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
