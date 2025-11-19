@extends('layouts.inspinia')

@section('title', $knowledgeBoard->name)

@push('styles')
<style>
    .board-cover-thumbnail {
        width: 100%;
        max-width: 300px;
        height: 100px;
        object-fit: cover;
        border-radius: 8px;
        cursor: pointer;
        transition: transform 0.3s;
        border: 2px solid #dee2e6;
    }

    .board-cover-thumbnail:hover {
        transform: scale(1.05);
        border-color: #007bff;
    }

    .cover-image-wrapper {
        position: relative;
        display: inline-block;
    }

    .image-overlay {
        position: absolute;
        top: 5px;
        right: 5px;
        background: rgba(0, 0, 0, 0.7);
        color: white;
        padding: 4px;
        border-radius: 50%;
        font-size: 0.875rem;
        display: flex;
        align-items: center;
        justify-content: center;
        width: 28px;
        height: 28px;
        pointer-events: none;
    }

    /* Tree View Styles */
    .category-tree {
        list-style: none;
        padding-left: 0;
    }

    .category-tree li {
        position: relative;
        padding: 8px 12px;
        margin: 4px 0;
        border-left: 2px solid transparent;
        transition: all 0.3s;
    }

    .category-tree li:hover {
        background-color: #f8f9fa;
        border-left-color: #007bff;
    }

    .category-tree .parent-category {
        font-weight: 600;
        font-size: 1rem;
        color: #212529;
    }

    .category-tree .child-categories {
        list-style: none;
        padding-left: 25px;
        margin-top: 8px;
    }

    .category-tree .sub-child-categories {
        list-style: none;
        padding-left: 25px;
        margin-top: 4px;
    }

    .category-item {
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .category-info {
        display: flex;
        align-items: center;
        gap: 10px;
        flex: 1;
    }

    .category-icon {
        font-size: 1.25rem;
        color: #007bff;
    }

    .category-badge {
        font-size: 0.75rem;
        padding: 2px 8px;
    }

    .category-actions {
        display: flex;
        gap: 5px;
        opacity: 0;
        transition: opacity 0.3s;
    }

    .category-tree li:hover .category-actions {
        opacity: 1;
    }

    .tree-connector {
        color: #6c757d;
        margin-right: 5px;
    }

    .info-item {
        padding: 1rem;
        border-left: 3px solid #007bff;
        background-color: #f8f9fa;
        margin-bottom: 1rem;
        border-radius: 4px;
    }

    .info-item h6 {
        color: #6c757d;
        font-size: 0.875rem;
        text-transform: uppercase;
        margin-bottom: 0.5rem;
        font-weight: 600;
    }

    .info-item p {
        margin-bottom: 0;
        font-size: 1rem;
        color: #212529;
    }

    .public-url-box {
        background-color: #e7f3ff;
        border: 1px solid #b3d9ff;
        border-radius: 4px;
        padding: 1rem;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .public-url-text {
        font-family: monospace;
        color: #0066cc;
        word-break: break-all;
    }

    /* Lightbox Styles */
    .lightbox-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.9);
        z-index: 9999;
        display: none;
        align-items: center;
        justify-content: center;
        padding: 20px;
    }

    .lightbox-overlay.active {
        display: flex;
    }

    .lightbox-content {
        position: relative;
        max-width: 90%;
        max-height: 90%;
        display: flex;
        flex-direction: column;
        align-items: center;
    }

    .lightbox-image {
        max-width: 100%;
        max-height: 80vh;
        object-fit: contain;
        border-radius: 8px;
        box-shadow: 0 0 30px rgba(255, 255, 255, 0.3);
    }

    .lightbox-controls {
        display: flex;
        gap: 10px;
        margin-top: 20px;
    }

    .lightbox-btn {
        background-color: #fff;
        color: #333;
        border: none;
        padding: 10px 20px;
        border-radius: 4px;
        cursor: pointer;
        font-size: 1rem;
        display: flex;
        align-items: center;
        gap: 8px;
        transition: background-color 0.3s;
    }

    .lightbox-btn:hover {
        background-color: #f0f0f0;
    }

    .lightbox-close {
        position: absolute;
        top: -40px;
        right: 0;
        background: none;
        border: none;
        color: white;
        font-size: 2rem;
        cursor: pointer;
        padding: 0;
        width: 40px;
        height: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    /* Tabs Styling */
    .nav-tabs {
        border-bottom: 2px solid #dee2e6;
    }

    .nav-tabs .nav-link {
        border: none;
        color: #6c757d;
        font-weight: 500;
        padding: 0.75rem 1.5rem;
        border-bottom: 3px solid transparent;
        transition: all 0.3s;
    }

    .nav-tabs .nav-link:hover {
        border-color: transparent;
        color: #007bff;
    }

    .nav-tabs .nav-link.active {
        color: #007bff;
        border-bottom-color: #007bff;
        background-color: transparent;
    }
</style>
@endpush

@section('content')
<!-- Board Info Card -->
<div class="row">
    <div class="col-12">
        @include('knowledge-board.partials.board-info-card')
    </div>
</div>

<!-- Tabs -->
<div class="row mt-3">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <!-- Nav Tabs -->
                <ul class="nav nav-tabs mb-4" id="boardTabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="articles-tab" data-bs-toggle="tab" data-bs-target="#articles" type="button" role="tab">
                            <i class="ti ti-files me-2"></i>Articles
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="categories-tab" data-bs-toggle="tab" data-bs-target="#categories" type="button" role="tab">
                            <i class="ti ti-folder-tree me-2"></i>Categories
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="settings-tab" data-bs-toggle="tab" data-bs-target="#settings" type="button" role="tab">
                            <i class="ti ti-settings me-2"></i>Board Settings
                        </button>
                    </li>
                </ul>

                <!-- Tab Content -->
                <div class="tab-content" id="boardTabsContent">
                    <!-- Articles Tab -->
                    <div class="tab-pane fade show active" id="articles" role="tabpanel">
                        @include('knowledge-board.partials.articles-listing')
                    </div>

                    <!-- Categories Tab -->
                    <div class="tab-pane fade" id="categories" role="tabpanel">
                        @include('knowledge-board.partials.categories-tree')
                    </div>

                    <!-- Settings Tab -->
                    <div class="tab-pane fade" id="settings" role="tabpanel">
                        @include('knowledge-board.partials.board-settings')
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Lightbox -->
@if($knowledgeBoard->cover_page)
<div class="lightbox-overlay" id="lightbox" onclick="closeLightbox(event)">
    <div class="lightbox-content" onclick="event.stopPropagation()">
        <button class="lightbox-close" onclick="closeLightbox()">&times;</button>
        <img src="{{ asset('storage/' . $knowledgeBoard->cover_page) }}" alt="Cover Page" class="lightbox-image" id="lightboxImage">
        <div class="lightbox-controls">
            <button class="lightbox-btn" onclick="downloadImage()">
                <i class="ti ti-download"></i>
                Save Image
            </button>
            <button class="lightbox-btn" onclick="closeLightbox()">
                <i class="ti ti-x"></i>
                Close
            </button>
        </div>
    </div>
</div>
@endif
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Auto-dismiss all alerts after 3 seconds
    const dismissibleAlerts = document.querySelectorAll('.alert.alert-dismissible');
    dismissibleAlerts.forEach(function(alert) {
        setTimeout(function() {
            alert.classList.remove('show');
            setTimeout(function() {
                alert.remove();
            }, 150);
        }, 3000);
    });

    // Check for success messages in sessionStorage (for category/article operations)
    const categorySuccess = sessionStorage.getItem('category_success');
    const articleSuccess = sessionStorage.getItem('article_success');

    if (categorySuccess) {
        showAlert(categorySuccess, 'success');
        sessionStorage.removeItem('category_success');
    }

    if (articleSuccess) {
        showAlert(articleSuccess, 'success');
        sessionStorage.removeItem('article_success');
    }
});

// Helper function to show alerts
function showAlert(message, type = 'success') {
    const alertDiv = document.createElement('div');
    const iconClass = type === 'success' ? 'ti-circle-check' : 'ti-alert-circle';
    const alertClass = type === 'success' ? 'alert-success' : 'alert-danger';

    alertDiv.className = `alert ${alertClass} alert-dismissible fade show position-fixed top-0 start-50 translate-middle-x mt-3`;
    alertDiv.style.zIndex = '9999';
    alertDiv.innerHTML = `
        <i class="ti ${iconClass} me-2"></i>${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    `;
    document.body.appendChild(alertDiv);

    // Auto remove after 3 seconds
    setTimeout(function() {
        alertDiv.classList.remove('show');
        setTimeout(function() {
            alertDiv.remove();
        }, 150);
    }, 3000);
}

// Function to copy public URL to clipboard
function copyPublicUrl(url) {
    navigator.clipboard.writeText(url).then(function() {
        // Show success message
        const alertDiv = document.createElement('div');
        alertDiv.className = 'alert alert-success alert-dismissible fade show position-fixed top-0 start-50 translate-middle-x mt-3';
        alertDiv.style.zIndex = '9999';
        alertDiv.innerHTML = `
            <i class="ti ti-check me-2"></i>Public URL copied to clipboard!
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        `;
        document.body.appendChild(alertDiv);

        // Auto remove after 3 seconds
        setTimeout(() => {
            alertDiv.remove();
        }, 3000);
    }, function(err) {
        alert('Failed to copy URL');
    });
}

// Lightbox functions
function openLightbox() {
    const lightbox = document.getElementById('lightbox');
    if (lightbox) {
        lightbox.classList.add('active');
        document.body.style.overflow = 'hidden'; // Prevent scrolling
    }
}

function closeLightbox(event) {
    if (event) {
        // Only close if clicking on the overlay itself
        if (event.target.id !== 'lightbox') {
            return;
        }
    }

    const lightbox = document.getElementById('lightbox');
    if (lightbox) {
        lightbox.classList.remove('active');
        document.body.style.overflow = ''; // Restore scrolling
    }
}

function downloadImage() {
    const imageUrl = document.getElementById('lightboxImage').src;
    const imageName = '{{ $knowledgeBoard->name }}' + '_cover_image.jpg';

    // Create a temporary anchor element
    const link = document.createElement('a');
    link.href = imageUrl;
    link.download = imageName;
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);

    // Show success message
    const alertDiv = document.createElement('div');
    alertDiv.className = 'alert alert-success alert-dismissible fade show position-fixed top-0 start-50 translate-middle-x mt-3';
    alertDiv.style.zIndex = '9999';
    alertDiv.innerHTML = `
        <i class="ti ti-check me-2"></i>Image downloaded successfully!
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    `;
    document.body.appendChild(alertDiv);

    // Auto remove after 3 seconds
    setTimeout(() => {
        alertDiv.remove();
    }, 3000);
}

// Close lightbox with Escape key
document.addEventListener('keydown', function(event) {
    if (event.key === 'Escape') {
        closeLightbox();
    }
});

// Handle article delete confirmation
const deleteArticleForms = document.querySelectorAll('.delete-article-form');
deleteArticleForms.forEach(form => {
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        if (confirm('Are you sure you want to delete this article? This action cannot be undone.')) {
            this.submit();
        }
    });
});

// Handle category delete confirmation
const deleteCategoryForms = document.querySelectorAll('.delete-category-form');
deleteCategoryForms.forEach(form => {
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        if (confirm('Are you sure you want to delete this category? This action cannot be undone.')) {
            this.submit();
        }
    });
});
</script>
@endpush
