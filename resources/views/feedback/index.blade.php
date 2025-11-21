@extends('layouts.inspinia')

@section('title', 'Feedback Management')

@push('styles')
<style>
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
                <h4 class="page-title">Feedback Management</h4>
                <p class="text-muted fs-14 mb-0">Manage user feedback and ideas</p>
            </div>
            <div class="d-flex gap-2">
                @if(Auth::user()->canManageFeedback())
                <a href="{{ route('feedback.kanban') }}" class="btn btn-info" target="_blank">
                    <i class="ti ti-layout-kanban me-1"></i>View Feedback in Kanboard
                </a>
                <a href="{{ route('feedback.create') }}" class="btn btn-primary">
                    <i class="ti ti-plus me-1"></i>Add New Feedback
                </a>
                @endif
            </div>
        </div>
    </div>
</div>

@if(Auth::user()->isReadOnly())
    <div class="alert alert-info alert-dismissible fade show" role="alert">
        <i class="ti ti-info-circle me-2"></i>You are in <strong>read-only mode</strong>. You can view but not modify feedback.
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

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
                    <i class="ti ti-message-dots me-2"></i>All Feedback
                </h5>
                <span class="badge badge-soft-success">{{ $feedbacks->total() }} Total</span>
            </div>
            <div class="card-body" data-table>
                @if($feedbacks->count() > 0)
                    <!-- Search Input -->
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <div class="input-group">
                                <span class="input-group-text"><i class="ti ti-search"></i></span>
                                <input type="text" class="form-control" placeholder="Search feedback..." data-table-search>
                            </div>
                        </div>
                    </div>

                    <!-- Table -->
                    <div class="table-responsive">
                        <table class="table table-hover table-striped align-middle" id="feedbackTable">
                            <thead class="table-light">
                                <tr>
                                    <th>#</th>
                                    <th>Idea</th>
                                    <th>Submitter</th>
                                    <th>Category</th>
                                    <th>Status</th>
                                    <th>Visibility</th>
                                    <th>Source</th>
                                    <th>Tags</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($feedbacks as $feedback)
                                    <tr>
                                        <td>{{ $feedback->id }}</td>
                                        <td>
                                            <div>
                                                <h6 class="mb-1">{{ \Illuminate\Support\Str::limit($feedback->idea, 50) }}</h6>
                                                @if($feedback->value_description)
                                                    <small class="text-muted">{{ Str::limit($feedback->value_description, 60) }}</small>
                                                @endif
                                            </div>
                                        </td>
                                        <td>
                                            <div>
                                                <strong>{{ $feedback->name }}</strong>
                                                <br>
                                                <small class="text-muted">{{ $feedback->email }}</small>
                                            </div>
                                        </td>
                                        <td>
                                            @if($feedback->category)
                                                <span class="badge" style="background-color: {{ $feedback->category->color }};">
                                                    {{ $feedback->category->name }}
                                                </span>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($feedback->roadmap)
                                                <span class="badge" style="background-color: {{ $feedback->roadmap->color }};">
                                                    {{ $feedback->roadmap->name }}
                                                </span>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($feedback->is_public)
                                                <span class="badge bg-success">Public</span>
                                            @else
                                                <span class="badge bg-secondary">Private</span>
                                            @endif
                                        </td>
                                        <td>
                                            <small class="badge bg-info">{{ $feedback->source }}</small>
                                        </td>
                                        <td>
                                            @if($feedback->tags && count($feedback->tags) > 0)
                                                @foreach(array_slice($feedback->tags, 0, 2) as $tag)
                                                    <span class="tag-badge">{{ $tag }}</span>
                                                @endforeach
                                                @if(count($feedback->tags) > 2)
                                                    <span class="tag-badge">+{{ count($feedback->tags) - 2 }}</span>
                                                @endif
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('feedback.show', $feedback) }}" class="btn btn-sm btn-soft-info" title="View">
                                                    <i class="ti ti-eye"></i>
                                                </a>
                                                @if(Auth::user()->canManageFeedback())
                                                <a href="{{ route('feedback.edit', $feedback) }}" class="btn btn-sm btn-soft-primary" title="Edit">
                                                    <i class="ti ti-edit"></i>
                                                </a>
                                                @endif
                                                @if(Auth::user()->canDelete())
                                                <form method="POST" action="{{ route('feedback.destroy', $feedback) }}" class="d-inline delete-form">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-soft-danger" title="Delete">
                                                        <i class="ti ti-trash"></i>
                                                    </button>
                                                </form>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="d-flex justify-content-center mt-3">
                        {{ $feedbacks->links() }}
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="ti ti-message-off fs-1 text-muted mb-3"></i>
                        <h5 class="text-muted">No Feedback Found</h5>
                        @if(Auth::user()->canManageFeedback())
                        <p class="text-muted mb-4">Start by adding your first feedback idea!</p>
                        <a href="{{ route('feedback.create') }}" class="btn btn-primary">
                            <i class="ti ti-plus me-1"></i>Add New Feedback
                        </a>
                        @else
                        <p class="text-muted mb-4">There are no feedback entries to display.</p>
                        @endif
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
    const dismissibleAlerts = document.querySelectorAll('.alert.alert-dismissible');

    dismissibleAlerts.forEach(function(alert) {
        setTimeout(function() {
            alert.classList.remove('show');
            setTimeout(function() {
                alert.remove();
            }, 150);
        }, 3000);
    });

    // Delete confirmation
    const deleteForms = document.querySelectorAll('.delete-form');
    deleteForms.forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            if (confirm('Are you sure you want to delete this feedback? This action cannot be undone.')) {
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
                        noResultsRow.innerHTML = '<td colspan="9" class="text-center py-4"><i class="ti ti-search-off fs-1 text-muted mb-2 d-block"></i><h6 class="text-muted">No results found</h6><p class="text-muted mb-0">Try adjusting your search terms</p></td>';
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
