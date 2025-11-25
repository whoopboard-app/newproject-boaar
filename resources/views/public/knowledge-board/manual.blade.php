<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $knowledgeBoard->name }} - {{ $settings->product_name ?? 'Documentation' }}</title>

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
        .manual-header {
            background: var(--bg-dark);
            padding: 3rem 0;
            text-align: center;
        }

        .manual-header-inner {
            max-width: 900px;
            margin: 0 auto;
            padding: 0 2rem;
        }

        .manual-brand {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 1rem;
            margin-bottom: 1.5rem;
        }

        .manual-brand-logo {
            width: 48px;
            height: 48px;
            border-radius: 10px;
            object-fit: contain;
            background: rgba(255,255,255,0.1);
        }

        .manual-brand-name {
            font-size: 1.5rem;
            font-weight: 700;
            color: white;
        }

        .manual-title {
            font-size: 2.5rem;
            font-weight: 800;
            color: white;
            margin-bottom: 0.75rem;
            letter-spacing: -0.02em;
        }

        .manual-description {
            font-size: 1.125rem;
            color: rgba(255,255,255,0.7);
            max-width: 600px;
            margin: 0 auto 2rem;
        }

        /* Search */
        .manual-search {
            max-width: 500px;
            margin: 0 auto;
            position: relative;
        }

        .manual-search-input {
            width: 100%;
            padding: 1rem 1.25rem 1rem 3rem;
            border: none;
            border-radius: 10px;
            font-size: 1rem;
            background: rgba(255,255,255,0.95);
            color: var(--text-primary);
        }

        .manual-search-input:focus {
            outline: none;
            box-shadow: 0 0 0 4px rgba(255,255,255,0.2);
        }

        .manual-search-input::placeholder {
            color: var(--text-light);
        }

        .manual-search-icon {
            position: absolute;
            left: 1rem;
            top: 50%;
            transform: translateY(-50%);
            color: var(--text-light);
            font-size: 1.25rem;
        }

        /* Search Results */
        .manual-search-results {
            position: absolute;
            top: 100%;
            left: 0;
            right: 0;
            background: white;
            border-radius: 10px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.2);
            margin-top: 0.5rem;
            max-height: 400px;
            overflow-y: auto;
            display: none;
            z-index: 1000;
            text-align: left;
        }

        .manual-search-results.active {
            display: block;
        }

        .manual-search-result-item {
            padding: 1rem 1.25rem;
            border-bottom: 1px solid var(--border-color);
            display: block;
            color: var(--text-primary);
        }

        .manual-search-result-item:last-child {
            border-bottom: none;
        }

        .manual-search-result-item:hover {
            background: var(--bg-card);
            text-decoration: none;
        }

        .manual-search-result-title {
            font-weight: 600;
            color: var(--text-primary);
            margin-bottom: 0.25rem;
        }

        .manual-search-result-category {
            font-size: 0.75rem;
            color: var(--primary-color);
            margin-bottom: 0.25rem;
        }

        .manual-search-result-excerpt {
            font-size: 0.8125rem;
            color: var(--text-secondary);
        }

        .manual-search-no-results {
            padding: 1.5rem;
            text-align: center;
            color: var(--text-secondary);
        }

        /* Main Content */
        .manual-main {
            max-width: 900px;
            margin: 0 auto;
            padding: 3rem 2rem;
        }

        /* Back Link */
        .manual-back {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            color: var(--text-secondary);
            font-size: 0.875rem;
            margin-bottom: 2rem;
        }

        .manual-back:hover {
            color: var(--primary-color);
            text-decoration: none;
        }

        /* Category Card */
        .manual-category {
            background: var(--bg-card);
            border-radius: 12px;
            padding: 1.75rem;
            margin-bottom: 1.5rem;
            border: 1px solid var(--border-color);
            transition: all 0.2s;
        }

        .manual-category:hover {
            border-color: var(--primary-color);
            box-shadow: 0 4px 12px rgba(88, 101, 242, 0.1);
        }

        .manual-category-header {
            display: flex;
            align-items: center;
            margin-bottom: 1.25rem;
        }

        .manual-category-icon {
            width: 44px;
            height: 44px;
            background: var(--primary-color);
            color: white;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 1rem;
            font-size: 1.25rem;
        }

        .manual-category-info {
            flex: 1;
        }

        .manual-category-name {
            font-size: 1.25rem;
            font-weight: 700;
            color: var(--text-primary);
            margin-bottom: 0.125rem;
        }

        .manual-category-count {
            font-size: 0.8125rem;
            color: var(--text-secondary);
        }

        /* Article List */
        .manual-articles {
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
        }

        .manual-article {
            display: flex;
            align-items: center;
            padding: 0.75rem 1rem;
            border-radius: 8px;
            color: var(--text-primary);
            transition: all 0.2s;
        }

        .manual-article:hover {
            background: rgba(88, 101, 242, 0.05);
            color: var(--primary-color);
            text-decoration: none;
        }

        .manual-article-icon {
            color: var(--text-light);
            margin-right: 0.75rem;
            font-size: 1.125rem;
        }

        .manual-article:hover .manual-article-icon {
            color: var(--primary-color);
        }

        .manual-article-title {
            font-weight: 500;
        }

        .manual-article-arrow {
            margin-left: auto;
            color: var(--text-light);
            font-size: 1rem;
            opacity: 0;
            transform: translateX(-4px);
            transition: all 0.2s;
        }

        .manual-article:hover .manual-article-arrow {
            opacity: 1;
            transform: translateX(0);
        }

        /* Empty State */
        .manual-empty {
            text-align: center;
            padding: 4rem 2rem;
            background: var(--bg-card);
            border-radius: 12px;
            border: 1px solid var(--border-color);
        }

        .manual-empty-icon {
            font-size: 4rem;
            color: var(--border-color);
            margin-bottom: 1rem;
        }

        .manual-empty-title {
            font-size: 1.25rem;
            font-weight: 600;
            color: var(--text-primary);
            margin-bottom: 0.5rem;
        }

        .manual-empty-text {
            color: var(--text-secondary);
        }

        /* Footer */
        .manual-footer {
            text-align: center;
            padding: 2rem;
            color: var(--text-secondary);
            font-size: 0.875rem;
            border-top: 1px solid var(--border-color);
            margin-top: 2rem;
        }

        /* Responsive */
        @media (max-width: 640px) {
            .manual-title {
                font-size: 1.75rem;
            }

            .manual-main {
                padding: 2rem 1rem;
            }

            .manual-category {
                padding: 1.25rem;
            }
        }
    </style>
</head>
<body>
    <!-- Header -->
    <header class="manual-header">
        <div class="manual-header-inner">
            <div class="manual-brand">
                @if($settings->logo)
                    <img src="{{ asset('storage/' . $settings->logo) }}" alt="{{ $settings->product_name }}" class="manual-brand-logo">
                @else
                    <div class="manual-brand-logo" style="display: flex; align-items: center; justify-content: center; color: white; font-weight: 700; font-size: 1.25rem;">
                        {{ strtoupper(substr($settings->product_name ?? 'D', 0, 1)) }}
                    </div>
                @endif
                <span class="manual-brand-name">{{ $settings->product_name ?? 'Documentation' }}</span>
            </div>

            <h1 class="manual-title">{{ $knowledgeBoard->name }}</h1>
            <p class="manual-description">{{ $knowledgeBoard->short_description }}</p>

            <div class="manual-search">
                <i class="ti ti-search manual-search-icon"></i>
                <input type="text" class="manual-search-input" id="searchInput" placeholder="Search documentation..." autocomplete="off">
                <div class="manual-search-results" id="searchResults"></div>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="manual-main">
        <a href="{{ route('public.knowledge', $settings->unique_url) }}" class="manual-back">
            <i class="ti ti-arrow-left"></i>
            Back to all documentation
        </a>

        @php
            $hasArticles = false;
            foreach($articles as $categoryArticles) {
                if($categoryArticles->count() > 0) {
                    $hasArticles = true;
                    break;
                }
            }
        @endphp

        @if($hasArticles)
            @foreach($categories as $category)
                @if(isset($articles[$category->id]) && $articles[$category->id]->count() > 0)
                    <div class="manual-category">
                        <div class="manual-category-header">
                            <div class="manual-category-icon">
                                <i class="{{ $category->category_icon ?: 'ti ti-folder' }}"></i>
                            </div>
                            <div class="manual-category-info">
                                <h2 class="manual-category-name">{{ $category->category_name }}</h2>
                                <span class="manual-category-count">{{ $articles[$category->id]->count() }} articles</span>
                            </div>
                        </div>

                        <div class="manual-articles">
                            @foreach($articles[$category->id] as $article)
                                <a href="{{ route('public.knowledge.article', [$settings->unique_url, $knowledgeBoard->id, $article->id]) }}" class="manual-article">
                                    <i class="ti ti-file-text manual-article-icon"></i>
                                    <span class="manual-article-title">{{ $article->article_title }}</span>
                                    <i class="ti ti-chevron-right manual-article-arrow"></i>
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endif

                {{-- Child categories --}}
                @foreach($category->childCategories as $childCategory)
                    @if(isset($articles[$childCategory->id]) && $articles[$childCategory->id]->count() > 0)
                        <div class="manual-category">
                            <div class="manual-category-header">
                                <div class="manual-category-icon">
                                    <i class="{{ $childCategory->category_icon ?: 'ti ti-folder' }}"></i>
                                </div>
                                <div class="manual-category-info">
                                    <h2 class="manual-category-name">{{ $childCategory->category_name }}</h2>
                                    <span class="manual-category-count">{{ $articles[$childCategory->id]->count() }} articles</span>
                                </div>
                            </div>

                            <div class="manual-articles">
                                @foreach($articles[$childCategory->id] as $article)
                                    <a href="{{ route('public.knowledge.article', [$settings->unique_url, $knowledgeBoard->id, $article->id]) }}" class="manual-article">
                                        <i class="ti ti-file-text manual-article-icon"></i>
                                        <span class="manual-article-title">{{ $article->article_title }}</span>
                                        <i class="ti ti-chevron-right manual-article-arrow"></i>
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    @endif
                @endforeach
            @endforeach
        @else
            <div class="manual-empty">
                <i class="ti ti-files-off manual-empty-icon"></i>
                <h3 class="manual-empty-title">No documentation yet</h3>
                <p class="manual-empty-text">Check back later for helpful guides and documentation!</p>
            </div>
        @endif
    </main>

    <!-- Footer -->
    <footer class="manual-footer">
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
                                <a href="${item.url}" class="manual-search-result-item">
                                    <div class="manual-search-result-category">${item.category}</div>
                                    <div class="manual-search-result-title">${item.title}</div>
                                    <div class="manual-search-result-excerpt">${item.excerpt}</div>
                                </a>
                            `).join('');
                        } else {
                            searchResults.innerHTML = '<div class="manual-search-no-results">No results found</div>';
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
            if (!e.target.closest('.manual-search')) {
                searchResults.classList.remove('active');
            }
        });
    </script>
</body>
</html>
