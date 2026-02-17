# ✅ إعداد كامل: Realtime Notifications مع Pusher

## 📋 قائمة التحقق من الإعداد

### ✅ ما تم إنجازه تلقائياً:

1. ✅ **Event للإشعارات** - `app/Events/NotificationSent.php`
2. ✅ **Broadcasting Channel** - `routes/channels.php`
3. ✅ **Notification Model** - مرتبط مع Broadcasting
4. ✅ **Broadcasting Auth Route** - `routes/api.php`
5. ✅ **Queue Tables** - موجودة في migrations
6. ✅ **Scripts للإعداد** - `setup-pusher.sh` و `setup-pusher.bat`
7. ✅ **Command للاختبار** - `php artisan notification:test`

## 🚀 خطوات الإعداد السريعة

### الخطوة 1: إنشاء حساب Pusher

1. اذهب إلى [pusher.com](https://pusher.com)
2. سجل حساب جديد (مجاني)
3. أنشئ تطبيق جديد (Channels App)
4. احصل على:
   - `PUSHER_APP_ID`
   - `PUSHER_APP_KEY`
   - `PUSHER_APP_SECRET`
   - `PUSHER_APP_CLUSTER` (مثل: `mt1`, `eu`, `ap1`)

### الخطوة 2: تحديث ملف `.env`

أضف/حدث المتغيرات التالية في ملف `.env`:

```env
# Broadcasting Settings
BROADCAST_CONNECTION=pusher

# Pusher Settings
PUSHER_APP_ID=your-app-id
PUSHER_APP_KEY=your-app-key
PUSHER_APP_SECRET=your-app-secret
PUSHER_APP_CLUSTER=mt1
PUSHER_HOST=
PUSHER_PORT=443
PUSHER_SCHEME=https

# Queue Settings (مطلوب للـ Broadcasting)
QUEUE_CONNECTION=database
```

### الخطوة 3: تشغيل Migrations

```bash
# تأكد من وجود جدول jobs
php artisan migrate
```

### الخطوة 4: تشغيل Queue Worker

```bash
# في terminal منفصل
php artisan queue:work
```

أو في production باستخدام Supervisor:

```ini
[program:laravel-worker]
process_name=%(program_name)s_%(process_num)02d
command=php /path/to/artisan queue:work --sleep=3 --tries=3 --max-time=3600
autostart=true
autorestart=true
stopasgroup=true
killasgroup=true
user=www-data
numprocs=2
redirect_stderr=true
stdout_logfile=/path/to/worker.log
stopwaitsecs=3600
```

### الخطوة 5: الاختبار

#### اختبار من Command Line:

```bash
php artisan notification:test
```

أو مع خيارات:

```bash
php artisan notification:test 1 --type=offer_received --title="عرض جديد" --message="لقد تلقيت عرضاً جديداً"
```

#### اختبار من Tinker:

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
```

## 🔧 الإعدادات المتقدمة

### استخدام Redis بدلاً من Database

```env
QUEUE_CONNECTION=redis
```

ثم شغل:

```bash
php artisan queue:work redis
```

### إعدادات Pusher المتقدمة

```env
# للـ local development
PUSHER_HOST=127.0.0.1
PUSHER_PORT=6001
PUSHER_SCHEME=http

# للـ production
PUSHER_HOST=
PUSHER_PORT=443
PUSHER_SCHEME=https
```

## 📱 الاستخدام في Frontend

### 1. تثبيت المكتبات

```bash
npm install --save laravel-echo pusher-js
```

### 2. إعداد Laravel Echo

في `resources/js/bootstrap.js`:

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

### 3. الاستماع للإشعارات

```javascript
const userId = window.currentUser?.id || localStorage.getItem('user_id');

const channel = window.Echo.private(`user.${userId}`);

channel.listen('.notification.sent', (data) => {
    console.log('إشعار جديد:', data);
    // عرض الإشعار للمستخدم
    showNotification(data);
    // تحديث العداد
    updateUnreadCount(data.unread_count);
});
```

## 🧪 الاختبار الشامل

### 1. اختبار Broadcasting Connection

```bash
php artisan tinker
```

```php
use Illuminate\Support\Facades\Broadcast;

// اختبار channel
Broadcast::channel('user.1', function ($user, $userId) {
    return (int) $user->id === (int) $userId;
});
```

### 2. اختبار Event

```php
use App\Events\NotificationSent;
use App\Models\Notification;

$notification = Notification::find(1);
event(new NotificationSent($notification));
```

### 3. اختبار Queue

```bash
# التحقق من وجود jobs في queue
php artisan queue:work --once

# عرض failed jobs
php artisan queue:failed
```

## 🐛 Troubleshooting

### المشكلة: الإشعارات لا تصل

1. **تحقق من Queue Worker:**
   ```bash
   php artisan queue:work --verbose
   ```

2. **تحقق من Broadcasting Config:**
   ```bash
   php artisan config:clear
   php artisan config:cache
   ```

3. **تحقق من Pusher Dashboard:**
   - اذهب إلى Pusher Dashboard
   - تحقق من Debug Console
   - ابحث عن الأخطاء

4. **تحقق من Logs:**
   ```bash
   tail -f storage/logs/laravel.log
   ```

### المشكلة: Authentication فاشل

1. **تحقق من Route:**
   ```bash
   php artisan route:list | grep broadcasting
   ```

2. **تحقق من Middleware:**
   - تأكد من أن `api.token` middleware يعمل
   - تحقق من Authorization header

3. **اختبار Auth Endpoint:**
   ```bash
   curl -X POST http://localhost:8000/api/broadcasting/auth \
     -H "Authorization: Bearer YOUR_TOKEN" \
     -H "Content-Type: application/json" \
     -d '{"socket_id":"123.456","channel_name":"private-user.1"}'
   ```

### المشكلة: Queue لا يعمل

1. **إعادة تشغيل Queue:**
   ```bash
   php artisan queue:restart
   ```

2. **تحقق من Database:**
   ```sql
   SELECT * FROM jobs;
   SELECT * FROM failed_jobs;
   ```

3. **تحقق من Permissions:**
   ```bash
   chmod -R 775 storage
   chmod -R 775 bootstrap/cache
   ```

## 📊 Monitoring

### مراقبة Queue

```bash
# عرض عدد jobs في queue
php artisan queue:monitor

# عرض failed jobs
php artisan queue:failed-table
php artisan queue:retry all
```

### مراقبة Pusher

- اذهب إلى Pusher Dashboard
- تحقق من Metrics
- راقب Messages و Connections

## 🔒 Security

### 1. Channel Authorization

تأكد من أن `routes/channels.php` يتحقق من صلاحيات المستخدم:

```php
Broadcast::channel('user.{userId}', function ($user, $userId) {
    return (int) $user->id === (int) $userId;
});
```

### 2. API Token

تأكد من استخدام API token صحيح في Frontend:

```javascript
auth: {
    headers: {
        'Authorization': 'Bearer ' + apiToken
    }
}
```

## 📚 الملفات المهمة

- `app/Events/NotificationSent.php` - Event للإشعارات
- `app/Models/Notification.php` - Model مع Broadcasting
- `routes/channels.php` - تعريف Channels
- `routes/api.php` - Broadcasting Auth Route
- `config/broadcasting.php` - إعدادات Broadcasting
- `config/queue.php` - إعدادات Queue
- `app/Console/Commands/TestNotification.php` - Command للاختبار

## 🎯 Production Checklist

- [ ] تحديث `.env` ببيانات Pusher الصحيحة
- [ ] تعيين `QUEUE_CONNECTION` إلى `redis` أو `database`
- [ ] إعداد Supervisor لـ Queue Worker
- [ ] تفعيل SSL/TLS في Pusher
- [ ] إضافة error handling في Frontend
- [ ] إضافة reconnection logic
- [ ] مراقبة Pusher Dashboard
- [ ] إعداد monitoring للـ queue
- [ ] اختبار شامل قبل النشر

## 📞 الدعم

إذا واجهت أي مشاكل:

1. راجع `PUSHER_SETUP.md` للدليل المفصل
2. راجع `REALTIME_NOTIFICATIONS_QUICK_START.md` للدليل السريع
3. تحقق من [Pusher Documentation](https://pusher.com/docs)
4. تحقق من [Laravel Broadcasting](https://laravel.com/docs/broadcasting)

---

**تم الإعداد بنجاح! 🎉**
