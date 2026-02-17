# إصلاح مشكلة الـ API على السيرفر

## المشكلة
الـ API يعمل على الـ local لكن لا يعمل على السيرفر (endak.net).

## الحلول المطلوبة على السيرفر

### 1. تنظيف جميع الـ Cache
قم بتشغيل هذه الأوامر على السيرفر:

```bash
php artisan route:clear
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan optimize:clear
```

### 2. إعادة بناء الـ Cache (اختياري - للإنتاج)
```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

### 3. التحقق من الـ .htaccess
تأكد من أن ملف `.htaccess` في مجلد `public` يحتوي على:

```apache
<IfModule mod_rewrite.c>
    <IfModule mod_negotiation.c>
        Options -MultiViews -Indexes
    </IfModule>

    RewriteEngine On

    # Handle Authorization Header
    RewriteCond %{HTTP:Authorization} .
    RewriteRule .* - [E=HTTP_AUTHORIZATION:%{HTTP:Authorization}]

    # Redirect Trailing Slashes If Not A Folder...
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteCond %{REQUEST_URI} (.+)/$
    RewriteRule ^ %1 [L,R=301]

    # Send Requests To Front Controller...
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteRule ^ index.php [L]
</IfModule>
```

### 4. التحقق من إعدادات الـ Server
تأكد من:
- PHP version >= 8.2
- mod_rewrite مفعل
- جميع الـ extensions المطلوبة مثبتة

### 5. التحقق من الـ Permissions
```bash
chmod -R 755 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache
```

### 6. التحقق من الـ .env
تأكد من أن ملف `.env` على السيرفر يحتوي على:
```
APP_ENV=production
APP_DEBUG=false
APP_URL=https://endak.net

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=your_database
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

### 7. اختبار الـ API
بعد تطبيق جميع الخطوات، اختبر الـ API:

```bash
# GET Request
curl -X GET https://endak.net/api/login

# POST Request
curl -X POST https://endak.net/api/login \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{"email":"user@example.com","password":"password123"}'
```

## استكشاف الأخطاء

### إذا كان الـ response HTML بدلاً من JSON:
1. تحقق من أن `ForceJsonResponse` middleware يعمل
2. تحقق من الـ headers في الطلب
3. تأكد من تنظيف الـ cache

### إذا كان الـ route غير موجود:
1. قم بتشغيل `php artisan route:clear`
2. تحقق من ملف `routes/api.php`
3. تأكد من أن الـ routes مسجلة بشكل صحيح

### إذا كان تسجيل الدخول يفشل:
1. تحقق من وجود المستخدم في قاعدة البيانات
2. تحقق من أن كلمة المرور محفوظة بشكل hashed
3. تحقق من الـ logs في `storage/logs/laravel.log`

## أوامر سريعة للنسخ واللصق

```bash
# تنظيف شامل
php artisan optimize:clear

# إعادة بناء (للإنتاج)
php artisan config:cache && php artisan route:cache && php artisan view:cache

# التحقق من الـ routes
php artisan route:list --path=api/login
```


