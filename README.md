# Endak - منصة الخدمات

منصة خدمات شاملة مشابهة لـ endak.net تتيح للمستخدمين عرض وطلب الخدمات المختلفة.

## المميزات

### للمستخدمين
- تصفح الأقسام والخدمات المختلفة
- البحث في الخدمات
- عرض تفاصيل الخدمات
- تقييم الخدمات
- طلب الخدمات

### لمزودي الخدمات
- إضافة خدمات جديدة
- إدارة الخدمات
- متابعة الطلبات
- تحديث معلومات الخدمات

### للمديرين
- لوحة إدارة شاملة
- إدارة الأقسام والخدمات
- إدارة المستخدمين
- إحصائيات مفصلة
- إعدادات النظام

## التقنيات المستخدمة

- **Backend**: Laravel 11
- **Frontend**: Bootstrap 5 (RTL)
- **Database**: MySQL/SQLite
- **Icons**: Font Awesome
- **Fonts**: Cairo (Google Fonts)

## متطلبات النظام

- PHP 8.2 أو أحدث
- Composer
- Node.js & NPM (اختياري)
- MySQL أو SQLite

## التثبيت

### 1. استنساخ المشروع
```bash
git clone <repository-url>
cd myendak
```

### 2. تثبيت التبعيات
```bash
composer install
```

### 3. إعداد البيئة
```bash
cp .env.example .env
php artisan key:generate
```

### 4. إعداد قاعدة البيانات
```bash
# تعديل ملف .env لإعدادات قاعدة البيانات
php artisan migrate
php artisan db:seed
```

### 5. إنشاء رابط التخزين
```bash
php artisan storage:link
```

### 6. تشغيل المشروع
```bash
php artisan serve
```

## البيانات التجريبية

بعد تشغيل الـ seeder، ستجد:

### المستخدمين
- **مدير النظام**: admin@endak.com / password
- **مستخدم عادي**: user@endak.com / password

### الأقسام
- الخدمات المنزلية (مع أقسام فرعية)
- الخدمات التعليمية
- الخدمات الصحية
- الخدمات التقنية (مع أقسام فرعية)
- الخدمات التجميلية
- الخدمات الرياضية

## هيكل المشروع

```
myendak/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── CategoryController.php
│   │   │   ├── ServiceController.php
│   │   │   ├── HomeController.php
│   │   │   └── Admin/
│   │   │       ├── DashboardController.php
│   │   │       └── CategoryController.php
│   │   └── Middleware/
│   │       └── AdminMiddleware.php
│   └── Models/
│       ├── Category.php
│       ├── Service.php
│       ├── Order.php
│       └── User.php
├── database/
│   ├── migrations/
│   └── seeders/
├── resources/
│   └── views/
│       ├── layouts/
│       │   ├── app.blade.php
│       │   └── admin.blade.php
│       ├── home.blade.php
│       ├── categories/
│       ├── services/
│       ├── admin/
│       └── contact.blade.php
└── routes/
    └── web.php
```

## الصفحات الرئيسية

### الواجهة الأمامية
- `/` - الصفحة الرئيسية
- `/categories` - جميع الأقسام
- `/categories/{slug}` - قسم معين
- `/services` - جميع الخدمات
- `/services/{slug}` - خدمة معينة
- `/contact` - اتصل بنا

### لوحة الإدارة
- `/admin/dashboard` - لوحة التحكم
- `/admin/categories` - إدارة الأقسام
- `/admin/categories/create` - إضافة قسم جديد
- `/admin/categories/{id}/edit` - تعديل قسم

## المميزات التقنية

### Responsive Design
- تصميم متجاوب يعمل على جميع الأجهزة
- دعم الهواتف المحمولة
- واجهة مستخدم سهلة الاستخدام

### SEO Friendly
- URLs نظيفة
- Meta tags قابلة للتخصيص
- Structured data

### Security
- CSRF protection
- Input validation
- SQL injection protection
- XSS protection

### Performance
- Database indexing
- Eager loading
- Image optimization
- Caching ready

## المساهمة

1. Fork المشروع
2. إنشاء branch جديد (`git checkout -b feature/AmazingFeature`)
3. Commit التغييرات (`git commit -m 'Add some AmazingFeature'`)
4. Push إلى Branch (`git push origin feature/AmazingFeature`)
5. فتح Pull Request

## الترخيص

هذا المشروع مرخص تحت رخصة MIT. راجع ملف `LICENSE` للتفاصيل.

## الدعم

للدعم والاستفسارات:
- البريد الإلكتروني: info@endak.com
- الهاتف: +966 50 123 4567

## التحديثات القادمة

- [ ] نظام الدفع الإلكتروني
- [ ] تطبيق الهاتف المحمول
- [ ] نظام الإشعارات
- [ ] نظام المحادثات
- [ ] نظام التقييمات المتقدم
- [ ] API للمطورين
- [ ] نظام العضويات
- [ ] نظام العمولات
