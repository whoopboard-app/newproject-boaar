<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $feedback->idea }} - {{ $settings->product_name ?? 'Feedback' }}</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Tabler Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/tabler-icons.min.css">

    <style>
        :root {
            --primary-color: #5865F2;
            --border-color: #e5e7eb;
            --text-primary: #1f2937;
            --text-secondary: #6b7280;
            --bg-hover: #f9fafb;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            background-color: #ffffff;
            color: var(--text-primary);
        }

        /* Header */
        .public-header {
            border-bottom: 1px solid var(--border-color);
            background: white;
            padding: 1rem 0;
        }

        .logo-section {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .logo-img {
            width: 192px;
            height: 75px;
            object-fit: contain;
            border-radius: 8px;
        }

        .product-name {
            font-size: 1.25rem;
            font-weight: 600;
            color: var(--text-primary);
            margin: 0;
        }

        /* Navigation */
        .public-nav {
            display: flex;
            gap: 0.5rem;
            margin-top: 1rem;
        }

        .nav-tab {
            padding: 0.5rem 1rem;
            border-radius: 6px;
            text-decoration: none;
            color: var(--text-secondary);
            font-weight: 500;
            transition: all 0.2s;
        }

        .nav-tab:hover {
            background: var(--bg-hover);
            color: var(--text-primary);
        }

        .nav-tab.active {
            background: var(--primary-color);
            color: white;
        }

        /* Feedback Detail */
        .feedback-container {
            max-width: 900px;
            margin: 0 auto;
            padding: 2rem 1rem;
        }

        .back-link {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            color: var(--text-secondary);
            text-decoration: none;
            font-size: 0.9375rem;
            margin-bottom: 2rem;
            transition: color 0.2s;
        }

        .back-link:hover {
            color: var(--primary-color);
        }

        /* Main Content */
        .feedback-main {
            display: grid;
            grid-template-columns: 1fr 280px;
            gap: 2rem;
        }

        @media (max-width: 768px) {
            .feedback-main {
                grid-template-columns: 1fr;
            }
        }

        /* Feedback Card */
        .feedback-detail-card {
            background: white;
            border: 1px solid var(--border-color);
            border-radius: 12px;
            padding: 2rem;
        }

        .feedback-header {
            display: flex;
            gap: 1.5rem;
            margin-bottom: 1.5rem;
        }

        .vote-box {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 0.25rem;
            background: #f9fafb;
            border: 1px solid var(--border-color);
            border-radius: 8px;
            padding: 0.75rem 1rem;
            min-width: 70px;
            cursor: pointer;
            transition: all 0.2s;
        }

        .vote-box:hover {
            background: var(--primary-color);
            border-color: var(--primary-color);
            color: white;
        }

        .vote-box.voted {
            background: var(--primary-color);
            border-color: var(--primary-color);
            color: white;
        }

        .vote-box.processing {
            opacity: 0.7;
            pointer-events: none;
        }

        .vote-icon {
            font-size: 1.5rem;
        }

        .vote-count {
            font-size: 1.125rem;
            font-weight: 700;
        }

        .vote-label {
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .feedback-title {
            font-size: 1.75rem;
            font-weight: 700;
            color: var(--text-primary);
            margin-bottom: 1rem;
            line-height: 1.3;
        }

        .feedback-meta {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            gap: 0.75rem;
            margin-bottom: 1.5rem;
        }

        .status-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.375rem;
            padding: 0.375rem 0.875rem;
            border-radius: 20px;
            font-size: 0.8125rem;
            font-weight: 600;
        }

        .category-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.375rem;
            padding: 0.375rem 0.875rem;
            border-radius: 20px;
            background: #f3f4f6;
            color: var(--text-secondary);
            font-size: 0.8125rem;
            font-weight: 500;
        }

        .date-info {
            color: var(--text-secondary);
            font-size: 0.875rem;
        }

        .feedback-description {
            color: var(--text-primary);
            font-size: 1rem;
            line-height: 1.7;
            margin-bottom: 1.5rem;
        }

        .feedback-image {
            margin-bottom: 1.5rem;
        }

        .feedback-image img {
            max-width: 100%;
            border-radius: 8px;
            border: 1px solid var(--border-color);
        }

        /* Submitter Info */
        .submitter-info {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding-top: 1.5rem;
            border-top: 1px solid var(--border-color);
        }

        .submitter-avatar {
            width: 40px;
            height: 40px;
            background: var(--primary-color);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 600;
            font-size: 1rem;
        }

        .submitter-details {
            flex: 1;
        }

        .submitter-name {
            font-weight: 600;
            color: var(--text-primary);
        }

        .submitter-date {
            font-size: 0.875rem;
            color: var(--text-secondary);
        }

        /* Comments Section */
        .comments-section {
            margin-top: 2rem;
            padding-top: 2rem;
            border-top: 1px solid var(--border-color);
        }

        .comments-title {
            font-size: 1.25rem;
            font-weight: 600;
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .comment-count {
            background: #f3f4f6;
            padding: 0.125rem 0.5rem;
            border-radius: 12px;
            font-size: 0.875rem;
            color: var(--text-secondary);
        }

        .comment-item {
            display: flex;
            gap: 1rem;
            padding: 1rem 0;
            border-bottom: 1px solid var(--border-color);
        }

        .comment-item:last-child {
            border-bottom: none;
        }

        .comment-avatar {
            width: 36px;
            height: 36px;
            background: #e5e7eb;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--text-secondary);
            font-weight: 600;
            font-size: 0.875rem;
            flex-shrink: 0;
        }

        .comment-content {
            flex: 1;
        }

        .comment-header {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            margin-bottom: 0.5rem;
        }

        .comment-author {
            font-weight: 600;
            color: var(--text-primary);
        }

        .comment-date {
            font-size: 0.8125rem;
            color: var(--text-secondary);
        }

        .comment-text {
            color: var(--text-primary);
            line-height: 1.6;
        }

        .no-comments {
            text-align: center;
            padding: 2rem;
            color: var(--text-secondary);
        }

        /* Sidebar */
        .sidebar-card {
            background: white;
            border: 1px solid var(--border-color);
            border-radius: 12px;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
        }

        .sidebar-title {
            font-size: 0.875rem;
            font-weight: 600;
            color: var(--text-secondary);
            text-transform: uppercase;
            letter-spacing: 0.05em;
            margin-bottom: 1rem;
        }

        /* Related Feedbacks */
        .related-item {
            display: block;
            padding: 0.75rem 0;
            border-bottom: 1px solid var(--border-color);
            text-decoration: none;
            color: inherit;
            transition: all 0.2s;
        }

        .related-item:last-child {
            border-bottom: none;
            padding-bottom: 0;
        }

        .related-item:hover {
            color: var(--primary-color);
        }

        .related-title {
            font-weight: 500;
            margin-bottom: 0.25rem;
            line-height: 1.4;
        }

        .related-meta {
            font-size: 0.8125rem;
            color: var(--text-secondary);
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        /* Header Actions */
        .header-actions {
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .btn-login {
            background: transparent;
            border: 1px solid var(--border-color);
            padding: 0.5rem 1rem;
            border-radius: 6px;
            color: var(--text-primary);
            font-weight: 500;
            text-decoration: none;
            transition: all 0.2s;
        }

        .btn-login:hover {
            border-color: var(--primary-color);
            color: var(--primary-color);
        }

        .btn-logout {
            background: transparent;
            border: 1px solid #dc3545;
            padding: 0.5rem 1rem;
            border-radius: 6px;
            color: #dc3545;
            font-weight: 500;
            text-decoration: none;
            transition: all 0.2s;
        }

        .btn-logout:hover {
            background: #dc3545;
            color: white;
        }

        .user-info {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.375rem 0.75rem;
            background: #f3f4f6;
            border-radius: 6px;
            font-size: 0.875rem;
            color: var(--text-secondary);
        }

        .user-info i {
            color: var(--primary-color);
        }
    </style>
</head>
<body>
    <!-- Header -->
    <header class="public-header pt-0">
        <div class="container">
            <div class="d-flex justify-content-between align-items-center">
                <div class="logo-section">
                    @if($settings->logo)
                        <img src="{{ asset('storage/' . $settings->logo) }}" alt="{{ $settings->product_name }}" class="logo-img">
                    @else
                        <div class="logo-img" style="width: 40px; height: 40px; background: var(--primary-color); display: flex; align-items: center; justify-content: center; color: white; font-weight: 700; font-size: 1.25rem;">
                            {{ strtoupper(substr($settings->product_name ?? 'F', 0, 1)) }}
                        </div>
                        <h1 class="product-name">{{ $settings->product_name ?? 'Feedback Board' }}</h1>
                    @endif
                </div>

                <div class="header-actions">
                    <a href="{{ route('public.subscribe', $settings->unique_url) }}" class="btn btn-primary" style="background: var(--primary-color); border: none; padding: 0.5rem 1.5rem; border-radius: 6px; text-decoration: none; color: white; font-weight: 500;">
                        <i class="ti ti-bell-ringing me-1"></i> Subscribe
                    </a>

                    @if($isLoggedIn)
                        <span class="user-info">
                            <i class="ti ti-user-circle"></i>
                            {{ $publicUser->full_name ?? $publicUser->email }}
                        </span>
                        <a href="{{ route('public.auth.logout', $settings->unique_url) }}" class="btn-logout">
                            <i class="ti ti-logout me-1"></i> Log out
                        </a>
                    @else
                        <a href="{{ route('public.auth.login', $settings->unique_url) }}" class="btn-login">
                            <i class="ti ti-login me-1"></i> Log in
                        </a>
                    @endif
                </div>
            </div>

            <nav class="public-nav">
                <a href="{{ route('public.home', $settings->unique_url) }}" class="nav-tab active">
                    <i class="ti ti-message-2"></i> Feedback
                </a>
                <a href="{{ route('public.roadmap', $settings->unique_url) }}" class="nav-tab">
                    <i class="ti ti-route"></i> Roadmap
                </a>
                <a href="{{ route('public.changelog', $settings->unique_url) }}" class="nav-tab">
                    <i class="ti ti-clipboard-list"></i> Changelog
                </a>
            </nav>
        </div>
    </header>

    <!-- Main Content -->
    <div class="container">
        <div class="feedback-container">
            <a href="{{ route('public.home', $settings->unique_url) }}" class="back-link">
                <i class="ti ti-arrow-left"></i>
                Back to all feedback
            </a>

            <div class="feedback-main">
                <!-- Left: Main Content -->
                <div class="feedback-content-area">
                    <div class="feedback-detail-card">
                        <div class="feedback-header">
                            <div class="vote-box {{ $hasVoted ? 'voted' : '' }}"
                                 id="voteBox"
                                 data-feedback-id="{{ $feedback->id }}"
                                 onclick="handleVote(this, {{ $feedback->id }})"
                                 title="{{ $hasVoted ? 'Click to remove your vote' : 'Click to vote for this idea' }}">
                                <i class="ti ti-arrow-up vote-icon"></i>
                                <span class="vote-count" id="voteCount">{{ $voteCount }}</span>
                                <span class="vote-label">{{ $voteCount == 1 ? 'vote' : 'votes' }}</span>
                            </div>
                            <div>
                                <h1 class="feedback-title">{{ $feedback->idea }}</h1>
                                <div class="feedback-meta">
                                    @if($feedback->roadmap)
                                        <span class="status-badge" style="background-color: {{ $feedback->roadmap->color }}20; color: {{ $feedback->roadmap->color }};">
                                            <i class="ti ti-circle-filled" style="font-size: 8px;"></i>
                                            {{ $feedback->roadmap->name }}
                                        </span>
                                    @endif
                                    @if($feedback->category)
                                        <span class="category-badge">
                                            <i class="ti ti-tag"></i>
                                            {{ $feedback->category->name }}
                                        </span>
                                    @endif
                                    <span class="date-info">
                                        <i class="ti ti-clock"></i>
                                        {{ $feedback->created_at->format('M d, Y') }}
                                    </span>
                                </div>
                            </div>
                        </div>

                        @if($feedback->value_description)
                            <div class="feedback-description">
                                {!! nl2br(e($feedback->value_description)) !!}
                            </div>
                        @endif

                        @if($feedback->image)
                            <div class="feedback-image">
                                <img src="{{ asset('storage/' . $feedback->image) }}" alt="Feedback Image">
                            </div>
                        @endif

                        <div class="submitter-info">
                            <div class="submitter-avatar">
                                {{ strtoupper(substr($feedback->name ?? 'A', 0, 1)) }}
                            </div>
                            <div class="submitter-details">
                                <div class="submitter-name">{{ $feedback->name ?? 'Anonymous' }}</div>
                                <div class="submitter-date">Submitted {{ $feedback->created_at->diffForHumans() }}</div>
                            </div>
                        </div>

                        <!-- Comments Section -->
                        <div class="comments-section">
                            <h3 class="comments-title">
                                <i class="ti ti-message-circle"></i>
                                Comments
                                <span class="comment-count">{{ $feedback->publicComments->count() }}</span>
                            </h3>

                            @if($feedback->publicComments->count() > 0)
                                @foreach($feedback->publicComments as $comment)
                                    <div class="comment-item">
                                        <div class="comment-avatar">
                                            {{ strtoupper(substr($comment->commenter_name ?? 'A', 0, 1)) }}
                                        </div>
                                        <div class="comment-content">
                                            <div class="comment-header">
                                                <span class="comment-author">{{ $comment->commenter_name ?? 'Anonymous' }}</span>
                                                <span class="comment-date">{{ $comment->created_at->diffForHumans() }}</span>
                                            </div>
                                            <div class="comment-text">
                                                {!! nl2br(e($comment->comment)) !!}
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            @else
                                <div class="no-comments">
                                    <i class="ti ti-message-off" style="font-size: 2rem; display: block; margin-bottom: 0.5rem;"></i>
                                    No comments yet. Be the first to share your thoughts!
                                </div>
                            @endif

                            <!-- Add Comment Form -->
                            <div class="add-comment-section mt-4 pt-4 border-top">
                                <h4 class="mb-3" style="font-size: 1rem; font-weight: 600;">
                                    <i class="ti ti-message-plus me-2"></i>Add a Comment
                                </h4>

                                @if($feedbackSettings->enable_login_for_voting && !$isLoggedIn)
                                    <div class="login-prompt" style="background: #f9fafb; border: 1px solid var(--border-color); border-radius: 8px; padding: 1.5rem; text-align: center;">
                                        <i class="ti ti-lock" style="font-size: 2rem; color: var(--text-secondary); display: block; margin-bottom: 0.5rem;"></i>
                                        <p class="mb-3 text-muted">You need to log in to leave a comment.</p>
                                        <a href="{{ route('public.auth.login', $settings->unique_url) }}" class="btn" style="background: var(--primary-color); color: white;">
                                            <i class="ti ti-login me-1"></i> Log in to Comment
                                        </a>
                                    </div>
                                @else
                                    <div id="commentAlert" class="alert d-none mb-3"></div>
                                    <form id="commentForm">
                                        @csrf
                                        @if(!$isLoggedIn)
                                            <div class="row mb-3">
                                                <div class="col-md-6 mb-3 mb-md-0">
                                                    <input type="text" class="form-control" id="commenterName" name="commenter_name" placeholder="Your Name" required>
                                                </div>
                                                <div class="col-md-6">
                                                    <input type="email" class="form-control" id="commenterEmail" name="commenter_email" placeholder="Your Email (optional)">
                                                </div>
                                            </div>
                                        @endif
                                        <div class="mb-3">
                                            <textarea class="form-control" id="commentText" name="comment" rows="3" placeholder="Write your comment here..." required style="resize: none;"></textarea>
                                        </div>
                                        <div class="d-flex justify-content-end">
                                            <button type="submit" class="btn" id="submitCommentBtn" style="background: var(--primary-color); color: white;">
                                                <i class="ti ti-send me-1"></i> Post Comment
                                            </button>
                                        </div>
                                    </form>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right: Sidebar -->
                <aside>
                    @if($relatedFeedbacks->count() > 0)
                        <div class="sidebar-card">
                            <h4 class="sidebar-title">Related Ideas</h4>
                            @foreach($relatedFeedbacks as $related)
                                <a href="{{ route('public.feedback.show', [$settings->unique_url, $related->id]) }}" class="related-item">
                                    <div class="related-title">{{ Str::limit($related->idea, 60) }}</div>
                                    <div class="related-meta">
                                        <i class="ti ti-arrow-up"></i>
                                        {{ $related->votes->count() }} votes
                                        <span>&bull;</span>
                                        {{ $related->created_at->diffForHumans() }}
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    @endif

                    <div class="sidebar-card">
                        <h4 class="sidebar-title">Share This Idea</h4>
                        <div class="d-flex gap-2">
                            <a href="https://twitter.com/intent/tweet?url={{ urlencode(request()->url()) }}&text={{ urlencode($feedback->idea) }}"
                               target="_blank"
                               class="btn btn-sm btn-outline-secondary">
                                <i class="ti ti-brand-twitter"></i>
                            </a>
                            <a href="https://www.linkedin.com/shareArticle?mini=true&url={{ urlencode(request()->url()) }}&title={{ urlencode($feedback->idea) }}"
                               target="_blank"
                               class="btn btn-sm btn-outline-secondary">
                                <i class="ti ti-brand-linkedin"></i>
                            </a>
                            <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(request()->url()) }}"
                               target="_blank"
                               class="btn btn-sm btn-outline-secondary">
                                <i class="ti ti-brand-facebook"></i>
                            </a>
                            <button type="button" class="btn btn-sm btn-outline-secondary" onclick="copyToClipboard()">
                                <i class="ti ti-copy"></i>
                            </button>
                        </div>
                    </div>
                </aside>
            </div>
        </div>
    </div>

    @include('partials.public-footer')

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        const CONFIG = {
            uniqueUrl: '{{ $settings->unique_url }}',
            isLoggedIn: {{ $isLoggedIn ? 'true' : 'false' }},
            requireLoginForVoting: {{ ($feedbackSettings->enable_login_for_voting ?? false) ? 'true' : 'false' }},
            enableAnonymousVoting: {{ ($feedbackSettings->enable_anonymous_voting ?? true) ? 'true' : 'false' }},
            csrfToken: document.querySelector('meta[name="csrf-token"]').content
        };

        async function handleVote(element, feedbackId) {
            if (CONFIG.requireLoginForVoting && !CONFIG.isLoggedIn) {
                if (confirm('You need to log in to vote. Would you like to log in now?')) {
                    window.location.href = '/' + CONFIG.uniqueUrl + '/login';
                }
                return;
            }

            if (!CONFIG.enableAnonymousVoting && !CONFIG.isLoggedIn) {
                alert('Anonymous voting is not enabled. Please log in to vote.');
                return;
            }

            element.classList.add('processing');

            try {
                const isVoted = element.classList.contains('voted');
                const action = isVoted ? 'unvote' : 'vote';

                const response = await fetch('/' + CONFIG.uniqueUrl + '/feedback/' + feedbackId + '/' + action, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': CONFIG.csrfToken,
                        'Accept': 'application/json'
                    }
                });

                const data = await response.json();

                if (data.success) {
                    const countElement = element.querySelector('.vote-count');
                    countElement.textContent = data.vote_count;

                    const labelElement = element.querySelector('.vote-label');
                    labelElement.textContent = data.vote_count == 1 ? 'vote' : 'votes';

                    if (action === 'vote') {
                        element.classList.add('voted');
                        element.title = 'Click to remove your vote';
                    } else {
                        element.classList.remove('voted');
                        element.title = 'Click to vote for this idea';
                    }
                } else {
                    alert(data.message || 'An error occurred. Please try again.');
                }
            } catch (error) {
                console.error('Vote error:', error);
                alert('An error occurred. Please try again.');
            } finally {
                element.classList.remove('processing');
            }
        }

        function copyToClipboard() {
            navigator.clipboard.writeText(window.location.href).then(() => {
                alert('Link copied to clipboard!');
            }).catch(() => {
                alert('Failed to copy link.');
            });
        }

        // Comment form handling
        const commentForm = document.getElementById('commentForm');
        if (commentForm) {
            commentForm.addEventListener('submit', async function(e) {
                e.preventDefault();

                const submitBtn = document.getElementById('submitCommentBtn');
                const commentAlert = document.getElementById('commentAlert');

                // Get form data
                const formData = {
                    comment: document.getElementById('commentText').value.trim()
                };

                // Get name/email if not logged in
                const nameInput = document.getElementById('commenterName');
                const emailInput = document.getElementById('commenterEmail');
                if (nameInput) {
                    formData.name = nameInput.value.trim();
                }
                if (emailInput) {
                    formData.email = emailInput.value.trim();
                }

                // Validate
                if (!formData.comment) {
                    showCommentAlert('Please enter a comment.', 'danger');
                    return;
                }
                if (nameInput && !formData.name) {
                    showCommentAlert('Please enter your name.', 'danger');
                    return;
                }

                // Disable button
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> Posting...';

                try {
                    const response = await fetch('/' + CONFIG.uniqueUrl + '/feedback/{{ $feedback->id }}/comment', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': CONFIG.csrfToken,
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify(formData)
                    });

                    const data = await response.json();

                    if (data.success) {
                        showCommentAlert('Comment posted successfully!', 'success');

                        // Add the new comment to the list
                        addCommentToList(data.comment);

                        // Clear form
                        document.getElementById('commentText').value = '';
                        if (nameInput) nameInput.value = '';
                        if (emailInput) emailInput.value = '';

                        // Update comment count
                        const countSpan = document.querySelector('.comment-count');
                        if (countSpan) {
                            countSpan.textContent = parseInt(countSpan.textContent) + 1;
                        }

                        // Remove "no comments" message if exists
                        const noComments = document.querySelector('.no-comments');
                        if (noComments) {
                            noComments.remove();
                        }
                    } else {
                        showCommentAlert(data.message || 'Failed to post comment.', 'danger');
                    }
                } catch (error) {
                    console.error('Comment error:', error);
                    showCommentAlert('An error occurred. Please try again.', 'danger');
                } finally {
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = '<i class="ti ti-send me-1"></i> Post Comment';
                }
            });
        }

        function showCommentAlert(message, type) {
            const alert = document.getElementById('commentAlert');
            alert.className = `alert alert-${type} mb-3`;
            alert.textContent = message;
            alert.classList.remove('d-none');

            if (type === 'success') {
                setTimeout(() => {
                    alert.classList.add('d-none');
                }, 3000);
            }
        }

        function addCommentToList(comment) {
            const commentsSection = document.querySelector('.comments-section');
            const addCommentSection = document.querySelector('.add-comment-section');

            const commentHtml = `
                <div class="comment-item">
                    <div class="comment-avatar">
                        ${comment.commenter_name ? comment.commenter_name.charAt(0).toUpperCase() : 'A'}
                    </div>
                    <div class="comment-content">
                        <div class="comment-header">
                            <span class="comment-author">${comment.commenter_name || 'Anonymous'}</span>
                            <span class="comment-date">Just now</span>
                        </div>
                        <div class="comment-text">
                            ${comment.comment.replace(/\n/g, '<br>')}
                        </div>
                    </div>
                </div>
            `;

            // Insert before add comment section
            addCommentSection.insertAdjacentHTML('beforebegin', commentHtml);
        }
    </script>
</body>
</html>
