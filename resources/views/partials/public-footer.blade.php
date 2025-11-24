<!-- Public Footer -->
<footer class="public-footer" style="border-top: 1px solid var(--border-color, #e5e7eb); margin-top: 4rem; padding: 2rem 0; background: #f9fafb;">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-6 text-center text-md-start mb-3 mb-md-0">
                <p class="mb-0" style="color: var(--text-secondary, #6b7280); font-size: 0.875rem;">
                    © {{ date('Y') }} <strong>{{ $board->name ?? config('app.name', 'WhoopBoard') }}</strong>. All rights reserved.
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
