@extends('layouts.inspinia')

@section('title', $userSegment->name)

@push('styles')
<style>
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

    .tag-badge {
        display: inline-block;
        padding: 4px 12px;
        margin: 4px;
        font-size: 0.875rem;
        border-radius: 4px;
        background-color: #e9ecef;
        color: #495057;
    }

    .section-header {
        background-color: #f8f9fa;
        padding: 0.75rem 1rem;
        margin: 1.5rem 0 1rem 0;
        border-left: 3px solid #007bff;
        font-weight: 600;
        color: #495057;
    }
</style>
@endpush

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-flex justify-content-between align-items-center">
            <div>
                <h4 class="page-title">{{ $userSegment->name }}</h4>
                <p class="text-muted fs-14 mb-0">User Segment Details</p>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('user-segment.index') }}" class="btn btn-secondary">
                    <i class="ti ti-arrow-left me-1"></i>Back to Segments
                </a>
                <a href="{{ route('user-segment.edit', $userSegment) }}" class="btn btn-primary">
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
                <!-- Basic Information -->
                <h5 class="mb-3">{{ $userSegment->name }}</h5>

                @if($userSegment->description)
                    <div class="alert alert-info">
                        <i class="ti ti-info-circle me-2"></i>{{ $userSegment->description }}
                    </div>
                @endif

                <!-- Revenue Ranges -->
                @if($userSegment->revenue_ranges && count($userSegment->revenue_ranges) > 0)
                    <div class="mb-4">
                        <h6 class="text-muted mb-2">Revenue Ranges</h6>
                        <div>
                            @foreach($userSegment->revenue_ranges as $range)
                                <span class="tag-badge">{{ $range }}</span>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- Demographic Attributes -->
                @if(
                    ($userSegment->locations && count($userSegment->locations) > 0) ||
                    ($userSegment->age_ranges && count($userSegment->age_ranges) > 0) ||
                    ($userSegment->genders && count($userSegment->genders) > 0) ||
                    ($userSegment->languages && count($userSegment->languages) > 0)
                )
                    <div class="section-header">
                        <i class="ti ti-users me-2"></i>Demographic Attributes
                    </div>

                    @if($userSegment->locations && count($userSegment->locations) > 0)
                        <div class="mb-3">
                            <h6 class="text-muted mb-2">Location / Region</h6>
                            <div>
                                @foreach($userSegment->locations as $location)
                                    <span class="tag-badge">{{ $location }}</span>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    @if($userSegment->age_ranges && count($userSegment->age_ranges) > 0)
                        <div class="mb-3">
                            <h6 class="text-muted mb-2">Age Range</h6>
                            <div>
                                @foreach($userSegment->age_ranges as $age)
                                    <span class="tag-badge">{{ $age }}</span>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    @if($userSegment->genders && count($userSegment->genders) > 0)
                        <div class="mb-3">
                            <h6 class="text-muted mb-2">Gender</h6>
                            <div>
                                @foreach($userSegment->genders as $gender)
                                    <span class="tag-badge">{{ $gender }}</span>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    @if($userSegment->languages && count($userSegment->languages) > 0)
                        <div class="mb-3">
                            <h6 class="text-muted mb-2">Language / Locale</h6>
                            <div>
                                @foreach($userSegment->languages as $language)
                                    <span class="tag-badge">{{ $language }}</span>
                                @endforeach
                            </div>
                        </div>
                    @endif
                @endif

                <!-- Behavioral & Account Attributes -->
                @if(
                    ($userSegment->user_types && count($userSegment->user_types) > 0) ||
                    ($userSegment->plan_types && count($userSegment->plan_types) > 0) ||
                    ($userSegment->engagement_levels && count($userSegment->engagement_levels) > 0) ||
                    ($userSegment->usage_frequencies && count($userSegment->usage_frequencies) > 0)
                )
                    <div class="section-header">
                        <i class="ti ti-chart-dots me-2"></i>Behavioral & Account Attributes
                    </div>

                    @if($userSegment->user_types && count($userSegment->user_types) > 0)
                        <div class="mb-3">
                            <h6 class="text-muted mb-2">User Type / Role</h6>
                            <div>
                                @foreach($userSegment->user_types as $type)
                                    <span class="tag-badge">{{ $type }}</span>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    @if($userSegment->plan_types && count($userSegment->plan_types) > 0)
                        <div class="mb-3">
                            <h6 class="text-muted mb-2">Plan Type / Subscription Tier</h6>
                            <div>
                                @foreach($userSegment->plan_types as $plan)
                                    <span class="tag-badge">{{ $plan }}</span>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    @if($userSegment->engagement_levels && count($userSegment->engagement_levels) > 0)
                        <div class="mb-3">
                            <h6 class="text-muted mb-2">Engagement Level</h6>
                            <div>
                                @foreach($userSegment->engagement_levels as $level)
                                    <span class="tag-badge">{{ $level }}</span>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    @if($userSegment->usage_frequencies && count($userSegment->usage_frequencies) > 0)
                        <div class="mb-3">
                            <h6 class="text-muted mb-2">Usage Frequency</h6>
                            <div>
                                @foreach($userSegment->usage_frequencies as $frequency)
                                    <span class="tag-badge">{{ $frequency }}</span>
                                @endforeach
                            </div>
                        </div>
                    @endif
                @endif
            </div>
        </div>
    </div>

    <!-- Sidebar -->
    <div class="col-lg-4">
        <!-- Segment Information -->
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="ti ti-info-circle me-2"></i>Segment Information
                </h5>
            </div>
            <div class="card-body">
                <!-- Status -->
                <div class="info-box">
                    <h6>Status</h6>
                    <p>
                        @if($userSegment->status == 'active')
                            <span class="badge bg-success">Active</span>
                        @else
                            <span class="badge bg-secondary">Inactive</span>
                        @endif
                    </p>
                </div>

                <!-- Created At -->
                <div class="info-box">
                    <h6>Created At</h6>
                    <p>{{ $userSegment->created_at->format('M d, Y h:i A') }}</p>
                </div>

                <!-- Last Updated -->
                <div class="info-box">
                    <h6>Last Updated</h6>
                    <p>{{ $userSegment->updated_at->format('M d, Y h:i A') }}</p>
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
                    <a href="{{ route('user-segment.edit', $userSegment) }}" class="btn btn-primary">
                        <i class="ti ti-edit me-2"></i>Edit Segment
                    </a>
                    <form action="{{ route('user-segment.destroy', $userSegment) }}" method="POST" id="deleteForm">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger w-100">
                            <i class="ti ti-trash me-2"></i>Delete Segment
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
            if (confirm('Are you sure you want to delete this user segment? This action cannot be undone.')) {
                this.submit();
            }
        });
    }
});
</script>
@endpush
