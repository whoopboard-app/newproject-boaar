@extends('layouts.inspinia')

@section('title', 'Campaign Details - ' . $campaign->name)

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/@yaireo/tagify/dist/tagify.css" rel="stylesheet" type="text/css">
<style>
    .tagify {
        min-height: 39.51px !important;
        padding: 0.375rem 0.75rem !important;
        border: 1px solid #dee2e6 !important;
        border-radius: 0.25rem !important;
    }
    .tagify__tag--locked {
        background-color: #6c757d !important;
        color: #fff !important;
        cursor: not-allowed !important;
        opacity: 0.9 !important;
    }
    .tagify__tag--locked .tagify__tag__removeBtn {
        display: none !important;
    }
    .tagify__tag--locked:hover {
        background-color: #6c757d !important;
    }
    .tagify__tag--locked > div {
        color: #fff !important;
    }
    .tagify__tag--locked .tagify__tag-text {
        color: #fff !important;
    }
</style>
@endpush

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-flex align-items-center justify-content-between">
            <div>
                <h2 class="mb-0">{{ $campaign->name }}</h2>
                <p class="text-muted fs-14 mb-0">Campaign Details & Reports</p>
            </div>
            <div>
                <a href="{{ route('testimonials.index', ['tab' => 'campaigns']) }}" class="btn btn-secondary">
                    <i class="ti ti-arrow-left me-1"></i> Back to Campaigns
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Success Message -->
@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="ti ti-check-circle me-2"></i>{{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<!-- Success message container for dynamic messages -->
<div id="dynamicSuccessMessage" style="display: none;"></div>

<!-- Quick Overview Cards -->
<div class="row mb-4">
    <div class="col-lg-2 col-md-6 mb-3">
        <div class="card">
            <div class="card-body text-center">
                <i class="ti ti-users text-primary" style="font-size: 2rem;"></i>
                <h3 class="mt-2">{{ $stats['total_subscribers'] }}</h3>
                <p class="text-muted mb-0">Total Subscribers</p>
            </div>
        </div>
    </div>
    <div class="col-lg-2 col-md-6 mb-3">
        <div class="card">
            <div class="card-body text-center">
                <i class="ti ti-mail-check text-success" style="font-size: 2rem;"></i>
                <h3 class="mt-2 text-success">{{ $stats['emails_sent'] }}</h3>
                <p class="text-muted mb-0">Emails Sent</p>
                <small class="text-muted">{{ $stats['open_rate'] }}% open rate</small>
            </div>
        </div>
    </div>
    <div class="col-lg-2 col-md-6 mb-3">
        <div class="card">
            <div class="card-body text-center">
                <i class="ti ti-mail-x text-danger" style="font-size: 2rem;"></i>
                <h3 class="mt-2 text-danger">{{ $stats['emails_failed'] }}</h3>
                <p class="text-muted mb-0">Emails Failed</p>
            </div>
        </div>
    </div>
    <div class="col-lg-2 col-md-6 mb-3">
        <div class="card">
            <div class="card-body text-center">
                <i class="ti ti-message-check text-info" style="font-size: 2rem;"></i>
                <h3 class="mt-2 text-info">{{ $stats['testimonials_submitted'] }}</h3>
                <p class="text-muted mb-0">Testimonials</p>
                <small class="text-muted">{{ $stats['conversion_rate'] }}% conversion</small>
            </div>
        </div>
    </div>
    <div class="col-lg-2 col-md-6 mb-3">
        <div class="card">
            <div class="card-body text-center">
                <i class="ti ti-star text-warning" style="font-size: 2rem;"></i>
                @if($stats['average_rating'])
                    <h3 class="mt-2 text-warning">{{ number_format($stats['average_rating'], 1) }}</h3>
                    <div class="text-warning mb-2">
                        @for($i = 1; $i <= 5; $i++)
                            <i class="ti ti-star{{ $i <= round($stats['average_rating']) ? '-filled' : '' }}"></i>
                        @endfor
                    </div>
                @else
                    <h3 class="mt-2 text-muted">-</h3>
                @endif
                <p class="text-muted mb-0">Average Rating</p>
            </div>
        </div>
    </div>
    <div class="col-lg-2 col-md-6 mb-3">
        <div class="card">
            <div class="card-body text-center">
                <i class="ti ti-click text-secondary" style="font-size: 2rem;"></i>
                <h3 class="mt-2">{{ $stats['links_clicked'] }}</h3>
                <p class="text-muted mb-0">Links Clicked</p>
                <small class="text-muted">{{ $stats['click_rate'] }}% rate</small>
            </div>
        </div>
    </div>
</div>

<!-- Campaign Info & Actions -->
<div class="row mb-4">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Campaign Information</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <strong>Status:</strong>
                        @if($campaign->status === 'active')
                            <span class="badge bg-success">Active</span>
                        @elseif($campaign->status === 'inactive')
                            <span class="badge bg-secondary">Inactive</span>
                        @else
                            <span class="badge bg-warning">Draft</span>
                        @endif
                    </div>
                    <div class="col-md-6 mb-3">
                        <strong>Template:</strong> {{ $campaign->template->name }}
                    </div>
                    <div class="col-md-6 mb-3">
                        <strong>Delivery Type:</strong>
                        <span class="badge bg-info">{{ ucfirst($campaign->delivery_type) }}</span>
                    </div>
                    <div class="col-md-6 mb-3">
                        <strong>Created:</strong> {{ $campaign->created_at->format('M d, Y H:i') }}
                    </div>
                    @if($campaign->scheduled_at)
                        <div class="col-md-6 mb-3">
                            <strong>Scheduled:</strong> {{ $campaign->scheduled_at->format('M d, Y H:i') }}
                        </div>
                    @endif
                    @if($campaign->sent_at)
                        <div class="col-md-6 mb-3">
                            <strong>Sent:</strong> {{ $campaign->sent_at->format('M d, Y H:i') }}
                        </div>
                    @endif
                    @if($campaign->objective)
                        <div class="col-12 mb-3">
                            <strong>Objective:</strong>
                            <p class="mb-0">{{ $campaign->objective }}</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Actions</h5>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <button type="button" class="btn btn-primary" data-bs-toggle="offcanvas" data-bs-target="#editCampaignDrawer">
                        <i class="ti ti-edit me-1"></i> Edit Campaign
                    </button>
                    <form action="{{ route('testimonial-campaigns.pause', $campaign) }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-warning w-100">
                            <i class="ti ti-{{ $campaign->status === 'active' ? 'player-pause' : 'player-play' }} me-1"></i>
                            {{ $campaign->status === 'active' ? 'Pause' : 'Resume' }} Campaign
                        </button>
                    </form>
                    <button class="btn btn-danger" onclick="deleteCampaignFromView({{ $campaign->id }})">
                        <i class="ti ti-trash me-1"></i> Delete Campaign
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Tabs for Email Logs -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <ul class="nav nav-tabs card-header-tabs" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" data-bs-toggle="tab" href="#deliveryLog">
                            <i class="ti ti-mail-check me-1"></i> Email Delivery Log ({{ $sentEmails->count() }})
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-bs-toggle="tab" href="#failedLog">
                            <i class="ti ti-mail-x me-1"></i> Failed Email Log ({{ $failedEmails->count() }})
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-bs-toggle="tab" href="#scheduledLog">
                            <i class="ti ti-clock me-1"></i> Scheduled Email Log ({{ $scheduledEmails->count() }})
                        </a>
                    </li>
                </ul>
            </div>
            <div class="card-body">
                <div class="tab-content">
                    <!-- Delivery Log -->
                    <div class="tab-pane fade show active" id="deliveryLog">
                        @if($sentEmails->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>Email</th>
                                            <th>Name</th>
                                            <th>Sent At</th>
                                            <th>Opened</th>
                                            <th>Clicked</th>
                                            <th>Submitted</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($sentEmails as $email)
                                            <tr>
                                                <td>{{ $email->subscriber->email }}</td>
                                                <td>{{ $email->subscriber->full_name }}</td>
                                                <td>{{ $email->sent_at ? $email->sent_at->format('M d, Y H:i') : '-' }}</td>
                                                <td>
                                                    @if($email->email_opened)
                                                        <span class="badge bg-success"><i class="ti ti-check"></i> Yes</span>
                                                    @else
                                                        <span class="badge bg-secondary">No</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if($email->link_clicked)
                                                        <span class="badge bg-info"><i class="ti ti-check"></i> Yes</span>
                                                    @else
                                                        <span class="badge bg-secondary">No</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if($email->testimonial_submitted)
                                                        <span class="badge bg-primary"><i class="ti ti-check"></i> Yes</span>
                                                    @else
                                                        <span class="badge bg-secondary">No</span>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <p class="text-muted text-center py-5">No emails sent yet.</p>
                        @endif
                    </div>

                    <!-- Failed Log -->
                    <div class="tab-pane fade" id="failedLog">
                        @if($failedEmails->count() > 0)
                            <form id="resendForm" method="POST" action="{{ route('testimonial-campaigns.resend-failed', $campaign) }}">
                                @csrf
                                <div class="mb-3">
                                    <button type="submit" class="btn btn-primary btn-sm">
                                        <i class="ti ti-send me-1"></i> Resend Selected
                                    </button>
                                    <label class="ms-3">
                                        <input type="checkbox" id="selectAll" class="form-check-input me-1">
                                        Select All
                                    </label>
                                </div>
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead>
                                            <tr>
                                                <th width="30"><input type="checkbox" id="selectAllHeader" class="form-check-input"></th>
                                                <th>Email</th>
                                                <th>Name</th>
                                                <th>Attempts</th>
                                                <th>Failure Reason</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($failedEmails as $email)
                                                <tr>
                                                    <td><input type="checkbox" name="subscriber_ids[]" value="{{ $email->id }}" class="form-check-input failed-checkbox"></td>
                                                    <td>{{ $email->subscriber->email }}</td>
                                                    <td>{{ $email->subscriber->full_name }}</td>
                                                    <td><span class="badge bg-warning">{{ $email->send_attempts }}</span></td>
                                                    <td><small class="text-danger">{{ Str::limit($email->failure_reason, 80) }}</small></td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </form>
                        @else
                            <p class="text-muted text-center py-5">No failed emails.</p>
                        @endif
                    </div>

                    <!-- Scheduled Log -->
                    <div class="tab-pane fade" id="scheduledLog">
                        @if($scheduledEmails->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>Email</th>
                                            <th>Name</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($scheduledEmails as $email)
                                            <tr>
                                                <td>{{ $email->subscriber->email }}</td>
                                                <td>{{ $email->subscriber->full_name }}</td>
                                                <td><span class="badge bg-info">Pending</span></td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <p class="text-muted text-center py-5">No scheduled emails pending.</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Edit Campaign Drawer -->
<div class="offcanvas offcanvas-end" tabindex="-1" id="editCampaignDrawer" aria-labelledby="editCampaignDrawerLabel" style="width: 600px;">
    <div class="offcanvas-header">
        <h5 class="offcanvas-title" id="editCampaignDrawerLabel">Edit Campaign</h5>
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body">
        <form id="editCampaignForm">
            @csrf
            @method('PUT')

            <!-- Campaign Name -->
            <div class="mb-3">
                <label for="edit_campaign_name" class="form-label">Campaign Name <span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="edit_campaign_name" name="name" value="{{ $campaign->name }}" required>
            </div>

            <!-- Objective -->
            <div class="mb-3">
                <label for="edit_objective" class="form-label">Objective</label>
                <textarea class="form-control" id="edit_objective" name="objective" rows="3" placeholder="What's the goal of this campaign?">{{ $campaign->objective }}</textarea>
            </div>

            <!-- Status (Read-only) -->
            <div class="mb-3">
                <label class="form-label">Status</label>
                <input type="text" class="form-control" value="@if($campaign->status === 'active')Active @elseif($campaign->status === 'inactive')Inactive @else Draft @endif" readonly disabled>
                <small class="text-muted">Status cannot be changed from edit. Use Pause/Resume button instead.</small>
            </div>

            <!-- Template (Read-only) -->
            <div class="mb-3">
                <label class="form-label">Testimonial Template</label>
                <input type="text" class="form-control" value="{{ $campaign->template->name }}" readonly disabled>
                <small class="text-muted">Template cannot be changed after campaign creation</small>
            </div>

            @php
                // Get segments used in this campaign by analyzing campaign subscribers
                $campaignSubscriberIds = $campaign->campaignSubscribers->pluck('subscriber_id')->unique();
                $campaignSubscribers = \App\Models\Subscriber::whereIn('id', $campaignSubscriberIds)->with('segments')->get();

                // Get unique segments from all campaign subscribers
                $existingSegmentIds = [];
                foreach ($campaignSubscribers as $subscriber) {
                    foreach ($subscriber->segments as $segment) {
                        $existingSegmentIds[] = $segment->id;
                    }
                }
                $existingSegmentIds = array_unique($existingSegmentIds);
                $existingSegments = \App\Models\UserSegment::whereIn('id', $existingSegmentIds)->get();

                // Calculate subscriber counts for existing segments
                $existingSegmentsWithCounts = $existingSegments->map(function($segment) {
                    $subscribers = $segment->subscribers()->where('status', 'subscribed')->get();
                    $uniqueEmails = $subscribers->pluck('email')->unique();

                    return [
                        'id' => $segment->id,
                        'name' => $segment->name,
                        'subscriber_count' => $uniqueEmails->count(),
                    ];
                })->values();

                // Calculate total subscriber count
                $totalSubscriberCount = $existingSegmentsWithCounts->sum('subscriber_count');
            @endphp

            <!-- Subscriber List (Read-only) -->
            <div class="mb-3">
                <label class="form-label">
                    Subscriber Segments
                    <span id="editSubscriberCount" class="badge bg-info ms-2"></span>
                </label>
                <div class="form-control bg-light" style="min-height: 45px; display: flex; flex-wrap: wrap; gap: 5px; align-items: center;">
                    @foreach($existingSegments as $segment)
                        <span class="badge bg-secondary" style="font-size: 0.875rem; padding: 0.5rem 0.75rem;">
                            <i class="ti ti-lock me-1"></i>{{ $segment->name }}
                        </span>
                    @endforeach
                    @if($existingSegments->isEmpty())
                        <span class="text-muted">No segments selected</span>
                    @endif
                </div>
                <small class="text-muted d-block mt-1">
                    <i class="ti ti-info-circle me-1"></i>Subscriber segments cannot be changed after campaign creation.
                </small>
            </div>

            <!-- Email Delivery Type -->
            <div class="mb-3">
                <label for="edit_delivery_type" class="form-label">Email Delivery Type <span class="text-danger">*</span></label>
                <select class="form-select" id="edit_delivery_type" name="delivery_type" required onchange="toggleEditScheduleField()">
                    <option value="instant" {{ $campaign->delivery_type === 'instant' ? 'selected' : '' }}>Instant</option>
                    <option value="scheduled" {{ $campaign->delivery_type === 'scheduled' ? 'selected' : '' }}>Scheduled</option>
                </select>
            </div>

            <!-- Schedule Date -->
            <div class="mb-3" id="edit_schedule_field" style="display: {{ $campaign->delivery_type === 'scheduled' ? 'block' : 'none' }};">
                <label for="edit_scheduled_at" class="form-label">Schedule Date for Email Delivery <span class="text-danger">*</span></label>
                <input type="datetime-local" class="form-control" id="edit_scheduled_at" name="scheduled_at"
                       value="{{ $campaign->scheduled_at ? $campaign->scheduled_at->format('Y-m-d\TH:i') : '' }}"
                       {{ $campaign->delivery_type === 'scheduled' ? 'required' : '' }}>
            </div>

            <!-- Submit Button -->
            <div class="d-grid gap-2">
                <button type="submit" class="btn btn-primary btn-lg">
                    <i class="ti ti-check me-1"></i> Update Campaign
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/@yaireo/tagify"></script>
<script src="https://cdn.jsdelivr.net/npm/@yaireo/tagify/dist/tagify.polyfills.min.js"></script>
<script>
// Select all checkboxes functionality
document.addEventListener('DOMContentLoaded', function() {
    const selectAllCheckbox = document.getElementById('selectAll');
    const selectAllHeaderCheckbox = document.getElementById('selectAllHeader');
    const failedCheckboxes = document.querySelectorAll('.failed-checkbox');

    if (selectAllCheckbox) {
        selectAllCheckbox.addEventListener('change', function() {
            failedCheckboxes.forEach(cb => cb.checked = this.checked);
            if (selectAllHeaderCheckbox) selectAllHeaderCheckbox.checked = this.checked;
        });
    }

    if (selectAllHeaderCheckbox) {
        selectAllHeaderCheckbox.addEventListener('change', function() {
            failedCheckboxes.forEach(cb => cb.checked = this.checked);
            if (selectAllCheckbox) selectAllCheckbox.checked = this.checked;
        });
    }

    // Form submission
    const resendForm = document.getElementById('resendForm');
    if (resendForm) {
        resendForm.addEventListener('submit', function(e) {
            e.preventDefault();

            const checkedBoxes = document.querySelectorAll('.failed-checkbox:checked');
            if (checkedBoxes.length === 0) {
                alert('Please select at least one email to resend.');
                return;
            }

            if (!confirm(`Are you sure you want to resend ${checkedBoxes.length} email(s)?`)) {
                return;
            }

            // Convert to AJAX submission
            const formData = new FormData(this);
            const subscriberIds = [];
            formData.getAll('subscriber_ids[]').forEach(id => subscriberIds.push(parseInt(id)));

            fetch(this.action, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({ subscriber_ids: subscriberIds })
            })
            .then(response => response.json())
            .then(data => {
                alert(data.message);
                window.location.reload();
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Failed to resend emails. Please try again.');
            });
        });
    }
});

function deleteCampaignFromView(campaignId) {
    if (confirm('Are you sure you want to delete this campaign? This action cannot be undone.')) {
        fetch(`/testimonial-campaigns/${campaignId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        })
        .then(response => response.json())
        .then(data => {
            alert(data.message);
            window.location.href = '{{ route('testimonials.index', ['tab' => 'campaigns']) }}';
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Failed to delete campaign.');
        });
    }
}

function toggleEditScheduleField() {
    const deliveryType = document.getElementById('edit_delivery_type').value;
    const scheduleField = document.getElementById('edit_schedule_field');
    const scheduledAtInput = document.getElementById('edit_scheduled_at');

    if (deliveryType === 'scheduled') {
        scheduleField.style.display = 'block';
        scheduledAtInput.required = true;
    } else {
        scheduleField.style.display = 'none';
        scheduledAtInput.required = false;
    }
}

// Initialize Edit Campaign - Display subscriber count
const editSubscriberCountBadge = document.getElementById('editSubscriberCount');
if (editSubscriberCountBadge) {
    const totalCount = @json($totalSubscriberCount);
    editSubscriberCountBadge.textContent = `${totalCount} subscribers`;
}

// Handle edit campaign form submission
document.getElementById('editCampaignForm').addEventListener('submit', function(e) {
    e.preventDefault();

    const formData = new FormData(this);
    const data = {};

    formData.forEach((value, key) => {
        if (key === '_method') {
            // Skip the _method field as we'll use PUT in fetch
        } else {
            data[key] = value;
        }
    });

    console.log('Updating campaign with data:', data);

    // Disable submit button
    const submitBtn = this.querySelector('button[type="submit"]');
    const originalBtnText = submitBtn.innerHTML;
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Updating...';

    // Submit via AJAX
    fetch('{{ route("testimonial-campaigns.update", $campaign) }}', {
        method: 'PUT',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
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
        console.log('Campaign updated:', data);

        // Close the drawer
        const drawer = bootstrap.Offcanvas.getInstance(document.getElementById('editCampaignDrawer'));
        if (drawer) {
            drawer.hide();
        }

        const message = data.message || 'Campaign updated successfully!';

        // Show success toast notification
        showSuccessToast(message);

        // Show success alert message
        showSuccessMessage(message);

        // Reload page after a short delay to show the message
        setTimeout(() => {
            window.location.reload();
        }, 1500);
    })
    .catch(error => {
        console.error('Error:', error);
        submitBtn.disabled = false;
        submitBtn.innerHTML = originalBtnText;

        let errorMessage = 'Failed to update campaign. Please try again.';
        if (error.message) {
            errorMessage = error.message;
        } else if (error.errors) {
            errorMessage = Object.values(error.errors).flat().join('\n');
        }
        alert(errorMessage);
    });
});

// Function to show success message on the page
function showSuccessMessage(message) {
    const container = document.getElementById('dynamicSuccessMessage');
    if (container) {
        container.innerHTML = `
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="ti ti-check-circle me-2"></i>${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        `;
        container.style.display = 'block';

        // Scroll to the top to show the message
        window.scrollTo({ top: 0, behavior: 'smooth' });
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
