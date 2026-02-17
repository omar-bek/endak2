<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Endak - ' . __('messages.welcome_title'))</title>

    <!-- Bootstrap CSS -->
    @if (app()->getLocale() === 'ar')
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
    @else
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    @endif
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;600;700&display=swap" rel="stylesheet">

    <style>
        :root {
            --primary-color: #685C84;
            --primary-dark: #675B83;
            --secondary-color: #6A5E86;
            --accent-color: #766A90;
            --success-color: #10b981;
            --warning-color: #f59e0b;
            --danger-color: #ef4444;
            --dark-color: #675B83;
            --darker-color: #685C84;
            --light-color: #F8F6F7;
            --lighter-color: #F6F4F5;
            --text-color: #675B83;
            --text-muted: #9E96AE;
            --border-color: #B1A9BE;
            --shadow-sm: 0 1px 2px 0 rgb(0 0 0 / 0.05);
            --shadow-md: 0 4px 6px -1px rgb(0 0 0 / 0.1), 0 2px 4px -2px rgb(0 0 0 / 0.1);
            --shadow-lg: 0 10px 15px -3px rgb(0 0 0 / 0.1), 0 4px 6px -4px rgb(0 0 0 / 0.1);
            --shadow-xl: 0 20px 25px -5px rgb(0 0 0 / 0.1), 0 8px 10px -6px rgb(0 0 0 / 0.1);
        }

        * {
            box-sizing: border-box;
        }


        body {
            font-family: 'Cairo', 'Inter', 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            /* background: linear-gradient(135deg, #685C84 0%, #6A5E86 25%, #766A90 50%, #B1A9BE 75%, #F8F6F7 100%); */
            background-attachment: fixed;
            color: var(--text-color);
            line-height: 1.7;
            margin: 0;
            padding: 0;
            min-height: 100vh;
        }

        /* RTL Support */
        [dir="rtl"] {
            text-align: right;
        }

        [dir="ltr"] {
            text-align: left;
        }

        /* Glass Morphism Effect */
        .glass {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            box-shadow: var(--shadow-lg);
        }

        .glass-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.3);
            box-shadow: var(--shadow-xl);
        }

        /* Navigation */
        .navbar {
            /* background: rgba(104, 92, 132, 0.95) !important;
            backdrop-filter: blur(20px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            box-shadow: var(--shadow-lg);
            padding: 1rem 0;
            transition: all 0.3s ease;
            position: relative; */
            z-index: 1000;
        }

        .navbar.scrolled {
            background: rgba(103, 91, 131, 0.98) !important;
            padding: 0.5rem 0;
        }

        .navbar-brand {
            font-weight: 700;
            font-size: 1.5rem;
            background: linear-gradient(135deg, #B1A9BE, #F8F6F7);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            text-decoration: none;
            transition: all 0.3s ease;
        }

        .navbar-brand:hover {
            transform: scale(1.02);
        }

        .nav-link {
            font-weight: 500;
            font-size: 0.85rem;
            color: rgba(255, 255, 255, 0.9) !important;
            transition: all 0.3s ease;
            position: relative;
            padding: 0.5rem 1rem !important;
            border-radius: 8px;
            margin: 0 0.15rem;
        }

        .nav-link:hover {
            color: white !important;
            background: rgba(255, 255, 255, 0.1);
            transform: translateY(-2px);
        }

        /* .nav-link.active {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white !important;
        } */



        /* Flash Messages */
        .alert {
            border: none;
            border-radius: 12px;
            box-shadow: var(--shadow-md);
            font-weight: 500;
            animation: slideInDown 0.5s ease-out;
        }

        .alert-success {
            background: linear-gradient(135deg, #10b981, #059669);
            color: white;
            border-left: 4px solid #047857;
        }

        .alert-danger {
            background: linear-gradient(135deg, #ef4444, #dc2626);
            color: white;
            border-left: 4px solid #b91c1c;
        }

        .alert-warning {
            background: linear-gradient(135deg, #f59e0b, #d97706);
            color: white;
            border-left: 4px solid #b45309;
        }

        .alert-info {
            background: linear-gradient(135deg, #3b82f6, #2563eb);
            color: white;
            border-left: 4px solid #1d4ed8;
        }

        .alert .btn-close {
            filter: invert(1);
            opacity: 0.8;
        }

        .alert .btn-close:hover {
            opacity: 1;
        }

        @keyframes slideInDown {
            from {
                transform: translateY(-100%);
                opacity: 0;
            }

            to {
                transform: translateY(0);
                opacity: 1;
            }
        }

        /* Cards */


        /* Footer */
        /* .footer {
            background: linear-gradient(135deg, #675B83 0%, #685C84 100%);
            color: white;
            padding: 80px 0 40px;
            position: relative;
            margin-top: 100px;
        } */

        .footer::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(135deg, var(--primary-color), var(--accent-color));
        }

        .footer h5 {
            font-weight: 700;
            margin-bottom: 1.5rem;
            color: #F8F6F7;
        }

        .footer a {
            color: rgba(255, 255, 255, 0.8);
            text-decoration: none;
            transition: all 0.3s ease;
        }

        .footer a:hover {
            color: #B1A9BE;
            transform: translateX(5px);
        }

        /* Category Cards with Images */

        /* Language Switcher */
        .language-btn {
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            color: white;
            padding: 10px 18px;
            border-radius: 16px;
            text-decoration: none;
            transition: all 0.3s ease;
            backdrop-filter: blur(10px);
            font-weight: 600;
        }

        .language-btn:hover {
            background: rgba(255, 255, 255, 0.2);
            color: white;
            transform: translateY(-2px);
            box-shadow: var(--shadow-md);
        }

        /* Notifications */
        .notification-badge {
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0% {
                transform: scale(1);
            }

            50% {
                transform: scale(1.15);
            }

            100% {
                transform: scale(1);
            }
        }

        .dropdown-menu {
            border: none;
            border-radius: 12px;
            box-shadow: var(--shadow-lg);
            backdrop-filter: blur(20px);
            background: rgba(255, 255, 255, 0.95);
            padding: 0.5rem 0;
            margin-top: 0.5rem;
            min-width: 200px;
            position: absolute;
            top: 100%;
            left: 0;
            z-index: 1000;
            display: none;
            opacity: 0;
            visibility: hidden;
            transform: translateY(-10px);
            transition: all 0.3s ease;
        }

        .dropdown-menu.show {
            display: block;
            opacity: 1;
            visibility: visible;
            transform: translateY(0);
        }

        .dropdown-item {
            border-radius: 8px;
            margin: 0.15rem 0.5rem;
            padding: 0.5rem 0.75rem;
            transition: all 0.3s ease;
            font-weight: 500;
            font-size: 0.85rem;
            display: block;
            text-decoration: none;
            color: var(--text-color);
        }

        .dropdown-item:hover {
            background: linear-gradient(135deg, #685C84, #6A5E86);
            color: white;
            transform: translateX(5px);
            text-decoration: none;
        }

        /* Fix dropdown positioning */
        .dropdown {
            position: relative;
        }

        .dropdown-menu-end {
            left: auto !important;
            right: 0 !important;
        }

        [dir="rtl"] .dropdown-menu {
            left: auto;
            right: 0;
        }

        [dir="rtl"] .dropdown-menu-end {
            right: auto !important;
            left: 0 !important;
        }

        /* Ensure dropdowns are visible */
        .dropdown-menu.show {
            display: block !important;
            opacity: 1 !important;
            visibility: visible !important;
            transform: translateY(0) !important;
        }

        /* Override Bootstrap dropdown styles */
        .dropdown-menu {
            display: none;
        }

        .dropdown-menu.show {
            display: block !important;
        }

        /* Fix dropdown positioning */
        .dropdown {
            position: relative;
        }

        .dropdown-menu {
            position: absolute;
            top: 100%;
            left: 0;
            right: auto;
        }

        [dir="rtl"] .dropdown-menu {
            left: auto;
            right: 0;
        }

        /* Ensure proper z-index */
        .navbar-nav .dropdown-menu {
            z-index: 1050;
        }

        /* Force dropdown visibility */
        .dropdown-menu.show {
            display: block !important;
            opacity: 1 !important;
            visibility: visible !important;
            transform: translateY(0) !important;
            pointer-events: auto !important;
        }

        /* Ensure dropdown container is positioned correctly */
        .navbar-nav .dropdown {
            position: relative;
        }

        /* Fix for RTL dropdowns */
        [dir="rtl"] .dropdown-menu-end {
            right: auto !important;
            left: 0 !important;
        }

        /* Animation for dropdown appearance */
        .dropdown-menu {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        /* Ensure dropdowns don't get cut off */
        .navbar-nav {
            position: relative;
        }

        .navbar-nav .dropdown {
            position: static;
        }

        .navbar-nav .dropdown-menu {
            position: absolute;
            top: 100%;
            left: 0;
            right: auto;
            margin-top: 0.5rem;
        }

        /* Fix for dropdown-menu-end */
        .navbar-nav .dropdown-menu-end {
            left: auto;
            right: 0;
        }

        /* Ensure proper stacking context */
        .navbar>.container {
            position: relative;
            z-index: 1001;
        }

        /* Ensure user dropdown is above other elements */
        .nav-item.dropdown {
            position: relative;
            z-index: 1002;
        }

        .nav-item.dropdown .dropdown-menu {
            z-index: 1003;
            position: absolute;
            top: 100%;
            left: 0;
            right: auto;
        }

        [dir="rtl"] .nav-item.dropdown .dropdown-menu {
            left: auto;
            right: 0;
        }

        /* Debug styles - remove if working */
        .dropdown-menu {
            border: 1px solid red !important;
        }

        .dropdown-menu.show {
            border: 1px solid green !important;
        }

        /* Simple dropdown fix */
        .dropdown-menu {
            display: none !important;
        }

        .dropdown-menu.show {
            display: block !important;
        }

        /* Force all dropdowns to work */
        .dropdown-menu {
            display: none;
        }

        .dropdown-menu.show {
            display: block !important;
            opacity: 1 !important;
            visibility: visible !important;
        }

        /* Ensure dropdown positioning */
        .dropdown {
            position: relative;
        }

        .dropdown-menu {
            position: absolute;
            top: 100%;
            left: 0;
            z-index: 1000;
        }

        /* Specific styles for user dropdown */
        .nav-item.dropdown .dropdown-menu {
            min-width: 200px;
            margin-top: 0.5rem;
        }

        .nav-item.dropdown .dropdown-item {
            padding: 0.5rem 0.75rem;
            font-size: 0.85rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .nav-item.dropdown .dropdown-item i {
            width: 16px;
            text-align: center;
        }

        /* Ensure user dropdown is visible */
        .nav-item.dropdown .dropdown-menu.show {
            display: block !important;
            opacity: 1 !important;
            visibility: visible !important;
            transform: translateY(0) !important;
            pointer-events: auto !important;
        }

        /* Force user dropdown to be visible when shown */
        .nav-item.dropdown .dropdown-menu.show {
            display: block !important;
            opacity: 1 !important;
            visibility: visible !important;
            transform: translateY(0) !important;
            pointer-events: auto !important;
        }

        /* Override any Bootstrap hiding */
        .nav-item.dropdown .dropdown-menu.show {
            display: block !important;
        }

        /* Ensure dropdown is not hidden by overflow */
        .navbar-nav {
            overflow: visible;
        }

        .navbar {
            overflow: visible;
        }

        /* Force user dropdown visibility with important */
        .nav-item.dropdown .dropdown-menu.show {
            display: block !important;
            opacity: 1 !important;
            visibility: visible !important;
            transform: translateY(0) !important;
            pointer-events: auto !important;
            z-index: 9999 !important;
        }

        /* Ensure proper stacking */
        .nav-item.dropdown {
            position: relative;
            z-index: 9998;
        }

        /* Ensure proper positioning for user dropdown */
        .nav-item.dropdown {
            position: relative;
        }

        .nav-item.dropdown .dropdown-menu {
            position: absolute;
            top: 100%;
            left: 0;
            right: auto;
            margin-top: 0.5rem;
            min-width: 200px;
            background: white;
            border: 1px solid rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }

        [dir="rtl"] .nav-item.dropdown .dropdown-menu {
            left: auto;
            right: 0;
        }

        /* Force user dropdown to be visible */
        .nav-item.dropdown .dropdown-menu {
            display: none;
        }

        .nav-item.dropdown .dropdown-menu.show {
            display: block !important;
            opacity: 1 !important;
            visibility: visible !important;
            transform: translateY(0) !important;
            pointer-events: auto !important;
        }

        /* Additional force for user dropdown */
        .nav-item.dropdown .dropdown-menu.show {
            display: block !important;
            opacity: 1 !important;
            visibility: visible !important;
            transform: translateY(0) !important;
            pointer-events: auto !important;
            z-index: 9999 !important;
        }

        /* Force user dropdown to be visible with all properties */
        .nav-item.dropdown .dropdown-menu.show {
            display: block !important;
            opacity: 1 !important;
            visibility: visible !important;
            transform: translateY(0) !important;
            pointer-events: auto !important;
            z-index: 9999 !important;
            position: absolute !important;
            top: 100% !important;
            left: 0 !important;
            right: auto !important;
        }

        [dir="rtl"] .nav-item.dropdown .dropdown-menu.show {
            left: auto !important;
            right: 0 !important;
        }

        /* Ensure user dropdown is not hidden */
        .nav-item.dropdown {
            overflow: visible;
        }

        .navbar-nav {
            overflow: visible;
        }

        /* Force user dropdown to be visible with all properties */
        .nav-item.dropdown .dropdown-menu.show {
            display: block !important;
            opacity: 1 !important;
            visibility: visible !important;
            transform: translateY(0) !important;
            pointer-events: auto !important;
            z-index: 9999 !important;
            position: absolute !important;
            top: 100% !important;
            left: 0 !important;
            right: auto !important;
            background: white !important;
            border: 1px solid rgba(0, 0, 0, 0.1) !important;
            border-radius: 8px !important;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15) !important;
            min-width: 200px !important;
            margin-top: 0.5rem !important;
        }

        [dir="rtl"] .nav-item.dropdown .dropdown-menu.show {
            left: auto !important;
            right: 0 !important;
        }

        /* Ensure user dropdown items are visible */
        .nav-item.dropdown .dropdown-menu.show .dropdown-item {
            display: block !important;
            opacity: 1 !important;
            visibility: visible !important;
            color: #333 !important;
            background: transparent !important;
        }

        .nav-item.dropdown .dropdown-menu.show .dropdown-item:hover {
            background: rgba(0, 0, 0, 0.05) !important;
        }

        /* Animations */

        /* Responsive */

        .navbar-brand {
            font-size: 1.3rem;
        }

        .nav-link {
            font-size: 0.8rem;
            padding: 0.4rem 0.8rem !important;
        }

        .card {
            margin-bottom: 1rem;
        }

        .card-body {
            padding: 1rem;
        }

        .btn {
            padding: 8px 20px;
            font-size: 0.85rem;
        }

        .category-card {
            padding: 1.5rem 0.8rem;
        }

        .category-icon {
            font-size: 3rem;
        }

        .dropdown-menu {
            min-width: 180px;
        }

        .dropdown-item {
            font-size: 0.8rem;
            padding: 0.4rem 0.6rem;
        }
        }

        /* Loading Animation */
        .loading {
            display: inline-block;
            width: 24px;
            height: 24px;
            border: 3px solid rgba(255, 255, 255, .3);
            border-radius: 50%;
            border-top-color: #fff;
            animation: spin 1s ease-in-out infinite;
        }

        @keyframes spin {
            to {
                transform: rotate(360deg);
            }
        }

        /* Custom Scrollbar */

        /* Utility Classes */
        .text-gradient {
            background: linear-gradient(135deg, var(--primary-color), var(--accent-color));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .bg-gradient {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
        }

        .shadow-custom {
            box-shadow: var(--shadow-xl);
        }

        .border-gradient {
            border: 2px solid;
            border-image: linear-gradient(135deg, var(--primary-color), var(--accent-color)) 1;
        }

        /* Modern Footer Navigation */
        .footer-nav {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            background: rgba(255, 255, 255, 0.97);
            backdrop-filter: blur(18px);
            border-top: 1px solid rgba(47, 92, 105, 0.1);
            box-shadow: 0 -6px 25px rgba(47, 92, 105, 0.15);
            z-index: 1000;
            padding: 10px 0;
        }

        .footer-nav-container {
            display: flex;
            justify-content: space-around;
            align-items: center;
            max-width: 100%;
            margin: 0 auto;
            padding: 0 12px;
        }

        .footer-nav-item {
            display: flex;
            flex-direction: column;
            align-items: center;
            text-decoration: none;
            color: #3c6f7d;
            transition: all 0.3s ease;
            padding: 8px 12px;
            border-radius: 12px;
            position: relative;
            min-width: 60px;
        }

        .footer-nav-item:hover {
            color: #f3a446;
            transform: translateY(-2px);
        }

        .footer-nav-item.active {
            color: #f3a446;
            font-weight: 600;
        }

        .footer-nav-icon {
            position: relative;
            width: 24px;
            height: 24px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 4px;
        }

        .footer-nav-icon i {
            font-size: 20px;
            transition: 0.3s ease;
        }

        .footer-nav-item:hover .footer-nav-icon i {
            transform: scale(1.1);
        }

        .footer-nav-text {
            font-size: 11px;
            font-weight: 500;
        }

        .footer-nav-center .footer-nav-icon-center {
            width: 52px;
            height: 52px;
            background: linear-gradient(135deg, #2f5c69, #3c6f7d);
            border-radius: 50%;
            margin-bottom: 4px;
            box-shadow: 0 4px 14px rgba(60, 111, 125, 0.3);
            transition: 0.3s ease;
        }

        .footer-nav-center .footer-nav-icon-center i {
            color: #fff;
            font-size: 20px;
        }

        .footer-nav-center:hover .footer-nav-icon-center {
            transform: scale(1.1);
            box-shadow: 0 6px 20px rgba(60, 111, 125, 0.4);
        }

        .footer-nav-center .footer-nav-text {
            color: #2f5c69;
            font-weight: 600;
        }

        .footer-nav-badge {
            position: absolute;
            top: -2px;
            right: -4px;
            color: white;
            font-size: 10px;
            font-weight: bold;
            padding: 2px 6px;
            border-radius: 10px;
            min-width: 18px;
            height: 18px;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.25);
            animation: pulse 2s infinite;
        }

        .footer-nav-badge-messages {
            background: #ef4444;
        }

        .footer-nav-badge-notifications {
            background: #f3a446;
        }

        @keyframes pulse {

            0%,
            100% {
                transform: scale(1);
            }

            50% {
                transform: scale(1.15);
            }
        }

        @media (max-width: 480px) {
            .footer-nav-icon-center {
                width: 44px !important;
                height: 44px !important;
            }

            .footer-nav-icon-center i {
                font-size: 18px;
            }

            .footer-nav-text {
                font-size: 10px;
            }
        }

        /* Add padding to body to prevent content from being hidden behind footer */
        body {
            padding-bottom: 80px;
        }

        /* Hide footer nav on desktop if needed */
        @media (min-width: 1000px) {
            .footer-nav {
                display: none;
            }

            body {
                padding-bottom: 0;
            }
        }

        /* Show footer nav on mobile */
        @media (max-width: 768px) {
            .footer-nav {
                display: block;
            }
        }
    </style>

    @stack('styles')
</head>


<body>
    @php
        $lang = app()->getLocale();
    @endphp
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg fixed-top custom-navbar d-none d-lg-block">
        <div class="container">
            <a class="navbar-brand fw-bold d-flex align-items-center" href="{{ route('home') }}">
                <img src="{{ asset(\App\Models\SystemSetting::get('site_logo', 'home.png')) }}"
                    alt="{{ \App\Models\SystemSetting::get('site_name_ar', 'إنداك') }}" class="me-2"
                    style="height: 40px; width: auto;">
            </a>


            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}"
                            href="{{ route('home') }}">{{ __('messages.home') }}</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('categories.index') ? 'active' : '' }}"
                            href="{{ route('categories.index') }}">{{ __('messages.categories') }}</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('services.index') ? 'active' : '' }}"
                            href="{{ route('services.index') }}">{{ __('messages.services') }}</a>
                    </li>
                    <!-- @auth
                                                                                                                                <li class="nav-item">
                                                                                                                                    <a class="nav-link {{ request()->routeIs('services.my-services') ? 'active' : '' }}" href="{{ route('services.my-services') }}">{{ __('messages.my_services') }}</a>
                                                                                                                                </li>
                                                                                                                                @if (Auth::user()->isProvider())
    <li class="nav-item">
                                                                                                                                        <a class="nav-link {{ request()->routeIs('service-offers.my-offers') ? 'active' : '' }}" href="{{ route('service-offers.my-offers') }}">{{ __('messages.my_offers') }}</a>
                                                                                                                                    </li>
    @endif
                @endauth -->
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('contact') ? 'active' : '' }}"
                            href="{{ route('contact') }}">{{ __('messages.contact_us') }}</a>
                    </li>
                </ul>

                <ul class="navbar-nav">
                    <!-- Language Switcher -->
                    <li class="nav-item dropdown me-2">
                        <a class="nav-link dropdown-toggle language-btn" href="#" role="button">
                            <i class="fas fa-globe me-1"></i>{{ app()->getLocale() === 'ar' ? 'العربية' : 'English' }}
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="{{ route('language.switch', 'ar') }}">
                                    <i class="fas fa-flag me-2"></i>العربية
                                </a></li>
                            <li><a class="dropdown-item" href="{{ route('language.switch', 'en') }}">
                                    <i class="fas fa-flag me-2"></i>English
                                </a></li>
                        </ul>
                    </li>

                    <!-- Messages -->
                    @auth
                        <li class="nav-item me-3 position-relative">
                            <a class="nav-link" href="{{ route('messages.index') }}">
                                <i class="fas fa-comments"></i>
                                @if (Auth::user()->unread_messages_count > 0)
                                    <span id="navbar-messages-badge"
                                        class="badge bg-danger position-absolute top-0 start-100 translate-middle"
                                        style="font-size: 0.7rem; padding: 0.2rem 0.4rem;">
                                        {{ Auth::user()->unread_messages_count > 99 ? '99+' : Auth::user()->unread_messages_count }}
                                    </span>
                                @else
                                    <span id="navbar-messages-badge"
                                        class="badge bg-danger position-absolute top-0 start-100 translate-middle"
                                        style="font-size: 0.7rem; padding: 0.2rem 0.4rem; display: none;"></span>
                                @endif
                            </a>
                        </li>
                    @endauth

                    <!-- Notifications -->
                    @auth
                        <li class="nav-item me-3 position-relative">
                            <a class="nav-link" href="{{ route('notifications.index') }}">
                                <i class="fas fa-bell"></i>
                                <span id="navbar-notifications-badge"
                                    class="badge bg-danger position-absolute top-0 start-100 translate-middle notification-badge"
                                    style="{{ Auth::user()->unread_notifications_count > 0 ? '' : 'display: none;' }}">
                                    {{ Auth::user()->unread_notifications_count > 99 ? '99+' : Auth::user()->unread_notifications_count }}
                                </span>
                            </a>
                        </li>
                    @endauth

                    @auth
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" role="button">
                                @if (Auth::user()->image && file_exists(public_path('storage/' . Auth::user()->image)))
                                    <img src="{{ asset('storage/' . Auth::user()->image) }}"
                                        alt="{{ Auth::user()->name }}" class="rounded-circle me-2"
                                        style="width: 30px; height: 30px; object-fit: cover;">
                                @else
                                    <div class="rounded-circle me-2 d-flex align-items-center justify-content-center bg-primary text-white"
                                        style="width: 30px; height: 30px; font-size: 14px; font-weight: bold;">
                                        {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                                    </div>
                                @endif
                                <span>{{ Auth::user()->name }}</span>
                            </a>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="{{ route('messages.new-design') }}">
                                        <i class="fas fa-comments"></i> الرسائل الجديدة
                                        <span id="messages-badge-menu" class="badge bg-danger ms-2"
                                            style="font-size: 0.7rem; padding: 0.2rem 0.4rem; display: none;">
                                            0
                                        </span>
                                    </a></li>
                                <li><a class="dropdown-item" href="{{ route('messages.index') }}">
                                        <i class="fas fa-comments"></i> الرسائل القديمة
                                    </a></li>
                                <li><a class="dropdown-item" href="{{ route('notifications.index') }}">
                                        <i class="fas fa-bell"></i> الإشعارات
                                        @if (Auth::user()->unread_notifications_count > 0)
                                            <span class="badge bg-danger ms-2"
                                                style="font-size: 0.7rem; padding: 0.2rem 0.4rem;">{{ Auth::user()->unread_notifications_count }}</span>
                                        @endif
                                    </a></li>
                                <li><a class="dropdown-item" href="{{ route('services.my-services') }}">
                                        <i class="fas fa-list"></i> {{ __('messages.my_services') }}
                                    </a></li>
                                @if (Auth::user()->isProvider())
                                    <li><a class="dropdown-item" href="{{ route('service-offers.my-offers') }}">
                                            <i class="fas fa-handshake"></i> {{ __('messages.my_offers') }}
                                        </a></li>
                                @endif
                                <li><a class="dropdown-item"
                                        href="{{ Auth::user()->isProvider() ? route('provider.profile') : route('profile') }}">
                                        <i class="fas fa-user-edit"></i> {{ __('messages.profile') }}
                                    </a></li>
                                @if (Auth::user()->isProvider() && !Auth::user()->hasCompleteProviderProfile())
                                    <li><a class="dropdown-item text-warning"
                                            href="{{ route('provider.complete-profile') }}">
                                            <i class="fas fa-exclamation-triangle"></i> إكمال الملف الشخصي
                                        </a></li>
                                @endif
                                @if (Auth::user()->isProvider() && Auth::user()->hasCompleteProviderProfile())
                                    <li><a class="dropdown-item text-primary"
                                            href="{{ route('provider.profile.edit') }}">
                                            <i class="fas fa-edit"></i> تعديل الملف الشخصي
                                        </a></li>
                                @endif
                                @if (Auth::user()->is_admin)
                                    <li><a class="dropdown-item" href="{{ route('admin.dashboard') }}">
                                            <i class="fas fa-cog"></i> {{ __('messages.admin_panel') }}
                                        </a></li>
                                @endif
                                <li>
                                    <hr class="dropdown-divider" style="margin: 0.3rem 0;">
                                </li>
                                <li>
                                    <form method="POST" action="{{ route('logout') }}" class="d-inline">
                                        @csrf
                                        <button type="submit" class="dropdown-item">
                                            <i class="fas fa-sign-out-alt"></i> {{ __('messages.logout') }}
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </li>
                    @else
                        <li class="nav-item"><a class="nav-link"
                                href="{{ route('login') }}">{{ __('messages.login') }}</a></li>
                        <li class="nav-item"><a class="nav-link"
                                href="{{ route('register') }}">{{ __('messages.register') }}</a></li>
                    @endauth
                </ul>
            </div>
        </div>
    </nav>

    <style>
        .custom-navbar {
            background: rgba(47, 92, 105, 0.9);
            backdrop-filter: blur(10px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.15);
            transition: all 0.4s ease;
        }

        .custom-navbar .navbar-brand {
            color: #fff !important;
            font-size: 1.3rem;
            letter-spacing: 0.5px;
        }

        .custom-navbar .nav-link {
            color: #fff !important;
            font-weight: 500;
            transition: color 0.3s ease, transform 0.2s;
        }

        .custom-navbar .nav-link:hover {
            color: #f3a446 !important;
            transform: scale(1.05);
        }

        .custom-navbar .nav-link.active {
            color: #f3a446 !important;
            font-weight: 600;
            border-bottom: 2px solid #f3a446;
        }

        .dropdown-menu {
            border-radius: 10px;
            border: none;
            background-color: #fff;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        .dropdown-item:hover {
            background-color: rgba(243, 164, 70, 0.1);
            color: #f3a446;
        }

        .navbar-toggler {
            border: none;
        }

        .navbar-toggler:focus {
            box-shadow: none;
        }

        .badge {
            font-size: 0.7rem;
            padding: 0.25em 0.4em;
            border-radius: 50%;
        }
    </style>


    <!-- Main Content -->
    <main>
        <!-- Flash Messages -->
        @if (session('success'))
            <div class="container mt-4">
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle me-2"></i>
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            </div>
        @endif

        @if (session('error'))
            <div class="container mt-4">
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            </div>
        @endif

        @if (session('warning'))
            <div class="container mt-4">
                <div class="alert alert-warning alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-circle me-2"></i>
                    {{ session('warning') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            </div>
        @endif

        @if (session('info'))
            <div class="container mt-4">
                <div class="alert alert-info alert-dismissible fade show" role="alert">
                    <i class="fas fa-info-circle me-2"></i>
                    {{ session('info') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            </div>
        @endif

        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="custom-footer mt-1">
        <div class="container">
            <div class="row text-center text-md-start">
                <!-- Logo & Description -->
                <div class="col-md-4 mb-4">
                    <h5 class="footer-title d-flex align-items-center">
                        <img src="{{ asset(\App\Models\SystemSetting::get('site_logo', 'home.png')) }}"
                            alt="Endak Logo" class="me-2" style="height: 50px; width: auto;">
                        Endak
                    </h5>
                    <p class="footer-text">{{ __('messages.welcome_subtitle') }}</p>
                </div>

                <!-- Quick Links -->
                <div class="col-md-4 mb-4">
                    <h5 class="footer-title">{{ __('messages.quick_links') }}</h5>
                    <ul class="list-unstyled mt-3">
                        <li><a href="{{ route('home') }}" class="footer-link">{{ __('messages.home') }}</a></li>
                        <li><a href="{{ route('categories.index') }}"
                                class="footer-link">{{ __('messages.categories') }}</a></li>
                        <li><a href="{{ route('services.index') }}"
                                class="footer-link">{{ __('messages.services') }}</a></li>
                        <li><a href="{{ route('contact') }}" class="footer-link">{{ __('messages.contact_us') }}</a>
                        </li>
                    </ul>
                </div>

                <!-- Social Media -->
                <div class="col-md-4 mb-4">
                    <h5 class="footer-title">{{ __('messages.contact_info') }}</h5>
                    <div class="social-links mt-3">
                        <a href="#" class="social-icon"><i class="fab fa-facebook-f"></i></a>
                        <a href="#" class="social-icon"><i class="fab fa-twitter"></i></a>
                        <a href="#" class="social-icon"><i class="fab fa-instagram"></i></a>
                        <a href="#" class="social-icon"><i class="fab fa-linkedin-in"></i></a>
                    </div>
                </div>
            </div>

            <!-- About & Terms Links -->
            <div class="row mt-3">
                <div class="col-md-12 d-flex justify-content-start align-items-center">
                    <a href="{{ route('terms') }}" class="footer-link me-3">
                        <i class="fas fa-file-contract text-warning"></i> {{ __('messages.terms_conditions') }}
                    </a>
                    <a href="{{ route('about') }}" class="footer-link">
                        <i class="fas fa-info-circle text-success"></i> {{ __('messages.about_us') }}
                    </a>
                </div>
            </div>

            <hr class="footer-divider">

            <div class="text-center mt-3">
                <p class="footer-copy mb-0 text-muted">
                    &copy; {{ date('Y') }} <strong>Endak</strong>. {{ __('messages.all_rights_reserved') }}
                </p>
            </div>
        </div>
    </footer>



    <style>
        /* === Footer Styling (Matches Navbar) === */
        .custom-footer {
            background: rgba(47, 92, 105, 0.95);
            color: #f9f9f9;
            padding: 50px 0 10px;
            border-top: 2px solid rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(10px);
            position: relative;
        }

        /* Gradient glow at the top edge */
        .custom-footer::before {
            content: "";
            position: absolute;
            top: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 90%;
            height: 3px;
            background: linear-gradient(to right, transparent, #f3a446, transparent);
            opacity: 0.8;
        }

        /* Titles */
        .footer-title {
            font-weight: 600;
            color: #fff;
            font-size: 1.2rem;
            margin-bottom: 15px;
        }

        /* Text */
        .footer-text {
            color: #dce8eb;
            font-size: 0.95rem;
            line-height: 1.7;
        }

        /* Links */
        .footer-link {
            display: block;
            color: #e9f2f4;
            text-decoration: none;
            margin-bottom: 8px;
            transition: all 0.3s ease;
        }

        .footer-link:hover {
            color: #f3a446;
            transform: translateX(5px);
        }

        /* Divider line */
        .footer-divider {
            border-color: rgba(255, 255, 255, 0.1);
            margin: 30px 0;
        }

        /* Social icons */
        .social-icon {
            display: inline-block;
            margin-right: 15px;
            font-size: 20px;
            color: #e9f2f4;
            background: rgba(255, 255, 255, 0.08);
            width: 42px;
            height: 42px;
            border-radius: 50%;
            line-height: 42px;
            text-align: center;
            transition: all 0.3s ease;
        }

        .social-icon:hover {
            color: #f3a446;
            background: rgba(255, 255, 255, 0.15);
            transform: translateY(-3px);
        }


        /* Copy text */
        .footer-copy {
            color: #d3dede;
            font-size: 0.9rem;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .custom-footer {
                text-align: center;
                padding: 40px 20px;
            }
        }
    </style>


    <!-- Modern Footer Navigation -->
    <nav class="footer-nav">
        <div class="footer-nav-container">
            <a href="{{ route('home') }}" class="footer-nav-item {{ request()->routeIs('home') ? 'active' : '' }}">
                <div class="footer-nav-icon">
                    <i class="fas fa-home"></i>
                </div>
                <span class="footer-nav-text">{{ __('messages.home') }}</span>
            </a>

            @auth
                @if (Auth::user()->isProvider())
                    <a href="{{ route('services.index') }}"
                        class="footer-nav-item {{ request()->routeIs('services.my-services') ? 'active' : '' }}">
                        <div class="footer-nav-icon">
                            <i class="fas fa-concierge-bell"></i>
                        </div>
                        <span class="footer-nav-text">{{ __('messages.services') }}</span>
                    </a>
                @else
                    <a href="{{ route('services.index') }}"
                        class="footer-nav-item {{ request()->routeIs('services.my-services') ? 'active' : '' }}">
                        <div class="footer-nav-icon">
                            <i class="fas fa-th-large"></i>
                        </div>
                        <span class="footer-nav-text">{{ __('messages.Ads') }}</span>
                    </a>
                @endif
            @else
                <a href="{{ route('login') }}" class="footer-nav-item">
                    <div class="footer-nav-icon">
                        <i class="fas fa-th-large"></i>
                    </div>
                    <span class="footer-nav-text">{{ __('messages.Ads') }}</span>
                </a>
            @endauth

            @auth
                @if (Auth::user()->isProvider())
                    <a href="{{ route('service-offers.my-offers') }}"
                        class="footer-nav-item footer-nav-center {{ request()->routeIs('service-offers.my-offers') ? 'active' : '' }}">
                        <div class="footer-nav-icon footer-nav-icon-center">
                            <i class="fas fa-handshake"></i>
                        </div>
                        <span class="footer-nav-text">{{ __('messages.my_offers') }}</span>
                    </a>
                @else
                    <a href="{{ route('categories.index') }}" class="footer-nav-item footer-nav-center">
                        <div class="footer-nav-icon footer-nav-icon-center">
                            <i class="fas fa-plus"></i>
                        </div>
                        <span class="footer-nav-text"> {{ __('messages.ad_pup') }}</span>
                    </a>
                @endif
            @else
                <a href="{{ route('categories.index') }}" class="footer-nav-item footer-nav-center">
                    <div class="footer-nav-icon footer-nav-icon-center">
                        <i class="fas fa-plus"></i>
                    </div>
                    <span class="footer-nav-text"> {{ __('messages.ad_pup') }} </span>
                </a>
            @endauth

            @auth
                <a href="{{ route('messages.index') }}"
                    class="footer-nav-item {{ request()->routeIs('messages.*') ? 'active' : '' }}">
                    <div class="footer-nav-icon">
                        <i class="fas fa-comments"></i>
                        @if (Auth::user()->unread_messages_count > 0)
                            <span
                                class="footer-nav-badge footer-nav-badge-messages">{{ Auth::user()->unread_messages_count > 99 ? '99+' : Auth::user()->unread_messages_count }}</span>
                        @endif
                    </div>
                    <span class="footer-nav-text"> {{ __('messages.Message') }}</span>
                </a>
            @else
                <a href="{{ route('login') }}" class="footer-nav-item">
                    <div class="footer-nav-icon">
                        <i class="fas fa-comments"></i>
                    </div>
                    <span class="footer-nav-text"> {{ __('messages.Message') }}</span>
                </a>
            @endauth

            @auth
                <a href="{{ route('notifications.index') }}"
                    class="footer-nav-item {{ request()->routeIs('notifications.*') ? 'active' : '' }}">
                    <div class="footer-nav-icon">
                        <i class="fas fa-bell"></i>
                        @if (Auth::user()->unread_notifications_count > 0)
                            <span
                                class="footer-nav-badge footer-nav-badge-notifications">{{ Auth::user()->unread_notifications_count > 99 ? '99+' : Auth::user()->unread_notifications_count }}</span>
                        @endif
                    </div>
                    <span class="footer-nav-text"> {{ __('messages.notifications') }}</span>
                </a>
            @else
                <a href="{{ route('login') }}" class="footer-nav-item">
                    <div class="footer-nav-icon">
                        <i class="fas fa-bell"></i>
                    </div>
                    <span class="footer-nav-text"> {{ __('messages.notifications') }}</span>
                </a>
            @endauth

            <a href="#" class="footer-nav-item" onclick="toggleMenu()">
                <div class="footer-nav-icon">
                    <i class="fas fa-bars"></i>
                </div>
                <span class="footer-nav-text"> {{ __('messages.menu') }}</span>
            </a>
        </div>
    </nav>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Pusher JS for Realtime Notifications -->
    @if (config('broadcasting.default') === 'pusher')
        <script src="https://js.pusher.com/8.2.0/pusher.min.js"></script>
    @endif

    <!-- Enhanced JavaScript -->
    <script>
        // Navbar scroll effect


        // Smooth scrolling for anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function(e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });

        // Add animation classes on scroll
        const observerOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        };

        const observer = new IntersectionObserver(function(entries) {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('fade-in-up');
                }
            });
        }, observerOptions);

        // Observe all cards and sections
        document.querySelectorAll('.card, .category-card, .section').forEach(el => {
            observer.observe(el);
        });

        // Enhanced button effects
        document.querySelectorAll('.btn').forEach(btn => {
            btn.addEventListener('mouseenter', function() {
                this.style.transform = 'translateY(-2px) scale(1.01)';
            });

            btn.addEventListener('mouseleave', function() {
                this.style.transform = 'translateY(0) scale(1)';
            });
        });

        // Auto-hide flash messages after 5 seconds
        document.querySelectorAll('.alert').forEach(alert => {
            setTimeout(() => {
                if (alert && !alert.classList.contains('alert-dismissed')) {
                    alert.classList.add('alert-dismissed');
                    alert.style.animation = 'slideOutUp 0.5s ease-in forwards';
                    setTimeout(() => {
                        if (alert.parentNode) {
                            alert.parentNode.removeChild(alert);
                        }
                    }, 500);
                }
            }, 5000);
        });

        // Add slideOutUp animation
        const style = document.createElement('style');
        style.textContent = `
            @keyframes slideOutUp {
                from {
                    transform: translateY(0);
                    opacity: 1;
                }
                to {
                    transform: translateY(-100%);
                    opacity: 0;
                }
            }
        `;
        document.head.appendChild(style);

        // Card hover effects
        document.querySelectorAll('.card, .category-card').forEach(card => {
            card.addEventListener('mouseenter', function() {
                this.style.transform = 'translateY(-8px) scale(1.01)';
            });

            card.addEventListener('mouseleave', function() {
                this.style.transform = 'translateY(0) scale(1)';
            });
        });

        // Enhanced dropdown functionality
        document.addEventListener('DOMContentLoaded', function() {
            console.log('DOM loaded, initializing dropdowns...');

            // Simple dropdown functionality
            document.querySelectorAll('.dropdown-toggle').forEach(toggle => {
                toggle.addEventListener('click', function(e) {
                    e.preventDefault();
                    e.stopPropagation();

                    const dropdown = this.closest('.dropdown');
                    const menu = dropdown.querySelector('.dropdown-menu');

                    // Close all other dropdowns
                    document.querySelectorAll('.dropdown-menu.show').forEach(otherMenu => {
                        if (otherMenu !== menu) {
                            otherMenu.classList.remove('show');
                        }
                    });

                    // Toggle current dropdown
                    menu.classList.toggle('show');

                    console.log('Dropdown toggled:', menu.classList.contains('show'));
                });
            });

            // Close dropdowns when clicking outside
            document.addEventListener('click', function(e) {
                if (!e.target.closest('.dropdown')) {
                    document.querySelectorAll('.dropdown-menu.show').forEach(menu => {
                        menu.classList.remove('show');
                    });
                }
            });

            // Close dropdowns when pressing Escape
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') {
                    document.querySelectorAll('.dropdown-menu.show').forEach(menu => {
                        menu.classList.remove('show');
                    });
                }
            });

            // Add hover effect for dropdown items
            document.querySelectorAll('.dropdown-item').forEach(item => {
                item.addEventListener('mouseenter', function() {
                    this.style.transform = 'translateX(5px)';
                });

                item.addEventListener('mouseleave', function() {
                    this.style.transform = 'translateX(0)';
                });
            });

            // Debug: Log all dropdown elements
            console.log('All dropdown toggles:', document.querySelectorAll('.dropdown-toggle'));
            console.log('All dropdown menus:', document.querySelectorAll('.dropdown-menu'));
            console.log('User dropdown:', document.querySelector('.nav-item.dropdown'));

            // Test user dropdown specifically
            const userDropdown = document.querySelector('.nav-item.dropdown .dropdown-toggle');
            if (userDropdown) {
                console.log('User dropdown found:', userDropdown);
                userDropdown.addEventListener('click', function(e) {
                    console.log('User dropdown clicked!');
                    const menu = this.closest('.dropdown').querySelector('.dropdown-menu');
                    console.log('User menu:', menu);
                    if (menu) {
                        menu.style.display = 'block';
                        menu.style.opacity = '1';
                        menu.style.visibility = 'visible';
                        menu.style.transform = 'translateY(0)';
                        menu.style.zIndex = '9999';
                        console.log('User menu forced to show');
                    }
                });
            }

            // Additional dropdown fix
            document.querySelectorAll('.dropdown-toggle').forEach(toggle => {
                toggle.addEventListener('click', function(e) {
                    const menu = this.nextElementSibling;
                    if (menu && menu.classList.contains('dropdown-menu')) {
                        menu.style.display = menu.style.display === 'block' ? 'none' : 'block';
                    }
                });
            });
        });

        // Loading states for forms
        document.querySelectorAll('form').forEach(form => {
            form.addEventListener('submit', function() {
                const submitBtn = this.querySelector('button[type="submit"]');
                if (submitBtn) {
                    submitBtn.innerHTML = '<span class="loading"></span> جاري الإرسال...';
                    submitBtn.disabled = true;
                }
            });
        });

        // Tooltip initialization
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });

        // Messages Update Script
        @auth

        function updateMessagesCount() {
            fetch('{{ route('messages.unread-count') }}')
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const badge = document.getElementById('messages-badge');
                        const menuBadge = document.getElementById('messages-badge-menu');

                        if (data.count > 0) {
                            const count = data.count > 99 ? '99+' : data.count;
                            if (badge) {
                                badge.textContent = count;
                                badge.style.display = 'block';
                            }
                            if (menuBadge) {
                                menuBadge.textContent = count;
                                menuBadge.style.display = 'inline-block';
                            }
                        } else {
                            if (badge) {
                                badge.style.display = 'none';
                            }
                            if (menuBadge) {
                                menuBadge.style.display = 'none';
                            }
                        }
                    }
                })
                .catch(error => console.log('Error updating messages count:', error));
        }

        setInterval(updateMessagesCount, 30000);
        updateMessagesCount();
        @endauth

        // Notifications Update Script (Realtime + Polling)
        @auth
        // دالة تحديث عداد الإشعارات
        function updateNotificationBadge(count) {
            const bell = document.querySelector('.fa-bell');
            const bellParent = bell ? bell.parentElement : null;

            // البحث عن badge في navbar
            let navbarBadge = document.getElementById('navbar-notifications-badge');

            // إزالة أي badges مكررة في navbar (إذا وجدت أكثر من badge بنفس ID أو class)
            if (bellParent) {
                const allBadges = bellParent.querySelectorAll('.badge, .notification-badge, span.bg-danger');
                let foundMainBadge = false;
                allBadges.forEach(badge => {
                    if (badge.id === 'navbar-notifications-badge') {
                        if (foundMainBadge) {
                            // إذا كان هناك badge مكرر بنفس ID، احذفه
                            badge.remove();
                        } else {
                            foundMainBadge = true;
                            navbarBadge = badge; // استخدم الـ badge الموجود
                        }
                    } else if (badge.classList.contains('badge') || badge.classList.contains(
                            'notification-badge')) {
                        // إذا كان هناك badge آخر بدون ID أو بـ ID مختلف، احذفه
                        badge.remove();
                    }
                });
            }

            // إذا لم يكن موجوداً بعد التنظيف، أنشئ واحداً جديداً
            if (!navbarBadge && bellParent) {
                navbarBadge = document.createElement('span');
                navbarBadge.id = 'navbar-notifications-badge';
                navbarBadge.className =
                    'badge bg-danger position-absolute top-0 start-100 translate-middle notification-badge';
                bellParent.appendChild(navbarBadge);
            }

            // تحديث أو إنشاء badge في footer
            let footerBadge = document.querySelector('.footer-nav-badge-notifications');
            if (!footerBadge) {
                const footerIcon = document.querySelector('.footer-nav-item[href*="notifications"] .footer-nav-icon');
                if (footerIcon) {
                    footerBadge = document.createElement('span');
                    footerBadge.className = 'footer-nav-badge footer-nav-badge-notifications';
                    footerIcon.appendChild(footerBadge);
                }
            }

            // تحديث أو إنشاء badge في menu
            let menuBadge = document.querySelector('#notifications-menu-badge');
            if (!menuBadge) {
                const menuLink = document.querySelector('a[href*="notifications"]');
                if (menuLink) {
                    menuBadge = document.createElement('span');
                    menuBadge.id = 'notifications-menu-badge';
                    menuBadge.className = 'badge bg-danger ms-2';
                    menuBadge.style.cssText = 'font-size: 0.7rem; padding: 0.2rem 0.4rem;';
                    menuLink.appendChild(menuBadge);
                }
            }

            const displayCount = count > 99 ? '99+' : count;

            if (count > 0) {
                if (navbarBadge) {
                    // مسح أي محتوى موجود مسبقاً (للتأكد من عدم وجود تكرار)
                    while (navbarBadge.firstChild) {
                        navbarBadge.removeChild(navbarBadge.firstChild);
                    }
                    navbarBadge.textContent = displayCount;
                    navbarBadge.style.display = 'block';
                }
                if (footerBadge) {
                    footerBadge.textContent = displayCount;
                    footerBadge.style.display = 'flex';
                }
                if (menuBadge) {
                    menuBadge.textContent = displayCount;
                    menuBadge.style.display = 'inline-block';
                }

                // تحديث title الصفحة
                if (count > 0) {
                    document.title = `(${displayCount}) ${document.title.replace(/^\(\d+\)\s*/, '')}`;
                }
            } else {
                if (navbarBadge) navbarBadge.style.display = 'none';
                if (footerBadge) footerBadge.style.display = 'none';
                if (menuBadge) menuBadge.style.display = 'none';
                document.title = document.title.replace(/^\(\d+\)\s*/, '');
            }
        }

        // Realtime Notifications via Pusher
        @if (config('broadcasting.default') === 'pusher')
            (function() {
                const userId = {{ Auth::id() }};

                // للـ Web Routes: استخدام session authentication (CSRF token)
                // للـ API Routes: استخدام API token
                let apiToken = localStorage.getItem('api_token');
                const isWebRoute = !apiToken; // إذا لم يكن API token، نحن في web route

                // إعداد authEndpoint بناءً على نوع الـ route
                const authEndpoint = isWebRoute ? '/broadcasting/auth' : '/api/broadcasting/auth';

                // إعداد headers
                let authHeaders = {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                };

                // الحصول على CSRF token
                const csrfTokenMeta = document.querySelector('meta[name="csrf-token"]');
                const csrfToken = csrfTokenMeta ? csrfTokenMeta.getAttribute('content') : null;

                if (apiToken) {
                    // API route - استخدام Bearer token
                    authHeaders['Authorization'] = 'Bearer ' + apiToken;
                } else if (csrfToken) {
                    // Web route - استخدام CSRF token
                    authHeaders['X-CSRF-TOKEN'] = csrfToken;
                } else {
                    console.warn(
                        '⚠️ API Token or CSRF Token not found for realtime notifications. Using polling only.');
                    return;
                }

                // تحميل Pusher JS إذا لم يكن محملاً
                if (typeof Pusher === 'undefined') {
                    const script = document.createElement('script');
                    script.src = 'https://js.pusher.com/8.2.0/pusher.min.js';
                    script.onload = function() {
                        initPusherNotifications();
                    };
                    document.head.appendChild(script);
                } else {
                    initPusherNotifications();
                }

                function initPusherNotifications() {
                    try {
                        const pusher = new Pusher('{{ config('broadcasting.connections.pusher.key') }}', {
                            cluster: '{{ config('broadcasting.connections.pusher.options.cluster') }}',
                            forceTLS: true,
                            encrypted: true,
                            authEndpoint: authEndpoint,
                            auth: {
                                headers: authHeaders
                            },
                            enabledTransports: ['ws', 'wss']
                        });

                        // Event listeners للاتصال
                        pusher.connection.bind('connected', () => {
                            console.log('✅ متصل بـ Pusher للإشعارات realtime');
                        });

                        pusher.connection.bind('error', (err) => {
                            console.error('❌ خطأ في اتصال Pusher:', err);
                        });

                        // الاشتراك في channel
                        const channel = pusher.subscribe('private-user.' + userId);

                        // Event listeners للـ channel
                        channel.bind('pusher:subscription_succeeded', () => {
                            console.log('✅ تم الاشتراك في إشعارات realtime للمستخدم:', userId);
                        });

                        channel.bind('pusher:subscription_error', (status) => {
                            console.error('❌ فشل الاشتراك في إشعارات realtime:', status);
                            console.log('User ID:', userId);
                            console.log('API Token preview:', apiToken ? apiToken.substring(0, 10) + '...' :
                                'NOT SET');
                        });

                        // الاستماع لحدث notification.sent
                        channel.bind('notification.sent', (data) => {
                            console.log('✅ إشعار جديد realtime:', data);

                            // تحديث العداد مباشرة
                            if (data.unread_count !== undefined) {
                                updateNotificationBadge(data.unread_count);
                                console.log('📊 تم تحديث عداد الإشعارات:', data.unread_count);
                            }

                            // عرض إشعار المتصفح
                            if ('Notification' in window && Notification.permission === 'granted') {
                                new Notification(data.title, {
                                    body: data.message,
                                    icon: '/images/logo.png',
                                    badge: '/images/badge.png',
                                    tag: 'notification-' + data.id
                                });
                            }
                        });
                    } catch (error) {
                        console.error('❌ خطأ في إعداد Pusher:', error);
                    }
                }
            })();
        @endif

        // Polling fallback (كل 30 ثانية)
        setInterval(function() {
            fetch('/notifications/unread', {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.count !== undefined) {
                        updateNotificationBadge(data.count);
                    }
                })
                .catch(error => console.log('Error updating notifications:', error));
        }, 30000);

        // تحديث أولي عند تحميل الصفحة
        fetch('/notifications/unread', {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.count !== undefined) {
                    updateNotificationBadge(data.count);
                }
            })
            .catch(error => console.log('Error updating notifications:', error));
        @endauth



        // Search functionality enhancement
        const searchInput = document.querySelector('input[type="search"]');
        if (searchInput) {
            searchInput.addEventListener('input', function() {
                const searchTerm = this.value.toLowerCase();
                const cards = document.querySelectorAll('.card, .category-card');

                cards.forEach(card => {
                    const text = card.textContent.toLowerCase();
                    if (text.includes(searchTerm)) {
                        card.style.display = 'block';
                        card.classList.add('fade-in-up');
                    } else {
                        card.style.display = 'none';
                    }
                });
            });
        }

        // Theme toggle (if needed)
        const themeToggle = document.querySelector('#theme-toggle');
        if (themeToggle) {
            themeToggle.addEventListener('click', function() {
                document.body.classList.toggle('dark-theme');
                localStorage.setItem('theme', document.body.classList.contains('dark-theme') ? 'dark' : 'light');
            });
        }

        // Initialize theme from localStorage
        if (localStorage.getItem('theme') === 'dark') {
            document.body.classList.add('dark-theme');
        }

        // Footer Navigation Functions
        function toggleMenu() {
            // Create a modal or dropdown menu for the menu button
            @auth
            @if (Auth::user()->isProvider())
                // مزود الخدمة
                const menuItems = [{
                        icon: 'fas fa-user',
                        text: '{{ __('messages.menu_profile') }}',
                        href: '{{ route('provider.profile') }}'
                    },
                    {
                        icon: 'fas fa-concierge-bell',
                        text: '{{ __('messages.menu_my_services') }}',
                        href: '{{ route('services.my-services') }}'
                    },
                    {
                        icon: 'fas fa-handshake',
                        text: '{{ __('messages.menu_my_offers') }}',
                        href: '{{ route('service-offers.my-offers') }}'
                    },
                    {
                        icon: 'fas fa-cog',
                        text: '{{ __('messages.menu_settings') }}',
                        href: '#'
                    },
                    {
                        icon: 'fas fa-question-circle',
                        text: '{{ __('messages.menu_help') }}',
                        href: '{{ route('contact') }}'
                    },
                    {
                        icon: 'fas fa-info-circle',
                        text: '{{ __('messages.menu_about') }}',
                        href: '#'
                    }
                ];
            @else
                // مستخدم عادي
                const menuItems = [{
                        icon: 'fas fa-user',
                        text: '{{ __('messages.menu_profile') }}',
                        href: '{{ route('profile') }}'
                    },
                    {
                        icon: 'fas fa-th-large',
                        text: '{{ __('messages.menu_my_ads') }}',
                        href: '{{ route('services.index') }}'
                    },
                    {
                        icon: 'fas fa-cog',
                        text: '{{ __('messages.menu_settings') }}',
                        href: '#'
                    },
                    {
                        icon: 'fas fa-question-circle',
                        text: '{{ __('messages.menu_help') }}',
                        href: '{{ route('contact') }}'
                    },
                    {
                        icon: 'fas fa-info-circle',
                        text: '{{ __('messages.menu_about') }}',
                        href: '#'
                    }
                ];
            @endif
        @else
            // ضيف (غير مسجل الدخول)
            const menuItems = [{
                    icon: 'fas fa-user-plus',
                    text: '{{ __('messages.menu_create_account') }}',
                    href: '{{ route('register') }}'
                },
                {
                    icon: 'fas fa-question-circle',
                    text: '{{ __('messages.menu_help') }}',
                    href: '{{ route('contact') }}'
                },
                {
                    icon: 'fas fa-info-circle',
                    text: '{{ __('messages.menu_about') }}',
                    href: '#'
                }
            ];
        @endauth

        // Language switcher items
        const languageItems = [{
                icon: 'fas fa-flag',
                text: 'العربية',
                href: '{{ route('language.switch', 'ar') }}',
                isActive: '{{ app()->getLocale() }}' === 'ar'
            },
            {
                icon: 'fas fa-flag',
                text: 'English',
                href: '{{ route('language.switch', 'en') }}',
                isActive: '{{ app()->getLocale() }}' === 'en'
            }
        ];

        const menuModal = document.createElement('div');
        menuModal.className = 'footer-menu-modal';
        menuModal.innerHTML = `
                <div class="footer-menu-overlay" onclick="closeMenu()"></div>
                <div class="footer-menu-content">
                    <div class="footer-menu-header">
                        <h6>القائمة</h6>
                        <button onclick="closeMenu()" class="footer-menu-close">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                    <div class="footer-menu-items">
                        <!-- Language Switcher Section -->
                        <div class="footer-menu-section">
                            <div class="footer-menu-section-title">
                                <i class="fas fa-globe"></i>
                                <span>اللغة / Language</span>
                            </div>
                            ${languageItems.map(item => `
                                                                                                                                    <a href="${item.href}" class="footer-menu-item ${item.isActive ? 'active' : ''}">
                                                                                                                                        <i class="${item.icon}"></i>
                                                                                                                                        <span>${item.text}</span>
                                                                                                                                        ${item.isActive ? '<i class="fas fa-check text-success"></i>' : ''}
                                                                                                                                    </a>
                                                                                                                                `).join('')}
                        </div>

                        <hr class="footer-menu-divider">

                        <!-- Main Menu Items -->
                        ${menuItems.map(item => `
                                                                                                                                <a href="${item.href}" class="footer-menu-item">
                                                                                                                                    <i class="${item.icon}"></i>
                                                                                                                                    <span>${item.text}</span>
                                                                                                                                </a>
                                                                                                                            `).join('')}

                        @auth
                         @if (Auth::user()->is_admin)
                            <a href="{{ route('admin.dashboard') }}" class="footer-menu-item">
                                <i class="fas fa-cog"></i>
                                <span>{{ __('messages.admin_panel') }}</span>
                            </a>
                        @endif
                            <hr class="footer-menu-divider">
                            <a href="{{ route('logout') }}" class="footer-menu-item" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                <i class="fas fa-sign-out-alt"></i>
                                <span> </span>
                            </a>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                @csrf
                            </form>
                        @else
                            <hr class="footer-menu-divider">
                            <a href="{{ route('login') }}" class="footer-menu-item">
                                <i class="fas fa-sign-in-alt"></i>
                                <span> {{ __('messages.login') }}</span>
                            </a>
                        @endauth

                    </div>
                </div>
            `;


        const modalStyles = document.createElement('style');
        modalStyles.textContent = `
                .footer-menu-modal {
                    position: fixed;
                    top: 0;
                    left: 0;
                    right: 0;
                    bottom: 0;
                    z-index: 9999;
                    display: flex;
                    align-items: flex-end;
                }

                .footer-menu-overlay {
                    position: absolute;
                    top: 0;
                    left: 0;
                    right: 0;
                    bottom: 0;
                    background: rgba(0, 0, 0, 0.5);
                    backdrop-filter: blur(5px);
                }

                .footer-menu-content {
                    background: white;
                    border-radius: 20px 20px 0 0;
                    width: 100%;
                    max-height: 70vh;
                    overflow-y: auto;
                    position: relative;
                    z-index: 1;
                    animation: slideUp 0.3s ease-out;
                }

                .footer-menu-header {
                    display: flex;
                    justify-content: space-between;
                    align-items: center;
                    padding: 20px 20px 10px;
                    border-bottom: 1px solid #f0f0f0;
                }

                .footer-menu-header h6 {
                    margin: 0;
                    font-weight: 600;
                    color: #333;
                }

                .footer-menu-close {
                    background: none;
                    border: none;
                    font-size: 18px;
                    color: #666;
                    cursor: pointer;
                    padding: 5px;
                    border-radius: 50%;
                    transition: all 0.3s ease;
                }

                .footer-menu-close:hover {
                    background: #f0f0f0;
                    color: #333;
                }

                .footer-menu-items {
                    padding: 10px 0 30px;
                }

                .footer-menu-item {
                    display: flex;
                    align-items: center;
                    padding: 15px 20px;
                    text-decoration: none;
                    color: #333;
                    transition: all 0.3s ease;
                    border-bottom: 1px solid #f8f8f8;
                }

                .footer-menu-item:hover {
                    background: #f8f9fa;
                    color: var(--primary-color);
                    text-decoration: none;
                }

                .footer-menu-item i {
                    width: 24px;
                    margin-left: 15px;
                    font-size: 16px;
                }

                .footer-menu-item span {
                    font-weight: 500;
                }

                .footer-menu-item.active {
                    background: linear-gradient(135deg, #685C84, #6A5E86);
                    color: white;
                }

                .footer-menu-item.active:hover {
                    background: linear-gradient(135deg, #5a4f73, #5c5077);
                    color: white;
                }

                .footer-menu-section {
                    margin-bottom: 15px;
                }

                .footer-menu-section-title {
                    display: flex;
                    align-items: center;
                    padding: 10px 20px 5px;
                    font-weight: 600;
                    color: #685C84;
                    font-size: 14px;
                    border-bottom: 1px solid #f0f0f0;
                    margin-bottom: 5px;
                }

                .footer-menu-section-title i {
                    margin-left: 8px;
                    font-size: 16px;
                }

                .footer-menu-divider {
                    margin: 10px 0;
                    border: none;
                    border-top: 1px solid #f0f0f0;
                }

                @keyframes slideUp {
                    from {
                        transform: translateY(100%);
                    }
                    to {
                        transform: translateY(0);
                    }
                }
            `;

        document.head.appendChild(modalStyles);
        document.body.appendChild(menuModal);

        // Prevent body scroll
        document.body.style.overflow = 'hidden';
        }



        // Add slideDown animation
        const slideDownStyle = document.createElement('style');

        document.head.appendChild(slideDownStyle);

        // Update footer navigation badges
        @auth

        function updateFooterNavBadges() {
            fetch('{{ route('messages.unread-count') }}')
                .then(response => response.json())
                .then(data => {
                    // Update footer badge
                    const footerBadge = document.querySelector('.footer-nav-badge-messages');
                    const footerIcon = document.querySelector('.footer-nav-item[href*="messages"] .footer-nav-icon');
                    if (data.success && data.count > 0) {
                        const count = data.count > 99 ? '99+' : data.count;
                        if (footerBadge) {
                            footerBadge.textContent = count;
                            footerBadge.style.display = 'flex';
                        } else if (footerIcon) {
                            const newBadge = document.createElement('span');
                            newBadge.className = 'footer-nav-badge footer-nav-badge-messages';
                            newBadge.textContent = count;
                            footerIcon.appendChild(newBadge);
                        }
                    } else if (footerBadge) {
                        footerBadge.style.display = 'none';
                    }

                    // Update navbar badge
                    const navbarBadge = document.getElementById('navbar-messages-badge');
                    const menuBadge = document.getElementById('messages-badge-menu');
                    if (data.success && data.count > 0) {
                        const count = data.count > 99 ? '99+' : data.count;
                        if (navbarBadge) {
                            navbarBadge.textContent = count;
                            navbarBadge.style.display = 'inline-block';
                        }
                        if (menuBadge) {
                            menuBadge.textContent = count;
                            menuBadge.style.display = 'inline-block';
                        }
                    } else {
                        if (navbarBadge) {
                            navbarBadge.style.display = 'none';
                        }
                        if (menuBadge) {
                            menuBadge.style.display = 'none';
                        }
                    }
                })
                .catch(error => {
                    console.error('Error updating messages badge:', error);
                });

            fetch('/notifications/unread', {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    const badge = document.querySelector('.footer-nav-badge-notifications');
                    const icon = document.querySelector('.footer-nav-item[href*="notifications"] .footer-nav-icon');
                    if (data.count > 0) {
                        const count = data.count > 99 ? '99+' : data.count;
                        if (badge) {
                            badge.textContent = count;
                            badge.style.display = 'flex';
                        } else {
                            const newBadge = document.createElement('span');
                            newBadge.className = 'footer-nav-badge footer-nav-badge-notifications';
                            newBadge.textContent = count;
                            icon.appendChild(newBadge);
                        }
                    } else if (badge) {
                        badge.style.display = 'none';
                    }
                });
        }


        setInterval(updateFooterNavBadges, 30000);
        updateFooterNavBadges();
        @endauth

        function closeMenu() {
            const menuModal = document.querySelector('.footer-menu-modal');
            if (menuModal) {
                menuModal.remove();
                document.body.style.overflow = '';
            }
        }

        function handleProtectedLink(event, href) {
            @guest
            event.preventDefault();
            if (confirm('يجب تسجيل الدخول أولاً. هل تريد الانتقال لصفحة تسجيل الدخول؟')) {
                window.location.href = '{{ route('login') }}';
            }
        @endguest
        }
    </script>

    @stack('scripts')

    <style>
        .dropdown-menu {
            border: none !important;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15) !important;
            border-radius: 12px !important;
            backdrop-filter: blur(10px);
            background: rgba(255, 255, 255, 0.95) !important;
        }

        .dropdown-item {
            transition: all 0.2s ease !important;
            border-radius: 8px !important;
            margin: 2px 8px !important;
        }

        .dropdown-item:hover {
            background: linear-gradient(135deg, #8b5cf6 0%, #a855f7 100%) !important;
            color: white !important;
            transform: translateX(-5px) !important;
        }

        /* تحسين البادج */
        .badge {
            animation: pulse 2s infinite;
        }

        @keyframes pulse {

            0%,
            100% {
                opacity: 1;
            }

            50% {
                opacity: 0.7;
            }
        }

        .btn {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1) !important;
        }

        .btn:hover {
            transform: translateY(-2px) !important;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15) !important;
        }

        .navbar {
            backdrop-filter: blur(20px) !important;
        }

        .card {
            transition: all 0.3s ease !important;
        }

        .card:hover {
            transform: translateY(-5px) !important;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1) !important;
        }

        .whatsapp-float {
            position: fixed;
            width: 60px;
            height: 60px;
            bottom: 80px;
            left: 20px;
            background-color: #25d366;
            color: white;
            border-radius: 50px;
            text-align: center;
            font-size: 30px;
            box-shadow: 2px 2px 3px #999;
            z-index: 1000;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
            text-decoration: none;
            animation: pulse-whatsapp 2s infinite;

        }

        .whatsapp-float:hover {
            background-color: #128c7e;
            color: white;
            text-decoration: none;
            transform: scale(1.1);
        }

        @keyframes pulse-whatsapp {
            0% {
                box-shadow: 0 0 0 0 rgba(37, 211, 102, 0.7);
            }

            70% {
                box-shadow: 0 0 0 10px rgba(37, 211, 102, 0);
            }

            100% {
                box-shadow: 0 0 0 0 rgba(37, 211, 102, 0);
            }
        }

        @media (max-width: 768px) {
            .whatsapp-float {
                width: 50px;
                height: 50px;
                font-size: 24px;
                bottom: 70px;
                left: 15px;
                margin-bottom: 100px
            }
        }
    </style>

    @if (\App\Helpers\WhatsAppHelper::isEnabled())
        <a href="{{ \App\Helpers\WhatsAppHelper::getWhatsAppUrl() }}" class="whatsapp-float" target="_blank"
            title="تواصل معنا عبر الواتساب">
            <i class="fab fa-whatsapp"></i>
        </a>
    @endif
    <!-- User Type Selection Modal -->
    @auth
        @if (session('show_user_type_modal'))
            @include('layouts.user_type_modal') {{-- ضع كود المودال في ملف جزئي --}}
        @endif
    @endauth

</body>

</html>
