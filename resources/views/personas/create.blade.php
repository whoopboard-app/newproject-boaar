@extends('layouts.inspinia')

@section('title', 'Create Persona')

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
                <h2 class="mb-0">Create Persona</h2>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('personas.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="mb-3">
                            <label for="name" class="form-label">Persona Name *</label>
                            <input type="text" name="name" id="name" value="{{ old('name') }}" required class="form-control" placeholder="e.g., Tech-Savvy Sarah">
                            @error('name')
                                <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Give your persona a memorable name</small>
                        </div>

                        <div class="mb-3">
                            <label for="avatar" class="form-label">Avatar</label>
                            <input type="file" name="avatar" id="avatar" accept="image/*" class="form-control">
                            @error('avatar')
                                <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="role" class="form-label">Role / Job Title *</label>
                            <input type="text" name="role" id="role" value="{{ old('role') }}" required class="form-control" placeholder="e.g., Marketing Manager">
                            @error('role')
                                <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="age_range" class="form-label">Age Range</label>
                                <input type="text" name="age_range" id="age_range" value="{{ old('age_range') }}" class="form-control" placeholder="e.g., 30-35">
                                @error('age_range')
                                    <div class="text-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="location" class="form-label">Location</label>
                                <input type="text" name="location" id="location" value="{{ old('location') }}" class="form-control" placeholder="e.g., San Francisco, CA">
                                @error('location')
                                    <div class="text-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Description *</label>
                            <textarea name="description" id="description" rows="3" required class="form-control" placeholder="Brief overview of this persona">{{ old('description') }}</textarea>
                            @error('description')
                                <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="goals" class="form-label">Goals</label>
                            <input type="text" class="form-control" id="goals" name="goals" placeholder="Add goals..." value="{{ old('goals', '') }}">
                            <small class="text-muted">Press Enter to add multiple goals</small>
                            @error('goals')
                                <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="pain_points" class="form-label">Pain Points</label>
                            <input type="text" class="form-control" id="pain_points" name="pain_points" placeholder="Add pain points..." value="{{ old('pain_points', '') }}">
                            <small class="text-muted">Press Enter to add multiple pain points</small>
                            @error('pain_points')
                                <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="behaviors" class="form-label">Behaviors</label>
                            <input type="text" class="form-control" id="behaviors" name="behaviors" placeholder="Add behaviors..." value="{{ old('behaviors', '') }}">
                            <small class="text-muted">Press Enter to add multiple behaviors</small>
                            @error('behaviors')
                                <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="quote" class="form-label">Representative Quote</label>
                            <textarea name="quote" id="quote" rows="2" class="form-control" placeholder='"I need tools that just work..."'>{{ old('quote') }}</textarea>
                            @error('quote')
                                <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="segments" class="form-label">Related Segments</label>
                            <input type="text" class="form-control" id="segments" name="segments" placeholder="Select segments..." value="{{ old('segments', '') }}">
                            <small class="text-muted">Link this persona to existing user segments</small>
                            @error('segments')
                                <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="status" class="form-label">Status *</label>
                            <select name="status" id="status" required class="form-select">
                                <option value="active" {{ old('status') === 'active' ? 'selected' : '' }}>Active</option>
                                <option value="inactive" {{ old('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
                                <option value="draft" {{ old('status') === 'draft' ? 'selected' : '' }}>Draft</option>
                            </select>
                            @error('status')
                                <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('personas.index') }}" class="btn btn-secondary">Cancel</a>
                            <button type="submit" class="btn btn-primary">Create Persona</button>
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
document.addEventListener('DOMContentLoaded', function() {
    // Force status select to be visible
    const statusSelect = document.querySelector('#status');
    if (statusSelect) {
        statusSelect.style.display = 'block';
        statusSelect.style.removeProperty('display');
    }

    // Segments
    const segments = @json($segments->map(function($segment) {
        return ['value' => (string)$segment->id, 'name' => $segment->name];
    }));

    const segmentInput = document.querySelector('#segments');
    const segmentTagify = new Tagify(segmentInput, {
        whitelist: segments.map(s => s.name),
        maxTags: segments.length,
        dropdown: {
            maxItems: 20,
            enabled: 0,
            closeOnSelect: false
        },
        placeholder: "Select segments...",
        enforceWhitelist: true,
        keepInvalidTags: false
    });

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

    // Form submission
    document.querySelector('form').addEventListener('submit', function(e) {
        e.preventDefault();

        // Handle segments
        const selectedSegmentNames = segmentTagify.value.map(tag => tag.value);
        const selectedSegmentIds = segments
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
