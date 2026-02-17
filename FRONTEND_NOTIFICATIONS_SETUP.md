# 🔧 إصلاح مشكلة Frontend - الإشعارات لا تظهر

## ✅ المشكلة:

الإشعارات تصل إلى **Pusher** (يظهر في Debug Console) لكن **Frontend لا يستقبلها**.

## 🔍 الأسباب المحتملة:

1. **Frontend يستخدم Reverb بدلاً من Pusher**
2. **Channel name غير صحيح**
3. **Event name غير صحيح** (يجب أن يكون `.notification.sent` مع النقطة)
4. **Authentication فاشل**
5. **Laravel Echo غير متصل**

## 🚀 الحل الكامل:

### 1. تثبيت المكتبات

```bash
npm install --save laravel-echo pusher-js
```

### 2. إعداد Laravel Echo (Pusher)

أنشئ ملف `resources/js/notifications.js`:

```javascript
import Echo from 'laravel-echo';
import Pusher from 'pusher-js';

// إعداد Pusher كـ global
window.Pusher = Pusher;

// إعداد Echo مع Pusher
window.Echo = new Echo({
    broadcaster: 'pusher',  // مهم: يجب أن يكون 'pusher' وليس 'reverb'
    key: import.meta.env.VITE_PUSHER_APP_KEY || process.env.MIX_PUSHER_APP_KEY || 'e91ff80f1a87987e5a08',
    cluster: import.meta.env.VITE_PUSHER_APP_CLUSTER || process.env.MIX_PUSHER_APP_CLUSTER || 'eu',
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
    console.log('🔔 الاشتراك في إشعارات المستخدم:', userId);
    
    // الاشتراك في private channel
    const channel = window.Echo.private(`user.${userId}`);
    
    console.log('📡 Channel:', `private-user.${userId}`);
    
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
                icon: '/images/logo.png'
            });
        }
    });
    
    // Event listeners للتحقق من الاتصال
    channel.subscribed(() => {
        console.log('✅ تم الاشتراك في channel بنجاح');
    });
    
    channel.error((error) => {
        console.error('❌ خطأ في channel:', error);
    });
    
    return channel;
}

// دالة قطع الاتصال
export function unsubscribeFromNotifications(userId) {
    if (window.Echo) {
        window.Echo.leave(`user.${userId}`);
        console.log('🔌 تم قطع الاتصال من channel');
    }
}

export default window.Echo;
```

### 3. استخدام في React/Vue/Vanilla JS

#### React Example:

```jsx
import { useEffect } from 'react';
import { subscribeToNotifications, unsubscribeFromNotifications } from './notifications';

function NotificationListener({ userId, apiToken }) {
    useEffect(() => {
        if (!userId || !apiToken) {
            console.warn('⚠️ userId أو apiToken غير موجود');
            return;
        }
        
        // حفظ api_token في localStorage
        localStorage.setItem('api_token', apiToken);
        
        // الاشتراك في الإشعارات
        const channel = subscribeToNotifications(userId, (notification) => {
            console.log('إشعار جديد:', notification);
            // تحديث state أو عرض notification
        });
        
        // تنظيف عند unmount
        return () => {
            unsubscribeFromNotifications(userId);
        };
    }, [userId, apiToken]);
    
    return null;
}
```

#### Vue Example:

```vue
<template>
    <div></div>
</template>

<script>
import { subscribeToNotifications, unsubscribeFromNotifications } from './notifications';

export default {
    props: ['userId', 'apiToken'],
    mounted() {
        if (!this.userId || !this.apiToken) {
            console.warn('⚠️ userId أو apiToken غير موجود');
            return;
        }
        
        // حفظ api_token في localStorage
        localStorage.setItem('api_token', this.apiToken);
        
        // الاشتراك في الإشعارات
        this.channel = subscribeToNotifications(this.userId, (notification) => {
            console.log('إشعار جديد:', notification);
            // تحديث data أو emit event
            this.$emit('notification-received', notification);
        });
    },
    beforeUnmount() {
        if (this.userId) {
            unsubscribeFromNotifications(this.userId);
        }
    }
};
</script>
```

#### Vanilla JS Example:

```html
<!DOCTYPE html>
<html>
<head>
    <title>Notifications Test</title>
</head>
<body>
    <div id="notifications"></div>
    
    <script type="module">
        import { subscribeToNotifications } from './notifications.js';
        
        // الحصول على userId و apiToken
        const userId = 1; // من localStorage أو API
        const apiToken = localStorage.getItem('api_token');
        
        if (!userId || !apiToken) {
            console.error('❌ userId أو apiToken غير موجود');
        } else {
            // الاشتراك في الإشعارات
            subscribeToNotifications(userId, (notification) => {
                console.log('إشعار جديد:', notification);
                
                // عرض الإشعار في الصفحة
                const div = document.getElementById('notifications');
                div.innerHTML = `
                    <div style="padding: 10px; background: #f0f0f0; margin: 10px;">
                        <strong>${notification.title}</strong><br>
                        ${notification.message}
                    </div>
                ` + div.innerHTML;
            });
        }
    </script>
</body>
</html>
```

### 4. إعداد Environment Variables

في `.env`:

```env
VITE_PUSHER_APP_KEY=e91ff80f1a87987e5a08
VITE_PUSHER_APP_CLUSTER=eu
```

أو في `vite.config.js`:

```javascript
export default defineConfig({
    // ...
    define: {
        'process.env': {
            MIX_PUSHER_APP_KEY: 'e91ff80f1a87987e5a08',
            MIX_PUSHER_APP_CLUSTER: 'eu'
        }
    }
});
```

### 5. التحقق من Authentication

افتح Console في المتصفح وتحقق من:

1. **Connection Status:**
```javascript
window.Echo.connector.pusher.connection.state
// يجب أن يكون: 'connected'
```

2. **Channel Subscription:**
```javascript
window.Echo.private('user.1').subscribed
// يجب أن يكون: true
```

3. **Errors:**
افتح Console وابحث عن أخطاء:
- `401 Unauthorized` → مشكلة في Authentication
- `403 Forbidden` → مشكلة في Channel Authorization
- `Connection failed` → مشكلة في Pusher connection

### 6. اختبار مباشر من Console

افتح Console في المتصفح وجرب:

```javascript
// 1. التحقق من Echo
console.log(window.Echo);

// 2. الاشتراك في channel
const channel = window.Echo.private('user.1');

// 3. الاستماع للحدث
channel.listen('.notification.sent', (data) => {
    console.log('✅ إشعار مستلم:', data);
});

// 4. التحقق من الاشتراك
channel.subscribed(() => {
    console.log('✅ تم الاشتراك');
});
```

## 🔍 Troubleshooting:

### المشكلة: `401 Unauthorized`

**الحل:**
1. تحقق من API Token في localStorage:
```javascript
localStorage.getItem('api_token')
```

2. تحقق من Broadcasting Auth Route:
```bash
php artisan route:list | grep broadcasting
```

3. تحقق من Middleware `api.token`

### المشكلة: `403 Forbidden`

**الحل:**
1. تحقق من `routes/channels.php`:
```php
Broadcast::channel('user.{userId}', function ($user, $userId) {
    return (int) $user->id === (int) $userId;
});
```

2. تحقق من أن المستخدم مسجل دخوله

### المشكلة: Connection Failed

**الحل:**
1. تحقق من Pusher credentials في `.env`
2. تحقق من Cluster (يجب أن يكون `eu`)
3. تحقق من Network في DevTools

## ✅ Checklist:

- [ ] Laravel Echo مثبت (`npm install laravel-echo pusher-js`)
- [ ] Echo يستخدم `broadcaster: 'pusher'` (وليس 'reverb')
- [ ] Pusher credentials صحيحة
- [ ] `authEndpoint: '/api/broadcasting/auth'`
- [ ] API Token موجود في localStorage
- [ ] Channel name صحيح: `private-user.{userId}`
- [ ] Event name صحيح: `.notification.sent` (مع النقطة)
- [ ] Console لا تظهر أخطاء
- [ ] Connection state: `connected`
- [ ] Channel subscribed: `true`

---

**بعد تطبيق هذه الخطوات، الإشعارات يجب أن تعمل في Frontend!** ✅
