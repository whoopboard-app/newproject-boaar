<div class="card">
    <div class="card-body">
        <div class="row align-items-center">
            <!-- Board Info -->
            <div class="col">
                <h4 class="mb-2">{{ $knowledgeBoard->name }}</h4>
                <p class="text-muted mb-3">{{ $knowledgeBoard->short_description }}</p>

                <div class="d-flex flex-wrap gap-2">
                    <!-- Document Type -->
                    @if($knowledgeBoard->document_type == 'manual')
                        <span class="badge bg-primary">
                            <i class="ti ti-book me-1"></i>Manual
                        </span>
                    @else
                        <span class="badge bg-info">
                            <i class="ti ti-help me-1"></i>Help Document
                        </span>
                    @endif

                    <!-- Visibility -->
                    @if($knowledgeBoard->visibility_type == 'public')
                        <span class="badge bg-success">
                            <i class="ti ti-world me-1"></i>Public
                        </span>
                    @else
                        <span class="badge bg-warning">
                            <i class="ti ti-lock me-1"></i>Private
                        </span>
                    @endif

                    <!-- Status -->
                    @if($knowledgeBoard->status == 'published')
                        <span class="badge bg-success">
                            <i class="ti ti-check-circle me-1"></i>Published
                        </span>
                    @elseif($knowledgeBoard->status == 'unpublished')
                        <span class="badge bg-danger">
                            <i class="ti ti-x-circle me-1"></i>Unpublished
                        </span>
                    @else
                        <span class="badge bg-secondary">
                            <i class="ti ti-pencil me-1"></i>Draft
                        </span>
                    @endif
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="col-auto">
                <div class="d-flex gap-2">
                    <a href="{{ route('knowledge-board.index') }}" class="btn btn-secondary">
                        <i class="ti ti-arrow-left me-1"></i>Back
                    </a>
                    <a href="{{ route('board-category.create', $knowledgeBoard) }}" class="btn btn-success">
                        <i class="ti ti-folder-plus me-1"></i>Add Category
                    </a>
                    <a href="{{ route('board-article.create', $knowledgeBoard) }}" class="btn btn-primary">
                        <i class="ti ti-file-plus me-1"></i>Add Article
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
