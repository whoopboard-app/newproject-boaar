<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Testimonials - {{ $settings->product_name ?? 'Feedback' }}</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Tabler Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/tabler-icons.min.css">

    <!-- Video.js for HLS playback -->
    <link href="https://vjs.zencdn.net/8.6.1/video-js.css" rel="stylesheet">

    <style>
        :root {
            --primary-color: #5865F2;
            --border-color: #e5e7eb;
            --text-primary: #1f2937;
            --text-secondary: #6b7280;
            --bg-hover: #f9fafb;
            --card-bg: #ffffff;
            --page-bg: #f3f4f6;
        }

        * {
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            background-color: var(--page-bg);
            color: var(--text-primary);
            margin: 0;
            padding: 0;
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

        /* Page Header */
        .page-header {
            text-align: center;
            padding: 3rem 1rem 2rem;
            background: white;
            border-bottom: 1px solid var(--border-color);
        }

        .page-header h1 {
            font-size: 2.5rem;
            font-weight: 800;
            color: var(--text-primary);
            margin: 0 0 0.75rem 0;
            letter-spacing: -0.02em;
        }

        .page-header p {
            color: var(--text-secondary);
            font-size: 1.125rem;
            margin: 0;
            max-width: 600px;
            margin: 0 auto;
        }

        /* Masonry Grid */
        .masonry-container {
            padding: 2rem 1rem;
            max-width: 1400px;
            margin: 0 auto;
        }

        .masonry-grid {
            column-count: 4;
            column-gap: 1.25rem;
        }

        @media (max-width: 1200px) {
            .masonry-grid {
                column-count: 3;
            }
        }

        @media (max-width: 900px) {
            .masonry-grid {
                column-count: 2;
            }
        }

        @media (max-width: 600px) {
            .masonry-grid {
                column-count: 1;
            }

            .page-header h1 {
                font-size: 1.75rem;
            }
        }

        /* Testimonial Card - Wall of Love Style */
        .testimonial-card {
            background: var(--card-bg);
            border-radius: 16px;
            padding: 1.5rem;
            margin-bottom: 1.25rem;
            break-inside: avoid;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.08), 0 1px 2px rgba(0, 0, 0, 0.06);
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .testimonial-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 24px rgba(0, 0, 0, 0.1), 0 4px 8px rgba(0, 0, 0, 0.06);
        }

        /* Rating Stars */
        .testimonial-rating {
            display: flex;
            gap: 2px;
            margin-bottom: 1rem;
        }

        .testimonial-rating .star {
            color: #FBBF24;
            font-size: 1.125rem;
        }

        .testimonial-rating .star.empty {
            color: #E5E7EB;
        }

        /* Content */
        .testimonial-content {
            color: var(--text-primary);
            font-size: 0.9375rem;
            line-height: 1.65;
            margin-bottom: 1.25rem;
            word-wrap: break-word;
        }

        /* Video */
        .testimonial-video {
            position: relative;
            border-radius: 12px;
            overflow: hidden;
            margin-bottom: 1.25rem;
            background: #000;
        }

        .testimonial-video img {
            width: 100%;
            height: auto;
            display: block;
        }

        .testimonial-video iframe {
            width: 100%;
            aspect-ratio: 16/9;
            border: none;
            display: block;
        }

        .video-play-overlay {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            background: rgba(0, 0, 0, 0.3);
            cursor: pointer;
            transition: background 0.2s;
        }

        .video-play-overlay:hover {
            background: rgba(0, 0, 0, 0.4);
        }

        .video-play-btn {
            width: 64px;
            height: 64px;
            background: rgba(255, 255, 255, 0.95);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--text-primary);
            font-size: 1.5rem;
            transition: transform 0.2s;
        }

        .video-play-overlay:hover .video-play-btn {
            transform: scale(1.1);
        }

        /* Author Section */
        .testimonial-author {
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .author-avatar {
            width: 44px;
            height: 44px;
            border-radius: 50%;
            overflow: hidden;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 600;
            font-size: 1rem;
            flex-shrink: 0;
        }

        .author-avatar img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .author-info {
            flex: 1;
            min-width: 0;
        }

        .author-name {
            font-size: 0.9375rem;
            font-weight: 600;
            color: var(--text-primary);
            margin: 0;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .author-title {
            font-size: 0.8125rem;
            color: var(--text-secondary);
            margin: 0;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        /* Source Badge */
        .testimonial-source {
            display: inline-flex;
            align-items: center;
            gap: 0.375rem;
            font-size: 0.75rem;
            color: var(--text-secondary);
            margin-top: 1rem;
            padding-top: 1rem;
            border-top: 1px solid #f0f0f0;
        }

        .testimonial-source i {
            font-size: 0.875rem;
        }

        /* Twitter/X Style Card Variant */
        .testimonial-card.twitter-style {
            border-left: 3px solid #1DA1F2;
        }

        .testimonial-card.linkedin-style {
            border-left: 3px solid #0A66C2;
        }

        /* Empty State */
        .empty-state {
            text-align: center;
            padding: 6rem 2rem;
            background: white;
            border-radius: 16px;
            max-width: 500px;
            margin: 2rem auto;
        }

        .empty-icon {
            font-size: 4rem;
            color: #E5E7EB;
            margin-bottom: 1.5rem;
        }

        .empty-title {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--text-primary);
            margin-bottom: 0.5rem;
        }

        .empty-text {
            color: var(--text-secondary);
            font-size: 1rem;
        }

        /* Pagination */
        .pagination-wrapper {
            padding: 2rem 1rem 3rem;
            display: flex;
            justify-content: center;
        }

        .pagination {
            gap: 0.5rem;
        }

        .pagination .page-link {
            border-radius: 8px;
            border: none;
            color: var(--text-secondary);
            padding: 0.5rem 1rem;
            background: white;
            box-shadow: 0 1px 2px rgba(0,0,0,0.05);
        }

        .pagination .page-link:hover {
            background: var(--bg-hover);
            color: var(--text-primary);
        }

        .pagination .page-item.active .page-link {
            background: var(--primary-color);
            color: white;
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

    <!-- Page Header -->
    <div class="page-header">
        <h1>Wall of Love</h1>
        <p>See what our amazing customers have to say about {{ $settings->product_name ?? 'us' }}</p>
    </div>

    <!-- Main Content -->
    @if($testimonials->count() > 0)
        <div class="masonry-container">
            <div class="masonry-grid">
                @foreach($testimonials as $testimonial)
                    @php
                        $sourceClass = '';
                        if ($testimonial->source) {
                            $sourceLower = strtolower($testimonial->source);
                            if (str_contains($sourceLower, 'twitter') || str_contains($sourceLower, 'x.com')) {
                                $sourceClass = 'twitter-style';
                            } elseif (str_contains($sourceLower, 'linkedin')) {
                                $sourceClass = 'linkedin-style';
                            }
                        }
                    @endphp
                    <div class="testimonial-card {{ $sourceClass }}">
                        @if($testimonial->rating)
                            <div class="testimonial-rating">
                                @for($i = 1; $i <= 5; $i++)
                                    <i class="ti ti-star-filled star {{ $i <= $testimonial->rating ? '' : 'empty' }}"></i>
                                @endfor
                            </div>
                        @endif

                        @if($testimonial->type === 'video')
                            <div class="testimonial-video">
                                @if($testimonial->hasMuxVideo())
                                    {{-- Mux Video Player --}}
                                    <div class="mux-video-container" style="position: relative; aspect-ratio: 16/9;">
                                        <video
                                            id="mux-player-{{ $testimonial->id }}"
                                            class="video-js vjs-default-skin"
                                            controls
                                            preload="metadata"
                                            poster="{{ $testimonial->getMuxThumbnailUrl() }}"
                                            style="width: 100%; height: 100%;"
                                        >
                                            <source src="{{ $testimonial->getMuxStreamUrl() }}" type="application/x-mpegURL">
                                        </video>
                                    </div>
                                @elseif($testimonial->video_url)
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
                                    @elseif($testimonial->video_thumbnail)
                                        <img src="{{ asset('storage/' . $testimonial->video_thumbnail) }}" alt="Video thumbnail">
                                        <div class="video-play-overlay" onclick="window.open('{{ $videoUrl }}', '_blank')">
                                            <div class="video-play-btn">
                                                <i class="ti ti-player-play-filled"></i>
                                            </div>
                                        </div>
                                    @endif
                                @elseif($testimonial->mux_status === 'processing')
                                    {{-- Video is still processing --}}
                                    <div style="aspect-ratio: 16/9; background: #f0f0f0; display: flex; align-items: center; justify-content: center; border-radius: 12px;">
                                        <div class="text-center text-muted">
                                            <i class="ti ti-loader ti-spin" style="font-size: 2rem;"></i>
                                            <p class="mt-2 mb-0">Video processing...</p>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        @endif

                        @if($testimonial->text_content)
                            <div class="testimonial-content">
                                {{ $testimonial->text_content }}
                            </div>
                        @endif

                        <div class="testimonial-author">
                            @if($testimonial->avatar)
                                <div class="author-avatar">
                                    <img src="{{ asset('storage/' . $testimonial->avatar) }}" alt="{{ $testimonial->name }}">
                                </div>
                            @else
                                <div class="author-avatar">
                                    {{ strtoupper(substr($testimonial->name, 0, 1)) }}
                                </div>
                            @endif
                            <div class="author-info">
                                <p class="author-name">{{ $testimonial->name }}</p>
                                @if($testimonial->position || $testimonial->company)
                                    <p class="author-title">
                                        @if($testimonial->position){{ $testimonial->position }}@endif
                                        @if($testimonial->position && $testimonial->company) @ @endif
                                        @if($testimonial->company){{ $testimonial->company }}@endif
                                    </p>
                                @endif
                            </div>
                        </div>

                        @if($testimonial->source)
                            <div class="testimonial-source">
                                @if(str_contains(strtolower($testimonial->source), 'twitter') || str_contains(strtolower($testimonial->source), 'x.com'))
                                    <i class="ti ti-brand-twitter" style="color: #1DA1F2;"></i>
                                @elseif(str_contains(strtolower($testimonial->source), 'linkedin'))
                                    <i class="ti ti-brand-linkedin" style="color: #0A66C2;"></i>
                                @elseif(str_contains(strtolower($testimonial->source), 'facebook'))
                                    <i class="ti ti-brand-facebook" style="color: #1877F2;"></i>
                                @elseif(str_contains(strtolower($testimonial->source), 'google'))
                                    <i class="ti ti-brand-google" style="color: #4285F4;"></i>
                                @elseif(str_contains(strtolower($testimonial->source), 'producthunt'))
                                    <i class="ti ti-brand-producthunt" style="color: #DA552F;"></i>
                                @else
                                    <i class="ti ti-world"></i>
                                @endif
                                {{ $testimonial->source }}
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Pagination -->
        @if($testimonials->hasPages())
            <div class="pagination-wrapper">
                {{ $testimonials->links() }}
            </div>
        @endif
    @else
        <div class="masonry-container">
            <div class="empty-state">
                <i class="ti ti-heart empty-icon"></i>
                <h2 class="empty-title">No testimonials yet</h2>
                <p class="empty-text">Be the first to share your love!</p>
            </div>
        </div>
    @endif

    @include('partials.public-footer')

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Video.js for HLS playback -->
    <script src="https://vjs.zencdn.net/8.6.1/video.min.js"></script>
    <script>
        // Initialize all Video.js players for Mux videos
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.video-js').forEach(function(videoEl) {
                videojs(videoEl, {
                    controls: true,
                    fluid: true,
                    preload: 'metadata'
                });
            });
        });
    </script>
</body>
</html>
