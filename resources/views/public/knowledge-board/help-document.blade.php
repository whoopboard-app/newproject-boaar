<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $knowledgeBoard->name }} - {{ $settings->product_name ?? 'Knowledge Base' }}</title>

    <!-- Tabler Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/tabler-icons.min.css">

    <style>
        :root {
            --primary-color: #3197d6;
            --primary-hover: #2980b9;
            --text-primary: #585858;
            --text-secondary: #888;
            --text-dark: #333;
            --bg-page: #f7f9fa;
            --bg-white: #ffffff;
            --border-color: #e1e5e8;
            --sidebar-width: 280px;
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
            color: var(--primary-hover);
            text-decoration: underline;
        }

        /* Header */
        .kb-header {
            background: var(--primary-color);
            padding: 1rem 0;
            position: sticky;
            top: 0;
            z-index: 100;
        }

        .kb-header-inner {
            max-width: 1400px;
            margin: 0 auto;
            padding: 0 2rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 2rem;
        }

        .kb-brand {
            display: flex;
            align-items: center;
            gap: 1rem;
            color: white;
            text-decoration: none;
        }

        .kb-brand:hover {
            color: white;
            text-decoration: none;
        }

        .kb-brand-logo {
            width: 40px;
            height: 40px;
            border-radius: 8px;
            object-fit: contain;
            background: rgba(255,255,255,0.2);
        }

        .kb-brand-text {
            display: flex;
            flex-direction: column;
        }

        .kb-brand-name {
            font-size: 1.125rem;
            font-weight: 600;
        }

        .kb-brand-tagline {
            font-size: 0.75rem;
            opacity: 0.8;
        }

        /* Search */
        .kb-search {
            flex: 1;
            max-width: 500px;
            position: relative;
        }

        .kb-search-input {
            width: 100%;
            padding: 0.75rem 1rem 0.75rem 2.75rem;
            border: none;
            border-radius: 8px;
            font-size: 0.9375rem;
            background: rgba(255,255,255,0.95);
            color: var(--text-primary);
        }

        .kb-search-input:focus {
            outline: none;
            box-shadow: 0 0 0 3px rgba(255,255,255,0.3);
        }

        .kb-search-input::placeholder {
            color: var(--text-secondary);
        }

        .kb-search-icon {
            position: absolute;
            left: 1rem;
            top: 50%;
            transform: translateY(-50%);
            color: var(--text-secondary);
            font-size: 1.125rem;
        }

        /* Search Results Dropdown */
        .kb-search-results {
            position: absolute;
            top: 100%;
            left: 0;
            right: 0;
            background: white;
            border-radius: 8px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.15);
            margin-top: 0.5rem;
            max-height: 400px;
            overflow-y: auto;
            display: none;
            z-index: 1000;
        }

        .kb-search-results.active {
            display: block;
        }

        .kb-search-result-item {
            padding: 1rem;
            border-bottom: 1px solid var(--border-color);
            display: block;
            color: var(--text-primary);
        }

        .kb-search-result-item:last-child {
            border-bottom: none;
        }

        .kb-search-result-item:hover {
            background: var(--bg-page);
            text-decoration: none;
        }

        .kb-search-result-title {
            font-weight: 600;
            color: var(--text-dark);
            margin-bottom: 0.25rem;
        }

        .kb-search-result-category {
            font-size: 0.75rem;
            color: var(--primary-color);
            margin-bottom: 0.25rem;
        }

        .kb-search-result-excerpt {
            font-size: 0.8125rem;
            color: var(--text-secondary);
        }

        .kb-search-no-results {
            padding: 1.5rem;
            text-align: center;
            color: var(--text-secondary);
        }

        /* Layout */
        .kb-layout {
            display: flex;
            max-width: 1400px;
            margin: 0 auto;
            min-height: calc(100vh - 70px);
        }

        /* Sidebar */
        .kb-sidebar {
            width: var(--sidebar-width);
            background: var(--bg-white);
            border-right: 1px solid var(--border-color);
            padding: 1.5rem 0;
            position: sticky;
            top: 70px;
            height: calc(100vh - 70px);
            overflow-y: auto;
        }

        .kb-sidebar-title {
            font-size: 0.6875rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: var(--text-secondary);
            padding: 0 1.5rem;
            margin-bottom: 0.75rem;
        }

        .kb-nav-category {
            margin-bottom: 0.5rem;
        }

        .kb-nav-category-header {
            display: flex;
            align-items: center;
            padding: 0.5rem 1.5rem;
            cursor: pointer;
            color: var(--text-dark);
            font-weight: 500;
            font-size: 0.9375rem;
            transition: background 0.2s;
        }

        .kb-nav-category-header:hover {
            background: var(--bg-page);
        }

        .kb-nav-category-icon {
            margin-right: 0.5rem;
            font-size: 1.125rem;
            color: var(--text-secondary);
        }

        .kb-nav-category-toggle {
            margin-left: auto;
            font-size: 0.875rem;
            color: var(--text-secondary);
            transition: transform 0.2s;
        }

        .kb-nav-category.expanded .kb-nav-category-toggle {
            transform: rotate(90deg);
        }

        .kb-nav-articles {
            display: none;
            padding-left: 2.5rem;
        }

        .kb-nav-category.expanded .kb-nav-articles {
            display: block;
        }

        .kb-nav-article {
            display: block;
            padding: 0.375rem 1.5rem 0.375rem 1rem;
            color: var(--text-primary);
            font-size: 0.875rem;
            border-left: 2px solid transparent;
            transition: all 0.2s;
        }

        .kb-nav-article:hover {
            color: var(--primary-color);
            background: var(--bg-page);
            text-decoration: none;
        }

        .kb-nav-article.active {
            color: var(--primary-color);
            border-left-color: var(--primary-color);
            background: rgba(49, 151, 214, 0.05);
        }

        .kb-nav-count {
            font-size: 0.75rem;
            color: var(--text-secondary);
            margin-left: 0.5rem;
        }

        /* Main Content */
        .kb-main {
            flex: 1;
            padding: 2rem 3rem;
            max-width: calc(100% - var(--sidebar-width));
        }

        .kb-page-title {
            font-size: 1.75rem;
            font-weight: 700;
            color: var(--text-dark);
            margin-bottom: 0.5rem;
        }

        .kb-page-description {
            color: var(--text-secondary);
            font-size: 1rem;
            margin-bottom: 2rem;
        }

        /* Category Section */
        .kb-category-section {
            margin-bottom: 2.5rem;
        }

        .kb-category-header {
            display: flex;
            align-items: center;
            margin-bottom: 1rem;
            padding-bottom: 0.75rem;
            border-bottom: 2px solid var(--border-color);
        }

        .kb-category-icon {
            width: 36px;
            height: 36px;
            background: var(--primary-color);
            color: white;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 0.75rem;
            font-size: 1.125rem;
        }

        .kb-category-name {
            font-size: 1.25rem;
            font-weight: 600;
            color: var(--text-dark);
        }

        .kb-category-count {
            margin-left: auto;
            font-size: 0.875rem;
            color: var(--text-secondary);
        }

        /* Article List */
        .kb-article-list {
            display: flex;
            flex-direction: column;
            gap: 0.75rem;
        }

        .kb-article-item {
            display: flex;
            align-items: flex-start;
            padding: 1rem 1.25rem;
            background: var(--bg-white);
            border: 1px solid var(--border-color);
            border-radius: 8px;
            transition: all 0.2s;
        }

        .kb-article-item:hover {
            border-color: var(--primary-color);
            box-shadow: 0 2px 8px rgba(49, 151, 214, 0.1);
            text-decoration: none;
        }

        .kb-article-icon {
            color: var(--text-secondary);
            margin-right: 0.75rem;
            margin-top: 0.125rem;
            font-size: 1.125rem;
        }

        .kb-article-content {
            flex: 1;
        }

        .kb-article-title {
            font-weight: 600;
            color: var(--text-dark);
            margin-bottom: 0.25rem;
            font-size: 0.9375rem;
        }

        .kb-article-item:hover .kb-article-title {
            color: var(--primary-color);
        }

        .kb-article-excerpt {
            font-size: 0.8125rem;
            color: var(--text-secondary);
            line-height: 1.5;
        }

        /* Empty State */
        .kb-empty {
            text-align: center;
            padding: 4rem 2rem;
            background: var(--bg-white);
            border-radius: 12px;
            border: 1px solid var(--border-color);
        }

        .kb-empty-icon {
            font-size: 4rem;
            color: var(--border-color);
            margin-bottom: 1rem;
        }

        .kb-empty-title {
            font-size: 1.25rem;
            font-weight: 600;
            color: var(--text-dark);
            margin-bottom: 0.5rem;
        }

        .kb-empty-text {
            color: var(--text-secondary);
        }

        /* Footer */
        .kb-footer {
            text-align: center;
            padding: 2rem;
            color: var(--text-secondary);
            font-size: 0.875rem;
            border-top: 1px solid var(--border-color);
            background: var(--bg-white);
        }

        /* Responsive */
        @media (max-width: 900px) {
            .kb-layout {
                flex-direction: column;
            }

            .kb-sidebar {
                width: 100%;
                position: relative;
                top: 0;
                height: auto;
                border-right: none;
                border-bottom: 1px solid var(--border-color);
            }

            .kb-main {
                max-width: 100%;
                padding: 1.5rem;
            }

            .kb-header-inner {
                flex-direction: column;
                gap: 1rem;
            }

            .kb-search {
                max-width: 100%;
            }
        }
    </style>
</head>
<body>
    <!-- Header -->
    <header class="kb-header">
        <div class="kb-header-inner">
            <a href="{{ route('public.knowledge', $settings->unique_url) }}" class="kb-brand">
                @if($settings->logo)
                    <img src="{{ asset('storage/' . $settings->logo) }}" alt="{{ $settings->product_name }}" class="kb-brand-logo">
                @else
                    <div class="kb-brand-logo" style="display: flex; align-items: center; justify-content: center; color: white; font-weight: 700;">
                        {{ strtoupper(substr($settings->product_name ?? 'K', 0, 1)) }}
                    </div>
                @endif
                <div class="kb-brand-text">
                    <span class="kb-brand-name">{{ $knowledgeBoard->name }}</span>
                    <span class="kb-brand-tagline">{{ $settings->product_name ?? 'Knowledge Base' }}</span>
                </div>
            </a>

            <div class="kb-search">
                <i class="ti ti-search kb-search-icon"></i>
                <input type="text" class="kb-search-input" id="searchInput" placeholder="Search articles..." autocomplete="off">
                <div class="kb-search-results" id="searchResults"></div>
            </div>
        </div>
    </header>

    <!-- Layout -->
    <div class="kb-layout">
        <!-- Sidebar -->
        <aside class="kb-sidebar">
            <div class="kb-sidebar-title">Categories</div>

            @foreach($categories as $category)
                <div class="kb-nav-category{{ isset($articles[$category->id]) && $articles[$category->id]->count() > 0 ? ' expanded' : '' }}">
                    <div class="kb-nav-category-header" onclick="toggleCategory(this)">
                        <i class="{{ $category->category_icon ?: 'ti ti-folder' }} kb-nav-category-icon"></i>
                        <span>{{ $category->category_name }}</span>
                        @if(isset($articles[$category->id]))
                            <span class="kb-nav-count">({{ $articles[$category->id]->count() }})</span>
                        @endif
                        <i class="ti ti-chevron-right kb-nav-category-toggle"></i>
                    </div>
                    <div class="kb-nav-articles">
                        @if(isset($articles[$category->id]))
                            @foreach($articles[$category->id] as $article)
                                <a href="{{ route('public.knowledge.article', [$settings->unique_url, $knowledgeBoard->id, $article->id]) }}" class="kb-nav-article">
                                    {{ $article->article_title }}
                                </a>
                            @endforeach
                        @endif
                    </div>
                </div>

                {{-- Child categories --}}
                @foreach($category->childCategories as $childCategory)
                    <div class="kb-nav-category" style="margin-left: 1rem;">
                        <div class="kb-nav-category-header" onclick="toggleCategory(this)">
                            <i class="{{ $childCategory->category_icon ?: 'ti ti-folder' }} kb-nav-category-icon"></i>
                            <span>{{ $childCategory->category_name }}</span>
                            @if(isset($articles[$childCategory->id]))
                                <span class="kb-nav-count">({{ $articles[$childCategory->id]->count() }})</span>
                            @endif
                            <i class="ti ti-chevron-right kb-nav-category-toggle"></i>
                        </div>
                        <div class="kb-nav-articles">
                            @if(isset($articles[$childCategory->id]))
                                @foreach($articles[$childCategory->id] as $article)
                                    <a href="{{ route('public.knowledge.article', [$settings->unique_url, $knowledgeBoard->id, $article->id]) }}" class="kb-nav-article">
                                        {{ $article->article_title }}
                                    </a>
                                @endforeach
                            @endif
                        </div>
                    </div>
                @endforeach
            @endforeach
        </aside>

        <!-- Main Content -->
        <main class="kb-main">
            <h1 class="kb-page-title">{{ $knowledgeBoard->name }}</h1>
            <p class="kb-page-description">{{ $knowledgeBoard->short_description }}</p>

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
                        <section class="kb-category-section" id="category-{{ $category->id }}">
                            <div class="kb-category-header">
                                <div class="kb-category-icon">
                                    <i class="{{ $category->category_icon ?: 'ti ti-folder' }}"></i>
                                </div>
                                <h2 class="kb-category-name">{{ $category->category_name }}</h2>
                                <span class="kb-category-count">{{ $articles[$category->id]->count() }} articles</span>
                            </div>

                            <div class="kb-article-list">
                                @foreach($articles[$category->id] as $article)
                                    <a href="{{ route('public.knowledge.article', [$settings->unique_url, $knowledgeBoard->id, $article->id]) }}" class="kb-article-item">
                                        <i class="ti ti-file-text kb-article-icon"></i>
                                        <div class="kb-article-content">
                                            <div class="kb-article-title">{{ $article->article_title }}</div>
                                            <div class="kb-article-excerpt">{{ Str::limit(strip_tags($article->detailed_post), 150) }}</div>
                                        </div>
                                    </a>
                                @endforeach
                            </div>
                        </section>
                    @endif

                    {{-- Child categories --}}
                    @foreach($category->childCategories as $childCategory)
                        @if(isset($articles[$childCategory->id]) && $articles[$childCategory->id]->count() > 0)
                            <section class="kb-category-section" id="category-{{ $childCategory->id }}">
                                <div class="kb-category-header">
                                    <div class="kb-category-icon">
                                        <i class="{{ $childCategory->category_icon ?: 'ti ti-folder' }}"></i>
                                    </div>
                                    <h2 class="kb-category-name">{{ $childCategory->category_name }}</h2>
                                    <span class="kb-category-count">{{ $articles[$childCategory->id]->count() }} articles</span>
                                </div>

                                <div class="kb-article-list">
                                    @foreach($articles[$childCategory->id] as $article)
                                        <a href="{{ route('public.knowledge.article', [$settings->unique_url, $knowledgeBoard->id, $article->id]) }}" class="kb-article-item">
                                            <i class="ti ti-file-text kb-article-icon"></i>
                                            <div class="kb-article-content">
                                                <div class="kb-article-title">{{ $article->article_title }}</div>
                                                <div class="kb-article-excerpt">{{ Str::limit(strip_tags($article->detailed_post), 150) }}</div>
                                            </div>
                                        </a>
                                    @endforeach
                                </div>
                            </section>
                        @endif
                    @endforeach
                @endforeach
            @else
                <div class="kb-empty">
                    <i class="ti ti-files-off kb-empty-icon"></i>
                    <h3 class="kb-empty-title">No articles yet</h3>
                    <p class="kb-empty-text">Check back later for helpful documentation!</p>
                </div>
            @endif
        </main>
    </div>

    <!-- Footer -->
    <footer class="kb-footer">
        <p>&copy; {{ date('Y') }} {{ $settings->product_name ?? 'Knowledge Base' }}. All rights reserved.</p>
    </footer>

    <script>
        // Toggle category expansion
        function toggleCategory(element) {
            const category = element.closest('.kb-nav-category');
            category.classList.toggle('expanded');
        }

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
                                <a href="${item.url}" class="kb-search-result-item">
                                    <div class="kb-search-result-category">${item.category}</div>
                                    <div class="kb-search-result-title">${item.title}</div>
                                    <div class="kb-search-result-excerpt">${item.excerpt}</div>
                                </a>
                            `).join('');
                        } else {
                            searchResults.innerHTML = '<div class="kb-search-no-results">No results found</div>';
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
            if (!e.target.closest('.kb-search')) {
                searchResults.classList.remove('active');
            }
        });

        // Focus search on page load
        searchInput.focus();
    </script>
</body>
</html>
