<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $settings->product_name ?? 'Feedback' }}</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Tabler Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/tabler-icons.min.css">

    <!-- hCaptcha -->
    @if($feedbackSettings->enable_captcha ?? false)
    <script src="https://js.hcaptcha.com/1/api.js" async defer></script>
    @endif

    <style>
        :root {
            --primary-color: #5865F2;
            --border-color: #e5e7eb;
            --text-primary: #1f2937;
            --text-secondary: #6b7280;
            --bg-hover: #f9fafb;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            background-color: #ffffff;
            color: var(--text-primary);
        }

        /* Header */
        .public-header {
            border-bottom: 1px solid var(--border-color);
            background: white;
            padding: 1rem 0;
        }

        .logo-section {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .logo-img {
            width: 192px;
            height: 75px;
            object-fit: contain;
            border-radius: 8px;
        }

        .product-name {
            font-size: 1.25rem;
            font-weight: 600;
            color: var(--text-primary);
            margin: 0;
        }

        /* Navigation */
        .public-nav {
            display: flex;
            gap: 0.5rem;
            margin-top: 1rem;
        }

        .nav-tab {
            padding: 0.5rem 1rem;
            border-radius: 6px;
            text-decoration: none;
            color: var(--text-secondary);
            font-weight: 500;
            transition: all 0.2s;
        }

        .nav-tab:hover {
            background: var(--bg-hover);
            color: var(--text-primary);
        }

        .nav-tab.active {
            background: var(--primary-color);
            color: white;
        }

        /* Layout */
        .public-content {
            display: grid;
            grid-template-columns: 240px 1fr;
            gap: 2rem;
            padding: 2rem 0;
            max-width: 1200px;
            margin: 0 auto;
        }

        /* Filters */
        .filters-section {
            display: flex;
            flex-direction: column;
            gap: 0.75rem;
            margin-bottom: 1.5rem;
        }

        .search-box {
            width: 100%;
        }

        .search-box input {
            width: 100%;
            padding: 0.625rem 0.875rem;
            border: 1px solid var(--border-color);
            border-radius: 6px;
            font-size: 0.875rem;
            background: white;
        }

        .search-box input:focus {
            outline: none;
            border-color: var(--primary-color);
        }

        .filter-dropdown {
            width: 100%;
        }

        .filter-dropdown select {
            width: 100%;
            padding: 0.625rem 0.875rem;
            border: 1px solid var(--border-color);
            border-radius: 6px;
            font-size: 0.875rem;
            cursor: pointer;
            background: white;
        }

        .filter-dropdown select:focus {
            outline: none;
            border-color: var(--primary-color);
        }

        /* Sidebar */
        .sidebar {
            position: sticky;
            top: 2rem;
            height: fit-content;
        }

        .sidebar-title {
            font-size: 0.875rem;
            font-weight: 600;
            color: var(--text-secondary);
            text-transform: uppercase;
            letter-spacing: 0.05em;
            margin-bottom: 1rem;
        }

        .category-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0.625rem 0.75rem;
            border-radius: 6px;
            text-decoration: none;
            color: var(--text-primary);
            transition: all 0.2s;
            margin-bottom: 0.25rem;
        }

        .category-item:hover {
            background: var(--bg-hover);
            color: var(--text-primary);
        }

        .category-item.active {
            background: var(--primary-color);
            color: white;
        }

        .category-name {
            font-weight: 500;
            font-size: 0.9375rem;
        }

        .category-count {
            font-size: 0.875rem;
            color: var(--text-secondary);
            background: #f3f4f6;
            padding: 0.125rem 0.5rem;
            border-radius: 12px;
            font-weight: 500;
        }

        .category-item.active .category-count {
            background: rgba(255, 255, 255, 0.2);
            color: white;
        }

        /* Main Content */
        .feedback-list {
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }

        .feedback-card {
            background: white;
            border: 1px solid var(--border-color);
            border-radius: 8px;
            padding: 1.25rem;
            transition: all 0.2s;
            text-decoration: none;
            color: inherit;
            display: block;
        }

        .feedback-card:hover {
            border-color: var(--primary-color);
            box-shadow: 0 2px 8px rgba(88, 101, 242, 0.1);
            transform: translateY(-1px);
        }

        .feedback-header {
            display: flex;
            align-items: flex-start;
            gap: 1rem;
        }

        .vote-box {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 0.25rem;
            background: #f9fafb;
            border: 1px solid var(--border-color);
            border-radius: 8px;
            padding: 0.5rem 0.75rem;
            min-width: 60px;
            cursor: pointer;
            transition: all 0.2s;
        }

        .vote-box:hover {
            background: var(--primary-color);
            border-color: var(--primary-color);
            color: white;
        }

        .vote-icon {
            font-size: 1.25rem;
        }

        .vote-count {
            font-size: 0.875rem;
            font-weight: 600;
        }

        .feedback-content {
            flex: 1;
        }

        .feedback-title {
            font-size: 1.125rem;
            font-weight: 600;
            color: var(--text-primary);
            margin-bottom: 0.5rem;
        }

        .feedback-description {
            color: var(--text-secondary);
            font-size: 0.9375rem;
            line-height: 1.5;
            margin-bottom: 0.75rem;
        }

        .feedback-meta {
            display: flex;
            align-items: center;
            gap: 1rem;
            font-size: 0.875rem;
            color: var(--text-secondary);
        }

        .status-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.375rem;
            padding: 0.25rem 0.75rem;
            border-radius: 12px;
            font-size: 0.8125rem;
            font-weight: 500;
        }

        .category-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.375rem;
            padding: 0.25rem 0.75rem;
            border-radius: 12px;
            background: #f3f4f6;
            color: var(--text-secondary);
            font-size: 0.8125rem;
            font-weight: 500;
        }

        .empty-state {
            text-align: center;
            padding: 4rem 2rem;
        }

        .empty-icon {
            font-size: 4rem;
            color: var(--border-color);
            margin-bottom: 1rem;
        }

        .empty-title {
            font-size: 1.25rem;
            font-weight: 600;
            color: var(--text-primary);
            margin-bottom: 0.5rem;
        }

        .empty-text {
            color: var(--text-secondary);
        }

        @media (max-width: 768px) {
            .public-content {
                grid-template-columns: 1fr;
            }

            .sidebar {
                position: static;
            }
        }

        /* Vote box states */
        .vote-box.voted {
            background: var(--primary-color);
            border-color: var(--primary-color);
            color: white;
        }

        .vote-box.voted .vote-count {
            color: white;
        }

        .vote-box.processing {
            opacity: 0.7;
            pointer-events: none;
        }

        /* Add Idea Button */
        .btn-add-idea {
            background: var(--primary-color);
            border: none;
            padding: 0.5rem 1.5rem;
            border-radius: 6px;
            color: white;
            font-weight: 500;
            transition: all 0.2s;
        }

        .btn-add-idea:hover {
            background: #4752c4;
            color: white;
            transform: translateY(-1px);
        }

        /* Header actions */
        .header-actions {
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .btn-login {
            background: transparent;
            border: 1px solid var(--border-color);
            padding: 0.5rem 1rem;
            border-radius: 6px;
            color: var(--text-primary);
            font-weight: 500;
            text-decoration: none;
            transition: all 0.2s;
        }

        .btn-login:hover {
            border-color: var(--primary-color);
            color: var(--primary-color);
        }

        .btn-logout {
            background: transparent;
            border: 1px solid #dc3545;
            padding: 0.5rem 1rem;
            border-radius: 6px;
            color: #dc3545;
            font-weight: 500;
            text-decoration: none;
            transition: all 0.2s;
        }

        .btn-logout:hover {
            background: #dc3545;
            color: white;
        }

        .user-info {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.375rem 0.75rem;
            background: #f3f4f6;
            border-radius: 6px;
            font-size: 0.875rem;
            color: var(--text-secondary);
        }

        .user-info i {
            color: var(--primary-color);
        }

        /* Offcanvas styles */
        .offcanvas.offcanvas-end {
            width: 650px !important;
        }

        @media (max-width: 768px) {
            .offcanvas.offcanvas-end {
                width: 100% !important;
            }
        }

        .offcanvas-header {
            border-bottom: 1px solid var(--border-color);
            background: #f9fafb;
        }

        .offcanvas-title {
            font-weight: 600;
            color: var(--text-primary);
        }

        .form-label {
            font-weight: 500;
            color: var(--text-primary);
            margin-bottom: 0.5rem;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.2rem rgba(88, 101, 242, 0.15);
        }

        .form-text {
            font-size: 0.8125rem;
            color: var(--text-secondary);
        }

        .topic-checkbox {
            display: flex;
            flex-wrap: wrap;
            gap: 0.5rem;
        }

        .topic-checkbox .form-check {
            background: #f9fafb;
            border: 1px solid var(--border-color);
            border-radius: 6px;
            padding: 0.5rem 0.75rem;
            padding-left: 2rem;
            margin: 0;
            transition: all 0.2s;
        }

        .topic-checkbox .form-check:hover {
            border-color: var(--primary-color);
        }

        .topic-checkbox .form-check-input:checked + .form-check-label {
            color: var(--primary-color);
        }

        .topic-checkbox .form-check-input:checked ~ .form-check {
            border-color: var(--primary-color);
            background: rgba(88, 101, 242, 0.05);
        }

        /* Image upload */
        .image-upload-area {
            border: 2px dashed var(--border-color);
            border-radius: 8px;
            padding: 1.5rem;
            text-align: center;
            cursor: pointer;
            transition: all 0.2s;
        }

        .image-upload-area:hover {
            border-color: var(--primary-color);
            background: rgba(88, 101, 242, 0.02);
        }

        .image-upload-area.has-image {
            border-style: solid;
            border-color: var(--primary-color);
        }

        .image-preview {
            max-width: 100%;
            max-height: 150px;
            border-radius: 6px;
            margin-top: 0.75rem;
        }

        /* Success/Error alerts */
        .alert-idea-success {
            background: #d4edda;
            border-color: #c3e6cb;
            color: #155724;
        }

        .alert-idea-error {
            background: #f8d7da;
            border-color: #f5c6cb;
            color: #721c24;
        }
    </style>
</head>
<body>
    <!-- Header -->
    <header class="public-header pt-0">
        <div class="container">
            <div class="d-flex justify-content-between align-items-center">
                <div class="logo-section">
                    @if($settings->logo)
                        <img src="{{ asset('storage/' . $settings->logo) }}" alt="{{ $settings->product_name }}" class="logo-img">
                    @else
                        <div class="logo-img" style="background: var(--primary-color); display: flex; align-items: center; justify-content: center; color: white; font-weight: 700; font-size: 1.25rem;">
                            {{ strtoupper(substr($settings->product_name ?? 'F', 0, 1)) }}
                        </div>
                        <h1 class="product-name">{{ $settings->product_name ?? 'Feedback Board' }}</h1>
                    @endif
                </div>

                <div class="header-actions">
                    <!-- Add Idea Button -->
                    <button type="button" class="btn btn-add-idea" data-bs-toggle="offcanvas" data-bs-target="#addIdeaOffcanvas" aria-controls="addIdeaOffcanvas">
                        <i class="ti ti-bulb me-1"></i> Add Idea
                    </button>

                    <!-- Subscribe Button -->
                    <a href="{{ route('public.subscribe', $settings->unique_url) }}" class="btn btn-primary" style="background: var(--primary-color); border: none; padding: 0.5rem 1.5rem; border-radius: 6px; text-decoration: none; color: white; font-weight: 500;">
                        <i class="ti ti-bell-ringing me-1"></i> Subscribe
                    </a>

                    <!-- Login/Logout Section -->
                    @if($isLoggedIn)
                        <span class="user-info">
                            <i class="ti ti-user-circle"></i>
                            {{ $publicUser->full_name ?? $publicUser->email }}
                        </span>
                        <a href="{{ route('public.auth.logout', $settings->unique_url) }}" class="btn-logout">
                            <i class="ti ti-logout me-1"></i> Log out
                        </a>
                    @else
                        <a href="{{ route('public.auth.login', $settings->unique_url) }}" class="btn-login">
                            <i class="ti ti-login me-1"></i> Log in
                        </a>
                    @endif
                </div>
            </div>

            <nav class="public-nav">
                <a href="{{ route('public.home', $settings->unique_url) }}" class="nav-tab active">
                    <i class="ti ti-message-2"></i> Feedback
                </a>
                <a href="{{ route('public.roadmap', $settings->unique_url) }}" class="nav-tab">
                    <i class="ti ti-route"></i> Roadmap
                </a>
                <a href="{{ route('public.changelog', $settings->unique_url) }}" class="nav-tab">
                    <i class="ti ti-clipboard-list"></i> Changelog
                </a>
            </nav>
        </div>
    </header>

    <!-- Main Content -->
    <div class="container">
        <!-- Page Level Success Message -->
        <div id="pageSuccessAlert" class="alert alert-success alert-dismissible fade d-none" role="alert" style="margin-top: 1rem; border-radius: 8px;">
            <div class="d-flex align-items-center">
                <i class="ti ti-circle-check me-2" style="font-size: 1.25rem;"></i>
                <span id="pageSuccessMessage"></span>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>

        <div class="public-content" style="padding-top: 2rem;">
            <!-- Left Sidebar - Filters & Categories -->
            <aside class="sidebar">
                <!-- Filters Section -->
                <div class="filters-section">
                    <div class="search-box">
                        <input type="text" id="searchInput" placeholder="Search feedback..." />
                    </div>
                </div>

                <!-- Categories Section -->
                <div style="margin-top: 2rem;">
                    <div class="sidebar-title">Categories</div>
                    <div class="category-list">
                        <a href="#" class="category-item active">
                            <span class="category-name">All Feedback</span>
                            <span class="category-count">{{ $feedbacks->flatten()->count() }}</span>
                        </a>
                        @foreach($categories as $category)
                            <a href="#category-{{ $category->id }}" class="category-item" data-category="{{ $category->id }}">
                                <span class="category-name">{{ $category->name }}</span>
                                <span class="category-count">{{ $category->feedbacks_count }}</span>
                            </a>
                        @endforeach
                    </div>
                </div>
                <div class="filters-section">
                    <div class="filter-dropdown">
                        <select id="yearFilter">
                            <option value="">All Years</option>
                        </select>
                    </div>
                    <div class="filter-dropdown">
                        <select id="monthFilter">
                            <option value="">All Months</option>
                            <option value="1">January</option>
                            <option value="2">February</option>
                            <option value="3">March</option>
                            <option value="4">April</option>
                            <option value="5">May</option>
                            <option value="6">June</option>
                            <option value="7">July</option>
                            <option value="8">August</option>
                            <option value="9">September</option>
                            <option value="10">October</option>
                            <option value="11">November</option>
                            <option value="12">December</option>
                        </select>
                    </div>
                    <div class="filter-dropdown">
                        <select id="sortFilter">
                            <option value="latest">Latest First</option>
                            <option value="oldest">Oldest First</option>
                        </select>
                    </div>
                </div>
            </aside>

            <!-- Feedback List -->
            <main>
                @if($feedbacks->flatten()->count() > 0)
                    <div class="feedback-list">
                        @foreach($categories as $category)
                            @if(isset($feedbacks[$category->id]) && $feedbacks[$category->id]->count() > 0)
                                @foreach($feedbacks[$category->id] as $feedback)
                                    @php
                                        $voteCount = $feedback->votes()->count();
                                        $hasVoted = false;
                                        if ($isLoggedIn && $publicUser) {
                                            $hasVoted = $feedback->hasVotedBy($publicUser->id);
                                        } elseif (!$feedbackSettings->enable_login_for_voting && $feedbackSettings->enable_anonymous_voting) {
                                            $hasVoted = $feedback->hasVotedBy(null, null, request()->ip());
                                        }
                                    @endphp
                                    <div class="feedback-card"
                                         data-feedback-id="{{ $feedback->id }}"
                                         data-category="{{ $category->id }}"
                                         data-year="{{ $feedback->created_at->format('Y') }}"
                                         data-month="{{ $feedback->created_at->format('n') }}"
                                         data-timestamp="{{ $feedback->created_at->timestamp }}"
                                         data-search="{{ strtolower($feedback->idea . ' ' . strip_tags($feedback->value_description ?? '')) }}">
                                        <div class="feedback-header">
                                            <div class="vote-box {{ $hasVoted ? 'voted' : '' }}"
                                                 data-feedback-id="{{ $feedback->id }}"
                                                 onclick="event.stopPropagation(); handleVote(this, {{ $feedback->id }})"
                                                 title="{{ $hasVoted ? 'Click to remove your vote' : 'Click to vote for this idea' }}">
                                                <i class="ti ti-arrow-up vote-icon"></i>
                                                <span class="vote-count">{{ $voteCount }}</span>
                                            </div>
                                            <a href="{{ route('public.feedback.show', [$settings->unique_url, $feedback->id]) }}" class="feedback-content" style="text-decoration: none; color: inherit;">
                                                <h3 class="feedback-title">{{ $feedback->idea }}</h3>
                                                @if($feedback->value_description)
                                                    <div class="feedback-description">
                                                        {{ Str::limit(strip_tags($feedback->value_description), 150) }}
                                                    </div>
                                                @endif
                                                <div class="feedback-meta">
                                                    @if($feedback->roadmap)
                                                        <span class="status-badge" style="background-color: {{ $feedback->roadmap->color }}20; color: {{ $feedback->roadmap->color }};">
                                                            {{ $feedback->roadmap->name }}
                                                        </span>
                                                    @endif
                                                    <span class="category-badge">
                                                        {{ $category->name }}
                                                    </span>
                                                    <span>
                                                        <i class="ti ti-clock"></i>
                                                        {{ $feedback->created_at->diffForHumans() }}
                                                    </span>
                                                </div>
                                            </a>
                                        </div>
                                    </div>
                                @endforeach
                            @endif
                        @endforeach
                    </div>
                @else
                    <div class="empty-state">
                        <i class="ti ti-message-2-off empty-icon"></i>
                        <h2 class="empty-title">No feedback yet</h2>
                        <p class="empty-text">Be the first to share your ideas and suggestions!</p>
                    </div>
                @endif
            </main>
        </div>
    </div>

    @include('partials.public-footer')

    <!-- Add Idea Offcanvas -->
    <div class="offcanvas offcanvas-end" tabindex="-1" id="addIdeaOffcanvas" aria-labelledby="addIdeaOffcanvasLabel">
        <div class="offcanvas-header">
            <h5 class="offcanvas-title" id="addIdeaOffcanvasLabel">
                <i class="ti ti-bulb me-2" style="color: var(--primary-color);"></i>Share Your Idea
            </h5>
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body">
            <!-- Success/Error Alert -->
            <div id="ideaAlert" class="alert d-none mb-3" role="alert"></div>

            <form id="addIdeaForm" enctype="multipart/form-data">
                @csrf

                <!-- Idea Title -->
                <div class="mb-3">
                    <label for="ideaTitle" class="form-label">Tell us your idea! <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="ideaTitle" name="idea" required
                           placeholder="What's your brilliant idea?">
                    <div class="invalid-feedback" id="ideaError"></div>
                </div>

                <!-- Value Description -->
                <div class="mb-3">
                    <label for="ideaValue" class="form-label">Why will your idea add more value to the product?</label>
                    <textarea class="form-control" id="ideaValue" name="value_description" rows="3"
                              placeholder="Explain why this idea would be valuable..."></textarea>
                    <div class="invalid-feedback" id="valueError"></div>
                </div>

                <!-- Full Name -->
                <div class="mb-3">
                    <label for="ideaName" class="form-label">Full Name <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="ideaName" name="full_name" required
                           placeholder="Enter your full name"
                           value="{{ $isLoggedIn && $publicUser ? $publicUser->full_name : '' }}">
                    <div class="invalid-feedback" id="nameError"></div>
                </div>

                <!-- Email -->
                <div class="mb-3">
                    <label for="ideaEmail" class="form-label">Email Address <span class="text-danger">*</span></label>
                    <input type="email" class="form-control" id="ideaEmail" name="email" required
                           placeholder="Enter your email address"
                           value="{{ $isLoggedIn && $publicUser ? $publicUser->email : '' }}"
                           {{ $isLoggedIn && $publicUser ? 'readonly' : '' }}>
                    <div class="form-text">We'll send a confirmation link to this email.</div>
                    <div class="invalid-feedback" id="emailError"></div>
                </div>

                <!-- Categories/Topics -->
                <div class="mb-3">
                    <label class="form-label">Choose up to 3 topics for this idea (Optional)</label>
                    <div class="topic-checkbox">
                        @foreach($categories as $category)
                            <div class="form-check">
                                <input class="form-check-input topic-input" type="checkbox"
                                       name="categories[]" value="{{ $category->id }}"
                                       id="category{{ $category->id }}">
                                <label class="form-check-label" for="category{{ $category->id }}">
                                    {{ $category->name }}
                                </label>
                            </div>
                        @endforeach
                    </div>
                    <div class="form-text">Select categories that best describe your idea.</div>
                </div>

                <!-- Image Upload -->
                <div class="mb-3">
                    <label class="form-label">Add Image (Optional)</label>
                    <div class="image-upload-area" id="imageUploadArea" onclick="document.getElementById('ideaImage').click()">
                        <i class="ti ti-photo-plus" style="font-size: 2rem; color: var(--text-secondary);"></i>
                        <p class="mb-0 mt-2 text-muted">Click to upload an image</p>
                        <small class="text-muted">PNG, JPG up to 2MB</small>
                        <img id="imagePreview" class="image-preview d-none" alt="Preview">
                    </div>
                    <input type="file" class="d-none" id="ideaImage" name="image" accept="image/png,image/jpeg,image/jpg">
                    <div class="invalid-feedback" id="imageError"></div>
                </div>

                <!-- hCaptcha -->
                @if($feedbackSettings->enable_captcha ?? false)
                <div class="mb-3">
                    <div class="h-captcha" data-sitekey="{{ config('services.hcaptcha.sitekey') }}"></div>
                    <div class="invalid-feedback d-block" id="captchaError"></div>
                </div>
                @endif

                <!-- Privacy Policy Checkbox -->
                <div class="mb-4">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="privacyAgree" name="privacy_agree" required>
                        <label class="form-check-label" for="privacyAgree">
                            I agree with the <a href="#" target="_blank">Privacy Policy</a> and <a href="#" target="_blank">Terms and Conditions</a> <span class="text-danger">*</span>
                        </label>
                        <div class="invalid-feedback" id="privacyError">You must agree to the Privacy Policy and Terms.</div>
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="d-grid">
                    <button type="submit" class="btn btn-add-idea" id="submitIdeaBtn">
                        <i class="ti ti-send me-1"></i> Submit Idea
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- OTP Verification Modal -->
    <div class="modal fade" id="otpModal" tabindex="-1" aria-labelledby="otpModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header border-0 pb-0">
                    <h5 class="modal-title" id="otpModalLabel">
                        <i class="ti ti-mail-check me-2" style="color: var(--primary-color);"></i>Email Verification
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div id="otpAlert" class="alert d-none mb-3"></div>

                    <!-- Step 1: Email Input -->
                    <div id="otpStep1">
                        <p class="text-muted mb-3">Please enter your email address to verify your vote.</p>
                        <div class="mb-3">
                            <label for="otpEmail" class="form-label">Email Address</label>
                            <input type="email" class="form-control" id="otpEmail" placeholder="Enter your email">
                        </div>
                        <button type="button" class="btn w-100" style="background: var(--primary-color); color: white;" onclick="requestOtp()">
                            <i class="ti ti-send me-1"></i> Send Verification Code
                        </button>
                    </div>

                    <!-- Step 2: OTP Input -->
                    <div id="otpStep2" class="d-none">
                        <p class="text-muted mb-3">We've sent a 6-digit code to <strong id="otpEmailDisplay"></strong>. Enter it below to verify.</p>
                        <div class="mb-3">
                            <label for="otpCode" class="form-label">Verification Code</label>
                            <input type="text" class="form-control text-center fs-4 letter-spacing-2" id="otpCode" maxlength="6" placeholder="000000" style="letter-spacing: 0.5em;">
                        </div>
                        <button type="button" class="btn w-100 mb-2" style="background: var(--primary-color); color: white;" onclick="verifyOtp()">
                            <i class="ti ti-check me-1"></i> Verify & Vote
                        </button>
                        <button type="button" class="btn btn-link w-100 text-muted" onclick="resendOtp()">
                            Didn't receive the code? Resend
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // Configuration from server
        const CONFIG = {
            uniqueUrl: '{{ $settings->unique_url }}',
            isLoggedIn: {{ $isLoggedIn ? 'true' : 'false' }},
            requireLoginForVoting: {{ ($feedbackSettings->enable_login_for_voting ?? false) ? 'true' : 'false' }},
            enableAnonymousVoting: {{ ($feedbackSettings->enable_anonymous_voting ?? true) ? 'true' : 'false' }},
            requireOtpFirstVote: {{ ($feedbackSettings->require_email_otp_first_vote ?? false) ? 'true' : 'false' }},
            enableCaptcha: {{ ($feedbackSettings->enable_captcha ?? false) ? 'true' : 'false' }},
            csrfToken: document.querySelector('meta[name="csrf-token"]').content
        };

        // OTP Modal state
        let currentVoteElement = null;
        let currentFeedbackId = null;
        let otpModal = null;

        document.addEventListener('DOMContentLoaded', function() {
            otpModal = new bootstrap.Modal(document.getElementById('otpModal'));
        });

        // Voting function
        async function handleVote(element, feedbackId) {
            // Check if login is required
            if (CONFIG.requireLoginForVoting && !CONFIG.isLoggedIn) {
                if (confirm('You need to log in to vote. Would you like to log in now?')) {
                    window.location.href = '/' + CONFIG.uniqueUrl + '/login';
                }
                return;
            }

            // Check if voting is allowed
            if (!CONFIG.enableAnonymousVoting && !CONFIG.isLoggedIn) {
                alert('Anonymous voting is not enabled. Please log in to vote.');
                return;
            }

            // Add processing state
            element.classList.add('processing');

            try {
                const isVoted = element.classList.contains('voted');
                const action = isVoted ? 'unvote' : 'vote';

                const response = await fetch('/' + CONFIG.uniqueUrl + '/feedback/' + feedbackId + '/' + action, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': CONFIG.csrfToken,
                        'Accept': 'application/json'
                    }
                });

                const data = await response.json();

                if (data.success) {
                    // Update vote count
                    const countElement = element.querySelector('.vote-count');
                    countElement.textContent = data.vote_count;

                    // Toggle voted state
                    if (action === 'vote') {
                        element.classList.add('voted');
                        element.title = 'Click to remove your vote';
                    } else {
                        element.classList.remove('voted');
                        element.title = 'Click to vote for this idea';
                    }
                } else if (data.require_otp) {
                    // OTP required - show modal
                    element.classList.remove('processing');
                    currentVoteElement = element;
                    currentFeedbackId = feedbackId;
                    showOtpModal();
                } else {
                    alert(data.message || 'An error occurred. Please try again.');
                }
            } catch (error) {
                console.error('Vote error:', error);
                alert('An error occurred. Please try again.');
            } finally {
                element.classList.remove('processing');
            }
        }

        function showOtpModal() {
            // Reset modal state
            document.getElementById('otpStep1').classList.remove('d-none');
            document.getElementById('otpStep2').classList.add('d-none');
            document.getElementById('otpEmail').value = '';
            document.getElementById('otpCode').value = '';
            hideOtpAlert();
            otpModal.show();
        }

        async function requestOtp() {
            const email = document.getElementById('otpEmail').value.trim();

            if (!email || !isValidEmail(email)) {
                showOtpAlert('Please enter a valid email address.', 'danger');
                return;
            }

            try {
                const response = await fetch('/' + CONFIG.uniqueUrl + '/feedback/' + currentFeedbackId + '/request-otp', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': CONFIG.csrfToken,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ email: email })
                });

                const data = await response.json();

                if (data.success) {
                    if (data.already_verified) {
                        // Already verified, cast the vote directly
                        await castVoteWithEmail(email);
                    } else {
                        // Show OTP input step
                        document.getElementById('otpStep1').classList.add('d-none');
                        document.getElementById('otpStep2').classList.remove('d-none');
                        document.getElementById('otpEmailDisplay').textContent = email;
                        showOtpAlert(data.message, 'success');
                    }
                } else {
                    showOtpAlert(data.message || 'Failed to send verification code.', 'danger');
                }
            } catch (error) {
                console.error('OTP request error:', error);
                showOtpAlert('An error occurred. Please try again.', 'danger');
            }
        }

        async function verifyOtp() {
            const email = document.getElementById('otpEmail').value.trim();
            const otp = document.getElementById('otpCode').value.trim();

            if (!otp || otp.length !== 6) {
                showOtpAlert('Please enter the 6-digit verification code.', 'danger');
                return;
            }

            try {
                const response = await fetch('/' + CONFIG.uniqueUrl + '/feedback/' + currentFeedbackId + '/verify-otp', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': CONFIG.csrfToken,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ email: email, otp: otp })
                });

                const data = await response.json();

                if (data.success) {
                    // Update vote UI
                    if (currentVoteElement) {
                        const countElement = currentVoteElement.querySelector('.vote-count');
                        countElement.textContent = data.vote_count;
                        currentVoteElement.classList.add('voted');
                        currentVoteElement.title = 'Click to remove your vote';
                    }

                    showOtpAlert(data.message, 'success');

                    // Close modal after short delay
                    setTimeout(() => {
                        otpModal.hide();
                    }, 1500);
                } else {
                    showOtpAlert(data.message || 'Invalid verification code.', 'danger');
                }
            } catch (error) {
                console.error('OTP verify error:', error);
                showOtpAlert('An error occurred. Please try again.', 'danger');
            }
        }

        async function resendOtp() {
            await requestOtp();
        }

        async function castVoteWithEmail(email) {
            try {
                const response = await fetch('/' + CONFIG.uniqueUrl + '/feedback/' + currentFeedbackId + '/vote', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': CONFIG.csrfToken,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ email: email })
                });

                const data = await response.json();

                if (data.success) {
                    if (currentVoteElement) {
                        const countElement = currentVoteElement.querySelector('.vote-count');
                        countElement.textContent = data.vote_count;
                        currentVoteElement.classList.add('voted');
                        currentVoteElement.title = 'Click to remove your vote';
                    }

                    showOtpAlert('Vote recorded successfully!', 'success');

                    setTimeout(() => {
                        otpModal.hide();
                    }, 1500);
                } else {
                    showOtpAlert(data.message || 'Failed to cast vote.', 'danger');
                }
            } catch (error) {
                console.error('Vote error:', error);
                showOtpAlert('An error occurred. Please try again.', 'danger');
            }
        }

        function showOtpAlert(message, type) {
            const alert = document.getElementById('otpAlert');
            alert.className = `alert alert-${type} mb-3`;
            alert.textContent = message;
            alert.classList.remove('d-none');
        }

        function hideOtpAlert() {
            document.getElementById('otpAlert').classList.add('d-none');
        }

        function isValidEmail(email) {
            return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);
        }
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const categoryItems = document.querySelectorAll('.category-item');
            const feedbackCards = document.querySelectorAll('.feedback-card');
            const searchInput = document.getElementById('searchInput');
            const yearFilter = document.getElementById('yearFilter');
            const monthFilter = document.getElementById('monthFilter');
            const sortFilter = document.getElementById('sortFilter');

            let selectedCategory = '';

            // Populate year filter dynamically
            const years = new Set();
            feedbackCards.forEach(card => {
                years.add(card.dataset.year);
            });
            Array.from(years).sort((a, b) => b - a).forEach(year => {
                const option = document.createElement('option');
                option.value = year;
                option.textContent = year;
                yearFilter.appendChild(option);
            });

            // Combined filter function
            function applyFilters() {
                const searchTerm = searchInput.value.toLowerCase();
                const selectedYear = yearFilter.value;
                const selectedMonth = monthFilter.value;
                const sortOrder = sortFilter.value;

                // Convert NodeList to Array for sorting
                let cardsArray = Array.from(feedbackCards);

                // Sort cards
                cardsArray.sort((a, b) => {
                    const timeA = parseInt(a.dataset.timestamp);
                    const timeB = parseInt(b.dataset.timestamp);
                    return sortOrder === 'latest' ? timeB - timeA : timeA - timeB;
                });

                // Reorder DOM elements
                const parent = cardsArray[0]?.parentElement;
                if (parent) {
                    cardsArray.forEach(card => parent.appendChild(card));
                }

                // Filter cards
                feedbackCards.forEach(card => {
                    const matchesSearch = !searchTerm || card.dataset.search.includes(searchTerm);
                    const matchesCategory = !selectedCategory || card.dataset.category === selectedCategory;
                    const matchesYear = !selectedYear || card.dataset.year === selectedYear;
                    const matchesMonth = !selectedMonth || card.dataset.month === selectedMonth;

                    if (matchesSearch && matchesCategory && matchesYear && matchesMonth) {
                        card.style.display = 'block';
                    } else {
                        card.style.display = 'none';
                    }
                });
            }

            // Category filtering
            categoryItems.forEach(item => {
                item.addEventListener('click', function(e) {
                    e.preventDefault();
                    categoryItems.forEach(cat => cat.classList.remove('active'));
                    this.classList.add('active');
                    selectedCategory = this.dataset.category;
                    applyFilters();
                });
            });

            // Search filtering
            searchInput.addEventListener('input', applyFilters);

            // Year/Month filtering
            yearFilter.addEventListener('change', applyFilters);
            monthFilter.addEventListener('change', applyFilters);

            // Sort filtering
            sortFilter.addEventListener('change', applyFilters);

            // Initial sort
            applyFilters();

            // === Add Idea Form Handling ===
            const addIdeaForm = document.getElementById('addIdeaForm');
            const ideaAlert = document.getElementById('ideaAlert');
            const submitBtn = document.getElementById('submitIdeaBtn');
            const imageInput = document.getElementById('ideaImage');
            const imagePreview = document.getElementById('imagePreview');
            const imageUploadArea = document.getElementById('imageUploadArea');
            const topicCheckboxes = document.querySelectorAll('.topic-input');

            // Limit topic selection to 3
            topicCheckboxes.forEach(checkbox => {
                checkbox.addEventListener('change', function() {
                    const checked = document.querySelectorAll('.topic-input:checked');
                    if (checked.length > 3) {
                        this.checked = false;
                        showAlert('You can only select up to 3 topics.', 'warning');
                    }
                });
            });

            // Image preview
            imageInput.addEventListener('change', function() {
                const file = this.files[0];
                if (file) {
                    // Validate file size (2MB)
                    if (file.size > 2 * 1024 * 1024) {
                        showAlert('Image size must be less than 2MB.', 'danger');
                        this.value = '';
                        return;
                    }

                    const reader = new FileReader();
                    reader.onload = function(e) {
                        imagePreview.src = e.target.result;
                        imagePreview.classList.remove('d-none');
                        imageUploadArea.classList.add('has-image');
                    };
                    reader.readAsDataURL(file);
                }
            });

            // Form submission
            addIdeaForm.addEventListener('submit', async function(e) {
                e.preventDefault();

                // Clear previous errors
                document.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));
                hideAlert();

                // Validate privacy checkbox
                const privacyCheckbox = document.getElementById('privacyAgree');
                if (!privacyCheckbox.checked) {
                    privacyCheckbox.classList.add('is-invalid');
                    return;
                }

                // Validate hCaptcha if enabled
                if (CONFIG.enableCaptcha) {
                    const hcaptchaResponse = document.querySelector('[name="h-captcha-response"]')?.value;
                    if (!hcaptchaResponse) {
                        document.getElementById('captchaError').textContent = 'Please complete the captcha.';
                        return;
                    }
                }

                // Show loading state
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> Submitting...';

                try {
                    const formData = new FormData(this);

                    const response = await fetch('/' + CONFIG.uniqueUrl + '/feedback/submit', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': CONFIG.csrfToken,
                            'Accept': 'application/json'
                        },
                        body: formData
                    });

                    const data = await response.json();

                    if (data.success) {
                        // Reset form
                        addIdeaForm.reset();
                        imagePreview.classList.add('d-none');
                        imageUploadArea.classList.remove('has-image');

                        // Reset hCaptcha if enabled
                        if (CONFIG.enableCaptcha && typeof hcaptcha !== 'undefined') {
                            hcaptcha.reset();
                        }

                        // Close offcanvas immediately
                        const offcanvas = bootstrap.Offcanvas.getInstance(document.getElementById('addIdeaOffcanvas'));
                        if (offcanvas) {
                            offcanvas.hide();
                        }

                        // Show success message on the main page
                        showPageSuccess(data.message || 'Your idea has been submitted! Please check your email to confirm.');
                    } else {
                        // Show validation errors
                        if (data.errors) {
                            Object.keys(data.errors).forEach(field => {
                                const input = document.querySelector(`[name="${field}"]`);
                                if (input) {
                                    input.classList.add('is-invalid');
                                    const feedback = input.nextElementSibling;
                                    if (feedback && feedback.classList.contains('invalid-feedback')) {
                                        feedback.textContent = data.errors[field][0];
                                    }
                                }
                            });
                        }
                        showAlert(data.message || 'Please correct the errors and try again.', 'danger');
                    }
                } catch (error) {
                    console.error('Submit error:', error);
                    showAlert('An error occurred. Please try again.', 'danger');
                } finally {
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = '<i class="ti ti-send me-1"></i> Submit Idea';
                }
            });

            function showAlert(message, type) {
                ideaAlert.className = `alert alert-${type} mb-3`;
                ideaAlert.textContent = message;
                ideaAlert.classList.remove('d-none');
            }

            function hideAlert() {
                ideaAlert.classList.add('d-none');
            }

            function showPageSuccess(message) {
                const pageAlert = document.getElementById('pageSuccessAlert');
                const pageMessage = document.getElementById('pageSuccessMessage');

                pageMessage.textContent = message;
                pageAlert.classList.remove('d-none');
                pageAlert.classList.add('show');

                // Scroll to top to show the message
                window.scrollTo({ top: 0, behavior: 'smooth' });

                // Auto-hide after 8 seconds
                setTimeout(() => {
                    pageAlert.classList.remove('show');
                    setTimeout(() => {
                        pageAlert.classList.add('d-none');
                    }, 150);
                }, 8000);
            }
        });
    </script>
</body>
</html>
