<!--
Product: Metronic is a toolkit of UI components built with Tailwind CSS for developing scalable web applications quickly and efficiently
Version: v9.3.6
Author: Keenthemes
Contact: support@keenthemes.com
Website: https://www.keenthemes.com
Support: https://devs.keenthemes.com
Follow: https://www.twitter.com/keenthemes
License: https://keenthemes.com/metronic/tailwind/docs/getting-started/license
-->
<!DOCTYPE html>
<html class="h-full" data-kt-theme="true" data-kt-theme-mode="light" dir="ltr" lang="en">

<head>
    <base href="../../../">
    <title>
        Metronic - Tailwind CSS Search Results - List
    </title>
    <meta charset="utf-8" />
    <meta content="follow, index" name="robots" />
    <link href="https://127.0.0.1:8001/metronic-tailwind-html/demo1/store-client/search-results-list" rel="canonical" />
    <meta content="width=device-width, initial-scale=1, shrink-to-fit=no" name="viewport" />
    <meta content="Search results list cards layout, using Tailwind CSS" name="description" />
    <meta content="@keenthemes" name="twitter:site" />
    <meta content="@keenthemes" name="twitter:creator" />
    <meta content="summary_large_image" name="twitter:card" />
    <meta content="Metronic - Tailwind CSS Search Results - List" name="twitter:title" />
    <meta content="Search results list cards layout, using Tailwind CSS" name="twitter:description" />
    <!-- <meta content="assets/media/app/og-image.png" name="twitter:image" /> -->
    <meta content="https://127.0.0.1:8001/metronic-tailwind-html/demo1/store-client/search-results-list" property="og:url" />
    <meta content="en_US" property="og:locale" />
    <meta content="website" property="og:type" />
    <meta content="@keenthemes" property="og:site_name" />
    <meta content="Metronic - Tailwind CSS Search Results - List" property="og:title" />
    <meta content="Search results list cards layout, using Tailwind CSS" property="og:description" />
    <meta content="assets/media/app/og-image.png" property="og:image" />
    <!-- <link href="assets/media/app/apple-touch-icon.png" rel="apple-touch-icon" sizes="180x180" />
    <link href="assets/media/app/favicon-32x32.png" rel="icon" sizes="32x32" type="image/png" />
    <link href="assets/media/app/favicon-16x16.png" rel="icon" sizes="16x16" type="image/png" />
    <link href="assets/media/app/favicon.ico" rel="shortcut icon" /> -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet" />
    <link href="{{asset('assets/new-theme/vendors/apexcharts/apexcharts.css')}}" rel="stylesheet" />
    <link href="{{asset('assets/new-theme/vendors/keenicons/styles.bundle.css')}}" rel="stylesheet" />
    <link href="{{asset('assets/new-theme/css/styles.css')}}" rel="stylesheet" />
</head>
<style>
    .kt-menu-item.active .kt-menu-title{
        color: #1ab394;
    }
</style>
<body class="antialiased flex h-full text-base text-foreground bg-background demo1 kt-sidebar-fixed kt-header-fixed">
    <!-- Theme Mode -->
    <script>
        const defaultThemeMode = 'light'; // light|dark|system
        let themeMode;

        if (document.documentElement) {
            if (localStorage.getItem('kt-theme')) {
                themeMode = localStorage.getItem('kt-theme');
            } else if (
                document.documentElement.hasAttribute('data-kt-theme-mode')
            ) {
                themeMode =
                    document.documentElement.getAttribute('data-kt-theme-mode');
            } else {
                themeMode = defaultThemeMode;
            }

            if (themeMode === 'system') {
                themeMode = window.matchMedia('(prefers-color-scheme: dark)').matches ?
                    'dark' :
                    'light';
            }

            document.documentElement.classList.add(themeMode);
        }
    </script>
    <!-- End of Theme Mode -->
    <!-- Page -->
    <!-- Main -->
    <div class="flex grow">
        <!-- Sidebar -->
        <div class="kt-sidebar bg-background border-e border-e-border fixed top-0 bottom-0 z-20 hidden lg:flex flex-col items-stretch shrink-0 [--kt-drawer-enable:true] lg:[--kt-drawer-enable:false]" data-kt-drawer="true" data-kt-drawer-class="kt-drawer kt-drawer-start top-0 bottom-0" id="sidebar">
            <div class="kt-sidebar-header hidden lg:flex items-center relative justify-between px-3 lg:px-6 shrink-0" id="sidebar_header">
                <a class="dark:hidden" href="{{ route('dashboard') }}">
                    <img class="default-logo min-h-[22px] max-w-none" src="{{ asset('assets/images/logo.png') }}" />
                    <img class="small-logo min-h-[22px] max-w-none" src="{{ asset('assets/images/logo-sm.png') }}" />
                </a>
                <a class="hidden dark:block" href="{{ route('dashboard') }}">
                    <img class="default-logo min-h-[22px] max-w-none" src="{{ asset('assets/images/logo-black.png') }}" />
                    <img class="small-logo min-h-[22px] max-w-none" src="{{ asset('assets/images/logo-sm.png') }}" />
                </a>
                <button class="kt-btn kt-btn-outline kt-btn-icon size-[30px] absolute start-full top-2/4 -translate-x-2/4 -translate-y-2/4 rtl:translate-x-2/4" data-kt-toggle="body" data-kt-toggle-class="kt-sidebar-collapse" id="sidebar_toggle">
                    <i class="ki-filled ki-black-left-line kt-toggle-active:rotate-180 transition-all duration-300 rtl:translate rtl:rotate-180 rtl:kt-toggle-active:rotate-0">
                    </i>
                </button>
            </div>
          <div class="kt-sidebar-content flex grow shrink-0 py-5 pe-2" id="sidebar_content">
                <div class="kt-scrollable-y-hover grow shrink-0 flex ps-2 lg:ps-5 pe-1 lg:pe-3" data-kt-scrollable="true" data-kt-scrollable-dependencies="#sidebar_header" data-kt-scrollable-height="auto" data-kt-scrollable-offset="0px" data-kt-scrollable-wrappers="#sidebar_content" id="sidebar_scrollable">
                    <!-- Sidebar Menu -->
                    <div class="kt-menu flex flex-col grow gap-1" data-kt-menu="true" data-kt-menu-accordion-expand-all="false" id="sidebar_menu">
                        <div class="kt-menu-item pt-2.25 pb-px">
                            <span class="kt-menu-heading uppercase text-xs font-medium text-muted-foreground ps-[10px] pe-[10px]">
                                Menu
                            </span>
                        </div>
                        <div class="kt-menu-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                            <a class="kt-menu-link flex items-center grow cursor-pointer border border-transparent gap-[10px] ps-[10px] pe-[10px] py-[6px]" href="{{ route('dashboard') }}" tabindex="0">
                                <span class="kt-menu-icon items-start text-muted-foreground w-[20px]">
                                    <i class="ki-filled ki-element-11 text-lg">
                                    </i>
                                </span>
                                <span class="kt-menu-title text-sm font-medium text-foreground kt-menu-item-active:text-primary kt-menu-link-hover:!text-primary">
                                    Dashboard
                                </span>
                            </a>
                        </div>

                        <div class="kt-menu-item {{ request()->routeIs('changelog.*') ? 'active' : '' }}">
                            <a class="kt-menu-link flex items-center grow cursor-pointer border border-transparent gap-[10px] ps-[10px] pe-[10px] py-[6px]" href="{{ Route::has('changelog.index') ? route('changelog.index') : '#' }}" tabindex="0">
                                <span class="kt-menu-icon items-start text-muted-foreground w-[20px]">
                                    <i class="ki-filled ki-security-user text-lg">
                                    </i>
                                </span>
                                <span class="kt-menu-title text-sm font-medium text-foreground kt-menu-item-active:text-primary kt-menu-link-hover:!text-primary">
                                    Changelog
                                </span>
                            </a>
                        </div>
                        <div class="kt-menu-item {{ request()->routeIs('feedback.*') ? 'active' : '' }}">
                            <a class="kt-menu-link flex items-center grow cursor-pointer border border-transparent gap-[10px] ps-[10px] pe-[10px] py-[6px]" href="{{ Route::has('feedback.index') ? route('feedback.index') : '#' }}" tabindex="0">
                                <span class="kt-menu-icon items-start text-muted-foreground w-[20px]">
                                    <i class="ki-filled ki-element-10 text-lg">
                                    </i>
                                </span>
                                <span class="kt-menu-title text-sm font-medium text-foreground kt-menu-item-active:text-primary kt-menu-link-hover:!text-primary">
                                    Feedback
                                </span>
                            </a>
                        </div>
                        <div class="kt-menu-item {{ request()->routeIs('roadmap.index') ? 'active' : '' }}">
                            <a class="kt-menu-link flex items-center grow cursor-pointer border border-transparent gap-[10px] ps-[10px] pe-[10px] py-[6px]" href="{{ Route::has('roadmap.index') ? route('roadmap.index') : '#' }}" tabindex="0">
                                <span class="kt-menu-icon items-start text-muted-foreground w-[20px]">
                                    <i class="ki-filled ki-people text-lg">
                                    </i>
                                </span>
                                <span class="kt-menu-title text-sm font-medium text-foreground kt-menu-item-active:text-primary kt-menu-link-hover:!text-primary">
                                    Roadmap Statuses
                                </span>
                            </a>
                        </div>
                        <div class="kt-menu-item {{ request()->routeIs('roadmap-items.*') ? 'active' : '' }}">
                            <a class="kt-menu-link flex items-center grow cursor-pointer border border-transparent gap-[10px] ps-[10px] pe-[10px] py-[6px]" href="{{ Route::has('testimonials.index') ? route('testimonials.index') : '#' }}" tabindex="0">
                                <span class="kt-menu-icon items-start text-muted-foreground w-[20px]">
                                    <i class="ki-filled ki-element-10 text-lg">
                                    </i>
                                </span>
                                <span class="kt-menu-title text-sm font-medium text-foreground kt-menu-item-active:text-primary kt-menu-link-hover:!text-primary">
                                    Testimonials
                                </span>
                            </a>
                        </div>
                        <div class="kt-menu-item {{ request()->routeIs('knowledge-board.*') ? 'active' : '' }}">
                            <a class="kt-menu-link flex items-center grow cursor-pointer border border-transparent gap-[10px] ps-[10px] pe-[10px] py-[6px]" href="{{ Route::has('knowledge-board.index') ? route('knowledge-board.index') : '#' }}" tabindex="0">
                                <span class="kt-menu-icon items-start text-muted-foreground w-[20px]">
                                    <i class="ki-filled ki-element-2 text-lg">
                                    </i>
                                </span>
                                <span class="kt-menu-title text-sm font-medium text-foreground kt-menu-item-active:text-primary kt-menu-link-hover:!text-primary">
                                    Knowledge Board
                                </span>
                            </a>
                        </div>
                        <div class="kt-menu-item">
                            <a class="kt-menu-link flex items-center grow cursor-pointer border border-transparent gap-[10px] ps-[10px] pe-[10px] py-[6px]" href="#" tabindex="0">
                                <span class="kt-menu-icon items-start text-muted-foreground w-[20px]">
                                    <i class="ki-filled ki-element-1 text-lg">
                                    </i>
                                </span>
                                <span class="kt-menu-title text-sm font-medium text-foreground kt-menu-item-active:text-primary kt-menu-link-hover:!text-primary">
                                    Research Repo
                                </span>
                            </a>
                        </div>
                        <div class="kt-menu-item {{ request()->routeIs('subscribers.*') ? 'active' : '' }}">
                            <a class="kt-menu-link flex items-center grow cursor-pointer border border-transparent gap-[10px] ps-[10px] pe-[10px] py-[6px]" href="{{ Route::has('subscribers.index') ? route('subscribers.index') : '#' }}" tabindex="0">
                                <span class="kt-menu-icon items-start text-muted-foreground w-[20px]">
                                    <i class="ki-filled ki-users text-lg">
                                    </i>
                                </span>
                                <span class="kt-menu-title text-sm font-medium text-foreground kt-menu-item-active:text-primary kt-menu-link-hover:!text-primary">
                                    Subscribe List
                                </span>
                            </a>
                        </div>
                        <div class="kt-menu-item {{ request()->routeIs('roadmap.index') ? 'active' : '' }}">
                            <a class="kt-menu-link flex items-center grow cursor-pointer border border-transparent gap-[10px] ps-[10px] pe-[10px] py-[6px]" href="{{ Route::has('roadmap.index') ? route('roadmap.index') : '#' }}" tabindex="0">
                                <span class="kt-menu-icon items-start text-muted-foreground w-[20px]">
                                    <i class="ki-filled ki-python text-lg">
                                    </i>
                                </span>
                                <span class="kt-menu-title text-sm font-medium text-foreground kt-menu-item-active:text-primary kt-menu-link-hover:!text-primary">
                                    Roadmap Statuses
                                </span>
                            </a>
                        </div>
                        <div class="kt-menu-item pt-2.25 pb-px">
                            <span class="kt-menu-heading uppercase text-xs font-medium text-muted-foreground ps-[10px] pe-[10px]">
                                Customer Insights
                            </span>
                        </div>
                        <div class="kt-menu-item {{ request()->routeIs('user-segment.*') ? 'active' : '' }}">
                            <a class="kt-menu-link flex items-center grow cursor-pointer border border-transparent gap-[10px] ps-[10px] pe-[10px] py-[6px]" href="{{ Route::has('user-segment.index') ? route('user-segment.index') : '#' }}" tabindex="0">
                                <span class="kt-menu-icon items-start text-muted-foreground w-[20px]">
                                    <i class="ki-filled ki-cheque text-lg">
                                    </i>
                                </span>
                                <span class="kt-menu-title text-sm font-medium text-foreground kt-menu-item-active:text-primary kt-menu-link-hover:!text-primary">
                                    Segmentations
                                </span>
                            </a>
                        </div>
                        <div class="kt-menu-item {{ request()->routeIs('personas.*') ? 'active' : '' }}">
                            <a class="kt-menu-link flex items-center grow cursor-pointer border border-transparent gap-[10px] ps-[10px] pe-[10px] py-[6px]" href="{{ Route::has('personas.index') ? route('personas.index') : '#' }}" tabindex="0">
                                <span class="kt-menu-icon items-start text-muted-foreground w-[20px]">
                                    <i class="ki-filled ki-artificial-intelligence text-lg">
                                    </i>
                                </span>
                                <span class="kt-menu-title text-sm font-medium text-foreground kt-menu-item-active:text-primary kt-menu-link-hover:!text-primary">
                                    Personas
                                </span>
                            </a>
                        </div>
                        <div class="kt-menu-item">
                            <a class="kt-menu-link flex items-center grow cursor-pointer border border-transparent gap-[10px] ps-[10px] pe-[10px] py-[6px]" href="#" tabindex="0">
                                <span class="kt-menu-icon items-start text-muted-foreground w-[20px]">
                                    <i class="ki-filled ki-element-10 text-lg">
                                    </i>
                                </span>
                                <span class="kt-menu-title text-sm font-medium text-foreground kt-menu-item-active:text-primary kt-menu-link-hover:!text-primary">
                                    Journey Mapping
                                </span>
                            </a>
                        </div>


                        <div class="kt-menu-item pt-2.25 pb-px">
                            <span class="kt-menu-heading uppercase text-xs font-medium text-muted-foreground ps-[10px] pe-[10px]">
                                Settings
                            </span>
                        </div>
                        @if(Auth::user()->canAccessAppSettings())
                        <div class="kt-menu-item {{ request()->routeIs('settings.*') ? 'active' : '' }}">
                            <a class="kt-menu-link flex items-center grow cursor-pointer border border-transparent gap-[10px] ps-[10px] pe-[10px] py-[6px]" href="{{ Route::has('settings.index') ? route('settings.index') : '#' }}" tabindex="0">
                                <span class="kt-menu-icon items-start text-muted-foreground w-[20px]">
                                    <i class="ki-filled ki-setting-2 text-lg">
                                    </i>
                                </span>
                                <span class="kt-menu-title text-sm font-medium text-foreground kt-menu-item-active:text-primary kt-menu-link-hover:!text-primary">
                                    Personas
                                </span>
                            </a>
                        </div>
                        @endif
                        @php
                        $appSettings = \App\Models\AppSettings::where('team_id', Auth::user()->current_team_id)->first();
                        $publicUrl = $appSettings && $appSettings->subdomain_url ? request()->getScheme() . '://' . $appSettings->subdomain_url . '.' . request()->getHttpHost() : '#';
                        @endphp
                        <div class="kt-menu-item">
                            <a onclick="window.open(this.href, '_blank', 'width=1200,height=800,scrollbars=yes,resizable=yes,toolbar=yes,location=yes'); return false;" class="kt-menu-link flex items-center grow cursor-pointer border border-transparent gap-[10px] ps-[10px] pe-[10px] py-[6px]" href="{{ $publicUrl }}" tabindex="0">
                                <span class="kt-menu-icon items-start text-muted-foreground w-[20px]">
                                    <i class="ki-filled ki-heart text-xl">
                                    </i>
                                </span>
                                <span class="kt-menu-title text-sm font-medium text-foreground kt-menu-item-active:text-primary kt-menu-link-hover:!text-primary">
                                     Visit My Website
                                </span>
                            </a>
                        </div>
                        <div class="kt-menu-item">
                            <a class="kt-menu-link flex items-center grow cursor-pointer border border-transparent gap-[10px] ps-[10px] pe-[10px] py-[6px]" href="#" tabindex="0">
                                <span class="kt-menu-icon items-start text-muted-foreground w-[20px]">
                                    <i class="ki-filled ki-setting text-lg">
                                    </i>
                                </span>
                                <span class="kt-menu-title text-sm font-medium text-foreground kt-menu-item-active:text-primary kt-menu-link-hover:!text-primary">
                                    Your Widget
                                </span>
                            </a>
                        </div>
                        <div class="kt-menu-item">
                            <form method="POST" action="{{ route('logout') }}" id="sidebar-logout-form">
                                    @csrf
                                </form>
                            <a onclick="event.preventDefault(); document.getElementById('sidebar-logout-form').submit();"  class="kt-menu-link flex items-center grow cursor-pointer border border-transparent gap-[10px] ps-[10px] pe-[10px] py-[6px]" href="#" tabindex="0">
                                <span class="kt-menu-icon items-start text-muted-foreground w-[20px]">
                                    <i class="ki-filled ki-element-6 text-lg">
                                    </i>
                                </span>
                                <span class="kt-menu-title text-sm font-medium text-foreground kt-menu-item-active:text-primary kt-menu-link-hover:!text-primary">
                                    Sign Out
                                </span>
                            </a>
                        </div>
                    </div>
                </div>
                <!-- End of Sidebar Menu -->
            </div>
        </div>
        <!-- End of Sidebar -->
        <!-- Wrapper -->
        <div class="kt-wrapper flex grow flex-col">
            <!-- Header -->
            <header class="kt-header fixed top-0 z-10 start-0 end-0 flex items-stretch shrink-0 bg-background" data-kt-sticky="true" data-kt-sticky-class="border-b border-border" data-kt-sticky-name="header" id="header">
                <!-- Container -->
                <div class="kt-container-fixed flex justify-between items-stretch lg:gap-4" id="headerContainer">
                    <!-- Mobile Logo -->
                    <div class="flex gap-2.5 lg:hidden items-center -ms-1">
                        <a class="shrink-0" href="html/demo1.html">
                            <!-- <img class="max-h-[25px] w-full" src="assets/media/app/mini-logo.svg" /> -->
                        </a>
                        <div class="flex items-center">
                            <button class="kt-btn kt-btn-icon kt-btn-ghost" data-kt-drawer-toggle="#sidebar">
                                <i class="ki-filled ki-menu">
                                </i>
                            </button>
                        </div>
                    </div>
                    <!-- End of Mobile Logo -->
                    <!-- Breadcrumbs -->
                    <div class="flex [.kt-header_&]:below-lg:hidden items-center gap-1.25 text-xs lg:text-sm font-medium mb-2.5 lg:mb-0 [--kt-reparent-target:#contentContainer] lg:[--kt-reparent-target:#headerContainer] [--kt-reparent-mode:prepend] lg:[--kt-reparent-mode:prepend]" data-kt-reparent="true">
                        <span class="text-secondary-foreground">
                            Changelog
                        </span>
                        <i class="ki-filled ki-right text-muted-foreground text-[10px]">
                        </i>
                        <span class="text-mono font-medium">
                            Changelog - Create
                        </span>
                    </div>
                    <!-- End of Breadcrumbs -->
                    <!-- Topbar -->
                    <div class="flex items-center gap-2.5">
                        <!-- User -->
                        <div class="shrink-0" data-kt-dropdown="true" data-kt-dropdown-offset="10px, 10px" data-kt-dropdown-offset-rtl="-20px, 10px" data-kt-dropdown-placement="bottom-end" data-kt-dropdown-placement-rtl="bottom-start" data-kt-dropdown-trigger="click">
                            <button class="kt-btn kt-btn-icon kt-btn-ghost size-9 relative rounded-full hover:bg-primary/10 [&.active]:bg-primary/10 hover:[&_i]:text-primary [&.active]:[&_i]:text-primary" data-kt-dropdown-toggle="true">
                                <i class="ki-filled ki-profile-circle text-xl">
                                </i>
                            </button>
                            <div class="kt-dropdown-menu w-[250px]" data-kt-dropdown-menu="true">
                                <div class="flex items-center justify-between px-2.5 py-1.5 gap-1.5">
                                    <div class="flex items-center gap-2">
                                        <!-- <img alt="" class="size-9 shrink-0 rounded-full border-2 border-green-500" src="assets/media/avatars/300-2.png" /> -->
                                        <div class="flex flex-col gap-1.5">
                                            <span class="text-sm text-foreground font-semibold leading-none">
                                                Cody Fisher
                                            </span>
                                            <a class="text-xs text-secondary-foreground hover:text-primary font-medium leading-none" href="html/demo1/account/home/get-started.html">
                                                c.fisher@gmail.com
                                            </a>
                                        </div>
                                    </div>
                                    <span class="kt-badge kt-badge-sm kt-badge-primary kt-badge-outline">
                                        Pro
                                    </span>
                                </div>
                                <ul class="kt-dropdown-menu-sub">
                                    <li>
                                        <div class="kt-dropdown-menu-separator">
                                        </div>
                                    </li>
                                    <li>
                                        <a class="kt-dropdown-menu-link" href="html/demo1/public-profile/profiles/default.html">
                                            <i class="ki-filled ki-badge">
                                            </i>
                                            Public Profile
                                        </a>
                                    </li>
                                    <li>
                                        <a class="kt-dropdown-menu-link" href="html/demo1/account/home/user-profile.html">
                                            <i class="ki-filled ki-profile-circle">
                                            </i>
                                            My Profile
                                        </a>
                                    </li>
                                    <li data-kt-dropdown="true" data-kt-dropdown-placement="right-start" data-kt-dropdown-trigger="hover">
                                        <button class="kt-dropdown-menu-toggle" data-kt-dropdown-toggle="true">
                                            <i class="ki-filled ki-setting-2">
                                            </i>
                                            My Account
                                            <span class="kt-dropdown-menu-indicator">
                                                <i class="ki-filled ki-right text-xs">
                                                </i>
                                            </span>
                                        </button>
                                        <div class="kt-dropdown-menu w-[220px]" data-kt-dropdown-menu="true">
                                            <ul class="kt-dropdown-menu-sub">
                                                <li>
                                                    <a class="kt-dropdown-menu-link" href="html/demo1/account/home/get-started.html">
                                                        <i class="ki-filled ki-coffee">
                                                        </i>
                                                        Get Started
                                                    </a>
                                                </li>
                                                <li>
                                                    <a class="kt-dropdown-menu-link" href="html/demo1/account/home/user-profile.html">
                                                        <i class="ki-filled ki-some-files">
                                                        </i>
                                                        My Profile
                                                    </a>
                                                </li>
                                                <li>
                                                    <a class="kt-dropdown-menu-link" href="#">
                                                        <span class="flex items-center gap-2">
                                                            <i class="ki-filled ki-icon">
                                                            </i>
                                                            Billing
                                                        </span>
                                                        <span class="ms-auto inline-flex items-center" data-kt-tooltip="true" data-kt-tooltip-placement="top">
                                                            <i class="ki-filled ki-information-2 text-base text-muted-foreground">
                                                            </i>
                                                            <span class="kt-tooltip" data-kt-tooltip-content="true">
                                                                Payment and subscription info
                                                            </span>
                                                        </span>
                                                    </a>
                                                </li>
                                                <li>
                                                    <a class="kt-dropdown-menu-link" href="html/demo1/account/security/overview.html">
                                                        <i class="ki-filled ki-medal-star">
                                                        </i>
                                                        Security
                                                    </a>
                                                </li>
                                                <li>
                                                    <a class="kt-dropdown-menu-link" href="html/demo1/account/members/teams.html">
                                                        <i class="ki-filled ki-setting">
                                                        </i>
                                                        Members & Roles
                                                    </a>
                                                </li>
                                                <li>
                                                    <a class="kt-dropdown-menu-link" href="html/demo1/account/integrations.html">
                                                        <i class="ki-filled ki-switch">
                                                        </i>
                                                        Integrations
                                                    </a>
                                                </li>
                                                <li>
                                                    <div class="kt-dropdown-menu-separator">
                                                    </div>
                                                </li>
                                                <li>
                                                    <a class="kt-dropdown-menu-link" href="html/demo1/account/security/overview.html">
                                                        <span class="flex items-center gap-2">
                                                            <i class="ki-filled ki-shield-tick">
                                                            </i>
                                                            Notifications
                                                        </span>
                                                        <input checked="" class="ms-auto kt-switch" name="check" type="checkbox" value="1" />
                                                    </a>
                                                </li>
                                            </ul>
                                        </div>
                                    </li>
                                    <li>
                                        <a class="kt-dropdown-menu-link" href="https://devs.keenthemes.com">
                                            <i class="ki-filled ki-message-programming">
                                            </i>
                                            Dev Forum
                                        </a>
                                    </li>
                                    <li data-kt-dropdown="true" data-kt-dropdown-placement="right-start" data-kt-dropdown-trigger="hover">
                                        <button class="kt-dropdown-menu-toggle py-1" data-kt-dropdown-toggle="true">
                                            <span class="flex items-center gap-2">
                                                <i class="ki-filled ki-icon">
                                                </i>
                                                Language
                                            </span>
                                            <span class="ms-auto kt-badge kt-badge-stroke shrink-0">
                                                English
                                                <!-- <img alt="" class="inline-block size-3.5 rounded-full" src="assets/media/flags/united-states.svg" /> -->
                                            </span>
                                        </button>
                                    </li>
                                    <li>
                                        <div class="kt-dropdown-menu-separator">
                                        </div>
                                    </li>
                                </ul>
                                <div class="px-2.5 pt-1.5 mb-2.5 flex flex-col gap-3.5">
                                    <div class="flex items-center gap-2 justify-between">
                                        <span class="flex items-center gap-2">
                                            <i class="ki-filled ki-moon text-base text-muted-foreground">
                                            </i>
                                            <span class="font-medium text-2sm">
                                                Dark Mode
                                            </span>
                                        </span>
                                        <input class="kt-switch" data-kt-theme-switch-state="dark" data-kt-theme-switch-toggle="true" name="check" type="checkbox" value="1" />
                                    </div>
                                    <a class="kt-btn kt-btn-outline justify-center w-full" href="html/demo1/authentication/classic/sign-in.html">
                                        Log out
                                    </a>
                                </div>
                            </div>
                        </div>
                        <!-- End of User -->
                        <!--Wishlist-->
                        <button class="kt-btn kt-btn-icon kt-btn-ghost size-9 rounded-full hover:bg-primary/10 [&.active]:bg-primary/10 hover:[&_i]:text-primary [&.active]:[&_i]:text-primary" data-kt-drawer-toggle="#drawers_shop_wishlist">
                            <i class="ki-filled ki-heart text-xl">
                            </i>
                        </button>
                        <!--Product Wishlist-->
                        <div class="hidden kt-drawer kt-drawer-end card flex-col max-w-[90%] w-[600px] top-5 bottom-5 end-5 rounded-xl border border-border" data-kt-drawer="true" data-kt-drawer-container="body" id="drawers_shop_wishlist">
                            <div class="kt-card-header px-5">
                                <h3 class="kt-card-title">
                                    Wishlist
                                </h3>
                                <button class="kt-btn kt-btn-sm kt-btn-icon kt-btn-ghost shrink-0" data-kt-drawer-dismiss="true">
                                    <i class="ki-filled ki-cross text-base">
                                    </i>
                                </button>
                            </div>
                            <div class="kt-card-footer px-5">
                                <button class="kt-btn kt-btn-outline grow">
                                    Remove all
                                </button>
                            </div>
                        </div>
                        <!--End of Wishlist Drawer-->
                        <!--End of Wishlist-->
                        <!--Cart-->
                        <!--End of Cart-->
                        <!--Product Details Drawer-->
                        <div class="hidden kt-drawer kt-drawer-end kt-card flex-col max-w-[90%] w-[520px] top-5 bottom-5 end-5 rounded-xl border border-border" data-kt-drawer="true" data-kt-drawer-container="body" id="drawers_shop_product_details">
                            <div class="kt-card-header px-5">
                                <h3 class="kt-card-title">
                                    Product Details
                                </h3>
                                <button class="kt-btn kt-btn-sm kt-btn-icon kt-btn-ghost shrink-0" data-kt-drawer-dismiss="true">
                                    <i class="ki-filled ki-cross text-base">
                                    </i>
                                </button>
                            </div>
                            <div class="kt-card-footer px-5">
                                <button class="kt-btn kt-btn-primary grow">
                                    <i class="ki-filled ki-handcart">
                                    </i>
                                    Add to Cart
                                </button>
                            </div>
                        </div>
                        <!--End of Product Details Drawer-->
                    </div>
                    <!-- End of Topbar -->
                </div>
                <!-- End of Container -->
            </header>
            <!-- End of Header -->
            <!-- Content -->
            <main class="grow pt-5" id="content" role="content">
                @yield('content')
                <!-- End of Container -->
            </main>
            <!-- End of Content -->
            <!-- Footer -->
            <footer class="kt-footer">
                <!-- Container -->
                <div class="kt-container-fixed">
                    <div class="flex flex-col md:flex-row justify-center md:justify-between items-center gap-3 py-5">
                        <div class="flex order-2 md:order-1 gap-2 font-normal text-sm">
                            <span class="text-secondary-foreground">
                                2025©
                            </span>
                        </div>
                        
                    </div>
                </div>
                <!-- End of Container -->
            </footer>
            <!-- End of Footer -->
        </div>
        <!-- End of Wrapper -->
    </div>
    <!-- End of Main -->
    <!-- End of Page -->
    <!-- Scripts -->
    <script src="{{asset('assets/new-theme/js/core.bundle.js')}}">
    </script>
    <script src="{{asset('assets/new-theme/vendors/ktui/ktui.min.js')}}">
    </script>
    <script src="{{asset('assets/new-theme/vendors/apexcharts/apexcharts.min.js')}}">
    </script>
    <script src="{{asset('assets/new-theme/js/layouts/demo1.js')}}">
    </script>
     @stack('scripts')
    <!-- End of Scripts -->
</body>

</html>