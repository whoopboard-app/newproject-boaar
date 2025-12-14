@extends('backoffice.layouts.app')

@section('title', 'Clients')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="mb-1">Clients</h4>
            <p class="text-muted mb-0">Manage all team owners (clients)</p>
        </div>
    </div>

    <!-- Search & Filters -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('backoffice.clients.index') }}" class="row g-3">
                <div class="col-md-6">
                    <div class="input-group">
                        <span class="input-group-text"><i class="ti ti-search"></i></span>
                        <input type="text" class="form-control" name="search" placeholder="Search by name or email..." value="{{ request('search') }}">
                    </div>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="ti ti-search me-1"></i> Search
                    </button>
                </div>
                @if(request('search'))
                <div class="col-md-2">
                    <a href="{{ route('backoffice.clients.index') }}" class="btn btn-outline-secondary w-100">
                        <i class="ti ti-x me-1"></i> Clear
                    </a>
                </div>
                @endif
            </form>
        </div>
    </div>

    <!-- Clients Table -->
    <div class="card">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th style="width: 50px;">#</th>
                            <th>Client</th>
                            <th>Email</th>
                            <th>Teams</th>
                            <th>Joined</th>
                            <th style="width: 100px;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($clients as $client)
                        <tr>
                            <td>{{ $client->id }}</td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <img src="{{ $client->avatar_url ?? 'https://ui-avatars.com/api/?name=' . urlencode($client->name) . '&background=random' }}"
                                         alt="{{ $client->name }}"
                                         class="rounded-circle me-2"
                                         width="40"
                                         height="40">
                                    <div>
                                        <div class="fw-medium">{{ $client->name }}</div>
                                        @if($client->is_super_admin)
                                            <span class="badge bg-dark">Super Admin</span>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td>
                                <a href="mailto:{{ $client->email }}" class="text-muted">
                                    {{ $client->email }}
                                </a>
                            </td>
                            <td>
                                <span class="badge bg-primary">{{ $client->owned_teams_count }} {{ Str::plural('team', $client->owned_teams_count) }}</span>
                            </td>
                            <td>
                                <span title="{{ $client->created_at->format('M d, Y H:i') }}">
                                    {{ $client->created_at->diffForHumans() }}
                                </span>
                            </td>
                            <td>
                                <a href="{{ route('backoffice.clients.show', $client) }}" class="btn btn-sm btn-outline-primary">
                                    <i class="ti ti-eye"></i>
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-4 text-muted">
                                <i class="ti ti-users-minus fs-1 d-block mb-2"></i>
                                No clients found
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if($clients->hasPages())
        <div class="card-footer">
            {{ $clients->withQueryString()->links() }}
        </div>
        @endif
    </div>

    <!-- Stats Summary -->
    <div class="row mt-4">
        <div class="col-md-4">
            <div class="stat-card">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <div class="stat-label">Total Clients</div>
                        <div class="stat-value">{{ $clients->total() }}</div>
                    </div>
                    <div class="stat-icon bg-primary bg-opacity-10 text-primary">
                        <i class="ti ti-users"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
