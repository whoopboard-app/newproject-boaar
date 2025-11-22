<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $settings->product_name ?? 'Feedback' }}</title>

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
            width: 192px;
            height: 75px;
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

        /* Layout */
        .public-content {
            display: grid;
            grid-template-columns: 240px 1fr;
            gap: 2rem;
            padding: 2rem 0;
            max-width: 1200px;
            margin: 0 auto;
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

        /* Main Content */
        .feedback-list {
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }

        .feedback-card {
            background: white;
            border: 1px solid var(--border-color);
            border-radius: 8px;
            padding: 1.25rem;
            transition: all 0.2s;
            text-decoration: none;
            color: inherit;
            display: block;
        }

        .feedback-card:hover {
            border-color: var(--primary-color);
            box-shadow: 0 2px 8px rgba(88, 101, 242, 0.1);
            transform: translateY(-1px);
        }

        .feedback-header {
            display: flex;
            align-items: flex-start;
            gap: 1rem;
        }

        .vote-box {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 0.25rem;
            background: #f9fafb;
            border: 1px solid var(--border-color);
            border-radius: 8px;
            padding: 0.5rem 0.75rem;
            min-width: 60px;
            cursor: pointer;
            transition: all 0.2s;
        }

        .vote-box:hover {
            background: var(--primary-color);
            border-color: var(--primary-color);
            color: white;
        }

        .vote-icon {
            font-size: 1.25rem;
        }

        .vote-count {
            font-size: 0.875rem;
            font-weight: 600;
        }

        .feedback-content {
            flex: 1;
        }

        .feedback-title {
            font-size: 1.125rem;
            font-weight: 600;
            color: var(--text-primary);
            margin-bottom: 0.5rem;
        }

        .feedback-description {
            color: var(--text-secondary);
            font-size: 0.9375rem;
            line-height: 1.5;
            margin-bottom: 0.75rem;
        }

        .feedback-meta {
            display: flex;
            align-items: center;
            gap: 1rem;
            font-size: 0.875rem;
            color: var(--text-secondary);
        }

        .status-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.375rem;
            padding: 0.25rem 0.75rem;
            border-radius: 12px;
            font-size: 0.8125rem;
            font-weight: 500;
        }

        .category-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.375rem;
            padding: 0.25rem 0.75rem;
            border-radius: 12px;
            background: #f3f4f6;
            color: var(--text-secondary);
            font-size: 0.8125rem;
            font-weight: 500;
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

            <nav class="public-nav">
                <a href="{{ route('public.home', $settings->unique_url) }}" class="nav-tab active">
                    <i class="ti ti-message-2"></i> Feedback
                </a>
                <a href="{{ route('public.roadmap', $settings->unique_url) }}" class="nav-tab">
                    <i class="ti ti-route"></i> Roadmap
                </a>
                <a href="{{ route('public.changelog', $settings->unique_url) }}" class="nav-tab">
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
                <input type="text" id="searchInput" placeholder="Search feedback..." />
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
                    <a href="#" class="category-item active">
                        <span class="category-name">All Feedback</span>
                        <span class="category-count">{{ $feedbacks->flatten()->count() }}</span>
                    </a>
                    @foreach($categories as $category)
                        <a href="#category-{{ $category->id }}" class="category-item" data-category="{{ $category->id }}">
                            <span class="category-name">{{ $category->name }}</span>
                            <span class="category-count">{{ $category->feedbacks_count }}</span>
                        </a>
                    @endforeach
                </div>
            </aside>

            <!-- Feedback List -->
            <main>
                @if($feedbacks->flatten()->count() > 0)
                    <div class="feedback-list">
                        @foreach($categories as $category)
                            @if(isset($feedbacks[$category->id]) && $feedbacks[$category->id]->count() > 0)
                                @foreach($feedbacks[$category->id] as $feedback)
                                    <div class="feedback-card"
                                         data-category="{{ $category->id }}"
                                         data-year="{{ $feedback->created_at->format('Y') }}"
                                         data-month="{{ $feedback->created_at->format('n') }}"
                                         data-timestamp="{{ $feedback->created_at->timestamp }}"
                                         data-search="{{ strtolower($feedback->idea . ' ' . strip_tags($feedback->value_description ?? '')) }}">
                                        <div class="feedback-header">
                                            <div class="vote-box">
                                                <i class="ti ti-arrow-up vote-icon"></i>
                                                <span class="vote-count">0</span>
                                            </div>
                                            <div class="feedback-content">
                                                <h3 class="feedback-title">{{ $feedback->idea }}</h3>
                                                @if($feedback->value_description)
                                                    <div class="feedback-description">
                                                        {{ Str::limit(strip_tags($feedback->value_description), 150) }}
                                                    </div>
                                                @endif
                                                <div class="feedback-meta">
                                                    @if($feedback->roadmap)
                                                        <span class="status-badge" style="background-color: {{ $feedback->roadmap->color }}20; color: {{ $feedback->roadmap->color }};">
                                                            {{ $feedback->roadmap->name }}
                                                        </span>
                                                    @endif
                                                    <span class="category-badge">
                                                        {{ $category->name }}
                                                    </span>
                                                    <span>
                                                        <i class="ti ti-clock"></i>
                                                        {{ $feedback->created_at->diffForHumans() }}
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            @endif
                        @endforeach
                    </div>
                @else
                    <div class="empty-state">
                        <i class="ti ti-message-2-off empty-icon"></i>
                        <h2 class="empty-title">No feedback yet</h2>
                        <p class="empty-text">Be the first to share your ideas and suggestions!</p>
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
            const feedbackCards = document.querySelectorAll('.feedback-card');
            const searchInput = document.getElementById('searchInput');
            const yearFilter = document.getElementById('yearFilter');
            const monthFilter = document.getElementById('monthFilter');
            const sortFilter = document.getElementById('sortFilter');

            let selectedCategory = '';

            // Populate year filter dynamically
            const years = new Set();
            feedbackCards.forEach(card => {
                years.add(card.dataset.year);
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
                let cardsArray = Array.from(feedbackCards);

                // Sort cards
                cardsArray.sort((a, b) => {
                    const timeA = parseInt(a.dataset.timestamp);
                    const timeB = parseInt(b.dataset.timestamp);
                    return sortOrder === 'latest' ? timeB - timeA : timeA - timeB;
                });

                // Reorder DOM elements
                const parent = cardsArray[0]?.parentElement;
                if (parent) {
                    cardsArray.forEach(card => parent.appendChild(card));
                }

                // Filter cards
                feedbackCards.forEach(card => {
                    const matchesSearch = !searchTerm || card.dataset.search.includes(searchTerm);
                    const matchesCategory = !selectedCategory || card.dataset.category === selectedCategory;
                    const matchesYear = !selectedYear || card.dataset.year === selectedYear;
                    const matchesMonth = !selectedMonth || card.dataset.month === selectedMonth;

                    if (matchesSearch && matchesCategory && matchesYear && matchesMonth) {
                        card.style.display = 'block';
                    } else {
                        card.style.display = 'none';
                    }
                });
            }

            // Category filtering
            categoryItems.forEach(item => {
                item.addEventListener('click', function(e) {
                    e.preventDefault();
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
