@extends('backoffice.layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="mb-4">
        <h1 class="h3 mb-1">System Overview</h1>
        <p class="text-muted mb-0">Welcome to the backoffice admin portal</p>
    </div>

    <!-- Stats Cards -->
    <div class="row g-4 mb-4">
        <div class="col-xl-3 col-md-6">
            <a href="{{ route('backoffice.clients.index') }}" class="text-decoration-none">
                <div class="stat-card">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <div class="stat-label">Total Clients</div>
                            <div class="stat-value">{{ number_format($stats['total_clients']) }}</div>
                        </div>
                        <div class="stat-icon bg-purple bg-opacity-10 text-purple" style="background-color: rgba(139, 92, 246, 0.1) !important; color: #8b5cf6 !important;">
                            <i class="ti ti-briefcase"></i>
                        </div>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="stat-card">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <div class="stat-label">Total Users</div>
                        <div class="stat-value">{{ number_format($stats['total_users']) }}</div>
                    </div>
                    <div class="stat-icon bg-primary bg-opacity-10 text-primary">
                        <i class="ti ti-users"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="stat-card">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <div class="stat-label">Total Teams</div>
                        <div class="stat-value">{{ number_format($stats['total_teams']) }}</div>
                    </div>
                    <div class="stat-icon bg-success bg-opacity-10 text-success">
                        <i class="ti ti-building"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="stat-card">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <div class="stat-label">Total Feedbacks</div>
                        <div class="stat-value">{{ number_format($stats['total_feedbacks']) }}</div>
                    </div>
                    <div class="stat-icon bg-warning bg-opacity-10 text-warning">
                        <i class="ti ti-message-circle"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Activity -->
    <div class="row g-4">
        <!-- Recent Users -->
        <div class="col-xl-6">
            <div class="recent-card">
                <div class="card-header d-flex align-items-center">
                    <i class="ti ti-users me-2 text-primary"></i>
                    Recent Users
                </div>
                <div class="card-body p-0">
                    @forelse($recentUsers as $user)
                        <div class="recent-item d-flex align-items-center justify-content-between">
                            <div class="d-flex align-items-center">
                                <img src="{{ $user->avatar_url ?? 'https://ui-avatars.com/api/?name=' . urlencode($user->name) . '&background=random' }}"
                                     alt="{{ $user->name }}"
                                     class="rounded-circle me-3"
                                     width="40"
                                     height="40">
                                <div>
                                    <div class="fw-medium">{{ $user->name }}</div>
                                    <small class="text-muted">{{ $user->email }}</small>
                                </div>
                            </div>
                            <div class="text-end">
                                <small class="text-muted">{{ $user->created_at->diffForHumans() }}</small>
                                @if($user->is_super_admin)
                                    <span class="badge bg-dark ms-2">Super Admin</span>
                                @endif
                            </div>
                        </div>
                    @empty
                        <div class="recent-item text-center text-muted py-4">
                            No users found
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Recent Teams -->
        <div class="col-xl-6">
            <div class="recent-card">
                <div class="card-header d-flex align-items-center">
                    <i class="ti ti-building me-2 text-success"></i>
                    Recent Teams
                </div>
                <div class="card-body p-0">
                    @forelse($recentTeams as $team)
                        <div class="recent-item d-flex align-items-center justify-content-between">
                            <div class="d-flex align-items-center">
                                <div class="rounded-circle bg-success bg-opacity-10 text-success d-flex align-items-center justify-content-center me-3"
                                     style="width: 40px; height: 40px;">
                                    <i class="ti ti-building"></i>
                                </div>
                                <div>
                                    <div class="fw-medium">{{ $team->name }}</div>
                                    <small class="text-muted">{{ $team->slug }}</small>
                                </div>
                            </div>
                            <div class="text-end">
                                <small class="text-muted">{{ $team->created_at->diffForHumans() }}</small>
                                @if($team->owner)
                                    <div class="small text-muted">Owner: {{ $team->owner->name }}</div>
                                @endif
                            </div>
                        </div>
                    @empty
                        <div class="recent-item text-center text-muted py-4">
                            No teams found
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
