@extends('layouts.app')

@php $seoNoindex = true; @endphp
@section('title', 'الملف الشخصي')

@section('content')
<style>
.ep-profile { min-height: 100vh; padding-bottom: 100px; }

/* Cover */
.ep-cover {
    position: relative;
    height: 220px;
    background: linear-gradient(135deg, var(--e-primary-dark), var(--e-primary), var(--e-primary-light));
    overflow: hidden;
}
.ep-cover::before {
    content: '';
    position: absolute;
    inset: 0;
    background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.05'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
}
.ep-cover::after {
    content: '';
    position: absolute;
    bottom: 0;
    left: 0;
    right: 0;
    height: 80px;
    background: linear-gradient(to top, var(--e-gray-50), transparent);
}

/* Avatar */
.ep-avatar-section {
    position: relative;
    margin-top: -75px;
    z-index: 2;
    text-align: center;
}
.ep-avatar-wrap { position: relative; display: inline-block; }
.ep-avatar-img {
    width: 140px;
    height: 140px;
    border-radius: var(--e-radius-full);
    object-fit: cover;
    border: 5px solid var(--e-white);
    box-shadow: var(--e-shadow-lg);
    background: var(--e-white);
}
.ep-avatar-fallback {
    width: 140px;
    height: 140px;
    border-radius: var(--e-radius-full);
    background: linear-gradient(135deg, var(--e-primary), var(--e-primary-light));
    color: var(--e-white);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 52px;
    font-weight: 700;
    border: 5px solid var(--e-white);
    box-shadow: var(--e-shadow-lg);
}
.ep-online-dot {
    position: absolute;
    bottom: 10px;
    right: 10px;
    width: 18px;
    height: 18px;
    border-radius: var(--e-radius-full);
    background: var(--e-success);
    border: 3px solid var(--e-white);
}
.ep-user-name {
    font-size: 1.6rem;
    font-weight: 700;
    color: var(--e-gray-800);
    margin: 14px 0 6px;
}
.ep-user-role {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 5px 16px;
    border-radius: var(--e-radius-full);
    font-size: 0.8rem;
    font-weight: 600;
}
.ep-role-provider { background: var(--e-primary-100); color: var(--e-primary); }
.ep-role-customer { background: var(--e-accent-50); color: var(--e-accent-dark); }
.ep-role-admin { background: var(--e-warning-light); color: #92400e; }
.ep-bio {
    color: var(--e-gray-500);
    font-size: 0.9rem;
    margin-top: 12px;
    max-width: 480px;
    margin-inline: auto;
    line-height: 1.8;
}
.ep-edit-btn {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    margin-top: 18px;
    padding: 10px 28px;
    border-radius: var(--e-radius-full);
    background: linear-gradient(135deg, var(--e-primary), var(--e-primary-light));
    color: var(--e-white);
    font-size: 0.88rem;
    font-weight: 600;
    font-family: var(--e-font);
    text-decoration: none;
    border: none;
    cursor: pointer;
    box-shadow: var(--e-shadow-primary);
    transition: var(--e-transition);
}
.ep-edit-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 24px rgba(47, 92, 105, 0.35);
    color: var(--e-white);
}

/* Info Grid */
.ep-info-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 16px;
    margin-top: 28px;
}
.ep-info-item {
    display: flex;
    align-items: center;
    gap: 14px;
    padding: 16px 20px;
    background: var(--e-white);
    border-radius: var(--e-radius-lg);
    border: 1px solid var(--e-gray-200);
    transition: var(--e-transition);
}
.ep-info-item:hover {
    box-shadow: var(--e-shadow);
    border-color: var(--e-gray-300);
}
.ep-info-icon {
    width: 42px;
    height: 42px;
    border-radius: var(--e-radius);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 16px;
    flex-shrink: 0;
}
.ep-info-icon.phone { background: var(--e-success-light); color: var(--e-success); }
.ep-info-icon.email { background: var(--e-info-light); color: var(--e-info); }
.ep-info-icon.date { background: var(--e-warning-light); color: var(--e-warning); }
.ep-info-label {
    font-size: 0.75rem;
    color: var(--e-gray-400);
    font-weight: 500;
    margin-bottom: 2px;
}
.ep-info-value {
    font-size: 0.9rem;
    color: var(--e-gray-700);
    font-weight: 600;
}

/* Alerts */
.ep-profile .alert {
    border-radius: var(--e-radius);
    border: none;
    font-size: 0.9rem;
}
.ep-profile .alert-success { background: var(--e-success-light); color: #065f46; }
.ep-profile .alert-danger { background: var(--e-danger-light); color: #991b1b; }

/* Cards */
.ep-card {
    background: var(--e-white);
    border-radius: var(--e-radius-lg);
    box-shadow: var(--e-shadow);
    border: 1px solid var(--e-gray-200);
    overflow: hidden;
    transition: var(--e-transition);
}
.ep-card:hover { box-shadow: var(--e-shadow-md); }
.ep-card-header {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 18px 24px;
    border-bottom: 1px solid var(--e-gray-100);
}
.ep-card-header-icon {
    width: 36px;
    height: 36px;
    border-radius: var(--e-radius);
    background: var(--e-primary-100);
    color: var(--e-primary);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 15px;
}
.ep-card-header h5 {
    margin: 0;
    font-size: 1rem;
    font-weight: 700;
    color: var(--e-gray-800);
}
.ep-card-body { padding: 24px; }

/* Stats */
.ep-stats {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(140px, 1fr));
    gap: 14px;
}
.ep-stat-card {
    text-align: center;
    padding: 22px 14px;
    border-radius: var(--e-radius);
    transition: var(--e-transition);
}
.ep-stat-card:hover { transform: translateY(-3px); }
.ep-stat-card.primary {
    background: linear-gradient(135deg, var(--e-primary), var(--e-primary-light));
    color: var(--e-white);
    box-shadow: var(--e-shadow-primary);
}
.ep-stat-card.success {
    background: linear-gradient(135deg, #059669, var(--e-success));
    color: var(--e-white);
    box-shadow: 0 4px 14px rgba(16, 185, 129, 0.25);
}
.ep-stat-card.accent {
    background: linear-gradient(135deg, var(--e-accent-dark), var(--e-accent));
    color: var(--e-white);
    box-shadow: var(--e-shadow-accent);
}
.ep-stat-number { font-size: 1.8rem; font-weight: 800; line-height: 1; margin-bottom: 4px; }
.ep-stat-label { font-size: 0.82rem; opacity: 0.9; font-weight: 500; }

/* Links */
.ep-links { display: flex; gap: 10px; justify-content: center; flex-wrap: wrap; margin-top: 16px; }
.ep-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    padding: 10px 24px;
    border: none;
    border-radius: var(--e-radius);
    font-family: var(--e-font);
    font-size: 0.9rem;
    font-weight: 600;
    cursor: pointer;
    transition: var(--e-transition);
    text-decoration: none;
}
.ep-btn-primary {
    background: linear-gradient(135deg, var(--e-primary), var(--e-primary-light));
    color: var(--e-white);
    box-shadow: var(--e-shadow-primary);
}
.ep-btn-primary:hover { transform: translateY(-2px); box-shadow: 0 6px 20px rgba(47, 92, 105, 0.35); color: var(--e-white); }
.ep-btn-accent {
    background: linear-gradient(135deg, var(--e-accent), var(--e-accent-light));
    color: var(--e-white);
    box-shadow: var(--e-shadow-accent);
}
.ep-btn-accent:hover { transform: translateY(-2px); box-shadow: 0 6px 20px rgba(243, 164, 70, 0.4); color: var(--e-white); }
.ep-btn-outline {
    background: transparent;
    color: var(--e-primary);
    border: 2px solid var(--e-primary-200);
}
.ep-btn-outline:hover { background: var(--e-primary); color: var(--e-white); border-color: var(--e-primary); }

/* Complete Alert */
.ep-complete-alert {
    background: linear-gradient(135deg, #fffbeb, #fef3c7);
    border: 1px solid #fcd34d;
    border-radius: var(--e-radius);
    padding: 20px;
    text-align: center;
}
.ep-complete-alert i { font-size: 2rem; color: var(--e-warning); margin-bottom: 8px; }
.ep-complete-alert h6 { font-weight: 700; color: var(--e-gray-800); margin-bottom: 6px; }
.ep-complete-alert p { color: var(--e-gray-500); font-size: 0.85rem; margin-bottom: 14px; }

/* Animation */
.ep-animate {
    opacity: 0;
    transform: translateY(20px);
    animation: epSlideUp 0.5s ease forwards;
}
.ep-animate-d1 { animation-delay: 0.08s; }
.ep-animate-d2 { animation-delay: 0.16s; }
.ep-animate-d3 { animation-delay: 0.24s; }
@keyframes epSlideUp { to { opacity: 1; transform: translateY(0); } }

/* Responsive */
@media (max-width: 767px) {
    .ep-cover { height: 160px; }
    .ep-avatar-section { margin-top: -60px; }
    .ep-avatar-img, .ep-avatar-fallback { width: 115px; height: 115px; font-size: 42px; }
    .ep-user-name { font-size: 1.3rem; }
    .ep-info-grid { grid-template-columns: 1fr; }
    .ep-card-body { padding: 18px; }
    .ep-stats { grid-template-columns: 1fr 1fr; }
}
</style>

<div class="ep-profile">
    <div class="ep-cover"></div>

    <div class="container" style="max-width: 720px;">
        {{-- Alerts --}}
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show mt-3 ep-animate" role="alert">
                <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show mt-3 ep-animate" role="alert">
                <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        {{-- Avatar & Info --}}
        <div class="ep-avatar-section ep-animate">
            <div class="ep-avatar-wrap">
                @if($user->image && file_exists(public_path('storage/' . $user->image)))
                    <img src="{{ asset('storage/' . $user->image) }}" alt="{{ $user->name }}" class="ep-avatar-img">
                @else
                    <div class="ep-avatar-fallback">{{ strtoupper(mb_substr($user->name, 0, 1)) }}</div>
                @endif
                <span class="ep-online-dot"></span>
            </div>

            <h3 class="ep-user-name">{{ $user->name }}</h3>

            @if($user->isProvider())
                <span class="ep-user-role ep-role-provider"><i class="fas fa-tools"></i> مزود خدمة</span>
            @elseif($user->isCustomer())
                <span class="ep-user-role ep-role-customer"><i class="fas fa-user"></i> عميل</span>
            @elseif($user->isAdmin())
                <span class="ep-user-role ep-role-admin"><i class="fas fa-crown"></i> مدير النظام</span>
            @endif

            @if($user->bio)
                <p class="ep-bio">{{ $user->bio }}</p>
            @endif

            <a href="{{ route('profile.edit') }}" class="ep-edit-btn">
                <i class="fas fa-pen"></i> تعديل الملف الشخصي
            </a>
        </div>

        {{-- Info Cards --}}
        <div class="ep-info-grid ep-animate ep-animate-d1">
            @if($user->phone)
            <div class="ep-info-item">
                <div class="ep-info-icon phone"><i class="fas fa-phone-alt"></i></div>
                <div>
                    <div class="ep-info-label">رقم الهاتف</div>
                    <div class="ep-info-value" dir="ltr">{{ $user->phone }}</div>
                </div>
            </div>
            @endif
            <div class="ep-info-item">
                <div class="ep-info-icon email"><i class="fas fa-envelope"></i></div>
                <div>
                    <div class="ep-info-label">البريد الإلكتروني</div>
                    <div class="ep-info-value">{{ $user->email }}</div>
                </div>
            </div>
            <div class="ep-info-item">
                <div class="ep-info-icon date"><i class="fas fa-calendar-alt"></i></div>
                <div>
                    <div class="ep-info-label">تاريخ الانضمام</div>
                    <div class="ep-info-value">{{ $user->created_at->format('Y/m/d') }}</div>
                </div>
            </div>
        </div>

        {{-- Provider Dashboard --}}
        @if($user->isProvider())
            <div class="ep-card mt-4 ep-animate ep-animate-d2">
                <div class="ep-card-header">
                    <div class="ep-card-header-icon"><i class="fas fa-chart-bar"></i></div>
                    <h5>لوحة مزود الخدمة</h5>
                </div>
                <div class="ep-card-body">
                    @if($user->hasCompleteProviderProfile())
                        <div class="ep-stats">
                            <div class="ep-stat-card primary">
                                <div class="ep-stat-number">{{ $user->services->count() }}</div>
                                <div class="ep-stat-label">إجمالي الخدمات</div>
                            </div>
                            <div class="ep-stat-card success">
                                <div class="ep-stat-number">{{ $user->services->where('is_active', true)->count() }}</div>
                                <div class="ep-stat-label">خدمات نشطة</div>
                            </div>
                            <div class="ep-stat-card accent">
                                <div class="ep-stat-number">{{ \App\Models\ServiceOffer::where('provider_id', $user->id)->count() }}</div>
                                <div class="ep-stat-label">العروض المقدمة</div>
                            </div>
                        </div>
                        <div class="ep-links">
                            <a href="{{ route('provider.services.index') }}" class="ep-btn ep-btn-primary">
                                <i class="fas fa-cogs"></i> إدارة الخدمات
                            </a>
                            <a href="{{ route('provider.profile') }}" class="ep-btn ep-btn-outline">
                                <i class="fas fa-user-cog"></i> الملف المتقدم
                            </a>
                        </div>
                    @else
                        <div class="ep-complete-alert">
                            <i class="fas fa-exclamation-triangle d-block"></i>
                            <h6>يجب إكمال الملف الشخصي أولاً</h6>
                            <p>لإدارة الخدمات والعروض، أكمل ملفك الشخصي كمزود خدمة.</p>
                            <a href="{{ route('provider.complete-profile') }}" class="ep-btn ep-btn-accent">
                                <i class="fas fa-user-plus"></i> إكمال الملف الشخصي
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        @endif

        {{-- Customer Dashboard --}}
        @if($user->isCustomer())
            <div class="ep-card mt-4 ep-animate ep-animate-d2">
                <div class="ep-card-header">
                    <div class="ep-card-header-icon"><i class="fas fa-th-large"></i></div>
                    <h5>الإجراءات السريعة</h5>
                </div>
                <div class="ep-card-body">
                    <div class="ep-links">
                        <a href="{{ route('services.index') }}" class="ep-btn ep-btn-primary">
                            <i class="fas fa-search"></i> تصفح الخدمات
                        </a>
                        <a href="{{ route('services.my-services') }}" class="ep-btn ep-btn-outline">
                            <i class="fas fa-list"></i> خدماتي
                        </a>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection
