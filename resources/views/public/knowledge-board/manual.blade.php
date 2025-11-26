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
            --header-bg: #11939A;
            --sidebar-bg: #ffffff;
            --sidebar-width: 300px;
            --content-bg: #ffffff;
            --text-primary: #000000;
            --text-secondary: #545454;
            --text-light: #757575;
            --accent-color: #11939A;
            --accent-hover: #0d7a80;
            --border-color: #e5e5e5;
            --hover-bg: #f5f5f5;
            --code-bg: #2d2d2d;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            background-color: var(--content-bg);
            color: var(--text-primary);
            line-height: 1.6;
        }

        a {
            color: var(--accent-color);
            text-decoration: none;
        }

        a:hover {
            color: var(--accent-hover);
        }

        /* Header */
        .uber-header {
            background: var(--header-bg);
            height: 70px;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 100;
            display: flex;
            align-items: center;
            padding: 0 24px;
        }

        .uber-header-brand {
            display: flex;
            align-items: center;
            gap: 12px;
            text-decoration: none;
        }

        .uber-header-logo {
            width: 36px;
            height: 36px;
            border-radius: 6px;
            object-fit: contain;
            background: rgba(255,255,255,0.1);
        }

        .uber-header-title {
            font-size: 18px;
            font-weight: 700;
            color: white;
        }

        .uber-header-nav {
            margin-left: 40px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .uber-header-nav-link {
            color: rgba(255,255,255,0.8);
            font-size: 14px;
            font-weight: 500;
            padding: 8px 16px;
            border-radius: 4px;
            transition: all 0.2s;
        }

        .uber-header-nav-link:hover {
            color: white;
            background: rgba(255,255,255,0.1);
        }

        .uber-header-nav-link.active {
            color: white;
        }

        .uber-header-search {
            margin-left: auto;
            position: relative;
            width: 280px;
        }

        .uber-header-search-input {
            width: 100%;
            padding: 10px 16px 10px 40px;
            border: 1px solid rgba(255,255,255,0.2);
            border-radius: 8px;
            font-size: 14px;
            background: rgba(255,255,255,0.05);
            color: white;
            transition: all 0.2s;
        }

        .uber-header-search-input:focus {
            outline: none;
            border-color: rgba(255,255,255,0.4);
            background: rgba(255,255,255,0.1);
        }

        .uber-header-search-input::placeholder {
            color: rgba(255,255,255,0.5);
        }

        .uber-header-search-icon {
            position: absolute;
            left: 14px;
            top: 50%;
            transform: translateY(-50%);
            color: rgba(255,255,255,0.5);
            font-size: 16px;
        }

        /* Search Results Dropdown */
        .uber-search-results {
            position: absolute;
            top: 100%;
            left: 0;
            right: 0;
            background: white;
            border-radius: 8px;
            box-shadow: 0 8px 32px rgba(0,0,0,0.2);
            margin-top: 8px;
            max-height: 400px;
            overflow-y: auto;
            display: none;
            z-index: 1000;
        }

        .uber-search-results.active {
            display: block;
        }

        .uber-search-result-item {
            padding: 12px 16px;
            border-bottom: 1px solid var(--border-color);
            display: block;
            color: var(--text-primary);
        }

        .uber-search-result-item:last-child {
            border-bottom: none;
        }

        .uber-search-result-item:hover {
            background: var(--hover-bg);
        }

        .uber-search-result-category {
            font-size: 11px;
            color: var(--accent-color);
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 2px;
        }

        .uber-search-result-title {
            font-weight: 600;
            color: var(--text-primary);
            font-size: 14px;
        }

        .uber-search-result-excerpt {
            font-size: 12px;
            color: var(--text-secondary);
            margin-top: 2px;
        }

        .uber-search-no-results {
            padding: 20px;
            text-align: center;
            color: var(--text-secondary);
            font-size: 14px;
        }

        /* Layout */
        .uber-layout {
            display: flex;
            padding-top: 70px;
            min-height: calc(100vh - 70px - 100px);
        }

        /* Sidebar */
        .uber-sidebar {
            width: var(--sidebar-width);
            background: var(--sidebar-bg);
            border-right: 1px solid var(--border-color);
            flex-shrink: 0;
            padding: 24px 0;
            min-height: 100%;
        }

        .uber-sidebar-section {
            margin-bottom: 8px;
        }

        .uber-sidebar-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 10px 24px;
            cursor: pointer;
            user-select: none;
            transition: background 0.2s;
        }

        .uber-sidebar-header:hover {
            background: var(--hover-bg);
        }

        .uber-sidebar-title {
            font-size: 14px;
            font-weight: 600;
            color: var(--text-primary);
        }

        .uber-sidebar-chevron {
            font-size: 14px;
            color: var(--text-light);
            transition: transform 0.2s;
        }

        .uber-sidebar-section.collapsed .uber-sidebar-chevron {
            transform: rotate(-90deg);
        }

        .uber-sidebar-section.collapsed .uber-sidebar-items {
            display: none;
        }

        .uber-sidebar-items {
            padding: 0;
        }

        .uber-sidebar-item {
            display: block;
            padding: 8px 24px 8px 40px;
            font-size: 14px;
            color: var(--text-secondary);
            transition: all 0.2s;
            border-left: 3px solid transparent;
        }

        .uber-sidebar-item:hover {
            color: var(--text-primary);
            background: var(--hover-bg);
        }

        .uber-sidebar-item.active {
            color: var(--accent-color);
            border-left-color: var(--accent-color);
            background: rgba(17, 147, 154, 0.05);
        }

        /* Child category items */
        .uber-sidebar-child-section {
            margin-left: 16px;
        }

        .uber-sidebar-child-header {
            padding: 8px 24px 8px 40px;
            font-size: 13px;
            font-weight: 500;
            color: var(--text-light);
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .uber-sidebar-child-header:hover {
            color: var(--text-primary);
        }

        .uber-sidebar-child-items .uber-sidebar-item {
            padding-left: 56px;
            font-size: 13px;
        }

        /* Main Content */
        .uber-main {
            flex: 1;
            padding: 48px 64px;
            max-width: 900px;
        }

        .uber-breadcrumb {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 13px;
            color: var(--text-light);
            margin-bottom: 24px;
        }

        .uber-breadcrumb a {
            color: var(--text-light);
        }

        .uber-breadcrumb a:hover {
            color: var(--accent-color);
        }

        .uber-breadcrumb-separator {
            font-size: 12px;
        }

        .uber-content-title {
            font-size: 36px;
            font-weight: 700;
            color: var(--text-primary);
            margin-bottom: 16px;
            letter-spacing: -0.5px;
        }

        .uber-content-description {
            font-size: 18px;
            color: var(--text-secondary);
            margin-bottom: 40px;
            line-height: 1.6;
        }

        /* Category Cards */
        .uber-categories {
            display: flex;
            flex-direction: column;
            gap: 32px;
        }

        .uber-category {
            border-bottom: 1px solid var(--border-color);
            padding-bottom: 32px;
        }

        .uber-category:last-child {
            border-bottom: none;
        }

        .uber-category-header {
            margin-bottom: 16px;
        }

        .uber-category-name {
            font-size: 24px;
            font-weight: 700;
            color: var(--text-primary);
            margin-bottom: 4px;
        }

        .uber-category-count {
            font-size: 14px;
            color: var(--text-light);
        }

        .uber-category-articles {
            display: flex;
            flex-direction: column;
            gap: 0;
        }

        .uber-article-link {
            display: flex;
            align-items: center;
            padding: 12px 0;
            color: var(--text-primary);
            border-bottom: 1px solid var(--border-color);
            transition: all 0.2s;
        }

        .uber-article-link:last-child {
            border-bottom: none;
        }

        .uber-article-link:hover {
            color: var(--accent-color);
        }

        .uber-article-link:hover .uber-article-arrow {
            transform: translateX(4px);
            opacity: 1;
        }

        .uber-article-title {
            font-size: 16px;
            font-weight: 500;
        }

        .uber-article-arrow {
            margin-left: auto;
            font-size: 16px;
            color: var(--accent-color);
            opacity: 0;
            transition: all 0.2s;
        }

        /* Empty State */
        .uber-empty {
            text-align: center;
            padding: 80px 40px;
            background: var(--hover-bg);
            border-radius: 8px;
        }

        .uber-empty-icon {
            font-size: 48px;
            color: var(--border-color);
            margin-bottom: 16px;
        }

        .uber-empty-title {
            font-size: 20px;
            font-weight: 600;
            color: var(--text-primary);
            margin-bottom: 8px;
        }

        .uber-empty-text {
            color: var(--text-secondary);
            font-size: 14px;
        }

        /* Footer */
        .uber-footer {
            background: var(--header-bg);
            color: rgba(255,255,255,0.7);
            padding: 40px 64px;
            font-size: 14px;
            text-align: center;
        }

        /* Mobile Sidebar Toggle */
        .uber-mobile-toggle {
            display: none;
            background: none;
            border: none;
            color: white;
            font-size: 24px;
            cursor: pointer;
            padding: 8px;
            margin-right: 16px;
        }

        /* Responsive */
        @media (max-width: 1024px) {
            .uber-main {
                padding: 32px;
            }
        }

        @media (max-width: 768px) {
            .uber-mobile-toggle {
                display: block;
            }

            .uber-header-nav {
                display: none;
            }

            .uber-header-search {
                width: 200px;
            }

            .uber-layout {
                flex-direction: column;
            }

            .uber-sidebar {
                position: fixed;
                top: 70px;
                left: 0;
                bottom: 0;
                transform: translateX(-100%);
                transition: transform 0.3s;
                z-index: 99;
                background: var(--sidebar-bg);
            }

            .uber-sidebar.open {
                transform: translateX(0);
            }

            .uber-main {
                padding: 24px 16px;
            }

            .uber-footer {
                padding: 24px 16px;
            }

            .uber-content-title {
                font-size: 28px;
            }

            .uber-category-name {
                font-size: 20px;
            }
        }
    </style>
</head>
<body>
    <!-- Header -->
    <header class="uber-header">
        <button class="uber-mobile-toggle" id="mobileToggle">
            <i class="ti ti-menu-2"></i>
        </button>

        <a href="{{ route('public.knowledge', $settings->unique_url) }}" class="uber-header-brand">
            @if($settings->logo)
                <img src="{{ asset('storage/' . $settings->logo) }}" alt="{{ $settings->product_name }}" class="uber-header-logo">
            @else
                <div class="uber-header-logo" style="display: flex; align-items: center; justify-content: center; color: white; font-weight: 700;">
                    {{ strtoupper(substr($settings->product_name ?? 'D', 0, 1)) }}
                </div>
            @endif
            <span class="uber-header-title">{{ $settings->product_name ?? 'Docs' }}</span>
        </a>

        <nav class="uber-header-nav">
            <a href="{{ route('public.knowledge.show', [$settings->unique_url, $knowledgeBoard->id]) }}" class="uber-header-nav-link active">Docs</a>
        </nav>

        <div class="uber-header-search">
            <i class="ti ti-search uber-header-search-icon"></i>
            <input type="text" class="uber-header-search-input" id="searchInput" placeholder="Search..." autocomplete="off">
            <div class="uber-search-results" id="searchResults"></div>
        </div>
    </header>

    <!-- Layout -->
    <div class="uber-layout">
        <!-- Sidebar -->
        <aside class="uber-sidebar" id="sidebar">
            @foreach($categories as $category)
                @if(isset($articles[$category->id]) && $articles[$category->id]->count() > 0)
                    <div class="uber-sidebar-section">
                        <div class="uber-sidebar-header" onclick="toggleSection(this)">
                            <span class="uber-sidebar-title">{{ $category->category_name }}</span>
                            <i class="ti ti-chevron-down uber-sidebar-chevron"></i>
                        </div>
                        <div class="uber-sidebar-items">
                            @foreach($articles[$category->id] as $article)
                                <a href="{{ route('public.knowledge.article', [$settings->unique_url, $knowledgeBoard->id, $article->id]) }}" class="uber-sidebar-item">
                                    {{ $article->article_title }}
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endif

                {{-- Child categories --}}
                @foreach($category->childCategories as $childCategory)
                    @if(isset($articles[$childCategory->id]) && $articles[$childCategory->id]->count() > 0)
                        <div class="uber-sidebar-section uber-sidebar-child-section">
                            <div class="uber-sidebar-header" onclick="toggleSection(this)">
                                <span class="uber-sidebar-title">{{ $childCategory->category_name }}</span>
                                <i class="ti ti-chevron-down uber-sidebar-chevron"></i>
                            </div>
                            <div class="uber-sidebar-items uber-sidebar-child-items">
                                @foreach($articles[$childCategory->id] as $article)
                                    <a href="{{ route('public.knowledge.article', [$settings->unique_url, $knowledgeBoard->id, $article->id]) }}" class="uber-sidebar-item">
                                        {{ $article->article_title }}
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    @endif
                @endforeach
            @endforeach
        </aside>

        <!-- Main Content -->
        <main class="uber-main">
            <a href="{{ route('public.knowledge', $settings->unique_url) }}" style="display: inline-flex; align-items: center; gap: 8px; padding: 10px 20px; background: var(--hover-bg); border: 1px solid var(--border-color); border-radius: 8px; color: var(--text-primary); font-size: 14px; font-weight: 500; margin-bottom: 24px; transition: all 0.2s;" onmouseover="this.style.background='var(--accent-color)'; this.style.color='white'; this.style.borderColor='var(--accent-color)';" onmouseout="this.style.background='var(--hover-bg)'; this.style.color='var(--text-primary)'; this.style.borderColor='var(--border-color)';">
                <i class="ti ti-arrow-left"></i>
                Back to Knowledge Board
            </a>

            <nav class="uber-breadcrumb">
                <a href="{{ route('public.knowledge', $settings->unique_url) }}">Documentation</a>
                <i class="ti ti-chevron-right uber-breadcrumb-separator"></i>
                <span>{{ $knowledgeBoard->name }}</span>
            </nav>

            <h1 class="uber-content-title">{{ $knowledgeBoard->name }}</h1>
            @if($knowledgeBoard->short_description)
                <p class="uber-content-description">{{ $knowledgeBoard->short_description }}</p>
            @endif

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
                <div class="uber-categories">
                    @foreach($categories as $category)
                        @if(isset($articles[$category->id]) && $articles[$category->id]->count() > 0)
                            <div class="uber-category">
                                <div class="uber-category-header">
                                    <h2 class="uber-category-name">{{ $category->category_name }}</h2>
                                    <span class="uber-category-count">{{ $articles[$category->id]->count() }} {{ Str::plural('article', $articles[$category->id]->count()) }}</span>
                                </div>
                                <div class="uber-category-articles">
                                    @foreach($articles[$category->id] as $article)
                                        <a href="{{ route('public.knowledge.article', [$settings->unique_url, $knowledgeBoard->id, $article->id]) }}" class="uber-article-link">
                                            <span class="uber-article-title">{{ $article->article_title }}</span>
                                            <i class="ti ti-arrow-right uber-article-arrow"></i>
                                        </a>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        {{-- Child categories --}}
                        @foreach($category->childCategories as $childCategory)
                            @if(isset($articles[$childCategory->id]) && $articles[$childCategory->id]->count() > 0)
                                <div class="uber-category">
                                    <div class="uber-category-header">
                                        <h2 class="uber-category-name">{{ $childCategory->category_name }}</h2>
                                        <span class="uber-category-count">{{ $articles[$childCategory->id]->count() }} {{ Str::plural('article', $articles[$childCategory->id]->count()) }}</span>
                                    </div>
                                    <div class="uber-category-articles">
                                        @foreach($articles[$childCategory->id] as $article)
                                            <a href="{{ route('public.knowledge.article', [$settings->unique_url, $knowledgeBoard->id, $article->id]) }}" class="uber-article-link">
                                                <span class="uber-article-title">{{ $article->article_title }}</span>
                                                <i class="ti ti-arrow-right uber-article-arrow"></i>
                                            </a>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        @endforeach
                    @endforeach
                </div>
            @else
                <div class="uber-empty">
                    <i class="ti ti-files-off uber-empty-icon"></i>
                    <h3 class="uber-empty-title">No documentation yet</h3>
                    <p class="uber-empty-text">Check back later for helpful guides and documentation!</p>
                </div>
            @endif
        </main>
    </div>

    <!-- Footer -->
    <footer class="uber-footer">
        <p>&copy; {{ date('Y') }} {{ $settings->product_name ?? 'Documentation' }}. All rights reserved.</p>
    </footer>

    <script>
        // Toggle sidebar sections
        function toggleSection(header) {
            const section = header.parentElement;
            section.classList.toggle('collapsed');
        }

        // Mobile sidebar toggle
        const mobileToggle = document.getElementById('mobileToggle');
        const sidebar = document.getElementById('sidebar');

        mobileToggle.addEventListener('click', function() {
            sidebar.classList.toggle('open');
        });

        // Close sidebar when clicking outside on mobile
        document.addEventListener('click', function(e) {
            if (window.innerWidth <= 768) {
                if (!e.target.closest('.uber-sidebar') && !e.target.closest('.uber-mobile-toggle')) {
                    sidebar.classList.remove('open');
                }
            }
        });

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
                                <a href="${item.url}" class="uber-search-result-item">
                                    <div class="uber-search-result-category">${item.category}</div>
                                    <div class="uber-search-result-title">${item.title}</div>
                                    <div class="uber-search-result-excerpt">${item.excerpt}</div>
                                </a>
                            `).join('');
                        } else {
                            searchResults.innerHTML = '<div class="uber-search-no-results">No results found</div>';
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
            if (!e.target.closest('.uber-header-search')) {
                searchResults.classList.remove('active');
            }
        });
    </script>
</body>
</html>
