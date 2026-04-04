@extends('layouts.app')

@section('title', 'طلب خدمة - ' . $category->name)

@section('content')

    <style>
        /* ===== Page Header ===== */
        .request-hero {
            background: linear-gradient(135deg, var(--e-primary-dark) 0%, var(--e-primary) 50%, var(--e-primary-light) 100%);
            padding: 2.5rem 0 3.5rem;
            position: relative;
            overflow: hidden;
        }

        .request-hero::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -20%;
            width: 500px;
            height: 500px;
            background: radial-gradient(circle, rgba(243, 164, 70, 0.1) 0%, transparent 70%);
            border-radius: 50%;
        }

        .request-hero::after {
            content: '';
            position: absolute;
            bottom: -30%;
            left: -10%;
            width: 400px;
            height: 400px;
            background: radial-gradient(circle, rgba(255, 255, 255, 0.05) 0%, transparent 70%);
            border-radius: 50%;
        }

        .hero-breadcrumb .breadcrumb {
            background: none;
            padding: 0;
            margin-bottom: 1.5rem;
        }

        .hero-breadcrumb .breadcrumb-item a {
            color: rgba(255, 255, 255, 0.7);
            text-decoration: none;
            font-size: 0.85rem;
            transition: color var(--e-transition);
        }

        .hero-breadcrumb .breadcrumb-item a:hover {
            color: var(--e-accent);
        }

        .hero-breadcrumb .breadcrumb-item.active {
            color: rgba(255, 255, 255, 0.5);
            font-size: 0.85rem;
        }

        .hero-breadcrumb .breadcrumb-item+.breadcrumb-item::before {
            color: rgba(255, 255, 255, 0.4);
        }

        .hero-category {
            display: flex;
            align-items: center;
            gap: 1.5rem;
        }

        .hero-category-img {
            width: 80px;
            height: 80px;
            border-radius: var(--e-radius-lg);
            object-fit: cover;
            border: 3px solid rgba(255, 255, 255, 0.2);
            flex-shrink: 0;
        }

        .hero-category-info h1 {
            color: #fff;
            font-size: 1.6rem;
            font-weight: 700;
            margin-bottom: 0.25rem;
        }

        .hero-category-info p {
            color: rgba(255, 255, 255, 0.7);
            font-size: 0.9rem;
            margin-bottom: 0;
            line-height: 1.5;
        }

        .hero-subcategory-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            background: rgba(255, 255, 255, 0.12);
            color: var(--e-accent-light);
            padding: 0.35rem 0.85rem;
            border-radius: var(--e-radius-full);
            font-size: 0.8rem;
            margin-top: 0.6rem;
            backdrop-filter: blur(10px);
        }

        /* ===== Main Content Area ===== */
        .request-content {
            margin-top: -2rem;
            padding-bottom: 3rem;
            position: relative;
            z-index: 1;
        }

        /* ===== Form Card ===== */
        .request-form-card {
            background: var(--e-white);
            border-radius: var(--e-radius-xl);
            box-shadow: var(--e-shadow-lg);
            overflow: hidden;
            border: 1px solid var(--e-gray-100);
        }

        .form-section {
            padding: 1.75rem 2rem;
            border-bottom: 1px solid var(--e-gray-100);
        }

        .form-section:last-of-type {
            border-bottom: none;
        }

        .form-section-title {
            font-size: 1rem;
            font-weight: 700;
            color: var(--e-gray-800);
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
            gap: 0.6rem;
        }

        .form-section-title i {
            color: var(--e-primary);
            font-size: 1.1rem;
            width: 32px;
            height: 32px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background: var(--e-primary-50);
            border-radius: var(--e-radius-sm);
        }

        /* ===== Floating Label Input System ===== */
        .ef-field {
            position: relative;
            margin-bottom: 0;
        }

        .ef-field .ef-input,
        .ef-field .ef-select,
        .ef-field .ef-textarea {
            width: 100%;
            border: 1.5px solid var(--e-gray-200);
            border-radius: var(--e-radius);
            padding: 1.4rem 1rem 0.5rem;
            font-size: 0.92rem;
            color: var(--e-gray-800);
            background-color: var(--e-white);
            transition: border-color var(--e-transition), box-shadow var(--e-transition), background-color var(--e-transition);
            outline: none;
            font-family: var(--e-font);
            line-height: 1.5;
            appearance: none;
            -webkit-appearance: none;
        }

        .ef-field .ef-input::placeholder,
        .ef-field .ef-textarea::placeholder {
            color: transparent;
        }

        .ef-field .ef-input:focus,
        .ef-field .ef-select:focus,
        .ef-field .ef-textarea:focus {
            border-color: var(--e-primary);
            box-shadow: 0 0 0 3px var(--e-primary-100);
            background-color: var(--e-white);
        }

        .ef-field .ef-label {
            position: absolute;
            top: 50%;
            right: 1rem;
            transform: translateY(-50%);
            font-size: 0.9rem;
            color: var(--e-gray-400);
            pointer-events: none;
            transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
            transform-origin: right center;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            max-width: calc(100% - 2rem);
            font-weight: 500;
            line-height: 1;
        }

        [dir="ltr"] .ef-field .ef-label {
            right: auto;
            left: 1rem;
            transform-origin: left center;
        }

        /* Float label up on focus or when input has value */
        .ef-field .ef-input:focus ~ .ef-label,
        .ef-field .ef-input:not(:placeholder-shown) ~ .ef-label,
        .ef-field .ef-select:focus ~ .ef-label,
        .ef-field .ef-select:not([data-empty="true"]) ~ .ef-label,
        .ef-field .ef-textarea:focus ~ .ef-label,
        .ef-field .ef-textarea:not(:placeholder-shown) ~ .ef-label,
        .ef-field .ef-label.ef-label--float {
            top: 0.55rem;
            transform: translateY(0) scale(0.78);
            color: var(--e-primary);
            font-weight: 600;
        }

        /* Textarea label positioning */
        .ef-field--textarea .ef-label {
            top: 1rem;
            transform: translateY(0);
        }

        .ef-field--textarea .ef-textarea:focus ~ .ef-label,
        .ef-field--textarea .ef-textarea:not(:placeholder-shown) ~ .ef-label {
            top: 0.55rem;
        }

        .ef-field .ef-textarea {
            min-height: 110px;
            resize: vertical;
            padding-top: 1.6rem;
        }

        /* Select chevron */
        .ef-field .ef-select {
            cursor: pointer;
            padding-left: 2.2rem;
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16'%3e%3cpath fill='none' stroke='%2394a3b8' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='m2 5 6 6 6-6'/%3e%3c/svg%3e");
            background-repeat: no-repeat;
            background-position: left 0.85rem center;
            background-size: 14px 10px;
        }

        [dir="rtl"] .ef-field .ef-select {
            padding-left: 1rem;
            padding-right: 1rem;
            background-position: left 0.85rem center;
        }

        [dir="ltr"] .ef-field .ef-select {
            padding-right: 2.2rem;
            padding-left: 1rem;
            background-position: right 0.85rem center;
        }

        /* Input with leading icon */
        .ef-field--icon .ef-icon {
            position: absolute;
            top: 50%;
            right: 1rem;
            transform: translateY(-50%);
            color: var(--e-gray-400);
            font-size: 0.95rem;
            transition: color var(--e-transition);
            pointer-events: none;
            z-index: 1;
        }

        [dir="ltr"] .ef-field--icon .ef-icon {
            right: auto;
            left: 1rem;
        }

        .ef-field--icon .ef-input,
        .ef-field--icon .ef-select {
            padding-right: 2.6rem;
        }

        [dir="ltr"] .ef-field--icon .ef-input,
        [dir="ltr"] .ef-field--icon .ef-select {
            padding-right: 1rem;
            padding-left: 2.6rem;
        }

        .ef-field--icon .ef-label {
            right: 2.6rem;
        }

        [dir="ltr"] .ef-field--icon .ef-label {
            right: auto;
            left: 2.6rem;
        }

        .ef-field--icon .ef-input:focus ~ .ef-icon,
        .ef-field--icon .ef-select:focus ~ .ef-icon {
            color: var(--e-primary);
        }

        /* Textarea icon (top aligned) */
        .ef-field--textarea.ef-field--icon .ef-icon {
            top: 1.1rem;
            transform: none;
        }

        /* Required indicator */
        .ef-required {
            color: var(--e-danger);
            font-weight: 700;
            margin-inline-start: 2px;
        }

        /* Helper / hint text */
        .ef-hint {
            font-size: 0.78rem;
            color: var(--e-gray-400);
            margin-top: 0.3rem;
            padding-inline-start: 0.25rem;
        }

        /* ===== Modern Checkbox / Toggle ===== */
        .ef-check {
            display: flex;
            align-items: center;
            gap: 0.65rem;
            padding: 0.75rem 1rem;
            border: 1.5px solid var(--e-gray-200);
            border-radius: var(--e-radius);
            background: var(--e-white);
            cursor: pointer;
            transition: all var(--e-transition);
            user-select: none;
        }

        .ef-check:hover {
            border-color: var(--e-primary-200);
            background: var(--e-primary-50);
        }

        .ef-check:has(.ef-check__input:checked) {
            border-color: var(--e-primary);
            background: var(--e-primary-50);
        }

        .ef-check__input {
            width: 20px;
            height: 20px;
            border: 2px solid var(--e-gray-300);
            border-radius: 6px;
            appearance: none;
            -webkit-appearance: none;
            cursor: pointer;
            transition: all var(--e-transition);
            flex-shrink: 0;
            position: relative;
            background: var(--e-white);
        }

        .ef-check__input:checked {
            background: var(--e-primary);
            border-color: var(--e-primary);
        }

        .ef-check__input:checked::after {
            content: '';
            position: absolute;
            top: 3px;
            left: 6px;
            width: 5px;
            height: 9px;
            border: solid white;
            border-width: 0 2px 2px 0;
            transform: rotate(45deg);
        }

        .ef-check__input:focus-visible {
            box-shadow: 0 0 0 3px var(--e-primary-100);
        }

        .ef-check__label {
            font-size: 0.9rem;
            font-weight: 500;
            color: var(--e-gray-700);
            line-height: 1.4;
        }

        /* ===== Date / Time inputs ===== */
        .ef-field .ef-input[type="date"],
        .ef-field .ef-input[type="time"] {
            padding-top: 1.4rem;
            padding-bottom: 0.5rem;
        }

        .ef-field .ef-input[type="date"] ~ .ef-label,
        .ef-field .ef-input[type="time"] ~ .ef-label {
            top: 0.55rem;
            transform: translateY(0) scale(0.78);
            color: var(--e-primary);
            font-weight: 600;
        }

        /* ===== Number input ===== */
        .ef-field .ef-input[type="number"] {
            -moz-appearance: textfield;
        }

        .ef-field .ef-input[type="number"]::-webkit-inner-spin-button,
        .ef-field .ef-input[type="number"]::-webkit-outer-spin-button {
            opacity: 1;
            height: 30px;
        }

        /* ===== File input (non-image) ===== */
        .ef-file-wrap {
            position: relative;
        }

        .ef-file-wrap .ef-file-input {
            width: 100%;
            border: 1.5px dashed var(--e-gray-300);
            border-radius: var(--e-radius);
            padding: 0.75rem 1rem;
            font-size: 0.85rem;
            color: var(--e-gray-600);
            background: var(--e-gray-50);
            cursor: pointer;
            transition: all var(--e-transition);
        }

        .ef-file-wrap .ef-file-input:hover {
            border-color: var(--e-primary);
            background: var(--e-primary-50);
        }

        .ef-file-wrap .ef-file-input:focus {
            border-color: var(--e-primary);
            box-shadow: 0 0 0 3px var(--e-primary-100);
        }

        /* ===== Legacy compat: keep .form-control working in groups ===== */
        .request-form .form-label {
            font-size: 0.88rem;
            font-weight: 600;
            color: var(--e-gray-700);
            margin-bottom: 0.4rem;
        }

        .request-form .form-control,
        .request-form .form-select {
            border: 1.5px solid var(--e-gray-200);
            border-radius: var(--e-radius);
            padding: 0.65rem 1rem;
            font-size: 0.9rem;
            color: var(--e-gray-800);
            background-color: var(--e-white);
            transition: all var(--e-transition);
        }

        .request-form .form-control:focus,
        .request-form .form-select:focus {
            border-color: var(--e-primary);
            box-shadow: 0 0 0 3px var(--e-primary-100);
            background-color: var(--e-white);
        }

        /* ===== Image Upload ===== */
        .upload-zone {
            border: 2px dashed var(--e-gray-300);
            border-radius: var(--e-radius-lg);
            padding: 2rem 1.5rem;
            text-align: center;
            cursor: pointer;
            transition: all var(--e-transition);
            background: var(--e-gray-50);
        }

        .upload-zone:hover {
            border-color: var(--e-primary);
            background: var(--e-primary-50);
        }

        .upload-zone.dragover {
            border-color: var(--e-success);
            background: var(--e-success-light);
        }

        .upload-zone-icon {
            font-size: 2.2rem;
            color: var(--e-primary-light);
            margin-bottom: 0.5rem;
        }

        .upload-zone-text {
            color: var(--e-gray-500);
            font-size: 0.85rem;
            margin-bottom: 0.75rem;
        }

        .upload-zone-btn {
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            background: var(--e-primary);
            color: #fff;
            border: none;
            padding: 0.5rem 1.2rem;
            border-radius: var(--e-radius-full);
            font-size: 0.8rem;
            font-weight: 600;
            cursor: pointer;
            transition: all var(--e-transition);
        }

        .upload-zone-btn:hover {
            background: var(--e-primary-dark);
            transform: translateY(-1px);
            box-shadow: var(--e-shadow-primary);
        }

        .image-preview-container {
            min-height: 0;
        }

        .image-preview-item {
            position: relative;
            border-radius: var(--e-radius);
            overflow: hidden;
            box-shadow: var(--e-shadow-sm);
            transition: all var(--e-transition);
        }

        .image-preview-item:hover {
            transform: translateY(-2px);
            box-shadow: var(--e-shadow-md);
        }

        .image-preview-item img {
            width: 100%;
            height: 110px;
            object-fit: cover;
        }

        .remove-image-btn {
            position: absolute;
            top: 6px;
            right: 6px;
            background: rgba(239, 68, 68, 0.9);
            border: none;
            color: white;
            border-radius: 50%;
            width: 26px;
            height: 26px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all var(--e-transition);
            font-size: 0.7rem;
        }

        .remove-image-btn:hover {
            background: var(--e-danger);
            transform: scale(1.15);
        }

        .add-more-images {
            text-align: center;
            padding: 0.6rem;
            border: 1px dashed var(--e-success);
            border-radius: var(--e-radius);
            background: rgba(16, 185, 129, 0.04);
        }

        /* ===== Repeatable Groups ===== */
        .repeatable-group {
            position: relative;
        }

        .group-instance {
            transition: all var(--e-transition);
            border: 1.5px solid var(--e-gray-200);
            border-radius: var(--e-radius-lg);
            overflow: hidden;
            background: var(--e-white);
        }

        .group-instance:hover {
            border-color: var(--e-gray-300);
            box-shadow: var(--e-shadow-sm);
        }

        .group-instance .card-header {
            background: var(--e-gray-50);
            border-bottom: 1px solid var(--e-gray-200);
            padding: 0.65rem 1rem;
        }

        .group-instance[data-instance="0"] {
            border-color: var(--e-primary-200);
        }

        .group-instance[data-instance="0"] .card-header {
            background: var(--e-primary-50);
            border-bottom-color: var(--e-primary-200);
        }

        .group-title {
            font-weight: 600;
            color: var(--e-gray-700);
            font-size: 0.9rem;
            margin: 0;
        }

        .group-instance[data-instance="0"] .group-title {
            color: var(--e-primary-dark);
        }

        .group-controls {
            display: flex;
            gap: 0.4rem;
            align-items: center;
        }

        .add-group-instance {
            border-radius: var(--e-radius-full);
            padding: 0.25rem 0.7rem;
            font-size: 0.75rem;
            font-weight: 600;
            background: var(--e-success);
            border-color: var(--e-success);
            transition: all var(--e-transition);
        }

        .add-group-instance:hover {
            transform: scale(1.04);
            box-shadow: 0 3px 8px rgba(16, 185, 129, 0.3);
        }

        .remove-group-instance {
            border-radius: var(--e-radius-full);
            padding: 0.25rem 0.7rem;
            font-size: 0.75rem;
            font-weight: 600;
            transition: all var(--e-transition);
        }

        .remove-group-instance:hover {
            transform: scale(1.04);
            box-shadow: 0 3px 8px rgba(239, 68, 68, 0.3);
        }

        .group-instance .card-body {
            padding: 1rem;
        }

        .group-instance .row {
            margin: 0;
            flex-wrap: wrap;
        }

        .group-instance .col-12 {
            padding: 0;
            width: 100%;
            display: flex;
            flex-direction: column;
        }

        .group-instance .col-md-6 {
            padding: 0 10px;
        }

        /* Group instances inherit the ef-field system */
        .group-instance .ef-field {
            margin-bottom: 0;
        }

        .group-instance .ef-field .ef-input,
        .group-instance .ef-field .ef-select,
        .group-instance .ef-field .ef-textarea {
            font-size: 0.88rem;
        }

        .group-instance .ef-field .ef-label {
            font-size: 0.84rem;
        }

        .group-instance .ef-check {
            padding: 0.6rem 0.85rem;
        }

        .group-instance .ef-check__label {
            font-size: 0.84rem;
        }

        .group-instance .col-12 {
            width: 100%;
            flex: 0 0 100%;
            max-width: 100%;
        }

        /* ===== Validation ===== */
        .is-invalid,
        .ef-field .ef-input.is-invalid,
        .ef-field .ef-select.is-invalid,
        .ef-field .ef-textarea.is-invalid {
            border-color: var(--e-danger) !important;
            box-shadow: 0 0 0 3px rgba(239, 68, 68, 0.12) !important;
        }

        .ef-field .ef-input.is-invalid ~ .ef-label,
        .ef-field .ef-select.is-invalid ~ .ef-label,
        .ef-field .ef-textarea.is-invalid ~ .ef-label {
            color: var(--e-danger) !important;
        }

        .ef-field .ef-input.is-invalid ~ .ef-icon,
        .ef-field .ef-select.is-invalid ~ .ef-icon {
            color: var(--e-danger) !important;
        }

        .is-valid,
        .ef-field .ef-input.is-valid,
        .ef-field .ef-select.is-valid,
        .ef-field .ef-textarea.is-valid {
            border-color: var(--e-success) !important;
            box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.12) !important;
        }

        .ef-field .ef-input.is-valid ~ .ef-label,
        .ef-field .ef-select.is-valid ~ .ef-label,
        .ef-field .ef-textarea.is-valid ~ .ef-label {
            color: var(--e-success) !important;
        }

        .ef-field .ef-input.is-valid ~ .ef-icon,
        .ef-field .ef-select.is-valid ~ .ef-icon {
            color: var(--e-success) !important;
        }

        .ef-check:has(.is-invalid) {
            border-color: var(--e-danger) !important;
            background: var(--e-danger-light) !important;
        }

        .ef-check:has(.is-valid) {
            border-color: var(--e-success) !important;
            background: var(--e-success-light) !important;
        }

        .invalid-feedback {
            display: block;
            width: 100%;
            margin-top: 0.25rem;
            font-size: 0.8rem;
            color: var(--e-danger);
        }

        .field-error {
            color: var(--e-danger);
            font-size: 0.78rem;
            margin-top: 0.35rem;
            display: flex;
            align-items: center;
            gap: 0.3rem;
        }

        .field-error::before {
            content: '\f06a';
            font-family: 'Font Awesome 6 Free';
            font-weight: 900;
            font-size: 0.72rem;
        }

        .required-field-marker {
            color: var(--e-danger);
            font-weight: bold;
        }

        /* ===== Submit Button ===== */
        .submit-section {
            padding: 1.5rem 2rem 2rem;
        }

        .btn-submit-request {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.6rem;
            width: 100%;
            padding: 0.9rem 2rem;
            background: linear-gradient(135deg, var(--e-primary) 0%, var(--e-primary-dark) 100%);
            color: #fff;
            border: none;
            border-radius: var(--e-radius-full);
            font-size: 1.05rem;
            font-weight: 700;
            cursor: pointer;
            transition: all var(--e-transition);
            box-shadow: var(--e-shadow-primary);
        }

        .btn-submit-request:hover {
            background: linear-gradient(135deg, var(--e-accent) 0%, var(--e-accent-dark) 100%);
            box-shadow: var(--e-shadow-accent);
            transform: translateY(-2px);
            color: #fff;
        }

        .btn-submit-request:active {
            transform: translateY(0);
        }

        .btn-submit-request.is-loading {
            pointer-events: none;
            opacity: 0.8;
        }

        .btn-submit-request.is-loading i {
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }

        /* ===== Sidebar Info Card ===== */
        .sidebar-info-card {
            background: var(--e-white);
            border-radius: var(--e-radius-xl);
            box-shadow: var(--e-shadow);
            border: 1px solid var(--e-gray-100);
            overflow: hidden;
            position: sticky;
            top: 100px;
        }

        .sidebar-info-header {
            background: linear-gradient(135deg, var(--e-primary) 0%, var(--e-primary-dark) 100%);
            padding: 1.5rem;
            text-align: center;
        }

        .sidebar-info-header img {
            width: 90px;
            height: 90px;
            border-radius: var(--e-radius-lg);
            object-fit: cover;
            border: 3px solid rgba(255, 255, 255, 0.25);
            margin-bottom: 0.75rem;
        }

        .sidebar-info-header h3 {
            color: #fff;
            font-size: 1.15rem;
            font-weight: 700;
            margin-bottom: 0.3rem;
        }

        .sidebar-info-header p {
            color: rgba(255, 255, 255, 0.7);
            font-size: 0.82rem;
            margin-bottom: 0;
            line-height: 1.5;
        }

        .sidebar-info-body {
            padding: 1.25rem;
        }

        .sidebar-subcategory {
            background: var(--e-primary-50);
            border: 1px solid var(--e-primary-100);
            border-radius: var(--e-radius);
            padding: 0.85rem;
            margin-bottom: 1rem;
        }

        .sidebar-subcategory-label {
            font-size: 0.75rem;
            font-weight: 600;
            color: var(--e-gray-500);
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 0.3rem;
        }

        .sidebar-subcategory-name {
            font-size: 0.9rem;
            font-weight: 700;
            color: var(--e-primary-dark);
        }

        .sidebar-subcategory-desc {
            font-size: 0.78rem;
            color: var(--e-gray-500);
            margin-top: 0.25rem;
        }

        .sidebar-back-btn {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            width: 100%;
            padding: 0.6rem;
            border: 1.5px solid var(--e-gray-200);
            border-radius: var(--e-radius);
            color: var(--e-gray-600);
            font-size: 0.85rem;
            font-weight: 600;
            text-decoration: none;
            transition: all var(--e-transition);
        }

        .sidebar-back-btn:hover {
            border-color: var(--e-primary);
            color: var(--e-primary);
            background: var(--e-primary-50);
        }

        /* ===== Validation Alert ===== */
        #validationAlert {
            border-radius: var(--e-radius);
            border: none;
            background: var(--e-danger-light);
            color: var(--e-danger);
            font-size: 0.85rem;
        }

        #validationAlert ul {
            font-size: 0.82rem;
        }

        /* ===== Responsive ===== */
        @media (max-width: 991px) {
            .hero-category-img {
                width: 60px;
                height: 60px;
            }

            .hero-category-info h1 {
                font-size: 1.3rem;
            }

            .form-section {
                padding: 1.25rem 1.25rem;
            }

            .submit-section {
                padding: 1.25rem;
            }
        }

        @media (max-width: 768px) {
            .request-hero {
                padding: 1.5rem 0 2.5rem;
            }

            .hero-category {
                gap: 1rem;
            }

            .image-preview-container .col-md-3 {
                flex: 0 0 50%;
                max-width: 50%;
            }
        }

        @media (max-width: 576px) {
            .image-preview-container .col-md-3 {
                flex: 0 0 100%;
                max-width: 100%;
            }

            .form-section {
                padding: 1rem;
            }
        }
    </style>

    {{-- ===== Hero Header ===== --}}
    <div class="request-hero">
        <div class="container">
            <nav class="hero-breadcrumb" aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">{{ __('messages.home') }}</a></li>
                    <li class="breadcrumb-item"><a
                            href="{{ route('categories.index') }}">{{ __('messages.categories') }}</a></li>
                    <li class="breadcrumb-item"><a
                            href="{{ route('categories.show', $category->slug) }}">{{ $category->name }}</a></li>
                    <li class="breadcrumb-item active" aria-current="page">{{ __('messages.request_service') }}</li>
                </ol>
            </nav>

            <div class="hero-category">
                @if ($category->image)
                    <img src="{{ asset('storage/' . $category->image) }}"
                        alt="{{ app()->getLocale() == 'ar' ? $category->name : $category->name_en }}"
                        class="hero-category-img">
                @endif
                <div class="hero-category-info">
                    <h1>{{ __('messages.request_service_title') }}</h1>
                    <p>{{ app()->getLocale() == 'ar' ? $category->description : $category->description_en }}</p>
                    @if ($selectedSubCategory)
                        <div class="hero-subcategory-badge">
                            <i class="fas fa-layer-group"></i>
                            {{ app()->getLocale() == 'ar' ? $selectedSubCategory->name_ar : $selectedSubCategory->name_en }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- ===== Main Content ===== --}}
    <div class="request-content">
        <div class="container">
            <div class="row g-4 justify-content-center">

                {{-- Form Column --}}
                <div class="col-lg-8 order-1 order-lg-1">
                    <div class="request-form-card">
                        <form action="{{ route('services.store') }}" method="POST" enctype="multipart/form-data"
                            id="serviceRequestForm" class="request-form"
                            onsubmit="return validateServiceForm(event);">
                            @csrf
                            <input type="hidden" name="category_id" value="{{ $category->id }}">
                            <input type="hidden" name="user_id" value="{{ auth()->id() }}">

                            @if ($selectedSubCategoryId)
                                <input type="hidden" name="sub_category_id" value="{{ $selectedSubCategoryId }}">
                            @endif

                            {{-- Error Messages --}}
                            @if ($errors->any())
                                <div class="form-section" style="border-bottom: none; padding-bottom: 0;">
                                    <div class="alert alert-danger" style="border-radius: var(--e-radius);">
                                        <ul class="mb-0">
                                            @foreach ($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                            @endif

                            @if ($hasSubCategories && !$selectedSubCategoryId)
                                <div class="form-section">
                                    <div class="alert alert-warning" style="border-radius: var(--e-radius);">
                                        <i class="fas fa-exclamation-triangle me-1"></i>
                                        <strong>{{ __('messages.warning_choose_subcategory') }}</strong>
                                        <br><small>{{ __('messages.choose_subcategory_note') }}</small>
                                        <br>
                                        <a href="{{ route('categories.show', $category->slug) }}"
                                            class="btn btn-sm btn-outline-primary mt-2">
                                            <i class="fas fa-arrow-left"></i> {{ __('messages.back_to_categories') }}
                                        </a>
                                    </div>
                                </div>
                            @endif

                            {{-- Voice Note Section --}}
                            @if ($category->voice_note_enabled)
                                <div class="form-section">
                                    <div class="form-section-title">
                                        <i class="fas fa-microphone"></i>
                                        {{ __('messages.voice_note') }}
                                    </div>
                                    @include('partials.voice-note-recorder')
                                </div>
                            @endif

                            {{-- Notes Section --}}
                            <div class="form-section">
                                <div class="form-section-title">
                                    <i class="fas fa-sticky-note"></i>
                                    {{ __('messages.additional_notes') }}
                                </div>
                                <div class="ef-field ef-field--textarea ef-field--icon">
                                    <i class="fas fa-pen ef-icon"></i>
                                    <textarea name="notes" id="notes" class="ef-textarea" rows="4"
                                        placeholder=" " aria-label="{{ __('messages.additional_notes') }}">{{ old('notes') }}</textarea>
                                    <label for="notes" class="ef-label">{{ __('messages.additional_notes_placeholder') }}</label>
                                </div>
                            </div>

                            {{-- Image Upload Section --}}
                            @php
                                $imageFields = $category->fields->where('type', 'image');
                            @endphp

                            @if ($imageFields->count())
                                <div class="form-section">
                                    <div class="form-section-title">
                                        <i class="fas fa-images"></i>
                                        {{ __('messages.upload_images') }}
                                    </div>
                                    <div class="row">
                                        @foreach ($imageFields as $field)
                                            @php
                                                $fieldName =
                                                    app()->getLocale() == 'ar' ? $field->name_ar : $field->name_en;
                                            @endphp
                                            <div class="col-12 mb-3">
                                                <label for="custom_fields_{{ $field->name }}_0"
                                                    class="form-label">
                                                    {{ $fieldName }}
                                                    @if ($field->is_required)
                                                        <span class="required-star">*</span>
                                                    @endif
                                                </label>

                                                <div class="image-upload-container"
                                                    data-field-name="{{ $field->name }}">
                                                    <div class="upload-zone upload-area"
                                                        style="min-height: 120px;">
                                                        <div class="upload-content">
                                                            <div class="upload-zone-icon">
                                                                <i class="fas fa-cloud-upload-alt"></i>
                                                            </div>
                                                            <p class="upload-zone-text">
                                                                {{ __('messages.image_upload_drag_drop') }}
                                                            </p>
                                                            <p class="small text-muted mb-2">
                                                                {{ __('messages.image_upload_multiple_note') }}
                                                            </p>

                                                            <input type="file"
                                                                name="custom_fields[{{ $field->name }}][0][]"
                                                                id="custom_fields_{{ $field->name }}_0"
                                                                class="form-control d-none image-file-input"
                                                                accept="image/*" multiple
                                                                data-field-name="{{ $field->name }}"
                                                                {{ $field->is_required ? 'required' : '' }}>

                                                            <button type="button" class="upload-zone-btn"
                                                                onclick="document.getElementById('custom_fields_{{ $field->name }}_0').click()">
                                                                <i class="fas fa-plus"></i>
                                                                {{ __('messages.choose_images') }}
                                                            </button>
                                                        </div>
                                                    </div>

                                                    <div class="image-preview-container row mt-2"
                                                        id="preview_{{ $field->name }}_0"></div>

                                                    <div class="add-more-images mt-2"
                                                        id="add_more_{{ $field->name }}_0" style="display: none;">
                                                        <button type="button"
                                                            class="btn btn-outline-success btn-sm"
                                                            onclick="showUploadArea('{{ $field->name }}')">
                                                            <i class="fas fa-plus"></i>
                                                            {{ __('messages.add_more_images') }}
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif

                            {{-- City Selection --}}
                            <div class="form-section">
                                <div class="form-section-title">
                                    <i class="fas fa-map-marker-alt"></i>
                                    {{ __('messages.choose_city') }}
                                    @if ($cities->count() > 0)
                                        <span class="text-muted fw-normal"
                                            style="font-size: 0.78rem;">({{ $cities->count() }}
                                            {{ __('messages.available_cities_count') }})</span>
                                    @endif
                                </div>
                                @if ($cities->count() > 0)
                                    <div class="ef-field ef-field--icon">
                                        <i class="fas fa-map-marker-alt ef-icon"></i>
                                        <select name="city_id" id="city_id" class="ef-select" required
                                            aria-label="{{ __('messages.choose_city') }}"
                                            data-empty="{{ old('city_id') ? 'false' : 'true' }}"
                                            onchange="this.dataset.empty = this.value ? 'false' : 'true'">
                                            <option value="">--</option>
                                            @foreach ($cities as $city)
                                                <option value="{{ $city->id }}"
                                                    {{ old('city_id') == $city->id ? 'selected' : '' }}>
                                                    {{ $city->name_ar ?? ($city->name_en ?? 'اسم غير محدد') }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <label for="city_id" class="ef-label">{{ __('messages.choose_city') }} <span class="ef-required">*</span></label>
                                    </div>
                                @else
                                    <div class="alert alert-warning" style="border-radius: var(--e-radius);">
                                        <i class="fas fa-exclamation-triangle"></i>
                                        {{ __('messages.no_cities_available') }}
                                    </div>
                                    <input type="hidden" name="city_id" value="">
                                @endif
                            </div>

                            {{-- Custom Fields --}}
                            @php
                                $fieldsWithoutImages = $category->fields->where('type', '!=', 'image');
                                $groupedFields = $fieldsWithoutImages->groupBy('input_group');
                            @endphp

                            @if ($fieldsWithoutImages->count())
                                <div class="form-section">
                                    <div class="form-section-title">
                                        <i class="fas fa-list-alt"></i>
                                        {{ __('messages.service_details') }}
                                    </div>

                                    @foreach ($groupedFields as $group => $fields)
                                        @php
                                            $isRepeatable = $fields->first()->is_repeatable ?? false;
                                            $groupId = $group
                                                ? 'group_' . str_replace([' ', '-'], '_', $group)
                                                : 'ungrouped';
                                            $groupName =
                                                app()->getLocale() == 'ar'
                                                    ? $group
                                                    : $fields->first()->input_group_en ?? $group;
                                        @endphp

                                        @if ($group)
                                            <div class="repeatable-group mb-3" data-group-id="{{ $groupId }}"
                                                data-repeatable="{{ $isRepeatable ? 'true' : 'false' }}">
                                                <div class="card mb-3 group-instance" data-instance="0">
                                                    <div
                                                        class="card-header d-flex justify-content-between align-items-center">
                                                        <h5 class="mb-0 group-title">{{ $groupName }}</h5>
                                                        @if ($isRepeatable)
                                                            <div class="group-controls">
                                                                <button type="button"
                                                                    class="btn btn-sm btn-success add-group-instance"
                                                                    data-group-id="{{ $groupId }}">
                                                                    <i class="fas fa-plus"></i>
                                                                    {{ __('messages.add_group') }}
                                                                </button>
                                                                <button type="button"
                                                                    class="btn btn-sm btn-danger remove-group-instance d-none"
                                                                    data-group-id="{{ $groupId }}">
                                                                    <i class="fas fa-trash"></i>
                                                                    {{ __('messages.remove_group') }}
                                                                </button>
                                                            </div>
                                                        @endif
                                                    </div>
                                                    <div class="card-body">
                                                        <div class="row">
                                        @endif

                                        @foreach ($fields as $field)
                                            @php
                                                $fieldName =
                                                    app()->getLocale() == 'ar'
                                                        ? $field->name_ar
                                                        : $field->name_en;
                                                $fieldValue =
                                                    app()->getLocale() == 'ar'
                                                        ? $field->value ?? $field->name_ar
                                                        : $field->value_en ?? $field->name_en;
                                                $fieldIcon = $field->type !== 'title' ? $field->getTypeIconAttribute() : 'heading';
                                                $oldVal = old('custom_fields.' . $field->name . '.0');
                                            @endphp

                                            @if ($field->type === 'title')
                                                <div class="col-12 mb-3">
                                                    <div style="display:flex; align-items:center; gap:0.5rem; padding-bottom:0.5rem; border-bottom:2px solid var(--e-gray-100);">
                                                        <span style="width:28px;height:28px;display:inline-flex;align-items:center;justify-content:center;background:var(--e-primary-50);border-radius:var(--e-radius-sm);color:var(--e-primary);font-size:0.8rem;">
                                                            <i class="fas fa-heading"></i>
                                                        </span>
                                                        <span style="font-size:0.95rem;font-weight:700;color:var(--e-gray-800);">{{ $fieldValue }}</span>
                                                    </div>
                                                </div>

                                            @elseif($field->type === 'select' && is_array($field->options))
                                                <div class="col-12 mb-3">
                                                    <div class="ef-field ef-field--icon">
                                                        <i class="fas fa-{{ $fieldIcon }} ef-icon"></i>
                                                        <select name="custom_fields[{{ $field->name }}][0]"
                                                            id="custom_fields_{{ $field->name }}_0"
                                                            class="ef-select"
                                                            aria-label="{{ $fieldName }}"
                                                            data-empty="{{ $oldVal ? 'false' : 'true' }}"
                                                            onchange="this.dataset.empty = this.value ? 'false' : 'true'"
                                                            {{ $field->is_required ? 'required' : '' }}>
                                                            <option value="">--</option>
                                                            @foreach ($field->options as $option)
                                                                <option value="{{ $option }}"
                                                                    {{ $oldVal == $option ? 'selected' : '' }}>
                                                                    {{ $option }}</option>
                                                            @endforeach
                                                        </select>
                                                        <label for="custom_fields_{{ $field->name }}_0" class="ef-label">
                                                            {{ $fieldName }}
                                                            @if ($field->is_required) <span class="ef-required">*</span> @endif
                                                        </label>
                                                    </div>
                                                </div>

                                            @elseif($field->type === 'checkbox')
                                                <div class="col-12 mb-3">
                                                    <label class="ef-check" for="custom_fields_{{ $field->name }}_0">
                                                        <input type="checkbox"
                                                            name="custom_fields[{{ $field->name }}][0]"
                                                            id="custom_fields_{{ $field->name }}_0"
                                                            value="1" class="ef-check__input"
                                                            {{ $oldVal == '1' ? 'checked' : '' }}>
                                                        <span class="ef-check__label">{{ $fieldName }}</span>
                                                    </label>
                                                </div>

                                            @elseif($field->type === 'textarea')
                                                <div class="col-12 mb-3">
                                                    <div class="ef-field ef-field--textarea ef-field--icon">
                                                        <i class="fas fa-{{ $fieldIcon }} ef-icon"></i>
                                                        <textarea name="custom_fields[{{ $field->name }}][0]"
                                                            id="custom_fields_{{ $field->name }}_0"
                                                            class="ef-textarea" rows="3" placeholder=" "
                                                            aria-label="{{ $fieldName }}"
                                                            {{ $field->is_required ? 'required' : '' }}>{{ $oldVal }}</textarea>
                                                        <label for="custom_fields_{{ $field->name }}_0" class="ef-label">
                                                            {{ $fieldName }}
                                                            @if ($field->is_required) <span class="ef-required">*</span> @endif
                                                        </label>
                                                    </div>
                                                </div>

                                            @elseif($field->type === 'date')
                                                <div class="col-12 mb-3">
                                                    <div class="ef-field ef-field--icon">
                                                        <i class="fas fa-calendar-alt ef-icon"></i>
                                                        <input type="date"
                                                            name="custom_fields[{{ $field->name }}][0]"
                                                            id="custom_fields_{{ $field->name }}_0"
                                                            class="ef-input" placeholder=" "
                                                            aria-label="{{ $fieldName }}"
                                                            value="{{ $oldVal }}"
                                                            {{ $field->is_required ? 'required' : '' }}>
                                                        <label for="custom_fields_{{ $field->name }}_0" class="ef-label">
                                                            {{ $fieldName }}
                                                            @if ($field->is_required) <span class="ef-required">*</span> @endif
                                                        </label>
                                                    </div>
                                                </div>

                                            @elseif($field->type === 'time')
                                                <div class="col-12 mb-3">
                                                    <div class="ef-field ef-field--icon">
                                                        <i class="fas fa-clock ef-icon"></i>
                                                        <input type="time"
                                                            name="custom_fields[{{ $field->name }}][0]"
                                                            id="custom_fields_{{ $field->name }}_0"
                                                            class="ef-input" placeholder=" "
                                                            aria-label="{{ $fieldName }}"
                                                            value="{{ $oldVal }}"
                                                            {{ $field->is_required ? 'required' : '' }}>
                                                        <label for="custom_fields_{{ $field->name }}_0" class="ef-label">
                                                            {{ $fieldName }}
                                                            @if ($field->is_required) <span class="ef-required">*</span> @endif
                                                        </label>
                                                    </div>
                                                </div>

                                            @elseif($field->type === 'file')
                                                <div class="col-12 mb-3">
                                                    <div class="ef-file-wrap">
                                                        <label for="custom_fields_{{ $field->name }}_0" class="form-label" style="font-size:0.85rem;font-weight:600;color:var(--e-gray-700);">
                                                            <i class="fas fa-{{ $fieldIcon }} me-1" style="color:var(--e-gray-400);"></i>
                                                            {{ $fieldName }}
                                                            @if ($field->is_required) <span class="ef-required">*</span> @endif
                                                        </label>
                                                        <input type="file"
                                                            name="custom_fields[{{ $field->name }}][0]"
                                                            id="custom_fields_{{ $field->name }}_0"
                                                            class="ef-file-input"
                                                            aria-label="{{ $fieldName }}"
                                                            {{ $field->is_required ? 'required' : '' }}>
                                                    </div>
                                                </div>

                                            @elseif($field->type !== 'image')
                                                <div class="col-12 mb-3">
                                                    <div class="ef-field ef-field--icon">
                                                        <i class="fas fa-{{ $fieldIcon }} ef-icon"></i>
                                                        <input type="{{ $field->type }}"
                                                            name="custom_fields[{{ $field->name }}][0]"
                                                            id="custom_fields_{{ $field->name }}_0"
                                                            class="ef-input" placeholder=" "
                                                            aria-label="{{ $fieldName }}"
                                                            value="{{ $oldVal }}"
                                                            {{ $field->is_required ? 'required' : '' }}>
                                                        <label for="custom_fields_{{ $field->name }}_0" class="ef-label">
                                                            {{ $fieldName }}
                                                            @if ($field->is_required) <span class="ef-required">*</span> @endif
                                                        </label>
                                                    </div>
                                                </div>
                                            @endif
                                        @endforeach

                                        @if ($group)
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                    @endforeach
                                </div>
                            @endif

                            {{-- Submit --}}
                            <div class="submit-section">
                                <button type="submit" class="btn-submit-request" id="submitBtn">
                                    <i class="fas fa-paper-plane" id="submitIcon"></i>
                                    <span id="submitText">{{ __('messages.submit_request') }}</span>
                                </button>

                                <div id="validationAlert" class="alert alert-danger mt-3" style="display: none;">
                                    <i class="fas fa-exclamation-triangle me-2"></i>
                                    <strong id="validationMessage"></strong>
                                    <ul id="validationErrors" class="mb-0 mt-2"></ul>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                {{-- Sidebar --}}
                <div class="col-lg-4 order-2 order-lg-2">
                    <div class="sidebar-info-card">
                        <div class="sidebar-info-header">
                            @if ($category->image)
                                <img src="{{ asset('storage/' . $category->image) }}"
                                    alt="{{ app()->getLocale() == 'ar' ? $category->name : $category->name_en }}">
                            @endif
                            <h3>{{ app()->getLocale() == 'ar' ? $category->name : $category->name_en }}</h3>
                            <p>{{ app()->getLocale() == 'ar' ? $category->description : $category->description_en }}
                            </p>
                        </div>
                        <div class="sidebar-info-body">
                            @if ($selectedSubCategory)
                                <div class="sidebar-subcategory">
                                    <div class="sidebar-subcategory-label">
                                        {{ __('messages.selected_subcategory_label') }}</div>
                                    <div class="sidebar-subcategory-name">
                                        {{ app()->getLocale() == 'ar' ? $selectedSubCategory->name_ar : $selectedSubCategory->name_en }}
                                    </div>
                                    @if ($selectedSubCategory->description_ar || $selectedSubCategory->description_en)
                                        <div class="sidebar-subcategory-desc">
                                            {{ app()->getLocale() == 'ar' ? $selectedSubCategory->description_ar : $selectedSubCategory->description_en }}
                                        </div>
                                    @endif
                                </div>
                            @endif

                            <a href="{{ route('categories.show', $category->slug) }}" class="sidebar-back-btn">
                                <i class="fas fa-arrow-left"></i>
                                {{ __('messages.back_to_categories') }}
                            </a>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    {{-- Repeatable Group Template --}}
    <template id="group-template">
        <div class="card mb-3 group-instance" data-instance="">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0 group-title"></h5>
                <div class="group-controls">
                    <button type="button" class="btn btn-sm btn-success add-group-instance">
                        <i class="fas fa-plus"></i> {{ __('messages.add_group') }}
                    </button>
                    <button type="button" class="btn btn-sm btn-danger remove-group-instance">
                        <i class="fas fa-trash"></i> {{ __('messages.del_group') }}
                    </button>
                </div>
            </div>
            <div class="card-body"></div>
        </div>
    </template>

@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Add group instance
            document.addEventListener('click', function(e) {
                if (e.target.classList.contains('add-group-instance') || e.target.closest(
                        '.add-group-instance')) {
                    const button = e.target.classList.contains('add-group-instance') ? e.target : e.target
                        .closest('.add-group-instance');
                    const groupId = button.dataset.groupId;
                    addGroupInstance(groupId);
                }
            });

            // Remove group instance
            document.addEventListener('click', function(e) {
                if (e.target.classList.contains('remove-group-instance') || e.target.closest(
                        '.remove-group-instance')) {
                    const button = e.target.classList.contains('remove-group-instance') ? e.target : e
                        .target.closest('.remove-group-instance');
                    const groupId = button.dataset.groupId;
                    removeGroupInstance(groupId);
                }
            });

            function addGroupInstance(groupId) {
                const groupContainer = document.querySelector(`[data-group-id="${groupId}"]`);
                const existingInstances = groupContainer.querySelectorAll('.group-instance');
                const newInstanceIndex = existingInstances.length;

                const firstInstance = existingInstances[0];
                const newInstance = firstInstance.cloneNode(true);

                newInstance.dataset.instance = newInstanceIndex;

                const fields = newInstance.querySelectorAll('input, select, textarea');
                fields.forEach(field => {
                    const oldName = field.name;
                    const oldId = field.id;

                    if (oldName) {
                        const nameMatch = oldName.match(/custom_fields\[([^\]]+)\]\[(\d+)\]/);
                        if (nameMatch) {
                            const fieldName = nameMatch[1];
                            field.name = `custom_fields[${fieldName}][${newInstanceIndex}]`;
                        }
                    }

                    if (oldId) {
                        const idMatch = oldId.match(/custom_fields_([^_]+)_(\d+)/);
                        if (idMatch) {
                            const fieldName = idMatch[1];
                            field.id = `custom_fields_${fieldName}_${newInstanceIndex}`;
                        }
                    }

                    if (field.type === 'checkbox') {
                        field.checked = false;
                    } else if (field.type === 'file') {
                        field.value = '';
                    } else {
                        field.value = '';
                    }
                });

                const labels = newInstance.querySelectorAll('label');
                labels.forEach(label => {
                    const forAttr = label.getAttribute('for');
                    if (forAttr) {
                        const idMatch = forAttr.match(/custom_fields_([^_]+)_(\d+)/);
                        if (idMatch) {
                            const fieldName = idMatch[1];
                            label.setAttribute('for',
                                `custom_fields_${fieldName}_${newInstanceIndex}`);
                        }
                    }
                });

                const removeButtons = groupContainer.querySelectorAll('.remove-group-instance');
                removeButtons.forEach(btn => btn.classList.remove('d-none'));

                groupContainer.appendChild(newInstance);

                const groupTitle = newInstance.querySelector('.group-title');
                if (groupTitle) {
                    groupTitle.textContent = groupTitle.textContent + ` (${newInstanceIndex + 1})`;
                }

                newInstance.style.opacity = '0';
                newInstance.style.transform = 'translateY(-20px)';
                setTimeout(() => {
                    newInstance.style.transition = 'all 0.3s ease';
                    newInstance.style.opacity = '1';
                    newInstance.style.transform = 'translateY(0)';
                }, 10);
            }

            function removeGroupInstance(groupId) {
                const groupContainer = document.querySelector(`[data-group-id="${groupId}"]`);
                const instances = groupContainer.querySelectorAll('.group-instance');

                if (instances.length > 1) {
                    const lastInstance = instances[instances.length - 1];
                    lastInstance.remove();

                    const remainingInstances = groupContainer.querySelectorAll('.group-instance');
                    if (remainingInstances.length === 1) {
                        const removeButtons = groupContainer.querySelectorAll('.remove-group-instance');
                        removeButtons.forEach(btn => btn.classList.add('d-none'));
                    }

                    remainingInstances.forEach((instance, index) => {
                        instance.dataset.instance = index;

                        const fields = instance.querySelectorAll('input, select, textarea');
                        fields.forEach(field => {
                            const oldName = field.name;
                            const oldId = field.id;

                            if (oldName) {
                                const nameMatch = oldName.match(
                                    /custom_fields\[([^\]]+)\]\[(\d+)\]/);
                                if (nameMatch) {
                                    const fieldName = nameMatch[1];
                                    field.name =
                                        `custom_fields[${fieldName}][${index}]`;
                                }
                            }

                            if (oldId) {
                                const idMatch = oldId.match(
                                    /custom_fields_([^_]+)_(\d+)/);
                                if (idMatch) {
                                    const fieldName = idMatch[1];
                                    field.id =
                                        `custom_fields_${fieldName}_${index}`;
                                }
                            }
                        });

                        const labels = instance.querySelectorAll('label');
                        labels.forEach(label => {
                            const forAttr = label.getAttribute('for');
                            if (forAttr) {
                                const idMatch = forAttr.match(
                                    /custom_fields_([^_]+)_(\d+)/);
                                if (idMatch) {
                                    const fieldName = idMatch[1];
                                    label.setAttribute('for',
                                        `custom_fields_${fieldName}_${index}`);
                                }
                            }
                        });

                        const groupTitle = instance.querySelector('.group-title');
                        if (groupTitle && index > 0) {
                            const baseTitle = groupTitle.textContent.replace(/ \(\d+\)$/, '');
                            groupTitle.textContent = baseTitle + ` (${index + 1})`;
                        } else if (groupTitle && index === 0) {
                            const baseTitle = groupTitle.textContent.replace(/ \(\d+\)$/, '');
                            groupTitle.textContent = baseTitle;
                        }
                    });
                }
            }

            // Image Upload Handler
            window.handleImageUpload = function(input) {
                const files = Array.from(input.files);
                let fieldName = null;
                let previewContainer = null;

                const container = input.closest('.image-upload-container');
                if (container) {
                    fieldName = container.dataset.fieldName || container.getAttribute('data-field-name');
                    if (fieldName) {
                        previewContainer = document.getElementById('preview_' + fieldName + '_0');
                    }
                }

                if (!fieldName && input.name) {
                    const nameMatch = input.name.match(/custom_fields\[([^\]]+)\]/);
                    if (nameMatch && nameMatch[1]) {
                        fieldName = nameMatch[1];
                        previewContainer = document.getElementById('preview_' + fieldName + '_0');
                    }
                }

                if (!fieldName && input.id) {
                    const idMatch = input.id.match(/custom_fields_([^_]+)_/);
                    if (idMatch && idMatch[1]) {
                        fieldName = idMatch[1];
                        previewContainer = document.getElementById('preview_' + fieldName + '_0');
                    }
                }

                if (!previewContainer || files.length === 0) return;

                files.forEach(function(file) {
                    if (file.type.startsWith('image/')) {
                        const reader = new FileReader();
                        reader.onload = function(e) {
                            addImagePreview(e.target.result, file.name, fieldName,
                                previewContainer);
                        };
                        reader.readAsDataURL(file);
                    }
                });
            };

            function addImagePreview(imageSrc, fileName, fieldName, container) {
                if (!container) return;

                try {
                    const col = document.createElement('div');
                    col.className = 'col-md-3 mb-2';

                    col.innerHTML = `
                        <div class="image-preview-item">
                            <img src="${imageSrc}" alt="${fileName}" class="img-fluid">
                            <button type="button" class="remove-image-btn" data-field-name="${fieldName}">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    `;

                    container.appendChild(col);

                    const removeBtn = col.querySelector('.remove-image-btn');
                    if (removeBtn) {
                        removeBtn.addEventListener('click', function(e) {
                            e.preventDefault();
                            removeImagePreview(this, fieldName);
                        });
                    }

                    const uploadArea = container.closest('.image-upload-container').querySelector(
                        '.upload-area');
                    const addMoreBtn = document.getElementById('add_more_' + fieldName + '_0');

                    if (uploadArea) uploadArea.style.display = 'none';
                    if (addMoreBtn) addMoreBtn.style.display = 'block';

                } catch (error) {
                    console.error('Error adding image preview:', error);
                }
            }

            function removeImagePreview(button, fieldName) {
                const previewItem = button.closest('.col-md-3');
                if (previewItem) previewItem.remove();

                const container = document.getElementById('preview_' + fieldName + '_0');
                if (container) {
                    if (container.children.length === 0) {
                        const uploadArea = container.closest('.image-upload-container').querySelector(
                            '.upload-area');
                        const addMoreBtn = document.getElementById('add_more_' + fieldName + '_0');
                        if (uploadArea) uploadArea.style.display = 'block';
                        if (addMoreBtn) addMoreBtn.style.display = 'none';
                    }
                }
            }

            // Setup image upload listeners (single binding, flag-guarded)
            window.bindImageInputs = function() {
                document.querySelectorAll('input[type="file"]').forEach(function(input) {
                    if (input.dataset.vnBound) return; // already bound
                    if (input.accept && (input.accept.includes('image') || input.accept === 'image/*')) {
                        input.dataset.vnBound = '1';
                        input.addEventListener('change', function(e) {
                            handleImageUpload(e.target);
                        });
                    }
                });
            };

            // Drag and drop
            window.bindDragDrop = function() {
                document.querySelectorAll('.upload-area, .upload-zone').forEach(function(area) {
                    if (area.dataset.vnBound) return;
                    area.dataset.vnBound = '1';

                    area.addEventListener('dragover', function(e) {
                        e.preventDefault();
                        this.classList.add('dragover');
                    });

                    area.addEventListener('dragleave', function(e) {
                        e.preventDefault();
                        this.classList.remove('dragover');
                    });

                    area.addEventListener('drop', function(e) {
                        e.preventDefault();
                        this.classList.remove('dragover');
                        const files = e.dataTransfer.files;
                        const input = this.querySelector('input[type="file"]');
                        if (input) {
                            input.files = files;
                            handleImageUpload(input);
                        }
                    });

                    area.addEventListener('click', function(e) {
                        if (e.target === this || e.target.closest('.upload-content')) {
                            const input = this.querySelector('input[type="file"]');
                            if (input) input.click();
                        }
                    });
                });
            };

            bindImageInputs();
            bindDragDrop();

            function showUploadArea(fieldName) {
                const container = document.querySelector(`[data-field-name="${fieldName}"]`);
                if (!container) return;

                const uploadArea = container.querySelector('.upload-area, .upload-zone');
                const addMoreBtn = document.getElementById('add_more_' + fieldName + '_0');
                const fileInput = container.querySelector('input[type="file"]');

                if (uploadArea) uploadArea.style.display = 'block';
                if (addMoreBtn) addMoreBtn.style.display = 'none';
                if (fileInput) fileInput.value = '';
            }

            // Make showUploadArea globally accessible
            window.showUploadArea = showUploadArea;
        });

        // Validation
        @php
            $requiredFieldsData = [];
            foreach ($category->fields->where('is_active', true)->where('is_required', true) as $field) {
                $requiredFieldsData[] = [
                    'name' => $field->name,
                    'type' => $field->type,
                    'name_ar' => $field->name_ar,
                    'name_en' => $field->name_en,
                    'is_repeatable' => $field->is_repeatable ?? false,
                    'input_group' => $field->input_group,
                ];
            }
        @endphp

        const requiredCustomFields = @json($requiredFieldsData);

        window.validateServiceForm = function(e) {
            if (e) {
                e.preventDefault();
                e.stopPropagation();
            }

            const form = document.getElementById('serviceRequestForm');
            if (!form) return false;

            if (typeof clearValidation === 'function') clearValidation();

            if (typeof validateForm === 'function') {
                const errors = validateForm();
                if (errors.length > 0) {
                    if (typeof showValidationErrors === 'function') showValidationErrors(errors);
                    const firstErrorField = document.querySelector('.is-invalid');
                    if (firstErrorField) {
                        firstErrorField.scrollIntoView({ behavior: 'smooth', block: 'center' });
                        firstErrorField.focus();
                    }
                    return false;
                }
            }

            // Show loading state
            const btn = document.getElementById('submitBtn');
            if (btn) {
                btn.classList.add('is-loading');
                const icon = btn.querySelector('#submitIcon');
                const text = btn.querySelector('#submitText');
                if (icon) icon.className = 'fas fa-spinner';
                if (text) text.textContent = '{{ __("messages.sending") ?? "جاري الإرسال..." }}';
            }
            form.onsubmit = null;
            form.submit();
            return false;
        };

        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('serviceRequestForm');
            const validationAlert = document.getElementById('validationAlert');
            const validationMessage = document.getElementById('validationMessage');
            const validationErrors = document.getElementById('validationErrors');

            const validationMessages = {
                ar: {
                    requiredField: 'هذا الحقل مطلوب',
                    completeRequiredFields: 'يرجى إكمال جميع الحقول المطلوبة قبل الإرسال',
                    imageRequired: 'هذا الحقل مطلوب (يجب رفع صورة واحدة على الأقل)',
                    fieldRequired: 'هذا الحقل مطلوب',
                    error: 'خطأ'
                },
                en: {
                    requiredField: 'This field is required',
                    completeRequiredFields: 'Please complete all required fields before submitting',
                    imageRequired: 'This field is required (at least one image must be uploaded)',
                    fieldRequired: 'This field is required',
                    error: 'Error'
                }
            };

            const currentLang = '{{ app()->getLocale() }}';
            const messages = validationMessages[currentLang] || validationMessages.ar;

            if (!form || !validationAlert || !validationMessage || !validationErrors) return;

            window.validateServiceForm = function(e) {
                if (e) {
                    e.preventDefault();
                    e.stopPropagation();
                }

                clearValidation();
                const errors = validateForm();

                if (errors.length > 0) {
                    showValidationErrors(errors);
                    const firstErrorField = document.querySelector('.is-invalid');
                    if (firstErrorField) {
                        firstErrorField.scrollIntoView({ behavior: 'smooth', block: 'center' });
                        firstErrorField.focus();
                    }
                    return false;
                } else {
                    // Show loading state
                    const btn = document.getElementById('submitBtn');
                    if (btn) {
                        btn.classList.add('is-loading');
                        const icon = btn.querySelector('#submitIcon');
                        const text = btn.querySelector('#submitText');
                        if (icon) icon.className = 'fas fa-spinner';
                        if (text) text.textContent = '{{ __("messages.sending") ?? "جاري الإرسال..." }}';
                    }
                    form.onsubmit = null;
                    form.submit();
                    return false;
                }
            };

            form.addEventListener('submit', function(e) {
                return window.validateServiceForm(e);
            });

            form.addEventListener('input', function(e) {
                if (e.target.hasAttribute('required') || e.target.closest('[data-required]')) {
                    validateField(e.target);
                }
            });

            form.addEventListener('change', function(e) {
                if (e.target.hasAttribute('required') || e.target.closest('[data-required]')) {
                    validateField(e.target);
                }
            });

            function validateForm() {
                const errors = [];
                const formElement = document.getElementById('serviceRequestForm');
                if (!formElement) return errors;

                const citySelect = formElement.querySelector('#city_id');
                if (citySelect && citySelect.hasAttribute('required')) {
                    if (!citySelect.value || citySelect.value === '') {
                        const cityLabel = '{{ __('messages.choose_city') }}';
                        errors.push({ field: citySelect, message: cityLabel + ' - ' + messages.requiredField });
                    }
                }

                const requiredInputs = formElement.querySelectorAll(
                    'input[required]:not([type="file"]):not([type="hidden"]):not([type="checkbox"])');
                requiredInputs.forEach(input => {
                    if (!input.value || input.value.trim() === '') {
                        errors.push({ field: input, message: getFieldLabel(input) + ' - ' + messages.requiredField });
                    }
                });

                const requiredSelects = formElement.querySelectorAll('select[required]');
                requiredSelects.forEach(select => {
                    if (!select.value || select.value === '' || select.value === null || select.value === '0') {
                        errors.push({ field: select, message: getFieldLabel(select) + ' - ' + messages.requiredField });
                    }
                });

                const requiredTextareas = formElement.querySelectorAll('textarea[required]');
                requiredTextareas.forEach(textarea => {
                    if (!textarea.value || textarea.value.trim() === '') {
                        errors.push({ field: textarea, message: getFieldLabel(textarea) + ' - ' + messages.requiredField });
                    }
                });

                const requiredFileInputs = formElement.querySelectorAll('input[type="file"][required]');
                requiredFileInputs.forEach(fileInput => {
                    const nameAttr = fileInput.getAttribute('name');
                    if (!nameAttr) return;

                    const nameMatch = nameAttr.match(/custom_fields\[([^\]]+)\]\[(\d+)\]/);
                    if (nameMatch) {
                        const fieldName = nameMatch[1];
                        const instanceNum = nameMatch[2];
                        const hasFiles = fileInput.files && fileInput.files.length > 0;
                        const previewContainer = document.getElementById('preview_' + fieldName + '_' + instanceNum);
                        const hasPreviews = previewContainer && previewContainer.children.length > 0;

                        if (!hasFiles && !hasPreviews) {
                            errors.push({ field: fileInput, message: getFieldLabel(fileInput) + ' - ' + messages.imageRequired });
                        }
                    } else {
                        if (!(fileInput.files && fileInput.files.length > 0)) {
                            errors.push({ field: fileInput, message: getFieldLabel(fileInput) + ' - ' + messages.imageRequired });
                        }
                    }
                });

                const requiredCheckboxes = formElement.querySelectorAll('input[type="checkbox"][required]');
                requiredCheckboxes.forEach(checkbox => {
                    if (!checkbox.checked) {
                        errors.push({ field: checkbox, message: getFieldLabel(checkbox) + ' - ' + messages.requiredField });
                    }
                });

                if (typeof requiredCustomFields !== 'undefined' && requiredCustomFields.length > 0) {
                    const currentLangForValidation = '{{ app()->getLocale() }}';

                    requiredCustomFields.forEach(fieldData => {
                        const fieldName = fieldData.name;
                        const fieldType = fieldData.type;
                        const fieldLabel = currentLangForValidation === 'ar' ? fieldData.name_ar : fieldData.name_en;

                        const fieldInputs = formElement.querySelectorAll(
                            `input[name*="custom_fields[${fieldName}]"],
                             select[name*="custom_fields[${fieldName}]"],
                             textarea[name*="custom_fields[${fieldName}]"]`
                        );

                        if (fieldInputs.length === 0) return;

                        let hasValidValue = false;

                        fieldInputs.forEach(input => {
                            if (fieldType === 'image') {
                                const nameMatch = input.getAttribute('name').match(/custom_fields\[([^\]]+)\]\[(\d+)\]/);
                                if (nameMatch) {
                                    const instanceNum = nameMatch[2];
                                    const previewContainer = document.getElementById('preview_' + fieldName + '_' + instanceNum);
                                    if ((input.files && input.files.length > 0) || (previewContainer && previewContainer.children.length > 0)) {
                                        hasValidValue = true;
                                    }
                                }
                            } else if (fieldType === 'checkbox') {
                                if (input.checked) hasValidValue = true;
                            } else if (fieldType === 'select') {
                                if (input.value && input.value !== '' && input.value !== null && input.value !== '0') hasValidValue = true;
                            } else {
                                if (input.value && input.value.trim() !== '') hasValidValue = true;
                            }
                        });

                        if (!hasValidValue) {
                            errors.push({
                                field: fieldInputs[0],
                                message: fieldLabel + ' - ' + (fieldType === 'image' ? messages.imageRequired : messages.requiredField)
                            });
                        }
                    });
                }

                return errors;
            }

            function validateField(field) {
                const isValid = checkFieldValidity(field);
                if (isValid) {
                    field.classList.remove('is-invalid');
                    field.classList.add('is-valid');
                    removeFieldError(field);
                } else {
                    field.classList.remove('is-valid');
                    field.classList.add('is-invalid');
                    showFieldError(field);
                }
            }

            function checkFieldValidity(field) {
                if (field.type === 'file') {
                    const fieldName = field.getAttribute('name').match(/custom_fields\[([^\]]+)\]/);
                    if (fieldName) {
                        const previewContainer = document.getElementById('preview_' + fieldName[1] + '_0');
                        return (field.files && field.files.length > 0) || (previewContainer && previewContainer.children.length > 0);
                    }
                    return field.files && field.files.length > 0;
                } else if (field.type === 'checkbox') {
                    return field.checked;
                } else if (field.tagName === 'SELECT') {
                    return field.value && field.value !== '' && field.value !== null;
                } else {
                    return field.value && field.value.trim() !== '';
                }
            }

            function getFieldLabel(field) {
                // Check ef-field wrapper first, then standard containers
                const efField = field.closest('.ef-field, .ef-check, .ef-file-wrap');
                if (efField) {
                    const label = efField.querySelector('.ef-label, .ef-check__label, label');
                    if (label) {
                        let labelText = label.textContent.trim();
                        labelText = labelText.replace(/\*/g, '').trim();
                        if (labelText) return labelText;
                    }
                }
                const label = field.closest('.col-12, .mb-3, .form-group')?.querySelector('label');
                if (label) {
                    let labelText = label.textContent.trim();
                    labelText = labelText.replace(/\*/g, '').replace(/\[.*?\]/g, '').trim();
                    return labelText || field.name || field.id;
                }
                // Try aria-label
                if (field.getAttribute('aria-label')) return field.getAttribute('aria-label');
                if (field.placeholder && field.placeholder.trim()) return field.placeholder;
                return field.name || field.id || '{{ __('messages.error') }}';
            }

            function showValidationErrors(errors) {
                validationAlert.style.display = 'block';
                validationMessage.textContent = messages.error + ': ' + messages.completeRequiredFields;
                validationErrors.innerHTML = '';
                errors.forEach(error => {
                    const li = document.createElement('li');
                    li.textContent = error.message;
                    validationErrors.appendChild(li);
                    error.field.classList.add('is-invalid');
                    error.field.classList.remove('is-valid');
                    showFieldError(error.field);
                });
            }

            function showFieldError(field) {
                removeFieldError(field);
                const errorDiv = document.createElement('div');
                errorDiv.className = 'field-error';
                errorDiv.textContent = messages.fieldRequired;
                const fieldContainer = field.closest('.col-12, .mb-3, .form-group, .ef-file-wrap') || field.closest('.ef-field')?.parentElement || field.parentElement;
                if (fieldContainer) fieldContainer.appendChild(errorDiv);
            }

            function removeFieldError(field) {
                const fieldContainer = field.closest('.col-12, .mb-3, .form-group, .ef-file-wrap') || field.closest('.ef-field')?.parentElement || field.parentElement;
                if (fieldContainer) {
                    const existingError = fieldContainer.querySelector('.field-error');
                    if (existingError) existingError.remove();
                }
            }

            function clearValidation() {
                document.querySelectorAll('.is-invalid').forEach(f => f.classList.remove('is-invalid'));
                document.querySelectorAll('.is-valid').forEach(f => f.classList.remove('is-valid'));
                document.querySelectorAll('.field-error').forEach(e => e.remove());
                validationAlert.style.display = 'none';
            }

            // Re-bind after dynamic content (e.g. repeatable groups)
            // Uses the flag-guarded bindImageInputs so no duplicates
            setTimeout(function() {
                bindImageInputs();
                bindDragDrop();
            }, 1000);

            // ===== Floating label helpers =====
            // Init selects: float label if a value is pre-selected (old())
            document.querySelectorAll('.ef-select').forEach(function(sel) {
                sel.dataset.empty = sel.value ? 'false' : 'true';
                sel.addEventListener('change', function() {
                    this.dataset.empty = this.value ? 'false' : 'true';
                });
            });

            // Handle browser autofill (Chrome fires animationstart for autofilled inputs)
            document.querySelectorAll('.ef-input').forEach(function(input) {
                input.addEventListener('animationstart', function(e) {
                    if (e.animationName === 'onAutoFillStart' || e.animationName.includes('auto')) {
                        const label = this.parentElement.querySelector('.ef-label');
                        if (label) label.classList.add('ef-label--float');
                    }
                });
            });

        });
    </script>
@endpush
