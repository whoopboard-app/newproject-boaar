@extends('layouts.inspinia')

@section('title', 'Testimonials')

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/@yaireo/tagify/dist/tagify.css" rel="stylesheet" type="text/css">
<style>
    .tagify {
        min-height: 39.51px !important;
        padding: 0.375rem 0.75rem !important;
        border: 1px solid #dee2e6 !important;
        border-radius: 0.25rem !important;
    }
    .nav-tabs .nav-link {
        color: #6c757d;
    }
    .nav-tabs .nav-link.active {
        color: #495057;
        font-weight: 600;
    }
    .fs-48 {
        font-size: 48px;
    }
    .card-header h6 {
        font-size: 1rem;
        font-weight: 600;
    }
</style>
@endpush

@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="card top-area">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">
                    <i class="ti ti-list me-2"></i>Testimonials
                    <p class="text-muted fs-14 mb-0">Manage your testimonials and templates</p>
                </h5>
                <div class="d-flex justify-content-between align-items-center">
                    <a href="{{ route('testimonials.create') }}" class="btn btn-primary">
                                <i class="ti ti-plus me-1"></i> Add Testimonial
                            </a>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Tabs -->
<div class="row">
    <div class="col-12 testimonial-card">
        <ul class="nav nav-tabs nav-bordered mb-3 custom-tab-design" role="tablist">
            <li class="nav-item" role="presentation">
                <a class="nav-link {{ $activeTab === 'testimonials' ? 'active' : '' }}" href="{{ route('testimonials.index', ['tab' => 'testimonials']) }}">
                    <i class="ti ti-messages me-1"></i> All Testimonials
                </a>
            </li>
            <li class="nav-item" role="presentation">
                <a class="nav-link {{ $activeTab === 'campaigns' ? 'active' : '' }}" href="{{ route('testimonials.index', ['tab' => 'campaigns']) }}">
                    <i class="ti ti-mail me-1"></i> Testimonials Campaign
                </a>
            </li>
            <li class="nav-item" role="presentation">
                <a class="nav-link {{ $activeTab === 'templates' ? 'active' : '' }}" href="{{ route('testimonials.index', ['tab' => 'templates']) }}">
                    <i class="ti ti-template me-1"></i> Testimonials Template
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
                                                    @else
                                                        <span class="badge bg-secondary">{{ ucfirst($testimonial->status) }}</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        @if($testimonial->avatar)
                                                            <img src="{{ asset('storage/' . $testimonial->avatar) }}" alt="{{ $testimonial->name }}" class="rounded-circle me-2" style="width: 32px; height: 32px; object-fit: cover;">
                                                        @else
                                                            <div class="rounded-circle me-2 bg-primary text-white d-flex align-items-center justify-content-center" style="width: 32px; height: 32px; font-size: 14px;">
                                                                {{ strtoupper(substr($testimonial->name ?? 'T', 0, 1)) }}
                                                            </div>
                                                        @endif
                                                        <div>
                                                            <div class="fw-semibold">{{ Str::limit($testimonial->text_content ?? $testimonial->video_url, 50) }}</div>
                                                            @if($testimonial->name)
                                                                <small class="text-muted">{{ $testimonial->name }}</small>
                                                            @elseif($testimonial->company)
                                                                <small class="text-muted">{{ $testimonial->company }}</small>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    @if($testimonial->type === 'video')
                                                        <span class="badge bg-info"><i class="ti ti-video me-1"></i> Video</span>
                                                        @if($testimonial->mux_upload_id)
                                                            @if($testimonial->mux_status === 'ready')
                                                                <span class="badge bg-success ms-1" title="Video ready to play"><i class="ti ti-check"></i></span>
                                                            @elseif($testimonial->mux_status === 'error')
                                                                <span class="badge bg-danger ms-1" title="Video processing failed"><i class="ti ti-x"></i></span>
                                                            @else
                                                                <span class="badge bg-warning ms-1" title="Video processing"><i class="ti ti-loader"></i></span>
                                                            @endif
                                                        @endif
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
                                <img src="{{asset('assets/images/Concord.png')}}" />
                                <p class="text-muted mt-3">No testimonials yet. Add your first testimonial!</p>
                                <a href="{{ route('testimonials.create') }}" class="btn btn-primary">
                                    <i class="ti ti-plus me-1"></i> Add Testimonial
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Campaigns Tab -->
            <div class="tab-pane fade {{ $activeTab === 'campaigns' ? 'show active' : '' }}" id="campaigns" role="tabpanel">
                <!-- Success message container for campaigns -->
                <div id="campaignSuccessAlert" style="display: none;"></div>

                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Testimonials Campaigns</h5>
                        <button type="button" class="btn btn-primary" data-bs-toggle="offcanvas" data-bs-target="#campaignDrawer">
                            <i class="ti ti-calendar-plus me-1"></i> Schedule a Campaign
                        </button>
                    </div>
                    <div class="card-body">
                        @if($campaigns->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>Status</th>
                                            <th>Campaign Name</th>
                                            <th>Subscribers Sent</th>
                                            <th>Testimonials Collected</th>
                                            <th>Average Rating</th>
                                            <th>Date of Campaign</th>
                                            <th>Opens/Clicks</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($campaigns as $campaign)
                                            <tr>
                                                <td>
                                                    @if($campaign->status === 'active')
                                                        <span class="badge bg-success">Active</span>
                                                    @elseif($campaign->status === 'inactive')
                                                        <span class="badge bg-secondary">Inactive</span>
                                                    @else
                                                        <span class="badge bg-warning">Draft</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <div class="fw-semibold">{{ $campaign->name }}</div>
                                                    @if($campaign->objective)
                                                        <small class="text-muted">{{ Str::limit($campaign->objective, 50) }}</small>
                                                    @endif
                                                </td>
                                                <td>
                                                    <span class="badge bg-primary">{{ $campaign->subscribers_sent }}</span>
                                                </td>
                                                <td>
                                                    <span class="badge bg-success">{{ $campaign->testimonials_count }}</span>
                                                </td>
                                                <td>
                                                    @if($campaign->average_rating)
                                                        <div class="text-warning">
                                                            @for($i = 1; $i <= 5; $i++)
                                                                <i class="ti ti-star{{ $i <= round($campaign->average_rating) ? '-filled' : '' }}"></i>
                                                            @endfor
                                                            <small class="text-muted ms-1">{{ number_format($campaign->average_rating, 1) }}</small>
                                                        </div>
                                                    @else
                                                        <span class="text-muted">-</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if($campaign->sent_at)
                                                        {{ $campaign->sent_at->format('M d, Y') }}
                                                    @elseif($campaign->scheduled_at)
                                                        <small class="text-muted">Scheduled: {{ $campaign->scheduled_at->format('M d, Y') }}</small>
                                                    @else
                                                        <span class="text-muted">Not sent</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <small>
                                                        <i class="ti ti-mail-opened"></i> {{ $campaign->emails_opened }}
                                                        <i class="ti ti-click ms-2"></i> {{ $campaign->emails_clicked }}
                                                    </small>
                                                </td>
                                                <td>
                                                    <div class="btn-group btn-group-sm">
                                                        <a href="{{ route('testimonial-campaigns.view', $campaign) }}" class="btn btn-sm btn-outline-success" title="View Details">
                                                            <i class="ti ti-eye"></i>
                                                        </a>
                                                        <button class="btn btn-sm btn-outline-primary" title="Quick Statistics" onclick="viewCampaignStats({{ $campaign->id }})">
                                                            <i class="ti ti-chart-bar"></i>
                                                        </button>
                                                        <button class="btn btn-sm btn-outline-danger" title="Delete" onclick="deleteCampaign({{ $campaign->id }})">
                                                            <i class="ti ti-trash"></i>
                                                        </button>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="text-center py-5">
                                <i class="ti ti-mail" style="font-size: 4rem; color: #ddd;"></i>
                                <p class="text-muted mt-3">No campaigns yet. Schedule your first campaign!</p>
                                <button type="button" class="btn btn-primary" data-bs-toggle="offcanvas" data-bs-target="#campaignDrawer">
                                    <i class="ti ti-calendar-plus me-1"></i> Schedule a Campaign
                                </button>
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

<!-- Campaign Drawer -->
<div class="offcanvas offcanvas-end" tabindex="-1" id="campaignDrawer" aria-labelledby="campaignDrawerLabel" style="width: 600px;">
    <div class="offcanvas-header">
        <h5 class="offcanvas-title" id="campaignDrawerLabel">Schedule a Campaign</h5>
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body">
        <form id="campaignForm">
            @csrf

            <!-- Campaign Name -->
            <div class="mb-3">
                <label for="campaign_name" class="form-label">Campaign Name <span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="campaign_name" name="name" required>
            </div>

            <!-- Objective -->
            <div class="mb-3">
                <label for="objective" class="form-label">Objective</label>
                <textarea class="form-control" id="objective" name="objective" rows="3" placeholder="What's the goal of this campaign?"></textarea>
            </div>

            <!-- Status -->
            <div class="mb-3">
                <label for="campaign_status" class="form-label">Status <span class="text-danger">*</span></label>
                <select class="form-select" id="campaign_status" name="status" required style="display: block !important; visibility: visible !important; opacity: 1 !important; height: auto !important; width: 100% !important;">
                    <option value="active">Active</option>
                    <option value="inactive">Inactive</option>
                </select>
                <small class="text-muted d-block mt-1">Note: Only active campaigns will send emails and schedules will work.</small>
            </div>

            <!-- Template -->
            <div class="mb-3">
                <label for="template_id" class="form-label">Select Testimonial Template <span class="text-danger">*</span></label>
                <select class="form-select" id="template_id" name="template_id" required style="display: block !important; visibility: visible !important; opacity: 1 !important; height: auto !important; width: 100% !important;">
                    <option value="">Choose a template...</option>
                    @foreach($templates as $template)
                        <option value="{{ $template->id }}">{{ $template->name }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Subscriber List -->
            <div class="mb-3">
                <label for="segment_ids" class="form-label">
                    Select Subscriber List <span class="text-danger">*</span>
                    <span id="subscriberCount" class="badge bg-info ms-2"></span>
                </label>
                <input type="text" class="form-control" id="segment_ids" placeholder="Select subscriber segments..." value="" autocomplete="off">
                <div id="segmentIdsContainer"></div>
                @php
                    $campaignSegments = \App\Models\UserSegment::where('team_id', Auth::user()->current_team_id)->get();

                    // Get all unique subscriber emails across all segments (deduplicated)
                    $allSubscribers = \App\Models\Subscriber::where('status', 'subscribed')
                        ->whereHas('segments', function($query) use ($campaignSegments) {
                            $query->whereIn('user_segments.id', $campaignSegments->pluck('id'));
                        })
                        ->get();

                    // Get emails that have already submitted testimonials
                    $testimonialEmails = \App\Models\Testimonial::whereNotNull('email')
                        ->where('team_id', Auth::user()->current_team_id)
                        ->pluck('email')
                        ->unique()
                        ->toArray();

                    // Calculate counts for each segment with deduplication
                    $segmentsWithCounts = $campaignSegments->map(function($segment) use ($testimonialEmails) {
                        $subscribers = $segment->subscribers()->where('status', 'subscribed')->get();
                        $uniqueEmails = $subscribers->pluck('email')->unique();
                        $subscribersWithoutTestimonials = $uniqueEmails->diff($testimonialEmails);

                        return [
                            'id' => $segment->id,
                            'name' => $segment->name,
                            'subscriber_emails' => $uniqueEmails->toArray(),
                            'subscriber_count' => $uniqueEmails->count(),
                            'subscriber_count_without_testimonials' => $subscribersWithoutTestimonials->count()
                        ];
                    });

                    // Total unique subscribers (deduplicated across all segments)
                    $totalUniqueEmails = $allSubscribers->pluck('email')->unique();
                    $totalSubscribers = $totalUniqueEmails->count();
                    $totalSubscribersWithoutTestimonials = $totalUniqueEmails->diff($testimonialEmails)->count();
                @endphp
            </div>

            <!-- Exclude Testimonial Givers -->
            <div class="mb-3">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="exclude_testimonial_givers" name="exclude_testimonial_givers" value="1">
                    <label class="form-check-label" for="exclude_testimonial_givers">
                        Remove subscribers that already gave a testimonial
                    </label>
                    <small class="text-muted d-block mt-1">This will prevent sending duplicate emails to subscribers who have already submitted testimonials.</small>
                </div>
            </div>

            <!-- Email Delivery Type -->
            <div class="mb-3">
                <label for="delivery_type" class="form-label">Email Delivery Type <span class="text-danger">*</span></label>
                <select class="form-select" id="delivery_type" name="delivery_type" required onchange="toggleScheduleField()">
                    <option value="instant">Instant</option>
                    <option value="scheduled">Scheduled</option>
                </select>
            </div>

            <!-- Schedule Date (hidden by default) -->
            <div class="mb-3" id="schedule_field" style="display: none;">
                <label for="scheduled_at" class="form-label">Schedule Date for Email Delivery <span class="text-danger">*</span></label>
                <input type="datetime-local" class="form-control" id="scheduled_at" name="scheduled_at">
            </div>

            <!-- Submit Button -->
            <div class="d-grid gap-2">
                <button type="submit" class="btn btn-primary btn-lg">
                    <i class="ti ti-calendar-check me-1"></i> Schedule Campaign
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/@yaireo/tagify"></script>
<script src="https://cdn.jsdelivr.net/npm/@yaireo/tagify/dist/tagify.polyfills.min.js"></script>
<script>
// Initialize Tagify for segment selection
document.addEventListener('DOMContentLoaded', function() {
    // Force campaign select fields to be visible
    const campaignStatusSelect = document.getElementById('campaign_status');
    const templateSelect = document.getElementById('template_id');

    if (campaignStatusSelect) {
        campaignStatusSelect.style.display = 'block';
        campaignStatusSelect.style.visibility = 'visible';
        campaignStatusSelect.style.opacity = '1';
        campaignStatusSelect.style.height = 'auto';
        campaignStatusSelect.style.width = '100%';
    }

    if (templateSelect) {
        templateSelect.style.display = 'block';
        templateSelect.style.visibility = 'visible';
        templateSelect.style.opacity = '1';
        templateSelect.style.height = 'auto';
        templateSelect.style.width = '100%';
    }

    // Check for success message from sessionStorage
    const successMessage = sessionStorage.getItem('campaignSuccess');
    if (successMessage) {
        // Remove from sessionStorage
        sessionStorage.removeItem('campaignSuccess');

        // Show success toast
        showSuccessToast(successMessage);

        // Also show alert at the top
        const alertContainer = document.getElementById('campaignSuccessAlert');
        if (alertContainer) {
            alertContainer.innerHTML = `
                <div class="alert alert-success alert-dismissible fade show mb-3" role="alert">
                    <i class="ti ti-check-circle me-2"></i>${successMessage}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            `;
            alertContainer.style.display = 'block';

            // Auto-dismiss after 5 seconds
            setTimeout(() => {
                const alert = alertContainer.querySelector('.alert');
                if (alert) {
                    const bsAlert = new bootstrap.Alert(alert);
                    bsAlert.close();
                }
            }, 5000);
        }
    }

    const segmentsData = @json($segmentsWithCounts ?? []);
    const totalSubscribers = @json($totalSubscribers ?? 0);
    const totalSubscribersWithoutTestimonials = @json($totalSubscribersWithoutTestimonials ?? 0);

    const segments = segmentsData.map(segment => ({
        id: segment.id,
        name: segment.name,
        subscriber_emails: segment.subscriber_emails || [],
        subscriber_count: segment.subscriber_count,
        subscriber_count_without_testimonials: segment.subscriber_count_without_testimonials
    }));

    console.log('Segments data:', segments);
    console.log('Whitelist:', segments.map(s => s.name));

    const segmentInput = document.querySelector('#segment_ids');
    const segmentIdsContainer = document.getElementById('segmentIdsContainer');
    const subscriberCountBadge = document.getElementById('subscriberCount');
    const excludeTestimonialGivers = document.getElementById('exclude_testimonial_givers');
    let tagify; // Declare tagify variable here before it's used

    // Function to update subscriber count display
    function updateSubscriberCount() {
        const excludeChecked = excludeTestimonialGivers ? excludeTestimonialGivers.checked : false;
        const selectedSegmentNames = tagify ? tagify.value.map(tag => tag.value) : [];

        if (selectedSegmentNames.length === 0) {
            // No segments selected - show total unique count
            const count = excludeChecked ? totalSubscribersWithoutTestimonials : totalSubscribers;
            subscriberCountBadge.textContent = `Total: ${count} subscribers`;
        } else {
            // Segments selected - calculate unique count across selected segments
            const selectedSegments = segments.filter(s => selectedSegmentNames.includes(s.name));

            // Get unique emails across all selected segments (deduplication)
            const uniqueEmails = new Set();
            selectedSegments.forEach(segment => {
                segment.subscriber_emails.forEach(email => uniqueEmails.add(email));
            });

            let count;
            if (excludeChecked) {
                // Use the count without testimonials for selected segments
                // We need to deduplicate across selected segments
                count = selectedSegments.reduce((sum, s) => sum + s.subscriber_count_without_testimonials, 0);
                // Note: This is an approximation. For exact deduplication with filter,
                // we'd need the full email lists without testimonials, but this gives a good estimate
            } else {
                count = uniqueEmails.size;
            }

            subscriberCountBadge.textContent = `${count} subscribers`;
        }
    }

    // Initialize with total subscriber count
    if (subscriberCountBadge) {
        updateSubscriberCount();
    }

    if (segmentInput) {
        // Clear any existing value
        segmentInput.value = '';

        tagify = new Tagify(segmentInput, {
            whitelist: segments.map(s => s.name),
            maxTags: segments.length,
            dropdown: {
                maxItems: 20,
                enabled: 0,              // Show dropdown on focus without typing
                closeOnSelect: false,    // Keep dropdown open after selection
                highlightFirst: true,    // Highlight first item
                position: 'text'         // Position dropdown relative to text
            },
            placeholder: "Select subscriber segments...",
            enforceWhitelist: true,
            keepInvalidTags: false,
            duplicates: false
        });

        // Show dropdown when input is focused/clicked
        tagify.on('focus', function() {
            tagify.dropdown.show.call(tagify, tagify.value[tagify.value.length - 1]);
        });

        // Show dropdown when clicking on the input
        segmentInput.addEventListener('click', function() {
            if (!tagify.state.dropdown.visible) {
                tagify.dropdown.show.call(tagify);
            }
        });

        // Update hidden inputs and subscriber count whenever tagify changes
        tagify.on('change', function(e) {
            // Clear existing hidden inputs
            segmentIdsContainer.innerHTML = '';

            // Get selected segment names
            const selectedSegmentNames = tagify.value.map(tag => tag.value);

            // Convert names to IDs
            const selectedSegments = segments.filter(s => selectedSegmentNames.includes(s.name));
            const selectedSegmentIds = selectedSegments.map(s => parseInt(s.id));

            // Add hidden inputs for each selected segment ID
            selectedSegmentIds.forEach(id => {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'segment_ids[]';
                input.value = id;
                segmentIdsContainer.appendChild(input);
            });

            // Update subscriber count
            updateSubscriberCount();
        });
    }

    // Update count when checkbox changes
    if (excludeTestimonialGivers) {
        excludeTestimonialGivers.addEventListener('change', function() {
            updateSubscriberCount();
        });
    }

    // Reset form when drawer is opened
    const campaignDrawer = document.getElementById('campaignDrawer');
    if (campaignDrawer) {
        campaignDrawer.addEventListener('show.bs.offcanvas', function() {
            // Reset the form
            document.getElementById('campaignForm').reset();

            // Clear Tagify
            if (tagify) {
                tagify.removeAllTags();
            }

            // Clear hidden inputs
            segmentIdsContainer.innerHTML = '';

            // Reset subscriber count
            updateSubscriberCount();
        });
    }
});

function toggleScheduleField() {
    const deliveryType = document.getElementById('delivery_type').value;
    const scheduleField = document.getElementById('schedule_field');
    const scheduledAtInput = document.getElementById('scheduled_at');

    if (deliveryType === 'scheduled') {
        scheduleField.style.display = 'block';
        scheduledAtInput.required = true;
    } else {
        scheduleField.style.display = 'none';
        scheduledAtInput.required = false;
    }
}

// Handle campaign form submission
document.getElementById('campaignForm').addEventListener('submit', function(e) {
    e.preventDefault();

    const formData = new FormData(this);
    const data = {};

    formData.forEach((value, key) => {
        if (key === 'segment_ids[]') {
            if (!data.segment_ids) {
                data.segment_ids = [];
            }
            // Convert to integer
            data.segment_ids.push(parseInt(value));
        } else if (key === 'exclude_testimonial_givers') {
            // Convert checkbox to boolean
            data.exclude_testimonial_givers = value === '1' ? true : false;
        } else {
            data[key] = value;
        }
    });

    // Ensure exclude_testimonial_givers is set even if checkbox is unchecked
    if (!data.hasOwnProperty('exclude_testimonial_givers')) {
        data.exclude_testimonial_givers = false;
    }

    console.log('Submitting campaign data:', data);

    // Disable submit button to prevent double submission
    const submitBtn = this.querySelector('button[type="submit"]');
    const originalBtnText = submitBtn.innerHTML;
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Creating...';

    // Submit via AJAX
    fetch('{{ route("testimonial-campaigns.store") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
        },
        body: JSON.stringify(data)
    })
    .then(response => {
        if (!response.ok) {
            return response.json().then(err => Promise.reject(err));
        }
        return response.json();
    })
    .then(data => {
        console.log('Campaign created:', data);

        // Show success message and redirect
        sessionStorage.setItem('campaignSuccess', data.message || 'Campaign created successfully!');
        window.location.href = '{{ route("testimonials.index", ["tab" => "campaigns"]) }}';
    })
    .catch(error => {
        console.error('Error:', error);
        submitBtn.disabled = false;
        submitBtn.innerHTML = originalBtnText;

        let errorMessage = 'Failed to create campaign. Please try again.';
        if (error.message) {
            errorMessage = error.message;
        } else if (error.errors) {
            errorMessage = Object.values(error.errors).flat().join('\n');
        }
        alert(errorMessage);
    });
});

function viewCampaignStats(campaignId) {
    // Fetch and display campaign statistics
    fetch(`/testimonial-campaigns/${campaignId}/statistics`)
        .then(response => response.json())
        .then(data => {
            let statsHtml = `
                <div class="modal fade" id="statsModal" tabindex="-1">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Campaign Statistics</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body">
                                <div class="row">
                                    <div class="col-6 mb-3">
                                        <strong>Total Subscribers:</strong> ${data.total_subscribers}
                                    </div>
                                    <div class="col-6 mb-3">
                                        <strong>Emails Sent:</strong> ${data.emails_sent}
                                    </div>
                                    <div class="col-6 mb-3">
                                        <strong>Emails Failed:</strong> <span class="text-danger">${data.emails_failed || 0}</span>
                                    </div>
                                    <div class="col-6 mb-3">
                                        <strong>Emails Opened:</strong> ${data.emails_opened} (${data.open_rate}%)
                                    </div>
                                    <div class="col-6 mb-3">
                                        <strong>Links Clicked:</strong> ${data.links_clicked} (${data.click_rate}%)
                                    </div>
                                    <div class="col-6 mb-3">
                                        <strong>Testimonials Submitted:</strong> ${data.testimonials_submitted} (${data.conversion_rate}%)
                                    </div>
                                    <div class="col-12 mb-3">
                                        <strong>Average Rating:</strong>
                                        ${data.average_rating ?
                                            `<span class="text-warning">
                                                ${[1,2,3,4,5].map(i => `<i class="ti ti-star${i <= Math.round(data.average_rating) ? '-filled' : ''}"></i>`).join('')}
                                                <span class="ms-2">${data.average_rating.toFixed(1)}</span>
                                            </span>`
                                            : '<span class="text-muted">N/A</span>'}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            `;

            // Remove existing modal if any
            document.querySelectorAll('#statsModal').forEach(el => el.remove());

            // Add new modal to body
            document.body.insertAdjacentHTML('beforeend', statsHtml);

            // Show modal
            const modal = new bootstrap.Modal(document.getElementById('statsModal'));
            modal.show();
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Failed to load statistics.');
        });
}

function deleteCampaign(campaignId) {
    if (confirm('Are you sure you want to delete this campaign?')) {
        fetch(`/testimonial-campaigns/${campaignId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.message) {
                alert(data.message);
                window.location.reload();
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Failed to delete campaign.');
        });
    }
}

// Function to show success toast notification
function showSuccessToast(message) {
    // Create toast container if it doesn't exist
    let toastContainer = document.querySelector('.toast-container');
    if (!toastContainer) {
        toastContainer = document.createElement('div');
        toastContainer.className = 'toast-container position-fixed top-0 end-0 p-3';
        toastContainer.style.zIndex = '9999';
        document.body.appendChild(toastContainer);
    }

    // Create toast element
    const toastId = 'toast-' + Date.now();
    const toastHtml = `
        <div id="${toastId}" class="toast align-items-center text-bg-success border-0" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="d-flex">
                <div class="toast-body">
                    <i class="ti ti-check-circle me-2"></i>${message}
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
        </div>
    `;

    toastContainer.insertAdjacentHTML('beforeend', toastHtml);

    // Show toast
    const toastElement = document.getElementById(toastId);
    const toast = new bootstrap.Toast(toastElement, {
        autohide: true,
        delay: 5000
    });
    toast.show();

    // Remove toast element after it's hidden
    toastElement.addEventListener('hidden.bs.toast', function() {
        toastElement.remove();
    });
}
</script>
@endpush
@endsection
