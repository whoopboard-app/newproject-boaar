@extends('layouts.inspinia')

@section('title', $isDefault ? 'Default Theme' : 'Custom Theme')

@push('styles')
<style>
    .color-picker-wrapper {
        position: relative;
    }
    .color-picker-wrapper input[type="color"] {
        width: 50px;
        height: 38px;
        padding: 0;
        border: 1px solid #ced4da;
        border-radius: 4px;
        cursor: pointer;
    }
    .color-picker-wrapper input[type="text"] {
        padding-left: 60px;
    }
    .color-input-group {
        display: flex;
        align-items: center;
        gap: 10px;
    }
    .preview-container {
        position: sticky;
        top: 20px;
    }
    .preview-card {
        border: 2px solid #e5e7eb;
        border-radius: 12px;
        overflow: hidden;
        background: #f7f9fa;
    }
    .preview-header {
        padding: 30px 20px;
        text-align: center;
        transition: all 0.3s ease;
    }
    .preview-header-brand {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
        margin-bottom: 15px;
    }
    .preview-header-logo {
        width: 36px;
        height: 36px;
        background: rgba(255,255,255,0.2);
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
    }
    .preview-header-title {
        font-size: 14px;
        font-weight: 700;
    }
    .preview-intro {
        font-size: 20px;
        font-weight: 700;
        margin-bottom: 8px;
        transition: all 0.3s ease;
    }
    .preview-desc {
        font-size: 13px;
        opacity: 0.85;
        transition: all 0.3s ease;
    }
    .preview-search {
        max-width: 280px;
        margin: 15px auto 0;
        background: white;
        border-radius: 8px;
        padding: 10px 15px;
        font-size: 12px;
        color: #888;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .preview-content {
        padding: 20px;
        background: white;
    }
    .preview-content-box {
        background: #f7f9fa;
        border-radius: 8px;
        padding: 15px;
        margin-bottom: 10px;
    }
    .preview-content-line {
        height: 8px;
        background: #e5e7eb;
        border-radius: 4px;
        margin-bottom: 8px;
    }
    .preview-footer {
        padding: 20px;
        text-align: center;
        font-size: 11px;
        transition: all 0.3s ease;
    }
    .form-section {
        background: #f8f9fa;
        border-radius: 8px;
        padding: 20px;
        margin-bottom: 20px;
    }
    .form-section-title {
        font-size: 14px;
        font-weight: 600;
        color: #333;
        margin-bottom: 15px;
        padding-bottom: 10px;
        border-bottom: 1px solid #e5e7eb;
    }
    .readonly-notice {
        background: #f8f9fa;
        border: 1px dashed #dee2e6;
        border-radius: 8px;
        padding: 15px;
        text-align: center;
    }
    /* Menu sortable styles */
    #menuSortable .menu-item {
        background: #fff;
        border: 1px solid #e5e7eb;
        border-radius: 8px;
        margin-bottom: 8px;
        transition: all 0.2s ease;
    }
    #menuSortable .menu-item:hover {
        border-color: #11939A;
        box-shadow: 0 2px 8px rgba(17, 147, 154, 0.1);
    }
    #menuSortable .menu-item.dragging {
        opacity: 0.5;
        border-color: #11939A;
        box-shadow: 0 4px 12px rgba(17, 147, 154, 0.2);
    }
    #menuSortable .drag-handle:hover {
        color: #11939A !important;
    }
    #menuSortable .drag-handle i {
        font-size: 18px;
    }
    .menu-item .form-check-input:checked {
        background-color: #11939A;
        border-color: #11939A;
    }
</style>
@endpush

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-flex justify-content-between align-items-center">
            <div>
                <h4 class="page-title">{{ $isDefault ? 'Default Theme' : 'Custom Theme' }}</h4>
                <p class="text-muted fs-14 mb-0">
                    <a href="{{ route('settings.themes') }}" class="text-muted">Themes</a>
                    <i class="ti ti-chevron-right mx-1"></i>
                    {{ $isDefault ? 'Default' : 'Custom' }}
                </p>
            </div>
            <a href="{{ route('settings.themes') }}" class="btn btn-secondary">
                <i class="ti ti-arrow-left me-1"></i>Back to Themes
            </a>
        </div>
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="ti ti-circle-check me-2"></i>{{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="ti ti-alert-circle me-2"></i>{{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

<div class="row">
    <!-- Form Column -->
    <div class="col-lg-7">
        @if($isDefault)
            <!-- Default Theme - Read Only View -->
            <div class="card mb-4">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="avatar-sm me-3">
                            <span class="avatar-title rounded-circle" style="background-color: rgba(17, 147, 154, 0.18);">
                                <i class="ti ti-palette" style="color: #11939A;"></i>
                            </span>
                        </div>
                        <div>
                            <h5 class="mb-1">Default Theme</h5>
                            <span class="badge bg-secondary">Read Only</span>
                            <span class="badge bg-light text-dark ms-1">Applies to All</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Header Settings (Read Only) -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="ti ti-layout-navbar me-2"></i>Header Settings
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Header Background Color</label>
                            <div class="color-input-group">
                                <input type="color" value="#11939A" class="form-control-color" disabled>
                                <input type="text" class="form-control" value="#11939A" disabled>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Header Text Color</label>
                            <div class="color-input-group">
                                <input type="color" value="#FFFFFF" class="form-control-color" disabled>
                                <input type="text" class="form-control" value="#FFFFFF" disabled>
                            </div>
                        </div>
                    </div>
                    <div class="readonly-notice">
                        <i class="ti ti-info-circle text-muted me-2"></i>
                        <span class="text-muted">Default theme values cannot be edited. Create a <strong>Custom Theme</strong> to customize colors.</span>
                    </div>
                </div>
            </div>

            <!-- Footer Settings (Read Only) -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="ti ti-layout-bottombar me-2"></i>Footer Settings
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Footer Background Color</label>
                            <div class="color-input-group">
                                <input type="color" value="#11939A" class="form-control-color" disabled>
                                <input type="text" class="form-control" value="#11939A" disabled>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Footer Text Color</label>
                            <div class="color-input-group">
                                <input type="color" value="#FFFFFF" class="form-control-color" disabled>
                                <input type="text" class="form-control" value="#FFFFFF" disabled>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Action - Go to Custom -->
            <div class="d-flex gap-2 mb-4">
                <a href="{{ route('settings.themes.edit', ['knowledgeBoard' => 'custom']) }}" class="btn btn-primary">
                    <i class="ti ti-plus me-1"></i>Create Custom Theme
                </a>
                <a href="{{ route('settings.themes') }}" class="btn btn-secondary">
                    Back to Themes
                </a>
            </div>
        @else
            <!-- Custom Theme - Editable Form -->
            <form action="{{ route('settings.themes.update', ['knowledgeBoard' => 'custom']) }}" method="POST">
                @csrf
                @method('PUT')

                <!-- Apply To Selection -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="ti ti-target me-2"></i>Apply Theme To
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-0">
                            <label class="form-label">Select Knowledge Board(s)</label>
                            <select class="form-select" id="apply_to" name="apply_to" required>
                                <option value="all" {{ old('apply_to', $applyTo) == 'all' ? 'selected' : '' }}>All Knowledge Boards</option>
                                @foreach($knowledgeBoards as $kb)
                                    <option value="{{ $kb->id }}" {{ old('apply_to', $applyTo) == $kb->id ? 'selected' : '' }}>
                                        {{ $kb->name }} ({{ $kb->document_type === 'manual' ? 'Manual' : 'Help Document' }})
                                    </option>
                                @endforeach
                            </select>
                            <small class="text-muted">Choose which Knowledge Board(s) to apply this custom theme to.</small>
                            @error('apply_to')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Header Settings -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="ti ti-layout-navbar me-2"></i>Header Settings
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Header Background Color</label>
                                <div class="color-input-group">
                                    <input type="color" id="header_background_color" name="header_background_color"
                                           value="{{ old('header_background_color', $theme->header_background_color ?? '#11939A') }}"
                                           class="form-control-color">
                                    <input type="text" class="form-control" id="header_background_color_text"
                                           value="{{ old('header_background_color', $theme->header_background_color ?? '#11939A') }}"
                                           pattern="^#[0-9A-Fa-f]{6}$" placeholder="#11939A">
                                </div>
                                @error('header_background_color')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Header Text Color</label>
                                <div class="color-input-group">
                                    <input type="color" id="header_text_color" name="header_text_color"
                                           value="{{ old('header_text_color', $theme->header_text_color ?? '#FFFFFF') }}"
                                           class="form-control-color">
                                    <input type="text" class="form-control" id="header_text_color_text"
                                           value="{{ old('header_text_color', $theme->header_text_color ?? '#FFFFFF') }}"
                                           pattern="^#[0-9A-Fa-f]{6}$" placeholder="#FFFFFF">
                                </div>
                                @error('header_text_color')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Header Intro Text</label>
                            <input type="text" class="form-control" id="header_intro_text" name="header_intro_text"
                                   value="{{ old('header_intro_text', $theme->header_intro_text ?? '') }}"
                                   placeholder="e.g., Welcome to our Help Center" maxlength="255">
                            <small class="text-muted">Leave empty to use the Knowledge Board name</small>
                            @error('header_intro_text')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-0">
                            <label class="form-label">Header Short Description</label>
                            <textarea class="form-control" id="header_short_description" name="header_short_description"
                                      rows="2" placeholder="e.g., How can we help you today?" maxlength="1000">{{ old('header_short_description', $theme->header_short_description ?? '') }}</textarea>
                            <small class="text-muted">Leave empty to use the Knowledge Board description</small>
                            @error('header_short_description')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Footer Settings -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="ti ti-layout-bottombar me-2"></i>Footer Settings
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Footer Background Color</label>
                                <div class="color-input-group">
                                    <input type="color" id="footer_background_color" name="footer_background_color"
                                           value="{{ old('footer_background_color', $theme->footer_background_color ?? '#11939A') }}"
                                           class="form-control-color">
                                    <input type="text" class="form-control" id="footer_background_color_text"
                                           value="{{ old('footer_background_color', $theme->footer_background_color ?? '#11939A') }}"
                                           pattern="^#[0-9A-Fa-f]{6}$" placeholder="#11939A">
                                </div>
                                @error('footer_background_color')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Footer Text Color</label>
                                <div class="color-input-group">
                                    <input type="color" id="footer_text_color" name="footer_text_color"
                                           value="{{ old('footer_text_color', $theme->footer_text_color ?? '#FFFFFF') }}"
                                           class="form-control-color">
                                    <input type="text" class="form-control" id="footer_text_color_text"
                                           value="{{ old('footer_text_color', $theme->footer_text_color ?? '#FFFFFF') }}"
                                           pattern="^#[0-9A-Fa-f]{6}$" placeholder="#FFFFFF">
                                </div>
                                @error('footer_text_color')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <!-- SEO Settings -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="ti ti-search me-2"></i>SEO Settings
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label">Meta Title</label>
                            <input type="text" class="form-control" id="meta_title" name="meta_title"
                                   value="{{ old('meta_title', $theme->meta_title ?? '') }}"
                                   placeholder="e.g., Help Center - Your Company" maxlength="255">
                            <small class="text-muted">Leave empty to use default title</small>
                            @error('meta_title')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Meta Description</label>
                            <textarea class="form-control" id="meta_description" name="meta_description"
                                      rows="3" placeholder="e.g., Find answers to your questions in our comprehensive help center..." maxlength="500">{{ old('meta_description', $theme->meta_description ?? '') }}</textarea>
                            <small class="text-muted">Recommended: 150-160 characters for best SEO results</small>
                            @error('meta_description')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-0">
                            <label class="form-label">Google Analytics ID</label>
                            <input type="text" class="form-control" id="google_analytics_id" name="google_analytics_id"
                                   value="{{ old('google_analytics_id', $theme->google_analytics_id ?? '') }}"
                                   placeholder="e.g., G-XXXXXXXXXX or UA-XXXXXXXXX-X" maxlength="50">
                            <small class="text-muted">Enter your Google Analytics Measurement ID (GA4) or Tracking ID (Universal Analytics)</small>
                            @error('google_analytics_id')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Menu Order Settings -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="ti ti-menu-order me-2"></i>Menu Order
                        </h5>
                    </div>
                    <div class="card-body">
                        <p class="text-muted small mb-3">Drag and drop to reorder menu items. Toggle visibility to show/hide items in the public navigation.</p>

                        @php
                            $menuOrder = $theme->menu_order ?? \App\Models\KnowledgeBoardTheme::getDefaultMenuOrder();
                            // Sort by order for display
                            usort($menuOrder, fn($a, $b) => ($a['order'] ?? 0) - ($b['order'] ?? 0));
                        @endphp

                        <div id="menuSortable" class="list-group">
                            @foreach($menuOrder as $index => $item)
                                <div class="list-group-item d-flex align-items-center gap-3 menu-item" data-key="{{ $item['key'] }}">
                                    <div class="drag-handle" style="cursor: grab;">
                                        <i class="ti ti-grip-vertical text-muted"></i>
                                    </div>
                                    <div class="form-check form-switch mb-0">
                                        <input class="form-check-input menu-visible" type="checkbox"
                                               id="menu_visible_{{ $item['key'] }}"
                                               {{ ($item['visible'] ?? true) ? 'checked' : '' }}>
                                    </div>
                                    <div class="flex-grow-1">
                                        <input type="text" class="form-control form-control-sm menu-label"
                                               value="{{ $item['label'] }}" placeholder="Menu label" maxlength="50">
                                    </div>
                                    <span class="badge bg-light text-muted">{{ $item['key'] }}</span>

                                    <!-- Hidden inputs for form submission -->
                                    <input type="hidden" name="menu_order[{{ $index }}][key]" value="{{ $item['key'] }}" class="menu-key-input">
                                    <input type="hidden" name="menu_order[{{ $index }}][label]" value="{{ $item['label'] }}" class="menu-label-input">
                                    <input type="hidden" name="menu_order[{{ $index }}][visible]" value="{{ ($item['visible'] ?? true) ? '1' : '0' }}" class="menu-visible-input">
                                    <input type="hidden" name="menu_order[{{ $index }}][order]" value="{{ $index + 1 }}" class="menu-order-input">
                                </div>
                            @endforeach
                        </div>

                        <small class="text-muted d-block mt-2">
                            <i class="ti ti-info-circle me-1"></i>Menu items will appear in this order on your public website navigation.
                        </small>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="d-flex gap-2 mb-4">
                    <button type="submit" class="btn btn-primary">
                        <i class="ti ti-check me-1"></i>Save Theme
                    </button>
                    <a href="{{ route('settings.themes') }}" class="btn btn-secondary">
                        Cancel
                    </a>
                    <button type="button" class="btn btn-outline-danger ms-auto" data-bs-toggle="modal" data-bs-target="#resetModal">
                        <i class="ti ti-refresh me-1"></i>Reset to Default
                    </button>
                </div>
            </form>

            <!-- Reset Confirmation Modal -->
            <div class="modal fade" id="resetModal" tabindex="-1" aria-labelledby="resetModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="resetModalLabel">Reset to Default Theme</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <p>Are you sure you want to reset to the default theme?</p>
                            <p class="text-muted small mb-0">This will set the default teal (#11939A) theme as active. Your custom theme settings will be preserved and can be re-activated later.</p>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <form action="{{ route('settings.themes.select', ['theme' => 'default']) }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-danger">
                                    <i class="ti ti-refresh me-1"></i>Reset to Default
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

        @endif
    </div>

    <!-- Preview Column -->
    <div class="col-lg-5">
        <div class="preview-container">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="ti ti-eye me-2"></i>Live Preview
                    </h5>
                </div>
                <div class="card-body p-0">
                    <div class="preview-card">
                        <!-- Header Preview -->
                        <div class="preview-header" id="previewHeader"
                             style="background-color: {{ $theme->header_background_color ?? '#11939A' }}; color: {{ $theme->header_text_color ?? '#FFFFFF' }};">
                            <div class="preview-header-brand">
                                <div class="preview-header-logo">
                                    <i class="ti ti-book"></i>
                                </div>
                                <span class="preview-header-title">Knowledge Board</span>
                            </div>
                            <div class="preview-intro" id="previewIntro">
                                {{ $theme->header_intro_text ?? 'Welcome' }}
                            </div>
                            <div class="preview-desc" id="previewDesc">
                                {{ $theme->header_short_description ?? 'How can we help you today?' }}
                            </div>
                            <div class="preview-search">
                                <i class="ti ti-search"></i>
                                <span>Search for articles...</span>
                            </div>
                        </div>

                        <!-- Content Preview -->
                        <div class="preview-content">
                            <div class="preview-content-box">
                                <div class="preview-content-line" style="width: 60%;"></div>
                                <div class="preview-content-line" style="width: 80%;"></div>
                                <div class="preview-content-line" style="width: 40%;"></div>
                            </div>
                            <div class="preview-content-box">
                                <div class="preview-content-line" style="width: 70%;"></div>
                                <div class="preview-content-line" style="width: 50%;"></div>
                            </div>
                        </div>

                        <!-- Footer Preview -->
                        <div class="preview-footer" id="previewFooter"
                             style="background-color: {{ $theme->footer_background_color ?? '#11939A' }}; color: {{ $theme->footer_text_color ?? '#FFFFFF' }};">
                            &copy; {{ date('Y') }} Your Company. All rights reserved.
                        </div>
                    </div>
                </div>
            </div>

            @if(!$isDefault)
                <!-- Quick Actions -->
                <div class="card mt-3">
                    <div class="card-body">
                        <h6 class="mb-3">Quick Color Presets</h6>
                        <div class="d-flex flex-wrap gap-2">
                            <button type="button" class="btn btn-sm btn-outline-secondary preset-btn" data-header="#11939A" data-footer="#11939A" data-text="#FFFFFF">
                                <span style="display: inline-block; width: 12px; height: 12px; background: #11939A; border-radius: 2px; margin-right: 5px;"></span>Teal
                            </button>
                            <button type="button" class="btn btn-sm btn-outline-secondary preset-btn" data-header="#3B82F6" data-footer="#3B82F6" data-text="#FFFFFF">
                                <span style="display: inline-block; width: 12px; height: 12px; background: #3B82F6; border-radius: 2px; margin-right: 5px;"></span>Blue
                            </button>
                            <button type="button" class="btn btn-sm btn-outline-secondary preset-btn" data-header="#10B981" data-footer="#10B981" data-text="#FFFFFF">
                                <span style="display: inline-block; width: 12px; height: 12px; background: #10B981; border-radius: 2px; margin-right: 5px;"></span>Green
                            </button>
                            <button type="button" class="btn btn-sm btn-outline-secondary preset-btn" data-header="#8B5CF6" data-footer="#8B5CF6" data-text="#FFFFFF">
                                <span style="display: inline-block; width: 12px; height: 12px; background: #8B5CF6; border-radius: 2px; margin-right: 5px;"></span>Purple
                            </button>
                            <button type="button" class="btn btn-sm btn-outline-secondary preset-btn" data-header="#000000" data-footer="#000000" data-text="#FFFFFF">
                                <span style="display: inline-block; width: 12px; height: 12px; background: #000000; border-radius: 2px; margin-right: 5px;"></span>Black
                            </button>
                            <button type="button" class="btn btn-sm btn-outline-secondary preset-btn" data-header="#EF4444" data-footer="#EF4444" data-text="#FFFFFF">
                                <span style="display: inline-block; width: 12px; height: 12px; background: #EF4444; border-radius: 2px; margin-right: 5px;"></span>Red
                            </button>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    @if(!$isDefault)
    // Color picker sync
    const colorFields = ['header_background_color', 'header_text_color', 'footer_background_color', 'footer_text_color'];

    colorFields.forEach(field => {
        const colorInput = document.getElementById(field);
        const textInput = document.getElementById(field + '_text');

        if (colorInput && textInput) {
            // Sync color picker to text input
            colorInput.addEventListener('input', function() {
                textInput.value = this.value.toUpperCase();
                updatePreview();
            });

            // Sync text input to color picker
            textInput.addEventListener('input', function() {
                if (/^#[0-9A-Fa-f]{6}$/.test(this.value)) {
                    colorInput.value = this.value;
                    updatePreview();
                }
            });
        }
    });

    // Text fields update preview
    const introText = document.getElementById('header_intro_text');
    const descText = document.getElementById('header_short_description');
    if (introText) introText.addEventListener('input', updatePreview);
    if (descText) descText.addEventListener('input', updatePreview);

    // Update preview function
    function updatePreview() {
        const headerBg = document.getElementById('header_background_color')?.value || '#11939A';
        const headerText = document.getElementById('header_text_color')?.value || '#FFFFFF';
        const footerBg = document.getElementById('footer_background_color')?.value || '#11939A';
        const footerText = document.getElementById('footer_text_color')?.value || '#FFFFFF';
        const introTextVal = document.getElementById('header_intro_text')?.value || '';
        const descTextVal = document.getElementById('header_short_description')?.value || '';

        // Update header
        const previewHeader = document.getElementById('previewHeader');
        if (previewHeader) {
            previewHeader.style.backgroundColor = headerBg;
            previewHeader.style.color = headerText;
        }

        // Update footer
        const previewFooter = document.getElementById('previewFooter');
        if (previewFooter) {
            previewFooter.style.backgroundColor = footerBg;
            previewFooter.style.color = footerText;
        }

        // Update text
        const previewIntro = document.getElementById('previewIntro');
        const previewDesc = document.getElementById('previewDesc');
        if (previewIntro) previewIntro.textContent = introTextVal || 'Welcome';
        if (previewDesc) previewDesc.textContent = descTextVal || 'How can we help you today?';
    }

    // Preset buttons
    document.querySelectorAll('.preset-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const headerColor = this.dataset.header;
            const footerColor = this.dataset.footer;
            const textColor = this.dataset.text;

            const headerBgInput = document.getElementById('header_background_color');
            const headerBgText = document.getElementById('header_background_color_text');
            const footerBgInput = document.getElementById('footer_background_color');
            const footerBgText = document.getElementById('footer_background_color_text');
            const headerTextInput = document.getElementById('header_text_color');
            const headerTextText = document.getElementById('header_text_color_text');
            const footerTextInput = document.getElementById('footer_text_color');
            const footerTextText = document.getElementById('footer_text_color_text');

            if (headerBgInput) headerBgInput.value = headerColor;
            if (headerBgText) headerBgText.value = headerColor;
            if (footerBgInput) footerBgInput.value = footerColor;
            if (footerBgText) footerBgText.value = footerColor;
            if (headerTextInput) headerTextInput.value = textColor;
            if (headerTextText) headerTextText.value = textColor;
            if (footerTextInput) footerTextInput.value = textColor;
            if (footerTextText) footerTextText.value = textColor;

            updatePreview();
        });
    });

    // Menu sortable functionality
    const menuSortable = document.getElementById('menuSortable');
    if (menuSortable) {
        let draggedItem = null;

        menuSortable.querySelectorAll('.menu-item').forEach(item => {
            const dragHandle = item.querySelector('.drag-handle');

            dragHandle.addEventListener('mousedown', function() {
                item.setAttribute('draggable', 'true');
            });

            item.addEventListener('dragstart', function(e) {
                draggedItem = this;
                this.classList.add('dragging');
                e.dataTransfer.effectAllowed = 'move';
            });

            item.addEventListener('dragend', function() {
                this.classList.remove('dragging');
                this.removeAttribute('draggable');
                draggedItem = null;
                updateMenuOrder();
            });

            item.addEventListener('dragover', function(e) {
                e.preventDefault();
                e.dataTransfer.dropEffect = 'move';

                if (draggedItem && draggedItem !== this) {
                    const rect = this.getBoundingClientRect();
                    const midY = rect.top + rect.height / 2;

                    if (e.clientY < midY) {
                        this.parentNode.insertBefore(draggedItem, this);
                    } else {
                        this.parentNode.insertBefore(draggedItem, this.nextSibling);
                    }
                }
            });
        });

        // Update hidden inputs when order changes
        function updateMenuOrder() {
            const items = menuSortable.querySelectorAll('.menu-item');
            items.forEach((item, index) => {
                // Update order input
                const orderInput = item.querySelector('.menu-order-input');
                if (orderInput) orderInput.value = index + 1;

                // Update array index in name attributes
                item.querySelector('.menu-key-input').name = `menu_order[${index}][key]`;
                item.querySelector('.menu-label-input').name = `menu_order[${index}][label]`;
                item.querySelector('.menu-visible-input').name = `menu_order[${index}][visible]`;
                item.querySelector('.menu-order-input').name = `menu_order[${index}][order]`;
            });
        }

        // Sync label input to hidden input
        menuSortable.querySelectorAll('.menu-label').forEach(input => {
            input.addEventListener('input', function() {
                const menuItem = this.closest('.menu-item');
                const hiddenInput = menuItem.querySelector('.menu-label-input');
                if (hiddenInput) hiddenInput.value = this.value;
            });
        });

        // Sync visibility checkbox to hidden input
        menuSortable.querySelectorAll('.menu-visible').forEach(checkbox => {
            checkbox.addEventListener('change', function() {
                const menuItem = this.closest('.menu-item');
                const hiddenInput = menuItem.querySelector('.menu-visible-input');
                if (hiddenInput) hiddenInput.value = this.checked ? '1' : '0';
            });
        });
    }
    @endif

    // Auto-dismiss alerts
    setTimeout(function() {
        const alerts = document.querySelectorAll('.alert-dismissible');
        alerts.forEach(alert => {
            const bsAlert = new bootstrap.Alert(alert);
            bsAlert.close();
        });
    }, 3000);
});
</script>
@endpush
