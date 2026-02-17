@extends('layouts.app')

@section('title', 'من نحن')

@section('content')
<style>
  .about-section {
        background: linear-gradient(135deg, #e9f7f6 0%, #fefefe 100%);
        margin-top: 80px;
    }

    @media (max-width: 992px) {
        .about-section {
            margin-top: 0;
        }
    }

    .about-card {
        border: 2px solid #d4af37; 
        border-radius: 20px;
        overflow: hidden;
        background: #fff;
        transition: transform 0.4s ease, box-shadow 0.4s ease;
        animation: fadeInUp 1.2s ease;
    }

    .about-card:hover {
        transform: translateY(-6px);
        box-shadow: 0 10px 25px rgba(0, 128, 128, 0.15);
    }

    .about-header {
        text-align: center;
        margin-bottom: 40px;
    }

    .about-header h2 {
        color: #007c80; 
        font-weight: 700;
    }

    .about-header i {
        color: #d4af37;
    }

    .about-section h5 {
        color: #007c80;
        font-weight: 600;
        position: relative;
        display: inline-block;
    }

    .about-section h5::after {
        content: '';
        position: absolute;
        bottom: -6px;
        left: 0;
        width: 50%;
        height: 3px;
        background: #d4af37;
        border-radius: 5px;
        transition: width 0.4s ease;
    }

    .about-section h5:hover::after {
        width: 100%;
    }

    .about-section p {
        color: #555;
        line-height: 1.9;
    }

    @keyframes fadeInUp {
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

<section class="py-5 about-section">
    <div class="container">
        <div class="card about-card shadow-lg border-2 rounded-4 p-5">
            <div class="about-header">
                <h2><i class="fas fa-info-circle me-2"></i>{{ __('messages.about_us_title') }}</h2>
            </div>

            <p class="mb-4">
                <strong>Endak</strong> {{ __('messages.about_intro_1') }}
                {{ __('messages.about_intro_2') }} <strong>{{ __('messages.about_intro_3') }}</strong> {{ __('messages.about_intro_4') }}
            </p>

            <h5>{{ __('messages.about_vision_title') }}</h5>
            <p>
                {{ __('messages.about_vision_body_1') }}
                {{ __('messages.about_vision_body_2') }}
            </p>

            <h5 class="mt-4">{{ __('messages.about_mission_title') }}</h5>
            <p>
                {{ __('messages.about_mission_body_1') }}
                <strong>Endak</strong> {{ __('messages.about_mission_body_2') }}
                {{ __('messages.about_mission_body_3') }}
            </p>

            <h5 class="mt-4">{{ __('messages.about_services_title') }}</h5>
            <p>
                {{ __('messages.about_services_intro') }}
            </p>
            <ul class="text-muted">
                <li>🔹 {{ __('messages.about_service_item_1') }}</li>
                <li>🔹 {{ __('messages.about_service_item_2') }}</li>
                <li>🔹 {{ __('messages.about_service_item_3') }}</li>
                <li>🔹 {{ __('messages.about_service_item_4') }}</li>
                <li>🔹 {{ __('messages.about_service_item_5') }}</li>
                <li>🔹 {{ __('messages.about_service_item_6') }}</li>
            </ul>

            <h5 class="mt-4">{{ __('messages.about_values_title') }}</h5>
            <p>
                {{ __('messages.about_values_body_1') }} <strong>{{ __('messages.about_values_body_2') }}</strong> {{ __('messages.about_values_body_3') }}
            </p>

            <h5 class="mt-4">{{ __('messages.about_disclaimer_title') }}</h5>
            <p>
                <strong>Endak</strong> {{ __('messages.about_disclaimer_body_1') }}
                {{ __('messages.about_disclaimer_body_2') }}
                {{ __('messages.about_disclaimer_body_3') }}
            </p>

            <div class="text-center mt-5">
                <h5 class="text-success mb-3"><strong>{{ __('messages.about_footer_main') }}</strong></h5>
                <p class="text-muted">{{ __('messages.about_footer_sub') }}</p>
            </div>
        </div>
    </div>
</section>
@endsection
