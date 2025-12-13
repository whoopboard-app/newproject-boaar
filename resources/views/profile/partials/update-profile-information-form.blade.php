<section>
    <header class="mb-4">
        <h4 class="text-dark fw-semibold">Profile Information</h4>
        <p class="text-muted fs-14 mb-0">Update your account's profile information and email address.</p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" enctype="multipart/form-data">
        @csrf
        @method('patch')

        <!-- Profile Image -->
        <div class="mb-4">
            <label class="form-label">Profile Image</label>
            <div class="d-flex align-items-center gap-3">
                <div class="position-relative">
                    <img src="{{ $user->profile_image_url }}" alt="{{ $user->name }}" class="rounded-circle" width="80" height="80" style="object-fit: cover;" id="profile-image-preview">
                </div>
                <div>
                    <input type="file" class="form-control @error('profile_image') is-invalid @enderror" id="profile_image" name="profile_image" accept="image/*" onchange="previewImage(this)">
                    <small class="text-muted">JPG, PNG, GIF or WebP. Max 2MB.</small>
                    @error('profile_image')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            @if($user->profile_image)
                <div class="mt-2">
                    <label class="form-check-label">
                        <input type="checkbox" name="remove_profile_image" value="1" class="form-check-input">
                        Remove current image
                    </label>
                </div>
            @endif
        </div>

        <div class="mb-3">
            <label for="name" class="form-label">Name</label>
            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $user->name) }}" required autofocus autocomplete="name">
            @error('name')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email', $user->email) }}" required autocomplete="username" readonly>
            @error('email')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div class="mt-2">
                    <div class="alert alert-warning">
                        <p class="mb-2">Your email address is unverified.</p>
                        <button form="send-verification" class="btn btn-sm btn-warning">
                            Click here to re-send the verification email.
                        </button>
                    </div>

                    @if (session('status') === 'verification-link-sent')
                        <div class="alert alert-success mt-2">
                            A new verification link has been sent to your email address.
                        </div>
                    @endif
                </div>
            @endif
        </div>

        <!-- My Role -->
        <div class="mb-3">
            <label for="my_role" class="form-label">My Role</label>
            @php
                $role = $user->roleInTeam();
                $roleBadges = [
                    'owner' => 'bg-primary',
                    'admin' => 'bg-success',
                    'moderator' => 'bg-info',
                    'idea_submitter' => 'bg-warning',
                    'viewer' => 'bg-secondary',
                ];
                $roleLabels = [
                    'owner' => 'Owner',
                    'admin' => 'Admin',
                    'moderator' => 'Moderator',
                    'idea_submitter' => 'Idea Submitter',
                    'viewer' => 'Viewer',
                ];
            @endphp
            <div>
                <span class="badge {{ $roleBadges[$role] ?? 'bg-secondary' }} fs-14 px-3 py-2">
                    {{ $roleLabels[$role] ?? ucfirst($role ?? 'No Role') }}
                </span>
            </div>
            <small class="text-muted">Your role in the current team</small>
        </div>

        <!-- About Me -->
        <div class="mb-3">
            <label for="about_me" class="form-label">About Me</label>
            <textarea class="form-control @error('about_me') is-invalid @enderror" id="about_me" name="about_me" rows="4" placeholder="Tell us a bit about yourself...">{{ old('about_me', $user->about_me) }}</textarea>
            <small class="text-muted">Maximum 1000 characters</small>
            @error('about_me')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="d-flex align-items-center gap-3">
            <button type="submit" class="btn btn-primary">Save</button>

            @if (session('status') === 'profile-updated')
                <span class="text-success fs-14">
                    <i class="ti ti-check"></i> Saved.
                </span>
            @endif
        </div>
    </form>
</section>

<script>
function previewImage(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('profile-image-preview').src = e.target.result;
        }
        reader.readAsDataURL(input.files[0]);
    }
}
</script>
