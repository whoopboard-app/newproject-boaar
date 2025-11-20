@extends('layouts.inspinia')

@section('title', 'Add New Subscriber')

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/@yaireo/tagify/dist/tagify.css" rel="stylesheet" type="text/css">
<style>
    .tagify {
        min-height: 39.51px !important;
        padding: 0.375rem 0.75rem !important;
        border: 1px solid #dee2e6 !important;
        border-radius: 0.25rem !important;
    }

    .tagify__input {
        margin: 0 !important;
        padding: 0 !important;
    }

    .tagify__tag {
        margin: 2px !important;
    }
</style>
@endpush

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <h2 class="mb-0">Add New Subscriber</h2>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('subscribers.store') }}" method="POST">
                        @csrf

                        <div class="mb-3">
                            <label for="full_name" class="form-label">Full Name *</label>
                            <input type="text" name="full_name" id="full_name" value="{{ old('full_name') }}" required class="form-control">
                            @error('full_name')
                                <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label">Email Address *</label>
                            <input type="email" name="email" id="email" value="{{ old('email') }}" required class="form-control">
                            @error('email')
                                <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="source" class="form-label">Source *</label>
                            <select name="source" id="source" required class="form-select">
                                <option value="admin" {{ old('source') === 'admin' ? 'selected' : '' }}>Admin</option>
                                <option value="upload" {{ old('source') === 'upload' ? 'selected' : '' }}>Upload</option>
                                <option value="online_subscribe" {{ old('source') === 'online_subscribe' ? 'selected' : '' }}>Online Subscribe</option>
                            </select>
                            @error('source')
                                <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="segments" class="form-label">Segments</label>
                            <input type="text" class="form-control" id="segments" name="segments" placeholder="Select segments..." value="{{ old('segments', '') }}">
                            <small class="text-muted">Click to see available segments and select multiple</small>
                            @error('segments')
                                <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <div class="form-check">
                                <input type="checkbox" name="send_verification" value="1" {{ old('send_verification') ? 'checked' : '' }}
                                    class="form-check-input" id="send_verification">
                                <label class="form-check-label" for="send_verification">
                                    Send email verification to confirm subscription
                                </label>
                            </div>
                        </div>

                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('subscribers.index') }}" class="btn btn-secondary">Cancel</a>
                            <button type="submit" class="btn btn-primary">Add Subscriber</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/@yaireo/tagify"></script>
<script src="https://cdn.jsdelivr.net/npm/@yaireo/tagify/dist/tagify.polyfills.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Get segments from PHP
    const segments = @json($segments->map(function($segment) {
        return ['value' => (string)$segment->id, 'name' => $segment->name];
    }));

    // Initialize Tagify for segments input
    const segmentInput = document.querySelector('#segments');

    const tagify = new Tagify(segmentInput, {
        whitelist: segments.map(s => s.name),
        maxTags: segments.length,
        dropdown: {
            maxItems: 20,
            classname: "segments-dropdown",
            enabled: 0,
            closeOnSelect: false
        },
        placeholder: "Select segments...",
        enforceWhitelist: true, // Only allow selection from existing segments
        keepInvalidTags: false
    });

    // Before form submission, convert segment names back to IDs
    const form = document.querySelector('form');
    form.addEventListener('submit', function(e) {
        e.preventDefault();

        const selectedSegmentNames = tagify.value.map(tag => tag.value);
        const selectedSegmentIds = segments
            .filter(s => selectedSegmentNames.includes(s.name))
            .map(s => s.value);

        // Clear any existing hidden segment inputs
        const existingInputs = form.querySelectorAll('input[name="segments[]"]');
        existingInputs.forEach(input => input.remove());

        // Remove the tagify input to prevent it from being submitted
        const originalInput = form.querySelector('#segments');
        if (originalInput) {
            originalInput.removeAttribute('name');
        }

        // Add hidden inputs for each selected segment ID
        if (selectedSegmentIds.length > 0) {
            selectedSegmentIds.forEach(id => {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'segments[]';
                input.value = id;
                form.appendChild(input);
            });
        } else {
            // If no segments selected, add empty array indicator
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'segments';
            input.value = '';
            form.appendChild(input);
        }

        // Now submit the form
        form.submit();
    });
});
</script>
@endpush
