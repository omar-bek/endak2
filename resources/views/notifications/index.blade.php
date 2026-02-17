@extends('layouts.app')

@section('title', 'الإشعارات')

@section('content')
<div class="notifications-container">
    <div class="container py-5">
        <div class="page-header">
            <h2 class="page-title">
                <i class="fas fa-bell"></i> {{ __('messages.notifications_title') }}
                @if($notifications->where('read_at', null)->count() > 0)
                <span class="badge unread-count-badge ms-2">{{ $notifications->where('read_at', null)->count() }}</span>
                @endif
            </h2>
            @if($notifications->where('read_at', null)->count() > 0)
            <button class="btn btn-mark-all" onclick="markAllAsRead()" id="markAllReadBtn">
                <i class="fas fa-check-double"></i> {{ __('messages.mark_all_read') }}
            </button>
            @endif
        </div>

        @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
        @endif

        @if($notifications->count() > 0)
        <div class="notifications-list">
            @foreach($notifications as $notification)
            <div class="notification-card {{ $notification->isRead() ? 'is-read' : 'is-unread' }}" style="animation-delay: {{ $loop->index * 0.05 }}s">
                <div class="notification-icon">
                    <i class="{{ $notification->icon }}"></i>
                </div>
                <div class="notification-content">
                    <div class="notification-header">
                        <h6 class="notification-title">
                            {{ $notification->title }}
                            @if(!$notification->isRead())
                            <span class="badge new-badge ms-2">{{ __('messages.new_badge') }}</span>
                            @endif
                        </h6>
                        <small class="notification-time">
                            <i class="fas fa-clock"></i> {{ $notification->created_at->diffForHumans() }}
                        </small>
                    </div>
                    <p class="notification-message">{{ $notification->message }}</p>

                    <div class="notification-actions">
                        @if($notification->data && isset($notification->data['offer_id']))
                        @php
                        $offer = \App\Models\ServiceOffer::find($notification->data['offer_id']);
                        @endphp
                        @if($offer && $offer->service)
                        @if(auth()->id() == $offer->provider_id)
                        <a href="{{ route('service-offers.show', $offer->id) }}" class="btn btn-action-view-offer">
                            <i class="fas fa-handshake"></i> {{ __('messages.view_my_offer') }}
                        </a>
                        @elseif(auth()->id() == $offer->service->user_id)
                        <a href="{{ route('service-offers.show', $offer->id) }}" class="btn btn-action-view-offer">
                            <i class="fas fa-handshake"></i> {{ __('messages.view_offer') }}
                        </a>
                        @endif
                        @elseif($offer && !$offer->service)
                        <span class="btn btn-action-disabled">
                            <i class="fas fa-ban"></i> {{ __('messages.deleted_service') }}
                        </span>
                        @endif
                        @endif

                        @if($notification->data && isset($notification->data['service_id']))
                        @php
                        $service = \App\Models\Service::find($notification->data['service_id']);
                        @endphp
                        @if($service)
                        <a href="{{ route('services.show', $service->slug) }}" class="btn btn-action-view-service">
                            <i class="fas fa-eye"></i> {{ __('messages.view_service') }}
                        </a>
                        @else
                        <span class="btn btn-action-disabled">
                            <i class="fas fa-ban"></i> {{ __('messages.deleted_service') }}
                        </span>
                        @endif
                        @endif
                    </div>
                </div>
                <div class="notification-buttons">
                    @if(!$notification->isRead())
                    <button class="btn btn-icon btn-mark-read" title="{{ __('messages.mark_read_title') }}" onclick="markAsRead('{{ $notification->id }}')">
                        <i class="fas fa-check"></i>
                    </button>
                    @endif
                    <form method="POST" action="{{ route('notifications.destroy', $notification->id) }}" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-icon btn-delete" title="{{ __('messages.delete_notification_title') }}" onclick="return confirm('{{ __('messages.delete_confirm') }}')">
                            <i class="fas fa-trash"></i>
                        </button>
                    </form>
                </div>
            </div>
            @endforeach
        </div>

        <div class="d-flex justify-content-center mt-4">
            {{ $notifications->links() }}
        </div>
        @else
        <div class="no-notifications-placeholder">
            <div class="no-notifications-icon">
                <i class="fas fa-bell-slash"></i>
            </div>
            <h4>{{ __('messages.no_notifications_title') }}</h4>
            <p>{{ __('messages.no_notifications_message') }}</p>
        </div>
        @endif
    </div>
</div>

<style>
    /* === Font Import === */
    @import url('https://fonts.googleapis.com/css2?family=Cairo:wght@400;500;600;700&display=swap');

    /* === Color & Style Variables (from Navbar) === */
    :root {
        --bg-dark: #2f5c69;
        --accent: #f3a446;
        --accent-hover: #ffb861;
        --page-bg: #f5f7fa;
        --card-bg: #ffffff;
        --text-dark: #343a40;
        --text-muted: #6c757d;
        --border-color: #e9ecef;
        --success: #28a745;
        --danger: #dc3545;
    }

    /* === Main Page Styles === */
    .notifications-container {
        background-color: var(--page-bg);
        font-family: 'Cairo', sans-serif;
        min-height: 100vh;
        margin-top:30px;
    }

    .page-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 2.5rem;
        padding-bottom: 1rem;
        border-bottom: 1px solid var(--border-color);
    }

    .page-title {
        color: var(--bg-dark);
        font-weight: 700;
        display: flex;
        align-items: center;
    }

    .page-title .fa-bell {
        margin-left: 10px;
        color: var(--accent);
    }

    .unread-count-badge {
        background-color: var(--accent);
        color: var(--bg-dark);
        font-weight: bold;
        border-radius: 8px;
        font-size: 0.9rem;
    }

    .btn-mark-all {
        background: linear-gradient(135deg, var(--accent), var(--accent-hover));
        color: var(--bg-dark);
        border: none;
        font-weight: 600;
        padding: 0.6rem 1.2rem;
        border-radius: 8px;
        box-shadow: 0 4px 10px rgba(243, 164, 70, 0.3);
        transition: all 0.3s ease;
    }

    .btn-mark-all:hover {
        color: #fff;
        transform: translateY(-2px);
        box-shadow: 0 6px 15px rgba(243, 164, 70, 0.4);
    }

    /* === Notification Card === */
    .notification-card {
        display: flex;
        background: var(--card-bg);
        border-radius: 12px;
        margin-bottom: 1rem;
        padding: 1.25rem;
        border: 1px solid var(--border-color);
        border-right: 5px solid transparent;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
        transition: all 0.3s ease-in-out;
        opacity: 0;
        animation: fadeInUp 0.5s ease-out forwards;
    }

    .notification-card:hover {
        transform: translateY(-5px) scale(1.01);
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.08);
    }

    .notification-card.is-unread {
        border-right-color: var(--accent);
        background: #fff;
    }

    .notification-card.is-read {
        border-right-color: var(--border-color);
        opacity: 0.8;
    }
    
    .notification-card.is-read:hover {
        opacity: 1;
    }

    .notification-icon {
        flex-shrink: 0;
        width: 50px;
        height: 50px;
        border-radius: 50%;
        background-color: rgba(47, 92, 105, 0.1);
        color: var(--bg-dark);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        margin-left: 1.5rem;
    }

    .is-unread .notification-icon {
        background-color: rgba(243, 164, 70, 0.1);
        color: var(--accent);
    }

    .notification-content {
        flex-grow: 1;
    }
    .notification-header {
        display: flex;
        justify-content: space-between;
        align-items: baseline;
        margin-bottom: 0.25rem;
    }
    .notification-title {
        font-weight: 600;
        color: var(--text-dark);
        margin-bottom: 0;
    }
    .is-read .notification-title {
        font-weight: 500;
        color: var(--text-muted);
    }

    .new-badge {
        background-color: var(--accent);
        color: var(--bg-dark);
        font-size: 0.7rem;
        padding: 0.2rem 0.5rem;
    }

    .notification-time {
        font-size: 0.8rem;
        color: var(--text-muted);
    }
    .notification-message {
        font-size: 0.9rem;
        color: var(--text-muted);
        line-height: 1.6;
        margin-bottom: 1rem;
    }
    
    .notification-actions .btn {
        font-size: 0.8rem;
        padding: 0.3rem 0.8rem;
        border-radius: 20px;
        font-weight: 500;
        margin-right: 0.5rem;
    }

    .btn-action-view-offer {
        background-color: rgba(40, 167, 69, 0.1);
        color: #20843c;
    }
     .btn-action-view-service {
        background-color: rgba(23, 162, 184, 0.1);
        color: #118195;
    }
    .btn-action-disabled {
        background-color: #f1f1f1;
        color: #999;
        cursor: not-allowed;
    }


    .notification-buttons {
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        gap: 8px;
        margin-right: 1.25rem;
    }

    .btn-icon {
        background: transparent;
        border: 1px solid var(--border-color);
        color: var(--text-muted);
        width: 32px;
        height: 32px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.3s ease;
    }
    .btn-icon:hover {
        background-color: #f1f1f1;
    }
    .btn-mark-read:hover { color: var(--success); border-color: var(--success);}
    .btn-delete:hover { color: var(--danger); border-color: var(--danger);}


    /* === No Notifications Placeholder === */
    .no-notifications-placeholder {
        text-align: center;
        padding: 4rem 1rem;
        background: var(--card-bg);
        border-radius: 12px;
        color: var(--text-muted);
    }
    .no-notifications-icon {
        font-size: 4rem;
        color: var(--bg-dark);
        opacity: 0.2;
        margin-bottom: 1.5rem;
    }
    .no-notifications-placeholder h4 {
        color: var(--bg-dark);
        font-weight: 600;
    }

    /* === Pagination Styling === */
    .pagination .page-link {
        color: var(--bg-dark);
        border: 1px solid var(--border-color);
        margin: 0 3px;
        border-radius: 8px;
    }
    .pagination .page-link:hover {
        background-color: rgba(47, 92, 105, 0.1);
        color: var(--bg-dark);
    }
    .pagination .page-item.active .page-link {
        background-color: var(--bg-dark);
        border-color: var(--bg-dark);
        color: #fff;
        box-shadow: 0 4px 10px rgba(47, 92, 105, 0.3);
    }
    .pagination .page-item.disabled .page-link {
        color: #ccc;
    }

    /* === Animation === */
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }
    .fa-spinner {
        animation: spin 1s linear infinite;
    }

</style>

<script>
function markAsRead(notificationId) {
    const button = event.currentTarget; 
    const originalIcon = button.innerHTML;
    button.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
    button.disabled = true;

    fetch(`/notifications/${notificationId}/read`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Content-Type': 'application/json',
            'Accept': 'application/json',
        },
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            const card = button.closest('.notification-card');
            card.classList.remove('is-unread');
            card.classList.add('is-read');
            button.remove(); // Remove the button on success
            // Optionally, update counters without reloading
            location.reload(); // Simple solution is to reload
        } else {
            throw new Error('Failed to mark as read');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        button.innerHTML = originalIcon;
        button.disabled = false;
        alert('حدث خطأ أثناء تحديث الإشعار.');
    });
}


function markAllAsRead() {
    const button = document.getElementById('markAllReadBtn');
    if (!button) return;

    const originalText = button.innerHTML;
    button.innerHTML = '<i class="fas fa-spinner fa-spin"></i> جاري التحديث...';
    button.disabled = true;

    const csrfToken = document.querySelector('meta[name="csrf-token"]');
    if (!csrfToken) {
        alert('خطأ في الأمان. يرجى إعادة تحميل الصفحة.');
        button.innerHTML = originalText;
        button.disabled = false;
        return;
    }

    fetch('/notifications/read-all', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': csrfToken.getAttribute('content'),
            'Content-Type': 'application/json',
            'Accept': 'application/json',
        },
    })
    .then(response => {
        if (!response.ok) throw new Error(`HTTP error! status: ${response.status}`);
        return response.json();
    })
    .then(data => {
        if (data.success) {
            location.reload();
        } else {
            throw new Error(data.message || 'Server returned an error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        button.innerHTML = originalText;
        button.disabled = false;
        alert('حدث خطأ أثناء تحديث الإشعارات: ' + error.message);
    });
}
</script>
@endsection