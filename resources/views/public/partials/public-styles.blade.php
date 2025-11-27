{{-- Shared CSS Styles for Public Pages --}}
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

    /* Header Actions */
    .header-actions {
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .btn-add-idea {
        background: var(--primary-color);
        border: none;
        padding: 0.5rem 1.5rem;
        border-radius: 6px;
        color: white;
        font-weight: 500;
        transition: all 0.2s;
    }

    .btn-add-idea:hover {
        background: #4752c4;
        color: white;
        transform: translateY(-1px);
    }

    .btn-subscribe {
        background: var(--primary-color);
        border: none;
        padding: 0.5rem 1.5rem;
        border-radius: 6px;
        text-decoration: none;
        color: white;
        font-weight: 500;
        transition: all 0.2s;
    }

    .btn-subscribe:hover {
        background: #4752c4;
        color: white;
        transform: translateY(-1px);
    }

    .btn-login {
        background: transparent;
        border: 1px solid var(--border-color);
        padding: 0.5rem 1rem;
        border-radius: 6px;
        color: var(--text-primary);
        font-weight: 500;
        text-decoration: none;
        transition: all 0.2s;
    }

    .btn-login:hover {
        border-color: var(--primary-color);
        color: var(--primary-color);
    }

    .btn-logout {
        background: transparent;
        border: 1px solid #dc3545;
        padding: 0.5rem 1rem;
        border-radius: 6px;
        color: #dc3545;
        font-weight: 500;
        text-decoration: none;
        transition: all 0.2s;
    }

    .btn-logout:hover {
        background: #dc3545;
        color: white;
    }

    .user-info {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.375rem 0.75rem;
        background: #f3f4f6;
        border-radius: 6px;
        font-size: 0.875rem;
        color: var(--text-secondary);
    }

    .user-info i {
        color: var(--primary-color);
    }

    /* Navigation Tabs */
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

    /* Common Layout */
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

    /* Category Items */
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

    /* Category Badge */
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

    /* Status Badge */
    .status-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.375rem;
        padding: 0.25rem 0.75rem;
        border-radius: 12px;
        font-size: 0.8125rem;
        font-weight: 500;
    }

    /* Empty State */
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

    /* Offcanvas */
    .offcanvas.offcanvas-end {
        width: 650px !important;
    }

    .offcanvas-header {
        border-bottom: 1px solid var(--border-color);
        background: #f9fafb;
    }

    .offcanvas-title {
        font-weight: 600;
        color: var(--text-primary);
    }

    /* Forms */
    .form-label {
        font-weight: 500;
        color: var(--text-primary);
        margin-bottom: 0.5rem;
    }

    .form-control:focus,
    .form-select:focus {
        border-color: var(--primary-color);
        box-shadow: 0 0 0 0.2rem rgba(88, 101, 242, 0.15);
    }

    .form-text {
        font-size: 0.8125rem;
        color: var(--text-secondary);
    }

    /* Responsive */
    @media (max-width: 768px) {
        .public-content {
            grid-template-columns: 1fr;
        }

        .sidebar {
            position: static;
        }

        .offcanvas.offcanvas-end {
            width: 100% !important;
        }

        .header-actions {
            flex-wrap: wrap;
            gap: 0.5rem;
        }
    }
</style>
