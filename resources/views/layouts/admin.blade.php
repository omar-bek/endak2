<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'لوحة الإدارة') - Endak</title>

    <!-- Bootstrap RTL CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;600;700&display=swap" rel="stylesheet">

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Cairo', sans-serif;
            background-color: #f8f9fa;
            overflow-x: hidden;
        }

        /* Sidebar Styles */
        .sidebar {
            position: fixed;
            top: 0;
            right: 0;
            width: 280px;
            height: 100vh;
            background: linear-gradient(135deg, #685C84 0%, #675B83 100%);
            color: white;
            z-index: 1000;
            transition: all 0.3s ease;
            overflow-y: auto;
            overflow-x: hidden;
        }

        .sidebar.collapsed {
            width: 70px;
        }

        .sidebar-header {
            padding: 20px;
            border-bottom: 1px solid rgba(255,255,255,0.1);
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .sidebar-logo {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .sidebar-logo i {
            font-size: 1.5rem;
            color: #3498db;
        }

        .sidebar-logo h5 {
            margin: 0;
            font-weight: 600;
            transition: opacity 0.3s ease;
        }

        .sidebar.collapsed .sidebar-logo h5 {
            opacity: 0;
            width: 0;
            overflow: hidden;
        }

        .sidebar-toggle {
            background: none;
            border: none;
            color: white;
            font-size: 1.2rem;
            cursor: pointer;
            padding: 5px;
            border-radius: 4px;
            transition: background-color 0.3s ease;
        }

        .sidebar-toggle:hover {
            background-color: rgba(255,255,255,0.1);
        }

        .sidebar-menu {
            padding: 20px 0;
        }

        .menu-section {
            margin-bottom: 30px;
        }

        .menu-section-title {
            padding: 0 20px 10px;
            font-size: 0.85rem;
            color: rgba(255,255,255,0.6);
            text-transform: uppercase;
            letter-spacing: 1px;
            transition: opacity 0.3s ease;
        }

        .sidebar.collapsed .menu-section-title {
            opacity: 0;
            height: 0;
            overflow: hidden;
            padding: 0;
        }

        .menu-item {
            display: block;
            padding: 12px 20px;
            color: rgba(255,255,255,0.8);
            text-decoration: none;
            transition: all 0.3s ease;
            border: none;
            background: none;
            width: 100%;
            text-align: right;
            cursor: pointer;
        }

        .menu-item:hover {
            background-color: rgba(255,255,255,0.1);
            color: white;
            padding-right: 30px;
        }

        .menu-item.active {
            background-color: rgba(52, 152, 219, 0.2);
            color: #3498db;
            border-right: 3px solid #3498db;
        }

        .menu-item i {
            width: 20px;
            margin-left: 10px;
            font-size: 1rem;
        }

        .menu-item span {
            transition: opacity 0.3s ease;
        }

        .sidebar.collapsed .menu-item span {
            opacity: 0;
            width: 0;
            overflow: hidden;
        }

        .sidebar.collapsed .menu-item {
            text-align: center;
            padding: 12px 10px;
        }

        .sidebar.collapsed .menu-item:hover {
            padding-right: 10px;
        }

        /* Main Content */
        .main-content {
            margin-right: 280px;
            min-height: 100vh;
            transition: all 0.3s ease;
        }

        .main-content.expanded {
            margin-right: 70px;
        }

        .main-header {
            background: white;
            padding: 20px 30px;
            border-bottom: 1px solid #e9ecef;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .main-header h4 {
            margin: 0;
            color: #2c3e50;
            font-weight: 600;
        }

        .user-info {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .user-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 600;
        }

        .content-area {
            padding: 30px;
        }

        /* Mobile Styles */
        .mobile-menu-btn {
            display: none;
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 1001;
            background: #2c3e50;
            color: white;
            border: none;
            border-radius: 50%;
            width: 50px;
            height: 50px;
            font-size: 1.2rem;
            box-shadow: 0 4px 12px rgba(0,0,0,0.3);
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .mobile-menu-btn:hover {
            background: #34495e;
            transform: scale(1.05);
        }

        .sidebar-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.5);
            z-index: 999;
        }

        .sidebar-overlay.show {
            display: block;
        }

        /* Custom Scrollbar */
        .sidebar::-webkit-scrollbar {
            width: 6px;
        }

        .sidebar::-webkit-scrollbar-track {
            background: rgba(255,255,255,0.1);
        }

        .sidebar::-webkit-scrollbar-thumb {
            background: rgba(255,255,255,0.3);
            border-radius: 3px;
        }

        .sidebar::-webkit-scrollbar-thumb:hover {
            background: rgba(255,255,255,0.5);
        }

        /* Responsive */
        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(100%);
                width: 280px;
            }

            .sidebar.show {
                transform: translateX(0);
            }

            .sidebar.collapsed {
                width: 280px;
            }

            .main-content {
                margin-right: 0;
            }

            .mobile-menu-btn {
                display: block;
            }

            .content-area {
                padding: 20px 15px;
            }

            .main-header {
                padding: 15px 20px;
            }
        }

        /* Alert Styles */
        .alert {
            border: none;
            border-radius: 8px;
            margin-bottom: 20px;
        }

        .alert-success {
            background: linear-gradient(135deg, #d4edda 0%, #c3e6cb 100%);
            color: #155724;
        }

        .alert-danger {
            background: linear-gradient(135deg, #f8d7da 0%, #f5c6cb 100%);
            color: #721c24;
        }
    </style>

    @stack('styles')
</head>
<body>
    <!-- Mobile Menu Button -->
    <button class="mobile-menu-btn" onclick="toggleMobileSidebar()">
        <i class="fas fa-bars"></i>
    </button>

    <!-- Sidebar Overlay -->
    <div class="sidebar-overlay" id="sidebarOverlay" onclick="closeMobileSidebar()"></div>

    <!-- Sidebar -->
    <nav class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <div class="sidebar-logo">
                <i class="fas fa-tools"></i>
                <h5>Endak Admin</h5>
            </div>
            <button class="sidebar-toggle" onclick="toggleSidebar()">
                <i class="fas fa-bars"></i>
            </button>
        </div>

        <div class="sidebar-menu">
            <!-- لوحة التحكم -->
            <div class="menu-section">
                <div class="menu-section-title">الرئيسية</div>
                <a href="{{ route('admin.dashboard') }}" class="menu-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    <i class="fas fa-tachometer-alt"></i>
                    <span>لوحة التحكم</span>
                </a>
            </div>

            <!-- إدارة المحتوى -->
            <div class="menu-section">
                <div class="menu-section-title">إدارة المحتوى</div>
                <a href="{{ route('admin.categories.index') }}" class="menu-item {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}">
                    <i class="fas fa-th-large"></i>
                    <span>الأقسام الرئيسية</span>
                </a>
                <a href="{{ route('admin.sub_categories.index') }}" class="menu-item {{ request()->routeIs('admin.sub_categories.*') ? 'active' : '' }}">
                    <i class="fas fa-layer-group"></i>
                    <span>الأقسام الفرعية</span>
                </a>
            </div>

            <!-- إدارة المواقع -->
            <div class="menu-section">
                <div class="menu-section-title">إدارة المواقع</div>
                <a href="{{ route('admin.cities.index') }}" class="menu-item {{ request()->routeIs('admin.cities.*') ? 'active' : '' }}">
                    <i class="fas fa-city"></i>
                    <span>إدارة المدن</span>
                </a>
                <a href="{{ route('admin.category-cities.index') }}" class="menu-item {{ request()->routeIs('admin.category-cities.*') ? 'active' : '' }}">
                    <i class="fas fa-link"></i>
                    <span>ربط الأقسام بالمدن</span>
                </a>
            </div>

            <!-- إدارة الخدمات -->
            <div class="menu-section">
                <div class="menu-section-title">إدارة الخدمات</div>
                <a href="{{ route('admin.services.index') }}" class="menu-item {{ request()->routeIs('admin.services.*') ? 'active' : '' }}">
                    <i class="fas fa-concierge-bell"></i>
                    <span>جميع الخدمات</span>
                </a>
                <a href="{{ route('admin.service-offers.index') }}" class="menu-item {{ request()->routeIs('admin.service-offers.*') ? 'active' : '' }}">
                    <i class="fas fa-handshake"></i>
                    <span>عروض الخدمات</span>
                </a>
            </div>

            <!-- إدارة المستخدمين -->
            <div class="menu-section">
                <div class="menu-section-title">إدارة المستخدمين</div>
                <a href="{{ route('admin.users.index') }}" class="menu-item {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                    <i class="fas fa-user"></i>
                    <span>جميع المستخدمين</span>
                </a>
                <a href="{{ route('admin.providers.index') }}" class="menu-item {{ request()->routeIs('admin.providers.*') ? 'active' : '' }}">
                    <i class="fas fa-user-tie"></i>
                    <span>مزودي الخدمات</span>
                </a>
            </div>

            <!-- إدارة الطلبات -->
            <div class="menu-section">
                <div class="menu-section-title">إدارة الطلبات</div>
                <a href="{{ route('admin.orders.index') }}" class="menu-item {{ request()->routeIs('admin.orders.*') ? 'active' : '' }}">
                    <i class="fas fa-list"></i>
                    <span>جميع الطلبات</span>
                </a>
                <a href="{{ route('admin.messages.index') }}" class="menu-item {{ request()->routeIs('admin.messages.*') ? 'active' : '' }}">
                    <i class="fas fa-comments"></i>
                    <span>المحادثات</span>
                </a>
            </div>

            <!-- إدارة النظام -->
            <div class="menu-section">
                <div class="menu-section-title">إدارة النظام</div>
                <a href="{{ route('admin.system-settings.index') }}" class="menu-item {{ request()->routeIs('admin.system-settings.*') ? 'active' : '' }}">
                    <i class="fas fa-sliders-h"></i>
                    <span>إعدادات النظام</span>
                </a>
                <a href="{{ route('admin.backups.index') }}" class="menu-item {{ request()->routeIs('admin.backups.*') ? 'active' : '' }}">
                    <i class="fas fa-database"></i>
                    <span>النسخ الاحتياطية</span>
                </a>
                <a href="{{ route('admin.logs.index') }}" class="menu-item {{ request()->routeIs('admin.logs.*') ? 'active' : '' }}">
                    <i class="fas fa-file-alt"></i>
                    <span>سجلات النظام</span>
                </a>
            </div>

            <!-- إدارة الواتساب -->

            <!-- روابط خارجية -->
            <div class="menu-section">
                <div class="menu-section-title">روابط سريعة</div>
                <a href="{{ route('home') }}" class="menu-item">
                    <i class="fas fa-home"></i>
                    <span>العودة للموقع</span>
                </a>
                <form method="POST" action="{{ route('logout') }}" class="d-inline">
                    @csrf
                    <button type="submit" class="menu-item">
                        <i class="fas fa-sign-out-alt"></i>
                        <span>تسجيل الخروج</span>
                    </button>
                </form>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="main-content" id="mainContent">
        <!-- Header -->
        <header class="main-header">
            <div class="d-flex justify-content-between align-items-center">
                <h4>@yield('page-title', 'لوحة الإدارة')</h4>
                <div class="user-info d-flex align-items-center">
                    <span class="me-3">مرحباً، {{ Auth::user()->name }}</span>
                    @if(Auth::user()->image && file_exists(public_path('storage/' . Auth::user()->image)))
                        <img src="{{ asset('storage/' . Auth::user()->image) }}"
                             alt="{{ Auth::user()->name }}"
                             class="rounded-circle"
                             style="width: 40px; height: 40px; object-fit: cover;"
                             onerror="this.onerror=null;this.src='{{ asset('images/default-avatar.png') }}';">
                    @else
                        <div class="user-avatar">
                            {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                        </div>
                    @endif
                </div>
            </div>
        </header>

        <!-- Content -->
        <div class="content-area">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle me-2"></i>
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-circle me-2"></i>
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @yield('content')
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // Sidebar Toggle Functions
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const mainContent = document.getElementById('mainContent');

            sidebar.classList.toggle('collapsed');
            mainContent.classList.toggle('expanded');

            // Save state to localStorage
            localStorage.setItem('sidebarCollapsed', sidebar.classList.contains('collapsed'));
        }

        function toggleMobileSidebar() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebarOverlay');

            sidebar.classList.toggle('show');
            overlay.classList.toggle('show');
        }

        function closeMobileSidebar() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebarOverlay');

            sidebar.classList.remove('show');
            overlay.classList.remove('show');
        }

        // Initialize sidebar state
        document.addEventListener('DOMContentLoaded', function() {
            // Load saved sidebar state
            const isCollapsed = localStorage.getItem('sidebarCollapsed') === 'true';
            if (isCollapsed) {
                document.getElementById('sidebar').classList.add('collapsed');
                document.getElementById('mainContent').classList.add('expanded');
            }

            // Close mobile sidebar when clicking on menu items
            const menuItems = document.querySelectorAll('.menu-item');
            menuItems.forEach(item => {
                item.addEventListener('click', function() {
                    if (window.innerWidth <= 768) {
                        closeMobileSidebar();
                    }
                });
            });

            // Handle window resize
            window.addEventListener('resize', function() {
                if (window.innerWidth > 768) {
                    closeMobileSidebar();
                }
            });

            // Smooth scroll to active menu item
            const activeItem = document.querySelector('.menu-item.active');
            if (activeItem) {
                activeItem.scrollIntoView({
                    behavior: 'smooth',
                    block: 'center'
                });
            }
        });

        // Keyboard shortcuts
        document.addEventListener('keydown', function(e) {
            // Ctrl/Cmd + B to toggle sidebar
            if ((e.ctrlKey || e.metaKey) && e.key === 'b') {
                e.preventDefault();
                toggleSidebar();
            }

            // Escape to close mobile sidebar
            if (e.key === 'Escape') {
                closeMobileSidebar();
            }
        });
    </script>

    @stack('scripts')
</body>
</html>
