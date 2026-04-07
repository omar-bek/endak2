<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Endak - ' . __('messages.welcome_title'))</title>

    {{-- SEO Meta Tags, Open Graph, Twitter Cards, JSON-LD --}}
    @include('partials._seo')

    <!-- Bootstrap CSS -->
    @if (app()->getLocale() === 'ar')
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
    @else
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    @endif
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/endak.css') }}?v={{ filemtime(public_path('css/endak.css')) }}">

    @stack('styles')
</head>

<body>
    {{-- ==================== SMART APP BANNER (Mobile) ==================== --}}
    @include('partials._app-banner')

    {{-- ==================== NAVBAR (Desktop) ==================== --}}
    <nav class="navbar navbar-expand-lg fixed-top custom-navbar d-none d-lg-block">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center" href="{{ route('home') }}">
                <img src="{{ asset(\App\Models\SystemSetting::get('site_logo', 'home.png')) }}"
                    alt="{{ \App\Models\SystemSetting::get('site_name_ar', 'إنداك') }}"
                    style="height: 40px; width: auto;">
            </a>

            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}" href="{{ route('home') }}">{{ __('messages.home') }}</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('categories.index') ? 'active' : '' }}" href="{{ route('categories.index') }}">{{ __('messages.categories') }}</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('services.index') ? 'active' : '' }}" href="{{ route('services.index') }}">{{ __('messages.services') }}</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('contact') ? 'active' : '' }}" href="{{ route('contact') }}">{{ __('messages.contact_us') }}</a>
                    </li>
                </ul>

                <ul class="navbar-nav">
                    {{-- Language Switcher --}}
                    <li class="nav-item dropdown me-2">
                        <a class="nav-link dropdown-toggle language-btn" href="#" role="button">
                            <i class="fas fa-globe me-1"></i>{{ app()->getLocale() === 'ar' ? 'العربية' : 'English' }}
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="{{ route('language.switch', 'ar') }}"><i class="fas fa-flag me-2"></i>العربية</a></li>
                            <li><a class="dropdown-item" href="{{ route('language.switch', 'en') }}"><i class="fas fa-flag me-2"></i>English</a></li>
                        </ul>
                    </li>

                    @auth
                        {{-- Messages --}}
                        <li class="nav-item me-3 position-relative">
                            <a class="nav-link" href="{{ route('messages.index') }}">
                                <i class="fas fa-comments"></i>
                                <span id="navbar-messages-badge" class="notification-count-badge"
                                    style="{{ Auth::user()->unread_messages_count > 0 ? '' : 'display:none;' }}">
                                    {{ Auth::user()->unread_messages_count > 99 ? '99+' : Auth::user()->unread_messages_count }}
                                </span>
                            </a>
                        </li>

                        {{-- Notifications --}}
                        <li class="nav-item me-3 position-relative">
                            <a class="nav-link nav-notification-bell" href="{{ route('notifications.index') }}">
                                <i class="fas fa-bell{{ Auth::user()->unread_notifications_count > 0 ? ' bell-shake' : '' }}"></i>
                                <span id="navbar-notifications-badge" class="notification-count-badge"
                                    style="{{ Auth::user()->unread_notifications_count > 0 ? '' : 'display:none;' }}">
                                    {{ Auth::user()->unread_notifications_count > 99 ? '99+' : Auth::user()->unread_notifications_count }}
                                </span>
                            </a>
                        </li>

                        {{-- User Dropdown --}}
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" role="button">
                                @include('partials.user-avatar', ['user' => Auth::user(), 'size' => 30, 'class' => 'me-2'])
                                <span>{{ Auth::user()->name }}</span>
                            </a>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="{{ route('messages.new-design') }}">
                                    <i class="fas fa-comments"></i> الرسائل الجديدة
                                    <span id="messages-badge-menu" class="notification-count-badge-inline" style="display:none;">0</span>
                                </a></li>
                                <li><a class="dropdown-item" href="{{ route('messages.index') }}"><i class="fas fa-envelope"></i> الرسائل القديمة</a></li>
                                <li><a class="dropdown-item" href="{{ route('notifications.index') }}">
                                    <i class="fas fa-bell"></i> الإشعارات
                                    @if (Auth::user()->unread_notifications_count > 0)
                                        <span id="notifications-menu-badge" class="notification-count-badge-inline">{{ Auth::user()->unread_notifications_count }}</span>
                                    @endif
                                </a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item" href="{{ route('services.my-services') }}"><i class="fas fa-list"></i> {{ __('messages.my_services') }}</a></li>
                                <li><a class="dropdown-item" href="{{ route('service-offers.received') }}">
                                    <i class="fas fa-inbox"></i> العروض المقدمة
                                    @php $pendingCount = \App\Models\ServiceOffer::whereHas('service', fn($q) => $q->where('user_id', Auth::id()))->where('status', 'pending')->count(); @endphp
                                    @if($pendingCount > 0)
                                        <span class="notification-count-badge-inline">{{ $pendingCount }}</span>
                                    @endif
                                </a></li>
                                @if (Auth::user()->isProvider())
                                    <li><a class="dropdown-item" href="{{ route('service-offers.my-offers') }}"><i class="fas fa-handshake"></i> {{ __('messages.my_offers') }}</a></li>
                                @endif
                                <li><a class="dropdown-item" href="{{ Auth::user()->isProvider() ? route('provider.profile') : route('profile') }}"><i class="fas fa-user-edit"></i> {{ __('messages.profile') }}</a></li>
                                @if (Auth::user()->isProvider() && !Auth::user()->hasCompleteProviderProfile())
                                    <li><a class="dropdown-item text-warning" href="{{ route('provider.complete-profile') }}"><i class="fas fa-exclamation-triangle"></i> إكمال الملف الشخصي</a></li>
                                @endif
                                @if (Auth::user()->isProvider() && Auth::user()->hasCompleteProviderProfile())
                                    <li><a class="dropdown-item" href="{{ route('provider.profile.edit') }}"><i class="fas fa-edit"></i> تعديل الملف الشخصي</a></li>
                                @endif
                                @if (Auth::user()->is_admin)
                                    <li><a class="dropdown-item" href="{{ route('admin.dashboard') }}"><i class="fas fa-cog"></i> {{ __('messages.admin_panel') }}</a></li>
                                @endif
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="dropdown-item"><i class="fas fa-sign-out-alt"></i> {{ __('messages.logout') }}</button>
                                    </form>
                                </li>
                            </ul>
                        </li>
                    @else
                        <li class="nav-item"><a class="nav-link" href="{{ route('login') }}">{{ __('messages.login') }}</a></li>
                        <li class="nav-item"><a class="nav-link" href="{{ route('register') }}">{{ __('messages.register') }}</a></li>
                    @endauth
                </ul>
            </div>
        </div>
    </nav>

    {{-- ==================== MAIN CONTENT ==================== --}}
    <main>
        @foreach (['success' => 'check-circle', 'error' => 'exclamation-triangle', 'warning' => 'exclamation-circle', 'info' => 'info-circle'] as $type => $icon)
            @if (session($type))
                <div class="container mt-4">
                    <div class="alert alert-{{ $type === 'error' ? 'danger' : $type }} alert-dismissible fade show" role="alert">
                        <i class="fas fa-{{ $icon }} me-2"></i>{{ session($type) }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                </div>
            @endif
        @endforeach

        @yield('content')
    </main>

    {{-- ==================== FOOTER ==================== --}}
    <footer class="custom-footer">
        <div class="container">
            <div class="row text-center text-md-start">
                <div class="col-md-4 mb-4">
                    <h5 class="footer-title d-flex align-items-center justify-content-center justify-content-md-start">
                        <img src="{{ asset(\App\Models\SystemSetting::get('site_logo', 'home.png')) }}" alt="Endak" style="height: 50px; width: auto;" class="me-2">
                        Endak
                    </h5>
                    <p class="footer-text">{{ __('messages.welcome_subtitle') }}</p>
                </div>

                <div class="col-md-4 mb-4">
                    <h5 class="footer-title">{{ __('messages.quick_links') }}</h5>
                    <ul class="list-unstyled mt-3">
                        <li><a href="{{ route('home') }}" class="footer-link">{{ __('messages.home') }}</a></li>
                        <li><a href="{{ route('categories.index') }}" class="footer-link">{{ __('messages.categories') }}</a></li>
                        <li><a href="{{ route('services.index') }}" class="footer-link">{{ __('messages.services') }}</a></li>
                        <li><a href="{{ route('contact') }}" class="footer-link">{{ __('messages.contact_us') }}</a></li>
                    </ul>
                </div>

                <div class="col-md-4 mb-4">
                    <h5 class="footer-title">{{ __('messages.contact_info') }}</h5>
                    @php
                        $socialLinks = [
                            ['key' => 'social_facebook', 'icon' => 'fab fa-facebook-f'],
                            ['key' => 'social_twitter', 'icon' => 'fab fa-x-twitter'],
                            ['key' => 'social_instagram', 'icon' => 'fab fa-instagram'],
                            ['key' => 'social_tiktok', 'icon' => 'fab fa-tiktok'],
                            ['key' => 'social_youtube', 'icon' => 'fab fa-youtube'],
                        ];
                    @endphp
                    <div class="social-links mt-3 d-flex justify-content-center justify-content-md-start">
                        @foreach($socialLinks as $social)
                            @if($url = \App\Models\SystemSetting::get($social['key']))
                                <a href="{{ $url }}" target="_blank" rel="noopener noreferrer" class="social-icon"><i class="{{ $social['icon'] }}"></i></a>
                            @endif
                        @endforeach
                    </div>

                    @php
                        $providerAppEnabled = \App\Models\SystemSetting::get('provider_app_enabled', false);
                        $clientAppEnabled = \App\Models\SystemSetting::get('client_app_enabled', false);
                        $providerGooglePlay = \App\Models\SystemSetting::get('provider_app_google_play', '');
                        $providerAppStore = \App\Models\SystemSetting::get('provider_app_appstore', '');
                        $clientGooglePlay = \App\Models\SystemSetting::get('client_app_google_play', '');
                        $clientAppStore = \App\Models\SystemSetting::get('client_app_appstore', '');
                    @endphp

                    @if ($providerAppEnabled || $clientAppEnabled)
                        <h5 class="footer-title mt-4">{{ __('messages.download_app') }}</h5>
                        <div class="app-links mt-3">
                            @if ($providerAppEnabled && ($providerGooglePlay || $providerAppStore))
                                <div class="app-section mb-3">
                                    <span class="app-section-label">{{ __('messages.provider_app') }}</span>
                                    <div class="store-buttons">
                                        @if ($providerGooglePlay)
                                            <a href="{{ $providerGooglePlay }}" target="_blank" class="store-btn">
                                                <i class="fab fa-google-play"></i>
                                                <div class="store-btn-text"><small>GET IT ON</small><span>Google Play</span></div>
                                            </a>
                                        @endif
                                        @if ($providerAppStore)
                                            <a href="{{ $providerAppStore }}" target="_blank" class="store-btn">
                                                <i class="fab fa-apple"></i>
                                                <div class="store-btn-text"><small>Download on the</small><span>App Store</span></div>
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            @endif
                            @if ($clientAppEnabled && ($clientGooglePlay || $clientAppStore))
                                <div class="app-section">
                                    <span class="app-section-label">{{ __('messages.client_app') }}</span>
                                    <div class="store-buttons">
                                        @if ($clientGooglePlay)
                                            <a href="{{ $clientGooglePlay }}" target="_blank" class="store-btn">
                                                <i class="fab fa-google-play"></i>
                                                <div class="store-btn-text"><small>GET IT ON</small><span>Google Play</span></div>
                                            </a>
                                        @endif
                                        @if ($clientAppStore)
                                            <a href="{{ $clientAppStore }}" target="_blank" class="store-btn">
                                                <i class="fab fa-apple"></i>
                                                <div class="store-btn-text"><small>Download on the</small><span>App Store</span></div>
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            @endif
                        </div>
                    @endif
                </div>
            </div>

            <div class="row mt-3">
                <div class="col-12 d-flex justify-content-center justify-content-md-start">
                    <a href="{{ route('terms') }}" class="footer-link me-3"><i class="fas fa-file-contract text-warning"></i> {{ __('messages.terms_conditions') }}</a>
                    <a href="{{ route('about') }}" class="footer-link"><i class="fas fa-info-circle text-success"></i> {{ __('messages.about_us') }}</a>
                </div>
            </div>

            <hr class="footer-divider">
            <p class="footer-copy text-center mb-0">&copy; {{ date('Y') }} <strong>Endak</strong>. {{ __('messages.all_rights_reserved') }}</p>
        </div>
    </footer>

    {{-- ==================== MOBILE FOOTER NAV ==================== --}}
    <nav class="footer-nav">
        <div class="footer-nav-container">
            {{-- Home --}}
            <a href="{{ route('home') }}" class="footer-nav-item {{ request()->routeIs('home') ? 'active' : '' }}">
                <div class="footer-nav-icon"><i class="fas fa-home"></i></div>
                <span class="footer-nav-text">طلب خدمة</span>
                <span class="fnav-dot"></span>
            </a>

            {{-- Services --}}
            <a href="{{ auth()->check() ? route('services.index') : route('login') }}"
                class="footer-nav-item {{ request()->routeIs('services.*') ? 'active' : '' }}">
                <div class="footer-nav-icon">
                    <i class="fas {{ auth()->check() && auth()->user()->isProvider() ? 'fa-concierge-bell' : 'fa-th-large' }}"></i>
                </div>
                <span class="footer-nav-text">{{ auth()->check() && auth()->user()->isProvider() ? __('messages.services') : __('messages.Ads') }}</span>
                <span class="fnav-dot"></span>
            </a>

            {{-- Center Action --}}
            @auth
                @if (Auth::user()->isProvider())
                    <a href="{{ route('service-offers.my-offers') }}" class="footer-nav-item footer-nav-center {{ request()->routeIs('service-offers.my-offers') ? 'active' : '' }}">
                        <div class="footer-nav-icon-center"><i class="fas fa-handshake"></i></div>
                        <span class="footer-nav-text">{{ __('messages.my_offers') }}</span>
                    </a>
                @else
                    <a href="{{ route('service-offers.received') }}" class="footer-nav-item footer-nav-center {{ request()->routeIs('service-offers.received') ? 'active' : '' }}">
                        <div class="footer-nav-icon-center"><i class="fas fa-handshake"></i></div>
                        <span class="footer-nav-text">{{ __('messages.received_offers') }}</span>
                    </a>
                @endif
            @else
                <a href="{{ route('categories.index') }}" class="footer-nav-item footer-nav-center">
                    <div class="footer-nav-icon-center"><i class="fas fa-plus"></i></div>
                    <span class="footer-nav-text">{{ __('messages.ad_pup') }}</span>
                </a>
            @endauth

            {{-- Messages --}}
            <a href="{{ auth()->check() ? route('messages.index') : route('login') }}"
                class="footer-nav-item {{ request()->routeIs('messages.*') ? 'active' : '' }}">
                <div class="footer-nav-icon">
                    <i class="fas fa-comments"></i>
                    @auth
                        @if (Auth::user()->unread_messages_count > 0)
                            <span class="footer-nav-badge footer-nav-badge-messages">{{ Auth::user()->unread_messages_count > 99 ? '99+' : Auth::user()->unread_messages_count }}</span>
                        @endif
                    @endauth
                </div>
                <span class="footer-nav-text">{{ __('messages.Message') }}</span>
                <span class="fnav-dot"></span>
            </a>

            {{-- Notifications --}}
            <a href="{{ auth()->check() ? route('notifications.index') : route('login') }}"
                class="footer-nav-item {{ request()->routeIs('notifications.*') ? 'active' : '' }}">
                <div class="footer-nav-icon">
                    <i class="fas fa-bell"></i>
                    @auth
                        @if (Auth::user()->unread_notifications_count > 0)
                            <span class="footer-nav-badge footer-nav-badge-notifications">{{ Auth::user()->unread_notifications_count > 99 ? '99+' : Auth::user()->unread_notifications_count }}</span>
                        @endif
                    @endauth
                </div>
                <span class="footer-nav-text">{{ __('messages.notifications') }}</span>
                <span class="fnav-dot"></span>
            </a>

            {{-- Menu --}}
            <a href="#" class="footer-nav-item" onclick="toggleMenu()">
                <div class="footer-nav-icon"><i class="fas fa-bars"></i></div>
                <span class="footer-nav-text">{{ __('messages.menu') }}</span>
                <span class="fnav-dot"></span>
            </a>
        </div>
    </nav>

    {{-- ==================== SCRIPTS ==================== --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    @if (config('broadcasting.default') === 'pusher')
        <script src="https://js.pusher.com/8.2.0/pusher.min.js"></script>
    @endif

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Dropdown toggle
            document.querySelectorAll('.dropdown-toggle').forEach(function(toggle) {
                toggle.addEventListener('click', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    var menu = this.closest('.dropdown').querySelector('.dropdown-menu');
                    document.querySelectorAll('.dropdown-menu.show').forEach(function(m) {
                        if (m !== menu) m.classList.remove('show');
                    });
                    menu.classList.toggle('show');
                });
            });

            document.addEventListener('click', function(e) {
                if (!e.target.closest('.dropdown')) {
                    document.querySelectorAll('.dropdown-menu.show').forEach(function(m) { m.classList.remove('show'); });
                }
            });

            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') {
                    document.querySelectorAll('.dropdown-menu.show').forEach(function(m) { m.classList.remove('show'); });
                }
            });

            // Auto-hide alerts
            document.querySelectorAll('.alert').forEach(function(el) {
                setTimeout(function() {
                    if (el && el.parentNode) {
                        el.style.transition = 'opacity 0.4s, transform 0.4s';
                        el.style.opacity = '0';
                        el.style.transform = 'translateY(-20px)';
                        setTimeout(function() { if (el.parentNode) el.parentNode.removeChild(el); }, 400);
                    }
                }, 5000);
            });

            // Scroll reveal
            var observer = new IntersectionObserver(function(entries) {
                entries.forEach(function(entry) {
                    if (entry.isIntersecting) entry.target.classList.add('fade-in-up');
                });
            }, { threshold: 0.1 });

            document.querySelectorAll('.card, .category-card').forEach(function(el) { observer.observe(el); });

            // Tooltips
            [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]')).forEach(function(el) {
                new bootstrap.Tooltip(el);
            });

            // Form loading states
            document.querySelectorAll('form').forEach(function(form) {
                form.addEventListener('submit', function() {
                    var btn = this.querySelector('button[type="submit"]');
                    if (btn && !btn.disabled) {
                        btn.dataset.originalText = btn.innerHTML;
                        btn.innerHTML = '<span class="loading"></span>';
                        btn.disabled = true;
                    }
                });
            });
        });

        {{-- ==================== BADGE UPDATE SCRIPTS ==================== --}}
        @auth
        function updateMessagesCount() {
            fetch('{{ route("messages.unread-count") }}', { headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' } })
                .then(function(r) { return r.json(); })
                .then(function(data) {
                    if (!data.success) return;
                    var count = data.count > 99 ? '99+' : data.count;
                    ['navbar-messages-badge', 'messages-badge-menu'].forEach(function(id) {
                        var el = document.getElementById(id);
                        if (el) { el.textContent = count; el.style.display = data.count > 0 ? 'flex' : 'none'; }
                    });
                    var fb = document.querySelector('.footer-nav-badge-messages');
                    if (fb) { fb.textContent = count; fb.style.display = data.count > 0 ? 'flex' : 'none'; }
                }).catch(function() {});
        }

        function updateNotificationBadge(count) {
            var display = count > 99 ? '99+' : count;
            var show = count > 0;

            // Bell shake
            var bell = document.querySelector('.nav-notification-bell .fa-bell');
            if (bell) { show ? bell.classList.add('bell-shake') : bell.classList.remove('bell-shake'); }

            // Navbar badge
            var nb = document.getElementById('navbar-notifications-badge');
            if (nb) { nb.textContent = display; nb.style.display = show ? 'flex' : 'none'; }

            // Footer badge
            var fb = document.querySelector('.footer-nav-badge-notifications');
            if (fb) { fb.textContent = display; fb.style.display = show ? 'flex' : 'none'; }

            // Menu badge
            var mb = document.getElementById('notifications-menu-badge');
            if (mb) { mb.textContent = display; mb.style.display = show ? 'inline-flex' : 'none'; }

            // Page title
            document.title = show
                ? '(' + display + ') ' + document.title.replace(/^\(\d+\)\s*/, '')
                : document.title.replace(/^\(\d+\)\s*/, '');
        }

        // Polling (60s) - pauses when tab is hidden
        function fetchNotificationsCount() {
            fetch('/notifications/unread', { headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' } })
                .then(function(r) { return r.json(); })
                .then(function(d) { if (d.count !== undefined) updateNotificationBadge(d.count); })
                .catch(function() {});
        }

        function fetchAllBadges() {
            updateMessagesCount();
            fetchNotificationsCount();
        }

        var badgeTimer = setInterval(fetchAllBadges, 60000);
        document.addEventListener('visibilitychange', function() {
            if (document.hidden) { clearInterval(badgeTimer); badgeTimer = null; }
            else if (!badgeTimer) { fetchAllBadges(); badgeTimer = setInterval(fetchAllBadges, 60000); }
        });

        // Initial fetch
        fetchAllBadges();

        {{-- Pusher Realtime --}}
        @if (config('broadcasting.default') === 'pusher')
        (function() {
            var userId = {{ Auth::id() }};
            var apiToken = localStorage.getItem('api_token');
            var csrfMeta = document.querySelector('meta[name="csrf-token"]');
            var headers = { 'Accept': 'application/json', 'Content-Type': 'application/json' };

            if (apiToken) headers['Authorization'] = 'Bearer ' + apiToken;
            else if (csrfMeta) headers['X-CSRF-TOKEN'] = csrfMeta.getAttribute('content');
            else return;

            if (typeof Pusher === 'undefined') return;

            try {
                var pusher = new Pusher('{{ config("broadcasting.connections.pusher.key") }}', {
                    cluster: '{{ config("broadcasting.connections.pusher.options.cluster") }}',
                    forceTLS: true,
                    authEndpoint: apiToken ? '/api/broadcasting/auth' : '/broadcasting/auth',
                    auth: { headers: headers }
                });

                pusher.subscribe('private-user.' + userId).bind('notification.sent', function(data) {
                    if (data.unread_count !== undefined) updateNotificationBadge(data.unread_count);
                    if ('Notification' in window && Notification.permission === 'granted') {
                        new Notification(data.title, { body: data.message, icon: '/images/logo.png', tag: 'notif-' + data.id });
                    }
                });
            } catch(e) {}
        })();
        @endif
        @endauth

        {{-- ==================== MOBILE MENU ==================== --}}
        function toggleMenu() {
            @auth
            @if (Auth::user()->isProvider())
            var menuItems = [
                { icon: 'fas fa-user', text: '{{ __("messages.menu_profile") }}', href: '{{ route("provider.profile") }}' },
                { icon: 'fas fa-concierge-bell', text: '{{ __("messages.menu_my_services") }}', href: '{{ route("services.my-services") }}' },
                { icon: 'fas fa-handshake', text: '{{ __("messages.menu_my_offers") }}', href: '{{ route("service-offers.my-offers") }}' },
                { icon: 'fas fa-question-circle', text: '{{ __("messages.menu_help") }}', href: '{{ route("contact") }}' }
            ];
            @else
            var menuItems = [
                { icon: 'fas fa-user', text: '{{ __("messages.menu_profile") }}', href: '{{ route("profile") }}' },
                { icon: 'fas fa-th-large', text: '{{ __("messages.menu_my_ads") }}', href: '{{ route("services.index") }}' },
                { icon: 'fas fa-question-circle', text: '{{ __("messages.menu_help") }}', href: '{{ route("contact") }}' }
            ];
            @endif
            @else
            var menuItems = [
                { icon: 'fas fa-user-plus', text: '{{ __("messages.menu_create_account") }}', href: '{{ route("register") }}' },
                { icon: 'fas fa-question-circle', text: '{{ __("messages.menu_help") }}', href: '{{ route("contact") }}' }
            ];
            @endauth

            var langItems = [
                { icon: 'fas fa-flag', text: 'العربية', href: '{{ route("language.switch", "ar") }}', active: '{{ app()->getLocale() }}' === 'ar' },
                { icon: 'fas fa-flag', text: 'English', href: '{{ route("language.switch", "en") }}', active: '{{ app()->getLocale() }}' === 'en' }
            ];

            var modal = document.createElement('div');
            modal.className = 'footer-menu-modal';
            modal.innerHTML =
                '<div class="footer-menu-overlay" onclick="closeMenu()"></div>' +
                '<div class="footer-menu-content">' +
                    '<div class="footer-menu-header"><h6>القائمة</h6><button onclick="closeMenu()" class="footer-menu-close"><i class="fas fa-times"></i></button></div>' +
                    '<div class="footer-menu-items">' +
                        '<div class="footer-menu-section"><div class="footer-menu-section-title"><i class="fas fa-globe"></i><span>اللغة / Language</span></div>' +
                        langItems.map(function(i) { return '<a href="' + i.href + '" class="footer-menu-item ' + (i.active ? 'active' : '') + '"><i class="' + i.icon + '"></i><span>' + i.text + '</span>' + (i.active ? '<i class="fas fa-check text-success"></i>' : '') + '</a>'; }).join('') +
                        '</div><hr class="footer-menu-divider">' +
                        menuItems.map(function(i) { return '<a href="' + i.href + '" class="footer-menu-item"><i class="' + i.icon + '"></i><span>' + i.text + '</span></a>'; }).join('') +
                        @auth
                        @if (Auth::user()->is_admin)
                        '<a href="{{ route("admin.dashboard") }}" class="footer-menu-item"><i class="fas fa-cog"></i><span>{{ __("messages.admin_panel") }}</span></a>' +
                        @endif
                        '<hr class="footer-menu-divider"><a href="#" class="footer-menu-item" onclick="event.preventDefault();document.getElementById(\'logout-form-mobile\').submit();"><i class="fas fa-sign-out-alt"></i><span>{{ __("messages.logout") }}</span></a>' +
                        @else
                        '<hr class="footer-menu-divider"><a href="{{ route("login") }}" class="footer-menu-item"><i class="fas fa-sign-in-alt"></i><span>{{ __("messages.login") }}</span></a>' +
                        @endauth
                    '</div></div>';

            document.body.appendChild(modal);
            document.body.style.overflow = 'hidden';
        }

        function closeMenu() {
            var m = document.querySelector('.footer-menu-modal');
            if (m) { m.remove(); document.body.style.overflow = ''; }
        }
    </script>

    @auth
        <form id="logout-form-mobile" action="{{ route('logout') }}" method="POST" style="display:none;">@csrf</form>
    @endauth

    @stack('scripts')

    @if (\App\Helpers\WhatsAppHelper::isEnabled())
        <a href="{{ \App\Helpers\WhatsAppHelper::getWhatsAppUrl() }}" class="whatsapp-float" target="_blank" title="تواصل معنا عبر الواتساب">
            <i class="fab fa-whatsapp"></i>
        </a>
    @endif

    @auth
        @if (session('show_user_type_modal'))
            @include('layouts.user_type_modal')
        @endif
    @endauth
</body>
</html>
