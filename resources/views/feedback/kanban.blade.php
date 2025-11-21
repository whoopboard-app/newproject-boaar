<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Feedback Board</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Tabler Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/tabler-icons.min.css">

    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
            background-color: #f5f5f5;
            overflow-x: hidden;
        }

        .kanban-header {
            background-color: #ffffff;
            border-bottom: 2px solid #e0e0e0;
            padding: 1.5rem 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: sticky;
            top: 0;
            z-index: 1000;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .kanban-header h1 {
            margin: 0;
            font-size: 1.75rem;
            font-weight: 600;
            color: #333;
        }

        .kanban-container {
            padding: 2rem;
            display: flex;
            gap: 1.5rem;
            overflow-x: auto;
            min-height: calc(100vh - 100px);
        }

        .kanban-column {
            background-color: #f8f9fa;
            border-radius: 8px;
            min-width: 320px;
            max-width: 320px;
            display: flex;
            flex-direction: column;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        }

        .kanban-column-header {
            padding: 1rem 1.25rem;
            border-bottom: 2px solid #dee2e6;
            background-color: #ffffff;
            border-radius: 8px 8px 0 0;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .kanban-column-title {
            font-weight: 600;
            font-size: 1rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .kanban-column-count {
            background-color: #e9ecef;
            padding: 0.25rem 0.6rem;
            border-radius: 12px;
            font-size: 0.85rem;
            font-weight: 600;
        }

        .kanban-cards {
            padding: 1rem;
            flex: 1;
            overflow-y: auto;
            min-height: 100px;
        }

        .kanban-card {
            background-color: #ffffff;
            border: 1px solid #dee2e6;
            border-radius: 6px;
            padding: 1rem;
            margin-bottom: 0.75rem;
            cursor: move;
            transition: all 0.2s;
            box-shadow: 0 1px 2px rgba(0,0,0,0.05);
        }

        .kanban-card:hover {
            box-shadow: 0 4px 8px rgba(0,0,0,0.15);
            transform: translateY(-2px);
        }

        .kanban-card.dragging {
            opacity: 0.5;
            transform: rotate(2deg);
        }

        .kanban-card-id {
            font-size: 0.75rem;
            color: #6c757d;
            font-weight: 600;
            margin-bottom: 0.5rem;
        }

        .kanban-card-title {
            font-weight: 600;
            font-size: 0.95rem;
            margin-bottom: 0.5rem;
            color: #212529;
            line-height: 1.4;
        }

        .kanban-card-description {
            font-size: 0.85rem;
            color: #6c757d;
            margin-bottom: 0.75rem;
            line-height: 1.4;
        }

        .kanban-card-meta {
            display: flex;
            flex-wrap: wrap;
            gap: 0.5rem;
            align-items: center;
            font-size: 0.8rem;
        }

        .kanban-card-submitter {
            font-size: 0.8rem;
            color: #495057;
            font-weight: 500;
        }

        .category-badge {
            padding: 0.25rem 0.6rem;
            border-radius: 4px;
            font-size: 0.75rem;
            font-weight: 600;
            color: #fff;
        }

        .tag-badge {
            display: inline-block;
            padding: 0.2rem 0.5rem;
            margin: 2px;
            font-size: 0.7rem;
            border-radius: 3px;
            background-color: #e9ecef;
            color: #495057;
        }

        .kanban-cards.drag-over {
            background-color: #e3f2fd;
            border: 2px dashed #2196F3;
            border-radius: 6px;
        }

        .modal-backdrop {
            background-color: rgba(0, 0, 0, 0.5);
        }

        /* Empty state */
        .empty-column {
            text-align: center;
            padding: 2rem 1rem;
            color: #6c757d;
        }

        .empty-column i {
            font-size: 3rem;
            margin-bottom: 0.5rem;
            opacity: 0.3;
        }
    </style>
</head>
<body>
    <!-- Header -->
    <div class="kanban-header">
        <h1><i class="ti ti-layout-kanban me-2"></i>Feedback Board</h1>
        <button type="button" class="btn btn-secondary" onclick="window.close()">
            <i class="ti ti-x me-1"></i>Close
        </button>
    </div>

    <!-- Kanban Board -->
    <div class="kanban-container">
        @forelse($roadmaps as $roadmap)
            <div class="kanban-column" data-roadmap-id="{{ $roadmap->id }}">
                <div class="kanban-column-header">
                    <div class="kanban-column-title">
                        <span class="category-badge" style="background-color: {{ $roadmap->color }};">
                            {{ $roadmap->name }}
                        </span>
                    </div>
                    <span class="kanban-column-count">
                        {{ isset($feedbacks[$roadmap->id]) ? $feedbacks[$roadmap->id]->count() : 0 }}
                    </span>
                </div>
                <div class="kanban-cards" data-roadmap-id="{{ $roadmap->id }}">
                    @if(isset($feedbacks[$roadmap->id]) && $feedbacks[$roadmap->id]->count() > 0)
                        @foreach($feedbacks[$roadmap->id] as $feedback)
                            <div class="kanban-card" draggable="true" data-feedback-id="{{ $feedback->id }}">
                                <div class="kanban-card-id">#{{ $feedback->id }}</div>
                                <div class="kanban-card-title">{{ Str::limit($feedback->idea, 60) }}</div>
                                @if($feedback->value_description)
                                    <div class="kanban-card-description">{{ Str::limit($feedback->value_description, 80) }}</div>
                                @endif
                                <div class="kanban-card-meta">
                                    <div class="kanban-card-submitter">
                                        <i class="ti ti-user me-1"></i>{{ $feedback->name }}
                                    </div>
                                    @if($feedback->category)
                                        <span class="category-badge" style="background-color: {{ $feedback->category->color }};">
                                            {{ $feedback->category->name }}
                                        </span>
                                    @endif
                                </div>
                                @if($feedback->tags && count($feedback->tags) > 0)
                                    <div class="mt-2">
                                        @foreach(array_slice($feedback->tags, 0, 3) as $tag)
                                            <span class="tag-badge">{{ $tag }}</span>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    @else
                        <div class="empty-column">
                            <i class="ti ti-inbox"></i>
                            <div>No feedback items</div>
                        </div>
                    @endif
                </div>
            </div>
        @empty
            <div class="alert alert-info w-100 m-3">
                <i class="ti ti-info-circle me-2"></i>No roadmap statuses found. Please create roadmap statuses first.
            </div>
        @endforelse
    </div>

    <!-- Status Change Modal -->
    <div class="modal fade" id="statusChangeModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="ti ti-edit me-2"></i>Status Change Note
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="statusChangeNote" class="form-label">Add a note for this status change (optional):</label>
                        <textarea class="form-control" id="statusChangeNote" rows="3" placeholder="Why are you changing the status?"></textarea>
                    </div>
                    <div class="alert alert-info mb-0">
                        <small><i class="ti ti-info-circle me-1"></i>This note will be added as an internal comment.</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" id="confirmStatusChange">
                        <i class="ti ti-check me-1"></i>Update Status
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            let draggedCard = null;
            let sourceRoadmapId = null;
            let targetRoadmapId = null;
            let feedbackId = null;

            const statusChangeModal = new bootstrap.Modal(document.getElementById('statusChangeModal'));

            // Get all draggable cards
            const cards = document.querySelectorAll('.kanban-card');
            const columns = document.querySelectorAll('.kanban-cards');

            // Add drag event listeners to all cards
            cards.forEach(card => {
                card.addEventListener('dragstart', handleDragStart);
                card.addEventListener('dragend', handleDragEnd);
            });

            // Add drop event listeners to all columns
            columns.forEach(column => {
                column.addEventListener('dragover', handleDragOver);
                column.addEventListener('drop', handleDrop);
                column.addEventListener('dragenter', handleDragEnter);
                column.addEventListener('dragleave', handleDragLeave);
            });

            function handleDragStart(e) {
                draggedCard = this;
                sourceRoadmapId = this.closest('.kanban-cards').dataset.roadmapId;
                feedbackId = this.dataset.feedbackId;

                this.classList.add('dragging');
                e.dataTransfer.effectAllowed = 'move';
                e.dataTransfer.setData('text/html', this.innerHTML);
            }

            function handleDragEnd(e) {
                this.classList.remove('dragging');
            }

            function handleDragOver(e) {
                if (e.preventDefault) {
                    e.preventDefault();
                }
                e.dataTransfer.dropEffect = 'move';
                return false;
            }

            function handleDragEnter(e) {
                this.classList.add('drag-over');
            }

            function handleDragLeave(e) {
                this.classList.remove('drag-over');
            }

            function handleDrop(e) {
                if (e.stopPropagation) {
                    e.stopPropagation();
                }

                this.classList.remove('drag-over');

                targetRoadmapId = this.dataset.roadmapId;

                // Don't do anything if dropping in the same column
                if (sourceRoadmapId !== targetRoadmapId) {
                    // Show modal for status change note
                    document.getElementById('statusChangeNote').value = '';
                    statusChangeModal.show();
                }

                return false;
            }

            // Handle status change confirmation
            document.getElementById('confirmStatusChange').addEventListener('click', function() {
                const note = document.getElementById('statusChangeNote').value.trim();

                // Prepare data
                const data = {
                    roadmap_id: targetRoadmapId === 'null' ? null : targetRoadmapId,
                    note: note,
                    _token: document.querySelector('meta[name="csrf-token"]').content
                };

                // Send AJAX request
                fetch(`/feedback/${feedbackId}/update-status`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': data._token
                    },
                    body: JSON.stringify(data)
                })
                .then(response => response.json())
                .then(result => {
                    if (result.success) {
                        // Move the card visually
                        const targetColumn = document.querySelector(`.kanban-cards[data-roadmap-id="${targetRoadmapId}"]`);
                        const sourceColumn = document.querySelector(`.kanban-cards[data-roadmap-id="${sourceRoadmapId}"]`);

                        // Remove empty state if exists
                        const emptyState = targetColumn.querySelector('.empty-column');
                        if (emptyState) {
                            emptyState.remove();
                        }

                        // Move the card
                        targetColumn.appendChild(draggedCard);

                        // Update counts
                        updateColumnCount(sourceRoadmapId);
                        updateColumnCount(targetRoadmapId);

                        // Check if source column is now empty
                        if (sourceColumn.children.length === 0) {
                            sourceColumn.innerHTML = '<div class="empty-column"><i class="ti ti-inbox"></i><div>No feedback items</div></div>';
                        }

                        // Close modal
                        statusChangeModal.hide();

                        // Show success message
                        showToast('Success', result.message, 'success');
                    } else {
                        showToast('Error', 'Failed to update status', 'danger');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showToast('Error', 'An error occurred while updating status', 'danger');
                });
            });

            function updateColumnCount(roadmapId) {
                const column = document.querySelector(`.kanban-column[data-roadmap-id="${roadmapId}"]`);
                const cards = column.querySelectorAll('.kanban-card');
                const countBadge = column.querySelector('.kanban-column-count');
                countBadge.textContent = cards.length;
            }

            function showToast(title, message, type) {
                // Create a simple toast notification
                const toast = document.createElement('div');
                toast.className = `alert alert-${type} position-fixed top-0 end-0 m-3`;
                toast.style.zIndex = '9999';
                toast.innerHTML = `<strong>${title}:</strong> ${message}`;
                document.body.appendChild(toast);

                setTimeout(() => {
                    toast.remove();
                }, 3000);
            }
        });
    </script>
</body>
</html>
