@php
    $menuConfig = [
        'feedback' => [
            'route' => 'public.home',
            'icon' => 'ti ti-message-2',
            'default_label' => 'Feedback'
        ],
        'roadmap' => [
            'route' => 'public.roadmap',
            'icon' => 'ti ti-route',
            'default_label' => 'Roadmap'
        ],
        'changelog' => [
            'route' => 'public.changelog',
            'icon' => 'ti ti-clipboard-list',
            'default_label' => 'Changelog'
        ],
        'testimonial' => [
            'route' => 'public.testimonials',
            'icon' => 'ti ti-star',
            'default_label' => 'Testimonials'
        ],
        'knowledge_board' => [
            'route' => 'public.knowledge',
            'icon' => 'ti ti-book',
            'default_label' => 'Knowledge'
        ],
    ];

    $currentRoute = Route::currentRouteName();
@endphp

<nav class="public-nav">
    @foreach($menuOrder ?? [] as $item)
        @if(($item['visible'] ?? true) && isset($menuConfig[$item['key']]))
            @php
                $config = $menuConfig[$item['key']];
                $isActive = false;

                // Determine if this tab is active
                if ($item['key'] === 'feedback') {
                    $isActive = in_array($currentRoute, ['public.home', 'public.feedback']);
                } elseif ($item['key'] === 'roadmap') {
                    $isActive = $currentRoute === 'public.roadmap';
                } elseif ($item['key'] === 'changelog') {
                    $isActive = in_array($currentRoute, ['public.changelog', 'public.changelog.detail']);
                } elseif ($item['key'] === 'testimonial') {
                    $isActive = in_array($currentRoute, ['public.testimonials', 'public.testimonial']);
                } elseif ($item['key'] === 'knowledge_board') {
                    $isActive = str_starts_with($currentRoute, 'public.knowledge');
                }
            @endphp
            <a href="{{ route($config['route'], $settings->unique_url) }}" class="nav-tab {{ $isActive ? 'active' : '' }}">
                <i class="{{ $config['icon'] }}"></i> {{ $item['label'] ?? $config['default_label'] }}
            </a>
        @endif
    @endforeach
</nav>
