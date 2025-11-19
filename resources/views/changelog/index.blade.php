@extends('layouts.inspinia')

@section('title', 'Changelog')

@push('styles')
<style>
    .changelog-cover {
        width: 60px;
        height: 34px;
        object-fit: cover;
        border-radius: 4px;
    }

    .tag-badge {
        display: inline-block;
        padding: 2px 8px;
        margin: 2px;
        font-size: 0.75rem;
        border-radius: 3px;
        background-color: #e9ecef;
        color: #495057;
    }
</style>
@endpush

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-flex justify-content-between align-items-center">
            <div>
                <h4 class="page-title">Changelog</h4>
                <p class="text-muted fs-14 mb-0">Manage and view all changelog entries</p>
            </div>
            <a href="{{ route('changelog.create') }}" class="btn btn-primary">
                <i class="ti ti-plus me-1"></i>Add New Changelog
            </a>
        </div>
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="ti ti-circle-check me-2"></i>{{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="ti ti-alert-circle me-2"></i>{{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">
                    <i class="ti ti-list me-2"></i>All Changelogs
                </h5>
                <span class="badge badge-soft-success">{{ $changelogs->count() }} Total</span>
            </div>
            <div class="card-body" data-table>
                @if($changelogs->count() > 0)
                    <!-- Search Input -->
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <div class="input-group">
                                <span class="input-group-text"><i class="ti ti-search"></i></span>
                                <input type="text" class="form-control" placeholder="Search changelogs..." data-table-search>
                            </div>
                        </div>
                    </div>

                    <!-- Table -->
                    <div class="table-responsive">
                        <table class="table table-hover table-striped align-middle" id="changelogTable">
                            <thead class="table-light">
                                <tr>
                                    <th>Cover</th>
                                    <th>Title</th>
                                    <th>Category</th>
                                    <th>Author</th>
                                    <th>Published Date</th>
                                    <th>Status</th>
                                    <th>Tags</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($changelogs as $changelog)
                                    <tr>
                                        <td>
                                            @if($changelog->cover_image)
                                                <img src="{{ asset('storage/' . $changelog->cover_image) }}" alt="Cover" class="changelog-cover">
                                            @else
                                                <div class="changelog-cover bg-light d-flex align-items-center justify-content-center">
                                                    <i class="ti ti-photo text-muted"></i>
                                                </div>
                                            @endif
                                        </td>
                                        <td>
                                            <div>
                                                <h6 class="mb-1">{{ $changelog->title }}</h6>
                                                <small class="text-muted">{{ Str::limit($changelog->short_description, 60) }}</small>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge" style="background-color: {{ $changelog->category->color }};">
                                                {{ $changelog->category->name }}
                                            </span>
                                        </td>
                                        <td>{{ $changelog->author_name }}</td>
                                        <td>{{ $changelog->published_date->format('M d, Y') }}</td>
                                        <td>
                                            @if($changelog->status == 'published')
                                                <span class="badge bg-success">Published</span>
                                            @elseif($changelog->status == 'draft')
                                                <span class="badge bg-secondary">Draft</span>
                                            @else
                                                <span class="badge bg-info">Scheduled</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($changelog->tags && count($changelog->tags) > 0)
                                                @foreach(array_slice($changelog->tags, 0, 2) as $tag)
                                                    <span class="tag-badge">{{ $tag }}</span>
                                                @endforeach
                                                @if(count($changelog->tags) > 2)
                                                    <span class="tag-badge">+{{ count($changelog->tags) - 2 }}</span>
                                                @endif
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('changelog.show', $changelog) }}" class="btn btn-sm btn-soft-info" title="View">
                                                    <i class="ti ti-eye"></i>
                                                </a>
                                                <a href="{{ route('changelog.edit', $changelog) }}" class="btn btn-sm btn-soft-primary" title="Edit">
                                                    <i class="ti ti-edit"></i>
                                                </a>
                                                <form action="{{ route('changelog.destroy', $changelog) }}" method="POST" class="d-inline delete-form">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-soft-danger" title="Delete">
                                                        <i class="ti ti-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="ti ti-file-off fs-1 text-muted mb-3"></i>
                        <h5 class="text-muted">No Changelogs Found</h5>
                        <p class="text-muted mb-4">Start by creating your first changelog entry.</p>
                        <a href="{{ route('changelog.create') }}" class="btn btn-primary">
                            <i class="ti ti-plus me-1"></i>Add New Changelog
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Auto-dismiss all alerts after 3 seconds
    const alerts = document.querySelectorAll('.alert:not(.alert-dismissible)');
    const dismissibleAlerts = document.querySelectorAll('.alert.alert-dismissible');

    dismissibleAlerts.forEach(function(alert) {
        setTimeout(function() {
            alert.classList.remove('show');
            setTimeout(function() {
                alert.remove();
            }, 150);
        }, 3000);
    });

    // Check for success message in sessionStorage
    const successMessage = sessionStorage.getItem('changelog_success');
    if (successMessage) {
        // Create and show success alert
        const alertDiv = document.createElement('div');
        alertDiv.className = 'alert alert-success alert-dismissible fade show';
        alertDiv.setAttribute('role', 'alert');
        alertDiv.innerHTML = `
            <i class="ti ti-circle-check me-2"></i>${successMessage}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        `;

        // Insert at the top of the page content
        const firstRow = document.querySelector('.row');
        firstRow.parentNode.insertBefore(alertDiv, firstRow.nextSibling);

        // Clear the message from sessionStorage
        sessionStorage.removeItem('changelog_success');

        // Auto-dismiss after 3 seconds
        setTimeout(function() {
            alertDiv.classList.remove('show');
            setTimeout(function() {
                alertDiv.remove();
            }, 150); // Wait for fade out animation
        }, 3000);
    }

    // Delete confirmation
    const deleteForms = document.querySelectorAll('.delete-form');
    deleteForms.forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            if (confirm('Are you sure you want to delete this changelog? This action cannot be undone.')) {
                this.submit();
            }
        });
    });

    // Custom table search functionality
    const tableContainer = document.querySelector('[data-table]');

    if (tableContainer) {
        const searchInput = tableContainer.querySelector('[data-table-search]');
        const table = tableContainer.querySelector('table');

        if (searchInput && table) {
            const tbody = table.querySelector('tbody');
            const rows = tbody.querySelectorAll('tr');

            searchInput.addEventListener('input', function() {
                const searchTerm = this.value.toLowerCase().trim();

                rows.forEach(row => {
                    const text = row.textContent.toLowerCase();

                    if (text.includes(searchTerm)) {
                        row.style.display = '';
                    } else {
                        row.style.display = 'none';
                    }
                });

                // Show "no results" message if all rows are hidden
                const visibleRows = Array.from(rows).filter(row => row.style.display !== 'none');

                let noResultsRow = tbody.querySelector('.no-results-row');

                if (visibleRows.length === 0 && searchTerm !== '') {
                    if (!noResultsRow) {
                        noResultsRow = document.createElement('tr');
                        noResultsRow.className = 'no-results-row';
                        noResultsRow.innerHTML = '<td colspan="8" class="text-center py-4"><i class="ti ti-search-off fs-1 text-muted mb-2 d-block"></i><h6 class="text-muted">No results found</h6><p class="text-muted mb-0">Try adjusting your search terms</p></td>';
                        tbody.appendChild(noResultsRow);
                    }
                } else if (noResultsRow) {
                    noResultsRow.remove();
                }
            });
        }
    }
});
</script>
@endpush
