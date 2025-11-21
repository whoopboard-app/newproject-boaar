@extends('layouts.inspinia')

@section('title', 'Testimonials')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-flex align-items-center justify-content-between">
            <div>
                <h2 class="mb-0">Testimonials</h2>
                <p class="text-muted fs-14 mb-0">Manage your testimonials and templates</p>
            </div>
        </div>
    </div>
</div>

<!-- Tabs -->
<div class="row">
    <div class="col-12">
        <ul class="nav nav-tabs mb-3" role="tablist">
            <li class="nav-item" role="presentation">
                <a class="nav-link {{ $activeTab === 'testimonials' ? 'active' : '' }}" href="{{ route('testimonials.index', ['tab' => 'testimonials']) }}">
                    <i class="ti ti-messages me-1"></i> Testimonials
                </a>
            </li>
            <li class="nav-item" role="presentation">
                <a class="nav-link {{ $activeTab === 'templates' ? 'active' : '' }}" href="{{ route('testimonials.index', ['tab' => 'templates']) }}">
                    <i class="ti ti-template me-1"></i> Testimonial Templates
                </a>
            </li>
        </ul>

        <div class="tab-content">
            <!-- Testimonials Tab -->
            <div class="tab-pane fade {{ $activeTab === 'testimonials' ? 'show active' : '' }}" id="testimonials" role="tabpanel">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">All Testimonials</h5>
                        <div>
                            <a href="{{ route('testimonials.create') }}" class="btn btn-primary">
                                <i class="ti ti-plus me-1"></i> Add Testimonial
                            </a>
                            <button class="btn btn-secondary" disabled>
                                <i class="ti ti-upload me-1"></i> Import Testimonials
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        @if($testimonials->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>Status</th>
                                            <th>Testimonial Name</th>
                                            <th>Type</th>
                                            <th>Rating</th>
                                            <th>Name of User</th>
                                            <th>Source</th>
                                            <th>Date</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($testimonials as $testimonial)
                                            <tr>
                                                <td>
                                                    @if($testimonial->status === 'active')
                                                        <span class="badge bg-success">Active</span>
                                                    @elseif($testimonial->status === 'inactive')
                                                        <span class="badge bg-secondary">Inactive</span>
                                                    @else
                                                        <span class="badge bg-warning">Draft</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        @if($testimonial->avatar)
                                                            <img src="{{ asset('storage/' . $testimonial->avatar) }}" alt="{{ $testimonial->name }}" class="rounded-circle me-2" style="width: 32px; height: 32px; object-fit: cover;">
                                                        @else
                                                            <div class="rounded-circle me-2 bg-primary text-white d-flex align-items-center justify-content-center" style="width: 32px; height: 32px; font-size: 14px;">
                                                                {{ strtoupper(substr($testimonial->name, 0, 1)) }}
                                                            </div>
                                                        @endif
                                                        <div>
                                                            <div class="fw-semibold">{{ Str::limit($testimonial->text_content ?? $testimonial->video_url, 50) }}</div>
                                                            @if($testimonial->company)
                                                                <small class="text-muted">{{ $testimonial->company }}</small>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    @if($testimonial->type === 'video')
                                                        <span class="badge bg-info"><i class="ti ti-video me-1"></i> Video</span>
                                                    @else
                                                        <span class="badge bg-primary"><i class="ti ti-file-text me-1"></i> Text</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if($testimonial->rating)
                                                        <div class="text-warning">
                                                            @for($i = 1; $i <= 5; $i++)
                                                                <i class="ti ti-star{{ $i <= $testimonial->rating ? '-filled' : '' }}"></i>
                                                            @endfor
                                                        </div>
                                                    @else
                                                        <span class="text-muted">-</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    {{ $testimonial->name }}
                                                    @if($testimonial->position)
                                                        <br><small class="text-muted">{{ $testimonial->position }}</small>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if($testimonial->source === 'email')
                                                        <span class="badge bg-info">From Email</span>
                                                    @elseif($testimonial->source === 'website')
                                                        <span class="badge bg-success">From Website</span>
                                                    @elseif($testimonial->source === 'script')
                                                        <span class="badge bg-primary">From Script</span>
                                                    @else
                                                        <span class="badge bg-secondary">Manual</span>
                                                    @endif
                                                </td>
                                                <td>{{ $testimonial->created_at->format('M d, Y') }}</td>
                                                <td>
                                                    <div class="btn-group btn-group-sm">
                                                        <a href="{{ route('testimonials.show', $testimonial) }}" class="btn btn-sm btn-outline-primary" title="View">
                                                            <i class="ti ti-eye"></i>
                                                        </a>
                                                        <a href="{{ route('testimonials.edit', $testimonial) }}" class="btn btn-sm btn-outline-secondary" title="Edit">
                                                            <i class="ti ti-edit"></i>
                                                        </a>
                                                        <form action="{{ route('testimonials.destroy', $testimonial) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this testimonial?');">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete">
                                                                <i class="ti ti-trash"></i>
                                                            </button>
                                                        </form>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            {{ $testimonials->links() }}
                        @else
                            <div class="text-center py-5">
                                <i class="ti ti-messages" style="font-size: 4rem; color: #ddd;"></i>
                                <p class="text-muted mt-3">No testimonials yet. Add your first testimonial!</p>
                                <a href="{{ route('testimonials.create') }}" class="btn btn-primary">
                                    <i class="ti ti-plus me-1"></i> Add Testimonial
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Templates Tab -->
            <div class="tab-pane fade {{ $activeTab === 'templates' ? 'show active' : '' }}" id="templates" role="tabpanel">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Testimonial Templates</h5>
                        <a href="{{ route('testimonial-templates.create') }}" class="btn btn-primary">
                            <i class="ti ti-plus me-1"></i> Add Template
                        </a>
                    </div>
                    <div class="card-body">
                        @if($templates->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>Status</th>
                                            <th>Template Name</th>
                                            <th>Number of Submissions</th>
                                            <th>Created</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($templates as $template)
                                            <tr>
                                                <td>
                                                    @if($template->status === 'active')
                                                        <span class="badge bg-success">Active</span>
                                                    @else
                                                        <span class="badge bg-secondary">Inactive</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <div class="fw-semibold">{{ $template->name }}</div>
                                                    <small class="text-muted">{{ url('/testimonial/' . $template->unique_url) }}</small>
                                                </td>
                                                <td>
                                                    <span class="badge bg-primary">{{ $template->testimonials_count }} submissions</span>
                                                </td>
                                                <td>{{ $template->created_at->format('M d, Y') }}</td>
                                                <td>
                                                    <div class="btn-group btn-group-sm">
                                                        <a href="{{ route('testimonial-templates.show', $template) }}" class="btn btn-sm btn-outline-primary" title="View">
                                                            <i class="ti ti-eye"></i>
                                                        </a>
                                                        <a href="{{ route('testimonial-templates.edit', $template) }}" class="btn btn-sm btn-outline-secondary" title="Edit">
                                                            <i class="ti ti-edit"></i>
                                                        </a>
                                                        <form action="{{ route('testimonial-templates.destroy', $template) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this template?');">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete">
                                                                <i class="ti ti-trash"></i>
                                                            </button>
                                                        </form>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="text-center py-5">
                                <i class="ti ti-template" style="font-size: 4rem; color: #ddd;"></i>
                                <p class="text-muted mt-3">No templates yet. Create your first template!</p>
                                <a href="{{ route('testimonial-templates.create') }}" class="btn btn-primary">
                                    <i class="ti ti-plus me-1"></i> Add Template
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
