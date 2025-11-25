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

        .kb-search-icon {
            position: absolute;
            left: 1rem;
            top: 50%;
            transform: translateY(-50%);
            color: var(--text-secondary);
            font-size: 1.125rem;
        }

        /* Search Results */
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

        .kb-search-result-item:hover {
            background: var(--bg-page);
            text-decoration: none;
        }

        .kb-search-result-title {
            font-weight: 600;
            color: var(--text-dark);
        }

        .kb-search-result-category {
            font-size: 0.75rem;
            color: var(--primary-color);
        }

        .kb-search-result-excerpt {
            font-size: 0.8125rem;
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
            font-weight: 500;
        }

        /* Main Content */
        .kb-main {
            flex: 1;
            padding: 2rem 3rem;
            max-width: calc(100% - var(--sidebar-width));
        }

        /* Breadcrumb */
        .kb-breadcrumb {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.875rem;
            color: var(--text-secondary);
            margin-bottom: 1.5rem;
        }

        .kb-breadcrumb a {
            color: var(--text-secondary);
        }

        .kb-breadcrumb a:hover {
            color: var(--primary-color);
        }

        .kb-breadcrumb-sep {
            color: var(--border-color);
        }

        /* Article */
        .kb-article {
            background: var(--bg-white);
            border-radius: 12px;
            padding: 2.5rem;
            border: 1px solid var(--border-color);
        }

        .kb-article-header {
            margin-bottom: 2rem;
            padding-bottom: 1.5rem;
            border-bottom: 1px solid var(--border-color);
        }

        .kb-article-category {
            display: inline-flex;
            align-items: center;
            gap: 0.375rem;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: var(--primary-color);
            margin-bottom: 0.75rem;
        }

        .kb-article-title {
            font-size: 2rem;
            font-weight: 700;
            color: var(--text-dark);
            margin-bottom: 1rem;
            line-height: 1.3;
        }

        .kb-article-meta {
            display: flex;
            align-items: center;
            gap: 1.5rem;
            font-size: 0.875rem;
            color: var(--text-secondary);
        }

        .kb-article-meta-item {
            display: flex;
            align-items: center;
            gap: 0.375rem;
        }

        /* Article Content */
        .kb-article-content {
            font-size: 1rem;
            line-height: 1.8;
            color: var(--text-primary);
        }

        .kb-article-content h1,
        .kb-article-content h2,
        .kb-article-content h3,
        .kb-article-content h4,
        .kb-article-content h5,
        .kb-article-content h6 {
            color: var(--text-dark);
            margin-top: 2rem;
            margin-bottom: 1rem;
            font-weight: 600;
        }

        .kb-article-content h2 {
            font-size: 1.5rem;
        }

        .kb-article-content h3 {
            font-size: 1.25rem;
        }

        .kb-article-content p {
            margin-bottom: 1rem;
        }

        .kb-article-content ul,
        .kb-article-content ol {
            margin-bottom: 1rem;
            padding-left: 1.5rem;
        }

        .kb-article-content li {
            margin-bottom: 0.5rem;
        }

        .kb-article-content a {
            color: var(--primary-color);
        }

        .kb-article-content img {
            max-width: 100%;
            height: auto;
            border-radius: 8px;
            margin: 1rem 0;
        }

        .kb-article-content blockquote {
            border-left: 4px solid var(--primary-color);
            padding-left: 1rem;
            margin: 1rem 0;
            color: var(--text-secondary);
            font-style: italic;
        }

        .kb-article-content pre,
        .kb-article-content code {
            background: var(--bg-page);
            border-radius: 4px;
            font-family: 'Monaco', 'Menlo', monospace;
            font-size: 0.875rem;
        }

        .kb-article-content pre {
            padding: 1rem;
            overflow-x: auto;
            margin: 1rem 0;
        }

        .kb-article-content code {
            padding: 0.125rem 0.375rem;
        }

        /* Article Navigation */
        .kb-article-nav {
            display: flex;
            justify-content: space-between;
            margin-top: 2rem;
            padding-top: 2rem;
            border-top: 1px solid var(--border-color);
        }

        .kb-article-nav-link {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            padding: 1rem 1.5rem;
            background: var(--bg-page);
            border-radius: 8px;
            color: var(--text-primary);
            max-width: 45%;
            transition: all 0.2s;
        }

        .kb-article-nav-link:hover {
            background: var(--primary-color);
            color: white;
            text-decoration: none;
        }

        .kb-article-nav-label {
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: var(--text-secondary);
        }

        .kb-article-nav-link:hover .kb-article-nav-label {
            color: rgba(255,255,255,0.8);
        }

        .kb-article-nav-title {
            font-weight: 600;
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

            .kb-article {
                padding: 1.5rem;
            }

            .kb-article-title {
                font-size: 1.5rem;
            }

            .kb-header-inner {
                flex-direction: column;
                gap: 1rem;
            }

            .kb-search {
                max-width: 100%;
            }

            .kb-article-nav {
                flex-direction: column;
                gap: 1rem;
            }

            .kb-article-nav-link {
                max-width: 100%;
            }
        }
    </style>
</head>
<body>
    <!-- Header -->
    <header class="kb-header">
        <div class="kb-header-inner">
            <a href="{{ route('public.knowledge.show', [$settings->unique_url, $knowledgeBoard->id]) }}" class="kb-brand">
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
                @php
                    $isCurrentCategory = isset($allArticles[$category->id]) && $allArticles[$category->id]->contains('id', $article->id);
                @endphp
                <div class="kb-nav-category{{ $isCurrentCategory || (isset($allArticles[$category->id]) && $allArticles[$category->id]->count() > 0) ? ' expanded' : '' }}">
                    <div class="kb-nav-category-header" onclick="toggleCategory(this)">
                        <i class="{{ $category->category_icon ?: 'ti ti-folder' }} kb-nav-category-icon"></i>
                        <span>{{ $category->category_name }}</span>
                        <i class="ti ti-chevron-right kb-nav-category-toggle"></i>
                    </div>
                    <div class="kb-nav-articles">
                        @if(isset($allArticles[$category->id]))
                            @foreach($allArticles[$category->id] as $navArticle)
                                <a href="{{ route('public.knowledge.article', [$settings->unique_url, $knowledgeBoard->id, $navArticle->id]) }}" class="kb-nav-article{{ $navArticle->id === $article->id ? ' active' : '' }}">
                                    {{ $navArticle->article_title }}
                                </a>
                            @endforeach
                        @endif
                    </div>
                </div>

                @foreach($category->childCategories as $childCategory)
                    @php
                        $isCurrentChildCategory = isset($allArticles[$childCategory->id]) && $allArticles[$childCategory->id]->contains('id', $article->id);
                    @endphp
                    <div class="kb-nav-category{{ $isCurrentChildCategory ? ' expanded' : '' }}" style="margin-left: 1rem;">
                        <div class="kb-nav-category-header" onclick="toggleCategory(this)">
                            <i class="{{ $childCategory->category_icon ?: 'ti ti-folder' }} kb-nav-category-icon"></i>
                            <span>{{ $childCategory->category_name }}</span>
                            <i class="ti ti-chevron-right kb-nav-category-toggle"></i>
                        </div>
                        <div class="kb-nav-articles">
                            @if(isset($allArticles[$childCategory->id]))
                                @foreach($allArticles[$childCategory->id] as $navArticle)
                                    <a href="{{ route('public.knowledge.article', [$settings->unique_url, $knowledgeBoard->id, $navArticle->id]) }}" class="kb-nav-article{{ $navArticle->id === $article->id ? ' active' : '' }}">
                                        {{ $navArticle->article_title }}
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
            <!-- Breadcrumb -->
            <nav class="kb-breadcrumb">
                <a href="{{ route('public.knowledge.show', [$settings->unique_url, $knowledgeBoard->id]) }}">{{ $knowledgeBoard->name }}</a>
                <span class="kb-breadcrumb-sep"><i class="ti ti-chevron-right"></i></span>
                <a href="{{ route('public.knowledge.show', [$settings->unique_url, $knowledgeBoard->id]) }}#category-{{ $article->boardCategory->id }}">{{ $article->boardCategory->category_name }}</a>
                <span class="kb-breadcrumb-sep"><i class="ti ti-chevron-right"></i></span>
                <span>{{ $article->article_title }}</span>
            </nav>

            <!-- Article -->
            <article class="kb-article">
                <header class="kb-article-header">
                    <div class="kb-article-category">
                        <i class="{{ $article->boardCategory->category_icon ?: 'ti ti-folder' }}"></i>
                        {{ $article->boardCategory->category_name }}
                    </div>
                    <h1 class="kb-article-title">{{ $article->article_title }}</h1>
                    <div class="kb-article-meta">
                        @if($article->author)
                            <div class="kb-article-meta-item">
                                <i class="ti ti-user"></i>
                                {{ $article->author->name }}
                            </div>
                        @endif
                        <div class="kb-article-meta-item">
                            <i class="ti ti-calendar"></i>
                            {{ $article->created_at->format('M d, Y') }}
                        </div>
                    </div>
                </header>

                <div class="kb-article-content">
                    {!! $article->detailed_post !!}
                </div>

                <!-- Navigation -->
                @if($prevArticle || $nextArticle)
                    <nav class="kb-article-nav">
                        @if($prevArticle)
                            <a href="{{ route('public.knowledge.article', [$settings->unique_url, $knowledgeBoard->id, $prevArticle->id]) }}" class="kb-article-nav-link">
                                <i class="ti ti-arrow-left"></i>
                                <div>
                                    <div class="kb-article-nav-label">Previous</div>
                                    <div class="kb-article-nav-title">{{ Str::limit($prevArticle->article_title, 40) }}</div>
                                </div>
                            </a>
                        @else
                            <div></div>
                        @endif

                        @if($nextArticle)
                            <a href="{{ route('public.knowledge.article', [$settings->unique_url, $knowledgeBoard->id, $nextArticle->id]) }}" class="kb-article-nav-link" style="text-align: right; margin-left: auto;">
                                <div>
                                    <div class="kb-article-nav-label">Next</div>
                                    <div class="kb-article-nav-title">{{ Str::limit($nextArticle->article_title, 40) }}</div>
                                </div>
                                <i class="ti ti-arrow-right"></i>
                            </a>
                        @endif
                    </nav>
                @endif
            </article>
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
                            searchResults.innerHTML = '<div style="padding: 1rem; text-align: center; color: var(--text-secondary);">No results found</div>';
                        }
                        searchResults.classList.add('active');
                    });
            }, 300);
        });

        document.addEventListener('click', function(e) {
            if (!e.target.closest('.kb-search')) {
                searchResults.classList.remove('active');
            }
        });
    </script>
</body>
</html>
