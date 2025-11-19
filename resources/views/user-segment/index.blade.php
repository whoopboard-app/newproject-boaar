@extends('layouts.inspinia')

@section('title', 'User Segments')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-flex justify-content-between align-items-center">
            <div>
                <h4 class="page-title">User Segments</h4>
                <p class="text-muted fs-14 mb-0">Manage your user segments</p>
            </div>
            <a href="{{ route('user-segment.create') }}" class="btn btn-primary">
                <i class="ti ti-plus me-1"></i>Add Segment
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
            <div class="card-body">
                @if($segments->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Status</th>
                                    <th>Description</th>
                                    <th>Attributes</th>
                                    <th>Created</th>
                                    <th style="width: 120px;">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($segments as $segment)
                                    <tr>
                                        <td>
                                            <div class="fw-semibold">{{ $segment->name }}</div>
                                        </td>
                                        <td>
                                            @if($segment->status == 'active')
                                                <span class="badge bg-success">Active</span>
                                            @else
                                                <span class="badge bg-secondary">Inactive</span>
                                            @endif
                                        </td>
                                        <td>
                                            <small class="text-muted">{{ Str::limit($segment->description, 50) ?: '-' }}</small>
                                        </td>
                                        <td>
                                            <div class="d-flex flex-wrap gap-1">
                                                @php
                                                    $attributeCount = 0;
                                                    $attributes = [
                                                        'revenue_ranges', 'locations', 'age_ranges', 'genders',
                                                        'languages', 'user_types', 'plan_types', 'engagement_levels',
                                                        'usage_frequencies'
                                                    ];
                                                    foreach ($attributes as $attr) {
                                                        if (!empty($segment->$attr)) {
                                                            $attributeCount++;
                                                        }
                                                    }
                                                @endphp
                                                @if($attributeCount > 0)
                                                    <span class="badge bg-info">{{ $attributeCount }} Attributes</span>
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </div>
                                        </td>
                                        <td>
                                            <small class="text-muted">{{ $segment->created_at->format('M d, Y') }}</small>
                                        </td>
                                        <td>
                                            <div class="d-flex gap-1">
                                                <a href="{{ route('user-segment.show', $segment) }}" class="btn btn-sm btn-soft-info" title="View">
                                                    <i class="ti ti-eye"></i>
                                                </a>
                                                <a href="{{ route('user-segment.edit', $segment) }}" class="btn btn-sm btn-soft-primary" title="Edit">
                                                    <i class="ti ti-edit"></i>
                                                </a>
                                                <form action="{{ route('user-segment.destroy', $segment) }}" method="POST" class="d-inline delete-form">
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
                        <i class="ti ti-users-group fs-1 text-muted mb-3"></i>
                        <h5 class="text-muted">No User Segments Yet</h5>
                        <p class="text-muted mb-3">Start creating user segments to organize your users.</p>
                        <a href="{{ route('user-segment.create') }}" class="btn btn-primary">
                            <i class="ti ti-plus me-1"></i>Add First Segment
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
    const successMessage = sessionStorage.getItem('segment_success');
    if (successMessage) {
        const alertDiv = document.createElement('div');
        alertDiv.className = 'alert alert-success alert-dismissible fade show';
        alertDiv.innerHTML = `
            <i class="ti ti-circle-check me-2"></i>${successMessage}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        `;
        document.querySelector('.row').parentNode.insertBefore(alertDiv, document.querySelector('.row').nextSibling);
        sessionStorage.removeItem('segment_success');

        // Auto-dismiss after 3 seconds
        setTimeout(function() {
            alertDiv.classList.remove('show');
            setTimeout(function() {
                alertDiv.remove();
            }, 150);
        }, 3000);
    }

    // Handle delete confirmations
    const deleteForms = document.querySelectorAll('.delete-form');
    deleteForms.forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            if (confirm('Are you sure you want to delete this user segment? This action cannot be undone.')) {
                this.submit();
            }
        });
    });
});
</script>
@endpush
