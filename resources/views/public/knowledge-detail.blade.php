<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $knowledgeBoard->name }} - {{ $settings->product_name ?? 'Feedback' }}</title>

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

        /* Content Layout */
        .content-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 2rem 1rem;
        }

        .content-grid {
            display: grid;
            grid-template-columns: 1fr 320px;
            gap: 2rem;
        }

        @media (max-width: 900px) {
            .content-grid {
                grid-template-columns: 1fr;
            }
        }

        /* Main Content Card */
        .main-content {
            background: var(--card-bg);
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.08);
        }

        .content-cover {
            width: 100%;
            height: 240px;
            object-fit: cover;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }

        .content-cover img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .content-cover-placeholder {
            width: 100%;
            height: 240px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .content-cover-placeholder i {
            font-size: 5rem;
            color: rgba(255, 255, 255, 0.6);
        }

        .content-body {
            padding: 2rem;
        }

        .content-type {
            display: inline-flex;
            align-items: center;
            gap: 0.375rem;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 1rem;
            padding: 0.375rem 0.75rem;
            border-radius: 6px;
        }

        .content-type.manual {
            background: #EDE9FE;
            color: #7C3AED;
        }

        .content-type.help {
            background: #DBEAFE;
            color: #2563EB;
        }

        .content-title {
            font-size: 2rem;
            font-weight: 800;
            color: var(--text-primary);
            margin: 0 0 1rem 0;
            line-height: 1.3;
        }

        .content-meta {
            display: flex;
            flex-wrap: wrap;
            gap: 1.5rem;
            padding-bottom: 1.5rem;
            border-bottom: 1px solid var(--border-color);
            margin-bottom: 1.5rem;
        }

        .meta-item {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            color: var(--text-secondary);
            font-size: 0.875rem;
        }

        .meta-item i {
            font-size: 1.125rem;
        }

        .content-description {
            color: var(--text-primary);
            font-size: 1rem;
            line-height: 1.8;
        }

        /* Back Button */
        .back-link {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            color: var(--text-secondary);
            text-decoration: none;
            font-size: 0.875rem;
            font-weight: 500;
            margin-bottom: 1.5rem;
            transition: color 0.2s;
        }

        .back-link:hover {
            color: var(--primary-color);
        }

        /* Sidebar */
        .sidebar {
            display: flex;
            flex-direction: column;
            gap: 1.5rem;
        }

        .sidebar-card {
            background: var(--card-bg);
            border-radius: 12px;
            padding: 1.25rem;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.08);
        }

        .sidebar-title {
            font-size: 0.875rem;
            font-weight: 700;
            color: var(--text-primary);
            margin: 0 0 1rem 0;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .related-list {
            display: flex;
            flex-direction: column;
            gap: 0.75rem;
        }

        .related-item {
            display: flex;
            gap: 0.75rem;
            padding: 0.75rem;
            background: var(--page-bg);
            border-radius: 8px;
            text-decoration: none;
            color: inherit;
            transition: all 0.2s;
        }

        .related-item:hover {
            background: #e5e7eb;
            color: inherit;
        }

        .related-cover {
            width: 48px;
            height: 48px;
            border-radius: 6px;
            overflow: hidden;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .related-cover img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .related-cover i {
            color: rgba(255, 255, 255, 0.7);
            font-size: 1.25rem;
        }

        .related-info {
            flex: 1;
            min-width: 0;
        }

        .related-name {
            font-size: 0.875rem;
            font-weight: 600;
            color: var(--text-primary);
            margin: 0 0 0.25rem 0;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .related-type {
            font-size: 0.75rem;
            color: var(--text-secondary);
        }

        /* Empty Related */
        .empty-related {
            text-align: center;
            padding: 1rem;
            color: var(--text-secondary);
            font-size: 0.875rem;
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

            @include('public.partials.navigation')
        </div>
    </header>

    <!-- Main Content -->
    <div class="content-container">
        <a href="{{ route('public.knowledge', $settings->unique_url) }}" class="btn btn-outline-secondary mb-3" style="display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.5rem 1rem; border-radius: 6px; font-weight: 500;">
            <i class="ti ti-arrow-left"></i>
            Back to Knowledge Board
        </a>

        <div class="content-grid">
            <!-- Main Content -->
            <div class="main-content">
                @if($knowledgeBoard->cover_page)
                    <div class="content-cover">
                        <img src="{{ asset('storage/' . $knowledgeBoard->cover_page) }}" alt="{{ $knowledgeBoard->name }}">
                    </div>
                @else
                    <div class="content-cover-placeholder">
                        <i class="ti ti-book"></i>
                    </div>
                @endif

                <div class="content-body">
                    <span class="content-type {{ $knowledgeBoard->document_type == 'manual' ? 'manual' : 'help' }}">
                        @if($knowledgeBoard->document_type == 'manual')
                            <i class="ti ti-book-2"></i> Manual
                        @else
                            <i class="ti ti-help"></i> Help Document
                        @endif
                    </span>

                    <h1 class="content-title">{{ $knowledgeBoard->name }}</h1>

                    <div class="content-meta">
                        @if($knowledgeBoard->boardOwner)
                            <div class="meta-item">
                                <i class="ti ti-user"></i>
                                {{ $knowledgeBoard->boardOwner->name }}
                            </div>
                        @endif
                        <div class="meta-item">
                            <i class="ti ti-calendar"></i>
                            {{ $knowledgeBoard->created_at->format('F d, Y') }}
                        </div>
                    </div>

                    <div class="content-description">
                        {!! nl2br(e($knowledgeBoard->short_description)) !!}
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="sidebar">
                <div class="sidebar-card">
                    <h3 class="sidebar-title">Other Knowledge Articles</h3>

                    @if($otherBoards->count() > 0)
                        <div class="related-list">
                            @foreach($otherBoards as $board)
                                <a href="{{ route('public.knowledge.show', [$settings->unique_url, $board->id]) }}" class="related-item">
                                    @if($board->cover_page)
                                        <div class="related-cover">
                                            <img src="{{ asset('storage/' . $board->cover_page) }}" alt="{{ $board->name }}">
                                        </div>
                                    @else
                                        <div class="related-cover">
                                            <i class="ti ti-book"></i>
                                        </div>
                                    @endif
                                    <div class="related-info">
                                        <h4 class="related-name">{{ $board->name }}</h4>
                                        <span class="related-type">
                                            {{ $board->document_type == 'manual' ? 'Manual' : 'Help Document' }}
                                        </span>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    @else
                        <div class="empty-related">
                            No other articles available
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    @include('partials.public-footer')

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
