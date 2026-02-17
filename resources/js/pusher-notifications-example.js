/**
 * مثال على استخدام Pusher للإشعارات في الوقت الفعلي
 * 
 * هذا الملف يحتوي على مثال بسيط يمكن استخدامه في أي frontend framework
 * (React, Vue, Vanilla JS, etc.)
 */

// ============================================
// 1. إعداد Laravel Echo و Pusher
// ============================================

import Echo from 'laravel-echo';
import Pusher from 'pusher-js';

// إعداد Pusher كـ global
window.Pusher = Pusher;

// إعداد Echo
window.Echo = new Echo({
    broadcaster: 'pusher',
    key: process.env.MIX_PUSHER_APP_KEY || window.PUSHER_APP_KEY,
    cluster: process.env.MIX_PUSHER_APP_CLUSTER || window.PUSHER_APP_CLUSTER,
    forceTLS: true,
    encrypted: true,
    authEndpoint: '/api/broadcasting/auth',
    auth: {
        headers: {
            'Authorization': 'Bearer ' + (localStorage.getItem('api_token') || ''),
            'Accept': 'application/json'
        }
    }
});

// ============================================
// 2. دالة للاشتراك في إشعارات المستخدم
// ============================================

/**
 * الاشتراك في إشعارات مستخدم معين
 * @param {number} userId - معرف المستخدم
 * @param {function} callback - دالة يتم استدعاؤها عند استلام إشعار
 * @returns {object} - Channel object للتحكم فيه لاحقاً
 */
export function subscribeToUserNotifications(userId, callback) {
    // الاشتراك في private channel
    const channel = window.Echo.private(`user.${userId}`);

    // الاستماع لحدث notification.sent
    channel.listen('.notification.sent', (data) => {
        console.log('إشعار جديد:', data);
        
        // استدعاء callback إذا كان موجوداً
        if (callback && typeof callback === 'function') {
            callback(data);
        }
        
        // عرض الإشعار تلقائياً
        showBrowserNotification(data);
    });

    return channel;
}

// ============================================
// 3. دالة عرض إشعار المتصفح
// ============================================

/**
 * عرض إشعار في المتصفح
 * @param {object} notification - بيانات الإشعار
 */
export function showBrowserNotification(notification) {
    // طلب إذن الإشعارات إذا لم يكن موجوداً
    if ('Notification' in window && Notification.permission === 'default') {
        Notification.requestPermission();
    }

    // عرض الإشعار إذا كان الإذن ممنوحاً
    if ('Notification' in window && Notification.permission === 'granted') {
        const notificationObj = new Notification(notification.title, {
            body: notification.message,
            icon: '/images/logo.png',
            badge: '/images/badge.png',
            tag: `notification-${notification.id}`,
            data: notification.data
        });

        // إغلاق الإشعار تلقائياً بعد 5 ثوان
        setTimeout(() => {
            notificationObj.close();
        }, 5000);

        // عند النقر على الإشعار
        notificationObj.onclick = function() {
            window.focus();
            // يمكنك إضافة redirect هنا
            // window.location.href = `/notifications/${notification.id}`;
            this.close();
        };
    }

    // عرض toast notification (إذا كان متوفراً)
    if (window.toastr) {
        window.toastr.info(notification.message, notification.title, {
            timeOut: 5000,
            closeButton: true,
            progressBar: true,
            onclick: function() {
                // يمكنك إضافة redirect هنا
            }
        });
    }
}

// ============================================
// 4. دالة تحديث عداد الإشعارات غير المقروءة
// ============================================

/**
 * تحديث عداد الإشعارات غير المقروءة
 * @param {number} count - عدد الإشعارات غير المقروءة
 */
export function updateUnreadCount(count) {
    // البحث عن عنصر العداد
    const badge = document.querySelector('.notification-badge');
    const counter = document.querySelector('.notification-count');
    
    if (badge) {
        badge.textContent = count;
        badge.style.display = count > 0 ? 'block' : 'none';
    }
    
    if (counter) {
        counter.textContent = count;
        counter.style.display = count > 0 ? 'inline' : 'none';
    }

    // تحديث title الصفحة
    if (count > 0) {
        document.title = `(${count}) ${document.title.replace(/^\(\d+\)\s*/, '')}`;
    } else {
        document.title = document.title.replace(/^\(\d+\)\s*/, '');
    }
}

// ============================================
// 5. دالة إضافة إشعار للقائمة
// ============================================

/**
 * إضافة إشعار جديد لقائمة الإشعارات
 * @param {object} notification - بيانات الإشعار
 */
export function addNotificationToList(notification) {
    const list = document.querySelector('.notifications-list');
    if (!list) return;

    // إنشاء عنصر الإشعار
    const item = document.createElement('div');
    item.className = `notification-item notification-${notification.type} ${notification.read_at ? 'read' : 'unread'}`;
    item.dataset.notificationId = notification.id;
    
    item.innerHTML = `
        <div class="notification-icon">
            <i class="${notification.icon}"></i>
        </div>
        <div class="notification-content">
            <div class="notification-title">${notification.title}</div>
            <div class="notification-message">${notification.message}</div>
            <div class="notification-time">${formatTime(notification.created_at)}</div>
        </div>
        ${!notification.read_at ? '<div class="notification-dot"></div>' : ''}
    `;

    // إضافة في البداية
    list.insertBefore(item, list.firstChild);

    // إضافة animation
    item.style.opacity = '0';
    setTimeout(() => {
        item.style.transition = 'opacity 0.3s';
        item.style.opacity = '1';
    }, 10);

    // إزالة الإشعارات القديمة (احتفظ بـ 50 إشعار فقط)
    const items = list.querySelectorAll('.notification-item');
    if (items.length > 50) {
        items[items.length - 1].remove();
    }
}

// ============================================
// 6. دالة تنسيق الوقت
// ============================================

/**
 * تنسيق الوقت بشكل مقروء
 * @param {string} dateString - تاريخ بصيغة ISO
 * @returns {string} - تاريخ منسق
 */
function formatTime(dateString) {
    const date = new Date(dateString);
    const now = new Date();
    const diff = now - date;
    
    const seconds = Math.floor(diff / 1000);
    const minutes = Math.floor(seconds / 60);
    const hours = Math.floor(minutes / 60);
    const days = Math.floor(hours / 24);

    if (seconds < 60) {
        return 'الآن';
    } else if (minutes < 60) {
        return `منذ ${minutes} دقيقة`;
    } else if (hours < 24) {
        return `منذ ${hours} ساعة`;
    } else if (days < 7) {
        return `منذ ${days} يوم`;
    } else {
        return date.toLocaleDateString('ar-SA');
    }
}

// ============================================
// 7. دالة طلب إذن الإشعارات
// ============================================

/**
 * طلب إذن عرض الإشعارات من المتصفح
 */
export function requestNotificationPermission() {
    if ('Notification' in window && Notification.permission === 'default') {
        Notification.requestPermission().then(permission => {
            if (permission === 'granted') {
                console.log('تم تفعيل الإشعارات بنجاح');
            } else {
                console.log('تم رفض الإشعارات');
            }
        });
    }
}

// ============================================
// 8. دالة قطع الاتصال
// ============================================

/**
 * قطع الاتصال مع Pusher
 * @param {number} userId - معرف المستخدم
 */
export function disconnectNotifications(userId) {
    if (window.Echo) {
        window.Echo.leave(`user.${userId}`);
        window.Echo.disconnect();
    }
}

// ============================================
// 9. مثال على الاستخدام
// ============================================

/**
 * مثال على الاستخدام الكامل
 */
export function initNotifications(userId) {
    // طلب إذن الإشعارات
    requestNotificationPermission();

    // الاشتراك في الإشعارات
    const channel = subscribeToUserNotifications(userId, (notification) => {
        // تحديث العداد
        updateUnreadCount(notification.unread_count);
        
        // إضافة للقائمة
        addNotificationToList(notification);
        
        // يمكنك إضافة أي منطق آخر هنا
        console.log('تم استلام إشعار:', notification);
    });

    // إرجاع channel للتحكم فيه لاحقاً
    return channel;
}

// ============================================
// 10. مثال React Hook
// ============================================

/**
 * مثال على استخدام React Hook
 * 
 * import { useEffect, useState } from 'react';
 * import { initNotifications, disconnectNotifications } from './pusher-notifications-example';
 * 
 * function useNotifications(userId) {
 *     const [notifications, setNotifications] = useState([]);
 *     const [unreadCount, setUnreadCount] = useState(0);
 * 
 *     useEffect(() => {
 *         if (!userId) return;
 * 
 *         const channel = initNotifications(userId);
 * 
 *         // تحديث state عند استلام إشعار
 *         channel.listen('.notification.sent', (data) => {
 *             setNotifications(prev => [data, ...prev]);
 *             setUnreadCount(data.unread_count);
 *         });
 * 
 *         return () => {
 *             disconnectNotifications(userId);
 *         };
 *     }, [userId]);
 * 
 *     return { notifications, unreadCount };
 * }
 */

// ============================================
// 11. مثال Vue Composable
// ============================================

/**
 * مثال على استخدام Vue Composable
 * 
 * import { ref, onMounted, onUnmounted } from 'vue';
 * import { initNotifications, disconnectNotifications } from './pusher-notifications-example';
 * 
 * export function useNotifications(userId) {
 *     const notifications = ref([]);
 *     const unreadCount = ref(0);
 * 
 *     onMounted(() => {
 *         if (!userId) return;
 * 
 *         const channel = initNotifications(userId);
 * 
 *         channel.listen('.notification.sent', (data) => {
 *             notifications.value.unshift(data);
 *             unreadCount.value = data.unread_count;
 *         });
 *     });
 * 
 *     onUnmounted(() => {
 *         if (userId) {
 *             disconnectNotifications(userId);
 *         }
 *     });
 * 
 *     return { notifications, unreadCount };
 * }
 */
