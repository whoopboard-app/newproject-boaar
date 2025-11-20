@extends('layouts.inspinia')

@section('title', 'Team Management')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-flex align-items-center justify-content-between">
            <div>
                <h2 class="mb-0">{{ $team->name }}</h2>
                <p class="text-muted fs-14 mb-0">Manage your team members and invitations</p>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('dashboard') }}" class="btn btn-secondary">
                    <i class="ti ti-arrow-left me-1"></i> Back to Dashboard
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

@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="ti ti-alert-circle me-2"></i>{{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<div class="row">
    <!-- Invite New Member -->
    <div class="col-lg-12 mb-4">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="ti ti-user-plus me-2"></i>Invite Team Member
                </h5>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('team.invitation.invite') }}">
                    @csrf
                    <div class="row">
                        <div class="col-md-5 mb-3 mb-md-0">
                            <label for="email" class="form-label">Email Address</label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror"
                                   id="email" name="email" value="{{ old('email') }}"
                                   placeholder="Enter email address" required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-4 mb-3 mb-md-0">
                            <label for="role" class="form-label">Role</label>
                            <select class="form-select @error('role') is-invalid @enderror"
                                    id="role" name="role" required>
                                <option value="">Select role</option>
                                <option value="admin">Admin - Full Access</option>
                                <option value="moderator">Moderator - Can Edit (No Delete)</option>
                                <option value="idea_submitter">Idea Submitter - Can Create Feedback</option>
                                <option value="viewer">Viewer - Read Only</option>
                            </select>
                            @error('role')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-3">
                            <label class="form-label d-none d-md-block">&nbsp;</label>
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="ti ti-send me-1"></i> Send Invite
                            </button>
                        </div>
                    </div>
                </form>

                <!-- Role Information -->
                <div class="alert alert-info mt-3 mb-0">
                    <h6 class="alert-heading mb-2"><i class="ti ti-info-circle me-1"></i>Role Permissions</h6>
                    <ul class="mb-0 ps-3">
                        <li><strong>Owner:</strong> Only the signup user - full access to everything</li>
                        <li><strong>Admin:</strong> Full access to manage team members, projects, and settings</li>
                        <li><strong>Moderator:</strong> Can edit logs, knowledge boards, and feedback (no delete option)</li>
                        <li><strong>Idea Submitter:</strong> Can create feedback only (no delete option)</li>
                        <li><strong>Viewer:</strong> Read-only access to all resources</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <!-- Team Members -->
    <div class="col-lg-12 mb-4">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">
                    <i class="ti ti-users me-2"></i>Team Members
                </h5>
                <span class="badge badge-soft-success">{{ $members->count() }} Members</span>
            </div>
            <div class="card-body">
                @if($members->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Role</th>
                                    <th>Joined</th>
                                    <th class="text-end" style="width: 150px;">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($members as $member)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <img src="{{ $member->avatar_url }}"
                                                     alt="{{ $member->name }}"
                                                     class="rounded-circle me-2"
                                                     style="width: 32px; height: 32px; object-fit: cover;">
                                                <strong>{{ $member->name }}</strong>
                                                @if($team->isOwner($member))
                                                    <span class="badge bg-warning ms-2">Owner</span>
                                                @endif
                                            </div>
                                        </td>
                                        <td>{{ $member->email }}</td>
                                        <td>
                                            @if($team->isOwner($member))
                                                <span class="badge bg-warning">Owner</span>
                                            @else
                                                <form method="POST" action="{{ route('team.member.update-role', $member) }}" class="d-inline role-update-form">
                                                    @csrf
                                                    @method('PUT')
                                                    <select class="form-select form-select-sm d-inline-block w-auto role-select"
                                                            name="role"
                                                            {{ Auth::user()->roleInTeam() !== 'owner' && Auth::user()->roleInTeam() !== 'admin' ? 'disabled' : '' }}>
                                                        <option value="admin" {{ $member->pivot->role === 'admin' ? 'selected' : '' }}>Admin</option>
                                                        <option value="moderator" {{ $member->pivot->role === 'moderator' ? 'selected' : '' }}>Moderator</option>
                                                        <option value="idea_submitter" {{ $member->pivot->role === 'idea_submitter' ? 'selected' : '' }}>Idea Submitter</option>
                                                        <option value="viewer" {{ $member->pivot->role === 'viewer' ? 'selected' : '' }}>Viewer</option>
                                                    </select>
                                                </form>
                                            @endif
                                        </td>
                                        <td>{{ $member->pivot->created_at->format('M j, Y') }}</td>
                                        <td class="text-end">
                                            @if(!$team->isOwner($member) && $member->id !== Auth::id())
                                                @if(Auth::user()->roleInTeam() === 'owner' || Auth::user()->roleInTeam() === 'admin')
                                                    <form method="POST" action="{{ route('team.member.remove', $member) }}" class="d-inline" onsubmit="return confirm('Are you sure you want to remove this member?')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-danger">
                                                            <i class="ti ti-user-minus"></i> Remove
                                                        </button>
                                                    </form>
                                                @endif
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-4">
                        <i class="ti ti-users fs-48 text-muted"></i>
                        <p class="text-muted mt-2">No team members yet</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Pending Invitations -->
    @if($pendingInvitations->count() > 0)
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">
                    <i class="ti ti-mail me-2"></i>Pending Invitations
                </h5>
                <span class="badge badge-soft-warning">{{ $pendingInvitations->count() }} Pending</span>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead>
                            <tr>
                                <th>Email</th>
                                <th>Role</th>
                                <th>Invited By</th>
                                <th>Sent</th>
                                <th>Expires</th>
                                <th class="text-end" style="width: 100px;">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($pendingInvitations as $invitation)
                                <tr>
                                    <td>{{ $invitation->email }}</td>
                                    <td>
                                        <span class="badge bg-info">{{ ucfirst(str_replace('_', ' ', $invitation->role)) }}</span>
                                    </td>
                                    <td>{{ $invitation->inviter->name }}</td>
                                    <td>{{ $invitation->created_at->format('M j, Y') }}</td>
                                    <td>
                                        @if($invitation->expires_at->isFuture())
                                            <span class="text-success">
                                                {{ $invitation->expires_at->diffForHumans() }}
                                            </span>
                                        @else
                                            <span class="text-danger">Expired</span>
                                        @endif
                                    </td>
                                    <td class="text-end">
                                        @if(Auth::user()->roleInTeam() === 'owner' || Auth::user()->roleInTeam() === 'admin')
                                            <form method="POST" action="{{ route('team.invitation.cancel', $invitation) }}" class="d-inline" onsubmit="return confirm('Are you sure you want to cancel this invitation?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger">
                                                    <i class="ti ti-x"></i> Cancel
                                                </button>
                                            </form>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
@endsection

@push('scripts')
<script>
    // Auto-submit role update form when role changes
    document.addEventListener('DOMContentLoaded', function() {
        const roleSelects = document.querySelectorAll('.role-select');

        roleSelects.forEach(function(select) {
            const originalValue = select.value;

            select.addEventListener('change', function() {
                if (confirm('Are you sure you want to change this member\'s role?')) {
                    this.closest('.role-update-form').submit();
                } else {
                    // Revert to original value
                    this.value = originalValue;
                }
            });
        });
    });
</script>
@endpush
