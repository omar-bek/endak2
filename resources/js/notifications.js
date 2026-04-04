/**
 * إعداد Laravel Echo و Pusher للإشعارات Realtime
 * 
 * الاستخدام:
 * import { subscribeToNotifications } from './notifications';
 * subscribeToNotifications(userId, (notification) => { ... });
 */

import Echo from 'laravel-echo';
import Pusher from 'pusher-js';

// إعداد Pusher كـ global
window.Pusher = Pusher;

// إعداد Echo مع Pusher
window.Echo = new Echo({
    broadcaster: 'pusher',  // مهم: يجب أن يكون 'pusher' وليس 'reverb'
    key: import.meta.env.VITE_PUSHER_APP_KEY || process.env.MIX_PUSHER_APP_KEY,
    cluster: import.meta.env.VITE_PUSHER_APP_CLUSTER || process.env.MIX_PUSHER_APP_CLUSTER,
    forceTLS: true,
    encrypted: true,
    authEndpoint: '/api/broadcasting/auth',  // مهم: يجب أن يكون /api/broadcasting/auth
    auth: {
        headers: {
            'Authorization': 'Bearer ' + (localStorage.getItem('api_token') || ''),
            'Accept': 'application/json'
        }
    },
    enabledTransports: ['ws', 'wss'],
});

// دالة للاشتراك في إشعارات المستخدم
export function subscribeToNotifications(userId, onNotification) {
    if (!userId) {
        console.error('❌ userId مطلوب للاشتراك في الإشعارات');
        return null;
    }
    
    console.log('🔔 الاشتراك في إشعارات المستخدم:', userId);
    
    // الاشتراك في private channel
    const channel = window.Echo.private(`user.${userId}`);
    
    console.log('📡 Channel:', `private-user.${userId}`);
    
    // Event listeners للتحقق من الاتصال
    channel.subscribed(() => {
        console.log('✅ تم الاشتراك في channel بنجاح:', `private-user.${userId}`);
    });
    
    channel.error((error) => {
        console.error('❌ خطأ في channel:', error);
    });
    
    // الاستماع لحدث notification.sent
    // مهم: يجب أن يكون '.notification.sent' مع النقطة في البداية
    channel.listen('.notification.sent', (data) => {
        console.log('✅ إشعار جديد مستلم:', data);
        
        // استدعاء callback
        if (onNotification && typeof onNotification === 'function') {
            onNotification(data);
        }
        
        // عرض إشعار المتصفح
        if ('Notification' in window && Notification.permission === 'granted') {
            new Notification(data.title, {
                body: data.message,
                icon: '/images/logo.png',
                badge: '/images/badge.png',
                tag: `notification-${data.id}`,
                data: data
            });
        }
    });
    
    return channel;
}

// دالة قطع الاتصال
export function unsubscribeFromNotifications(userId) {
    if (!userId) return;
    
    if (window.Echo) {
        window.Echo.leave(`user.${userId}`);
        console.log('🔌 تم قطع الاتصال من channel:', `private-user.${userId}`);
    }
}

// دالة التحقق من حالة الاتصال
export function getConnectionStatus() {
    if (!window.Echo || !window.Echo.connector || !window.Echo.connector.pusher) {
        return 'not_initialized';
    }
    
    return window.Echo.connector.pusher.connection.state;
}

// دالة التحقق من الاشتراك في channel
export function isSubscribed(userId) {
    if (!window.Echo || !userId) return false;
    
    const channel = window.Echo.private(`user.${userId}`);
    return channel.subscribed === true;
}

export default window.Echo;
