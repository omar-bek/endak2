@extends('layouts.app')

@section('title', 'الملف الشخصي')

@section('content')
<style>
.profile-page {
    background: linear-gradient(135deg, #eaf6f8, #ffffff);
    font-family: 'Tajawal', sans-serif;
    border-radius: 20px;
    padding: 20px;
}

@media (min-width: 992px) {
    .profile-page {
        margin-top: 60px;
    }
}

.profile-page .card {
    border: none;
    border-radius: 20px;
    box-shadow: 0 10px 25px rgba(60, 111, 125, 0.15);
    transition: all 0.4s ease;
    background: #fff;
}
.profile-page .card:hover {
    transform: translateY(-5px);
    box-shadow: 0 12px 28px rgba(60, 111, 125, 0.25);
}

.profile-page .card-header {
    background: linear-gradient(135deg, #2f5c69, #3c6f7d);
    color: white;
    border-radius: 20px 20px 0 0;
    padding: 15px 20px;
    font-weight: 600;
    letter-spacing: 0.5px;
}

.profile-page .btn-primary {
    background: linear-gradient(135deg, #3c6f7d, #2f5c69);
    border: none;
    transition: all 0.3s ease;
    box-shadow: 0 5px 15px rgba(60, 111, 125, 0.3);
}
.profile-page .btn-primary:hover {
    background: linear-gradient(135deg, #f3a446, #f6b76a);
    box-shadow: 0 7px 18px rgba(243, 164, 70, 0.45);
    transform: scale(1.05);
}
.profile-page .btn-outline-primary {
    color: #3c6f7d;
    border: 2px solid #3c6f7d;
    transition: all 0.3s ease;
}
.profile-page .btn-outline-primary:hover {
    background: linear-gradient(135deg, #3c6f7d, #2f5c69);
    color: #fff;
    border-color: transparent;
    transform: translateY(-3px);
}
.profile-page .btn-warning {
    background: linear-gradient(135deg, #f3a446, #f6b76a);
    border: none;
    color: #fff;
    transition: 0.3s ease;
}
.profile-page .btn-warning:hover {
    background: linear-gradient(135deg, #3c6f7d, #2f5c69);
    transform: scale(1.05);
}

.profile-page .card-body img {
    border: 4px solid #f3a446;
    box-shadow: 0 4px 10px rgba(60, 111, 125, 0.3);
    transition: all 0.4s ease;
}
.profile-page .card-body img:hover {
    transform: rotate(3deg) scale(1.07);
    box-shadow: 0 8px 20px rgba(60, 111, 125, 0.4);
}

.profile-page h4 {
    color: #2f5c69;
    font-weight: 700;
}
.profile-page .text-muted {
    color: #607d8b !important;
}

/* ===== أنيميشن دخول الصفحة ===== */
.profile-page .container {
    animation: fadeInUp 1s ease forwards;
    opacity: 0;
}
@keyframes fadeInUp {
    0% {
        transform: translateY(40px);
        opacity: 0;
    }
    100% {
        transform: translateY(0);
        opacity: 1;
    }
}

.profile-page .bg-primary,
.profile-page .bg-success,
.profile-page .bg-warning {
    border-radius: 15px;
    padding: 15px;
    transition: all 0.3s ease;
}
.profile-page .bg-primary {
    background: linear-gradient(135deg, #2f5c69, #3c6f7d) !important;
}
.profile-page .bg-success {
    background: linear-gradient(135deg, #3c6f7d, #2f5c69) !important;
}
.profile-page .bg-warning {
    background: linear-gradient(135deg, #f3a446, #f6b76a) !important;
}
.profile-page .bg-primary:hover,
.profile-page .bg-success:hover,
.profile-page .bg-warning:hover {
    transform: scale(1.05);
    box-shadow: 0 6px 18px rgba(0, 0, 0, 0.15);
}

/* ===== الفورم ===== */
.profile-page .form-control {
    border-radius: 10px;
    border: 1px solid rgba(60, 111, 125, 0.25);
    transition: all 0.3s ease;
}
.profile-page .form-control:focus {
    border-color: #f3a446;
    box-shadow: 0 0 6px rgba(243, 164, 70, 0.4);
}

.profile-page label {
    color: #2f5c69;
    font-weight: 600;
}

.profile-page .alert-warning {
    background: linear-gradient(135deg, #fff6e5, #ffe7c3);
    border: 1px solid #f3a446;
    color: #3c6f7d;
    border-radius: 15px;
    animation: pulse 2.5s infinite;
}
@keyframes pulse {
    0%, 100% {
        box-shadow: 0 0 0 rgba(243, 164, 70, 0);
    }
    50% {
        box-shadow: 0 0 15px rgba(243, 164, 70, 0.5);
    }
}

.profile-page i {
    color: #f3a446;
    transition: transform 0.3s ease;
}
.profile-page i:hover {
    transform: scale(1.2);
}
.profile-page .profile-sidebar {
    border: 2px solid #3c6f7d; 
}

</style>

<div class="container py-5 profile-page">
    <div class="row">
        <div class="col-lg-4 mb-4">
            <div class="card profile-sidebar">
                <div class="card-body text-center">
                    @if($user->image && file_exists(public_path('storage/' . $user->image)))
                        <img src="{{ asset('storage/' . $user->image) }}" alt="{{ $user->name }}"
                             class="rounded-circle mb-3" width="120" height="120" style="object-fit: cover;">
                    @else
                        <div class="rounded-circle mb-3 mx-auto d-flex align-items-center justify-content-center bg-primary text-white"
                             style="width: 120px; height: 120px; font-size: 48px; font-weight: bold;">
                            {{ strtoupper(substr($user->name, 0, 1)) }}
                        </div>
                    @endif

                    <h4>{{ $user->name }}</h4>
                    <p class="text-muted">
                        @if($user->isProvider())
                            <i class="fas fa-tools me-1"></i>مزود خدمة
                        @elseif($user->isCustomer())
                            <i class="fas fa-user me-1"></i>مستخدم
                        @elseif($user->isAdmin())
                            <i class="fas fa-crown me-1"></i>مدير النظام
                        @endif
                    </p>

                    @if($user->phone)
                        <p class="text-muted"><i class="fas fa-phone me-1"></i>{{ $user->phone }}</p>
                    @endif

                    <p class="text-muted"><i class="fas fa-envelope me-1"></i>{{ $user->email }}</p>

                    @if($user->bio)
                        <p class="text-muted">{{ $user->bio }}</p>
                    @endif

                    <p class="text-muted"><small>انضم في {{ $user->created_at->format('Y/m/d') }}</small></p>
                </div>
            </div>
        </div>

        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-edit me-2"></i>تعديل الملف الشخصي</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="name" class="form-label">الاسم الكامل *</label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror"
                                       id="name" name="name" value="{{ old('name', $user->name) }}" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="phone" class="form-label">رقم الهاتف *</label>
                                <input type="tel" class="form-control @error('phone') is-invalid @enderror"
                                       id="phone" name="phone" value="{{ old('phone', $user->phone) }}" required>
                                @error('phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="bio" class="form-label">نبذة شخصية</label>
                            <textarea class="form-control @error('bio') is-invalid @enderror"
                                      id="bio" name="bio" rows="3">{{ old('bio', $user->bio) }}</textarea>
                            @error('bio')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="image" class="form-label">الصورة الشخصية</label>
                            <input type="file" class="form-control @error('image') is-invalid @enderror"
                                   id="image" name="image" accept="image/*">
                            <small class="form-text text-muted">الأبعاد المفضلة: 300x300 بكسل. الأنواع المدعومة: JPG, PNG, GIF</small>
                            @error('image')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror

                            @if($user->image)
                                <div class="mt-2">
                                    <small class="text-muted">الصورة الحالية:</small>
                                    <div class="mt-1">
                                        <img src="{{ asset('storage/' . $user->image) }}" alt="الصورة الحالية"
                                             class="rounded" style="width: 80px; height: 80px; object-fit: cover;">
                                    </div>
                                </div>
                            @endif
                        </div>

                        <div class="d-flex justify-content-end">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i>حفظ التغييرات
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            @if($user->isProvider())
                <div class="card mt-4">
                    <div class="card-header"><h5 class="mb-0"><i class="fas fa-tachometer-alt me-2"></i>لوحة مزود الخدمة</h5></div>
                    <div class="card-body">
                        @if($user->hasCompleteProviderProfile())
                            <div class="row text-center">
                                <div class="col-md-4 mb-3">
                                    <div class="bg-primary text-white rounded p-3">
                                        <h3>{{ $user->services->count() }}</h3>
                                        <p class="mb-0">الخدمات</p>
                                    </div>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <div class="bg-success text-white rounded p-3">
                                        <h3>{{ $user->services->where('is_active', true)->count() }}</h3>
                                        <p class="mb-0">الخدمات النشطة</p>
                                    </div>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <div class="bg-warning text-white rounded p-3">
                                        <p class="mb-0">الطلبات</p>
                                    </div>
                                </div>
                            </div>
                            <div class="text-center">
                                <a href="{{ route('provider.services.index') }}" class="btn btn-primary">
                                    <i class="fas fa-cogs me-2"></i>إدارة الخدمات
                                </a>
                                <a href="{{ route('provider.profile') }}" class="btn btn-outline-primary">
                                    <i class="fas fa-user me-2"></i>الملف الشخصي المتقدم
                                </a>
                            </div>
                        @else
                            <div class="alert alert-warning">
                                <i class="fas fa-exclamation-triangle me-2"></i>
                                <strong>يجب إكمال الملف الشخصي أولاً</strong>
                                <p class="mb-0 mt-2">لإدارة الخدمات والعروض، يجب إكمال ملفك الشخصي كمزود خدمة.</p>
                            </div>
                            <div class="text-center">
                                <a href="{{ route('provider.complete-profile') }}" class="btn btn-warning">
                                    <i class="fas fa-user-plus me-2"></i>إكمال الملف الشخصي
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            @endif

            @if($user->isCustomer())
                <div class="card mt-4">
                    <div class="card-header"><h5 class="mb-0"><i class="fas fa-user me-2"></i>لوحة العميل</h5></div>
                    <div class="card-body text-center">
                        <a href="{{ route('services.index') }}" class="btn btn-outline-primary">
                            <i class="fas fa-search me-2"></i>تصفح الخدمات
                        </a>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
