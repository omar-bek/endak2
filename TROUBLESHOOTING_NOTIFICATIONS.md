# 🔧 حل مشاكل الإشعارات Realtime

## المشكلة: الإشعارات لا تظهر realtime

### ✅ تم إصلاح المشكلة!

تم إضافة إنشاء الإشعارات في `ApiServiceOfferController` عند:
- ✅ تقديم عرض جديد (`store`)
- ✅ قبول العرض (`accept`)
- ✅ رفض العرض (`reject`)

## 🔍 خطوات التحقق

### 1. تحقق من Queue Worker

الإشعارات تحتاج Queue Worker ليعمل:

```bash
php artisan queue:work
```

**مهم جداً**: يجب أن يكون Queue Worker يعمل في terminal منفصل!

### 2. تحقق من إعدادات Pusher في `.env`

```env
BROADCAST_CONNECTION=pusher
PUSHER_APP_ID=your-app-id
PUSHER_APP_KEY=your-app-key
PUSHER_APP_SECRET=your-app-secret
PUSHER_APP_CLUSTER=mt1
QUEUE_CONNECTION=database
```

### 3. تحقق من أن Broadcasting يعمل

```bash
php artisan config:clear
php artisan config:cache
```

### 4. اختبار الإشعارات

```bash
php artisan notification:test
```

أو من Tinker:

```bash
php artisan tinker
```

```php
use App\Models\Notification;
Notification::create([
    'user_id' => 1,
    'type' => 'offer_received',
    'title' => 'اختبار',
    'message' => 'هذا إشعار تجريبي'
]);
```

### 5. تحقق من Logs

```bash
tail -f storage/logs/laravel.log
```

ابحث عن:
- `API Notification sent for new offer`
- `API Notification sent for accepted offer`
- `API Notification sent for rejected offer`

### 6. تحقق من Pusher Dashboard

1. اذهب إلى [Pusher Dashboard](https://dashboard.pusher.com)
2. افتح Debug Console
3. اختر channel: `private-user.{userId}`
4. Event name: `notification.sent`
5. راقب الرسائل الواردة

### 7. تحقق من Frontend

تأكد من:
- Laravel Echo و Pusher JS مثبتين
- Echo متصل بشكل صحيح
- الاشتراك في channel الصحيح: `private-user.{userId}`
- الاستماع للحدث: `.notification.sent`

## 🐛 مشاكل شائعة وحلولها

### المشكلة: Queue Worker لا يعمل

**الحل:**
```bash
# تشغيل Queue Worker
php artisan queue:work

# أو في production
php artisan queue:work --daemon
```

### المشكلة: Broadcasting Connection = null

**الحل:**
```env
BROADCAST_CONNECTION=pusher
```

ثم:
```bash
php artisan config:clear
```

### المشكلة: Authentication فاشل

**الحل:**
1. تحقق من Route:
```bash
php artisan route:list | grep broadcasting
```

2. تحقق من API Token في Frontend
3. تحقق من Middleware `api.token`

### المشكلة: الإشعارات تُنشأ لكن لا تُرسل

**الحل:**
1. تحقق من Queue:
```bash
php artisan queue:work --verbose
```

2. تحقق من Failed Jobs:
```bash
php artisan queue:failed
```

3. تحقق من Logs:
```bash
tail -f storage/logs/laravel.log
```

### المشكلة: Event لا يُرسل

**الحل:**
1. تحقق من `app/Models/Notification.php`:
```php
protected $dispatchesEvents = [
    'created' => NotificationSent::class,
];
```

2. تحقق من `app/Events/NotificationSent.php`:
```php
class NotificationSent implements ShouldBroadcast
```

3. تحقق من Broadcasting Config:
```bash
php artisan config:show broadcasting
```

## 📊 Monitoring

### مراقبة Queue

```bash
# عرض عدد jobs
php artisan queue:monitor

# عرض failed jobs
php artisan queue:failed

# إعادة محاولة failed jobs
php artisan queue:retry all
```

### مراقبة Pusher

- اذهب إلى Pusher Dashboard
- تحقق من Metrics
- راقب Messages و Connections
- استخدم Debug Console للاختبار

## ✅ Checklist للتحقق

- [ ] Queue Worker يعمل (`php artisan queue:work`)
- [ ] `.env` يحتوي على إعدادات Pusher الصحيحة
- [ ] `BROADCAST_CONNECTION=pusher`
- [ ] `QUEUE_CONNECTION=database` أو `redis`
- [ ] Broadcasting Route موجود (`/api/broadcasting/auth`)
- [ ] Notification Model يحتوي على `$dispatchesEvents`
- [ ] Event `NotificationSent` موجود ويستخدم `ShouldBroadcast`
- [ ] Channel Authorization يعمل في `routes/channels.php`
- [ ] Frontend متصل بـ Pusher بشكل صحيح
- [ ] Frontend يشتغل في channel الصحيح: `private-user.{userId}`
- [ ] Frontend يستمع للحدث: `.notification.sent`

## 🧪 اختبار شامل

### 1. اختبار من Backend

```bash
php artisan notification:test 1 --type=offer_received --title="عرض جديد" --message="اختبار"
```

### 2. اختبار من API

```bash
# تقديم عرض جديد
curl -X POST http://localhost:8000/api/v1/services/1/offers \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -H "Content-Type: application/json" \
  -d '{"price": 100, "notes": "اختبار"}'
```

### 3. اختبار من Frontend

افتح Console في المتصفح وتحقق من:
- Echo متصل
- Channel مشترك
- Event مستلم

## 📞 إذا استمرت المشكلة

1. راجع `PUSHER_SETUP.md` للدليل الشامل
2. راجع `PUSHER_COMPLETE_SETUP.md` لإعداد كامل
3. تحقق من [Pusher Documentation](https://pusher.com/docs)
4. تحقق من [Laravel Broadcasting](https://laravel.com/docs/broadcasting)

---

**تم إصلاح المشكلة! الآن الإشعارات يجب أن تعمل بشكل صحيح.** ✅
