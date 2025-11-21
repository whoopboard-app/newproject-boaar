@extends('layouts.inspinia')

@section('title', isset($feedback) ? 'Edit Feedback' : 'Add New Feedback')

@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/choices.js/public/assets/styles/choices.min.css">
<link href="https://cdn.jsdelivr.net/npm/@yaireo/tagify/dist/tagify.css" rel="stylesheet" type="text/css" />
<style>
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
        <div class="page-title-box d-flex align-items-center justify-content-between">
            <div>
                <h2 class="mb-0">{{ isset($feedback) ? 'Edit Feedback' : 'Add New Feedback' }}</h2>
                <p class="text-muted fs-14 mb-0">{{ isset($feedback) ? 'Update feedback details' : 'Submit a new feedback idea' }}</p>
            </div>
            <a href="{{ isset($feedback) ? route('feedback.show', $feedback) : route('feedback.index') }}" class="btn btn-secondary">
                <i class="ti ti-arrow-left me-1"></i> Back
            </a>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <form method="POST" action="{{ isset($feedback) ? route('feedback.update', $feedback) : route('feedback.store') }}">
                    @csrf
                    @if(isset($feedback))
                        @method('PUT')
                    @endif

                    <!-- Tell about your idea -->
                    <div class="row">
                        <div class="col-md-12">
                            <div class="mb-3">
                                <label for="idea" class="form-label">Tell about your idea <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('idea') is-invalid @enderror" id="idea" name="idea" placeholder="Describe your idea..." value="{{ old('idea', isset($feedback) ? $feedback->idea : '') }}" required>
                                @error('idea')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Tags -->
                    <div class="row">
                        <div class="col-md-12">
                            <div class="mb-3">
                                <label for="tags" class="form-label">Tags</label>
                                <input type="text" class="form-control @error('tags') is-invalid @enderror" id="tags" name="tags" placeholder="Add tags..." value="{{ old('tags', isset($feedback) && $feedback->tags ? implode(', ', $feedback->tags) : '') }}">
                                @error('tags')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Select Category -->
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="feedback_category_id" class="form-label">Select Category</label>
                                <select class="form-select @error('feedback_category_id') is-invalid @enderror" id="feedback_category_id" name="feedback_category_id" data-choices>
                                    <option value="">-- Select Category --</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}" {{ old('feedback_category_id', isset($feedback) ? $feedback->feedback_category_id : '') == $category->id ? 'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('feedback_category_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Status (Roadmap) -->
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="roadmap_id" class="form-label">Status</label>
                                <select class="form-select @error('roadmap_id') is-invalid @enderror" id="roadmap_id" name="roadmap_id" data-choices>
                                    <option value="">-- Select Status --</option>
                                    @foreach($statuses as $status)
                                        <option value="{{ $status->id }}" {{ old('roadmap_id', isset($feedback) ? $feedback->roadmap_id : '') == $status->id ? 'selected' : '' }}>
                                            {{ $status->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('roadmap_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Why your idea will have more value to product -->
                    <div class="row">
                        <div class="col-md-12">
                            <div class="mb-3">
                                <label for="value_description" class="form-label">Why your idea will have more value to product</label>
                                <textarea class="form-control @error('value_description') is-invalid @enderror" id="value_description" name="value_description" rows="3" placeholder="Explain the value this idea brings...">{{ old('value_description', isset($feedback) ? $feedback->value_description : '') }}</textarea>
                                @error('value_description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Name and Email -->
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="name" class="form-label">Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" placeholder="Enter your name" value="{{ old('name', isset($feedback) ? $feedback->name : '') }}" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="email" class="form-label">Email Address <span class="text-danger">*</span></label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" placeholder="Enter your email" value="{{ old('email', isset($feedback) ? $feedback->email : '') }}" required>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Login Access Checkbox and Feedback Visibility -->
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="login_access_enabled" name="login_access_enabled" value="1" {{ old('login_access_enabled', isset($feedback) ? $feedback->login_access_enabled : false) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="login_access_enabled">
                                        Login access enabled for the user
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="is_public" class="form-label">Feedback Visibility to Public <span class="text-danger">*</span></label>
                                <select class="form-select @error('is_public') is-invalid @enderror" id="is_public" name="is_public" data-choices required>
                                    <option value="1" {{ old('is_public', isset($feedback) ? $feedback->is_public : '1') == '1' ? 'selected' : '' }}>Yes</option>
                                    <option value="0" {{ old('is_public', isset($feedback) ? $feedback->is_public : '1') == '0' ? 'selected' : '' }}>No</option>
                                </select>
                                @error('is_public')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Persona and Source in same row -->
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="persona_id" class="form-label">Select Persona</label>
                                <select class="form-select @error('persona_id') is-invalid @enderror" id="persona_id" name="persona_id" data-choices>
                                    <option value="">-- Select Persona --</option>
                                    @foreach($personas as $persona)
                                        <option value="{{ $persona->id }}" {{ old('persona_id', isset($feedback) ? $feedback->persona_id : '') == $persona->id ? 'selected' : '' }}>
                                            {{ $persona->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('persona_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="source" class="form-label">Source of Idea <span class="text-danger">*</span></label>
                                <select class="form-select @error('source') is-invalid @enderror" id="source" name="source" data-choices required>
                                    <option value="">-- Select Source --</option>
                                    @foreach($sources as $source)
                                        <option value="{{ $source }}" {{ old('source', isset($feedback) ? $feedback->source : 'Admin Added') == $source ? 'selected' : '' }}>
                                            {{ $source }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('source')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Show in Roadmap -->
                    <div class="row">
                        <div class="col-md-12">
                            <div class="mb-3">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="show_in_roadmap" name="show_in_roadmap" value="1" {{ old('show_in_roadmap', isset($feedback) ? $feedback->show_in_roadmap : false) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="show_in_roadmap">
                                        Enable this feedback in Roadmap
                                    </label>
                                    <small class="text-muted d-block mt-1">When enabled, this feedback will be copied to the Roadmap Items section with "Open" status</small>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="row">
                        <div class="col-md-12">
                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-primary">
                                    <i class="ti ti-check me-1"></i> {{ isset($feedback) ? 'Update Feedback' : 'Submit Feedback' }}
                                </button>
                                <a href="{{ isset($feedback) ? route('feedback.show', $feedback) : route('feedback.index') }}" class="btn btn-secondary">
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
<script src="https://cdn.jsdelivr.net/npm/choices.js/public/assets/scripts/choices.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@yaireo/tagify"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize all elements with data-choices attribute
        var choicesElements = document.querySelectorAll('[data-choices]');

        choicesElements.forEach(function(element) {
            new Choices(element, {
                searchEnabled: true,
                itemSelectText: '',
                removeItemButton: false,
                shouldSort: false,
            });
        });

        // Initialize Tagify for tags input
        var tagsInput = document.querySelector('input[name="tags"]');
        if (tagsInput) {
            var tagify = new Tagify(tagsInput, {
                originalInputValueFormat: valuesArr => valuesArr.map(item => item.value).join(','),
                delimiters: ",",
                maxTags: 10,
                dropdown: {
                    enabled: 0
                },
                placeholder: "Add tags...",
            });
        }
    });
</script>
@endpush
