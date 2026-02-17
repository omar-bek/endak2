@extends('layouts.app')

@section('title', 'الرسائل')

@section('content')
<div class="chat-container" id="chatContainer">
    <div class="conversations-sidebar" id="conversationsSidebar">
        <div class="sidebar-header">
            <h5 class="sidebar-title"><i class="fas fa-comments"></i> <span>{{ __('messages.conversations_title') }}</span></h5>
            <button class="sidebar-toggle" id="sidebarToggle" onclick="toggleSidebar()">
                <i class="fas fa-chevron-left"></i>
            </button>
        </div>
        <div class="sidebar-search">
            <div class="search-container">
                <input type="text" id="searchConversations" placeholder="{{ __('messages.search_conversations_placeholder') }}" class="search-input">
                <i class="fas fa-search search-icon"></i>
            </div>
        </div>
        <div class="conversations-list" id="conversationsList">
            @forelse($conversations as $conversation)
            @php
            $otherUser = $conversation->sender_id == auth()->id() ? $conversation->receiver : $conversation->sender;
            $lastMessage = $conversation;
            $unreadCount = \App\Models\Message::where('conversation_id', $conversation->conversation_id)
            ->where('receiver_id', auth()->id())
            ->where('is_read', false)
            ->where('is_deleted', false)
            ->count();
            @endphp
            <div class="conversation-item" onclick="window.location.href='{{ route('messages.show', $otherUser->id) }}'" data-name="{{ strtolower($otherUser->name) }}">
                <div class="conversation-avatar">
                    @if($otherUser->image && file_exists(public_path('storage/' . $otherUser->image)))
                    <img src="{{ asset('storage/' . $otherUser->image) }}" alt="{{ $otherUser->name }}" onerror="this.onerror=null;this.src='{{ asset('images/default-avatar.png') }}';">
                    @else
                    <div class="default-avatar">
                        {{ strtoupper(substr($otherUser->name, 0, 1)) }}
                    </div>
                    @endif
                    <div class="online-indicator {{ $otherUser->isOnline() ? 'online' : 'offline' }}"></div>
                </div>
                <div class="conversation-info">
                    <div class="info-header">
                        <div class="conversation-name">{{ $otherUser->name }}</div>
                        @if($lastMessage)
                        <div class="conversation-time">{{ $lastMessage->formatted_time }}</div>
                        @endif
                    </div>
                    <div class="info-body">
                        @if($lastMessage)
                        <div class="conversation-preview">
                            @if($lastMessage->isImage())
                            <i class="fas fa-image me-1"></i> {{ __('messages.message_preview_image') }}
                            @elseif($lastMessage->isVoice())
                            <i class="fas fa-microphone me-1"></i> {{ __('messages.message_preview_voice') }}
                            @elseif($lastMessage->isFile())
                            <i class="fas fa-file me-1"></i> {{ __('messages.message_preview_file') }}
                            @elseif($lastMessage->isLocation())
                            <i class="fas fa-map-marker-alt me-1"></i> {{ __('messages.message_preview_location') }}
                            @elseif($lastMessage->isContact())
                            <i class="fas fa-user me-1"></i> {{ __('messages.message_preview_contact') }}
                            @else
                            {{ Str::limit($lastMessage->content, 25) }}
                            @endif
                        </div>
                        @else
                        <div class="conversation-preview">{{ __('messages.no_messages_preview') }}</div>
                        @endif
                        @if($unreadCount > 0)
                        <div class="unread-badge">{{ $unreadCount }}</div>
                        @endif
                    </div>
                </div>
            </div>
            @empty
            <div class="no-conversations">
                <i class="fas fa-comment-slash"></i>
                <p>{{ __('messages.no_conversations_title') }}</p>
            </div>
            @endforelse
        </div>
    </div>

    <div class="chat-area">
        <div class="chat-header">
            <button class="mobile-sidebar-toggle" id="mobileSidebarToggle" onclick="toggleSidebar()">
                <i class="fas fa-bars"></i>
            </button>
            <div class="chat-header-info">
                <h5>{{ __('messages.chat_header_default_title') }}</h5>
                <small>{{ __('messages.chat_header_default_subtitle') }}</small>
            </div>
        </div>
        <div class="chat-content" id="chatContent">
            <div class="welcome-message">
                <div class="welcome-icon">
                    <i class="fas fa-comments"></i>
                </div>
                <h3>{{ __('messages.welcome_message_title') }}</h3>
                <p>{{ __('messages.welcome_message_body') }}</p>
            </div>
        </div>
    </div>
</div>


<style>
    @import url('https://fonts.googleapis.com/css2?family=Cairo:wght@400;500;600;700&display=swap');

    :root {
        --bg-dark: #2f5c69;
        --bg-dark-transparent: rgba(47, 92, 105, 0.9);
        --accent: #f3a446;
        --accent-hover: #ffb861;
        --text-light: #ffffff;
        --text-muted: #d1e0e4;
        --text-dark: #333;
        --chat-bg: #f5f7fa;
        --border-color: rgba(255, 255, 255, 0.1);
        --sidebar-width: 340px;
        --sidebar-collapsed-width: 90px;
    }

    /* === Main Layout === */
    .chat-container {
        display: flex;
        height: 100vh;
        overflow: hidden;
        font-family: 'Cairo', sans-serif;
        background: var(--chat-bg);
        transition: padding-right 0.4s cubic-bezier(0.25, 0.46, 0.45, 0.94);
    }

    .chat-layout { /* This class is not used in the new design, kept for compatibility */
        display: flex; flex: 1; height: 100vh;
    }

    /* === Conversations Sidebar === */
    .conversations-sidebar {
        width: var(--sidebar-width);
        background: var(--bg-dark-transparent);
        backdrop-filter: blur(12px);
        border-left: 1px solid var(--border-color);
        display: flex;
        flex-direction: column;
        flex-shrink: 0;
        transition: width 0.4s cubic-bezier(0.25, 0.46, 0.45, 0.94),
                    transform 0.4s ease,
                    box-shadow 0.3s ease;
        position: relative;
        z-index: 100;
        box-shadow: -5px 0 25px rgba(0, 0, 0, 0.2);
    }

    .conversations-sidebar.hidden {
    transform: translateX(100%);
}

    .sidebar-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 20px 25px;
        flex-shrink: 0;
        border-bottom: 1px solid var(--border-color);
    }

    .sidebar-title {
        color: var(--text-light);
        font-size: 1.25rem;
        font-weight: 700;
        display: flex;
        align-items: center;
        gap: 12px;
        white-space: nowrap;
    }

    .sidebar-toggle {
        background: rgba(255, 255, 255, 0.1);
        border: none;
        color: var(--text-light);
        width: 35px;
        height: 35px;
        border-radius: 50%;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: background 0.3s ease, transform 0.3s ease;
    }

    .sidebar-toggle:hover {
        background: rgba(255, 255, 255, 0.2);
        transform: scale(1.1);
    }

    .sidebar-toggle i {
        transition: transform 0.4s cubic-bezier(0.25, 0.46, 0.45, 0.94);
    }

    /* === Search Bar === */
    .sidebar-search {
        padding: 15px 25px;
        flex-shrink: 0;
        margin-top:20px;

    }
    .search-container { position: relative; }
    .search-input {
        width: 100%;
        padding: 12px 20px 12px 40px;
        border: 1px solid var(--border-color);
        border-radius: 25px;
        background: rgba(0, 0, 0, 0.2);
        color: var(--text-light);
        font-size: 14px;
        transition: all 0.3s ease;
    }
    .search-input::placeholder { color: var(--text-muted); }
    .search-input:focus {
        outline: none;
        background: rgba(0, 0, 0, 0.3);
        box-shadow: 0 0 0 2px var(--accent);
        border-color: var(--accent);
    }
    .search-icon {
        position: absolute;
        left: 15px;
        top: 50%;
        transform: translateY(-50%);
        color: var(--text-muted);
    }

    /* === Conversations List === */
    .conversations-list {
        flex: 1;
        overflow-y: auto;
        padding: 0 10px;
    }
    /* Custom scrollbar */
    .conversations-list::-webkit-scrollbar { width: 6px; }
    .conversations-list::-webkit-scrollbar-track { background: transparent; }
    .conversations-list::-webkit-scrollbar-thumb { background: rgba(255, 255, 255, 0.2); border-radius: 10px; }
    .conversations-list::-webkit-scrollbar-thumb:hover { background: rgba(255, 255, 255, 0.3); }

    .conversation-item {
        display: flex;
        align-items: center;
        padding: 12px 15px;
        margin: 8px 0;
        cursor: pointer;
        border-radius: 12px;
        transition: background 0.3s ease, transform 0.2s ease;
        position: relative;
        overflow: hidden;
    }

    .conversation-item:hover {
        background: rgba(255, 255, 255, 0.1);
        transform: translateY(-2px);
    }

    .conversation-item.active {
        background: rgba(0, 0, 0, 0.2);
        border-right: 4px solid var(--accent);
    }

    .conversation-avatar {
        position: relative;
        margin-left: 15px;
        flex-shrink: 0;
    }

    .conversation-avatar img, .default-avatar {
        width: 55px;
        height: 55px;
        border-radius: 50%;
        object-fit: cover;
        border: 3px solid rgba(255, 255, 255, 0.2);
    }

    .default-avatar {
        background: linear-gradient(135deg, var(--accent), var(--accent-hover));
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--text-light);
        font-weight: bold;
        font-size: 20px;
    }

    .online-indicator {
        position: absolute;
        bottom: 3px;
        left: 3px;
        width: 14px;
        height: 14px;
        border-radius: 50%;
        border: 2px solid var(--bg-dark);
        background-color: #28a745; /* Online */
    }
    .online-indicator.offline { background-color: #6c757d; /* Offline */ }

    .conversation-info {
        flex: 1;
        min-width: 0;
        color: var(--text-light);
    }
    .info-header { display: flex; justify-content: space-between; align-items: baseline; }
    .conversation-name { font-weight: 600; font-size: 15px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
    .conversation-time { font-size: 11px; color: var(--text-muted); flex-shrink: 0; }
    .info-body { display: flex; justify-content: space-between; align-items: center; margin-top: 4px; }
    .conversation-preview { font-size: 13px; color: var(--text-muted); white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
    .unread-badge {
        background: var(--accent);
        color: var(--text-dark);
        border-radius: 50%;
        width: 22px;
        height: 22px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 11px;
        font-weight: bold;
        flex-shrink: 0;
    }

    .no-conversations { text-align: center; padding: 40px 20px; color: var(--text-muted); }
    .no-conversations i { font-size: 48px; margin-bottom: 15px; display: block; }
    .no-conversations p { margin: 0; font-size: 14px; }


    /* === Collapsed Sidebar State (Desktop) === */
    .chat-container.sidebar-collapsed .conversations-sidebar {
        width: var(--sidebar-collapsed-width);
    }
    .chat-container.sidebar-collapsed .sidebar-title span,
    .chat-container.sidebar-collapsed .sidebar-search,
    .chat-container.sidebar-collapsed .conversation-info {
        display: none;
    }
    .chat-container.sidebar-collapsed .sidebar-header {
        justify-content: center;
    }
    .chat-container.sidebar-collapsed .sidebar-title i {
        margin: 0;
    }
    .chat-container.sidebar-collapsed .sidebar-toggle i {
        transform: rotate(180deg);
    }
    .chat-container.sidebar-collapsed .conversation-item {
        justify-content: center;
        padding: 15px 0;
    }
    .chat-container.sidebar-collapsed .conversation-avatar {
        margin: 0;
    }


    /* === Chat Area === */
    .chat-area {
        flex: 1;
        display: flex;
        flex-direction: column;
        overflow: hidden;
    }

    .chat-header {
        background: var(--bg-dark-transparent);
        backdrop-filter: blur(12px);
        color: var(--text-light);
        padding: 18px 25px;
        border-bottom: 1px solid var(--border-color);
        display: flex;
        align-items: center;
        gap: 15px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        z-index: 10;
        flex-shrink: 0;
    }

    .chat-header-info h5 {
        color: var(--text-light);
        margin: 0;
        font-weight: 600;
    }
    .chat-header-info small { color: var(--text-muted); }

    .chat-content {
        flex: 1;
        overflow-y: auto;
        padding: 30px;
        background-color: var(--chat-bg);
        display: flex;
        align-items: center;
        justify-content: center;
    }

    /* === Welcome Message === */
    .welcome-message { text-align: center; color: #888; }
    .welcome-icon { font-size: 72px; color: var(--bg-dark); opacity: 0.5; margin-bottom: 20px; }
    .welcome-message h3 { margin-bottom: 10px; color: #444; font-weight: 600; }
    .welcome-message p { color: #777; }

    /* === Mobile Responsiveness === */
    .mobile-sidebar-toggle { display: none; background: transparent; border: none; color: white; font-size: 20px; cursor: pointer;}

@media (min-width: 769px) {
    .chat-container {
        margin-top: 50px;
    }

    .sidebar-header {
        margin-top: 50px;
    }
}


        .conversations-sidebar {
            position: fixed;
            top: 0;
            right: 0;
            bottom: 0;
            height: 100%;
            width: 300px;
            transform: translateX(100%);
            box-shadow: -10px 0 30px rgba(0,0,0,0.2);
            border-left: 1px solid var(--border-color);
            border-right: none;
        }

        .conversations-sidebar.sidebar-visible {
            transform: translateX(0);
        }

        .sidebar-toggle { /* This is the close 'X' button on mobile */
           display: flex;
        }
        .sidebar-toggle i { transform: rotate(0) !important; }

        .mobile-sidebar-toggle {
            display: block;
        }

        .chat-header {
             padding: 15px 20px;
        }

        /* Overlay for when sidebar is open on mobile */
        .chat-container::after {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0,0,0,0.5);
            z-index: 99;
            opacity: 0;
            visibility: hidden;
            transition: opacity 0.4s ease, visibility 0.4s ease;
        }
        .chat-container.mobile-sidebar-open::after {
            opacity: 1;
            visibility: visible;
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
                } else {
                    item.style.display = 'none';
                }
            });

            // Show no results message if no conversations match
            const visibleItems = Array.from(conversationItems).filter(item =>
                item.style.display !== 'none'
            );

            let noResultsMsg = conversationsList.querySelector('.no-results');
            if (visibleItems.length === 0 && searchTerm !== '') {
                if (!noResultsMsg) {
                    noResultsMsg = document.createElement('div');
                    noResultsMsg.className = 'no-conversations no-results';
                    noResultsMsg.innerHTML = `<i class="fas fa-search"></i><p>لا توجد نتائج للبحث</p>`;
                    conversationsList.appendChild(noResultsMsg);
                }
            } else if (noResultsMsg) {
                noResultsMsg.remove();
            }
        });
    }

    // Auto-hide sidebar on mobile when clicking outside
    const chatContainer = document.getElementById('chatContainer');
    chatContainer.addEventListener('click', function(event) {
        const sidebar = document.getElementById('conversationsSidebar');
        if (window.innerWidth <= 768 && sidebar.classList.contains('sidebar-visible')) {
            // Check if the click was on the overlay (the container itself)
            if (event.target === chatContainer) {
                toggleSidebar();
            }
        }
    });
});

// Universal Sidebar toggle function
function toggleSidebar() {
    const sidebar = document.getElementById('conversationsSidebar');
    const container = document.getElementById('chatContainer');

    if (window.innerWidth > 5000) {
        // Desktop: collapse/expand
        container.classList.toggle('sidebar-collapsed');
    } else {
        // Mobile: show/hide
        sidebar.classList.toggle('sidebar-visible');
        container.classList.toggle('mobile-sidebar-open');
    }
}

document.addEventListener('DOMContentLoaded', function() {
    const sidebar = document.getElementById('conversationsSidebar');
    const container = document.getElementById('chatContainer');
    const toggleButton = document.getElementById('toggleSidebar');

    sidebar.classList.add('sidebar-visible');
    container.classList.add('mobile-sidebar-open');

    toggleButton.addEventListener('click', function() {
        sidebar.classList.toggle('sidebar-visible');
        container.classList.toggle('mobile-sidebar-open');
    });
});


</script>
@endsection