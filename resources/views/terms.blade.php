@extends('layouts.app')

@section('title', 'الشروط والأحكام')

@section('content')
<section class="py-5" style="background: linear-gradient(135deg, #eafaf9 0%, #ffffff 100%);">
    <div class="container">
        <div class="terms-card card shadow-lg rounded-4 border-0 mx-auto">
            <div class="card-body p-5">
                <h2 class="text-center mb-4" style="color: #007f7f;">
                    <i class="fas fa-file-contract me-2 text-warning"></i> {{ __('messages.terms_title') }}
                </h2>
                <p class="text-secondary text-center mb-5">
                    {{ __('messages.terms_intro_1') }} <strong class="text-primary">Endak</strong>{{ __('messages.terms_intro_2') }}
                    {{ __('messages.terms_intro_3') }}
                </p>

                <div class="mb-5">
                    <h4 class="fw-bold text-black mb-3"><i class="fas fa-user-shield text-warning me-2"></i> {{ __('messages.terms_section_1_title') }}</h4>
                    <ul class="list-unstyled lh-lg text-muted">
                        <li>• {{ __('messages.terms_section_1_item_1') }}</li>
                        <li>• {{ __('messages.terms_section_1_item_2') }}</li>
                        <li>• {{ __('messages.terms_section_1_item_3') }}</li>
                        <li>• {{ __('messages.terms_section_1_item_4') }}</li>
                    </ul>
                </div>

                <div class="mb-5">
                    <h4 class="fw-bold text-black mb-3"><i class="fas fa-id-card text-warning me-2"></i> {{ __('messages.terms_section_2_title') }}</h4>
                    <ul class="list-unstyled lh-lg text-muted">
                        <li>• {{ __('messages.terms_section_2_item_1') }}</li>
                        <li>• {{ __('messages.terms_section_2_item_2') }}</li>
                        <li>• {{ __('messages.terms_section_2_item_3') }}</li>
                        <li>• {{ __('messages.terms_section_2_item_4') }}</li>
                    </ul>
                </div>

                <div class="mb-5">
                    <h4 class="fw-bold text-black mb-3"><i class="fas fa-handshake text-warning me-2"></i> {{ __('messages.terms_section_3_title') }}</h4>
                    <ul class="list-unstyled lh-lg text-muted">
                        <li>• {{ __('messages.terms_section_3_item_1') }}</li>
                        <li>• {{ __('messages.terms_section_3_item_2') }}</li>
                        <li>• {{ __('messages.terms_section_3_item_3') }}</li>
                        <li>• {{ __('messages.terms_section_3_item_4') }}</li>
                    </ul>
                </div>

                <div class="mb-5">
                    <h4 class="fw-bold text-black mb-3"><i class="fas fa-lock text-warning me-2"></i> {{ __('messages.terms_section_4_title') }}</h4>
                    <ul class="list-unstyled lh-lg text-muted">
                        <li>• {{ __('messages.terms_section_4_item_1') }}</li>
                        <li>• {{ __('messages.terms_section_4_item_2') }}</li>
                        <li>• {{ __('messages.terms_section_4_item_3') }}</li>
                        <li>• {{ __('messages.terms_section_4_item_4') }}</li>
                    </ul>
                </div>

                <div class="mb-5">
                    <h4 class="fw-bold text-black mb-3"><i class="fas fa-balance-scale text-warning me-2"></i> {{ __('messages.terms_section_5_title') }}</h4>
                    <ul class="list-unstyled lh-lg text-muted">
                        <li>• {{ __('messages.terms_section_5_item_1') }}</li>
                        <li>• {{ __('messages.terms_section_5_item_2') }}</li>
                        <li>• {{ __('messages.terms_section_5_item_3') }}</li>
                    </ul>
                </div>

                <div class="mb-5">
                    <h4 class="fw-bold text-black mb-3"><i class="fas fa-sync text-warning me-2"></i> {{ __('messages.terms_section_6_title') }}</h4>
                    <ul class="list-unstyled lh-lg text-muted">
                        <li>• {{ __('messages.terms_section_6_item_1') }}</li>
                        <li>• {{ __('messages.terms_section_6_item_2') }}</li>
                        <li>• {{ __('messages.terms_section_6_item_3') }}</li>
                    </ul>
                </div>

                <div class="mb-4">
                    <h4 class="fw-bold text-black mb-3"><i class="fas fa-gavel text-warning me-2"></i> {{ __('messages.terms_section_7_title') }}</h4>
                    <ul class="list-unstyled lh-lg text-muted">
                        <li>• {{ __('messages.terms_section_7_item_1') }}</li>
                        <li>• {{ __('messages.terms_section_7_item_2') }}</li>
                        <li>• {{ __('messages.terms_section_7_item_3') }}</li>
                    </ul>
                </div>

                <p class="text-center mt-5 fw-bold" style="color: #007f7f;">
                    {{ __('messages.terms_footer_note') }}
                </p>
            </div>
        </div>
    </div>
</section>

<style>
@media (min-width: 992px) {
    .terms-card {
        margin-top: 60px;
    }
}

.terms-card {
    background: #ffffff;
    border: 2px solid transparent;
    background-clip: padding-box;
    position: relative;
}

.terms-card::before {
    content: "";
    position: absolute;
    inset: 0;
    border-radius: 1rem;
    padding: 2px;
    background: linear-gradient(135deg, #00a6a6, #f2c94c);
    -webkit-mask:
        linear-gradient(#fff 0 0) content-box,
        linear-gradient(#fff 0 0);
    -webkit-mask-composite: xor;
    mask-composite: exclude;
}

.terms-card:hover {
    transform: translateY(-5px);
    transition: all 0.3s ease;
    box-shadow: 0 10px 25px rgba(0, 166, 166, 0.2);
}
</style>
@endsection
