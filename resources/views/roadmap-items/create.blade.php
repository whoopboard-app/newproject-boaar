@extends('layouts.inspinia')

@section('title', isset($roadmapItem) ? 'Edit Roadmap Item' : 'Create Roadmap Item')

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/quill@2.0.0/dist/quill.snow.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/@yaireo/tagify/dist/tagify.css" rel="stylesheet">
@endpush

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-flex align-items-center justify-content-between">
            <div>
                <h2 class="mb-0">{{ isset($roadmapItem) ? 'Edit Roadmap Item' : 'Create Roadmap Item' }}</h2>
                <p class="text-muted fs-14 mb-0">{{ isset($roadmapItem) ? 'Update' : 'Add new' }} roadmap item details</p>
            </div>
            <a href="{{ route('roadmap-items.index') }}" class="btn btn-secondary">
                <i class="ti ti-arrow-left me-1"></i> Back to List
            </a>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-8 mx-auto">
        <div class="card">
            <div class="card-body">
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ isset($roadmapItem) ? route('roadmap-items.update', $roadmapItem) : route('roadmap-items.store') }}" method="POST">
                    @csrf
                    @if(isset($roadmapItem))
                        @method('PUT')
                    @endif

                    <!-- Idea -->
                    <div class="row">
                        <div class="col-md-12">
                            <div class="mb-3">
                                <label for="idea" class="form-label">Tell about your idea <span class="text-danger">*</span></label>
                                <textarea class="form-control" id="idea" name="idea" rows="3" required>{{ old('idea', isset($roadmapItem) ? $roadmapItem->idea : '') }}</textarea>
                                <small class="text-muted">Brief description of the roadmap item</small>
                            </div>
                        </div>
                    </div>

                    <!-- Notes -->
                    <div class="row">
                        <div class="col-md-12">
                            <div class="mb-3">
                                <label for="notes" class="form-label">Notes</label>
                                <div id="editor-container" style="height: 200px;"></div>
                                <textarea name="notes" id="notes" style="display:none;">{{ old('notes', isset($roadmapItem) ? $roadmapItem->notes : '') }}</textarea>
                                <small class="text-muted">Detailed notes about this roadmap item</small>
                            </div>
                        </div>
                    </div>

                    <!-- Tags -->
                    <div class="row">
                        <div class="col-md-12">
                            <div class="mb-3">
                                <label for="tags" class="form-label">Tags</label>
                                <input type="text" class="form-control" id="tags" name="tags"
                                    value="{{ old('tags', isset($roadmapItem) && $roadmapItem->tags ? implode(', ', $roadmapItem->tags) : '') }}"
                                    placeholder="Add tags...">
                                <small class="text-muted">Press Enter or comma to add tags</small>
                            </div>
                        </div>
                    </div>

                    <!-- External PM Tool ID -->
                    <div class="row">
                        <div class="col-md-12">
                            <div class="mb-3">
                                <label for="external_pm_tool_id" class="form-label">External Project Management Tool ID</label>
                                <input type="text" class="form-control" id="external_pm_tool_id" name="external_pm_tool_id"
                                    value="{{ old('external_pm_tool_id', isset($roadmapItem) ? $roadmapItem->external_pm_tool_id : '') }}"
                                    placeholder="JIRA-123, PROJ-456">
                                <small class="text-muted">Link to external project management tool (Jira, Asana, etc.)</small>
                            </div>
                        </div>
                    </div>

                    <!-- Status -->
                    <div class="row">
                        <div class="col-md-12">
                            <div class="mb-3">
                                <label for="roadmap_status_id" class="form-label">Status <span class="text-danger">*</span></label>
                                <select class="form-select" id="roadmap_status_id" name="roadmap_status_id" required>
                                    <option value="">-- Select Status --</option>
                                    @foreach($roadmapStatuses as $status)
                                        <option value="{{ $status->id }}"
                                            {{ old('roadmap_status_id', isset($roadmapItem) ? $roadmapItem->roadmap_status_id : '') == $status->id ? 'selected' : '' }}>
                                            {{ $status->name }}
                                        </option>
                                    @endforeach
                                </select>
                                <small class="text-muted">Current status from Roadmap Workflow</small>
                            </div>
                        </div>
                    </div>

                    @if(isset($roadmapItem) && $roadmapItem->feedback)
                        <div class="row">
                            <div class="col-md-12">
                                <div class="alert alert-info">
                                    <i class="ti ti-link me-2"></i>
                                    <strong>Linked to Feedback:</strong>
                                    <a href="{{ route('feedback.show', $roadmapItem->feedback) }}" target="_blank">
                                        {{ Str::limit($roadmapItem->feedback->idea, 60) }}
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endif

                    <div class="row">
                        <div class="col-md-12">
                            <button type="submit" class="btn btn-primary">
                                <i class="ti ti-check me-1"></i>
                                {{ isset($roadmapItem) ? 'Update Roadmap Item' : 'Create Roadmap Item' }}
                            </button>
                            <a href="{{ route('roadmap-items.index') }}" class="btn btn-secondary">
                                Cancel
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/quill@2.0.0/dist/quill.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@yaireo/tagify"></script>
<script>
    // Initialize Quill editor
    var quill = new Quill('#editor-container', {
        theme: 'snow',
        placeholder: 'Add detailed notes here...',
        modules: {
            toolbar: [
                ['bold', 'italic', 'underline', 'strike'],
                ['blockquote', 'code-block'],
                [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                [{ 'header': [1, 2, 3, false] }],
                ['link'],
                ['clean']
            ]
        }
    });

    // Set initial content
    var initialNotes = document.getElementById('notes').value;
    if (initialNotes) {
        quill.root.innerHTML = initialNotes;
    }

    // Update hidden textarea on form submit
    document.querySelector('form').addEventListener('submit', function() {
        document.getElementById('notes').value = quill.root.innerHTML;
    });

    // Initialize Tagify for tags input
    var input = document.querySelector('#tags');
    var tagify = new Tagify(input, {
        delimiters: ",",
        maxTags: 10,
        placeholder: "Add tags...",
        dropdown: {
            enabled: 0 // Disable dropdown suggestions
        }
    });
</script>
@endpush
