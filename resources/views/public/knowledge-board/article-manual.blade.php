<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $article->article_title }} - {{ $knowledgeBoard->name }}</title>

    <!-- Tabler Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/tabler-icons.min.css">

    <style>
        :root {
            --primary-color: #5865F2;
            --primary-hover: #4752c4;
            --text-primary: #333;
            --text-secondary: #666;
            --text-light: #888;
            --bg-dark: #1a1a2e;
            --bg-page: #ffffff;
            --bg-card: #f8f9fa;
            --border-color: #e5e7eb;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            background-color: var(--bg-page);
            color: var(--text-primary);
            line-height: 1.7;
        }

        a {
            color: var(--primary-color);
            text-decoration: none;
        }

        a:hover {
            color: var(--primary-hover);
            text-decoration: underline;
        }

        /* Header */
        .article-header {
            background: var(--bg-dark);
            padding: 2rem 0;
        }

        .article-header-inner {
            max-width: 900px;
            margin: 0 auto;
            padding: 0 2rem;
        }

        .article-brand {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 1rem;
            margin-bottom: 1.5rem;
        }

        .article-brand-logo {
            width: 40px;
            height: 40px;
            border-radius: 8px;
            object-fit: contain;
            background: rgba(255,255,255,0.1);
        }

        .article-brand-name {
            font-size: 1.25rem;
            font-weight: 700;
            color: white;
        }

        /* Search */
        .article-search {
            max-width: 500px;
            margin: 0 auto;
            position: relative;
        }

        .article-search-input {
            width: 100%;
            padding: 0.875rem 1rem 0.875rem 2.75rem;
            border: none;
            border-radius: 8px;
            font-size: 0.9375rem;
            background: rgba(255,255,255,0.95);
            color: var(--text-primary);
        }

        .article-search-input:focus {
            outline: none;
            box-shadow: 0 0 0 4px rgba(255,255,255,0.2);
        }

        .article-search-input::placeholder {
            color: var(--text-light);
        }

        .article-search-icon {
            position: absolute;
            left: 1rem;
            top: 50%;
            transform: translateY(-50%);
            color: var(--text-light);
            font-size: 1.125rem;
        }

        /* Search Results */
        .article-search-results {
            position: absolute;
            top: 100%;
            left: 0;
            right: 0;
            background: white;
            border-radius: 8px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.2);
            margin-top: 0.5rem;
            max-height: 400px;
            overflow-y: auto;
            display: none;
            z-index: 1000;
            text-align: left;
        }

        .article-search-results.active {
            display: block;
        }

        .article-search-result-item {
            padding: 1rem 1.25rem;
            border-bottom: 1px solid var(--border-color);
            display: block;
            color: var(--text-primary);
        }

        .article-search-result-item:last-child {
            border-bottom: none;
        }

        .article-search-result-item:hover {
            background: var(--bg-card);
            text-decoration: none;
        }

        .article-search-result-title {
            font-weight: 600;
            color: var(--text-primary);
            margin-bottom: 0.25rem;
        }

        .article-search-result-category {
            font-size: 0.75rem;
            color: var(--primary-color);
            margin-bottom: 0.25rem;
        }

        .article-search-result-excerpt {
            font-size: 0.8125rem;
            color: var(--text-secondary);
        }

        .article-search-no-results {
            padding: 1.5rem;
            text-align: center;
            color: var(--text-secondary);
        }

        /* Main Content */
        .article-main {
            max-width: 900px;
            margin: 0 auto;
            padding: 2rem 2rem 3rem;
        }

        /* Breadcrumb */
        .article-breadcrumb {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.875rem;
            color: var(--text-secondary);
            margin-bottom: 2rem;
            flex-wrap: wrap;
        }

        .article-breadcrumb a {
            color: var(--text-secondary);
        }

        .article-breadcrumb a:hover {
            color: var(--primary-color);
            text-decoration: none;
        }

        .article-breadcrumb-separator {
            color: var(--text-light);
        }

        .article-breadcrumb-current {
            color: var(--text-primary);
            font-weight: 500;
        }

        /* Article Content */
        .article-card {
            background: var(--bg-card);
            border-radius: 12px;
            padding: 2.5rem;
            border: 1px solid var(--border-color);
        }

        .article-title {
            font-size: 2rem;
            font-weight: 800;
            color: var(--text-primary);
            margin-bottom: 1rem;
            letter-spacing: -0.02em;
            line-height: 1.3;
        }

        .article-meta {
            display: flex;
            align-items: center;
            gap: 1.5rem;
            font-size: 0.875rem;
            color: var(--text-secondary);
            margin-bottom: 2rem;
            padding-bottom: 1.5rem;
            border-bottom: 1px solid var(--border-color);
        }

        .article-meta-item {
            display: flex;
            align-items: center;
            gap: 0.375rem;
        }

        .article-meta-item i {
            font-size: 1rem;
            color: var(--text-light);
        }

        .article-body {
            font-size: 1rem;
            line-height: 1.8;
            color: var(--text-primary);
        }

        /* Article body styles */
        .article-body h1 {
            font-size: 1.75rem;
            font-weight: 700;
            margin: 2rem 0 1rem;
            color: var(--text-primary);
        }

        .article-body h2 {
            font-size: 1.5rem;
            font-weight: 700;
            margin: 1.75rem 0 0.875rem;
            color: var(--text-primary);
        }

        .article-body h3 {
            font-size: 1.25rem;
            font-weight: 600;
            margin: 1.5rem 0 0.75rem;
            color: var(--text-primary);
        }

        .article-body p {
            margin-bottom: 1rem;
        }

        .article-body ul,
        .article-body ol {
            margin: 1rem 0;
            padding-left: 1.5rem;
        }

        .article-body li {
            margin-bottom: 0.5rem;
        }

        .article-body pre {
            background: #1e1e1e;
            color: #d4d4d4;
            padding: 1rem 1.25rem;
            border-radius: 8px;
            overflow-x: auto;
            margin: 1rem 0;
            font-family: 'Monaco', 'Menlo', monospace;
            font-size: 0.875rem;
        }

        .article-body code {
            background: #f1f1f1;
            padding: 0.125rem 0.375rem;
            border-radius: 4px;
            font-family: 'Monaco', 'Menlo', monospace;
            font-size: 0.875em;
        }

        .article-body pre code {
            background: none;
            padding: 0;
        }

        .article-body blockquote {
            border-left: 4px solid var(--primary-color);
            padding-left: 1rem;
            margin: 1rem 0;
            color: var(--text-secondary);
            font-style: italic;
        }

        .article-body img {
            max-width: 100%;
            height: auto;
            border-radius: 8px;
            margin: 1rem 0;
        }

        .article-body a {
            color: var(--primary-color);
        }

        .article-body a:hover {
            text-decoration: underline;
        }

        .article-body table {
            width: 100%;
            border-collapse: collapse;
            margin: 1rem 0;
        }

        .article-body th,
        .article-body td {
            border: 1px solid var(--border-color);
            padding: 0.75rem;
            text-align: left;
        }

        .article-body th {
            background: var(--bg-card);
            font-weight: 600;
        }

        /* Navigation */
        .article-nav {
            display: flex;
            gap: 1rem;
            margin-top: 2rem;
            padding-top: 2rem;
            border-top: 1px solid var(--border-color);
        }

        .article-nav-link {
            flex: 1;
            padding: 1.25rem;
            background: white;
            border: 1px solid var(--border-color);
            border-radius: 10px;
            color: var(--text-primary);
            transition: all 0.2s;
        }

        .article-nav-link:hover {
            border-color: var(--primary-color);
            box-shadow: 0 4px 12px rgba(88, 101, 242, 0.1);
            text-decoration: none;
        }

        .article-nav-link--prev {
            text-align: left;
        }

        .article-nav-link--next {
            text-align: right;
        }

        .article-nav-label {
            display: flex;
            align-items: center;
            gap: 0.375rem;
            font-size: 0.75rem;
            color: var(--text-secondary);
            text-transform: uppercase;
            letter-spacing: 0.05em;
            margin-bottom: 0.375rem;
        }

        .article-nav-link--next .article-nav-label {
            justify-content: flex-end;
        }

        .article-nav-title {
            font-weight: 600;
            color: var(--text-primary);
        }

        .article-nav-link:hover .article-nav-title {
            color: var(--primary-color);
        }

        .article-nav-spacer {
            flex: 1;
        }

        /* Back Link */
        .article-back {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            color: var(--text-secondary);
            font-size: 0.875rem;
            margin-top: 2rem;
        }

        .article-back:hover {
            color: var(--primary-color);
            text-decoration: none;
        }

        /* Footer */
        .article-footer {
            text-align: center;
            padding: 2rem;
            color: var(--text-secondary);
            font-size: 0.875rem;
            border-top: 1px solid var(--border-color);
            margin-top: 2rem;
        }

        /* Responsive */
        @media (max-width: 640px) {
            .article-card {
                padding: 1.5rem;
            }

            .article-title {
                font-size: 1.5rem;
            }

            .article-main {
                padding: 1.5rem 1rem;
            }

            .article-nav {
                flex-direction: column;
            }

            .article-nav-link--next {
                text-align: left;
            }

            .article-nav-link--next .article-nav-label {
                justify-content: flex-start;
            }

            .article-meta {
                flex-direction: column;
                align-items: flex-start;
                gap: 0.5rem;
            }
        }
    </style>
</head>
<body>
    <!-- Header -->
    <header class="article-header">
        <div class="article-header-inner">
            <a href="{{ route('public.knowledge.show', [$settings->unique_url, $knowledgeBoard->id]) }}" class="article-brand" style="text-decoration: none;">
                @if($settings->logo)
                    <img src="{{ asset('storage/' . $settings->logo) }}" alt="{{ $settings->product_name }}" class="article-brand-logo">
                @else
                    <div class="article-brand-logo" style="display: flex; align-items: center; justify-content: center; color: white; font-weight: 700; font-size: 1rem;">
                        {{ strtoupper(substr($settings->product_name ?? 'D', 0, 1)) }}
                    </div>
                @endif
                <span class="article-brand-name">{{ $knowledgeBoard->name }}</span>
            </a>

            <div class="article-search">
                <i class="ti ti-search article-search-icon"></i>
                <input type="text" class="article-search-input" id="searchInput" placeholder="Search documentation..." autocomplete="off">
                <div class="article-search-results" id="searchResults"></div>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="article-main">
        <!-- Breadcrumb -->
        <nav class="article-breadcrumb">
            <a href="{{ route('public.knowledge.show', [$settings->unique_url, $knowledgeBoard->id]) }}">
                <i class="ti ti-home" style="font-size: 1rem;"></i>
            </a>
            <span class="article-breadcrumb-separator">
                <i class="ti ti-chevron-right" style="font-size: 0.875rem;"></i>
            </span>
            @if($article->boardCategory)
                <a href="{{ route('public.knowledge.show', [$settings->unique_url, $knowledgeBoard->id]) }}">{{ $article->boardCategory->category_name }}</a>
                <span class="article-breadcrumb-separator">
                    <i class="ti ti-chevron-right" style="font-size: 0.875rem;"></i>
                </span>
            @endif
            <span class="article-breadcrumb-current">{{ $article->article_title }}</span>
        </nav>

        <!-- Article Card -->
        <article class="article-card">
            <h1 class="article-title">{{ $article->article_title }}</h1>

            <div class="article-meta">
                @if($article->boardCategory)
                    <span class="article-meta-item">
                        <i class="ti ti-folder"></i>
                        {{ $article->boardCategory->category_name }}
                    </span>
                @endif
                <span class="article-meta-item">
                    <i class="ti ti-calendar"></i>
                    {{ $article->created_at->format('M d, Y') }}
                </span>
                @if($article->updated_at != $article->created_at)
                    <span class="article-meta-item">
                        <i class="ti ti-refresh"></i>
                        Updated {{ $article->updated_at->format('M d, Y') }}
                    </span>
                @endif
            </div>

            <div class="article-body">
                {!! $article->detailed_post !!}
            </div>

            <!-- Navigation -->
            <nav class="article-nav">
                @if($prevArticle)
                    <a href="{{ route('public.knowledge.article', [$settings->unique_url, $knowledgeBoard->id, $prevArticle->id]) }}" class="article-nav-link article-nav-link--prev">
                        <div class="article-nav-label">
                            <i class="ti ti-arrow-left" style="font-size: 0.875rem;"></i>
                            Previous
                        </div>
                        <div class="article-nav-title">{{ $prevArticle->article_title }}</div>
                    </a>
                @else
                    <div class="article-nav-spacer"></div>
                @endif

                @if($nextArticle)
                    <a href="{{ route('public.knowledge.article', [$settings->unique_url, $knowledgeBoard->id, $nextArticle->id]) }}" class="article-nav-link article-nav-link--next">
                        <div class="article-nav-label">
                            Next
                            <i class="ti ti-arrow-right" style="font-size: 0.875rem;"></i>
                        </div>
                        <div class="article-nav-title">{{ $nextArticle->article_title }}</div>
                    </a>
                @else
                    <div class="article-nav-spacer"></div>
                @endif
            </nav>
        </article>

        <!-- Back Link -->
        <a href="{{ route('public.knowledge.show', [$settings->unique_url, $knowledgeBoard->id]) }}" class="article-back">
            <i class="ti ti-arrow-left"></i>
            Back to all documentation
        </a>
    </main>

    <!-- Footer -->
    <footer class="article-footer">
        <p>&copy; {{ date('Y') }} {{ $settings->product_name ?? 'Documentation' }}. All rights reserved.</p>
    </footer>

    <script>
        // Search functionality
        const searchInput = document.getElementById('searchInput');
        const searchResults = document.getElementById('searchResults');
        let searchTimeout;

        searchInput.addEventListener('input', function() {
            clearTimeout(searchTimeout);
            const query = this.value.trim();

            if (query.length < 2) {
                searchResults.classList.remove('active');
                return;
            }

            searchTimeout = setTimeout(() => {
                fetch(`{{ route('public.knowledge.search', [$settings->unique_url, $knowledgeBoard->id]) }}?q=${encodeURIComponent(query)}`)
                    .then(response => response.json())
                    .then(data => {
                        if (data.results.length > 0) {
                            searchResults.innerHTML = data.results.map(item => `
                                <a href="${item.url}" class="article-search-result-item">
                                    <div class="article-search-result-category">${item.category}</div>
                                    <div class="article-search-result-title">${item.title}</div>
                                    <div class="article-search-result-excerpt">${item.excerpt}</div>
                                </a>
                            `).join('');
                        } else {
                            searchResults.innerHTML = '<div class="article-search-no-results">No results found</div>';
                        }
                        searchResults.classList.add('active');
                    })
                    .catch(error => {
                        console.error('Search error:', error);
                    });
            }, 300);
        });

        // Close search results when clicking outside
        document.addEventListener('click', function(e) {
            if (!e.target.closest('.article-search')) {
                searchResults.classList.remove('active');
            }
        });
    </script>
</body>
</html>
