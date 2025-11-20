@extends('layouts.inspinia')

@section('title', 'Edit Persona')

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/@yaireo/tagify/dist/tagify.css" rel="stylesheet" type="text/css">
<style>
    .tagify {
        min-height: 39.51px !important;
        padding: 0.375rem 0.75rem !important;
        border: 1px solid #dee2e6 !important;
        border-radius: 0.25rem !important;
    }

    .dynamic-input-group {
        position: relative;
        margin-bottom: 10px;
    }

    .remove-item {
        position: absolute;
        right: 10px;
        top: 50%;
        transform: translateY(-50%);
        cursor: pointer;
        color: #dc3545;
    }
</style>
@endpush

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <h2 class="mb-0">Edit Persona</h2>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('personas.update', $persona) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label for="name" class="form-label">Persona Name *</label>
                            <input type="text" name="name" id="name" value="{{ old('name', $persona->name) }}" required class="form-control">
                            @error('name')
                                <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="avatar" class="form-label">Avatar</label>
                            @if($persona->avatar)
                                <div class="mb-2 position-relative d-inline-block" id="current-avatar">
                                    <img src="{{ asset('storage/' . $persona->avatar) }}" alt="{{ $persona->name }}" class="rounded" style="width: 100px; height: 100px; object-fit: cover;">
                                    <button type="button" class="btn btn-danger btn-sm position-absolute top-0 end-0" onclick="removeAvatar()" style="padding: 2px 6px; font-size: 12px;">
                                        <i class="ti ti-x"></i>
                                    </button>
                                    <input type="hidden" name="remove_avatar" id="remove_avatar" value="0">
                                </div>
                            @endif
                            <input type="file" name="avatar" id="avatar" accept="image/*" class="form-control">
                            @error('avatar')
                                <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="role" class="form-label">Role / Job Title *</label>
                            <input type="text" name="role" id="role" value="{{ old('role', $persona->role) }}" required class="form-control">
                            @error('role')
                                <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="age_range" class="form-label">Age Range</label>
                                <input type="text" name="age_range" id="age_range" value="{{ old('age_range', $persona->age_range) }}" class="form-control">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="location" class="form-label">Location</label>
                                <input type="text" name="location" id="location" value="{{ old('location', $persona->location) }}" class="form-control">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Description *</label>
                            <textarea name="description" id="description" rows="3" required class="form-control">{{ old('description', $persona->description) }}</textarea>
                        </div>

                        <div class="mb-3">
                            <label for="goals" class="form-label">Goals</label>
                            <input type="text" class="form-control" id="goals" name="goals" placeholder="Add goals...">
                            <small class="text-muted">Press Enter to add multiple goals</small>
                            @error('goals')
                                <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="pain_points" class="form-label">Pain Points</label>
                            <input type="text" class="form-control" id="pain_points" name="pain_points" placeholder="Add pain points...">
                            <small class="text-muted">Press Enter to add multiple pain points</small>
                            @error('pain_points')
                                <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="behaviors" class="form-label">Behaviors</label>
                            <input type="text" class="form-control" id="behaviors" name="behaviors" placeholder="Add behaviors...">
                            <small class="text-muted">Press Enter to add multiple behaviors</small>
                            @error('behaviors')
                                <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="quote" class="form-label">Representative Quote</label>
                            <textarea name="quote" id="quote" rows="2" class="form-control">{{ old('quote', $persona->quote) }}</textarea>
                        </div>

                        <div class="mb-3">
                            <label for="segments" class="form-label">Related Segments</label>
                            <input type="text" class="form-control" id="segments" name="segments" value="{{ old('segments', json_encode($persona->segments->pluck('name')->toArray())) }}">
                        </div>

                        <div class="mb-4">
                            <label for="status" class="form-label">Status *</label>
                            <select name="status" id="status" required class="form-select">
                                <option value="active" {{ old('status', $persona->status) === 'active' ? 'selected' : '' }}>Active</option>
                                <option value="inactive" {{ old('status', $persona->status) === 'inactive' ? 'selected' : '' }}>Inactive</option>
                                <option value="draft" {{ old('status', $persona->status) === 'draft' ? 'selected' : '' }}>Draft</option>
                            </select>
                            @error('status')
                                <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('personas.index') }}" class="btn btn-secondary">Cancel</a>
                            <button type="submit" class="btn btn-primary">Update Persona</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/@yaireo/tagify"></script>
<script>
function removeAvatar() {
    if (confirm('Are you sure you want to remove this avatar?')) {
        document.getElementById('current-avatar').remove();
        document.getElementById('remove_avatar').value = '1';
    }
}

document.addEventListener('DOMContentLoaded', function() {
    // Force status select to be visible
    const statusSelect = document.querySelector('#status');
    if (statusSelect) {
        statusSelect.style.display = 'block';
        statusSelect.style.removeProperty('display');
    }

    // Segments
    const allSegments = @json($segments->map(function($segment) {
        return ['value' => (string)$segment->id, 'name' => $segment->name];
    }));

    const segmentInput = document.querySelector('#segments');
    const segmentTagify = new Tagify(segmentInput, {
        whitelist: allSegments.map(s => s.name),
        maxTags: allSegments.length,
        dropdown: {
            maxItems: 20,
            enabled: 0,
            closeOnSelect: false
        },
        enforceWhitelist: true,
        keepInvalidTags: false
    });

    // Existing data from persona
    const existingGoals = @json($persona->goals ?? []);
    const existingPainPoints = @json($persona->pain_points ?? []);
    const existingBehaviors = @json($persona->behaviors ?? []);

    // Goals Tagify
    const goalsInput = document.querySelector('#goals');
    const goalsTagify = new Tagify(goalsInput, {
        maxTags: 20,
        dropdown: {
            enabled: 0
        },
        placeholder: "Add goals...",
        enforceWhitelist: false
    });
    // Load existing goals
    if (existingGoals && existingGoals.length > 0) {
        goalsTagify.addTags(existingGoals);
    }

    // Pain Points Tagify
    const painPointsInput = document.querySelector('#pain_points');
    const painPointsTagify = new Tagify(painPointsInput, {
        maxTags: 20,
        dropdown: {
            enabled: 0
        },
        placeholder: "Add pain points...",
        enforceWhitelist: false
    });
    // Load existing pain points
    if (existingPainPoints && existingPainPoints.length > 0) {
        painPointsTagify.addTags(existingPainPoints);
    }

    // Behaviors Tagify
    const behaviorsInput = document.querySelector('#behaviors');
    const behaviorsTagify = new Tagify(behaviorsInput, {
        maxTags: 20,
        dropdown: {
            enabled: 0
        },
        placeholder: "Add behaviors...",
        enforceWhitelist: false
    });
    // Load existing behaviors
    if (existingBehaviors && existingBehaviors.length > 0) {
        behaviorsTagify.addTags(existingBehaviors);
    }

    // Form submission
    document.querySelector('form').addEventListener('submit', function(e) {
        e.preventDefault();

        // Handle segments
        const selectedSegmentNames = segmentTagify.value.map(tag => tag.value);
        const selectedSegmentIds = allSegments
            .filter(s => selectedSegmentNames.includes(s.name))
            .map(s => s.value);

        segmentInput.removeAttribute('name');

        selectedSegmentIds.forEach(id => {
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'segments[]';
            input.value = id;
            this.appendChild(input);
        });

        // Handle goals
        const goals = goalsTagify.value.map(tag => tag.value);
        goalsInput.removeAttribute('name');
        if (goals.length > 0) {
            goals.forEach(goal => {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'goals[]';
                input.value = goal;
                this.appendChild(input);
            });
        }

        // Handle pain points
        const painPoints = painPointsTagify.value.map(tag => tag.value);
        painPointsInput.removeAttribute('name');
        if (painPoints.length > 0) {
            painPoints.forEach(point => {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'pain_points[]';
                input.value = point;
                this.appendChild(input);
            });
        }

        // Handle behaviors
        const behaviors = behaviorsTagify.value.map(tag => tag.value);
        behaviorsInput.removeAttribute('name');
        if (behaviors.length > 0) {
            behaviors.forEach(behavior => {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'behaviors[]';
                input.value = behavior;
                this.appendChild(input);
            });
        }

        this.submit();
    });
});
</script>
@endpush
