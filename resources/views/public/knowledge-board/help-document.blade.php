<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $theme->meta_title ?? ($knowledgeBoard->name . ' - ' . ($settings->product_name ?? 'Help Center')) }}</title>
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
            padding: 48px 24px 64px;
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
            margin-bottom: 32px;
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
            font-size: 36px;
            font-weight: 700;
            color: var(--header-text);
            margin-bottom: 16px;
            letter-spacing: -0.5px;
        }

        .hs-hero-subtitle {
            font-size: 18px;
            color: var(--header-text);
            opacity: 0.85;
            margin-bottom: 32px;
        }

        /* Search Box */
        .hs-search {
            position: relative;
            max-width: 600px;
            margin: 0 auto;
        }

        .hs-search-input {
            width: 100%;
            padding: 18px 24px 18px 56px;
            border: none;
            border-radius: 12px;
            font-size: 17px;
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
            left: 20px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--text-light);
            font-size: 22px;
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
            padding: 16px 20px;
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
            font-size: 12px;
            font-weight: 600;
            color: var(--primary-color);
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 4px;
        }

        .hs-search-result-title {
            font-weight: 600;
            color: var(--text-dark);
            font-size: 15px;
            margin-bottom: 4px;
        }

        .hs-search-result-excerpt {
            font-size: 13px;
            color: var(--text-light);
        }

        .hs-search-no-results {
            padding: 24px;
            text-align: center;
            color: var(--text-light);
        }

        /* Main Content */
        .hs-main {
            max-width: 1100px;
            margin: 0 auto;
            padding: 48px 24px;
        }

        /* Back Link */
        .hs-back {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            color: var(--text-light);
            font-size: 14px;
            margin-bottom: 32px;
        }

        .hs-back:hover {
            color: var(--primary-color);
        }

        /* Collections Grid */
        .hs-collections {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
            gap: 24px;
        }

        .hs-collection {
            background: var(--bg-white);
            border-radius: 12px;
            border: 1px solid var(--border-color);
            overflow: hidden;
            transition: all 0.2s;
        }

        .hs-collection:hover {
            border-color: var(--primary-color);
            box-shadow: 0 4px 16px rgba(49, 151, 214, 0.12);
        }

        .hs-collection-header {
            display: block;
            padding: 24px 24px 20px;
            border-bottom: 1px solid var(--border-color);
            text-decoration: none;
            transition: background 0.2s;
        }

        .hs-collection-header:hover {
            background: var(--bg-page);
        }

        .hs-collection-icon {
            width: 48px;
            height: 48px;
            background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
            color: white;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 16px;
            font-size: 24px;
        }

        .hs-collection-name {
            font-size: 18px;
            font-weight: 700;
            color: var(--text-dark);
            margin-bottom: 4px;
        }

        .hs-collection-count {
            font-size: 13px;
            color: var(--text-light);
        }

        .hs-collection-articles {
            padding: 8px 0;
        }

        .hs-collection-article {
            display: flex;
            align-items: center;
            padding: 12px 24px;
            color: var(--text-primary);
            font-size: 14px;
            transition: all 0.2s;
        }

        .hs-collection-article:hover {
            background: var(--bg-page);
            color: var(--primary-color);
        }

        .hs-collection-article-icon {
            color: var(--text-light);
            margin-right: 10px;
            font-size: 16px;
        }

        .hs-collection-article:hover .hs-collection-article-icon {
            color: var(--primary-color);
        }

        .hs-collection-article-title {
            flex: 1;
        }

        .hs-collection-article-arrow {
            color: var(--text-light);
            font-size: 14px;
            opacity: 0;
            transform: translateX(-4px);
            transition: all 0.2s;
        }

        .hs-collection-article:hover .hs-collection-article-arrow {
            opacity: 1;
            transform: translateX(0);
        }

        .hs-collection-more {
            display: block;
            padding: 14px 24px;
            text-align: center;
            font-size: 13px;
            font-weight: 600;
            color: var(--primary-color);
            border-top: 1px solid var(--border-color);
            background: var(--bg-page);
        }

        .hs-collection-more:hover {
            background: rgba(49, 151, 214, 0.1);
        }

        /* Empty State */
        .hs-empty {
            text-align: center;
            padding: 80px 40px;
            background: var(--bg-white);
            border-radius: 12px;
            border: 1px solid var(--border-color);
        }

        .hs-empty-icon {
            font-size: 64px;
            color: var(--border-color);
            margin-bottom: 20px;
        }

        .hs-empty-title {
            font-size: 24px;
            font-weight: 600;
            color: var(--text-dark);
            margin-bottom: 8px;
        }

        .hs-empty-text {
            color: var(--text-light);
            font-size: 16px;
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

        .hs-footer a {
            color: var(--footer-text);
            opacity: 0.8;
        }

        .hs-footer a:hover {
            opacity: 1;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .hs-hero {
                padding: 32px 16px 48px;
            }

            .hs-hero-title {
                font-size: 28px;
            }

            .hs-hero-subtitle {
                font-size: 16px;
            }

            .hs-search-input {
                padding: 16px 20px 16px 48px;
                font-size: 16px;
            }

            .hs-main {
                padding: 32px 16px;
            }

            .hs-collections {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <!-- Hero Section -->
    <header class="hs-hero">
        <div class="hs-hero-inner">
            <a href="{{ route('public.knowledge') }}" class="hs-brand">
                @if($settings->logo)
                    <img src="{{ asset('storage/' . $settings->logo) }}" alt="{{ $settings->product_name }}" class="hs-brand-logo">
                @else
                    <div class="hs-brand-logo" style="display: flex; align-items: center; justify-content: center; color: white; font-weight: 700; font-size: 18px;">
                        {{ strtoupper(substr($settings->product_name ?? 'H', 0, 1)) }}
                    </div>
                @endif
                <span class="hs-brand-name">{{ $settings->product_name ?? 'Help Center' }}</span>
            </a>

            <h1 class="hs-hero-title">{{ $theme->header_intro_text ?? $knowledgeBoard->name }}</h1>
            <p class="hs-hero-subtitle">{{ $theme->header_short_description ?? ($knowledgeBoard->short_description ?: 'How can we help you today?') }}</p>

            <div class="hs-search">
                <i class="ti ti-search hs-search-icon"></i>
                <input type="text" class="hs-search-input" id="searchInput" placeholder="Search for articles..." autocomplete="off">
                <div class="hs-search-results" id="searchResults"></div>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="hs-main">
        <a href="{{ route('public.knowledge') }}" style="display: inline-flex; align-items: center; gap: 8px; padding: 10px 20px; background: white; border: 1px solid var(--border-color); border-radius: 8px; color: var(--text-dark); font-size: 14px; font-weight: 500; margin-bottom: 24px; transition: all 0.2s;" onmouseover="this.style.background='var(--primary-color)'; this.style.color='white'; this.style.borderColor='var(--primary-color)';" onmouseout="this.style.background='white'; this.style.color='var(--text-dark)'; this.style.borderColor='var(--border-color)';">
            <i class="ti ti-arrow-left"></i>
            Back to Knowledge Board
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
            <div class="hs-collections">
                @foreach($categories as $category)
                    @if(isset($articles[$category->id]) && $articles[$category->id]->count() > 0)
                        <div class="hs-collection">
                            <a href="{{ route('public.knowledge.category', [$knowledgeBoard->id, $category->id]) }}" class="hs-collection-header" style="border-bottom: none;">
                                <div class="hs-collection-icon">
                                    <i class="{{ $category->category_icon ?: 'ti ti-folder' }}"></i>
                                </div>
                                <h2 class="hs-collection-name">{{ $category->category_name }}</h2>
                                <span class="hs-collection-count">{{ $articles[$category->id]->count() }} {{ Str::plural('article', $articles[$category->id]->count()) }}</span>
                            </a>
                        </div>
                    @endif

                    {{-- Child categories --}}
                    @foreach($category->childCategories as $childCategory)
                        @if(isset($articles[$childCategory->id]) && $articles[$childCategory->id]->count() > 0)
                            <div class="hs-collection">
                                <a href="{{ route('public.knowledge.category', [$knowledgeBoard->id, $childCategory->id]) }}" class="hs-collection-header" style="border-bottom: none;">
                                    <div class="hs-collection-icon">
                                        <i class="{{ $childCategory->category_icon ?: 'ti ti-folder' }}"></i>
                                    </div>
                                    <h2 class="hs-collection-name">{{ $childCategory->category_name }}</h2>
                                    <span class="hs-collection-count">{{ $articles[$childCategory->id]->count() }} {{ Str::plural('article', $articles[$childCategory->id]->count()) }}</span>
                                </a>
                            </div>
                        @endif
                    @endforeach
                @endforeach
            </div>
        @else
            <div class="hs-empty">
                <i class="ti ti-files-off hs-empty-icon"></i>
                <h3 class="hs-empty-title">No articles yet</h3>
                <p class="hs-empty-text">Check back later for helpful documentation!</p>
            </div>
        @endif
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
                fetch(`{{ route('public.knowledge.search', $knowledgeBoard->id) }}?q=${encodeURIComponent(query)}`)
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
