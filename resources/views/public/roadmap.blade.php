<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Roadmap - {{ $settings->product_name ?? 'Feedback' }}</title>

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

        /* Kanban Board */
        .kanban-container {
            display: flex;
            gap: 1.5rem;
            overflow-x: auto;
            padding: 2rem 0;
            min-height: calc(100vh - 200px);
        }

        .kanban-column {
            background-color: #f8f9fa;
            border-radius: 12px;
            min-width: 320px;
            max-width: 320px;
            display: flex;
            flex-direction: column;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        }

        .kanban-column-header {
            padding: 1.25rem;
            border-bottom: 2px solid var(--border-color);
            background-color: #ffffff;
            border-radius: 12px 12px 0 0;
        }

        .status-indicator {
            width: 12px;
            height: 12px;
            border-radius: 50%;
            display: inline-block;
            margin-right: 0.5rem;
        }

        .roadmap-title {
            font-size: 1.125rem;
            font-weight: 600;
            color: var(--text-primary);
            margin: 0 0 0.5rem 0;
            display: flex;
            align-items: center;
        }

        .roadmap-count {
            font-size: 0.8125rem;
            color: var(--text-secondary);
            background: #e9ecef;
            padding: 0.25rem 0.6rem;
            border-radius: 12px;
            display: inline-block;
        }

        .kanban-cards {
            padding: 1rem;
            flex: 1;
            overflow-y: auto;
        }

        .feedback-list {
            display: flex;
            flex-direction: column;
            gap: 0.75rem;
        }

        .feedback-card {
            background: white;
            border: 1px solid var(--border-color);
            border-radius: 8px;
            padding: 1rem;
            transition: all 0.2s;
            cursor: default;
        }

        .feedback-card:hover {
            border-color: var(--primary-color);
            box-shadow: 0 2px 8px rgba(88, 101, 242, 0.1);
            transform: translateY(-2px);
        }

        .feedback-title {
            font-size: 0.95rem;
            font-weight: 600;
            color: var(--text-primary);
            margin-bottom: 0.5rem;
            line-height: 1.4;
        }

        .feedback-description {
            color: var(--text-secondary);
            font-size: 0.85rem;
            line-height: 1.4;
            margin-bottom: 0.75rem;
        }

        .feedback-meta {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            font-size: 0.75rem;
            color: var(--text-secondary);
            flex-wrap: wrap;
        }

        .category-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.25rem;
            padding: 0.25rem 0.5rem;
            border-radius: 4px;
            background: #e9ecef;
            color: var(--text-secondary);
            font-size: 0.75rem;
            font-weight: 500;
        }

        .empty-column {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 3rem 1rem;
            color: #adb5bd;
            text-align: center;
        }

        .empty-column i {
            font-size: 3rem;
            margin-bottom: 0.5rem;
        }

        .empty-state {
            text-align: center;
            padding: 4rem 2rem;
            background: #f9fafb;
            border-radius: 8px;
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

        /* Scrollbar styling */
        .kanban-container::-webkit-scrollbar {
            height: 8px;
        }

        .kanban-container::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 4px;
        }

        .kanban-container::-webkit-scrollbar-thumb {
            background: #888;
            border-radius: 4px;
        }

        .kanban-container::-webkit-scrollbar-thumb:hover {
            background: #555;
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
                <a href="{{ route('public.home', $settings->unique_url) }}" class="nav-tab">
                    <i class="ti ti-message-2"></i> Feedback
                </a>
                <a href="{{ route('public.roadmap', $settings->unique_url) }}" class="nav-tab active">
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
        @if($roadmaps->count() > 0)
            <div class="kanban-container">
                @foreach($roadmaps as $roadmap)
                    <div class="kanban-column">
                        <div class="kanban-column-header">
                            <h2 class="roadmap-title">
                                <span class="status-indicator" style="background-color: {{ $roadmap->color }};"></span>
                                {{ $roadmap->name }}
                            </h2>
                            <span class="roadmap-count">
                                {{ isset($roadmapItems[$roadmap->id]) ? $roadmapItems[$roadmap->id]->count() : 0 }} items
                            </span>
                        </div>
                        <div class="kanban-cards">
                            @if(isset($roadmapItems[$roadmap->id]) && $roadmapItems[$roadmap->id]->count() > 0)
                                <div class="feedback-list">
                                    @foreach($roadmapItems[$roadmap->id] as $item)
                                        <div class="feedback-card" onclick="openRoadmapDrawer({{ $item->id }})" style="cursor: pointer;">
                                            <h3 class="feedback-title">{{ Str::limit($item->idea, 60) }}</h3>
                                            @if($item->notes)
                                                <div class="feedback-description">
                                                    {{ Str::limit(strip_tags($item->notes), 100) }}
                                                </div>
                                            @endif
                                            <div class="feedback-meta">
                                                @if($item->feedback && $item->feedback->category)
                                                    <span class="category-badge">
                                                        {{ $item->feedback->category->name }}
                                                    </span>
                                                @endif
                                                @if($item->tags && count($item->tags) > 0)
                                                    @foreach(array_slice($item->tags, 0, 2) as $tag)
                                                        <span class="category-badge">{{ $tag }}</span>
                                                    @endforeach
                                                @endif
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="empty-column">
                                    <i class="ti ti-inbox"></i>
                                    <div>No items</div>
                                </div>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="empty-state" style="margin-top: 3rem;">
                <i class="ti ti-route empty-icon"></i>
                <h2 class="empty-title">No roadmap yet</h2>
                <p class="empty-text">Check back later for updates on our progress!</p>
            </div>
        @endif
    </div>

    <!-- Roadmap Item Drawer -->
    <div class="offcanvas offcanvas-end" tabindex="-1" id="roadmapDrawer" style="width: 500px;">
        <div class="offcanvas-header" style="border-bottom: 1px solid var(--border-color);">
            <h5 class="offcanvas-title" style="font-weight: 600; color: var(--text-primary);">
                <i class="ti ti-route me-2"></i>Roadmap Item Details
            </h5>
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas"></button>
        </div>
        <div class="offcanvas-body">
            <div id="drawerContent">
                <div class="mb-4">
                    <label style="font-size: 0.75rem; font-weight: 600; color: var(--text-secondary); text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 0.5rem;">Idea</label>
                    <div id="drawerIdea" style="font-size: 1.125rem; font-weight: 600; color: var(--text-primary); line-height: 1.5;"></div>
                </div>

                <div class="mb-4" id="drawerNotesSection" style="display: none;">
                    <label style="font-size: 0.75rem; font-weight: 600; color: var(--text-secondary); text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 0.5rem;">Description</label>
                    <div id="drawerNotes" style="color: var(--text-primary); line-height: 1.6;"></div>
                </div>

                <div class="mb-4" id="drawerTagsSection" style="display: none;">
                    <label style="font-size: 0.75rem; font-weight: 600; color: var(--text-secondary); text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 0.5rem;">Tags</label>
                    <div id="drawerTags" style="display: flex; flex-wrap: wrap; gap: 0.5rem;"></div>
                </div>

                <div class="mb-4" id="drawerCategorySection" style="display: none;">
                    <label style="font-size: 0.75rem; font-weight: 600; color: var(--text-secondary); text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 0.5rem;">Category</label>
                    <div id="drawerCategory"></div>
                </div>

                <div class="mb-4" id="drawerStatusSection" style="display: none;">
                    <label style="font-size: 0.75rem; font-weight: 600; color: var(--text-secondary); text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 0.5rem;">Status</label>
                    <div id="drawerStatus"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // Store roadmap items data in JavaScript
        const roadmapItemsData = @json($roadmapItems->flatten());
        const roadmapsData = @json($roadmaps);

        function openRoadmapDrawer(itemId) {
            // Find the item in the data
            const item = roadmapItemsData.find(i => i.id === itemId);

            if (!item) return;

            // Populate drawer content
            document.getElementById('drawerIdea').textContent = item.idea;

            // Show/hide notes section
            const notesSection = document.getElementById('drawerNotesSection');
            const notesDiv = document.getElementById('drawerNotes');
            if (item.notes) {
                notesDiv.innerHTML = item.notes;
                notesSection.style.display = 'block';
            } else {
                notesSection.style.display = 'none';
            }

            // Show/hide tags section
            const tagsSection = document.getElementById('drawerTagsSection');
            const tagsDiv = document.getElementById('drawerTags');
            if (item.tags && item.tags.length > 0) {
                tagsDiv.innerHTML = item.tags.map(tag =>
                    `<span class="category-badge">${tag}</span>`
                ).join('');
                tagsSection.style.display = 'block';
            } else {
                tagsSection.style.display = 'none';
            }

            // Show/hide category section
            const categorySection = document.getElementById('drawerCategorySection');
            const categoryDiv = document.getElementById('drawerCategory');
            if (item.feedback && item.feedback.category) {
                categoryDiv.innerHTML = `<span class="category-badge">${item.feedback.category.name}</span>`;
                categorySection.style.display = 'block';
            } else {
                categorySection.style.display = 'none';
            }

            // Show/hide status section
            const statusSection = document.getElementById('drawerStatusSection');
            const statusDiv = document.getElementById('drawerStatus');
            if (item.roadmap_status_id) {
                const status = roadmapsData.find(r => r.id === item.roadmap_status_id);
                if (status) {
                    statusDiv.innerHTML = `
                        <span style="display: inline-flex; align-items: center; padding: 0.5rem 1rem; background: #f8f9fa; border-radius: 6px; font-weight: 500;">
                            <span class="status-indicator" style="background-color: ${status.color};"></span>
                            ${status.name}
                        </span>
                    `;
                    statusSection.style.display = 'block';
                } else {
                    statusSection.style.display = 'none';
                }
            } else {
                statusSection.style.display = 'none';
            }

            // Open the drawer
            const drawer = new bootstrap.Offcanvas(document.getElementById('roadmapDrawer'));
            drawer.show();
        }
    </script>
</body>
</html>
