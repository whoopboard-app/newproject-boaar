<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Roadmap - {{ $settings->product_name ?? 'Feedback' }}</title>

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
            width: 40px;
            height: 40px;
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

        /* Roadmap Section */
        .roadmap-section {
            margin-bottom: 3rem;
        }

        .roadmap-header {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            margin-bottom: 1.5rem;
            padding-bottom: 0.75rem;
            border-bottom: 2px solid var(--border-color);
        }

        .status-indicator {
            width: 12px;
            height: 12px;
            border-radius: 50%;
        }

        .roadmap-title {
            font-size: 1.5rem;
            font-weight: 600;
            color: var(--text-primary);
            margin: 0;
        }

        .roadmap-count {
            font-size: 0.875rem;
            color: var(--text-secondary);
            background: #f3f4f6;
            padding: 0.25rem 0.75rem;
            border-radius: 12px;
        }

        .feedback-list {
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }

        .feedback-card {
            background: white;
            border: 1px solid var(--border-color);
            border-radius: 8px;
            padding: 1.25rem;
            transition: all 0.2s;
        }

        .feedback-card:hover {
            border-color: var(--primary-color);
            box-shadow: 0 2px 8px rgba(88, 101, 242, 0.1);
        }

        .feedback-title {
            font-size: 1.125rem;
            font-weight: 600;
            color: var(--text-primary);
            margin-bottom: 0.5rem;
        }

        .feedback-description {
            color: var(--text-secondary);
            font-size: 0.9375rem;
            line-height: 1.5;
            margin-bottom: 0.75rem;
        }

        .feedback-meta {
            display: flex;
            align-items: center;
            gap: 1rem;
            font-size: 0.875rem;
            color: var(--text-secondary);
        }

        .category-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.375rem;
            padding: 0.25rem 0.75rem;
            border-radius: 12px;
            background: #f3f4f6;
            color: var(--text-secondary);
            font-size: 0.8125rem;
            font-weight: 500;
        }

        .empty-state {
            text-align: center;
            padding: 4rem 2rem;
            background: #f9fafb;
            border-radius: 8px;
        }

        .empty-icon {
            font-size: 4rem;
            color: var(--border-color);
            margin-bottom: 1rem;
        }

        .empty-title {
            font-size: 1.25rem;
            font-weight: 600;
            color: var(--text-primary);
            margin-bottom: 0.5rem;
        }

        .empty-text {
            color: var(--text-secondary);
        }
    </style>
</head>
<body>
    <!-- Header -->
    <header class="public-header">
        <div class="container">
            <div class="logo-section">
                @if($settings->logo)
                    <img src="{{ asset('storage/' . $settings->logo) }}" alt="{{ $settings->product_name }}" class="logo-img">
                @else
                    <div class="logo-img" style="background: var(--primary-color); display: flex; align-items: center; justify-content: center; color: white; font-weight: 700; font-size: 1.25rem;">
                        {{ strtoupper(substr($settings->product_name ?? 'F', 0, 1)) }}
                    </div>
                @endif
                <h1 class="product-name">{{ $settings->product_name ?? 'Feedback Board' }}</h1>
            </div>

            <nav class="public-nav">
                <a href="{{ route('public.home', $settings->unique_url) }}" class="nav-tab">
                    <i class="ti ti-message-2"></i> Feedback
                </a>
                <a href="{{ route('public.roadmap', $settings->unique_url) }}" class="nav-tab active">
                    <i class="ti ti-route"></i> Roadmap
                </a>
                <a href="{{ route('public.changelog', $settings->unique_url) }}" class="nav-tab">
                    <i class="ti ti-clipboard-list"></i> Changelog
                </a>
            </nav>
        </div>
    </header>

    <!-- Main Content -->
    <div class="container" style="padding: 2rem 0;">
        @forelse($roadmaps as $roadmap)
            <div class="roadmap-section">
                <div class="roadmap-header">
                    <span class="status-indicator" style="background-color: {{ $roadmap->color }};"></span>
                    <h2 class="roadmap-title">{{ $roadmap->name }}</h2>
                    <span class="roadmap-count">
                        {{ isset($feedbacks[$roadmap->id]) ? $feedbacks[$roadmap->id]->count() : 0 }} items
                    </span>
                </div>

                @if(isset($feedbacks[$roadmap->id]) && $feedbacks[$roadmap->id]->count() > 0)
                    <div class="feedback-list">
                        @foreach($feedbacks[$roadmap->id] as $feedback)
                            <div class="feedback-card">
                                <h3 class="feedback-title">{{ $feedback->idea }}</h3>
                                @if($feedback->value_description)
                                    <div class="feedback-description">
                                        {{ Str::limit(strip_tags($feedback->value_description), 150) }}
                                    </div>
                                @endif
                                <div class="feedback-meta">
                                    @if($feedback->category)
                                        <span class="category-badge">
                                            {{ $feedback->category->name }}
                                        </span>
                                    @endif
                                    <span>
                                        <i class="ti ti-clock"></i>
                                        {{ $feedback->created_at->diffForHumans() }}
                                    </span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="empty-state">
                        <i class="ti ti-inbox empty-icon"></i>
                        <p class="empty-text">No items in this status yet</p>
                    </div>
                @endif
            </div>
        @empty
            <div class="empty-state">
                <i class="ti ti-route empty-icon"></i>
                <h2 class="empty-title">No roadmap yet</h2>
                <p class="empty-text">Check back later for updates on our progress!</p>
            </div>
        @endforelse
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
