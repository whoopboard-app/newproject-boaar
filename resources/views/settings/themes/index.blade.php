@extends('layouts.inspinia')

@section('title', 'Themes (Public)')

@push('styles')
<style>
    .theme-card {
        transition: all 0.3s ease;
        border: 2px solid #198754;
        box-shadow: 0 0 0 3px rgba(25, 135, 84, 0.15);
    }
    .theme-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 15px rgba(17, 147, 154, 0.15);
    }
    .theme-preview {
        height: 160px;
        border-radius: 8px;
        overflow: hidden;
        position: relative;
    }
    .theme-preview-header {
        height: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 12px;
        font-weight: 600;
    }
    .theme-preview-content {
        height: 30%;
        background: #f7f9fa;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .theme-preview-footer {
        height: 20%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 10px;
    }
    .theme-badge {
        position: absolute;
        top: 8px;
        right: 8px;
        font-size: 10px;
        padding: 2px 8px;
    }
    .active-badge {
        position: absolute;
        top: 8px;
        left: 8px;
        font-size: 10px;
        padding: 2px 8px;
    }
</style>
@endpush

@php
    // Determine if custom theme exists and if it's active
    $hasCustomTheme = $knowledgeBoards->contains(function($kb) {
        return $kb->theme && $kb->theme->theme_type === 'custom';
    });

    $customTheme = $knowledgeBoards->first(function($kb) {
        return $kb->theme && $kb->theme->theme_type === 'custom';
    });

    // Check if custom theme is active (is_active = true)
    $isCustomActive = $knowledgeBoards->contains(function($kb) {
        return $kb->theme && $kb->theme->theme_type === 'custom' && $kb->theme->is_active;
    });

    // Default is active if no custom theme OR custom theme is not active
    $isDefaultActive = !$hasCustomTheme || !$isCustomActive;

    // Get active theme colors
    if ($isCustomActive && $customTheme) {
        $activeHeaderBg = $customTheme->theme->header_background_color;
        $activeHeaderText = $customTheme->theme->header_text_color;
        $activeFooterBg = $customTheme->theme->footer_background_color;
        $activeFooterText = $customTheme->theme->footer_text_color;
        $activeThemeName = 'Custom Theme';
        $activeThemeType = 'custom';
    } else {
        $activeHeaderBg = '#11939A';
        $activeHeaderText = '#FFFFFF';
        $activeFooterBg = '#11939A';
        $activeFooterText = '#FFFFFF';
        $activeThemeName = 'Default Theme';
        $activeThemeType = 'default';
    }
@endphp

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-flex justify-content-between align-items-center">
            <div>
                <h4 class="page-title">Themes (Public)</h4>
                <p class="text-muted fs-14 mb-0">Customize the appearance of your public Knowledge Board pages</p>
            </div>
            <a href="{{ route('settings.index') }}" class="btn btn-secondary">
                <i class="ti ti-arrow-left me-1"></i>Back to Settings
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
    <!-- Active Theme Card -->
    <div class="col-xl-4 col-lg-6 col-md-6 mb-4">
        <div class="card theme-card h-100">
            <div class="card-body">
                <!-- Theme Preview -->
                <div class="theme-preview mb-3">
                    <div class="theme-preview-header" style="background-color: {{ $activeHeaderBg }}; color: {{ $activeHeaderText }};">
                        <span>Header</span>
                    </div>
                    <div class="theme-preview-content">
                        <div class="d-flex gap-1">
                            <div style="width: 30px; height: 8px; background: #e5e7eb; border-radius: 4px;"></div>
                            <div style="width: 50px; height: 8px; background: #e5e7eb; border-radius: 4px;"></div>
                            <div style="width: 20px; height: 8px; background: #e5e7eb; border-radius: 4px;"></div>
                        </div>
                    </div>
                    <div class="theme-preview-footer" style="background-color: {{ $activeFooterBg }}; color: {{ $activeFooterText }};">
                        <span>Footer</span>
                    </div>
                    <span class="badge bg-success active-badge"><i class="ti ti-check me-1"></i>Active</span>
                    <span class="badge {{ $activeThemeType === 'custom' ? 'bg-info' : 'bg-secondary' }} theme-badge">{{ $activeThemeType === 'custom' ? 'Custom' : 'Default' }}</span>
                </div>

                <!-- Theme Info -->
                <h5 class="card-title mb-2">{{ $activeThemeName }}</h5>
                <p class="text-muted small mb-3">
                    @if($activeThemeType === 'custom')
                        Your customized theme is currently active on all Knowledge Boards.
                    @else
                        The default teal (#11939A) theme is currently active on all Knowledge Boards.
                    @endif
                </p>

                <!-- Color Swatches -->
                <div class="d-flex align-items-center gap-2 mb-3">
                    <span class="text-muted small">Colors:</span>
                    <div style="width: 24px; height: 24px; border-radius: 4px; background-color: {{ $activeHeaderBg }}; border: 1px solid #ddd;" title="Header"></div>
                    <div style="width: 24px; height: 24px; border-radius: 4px; background-color: {{ $activeFooterBg }}; border: 1px solid #ddd;" title="Footer"></div>
                </div>

                <!-- Action -->
                <a href="{{ route('settings.themes.edit', ['knowledgeBoard' => 'custom']) }}" class="btn btn-primary w-100">
                    <i class="ti ti-edit me-1"></i>Customize Theme
                </a>
            </div>
        </div>
    </div>
</div>

@if($knowledgeBoards->count() == 0)
    <div class="card">
        <div class="card-body text-center py-5">
            <div class="avatar-lg mx-auto mb-3">
                <span class="avatar-title rounded-circle bg-light">
                    <i class="ti ti-book-off fs-1 text-muted"></i>
                </span>
            </div>
            <h5 class="text-muted">No Knowledge Boards Found</h5>
            <p class="text-muted mb-3">Create a Knowledge Board first to customize its theme.</p>
            <a href="{{ route('knowledge-board.create') }}" class="btn btn-primary">
                <i class="ti ti-plus me-1"></i>Create Knowledge Board
            </a>
        </div>
    </div>
@endif
@endsection
