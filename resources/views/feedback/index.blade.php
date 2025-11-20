@extends('layouts.inspinia')

@section('title', 'Feedback Management')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-flex align-items-center justify-content-between">
            <div>
                <h2 class="mb-0">Feedback Management</h2>
                <p class="text-muted fs-14 mb-0">Manage user feedback and ideas</p>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('settings.index') }}" class="btn btn-secondary">
                    <i class="ti ti-arrow-left me-1"></i> Back to Settings
                </a>
                <a href="{{ route('feedback.create') }}" class="btn btn-primary">
                    <i class="ti ti-plus me-1"></i> Add New Feedback
                </a>
            </div>
        </div>
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="ti ti-circle-check me-2"></i>{{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">
                    <i class="ti ti-messages me-2"></i>All Feedback
                </h5>
                <span class="badge badge-soft-success">{{ $feedbacks->total() }} Total</span>
            </div>
            <div class="card-body">
                @if($feedbacks->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead>
                                <tr>
                                    <th style="width: 50px;">#</th>
                                    <th>Idea</th>
                                    <th>Submitter</th>
                                    <th>Category</th>
                                    <th>Status</th>
                                    <th>Source</th>
                                    <th style="width: 150px;" class="text-end">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($feedbacks as $feedback)
                                    <tr>
                                        <td>{{ $feedback->id }}</td>
                                        <td>
                                            <strong>{{ \Illuminate\Support\Str::limit($feedback->idea, 60) }}</strong>
                                            @if($feedback->tags)
                                                <div class="mt-1">
                                                    @foreach($feedback->tags as $tag)
                                                        <span class="badge bg-secondary me-1">{{ $tag }}</span>
                                                    @endforeach
                                                </div>
                                            @endif
                                        </td>
                                        <td>
                                            {{ $feedback->name }}
                                            <br>
                                            <small class="text-muted">{{ $feedback->email }}</small>
                                        </td>
                                        <td>
                                            @if($feedback->category)
                                                <span class="badge" style="background-color: {{ $feedback->category->color }}; color: white;">
                                                    {{ $feedback->category->name }}
                                                </span>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($feedback->roadmap)
                                                <span class="badge" style="background-color: {{ $feedback->roadmap->color }}; color: white;">
                                                    {{ $feedback->roadmap->name }}
                                                </span>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td>
                                            <small class="badge bg-info">{{ $feedback->source }}</small>
                                        </td>
                                        <td class="text-end">
                                            <a href="{{ route('feedback.show', $feedback) }}" class="btn btn-sm btn-soft-primary" title="View">
                                                <i class="ti ti-eye"></i>
                                            </a>
                                            <a href="{{ route('feedback.edit', $feedback) }}" class="btn btn-sm btn-soft-warning" title="Edit">
                                                <i class="ti ti-edit"></i>
                                            </a>
                                            <form method="POST" action="{{ route('feedback.destroy', $feedback) }}" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this feedback?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-soft-danger" title="Delete">
                                                    <i class="ti ti-trash"></i>
                                                </button>
                                            </form>
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
                        <p class="text-muted">Start by adding your first feedback idea!</p>
                        <a href="{{ route('feedback.create') }}" class="btn btn-primary">
                            <i class="ti ti-plus me-1"></i> Add New Feedback
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
