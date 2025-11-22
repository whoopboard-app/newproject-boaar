<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $changelog->title }} - {{ $settings->product_name ?? 'Changelog' }}</title>

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

        /* Changelog Detail */
        .changelog-container {
            max-width: 800px;
            margin: 0 auto;
            padding: 2rem 0;
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

        .changelog-header {
            margin-bottom: 2rem;
            padding-bottom: 2rem;
            border-bottom: 1px solid var(--border-color);
        }

        .changelog-meta {
            display: flex;
            align-items: center;
            gap: 1rem;
            margin-bottom: 1rem;
        }

        .changelog-category {
            display: inline-flex;
            align-items: center;
            padding: 0.25rem 0.75rem;
            border-radius: 12px;
            font-size: 0.8125rem;
            font-weight: 500;
        }

        .changelog-date {
            font-size: 0.875rem;
            color: var(--text-secondary);
        }

        .changelog-title {
            font-size: 2rem;
            font-weight: 700;
            color: var(--text-primary);
            margin: 0;
            line-height: 1.3;
        }

        .changelog-content {
            color: var(--text-primary);
            line-height: 1.8;
            font-size: 1rem;
        }

        .changelog-content h1,
        .changelog-content h2,
        .changelog-content h3,
        .changelog-content h4 {
            margin-top: 2rem;
            margin-bottom: 1rem;
            font-weight: 600;
        }

        .changelog-content h1 {
            font-size: 1.75rem;
        }

        .changelog-content h2 {
            font-size: 1.5rem;
        }

        .changelog-content h3 {
            font-size: 1.25rem;
        }

        .changelog-content ul,
        .changelog-content ol {
            margin-left: 1.5rem;
            margin-bottom: 1.5rem;
        }

        .changelog-content li {
            margin-bottom: 0.5rem;
        }

        .changelog-content p {
            margin-bottom: 1.5rem;
        }

        .changelog-content img {
            max-width: 100%;
            height: auto;
            border-radius: 8px;
            margin: 1.5rem 0;
        }

        .changelog-content code {
            background: #f3f4f6;
            padding: 0.2rem 0.4rem;
            border-radius: 4px;
            font-size: 0.875em;
            font-family: 'Courier New', monospace;
        }

        .changelog-content pre {
            background: #f3f4f6;
            padding: 1rem;
            border-radius: 8px;
            overflow-x: auto;
            margin: 1.5rem 0;
        }

        .changelog-content blockquote {
            border-left: 4px solid var(--primary-color);
            padding-left: 1rem;
            margin: 1.5rem 0;
            color: var(--text-secondary);
            font-style: italic;
        }
    </style>
</head>
<body>
    <!-- Header -->
    <header class="public-header pt-0">
        <div class="container">
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

            <nav class="public-nav">
                <a href="{{ route('public.home', $settings->unique_url) }}" class="nav-tab">
                    <i class="ti ti-message-2"></i> Feedback
                </a>
                <a href="{{ route('public.roadmap', $settings->unique_url) }}" class="nav-tab">
                    <i class="ti ti-route"></i> Roadmap
                </a>
                <a href="{{ route('public.changelog', $settings->unique_url) }}" class="nav-tab active">
                    <i class="ti ti-clipboard-list"></i> Changelog
                </a>
            </nav>
        </div>
    </header>

    <!-- Main Content -->
    <div class="container">
        <div class="changelog-container">
            <a href="{{ route('public.changelog', $settings->unique_url) }}" class="back-link">
                <i class="ti ti-arrow-left"></i>
                Back to all updates
            </a>

            <header class="changelog-header">
                <div class="changelog-meta">
                    @if($changelog->category)
                        <span class="changelog-category" style="background-color: {{ $changelog->category->color ?? '#e5e7eb' }}20; color: {{ $changelog->category->color ?? '#6b7280' }};">
                            {{ $changelog->category->name }}
                        </span>
                    @endif
                    <span class="changelog-date">
                        <i class="ti ti-calendar"></i>
                        {{ \Carbon\Carbon::parse($changelog->published_date)->format('F d, Y') }}
                    </span>
                </div>
                <h1 class="changelog-title">{{ $changelog->title }}</h1>
            </header>

            <article class="changelog-content">
                {!! $changelog->content !!}
            </article>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
