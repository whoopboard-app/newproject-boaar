@extends('layouts.inspinia')

@section('title', 'Knowledge Board')

@push('styles')
<style>
    .board-cover {
        width: 60px;
        height: 60px;
        object-fit: cover;
        border-radius: 8px;
    }

    .public-url-badge {
        cursor: pointer;
        transition: all 0.3s;
    }

    .public-url-badge:hover {
        transform: scale(1.05);
    }
</style>
@endpush

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-flex justify-content-between align-items-center">
            <div>
                <h4 class="page-title">Knowledge Board</h4>
                <p class="text-muted fs-14 mb-0">Manage your knowledge boards and documentation</p>
            </div>
            <a href="{{ route('knowledge-board.create') }}" class="btn btn-primary">
                <i class="ti ti-plus me-1"></i>Create New Board
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
                    <i class="ti ti-book me-2"></i>All Knowledge Boards
                </h5>
                <span class="badge badge-soft-success">{{ $boards->count() }} Total</span>
            </div>
            <div class="card-body" data-table>
                @if($boards->count() > 0)
                    <!-- Search Input -->
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <div class="input-group">
                                <span class="input-group-text"><i class="ti ti-search"></i></span>
                                <input type="text" class="form-control" placeholder="Search knowledge boards..." data-table-search>
                            </div>
                        </div>
                    </div>

                    <!-- Table -->
                    <div class="table-responsive">
                        <table class="table table-hover table-striped align-middle" id="knowledgeBoardTable">
                            <thead class="table-light">
                                <tr>
                                    <th>Cover</th>
                                    <th>Board Name</th>
                                    <th>Document Type</th>
                                    <th>Visibility</th>
                                    <th>Board Owner</th>
                                    <th>Status</th>
                                    <th>Public URL</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($boards as $board)
                                    <tr>
                                        <td>
                                            @if($board->cover_page)
                                                <img src="{{ asset('storage/' . $board->cover_page) }}" alt="Cover" class="board-cover">
                                            @else
                                                <div class="board-cover bg-light d-flex align-items-center justify-content-center">
                                                    <i class="ti ti-photo text-muted"></i>
                                                </div>
                                            @endif
                                        </td>
                                        <td>
                                            <div>
                                                <h6 class="mb-1">{{ $board->name }}</h6>
                                                <small class="text-muted">{{ Str::limit($board->short_description, 50) }}</small>
                                            </div>
                                        </td>
                                        <td>
                                            @if($board->document_type == 'manual')
                                                <span class="badge bg-primary">Manual</span>
                                            @else
                                                <span class="badge bg-info">Help Document</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($board->visibility_type == 'public')
                                                <span class="badge bg-success"><i class="ti ti-world me-1"></i>Public</span>
                                            @else
                                                <span class="badge bg-warning"><i class="ti ti-lock me-1"></i>Private</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="avatar-sm me-2">
                                                    <span class="avatar-title rounded-circle bg-soft-primary text-primary">
                                                        {{ strtoupper(substr($board->boardOwner->name ?? 'N/A', 0, 2)) }}
                                                    </span>
                                                </div>
                                                <div>
                                                    <h6 class="mb-0">{{ $board->boardOwner->name ?? 'N/A' }}</h6>
                                                    <small class="text-muted">{{ $board->boardOwner->email ?? '' }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            @if($board->status == 'published')
                                                <span class="badge bg-success">Published</span>
                                            @elseif($board->status == 'unpublished')
                                                <span class="badge bg-danger">Unpublished</span>
                                            @else
                                                <span class="badge bg-secondary">Draft</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($board->has_public_url)
                                                <span class="badge bg-primary public-url-badge"
                                                      title="Click to copy"
                                                      onclick="copyPublicUrl('{{ url('/kb/' . $board->public_url) }}')">
                                                    <i class="ti ti-link me-1"></i>Copy URL
                                                </span>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('knowledge-board.show', $board) }}" class="btn btn-sm btn-soft-info" title="View">
                                                    <i class="ti ti-eye"></i>
                                                </a>
                                                <button type="button" class="btn btn-sm btn-soft-primary" title="Edit">
                                                    <i class="ti ti-edit"></i>
                                                </button>
                                                <button type="button" class="btn btn-sm btn-soft-danger" title="Delete">
                                                    <i class="ti ti-trash"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="ti ti-book-off fs-1 text-muted mb-3"></i>
                        <h5 class="text-muted">No Knowledge Boards Found</h5>
                        <p class="text-muted mb-4">Start by creating your first knowledge board.</p>
                        <a href="{{ route('knowledge-board.create') }}" class="btn btn-primary">
                            <i class="ti ti-plus me-1"></i>Create New Board
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
</script>
@endpush
