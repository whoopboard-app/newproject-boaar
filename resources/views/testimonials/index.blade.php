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

            <!-- Campaigns Tab -->
            <div class="tab-pane fade {{ $activeTab === 'campaigns' ? 'show active' : '' }}" id="campaigns" role="tabpanel">
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
                                                        <button class="btn btn-sm btn-outline-primary" title="View Statistics" onclick="viewCampaignStats({{ $campaign->id }})">
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
                <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
                <select class="form-select" id="status" name="status" required>
                    <option value="draft">Draft</option>
                    <option value="active">Active</option>
                    <option value="inactive">Inactive</option>
                </select>
                <small class="text-muted">Note: Only active campaigns will send emails and schedules will work.</small>
            </div>

            <!-- Template -->
            <div class="mb-3">
                <label for="template_id" class="form-label">Select Testimonial Template <span class="text-danger">*</span></label>
                <select class="form-select" id="template_id" name="template_id" required>
                    <option value="">Choose a template...</option>
                    @foreach($templates as $template)
                        <option value="{{ $template->id }}">{{ $template->name }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Subscriber List -->
            <div class="mb-3">
                <label for="segment_ids" class="form-label">Select Subscriber List <span class="text-danger">*</span></label>
                <select class="form-select" id="segment_ids" name="segment_ids[]" multiple required style="min-height: 100px;">
                    @php
                        $segments = \App\Models\UserSegment::where('team_id', Auth::user()->current_team_id)->get();
                    @endphp
                    @foreach($segments as $segment)
                        <option value="{{ $segment->id }}">{{ $segment->name }} ({{ $segment->subscribers()->count() }} subscribers)</option>
                    @endforeach
                </select>
                <small class="text-muted">Hold Ctrl/Cmd to select multiple lists</small>
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
<script>
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
            data.segment_ids.push(value);
        } else {
            data[key] = value;
        }
    });

    // Submit via AJAX
    fetch('{{ route("testimonial-campaigns.store") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
        },
        body: JSON.stringify(data)
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
        alert('Failed to create campaign. Please try again.');
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
                                        <strong>Emails Opened:</strong> ${data.emails_opened} (${data.open_rate}%)
                                    </div>
                                    <div class="col-6 mb-3">
                                        <strong>Links Clicked:</strong> ${data.links_clicked} (${data.click_rate}%)
                                    </div>
                                    <div class="col-6 mb-3">
                                        <strong>Testimonials Submitted:</strong> ${data.testimonials_submitted} (${data.conversion_rate}%)
                                    </div>
                                    <div class="col-6 mb-3">
                                        <strong>Average Rating:</strong> ${data.average_rating ? data.average_rating.toFixed(1) : 'N/A'}
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
</script>
@endpush
@endsection
