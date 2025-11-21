<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Roadmap Board</title>

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
        }

        .kanban-card:hover {
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            transform: translateY(-2px);
        }

        .kanban-card.dragging {
            opacity: 0.5;
        }

        .kanban-cards.drag-over {
            background-color: #e3f2fd;
        }

        .kanban-card-id {
            font-size: 0.75rem;
            color: #6c757d;
            margin-bottom: 0.5rem;
        }

        .kanban-card-title {
            font-weight: 600;
            font-size: 0.95rem;
            margin-bottom: 0.5rem;
            color: #212529;
        }

        .kanban-card-description {
            font-size: 0.85rem;
            color: #6c757d;
            margin-bottom: 0.75rem;
            line-height: 1.4;
        }

        .kanban-card-meta {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.8rem;
            color: #6c757d;
            margin-top: 0.75rem;
        }

        .category-badge {
            display: inline-block;
            padding: 0.25rem 0.75rem;
            border-radius: 12px;
            font-size: 0.75rem;
            font-weight: 500;
            color: white;
        }

        .tag-badge {
            display: inline-block;
            padding: 0.25rem 0.5rem;
            background-color: #e9ecef;
            border-radius: 4px;
            font-size: 0.75rem;
            color: #495057;
            margin-right: 0.25rem;
            margin-bottom: 0.25rem;
        }

        .empty-column {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 3rem 1rem;
            color: #adb5bd;
            text-align: center;
        }

        .empty-column i {
            font-size: 3rem;
            margin-bottom: 1rem;
        }

        .btn-back {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.5rem 1rem;
            background-color: #f8f9fa;
            border: 1px solid #dee2e6;
            border-radius: 6px;
            text-decoration: none;
            color: #495057;
            transition: all 0.2s;
        }

        .btn-back:hover {
            background-color: #e9ecef;
            color: #212529;
        }

        .linked-feedback {
            display: flex;
            align-items: center;
            gap: 0.25rem;
            font-size: 0.75rem;
            color: #0d6efd;
            margin-top: 0.5rem;
        }

        .pm-tool-id {
            font-size: 0.75rem;
            color: #6c757d;
            font-family: monospace;
            margin-top: 0.5rem;
        }
    </style>
</head>
<body>
    <!-- Header -->
    <div class="kanban-header">
        <div>
            <h1><i class="ti ti-route me-2"></i>Roadmap Board</h1>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('roadmap-items.index') }}" class="btn-back">
                <i class="ti ti-list"></i>
                List View
            </a>
            <a href="{{ route('roadmap-items.create') }}" class="btn btn-primary">
                <i class="ti ti-plus me-1"></i>
                Add Item
            </a>
        </div>
    </div>

    <!-- Kanban Board -->
    <div class="kanban-container">
        @forelse($roadmapStatuses as $status)
            <div class="kanban-column" data-status-id="{{ $status->id }}">
                <div class="kanban-column-header">
                    <div class="kanban-column-title">
                        <span class="category-badge" style="background-color: {{ $status->color }};">
                            {{ $status->name }}
                        </span>
                    </div>
                    <span class="kanban-column-count">
                        {{ isset($roadmapItems[$status->id]) ? $roadmapItems[$status->id]->count() : 0 }}
                    </span>
                </div>
                <div class="kanban-cards" data-status-id="{{ $status->id }}">
                    @if(isset($roadmapItems[$status->id]) && $roadmapItems[$status->id]->count() > 0)
                        @foreach($roadmapItems[$status->id] as $item)
                            <div class="kanban-card" draggable="true" data-item-id="{{ $item->id }}">
                                <div class="kanban-card-id">#{{ $item->id }}</div>
                                <div class="kanban-card-title">{{ Str::limit($item->idea, 60) }}</div>
                                @if($item->notes)
                                    <div class="kanban-card-description">{{ Str::limit($item->notes, 80) }}</div>
                                @endif
                                @if($item->feedback)
                                    <div class="linked-feedback">
                                        <i class="ti ti-link"></i>
                                        <span>Linked to Feedback #{{ $item->feedback->id }}</span>
                                    </div>
                                @endif
                                @if($item->external_pm_tool_id)
                                    <div class="pm-tool-id">
                                        <i class="ti ti-external-link me-1"></i>{{ $item->external_pm_tool_id }}
                                    </div>
                                @endif
                                @if($item->tags && count($item->tags) > 0)
                                    <div class="mt-2">
                                        @foreach(array_slice($item->tags, 0, 3) as $tag)
                                            <span class="tag-badge">{{ $tag }}</span>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    @else
                        <div class="empty-column">
                            <i class="ti ti-inbox"></i>
                            <div>No items</div>
                        </div>
                    @endif
                </div>
            </div>
        @empty
            <div class="alert alert-info w-100 m-3">
                <i class="ti ti-info-circle me-2"></i>No roadmap workflow statuses found. Please create roadmap workflow statuses first.
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
                        <small><i class="ti ti-info-circle me-1"></i>This note will be added as a comment.</small>
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
            let sourceStatusId = null;
            let targetStatusId = null;
            let itemId = null;

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
                sourceStatusId = this.closest('.kanban-cards').dataset.statusId;
                itemId = this.dataset.itemId;

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
                targetStatusId = this.dataset.statusId;

                // Don't do anything if dropping in the same column
                if (sourceStatusId !== targetStatusId) {
                    // Show modal for status change note
                    document.getElementById('statusChangeNote').value = '';
                    statusChangeModal.show();
                }

                return false;
            }

            // Handle status change confirmation
            document.getElementById('confirmStatusChange').addEventListener('click', function() {
                const note = document.getElementById('statusChangeNote').value.trim();
                updateItemStatus(note);
                statusChangeModal.hide();
            });

            function updateItemStatus(note = '') {
                // Prepare data
                const data = {
                    roadmap_status_id: targetStatusId === 'null' ? null : targetStatusId,
                    note: note,
                    _token: document.querySelector('meta[name="csrf-token"]').content
                };

                // Send AJAX request
                fetch(`/roadmap-items/${itemId}/update-status`, {
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
                        // Move the card to the new column
                        const targetColumn = document.querySelector(`.kanban-cards[data-status-id="${targetStatusId}"]`);
                        targetColumn.appendChild(draggedCard);

                        // Update counts
                        updateColumnCounts();

                        // Show success message (optional)
                        console.log('Status updated successfully');
                    } else {
                        alert('Failed to update status: ' + (result.message || 'Unknown error'));
                        // Refresh the page to restore correct state
                        location.reload();
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Failed to update status');
                    location.reload();
                });
            }

            function updateColumnCounts() {
                columns.forEach(column => {
                    const count = column.querySelectorAll('.kanban-card').length;
                    const header = column.closest('.kanban-column').querySelector('.kanban-column-count');
                    header.textContent = count;

                    // Update empty state
                    const existingEmpty = column.querySelector('.empty-column');
                    if (count === 0 && !existingEmpty) {
                        column.innerHTML = '<div class="empty-column"><i class="ti ti-inbox"></i><div>No items</div></div>';
                    } else if (count > 0 && existingEmpty) {
                        existingEmpty.remove();
                    }
                });
            }

            // Make cards clickable to view details
            cards.forEach(card => {
                card.addEventListener('click', function(e) {
                    if (!this.classList.contains('dragging')) {
                        const itemId = this.dataset.itemId;
                        window.location.href = `/roadmap-items/${itemId}`;
                    }
                });
            });
        });
    </script>
</body>
</html>
