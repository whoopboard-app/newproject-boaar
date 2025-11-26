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

        /* Filters */
        .filters-section {
            display: flex;
            flex-direction: column;
            gap: 0.75rem;
            margin-bottom: 1.5rem;
        }

        .search-box {
            width: 100%;
        }

        .search-box input {
            width: 100%;
            padding: 0.625rem 0.875rem;
            border: 1px solid var(--border-color);
            border-radius: 6px;
            font-size: 0.875rem;
            background: white;
        }

        .search-box input:focus {
            outline: none;
            border-color: var(--primary-color);
        }

        .filter-dropdown {
            width: 100%;
        }

        .filter-dropdown select {
            width: 100%;
            padding: 0.625rem 0.875rem;
            border: 1px solid var(--border-color);
            border-radius: 6px;
            font-size: 0.875rem;
            cursor: pointer;
            background: white;
        }

        .filter-dropdown select:focus {
            outline: none;
            border-color: var(--primary-color);
        }

        /* Layout with Sidebar */
        .public-content {
            display: grid;
            grid-template-columns: 240px 1fr;
            gap: 2rem;
            padding-top: 2rem;
        }

        /* Sidebar */
        .sidebar {
            position: sticky;
            top: 2rem;
            height: fit-content;
        }

        .sidebar-title {
            font-size: 0.875rem;
            font-weight: 600;
            color: var(--text-secondary);
            text-transform: uppercase;
            letter-spacing: 0.05em;
            margin-bottom: 1rem;
        }

        .category-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0.625rem 0.75rem;
            border-radius: 6px;
            text-decoration: none;
            color: var(--text-primary);
            transition: all 0.2s;
            margin-bottom: 0.25rem;
            cursor: pointer;
        }

        .category-item:hover {
            background: var(--bg-hover);
            color: var(--text-primary);
        }

        .category-item.active {
            background: var(--primary-color);
            color: white;
        }

        .category-name {
            font-weight: 500;
            font-size: 0.9375rem;
        }

        .category-count {
            font-size: 0.875rem;
            color: var(--text-secondary);
            background: #f3f4f6;
            padding: 0.125rem 0.5rem;
            border-radius: 12px;
            font-weight: 500;
        }

        .category-item.active .category-count {
            background: rgba(255, 255, 255, 0.2);
            color: white;
        }

        /* Short description */
        .changelog-short-description {
            font-size: 1.125rem;
            color: var(--text-secondary);
            line-height: 1.6;
            margin-top: 1rem;
        }

        /* Tags */
        .changelog-tags {
            display: flex;
            flex-wrap: wrap;
            gap: 0.5rem;
            margin-top: 1rem;
        }

        .tag-chip {
            display: inline-flex;
            align-items: center;
            padding: 0.375rem 0.875rem;
            background: #f3f4f6;
            color: var(--text-primary);
            border-radius: 16px;
            font-size: 0.875rem;
            font-weight: 500;
            transition: all 0.2s;
        }

        .tag-chip:hover {
            background: #e5e7eb;
        }

        /* Changelog items in sidebar */
        .changelog-item-link {
            display: block;
            padding: 0.75rem;
            border-radius: 6px;
            text-decoration: none;
            color: var(--text-primary);
            transition: all 0.2s;
            margin-bottom: 0.5rem;
            border: 1px solid var(--border-color);
        }

        .changelog-item-link:hover {
            background: var(--bg-hover);
            border-color: var(--primary-color);
        }

        .changelog-item-link.current {
            background: var(--primary-color);
            color: white;
            border-color: var(--primary-color);
        }

        .changelog-item-title {
            font-weight: 500;
            font-size: 0.875rem;
            margin-bottom: 0.25rem;
        }

        .changelog-item-date {
            font-size: 0.75rem;
            color: var(--text-secondary);
        }

        .changelog-item-link.current .changelog-item-date {
            color: rgba(255, 255, 255, 0.8);
        }

        @media (max-width: 768px) {
            .public-content {
                grid-template-columns: 1fr;
            }

            .sidebar {
                position: static;
            }
        }
    </style>
</head>
<body>
    <!-- Header -->
    <header class="public-header pt-0">
        <div class="container">
            <div class="d-flex justify-content-between align-items-center">
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

                <a href="{{ route('public.subscribe', $settings->unique_url) }}" class="btn btn-primary" style="background: var(--primary-color); border: none; padding: 0.5rem 1.5rem; border-radius: 6px; text-decoration: none; color: white; font-weight: 500;">
                    <i class="ti ti-bell-ringing me-1"></i> Subscribe
                </a>
            </div>

            @include('public.partials.navigation')
        </div>
    </header>

    <!-- Main Content -->
    <div class="container">
        <div class="public-content" style="padding-top: 2rem;">
            <!-- Left Sidebar - Filters & Categories -->
            <aside class="sidebar">
                <!-- Filters Section -->
                <div class="filters-section">
                    <div class="search-box">
                        <input type="text" id="searchInput" placeholder="Search changelog..." />
                    </div>
                </div>

                <!-- Categories Section -->
                <div style="margin-top: 2rem;">
                    <div class="sidebar-title">Categories</div>
                    <div class="category-list">
                        <div class="category-item active" data-category="">
                            <span class="category-name">All Updates</span>
                            <span class="category-count">{{ $allChangelogs->count() }}</span>
                        </div>
                        @foreach($categories as $category)
                            <div class="category-item" data-category="{{ $category->id }}">
                                <span class="category-name">{{ $category->name }}</span>
                                <span class="category-count">{{ $category->changelogs_count }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>
                <div class="filters-section">
                    <div class="filter-dropdown">
                        <select id="yearFilter">
                            <option value="">All Years</option>
                        </select>
                    </div>
                    <div class="filter-dropdown">
                        <select id="monthFilter">
                            <option value="">All Months</option>
                            <option value="1">January</option>
                            <option value="2">February</option>
                            <option value="3">March</option>
                            <option value="4">April</option>
                            <option value="5">May</option>
                            <option value="6">June</option>
                            <option value="7">July</option>
                            <option value="8">August</option>
                            <option value="9">September</option>
                            <option value="10">October</option>
                            <option value="11">November</option>
                            <option value="12">December</option>
                        </select>
                    </div>
                    <div class="filter-dropdown">
                        <select id="sortFilter">
                            <option value="latest">Latest First</option>
                            <option value="oldest">Oldest First</option>
                        </select>
                    </div>
                </div>
            </aside>

            <!-- Main Content -->
            <main>
                <div class="changelog-container" style="max-width: 100%; padding: 0;">
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

                    @if($changelog->cover_image)
                        <div class="changelog-cover-image mb-4">
                            <img src="{{ asset('storage/' . $changelog->cover_image) }}" alt="{{ $changelog->title }}" style="width: 100%; border-radius: 8px; margin-bottom: 2rem;">
                        </div>
                    @endif

                    <article class="changelog-content pb-2">
                        {!! $changelog->description !!}
                    </article>
                    @if($changelog->tags && count($changelog->tags) > 0)
                        <div class="changelog-tags">
                            @foreach($changelog->tags as $tag)
                                <span class="tag-chip">
                                        <i class="ti ti-tag"></i> {{ $tag }}
                                    </span>
                            @endforeach
                        </div>
                    @endif

                    <!-- Rating Widget -->
                    @if(isset($ratingSettings))
                        @include('public.partials.changelog-rating-widget')
                    @endif
                </div>
            </main>
        </div>
    </div>

    @include('partials.public-footer')

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const categoryItems = document.querySelectorAll('.category-item');
            const changelogLinks = document.querySelectorAll('.changelog-item-link');
            const searchInput = document.getElementById('searchInput');
            const yearFilter = document.getElementById('yearFilter');
            const monthFilter = document.getElementById('monthFilter');
            const sortFilter = document.getElementById('sortFilter');

            let selectedCategory = '';

            // Populate year filter dynamically
            const years = new Set();
            changelogLinks.forEach(item => {
                years.add(item.dataset.year);
            });
            Array.from(years).sort((a, b) => b - a).forEach(year => {
                const option = document.createElement('option');
                option.value = year;
                option.textContent = year;
                yearFilter.appendChild(option);
            });

            // Combined filter function
            function applyFilters() {
                const searchTerm = searchInput.value.toLowerCase();
                const selectedYear = yearFilter.value;
                const selectedMonth = monthFilter.value;
                const sortOrder = sortFilter.value;

                // Convert NodeList to Array for sorting
                let itemsArray = Array.from(changelogLinks);

                // Sort items
                itemsArray.sort((a, b) => {
                    const timeA = parseInt(a.dataset.timestamp);
                    const timeB = parseInt(b.dataset.timestamp);
                    return sortOrder === 'latest' ? timeB - timeA : timeA - timeB;
                });

                // Reorder DOM elements
                const parent = itemsArray[0]?.parentElement;
                if (parent) {
                    itemsArray.forEach(item => parent.appendChild(item));
                }

                // Filter items
                changelogLinks.forEach(item => {
                    const matchesSearch = !searchTerm || item.dataset.search.includes(searchTerm);
                    const matchesCategory = !selectedCategory || item.dataset.category === selectedCategory;
                    const matchesYear = !selectedYear || item.dataset.year === selectedYear;
                    const matchesMonth = !selectedMonth || item.dataset.month === selectedMonth;

                    if (matchesSearch && matchesCategory && matchesYear && matchesMonth) {
                        item.style.display = 'block';
                    } else {
                        item.style.display = 'none';
                    }
                });
            }

            // Category filtering
            categoryItems.forEach(item => {
                item.addEventListener('click', function() {
                    categoryItems.forEach(cat => cat.classList.remove('active'));
                    this.classList.add('active');
                    selectedCategory = this.dataset.category;
                    applyFilters();
                });
            });

            // Search filtering
            searchInput.addEventListener('input', applyFilters);

            // Year/Month filtering
            yearFilter.addEventListener('change', applyFilters);
            monthFilter.addEventListener('change', applyFilters);

            // Sort filtering
            sortFilter.addEventListener('change', applyFilters);

            // Initial sort
            applyFilters();
        });
    </script>
</body>
</html>
