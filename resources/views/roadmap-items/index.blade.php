@extends('layouts.inspinia')

@section('title', 'Roadmap Items')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-flex align-items-center justify-content-between">
            <div>
                <h2 class="mb-0">Roadmap Items</h2>
                <p class="text-muted fs-14 mb-0">Manage your roadmap items</p>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('roadmap-items.kanban') }}" class="btn btn-info">
                    <i class="ti ti-layout-kanban me-1"></i> Kanban View
                </a>
                <a href="{{ route('roadmap-items.create') }}" class="btn btn-primary">
                    <i class="ti ti-plus me-1"></i> Add Roadmap Item
                </a>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Idea</th>
                                <th>Status</th>
                                <th>Tags</th>
                                <th>Linked Feedback</th>
                                <th>PM Tool ID</th>
                                <th>Created</th>
                                <th class="text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($roadmapItems as $item)
                                <tr>
                                    <td>#{{ $item->id }}</td>
                                    <td>
                                        <a href="{{ route('roadmap-items.show', $item) }}" class="text-decoration-none">
                                            <strong>{{ Str::limit($item->idea, 50) }}</strong>
                                        </a>
                                    </td>
                                    <td>
                                        @if($item->roadmapStatus)
                                            <span class="badge" style="background-color: {{ $item->roadmapStatus->color }}; color: white;">
                                                {{ $item->roadmapStatus->name }}
                                            </span>
                                        @else
                                            <span class="badge bg-secondary">No Status</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($item->tags && count($item->tags) > 0)
                                            @foreach($item->tags as $tag)
                                                <span class="badge bg-info">{{ $tag }}</span>
                                            @endforeach
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($item->feedback)
                                            <a href="{{ route('feedback.show', $item->feedback) }}" class="text-decoration-none">
                                                <i class="ti ti-link me-1"></i>{{ Str::limit($item->feedback->idea, 30) }}
                                            </a>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>{{ $item->external_pm_tool_id ?? '-' }}</td>
                                    <td>{{ $item->created_at->format('M d, Y') }}</td>
                                    <td class="text-end">
                                        <a href="{{ route('roadmap-items.show', $item) }}" class="btn btn-sm btn-info" title="View">
                                            <i class="ti ti-eye"></i>
                                        </a>
                                        <a href="{{ route('roadmap-items.edit', $item) }}" class="btn btn-sm btn-primary" title="Edit">
                                            <i class="ti ti-edit"></i>
                                        </a>
                                        <form action="{{ route('roadmap-items.destroy', $item) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this roadmap item?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger" title="Delete">
                                                <i class="ti ti-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center py-4 text-muted">
                                        No roadmap items yet. Create your first one!
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-3">
                    {{ $roadmapItems->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
