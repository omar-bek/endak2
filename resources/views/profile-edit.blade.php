@extends('layouts.app')

@section('title', 'تعديل الملف الشخصي')

@section('content')
<style>
.ep-edit-page { min-height: 100vh; padding-bottom: 100px; background: var(--e-gray-50); }

/* Top Bar */
.ep-topbar {
    background: linear-gradient(135deg, var(--e-primary-dark), var(--e-primary), var(--e-primary-light));
    padding: 28px 0 60px;
    position: relative;
}
.ep-topbar::after {
    content: '';
    position: absolute;
    bottom: 0;
    left: 0;
    right: 0;
    height: 40px;
    background: linear-gradient(to top, var(--e-gray-50), transparent);
}
.ep-topbar-inner {
    display: flex;
    align-items: center;
    justify-content: space-between;
}
.ep-topbar-title {
    color: var(--e-white);
    font-size: 1.2rem;
    font-weight: 700;
    display: flex;
    align-items: center;
    gap: 10px;
}
.ep-back-btn {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 8px 18px;
    border-radius: var(--e-radius-full);
    background: rgba(255,255,255,0.15);
    color: var(--e-white);
    font-size: 0.85rem;
    font-weight: 600;
    font-family: var(--e-font);
    text-decoration: none;
    border: 1px solid rgba(255,255,255,0.2);
    transition: var(--e-transition);
}
.ep-back-btn:hover {
    background: rgba(255,255,255,0.25);
    color: var(--e-white);
}

/* Main Card */
.ep-main-card {
    margin-top: -35px;
    position: relative;
    z-index: 2;
    background: var(--e-white);
    border-radius: var(--e-radius-xl);
    box-shadow: var(--e-shadow-lg);
    border: 1px solid var(--e-gray-200);
    overflow: hidden;
}

/* Avatar Upload */
.ep-avatar-upload {
    text-align: center;
    padding: 30px 24px 20px;
    background: linear-gradient(180deg, var(--e-gray-50), var(--e-white));
}
.ep-avatar-edit-wrap {
    position: relative;
    display: inline-block;
    cursor: pointer;
}
.ep-avatar-edit-img {
    width: 110px;
    height: 110px;
    border-radius: var(--e-radius-full);
    object-fit: cover;
    border: 3px solid var(--e-white);
    box-shadow: var(--e-shadow-md);
    transition: var(--e-transition);
}
.ep-avatar-edit-fallback {
    width: 110px;
    height: 110px;
    border-radius: var(--e-radius-full);
    background: linear-gradient(135deg, var(--e-primary), var(--e-primary-light));
    color: var(--e-white);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 42px;
    font-weight: 700;
    border: 3px solid var(--e-white);
    box-shadow: var(--e-shadow-md);
    transition: var(--e-transition);
}
.ep-avatar-edit-wrap:hover .ep-avatar-edit-img,
.ep-avatar-edit-wrap:hover .ep-avatar-edit-fallback {
    opacity: 0.7;
    transform: scale(1.03);
}
.ep-avatar-overlay {
    position: absolute;
    inset: 0;
    border-radius: var(--e-radius-full);
    background: rgba(0,0,0,0.4);
    display: flex;
    align-items: center;
    justify-content: center;
    opacity: 0;
    transition: var(--e-transition);
}
.ep-avatar-edit-wrap:hover .ep-avatar-overlay { opacity: 1; }
.ep-avatar-overlay i { color: var(--e-white); font-size: 22px; }
.ep-avatar-edit-input {
    position: absolute;
    inset: 0;
    opacity: 0;
    cursor: pointer;
    border-radius: var(--e-radius-full);
}
.ep-avatar-hint {
    font-size: 0.78rem;
    color: var(--e-gray-400);
    margin-top: 10px;
}

/* Preview */
.ep-new-avatar-preview {
    display: none;
    align-items: center;
    justify-content: center;
    gap: 12px;
    margin-top: 12px;
    padding: 10px 16px;
    background: var(--e-success-light);
    border-radius: var(--e-radius);
}
.ep-new-avatar-preview.show { display: flex; }
.ep-new-avatar-preview img {
    width: 44px;
    height: 44px;
    border-radius: var(--e-radius-full);
    object-fit: cover;
    border: 2px solid var(--e-white);
}
.ep-new-avatar-preview span {
    font-size: 0.82rem;
    color: #065f46;
    font-weight: 500;
}
.ep-remove-img {
    font-size: 0.78rem;
    color: var(--e-danger);
    cursor: pointer;
    background: none;
    border: none;
    font-family: var(--e-font);
    padding: 0;
    margin-right: auto;
}
.ep-remove-img:hover { text-decoration: underline; }

/* Form */
.ep-form-section {
    padding: 24px 28px;
}
.ep-section-title {
    font-size: 0.82rem;
    font-weight: 700;
    color: var(--e-gray-400);
    text-transform: uppercase;
    letter-spacing: 0.5px;
    margin-bottom: 18px;
    padding-bottom: 10px;
    border-bottom: 1px solid var(--e-gray-100);
}
.ep-form-group { margin-bottom: 20px; }
.ep-form-label {
    display: block;
    font-size: 0.85rem;
    font-weight: 600;
    color: var(--e-gray-700);
    margin-bottom: 6px;
}
.ep-form-label .req { color: var(--e-danger); margin-right: 2px; }
.ep-form-control {
    width: 100%;
    padding: 11px 16px;
    border: 1.5px solid var(--e-gray-200);
    border-radius: var(--e-radius);
    font-family: var(--e-font);
    font-size: 0.9rem;
    color: var(--e-gray-700);
    background: var(--e-white);
    transition: var(--e-transition);
    outline: none;
}
.ep-form-control:focus {
    border-color: var(--e-primary);
    box-shadow: 0 0 0 3px var(--e-primary-100);
}
.ep-form-control.is-invalid { border-color: var(--e-danger); }
.ep-form-control.is-invalid:focus { box-shadow: 0 0 0 3px rgba(239, 68, 68, 0.1); }
textarea.ep-form-control { resize: vertical; min-height: 100px; }
.ep-char-count {
    font-size: 0.75rem;
    color: var(--e-gray-400);
    text-align: left;
    margin-top: 4px;
}

/* Footer */
.ep-form-footer {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 12px;
    padding: 18px 28px;
    background: var(--e-gray-50);
    border-top: 1px solid var(--e-gray-100);
}
.ep-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    padding: 11px 28px;
    border: none;
    border-radius: var(--e-radius);
    font-family: var(--e-font);
    font-size: 0.9rem;
    font-weight: 600;
    cursor: pointer;
    transition: var(--e-transition);
    text-decoration: none;
}
.ep-btn-save {
    background: linear-gradient(135deg, var(--e-primary), var(--e-primary-light));
    color: var(--e-white);
    box-shadow: var(--e-shadow-primary);
}
.ep-btn-save:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(47, 92, 105, 0.35);
    color: var(--e-white);
}
.ep-btn-cancel {
    background: var(--e-white);
    color: var(--e-gray-600);
    border: 1.5px solid var(--e-gray-200);
}
.ep-btn-cancel:hover {
    background: var(--e-gray-100);
    color: var(--e-gray-700);
}

/* Alerts */
.ep-edit-page .alert {
    border-radius: var(--e-radius);
    border: none;
    font-size: 0.9rem;
}
.ep-edit-page .alert-success { background: var(--e-success-light); color: #065f46; }
.ep-edit-page .alert-danger { background: var(--e-danger-light); color: #991b1b; }

/* Animation */
.ep-animate {
    opacity: 0;
    transform: translateY(16px);
    animation: epUp 0.45s ease forwards;
}
@keyframes epUp { to { opacity: 1; transform: translateY(0); } }

/* Responsive */
@media (max-width: 767px) {
    .ep-form-section { padding: 20px 18px; }
    .ep-form-footer { padding: 16px 18px; flex-direction: column-reverse; }
    .ep-form-footer .ep-btn { width: 100%; justify-content: center; }
    .ep-topbar { padding: 20px 0 50px; }
    .ep-avatar-edit-img, .ep-avatar-edit-fallback { width: 95px; height: 95px; font-size: 36px; }
}
</style>

<div class="ep-edit-page">
    {{-- Top Bar --}}
    <div class="ep-topbar">
        <div class="container" style="max-width: 680px;">
            <div class="ep-topbar-inner">
                <div class="ep-topbar-title">
                    <i class="fas fa-pen-fancy"></i> تعديل الملف الشخصي
                </div>
                <a href="{{ route('profile') }}" class="ep-back-btn">
                    <i class="fas fa-arrow-right"></i> رجوع
                </a>
            </div>
        </div>
    </div>

    <div class="container" style="max-width: 680px;">
        {{-- Alerts --}}
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show mb-3 ep-animate" role="alert">
                <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show mb-3 ep-animate" role="alert">
                <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data" id="editForm">
            @csrf
            @method('PUT')

            <div class="ep-main-card ep-animate">
                {{-- Avatar Upload --}}
                <div class="ep-avatar-upload">
                    <div class="ep-avatar-edit-wrap">
                        @if($user->image && file_exists(public_path('storage/' . $user->image)))
                            <img src="{{ asset('storage/' . $user->image) }}" alt="{{ $user->name }}"
                                 class="ep-avatar-edit-img" id="currentAvatar">
                        @else
                            <div class="ep-avatar-edit-fallback" id="currentAvatar">
                                {{ strtoupper(mb_substr($user->name, 0, 1)) }}
                            </div>
                        @endif
                        <div class="ep-avatar-overlay"><i class="fas fa-camera"></i></div>
                        <input type="file" name="image" id="imageInput" accept="image/*" class="ep-avatar-edit-input">
                    </div>
                    <div class="ep-avatar-hint">اضغط على الصورة لتغييرها - JPG, PNG, GIF (حد أقصى 2MB)</div>
                    @error('image')
                        <div class="text-danger mt-2" style="font-size: 0.82rem;">{{ $message }}</div>
                    @enderror

                    <div class="ep-new-avatar-preview" id="avatarPreview">
                        <img id="previewImg" src="" alt="">
                        <span id="previewName"></span>
                        <button type="button" class="ep-remove-img" id="removeImg">
                            <i class="fas fa-times"></i> إزالة
                        </button>
                    </div>
                </div>

                {{-- Personal Info --}}
                <div class="ep-form-section">
                    <div class="ep-section-title">المعلومات الشخصية</div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="ep-form-group">
                                <label class="ep-form-label">الاسم الكامل <span class="req">*</span></label>
                                <input type="text" name="name" value="{{ old('name', $user->name) }}"
                                       class="ep-form-control @error('name') is-invalid @enderror" required>
                                @error('name')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="ep-form-group">
                                <label class="ep-form-label">رقم الهاتف</label>
                                <input type="tel" name="phone" value="{{ old('phone', $user->phone) }}"
                                       class="ep-form-control @error('phone') is-invalid @enderror" dir="ltr"
                                       placeholder="05XXXXXXXX">
                                @error('phone')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="ep-form-group">
                        <label class="ep-form-label">نبذة شخصية</label>
                        <textarea name="bio" id="bioInput"
                                  class="ep-form-control @error('bio') is-invalid @enderror"
                                  rows="4" maxlength="1000"
                                  placeholder="اكتب نبذة مختصرة عنك...">{{ old('bio', $user->bio) }}</textarea>
                        @error('bio')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                        <div class="ep-char-count"><span id="charCount">0</span> / 1000</div>
                    </div>
                </div>

                {{-- Footer --}}
                <div class="ep-form-footer">
                    <a href="{{ route('profile') }}" class="ep-btn ep-btn-cancel">
                        <i class="fas fa-times"></i> إلغاء
                    </a>
                    <button type="submit" class="ep-btn ep-btn-save">
                        <i class="fas fa-check"></i> حفظ التغييرات
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Image preview
    const imageInput = document.getElementById('imageInput');
    const preview = document.getElementById('avatarPreview');
    const previewImg = document.getElementById('previewImg');
    const previewName = document.getElementById('previewName');
    const removeBtn = document.getElementById('removeImg');

    imageInput.addEventListener('change', function() {
        if (this.files && this.files[0]) {
            const file = this.files[0];
            const reader = new FileReader();
            reader.onload = function(e) {
                previewImg.src = e.target.result;
                previewName.textContent = file.name;
                preview.classList.add('show');
            };
            reader.readAsDataURL(file);
        }
    });

    removeBtn.addEventListener('click', function() {
        imageInput.value = '';
        preview.classList.remove('show');
    });

    // Char counter
    const bioInput = document.getElementById('bioInput');
    const charCount = document.getElementById('charCount');

    function updateCount() {
        charCount.textContent = bioInput.value.length;
    }
    updateCount();
    bioInput.addEventListener('input', updateCount);
});
</script>
@endsection
