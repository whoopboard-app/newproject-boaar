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

    @include('public.partials.public-styles')

    <style>
        /* Changelog */
        .changelog-list {
            max-width: 800px;
            margin: 0 auto;
        }

        .changelog-item {
            background: white;
            border: 1px solid var(--border-color);
            border-radius: 8px;
            padding: 0;
            margin-bottom: 2rem;
            transition: all 0.2s;
            cursor: pointer;
            overflow: hidden;
        }

        .changelog-item:hover {
            border-color: var(--primary-color);
            box-shadow: 0 2px 8px rgba(88, 101, 242, 0.1);
            transform: translateY(-2px);
        }

        .changelog-item-body {
            padding: 2rem;
        }

        .changelog-cover {
            width: 100%;
            height: 250px;
            object-fit: cover;
            border-bottom: 1px solid var(--border-color);
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

    </style>
</head>
<body>
    <!-- Header -->
    @include('public.partials.top-navbar')

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
                            <span class="category-count">{{ $changelogs->total() }}</span>
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
                                     data-search="{{ strtolower($changelog->title . ' ' . strip_tags($changelog->description)) }}"
                                     onclick="window.location.href=this.dataset.url">

                                @if($changelog->cover_image)
                                    <img src="{{ asset('storage/' . $changelog->cover_image) }}" alt="{{ $changelog->title }}" class="changelog-cover">
                                @endif

                                <div class="changelog-item-body">
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
                                        {{ Str::limit($changelog->short_description, 200) }}
                                    </div>
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

    @include('partials.public-footer')

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
