<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $article->article_title }} - {{ $knowledgeBoard->name }}</title>
    @if($theme->meta_description)
    <meta name="description" content="{{ $theme->meta_description }}">
    @endif

    @if($theme->google_analytics_id)
    <!-- Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id={{ $theme->google_analytics_id }}"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());
        gtag('config', '{{ $theme->google_analytics_id }}');
    </script>
    @endif

    <!-- Tabler Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/tabler-icons.min.css">

    <style>
        :root {
            --primary-color: {{ $theme->header_background_color ?? '#11939A' }};
            --primary-dark: {{ $theme->header_background_color ?? '#11939A' }};
            --header-bg: {{ $theme->header_background_color ?? '#11939A' }};
            --header-text: {{ $theme->header_text_color ?? '#FFFFFF' }};
            --footer-bg: {{ $theme->footer_background_color ?? '#11939A' }};
            --footer-text: {{ $theme->footer_text_color ?? '#FFFFFF' }};
            --text-primary: #585858;
            --text-dark: #333333;
            --text-light: #888888;
            --bg-page: #f7f9fa;
            --bg-white: #ffffff;
            --border-color: #e1e5e8;
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
            line-height: 1.6;
        }

        a {
            color: var(--primary-color);
            text-decoration: none;
        }

        a:hover {
            color: var(--primary-dark);
        }

        /* Header Hero Section */
        .hs-hero {
            background: var(--header-bg);
            padding: 32px 24px 48px;
            text-align: center;
        }

        .hs-hero-inner {
            max-width: 720px;
            margin: 0 auto;
        }

        .hs-brand {
            display: inline-flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 24px;
            text-decoration: none;
        }

        .hs-brand-logo {
            width: 44px;
            height: 44px;
            border-radius: 10px;
            object-fit: contain;
            background: rgba(255,255,255,0.2);
        }

        .hs-brand-name {
            font-size: 20px;
            font-weight: 700;
            color: var(--header-text);
        }

        .hs-hero-title {
            font-size: 28px;
            font-weight: 700;
            color: var(--header-text);
            margin-bottom: 24px;
            letter-spacing: -0.5px;
        }

        /* Search Box */
        .hs-search {
            position: relative;
            max-width: 600px;
            margin: 0 auto;
        }

        .hs-search-input {
            width: 100%;
            padding: 16px 20px 16px 52px;
            border: none;
            border-radius: 12px;
            font-size: 16px;
            background: white;
            color: var(--text-dark);
            box-shadow: 0 4px 16px rgba(0,0,0,0.1);
        }

        .hs-search-input:focus {
            outline: none;
            box-shadow: 0 4px 24px rgba(0,0,0,0.15);
        }

        .hs-search-input::placeholder {
            color: var(--text-light);
        }

        .hs-search-icon {
            position: absolute;
            left: 18px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--text-light);
            font-size: 20px;
        }

        /* Search Results */
        .hs-search-results {
            position: absolute;
            top: 100%;
            left: 0;
            right: 0;
            background: white;
            border-radius: 12px;
            box-shadow: 0 8px 32px rgba(0,0,0,0.15);
            margin-top: 8px;
            max-height: 420px;
            overflow-y: auto;
            display: none;
            z-index: 1000;
            text-align: left;
        }

        .hs-search-results.active {
            display: block;
        }

        .hs-search-result-item {
            padding: 14px 18px;
            border-bottom: 1px solid var(--border-color);
            display: block;
            color: var(--text-primary);
        }

        .hs-search-result-item:last-child {
            border-bottom: none;
        }

        .hs-search-result-item:hover {
            background: var(--bg-page);
        }

        .hs-search-result-category {
            font-size: 11px;
            font-weight: 600;
            color: var(--primary-color);
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 3px;
        }

        .hs-search-result-title {
            font-weight: 600;
            color: var(--text-dark);
            font-size: 14px;
            margin-bottom: 3px;
        }

        .hs-search-result-excerpt {
            font-size: 12px;
            color: var(--text-light);
        }

        .hs-search-no-results {
            padding: 20px;
            text-align: center;
            color: var(--text-light);
        }

        /* Main Content */
        .hs-main {
            max-width: 900px;
            margin: 0 auto;
            padding: 40px 24px;
        }

        /* Breadcrumb */
        .hs-breadcrumb {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 14px;
            color: var(--text-light);
            margin-bottom: 24px;
            flex-wrap: wrap;
        }

        .hs-breadcrumb a {
            color: var(--text-light);
        }

        .hs-breadcrumb a:hover {
            color: var(--primary-color);
        }

        .hs-breadcrumb-separator {
            font-size: 12px;
        }

        .hs-breadcrumb-current {
            color: var(--text-primary);
        }

        /* Article Card */
        .hs-article {
            background: var(--bg-white);
            border-radius: 16px;
            border: 1px solid var(--border-color);
            overflow: hidden;
        }

        .hs-article-header {
            padding: 32px 32px 24px;
            border-bottom: 1px solid var(--border-color);
        }

        .hs-article-category {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 6px 14px;
            background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
            color: white;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            margin-bottom: 16px;
        }

        .hs-article-category i {
            font-size: 14px;
        }

        .hs-article-title {
            font-size: 32px;
            font-weight: 700;
            color: var(--text-dark);
            margin-bottom: 16px;
            line-height: 1.3;
            letter-spacing: -0.5px;
        }

        .hs-article-meta {
            display: flex;
            align-items: center;
            gap: 20px;
            font-size: 14px;
            color: var(--text-light);
        }

        .hs-article-meta-item {
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .hs-article-meta-item i {
            font-size: 16px;
        }

        /* Article Content */
        .hs-article-content {
            padding: 32px;
            font-size: 16px;
            line-height: 1.8;
            color: var(--text-primary);
        }

        .hs-article-content h1 {
            font-size: 26px;
            font-weight: 700;
            color: var(--text-dark);
            margin: 36px 0 16px;
            letter-spacing: -0.3px;
        }

        .hs-article-content h2 {
            font-size: 22px;
            font-weight: 700;
            color: var(--text-dark);
            margin: 28px 0 12px;
            letter-spacing: -0.3px;
        }

        .hs-article-content h3 {
            font-size: 18px;
            font-weight: 600;
            color: var(--text-dark);
            margin: 24px 0 10px;
        }

        .hs-article-content h4 {
            font-size: 16px;
            font-weight: 600;
            color: var(--text-dark);
            margin: 20px 0 8px;
        }

        .hs-article-content p {
            margin-bottom: 16px;
        }

        .hs-article-content ul,
        .hs-article-content ol {
            margin: 16px 0;
            padding-left: 24px;
        }

        .hs-article-content li {
            margin-bottom: 8px;
        }

        .hs-article-content a {
            color: var(--primary-color);
        }

        .hs-article-content a:hover {
            text-decoration: underline;
        }

        .hs-article-content img {
            max-width: 100%;
            height: auto;
            border-radius: 12px;
            margin: 24px 0;
            border: 1px solid var(--border-color);
        }

        .hs-article-content blockquote {
            border-left: 4px solid var(--primary-color);
            padding: 16px 20px;
            margin: 24px 0;
            background: var(--bg-page);
            color: var(--text-primary);
            border-radius: 0 12px 12px 0;
        }

        .hs-article-content pre {
            background: #2d2d2d;
            color: #e6e6e6;
            padding: 20px 24px;
            border-radius: 12px;
            overflow-x: auto;
            margin: 24px 0;
            font-family: 'Monaco', 'Menlo', 'Ubuntu Mono', monospace;
            font-size: 14px;
            line-height: 1.6;
        }

        .hs-article-content code {
            background: var(--bg-page);
            padding: 3px 8px;
            border-radius: 6px;
            font-family: 'Monaco', 'Menlo', 'Ubuntu Mono', monospace;
            font-size: 0.9em;
            color: var(--text-dark);
        }

        .hs-article-content pre code {
            background: none;
            padding: 0;
            color: inherit;
        }

        .hs-article-content table {
            width: 100%;
            border-collapse: collapse;
            margin: 24px 0;
            border-radius: 12px;
            overflow: hidden;
            border: 1px solid var(--border-color);
        }

        .hs-article-content th,
        .hs-article-content td {
            border: 1px solid var(--border-color);
            padding: 12px 16px;
            text-align: left;
        }

        .hs-article-content th {
            background: var(--bg-page);
            font-weight: 600;
            color: var(--text-dark);
        }

        /* Article Navigation */
        .hs-article-nav {
            display: flex;
            gap: 16px;
            padding: 24px 32px;
            border-top: 1px solid var(--border-color);
            background: var(--bg-page);
        }

        .hs-article-nav-link {
            flex: 1;
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 16px 20px;
            background: var(--bg-white);
            border: 1px solid var(--border-color);
            border-radius: 12px;
            color: var(--text-primary);
            transition: all 0.2s;
        }

        .hs-article-nav-link:hover {
            border-color: var(--primary-color);
            box-shadow: 0 4px 16px rgba(49, 151, 214, 0.12);
        }

        .hs-article-nav-link--prev {
            flex-direction: row;
        }

        .hs-article-nav-link--next {
            flex-direction: row-reverse;
            text-align: right;
        }

        .hs-article-nav-content {
            flex: 1;
            min-width: 0;
        }

        .hs-article-nav-label {
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: var(--text-light);
            margin-bottom: 4px;
        }

        .hs-article-nav-title {
            font-weight: 600;
            font-size: 14px;
            color: var(--text-dark);
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .hs-article-nav-link:hover .hs-article-nav-title {
            color: var(--primary-color);
        }

        .hs-article-nav-icon {
            font-size: 20px;
            color: var(--text-light);
            flex-shrink: 0;
        }

        .hs-article-nav-link:hover .hs-article-nav-icon {
            color: var(--primary-color);
        }

        .hs-article-nav-spacer {
            flex: 1;
        }

        /* Back Link */
        .hs-back {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            color: var(--text-light);
            font-size: 14px;
            font-weight: 500;
            margin-top: 24px;
            padding: 10px 0;
        }

        .hs-back:hover {
            color: var(--primary-color);
        }

        .hs-back i {
            font-size: 16px;
        }

        /* Related Articles */
        .hs-related {
            margin-top: 32px;
        }

        .hs-related-title {
            font-size: 18px;
            font-weight: 700;
            color: var(--text-dark);
            margin-bottom: 16px;
        }

        .hs-related-list {
            background: var(--bg-white);
            border-radius: 12px;
            border: 1px solid var(--border-color);
            overflow: hidden;
        }

        .hs-related-item {
            display: flex;
            align-items: center;
            padding: 14px 20px;
            color: var(--text-primary);
            border-bottom: 1px solid var(--border-color);
            transition: all 0.2s;
        }

        .hs-related-item:last-child {
            border-bottom: none;
        }

        .hs-related-item:hover {
            background: var(--bg-page);
            color: var(--primary-color);
        }

        .hs-related-item-icon {
            color: var(--text-light);
            margin-right: 12px;
            font-size: 16px;
        }

        .hs-related-item:hover .hs-related-item-icon {
            color: var(--primary-color);
        }

        .hs-related-item-title {
            flex: 1;
            font-size: 14px;
        }

        .hs-related-item-arrow {
            color: var(--text-light);
            font-size: 14px;
            opacity: 0;
            transform: translateX(-4px);
            transition: all 0.2s;
        }

        .hs-related-item:hover .hs-related-item-arrow {
            opacity: 1;
            transform: translateX(0);
        }

        /* Footer */
        .hs-footer {
            text-align: center;
            padding: 40px 24px;
            color: var(--footer-text);
            font-size: 14px;
            background: var(--footer-bg);
            margin-top: 48px;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .hs-hero {
                padding: 24px 16px 36px;
            }

            .hs-hero-title {
                font-size: 24px;
            }

            .hs-search-input {
                padding: 14px 16px 14px 44px;
                font-size: 15px;
            }

            .hs-main {
                padding: 24px 16px;
            }

            .hs-article-header {
                padding: 24px 20px;
            }

            .hs-article-title {
                font-size: 24px;
            }

            .hs-article-content {
                padding: 24px 20px;
            }

            .hs-article-nav {
                flex-direction: column;
                padding: 20px;
            }

            .hs-article-nav-link--next {
                flex-direction: row;
                text-align: left;
            }

            .hs-article-meta {
                flex-direction: column;
                align-items: flex-start;
                gap: 8px;
            }
        }
    </style>
</head>
<body>
    <!-- Hero Section -->
    <header class="hs-hero">
        <div class="hs-hero-inner">
            <a href="{{ route('public.knowledge', $settings->unique_url) }}" class="hs-brand">
                @if($settings->logo)
                    <img src="{{ asset('storage/' . $settings->logo) }}" alt="{{ $settings->product_name }}" class="hs-brand-logo">
                @else
                    <div class="hs-brand-logo" style="display: flex; align-items: center; justify-content: center; color: white; font-weight: 700; font-size: 18px;">
                        {{ strtoupper(substr($settings->product_name ?? 'H', 0, 1)) }}
                    </div>
                @endif
                <span class="hs-brand-name">{{ $settings->product_name ?? 'Help Center' }}</span>
            </a>

            <h1 class="hs-hero-title">{{ $knowledgeBoard->name }}</h1>

            <div class="hs-search">
                <i class="ti ti-search hs-search-icon"></i>
                <input type="text" class="hs-search-input" id="searchInput" placeholder="Search for articles..." autocomplete="off">
                <div class="hs-search-results" id="searchResults"></div>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="hs-main">
        <!-- Breadcrumb -->
        <nav class="hs-breadcrumb">
            <a href="{{ route('public.knowledge', $settings->unique_url) }}">
                <i class="ti ti-home" style="font-size: 14px;"></i>
            </a>
            <i class="ti ti-chevron-right hs-breadcrumb-separator"></i>
            <a href="{{ route('public.knowledge.show', [$settings->unique_url, $knowledgeBoard->id]) }}">{{ $knowledgeBoard->name }}</a>
            @if($article->boardCategory)
                @if($article->boardCategory->parentCategory)
                    <i class="ti ti-chevron-right hs-breadcrumb-separator"></i>
                    <a href="{{ route('public.knowledge.category', [$settings->unique_url, $knowledgeBoard->id, $article->boardCategory->parentCategory->id]) }}">{{ $article->boardCategory->parentCategory->category_name }}</a>
                @endif
                <i class="ti ti-chevron-right hs-breadcrumb-separator"></i>
                <a href="{{ route('public.knowledge.category', [$settings->unique_url, $knowledgeBoard->id, $article->boardCategory->id]) }}">{{ $article->boardCategory->category_name }}</a>
            @endif
            <i class="ti ti-chevron-right hs-breadcrumb-separator"></i>
            <span class="hs-breadcrumb-current">{{ Str::limit($article->article_title, 40) }}</span>
        </nav>

        <!-- Article Card -->
        <article class="hs-article">
            <header class="hs-article-header">
                @if($article->boardCategory)
                    <div class="hs-article-category">
                        <i class="{{ $article->boardCategory->category_icon ?: 'ti ti-folder' }}"></i>
                        {{ $article->boardCategory->category_name }}
                    </div>
                @endif
                <h1 class="hs-article-title">{{ $article->article_title }}</h1>
                <div class="hs-article-meta">
                    <div class="hs-article-meta-item">
                        <i class="ti ti-calendar"></i>
                        {{ $article->created_at->format('M d, Y') }}
                    </div>
                    @if($article->updated_at != $article->created_at)
                        <div class="hs-article-meta-item">
                            <i class="ti ti-refresh"></i>
                            Updated {{ $article->updated_at->format('M d, Y') }}
                        </div>
                    @endif
                </div>
            </header>

            <div class="hs-article-content">
                {!! $article->detailed_post !!}
            </div>

            <!-- Rating Widget -->
            @if(isset($ratingSettings))
                @include('public.knowledge-board.partials.rating-widget')
            @endif

            <!-- Navigation -->
            <nav class="hs-article-nav">
                @if($prevArticle)
                    <a href="{{ route('public.knowledge.article', [$settings->unique_url, $knowledgeBoard->id, $prevArticle->id]) }}" class="hs-article-nav-link hs-article-nav-link--prev">
                        <i class="ti ti-arrow-left hs-article-nav-icon"></i>
                        <div class="hs-article-nav-content">
                            <div class="hs-article-nav-label">Previous</div>
                            <div class="hs-article-nav-title">{{ Str::limit($prevArticle->article_title, 40) }}</div>
                        </div>
                    </a>
                @else
                    <div class="hs-article-nav-spacer"></div>
                @endif

                @if($nextArticle)
                    <a href="{{ route('public.knowledge.article', [$settings->unique_url, $knowledgeBoard->id, $nextArticle->id]) }}" class="hs-article-nav-link hs-article-nav-link--next">
                        <i class="ti ti-arrow-right hs-article-nav-icon"></i>
                        <div class="hs-article-nav-content">
                            <div class="hs-article-nav-label">Next</div>
                            <div class="hs-article-nav-title">{{ Str::limit($nextArticle->article_title, 40) }}</div>
                        </div>
                    </a>
                @else
                    <div class="hs-article-nav-spacer"></div>
                @endif
            </nav>
        </article>

        <!-- Related Articles (from same category) -->
        @if($article->boardCategory && isset($allArticles[$article->boardCategory->id]))
            @php
                $relatedArticles = $allArticles[$article->boardCategory->id]->where('id', '!=', $article->id)->take(5);
            @endphp
            @if($relatedArticles->count() > 0)
                <div class="hs-related">
                    <h2 class="hs-related-title">Related Articles</h2>
                    <div class="hs-related-list">
                        @foreach($relatedArticles as $relatedArticle)
                            <a href="{{ route('public.knowledge.article', [$settings->unique_url, $knowledgeBoard->id, $relatedArticle->id]) }}" class="hs-related-item">
                                <i class="ti ti-file-text hs-related-item-icon"></i>
                                <span class="hs-related-item-title">{{ $relatedArticle->article_title }}</span>
                                <i class="ti ti-chevron-right hs-related-item-arrow"></i>
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif
        @endif

        <!-- Back Link -->
        <a href="{{ route('public.knowledge.show', [$settings->unique_url, $knowledgeBoard->id]) }}" class="hs-back">
            <i class="ti ti-arrow-left"></i>
            Back to {{ $knowledgeBoard->name }}
        </a>
    </main>

    <!-- Footer -->
    <footer class="hs-footer">
        <p>&copy; {{ date('Y') }} {{ $settings->product_name ?? 'Help Center' }}. All rights reserved.</p>
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
                                <a href="${item.url}" class="hs-search-result-item">
                                    <div class="hs-search-result-category">${item.category}</div>
                                    <div class="hs-search-result-title">${item.title}</div>
                                    <div class="hs-search-result-excerpt">${item.excerpt}</div>
                                </a>
                            `).join('');
                        } else {
                            searchResults.innerHTML = '<div class="hs-search-no-results">No results found</div>';
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
            if (!e.target.closest('.hs-search')) {
                searchResults.classList.remove('active');
            }
        });
    </script>
</body>
</html>
