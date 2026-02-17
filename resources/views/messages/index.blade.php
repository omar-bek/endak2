@extends('layouts.app')

@section('title', 'الرسائل')

@section('content')
    <div class="chat-container">
        <div class="chat-layout">
            <!-- Overlay for mobile sidebar -->
            <div class="sidebar-overlay" id="sidebarOverlay" onclick="toggleSidebar()"></div>

            <!-- Sidebar for conversations -->
            <div class="conversations-sidebar" id="conversationsSidebar">
                <div class="sidebar-header">
                    <h5><i class="fas fa-comments"></i> المحادثات</h5>
                    <div class="search-container">
                        <input type="text" id="searchConversations" placeholder="البحث في المحادثات..."
                            class="search-input">
                        <i class="fas fa-search search-icon"></i>
                    </div>
                    <button class="sidebar-toggle" id="sidebarToggle" onclick="toggleSidebar()">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <div class="conversations-list" id="conversationsList">
                    @forelse($conversations as $message)
                        @php
                            $otherUser = $message->sender_id === auth()->id() ? $message->receiver : $message->sender;
                            $unreadCount = \App\Models\Message::where('conversation_id', $message->conversation_id)
                                ->where('receiver_id', auth()->id())
                                ->where('is_read', false)
                                ->where('is_deleted', false)
                                ->count();
                        @endphp
                        <div class="conversation-item"
                            onclick="window.location.href='{{ route('messages.show', $otherUser->id) }}'"
                            data-name="{{ strtolower($otherUser->name) }}">
                            <div class="conversation-avatar">
                                <img src="{{ asset('storage/' . ($otherUser->image ?? 'users/user.png')) }}"
                                    alt="{{ $otherUser->name }}"
                                    onerror="this.onerror=null;this.src='{{ asset('storage/users/user.png') }}';">
                                <div class="online-indicator {{ $otherUser->isOnline() ? 'online' : 'offline' }}"></div>
                            </div>
                            <div class="conversation-info">
                                <div class="conversation-name">{{ $otherUser->name }}</div>
                                <div class="conversation-preview">
                                    @if ($message->isText())
                                        {{ Str::limit($message->content, 30) }}
                                    @elseif($message->isImage())
                                        <i class="fas fa-image"></i> صورة
                                    @elseif($message->isVoice())
                                        <i class="fas fa-microphone"></i> رسالة صوتية
                                    @elseif($message->isFile())
                                        <i class="fas fa-file"></i> ملف
                                    @elseif($message->isLocation())
                                        <i class="fas fa-map-marker-alt"></i> موقع
                                    @elseif($message->isContact())
                                        <i class="fas fa-user"></i> معلومات اتصال
                                    @endif
                                </div>
                                <div class="conversation-time">{{ $message->formatted_time }}</div>
                            </div>
                            @if ($unreadCount > 0)
                                <div class="unread-badge">{{ $unreadCount }}</div>
                            @endif
                        </div>
                    @empty
                        <div class="no-conversations">
                            <i class="fas fa-comment-slash"></i>
                            <p>لا توجد محادثات</p>
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- Chat Area -->
            <div class="chat-area">
                <div class="chat-header">
                    <button class="mobile-sidebar-toggle" id="mobileSidebarToggle" onclick="toggleSidebar()">
                        <i class="fas fa-bars"></i>
                    </button>
                    <div class="chat-header-info">
                        <h5>اختر محادثة للبدء</h5>
                        <small>من القائمة الجانبية</small>
                    </div>
                </div>
                <div class="chat-content" id="chatContent">
                    <div class="welcome-message">
                        <div class="welcome-icon">
                            <i class="fas fa-comments"></i>
                        </div>
                        <h3>مرحباً بك في نظام الرسائل</h3>
                        <p>اختر محادثة من القائمة الجانبية لبدء المحادثة</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .chat-container {
            height: 100vh;
            display: flex;
            flex-direction: column;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }

        .chat-layout {
            display: flex;
            flex: 1;
            height: 100vh;
        }

        .conversations-sidebar {
            width: 300px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-right: 1px solid rgba(255, 255, 255, 0.1);
            display: flex;
            flex-direction: column;
            overflow-y: auto;
        }

        .sidebar-header {
            color: white;
            padding: 20px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            background: rgba(255, 255, 255, 0.1);
        }

        .sidebar-header h5 {
            margin: 0 0 15px 0;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .search-container {
            position: relative;
        }

        .search-input {
            width: 100%;
            padding: 10px 35px 10px 15px;
            border: none;
            border-radius: 20px;
            background: rgba(255, 255, 255, 0.2);
            color: white;
            font-size: 14px;
            transition: all 0.3s ease;
        }

        .search-input::placeholder {
            color: rgba(255, 255, 255, 0.7);
        }

        .search-input:focus {
            outline: none;
            background: rgba(255, 255, 255, 0.3);
            box-shadow: 0 0 0 2px rgba(255, 255, 255, 0.2);
        }

        .search-icon {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            color: rgba(255, 255, 255, 0.7);
            font-size: 14px;
        }

        .conversations-list {
            flex: 1;
            overflow-y: auto;
            padding: 10px 0;
        }

        .conversation-item {
            display: flex;
            align-items: center;
            padding: 15px 20px;
            cursor: pointer;
            transition: all 0.3s ease;
            border-radius: 10px;
            margin: 5px 10px;
            position: relative;
        }

        .conversation-item:hover {
            background: rgba(255, 255, 255, 0.15);
            transform: translateX(5px);
        }

        .conversation-item.active {
            background: rgba(255, 255, 255, 0.2);
            border-left: 4px solid #fff;
        }

        .conversation-avatar {
            position: relative;
            margin-right: 15px;
        }

        .conversation-avatar img {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            object-fit: cover;
            border: 3px solid rgba(255, 255, 255, 0.3);
        }

        .online-indicator {
            position: absolute;
            bottom: 2px;
            right: 2px;
            width: 12px;
            height: 12px;
            border-radius: 50%;
            border: 2px solid #667eea;
            background-color: #28a745;
        }

        .online-indicator.offline {
            background-color: #6c757d;
        }

        .conversation-info {
            flex: 1;
            min-width: 0;
        }

        .conversation-name {
            font-weight: 600;
            color: white;
            font-size: 14px;
            margin-bottom: 4px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .conversation-preview {
            font-size: 12px;
            color: rgba(255, 255, 255, 0.8);
            margin-bottom: 2px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .conversation-time {
            font-size: 11px;
            color: rgba(255, 255, 255, 0.6);
        }

        .unread-badge {
            background: #dc3545;
            color: white;
            border-radius: 50%;
            width: 20px;
            height: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 11px;
            font-weight: bold;
            margin-right: 10px;
        }

        .chat-area {
            flex: 1;
            display: flex;
            flex-direction: column;
            padding: 0;
            overflow: hidden;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
        }

        .chat-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 15px 20px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            display: flex;
            align-items: center;
            gap: 15px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            z-index: 10;
        }

        .chat-header-info h5 {
            color: white;
            margin: 0;
            font-weight: 600;
        }

        .chat-header-info small {
            color: rgba(255, 255, 255, 0.8);
        }

        .chat-content {
            flex: 1;
            overflow-y: auto;
            padding: 20px;
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .welcome-message {
            text-align: center;
            color: #6c757d;
        }

        .welcome-icon {
            font-size: 64px;
            color: #667eea;
            margin-bottom: 20px;
        }

        .welcome-message h3 {
            margin-bottom: 10px;
            color: #333;
        }

        .welcome-message p {
            color: #6c757d;
        }

        .mobile-sidebar-toggle {
            background: rgba(255, 255, 255, 0.2);
            border: none;
            color: white;
            width: 35px;
            height: 35px;
            border-radius: 50%;
            display: none;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            margin-right: 10px;
            transition: all 0.3s ease;
        }

        .mobile-sidebar-toggle:hover {
            background: rgba(255, 255, 255, 0.3);
        }

        .sidebar-toggle {
            position: absolute;
            top: 20px;
            right: 20px;
            background: rgba(255, 255, 255, 0.2);
            border: none;
            color: white;
            width: 30px;
            height: 30px;
            border-radius: 50%;
            display: none;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .sidebar-toggle:hover {
            background: rgba(255, 255, 255, 0.3);
        }

        .no-conversations {
            text-align: center;
            padding: 40px 20px;
            color: rgba(255, 255, 255, 0.7);
        }

        .no-conversations i {
            font-size: 48px;
            margin-bottom: 15px;
            display: block;
        }

        .no-conversations p {
            margin: 0;
            font-size: 14px;
        }

        /* Tablet Styles */
        @media (max-width: 1024px) and (min-width: 769px) {
            .conversations-sidebar {
                width: 280px;
            }

            .sidebar-header {
                padding: 15px;
            }

            .conversation-item {
                padding: 12px 15px;
            }
        }

        /* Mobile Styles */
        @media (max-width: 768px) {
            .chat-container {
                height: 100vh;
                height: 100dvh;
                /* Dynamic viewport height for mobile */
                overflow: hidden;
            }

            .chat-layout {
                position: relative;
                height: 100%;
            }

            .conversations-sidebar {
                position: fixed;
                top: 0;
                right: 0;
                width: 100%;
                max-width: 350px;
                height: 100vh;
                height: 100dvh;
                z-index: 1000;
                transform: translateX(100%);
                transition: transform 0.3s ease-in-out;
                box-shadow: -2px 0 10px rgba(0, 0, 0, 0.2);
                border-right: none;
            }

            .conversations-sidebar:not(.hidden) {
                transform: translateX(0);
            }

            .sidebar-header {
                padding: 15px;
                position: sticky;
                top: 0;
                z-index: 10;
                background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            }

            .sidebar-header h5 {
                font-size: 16px;
                margin: 0 0 12px 0;
            }

            .search-input {
                padding: 10px 35px 10px 12px;
                font-size: 14px;
            }

            .sidebar-toggle {
                display: flex;
                position: absolute;
                top: 15px;
                right: 15px;
                width: 32px;
                height: 32px;
                z-index: 11;
            }

            .mobile-sidebar-toggle {
                display: flex;
                width: 40px;
                height: 40px;
                font-size: 18px;
            }

            .conversations-list {
                display: block;
                overflow-y: auto;
                overflow-x: hidden;
                padding: 5px 0;
                -webkit-overflow-scrolling: touch;
            }

            .conversation-item {
                min-width: auto;
                padding: 12px 15px;
                margin: 3px 8px;
                border-radius: 8px;
                flex-shrink: 0;
                touch-action: manipulation;
                -webkit-tap-highlight-color: transparent;
            }

            .conversation-item:active {
                background: rgba(255, 255, 255, 0.2);
                transform: scale(0.98);
            }

            .conversation-item:hover {
                transform: none;
            }

            .conversation-avatar {
                margin-right: 12px;
            }

            .conversation-avatar img {
                width: 45px;
                height: 45px;
                border: 2px solid rgba(255, 255, 255, 0.3);
            }

            .online-indicator {
                width: 10px;
                height: 10px;
                border-width: 1.5px;
            }

            .conversation-name {
                font-size: 14px;
            }

            .conversation-preview {
                font-size: 12px;
            }

            .conversation-time {
                font-size: 10px;
            }

            .unread-badge {
                width: 18px;
                height: 18px;
                font-size: 10px;
                margin-right: 5px;
            }

            .chat-area {
                width: 100%;
            }

            .chat-header {
                padding: 12px 15px;
                position: sticky;
                top: 0;
                z-index: 100;
            }

            .chat-header-info h5 {
                font-size: 16px;
            }

            .chat-header-info small {
                font-size: 12px;
            }

            .chat-content {
                padding: 15px;
                height: calc(100vh - 60px);
                height: calc(100dvh - 60px);
            }

            .welcome-icon {
                font-size: 48px;
                margin-bottom: 15px;
            }

            .welcome-message h3 {
                font-size: 20px;
                margin-bottom: 8px;
            }

            .welcome-message p {
                font-size: 14px;
            }

            .no-conversations {
                padding: 30px 15px;
            }

            .no-conversations i {
                font-size: 40px;
                margin-bottom: 12px;
            }

            .no-conversations p {
                font-size: 13px;
            }

            /* Overlay when sidebar is open */
            .sidebar-overlay {
                display: none;
                position: fixed;
                top: 0;
                right: 0;
                width: 100%;
                height: 100%;
                background: rgba(0, 0, 0, 0.5);
                z-index: 999;
                opacity: 0;
                visibility: hidden;
                transition: opacity 0.3s ease, visibility 0.3s ease;
            }

            .sidebar-overlay.active {
                display: block;
                opacity: 1;
                visibility: visible;
            }
        }

        /* Small Mobile Devices */
        @media (max-width: 480px) {
            .conversations-sidebar {
                max-width: 100%;
            }

            .sidebar-header {
                padding: 12px;
            }

            .sidebar-header h5 {
                font-size: 15px;
                margin: 0 0 10px 0;
            }

            .search-input {
                padding: 8px 30px 8px 10px;
                font-size: 13px;
            }

            .conversation-item {
                padding: 10px 12px;
                margin: 2px 5px;
            }

            .conversation-avatar img {
                width: 40px;
                height: 40px;
            }

            .conversation-name {
                font-size: 13px;
            }

            .conversation-preview {
                font-size: 11px;
            }

            .conversation-time {
                font-size: 9px;
            }

            .chat-header {
                padding: 10px 12px;
            }

            .chat-header-info h5 {
                font-size: 15px;
            }

            .mobile-sidebar-toggle {
                width: 36px;
                height: 36px;
                font-size: 16px;
            }

            .chat-content {
                padding: 12px;
            }

            .welcome-icon {
                font-size: 40px;
            }

            .welcome-message h3 {
                font-size: 18px;
            }

            .welcome-message p {
                font-size: 13px;
            }
        }

        /* Landscape Orientation on Mobile */
        @media (max-width: 768px) and (orientation: landscape) {
            .conversations-sidebar {
                max-width: 300px;
            }

            .sidebar-header {
                padding: 10px 15px;
            }

            .sidebar-header h5 {
                margin: 0 0 8px 0;
                font-size: 14px;
            }

            .search-input {
                padding: 8px 30px 8px 10px;
                font-size: 13px;
            }

            .conversation-item {
                padding: 8px 12px;
            }

            .conversation-avatar img {
                width: 35px;
                height: 35px;
            }

            .conversation-name {
                font-size: 12px;
            }

            .conversation-preview {
                font-size: 11px;
            }

            .chat-content {
                padding: 10px;
            }
        }

        /* Touch Device Optimizations */
        @media (hover: none) and (pointer: coarse) {
            .conversation-item:hover {
                background: transparent;
            }

            .mobile-sidebar-toggle:hover,
            .sidebar-toggle:hover {
                background: rgba(255, 255, 255, 0.2);
            }
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Search functionality for conversations
            const searchInput = document.getElementById('searchConversations');
            const conversationsList = document.getElementById('conversationsList');
            const conversationItems = document.querySelectorAll('.conversation-item');

            if (searchInput) {
                searchInput.addEventListener('input', function() {
                    const searchTerm = this.value.toLowerCase().trim();

                    conversationItems.forEach(item => {
                        const name = item.getAttribute('data-name') || '';
                        if (name.includes(searchTerm)) {
                            item.style.display = 'flex';
                            item.style.animation = 'fadeInUp 0.3s ease-out';
                        } else {
                            item.style.display = 'none';
                        }
                    });

                    // Show no results message if no conversations match
                    const visibleItems = Array.from(conversationItems).filter(item =>
                        item.style.display !== 'none'
                    );

                    let noResultsMsg = document.querySelector('.no-results');
                    if (visibleItems.length === 0 && searchTerm !== '') {
                        if (!noResultsMsg) {
                            noResultsMsg = document.createElement('div');
                            noResultsMsg.className = 'no-conversations no-results';
                            noResultsMsg.innerHTML = `
                            <i class="fas fa-search"></i>
                            <p>لا توجد نتائج للبحث</p>
                        `;
                            conversationsList.appendChild(noResultsMsg);
                        }
                    } else if (noResultsMsg) {
                        noResultsMsg.remove();
                    }
                });
            }
        });

        // Prevent body scroll when sidebar is open on mobile
        function toggleBodyScroll(disable) {
            if (window.innerWidth <= 768) {
                if (disable) {
                    document.body.style.overflow = 'hidden';
                    document.body.style.position = 'fixed';
                    document.body.style.width = '100%';
                } else {
                    document.body.style.overflow = '';
                    document.body.style.position = '';
                    document.body.style.width = '';
                }
            }
        }

        // Sidebar toggle function
        function toggleSidebar() {
            const sidebar = document.getElementById('conversationsSidebar');
            const overlay = document.getElementById('sidebarOverlay');

            if (sidebar) {
                const isHidden = sidebar.classList.contains('hidden');
                sidebar.classList.toggle('hidden');

                // Toggle overlay on mobile
                if (window.innerWidth <= 768 && overlay) {
                    if (!isHidden) {
                        overlay.classList.remove('active');
                    } else {
                        overlay.classList.add('active');
                    }
                }

                toggleBodyScroll(!isHidden);
            }
        }

        // Auto-hide sidebar on mobile when clicking outside
        document.addEventListener('click', function(event) {
            const sidebar = document.getElementById('conversationsSidebar');
            const overlay = document.getElementById('sidebarOverlay');
            const mobileToggle = document.getElementById('mobileSidebarToggle');
            const sidebarToggle = document.getElementById('sidebarToggle');

            if (window.innerWidth <= 768 && sidebar && !sidebar.classList.contains('hidden')) {
                // Close if clicking outside sidebar or on overlay
                if (event.target === overlay ||
                    (!sidebar.contains(event.target) &&
                        mobileToggle && !mobileToggle.contains(event.target) &&
                        sidebarToggle && !sidebarToggle.contains(event.target))) {
                    sidebar.classList.add('hidden');
                    if (overlay) overlay.classList.remove('active');
                    toggleBodyScroll(false);
                }
            }
        });

        // Handle window resize
        window.addEventListener('resize', function() {
            const sidebar = document.getElementById('conversationsSidebar');
            const overlay = document.getElementById('sidebarOverlay');

            if (window.innerWidth > 768) {
                if (sidebar) {
                    sidebar.classList.remove('hidden');
                }
                if (overlay) {
                    overlay.classList.remove('active');
                }
                toggleBodyScroll(false);
            }
        });

        // Add smooth scroll behavior
        if ('scrollBehavior' in document.documentElement.style) {
            document.documentElement.style.scrollBehavior = 'smooth';
        }
    </script>
@endsection
