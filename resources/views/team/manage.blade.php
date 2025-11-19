@extends('layouts.inspinia')

@section('title', 'Team Management')

@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/choices.js/public/assets/styles/choices.min.css">
<style>
    /* Match Choices.js height and styling with Bootstrap form-control */
    .choices__inner {
        min-height: 39.51px !important;
        height: 39.51px !important;
        padding: 0.375rem 0.75rem !important;
        background-color: #fff !important;
        border: 1px solid #dee2e6 !important;
        border-radius: 0.25rem !important;
        font-size: 1rem !important;
        line-height: 1.5 !important;
        display: flex !important;
        align-items: center !important;
    }

    .choices__list--single {
        padding: 0 !important;
        display: flex !important;
        align-items: center !important;
    }

    .choices[data-type*=select-one] .choices__inner {
        padding-bottom: 0.375rem !important;
        padding-top: 0.375rem !important;
    }

    .choices__list--dropdown .choices__item--selectable {
        padding: 0.5rem 1rem !important;
    }

    .choices__item--selectable {
        line-height: 1.5 !important;
    }
</style>
@endpush

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <h4 class="page-title">Team Management</h4>
            <p class="text-muted fs-14">Invite and manage team members for your workspace</p>
        </div>
    </div>
</div>

<!-- Invite New Member Section -->
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="ti ti-user-plus me-2"></i>Invite New Team Member
                </h5>
            </div>
            <div class="card-body">
                <form>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="email" class="form-label">Email Address <span class="text-danger">*</span></label>
                                <input type="email" class="form-control" id="email" name="email" placeholder="Enter email address" required>
                                <div class="form-text">We'll send an invitation to this email address</div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="role" class="form-label">Role <span class="text-danger">*</span></label>
                                <select class="form-select" id="role" name="role" data-choices required>
                                    <option value="member" selected>Member</option>
                                    <option value="admin">Admin</option>
                                    <option value="viewer">Viewer</option>
                                    <option value="owner">Owner</option>
                                </select>
                                <div class="form-text">Choose appropriate role for this member</div>
                            </div>
                        </div>
                        <div class="col-md-2 d-flex align-items-end">
                            <div class="mb-3 w-100">
                                <button type="submit" class="btn btn-primary w-100">
                                    <i class="ti ti-send me-1"></i>Send Invite
                                </button>
                            </div>
                        </div>
                    </div>
                </form>

                <!-- Role Information -->
                <div class="alert alert-info mt-3">
                    <h6 class="alert-heading mb-2"><i class="ti ti-info-circle me-1"></i>Role Permissions</h6>
                    <ul class="mb-0 ps-3">
                        <li><strong>Owner:</strong> Full access to all workspace settings, billing, and team management</li>
                        <li><strong>Admin:</strong> Can manage team members, projects, and workspace settings</li>
                        <li><strong>Member:</strong> Can create and edit projects, limited settings access</li>
                        <li><strong>Viewer:</strong> Read-only access to projects and resources</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Pending Invitations Section -->
<div class="row mt-3">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">
                    <i class="ti ti-clock me-2"></i>Pending Invitations
                </h5>
                <span class="badge badge-soft-warning">3 Pending</span>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover table-centered mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Email</th>
                                <th>Role</th>
                                <th>Invited By</th>
                                <th>Sent Date</th>
                                <th>Status</th>
                                <th class="text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-sm me-2">
                                            <span class="avatar-title rounded-circle bg-soft-primary text-primary">
                                                <i class="ti ti-mail"></i>
                                            </span>
                                        </div>
                                        <div>
                                            <h6 class="mb-0">john.doe@example.com</h6>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge badge-soft-primary">Admin</span>
                                </td>
                                <td>{{ Auth::user()->name }}</td>
                                <td>2 days ago</td>
                                <td>
                                    <span class="badge bg-warning">
                                        <i class="ti ti-clock me-1"></i>Pending
                                    </span>
                                </td>
                                <td class="text-center">
                                    <div class="btn-group" role="group">
                                        <button type="button" class="btn btn-sm btn-soft-info" title="Resend Invitation">
                                            <i class="ti ti-refresh"></i>
                                        </button>
                                        <button type="button" class="btn btn-sm btn-soft-danger" title="Cancel Invitation">
                                            <i class="ti ti-x"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-sm me-2">
                                            <span class="avatar-title rounded-circle bg-soft-primary text-primary">
                                                <i class="ti ti-mail"></i>
                                            </span>
                                        </div>
                                        <div>
                                            <h6 class="mb-0">jane.smith@example.com</h6>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge badge-soft-success">Member</span>
                                </td>
                                <td>{{ Auth::user()->name }}</td>
                                <td>5 days ago</td>
                                <td>
                                    <span class="badge bg-warning">
                                        <i class="ti ti-clock me-1"></i>Pending
                                    </span>
                                </td>
                                <td class="text-center">
                                    <div class="btn-group" role="group">
                                        <button type="button" class="btn btn-sm btn-soft-info" title="Resend Invitation">
                                            <i class="ti ti-refresh"></i>
                                        </button>
                                        <button type="button" class="btn btn-sm btn-soft-danger" title="Cancel Invitation">
                                            <i class="ti ti-x"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-sm me-2">
                                            <span class="avatar-title rounded-circle bg-soft-primary text-primary">
                                                <i class="ti ti-mail"></i>
                                            </span>
                                        </div>
                                        <div>
                                            <h6 class="mb-0">bob.wilson@example.com</h6>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge badge-soft-info">Viewer</span>
                                </td>
                                <td>{{ Auth::user()->name }}</td>
                                <td>1 week ago</td>
                                <td>
                                    <span class="badge bg-warning">
                                        <i class="ti ti-clock me-1"></i>Pending
                                    </span>
                                </td>
                                <td class="text-center">
                                    <div class="btn-group" role="group">
                                        <button type="button" class="btn btn-sm btn-soft-info" title="Resend Invitation">
                                            <i class="ti ti-refresh"></i>
                                        </button>
                                        <button type="button" class="btn btn-sm btn-soft-danger" title="Cancel Invitation">
                                            <i class="ti ti-x"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Current Team Members Section -->
<div class="row mt-3">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">
                    <i class="ti ti-users me-2"></i>Team Members
                </h5>
                <span class="badge badge-soft-success">5 Active</span>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover table-centered mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Member</th>
                                <th>Email</th>
                                <th>Role</th>
                                <th>Joined Date</th>
                                <th>Status</th>
                                <th class="text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-sm me-2">
                                            <span class="avatar-title rounded-circle bg-primary text-white">
                                                {{ strtoupper(substr(Auth::user()->name, 0, 2)) }}
                                            </span>
                                        </div>
                                        <div>
                                            <h6 class="mb-0">{{ Auth::user()->name }}</h6>
                                            <small class="text-muted">You</small>
                                        </div>
                                    </div>
                                </td>
                                <td>{{ Auth::user()->email }}</td>
                                <td>
                                    <span class="badge badge-soft-danger">Owner</span>
                                </td>
                                <td>Jan 15, 2024</td>
                                <td>
                                    <span class="badge bg-success">
                                        <i class="ti ti-circle-check me-1"></i>Active
                                    </span>
                                </td>
                                <td class="text-center">
                                    <span class="text-muted">—</span>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-sm me-2">
                                            <span class="avatar-title rounded-circle bg-success text-white">
                                                AS
                                            </span>
                                        </div>
                                        <div>
                                            <h6 class="mb-0">Alice Johnson</h6>
                                        </div>
                                    </div>
                                </td>
                                <td>alice.johnson@example.com</td>
                                <td>
                                    <span class="badge badge-soft-primary">Admin</span>
                                </td>
                                <td>Feb 10, 2024</td>
                                <td>
                                    <span class="badge bg-success">
                                        <i class="ti ti-circle-check me-1"></i>Active
                                    </span>
                                </td>
                                <td class="text-center">
                                    <div class="btn-group" role="group">
                                        <button type="button" class="btn btn-sm btn-soft-primary" title="Edit Role">
                                            <i class="ti ti-edit"></i>
                                        </button>
                                        <button type="button" class="btn btn-sm btn-soft-danger" title="Remove Member">
                                            <i class="ti ti-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-sm me-2">
                                            <span class="avatar-title rounded-circle bg-info text-white">
                                                MB
                                            </span>
                                        </div>
                                        <div>
                                            <h6 class="mb-0">Michael Brown</h6>
                                        </div>
                                    </div>
                                </td>
                                <td>michael.brown@example.com</td>
                                <td>
                                    <span class="badge badge-soft-success">Member</span>
                                </td>
                                <td>Mar 05, 2024</td>
                                <td>
                                    <span class="badge bg-success">
                                        <i class="ti ti-circle-check me-1"></i>Active
                                    </span>
                                </td>
                                <td class="text-center">
                                    <div class="btn-group" role="group">
                                        <button type="button" class="btn btn-sm btn-soft-primary" title="Edit Role">
                                            <i class="ti ti-edit"></i>
                                        </button>
                                        <button type="button" class="btn btn-sm btn-soft-danger" title="Remove Member">
                                            <i class="ti ti-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-sm me-2">
                                            <span class="avatar-title rounded-circle bg-warning text-white">
                                                SD
                                            </span>
                                        </div>
                                        <div>
                                            <h6 class="mb-0">Sarah Davis</h6>
                                        </div>
                                    </div>
                                </td>
                                <td>sarah.davis@example.com</td>
                                <td>
                                    <span class="badge badge-soft-success">Member</span>
                                </td>
                                <td>Mar 20, 2024</td>
                                <td>
                                    <span class="badge bg-success">
                                        <i class="ti ti-circle-check me-1"></i>Active
                                    </span>
                                </td>
                                <td class="text-center">
                                    <div class="btn-group" role="group">
                                        <button type="button" class="btn btn-sm btn-soft-primary" title="Edit Role">
                                            <i class="ti ti-edit"></i>
                                        </button>
                                        <button type="button" class="btn btn-sm btn-soft-danger" title="Remove Member">
                                            <i class="ti ti-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-sm me-2">
                                            <span class="avatar-title rounded-circle bg-secondary text-white">
                                                TW
                                            </span>
                                        </div>
                                        <div>
                                            <h6 class="mb-0">Tom Wilson</h6>
                                        </div>
                                    </div>
                                </td>
                                <td>tom.wilson@example.com</td>
                                <td>
                                    <span class="badge badge-soft-info">Viewer</span>
                                </td>
                                <td>Apr 01, 2024</td>
                                <td>
                                    <span class="badge bg-success">
                                        <i class="ti ti-circle-check me-1"></i>Active
                                    </span>
                                </td>
                                <td class="text-center">
                                    <div class="btn-group" role="group">
                                        <button type="button" class="btn btn-sm btn-soft-primary" title="Edit Role">
                                            <i class="ti ti-edit"></i>
                                        </button>
                                        <button type="button" class="btn btn-sm btn-soft-danger" title="Remove Member">
                                            <i class="ti ti-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/choices.js/public/assets/scripts/choices.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize all elements with data-choices attribute
        var choicesElements = document.querySelectorAll('[data-choices]');

        choicesElements.forEach(function(element) {
            new Choices(element, {
                searchEnabled: true,
                itemSelectText: '',
                removeItemButton: false,
            });
        });
    });
</script>
@endpush
