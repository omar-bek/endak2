@extends('layouts.app')

@section('title', 'التسجيل')

@section('content')
    <div class="auth-container register-mode" id="authContainer">
        <div class="auth-card register-card">
            <div class="side-panel right-panel">
                <div class="content text-center">
                    <i class="fas fa-user-plus fa-3x text-warning mb-3"></i>
                    <h2>{{ __('messages.register_welcome') }}</h2>
                    <p>{{ __('messages.already_have_account') }}</p>
                    <button class="btn btn-outline-light mt-3 switch-btn"
                        id="switchToLogin">{{ __('messages.login') }}</button>
                </div>
            </div>

            <div class="form-section fadeInLeft">
                <div class="logo mb-4 text-center">
                    <a href="{{ route('home') }}" class="text-decoration-none text-dark fs-3 fw-bold">
                        <img src="{{ asset(\App\Models\SystemSetting::get('site_logo', 'home.png')) }}" alt="Endak Logo"
                            class="me-2" style="height: 50px; width: auto;">Endak
                    </a>
                </div>

                <!-- Social Login Buttons -->
                <div class="social-login-section mb-4">
                    <a href="{{ route('auth.google') }}" class="btn btn-social btn-google w-100 mb-3">
                        <i class="fab fa-google me-2"></i>
                        {{ __('messages.register_with_google') ?? 'التسجيل بجوجل' }}
                    </a>

                    <div class="divider mb-3">
                        <span class="divider-text">{{ __('messages.or') ?? 'أو' }}</span>
                    </div>
                </div>

                <form method="POST" action="{{ route('register') }}">
                    @csrf
                    <div class="mb-3 position-relative">
                        <i class="fas fa-user input-icon text-secondary"></i>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" name="name"
                            placeholder="{{ __('messages.full_name') }}" value="{{ old('name') }}" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3 position-relative">
                        <i class="fas fa-envelope input-icon text-secondary"></i>
                        <input type="email" class="form-control @error('email') is-invalid @enderror" name="email"
                            placeholder="{{ __('messages.email') }}" value="{{ old('email') }}" required>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3 position-relative">
                        <i class="fas fa-mobile-alt input-icon text-secondary"></i>
                        <input type="tel" class="form-control @error('phone') is-invalid @enderror" name="phone"
                            placeholder="{{ __('messages.phone') }}" value="{{ old('phone') }}" required>
                        <small class="form-text text-muted ms-2">
                            <i class="fas fa-info-circle me-1"></i>
                            {{ __('messages.phone_note') }}
                        </small>
                        @error('phone')
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

                    <div class="mb-3 position-relative">
                        <i class="fas fa-users input-icon text-secondary"></i>
                        <select class="form-control @error('user_type') is-invalid @enderror" name="user_type" required>
                            <option value="" disabled selected> {{ __('messages.select_account_type') }} </option>
                            <option value="customer" {{ old('user_type') == 'customer' ? 'selected' : '' }}>
                                {{ __('messages.user_regular') }}
                            </option>
                            <option value="provider" {{ old('user_type') == 'provider' ? 'selected' : '' }}>
                                {{ __('messages.user_provider') }}
                            </option>
                        </select>
                        @error('user_type')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-check mb-3 ">
                        <input class="form-check-input @error('terms') is-invalid @enderror" type="checkbox" id="terms"
                            name="terms" value="1" required>
                        <label class="form-check-label" for="terms">
                            <a href="#" class=" text-primary" data-bs-toggle="modal" data-bs-target="#termsModal">
                                {{ __('messages.terms_accept') }}</a>
                        </label>
                        @error('terms')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    @include('partials.terms-modal')

                    <button type="submit" class="btn btn-login w-100">
                        <i class="fas fa-user-plus me-2"></i>{{ __('messages.create_account') }}
                    </button>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.getElementById("switchToLogin").addEventListener("click", function() {
            window.location.href = "{{ route('login') }}";
        });
    </script>

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700&display=swap');

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
            flex-direction: row-reverse;
            width: 900px;
            max-width: 95%;
            background: #fff;
            border-radius: 20px;
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            animation: slideInRight 0.8s ease;
        }

        .side-panel {
            width: 45%;
            background: linear-gradient(135deg, var(--e-primary), var(--e-primary-light));
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
            background: var(--e-accent);
            border-color: var(--e-accent);
        }

        .form-section {
            width: 55%;
            padding: 3rem;
        }

        .position-relative {
            position: relative;
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
            font-size: 1rem;
        }

        .form-control::placeholder {
            color: #aaa;
        }

        .form-control:focus {
            border-color: var(--e-primary);
            box-shadow: 0 0 5px rgba(47, 92, 105, 0.3);
            outline: none;
        }

        .btn-login {
            background: linear-gradient(90deg, var(--e-primary), var(--e-primary-light));
            border: none;
            border-radius: 30px;
            padding: 0.75rem;
            color: #fff;
            font-weight: 600;
            transition: transform 0.3s, background 0.3s;
        }

        .btn-login:hover {
            transform: translateY(-3px);
            background: var(--e-accent);
        }

        /* Social Login Buttons */
        .social-login-section {
            margin-top: 0;
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

        @keyframes slideInRight {
            from {
                opacity: 0;
                transform: translateX(100px);
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
            }

            .form-section {
                width: 100%;
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
