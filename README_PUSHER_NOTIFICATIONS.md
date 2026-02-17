# 🔔 نظام الإشعارات الفورية (Realtime Notifications)

## ✅ تم الإعداد بالكامل!

تم إعداد نظام الإشعارات الفورية باستخدام **Laravel Broadcasting** و **Pusher** بنجاح.

## 📁 الملفات المُنشأة

### Backend (Laravel)
- ✅ `app/Events/NotificationSent.php` - Event للإشعارات
- ✅ `app/Models/Notification.php` - Model مع Broadcasting
- ✅ `routes/channels.php` - Broadcasting Channels
- ✅ `routes/api.php` - Broadcasting Auth Route
- ✅ `app/Console/Commands/TestNotification.php` - Command للاختبار

### Scripts
- ✅ `setup-pusher.sh` - Script إعداد (Linux/Mac)
- ✅ `setup-pusher.bat` - Script إعداد (Windows)

### Documentation
- ✅ `PUSHER_SETUP.md` - دليل شامل ومفصل
- ✅ `REALTIME_NOTIFICATIONS_QUICK_START.md` - دليل سريع
- ✅ `PUSHER_COMPLETE_SETUP.md` - دليل الإعداد الكامل
- ✅ `resources/js/pusher-notifications-example.js` - أمثلة Frontend

## 🚀 البدء السريع

### 1. إعداد Pusher

1. سجل في [pusher.com](https://pusher.com)
2. أنشئ تطبيق جديد
3. احصل على credentials

### 2. تحديث `.env`

```env
BROADCAST_CONNECTION=pusher
PUSHER_APP_ID=your-app-id
PUSHER_APP_KEY=your-app-key
PUSHER_APP_SECRET=your-app-secret
PUSHER_APP_CLUSTER=mt1
QUEUE_CONNECTION=database
```

### 3. تشغيل Queue Worker

```bash
php artisan queue:work
```

### 4. الاختبار

```bash
php artisan notification:test
```

## 💻 الاستخدام

### في Backend

```php
use App\Models\Notification;

// إنشاء إشعار (يتم إرساله تلقائياً عبر Pusher)
Notification::create([
    'user_id' => 1,
    'type' => 'offer_received',
    'title' => 'عرض جديد',
    'message' => 'لقد تلقيت عرضاً جديداً',
    'data' => ['service_id' => 123]
]);
```

### في Frontend

```javascript
import Echo from 'laravel-echo';
import Pusher from 'pusher-js';

window.Pusher = Pusher;
window.Echo = new Echo({
    broadcaster: 'pusher',
    key: 'YOUR_PUSHER_APP_KEY',
    cluster: 'YOUR_CLUSTER',
    authEndpoint: '/api/broadcasting/auth',
    auth: {
        headers: {
            'Authorization': 'Bearer ' + apiToken
        }
    }
});

const channel = Echo.private(`user.${userId}`);
channel.listen('.notification.sent', (data) => {
    console.log('إشعار جديد:', data);
});
```

## 📚 الوثائق

- **دليل شامل**: راجع `PUSHER_SETUP.md`
- **دليل سريع**: راجع `REALTIME_NOTIFICATIONS_QUICK_START.md`
- **إعداد كامل**: راجع `PUSHER_COMPLETE_SETUP.md`
- **أمثلة Frontend**: راجع `resources/js/pusher-notifications-example.js`

## 🧪 الاختبار

### Command Line

```bash
php artisan notification:test
php artisan notification:test 1 --type=offer_received --title="عرض جديد"
```

### Tinker

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

## 🔧 الإعدادات

### Queue Connection

- **Development**: `QUEUE_CONNECTION=database`
- **Production**: `QUEUE_CONNECTION=redis` (مُوصى به)

### Broadcasting

- **Default**: `BROADCAST_CONNECTION=pusher`
- **Alternatives**: `reverb`, `ably`, `redis`, `log`, `null`

## 📊 Monitoring

### Queue

```bash
php artisan queue:monitor
php artisan queue:failed
```

### Pusher Dashboard

- اذهب إلى [Pusher Dashboard](https://dashboard.pusher.com)
- راقب Messages و Connections

## 🐛 Troubleshooting

### الإشعارات لا تصل

1. تحقق من Queue Worker: `php artisan queue:work`
2. تحقق من Broadcasting Config: `php artisan config:clear`
3. تحقق من Pusher Dashboard
4. راجع Logs: `tail -f storage/logs/laravel.log`

### Authentication فاشل

1. تحقق من Route: `php artisan route:list | grep broadcasting`
2. تحقق من API Token في Frontend
3. تحقق من Middleware `api.token`

## ✅ Production Checklist

- [ ] تحديث `.env` ببيانات Pusher الصحيحة
- [ ] تعيين `QUEUE_CONNECTION=redis`
- [ ] إعداد Supervisor لـ Queue Worker
- [ ] تفعيل SSL/TLS
- [ ] إضافة error handling في Frontend
- [ ] إضافة reconnection logic
- [ ] مراقبة Pusher Dashboard

## 📞 الدعم

- [Pusher Documentation](https://pusher.com/docs)
- [Laravel Broadcasting](https://laravel.com/docs/broadcasting)
- راجع ملفات التوثيق في المشروع

---

**جاهز للاستخدام! 🎉**
