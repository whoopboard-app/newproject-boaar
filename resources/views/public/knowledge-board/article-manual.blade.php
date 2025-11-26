<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $theme->meta_title ?? $article->article_title . ' - ' . $knowledgeBoard->name }}</title>
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
            --header-bg: {{ $theme->header_background_color ?? '#11939A' }};
            --header-text: {{ $theme->header_text_color ?? '#FFFFFF' }};
            --footer-bg: {{ $theme->footer_background_color ?? '#11939A' }};
            --footer-text: {{ $theme->footer_text_color ?? '#FFFFFF' }};
            --sidebar-bg: #ffffff;
            --sidebar-width: 300px;
            --content-bg: #ffffff;
            --text-primary: #000000;
            --text-secondary: #545454;
            --text-light: #757575;
            --accent-color: {{ $theme->header_background_color ?? '#11939A' }};
            --accent-hover: {{ $theme->header_background_color ?? '#11939A' }};
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
            color: var(--header-text);
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
            overflow-y: auto;
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

        /* Article */
        .uber-article {
            background: var(--content-bg);
        }

        .uber-article-header {
            margin-bottom: 32px;
            padding-bottom: 24px;
            border-bottom: 1px solid var(--border-color);
        }

        .uber-article-category {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: var(--accent-color);
            margin-bottom: 12px;
        }

        .uber-article-title {
            font-size: 36px;
            font-weight: 700;
            color: var(--text-primary);
            margin-bottom: 16px;
            letter-spacing: -0.5px;
            line-height: 1.2;
        }

        .uber-article-meta {
            display: flex;
            align-items: center;
            gap: 20px;
            font-size: 14px;
            color: var(--text-light);
        }

        .uber-article-meta-item {
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .uber-article-meta-item i {
            font-size: 16px;
        }

        /* Article Content */
        .uber-article-content {
            font-size: 16px;
            line-height: 1.8;
            color: var(--text-secondary);
        }

        .uber-article-content h1 {
            font-size: 28px;
            font-weight: 700;
            color: var(--text-primary);
            margin: 40px 0 16px;
            letter-spacing: -0.3px;
        }

        .uber-article-content h2 {
            font-size: 24px;
            font-weight: 700;
            color: var(--text-primary);
            margin: 32px 0 12px;
            letter-spacing: -0.3px;
        }

        .uber-article-content h3 {
            font-size: 20px;
            font-weight: 600;
            color: var(--text-primary);
            margin: 24px 0 10px;
        }

        .uber-article-content h4 {
            font-size: 18px;
            font-weight: 600;
            color: var(--text-primary);
            margin: 20px 0 8px;
        }

        .uber-article-content p {
            margin-bottom: 16px;
        }

        .uber-article-content ul,
        .uber-article-content ol {
            margin: 16px 0;
            padding-left: 24px;
        }

        .uber-article-content li {
            margin-bottom: 8px;
        }

        .uber-article-content a {
            color: var(--accent-color);
        }

        .uber-article-content a:hover {
            text-decoration: underline;
        }

        .uber-article-content img {
            max-width: 100%;
            height: auto;
            border-radius: 8px;
            margin: 24px 0;
        }

        .uber-article-content blockquote {
            border-left: 4px solid var(--accent-color);
            padding: 16px 20px;
            margin: 24px 0;
            background: var(--hover-bg);
            color: var(--text-secondary);
            border-radius: 0 8px 8px 0;
        }

        .uber-article-content pre {
            background: var(--code-bg);
            color: #e6e6e6;
            padding: 20px 24px;
            border-radius: 8px;
            overflow-x: auto;
            margin: 24px 0;
            font-family: 'Monaco', 'Menlo', 'Ubuntu Mono', monospace;
            font-size: 14px;
            line-height: 1.6;
        }

        .uber-article-content code {
            background: var(--hover-bg);
            padding: 2px 6px;
            border-radius: 4px;
            font-family: 'Monaco', 'Menlo', 'Ubuntu Mono', monospace;
            font-size: 0.9em;
            color: var(--text-primary);
        }

        .uber-article-content pre code {
            background: none;
            padding: 0;
            color: inherit;
        }

        .uber-article-content table {
            width: 100%;
            border-collapse: collapse;
            margin: 24px 0;
        }

        .uber-article-content th,
        .uber-article-content td {
            border: 1px solid var(--border-color);
            padding: 12px 16px;
            text-align: left;
        }

        .uber-article-content th {
            background: var(--hover-bg);
            font-weight: 600;
            color: var(--text-primary);
        }

        /* Article Navigation */
        .uber-article-nav {
            display: flex;
            justify-content: space-between;
            margin-top: 48px;
            padding-top: 32px;
            border-top: 1px solid var(--border-color);
            gap: 24px;
        }

        .uber-article-nav-link {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 16px 20px;
            background: var(--hover-bg);
            border-radius: 8px;
            color: var(--text-primary);
            max-width: 48%;
            transition: all 0.2s;
        }

        .uber-article-nav-link:hover {
            background: var(--accent-color);
            color: white;
        }

        .uber-article-nav-link--prev {
            flex-direction: row;
        }

        .uber-article-nav-link--next {
            flex-direction: row-reverse;
            text-align: right;
            margin-left: auto;
        }

        .uber-article-nav-content {
            flex: 1;
            min-width: 0;
        }

        .uber-article-nav-label {
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: var(--text-light);
            margin-bottom: 4px;
        }

        .uber-article-nav-link:hover .uber-article-nav-label {
            color: rgba(255,255,255,0.8);
        }

        .uber-article-nav-title {
            font-weight: 600;
            font-size: 15px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .uber-article-nav-icon {
            font-size: 20px;
            flex-shrink: 0;
        }

        /* Footer */
        .uber-footer {
            background: var(--footer-bg);
            color: var(--footer-text);
            padding: 40px 64px;
            font-size: 14px;
            text-align: center;
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

            .uber-article-title {
                font-size: 28px;
            }

            .uber-article-nav {
                flex-direction: column;
            }

            .uber-article-nav-link {
                max-width: 100%;
            }

            .uber-article-nav-link--next {
                flex-direction: row;
                text-align: left;
            }

            .uber-article-meta {
                flex-direction: column;
                align-items: flex-start;
                gap: 8px;
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
                @php
                    $isCurrentCategory = isset($allArticles[$category->id]) && $allArticles[$category->id]->contains('id', $article->id);
                    $hasArticlesInCategory = isset($allArticles[$category->id]) && $allArticles[$category->id]->count() > 0;
                @endphp
                @if($hasArticlesInCategory)
                    <div class="uber-sidebar-section{{ $isCurrentCategory ? '' : ' collapsed' }}">
                        <div class="uber-sidebar-header" onclick="toggleSection(this)">
                            <span class="uber-sidebar-title">{{ $category->category_name }}</span>
                            <i class="ti ti-chevron-down uber-sidebar-chevron"></i>
                        </div>
                        <div class="uber-sidebar-items">
                            @foreach($allArticles[$category->id] as $navArticle)
                                <a href="{{ route('public.knowledge.article', [$settings->unique_url, $knowledgeBoard->id, $navArticle->id]) }}" class="uber-sidebar-item{{ $navArticle->id === $article->id ? ' active' : '' }}">
                                    {{ $navArticle->article_title }}
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endif

                {{-- Child categories --}}
                @foreach($category->childCategories as $childCategory)
                    @php
                        $isCurrentChildCategory = isset($allArticles[$childCategory->id]) && $allArticles[$childCategory->id]->contains('id', $article->id);
                        $hasArticlesInChild = isset($allArticles[$childCategory->id]) && $allArticles[$childCategory->id]->count() > 0;
                    @endphp
                    @if($hasArticlesInChild)
                        <div class="uber-sidebar-section uber-sidebar-child-section{{ $isCurrentChildCategory ? '' : ' collapsed' }}">
                            <div class="uber-sidebar-header" onclick="toggleSection(this)">
                                <span class="uber-sidebar-title">{{ $childCategory->category_name }}</span>
                                <i class="ti ti-chevron-down uber-sidebar-chevron"></i>
                            </div>
                            <div class="uber-sidebar-items uber-sidebar-child-items">
                                @foreach($allArticles[$childCategory->id] as $navArticle)
                                    <a href="{{ route('public.knowledge.article', [$settings->unique_url, $knowledgeBoard->id, $navArticle->id]) }}" class="uber-sidebar-item{{ $navArticle->id === $article->id ? ' active' : '' }}">
                                        {{ $navArticle->article_title }}
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
            <!-- Breadcrumb -->
            <nav class="uber-breadcrumb">
                <a href="{{ route('public.knowledge', $settings->unique_url) }}">Documentation</a>
                <i class="ti ti-chevron-right uber-breadcrumb-separator"></i>
                <a href="{{ route('public.knowledge.show', [$settings->unique_url, $knowledgeBoard->id]) }}">{{ $knowledgeBoard->name }}</a>
                @if($article->boardCategory)
                    <i class="ti ti-chevron-right uber-breadcrumb-separator"></i>
                    <span>{{ $article->boardCategory->category_name }}</span>
                @endif
            </nav>

            <!-- Article -->
            <article class="uber-article">
                <header class="uber-article-header">
                    @if($article->boardCategory)
                        <div class="uber-article-category">
                            <i class="{{ $article->boardCategory->category_icon ?: 'ti ti-folder' }}"></i>
                            {{ $article->boardCategory->category_name }}
                        </div>
                    @endif
                    <h1 class="uber-article-title">{{ $article->article_title }}</h1>
                    <div class="uber-article-meta">
                        <div class="uber-article-meta-item">
                            <i class="ti ti-calendar"></i>
                            {{ $article->created_at->format('M d, Y') }}
                        </div>
                        @if($article->updated_at != $article->created_at)
                            <div class="uber-article-meta-item">
                                <i class="ti ti-refresh"></i>
                                Updated {{ $article->updated_at->format('M d, Y') }}
                            </div>
                        @endif
                    </div>
                </header>

                <div class="uber-article-content">
                    {!! $article->detailed_post !!}
                </div>

                <!-- Rating Widget -->
                @if(isset($ratingSettings))
                    @include('public.knowledge-board.partials.rating-widget')
                @endif

                <!-- Navigation -->
                @if($prevArticle || $nextArticle)
                    <nav class="uber-article-nav">
                        @if($prevArticle)
                            <a href="{{ route('public.knowledge.article', [$settings->unique_url, $knowledgeBoard->id, $prevArticle->id]) }}" class="uber-article-nav-link uber-article-nav-link--prev">
                                <i class="ti ti-arrow-left uber-article-nav-icon"></i>
                                <div class="uber-article-nav-content">
                                    <div class="uber-article-nav-label">Previous</div>
                                    <div class="uber-article-nav-title">{{ Str::limit($prevArticle->article_title, 40) }}</div>
                                </div>
                            </a>
                        @endif

                        @if($nextArticle)
                            <a href="{{ route('public.knowledge.article', [$settings->unique_url, $knowledgeBoard->id, $nextArticle->id]) }}" class="uber-article-nav-link uber-article-nav-link--next">
                                <i class="ti ti-arrow-right uber-article-nav-icon"></i>
                                <div class="uber-article-nav-content">
                                    <div class="uber-article-nav-label">Next</div>
                                    <div class="uber-article-nav-title">{{ Str::limit($nextArticle->article_title, 40) }}</div>
                                </div>
                            </a>
                        @endif
                    </nav>
                @endif
            </article>
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
