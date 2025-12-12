@extends('layouts.inspinia')

@section('title', 'General Settings')

@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="card top-area">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">
                    <i class="ti ti-list me-2"></i>General Settings
                    <p class="text-muted fs-14 mb-0">ManConfigure your application's basic settings</p>
                </h5>
                <div class="d-flex justify-content-between align-items-center">
                    <a href="{{ route('settings.index') }}" class="btn btn-secondary">
                        <i class="ti ti-arrow-left me-1"></i> Back to Settings
                    </a>
                </div>
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

@if($errors->any())
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="ti ti-alert-circle me-2"></i>
        <ul class="mb-0">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="ti ti-settings me-2"></i>Application Configuration
                </h5>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('settings.general.update') }}" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <!-- Logo Upload -->
                    <div class="row mb-4">
                        <div class="col-md-12">
                            @if($settings && $settings->logo)
                                    <div class="avatar-xl">
                                        <img src="{{ asset('storage/' . $settings->logo) }}" alt="Logo" class="rounded" style="max-width: 100px; max-height: 100px;">
                                    </div>
                                @else
                                    <div class="avatar-xl">
                                        <span class="avatar-title rounded bg-light">
                                            <i class="ti ti-photo fs-1 text-muted"></i>
                                        </span>
                                    </div>
                                @endif
                            <label class="form-label">Upload Logo</label>
                            <div class="d-flex align-items-start gap-3">
                                
                                <div class="flex-grow-1">
                                    <input type="file" class="form-control @error('logo') is-invalid @enderror" id="logo" name="logo" accept="image/*">
                                    <small class="text-muted">Recommended size: 200x200px. Max file size: 2MB. Supported formats: JPG, PNG, SVG</small>
                                    @error('logo')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Product/Workspace Name -->
                    <div class="row mb-3">
                        <div class="col-md-12">
                            <label for="product_name" class="form-label">Name of Product/Workspace <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('product_name') is-invalid @enderror" id="product_name" name="product_name" placeholder="Enter product or workspace name" value="{{ old('product_name', $settings->product_name ?? '') }}" required>
                            @error('product_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Website URL -->
                    <div class="row mb-3">
                        <div class="col-md-12">
                            <label for="website_url" class="form-label">Website URL</label>
                            <input type="url" class="form-control @error('website_url') is-invalid @enderror" id="website_url" name="website_url" placeholder="https://example.com" value="{{ old('website_url', $settings->website_url ?? '') }}">
                            @error('website_url')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Public Subdomain URL -->
                    <div class="row mb-3">
                        <div class="col-md-12">
                            <label for="subdomain_url" class="form-label">Public Subdomain URL <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <input type="text" class="form-control @error('subdomain_url') is-invalid @enderror" id="subdomain_url" name="subdomain_url" placeholder="myteam" value="{{ old('subdomain_url', $settings->subdomain_url ?? '') }}" pattern="[a-z0-9-]+" title="Only lowercase letters, numbers, and hyphens allowed" required>
                                <span class="input-group-text">.{{ config('app.base_domain', request()->getHost()) }}</span>
                                @error('subdomain_url')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <small class="text-muted">Your public pages will be accessible at <strong><span id="subdomain-preview">{{ $settings->subdomain_url ?? 'myteam' }}</span>.{{ config('app.base_domain', request()->getHost()) }}/feedback</strong></small>
                        </div>
                    </div>

                    <!-- Block Search Engine Indexing -->
                    <div class="row mb-4">
                        <div class="col-md-12">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="block_search_indexing" name="block_search_indexing" value="1" {{ old('block_search_indexing', $settings->block_search_indexing ?? false) ? 'checked' : '' }}>
                                <label class="form-check-label" for="block_search_indexing">
                                    <strong>Block Search Engine Indexing</strong>
                                </label>
                            </div>
                            <small class="text-muted">When enabled, search engines like Google will be asked not to index your public pages. Useful for private or internal feedback boards.</small>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="row">
                        <div class="col-md-12">
                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-primary">
                                    <i class="ti ti-device-floppy me-1"></i> Update Settings
                                </button>
                                <a href="{{ route('settings.index') }}" class="btn btn-secondary">
                                    Cancel
                                </a>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Site Visibility Section -->
<div class="row mt-4">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="ti ti-eye me-2"></i>Site Visibility
                </h5>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('settings.general.update') }}">
                    @csrf
                    @method('PUT')

                    <!-- Hidden fields to preserve other settings -->
                    <input type="hidden" name="product_name" value="{{ $settings->product_name ?? '' }}">
                    <input type="hidden" name="subdomain_url" value="{{ $settings->subdomain_url ?? '' }}">
                    <input type="hidden" name="website_url" value="{{ $settings->website_url ?? '' }}">
                    @if($settings && $settings->block_search_indexing)
                        <input type="hidden" name="block_search_indexing" value="1">
                    @endif

                    <div class="row mb-4">
                        <div class="col-md-12">
                            <label class="form-label"><strong>Public Site Access</strong></label>
                            <div class="d-flex gap-4 mt-2">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="site_visibility" id="visibility_public" value="public" {{ old('site_visibility', $settings->site_visibility ?? 'public') === 'public' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="visibility_public">
                                        <i class="ti ti-world me-1"></i> Public
                                    </label>
                                    <div class="text-muted small">Anyone can view your public site</div>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="site_visibility" id="visibility_private" value="private" {{ old('site_visibility', $settings->site_visibility ?? 'public') === 'private' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="visibility_private">
                                        <i class="ti ti-lock me-1"></i> Private
                                    </label>
                                    <div class="text-muted small">Only invited users can view the site</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Private Site Info -->
                    <div class="row mb-3" id="private-site-info" style="{{ ($settings->site_visibility ?? 'public') === 'private' ? '' : 'display: none;' }}">
                        <div class="col-md-12">
                            <div class="alert alert-info mb-0">
                                <i class="ti ti-info-circle me-2"></i>
                                <strong>How Private Access Works:</strong>
                                <ul class="mb-0 mt-2">
                                    <li>Team members automatically have full access</li>
                                    <li>Invited users will enter their email on the public site</li>
                                    <li>They will receive a verification code via email</li>
                                    <li>After verification, they can view the site</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <button type="submit" class="btn btn-primary">
                                <i class="ti ti-device-floppy me-1"></i> Save Visibility Settings
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Access List Section (shown when private) -->
<div class="row mt-4" id="access-list-section" style="{{ ($settings->site_visibility ?? 'public') === 'private' ? '' : 'display: none;' }}">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">
                    <i class="ti ti-users me-2"></i>Invited Users
                </h5>
            </div>
            <div class="card-body">
                <!-- Add New Emails -->
                <form method="POST" action="{{ route('settings.site-access.add') }}" class="mb-4">
                    @csrf
                    <div class="row">
                        <div class="col-md-8">
                            <label for="emails" class="form-label">Add Email Addresses</label>
                            <textarea class="form-control @error('emails') is-invalid @enderror" id="emails" name="emails" rows="3" placeholder="Enter email addresses (one per line or comma-separated)&#10;example1@email.com&#10;example2@email.com, example3@email.com"></textarea>
                            @error('emails')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Enter one or more email addresses. Separate multiple emails with commas or new lines.</small>
                        </div>
                        <div class="col-md-4 d-flex align-items-end">
                            <button type="submit" class="btn btn-success">
                                <i class="ti ti-plus me-1"></i> Add to Access List
                            </button>
                        </div>
                    </div>
                </form>

                <!-- Access List Table -->
                @if(isset($siteAccessInvites) && $siteAccessInvites->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Email</th>
                                    <th>Status</th>
                                    <th>Added</th>
                                    <th width="100">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($siteAccessInvites as $invite)
                                    <tr>
                                        <td>{{ $invite->email }}</td>
                                        <td>
                                            @if($invite->verified_at)
                                                <span class="badge bg-success"><i class="ti ti-check me-1"></i>Verified</span>
                                            @else
                                                <span class="badge bg-warning text-dark"><i class="ti ti-clock me-1"></i>Pending</span>
                                            @endif
                                        </td>
                                        <td>{{ $invite->created_at->format('M d, Y') }}</td>
                                        <td>
                                            <form method="POST" action="{{ route('settings.site-access.remove', $invite) }}" class="d-inline" onsubmit="return confirm('Are you sure you want to remove this invite?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger">
                                                    <i class="ti ti-trash"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center text-muted py-4">
                        <i class="ti ti-users-group fs-1 d-block mb-2"></i>
                        <p class="mb-0">No users invited yet. Add email addresses above to grant access.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Maintenance Mode Section -->
<div class="row mt-4">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="ti ti-tool me-2"></i>Maintenance Mode
                </h5>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('settings.general.update') }}">
                    @csrf
                    @method('PUT')

                    <!-- Hidden fields to preserve other settings -->
                    <input type="hidden" name="product_name" value="{{ $settings->product_name ?? '' }}">
                    <input type="hidden" name="subdomain_url" value="{{ $settings->subdomain_url ?? '' }}">
                    <input type="hidden" name="website_url" value="{{ $settings->website_url ?? '' }}">
                    <input type="hidden" name="site_visibility" value="{{ $settings->site_visibility ?? 'public' }}">
                    @if($settings && $settings->block_search_indexing)
                        <input type="hidden" name="block_search_indexing" value="1">
                    @endif

                    <div class="row mb-4">
                        <div class="col-md-12">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="maintenance_mode" name="maintenance_mode" value="1" {{ old('maintenance_mode', $settings->maintenance_mode ?? false) ? 'checked' : '' }}>
                                <label class="form-check-label" for="maintenance_mode">
                                    <strong>Enable Maintenance Mode</strong>
                                </label>
                            </div>
                            <small class="text-muted">When enabled, visitors will see a maintenance page instead of your public site. Team members can still access the admin area.</small>
                        </div>
                    </div>

                    <!-- Maintenance Options (shown when maintenance mode is enabled) -->
                    <div id="maintenance-options" style="{{ ($settings->maintenance_mode ?? false) ? '' : 'display: none;' }}">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="maintenance_scheduled_at" class="form-label">Scheduled Start (Optional)</label>
                                <input type="datetime-local" class="form-control @error('maintenance_scheduled_at') is-invalid @enderror" id="maintenance_scheduled_at" name="maintenance_scheduled_at" value="{{ old('maintenance_scheduled_at', $settings->maintenance_scheduled_at ? $settings->maintenance_scheduled_at->format('Y-m-d\TH:i') : '') }}">
                                @error('maintenance_scheduled_at')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">Leave empty for immediate maintenance</small>
                            </div>
                            <div class="col-md-6">
                                <label for="maintenance_ends_at" class="form-label">Scheduled End (Optional)</label>
                                <input type="datetime-local" class="form-control @error('maintenance_ends_at') is-invalid @enderror" id="maintenance_ends_at" name="maintenance_ends_at" value="{{ old('maintenance_ends_at', $settings->maintenance_ends_at ? $settings->maintenance_ends_at->format('Y-m-d\TH:i') : '') }}">
                                @error('maintenance_ends_at')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">Maintenance will end automatically at this time</small>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-12">
                                <label for="maintenance_message" class="form-label">Custom Message (Optional)</label>
                                <textarea class="form-control @error('maintenance_message') is-invalid @enderror" id="maintenance_message" name="maintenance_message" rows="2" placeholder="We're currently performing scheduled maintenance. Please check back soon.">{{ old('maintenance_message', $settings->maintenance_message ?? '') }}</textarea>
                                @error('maintenance_message')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">This message will be shown to visitors during maintenance</small>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <button type="submit" class="btn btn-primary">
                                <i class="ti ti-device-floppy me-1"></i> Save Maintenance Settings
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Auto-dismiss alerts after 3 seconds
    const alerts = document.querySelectorAll('.alert.alert-dismissible');
    alerts.forEach(function(alert) {
        setTimeout(function() {
            alert.classList.remove('show');
            setTimeout(function() {
                alert.remove();
            }, 150);
        }, 3000);
    });

    // Preview logo before upload
    const logoInput = document.getElementById('logo');
    if (logoInput) {
        logoInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(event) {
                    const avatarImg = document.querySelector('.avatar-xl img');
                    if (avatarImg) {
                        avatarImg.src = event.target.result;
                    } else {
                        const avatarContainer = document.querySelector('.avatar-xl');
                        avatarContainer.innerHTML = `<img src="${event.target.result}" alt="Logo Preview" class="rounded" style="max-width: 100px; max-height: 100px;">`;
                    }
                };
                reader.readAsDataURL(file);
            }
        });
    }

    // Auto-generate subdomain from product name
    const productNameInput = document.getElementById('product_name');
    const subdomainUrlInput = document.getElementById('subdomain_url');
    const subdomainPreview = document.getElementById('subdomain-preview');

    function generateSlug(text) {
        if (!text) return '';
        return text
            .toLowerCase()
            .replace(/[^a-z0-9\s-]/g, '') // Remove special characters
            .replace(/\s+/g, '-') // Replace spaces with hyphens
            .replace(/-+/g, '-') // Replace multiple hyphens with single hyphen
            .replace(/^-+|-+$/g, ''); // Remove leading/trailing hyphens
    }

    if (productNameInput && subdomainUrlInput) {
        // Auto-generate on product name input (only if subdomain is empty)
        productNameInput.addEventListener('input', function() {
            if (!subdomainUrlInput.dataset.userEdited || subdomainUrlInput.value === '') {
                subdomainUrlInput.value = generateSlug(this.value);
                if (subdomainPreview) {
                    subdomainPreview.textContent = generateSlug(this.value) || 'myteam';
                }
            }
        });

        // Generate on page load if product name exists but subdomain doesn't
        if (productNameInput.value && !subdomainUrlInput.value) {
            subdomainUrlInput.value = generateSlug(productNameInput.value);
        }

        // Mark as user-edited when user manually changes it
        subdomainUrlInput.addEventListener('input', function() {
            this.dataset.userEdited = 'true';
            if (subdomainPreview) {
                subdomainPreview.textContent = this.value || 'myteam';
            }
        });
    }

    // Site Visibility Toggle
    const visibilityPublic = document.getElementById('visibility_public');
    const visibilityPrivate = document.getElementById('visibility_private');
    const privateSiteInfo = document.getElementById('private-site-info');
    const accessListSection = document.getElementById('access-list-section');

    function toggleVisibilityOptions() {
        const isPrivate = visibilityPrivate && visibilityPrivate.checked;
        if (privateSiteInfo) {
            privateSiteInfo.style.display = isPrivate ? '' : 'none';
        }
        if (accessListSection) {
            accessListSection.style.display = isPrivate ? '' : 'none';
        }
    }

    if (visibilityPublic) {
        visibilityPublic.addEventListener('change', toggleVisibilityOptions);
    }
    if (visibilityPrivate) {
        visibilityPrivate.addEventListener('change', toggleVisibilityOptions);
    }

    // Maintenance Mode Toggle
    const maintenanceMode = document.getElementById('maintenance_mode');
    const maintenanceOptions = document.getElementById('maintenance-options');

    if (maintenanceMode && maintenanceOptions) {
        maintenanceMode.addEventListener('change', function() {
            maintenanceOptions.style.display = this.checked ? '' : 'none';
        });
    }
});
</script>
@endpush
