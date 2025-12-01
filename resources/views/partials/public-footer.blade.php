<!-- Public Footer -->
@php
    $footerName = config('app.name', 'WhoopBoard');
    if (isset($board) && isset($board->name)) {
        $footerName = $board->name;
    } elseif (isset($settings) && isset($settings->product_name)) {
        $footerName = $settings->product_name;
    }

    // Get active published survey for this team (if settings available)
    $activeSurvey = null;
    if (isset($settings) && isset($settings->team_id)) {
        $activeSurvey = \App\Models\Survey::where('team_id', $settings->team_id)
            ->where('status', 'published')
            ->first();
    }
@endphp
<footer class="public-footer" style="border-top: 1px solid var(--border-color, #e5e7eb); margin-top: 4rem; padding: 2rem 0; background: #f9fafb;">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-6 text-center text-md-start mb-3 mb-md-0">
                <p class="mb-0" style="color: var(--text-secondary, #6b7280); font-size: 0.875rem;">
                    © {{ date('Y') }} <strong>{{ $footerName }}</strong>. All rights reserved.
                </p>
            </div>
            <div class="col-md-6 text-center text-md-end">
                <p class="mb-0" style="color: var(--text-secondary, #6b7280); font-size: 0.875rem;">
                    Powered by <a href="https://insighthq.com" target="_blank" rel="noopener" style="color: var(--primary-color, #5865F2); text-decoration: none; font-weight: 600;">InsightHQ</a>
                </p>
            </div>
        </div>
    </div>
</footer>

{{-- Survey Widget --}}
@if($activeSurvey)
<script src="{{ url('/js/survey-widget.js') }}"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        if (window.WhoopSurvey) {
            WhoopSurvey.init('{{ $activeSurvey->id }}', '{{ url('/') }}');
        }
    });
</script>
@endif
