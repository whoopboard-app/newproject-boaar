<div class="row">
    <!-- Board Owner -->
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="ti ti-user me-2"></i>Board Owner
                </h5>
            </div>
            <div class="card-body">
                <div class="d-flex align-items-center mb-3">
                    <div class="avatar-lg me-3">
                        <span class="avatar-title rounded-circle bg-soft-primary text-primary fs-3">
                            {{ strtoupper(substr($knowledgeBoard->boardOwner->name ?? 'N/A', 0, 2)) }}
                        </span>
                    </div>
                    <div>
                        <h5 class="mb-1">{{ $knowledgeBoard->boardOwner->name ?? 'N/A' }}</h5>
                        <p class="text-muted mb-0">{{ $knowledgeBoard->boardOwner->email ?? '' }}</p>
                    </div>
                </div>

                <hr>

                <!-- Timestamps -->
                <div class="mb-3">
                    <h6 class="text-muted fs-13 mb-2">Created At</h6>
                    <p class="mb-0">
                        <i class="ti ti-calendar me-1"></i>{{ $knowledgeBoard->created_at->format('M d, Y') }}
                    </p>
                    <p class="text-muted mb-0 fs-12">{{ $knowledgeBoard->created_at->diffForHumans() }}</p>
                </div>

                <div>
                    <h6 class="text-muted fs-13 mb-2">Last Updated</h6>
                    <p class="mb-0">
                        <i class="ti ti-calendar me-1"></i>{{ $knowledgeBoard->updated_at->format('M d, Y') }}
                    </p>
                    <p class="text-muted mb-0 fs-12">{{ $knowledgeBoard->updated_at->diffForHumans() }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Board Information -->
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="ti ti-info-circle me-2"></i>Board Information
                </h5>
            </div>
            <div class="card-body">
                <!-- Cover Image -->
                @if($knowledgeBoard->cover_page)
                    <div class="info-item">
                        <h6>Cover Image</h6>
                        <div class="cover-image-wrapper">
                            <img src="{{ asset('storage/' . $knowledgeBoard->cover_page) }}" alt="Cover Page" class="board-cover-thumbnail" onclick="openLightbox()">
                            <div class="image-overlay">
                                <i class="ti ti-zoom-in"></i>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Description -->
                <div class="info-item">
                    <h6>Description</h6>
                    <p>{{ $knowledgeBoard->short_description }}</p>
                </div>

                <!-- Document Type, Visibility & Status -->
                <div class="info-item">
                    <div class="row">
                        <div class="col-md-4 mb-3 mb-md-0">
                            <h6>Document Type</h6>
                            <p>
                                @if($knowledgeBoard->document_type == 'manual')
                                    <span class="badge bg-primary"><i class="ti ti-book me-1"></i>Manual</span>
                                @else
                                    <span class="badge bg-info"><i class="ti ti-help me-1"></i>Help Document</span>
                                @endif
                            </p>
                        </div>
                        <div class="col-md-4 mb-3 mb-md-0">
                            <h6>Visibility</h6>
                            <p>
                                @if($knowledgeBoard->visibility_type == 'public')
                                    <span class="badge bg-success"><i class="ti ti-world me-1"></i>Public</span>
                                @else
                                    <span class="badge bg-warning"><i class="ti ti-lock me-1"></i>Private</span>
                                @endif
                            </p>
                        </div>
                        <div class="col-md-4">
                            <h6>Status</h6>
                            <p>
                                @if($knowledgeBoard->status == 'published')
                                    <span class="badge bg-success">Published</span>
                                @elseif($knowledgeBoard->status == 'unpublished')
                                    <span class="badge bg-danger">Unpublished</span>
                                @else
                                    <span class="badge bg-secondary">Draft</span>
                                @endif
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Public URL -->
                @if($knowledgeBoard->has_public_url)
                    <div class="info-item">
                        <h6>Public URL</h6>
                        <div class="public-url-box">
                            <div class="public-url-text me-3">
                                {{ url('/kb/' . $knowledgeBoard->public_url) }}
                            </div>
                            <button class="btn btn-sm btn-primary" onclick="copyPublicUrl('{{ url('/kb/' . $knowledgeBoard->public_url) }}')">
                                <i class="ti ti-copy me-1"></i>Copy
                            </button>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="ti ti-bolt me-2"></i>Quick Actions
                </h5>
            </div>
            <div class="card-body">
                <div class="d-flex flex-wrap gap-2">
                    <a href="{{ route('board-category.create', $knowledgeBoard) }}" class="btn btn-success">
                        <i class="ti ti-folder-plus me-1"></i>Add Category
                    </a>
                    <a href="{{ route('board-article.create', $knowledgeBoard) }}" class="btn btn-info">
                        <i class="ti ti-file-plus me-1"></i>Add Articles
                    </a>
                    <button type="button" class="btn btn-primary">
                        <i class="ti ti-edit me-1"></i>Edit Board
                    </button>
                    @if($knowledgeBoard->has_public_url)
                        <a href="{{ url('/kb/' . $knowledgeBoard->public_url) }}" target="_blank" class="btn btn-info">
                            <i class="ti ti-external-link me-1"></i>View Public Page
                        </a>
                    @endif
                    <button type="button" class="btn btn-danger">
                        <i class="ti ti-trash me-1"></i>Delete Board
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
