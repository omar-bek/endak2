@extends('layouts.app')

@php $seoNoindex = true; @endphp
@section('title', __('messages.create_account'))

@section('content')
    <div class="auth-page">
        {{-- Background decoration --}}
        <div class="auth-bg">
            <div class="auth-bg__shape auth-bg__shape--1"></div>
            <div class="auth-bg__shape auth-bg__shape--2"></div>
        </div>

        <div class="container">
            <div class="row justify-content-center align-items-center min-vh-100 py-5">
                <div class="col-xl-10 col-lg-11">
                    <div class="auth-card auth-card--register">
                        {{-- Form Panel --}}
                        <div class="auth-card__form">
                            {{-- Logo --}}
                            <a href="{{ route('home') }}" class="auth-logo">
                                <img src="{{ asset(\App\Models\SystemSetting::get('site_logo', 'home.png')) }}"
                                    alt="Endak">
                            </a>

                            <h3 class="auth-form-title">{{ __('messages.create_account') }}</h3>

                            {{-- Social Login --}}
                            <a href="{{ route('auth.google') }}" class="auth-social-btn">
                                <svg class="auth-social-btn__icon" viewBox="0 0 24 24" width="20" height="20">
                                    <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92a5.06 5.06 0 0 1-2.2 3.32v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.1z"/>
                                    <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                                    <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/>
                                    <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/>
                                </svg>
                                {{ __('messages.register_with_google') }}
                            </a>

                            <div class="auth-divider">
                                <span>{{ __('messages.or') }}</span>
                            </div>

                            {{-- Register Form --}}
                            <form method="POST" action="{{ route('register') }}" class="auth-form">
                                @csrf

                                {{-- Name & Email row --}}
                                <div class="auth-row">
                                    <div class="ef-field ef-field--icon">
                                        <i class="fas fa-user ef-icon"></i>
                                        <input type="text"
                                            class="ef-input @error('name') is-invalid @enderror"
                                            name="name" id="name" placeholder=" "
                                            value="{{ old('name') }}" required
                                            aria-label="{{ __('messages.full_name') }}">
                                        <label for="name" class="ef-label">{{ __('messages.full_name') }}</label>
                                        @error('name')
                                            <div class="auth-field-error">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="ef-field ef-field--icon">
                                        <i class="fas fa-envelope ef-icon"></i>
                                        <input type="email"
                                            class="ef-input @error('email') is-invalid @enderror"
                                            name="email" id="email" placeholder=" "
                                            value="{{ old('email') }}" required
                                            aria-label="{{ __('messages.email') }}">
                                        <label for="email" class="ef-label">{{ __('messages.email') }}</label>
                                        @error('email')
                                            <div class="auth-field-error">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                {{-- Phone & Password row --}}
                                <div class="auth-row">
                                    <div class="ef-field ef-field--icon">
                                        <i class="fas fa-mobile-alt ef-icon"></i>
                                        <input type="tel"
                                            class="ef-input @error('phone') is-invalid @enderror"
                                            name="phone" id="phone" placeholder=" "
                                            value="{{ old('phone') }}" required
                                            aria-label="{{ __('messages.phone') }}">
                                        <label for="phone" class="ef-label">{{ __('messages.phone') }}</label>
                                        @error('phone')
                                            <div class="auth-field-error">{{ $message }}</div>
                                        @enderror
                                        <div class="ef-hint"><i class="fas fa-info-circle me-1"></i>{{ __('messages.phone_note') }}</div>
                                    </div>

                                    <div class="ef-field ef-field--icon">
                                        <i class="fas fa-lock ef-icon"></i>
                                        <input type="password"
                                            class="ef-input @error('password') is-invalid @enderror"
                                            name="password" id="password" placeholder=" " required
                                            aria-label="{{ __('messages.password') }}">
                                        <label for="password" class="ef-label">{{ __('messages.password') }}</label>
                                        <button type="button" class="auth-toggle-pw" onclick="togglePassword('password', this)" aria-label="Toggle password">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        @error('password')
                                            <div class="auth-field-error">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                {{-- Account Type --}}
                                <div class="auth-type-selector">
                                    <p class="auth-type-selector__label">{{ __('messages.select_account_type') }}</p>
                                    <div class="auth-type-cards">
                                        <label class="auth-type-card" for="type_customer">
                                            <input type="radio" name="user_type" id="type_customer" value="customer"
                                                class="auth-type-card__input @error('user_type') is-invalid @enderror"
                                                {{ old('user_type', 'customer') == 'customer' ? 'checked' : '' }} required>
                                            <div class="auth-type-card__body">
                                                <div class="auth-type-card__icon">
                                                    <i class="fas fa-shopping-bag"></i>
                                                </div>
                                                <div class="auth-type-card__text">
                                                    <strong>{{ __('messages.user_regular') }}</strong>
                                                </div>
                                            </div>
                                        </label>

                                        <label class="auth-type-card" for="type_provider">
                                            <input type="radio" name="user_type" id="type_provider" value="provider"
                                                class="auth-type-card__input"
                                                {{ old('user_type') == 'provider' ? 'checked' : '' }}>
                                            <div class="auth-type-card__body">
                                                <div class="auth-type-card__icon">
                                                    <i class="fas fa-tools"></i>
                                                </div>
                                                <div class="auth-type-card__text">
                                                    <strong>{{ __('messages.user_provider') }}</strong>
                                                </div>
                                            </div>
                                        </label>
                                    </div>
                                    @error('user_type')
                                        <div class="auth-field-error">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- Terms --}}
                                <label class="auth-check" for="terms">
                                    <input type="checkbox"
                                        class="auth-check__input @error('terms') is-invalid @enderror"
                                        id="terms" name="terms" value="1" required>
                                    <span class="auth-check__box"></span>
                                    <span class="auth-check__text">
                                        <a href="#" data-bs-toggle="modal" data-bs-target="#termsModal">{{ __('messages.terms_accept') }}</a>
                                    </span>
                                </label>
                                @error('terms')
                                    <div class="auth-field-error" style="margin-top:-0.5rem;">{{ $message }}</div>
                                @enderror

                                @include('partials.terms-modal')

                                {{-- Submit --}}
                                <button type="submit" class="auth-submit-btn">
                                    <span>{{ __('messages.create_account') }}</span>
                                    <i class="fas fa-arrow-left ms-2"></i>
                                </button>
                            </form>

                            {{-- Mobile: switch link --}}
                            <div class="auth-mobile-switch">
                                {{ __('messages.already_have_account') }}
                                <a href="{{ route('login') }}">{{ __('messages.login') }}</a>
                            </div>
                        </div>

                        {{-- Side Panel --}}
                        <div class="auth-card__side">
                            <div class="auth-card__side-content">
                                <div class="auth-card__side-icon">
                                    <i class="fas fa-user-plus"></i>
                                </div>
                                <h2 class="auth-card__side-title">{{ __('messages.register_welcome') }}</h2>
                                <p class="auth-card__side-text">{{ __('messages.already_have_account') }}</p>
                                <a href="{{ route('login') }}" class="auth-card__side-btn">
                                    <i class="fas fa-sign-in-alt me-2"></i>{{ __('messages.login') }}
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('auth._auth-styles')

    <script>
        function togglePassword(id, btn) {
            const input = document.getElementById(id);
            const icon = btn.querySelector('i');
            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.replace('fa-eye', 'fa-eye-slash');
            } else {
                input.type = 'password';
                icon.classList.replace('fa-eye-slash', 'fa-eye');
            }
        }
    </script>
@endsection
