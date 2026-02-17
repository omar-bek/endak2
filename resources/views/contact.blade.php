@extends('layouts.app')

@section('title', 'اتصل بنا')

@section('content')

<div class="contact-page-wrapper">

    <!-- Header Section -->
    <section class="contact-header">
        <div class="container text-center">
            <h1 class="header-title animate-fade-in-down">تواصل معنا</h1>
            <p class="header-subtitle animate-fade-in-up">هل لديك سؤال؟ فريقنا جاهز لمساعدتك في إنجاز أعمالك وتحقيق أهدافك.</p>
        </div>
        <div class="header-shape-divider">
            <svg data-name="Layer 1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1200 120" preserveAspectRatio="none">
                <path d="M321.39,56.44c58-10.79,114.16-30.13,172-41.86,82.39-16.72,168.19-17.73,250.45-.39C823.78,31,906.67,72,985.66,92.83c70.05,18.48,146.53,26.09,214.34,3V0H0V27.35A600.21,600.21,0,0,0,321.39,56.44Z" class="shape-fill"></path>
            </svg>
        </div>
    </section>

    <!-- Contact Section -->
    <section class="contact-body-section">
        <div class="container">
            <div class="row g-4 g-lg-5">
                <div class="col-lg-7 animate-fade-in-right">
                    <div class="contact-form-card">
                        <div class="card-header">
                            <i class="fas fa-paper-plane me-2"></i>
                            <h5 class="mb-0">أرسل لنا رسالة مباشرة</h5>
                        </div>
                        <div class="card-body">
                            <form>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <div class="form-floating">
                                            <input type="text" class="form-control" id="name" name="name" placeholder="الاسم الكامل" required>
                                            <label for="name">الاسم الكامل *</label>
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <div class="form-floating">
                                            <input type="email" class="form-control" id="email" name="email" placeholder="البريد الإلكتروني" required>
                                            <label for="email">البريد الإلكتروني *</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <div class="form-floating">
                                        <input type="text" class="form-control" id="subject" name="subject" placeholder="الموضوع" required>
                                        <label for="subject">الموضوع *</label>
                                    </div>
                                </div>
                                <div class="mb-4">
                                    <div class="form-floating">
                                        <textarea class="form-control" id="message" name="message" placeholder="اكتب رسالتك هنا" rows="6" required></textarea>
                                        <label for="message">الرسالة *</label>
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-submit-message">
                                    <span>أرسل رسالتك الآن</span>
                                    <i class="fas fa-arrow-left"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="col-lg-5 animate-fade-in-left">
                    <div class="contact-info-card">
                        <div class="card-header">
                            <i class="fas fa-info-circle me-2"></i>
                            <h5 class="mb-0">معلومات التواصل</h5>
                        </div>
                        <div class="card-body">
                            <div class="info-item">
                                <div class="info-icon"><i class="fas fa-envelope"></i></div>
                                <div>
                                    <h6>البريد الإلكتروني</h6>
                                    <a href="mailto:info@endak.com">info@endak.com</a>
                                </div>
                            </div>
                            <div class="info-item">
                                <div class="info-icon"><i class="fas fa-phone"></i></div>
                                <div>
                                    <h6>الهاتف</h6>
                                    <a href="tel:{{ \App\Helpers\WhatsAppHelper::getWhatsAppNumber() }}">{{ \App\Helpers\WhatsAppHelper::getWhatsAppNumber() }}</a>
                                </div>
                            </div>
                            <div class="info-item">
                                <div class="info-icon"><i class="fas fa-map-marker-alt"></i></div>
                                <div>
                                    <h6>العنوان</h6>
                                    <small>الرياض، المملكة العربية السعودية</small>
                                </div>
                            </div>
                             <div class="info-item">
                                <div class="info-icon"><i class="fas fa-clock"></i></div>
                                <div>
                                    <h6>ساعات العمل</h6>
                                    <small>الأحد - الخميس: 8 ص - 6 م</small>
                                </div>
                            </div>

                            @if (\App\Helpers\WhatsAppHelper::isEnabled())
                                <div class="mt-4">
                                    {!! \App\Helpers\WhatsAppHelper::getWhatsAppButton(
                                        'تواصل معنا عبر الواتساب',
                                        null,
                                        'btn btn-whatsapp w-100',
                                    ) !!}
                                </div>
                            @endif
                        </div>
                    </div>

                    <div class="social-media-card mt-4">
                        <div class="card-header">
                             <i class="fas fa-share-alt me-2"></i>
                            <h5 class="mb-0">تابعنا على الشبكات الاجتماعية</h5>
                        </div>
                        <div class="card-body">
                            <div class="social-links">
                                <a href="#" class="social-icon facebook"><i class="fab fa-facebook-f"></i></a>
                                <a href="#" class="social-icon twitter"><i class="fab fa-twitter"></i></a>
                                <a href="#" class="social-icon instagram"><i class="fab fa-instagram"></i></a>
                                <a href="#" class="social-icon linkedin"><i class="fab fa-linkedin-in"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- FAQ Section -->
    <section class="faq-section">
        <div class="container">
            <div class="text-center mb-5 animate-fade-in-up">
                <h2 class="section-title">الأسئلة الشائعة</h2>
                <p class="section-subtitle">إجابات على أكثر الأسئلة شيوعاً لمساعدتك على البدء في "عندك".</p>
            </div>
            <div class="row">
                <div class="col-lg-10 mx-auto">
                    <div class="accordion" id="faqAccordion">
                        <!-- FAQ Item 1 -->
                        <div class="accordion-item animate-fade-in-up" style="animation-delay: 0.1s;">
                            <h2 class="accordion-header">
                                <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#faq1">
                                    ما هو موقع "عندك" وكيف يعمل؟
                                </button>
                            </h2>
                            <div id="faq1" class="accordion-collapse collapse show" data-bs-parent="#faqAccordion">
                                <div class="accordion-body">
                                    "عندك" هو منصة إلكترونية تربط بين أصحاب المشاريع والباحثين عن خدمات (المشترين) مع محترفين ومستقلين مهرة (مقدمي الخدمات). يمكن للمشتري نشر خدمة يحتاجها، ويقوم مقدمو الخدمات بتقديم عروضهم لإنجازها، ثم يختار المشتري العرض الأنسب له.
                                </div>
                            </div>
                        </div>
                         <!-- FAQ Item 2 (For Buyers) -->
                        <div class="accordion-item animate-fade-in-up" style="animation-delay: 0.2s;">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq2">
                                    كيف أضمن جودة الخدمة التي سأحصل عليها؟
                                </button>
                            </h2>
                            <div id="faq2" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                <div class="accordion-body">
                                    نحن نوفر لك عدة أدوات لضمان الجودة: يمكنك مراجعة الملف الشخصي لمقدم الخدمة، معرض أعماله، وتقييمات العملاء السابقين. كما أن نظام الدفع لدينا يضمن عدم تحويل المبلغ للمستقل إلا بعد موافقتك على الخدمة المستلمة.
                                </div>
                            </div>
                        </div>
                        <!-- FAQ Item 3 (For Freelancers) -->
                        <div class="accordion-item animate-fade-in-up" style="animation-delay: 0.3s;">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq3">
                                    أنا مستقل، كيف أبدأ بتقديم خدماتي؟
                                </button>
                            </h2>
                            <div id="faq3" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                <div class="accordion-body">
                                    الأمر بسيط! قم بإنشاء حساب كمقدم خدمة، أكمل ملفك الشخصي بشكل احترافي، أضف معرض أعمال يبرز مهاراتك، ثم ابدأ بتصفح الخدمات المطلوبة وقدم عروضك المميزة للمشاريع التي تناسب خبراتك.
                                </div>
                            </div>
                        </div>
                         <!-- FAQ Item 4 -->
                        <div class="accordion-item animate-fade-in-up" style="animation-delay: 0.4s;">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq4">
                                    ما هي طرق الدفع المتاحة وكيف يتم تأمينها؟
                                </button>
                            </h2>
                            <div id="faq4" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                <div class="accordion-body">
                                    نحن ندعم جميع طرق الدفع الشائعة مثل البطاقات الائتمانية والتحويلات البنكية. جميع المعاملات المالية مؤمنة ومشفرة. يتم حجز المبلغ المدفوع لدينا ولا يتم الإفراج عنه لمقدم الخدمة إلا بعد تأكيدك استلام الخدمة ورضاك عنها.
                                </div>
                            </div>
                        </div>
                         <!-- FAQ Item 5 -->
                        <div class="accordion-item animate-fade-in-up" style="animation-delay: 0.5s;">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq5">
                                    ماذا أفعل في حال حدوث خلاف مع الطرف الآخر؟
                                </button>
                            </h2>
                            <div id="faq5" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                <div class="accordion-body">
                                    نسعى دائمًا لضمان تجربة سلسة للجميع. في حال حدوث أي خلاف، يمكنك فتح نزاع من خلال صفحة الخدمة. سيقوم فريق الدعم لدينا بمراجعة المشكلة والعمل كوسيط للوصول إلى حل عادل للطرفين.
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<style>
    /* === Font Import === */
    @import url('https://fonts.googleapis.com/css2?family=Cairo:wght@400;500;600;700&display=swap');

    /* === Color & Style Variables (from Navbar) === */
    :root {
        --bg-dark: #2f5c69;
        --bg-dark-rgb: 47, 92, 105;
        --accent: #f3a446;
        --accent-hover: #ffb861;
        --page-bg: #f8f9fa;
        --card-bg: #ffffff;
        --text-dark: #212529;
        --text-muted: #6c757d;
        --border-color: #dee2e6;
    }

    /* === Main Page Wrapper === */
    .contact-page-wrapper {
        font-family: 'Cairo', sans-serif;
        background-color: var(--page-bg);
    }

    /* === Header Section === */
    .contact-header {
        background-color: var(--bg-dark);
        color: #fff;
        padding: 6rem 0 8rem 0;
        position: relative;
        overflow: hidden;
    }

    .header-title {
        font-size: 3.5rem;
        font-weight: 700;
        margin-bottom: 0.5rem;
    }

    .header-subtitle {
        font-size: 1.25rem;
        font-weight: 400;
        color: rgba(255, 255, 255, 0.8);
        max-width: 600px;
        margin: auto;
    }
    
    .header-shape-divider {
        position: absolute;
        bottom: -1px;
        left: 0;
        width: 100%;
        overflow: hidden;
        line-height: 0;
        transform: rotate(180deg);
    }
    .header-shape-divider svg {
        position: relative;
        display: block;
        width: calc(100% + 1.3px);
        height: 80px;
    }
    .header-shape-divider .shape-fill {
        fill: var(--page-bg);
    }


    /* === Contact Body Section === */
    .contact-body-section {
        padding: 4rem 0;
        margin-top: -50px;
        position: relative;
        z-index: 2;
    }

    /* === Form Card === */
    .contact-form-card, .contact-info-card, .social-media-card {
        background: var(--card-bg);
        border: none;
        border-radius: 15px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
        overflow: hidden;
        /* height: 100%; */
    }
    .contact-form-card .card-header, .contact-info-card .card-header, .social-media-card .card-header {
        background: var(--bg-dark);
        color: #fff;
        padding: 1.25rem 1.5rem;
        border-bottom: 3px solid var(--accent);
        display: flex;
        align-items: center;
    }
    .contact-form-card .card-body, .contact-info-card .card-body, .social-media-card .card-body {
        padding: 2rem;
    }

    .form-control {
        min-height: calc(3.5rem + 2px);
        line-height: 1.25;
        border-radius: 8px;
        border: 1px solid var(--border-color);
        transition: all 0.3s ease;
    }
    .form-control:focus {
        border-color: var(--accent);
        box-shadow: 0 0 0 0.25rem rgba(243, 164, 70, 0.25);
    }
    .form-floating > label {
        padding-right: 1rem;
    }
    textarea.form-control {
        min-height: 150px;
    }
    
    .btn-submit-message {
        background: linear-gradient(135deg, var(--accent), var(--accent-hover));
        color: var(--bg-dark);
        border: none;
        font-weight: 700;
        font-size: 1rem;
        padding: 0.8rem 2rem;
        border-radius: 50px;
        transition: all 0.4s ease;
        position: relative;
        overflow: hidden;
        display: flex;
        align-items: center;
        justify-content: space-between;
        width: 220px;
    }

    .btn-submit-message i {
        transition: transform 0.3s ease;
    }
    .btn-submit-message:hover {
        color: #fff;
        box-shadow: 0 5px 15px rgba(243, 164, 70, 0.4);
        width: 235px;
    }
     .btn-submit-message:hover i {
        transform: translateX(-5px);
    }

    /* === Info Card === */
    .info-item {
        display: flex;
        align-items: center;
        margin-bottom: 1.5rem;
        transition: transform 0.3s ease;
    }
    .info-item:hover {
        transform: translateX(5px);
    }
    .info-icon {
        flex-shrink: 0;
        width: 45px;
        height: 45px;
        border-radius: 50%;
        background-color: rgba(var(--bg-dark-rgb), 0.1);
        color: var(--bg-dark);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.2rem;
        margin-left: 1rem;
        transition: all 0.3s ease;
    }
     .info-item:hover .info-icon {
        background-color: var(--bg-dark);
        color: #fff;
        transform: scale(1.1) rotate(15deg);
     }

    .info-item h6 {
        margin-bottom: 0;
        font-weight: 600;
    }
    .info-item a, .info-item small {
        color: var(--text-muted);
        text-decoration: none;
        transition: color 0.3s ease;
    }
    .info-item a:hover {
        color: var(--accent);
    }

    .btn-whatsapp {
        background: #25D366;
        color: #fff;
        font-weight: 700;
        border-radius: 8px;
        padding: 0.8rem 1rem;
        transition: all 0.3s ease;
    }
    .btn-whatsapp:hover {
        background: #128C7E;
        color: #fff;
        transform: translateY(-2px);
    }

    .social-links {
        display: flex;
        justify-content: center;
        gap: 1rem;
    }
    .social-icon  {
        
        width: 45px;
        height: 45px;
        border-radius: 50%;
        border: 2px solid var(--border-color);
        /* color: var(--text-muted); */
        display: inline-flex;
        align-items: center;
        justify-content: center;
        /* text-decoration: none; */
        font-size: 1.2rem;
        transition: all 0.3s ease;

    }
    .social-icon i{
        color: orange;
    }

    .social-icon:hover {
        transform: translateY(-5px);
        color: #fff;
    }
    .social-icon.facebook:hover { background-color: #3b5998; border-color: #3b5998;}
    .social-icon.twitter:hover { background-color: #1da1f2; border-color: #1da1f2;}
    .social-icon.instagram:hover { background: #d6249f; background: radial-gradient(circle at 30% 107%, #fdf497 0%, #fdf497 5%, #fd5949 45%,#d6249f 60%,#285AEB 90%); border:none;}
    .social-icon.linkedin:hover { background-color: #0077b5; border-color: #0077b5;}


    /* === FAQ Section === */
    .faq-section {
        padding: 4rem 0;
        background-color: var(--card-bg);
    }
    .section-title {
        color: var(--bg-dark);
        font-weight: 700;
    }
    .section-subtitle {
        color: var(--text-muted);
        max-width: 600px;
        margin: auto;
    }
    
    .accordion-item {
        border: 1px solid var(--border-color);
        border-radius: 10px !important;
        margin-bottom: 1rem;
        overflow: hidden;
        box-shadow: 0 5px 15px rgba(0,0,0,0.05);
    }
    .accordion-button {
        font-weight: 600;
        color: var(--text-dark);
        background-color: #fff;
    }
    .accordion-button:not(.collapsed) {
        color: var(--bg-dark);
        background-color: rgba(var(--bg-dark-rgb), 0.05);
        box-shadow: inset 0 -1px 0 var(--border-color);
    }
    .accordion-button:focus {
        box-shadow: none;
        border-color: var(--accent);
    }
    .accordion-button::after {
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16' fill='%232f5c69'%3e%3cpath fill-rule='evenodd' d='M1.646 4.646a.5.5 0 0 1 .708 0L8 10.293l5.646-5.647a.5.5 0 0 1 .708.708l-6 6a.5.5 0 0 1-.708 0l-6-6a.5.5 0 0 1 0-.708z'/%3e%3c/svg%3e");
        transition: transform .3s ease-in-out;
    }
    .accordion-button:not(.collapsed)::after {
        transform: rotate(-180deg);
        filter: brightness(0) saturate(100%) invert(80%) sepia(33%) saturate(4605%) hue-rotate(338deg) brightness(98%) contrast(92%);
    }

    /* === Animations === */
    @keyframes fadeInDown { from { opacity:0; transform: translate3d(0, -30px, 0); } to { opacity:1; transform: translate3d(0, 0, 0); } }
    .animate-fade-in-down { animation: fadeInDown 0.8s ease-out both; }

    @keyframes fadeInUp { from { opacity:0; transform: translate3d(0, 30px, 0); } to { opacity:1; transform: translate3d(0, 0, 0); } }
    .animate-fade-in-up { opacity:0; animation: fadeInUp 0.8s ease-out forwards; }

    @keyframes fadeInRight { from { opacity:0; transform: translate3d(-50px, 0, 0); } to { opacity:1; transform: translate3d(0, 0, 0); } }
    .animate-fade-in-right { opacity:0; animation: fadeInRight 1s ease-out forwards; }

    @keyframes fadeInLeft { from { opacity:0; transform: translate3d(50px, 0, 0); } to { opacity:1; transform: translate3d(0, 0, 0); } }
    .animate-fade-in-left { opacity:0; animation: fadeInLeft 1s ease-out forwards; animation-delay: 0.2s;}

</style>

@endsection