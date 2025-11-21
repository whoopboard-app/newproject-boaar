<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Changelog - {{ $settings->product_name ?? 'Feedback' }}</title>

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

        /* Changelog */
        .changelog-list {
            max-width: 800px;
            margin: 0 auto;
        }

        .changelog-item {
            background: white;
            border: 1px solid var(--border-color);
            border-radius: 8px;
            padding: 2rem;
            margin-bottom: 2rem;
            transition: all 0.2s;
            cursor: pointer;
        }

        .changelog-item:hover {
            border-color: var(--primary-color);
            box-shadow: 0 2px 8px rgba(88, 101, 242, 0.1);
            transform: translateY(-2px);
        }

        .changelog-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 1rem;
            padding-bottom: 1rem;
            border-bottom: 1px solid var(--border-color);
        }

        .changelog-title {
            font-size: 1.5rem;
            font-weight: 600;
            color: var(--text-primary);
            margin: 0;
        }

        .changelog-date {
            font-size: 0.875rem;
            color: var(--text-secondary);
        }

        .changelog-category {
            display: inline-flex;
            align-items: center;
            padding: 0.25rem 0.75rem;
            border-radius: 12px;
            font-size: 0.8125rem;
            font-weight: 500;
            margin-right: 0.5rem;
        }

        .changelog-content {
            color: var(--text-primary);
            line-height: 1.6;
        }

        .changelog-content h1,
        .changelog-content h2,
        .changelog-content h3 {
            margin-top: 1.5rem;
            margin-bottom: 0.75rem;
        }

        .changelog-content ul,
        .changelog-content ol {
            margin-left: 1.5rem;
            margin-bottom: 1rem;
        }

        .changelog-content p {
            margin-bottom: 1rem;
        }

        .pagination {
            justify-content: center;
            margin-top: 2rem;
        }

        .empty-state {
            text-align: center;
            padding: 4rem 2rem;
        }

        .empty-icon {
            font-size: 4rem;
            color: var(--border-color);
            margin-bottom: 1rem;
        }

        .empty-title {
            font-size: 1.25rem;
            font-weight: 600;
            color: var(--text-primary);
            margin-bottom: 0.5rem;
        }

        .empty-text {
            color: var(--text-secondary);
        }

        /* Filters */
        .filters-section {
            display: flex;
            gap: 1rem;
            margin-bottom: 2rem;
            flex-wrap: wrap;
        }

        .search-box {
            flex: 1;
            min-width: 250px;
        }

        .search-box input {
            width: 100%;
            padding: 0.625rem 1rem;
            border: 1px solid var(--border-color);
            border-radius: 6px;
            font-size: 0.9375rem;
        }

        .search-box input:focus {
            outline: none;
            border-color: var(--primary-color);
        }

        .filter-dropdown {
            min-width: 180px;
        }

        .filter-dropdown select {
            width: 100%;
            padding: 0.625rem 1rem;
            border: 1px solid var(--border-color);
            border-radius: 6px;
            font-size: 0.9375rem;
            cursor: pointer;
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
            padding: 2rem 0;
            max-width: 1200px;
            margin: 0 auto;
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
    <header class="public-header">
        <div class="container">
            <div class="logo-section">
                @if($settings->logo)
                    <img src="{{ asset('storage/' . $settings->logo) }}" alt="{{ $settings->product_name }}" class="logo-img">
                @else
                    <div class="logo-img" style="background: var(--primary-color); display: flex; align-items: center; justify-content: center; color: white; font-weight: 700; font-size: 1.25rem;">
                        {{ strtoupper(substr($settings->product_name ?? 'F', 0, 1)) }}
                    </div>
                @endif
                <h1 class="product-name">{{ $settings->product_name ?? 'Feedback Board' }}</h1>
            </div>

            <nav class="public-nav">
                <a href="{{ route('public.home', $settings->unique_url) }}" class="nav-tab">
                    <i class="ti ti-message-2"></i> Feedback
                </a>
                <a href="{{ route('public.roadmap', $settings->unique_url) }}" class="nav-tab">
                    <i class="ti ti-route"></i> Roadmap
                </a>
                <a href="{{ route('public.changelog', $settings->unique_url) }}" class="nav-tab active">
                    <i class="ti ti-clipboard-list"></i> Changelog
                </a>
            </nav>
        </div>
    </header>

    <!-- Main Content -->
    <div class="container">
        <!-- Filters -->
        <div class="filters-section" style="padding-top: 2rem;">
            <div class="search-box">
                <input type="text" id="searchInput" placeholder="Search changelog..." />
            </div>
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

        <div class="public-content">
            <!-- Sidebar -->
            <aside class="sidebar">
                <div class="sidebar-title">Categories</div>
                <div class="category-list">
                    <div class="category-item active" data-category="">
                        <span class="category-name">All Updates</span>
                        <span class="category-count">{{ $changelogs->total() }}</span>
                    </div>
                    @foreach($categories as $category)
                        <div class="category-item" data-category="{{ $category->id }}">
                            <span class="category-name">{{ $category->name }}</span>
                            <span class="category-count">{{ $category->changelogs_count }}</span>
                        </div>
                    @endforeach
                </div>
            </aside>

            <!-- Changelog List -->
            <main>
                @if($changelogs->count() > 0)
                    <div class="changelog-list">
                        @foreach($changelogs as $changelog)
                            <article class="changelog-item"
                                     data-category="{{ $changelog->category_id ?? '' }}"
                                     data-url="{{ route('public.changelog.show', [$settings->unique_url, $changelog->id]) }}"
                                     data-year="{{ \Carbon\Carbon::parse($changelog->published_date)->format('Y') }}"
                                     data-month="{{ \Carbon\Carbon::parse($changelog->published_date)->format('n') }}"
                                     data-timestamp="{{ \Carbon\Carbon::parse($changelog->published_date)->timestamp }}"
                                     data-search="{{ strtolower($changelog->title . ' ' . strip_tags($changelog->content)) }}"
                                     onclick="window.location.href=this.dataset.url">
                                <header class="changelog-header">
                                    <div>
                                        <h2 class="changelog-title">{{ $changelog->title }}</h2>
                                        <div style="margin-top: 0.5rem;">
                                            @if($changelog->category)
                                                <span class="changelog-category" style="background-color: {{ $changelog->category->color ?? '#e5e7eb' }}20; color: {{ $changelog->category->color ?? '#6b7280' }};">
                                                    {{ $changelog->category->name }}
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="changelog-date">
                                        <i class="ti ti-calendar"></i>
                                        {{ \Carbon\Carbon::parse($changelog->published_date)->format('M d, Y') }}
                                    </div>
                                </header>
                                <div class="changelog-content">
                                    {!! Str::limit(strip_tags($changelog->content), 300) !!}
                                </div>
                            </article>
                        @endforeach

                        <!-- Pagination -->
                        {{ $changelogs->links() }}
                    </div>
                @else
                    <div class="empty-state">
                        <i class="ti ti-clipboard-list empty-icon"></i>
                        <h2 class="empty-title">No changelog yet</h2>
                        <p class="empty-text">Stay tuned for updates and new features!</p>
                    </div>
                @endif
            </main>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const categoryItems = document.querySelectorAll('.category-item');
            const changelogItems = document.querySelectorAll('.changelog-item');
            const searchInput = document.getElementById('searchInput');
            const yearFilter = document.getElementById('yearFilter');
            const monthFilter = document.getElementById('monthFilter');
            const sortFilter = document.getElementById('sortFilter');

            let selectedCategory = '';

            // Populate year filter dynamically
            const years = new Set();
            changelogItems.forEach(item => {
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
                let itemsArray = Array.from(changelogItems);

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
                changelogItems.forEach(item => {
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
