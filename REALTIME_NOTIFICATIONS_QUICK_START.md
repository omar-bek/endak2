# دليل سريع: Realtime Notifications مع Pusher

## ✅ ما تم إنجازه

1. ✅ إنشاء Event `NotificationSent` للإشعارات
2. ✅ إعداد Broadcasting Channel للمستخدمين (`user.{userId}`)
3. ✅ ربط Notification Model مع Broadcasting (إرسال تلقائي عند الإنشاء)
4. ✅ إضافة Broadcasting Authentication Route
5. ✅ إنشاء ملف توثيق شامل

## 🚀 خطوات البدء السريعة

### 1. إعداد Pusher

1. سجل في [pusher.com](https://pusher.com) (حساب مجاني متاح)
2. أنشئ تطبيق جديد (Channels App)
3. احصل على:
   - `PUSHER_APP_ID`
   - `PUSHER_APP_KEY`
   - `PUSHER_APP_SECRET`
   - `PUSHER_APP_CLUSTER`

### 2. تحديث `.env`

```env
BROADCAST_CONNECTION=pusher

PUSHER_APP_ID=your-app-id
PUSHER_APP_KEY=your-app-key
PUSHER_APP_SECRET=your-app-secret
PUSHER_APP_CLUSTER=mt1
PUSHER_HOST=
PUSHER_PORT=443
PUSHER_SCHEME=https

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

## 📝 الاستخدام

### في Backend (Laravel)

```php
use App\Models\Notification;

// إنشاء إشعار (سيتم إرساله تلقائياً عبر Pusher)
$notification = Notification::create([
    'user_id' => 1,
    'type' => 'offer_received',
    'title' => 'عرض جديد',
    'message' => 'لقد تلقيت عرضاً جديداً',
    'data' => ['service_id' => 123]
]);
```

### في Frontend (JavaScript)

```javascript
import Echo from 'laravel-echo';
import Pusher from 'pusher-js';

window.Pusher = Pusher;

window.Echo = new Echo({
    broadcaster: 'pusher',
    key: 'YOUR_PUSHER_APP_KEY',
    cluster: 'YOUR_CLUSTER',
    forceTLS: true,
    authEndpoint: '/api/broadcasting/auth',
    auth: {
        headers: {
            'Authorization': 'Bearer ' + apiToken
        }
    }
});

// الاشتراك في إشعارات المستخدم
const channel = Echo.private(`user.${userId}`);

// الاستماع للإشعارات
channel.listen('.notification.sent', (data) => {
    console.log('إشعار جديد:', data);
    // عرض الإشعار للمستخدم
});
```

## 📚 الملفات المهمة

- `app/Events/NotificationSent.php` - Event للإشعارات
- `app/Models/Notification.php` - Model مع Broadcasting
- `routes/channels.php` - تعريف Channels
- `routes/api.php` - Broadcasting Auth Route
- `PUSHER_SETUP.md` - دليل شامل ومفصل
- `resources/js/pusher-notifications-example.js` - أمثلة Frontend

## 🧪 الاختبار

```bash
php artisan tinker
```

```php
use App\Models\Notification;

Notification::create([
    'user_id' => 1,
    'type' => 'system',
    'title' => 'اختبار',
    'message' => 'هذا إشعار تجريبي'
]);
```

## 📖 للمزيد من التفاصيل

راجع ملف `PUSHER_SETUP.md` للحصول على:
- دليل إعداد مفصل
- أمثلة React/Vue
- أمثلة Mobile Apps
- Troubleshooting
- Production Checklist
