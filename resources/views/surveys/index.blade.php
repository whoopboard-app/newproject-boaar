@extends('layouts.inspinia')

@section('title', 'Surveys')

@push('styles')
<style>
    .survey-type-badge {
        display: inline-block;
        padding: 4px 10px;
        font-size: 0.75rem;
        font-weight: 500;
        border-radius: 4px;
        text-transform: uppercase;
    }
    .type-nps { background-color: #dbeafe; color: #1e40af; }
    .type-csat { background-color: #dcfce7; color: #166534; }
    .type-ces { background-color: #fef3c7; color: #92400e; }
    .type-pmf { background-color: #f3e8ff; color: #7c3aed; }
    .type-custom { background-color: #f1f5f9; color: #475569; }
</style>
@endpush

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-flex justify-content-between align-items-center">
            <div>
                <h4 class="page-title">Surveys</h4>
                <p class="text-muted fs-14 mb-0">Collect feedback from your users with targeted surveys</p>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('surveys.create') }}" class="btn btn-primary">
                    <i class="ti ti-plus me-1"></i>Add Survey
                </a>
            </div>
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
                    <i class="ti ti-clipboard-list me-2"></i>All Surveys
                </h5>
                <span class="badge badge-soft-success">{{ $surveys->total() }} Total</span>
            </div>
            <div class="card-body" data-table>
                @if($surveys->count() > 0)
                    <!-- Search Input -->
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <div class="input-group">
                                <span class="input-group-text"><i class="ti ti-search"></i></span>
                                <input type="text" class="form-control" placeholder="Search surveys..." data-table-search>
                            </div>
                        </div>
                    </div>

                    <!-- Table -->
                    <div class="table-responsive">
                        <table class="table table-hover table-striped align-middle" id="surveysTable">
                            <thead class="table-light">
                                <tr>
                                    <th>Survey Name</th>
                                    <th>Type</th>
                                    <th>Status</th>
                                    <th>Responses</th>
                                    <th>Created</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($surveys as $survey)
                                    <tr>
                                        <td>
                                            <div>
                                                <h6 class="mb-1">{{ $survey->title }}</h6>
                                                <small class="text-muted">{{ Str::limit($survey->general_settings['question'] ?? '', 50) }}</small>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="survey-type-badge type-{{ $survey->type }}">
                                                {{ strtoupper(str_replace('_', ' ', $survey->type)) }}
                                            </span>
                                        </td>
                                        <td>
                                            @if($survey->status === 'published')
                                                <span class="badge bg-success">Active</span>
                                            @elseif($survey->status === 'paused')
                                                <span class="badge bg-warning">Paused</span>
                                            @else
                                                <span class="badge bg-secondary">Draft</span>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="fw-medium">{{ $survey->responses_count }}</span>
                                            <small class="text-muted">responses</small>
                                        </td>
                                        <td>
                                            <small class="text-muted">{{ $survey->created_at->format('M d, Y') }}</small>
                                        </td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('surveys.show', $survey) }}" class="btn btn-sm btn-soft-info" title="View">
                                                    <i class="ti ti-eye"></i>
                                                </a>
                                                <a href="{{ route('surveys.install', $survey) }}" class="btn btn-sm btn-soft-success" title="Install">
                                                    <i class="ti ti-code"></i>
                                                </a>
                                                <a href="{{ route('surveys.edit', $survey) }}" class="btn btn-sm btn-soft-primary" title="Edit">
                                                    <i class="ti ti-edit"></i>
                                                </a>
                                                <form method="POST" action="{{ route('surveys.toggle-active', $survey) }}" class="d-inline">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm btn-soft-warning" title="{{ $survey->status === 'published' ? 'Pause' : 'Activate' }}">
                                                        <i class="ti ti-{{ $survey->status === 'published' ? 'player-pause' : 'player-play' }}"></i>
                                                    </button>
                                                </form>
                                                <form method="POST" action="{{ route('surveys.destroy', $survey) }}" class="d-inline delete-form">
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

                    <!-- Pagination -->
                    <div class="d-flex justify-content-center mt-3">
                        {{ $surveys->links() }}
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="ti ti-clipboard-list fs-1 text-muted mb-3 d-block"></i>
                        <h5 class="text-muted">No Surveys Yet</h5>
                        <p class="text-muted mb-4">Create your first survey to start collecting user feedback!</p>
                        <a href="{{ route('surveys.create') }}" class="btn btn-primary">
                            <i class="ti ti-plus me-1"></i>Add Survey
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
    // Auto-dismiss alerts after 3 seconds
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
            if (confirm('Are you sure you want to delete this survey? All responses will be permanently deleted. This action cannot be undone.')) {
                this.submit();
            }
        });
    });

    // Table search functionality
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
                    row.style.display = text.includes(searchTerm) ? '' : 'none';
                });

                // Show "no results" message if all rows are hidden
                const visibleRows = Array.from(rows).filter(row => row.style.display !== 'none');
                let noResultsRow = tbody.querySelector('.no-results-row');

                if (visibleRows.length === 0 && searchTerm !== '') {
                    if (!noResultsRow) {
                        noResultsRow = document.createElement('tr');
                        noResultsRow.className = 'no-results-row';
                        noResultsRow.innerHTML = '<td colspan="6" class="text-center py-4"><i class="ti ti-search-off fs-1 text-muted mb-2 d-block"></i><h6 class="text-muted">No results found</h6><p class="text-muted mb-0">Try adjusting your search terms</p></td>';
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
