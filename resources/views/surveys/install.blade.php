@extends('layouts.inspinia')

@section('title', 'Install Survey - ' . $survey->title)

@push('styles')
<style>
    .code-block {
        background: #1e293b;
        border-radius: 8px;
        padding: 20px;
        position: relative;
    }
    .code-block pre {
        color: #e2e8f0;
        margin: 0;
        font-size: 0.85rem;
        white-space: pre-wrap;
        word-wrap: break-word;
    }
    .code-block .copy-btn {
        position: absolute;
        top: 10px;
        right: 10px;
        background: rgba(255,255,255,0.1);
        border: 1px solid rgba(255,255,255,0.2);
        color: #fff;
        padding: 6px 12px;
        border-radius: 4px;
        font-size: 0.8rem;
        cursor: pointer;
        transition: all 0.2s;
    }
    .code-block .copy-btn:hover {
        background: rgba(255,255,255,0.2);
    }
    .code-block .copy-btn.copied {
        background: #10b981;
        border-color: #10b981;
    }
    .install-step {
        display: flex;
        gap: 16px;
        margin-bottom: 24px;
    }
    .step-number {
        width: 32px;
        height: 32px;
        background: #6366f1;
        color: #fff;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 600;
        flex-shrink: 0;
    }
    .step-content {
        flex: 1;
    }
    .step-content h6 {
        margin-bottom: 8px;
        color: #1f2937;
    }
    .step-content p {
        color: #6b7280;
        margin-bottom: 12px;
    }
</style>
@endpush

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-flex justify-content-between align-items-center">
            <div>
                <h4 class="page-title">Install Survey</h4>
                <p class="text-muted fs-14 mb-0">Add the survey widget to your website</p>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('surveys.show', $survey) }}" class="btn btn-secondary">
                    <i class="ti ti-arrow-left me-1"></i>Back to Survey
                </a>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="ti ti-code me-2"></i>Installation Instructions
                </h5>
            </div>
            <div class="card-body">
                <div class="install-step">
                    <div class="step-number">1</div>
                    <div class="step-content">
                        <h6>Copy the embed code</h6>
                        <p>Add this script tag to your website, preferably just before the closing <code>&lt;/body&gt;</code> tag.</p>
                        <div class="code-block">
                            <button class="copy-btn" onclick="copyCode('embedCode')">
                                <i class="ti ti-copy me-1"></i>Copy
                            </button>
                            <pre id="embedCode">&lt;script&gt;
(function() {
    var script = document.createElement('script');
    script.src = '{{ url('/js/survey-widget.js') }}';
    script.async = true;
    script.dataset.surveyId = '{{ $survey->id }}';
    script.dataset.apiEndpoint = '{{ url('/api/survey/' . $survey->id . '/respond') }}';
    document.body.appendChild(script);
})();
&lt;/script&gt;</pre>
                        </div>
                    </div>
                </div>

                <div class="install-step">
                    <div class="step-number">2</div>
                    <div class="step-content">
                        <h6>Trigger the survey (optional)</h6>
                        <p>You can also manually trigger the survey using JavaScript:</p>
                        <div class="code-block">
                            <button class="copy-btn" onclick="copyCode('triggerCode')">
                                <i class="ti ti-copy me-1"></i>Copy
                            </button>
                            <pre id="triggerCode">// Show the survey
window.WhoopboardSurvey.show();

// Hide the survey
window.WhoopboardSurvey.hide();

// Identify the user (optional)
window.WhoopboardSurvey.identify({
    email: 'user@example.com',
    userId: 'user_123'
});</pre>
                        </div>
                    </div>
                </div>

                <div class="install-step">
                    <div class="step-number">3</div>
                    <div class="step-content">
                        <h6>API Integration (alternative)</h6>
                        <p>If you prefer to build your own UI, you can submit responses directly to the API:</p>
                        <div class="code-block">
                            <button class="copy-btn" onclick="copyCode('apiCode')">
                                <i class="ti ti-copy me-1"></i>Copy
                            </button>
                            <pre id="apiCode">// POST {{ url('/api/survey/' . $survey->id . '/respond') }}
// Content-Type: application/json

{
    "score": 9,
    "feedback": "Great product!",
    "respondent_id": "user_123",
    "respondent_email": "user@example.com",
    "metadata": {
        "page_url": "https://example.com/dashboard",
        "browser": "Chrome"
    }
}</pre>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="ti ti-info-circle me-2"></i>Survey Details
                </h5>
            </div>
            <div class="card-body">
                <table class="table table-borderless mb-0">
                    <tr>
                        <td class="text-muted">Title</td>
                        <td class="fw-medium">{{ $survey->title }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted">Type</td>
                        <td>
                            <span class="badge bg-primary">{{ strtoupper($survey->type) }}</span>
                        </td>
                    </tr>
                    <tr>
                        <td class="text-muted">Status</td>
                        <td>
                            @if($survey->status === 'published')
                                <span class="badge bg-success">Active</span>
                            @elseif($survey->status === 'paused')
                                <span class="badge bg-warning">Paused</span>
                            @else
                                <span class="badge bg-secondary">Draft</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td class="text-muted">Survey ID</td>
                        <td><code>{{ $survey->id }}</code></td>
                    </tr>
                </table>
            </div>
        </div>

        <div class="card mt-3">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="ti ti-bulb me-2"></i>Tips
                </h5>
            </div>
            <div class="card-body">
                <ul class="list-unstyled mb-0">
                    <li class="d-flex gap-2 mb-3">
                        <i class="ti ti-check text-success mt-1"></i>
                        <span>Place the script before <code>&lt;/body&gt;</code> for best performance</span>
                    </li>
                    <li class="d-flex gap-2 mb-3">
                        <i class="ti ti-check text-success mt-1"></i>
                        <span>Use <code>identify()</code> to track which users respond</span>
                    </li>
                    <li class="d-flex gap-2 mb-3">
                        <i class="ti ti-check text-success mt-1"></i>
                        <span>Add metadata to understand response context</span>
                    </li>
                    <li class="d-flex gap-2 mb-0">
                        <i class="ti ti-check text-success mt-1"></i>
                        <span>Make sure the survey is <strong>Active</strong> to receive responses</span>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function copyCode(elementId) {
    const codeElement = document.getElementById(elementId);
    const text = codeElement.textContent;

    navigator.clipboard.writeText(text).then(function() {
        const btn = codeElement.parentElement.querySelector('.copy-btn');
        const originalText = btn.innerHTML;
        btn.innerHTML = '<i class="ti ti-check me-1"></i>Copied!';
        btn.classList.add('copied');

        setTimeout(function() {
            btn.innerHTML = originalText;
            btn.classList.remove('copied');
        }, 2000);
    });
}
</script>
@endpush
