<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Knowledge Board - {{ $settings->product_name ?? 'Feedback' }}</title>

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
            --card-bg: #ffffff;
            --page-bg: #f3f4f6;
        }

        * {
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            background-color: var(--page-bg);
            color: var(--text-primary);
            margin: 0;
            padding: 0;
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

        /* Page Header */
        .page-header {
            text-align: center;
            padding: 3rem 1rem 2rem;
            background: white;
            border-bottom: 1px solid var(--border-color);
        }

        .page-header h1 {
            font-size: 2.5rem;
            font-weight: 800;
            color: var(--text-primary);
            margin: 0 0 0.75rem 0;
            letter-spacing: -0.02em;
        }

        .page-header p {
            color: var(--text-secondary);
            font-size: 1.125rem;
            margin: 0;
            max-width: 600px;
            margin: 0 auto;
        }

        /* Knowledge Grid */
        .knowledge-container {
            padding: 2rem 1rem;
            max-width: 1200px;
            margin: 0 auto;
        }

        .knowledge-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 1.5rem;
        }

        @media (max-width: 1024px) {
            .knowledge-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width: 640px) {
            .knowledge-grid {
                grid-template-columns: 1fr;
            }

            .page-header h1 {
                font-size: 1.75rem;
            }
        }

        /* Knowledge Card */
        .knowledge-card {
            background: var(--card-bg);
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.08), 0 1px 2px rgba(0, 0, 0, 0.06);
            transition: transform 0.2s ease, box-shadow 0.2s ease;
            text-decoration: none;
            color: inherit;
            display: flex;
            flex-direction: column;
        }

        .knowledge-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 24px rgba(0, 0, 0, 0.1), 0 4px 8px rgba(0, 0, 0, 0.06);
            color: inherit;
        }

        .knowledge-cover {
            width: 100%;
            height: 160px;
            object-fit: cover;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .knowledge-cover img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .knowledge-cover-placeholder {
            width: 100%;
            height: 160px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .knowledge-cover-placeholder i {
            font-size: 3rem;
            color: rgba(255, 255, 255, 0.7);
        }

        .knowledge-content {
            padding: 1.25rem;
            flex: 1;
            display: flex;
            flex-direction: column;
        }

        .knowledge-type {
            display: inline-flex;
            align-items: center;
            gap: 0.375rem;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 0.75rem;
            padding: 0.25rem 0.5rem;
            border-radius: 4px;
            width: fit-content;
        }

        .knowledge-type.manual {
            background: #EDE9FE;
            color: #7C3AED;
        }

        .knowledge-type.help {
            background: #DBEAFE;
            color: #2563EB;
        }

        .knowledge-title {
            font-size: 1.125rem;
            font-weight: 700;
            color: var(--text-primary);
            margin: 0 0 0.5rem 0;
            line-height: 1.4;
        }

        .knowledge-description {
            color: var(--text-secondary);
            font-size: 0.875rem;
            line-height: 1.6;
            margin: 0;
            flex: 1;
        }

        .knowledge-meta {
            display: flex;
            align-items: center;
            gap: 1rem;
            margin-top: 1rem;
            padding-top: 1rem;
            border-top: 1px solid var(--border-color);
            font-size: 0.8125rem;
            color: var(--text-secondary);
        }

        .knowledge-meta-item {
            display: flex;
            align-items: center;
            gap: 0.375rem;
        }

        /* Empty State */
        .empty-state {
            text-align: center;
            padding: 6rem 2rem;
            background: white;
            border-radius: 16px;
            max-width: 500px;
            margin: 2rem auto;
        }

        .empty-icon {
            font-size: 4rem;
            color: #E5E7EB;
            margin-bottom: 1.5rem;
        }

        .empty-title {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--text-primary);
            margin-bottom: 0.5rem;
        }

        .empty-text {
            color: var(--text-secondary);
            font-size: 1rem;
        }

        /* Pagination */
        .pagination-wrapper {
            padding: 2rem 1rem 3rem;
            display: flex;
            justify-content: center;
        }

        .pagination {
            gap: 0.5rem;
        }

        .pagination .page-link {
            border-radius: 8px;
            border: none;
            color: var(--text-secondary);
            padding: 0.5rem 1rem;
            background: white;
            box-shadow: 0 1px 2px rgba(0,0,0,0.05);
        }

        .pagination .page-link:hover {
            background: var(--bg-hover);
            color: var(--text-primary);
        }

        .pagination .page-item.active .page-link {
            background: var(--primary-color);
            color: white;
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
                        <div class="logo-img" style="background: var(--primary-color); display: flex; align-items: center; justify-content: center; color: white; font-weight: 700; font-size: 1.25rem;">
                            {{ strtoupper(substr($settings->product_name ?? 'F', 0, 1)) }}
                        </div>
                        <h1 class="product-name">{{ $settings->product_name ?? 'Feedback Board' }}</h1>
                    @endif
                </div>

                <a href="{{ route('public.subscribe', $settings->unique_url) }}" class="btn btn-primary" style="background: var(--primary-color); border: none; padding: 0.5rem 1.5rem; border-radius: 6px; text-decoration: none; color: white; font-weight: 500;">
                    <i class="ti ti-bell-ringing me-1"></i> Subscribe
                </a>
            </div>

            <nav class="public-nav">
                <a href="{{ route('public.home', $settings->unique_url) }}" class="nav-tab">
                    <i class="ti ti-message-2"></i> Feedback
                </a>
                <a href="{{ route('public.roadmap', $settings->unique_url) }}" class="nav-tab">
                    <i class="ti ti-route"></i> Roadmap
                </a>
                <a href="{{ route('public.changelog', $settings->unique_url) }}" class="nav-tab">
                    <i class="ti ti-clipboard-list"></i> Changelog
                </a>
                <a href="{{ route('public.testimonials', $settings->unique_url) }}" class="nav-tab">
                    <i class="ti ti-star"></i> Testimonials
                </a>
                <a href="{{ route('public.knowledge', $settings->unique_url) }}" class="nav-tab active">
                    <i class="ti ti-book"></i> Knowledge
                </a>
            </nav>
        </div>
    </header>

    <!-- Page Header -->
    <div class="page-header">
        <h1>Knowledge Board</h1>
        <p>Browse our guides, manuals, and help documents for {{ $settings->product_name ?? 'our product' }}</p>
    </div>

    <!-- Main Content -->
    @if($knowledgeBoards->count() > 0)
        <div class="knowledge-container">
            <div class="knowledge-grid">
                @foreach($knowledgeBoards as $board)
                    <a href="{{ route('public.knowledge.show', [$settings->unique_url, $board->id]) }}" class="knowledge-card">
                        @if($board->cover_page)
                            <div class="knowledge-cover">
                                <img src="{{ asset('storage/' . $board->cover_page) }}" alt="{{ $board->name }}">
                            </div>
                        @else
                            <div class="knowledge-cover-placeholder">
                                <i class="ti ti-book"></i>
                            </div>
                        @endif

                        <div class="knowledge-content">
                            <span class="knowledge-type {{ $board->document_type == 'manual' ? 'manual' : 'help' }}">
                                @if($board->document_type == 'manual')
                                    <i class="ti ti-book-2"></i> Manual
                                @else
                                    <i class="ti ti-help"></i> Help Document
                                @endif
                            </span>

                            <h3 class="knowledge-title">{{ $board->name }}</h3>
                            <p class="knowledge-description">{{ Str::limit($board->short_description, 120) }}</p>

                            <div class="knowledge-meta">
                                @if($board->boardOwner)
                                    <span class="knowledge-meta-item">
                                        <i class="ti ti-user"></i>
                                        {{ $board->boardOwner->name }}
                                    </span>
                                @endif
                                <span class="knowledge-meta-item">
                                    <i class="ti ti-calendar"></i>
                                    {{ $board->created_at->format('M d, Y') }}
                                </span>
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>

        <!-- Pagination -->
        @if($knowledgeBoards->hasPages())
            <div class="pagination-wrapper">
                {{ $knowledgeBoards->links() }}
            </div>
        @endif
    @else
        <div class="knowledge-container">
            <div class="empty-state">
                <i class="ti ti-book-off empty-icon"></i>
                <h2 class="empty-title">No knowledge articles yet</h2>
                <p class="empty-text">Check back later for guides and documentation!</p>
            </div>
        </div>
    @endif

    @include('partials.public-footer')

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
