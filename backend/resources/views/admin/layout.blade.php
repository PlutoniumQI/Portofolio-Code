<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="robots" content="noindex, nofollow">
    <title>@yield('title', 'Dashboard') · Portfolio Admin</title>
    <link rel="stylesheet" href="{{ asset('admin.css') }}">
    <script src="{{ asset('vendor/sortable/sortable.min.js') }}" defer></script>
    <script src="{{ asset('admin.js') }}" defer></script>
</head>
<body>
<div class="admin-shell">
    <aside class="admin-sidebar" id="adminSidebar">
        <div class="brand">
            <strong>Portfolio</strong>
            <span>Admin</span>
        </div>

        <div class="nav-label">Overview</div>
        <nav class="admin-nav">
            <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                Dashboard
            </a>
        </nav>

        <div class="nav-label">Content</div>
        <nav class="admin-nav">
            @foreach (config('admin.resources') as $slug => $definition)
                <a href="{{ route("admin.{$slug}.index") }}" class="{{ request()->routeIs("admin.{$slug}.*") ? 'active' : '' }}">
                    {{ $definition['label'] }}
                </a>
            @endforeach
        </nav>

        <div class="nav-label">Inbox</div>
        <nav class="admin-nav">
            <a href="{{ route('admin.submissions.index') }}" class="{{ request()->routeIs('admin.submissions.*') ? 'active' : '' }}">
                <span>Testimonial submissions</span>
                @if (($navPendingTestimonials ?? 0) > 0)
                    <span class="badge">{{ $navPendingTestimonials }}</span>
                @endif
            </a>
            <a href="{{ route('admin.messages.index') }}" class="{{ request()->routeIs('admin.messages.*') ? 'active' : '' }}">
                <span>Messages</span>
                @if (($navUnreadMessages ?? 0) > 0)
                    <span class="badge">{{ $navUnreadMessages }}</span>
                @endif
            </a>
        </nav>

        <div class="nav-label">Site</div>
        <nav class="admin-nav">
            <a href="{{ route('admin.profile.edit') }}" class="{{ request()->routeIs('admin.profile.*') ? 'active' : '' }}">
                Site profile &amp; CV
            </a>
            <a href="{{ route('admin.publish.index') }}" class="{{ request()->routeIs('admin.publish.*') ? 'active' : '' }}">
                Publish changes
            </a>
        </nav>

        <div class="nav-label">Account</div>
        <nav class="admin-nav">
            <a href="{{ route('admin.account.edit') }}" class="{{ request()->routeIs('admin.account.*') ? 'active' : '' }}">
                Profile &amp; password
            </a>
        </nav>

        @if (app()->isLocal())
            <div class="local-note">
                <strong>Local environment.</strong>
                Content changes here stay on this machine. Publish from production to update the live site.
            </div>
        @endif
    </aside>

    <div class="admin-main">
        <header class="admin-topbar">
            <button type="button" class="sidebar-toggle" id="sidebarToggle" aria-label="Toggle navigation">☰</button>
            <div class="topbar-title">@yield('title', 'Dashboard')</div>
            <div class="topbar-user">
                <a class="btn sm ghost" href="{{ config('portfolio.frontend.url') }}" target="_blank" rel="noopener">View site</a>
                <span>{{ auth()->user()?->name }}</span>
                <form method="POST" action="{{ route('admin.logout') }}">
                    @csrf
                    <button type="submit" class="btn sm">Sign out</button>
                </form>
            </div>
        </header>

        <main class="admin-content">
            @include('admin.partials.flash')
            @yield('content')
        </main>
    </div>
</div>
@stack('scripts')
</body>
</html>
