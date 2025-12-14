@extends('backoffice.layouts.app')

@section('title', 'Client Details')

@section('content')
<div class="container-fluid">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('backoffice.clients.index') }}">Clients</a></li>
            <li class="breadcrumb-item active">{{ $client->name }}</li>
        </ol>
    </nav>

    <!-- Client Profile Card -->
    <div class="card mb-4">
        <div class="card-body">
            <div class="d-flex align-items-center">
                <img src="{{ $client->avatar_url ?? 'https://ui-avatars.com/api/?name=' . urlencode($client->name) . '&background=random&size=100' }}"
                     alt="{{ $client->name }}"
                     class="rounded-circle me-4"
                     width="80"
                     height="80">
                <div class="flex-grow-1">
                    <h4 class="mb-1">
                        {{ $client->name }}
                        @if($client->is_super_admin)
                            <span class="badge bg-dark ms-2">Super Admin</span>
                        @endif
                    </h4>
                    <p class="text-muted mb-2">
                        <i class="ti ti-mail me-1"></i>
                        <a href="mailto:{{ $client->email }}">{{ $client->email }}</a>
                    </p>
                    <div class="d-flex gap-3 text-muted small">
                        <span><i class="ti ti-calendar me-1"></i> Joined {{ $client->created_at->format('M d, Y') }}</span>
                        @if($client->last_login_at)
                            <span><i class="ti ti-login me-1"></i> Last login {{ $client->last_login_at->diffForHumans() }}</span>
                        @endif
                        @if($client->timezone)
                            <span><i class="ti ti-clock me-1"></i> {{ $client->timezone }}</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Teams Section -->
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">
                <i class="ti ti-building me-2"></i>
                Owned Teams ({{ $client->ownedTeams->count() }})
            </h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th>Team Name</th>
                            <th>Slug</th>
                            <th>Members</th>
                            <th>Feedbacks</th>
                            <th>Changelogs</th>
                            <th>Created</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($client->ownedTeams as $team)
                        <tr>
                            <td>
                                <div class="fw-medium">{{ $team->name }}</div>
                                @if($team->description)
                                    <small class="text-muted">{{ Str::limit($team->description, 50) }}</small>
                                @endif
                            </td>
                            <td>
                                <code>{{ $team->slug }}</code>
                            </td>
                            <td>
                                <span class="badge bg-secondary">{{ $team->members_count ?? 0 }}</span>
                            </td>
                            <td>
                                <span class="badge bg-warning">{{ $team->feedbacks_count ?? 0 }}</span>
                            </td>
                            <td>
                                <span class="badge bg-info">{{ $team->changelogs_count ?? 0 }}</span>
                            </td>
                            <td>
                                <span title="{{ $team->created_at->format('M d, Y H:i') }}">
                                    {{ $team->created_at->diffForHumans() }}
                                </span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-4 text-muted">
                                <i class="ti ti-building-community fs-1 d-block mb-2"></i>
                                No teams owned by this client
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Back Button -->
    <div class="mt-4">
        <a href="{{ route('backoffice.clients.index') }}" class="btn btn-outline-secondary">
            <i class="ti ti-arrow-left me-1"></i> Back to Clients
        </a>
    </div>
</div>
@endsection
