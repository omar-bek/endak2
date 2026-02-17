# نظام إدارة الأقسام والمدن - Endak

## 🎯 نظرة عامة

نظام متقدم لإدارة العلاقة بين الأقسام والمدن، يتيح للمدير التحكم في المدن المتاحة لكل قسم بشكل مرن ومتقدم.

## 🚀 المميزات الرئيسية

### 1. **إدارة الأقسام والمدن**
- عرض جميع الأقسام المتاحة
- عرض جميع المدن المتاحة
- إحصائيات شاملة للنظام

### 2. **التحكم في المدن حسب القسم**
- فتح أي قسم لرؤية المدن المتاحة فيه
- اختيار المدن المرغوبة للقسم
- تفعيل/تعطيل كل مدينة في القسم بشكل منفصل

### 3. **واجهة إدارية متقدمة**
- تصميم عصري وسهل الاستخدام
- أزرار تفاعلية للتحكم السريع
- إحصائيات مباشرة ومحدثة

### 4. **إحصائيات شاملة**
- إحصائيات الأقسام والمدن
- الأقسام الأكثر نشاطاً
- المدن الأكثر استخداماً
- رسوم بيانية تفاعلية

## 🛠️ الملفات المضافة

### Controllers
- `app/Http/Controllers/Admin/CategoryCityController.php`

### Models (محدثة)
- `app/Models/Category.php` - إضافة علاقات المدن
- `app/Models/City.php` - إضافة علاقات الأقسام

### Views
- `resources/views/admin/category-cities/index.blade.php` - الصفحة الرئيسية
- `resources/views/admin/category-cities/show.blade.php` - صفحة تفاصيل القسم
- `resources/views/admin/category-cities/statistics.blade.php` - صفحة الإحصائيات

### Database
- `database/migrations/2025_09_01_120652_create_category_cities_table.php`

### Routes
- إضافة routes جديدة في `routes/web.php`

## 📊 قاعدة البيانات

### جدول `category_cities`
```sql
CREATE TABLE category_cities (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    category_id BIGINT UNSIGNED NOT NULL,
    city_id BIGINT UNSIGNED NOT NULL,
    is_active BOOLEAN DEFAULT TRUE,
    sort_order INT DEFAULT 0,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    UNIQUE KEY unique_category_city (category_id, city_id),
    INDEX idx_category_active (category_id, is_active),
    INDEX idx_city_active (city_id, is_active),
    INDEX idx_sort_order (sort_order),
    FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE CASCADE,
    FOREIGN KEY (city_id) REFERENCES cities(id) ON DELETE CASCADE
);
```

## 🔧 العلاقات في Models

### Category Model
```php
// العلاقة مع المدن
public function cities()
{
    return $this->belongsToMany(City::class, 'category_cities')
                ->withPivot('is_active', 'sort_order')
                ->withTimestamps();
}

// الحصول على المدن المفعلة فقط
public function activeCities()
{
    return $this->belongsToMany(City::class, 'category_cities')
                ->wherePivot('is_active', true)
                ->withPivot('sort_order')
                ->orderBy('pivot_sort_order')
                ->orderBy('cities.name');
}
```

### City Model
```php
// العلاقة مع الأقسام
public function categories()
{
    return $this->belongsToMany(Category::class, 'category_cities')
                ->withPivot('is_active', 'sort_order')
                ->withTimestamps();
}

// الحصول على الأقسام المفعلة فقط
public function activeCategories()
{
    return $this->belongsToMany(Category::class, 'category_cities')
                ->wherePivot('is_active', true)
                ->withPivot('sort_order')
                ->orderBy('pivot_sort_order')
                ->orderBy('categories.name');
}
```

## 🎨 الواجهات

### 1. **الصفحة الرئيسية** (`/admin/category-cities`)
- عرض الأقسام المتاحة مع عدد الأقسام الفرعية
- عرض المدن المتاحة مع حالتها
- رابط للإحصائيات
- أزرار إدارة لكل قسم

### 2. **صفحة تفاصيل القسم** (`/admin/category-cities/{id}`)
- معلومات القسم
- إحصائيات المدن
- قائمة المدن المتاحة مع checkboxes
- المدن المفعلة حالياً مع أزرار التحكم
- أزرار تحديد الكل/إلغاء التحديد

### 3. **صفحة الإحصائيات** (`/admin/category-cities/statistics`)
- إحصائيات عامة (الأقسام، المدن، العلاقات)
- الأقسام الأكثر نشاطاً
- المدن الأكثر استخداماً
- رسوم بيانية تفاعلية

## 🔄 العمليات المتاحة

### 1. **عرض الأقسام والمدن**
```php
// الحصول على الأقسام الرئيسية
$categories = Category::whereNull('parent_id')
    ->with(['children' => function($query) {
        $query->orderBy('sort_order');
    }])
    ->orderBy('sort_order')
    ->get();

// الحصول على المدن
$cities = City::orderBy('sort_order')->orderBy('name')->get();
```

### 2. **تحديث المدن في قسم**
```php
// حذف جميع المدن المرتبطة
DB::table('category_cities')->where('category_id', $categoryId)->delete();

// إضافة المدن المحددة
foreach ($selectedCities as $cityId) {
    DB::table('category_cities')->insert([
        'category_id' => $categoryId,
        'city_id' => $cityId,
        'is_active' => true,
        'created_at' => now(),
        'updated_at' => now()
    ]);
}
```

### 3. **تفعيل/تعطيل مدينة**
```php
DB::table('category_cities')
    ->where('category_id', $categoryId)
    ->where('city_id', $cityId)
    ->update([
        'is_active' => $isActive,
        'updated_at' => now()
    ]);
```

### 4. **الحصول على المدن حسب القسم**
```php
$cities = DB::table('category_cities')
    ->join('cities', 'category_cities.city_id', '=', 'cities.id')
    ->where('category_cities.category_id', $categoryId)
    ->where('category_cities.is_active', true)
    ->select('cities.*', 'category_cities.is_active as category_active')
    ->orderBy('cities.sort_order')
    ->orderBy('cities.name')
    ->get();
```

## 🎯 Routes المتاحة

```php
// إدارة الأقسام والمدن
Route::get('/category-cities', [CategoryCityController::class, 'index'])->name('category-cities.index');
Route::get('/category-cities/statistics', [CategoryCityController::class, 'statistics'])->name('category-cities.statistics');
Route::get('/category-cities/{category}', [CategoryCityController::class, 'show'])->name('category-cities.show');
Route::put('/category-cities/{category}/cities', [CategoryCityController::class, 'updateCities'])->name('category-cities.update-cities');
Route::post('/category-cities/{category}/toggle-city/{city}', [CategoryCityController::class, 'toggleCity'])->name('category-cities.toggle-city');
Route::get('/category-cities/{category}/cities', [CategoryCityController::class, 'getCitiesByCategory'])->name('category-cities.get-cities');
Route::post('/category-cities/{category}/add-city', [CategoryCityController::class, 'addCityToCategory'])->name('category-cities.add-city');
Route::delete('/category-cities/{category}/remove-city/{city}', [CategoryCityController::class, 'removeCityFromCategory'])->name('category-cities.remove-city');
```

## 🎨 JavaScript Features

### 1. **تفعيل/تعطيل مدينة**
```javascript
$(document).on('click', '.toggle-city-btn', function() {
    const cityId = $(this).data('city-id');
    const isActive = $(this).data('active');
    
    $.ajax({
        url: `/admin/category-cities/${categoryId}/toggle-city/${cityId}`,
        method: 'POST',
        data: {
            is_active: !isActive,
            _token: '{{ csrf_token() }}'
        },
        success: function(response) {
            // تحديث الواجهة
        }
    });
});
```

### 2. **إزالة مدينة**
```javascript
$(document).on('click', '.remove-city-btn', function() {
    const cityId = $(this).data('city-id');
    
    if (confirm('هل أنت متأكد من إزالة هذه المدينة من القسم؟')) {
        $.ajax({
            url: `/admin/category-cities/${categoryId}/remove-city/${cityId}`,
            method: 'DELETE',
            data: { _token: '{{ csrf_token() }}' },
            success: function(response) {
                // إزالة العنصر من الواجهة
            }
        });
    }
});
```

### 3. **تحديد الكل/إلغاء التحديد**
```javascript
function selectAll() {
    $('.city-checkbox').prop('checked', true);
}

function deselectAll() {
    $('.city-checkbox').prop('checked', false);
}
```

## 📊 الإحصائيات

### 1. **إحصائيات عامة**
- إجمالي الأقسام
- الأقسام المفعلة
- إجمالي المدن
- المدن المفعلة
- إجمالي العلاقات
- العلاقات المفعلة

### 2. **الأقسام الأكثر نشاطاً**
```php
$topCategories = DB::table('category_cities')
    ->join('categories', 'category_cities.category_id', '=', 'categories.id')
    ->select('categories.name', DB::raw('count(*) as city_count'))
    ->groupBy('categories.id', 'categories.name')
    ->orderBy('city_count', 'desc')
    ->limit(10)
    ->get();
```

### 3. **المدن الأكثر استخداماً**
```php
$topCities = DB::table('category_cities')
    ->join('cities', 'category_cities.city_id', '=', 'cities.id')
    ->select('cities.name', DB::raw('count(*) as category_count'))
    ->groupBy('cities.id', 'cities.name')
    ->orderBy('category_count', 'desc')
    ->limit(10)
    ->get();
```

## 🚀 كيفية الاستخدام

### 1. **الوصول للنظام**
- تسجيل الدخول كمدير
- الذهاب إلى "الأقسام والمدن" من القائمة الجانبية

### 2. **إدارة قسم معين**
- الضغط على "إدارة المدن" بجانب أي قسم
- تحديد المدن المرغوبة من القائمة
- الضغط على "حفظ التغييرات"

### 3. **التحكم الفردي**
- استخدام أزرار التفعيل/التعطيل لكل مدينة
- استخدام أزرار الإزالة للحذف السريع

### 4. **مشاهدة الإحصائيات**
- الضغط على "الإحصائيات" من الصفحة الرئيسية
- مراجعة الرسوم البيانية والجداول

## 🎉 النتيجة النهائية

**نظام متكامل لإدارة الأقسام والمدن يتيح:**

1. **التحكم المرن** في المدن المتاحة لكل قسم
2. **واجهة سهلة الاستخدام** مع أزرار تفاعلية
3. **إحصائيات شاملة** مع رسوم بيانية
4. **أداء عالي** مع فهارس محسنة
5. **مرونة كاملة** في إدارة العلاقات

**النظام جاهز للاستخدام! 🚀**

---

**تم التطوير بواسطة فريق Endak**  
**تاريخ التطوير**: 1 سبتمبر 2025  
**الإصدار**: 1.0.0
