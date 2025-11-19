<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="card-title mb-0">
            <i class="ti ti-files me-2"></i>Articles
        </h5>
        <div class="d-flex align-items-center gap-2">
            <span class="badge badge-soft-primary">{{ $articles->count() }} Articles</span>
            <a href="{{ route('board-article.create', $knowledgeBoard) }}" class="btn btn-sm btn-primary">
                <i class="ti ti-plus me-1"></i>Add Article
            </a>
        </div>
    </div>
    <div class="card-body">
        @if($articles->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead>
                        <tr>
                            <th style="width: 80px;">Cover</th>
                            <th>Title</th>
                            <th>Category</th>
                            <th>Author</th>
                            <th>Tags</th>
                            <th>Status</th>
                            <th>Popular</th>
                            <th style="width: 120px;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($articles as $article)
                            <tr>
                                <td>
                                    @if($article->cover_image)
                                        <img src="{{ asset('storage/' . $article->cover_image) }}"
                                             alt="{{ $article->article_title }}"
                                             class="rounded"
                                             style="width: 60px; height: 40px; object-fit: cover;">
                                    @else
                                        <div class="bg-light rounded d-flex align-items-center justify-content-center"
                                             style="width: 60px; height: 40px;">
                                            <i class="ti ti-photo text-muted"></i>
                                        </div>
                                    @endif
                                </td>
                                <td>
                                    <div class="fw-semibold">{{ $article->article_title }}</div>
                                    <small class="text-muted">{{ $article->created_at->format('M d, Y') }}</small>
                                </td>
                                <td>
                                    <span class="badge bg-info">
                                        <i class="{{ $article->boardCategory->category_icon ?? 'ti ti-folder' }} me-1"></i>
                                        {{ $article->boardCategory->category_name ?? 'N/A' }}
                                    </span>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-sm me-2">
                                            <span class="avatar-title rounded-circle bg-soft-primary text-primary fs-6">
                                                {{ strtoupper(substr($article->author->name ?? 'N', 0, 1)) }}
                                            </span>
                                        </div>
                                        <div>
                                            <div class="fw-medium">{{ $article->author->name ?? 'N/A' }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    @if($article->tags && count($article->tags) > 0)
                                        <div class="d-flex flex-wrap gap-1">
                                            @foreach(array_slice($article->tags, 0, 2) as $tag)
                                                <span class="badge bg-secondary">{{ $tag }}</span>
                                            @endforeach
                                            @if(count($article->tags) > 2)
                                                <span class="badge bg-light text-dark">+{{ count($article->tags) - 2 }}</span>
                                            @endif
                                        </div>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>
                                    @if($article->status == 'published')
                                        <span class="badge bg-success">Published</span>
                                    @elseif($article->status == 'unpublished')
                                        <span class="badge bg-danger">Unpublished</span>
                                    @else
                                        <span class="badge bg-secondary">Draft</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    @if($article->display_as_popular)
                                        <i class="ti ti-star-filled text-warning fs-5" title="Popular Article"></i>
                                    @else
                                        <i class="ti ti-star text-muted" title="Not Popular"></i>
                                    @endif
                                </td>
                                <td>
                                    <div class="d-flex gap-1">
                                        <a href="{{ route('board-article.show', [$knowledgeBoard, $article]) }}" class="btn btn-sm btn-soft-primary" title="View">
                                            <i class="ti ti-eye"></i>
                                        </a>
                                        <a href="{{ route('board-article.edit', [$knowledgeBoard, $article]) }}" class="btn btn-sm btn-soft-info" title="Edit">
                                            <i class="ti ti-edit"></i>
                                        </a>
                                        <form action="{{ route('board-article.destroy', [$knowledgeBoard, $article]) }}" method="POST" class="d-inline delete-article-form">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-soft-danger" title="Delete">
                                                <i class="ti ti-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="text-center py-5">
                <i class="ti ti-file-off fs-1 text-muted mb-3"></i>
                <h5 class="text-muted">No Articles Yet</h5>
                <p class="text-muted mb-3">Start creating articles for this knowledge board.</p>
                <a href="{{ route('board-article.create', $knowledgeBoard) }}" class="btn btn-primary">
                    <i class="ti ti-plus me-1"></i>Add First Article
                </a>
            </div>
        @endif
    </div>
</div>
