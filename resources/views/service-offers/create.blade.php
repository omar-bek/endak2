@extends('layouts.app')

@php $seoNoindex = true; @endphp
@section('title', 'تقديم عرض - ' . $service->title)

@section('content')

<style>
    .oc-hero {
        background: linear-gradient(135deg, var(--e-primary-dark) 0%, var(--e-primary) 50%, var(--e-primary-light) 100%);
        padding: 2rem 0 3.5rem; position: relative; overflow: hidden;
    }
    .oc-hero::before { content:''; position:absolute; width:450px; height:450px; border-radius:50%; background:rgba(243,164,70,0.06); top:-150px; right:-100px; }

    .oc-back { display:inline-flex; align-items:center; gap:0.4rem; color:rgba(255,255,255,0.7); font-size:0.82rem; text-decoration:none; margin-bottom:1rem; transition:color 0.2s; }
    .oc-back:hover { color:var(--e-accent); }

    .oc-service { display:flex; gap:1rem; align-items:center; }
    .oc-service-img { width:70px; height:70px; border-radius:var(--e-radius); object-fit:cover; border:2px solid rgba(255,255,255,0.15); flex-shrink:0; }
    .oc-service h2 { color:#fff; font-size:1.15rem; font-weight:700; margin-bottom:0.2rem; }
    .oc-service p { color:rgba(255,255,255,0.55); font-size:0.78rem; margin:0; }
    .oc-service-meta { display:flex; gap:0.75rem; margin-top:0.4rem; flex-wrap:wrap; }
    .oc-service-meta span { font-size:0.75rem; color:rgba(255,255,255,0.5); display:inline-flex; align-items:center; gap:0.3rem; }
    .oc-service-meta i { color:var(--e-accent); font-size:0.7rem; }

    .oc-content { margin-top:-2rem; position:relative; z-index:1; padding-bottom:3rem; }

    /* Form Card */
    .oc-card {
        background: var(--e-white); border-radius: var(--e-radius-xl);
        box-shadow: var(--e-shadow-lg); border: 1px solid var(--e-gray-100); overflow: hidden;
    }
    .oc-card-header {
        padding: 1.25rem 1.75rem; border-bottom: 1px solid var(--e-gray-100);
        display: flex; align-items: center; gap: 0.6rem;
    }
    .oc-card-header-icon {
        width: 38px; height: 38px; border-radius: var(--e-radius);
        background: linear-gradient(135deg, var(--e-primary), var(--e-primary-dark));
        display: flex; align-items: center; justify-content: center;
        color: #fff; font-size: 1rem; flex-shrink: 0;
    }
    .oc-card-header h3 { font-size: 1rem; font-weight: 700; color: var(--e-gray-800); margin: 0; }
    .oc-card-header small { font-size: 0.75rem; color: var(--e-gray-400); font-weight: 400; }
    .oc-card-body { padding: 1.75rem; }

    /* Floating inputs */
    .oc-field { position: relative; margin-bottom: 1.25rem; }
    .oc-field label {
        display: block; font-size: 0.85rem; font-weight: 600;
        color: var(--e-gray-700); margin-bottom: 0.35rem;
    }
    .oc-field label i { color: var(--e-gray-400); margin-left: 0.3rem; font-size: 0.8rem; }
    [dir="ltr"] .oc-field label i { margin-left: 0; margin-right: 0.3rem; }
    .oc-field .oc-input {
        width: 100%; border: 1.5px solid var(--e-gray-200); border-radius: var(--e-radius);
        padding: 0.7rem 1rem; font-size: 0.9rem; color: var(--e-gray-800);
        background: var(--e-white); transition: all 0.2s ease; outline: none; font-family: var(--e-font);
    }
    .oc-field .oc-input:focus { border-color: var(--e-primary); box-shadow: 0 0 0 3px var(--e-primary-100); }
    .oc-field .oc-hint { font-size: 0.74rem; color: var(--e-gray-400); margin-top: 0.3rem; }
    .oc-field textarea.oc-input { min-height: 120px; resize: vertical; }

    /* Price input group */
    .oc-price-wrap { position: relative; }
    .oc-price-wrap .oc-input { padding-left: 3.5rem; font-size: 1.1rem; font-weight: 700; }
    [dir="rtl"] .oc-price-wrap .oc-input { padding-left: 3.5rem; padding-right: 1rem; }
    [dir="ltr"] .oc-price-wrap .oc-input { padding-right: 3.5rem; padding-left: 1rem; }
    .oc-price-suffix {
        position: absolute; left: 0; top: 0; bottom: 0; width: 3.5rem;
        display: flex; align-items: center; justify-content: center;
        font-size: 0.78rem; font-weight: 700; color: var(--e-gray-500);
        background: var(--e-gray-50); border-radius: var(--e-radius) 0 0 var(--e-radius);
        border-left: 1.5px solid var(--e-gray-200);
    }
    [dir="ltr"] .oc-price-suffix {
        left: auto; right: 0;
        border-left: none; border-right: 1.5px solid var(--e-gray-200);
        border-radius: 0 var(--e-radius) var(--e-radius) 0;
    }

    /* Provider info */
    .oc-provider-info {
        background: var(--e-primary-50); border: 1px solid var(--e-primary-100);
        border-radius: var(--e-radius-lg); padding: 1rem 1.25rem; margin-bottom: 1.5rem;
    }
    .oc-provider-info-title {
        font-size: 0.78rem; font-weight: 700; color: var(--e-primary);
        text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 0.6rem;
        display: flex; align-items: center; gap: 0.4rem;
    }
    .oc-provider-row { display: flex; gap: 1.5rem; flex-wrap: wrap; }
    .oc-provider-item { font-size: 0.82rem; color: var(--e-gray-600); }
    .oc-provider-item strong { color: var(--e-gray-800); }

    /* Submit */
    .oc-submit-btn {
        display: flex; align-items: center; justify-content: center; gap: 0.6rem;
        width: 100%; padding: 0.85rem; border: none; border-radius: var(--e-radius-full);
        font-size: 1rem; font-weight: 700; cursor: pointer; transition: all 0.25s ease;
        background: linear-gradient(135deg, var(--e-primary) 0%, var(--e-primary-dark) 100%);
        color: #fff; box-shadow: var(--e-shadow-primary);
    }
    .oc-submit-btn:hover { transform: translateY(-2px); box-shadow: 0 8px 25px rgba(47,92,105,0.3); }
    .oc-submit-btn:active { transform: translateY(0); }

    .oc-cancel-link {
        display: block; text-align: center; margin-top: 0.75rem;
        font-size: 0.82rem; color: var(--e-gray-400); text-decoration: none;
    }
    .oc-cancel-link:hover { color: var(--e-danger); }

    /* Sidebar */
    .oc-sidebar-card {
        background: var(--e-white); border-radius: var(--e-radius-lg);
        border: 1px solid var(--e-gray-100); box-shadow: var(--e-shadow-sm); overflow: hidden; margin-bottom: 1rem;
    }
    .oc-sidebar-header {
        padding: 0.85rem 1rem; font-size: 0.85rem; font-weight: 700;
        color: var(--e-gray-800); display: flex; align-items: center; gap: 0.5rem;
        border-bottom: 1px solid var(--e-gray-100);
    }
    .oc-sidebar-header i { color: var(--e-primary); }
    .oc-sidebar-body { padding: 1rem; }
    .oc-tip { display: flex; gap: 0.5rem; align-items: flex-start; margin-bottom: 0.65rem; }
    .oc-tip:last-child { margin-bottom: 0; }
    .oc-tip i { color: var(--e-success); margin-top: 2px; font-size: 0.75rem; flex-shrink: 0; }
    .oc-tip span { font-size: 0.78rem; color: var(--e-gray-500); line-height: 1.5; }

    /* Errors */
    .oc-errors { background: var(--e-danger-light); border-radius: var(--e-radius); padding: 0.85rem 1rem; margin-bottom: 1.25rem; }
    .oc-errors ul { margin: 0; padding-right: 1rem; }
    .oc-errors li { font-size: 0.82rem; color: var(--e-danger); margin-bottom: 0.15rem; }

    @media (max-width: 768px) {
        .oc-service { flex-direction: column; text-align: center; align-items: center; }
        .oc-service-meta { justify-content: center; }
        .oc-card-body { padding: 1.25rem; }
    }
</style>

{{-- Hero --}}
<div class="oc-hero">
    <div class="container">
        <a href="{{ route('services.show', $service->slug) }}" class="oc-back">
            <i class="fas fa-arrow-right"></i> العودة للخدمة
        </a>
        <div class="oc-service">
            @if($service->image)
                <img src="{{ $service->image_url }}" alt="{{ $service->title }}" class="oc-service-img">
            @endif
            <div>
                <h2>{{ $service->title }}</h2>
                <p>{{ Str::limit($service->description, 100) }}</p>
                <div class="oc-service-meta">
                    <span><i class="fas fa-user"></i> {{ $service->user->name }}</span>
                    @if($service->location)
                        <span><i class="fas fa-map-marker-alt"></i> {{ $service->location }}</span>
                    @endif
                    @if($service->category)
                        <span><i class="fas fa-folder"></i> {{ $service->category->name }}</span>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Content --}}
<div class="oc-content">
    <div class="container">
        <div class="row g-4 justify-content-center">

            {{-- Form --}}
            <div class="col-lg-7">
                <div class="oc-card">
                    <div class="oc-card-header">
                        <div class="oc-card-header-icon"><i class="fas fa-handshake"></i></div>
                        <div>
                            <h3>تقديم عرض للخدمة</h3>
                            <small>حدد السعر وأضف تفاصيل عرضك</small>
                        </div>
                    </div>
                    <div class="oc-card-body">
                        <form action="{{ route('service-offers.store', $service) }}" method="POST" id="offerForm">
                            @csrf

                            @if ($errors->any())
                                <div class="oc-errors">
                                    <ul>
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            {{-- Price --}}
                            <div class="oc-field">
                                <label><i class="fas fa-coins"></i> السعر المطلوب <span style="color:var(--e-danger);">*</span></label>
                                <div class="oc-price-wrap">
                                    <input type="number" name="price" id="price" class="oc-input"
                                        value="{{ old('price') }}" step="0.01" min="0" required
                                        placeholder="0.00" aria-label="السعر">
                                    <div class="oc-price-suffix">ر.س</div>
                                </div>
                                <div class="oc-hint">أدخل السعر الذي تريد تقديمه لهذه الخدمة</div>
                            </div>

                            {{-- Notes --}}
                            <div class="oc-field">
                                <label><i class="fas fa-pen"></i> ملاحظات إضافية</label>
                                <textarea name="notes" id="notes" class="oc-input" rows="5"
                                    placeholder="اشرح تفاصيل عرضك، خبرتك، والمدة المتوقعة..."
                                    aria-label="ملاحظات">{{ old('notes') }}</textarea>
                                <div class="oc-hint">يمكنك إضافة تفاصيل حول الخدمة المقدمة أو الشروط</div>
                            </div>

                            {{-- Provider Info --}}
                            <div class="oc-provider-info">
                                <div class="oc-provider-info-title"><i class="fas fa-user-check"></i> معلوماتك كمزود خدمة</div>
                                <div class="oc-provider-row">
                                    <div class="oc-provider-item"><strong>الاسم:</strong> {{ auth()->user()->name }}</div>
                                    <div class="oc-provider-item"><strong>الهاتف:</strong> {{ auth()->user()->phone ?: 'غير محدد' }}</div>
                                </div>
                                @if(auth()->user()->bio)
                                    <div class="oc-provider-item mt-2"><strong>نبذة:</strong> {{ Str::limit(auth()->user()->bio, 100) }}</div>
                                @endif
                            </div>

                            {{-- Submit --}}
                            <button type="submit" class="oc-submit-btn" id="submitBtn">
                                <i class="fas fa-paper-plane" id="submitIcon"></i>
                                <span id="submitText">تقديم العرض</span>
                            </button>
                            <a href="{{ route('services.show', $service->slug) }}" class="oc-cancel-link">إلغاء والعودة</a>
                        </form>
                    </div>
                </div>
            </div>

            {{-- Sidebar --}}
            <div class="col-lg-4">
                <div class="oc-sidebar-card" style="position:sticky; top:100px;">
                    <div class="oc-sidebar-header"><i class="fas fa-lightbulb"></i> نصائح لعرض ناجح</div>
                    <div class="oc-sidebar-body">
                        <div class="oc-tip"><i class="fas fa-check-circle"></i><span>حدد سعرًا عادلًا ومنافسًا يعكس جودة عملك</span></div>
                        <div class="oc-tip"><i class="fas fa-check-circle"></i><span>اشرح خبرتك في هذا المجال وما يميزك</span></div>
                        <div class="oc-tip"><i class="fas fa-check-circle"></i><span>حدد المدة المتوقعة لإنجاز الخدمة</span></div>
                        <div class="oc-tip"><i class="fas fa-check-circle"></i><span>كن واضحًا بشأن ما يشمله العرض وما لا يشمله</span></div>
                        <div class="oc-tip"><i class="fas fa-check-circle"></i><span>الرد السريع يزيد فرص قبول عرضك</span></div>
                    </div>
                </div>

                <div class="oc-sidebar-card">
                    <div class="oc-sidebar-header"><i class="fas fa-shield-alt"></i> ضمانات</div>
                    <div class="oc-sidebar-body">
                        <div class="oc-tip"><i class="fas fa-lock" style="color:var(--e-primary);"></i><span>بياناتك محمية ولن تُشارك مع أطراف خارجية</span></div>
                        <div class="oc-tip"><i class="fas fa-handshake" style="color:var(--e-primary);"></i><span>لن يتم خصم أي مبلغ حتى يتم قبول عرضك</span></div>
                        <div class="oc-tip"><i class="fas fa-undo" style="color:var(--e-primary);"></i><span>يمكنك تعديل أو سحب عرضك قبل القبول</span></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    var form = document.getElementById('offerForm');
    if (form) {
        form.addEventListener('submit', function() {
            var btn = document.getElementById('submitBtn');
            var icon = document.getElementById('submitIcon');
            var text = document.getElementById('submitText');
            if (btn) { btn.style.pointerEvents = 'none'; btn.style.opacity = '0.8'; }
            if (icon) icon.className = 'fas fa-spinner fa-spin';
            if (text) text.textContent = 'جاري الإرسال...';
        });
    }
});
</script>
@endpush

@endsection
