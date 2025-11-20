@extends('layouts.inspinia-guest')

@section('title', 'Accept Team Invitation')

@section('content')
<div class="mb-4">
    <div class="alert alert-info border-0">
        <div class="d-flex align-items-center">
            <i class="mdi mdi-account-multiple-plus me-2 fs-18"></i>
            <div>
                <strong>{{ $invitation->team->name }}</strong> has invited you to join their team as
                <strong>{{ ucfirst(str_replace('_', ' ', $invitation->role)) }}</strong>
            </div>
        </div>
    </div>
</div>

<h4 class="text-dark fs-20 text-center mb-4">Create Your Account</h4>

<form method="POST" action="{{ route('team.invitation.accept.signup', $invitation->token) }}" enctype="multipart/form-data">
    @csrf

    <div class="row">
        <!-- First Name -->
        <div class="col-md-6 mb-3">
            <label for="first_name" class="form-label">First Name <span class="text-danger">*</span></label>
            <input type="text" class="form-control @error('first_name') is-invalid @enderror"
                   id="first_name" name="first_name" value="{{ old('first_name') }}"
                   required autofocus autocomplete="given-name"
                   placeholder="Enter your first name"
                   pattern="[a-zA-Z\s]+"
                   title="Only letters and spaces allowed">
            @error('first_name')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
        </div>

        <!-- Last Name -->
        <div class="col-md-6 mb-3">
            <label for="last_name" class="form-label">Last Name <span class="text-danger">*</span></label>
            <input type="text" class="form-control @error('last_name') is-invalid @enderror"
                   id="last_name" name="last_name" value="{{ old('last_name') }}"
                   required autocomplete="family-name"
                   placeholder="Enter your last name"
                   pattern="[a-zA-Z\s]+"
                   title="Only letters and spaces allowed">
            @error('last_name')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
        </div>
    </div>

    <!-- Email Address (Readonly) -->
    <div class="mb-3">
        <label for="email" class="form-label">Email Address</label>
        <input type="email" class="form-control" id="email"
               value="{{ $invitation->email }}" readonly
               style="background-color: #f8f9fa; cursor: not-allowed;">
        <small class="text-muted">This email address is associated with your invitation</small>
    </div>

    <!-- Role (Readonly) -->
    <div class="mb-3">
        <label for="role" class="form-label">Role</label>
        <input type="text" class="form-control" id="role"
               value="{{ ucfirst(str_replace('_', ' ', $invitation->role)) }}" readonly
               style="background-color: #f8f9fa; cursor: not-allowed;">
        <small class="text-muted">Your role in the team</small>
    </div>

    <!-- Password -->
    <div class="mb-3">
        <label for="password" class="form-label">Password <span class="text-danger">*</span></label>
        <div class="input-group">
            <input type="password" id="password" name="password"
                   class="form-control @error('password') is-invalid @enderror"
                   placeholder="Enter your password" required autocomplete="new-password">
            @error('password')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
        </div>
    </div>

    <!-- Confirm Password -->
    <div class="mb-3">
        <label for="password_confirmation" class="form-label">Confirm Password <span class="text-danger">*</span></label>
        <div class="input-group">
            <input type="password" id="password_confirmation" name="password_confirmation"
                   class="form-control @error('password_confirmation') is-invalid @enderror"
                   placeholder="Confirm your password" required autocomplete="new-password">
            @error('password_confirmation')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
        </div>
    </div>

    <!-- Upload Profile Image (Optional) -->
    <div class="mb-3">
        <label for="avatar" class="form-label">Profile Image <span class="text-muted">(Optional)</span></label>
        <input type="file" class="form-control @error('avatar') is-invalid @enderror"
               id="avatar" name="avatar" accept="image/*">
        <small class="text-muted">Max size: 2MB. If not uploaded, an avatar will be auto-generated</small>
        @error('avatar')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
        @enderror
    </div>

    <!-- Timezone (Optional) -->
    <div class="mb-3">
        <label for="timezone" class="form-label">Timezone <span class="text-muted">(Optional)</span></label>
        <select class="form-select @error('timezone') is-invalid @enderror" id="timezone" name="timezone">
            <option value="">Select timezone</option>
            @foreach(timezone_identifiers_list() as $tz)
                <option value="{{ $tz }}" {{ old('timezone') == $tz ? 'selected' : '' }}>
                    {{ $tz }}
                </option>
            @endforeach
        </select>
        @error('timezone')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
        @enderror
    </div>

    <div class="mb-0 text-center d-grid">
        <button class="btn btn-primary btn-lg" type="submit">
            <i class="mdi mdi-check-circle me-1"></i> Continue to Dashboard
        </button>
    </div>
</form>
@endsection

@section('extra-content')
<div class="row mt-3">
    <div class="col-12 text-center">
        <p class="text-muted">
            <i class="mdi mdi-shield-check text-success"></i>
            This invitation expires on {{ $invitation->expires_at->format('F j, Y') }}
        </p>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Initialize Choices.js for timezone dropdown if available
    document.addEventListener('DOMContentLoaded', function() {
        if (typeof Choices !== 'undefined') {
            const timezoneSelect = document.getElementById('timezone');
            if (timezoneSelect) {
                new Choices(timezoneSelect, {
                    searchEnabled: true,
                    searchPlaceholderValue: 'Search timezone...',
                    itemSelectText: '',
                });
            }
        }
    });
</script>
@endpush
