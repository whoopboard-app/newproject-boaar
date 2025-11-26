<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $testimonial->name }}'s Testimonial - {{ $settings->product_name ?? 'Feedback' }}</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Tabler Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/tabler-icons.min.css">

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
            grid-template-columns: 1fr 320px;
            gap: 2rem;
            padding: 2rem 0;
            max-width: 1200px;
            margin: 0 auto;
        }

        /* Back Link */
        .back-link {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            color: var(--text-secondary);
            text-decoration: none;
            font-size: 0.875rem;
            margin-bottom: 1.5rem;
            transition: color 0.2s;
        }

        .back-link:hover {
            color: var(--primary-color);
        }

        /* Main Testimonial Card */
        .testimonial-main {
            background: white;
            border: 1px solid var(--border-color);
            border-radius: 12px;
            padding: 2rem;
        }

        .testimonial-header {
            display: flex;
            align-items: center;
            gap: 1.5rem;
            margin-bottom: 1.5rem;
            padding-bottom: 1.5rem;
            border-bottom: 1px solid var(--border-color);
        }

        .testimonial-avatar {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            object-fit: cover;
            background: var(--primary-color);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 600;
            font-size: 2rem;
        }

        .testimonial-info h1 {
            font-size: 1.5rem;
            font-weight: 600;
            color: var(--text-primary);
            margin: 0 0 0.5rem 0;
        }

        .testimonial-info p {
            font-size: 1rem;
            color: var(--text-secondary);
            margin: 0;
        }

        .testimonial-rating {
            display: flex;
            gap: 0.25rem;
            margin-bottom: 1.5rem;
        }

        .testimonial-rating .star {
            color: #fbbf24;
            font-size: 1.5rem;
        }

        .testimonial-rating .star.empty {
            color: #e5e7eb;
        }

        .testimonial-video {
            position: relative;
            border-radius: 12px;
            overflow: hidden;
            margin-bottom: 1.5rem;
            background: #000;
        }

        .testimonial-video video {
            width: 100%;
            max-height: 500px;
        }

        .testimonial-video iframe {
            width: 100%;
            height: 400px;
            border: none;
        }

        .testimonial-content {
            font-size: 1.125rem;
            line-height: 1.8;
            color: var(--text-primary);
        }

        .testimonial-content p {
            margin-bottom: 1rem;
        }

        .testimonial-quote {
            font-size: 1.25rem;
            font-style: italic;
            line-height: 1.8;
            color: var(--text-primary);
            position: relative;
            padding-left: 1.5rem;
            border-left: 4px solid var(--primary-color);
        }

        .testimonial-meta {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-top: 2rem;
            padding-top: 1.5rem;
            border-top: 1px solid var(--border-color);
        }

        .testimonial-source {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            color: var(--text-secondary);
            font-size: 0.875rem;
        }

        .testimonial-date {
            color: var(--text-secondary);
            font-size: 0.875rem;
        }

        .testimonial-type-badge {
            font-size: 0.875rem;
            padding: 0.5rem 1rem;
            border-radius: 6px;
            font-weight: 500;
        }

        .testimonial-type-badge.text {
            background: #dbeafe;
            color: #1d4ed8;
        }

        .testimonial-type-badge.video {
            background: #fce7f3;
            color: #be185d;
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

        .other-testimonial {
            background: white;
            border: 1px solid var(--border-color);
            border-radius: 8px;
            padding: 1rem;
            margin-bottom: 1rem;
            transition: all 0.2s;
            cursor: pointer;
            text-decoration: none;
            display: block;
        }

        .other-testimonial:hover {
            border-color: var(--primary-color);
            transform: translateX(4px);
        }

        .other-testimonial-header {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            margin-bottom: 0.5rem;
        }

        .other-testimonial-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            object-fit: cover;
            background: var(--primary-color);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 600;
            font-size: 1rem;
        }

        .other-testimonial-info h4 {
            font-size: 0.875rem;
            font-weight: 600;
            color: var(--text-primary);
            margin: 0;
        }

        .other-testimonial-info p {
            font-size: 0.75rem;
            color: var(--text-secondary);
            margin: 0;
        }

        .other-testimonial-preview {
            font-size: 0.8125rem;
            color: var(--text-secondary);
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .other-testimonial-rating {
            display: flex;
            gap: 0.125rem;
            margin-top: 0.5rem;
        }

        .other-testimonial-rating .star {
            color: #fbbf24;
            font-size: 0.75rem;
        }

        .other-testimonial-rating .star.empty {
            color: #e5e7eb;
        }

        /* Share Section */
        .share-section {
            background: white;
            border: 1px solid var(--border-color);
            border-radius: 8px;
            padding: 1rem;
            margin-bottom: 1rem;
        }

        .share-buttons {
            display: flex;
            gap: 0.5rem;
        }

        .share-btn {
            flex: 1;
            padding: 0.5rem;
            border: 1px solid var(--border-color);
            border-radius: 6px;
            background: white;
            color: var(--text-secondary);
            text-decoration: none;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.2s;
        }

        .share-btn:hover {
            border-color: var(--primary-color);
            color: var(--primary-color);
        }

        @media (max-width: 992px) {
            .public-content {
                grid-template-columns: 1fr;
            }

            .sidebar {
                position: static;
            }
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

                <a href="{{ route('public.subscribe', $settings->unique_url) }}" class="btn btn-primary" style="background: var(--primary-color); border: none; padding: 0.5rem 1.5rem; border-radius: 6px; text-decoration: none; color: white; font-weight: 500;">
                    <i class="ti ti-bell-ringing me-1"></i> Subscribe
                </a>
            </div>

            @include('public.partials.navigation')
        </div>
    </header>

    <!-- Main Content -->
    <div class="container">
        <div class="public-content">
            <!-- Main Testimonial -->
            <main>
                <a href="{{ route('public.testimonials', $settings->unique_url) }}" class="back-link">
                    <i class="ti ti-arrow-left"></i> Back to Testimonials
                </a>

                <div class="testimonial-main">
                    <div class="testimonial-header">
                        @if($testimonial->avatar)
                            <img src="{{ asset('storage/' . $testimonial->avatar) }}" alt="{{ $testimonial->name }}" class="testimonial-avatar">
                        @else
                            <div class="testimonial-avatar">
                                {{ strtoupper(substr($testimonial->name, 0, 1)) }}
                            </div>
                        @endif
                        <div class="testimonial-info">
                            <h1>{{ $testimonial->name }}</h1>
                            <p>
                                @if($testimonial->position){{ $testimonial->position }}@endif
                                @if($testimonial->position && $testimonial->company) at @endif
                                @if($testimonial->company)<strong>{{ $testimonial->company }}</strong>@endif
                            </p>
                        </div>
                    </div>

                    @if($testimonial->rating)
                        <div class="testimonial-rating">
                            @for($i = 1; $i <= 5; $i++)
                                <i class="ti ti-star-filled star {{ $i <= $testimonial->rating ? '' : 'empty' }}"></i>
                            @endfor
                        </div>
                    @endif

                    @if($testimonial->type === 'video' && $testimonial->video_url)
                        <div class="testimonial-video">
                            @php
                                $videoUrl = $testimonial->video_url;
                                $isYoutube = strpos($videoUrl, 'youtube.com') !== false || strpos($videoUrl, 'youtu.be') !== false;
                                $isVimeo = strpos($videoUrl, 'vimeo.com') !== false;

                                if ($isYoutube) {
                                    preg_match('/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/\s]{11})/', $videoUrl, $matches);
                                    $youtubeId = $matches[1] ?? '';
                                    $embedUrl = "https://www.youtube.com/embed/{$youtubeId}";
                                } elseif ($isVimeo) {
                                    preg_match('/vimeo\.com\/(\d+)/', $videoUrl, $matches);
                                    $vimeoId = $matches[1] ?? '';
                                    $embedUrl = "https://player.vimeo.com/video/{$vimeoId}";
                                }
                            @endphp

                            @if($isYoutube || $isVimeo)
                                <iframe src="{{ $embedUrl }}" allowfullscreen></iframe>
                            @else
                                <video controls>
                                    <source src="{{ $videoUrl }}" type="video/mp4">
                                    Your browser does not support the video tag.
                                </video>
                            @endif
                        </div>
                    @endif

                    @if($testimonial->text_content)
                        <div class="testimonial-quote">
                            {{ $testimonial->text_content }}
                        </div>
                    @endif

                    <div class="testimonial-meta">
                        <div class="d-flex align-items-center gap-3">
                            <span class="testimonial-type-badge {{ $testimonial->type }}">
                                <i class="ti {{ $testimonial->type === 'video' ? 'ti-video' : 'ti-message-circle' }} me-1"></i>
                                {{ ucfirst($testimonial->type) }} Testimonial
                            </span>
                            @if($testimonial->source)
                                <span class="testimonial-source">
                                    <i class="ti ti-external-link"></i> {{ $testimonial->source }}
                                </span>
                            @endif
                        </div>
                        <span class="testimonial-date">
                            <i class="ti ti-calendar me-1"></i> {{ $testimonial->created_at->format('F d, Y') }}
                        </span>
                    </div>
                </div>
            </main>

            <!-- Sidebar -->
            <aside class="sidebar">
                <!-- Share Section -->
                <div class="share-section">
                    <div class="sidebar-title">Share</div>
                    <div class="share-buttons">
                        <a href="https://twitter.com/intent/tweet?url={{ urlencode(request()->url()) }}&text={{ urlencode($testimonial->name . "'s testimonial") }}" target="_blank" class="share-btn">
                            <i class="ti ti-brand-twitter"></i>
                        </a>
                        <a href="https://www.linkedin.com/sharing/share-offsite/?url={{ urlencode(request()->url()) }}" target="_blank" class="share-btn">
                            <i class="ti ti-brand-linkedin"></i>
                        </a>
                        <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(request()->url()) }}" target="_blank" class="share-btn">
                            <i class="ti ti-brand-facebook"></i>
                        </a>
                        <button onclick="navigator.clipboard.writeText('{{ request()->url() }}'); alert('Link copied!');" class="share-btn">
                            <i class="ti ti-link"></i>
                        </button>
                    </div>
                </div>

                <!-- Other Testimonials -->
                @if($otherTestimonials->count() > 0)
                    <div class="sidebar-title" style="margin-top: 1.5rem;">More Testimonials</div>
                    @foreach($otherTestimonials as $other)
                        <a href="{{ route('public.testimonials.show', [$settings->unique_url, $other->id]) }}" class="other-testimonial">
                            <div class="other-testimonial-header">
                                @if($other->avatar)
                                    <img src="{{ asset('storage/' . $other->avatar) }}" alt="{{ $other->name }}" class="other-testimonial-avatar">
                                @else
                                    <div class="other-testimonial-avatar">
                                        {{ strtoupper(substr($other->name, 0, 1)) }}
                                    </div>
                                @endif
                                <div class="other-testimonial-info">
                                    <h4>{{ $other->name }}</h4>
                                    @if($other->company)
                                        <p>{{ $other->company }}</p>
                                    @endif
                                </div>
                            </div>
                            @if($other->text_content)
                                <p class="other-testimonial-preview">"{{ Str::limit($other->text_content, 80) }}"</p>
                            @endif
                            @if($other->rating)
                                <div class="other-testimonial-rating">
                                    @for($i = 1; $i <= 5; $i++)
                                        <i class="ti ti-star-filled star {{ $i <= $other->rating ? '' : 'empty' }}"></i>
                                    @endfor
                                </div>
                            @endif
                        </a>
                    @endforeach
                @endif
            </aside>
        </div>
    </div>

    @include('partials.public-footer')

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
