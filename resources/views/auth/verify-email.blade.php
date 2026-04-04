@extends('layouts.app')

@php $seoNoindex = true; @endphp
@section('title', 'التحقق من البريد الإلكتروني')

@section('content')
<div class="auth-container">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-6">
                <div class="card shadow-lg border-0" style="border-radius: 20px; margin-top: 50px;">
                    <div class="card-body p-5">
                        <!-- Header -->
                        <div class="text-center mb-4">
                            <div class="email-icon mb-3">
                                <i class="fas fa-envelope fa-4x text-primary"></i>
                            </div>
                            <h3 class="fw-bold text-dark mb-2">التحقق من البريد الإلكتروني</h3>
                            <p class="text-muted">تم إرسال رابط التحقق إلى بريدك الإلكتروني</p>
                            <p class="text-primary fw-bold">{{ Auth::user()->email }}</p>
                        </div>

                        <!-- Success Message -->
                        @if (session('success'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                <i class="fas fa-check-circle me-2"></i>
                                {{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif

                        <!-- Warning Message -->
                        @if (session('warning'))
                            <div class="alert alert-warning alert-dismissible fade show" role="alert">
                                <i class="fas fa-exclamation-triangle me-2"></i>
                                {{ session('warning') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif

                        <!-- Info Message -->
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-2"></i>
                            <strong>قبل المتابعة، يرجى التحقق من بريدك الإلكتروني للحصول على رابط التحقق.</strong>
                            <br>
                            إذا لم تستلم البريد الإلكتروني، اضغط على الزر أدناه لإعادة الإرسال.
                        </div>

                        <!-- Resend Form -->
                        <form method="POST" action="{{ route('verification.send') }}" id="resendForm">
                            @csrf
                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-primary btn-lg" id="resendBtn">
                                    <i class="fas fa-paper-plane me-2"></i>
                                    إعادة إرسال رابط التحقق
                                </button>
                            </div>
                        </form>

                        <!-- Help Text -->
                        <div class="mt-4 text-center">
                            <p class="text-muted small">
                                <i class="fas fa-question-circle me-1"></i>
                                لا تستقبل البريد الإلكتروني؟ تحقق من مجلد الرسائل غير المرغوب فيها (Spam)
                            </p>
                            <form method="POST" action="{{ route('logout') }}" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-link text-muted small p-0">
                                    <i class="fas fa-sign-out-alt me-1"></i>تسجيل الخروج وتسجيل بحساب آخر
                                </button>
                            </form>
                        </div>
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

    .email-icon {
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
</style>

<script>
    document.getElementById('resendForm').addEventListener('submit', function() {
        var btn = document.getElementById('resendBtn');
        btn.disabled = true;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>جاري الإرسال...';

        // Cooldown 60 seconds
        var seconds = 60;
        var interval = setInterval(function() {
            seconds--;
            if (seconds <= 0) {
                clearInterval(interval);
                btn.disabled = false;
                btn.innerHTML = '<i class="fas fa-paper-plane me-2"></i>إعادة إرسال رابط التحقق';
            } else {
                btn.innerHTML = '<i class="fas fa-clock me-2"></i>إعادة الإرسال بعد ' + seconds + ' ثانية';
            }
        }, 1000);
    });
</script>
@endsection

