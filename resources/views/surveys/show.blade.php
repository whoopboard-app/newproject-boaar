@extends('layouts.inspinia')

@section('title', $survey->title)

@push('styles')
<style>
    /* NPS Score Display */
    .nps-score-display {
        text-align: center;
        padding: 30px;
    }
    .nps-score-value {
        font-size: 4rem;
        font-weight: 700;
        line-height: 1;
    }
    .nps-score-label {
        font-size: 0.9rem;
        color: #6b7280;
        margin-top: 8px;
    }
    .nps-positive { color: #10b981; }
    .nps-neutral { color: #f59e0b; }
    .nps-negative { color: #ef4444; }

    /* Stats Cards */
    .stat-card {
        background: #fff;
        border-radius: 12px;
        border: 1px solid #e5e7eb;
        padding: 20px;
        text-align: center;
    }
    .stat-card .stat-value {
        font-size: 2rem;
        font-weight: 700;
        color: #1f2937;
    }
    .stat-card .stat-label {
        font-size: 0.85rem;
        color: #6b7280;
        margin-top: 4px;
    }

    /* Distribution Bar */
    .distribution-bar {
        display: flex;
        height: 24px;
        border-radius: 12px;
        overflow: hidden;
        background: #f3f4f6;
    }
    .distribution-segment {
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.75rem;
        font-weight: 600;
        color: #fff;
        transition: width 0.3s ease;
    }
    .segment-promoter { background: #10b981; }
    .segment-passive { background: #f59e0b; }
    .segment-detractor { background: #ef4444; }

    /* Legend */
    .legend-item {
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 0.85rem;
    }
    .legend-dot {
        width: 12px;
        height: 12px;
        border-radius: 50%;
    }

    /* Response List */
    .response-item {
        background: #fff;
        border: 1px solid #e5e7eb;
        border-radius: 8px;
        padding: 16px;
        margin-bottom: 12px;
    }
    .response-item:hover {
        border-color: #d1d5db;
        box-shadow: 0 2px 4px rgba(0,0,0,0.05);
    }
    .response-score {
        width: 48px;
        height: 48px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.25rem;
        font-weight: 700;
        color: #fff;
    }
    .score-promoter { background: #10b981; }
    .score-passive { background: #f59e0b; }
    .score-detractor { background: #ef4444; }

    /* Preview Widget */
    .preview-container {
        background: #f8fafc;
        border-radius: 12px;
        padding: 24px;
    }
    .preview-widget {
        background: #ffffff;
        border-radius: 12px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        padding: 24px;
        max-width: 400px;
        margin: 0 auto;
    }
    .preview-widget .survey-question {
        font-size: 1.1rem;
        font-weight: 600;
        color: #1f2937;
        margin-bottom: 20px;
        line-height: 1.4;
    }
    .nps-scale {
        display: flex;
        gap: 4px;
        justify-content: space-between;
        margin-bottom: 8px;
    }
    .nps-scale .score-btn {
        width: 32px;
        height: 32px;
        border: 1px solid #e5e7eb;
        border-radius: 6px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.85rem;
        font-weight: 500;
        color: #6b7280;
        background: #fff;
    }
    .scale-labels {
        display: flex;
        justify-content: space-between;
        font-size: 0.75rem;
        color: #9ca3af;
    }

    /* Empty State */
    .empty-state {
        text-align: center;
        padding: 60px 20px;
    }
    .empty-state i {
        font-size: 4rem;
        color: #d1d5db;
        margin-bottom: 16px;
    }
    .empty-state h5 {
        color: #374151;
        margin-bottom: 8px;
    }
    .empty-state p {
        color: #6b7280;
        margin-bottom: 20px;
    }

    /* Tab Styling */
    .nav-tabs .nav-link {
        color: #6b7280;
        border: none;
        border-bottom: 2px solid transparent;
        padding: 12px 20px;
        font-weight: 500;
    }
    .nav-tabs .nav-link:hover {
        color: #374151;
        border-color: transparent;
    }
    .nav-tabs .nav-link.active {
        color: #6366f1;
        border-bottom-color: #6366f1;
        background: transparent;
    }

    /* Survey Type Badge */
    .survey-type-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 6px 12px;
        border-radius: 6px;
        font-weight: 600;
        font-size: 0.85rem;
    }
    .type-nps-bg { background-color: #dbeafe; color: #1e40af; }
    .type-csat-bg { background-color: #dcfce7; color: #166534; }
    .type-ces-bg { background-color: #fef3c7; color: #92400e; }
    .type-pmf-bg { background-color: #f3e8ff; color: #7c3aed; }
    .type-custom-bg { background-color: #f1f5f9; color: #475569; }

    /* Status Badge */
    .status-active { background-color: #dcfce7; color: #166534; }
    .status-inactive { background-color: #fef3c7; color: #92400e; }
</style>
@endpush

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-flex justify-content-between align-items-center">
            <div>
                <div class="d-flex align-items-center gap-3 mb-2">
                    <h4 class="page-title mb-0">{{ $survey->title }}</h4>
                    <span class="badge {{ $survey->status === 'published' ? 'status-active' : 'status-inactive' }}">
                        <i class="ti ti-{{ $survey->status === 'published' ? 'circle-check' : 'circle-pause' }} me-1"></i>
                        {{ $survey->status === 'published' ? 'Active' : ucfirst($survey->status) }}
                    </span>
                </div>
                <div class="d-flex align-items-center gap-2">
                    @php
                        $surveyTypes = \App\Models\Survey::getTypes();
                        $typeInfo = $surveyTypes[$survey->type] ?? ['name' => ucfirst($survey->type), 'icon' => 'clipboard-list'];
                    @endphp
                    <span class="survey-type-badge type-{{ $survey->type }}-bg">
                        <i class="ti ti-{{ $typeInfo['icon'] }}"></i>
                        {{ $typeInfo['name'] }}
                    </span>
                    <span class="text-muted">
                        <i class="ti ti-calendar me-1"></i>Created {{ $survey->created_at->format('M d, Y') }}
                    </span>
                </div>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('surveys.install', $survey) }}" class="btn btn-outline-primary">
                    <i class="ti ti-code me-1"></i>Install
                </a>
                <a href="{{ route('surveys.edit', $survey) }}" class="btn btn-outline-secondary">
                    <i class="ti ti-edit me-1"></i>Edit
                </a>
                <form action="{{ route('surveys.toggle-active', $survey) }}" method="POST" class="d-inline">
                    @csrf
                    <button type="submit" class="btn {{ $survey->status === 'published' ? 'btn-outline-warning' : 'btn-success' }}">
                        <i class="ti ti-{{ $survey->status === 'published' ? 'player-pause' : 'player-play' }} me-1"></i>
                        {{ $survey->status === 'published' ? 'Pause' : 'Activate' }}
                    </button>
                </form>
                <a href="{{ route('surveys.index') }}" class="btn btn-secondary">
                    <i class="ti ti-arrow-left me-1"></i>Back
                </a>
            </div>
        </div>
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="ti ti-check me-2"></i>{{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

<!-- Tabs Navigation -->
<ul class="nav nav-tabs mb-4" id="surveyTabs" role="tablist">
    <li class="nav-item" role="presentation">
        <button class="nav-link active" id="overview-tab" data-bs-toggle="tab" data-bs-target="#overview" type="button" role="tab">
            <i class="ti ti-chart-bar me-1"></i>Overview
        </button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link" id="responses-tab" data-bs-toggle="tab" data-bs-target="#responses" type="button" role="tab">
            <i class="ti ti-message-dots me-1"></i>Responses
            <span class="badge bg-secondary ms-1">{{ $statistics['total_responses'] }}</span>
        </button>
    </li>
</ul>

<!-- Tab Content -->
<div class="tab-content" id="surveyTabsContent">
    <!-- Overview Tab -->
    <div class="tab-pane fade show active" id="overview" role="tabpanel">
        <div class="row">
            <!-- Left Column: Stats & Analytics -->
            <div class="col-lg-7">
                @if($survey->type === 'nps')
                    <!-- NPS Score Card -->
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="card-title mb-0">
                                <i class="ti ti-chart-donut me-2"></i>NPS Score
                            </h5>
                        </div>
                        <div class="card-body">
                            @if($npsScore !== null)
                                <div class="nps-score-display">
                                    <div class="nps-score-value {{ $npsScore >= 50 ? 'nps-positive' : ($npsScore >= 0 ? 'nps-neutral' : 'nps-negative') }}">
                                        {{ $npsScore > 0 ? '+' : '' }}{{ $npsScore }}
                                    </div>
                                    <div class="nps-score-label">Net Promoter Score</div>
                                </div>

                                @php
                                    $promoters = $survey->responses()->promoters()->count();
                                    $passives = $survey->responses()->passives()->count();
                                    $detractors = $survey->responses()->detractors()->count();
                                    $total = $promoters + $passives + $detractors;
                                    $promoterPct = $total > 0 ? round(($promoters / $total) * 100) : 0;
                                    $passivePct = $total > 0 ? round(($passives / $total) * 100) : 0;
                                    $detractorPct = $total > 0 ? round(($detractors / $total) * 100) : 0;
                                @endphp

                                <div class="distribution-bar mb-3">
                                    @if($promoterPct > 0)
                                        <div class="distribution-segment segment-promoter" style="width: {{ $promoterPct }}%">
                                            {{ $promoterPct }}%
                                        </div>
                                    @endif
                                    @if($passivePct > 0)
                                        <div class="distribution-segment segment-passive" style="width: {{ $passivePct }}%">
                                            {{ $passivePct }}%
                                        </div>
                                    @endif
                                    @if($detractorPct > 0)
                                        <div class="distribution-segment segment-detractor" style="width: {{ $detractorPct }}%">
                                            {{ $detractorPct }}%
                                        </div>
                                    @endif
                                </div>

                                <div class="d-flex justify-content-center gap-4">
                                    <div class="legend-item">
                                        <div class="legend-dot" style="background: #10b981;"></div>
                                        <span>Promoters (9-10): {{ $promoters }}</span>
                                    </div>
                                    <div class="legend-item">
                                        <div class="legend-dot" style="background: #f59e0b;"></div>
                                        <span>Passives (7-8): {{ $passives }}</span>
                                    </div>
                                    <div class="legend-item">
                                        <div class="legend-dot" style="background: #ef4444;"></div>
                                        <span>Detractors (0-6): {{ $detractors }}</span>
                                    </div>
                                </div>
                            @else
                                <div class="empty-state">
                                    <i class="ti ti-chart-bar"></i>
                                    <h5>No responses yet</h5>
                                    <p>Your NPS score will appear here once you receive responses.</p>
                                </div>
                            @endif
                        </div>
                    </div>
                @endif

                <!-- Statistics Cards -->
                <div class="row mb-4">
                    <div class="col-md-4">
                        <div class="stat-card">
                            <div class="stat-value">{{ $statistics['total_responses'] }}</div>
                            <div class="stat-label">Total Responses</div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="stat-card">
                            <div class="stat-value">{{ $statistics['average_score'] ?? '-' }}</div>
                            <div class="stat-label">Average Score</div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="stat-card">
                            <div class="stat-value">{{ $survey->responses()->whereNotNull('feedback')->where('feedback', '!=', '')->count() }}</div>
                            <div class="stat-label">With Feedback</div>
                        </div>
                    </div>
                </div>

                <!-- Score Distribution -->
                @if($survey->type === 'nps' && !empty($statistics['score_distribution']))
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">
                                <i class="ti ti-chart-histogram me-2"></i>Score Distribution
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                @for($i = 0; $i <= 10; $i++)
                                    @php
                                        $count = $statistics['score_distribution'][$i] ?? 0;
                                        $maxCount = max(array_values($statistics['score_distribution']));
                                        $height = $maxCount > 0 ? ($count / $maxCount) * 100 : 0;
                                        $color = $i >= 9 ? '#10b981' : ($i >= 7 ? '#f59e0b' : '#ef4444');
                                    @endphp
                                    <div class="col text-center">
                                        <div style="height: 80px; display: flex; align-items: flex-end; justify-content: center;">
                                            <div style="width: 24px; height: {{ $height }}%; min-height: {{ $count > 0 ? '4px' : '0' }}; background: {{ $color }}; border-radius: 4px 4px 0 0;"></div>
                                        </div>
                                        <div class="mt-2">
                                            <strong>{{ $i }}</strong>
                                        </div>
                                        <small class="text-muted">{{ $count }}</small>
                                    </div>
                                @endfor
                            </div>
                        </div>
                    </div>
                @endif
            </div>

            <!-- Right Column: Survey Preview -->
            <div class="col-lg-5">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="ti ti-eye me-2"></i>Survey Preview
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="preview-container">
                            <div class="preview-widget">
                                <div class="survey-question">
                                    {{ $survey->general_settings['question'] ?? 'Survey question' }}
                                </div>

                                @if($survey->type === 'nps')
                                    <div class="nps-scale">
                                        @for($i = 0; $i <= 10; $i++)
                                            <div class="score-btn">{{ $i }}</div>
                                        @endfor
                                    </div>
                                    <div class="scale-labels">
                                        <span>{{ $survey->label_least_likely ?? 'Not at all likely' }}</span>
                                        <span>{{ $survey->label_most_likely ?? 'Extremely likely' }}</span>
                                    </div>
                                @elseif($survey->type === 'csat')
                                    <div class="d-flex justify-content-center gap-2 mb-2">
                                        @for($i = 1; $i <= 5; $i++)
                                            <div class="score-btn" style="width: 40px; height: 40px;">{{ $i }}</div>
                                        @endfor
                                    </div>
                                    <div class="scale-labels">
                                        <span>{{ $survey->label_least_likely ?? 'Very dissatisfied' }}</span>
                                        <span>{{ $survey->label_most_likely ?? 'Very satisfied' }}</span>
                                    </div>
                                @elseif($survey->type === 'open_feedback')
                                    <textarea class="form-control" rows="3" placeholder="Share your feedback..." disabled></textarea>
                                @elseif($survey->type === 'quick_poll')
                                    <div class="d-flex flex-column gap-2">
                                        @foreach($survey->general_settings['options'] ?? ['Option 1', 'Option 2', 'Option 3'] as $option)
                                            <div class="btn btn-outline-secondary text-start">{{ $option }}</div>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="card mt-4">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="ti ti-bolt me-2"></i>Quick Actions
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="d-grid gap-2">
                            <a href="{{ route('surveys.install', $survey) }}" class="btn btn-outline-primary">
                                <i class="ti ti-code me-2"></i>Get Embed Code
                            </a>
                            <a href="{{ route('surveys.edit', $survey) }}" class="btn btn-outline-secondary">
                                <i class="ti ti-settings me-2"></i>Survey Settings
                            </a>
                            <button type="button" class="btn btn-outline-danger" data-bs-toggle="modal" data-bs-target="#deleteModal">
                                <i class="ti ti-trash me-2"></i>Delete Survey
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Responses Tab -->
    <div class="tab-pane fade" id="responses" role="tabpanel">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">
                    <i class="ti ti-list me-2"></i>All Responses
                </h5>
                <div class="d-flex gap-2">
                    <select class="form-select form-select-sm" id="filterCategory" style="width: auto;">
                        <option value="">All Categories</option>
                        @if($survey->type === 'nps')
                            <option value="promoter">Promoters (9-10)</option>
                            <option value="passive">Passives (7-8)</option>
                            <option value="detractor">Detractors (0-6)</option>
                        @endif
                    </select>
                    <select class="form-select form-select-sm" id="filterFeedback" style="width: auto;">
                        <option value="">All Responses</option>
                        <option value="with_feedback">With Feedback</option>
                        <option value="without_feedback">Without Feedback</option>
                    </select>
                </div>
            </div>
            <div class="card-body">
                @if($survey->responses->isEmpty())
                    <div class="empty-state">
                        <i class="ti ti-inbox"></i>
                        <h5>No responses yet</h5>
                        <p>Responses will appear here once users start completing your survey.</p>
                        <a href="{{ route('surveys.install', $survey) }}" class="btn btn-primary">
                            <i class="ti ti-code me-2"></i>Get Install Code
                        </a>
                    </div>
                @else
                    <div id="responsesList">
                        @foreach($survey->responses->sortByDesc('created_at') as $response)
                            @php
                                $category = $response->nps_category;
                                $scoreClass = $category === 'promoter' ? 'score-promoter' : ($category === 'passive' ? 'score-passive' : 'score-detractor');
                            @endphp
                            <div class="response-item" data-category="{{ $category }}" data-has-feedback="{{ $response->feedback ? 'yes' : 'no' }}">
                                <div class="d-flex gap-3">
                                    <div class="response-score {{ $scoreClass }}">
                                        {{ $response->score }}
                                    </div>
                                    <div class="flex-grow-1">
                                        <div class="d-flex justify-content-between align-items-start mb-2">
                                            <div>
                                                @if($response->respondent_email)
                                                    <strong>{{ $response->respondent_email }}</strong>
                                                @elseif($response->respondent_id)
                                                    <strong>{{ $response->respondent_id }}</strong>
                                                @else
                                                    <span class="text-muted">Anonymous</span>
                                                @endif
                                                <span class="badge bg-{{ $category === 'promoter' ? 'success' : ($category === 'passive' ? 'warning' : 'danger') }} ms-2">
                                                    {{ ucfirst($category) }}
                                                </span>
                                            </div>
                                            <small class="text-muted">
                                                <i class="ti ti-clock me-1"></i>{{ $response->created_at->diffForHumans() }}
                                            </small>
                                        </div>
                                        @if($response->feedback)
                                            <p class="mb-0 text-muted">
                                                <i class="ti ti-quote me-1"></i>{{ $response->feedback }}
                                            </p>
                                        @else
                                            <p class="mb-0 text-muted fst-italic">No feedback provided</p>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Delete Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Delete Survey</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete <strong>{{ $survey->title }}</strong>?</p>
                <p class="text-danger mb-0">
                    <i class="ti ti-alert-triangle me-1"></i>
                    This will also delete all {{ $statistics['total_responses'] }} responses. This action cannot be undone.
                </p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <form action="{{ route('surveys.destroy', $survey) }}" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">
                        <i class="ti ti-trash me-1"></i>Delete Survey
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Filter responses
    const filterCategory = document.getElementById('filterCategory');
    const filterFeedback = document.getElementById('filterFeedback');
    const responsesList = document.getElementById('responsesList');

    function filterResponses() {
        if (!responsesList) return;

        const categoryValue = filterCategory.value;
        const feedbackValue = filterFeedback.value;
        const items = responsesList.querySelectorAll('.response-item');

        items.forEach(item => {
            let show = true;

            if (categoryValue && item.dataset.category !== categoryValue) {
                show = false;
            }

            if (feedbackValue === 'with_feedback' && item.dataset.hasFeedback !== 'yes') {
                show = false;
            }

            if (feedbackValue === 'without_feedback' && item.dataset.hasFeedback !== 'no') {
                show = false;
            }

            item.style.display = show ? 'block' : 'none';
        });
    }

    if (filterCategory) {
        filterCategory.addEventListener('change', filterResponses);
    }

    if (filterFeedback) {
        filterFeedback.addEventListener('change', filterResponses);
    }

    // Handle URL hash for direct tab linking
    const hash = window.location.hash;
    if (hash === '#responses') {
        const responsesTab = document.getElementById('responses-tab');
        if (responsesTab) {
            const tab = new bootstrap.Tab(responsesTab);
            tab.show();
        }
    }
});
</script>
@endpush
