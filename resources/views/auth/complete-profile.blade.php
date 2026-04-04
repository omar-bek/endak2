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
                            @include('partials.terms-modal')

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
        border-color: var(--e-primary);
        box-shadow: 0 0 5px rgba(47, 92, 105, 0.3);
        outline: none;
    }
</style>
@endsection

