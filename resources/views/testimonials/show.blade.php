@extends('layouts.inspinia')

@section('title', 'Testimonial Details')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-flex align-items-center justify-content-between">
            <div>
                <h2 class="mb-0">Testimonial Details</h2>
                <p class="text-muted fs-14 mb-0">
                    View testimonial information
                    @if($testimonial->campaign)
                        <span class="mx-2">•</span>
                        <span class="badge bg-info">
                            <i class="ti ti-mail me-1"></i> Campaign: {{ $testimonial->campaign->name }}
                        </span>
                    @endif
                </p>
            </div>
            <div>
                <a href="{{ route('testimonials.index') }}" class="btn btn-secondary">
                    <i class="ti ti-arrow-left me-1"></i> Back to Testimonials
                </a>
                <a href="{{ route('testimonials.edit', $testimonial) }}" class="btn btn-primary">
                    <i class="ti ti-edit me-1"></i> Edit
                </a>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Testimonial Content Card -->
    <div class="col-lg-8">
        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Testimonial Content</h5>
                <div>
                    @if($testimonial->status === 'published')
                        <span class="badge bg-success">Published</span>
                    @elseif($testimonial->status === 'pending_review')
                        <span class="badge bg-warning">Pending Review</span>
                    @elseif($testimonial->status === 'under_review')
                        <span class="badge bg-info">Under Review</span>
                    @elseif($testimonial->status === 'draft')
                        <span class="badge bg-secondary">Draft</span>
                    @elseif($testimonial->status === 'active')
                        <span class="badge bg-success">Active</span>
                    @elseif($testimonial->status === 'inactive')
                        <span class="badge bg-secondary">Inactive</span>
                    @endif

                    @if($testimonial->type === 'video')
                        <span class="badge bg-info ms-2"><i class="ti ti-video me-1"></i> Video</span>
                    @else
                        <span class="badge bg-primary ms-2"><i class="ti ti-file-text me-1"></i> Text</span>
                    @endif
                </div>
            </div>
            <div class="card-body">
                @if($testimonial->type === 'text')
                    <!-- Text Testimonial -->
                    <div class="testimonial-content mb-4">
                        <i class="ti ti-quote" style="font-size: 3rem; color: #e0e0e0;"></i>
                        <p class="fs-5 mb-3" style="font-style: italic;">
                            {{ $testimonial->text_content }}
                        </p>
                    </div>
                @else
                    <!-- Video Testimonial -->
                    <div class="testimonial-video mb-4">
                        @if($testimonial->video_thumbnail)
                            <img src="{{ asset('storage/' . $testimonial->video_thumbnail) }}" alt="Video Thumbnail" class="img-fluid rounded mb-3" style="max-width: 100%;">
                        @endif
                        @if($testimonial->video_url)
                            <div class="ratio ratio-16x9">
                                <iframe src="{{ $testimonial->video_url }}" allowfullscreen class="rounded"></iframe>
                            </div>
                        @endif
                    </div>
                @endif

                <!-- Rating -->
                @if($testimonial->rating)
                    <div class="mb-4">
                        <h6 class="text-muted mb-2">Rating</h6>
                        <div class="text-warning" style="font-size: 1.5rem;">
                            @for($i = 1; $i <= 5; $i++)
                                <i class="ti ti-star{{ $i <= $testimonial->rating ? '-filled' : '' }}"></i>
                            @endfor
                            <span class="text-muted ms-2" style="font-size: 1rem;">{{ $testimonial->rating }}/5</span>
                        </div>
                    </div>
                @endif

                <!-- Custom Data -->
                @if($testimonial->custom_data && is_array($testimonial->custom_data) && count($testimonial->custom_data) > 0)
                    <div class="mt-4">
                        <h6 class="text-muted mb-3">Additional Information</h6>
                        <div class="row">
                            @foreach($testimonial->custom_data as $key => $value)
                                @if($value)
                                    <div class="col-md-6 mb-3">
                                        <strong>{{ ucfirst(str_replace('_', ' ', $key)) }}:</strong>
                                        <p class="mb-0">{{ $value }}</p>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
        </div>

        <!-- Source Information -->
        @if($testimonial->template || $testimonial->campaign)
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Source Information</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        @if($testimonial->template)
                            <div class="col-md-6 mb-3">
                                <strong>Template:</strong>
                                <p class="mb-0">{{ $testimonial->template->name }}</p>
                            </div>
                        @endif
                        @if($testimonial->campaign)
                            <div class="col-md-6 mb-3">
                                <strong>Campaign:</strong>
                                <p class="mb-0">
                                    <a href="{{ route('testimonial-campaigns.view', $testimonial->campaign) }}" class="text-decoration-none">
                                        {{ $testimonial->campaign->name }}
                                        <i class="ti ti-external-link ms-1"></i>
                                    </a>
                                </p>
                            </div>
                        @endif
                        @if($testimonial->campaignSubscriber && $testimonial->campaignSubscriber->subscriber && $testimonial->campaignSubscriber->subscriber->segments->isNotEmpty())
                            <div class="col-md-6 mb-3">
                                <strong>Segment{{ $testimonial->campaignSubscriber->subscriber->segments->count() > 1 ? 's' : '' }}:</strong>
                                <p class="mb-0">
                                    @foreach($testimonial->campaignSubscriber->subscriber->segments as $segment)
                                        <span class="badge bg-primary">{{ $segment->name }}</span>{{ !$loop->last ? ' ' : '' }}
                                    @endforeach
                                </p>
                            </div>
                        @endif
                        <div class="col-md-6 mb-3">
                            <strong>Source:</strong>
                            @if($testimonial->source === 'email')
                                <span class="badge bg-info">From Email</span>
                            @elseif($testimonial->source === 'website')
                                <span class="badge bg-success">From Website</span>
                            @elseif($testimonial->source === 'script')
                                <span class="badge bg-primary">From Script</span>
                            @else
                                <span class="badge bg-secondary">Manual</span>
                            @endif
                        </div>
                        <div class="col-md-6 mb-3">
                            <strong>Submitted:</strong>
                            <p class="mb-0">{{ $testimonial->created_at->format('M d, Y H:i') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>

    <!-- Sidebar -->
    <div class="col-lg-4">
        <!-- Author Information -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">Author Information</h5>
            </div>
            <div class="card-body text-center">
                @if($testimonial->avatar)
                    <img src="{{ asset('storage/' . $testimonial->avatar) }}" alt="{{ $testimonial->name }}" class="rounded-circle mb-3" style="width: 100px; height: 100px; object-fit: cover;">
                @else
                    <div class="rounded-circle mb-3 bg-primary text-white d-inline-flex align-items-center justify-content-center" style="width: 100px; height: 100px; font-size: 2.5rem;">
                        {{ strtoupper(substr($testimonial->name, 0, 1)) }}
                    </div>
                @endif

                <h5 class="mb-1">{{ $testimonial->name }}</h5>

                @if($testimonial->position)
                    <p class="text-muted mb-1">{{ $testimonial->position }}</p>
                @endif

                @if($testimonial->company)
                    <p class="text-muted mb-3">{{ $testimonial->company }}</p>
                @endif

                @if($testimonial->email)
                    <div class="text-start mt-4">
                        <p class="mb-2"><strong>Email:</strong></p>
                        <p class="mb-0"><a href="mailto:{{ $testimonial->email }}">{{ $testimonial->email }}</a></p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Actions -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">Actions</h5>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="{{ route('testimonials.edit', $testimonial) }}" class="btn btn-primary">
                        <i class="ti ti-edit me-1"></i> Edit Testimonial
                    </a>

                    @if($testimonial->status !== 'published')
                        <form action="{{ route('testimonials.update', $testimonial) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="status" value="published">
                            <button type="submit" class="btn btn-success w-100">
                                <i class="ti ti-check me-1"></i> Publish Testimonial
                            </button>
                        </form>
                    @endif

                    <form action="{{ route('testimonials.destroy', $testimonial) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this testimonial?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger w-100">
                            <i class="ti ti-trash me-1"></i> Delete Testimonial
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Metadata -->
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Metadata</h5>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <strong class="text-muted d-block mb-1">Testimonial ID</strong>
                    <span class="badge bg-secondary">#{{ $testimonial->id }}</span>
                </div>
                <div class="mb-3">
                    <strong class="text-muted d-block mb-1">Created</strong>
                    {{ $testimonial->created_at->format('M d, Y H:i') }}
                    <br>
                    <small class="text-muted">{{ $testimonial->created_at->diffForHumans() }}</small>
                </div>
                <div class="mb-3">
                    <strong class="text-muted d-block mb-1">Last Updated</strong>
                    {{ $testimonial->updated_at->format('M d, Y H:i') }}
                    <br>
                    <small class="text-muted">{{ $testimonial->updated_at->diffForHumans() }}</small>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
