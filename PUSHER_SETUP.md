# إعداد Realtime Notifications باستخدام Pusher

## المتطلبات

1. حساب Pusher (مجاني متاح على [pusher.com](https://pusher.com))
2. Laravel Pusher package (موجود بالفعل في `composer.json`)

## خطوات الإعداد

### 1. إنشاء حساب Pusher والحصول على Credentials

1. سجل في [pusher.com](https://pusher.com)
2. أنشئ تطبيق جديد (Channels App)
3. احصل على:
   - `PUSHER_APP_ID`
   - `PUSHER_APP_KEY`
   - `PUSHER_APP_SECRET`
   - `PUSHER_APP_CLUSTER` (مثل: `mt1`, `eu`, `ap1`)

### 2. تحديث ملف `.env`

أضف/حدث المتغيرات التالية في ملف `.env`:

```env
BROADCAST_CONNECTION=pusher

PUSHER_APP_ID=your-app-id
PUSHER_APP_KEY=your-app-key
PUSHER_APP_SECRET=your-app-secret
PUSHER_APP_CLUSTER=mt1
PUSHER_HOST=
PUSHER_PORT=443
PUSHER_SCHEME=https

# Queue Driver (مطلوب للـ broadcasting)
QUEUE_CONNECTION=database
```

### 3. إنشاء جدول Jobs (إذا لم يكن موجوداً)

```bash
php artisan queue:table
php artisan migrate
```

### 4. تشغيل Queue Worker

```bash
php artisan queue:work
```

أو في production:

```bash
php artisan queue:work --daemon
```

## الاستخدام في Backend (Laravel)

### إنشاء إشعار جديد (يتم إرساله تلقائياً عبر Pusher)

```php
use App\Models\Notification;

// طريقة 1: استخدام createNotification
$notification = Notification::createNotification(
    $userId = 1,
    $type = 'offer_received',
    $title = 'عرض جديد',
    $message = 'لقد تلقيت عرضاً جديداً على خدمتك',
    $data = ['service_id' => 123, 'offer_id' => 456]
);

// طريقة 2: إنشاء مباشر
$notification = Notification::create([
    'user_id' => 1,
    'type' => 'service_requested',
    'title' => 'طلب خدمة جديد',
    'message' => 'لديك طلب خدمة جديد',
    'data' => ['service_id' => 789]
]);

// سيتم إرسال Event تلقائياً عبر Pusher عند الإنشاء
```

### أنواع الإشعارات المتاحة

- `offer_received` - عرض جديد
- `offer_accepted` - قبول العرض
- `offer_rejected` - رفض العرض
- `service_requested` - طلب خدمة جديد
- `payment_received` - استلام دفعة
- `service_completed` - اكتمال الخدمة
- `message_received` - رسالة جديدة
- `service_deleted` - حذف خدمة
- `system` - إشعار نظام

## الاستخدام في Frontend

### 1. تثبيت Laravel Echo و Pusher JS

```bash
npm install --save laravel-echo pusher-js
```

### 2. إعداد Laravel Echo

في ملف `resources/js/bootstrap.js` أو `resources/js/app.js`:

```javascript
import Echo from 'laravel-echo';
import Pusher from 'pusher-js';

window.Pusher = Pusher;

window.Echo = new Echo({
    broadcaster: 'pusher',
    key: process.env.MIX_PUSHER_APP_KEY,
    cluster: process.env.MIX_PUSHER_APP_CLUSTER,
    forceTLS: true,
    encrypted: true,
    authEndpoint: '/api/broadcasting/auth',
    auth: {
        headers: {
            'Authorization': 'Bearer ' + localStorage.getItem('api_token'),
            'Accept': 'application/json'
        }
    }
});
```

### 3. إعداد المتغيرات في `.env` (Frontend)

في ملف `.env`:

```env
MIX_PUSHER_APP_KEY="${PUSHER_APP_KEY}"
MIX_PUSHER_APP_CLUSTER="${PUSHER_APP_CLUSTER}"
```

### 4. الاستماع للإشعارات

```javascript
// الحصول على user_id من المستخدم المسجل دخوله
const userId = window.currentUser?.id || localStorage.getItem('user_id');

// الاشتراك في channel المستخدم
const channel = Echo.private(`user.${userId}`);

// الاستماع لحدث notification.sent
channel.listen('.notification.sent', (data) => {
    console.log('إشعار جديد:', data);
    
    // عرض الإشعار للمستخدم
    showNotification(data);
    
    // تحديث عداد الإشعارات غير المقروءة
    updateUnreadCount(data.unread_count);
    
    // إضافة الإشعار للقائمة
    addNotificationToList(data);
});

// مثال على دالة عرض الإشعار
function showNotification(notification) {
    // يمكنك استخدام مكتبة مثل toastr أو sweetalert
    if (window.toastr) {
        toastr.info(notification.message, notification.title, {
            timeOut: 5000,
            closeButton: true,
            progressBar: true
        });
    }
    
    // أو استخدام Notification API للمتصفح
    if ('Notification' in window && Notification.permission === 'granted') {
        new Notification(notification.title, {
            body: notification.message,
            icon: '/images/logo.png',
            badge: '/images/badge.png'
        });
    }
}

// مثال على دالة تحديث العداد
function updateUnreadCount(count) {
    const badge = document.querySelector('.notification-badge');
    if (badge) {
        badge.textContent = count;
        badge.style.display = count > 0 ? 'block' : 'none';
    }
}
```

### 5. طلب إذن الإشعارات من المتصفح

```javascript
// في بداية التطبيق
if ('Notification' in window && Notification.permission === 'default') {
    Notification.requestPermission().then(permission => {
        if (permission === 'granted') {
            console.log('تم تفعيل الإشعارات');
        }
    });
}
```

## API Authentication للـ Broadcasting

يحتاج Laravel Echo إلى authentication للـ private channels. تأكد من إضافة route للـ broadcasting auth:

في `routes/api.php`:

```php
use Illuminate\Support\Facades\Broadcast;

// Broadcasting Authentication
Broadcast::routes(['middleware' => ['api', 'api.token']]);
```

## مثال كامل - React/Vue Component

### React Example

```jsx
import { useEffect, useState } from 'react';
import Echo from 'laravel-echo';
import Pusher from 'pusher-js';

function NotificationComponent({ userId, apiToken }) {
    const [notifications, setNotifications] = useState([]);
    const [unreadCount, setUnreadCount] = useState(0);

    useEffect(() => {
        // إعداد Echo
        window.Pusher = Pusher;
        const echo = new Echo({
            broadcaster: 'pusher',
            key: process.env.REACT_APP_PUSHER_APP_KEY,
            cluster: process.env.REACT_APP_PUSHER_APP_CLUSTER,
            forceTLS: true,
            encrypted: true,
            authEndpoint: '/api/broadcasting/auth',
            auth: {
                headers: {
                    'Authorization': `Bearer ${apiToken}`,
                    'Accept': 'application/json'
                }
            }
        });

        // الاشتراك في channel
        const channel = echo.private(`user.${userId}`);

        // الاستماع للإشعارات
        channel.listen('.notification.sent', (data) => {
            setNotifications(prev => [data, ...prev]);
            setUnreadCount(data.unread_count);
            
            // عرض إشعار المتصفح
            if ('Notification' in window && Notification.permission === 'granted') {
                new Notification(data.title, {
                    body: data.message,
                    icon: '/images/logo.png'
                });
            }
        });

        // تنظيف عند unmount
        return () => {
            echo.leave(`user.${userId}`);
            echo.disconnect();
        };
    }, [userId, apiToken]);

    return (
        <div>
            <div>الإشعارات غير المقروءة: {unreadCount}</div>
            <ul>
                {notifications.map(notif => (
                    <li key={notif.id}>
                        <strong>{notif.title}</strong>
                        <p>{notif.message}</p>
                    </li>
                ))}
            </ul>
        </div>
    );
}
```

### Vue Example

```vue
<template>
    <div>
        <div>الإشعارات غير المقروءة: {{ unreadCount }}</div>
        <ul>
            <li v-for="notification in notifications" :key="notification.id">
                <strong>{{ notification.title }}</strong>
                <p>{{ notification.message }}</p>
            </li>
        </ul>
    </div>
</template>

<script>
import Echo from 'laravel-echo';
import Pusher from 'pusher-js';

export default {
    props: ['userId', 'apiToken'],
    data() {
        return {
            notifications: [],
            unreadCount: 0,
            echo: null
        };
    },
    mounted() {
        window.Pusher = Pusher;
        this.echo = new Echo({
            broadcaster: 'pusher',
            key: process.env.VUE_APP_PUSHER_APP_KEY,
            cluster: process.env.VUE_APP_PUSHER_APP_CLUSTER,
            forceTLS: true,
            encrypted: true,
            authEndpoint: '/api/broadcasting/auth',
            auth: {
                headers: {
                    'Authorization': `Bearer ${this.apiToken}`,
                    'Accept': 'application/json'
                }
            }
        });

        const channel = this.echo.private(`user.${this.userId}`);
        
        channel.listen('.notification.sent', (data) => {
            this.notifications.unshift(data);
            this.unreadCount = data.unread_count;
            
            if ('Notification' in window && Notification.permission === 'granted') {
                new Notification(data.title, {
                    body: data.message,
                    icon: '/images/logo.png'
                });
            }
        });
    },
    beforeUnmount() {
        if (this.echo) {
            this.echo.leave(`user.${this.userId}`);
            this.echo.disconnect();
        }
    }
};
</script>
```

## Mobile Apps (React Native / Flutter)

### React Native

```bash
npm install pusher-js react-native-pusher
```

```javascript
import Pusher from 'pusher-js/react-native';

const pusher = new Pusher('YOUR_APP_KEY', {
    cluster: 'YOUR_CLUSTER',
    encrypted: true,
    authEndpoint: 'https://your-api.com/api/broadcasting/auth',
    auth: {
        headers: {
            'Authorization': `Bearer ${apiToken}`,
            'Accept': 'application/json'
        }
    }
});

const channel = pusher.subscribe(`private-user.${userId}`);

channel.bind('notification.sent', (data) => {
    console.log('إشعار جديد:', data);
    // عرض الإشعار باستخدام react-native-push-notification
});
```

## Testing

### اختبار الإرسال من Tinker

```bash
php artisan tinker
```

```php
use App\Models\Notification;

$notification = Notification::create([
    'user_id' => 1,
    'type' => 'system',
    'title' => 'اختبار',
    'message' => 'هذا إشعار تجريبي',
    'data' => ['test' => true]
]);

// سيتم إرسال Event تلقائياً عبر Pusher
```

### اختبار من Pusher Debug Console

1. افتح Pusher Dashboard
2. اذهب إلى Debug Console
3. اختر channel: `private-user.1` (حيث 1 هو user_id)
4. Event name: `notification.sent`
5. أرسل بيانات تجريبية

## Troubleshooting

### المشكلة: الإشعارات لا تصل

1. **تحقق من Queue Worker**: تأكد من تشغيل `php artisan queue:work`
2. **تحقق من Broadcasting Config**: تأكد من `BROADCAST_CONNECTION=pusher` في `.env`
3. **تحقق من Pusher Credentials**: تأكد من صحة البيانات في `.env`
4. **تحقق من Channel Authorization**: تأكد من إضافة route للـ broadcasting auth
5. **تحقق من Console**: افتح Developer Tools وتحقق من الأخطاء

### المشكلة: Authentication فاشل

1. تأكد من إضافة `Broadcast::routes()` في `routes/api.php`
2. تأكد من إرسال `Authorization` header بشكل صحيح
3. تحقق من أن `api.token` middleware يعمل بشكل صحيح

### المشكلة: Queue لا يعمل

```bash
# تحقق من حالة الـ queue
php artisan queue:work --verbose

# إعادة تشغيل الـ queue
php artisan queue:restart
```

## Production Checklist

- [ ] تحديث `QUEUE_CONNECTION` إلى `redis` أو `database`
- [ ] إعداد Supervisor لـ Queue Worker
- [ ] تفعيل SSL/TLS في Pusher
- [ ] إضافة error handling في Frontend
- [ ] إضافة reconnection logic
- [ ] مراقبة Pusher Dashboard للاستخدام

## Resources

- [Pusher Documentation](https://pusher.com/docs)
- [Laravel Broadcasting](https://laravel.com/docs/broadcasting)
- [Laravel Echo](https://laravel.com/docs/broadcasting#client-side-installation)
