@extends('layouts.inspinia')

@section('title', 'Roadmap Statuses')

@push('styles')
<style>
    .color-picker-wrapper {
        position: relative;
    }

    .color-display {
        width: 40px;
        height: 40px;
        border-radius: 6px;
        border: 2px solid #dee2e6;
        cursor: pointer;
        transition: transform 0.2s;
    }

    .color-display:hover {
        transform: scale(1.1);
        border-color: #0d6efd;
    }

    .color-palette {
        display: none;
        position: absolute;
        top: 50px;
        left: 0;
        background: white;
        border: 1px solid #dee2e6;
        border-radius: 8px;
        padding: 12px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        z-index: 1000;
        width: 240px;
    }

    .color-palette.active {
        display: block;
    }

    .color-palette-grid {
        display: grid;
        grid-template-columns: repeat(5, 1fr);
        gap: 8px;
    }

    .color-option {
        width: 36px;
        height: 36px;
        border-radius: 6px;
        border: 2px solid transparent;
        cursor: pointer;
        transition: all 0.2s;
    }

    .color-option:hover {
        transform: scale(1.15);
        border-color: #0d6efd;
    }

    .color-option.selected {
        border-color: #0d6efd;
        box-shadow: 0 0 0 3px rgba(13,110,253,.25);
    }

    .toggle-switch {
        position: relative;
        display: inline-block;
        width: 50px;
        height: 24px;
    }

    .toggle-switch input {
        opacity: 0;
        width: 0;
        height: 0;
    }

    .toggle-slider {
        position: absolute;
        cursor: pointer;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: #ccc;
        border-radius: 24px;
        transition: .4s;
    }

    .toggle-slider:before {
        position: absolute;
        content: "";
        height: 18px;
        width: 18px;
        left: 3px;
        bottom: 3px;
        background-color: white;
        border-radius: 50%;
        transition: .4s;
    }

    input:checked + .toggle-slider {
        background-color: #198754;
    }

    input:checked + .toggle-slider:before {
        transform: translateX(26px);
    }

    /* Reorder Modal Styles */
    .reorder-status-item {
        background: #fff;
        border: 1px solid #dee2e6;
        border-radius: 8px;
        padding: 12px 15px;
        margin-bottom: 10px;
        display: flex;
        align-items: center;
        gap: 12px;
        transition: all 0.2s;
    }

    .reorder-status-item:hover {
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }

    .reorder-status-item.dragging {
        opacity: 0.5;
    }

    .reorder-status-item.disabled {
        background: #f8f9fa;
        opacity: 0.7;
    }

    .reorder-drag-handle {
        cursor: move;
        color: #6c757d;
        font-size: 20px;
    }

    .reorder-drag-handle:hover {
        color: #495057;
    }

    .reorder-drag-handle.disabled {
        cursor: not-allowed;
        color: #dee2e6;
    }

    .reorder-status-badge {
        padding: 6px 12px;
        border-radius: 6px;
        color: white;
        font-size: 14px;
        flex: 1;
    }
</style>
@endpush

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-flex align-items-center justify-content-between">
            <div>
                <h2 class="mb-0">Roadmap Statuses</h2>
                <p class="text-muted fs-14 mb-0">Manage your roadmap status workflow</p>
            </div>
            <a href="{{ route('settings.index') }}" class="btn btn-secondary">
                <i class="ti ti-arrow-left me-1"></i> Back to Settings
            </a>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h5 class="card-title mb-0">Status List</h5>
                    <div class="d-flex gap-2">
                        <button type="button" class="btn btn-info" onclick="openReorderModal()">
                            <i class="ti ti-arrows-sort me-1"></i> Reorder
                        </button>
                        <button type="button" class="btn btn-primary" onclick="addNewStatus()">
                            <i class="ti ti-plus me-1"></i> Add New Status
                        </button>
                    </div>
                </div>

                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead>
                            <tr>
                                <th style="width: 50px;">#</th>
                                <th>Status Name</th>
                                <th style="width: 150px;">Color</th>
                                <th style="width: 120px;">Status</th>
                                <th style="width: 180px;" class="text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody id="status-list">
                            @forelse($statuses as $index => $status)
                                <tr data-id="{{ $status->id }}" data-name="{{ $status->name }}">
                                    <td>{{ $index + 1 }}</td>
                                    <td><strong>{{ $status->name }}</strong></td>
                                    <td>
                                        <span class="badge" style="background-color: {{ $status->color }}; color: white; padding: 8px 16px;">
                                            {{ $status->name }}
                                        </span>
                                    </td>
                                    <td>
                                        @if($status->is_active)
                                            <span class="badge bg-success">Active</span>
                                        @else
                                            <span class="badge bg-secondary">Inactive</span>
                                        @endif
                                    </td>
                                    <td class="text-end">
                                        <button type="button" class="btn btn-sm btn-primary" onclick="addNewStatus()" title="Edit">
                                            <i class="ti ti-edit"></i>
                                        </button>
                                        <button type="button" class="btn btn-sm btn-info" onclick="openReorderModal()" title="Reorder">
                                            <i class="ti ti-arrows-sort"></i>
                                        </button>
                                        <button type="button" class="btn btn-sm btn-danger" onclick="deleteStatus({{ $status->id }})" title="Delete">
                                            <i class="ti ti-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-4 text-muted">
                                        No statuses yet. Add your first status!
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Manage Statuses Modal -->
<div class="modal fade" id="addStatusModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Manage Statuses</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="manageStatusesForm">
                @csrf
                <div class="modal-body">
                    <div id="modalStatusList">
                        <!-- Open Status (Always First) -->
                        <div class="modal-status-row" data-type="open">
                            <div class="d-flex align-items-center gap-3 mb-3 p-3 border rounded">
                                <input type="text" class="form-control" value="Open" maxlength="40" data-field="open-name" required>

                                <div class="color-picker-wrapper">
                                    <div class="color-display" style="background-color: #22C55E;" data-field="open-color" onclick="toggleModalColorPalette(this)"></div>
                                    <div class="color-palette">
                                        <div class="color-palette-grid">
                                            @foreach(['#EF4444', '#F97316', '#F59E0B', '#EAB308', '#84CC16', '#22C55E', '#10B981', '#14B8A6', '#06B6D4', '#0EA5E9', '#3B82F6', '#6366F1', '#8B5CF6', '#A855F7', '#D946EF', '#EC4899', '#F43F5E', '#64748B', '#6B7280', '#78716C'] as $color)
                                                <div class="color-option" style="background-color: {{ $color }};" onclick="selectColorInModal('{{ $color }}', this)"></div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>

                                <label class="toggle-switch">
                                    <input type="checkbox" checked disabled>
                                    <span class="toggle-slider"></span>
                                </label>
                                <small class="text-muted" style="width: 60px;">Active</small>
                            </div>
                        </div>

                        <!-- Custom Statuses Container -->
                        <div id="customStatusesContainer"></div>

                        <!-- Add Status Button -->
                        <div class="text-center my-3">
                            <button type="button" class="btn btn-outline-primary btn-sm" onclick="addCustomStatus()">
                                <i class="ti ti-plus me-1"></i> Add Status
                            </button>
                        </div>

                        <!-- Closed Status (Always Last) -->
                        <div class="modal-status-row" data-type="closed">
                            <div class="d-flex align-items-center gap-3 mb-3 p-3 border rounded">
                                <input type="text" class="form-control" value="Closed" maxlength="40" data-field="closed-name" required>

                                <div class="color-picker-wrapper">
                                    <div class="color-display" style="background-color: #6B7280;" data-field="closed-color" onclick="toggleModalColorPalette(this)"></div>
                                    <div class="color-palette">
                                        <div class="color-palette-grid">
                                            @foreach(['#EF4444', '#F97316', '#F59E0B', '#EAB308', '#84CC16', '#22C55E', '#10B981', '#14B8A6', '#06B6D4', '#0EA5E9', '#3B82F6', '#6366F1', '#8B5CF6', '#A855F7', '#D946EF', '#EC4899', '#F43F5E', '#64748B', '#6B7280', '#78716C'] as $color)
                                                <div class="color-option" style="background-color: {{ $color }};" onclick="selectColorInModal('{{ $color }}', this)"></div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>

                                <label class="toggle-switch">
                                    <input type="checkbox" disabled>
                                    <span class="toggle-slider"></span>
                                </label>
                                <small class="text-muted" style="width: 60px;">Inactive</small>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" onclick="saveAllStatuses()">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Reorder Statuses Modal -->
<div class="modal fade" id="reorderModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Reorder Statuses</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p class="text-muted mb-3">Drag to reorder statuses. Open and Closed cannot be reordered.</p>
                <div id="reorderStatusList">
                    <!-- Statuses will be loaded here dynamically -->
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" onclick="saveReorder()">Save Order</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
<script>
// Close color palettes when clicking outside
document.addEventListener('click', function(e) {
    if (!e.target.closest('.color-picker-wrapper')) {
        document.querySelectorAll('.color-palette').forEach(p => p.classList.remove('active'));
    }
});

let customStatusCounter = 0;

// Add new status - Load existing statuses into modal
function addNewStatus() {
    // Clear custom statuses container
    document.getElementById('customStatusesContainer').innerHTML = '';
    customStatusCounter = 0;

    // Load existing statuses from the table
    fetch('/roadmap')
        .then(response => response.text())
        .then(html => {
            const parser = new DOMParser();
            const doc = parser.parseFromString(html, 'text/html');
            const statusRows = doc.querySelectorAll('#status-list tr[data-id]');

            statusRows.forEach(row => {
                const name = row.dataset.name;
                const id = row.dataset.id;

                // Extract color from badge element
                const colorBadge = row.querySelector('.badge[style]');
                const colorStyle = colorBadge ? colorBadge.style.backgroundColor : '#3B82F6';

                // Extract active status from badge
                const statusBadge = row.querySelectorAll('.badge')[1]; // Second badge is the status badge
                const isActive = statusBadge ? statusBadge.classList.contains('bg-success') : true;

                if (name === 'Open') {
                    // Update Open status fields
                    document.querySelector('[data-field="open-name"]').value = name;
                    document.querySelector('[data-field="open-color"]').style.backgroundColor = colorStyle;
                } else if (name === 'Closed') {
                    // Update Closed status fields
                    document.querySelector('[data-field="closed-name"]').value = name;
                    document.querySelector('[data-field="closed-color"]').style.backgroundColor = colorStyle;
                } else {
                    // Add custom status
                    addCustomStatus(id, name, colorStyle, isActive);
                }
            });

            const modal = new bootstrap.Modal(document.getElementById('addStatusModal'));
            modal.show();
        });
}

// Add custom status row in modal
function addCustomStatus(id = null, name = '', color = '#3B82F6', isActive = true) {
    customStatusCounter++;
    const uniqueId = id || `new-${customStatusCounter}`;

    const statusHtml = `
        <div class="custom-status-row mb-3" data-id="${uniqueId}">
            <div class="d-flex align-items-center gap-3 p-3 border rounded">
                <input type="text" class="form-control" value="${name}" placeholder="Status name" maxlength="40" required>

                <div class="color-picker-wrapper">
                    <div class="color-display" style="background-color: ${color};" onclick="toggleModalColorPalette(this)"></div>
                    <div class="color-palette">
                        <div class="color-palette-grid">
                            ${generateColorOptions()}
                        </div>
                    </div>
                </div>

                <label class="toggle-switch">
                    <input type="checkbox" ${isActive ? 'checked' : ''}>
                    <span class="toggle-slider"></span>
                </label>

                <button type="button" class="btn btn-sm btn-danger" onclick="removeCustomStatus(this)">
                    <i class="ti ti-trash"></i>
                </button>
            </div>
        </div>
    `;

    document.getElementById('customStatusesContainer').insertAdjacentHTML('beforeend', statusHtml);
}

// Generate color options HTML
function generateColorOptions() {
    const colors = ['#EF4444', '#F97316', '#F59E0B', '#EAB308', '#84CC16', '#22C55E', '#10B981', '#14B8A6', '#06B6D4', '#0EA5E9', '#3B82F6', '#6366F1', '#8B5CF6', '#A855F7', '#D946EF', '#EC4899', '#F43F5E', '#64748B', '#6B7280', '#78716C'];
    return colors.map(color => `<div class="color-option" style="background-color: ${color};" onclick="selectColorInModal('${color}', this)"></div>`).join('');
}

// Remove custom status
function removeCustomStatus(button) {
    button.closest('.custom-status-row').remove();
}

// Toggle color palette in modal
function toggleModalColorPalette(element) {
    const palette = element.nextElementSibling;
    const allPalettes = document.querySelectorAll('.color-palette');

    allPalettes.forEach(p => {
        if (p !== palette) p.classList.remove('active');
    });

    palette.classList.toggle('active');
}

// Select color in modal
function selectColorInModal(color, element) {
    const colorDisplay = element.closest('.color-picker-wrapper').querySelector('.color-display');
    colorDisplay.style.backgroundColor = color;
    element.closest('.color-palette').classList.remove('active');
}

// Save all statuses
async function saveAllStatuses() {
    const openName = document.querySelector('[data-field="open-name"]').value;
    const openColor = rgbToHex(document.querySelector('[data-field="open-color"]').style.backgroundColor);

    const closedName = document.querySelector('[data-field="closed-name"]').value;
    const closedColor = rgbToHex(document.querySelector('[data-field="closed-color"]').style.backgroundColor);

    const customStatuses = [];
    document.querySelectorAll('.custom-status-row').forEach(row => {
        const nameInput = row.querySelector('input[type="text"]');
        const colorDisplay = row.querySelector('.color-display');
        const toggle = row.querySelector('input[type="checkbox"]');

        if (nameInput.value.trim()) {
            customStatuses.push({
                id: row.dataset.id,
                name: nameInput.value.trim(),
                color: rgbToHex(colorDisplay.style.backgroundColor),
                is_active: toggle.checked
            });
        }
    });

    // Prepare all statuses
    const allStatuses = [
        { name: openName, color: openColor, is_active: true, type: 'open' },
        ...customStatuses,
        { name: closedName, color: closedColor, is_active: false, type: 'closed' }
    ];

    try {
        // Delete all existing statuses and recreate
        const response = await fetch('/roadmap/bulk-update', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({ statuses: allStatuses })
        });

        const data = await response.json();

        if (data.success) {
            // Close modal and reload page
            bootstrap.Modal.getInstance(document.getElementById('addStatusModal')).hide();
            location.reload();
        } else {
            alert('Error saving statuses: ' + (data.message || 'Unknown error'));
        }
    } catch (error) {
        console.error('Error:', error);
        alert('Failed to save statuses');
    }
}

// Open reorder modal
let reorderSortable = null;
function openReorderModal() {
    const reorderList = document.getElementById('reorderStatusList');
    reorderList.innerHTML = '';

    // Get all statuses from the table
    const statusRows = document.querySelectorAll('#status-list tr[data-id]');

    statusRows.forEach((row, index) => {
        const id = row.dataset.id;
        const name = row.dataset.name;
        const colorBadge = row.querySelector('.badge[style]');
        const color = colorBadge ? colorBadge.style.backgroundColor : '#3B82F6';

        const isFixed = name === 'Open' || name === 'Closed';
        const itemClass = isFixed ? 'reorder-status-item disabled' : 'reorder-status-item';
        const handleClass = isFixed ? 'reorder-drag-handle disabled' : 'reorder-drag-handle';

        const itemHtml = `
            <div class="${itemClass}" data-id="${id}" data-name="${name}" ${isFixed ? 'data-fixed="true"' : ''}>
                <div class="${handleClass}">
                    <i class="ti ti-grip-vertical"></i>
                </div>
                <div class="reorder-status-badge" style="background-color: ${color};">
                    ${name}
                </div>
            </div>
        `;

        reorderList.insertAdjacentHTML('beforeend', itemHtml);
    });

    // Initialize SortableJS
    if (reorderSortable) {
        reorderSortable.destroy();
    }

    reorderSortable = new Sortable(reorderList, {
        handle: '.reorder-drag-handle:not(.disabled)',
        animation: 150,
        ghostClass: 'dragging',
        filter: '.disabled',
        onMove: function(evt) {
            // Prevent moving fixed items
            return !evt.related.dataset.fixed;
        }
    });

    // Open modal
    const modal = new bootstrap.Modal(document.getElementById('reorderModal'));
    modal.show();
}

// Save reorder
function saveReorder() {
    const reorderItems = document.querySelectorAll('#reorderStatusList .reorder-status-item');
    const statuses = [];

    reorderItems.forEach((item, index) => {
        statuses.push({
            id: item.dataset.id,
            sort_order: index
        });
    });

    fetch('/roadmap/reorder', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({ statuses: statuses })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            bootstrap.Modal.getInstance(document.getElementById('reorderModal')).hide();
            location.reload();
        } else {
            alert('Failed to reorder statuses');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Failed to reorder statuses');
    });
}

// Convert RGB to Hex
function rgbToHex(rgb) {
    if (rgb.startsWith('#')) return rgb;

    const result = rgb.match(/\d+/g);
    if (!result) return '#3B82F6';

    const r = parseInt(result[0]);
    const g = parseInt(result[1]);
    const b = parseInt(result[2]);

    return '#' + [r, g, b].map(x => {
        const hex = x.toString(16);
        return hex.length === 1 ? '0' + hex : hex;
    }).join('').toUpperCase();
}

// Delete status
function deleteStatus(id) {
    if (!confirm('Are you sure you want to delete this status?')) {
        return;
    }

    fetch(`/roadmap/${id}`, {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        } else {
            alert('Failed to delete status');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Failed to delete status');
    });
}
</script>
@endpush
