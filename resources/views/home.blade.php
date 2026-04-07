@extends('layouts.app')

@section('title', __('messages.home') . ' | ' . config('app.name', 'Endak'))

@php
    $seoDescription = __('messages.seo_home_description');
    $seoKeywords = __('messages.seo_home_keywords');
    $seoSchema = [
        '@context' => 'https://schema.org',
        '@type' => 'WebPage',
        'name' => __('messages.home'),
        'description' => $seoDescription,
        'url' => route('home'),
    ];
@endphp

@section('content')
<style>
/* ===== Hero ===== */
.eh-hero {
    position: relative;
    background: linear-gradient(160deg, var(--e-primary-dark) 0%, var(--e-primary) 40%, var(--e-primary-light) 100%);
    overflow: hidden;
    padding: 80px 0 100px;
    margin-top: -2px;
}
.eh-hero::before {
    content: '';
    position: absolute;
    inset: 0;
    background: url("data:image/svg+xml,%3Csvg width='80' height='80' viewBox='0 0 80 80' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='%23ffffff' fill-opacity='0.03'%3E%3Ccircle cx='40' cy='40' r='4'/%3E%3C/g%3E%3C/svg%3E");
}
.eh-hero::after {
    content: '';
    position: absolute;
    bottom: -2px;
    left: 0;
    right: 0;
    height: 80px;
    background: var(--e-gray-50);
    clip-path: ellipse(55% 100% at 50% 100%);
}
.eh-hero-inner {
    position: relative;
    z-index: 2;
    display: flex;
    align-items: center;
    gap: 40px;
}
.eh-hero-text { flex: 1; }
.eh-hero-visual {
    flex: 0 0 380px;
    text-align: center;
}
.eh-hero-visual img {
    max-width: 100%;
    max-height: 320px;
    filter: drop-shadow(0 20px 40px rgba(0,0,0,0.2));
    animation: ehFloat 5s ease-in-out infinite;
}
@keyframes ehFloat {
    0%,100% { transform: translateY(0); }
    50% { transform: translateY(-14px); }
}
.eh-badge {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    background: rgba(255,255,255,0.12);
    border: 1px solid rgba(255,255,255,0.15);
    backdrop-filter: blur(8px);
    padding: 8px 18px;
    border-radius: var(--e-radius-full);
    color: var(--e-accent);
    font-size: 0.85rem;
    font-weight: 600;
    margin-bottom: 20px;
}
.eh-badge i { font-size: 0.9rem; }
.eh-title {
    font-size: 2.6rem;
    font-weight: 800;
    color: var(--e-white);
    line-height: 1.3;
    margin-bottom: 18px;
}
.eh-title .accent { color: var(--e-accent); }
.eh-title .light { color: rgba(255,255,255,0.7); }
.eh-desc {
    font-size: 1.05rem;
    color: rgba(255,255,255,0.8);
    line-height: 1.8;
    margin-bottom: 28px;
    max-width: 520px;
}
.eh-btns { display: flex; flex-wrap: wrap; gap: 12px; }
.eh-btn {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 13px 30px;
    border-radius: var(--e-radius-full);
    font-family: var(--e-font);
    font-size: 0.95rem;
    font-weight: 700;
    text-decoration: none;
    transition: var(--e-transition);
    border: none;
    cursor: pointer;
}
.eh-btn-accent {
    background: var(--e-accent);
    color: var(--e-white);
    box-shadow: 0 6px 24px rgba(243,164,70,0.4);
}
.eh-btn-accent:hover {
    background: var(--e-accent-dark);
    transform: translateY(-3px);
    box-shadow: 0 10px 30px rgba(243,164,70,0.5);
    color: var(--e-white);
}
.eh-btn-outline {
    background: rgba(255,255,255,0.1);
    color: var(--e-white);
    border: 2px solid rgba(255,255,255,0.3);
}
.eh-btn-outline:hover {
    background: var(--e-white);
    color: var(--e-primary);
    border-color: var(--e-white);
    transform: translateY(-3px);
}
.eh-stats {
    display: flex;
    gap: 32px;
    margin-top: 36px;
    padding-top: 28px;
    border-top: 1px solid rgba(255,255,255,0.12);
}
.eh-stat-num {
    font-size: 1.6rem;
    font-weight: 800;
    color: var(--e-white);
    line-height: 1;
}
.eh-stat-label {
    font-size: 0.78rem;
    color: rgba(255,255,255,0.6);
    margin-top: 4px;
}

/* ===== Categories ===== */
.eh-cats {
    padding: 70px 0 60px;
    background: var(--e-gray-50);
}
.eh-section-head {
    text-align: center;
    margin-bottom: 40px;
}
.eh-section-tag {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    background: var(--e-primary-100);
    color: var(--e-primary);
    padding: 6px 16px;
    border-radius: var(--e-radius-full);
    font-size: 0.8rem;
    font-weight: 700;
    margin-bottom: 12px;
}
.eh-section-title {
    font-size: 1.8rem;
    font-weight: 800;
    color: var(--e-gray-800);
    margin-bottom: 8px;
}
.eh-section-desc {
    color: var(--e-gray-500);
    font-size: 0.95rem;
}

/* Category Card */
.eh-cat-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(240px, 1fr));
    gap: 20px;
}
.eh-cat-card {
    position: relative;
    border-radius: var(--e-radius-lg);
    overflow: hidden;
    text-decoration: none;
    display: block;
    box-shadow: var(--e-shadow);
    transition: var(--e-transition);
}
.eh-cat-card:hover {
    transform: translateY(-6px);
    box-shadow: var(--e-shadow-lg);
}
.eh-cat-img {
    width: 100%;
    height: 200px;
    object-fit: cover;
    display: block;
    transition: transform 0.5s ease;
}
.eh-cat-card:hover .eh-cat-img { transform: scale(1.08); }
.eh-cat-overlay {
    position: absolute;
    inset: 0;
    background: linear-gradient(0deg, rgba(0,0,0,0.65) 0%, rgba(0,0,0,0.1) 60%, transparent 100%);
    display: flex;
    align-items: flex-end;
    padding: 18px;
    transition: var(--e-transition);
}
.eh-cat-card:hover .eh-cat-overlay {
    background: linear-gradient(0deg, rgba(47,92,105,0.85) 0%, rgba(47,92,105,0.2) 60%, transparent 100%);
}
.eh-cat-name {
    color: var(--e-white);
    font-size: 1.15rem;
    font-weight: 700;
    text-shadow: 0 2px 8px rgba(0,0,0,0.3);
    margin: 0;
}
.eh-cat-arrow {
    position: absolute;
    top: 14px;
    left: 14px;
    width: 34px;
    height: 34px;
    border-radius: var(--e-radius-full);
    background: rgba(255,255,255,0.2);
    backdrop-filter: blur(6px);
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--e-white);
    font-size: 13px;
    opacity: 0;
    transform: translateX(8px);
    transition: var(--e-transition);
}
.eh-cat-card:hover .eh-cat-arrow {
    opacity: 1;
    transform: translateX(0);
}

/* ===== Features ===== */
.eh-features {
    padding: 60px 0;
    background: var(--e-white);
}
.eh-feat-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 24px;
}
.eh-feat-card {
    text-align: center;
    padding: 32px 24px;
    border-radius: var(--e-radius-lg);
    border: 1px solid var(--e-gray-100);
    transition: var(--e-transition);
    background: var(--e-white);
}
.eh-feat-card:hover {
    border-color: var(--e-primary-200);
    box-shadow: var(--e-shadow-md);
    transform: translateY(-4px);
}
.eh-feat-icon {
    width: 64px;
    height: 64px;
    border-radius: var(--e-radius-lg);
    margin: 0 auto 16px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 24px;
    transition: var(--e-transition);
}
.eh-feat-card:hover .eh-feat-icon { transform: scale(1.1); }
.eh-feat-icon.i1 { background: var(--e-primary-100); color: var(--e-primary); }
.eh-feat-icon.i2 { background: var(--e-accent-50); color: var(--e-accent); }
.eh-feat-icon.i3 { background: var(--e-success-light); color: var(--e-success); }
.eh-feat-icon.i4 { background: var(--e-info-light); color: var(--e-info); }
.eh-feat-title {
    font-size: 1rem;
    font-weight: 700;
    color: var(--e-gray-800);
    margin-bottom: 6px;
}
.eh-feat-desc {
    font-size: 0.85rem;
    color: var(--e-gray-500);
    line-height: 1.7;
}

/* ===== CTA ===== */
.eh-cta {
    padding: 60px 0;
    background: linear-gradient(135deg, var(--e-primary-dark), var(--e-primary));
    text-align: center;
    position: relative;
    overflow: hidden;
}
.eh-cta::before {
    content: '';
    position: absolute;
    inset: 0;
    background: url("data:image/svg+xml,%3Csvg width='40' height='40' viewBox='0 0 40 40' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='%23ffffff' fill-opacity='0.04'%3E%3Cpath d='M20 20h20v20H20z'/%3E%3C/g%3E%3C/svg%3E");
}
.eh-cta-inner { position: relative; z-index: 2; }
.eh-cta-title {
    font-size: 1.8rem;
    font-weight: 800;
    color: var(--e-white);
    margin-bottom: 12px;
}
.eh-cta-desc {
    color: rgba(255,255,255,0.75);
    font-size: 1rem;
    margin-bottom: 28px;
    max-width: 480px;
    margin-inline: auto;
}

/* ===== Animation ===== */
.eh-anim {
    opacity: 0;
    transform: translateY(24px);
    transition: opacity 0.6s ease, transform 0.6s ease;
}
.eh-anim.visible {
    opacity: 1;
    transform: translateY(0);
}

/* ===== Responsive ===== */
@media (max-width: 991px) {
    .eh-hero { padding: 50px 0 80px; }
    .eh-hero-inner { flex-direction: column-reverse; text-align: center; }
    .eh-hero-visual { flex: none; width: 100%; }
    .eh-hero-visual img { max-height: 220px; }
    .eh-title { font-size: 2rem; }
    .eh-desc { margin-inline: auto; }
    .eh-btns { justify-content: center; }
    .eh-stats { justify-content: center; }
}
@media (max-width: 576px) {
    .eh-hero { padding: 36px 0 70px; }
    .eh-title { font-size: 1.55rem; }
    .eh-desc { font-size: 0.92rem; }
    .eh-btn { padding: 11px 22px; font-size: 0.88rem; }
    .eh-stats { gap: 20px; }
    .eh-stat-num { font-size: 1.3rem; }
    .eh-section-title { font-size: 1.4rem; }
    .eh-cat-grid { grid-template-columns: repeat(2, 1fr); gap: 12px; }
    .eh-cat-img { height: 140px; }
    .eh-cat-name { font-size: 0.9rem; }
    .eh-cta-title { font-size: 1.4rem; }
}
</style>

{{-- Hero --}}
<section class="eh-hero">
    <div class="container">
        <div class="eh-hero-inner">
            <div class="eh-hero-text">
                <div class="eh-badge">
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <span style="color:rgba(255,255,255,0.8);margin-right:4px;">{{ __('messages.hero_rating') }}</span>
                </div>

                <h1 class="eh-title">
                    {{ __('messages.hero_title_1') }}
                    <span class="accent">Endak</span>
                    {{ __('messages.hero_title_2') }}
                    <span class="light">{{ __('messages.hero_title_3') }}</span>
                    {{ __('messages.hero_title_4') }}
                </h1>

                <p class="eh-desc">{{ __('messages.hero_description') }}</p>

                <div class="eh-btns">
                   
                    <a href="{{ route('services.index') }}" class="eh-btn eh-btn-outline">
                        <i class="fas fa-search"></i> {{ __('messages.hero_explore_services') }}
                    </a>
                </div>

                <div class="eh-stats">
                    <div>
                        <div class="eh-stat-num">{{ \App\Models\Category::where('is_active', true)->count() }}+</div>
                        <div class="eh-stat-label">قسم متاح</div>
                    </div>
                    <div>
                        <div class="eh-stat-num">{{ \App\Models\Service::where('is_active', true)->count() }}+</div>
                        <div class="eh-stat-label">خدمة منشورة</div>
                    </div>
                    <div>
                        <div class="eh-stat-num">{{ \App\Models\User::count() }}+</div>
                        <div class="eh-stat-label">مستخدم نشط</div>
                    </div>
                </div>
            </div>

            <div class="eh-hero-visual">
                <img src="{{ asset(\App\Models\SystemSetting::get('site_logo', 'home.png')) }}"
                     alt="{{ \App\Models\SystemSetting::get('site_name_ar', 'إنداك') }}"
                     onerror="this.onerror=null;this.src='{{ asset('home.png') }}';">
            </div>
        </div>
    </div>
</section>

{{-- Categories --}}
<section class="eh-cats">
    <div class="container">
        <div class="eh-section-head eh-anim">
            <div class="eh-section-tag"><i class="fas fa-layer-group"></i> الأقسام</div>
            <h2 class="eh-section-title">{{ __('messages.categories_title') }}</h2>
            <p class="eh-section-desc">{{ __('messages.categories_subtitle') }}</p>
        </div>

        <div class="eh-cat-grid">
            @forelse($categories as $category)
                <a href="{{ $category->hasChildren() ? route('categories.subcategories', $category->slug) : route('categories.show', $category->slug) }}"
                   class="eh-cat-card eh-anim">
                    <img src="{{ $category->image_url }}" alt="{{ $category->name }}" class="eh-cat-img"
                         onerror="this.onerror=null;this.src='{{ asset('images/default-service.svg') }}';">
                    <div class="eh-cat-overlay">
                        <h3 class="eh-cat-name">{{ app()->getLocale() == 'ar' ? $category->name : $category->name_en }}</h3>
                    </div>
                    <span class="eh-cat-arrow"><i class="fas fa-arrow-left"></i></span>
                </a>
            @empty
                <div style="grid-column:1/-1;text-align:center;padding:40px;color:var(--e-gray-400);">
                    <i class="fas fa-layer-group" style="font-size:2rem;margin-bottom:10px;display:block;"></i>
                    لا توجد أقسام متاحة حالياً
                </div>
            @endforelse
        </div>
    </div>
</section>



{{-- CTA --}}
<section class="eh-cta">
    <div class="container">
        <div class="eh-cta-inner eh-anim">
            <h2 class="eh-cta-title">جاهز تبدأ؟</h2>
            <p class="eh-cta-desc">سواء كنت تبحث عن خدمة أو تريد تقديم خدماتك، إنداك هو المكان المناسب لك.</p>
            <div class="eh-btns" style="justify-content:center;">
                @guest
                    <a href="{{ route('register') }}" class="eh-btn eh-btn-accent">
                        <i class="fas fa-user-plus"></i> سجّل الآن مجاناً
                    </a>
                @else
                    <a href="{{ route('categories.index') }}" class="eh-btn eh-btn-accent">
                        <i class="fas fa-rocket"></i> ابدأ الآن
                    </a>
                @endguest
                <a href="{{ \App\Helpers\WhatsAppHelper::getWhatsAppUrl() }}" target="_blank" rel="noopener noreferrer" class="eh-btn eh-btn-outline">
                    <i class="fab fa-whatsapp"></i> تواصل علي الواتساب
                </a>
            </div>
        </div>
    </div>
</section>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const observer = new IntersectionObserver((entries) => {
        entries.forEach((entry, i) => {
            if (entry.isIntersecting) {
                setTimeout(() => entry.target.classList.add('visible'), i * 60);
                observer.unobserve(entry.target);
            }
        });
    }, { threshold: 0.15 });
    document.querySelectorAll('.eh-anim').forEach(el => observer.observe(el));
});
</script>
@endsection
