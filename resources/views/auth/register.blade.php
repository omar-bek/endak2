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
<button class="btn btn-outline-light mt-3 switch-btn" id="switchToLogin">{{ __('messages.login') }}</button>
                </div>
            </div>

            <div class="form-section fadeInLeft">
                <div class="logo mb-4 text-center">
                    <a href="{{ route('home') }}" class="text-decoration-none text-dark fs-3 fw-bold">
                        <img src="{{ asset(\App\Models\SystemSetting::get('site_logo', 'home.png')) }}" alt="Endak Logo"
                            class="me-2" style="height: 50px; width: auto;">Endak
                    </a>
                </div>

                <form method="POST" action="{{ route('register') }}">
                    @csrf
                    <div class="mb-3 position-relative">
                        <i class="fas fa-user input-icon text-secondary"></i>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" name="name"
                            placeholder="{{ __('messages.full_name') }}"  value="{{ old('name') }}" required>
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
                            placeholder="{{ __('messages.phone') }}" value="{{ old('phone') }}"
                            required>
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
                            <option value="customer" {{ old('user_type') == 'customer' ? 'selected' : '' }}>{{ __('messages.user_regular') }} 
                                </option>
                            <option value="provider" {{ old('user_type') == 'provider' ? 'selected' : '' }}> {{ __('messages.user_provider') }}
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
                              <a href="#" class=" text-primary" data-bs-toggle="modal"
                                data-bs-target="#termsModal">   {{ __('messages.terms_accept') }}</a>
                        </label>
                        @error('terms')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="modal fade" id="termsModal" tabindex="-1" aria-labelledby="termsModalLabel"
                        aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered modal-lg">
                            <div class="modal-content border-0 shadow-lg">
                                <div class="modal-header"
                                    style="background: linear-gradient(135deg, #2f5c69, #3c7d8b); color: #fff;">
                                    <h5 class="modal-title" id="termsModalLabel">  {{ __('messages.terms_title') }} </h5>
                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                               <div class="modal-body p-4" style="max-height: 70vh; overflow-y: auto; background-color: #f9fbfc;">
    <h6 class="fw-bold mb-2 text-primary">{{ __('messages.terms_welcome') }}</h6>
    <p class="text-muted mb-4">{{ __('messages.terms_intro') }}</p>

    <h6 class="fw-bold">{{ __('messages.terms_1_title') }}</h6>
    <p class="text-muted">{{ __('messages.terms_1_text') }}</p>

    <h6 class="fw-bold mt-4">{{ __('messages.terms_2_title') }}</h6>
    <p class="text-muted">{{ __('messages.terms_2_text') }}</p>

    <h6 class="fw-bold mt-4">{{ __('messages.terms_3_title') }}</h6>
    <p class="text-muted">{{ __('messages.terms_3_text') }}</p>

    <h6 class="fw-bold mt-4">{{ __('messages.terms_4_title') }}</h6>
    <p class="text-muted">{{ __('messages.terms_4_text') }}</p>

    <h6 class="fw-bold mt-4">{{ __('messages.terms_5_title') }}</h6>
    <p class="text-muted">{{ __('messages.terms_5_text') }}</p>

    <h6 class="fw-bold mt-4">{{ __('messages.terms_6_title') }}</h6>
    <p class="text-muted">{{ __('messages.terms_6_text') }}</p>

    <h6 class="fw-bold mt-4">{{ __('messages.terms_7_title') }}</h6>
    <p class="text-muted">{{ __('messages.terms_7_text') }}</p>

    <h6 class="fw-bold mt-4">{{ __('messages.terms_8_title') }}</h6>
    <p class="text-muted">{{ __('messages.terms_8_text') }}</p>

    <h6 class="fw-bold mt-4">{{ __('messages.terms_9_title') }}</h6>
    <p class="text-muted">{{ __('messages.terms_9_text') }}</p>

    <p class="fw-semibold mt-4 text-center text-primary">{{ __('messages.terms_agree') }}</p>
</div>

                                <div class="modal-footer border-0 d-flex justify-content-center"
                                    style="background: #f9fbfc;">
                                    <button type="button" class="btn text-white px-4"
                                        style="background: linear-gradient(135deg, #2f5c69, #3c7d8b);"
                                        data-bs-dismiss="modal">{{ __('messages.terms_modal_close') }}</button>
                                </div>
                            </div>
                        </div>
                    </div>

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
            border-color: #2f5c69;
            box-shadow: 0 0 5px rgba(47, 92, 105, 0.3);
            outline: none;
        }

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
