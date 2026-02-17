# إصلاح مشكلة 403 Forbidden على الـ Server

## المشكلة
الـ API endpoints تعمل على localhost لكن تحصل على 403 Forbidden على الـ server.

## الحلول المطبقة

### 1. تحسين ملف `.htaccess`
تم تحديث ملف `public/.htaccess` لدعم API بشكل أفضل:
- إضافة قواعد خاصة لـ API routes
- إضافة CORS headers
- تحسين معالجة trailing slashes للـ API

### 2. التحقق من الإعدادات

#### أ. التحقق من mod_rewrite
تأكد من أن `mod_rewrite` مفعل في Apache:
```bash
# على Ubuntu/Debian
sudo a2enmod rewrite
sudo systemctl restart apache2

# على CentOS/RHEL
# تأكد من وجود LoadModule rewrite_module في httpd.conf
```

#### ب. التحقق من إعدادات Apache
تأكد من أن الـ VirtualHost يسمح بـ `.htaccess`:
```apache
<Directory /path/to/your/project/public>
    Options Indexes FollowSymLinks
    AllowOverride All
    Require all granted
</Directory>
```

#### ج. التحقق من Nginx (إذا كنت تستخدم Nginx)
أضف هذه القواعد في إعدادات Nginx:
```nginx
location /api {
    try_files $uri $uri/ /index.php?$query_string;
}

location ~ \.php$ {
    fastcgi_pass unix:/var/run/php/php8.1-fpm.sock;
    fastcgi_index index.php;
    fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
    include fastcgi_params;
}
```

### 3. التحقق من Permissions
تأكد من أن الملفات والمجلدات لها الصلاحيات الصحيحة:
```bash
# على Linux
chmod -R 755 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache
```

### 4. التحقق من Laravel Configuration
تأكد من أن `APP_ENV` و `APP_DEBUG` صحيحة في `.env`:
```env
APP_ENV=production
APP_DEBUG=false
```

### 5. اختبار الـ API
بعد تطبيق الحلول، اختبر الـ endpoints:
```bash
# Test cities endpoint
curl -X GET "https://endak.net/api/v1/cities"

# Test search endpoint
curl -X GET "https://endak.net/api/v1/services/search?category=1"
```

## حلول إضافية

### إذا استمرت المشكلة:

1. **التحقق من Server Logs**
   ```bash
   # Apache error log
   tail -f /var/log/apache2/error.log
   
   # Laravel log
   tail -f storage/logs/laravel.log
   ```

2. **التحقق من Firewall/Security Rules**
   - تأكد من أن الـ server لا يمنع الوصول إلى `/api/*`
   - تحقق من إعدادات ModSecurity إذا كان مفعل

3. **التحقق من PHP Configuration**
   - تأكد من أن `allow_url_fopen` مفعل
   - تحقق من `max_execution_time` و `memory_limit`

4. **التحقق من .env File**
   - تأكد من أن `APP_URL` صحيح
   - تحقق من إعدادات الـ database

## ملاحظات مهمة

- الـ API endpoints يجب أن تكون متاحة بدون authentication (public routes)
- تأكد من أن الـ routes موجودة في `routes/api.php`
- استخدم `php artisan route:list` للتحقق من الـ routes

## الدعم

إذا استمرت المشكلة، تحقق من:
1. Server logs
2. Laravel logs
3. Apache/Nginx configuration
4. Contact your hosting provider
