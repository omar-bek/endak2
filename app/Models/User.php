<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'is_admin',
        'user_type',
        'phone',
        'phone_verified_at',
        'bio',
        'avatar',
        'image',
        'terms_accepted_at',
        'email_verified_at',
        'device_token',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'api_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_admin' => 'boolean',
            'terms_accepted_at' => 'datetime',
        ];
    }

    // الحصول على اسم الدور
    public function getRoleNameAttribute()
    {
        switch ($this->user_type) {
            case 'admin':
                return 'مدير';
            case 'customer':
                return 'مستخدم عادي';
            case 'provider':
                return 'مزود خدمة';
            default:
                return 'غير محدد';
        }
    }

    // العلاقة مع الخدمات
    public function services()
    {
        return $this->hasMany(Service::class);
    }

    // العلاقة مع الطلبات
    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    // العلاقة مع العروض المقدمة
    public function offers()
    {
        return $this->hasMany(ServiceOffer::class, 'provider_id');
    }

    // العلاقة مع الإشعارات
    public function notifications()
    {
        return $this->hasMany(\App\Models\Notification::class);
    }

    // الحصول على الإشعارات غير المقروءة
    public function getUnreadNotificationsAttribute()
    {
        return $this->notifications()->whereNull('read_at')->orderBy('created_at', 'desc')->get();
    }

    // الحصول على عدد الإشعارات غير المقروءة
    public function getUnreadNotificationsCountAttribute()
    {
        return $this->notifications()->whereNull('read_at')->count();
    }

    // التحقق من كون المستخدم مدير
    public function isAdmin()
    {
        return $this->is_admin;
    }

    // التحقق من كون المستخدم مزود خدمة
    public function isProvider()
    {
        return $this->user_type === 'provider';
    }

    // العلاقة مع ملف مزود الخدمة
    public function providerProfile()
    {
        return $this->hasOne(ProviderProfile::class);
    }

    // العلاقة مع أقسام مزود الخدمة
    public function providerCategories()
    {
        return $this->hasMany(ProviderCategory::class);
    }

    // العلاقة مع مدن مزود الخدمة
    public function providerCities()
    {
        return $this->hasMany(ProviderCity::class);
    }

    // الحصول على الأقسام النشطة لمزود الخدمة
    public function activeProviderCategories()
    {
        return $this->providerCategories()->where('is_active', true);
    }

    // الحصول على المدن النشطة لمزود الخدمة
    public function activeProviderCities()
    {
        return $this->providerCities()->where('is_active', true);
    }

    // التحقق من اكتمال ملف مزود الخدمة
    public function hasCompleteProviderProfile()
    {
        if (!$this->isProvider()) {
            return false;
        }

        $profile = $this->providerProfile;
        return $profile && $profile->isProfileComplete();
    }

    // الحصول على الحد الأقصى للأقسام
    public function getMaxCategoriesAttribute()
    {
        if (!$this->isProvider()) {
            return 0;
        }

        $profile = $this->providerProfile;
        return $profile ? $profile->max_categories : SystemSetting::get('provider_max_categories', 3);
    }

    // الحصول على الحد الأقصى للمدن
    public function getMaxCitiesAttribute()
    {
        if (!$this->isProvider()) {
            return 0;
        }

        $profile = $this->providerProfile;
        return $profile ? $profile->max_cities : SystemSetting::get('provider_max_cities', 5);
    }

    // التحقق من كون المستخدم عميل
    public function isCustomer()
    {
        return $this->user_type === 'customer';
    }

    // الحصول على الصورة الشخصية
    public function getAvatarUrlAttribute()
    {
        if ($this->avatar) {
            return asset('storage/' . $this->avatar);
        }
        return "https://ui-avatars.com/api/?name=" . urlencode($this->name) . "&background=667eea&color=fff";
    }

    // التحقق من كون المستخدم متصل
    public function isOnline()
    {
        // يمكن تحسين هذا ليتحقق من آخر نشاط للمستخدم
        // حالياً نعتبر المستخدم متصل إذا كان آخر نشاط له في آخر 5 دقائق
        return $this->updated_at->diffInMinutes(now()) <= 5;
    }

    // العلاقة مع الرسائل المرسلة
    public function sentMessages()
    {
        return $this->hasMany(Message::class, 'sender_id');
    }

    // العلاقة مع الرسائل المستلمة
    public function receivedMessages()
    {
        return $this->hasMany(Message::class, 'receiver_id');
    }

    // الحصول على عدد الرسائل غير المقروءة
    public function getUnreadMessagesCountAttribute()
    {
        return $this->receivedMessages()
            ->where('is_read', false)
            ->where('is_deleted', false)
            ->count();
    }

    // البحث بالهاتف
    public function findForPassport($phone)
    {
        return $this->where('phone', $phone)->first();
    }

    // الحصول على معرف المستخدم للمصادقة
    public function getAuthIdentifierName()
    {
        return 'id';
    }
    public function hasCompletedProfile(): bool
    {
        return (bool) ($this->user_type && $this->terms_accepted_at);
    }

    public function generateApiToken(): string
    {
        $plainTextToken = Str::random(64);
        $this->forceFill([
            'api_token' => hash('sha256', $plainTextToken),
        ])->save();

        return $plainTextToken;
    }

    public function clearApiToken(): void
    {
        if ($this->api_token) {
            $this->forceFill([
                'api_token' => null,
            ])->save();
        }
    }
}
