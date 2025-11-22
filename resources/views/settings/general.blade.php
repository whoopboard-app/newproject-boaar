@extends('layouts.inspinia')

@section('title', 'General Settings')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-flex align-items-center justify-content-between">
            <div>
                <h2 class="mb-0">General Settings</h2>
                <p class="text-muted fs-14 mb-0">Configure your application's basic settings</p>
            </div>
            <a href="{{ route('settings.index') }}" class="btn btn-secondary">
                <i class="ti ti-arrow-left me-1"></i> Back to Settings
            </a>
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
                            <label class="form-label">Upload Logo</label>
                            <div class="d-flex align-items-start gap-3">
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

                    <!-- Unique URL -->
                    <div class="row mb-3">
                        <div class="col-md-12">
                            <label for="unique_url" class="form-label">Unique URL <span class="badge bg-info ms-2">Auto-Generated</span></label>
                            <div class="input-group">
                                <span class="input-group-text">{{ request()->getSchemeAndHttpHost() }}/</span>
                                <input type="text" class="form-control @error('unique_url') is-invalid @enderror" id="unique_url" name="unique_url" placeholder="your-unique-slug" value="{{ old('unique_url', $settings->unique_url ?? '') }}" pattern="[a-z0-9-]+" title="Only lowercase letters, numbers, and hyphens allowed" readonly>
                                <button type="button" class="btn btn-outline-secondary" id="regenerate-url" title="Regenerate URL">
                                    <i class="ti ti-refresh"></i>
                                </button>
                                @error('unique_url')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <small class="text-muted">This is automatically generated from your product name. Click the refresh button to regenerate.</small>
                        </div>
                    </div>

                    <!-- Public Subdomain URL (Coming Soon) -->
                    <div class="row mb-4">
                        <div class="col-md-12">
                            <label for="subdomain_url" class="form-label">Public Subdomain URL <span class="badge bg-warning ms-2">Coming Soon</span></label>
                            <input type="text" class="form-control" id="subdomain_url" name="subdomain_url" placeholder="your-subdomain.app.com" value="{{ old('subdomain_url', $settings->subdomain_url ?? '') }}" disabled>
                            <small class="text-muted">This feature is coming soon. You'll be able to set up a custom subdomain for your public pages.</small>
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

    // Auto-generate unique URL from product name
    const productNameInput = document.getElementById('product_name');
    const uniqueUrlInput = document.getElementById('unique_url');
    const regenerateBtn = document.getElementById('regenerate-url');

    function generateUniqueUrl(text) {
        if (!text) return '';
        return text
            .toLowerCase()
            .replace(/[^a-z0-9\s-]/g, '') // Remove special characters
            .replace(/\s+/g, '-') // Replace spaces with hyphens
            .replace(/-+/g, '-') // Replace multiple hyphens with single hyphen
            .replace(/^-+|-+$/g, ''); // Remove leading/trailing hyphens
    }

    if (productNameInput && uniqueUrlInput) {
        // Auto-generate on product name input
        productNameInput.addEventListener('input', function() {
            uniqueUrlInput.value = generateUniqueUrl(this.value);
        });

        // Regenerate button click
        if (regenerateBtn) {
            regenerateBtn.addEventListener('click', function() {
                uniqueUrlInput.value = generateUniqueUrl(productNameInput.value);
            });
        }

        // Generate on page load if product name exists but unique URL doesn't
        if (productNameInput.value && !uniqueUrlInput.value) {
            uniqueUrlInput.value = generateUniqueUrl(productNameInput.value);
        }
    }
});
</script>
@endpush
