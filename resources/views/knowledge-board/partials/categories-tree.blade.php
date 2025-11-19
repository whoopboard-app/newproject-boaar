<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="card-title mb-0">
            <i class="ti ti-folder-tree me-2"></i>Categories
        </h5>
        <div class="d-flex align-items-center gap-2">
            <span class="badge badge-soft-primary">{{ $categories->count() }} Parent Categories</span>
            <a href="{{ route('board-category.create', $knowledgeBoard) }}" class="btn btn-sm btn-success">
                <i class="ti ti-plus me-1"></i>Add Category
            </a>
        </div>
    </div>
    <div class="card-body">
        @if($categories->count() > 0)
            <ul class="category-tree">
                @foreach($categories as $parent)
                    <li>
                        <div class="category-item">
                            <div class="category-info">
                                <i class="{{ $parent->category_icon }} category-icon"></i>
                                <span class="parent-category">{{ $parent->category_name }}</span>
                                <span class="badge bg-{{ $parent->status == 'active' ? 'success' : 'secondary' }} category-badge">
                                    {{ ucfirst($parent->status) }}
                                </span>
                                @if($parent->short_description)
                                    <small class="text-muted">- {{ Str::limit($parent->short_description, 50) }}</small>
                                @endif
                            </div>
                            <div class="category-actions">
                                <button class="btn btn-sm btn-soft-primary" title="Edit">
                                    <i class="ti ti-edit"></i>
                                </button>
                                <button class="btn btn-sm btn-soft-danger" title="Delete">
                                    <i class="ti ti-trash"></i>
                                </button>
                            </div>
                        </div>

                        @if($parent->childCategories->count() > 0)
                            <ul class="child-categories">
                                @foreach($parent->childCategories as $child)
                                    <li>
                                        <div class="category-item">
                                            <div class="category-info">
                                                <span class="tree-connector">└─</span>
                                                <i class="{{ $child->category_icon }} category-icon"></i>
                                                <span>{{ $child->category_name }}</span>
                                                <span class="badge bg-{{ $child->status == 'active' ? 'success' : 'secondary' }} category-badge">
                                                    {{ ucfirst($child->status) }}
                                                </span>
                                                @if($child->short_description)
                                                    <small class="text-muted">- {{ Str::limit($child->short_description, 40) }}</small>
                                                @endif
                                            </div>
                                            <div class="category-actions">
                                                <button class="btn btn-sm btn-soft-primary" title="Edit">
                                                    <i class="ti ti-edit"></i>
                                                </button>
                                                <button class="btn btn-sm btn-soft-danger" title="Delete">
                                                    <i class="ti ti-trash"></i>
                                                </button>
                                            </div>
                                        </div>

                                        @if($child->childCategories->count() > 0)
                                            <ul class="sub-child-categories">
                                                @foreach($child->childCategories as $subChild)
                                                    <li>
                                                        <div class="category-item">
                                                            <div class="category-info">
                                                                <span class="tree-connector">└─</span>
                                                                <i class="{{ $subChild->category_icon }} category-icon"></i>
                                                                <span>{{ $subChild->category_name }}</span>
                                                                <span class="badge bg-{{ $subChild->status == 'active' ? 'success' : 'secondary' }} category-badge">
                                                                    {{ ucfirst($subChild->status) }}
                                                                </span>
                                                                @if($subChild->short_description)
                                                                    <small class="text-muted">- {{ Str::limit($subChild->short_description, 30) }}</small>
                                                                @endif
                                                            </div>
                                                            <div class="category-actions">
                                                                <button class="btn btn-sm btn-soft-primary" title="Edit">
                                                                    <i class="ti ti-edit"></i>
                                                                </button>
                                                                <button class="btn btn-sm btn-soft-danger" title="Delete">
                                                                    <i class="ti ti-trash"></i>
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </li>
                                                @endforeach
                                            </ul>
                                        @endif
                                    </li>
                                @endforeach
                            </ul>
                        @endif
                    </li>
                @endforeach
            </ul>
        @else
            <div class="text-center py-5">
                <i class="ti ti-folder-off fs-1 text-muted mb-3"></i>
                <h5 class="text-muted">No Categories Yet</h5>
                <p class="text-muted mb-3">Start organizing your knowledge board by adding categories.</p>
                <a href="{{ route('board-category.create', $knowledgeBoard) }}" class="btn btn-primary">
                    <i class="ti ti-folder-plus me-1"></i>Add First Category
                </a>
            </div>
        @endif
    </div>
</div>
