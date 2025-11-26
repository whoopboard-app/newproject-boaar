<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $category->category_name }} - {{ $knowledgeBoard->name }}</title>

    <!-- Tabler Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/tabler-icons.min.css">

    <style>
        :root {
            --primary-color: #11939A;
            --primary-dark: #0d7a80;
            --header-bg: #11939A;
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
            color: white;
        }

        .hs-hero-title {
            font-size: 28px;
            font-weight: 700;
            color: white;
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

        /* Category Header */
        .hs-category-header {
            background: var(--bg-white);
            border-radius: 16px;
            border: 1px solid var(--border-color);
            padding: 32px;
            margin-bottom: 24px;
            text-align: center;
        }

        .hs-category-icon {
            width: 64px;
            height: 64px;
            background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 16px;
        }

        .hs-category-icon i {
            font-size: 28px;
            color: white;
        }

        .hs-category-title {
            font-size: 28px;
            font-weight: 700;
            color: var(--text-dark);
            margin-bottom: 8px;
        }

        .hs-category-description {
            font-size: 16px;
            color: var(--text-light);
            max-width: 600px;
            margin: 0 auto;
        }

        .hs-category-count {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            margin-top: 16px;
            padding: 6px 14px;
            background: var(--bg-page);
            border-radius: 20px;
            font-size: 13px;
            color: var(--text-light);
        }

        /* Articles List */
        .hs-articles-title {
            font-size: 18px;
            font-weight: 700;
            color: var(--text-dark);
            margin-bottom: 16px;
        }

        .hs-articles-list {
            background: var(--bg-white);
            border-radius: 16px;
            border: 1px solid var(--border-color);
            overflow: hidden;
        }

        .hs-article-item {
            display: flex;
            align-items: center;
            padding: 18px 24px;
            border-bottom: 1px solid var(--border-color);
            color: var(--text-primary);
            transition: all 0.2s;
        }

        .hs-article-item:last-child {
            border-bottom: none;
        }

        .hs-article-item:hover {
            background: var(--bg-page);
        }

        .hs-article-icon {
            width: 40px;
            height: 40px;
            background: var(--bg-page);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 16px;
            flex-shrink: 0;
        }

        .hs-article-icon i {
            font-size: 18px;
            color: var(--primary-color);
        }

        .hs-article-content {
            flex: 1;
            min-width: 0;
        }

        .hs-article-title {
            font-size: 15px;
            font-weight: 600;
            color: var(--text-dark);
            margin-bottom: 4px;
        }

        .hs-article-item:hover .hs-article-title {
            color: var(--primary-color);
        }

        .hs-article-excerpt {
            font-size: 13px;
            color: var(--text-light);
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .hs-article-meta {
            font-size: 12px;
            color: var(--text-light);
            display: flex;
            align-items: center;
            gap: 4px;
            flex-shrink: 0;
            margin-left: 16px;
        }

        .hs-article-arrow {
            color: var(--text-light);
            font-size: 18px;
            margin-left: 12px;
            opacity: 0;
            transform: translateX(-4px);
            transition: all 0.2s;
        }

        .hs-article-item:hover .hs-article-arrow {
            opacity: 1;
            transform: translateX(0);
            color: var(--primary-color);
        }

        /* Empty State */
        .hs-empty {
            text-align: center;
            padding: 60px 24px;
            background: var(--bg-white);
            border-radius: 16px;
            border: 1px solid var(--border-color);
        }

        .hs-empty-icon {
            font-size: 48px;
            color: var(--text-light);
            margin-bottom: 16px;
        }

        .hs-empty-title {
            font-size: 18px;
            font-weight: 600;
            color: var(--text-dark);
            margin-bottom: 8px;
        }

        .hs-empty-text {
            color: var(--text-light);
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

        /* Footer */
        .hs-footer {
            text-align: center;
            padding: 40px 24px;
            color: rgba(255,255,255,0.7);
            font-size: 14px;
            background: var(--header-bg);
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

            .hs-main {
                padding: 24px 16px;
            }

            .hs-category-header {
                padding: 24px;
            }

            .hs-category-title {
                font-size: 24px;
            }

            .hs-article-item {
                padding: 14px 16px;
            }

            .hs-article-meta {
                display: none;
            }

            .hs-article-excerpt {
                display: none;
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
            @if($category->parentCategory)
                <i class="ti ti-chevron-right hs-breadcrumb-separator"></i>
                <a href="{{ route('public.knowledge.category', [$settings->unique_url, $knowledgeBoard->id, $category->parentCategory->id]) }}">{{ $category->parentCategory->category_name }}</a>
            @endif
            <i class="ti ti-chevron-right hs-breadcrumb-separator"></i>
            <span class="hs-breadcrumb-current">{{ $category->category_name }}</span>
        </nav>

        <!-- Category Header -->
        <div class="hs-category-header">
            <div class="hs-category-icon">
                <i class="{{ $category->category_icon ?: 'ti ti-folder' }}"></i>
            </div>
            <h1 class="hs-category-title">{{ $category->category_name }}</h1>
            @if($category->category_description)
                <p class="hs-category-description">{{ $category->category_description }}</p>
            @endif
            <div class="hs-category-count">
                <i class="ti ti-file-text"></i>
                {{ $articles->count() }} {{ Str::plural('article', $articles->count()) }}
            </div>
        </div>

        <!-- Articles List -->
        @if($articles->count() > 0)
            <h2 class="hs-articles-title">Articles in this category</h2>
            <div class="hs-articles-list">
                @foreach($articles as $article)
                    <a href="{{ route('public.knowledge.article', [$settings->unique_url, $knowledgeBoard->id, $article->id]) }}" class="hs-article-item">
                        <div class="hs-article-icon">
                            <i class="ti ti-file-text"></i>
                        </div>
                        <div class="hs-article-content">
                            <div class="hs-article-title">{{ $article->article_title }}</div>
                            @if($article->detailed_post)
                                <div class="hs-article-excerpt">{{ Str::limit(strip_tags($article->detailed_post), 80) }}</div>
                            @endif
                        </div>
                        <div class="hs-article-meta">
                            <i class="ti ti-clock"></i>
                            {{ $article->created_at->format('M d, Y') }}
                        </div>
                        <i class="ti ti-chevron-right hs-article-arrow"></i>
                    </a>
                @endforeach
            </div>
        @else
            <div class="hs-empty">
                <i class="ti ti-files-off hs-empty-icon"></i>
                <h3 class="hs-empty-title">No articles yet</h3>
                <p class="hs-empty-text">There are no articles in this category yet. Check back later!</p>
            </div>
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
