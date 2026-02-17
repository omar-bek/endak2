@extends('layouts.app')

@section('title', 'تسجيل الدخول')

@section('content')
    <div class="auth-container login-mode" id="authContainer">
        <div class="auth-card login-card">
            <div class="side-panel left-panel">
                <div class="content text-center">
                    <i class="fas fa-door-open fa-3x text-warning mb-3"></i>
                    <h2>{{ __('messages.login_welcome') }}</h2>
                    <p>{{ __('messages.no_account') }}</p>
                    <button class="btn btn-outline-light mt-3 switch-btn" id="switchToRegister">
                        {{ __('messages.create_new_account') }}</button>
                </div>
            </div>

            <div class="form-section fadeInRight">
                <div class="logo mb-4 text-center">
                    <a href="{{ route('home') }}" class="text-decoration-none text-dark fs-3 fw-bold">
                        <img src="{{ asset(\App\Models\SystemSetting::get('site_logo', 'home.png')) }}" alt="Endak Logo"
                            class="me-2" style="height: 50px; width: auto;"> Endak
                    </a>
                </div>

                <form method="POST" action="{{ route('login') }}">
                    @csrf
                    <div class="mb-3 position-relative">
                        <i class="fas fa-envelope input-icon text-secondary"></i>
                        <input type="email" class="form-control @error('email') is-invalid @enderror" name="email"
                            placeholder="{{ __('messages.email') }}" value="{{ old('email') }}" required autofocus>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3 position-relative">
                        <i class="fas fa-lock input-icon text-secondary"></i>
                        <input type="password" class="form-control @error('password') is-invalid @enderror" name="password"
                            placeholder="{{ __('messages.password') }}" required>
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="remember" name="remember">
                            <label class="form-check-label" for="remember">{{ __('messages.remember_me') }}</label>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-login w-100 mb-3">
                        <i class="fas fa-sign-in-alt me-2"></i>{{ __('messages.login') }}
                    </button>

                    <!-- Social Login Buttons -->
                    <div class="social-login-section">
                        <div class="divider mb-3">
                            <span class="divider-text">{{ __('messages.or') }}</span>
                        </div>

                        {{--  <a href="{{ route('auth.facebook') }}" class="btn btn-social btn-facebook w-100 mb-2">
                            <i class="fab fa-facebook-f me-2"></i>
                            {{ __('messages.login_with_facebook') }}
                        </a>  --}}

                        <a href="{{ route('auth.google') }}" class="btn btn-social btn-google w-100">
                            <i class="fab fa-google me-2"></i>
                            {{ __('messages.login_with_google') }}
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.getElementById("switchToRegister").addEventListener("click", function() {
            window.location.href = "{{ route('register') }}";
        });
    </script>

    <style>
        body {
            font-family: 'Cairo', sans-serif;
            background: #f5f6fa;
            overflow-x: hidden;
        }

        .auth-container {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .auth-card {
            display: flex;
            width: 900px;
            max-width: 95%;
            background: #fff;
            border-radius: 20px;
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            animation: slideInLeft 0.8s ease;
        }

        .side-panel {
            width: 45%;
            background: linear-gradient(135deg, #2f5c69, #3c7d8b);
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
            padding: 2rem;
        }

        .side-panel .btn {
            border-radius: 30px;
            padding: 0.6rem 1.5rem;
            border: 2px solid #fff;
            color: #fff;
            transition: all 0.3s ease;
        }

        .side-panel .btn:hover {
            background: #f3a446;
            border-color: #f3a446;
        }

        .form-section {
            width: 55%;
            padding: 3rem;
        }

        .form-control {
            border: 1px solid #ccc;
            border-radius: 10px;
            padding: 0.75rem 2.8rem 0.75rem 1rem;
            color: #333;
            font-size: 15px;
        }

        .form-control::placeholder {
            color: #999;
            font-style: italic;
            opacity: 0.9;
        }

        .input-icon {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #777;
            pointer-events: none;
        }

        .form-control:focus {
            border-color: #3c7d8b;
            box-shadow: 0 0 6px rgba(47, 92, 105, 0.3);
            outline: none;
        }

        /* ========== زر تسجيل الدخول ========== */
        .btn-login {
            background: linear-gradient(90deg, #2f5c69, #3c7d8b);
            border: none;
            border-radius: 30px;
            padding: 0.75rem;
            color: #fff;
            font-weight: 600;
            transition: transform 0.3s, background 0.3s;
        }

        .btn-login:hover {
            transform: translateY(-3px);
            background: #f3a446;
        }

        /* Social Login Buttons */
        .social-login-section {
            margin-top: 1.5rem;
        }

        .divider {
            text-align: center;
            position: relative;
            margin: 1.5rem 0;
        }

        .divider::before,
        .divider::after {
            content: '';
            position: absolute;
            top: 50%;
            width: 45%;
            height: 1px;
            background: #ddd;
        }

        .divider::before {
            right: 0;
        }

        .divider::after {
            left: 0;
        }

        .divider-text {
            background: #fff;
            padding: 0 1rem;
            color: #999;
            font-size: 0.9rem;
            position: relative;
            z-index: 1;
        }

        .btn-social {
            border-radius: 10px;
            padding: 0.75rem;
            font-weight: 600;
            transition: all 0.3s ease;
            border: 1px solid #ddd;
            display: flex;
            align-items: center;
            justify-content: center;
            text-decoration: none;
        }

        .btn-facebook {
            background: #1877f2;
            color: #fff;
            border-color: #1877f2;
        }

        .btn-facebook:hover {
            background: #166fe5;
            border-color: #166fe5;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(24, 119, 242, 0.3);
            color: #fff;
        }

        .btn-google {
            background: #fff;
            color: #4285f4;
            border-color: #ddd;
        }

        .btn-google:hover {
            background: #f8f9fa;
            border-color: #4285f4;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(66, 133, 244, 0.2);
            color: #4285f4;
        }

        @keyframes slideInLeft {
            from {
                opacity: 0;
                transform: translateX(-100px);
            }

            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        @media (max-width: 768px) {
            .auth-card {
                flex-direction: column;
                width: 95%;
                animation: slideDown 0.8s ease;
            }

            .side-panel {
                width: 100%;
                border-radius: 20px 20px 0 0;
                padding: 1.5rem;
            }

            .form-section {
                width: 100%;
                padding: 2rem;
            }

            @keyframes slideDown {
                from {
                    opacity: 0;
                    transform: translateY(-80px);
                }

                to {
                    opacity: 1;
                    transform: translateY(0);
                }
            }
        }
    </style>
@endsection
