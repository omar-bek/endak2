@extends('layouts.app')

@section('title', 'الأقسام')

@section('content')
    <!-- Header Section -->

    <!-- Categories Section -->
    <section class="categories-section py-5 text-center mt-5">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="fw-bold text-black">{{ __('messages.categories_title') }}</h2>
                <p class="text-light-50">{{ __('messages.categories_subtitle') }}</p>
            </div>

            <div class="row justify-content-center">
                @forelse($categories as $category)
                    <div class="col-6 col-md-6 col-lg-3 mb-4 fade-card">
                        <a href="{{ $category->hasChildren() ? route('categories.subcategories', $category->slug) : route('categories.show', $category->slug) }}"
                            class="card-link">
                            <div class="card category-card h-100">
                                <div class="category-image-container">
                                    <img src="{{ $category->image_url }}" class="category-image"
                                        alt="{{ $category->name }}"
                                        onerror="this.onerror=null; this.src='{{ asset('images/default-service.svg') }}';">
                                    <div class="category-overlay">
                                        <h1 class="category-title">
                                            {{ app()->getLocale() == 'ar' ? $category->name : $category->name_en }}
                                        </h1>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                @empty
                    <div class="col-12 text-center">
                        <p class="text-muted">لا توجد أقسام متاحة حالياً</p>
                    </div>
                @endforelse
            </div>
        </div>

        <style>
            .categories-section {
                background: linear-gradient(135deg, #f1f7ff, #e6eefc, #f1f7ff);
                color: #1b3c72;
                position: relative;
                overflow: hidden;
            }

            .fade-card {
                opacity: 0;
                transform: translateY(30px);
                animation: fadeUp 0.8s ease forwards;
            }

            .fade-card:nth-child(odd) {
                transform: translateX(-50px);
            }

            .fade-card:nth-child(even) {
                transform: translateX(50px);
            }

            @keyframes fadeUp {
                0% {
                    opacity: 0;
                    transform: translateY(30px);
                }

                100% {
                    opacity: 1;
                    transform: translateY(0);
                }
            }

            .card-link {
                text-decoration: none;
                color: inherit;
                display: block;
            }

            .category-card {
                position: relative;
                background: rgba(255, 255, 255, 0.7);
                border-radius: 20px;
                box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05);
                transition: all 0.4s ease;
                border: none;
                text-align: center;
                overflow: hidden;
                padding-bottom: 15px;
            }

            .category-card::before {
                content: "";
                position: absolute;
                top: 0;
                right: 0;
                width: 100px;
                height: 100px;
                border-top: 3px solid #004d40;
                border-right: 3px solid #004d40;
                border-top-right-radius: 20px;
                transition: all 0.4s ease;
            }

            .category-card::after {
                content: "";
                position: absolute;
                bottom: 0;
                left: 0;
                width: 100px;
                height: 100px;
                border-bottom: 3px solid #004d40;
                border-left: 3px solid #004d40;
                border-bottom-left-radius: 20px;
                transition: all 0.4s ease;
            }

            .category-card:hover {
                transform: scale(1.05);
                box-shadow: 0 15px 25px rgba(0, 0, 0, 0.2);
            }

            .category-card:hover::before,
            .category-card:hover::after {
                width: 100%;
                height: 100%;
                border-radius: 20px;
            }

            .category-image-container {
                position: relative;
                height: 200px;
                overflow: hidden;
                border-radius: 20px 20px 0 0;
            }

            .category-image {
                width: 100%;
                height: 100%;
                object-fit: cover;
                transition: transform 0.4s ease;
            }

            .category-card:hover .category-image {
                transform: scale(1.1);
            }

            /* overlay على الكارد كلها */
            .category-overlay {
                position: absolute;
                inset: 0;
                background: rgba(0, 0, 0, 0.3);
                /* خفيف ورا الكلمة */
                display: flex;
                justify-content: center;
                align-items: center;
                border-radius: 20px;
                transition: background 0.3s ease;
            }

            .category-card:hover .category-overlay {
                background: rgba(0, 0, 0, 0.45);
            }

            .category-title {
                font-size: 1.5rem;
                font-weight: bold;
                color: #fff;
                text-shadow: 0 2px 6px rgba(0, 0, 0, 0.5);
                margin: 0;
            }

            @media (min-width: 1200px) {
                .col-lg-3 {
                    flex: 0 0 25%;
                    max-width: 25%;
                }
            }

            @media (max-width: 768px) {
                .category-image-container {
                    height: 150px;
                }

                .category-title {
                    font-size: 1rem;
                }
            }

            @media (max-width: 576px) {
                .col-6 {
                    flex: 0 0 50%;
                    max-width: 50%;
                }

                .category-image-container {
                    height: 120px;
                }

                .category-title {
                    font-size: 0.9rem;
                }
            }
        </style>
    </section>
@endsection
