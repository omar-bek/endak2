@extends('layouts.app')

@section('title', 'إتمام الملف الشخصي')

@section('content')
<div class="auth-container">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-6">
                <div class="card shadow-lg border-0" style="border-radius: 20px; margin-top: 50px;">
                    <div class="card-body p-5">
                        <!-- Header -->
                        <div class="text-center mb-4">
                            <div class="profile-icon mb-3">
                                <i class="fas fa-user-circle fa-4x text-primary"></i>
                            </div>
                            <h3 class="fw-bold text-dark mb-2">إتمام الملف الشخصي</h3>
                            <p class="text-muted">يرجى اختيار نوع الحساب والموافقة على الشروط والأحكام للمتابعة</p>
                        </div>

                        <!-- Success Message -->
                        @if (session('success'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                <i class="fas fa-check-circle me-2"></i>
                                {{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif

                        <!-- Error Message -->
                        @if (session('error'))
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <i class="fas fa-exclamation-circle me-2"></i>
                                {{ session('error') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif

                        <!-- Form -->
                        <form method="POST" action="{{ route('save-user-type') }}">
                            @csrf
                            
                            <div class="mb-4 position-relative">
                                <i class="fas fa-users input-icon text-secondary"></i>
                                <label class="form-label fw-bold mb-2">اختر نوع الحساب <span class="text-danger">*</span></label>
                                <select class="form-control @error('user_type') is-invalid @enderror" name="user_type" required>
                                    <option value="" disabled selected>اختر نوع الحساب</option>
                                    <option value="customer" {{ old('user_type') == 'customer' ? 'selected' : '' }}>مستخدم عادي (لطلب الخدمات)</option>
                                    <option value="provider" {{ old('user_type') == 'provider' ? 'selected' : '' }}>مزود خدمة (لعرض الخدمات)</option>
                                </select>
                                @error('user_type')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-check mb-4">
                                <input class="form-check-input @error('terms') is-invalid @enderror" type="checkbox" id="terms"
                                    name="terms" value="1" required>
                                <label class="form-check-label" for="terms">
                                    أوافق على <a href="#" class="text-primary" data-bs-toggle="modal"
                                        data-bs-target="#termsModal">الشروط والأحكام</a> <span class="text-danger">*</span>
                                </label>
                                @error('terms')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Terms Modal -->
                            <div class="modal fade" id="termsModal" tabindex="-1" aria-labelledby="termsModalLabel"
                                aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered modal-lg">
                                    <div class="modal-content border-0 shadow-lg">
                                        <div class="modal-header"
                                            style="background: linear-gradient(135deg, #2f5c69, #3c7d8b); color: #fff;">
                                            <h5 class="modal-title" id="termsModalLabel">الشروط والأحكام - موقع Endak</h5>
                                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                                                aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body p-4"
                                            style="max-height: 70vh; overflow-y: auto; background-color: #f9fbfc;">
                                            <h6 class="fw-bold mb-2 text-primary">مرحباً بك في Endak!</h6>
                                            <p class="text-muted mb-4">باستخدامك لموقع Endak فإنك توافق على الشروط والأحكام التالية.
                                                نرجو قراءتها بعناية قبل البدء في استخدام خدماتنا.</p>

                                            <h6 class="fw-bold">1. قبول الشروط</h6>
                                            <p class="text-muted">يعتبر دخولك أو استخدامك لموقع Endak بمثابة موافقة كاملة منك على
                                                الالتزام بجميع الشروط والسياسات الخاصة بالموقع.</p>

                                            <h6 class="fw-bold mt-4">2. استخدام الموقع</h6>
                                            <p class="text-muted">يُسمح باستخدام الموقع فقط للأغراض القانونية والمشروعة، ويُمنع
                                                استخدامه في أي أنشطة مخالفة للقانون أو تسبب ضررًا للآخرين.</p>

                                            <h6 class="fw-bold mt-4">3. الحسابات والمسؤولية</h6>
                                            <p class="text-muted">أنت مسؤول عن سرية بيانات تسجيل الدخول الخاصة بك، وعن جميع الأنشطة
                                                التي تتم عبر حسابك. يحتفظ الموقع بحق إيقاف أي حساب يخالف القواعد.</p>

                                            <h6 class="fw-bold mt-4">4. الخدمات والضمانات</h6>
                                            <p class="text-muted">يُقدم موقع Endak خدماته بأعلى جودة ممكنة، ولكننا لا نضمن أن تكون
                                                الخدمة خالية من الأخطاء أو الانقطاعات التقنية.</p>

                                            <h6 class="fw-bold mt-4">5. سياسة الخصوصية</h6>
                                            <p class="text-muted">نحترم خصوصيتك ونحافظ على بياناتك الشخصية. يتم استخدام المعلومات
                                                فقط لتحسين تجربتك وتقديم خدمات أفضل.</p>

                                            <h6 class="fw-bold mt-4">6. حقوق الملكية الفكرية</h6>
                                            <p class="text-muted">جميع الحقوق محفوظة لموقع Endak. لا يجوز نسخ أو إعادة استخدام أي
                                                محتوى دون إذن خطي مسبق من إدارة الموقع.</p>

                                            <h6 class="fw-bold mt-4">7. التعديلات على الشروط</h6>
                                            <p class="text-muted">يحتفظ الموقع بحق تعديل هذه الشروط في أي وقت. سيتم إخطار
                                                المستخدمين بالتحديثات عبر الموقع أو البريد الإلكتروني.</p>

                                            <h6 class="fw-bold mt-4">8. إخلاء المسؤولية</h6>
                                            <p class="text-muted">Endak غير مسؤول عن أي خسائر أو أضرار مباشرة أو غير مباشرة ناتجة
                                                عن استخدام خدمات الموقع.</p>

                                            <h6 class="fw-bold mt-4">9. التواصل معنا</h6>
                                            <p class="text-muted">لأي استفسارات أو شكاوى يمكنك التواصل معنا عبر البريد الإلكتروني
                                                الرسمي للموقع.</p>

                                            <p class="fw-semibold mt-4 text-center text-primary">باستخدامك للموقع، فإنك تقر بأنك
                                                قرأت وفهمت ووافقت على هذه الشروط والأحكام.</p>
                                        </div>
                                        <div class="modal-footer border-0 d-flex justify-content-center"
                                            style="background: #f9fbfc;">
                                            <button type="button" class="btn text-white px-4"
                                                style="background: linear-gradient(135deg, #2f5c69, #3c7d8b);"
                                                data-bs-dismiss="modal">موافق</button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-primary btn-lg">
                                    <i class="fas fa-check-circle me-2"></i>
                                    إتمام الملف الشخصي
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .auth-container {
        min-height: 100vh;
        display: flex;
        align-items: center;
        background: #f5f6fa;
    }

    .profile-icon {
        animation: pulse 2s infinite;
    }

    @keyframes pulse {
        0%, 100% {
            transform: scale(1);
        }
        50% {
            transform: scale(1.1);
        }
    }

    .card {
        animation: slideInUp 0.5s ease;
    }

    @keyframes slideInUp {
        from {
            opacity: 0;
            transform: translateY(30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .input-icon {
        position: absolute;
        right: 15px;
        top: 50%;
        transform: translateY(-50%);
        font-size: 1rem;
        color: #999;
    }

    .form-control {
        border: 1px solid #ccc;
        border-radius: 10px;
        padding: 0.75rem 2.8rem 0.75rem 1rem;
    }

    .form-control:focus {
        border-color: #2f5c69;
        box-shadow: 0 0 5px rgba(47, 92, 105, 0.3);
        outline: none;
    }
</style>
@endsection

