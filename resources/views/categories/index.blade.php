@extends('layouts.app')

@section('title', 'الأقسام')

@section('content')
<style>
/* ===== Categories Page ===== */
.ecp {
    min-height: 100vh;
    padding-bottom: 100px;
}

/* Header */
.ecp-header {
    position: relative;
    background: linear-gradient(160deg, var(--e-primary-dark), var(--e-primary), var(--e-primary-light));
    padding: 50px 0 70px;
    overflow: hidden;
    margin-top: -2px;
}
.ecp-header::before {
    content: '';
    position: absolute;
    inset: 0;
    background: url("data:image/svg+xml,%3Csvg width='80' height='80' viewBox='0 0 80 80' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='%23ffffff' fill-opacity='0.03'%3E%3Ccircle cx='40' cy='40' r='4'/%3E%3C/g%3E%3C/svg%3E");
}
.ecp-header::after {
    content: '';
    position: absolute;
    bottom: -2px;
    left: 0;
    right: 0;
    height: 50px;
    background: var(--e-gray-50);
    clip-path: ellipse(55% 100% at 50% 100%);
}
.ecp-header-inner {
    position: relative;
    z-index: 2;
    text-align: center;
}
.ecp-header-tag {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    background: rgba(255,255,255,0.12);
    border: 1px solid rgba(255,255,255,0.15);
    padding: 6px 16px;
    border-radius: var(--e-radius-full);
    color: var(--e-accent);
    font-size: 0.82rem;
    font-weight: 600;
    margin-bottom: 14px;
}
.ecp-header h1 {
    font-size: 2rem;
    font-weight: 800;
    color: var(--e-white);
    margin-bottom: 10px;
}
.ecp-header p {
    color: rgba(255,255,255,0.7);
    font-size: 1rem;
    max-width: 500px;
    margin: 0 auto;
}

/* Search */
.ecp-search-wrap {
    max-width: 480px;
    margin: 24px auto 0;
    position: relative;
}
.ecp-search {
    width: 100%;
    padding: 13px 20px 13px 48px;
    border: none;
    border-radius: var(--e-radius-full);
    font-family: var(--e-font);
    font-size: 0.92rem;
    background: rgba(255,255,255,0.15);
    backdrop-filter: blur(8px);
    color: var(--e-white);
    outline: none;
    transition: var(--e-transition);
}
.ecp-search::placeholder { color: rgba(255,255,255,0.5); }
.ecp-search:focus {
    background: rgba(255,255,255,0.25);
    box-shadow: 0 0 0 3px rgba(255,255,255,0.1);
}
.ecp-search-icon {
    position: absolute;
    left: 18px;
    top: 50%;
    transform: translateY(-50%);
    color: rgba(255,255,255,0.5);
    font-size: 14px;
}
.ecp-count {
    margin-top: 14px;
    font-size: 0.82rem;
    color: rgba(255,255,255,0.5);
}
.ecp-count span {
    color: var(--e-accent);
    font-weight: 700;
}

/* Grid */
.ecp-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(260px, 1fr));
    gap: 22px;
    padding-top: 10px;
}

/* Card */
.ecp-card {
    position: relative;
    border-radius: var(--e-radius-xl);
    overflow: hidden;
    text-decoration: none;
    display: block;
    background: var(--e-white);
    box-shadow: var(--e-shadow);
    border: 1px solid var(--e-gray-100);
    transition: var(--e-transition-slow);
}
.ecp-card:hover {
    transform: translateY(-8px);
    box-shadow: var(--e-shadow-lg);
    border-color: var(--e-primary-200);
}
.ecp-card-img-wrap {
    position: relative;
    height: 190px;
    overflow: hidden;
}
.ecp-card-img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.6s ease;
}
.ecp-card:hover .ecp-card-img { transform: scale(1.1); }
.ecp-card-overlay {
    position: absolute;
    inset: 0;
    background: linear-gradient(0deg, rgba(0,0,0,0.55) 0%, rgba(0,0,0,0.05) 55%, transparent 100%);
    transition: var(--e-transition);
}
.ecp-card:hover .ecp-card-overlay {
    background: linear-gradient(0deg, rgba(47,92,105,0.8) 0%, rgba(47,92,105,0.15) 55%, transparent 100%);
}
.ecp-card-badge {
    position: absolute;
    top: 14px;
    right: 14px;
    background: rgba(255,255,255,0.2);
    backdrop-filter: blur(8px);
    padding: 4px 12px;
    border-radius: var(--e-radius-full);
    color: var(--e-white);
    font-size: 0.72rem;
    font-weight: 700;
    display: flex;
    align-items: center;
    gap: 4px;
}
.ecp-card-arrow {
    position: absolute;
    top: 14px;
    left: 14px;
    width: 34px;
    height: 34px;
    border-radius: var(--e-radius-full);
    background: var(--e-accent);
    color: var(--e-white);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 13px;
    opacity: 0;
    transform: translateX(8px);
    transition: var(--e-transition);
    box-shadow: 0 4px 12px rgba(243,164,70,0.4);
}
.ecp-card:hover .ecp-card-arrow {
    opacity: 1;
    transform: translateX(0);
}

/* Card Body */
.ecp-card-body {
    padding: 18px 20px;
    display: flex;
    align-items: center;
    justify-content: space-between;
}
.ecp-card-name {
    font-size: 1.05rem;
    font-weight: 700;
    color: var(--e-gray-800);
    margin: 0;
    transition: var(--e-transition);
}
.ecp-card:hover .ecp-card-name { color: var(--e-primary); }
.ecp-card-count {
    font-size: 0.78rem;
    color: var(--e-gray-400);
    display: flex;
    align-items: center;
    gap: 4px;
}
.ecp-card-count i { color: var(--e-accent); font-size: 0.7rem; }

/* Empty */
.ecp-empty {
    grid-column: 1 / -1;
    text-align: center;
    padding: 60px 20px;
    color: var(--e-gray-400);
}
.ecp-empty i {
    font-size: 3rem;
    display: block;
    margin-bottom: 14px;
    color: var(--e-gray-300);
}

/* Animation */
.ecp-anim {
    opacity: 0;
    transform: translateY(20px);
    transition: opacity 0.5s ease, transform 0.5s ease;
}
.ecp-anim.visible {
    opacity: 1;
    transform: translateY(0);
}

/* Responsive */
@media (max-width: 767px) {
    .ecp-header { padding: 36px 0 56px; }
    .ecp-header h1 { font-size: 1.5rem; }
    .ecp-grid { grid-template-columns: repeat(2, 1fr); gap: 12px; }
    .ecp-card-img-wrap { height: 140px; }
    .ecp-card-body { padding: 12px 14px; }
    .ecp-card-name { font-size: 0.88rem; }
    .ecp-card-badge { display: none; }
}
@media (max-width: 400px) {
    .ecp-grid { grid-template-columns: 1fr; }
    .ecp-card-img-wrap { height: 170px; }
}
</style>

<div class="ecp">
    {{-- Header --}}
    <div class="ecp-header">
        <div class="container">
            <div class="ecp-header-inner">
                <div class="ecp-header-tag"><i class="fas fa-layer-group"></i> تصفح حسب القسم</div>
                <h1>{{ __('messages.categories_title') }}</h1>
                <p>{{ __('messages.categories_subtitle') }}</p>

                <div class="ecp-search-wrap">
                    <i class="fas fa-search ecp-search-icon"></i>
                    <input type="text" id="catSearch" class="ecp-search" placeholder="ابحث عن قسم...">
                </div>
                <div class="ecp-count" id="catCount"><span>{{ $categories->count() }}</span> قسم متاح</div>
            </div>
        </div>
    </div>

    {{-- Grid --}}
    <div class="container" style="margin-top: -20px; position: relative; z-index: 3;">
        <div class="ecp-grid" id="catGrid">
            @forelse($categories as $category)
                @php
                    $serviceCount = \App\Models\Service::where('category_id', $category->id)->where('is_active', true)->count();
                    $catUrl = $category->hasChildren()
                        ? route('categories.subcategories', $category->slug)
                        : route('categories.show', $category->slug);
                @endphp
                <a href="{{ $catUrl }}" class="ecp-card ecp-anim" data-name="{{ $category->name }} {{ $category->name_en }}">
                    <div class="ecp-card-img-wrap">
                        <img src="{{ $category->image_url }}" alt="{{ $category->name }}" class="ecp-card-img"
                             onerror="this.onerror=null;this.src='{{ asset('images/default-service.svg') }}';">
                        <div class="ecp-card-overlay"></div>
                        @if($serviceCount > 0)
                            <div class="ecp-card-badge"><i class="fas fa-briefcase"></i> {{ $serviceCount }} خدمة</div>
                        @endif
                        <span class="ecp-card-arrow"><i class="fas fa-arrow-left"></i></span>
                    </div>
                    <div class="ecp-card-body">
                        <h3 class="ecp-card-name">{{ app()->getLocale() == 'ar' ? $category->name : $category->name_en }}</h3>
                        @if($category->hasChildren())
                            <span class="ecp-card-count"><i class="fas fa-folder"></i> أقسام فرعية</span>
                        @elseif($serviceCount > 0)
                            <span class="ecp-card-count"><i class="fas fa-circle"></i> {{ $serviceCount }} خدمة</span>
                        @endif
                    </div>
                </a>
            @empty
                <div class="ecp-empty">
                    <i class="fas fa-layer-group"></i>
                    <p>لا توجد أقسام متاحة حالياً</p>
                </div>
            @endforelse
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Animate on scroll
    const observer = new IntersectionObserver(entries => {
        entries.forEach((entry, i) => {
            if (entry.isIntersecting) {
                setTimeout(() => entry.target.classList.add('visible'), i * 50);
                observer.unobserve(entry.target);
            }
        });
    }, { threshold: 0.1 });
    document.querySelectorAll('.ecp-anim').forEach(el => observer.observe(el));

    // Search
    const search = document.getElementById('catSearch');
    const cards = document.querySelectorAll('.ecp-card');
    const countEl = document.getElementById('catCount');

    search.addEventListener('input', function() {
        const q = this.value.toLowerCase().trim();
        let visible = 0;
        cards.forEach(card => {
            const name = (card.getAttribute('data-name') || '').toLowerCase();
            const show = name.includes(q);
            card.style.display = show ? '' : 'none';
            if (show) visible++;
        });
        countEl.innerHTML = '<span>' + visible + '</span> قسم متاح';
    });
});
</script>
@endsection
