@extends('layouts.app')

@section('title', 'التحقق من رقم الهاتف')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-5">
                <div class="card shadow-lg border-0" style="border-radius: 20px; margin-top: 100px;">
                    <div class="card-body p-5">
                        <!-- Header -->
                        <div class="text-center mb-4">
                            <div class="whatsapp-icon mb-3">
                                <i class="fab fa-whatsapp" style="font-size: 3rem; color: #25d366;"></i>
                            </div>
                            <h3 class="fw-bold text-dark mb-2">التحقق من رقم الهاتف</h3>
                            <p class="text-muted">تم إرسال رمز التحقق إلى رقم الواتساب الخاص بك</p>
                            <p class="text-primary fw-bold">{{ session('registration_data.phone') }}</p>
                        </div>

                        <!-- Success Message -->
                        @if (session('success'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                <i class="fas fa-check-circle me-2"></i>
                                {{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif

                        <!-- OTP Form -->
                        <form method="POST" action="{{ route('verify-otp') }}" id="otpForm">
                            @csrf

                            <div class="mb-4">
                                <label for="otp_code" class="form-label fw-bold">رمز التحقق</label>
                                <div class="otp-input-container">
                                    <input type="text"
                                        class="form-control form-control-lg text-center @error('otp_code') is-invalid @enderror"
                                        id="otp_code" name="otp_code" placeholder="000000" maxlength="6" required
                                        autocomplete="off"
                                        style="font-size: 1.5rem; letter-spacing: 0.5rem; font-weight: bold;">
                                    @error('otp_code')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <small class="form-text text-muted mt-2">
                                    <i class="fas fa-info-circle me-1"></i>
                                    أدخل الرمز المكون من 6 أرقام
                                </small>
                            </div>

                            <!-- Submit Button -->
                            <div class="d-grid mb-3">
                                <button type="submit" class="btn btn-primary btn-lg" id="verifyBtn">
                                    <i class="fas fa-check me-2"></i>
                                    التحقق من الرمز
                                </button>
                            </div>

                            <!-- Resend OTP -->
                            <div class="text-center">
                                <p class="text-muted mb-2">لم تستلم الرمز؟</p>
                                <form method="POST" action="{{ route('resend-otp') }}" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-outline-primary" id="resendBtn">
                                        <i class="fas fa-redo me-2"></i>
                                        إعادة إرسال الرمز
                                    </button>
                                </form>
                            </div>

                            <!-- Timer -->
                            <div class="text-center mt-3">
                                <small class="text-muted" id="timer">
                                    يمكنك إعادة الإرسال خلال <span id="countdown">60</span> ثانية
                                </small>
                            </div>
                        </form>

                        <!-- Back to Registration -->
                        <div class="text-center mt-4">
                            <a href="{{ route('register') }}" class="text-decoration-none">
                                <i class="fas fa-arrow-right me-1"></i>
                                العودة إلى التسجيل
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .whatsapp-icon {
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0% {
                transform: scale(1);
            }

            50% {
                transform: scale(1.1);
            }

            100% {
                transform: scale(1);
            }
        }

        .otp-input-container {
            position: relative;
        }

        .otp-input-container input {
            border: 2px solid #e9ecef;
            transition: all 0.3s ease;
        }

        .otp-input-container input:focus {
            border-color: #25d366;
            box-shadow: 0 0 0 0.2rem rgba(37, 211, 102, 0.25);
        }

        .btn-primary {
            background: linear-gradient(135deg, #25d366, #128c7e);
            border: none;
            transition: all 0.3s ease;
        }

        .btn-primary:hover {
            background: linear-gradient(135deg, #128c7e, #25d366);
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(37, 211, 102, 0.3);
        }

        .btn-outline-primary {
            border-color: #25d366;
            color: #25d366;
            transition: all 0.3s ease;
        }

        .btn-outline-primary:hover {
            background-color: #25d366;
            border-color: #25d366;
            color: white;
        }

        .card {
            background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
            backdrop-filter: blur(10px);
        }

        #timer {
            font-weight: 500;
        }

        #countdown {
            color: #25d366;
            font-weight: bold;
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const otpInput = document.getElementById('otp_code');
            const verifyBtn = document.getElementById('verifyBtn');
            const resendBtn = document.getElementById('resendBtn');
            const countdownElement = document.getElementById('countdown');
            const timerElement = document.getElementById('timer');

            let countdown = 60;
            let timer;

            // Auto-focus on OTP input
            otpInput.focus();

            // Format OTP input (only numbers)
            otpInput.addEventListener('input', function(e) {
                let value = e.target.value.replace(/\D/g, '');
                e.target.value = value;

                // Auto-submit when 6 digits are entered
                if (value.length === 6) {
                    setTimeout(() => {
                        document.getElementById('otpForm').submit();
                    }, 500);
                }
            });

            // Start countdown timer
            function startCountdown() {
                resendBtn.disabled = true;
                resendBtn.innerHTML = '<i class="fas fa-clock me-2"></i>انتظر...';

                timer = setInterval(() => {
                    countdown--;
                    countdownElement.textContent = countdown;

                    if (countdown <= 0) {
                        clearInterval(timer);
                        resendBtn.disabled = false;
                        resendBtn.innerHTML = '<i class="fas fa-redo me-2"></i>إعادة إرسال الرمز';
                        timerElement.style.display = 'none';
                    }
                }, 1000);
            }

            // Start countdown on page load
            startCountdown();

            // Handle resend button click
            resendBtn.addEventListener('click', function() {
                countdown = 60;
                timerElement.style.display = 'block';
                startCountdown();
            });

            // Handle form submission
            document.getElementById('otpForm').addEventListener('submit', function() {
                verifyBtn.disabled = true;
                verifyBtn.innerHTML =
                    '<span class="spinner-border spinner-border-sm me-2"></span>جاري التحقق...';
            });

            // Handle backspace to clear previous digit
            otpInput.addEventListener('keydown', function(e) {
                if (e.key === 'Backspace' && this.value.length === 0) {
                    this.previousElementSibling?.focus();
                }
            });
        });
    </script>
@endsection

