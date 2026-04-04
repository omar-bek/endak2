<style>
/* ==========================================================
   AUTH PAGES - Shared Styles (Login & Register)
   Uses Endak Design System variables from endak.css
   ========================================================== */

/* ===== Page & Background ===== */
.auth-page {
    position: relative;
    min-height: 100vh;
    background: var(--e-gray-50);
    overflow: hidden;
}

.auth-bg {
    position: fixed;
    inset: 0;
    pointer-events: none;
    z-index: 0;
}

.auth-bg__shape {
    position: absolute;
    border-radius: 50%;
}

.auth-bg__shape--1 {
    width: 600px;
    height: 600px;
    top: -15%;
    right: -10%;
    background: radial-gradient(circle, var(--e-primary-100) 0%, transparent 70%);
}

.auth-bg__shape--2 {
    width: 500px;
    height: 500px;
    bottom: -20%;
    left: -8%;
    background: radial-gradient(circle, var(--e-accent-50) 0%, transparent 70%);
}

/* ===== Auth Card ===== */
.auth-card {
    position: relative;
    z-index: 1;
    display: flex;
    background: var(--e-white);
    border-radius: var(--e-radius-xl);
    box-shadow: var(--e-shadow-lg);
    overflow: hidden;
    animation: authFadeIn 0.6s ease;
}

@keyframes authFadeIn {
    from { opacity: 0; transform: translateY(20px); }
    to { opacity: 1; transform: translateY(0); }
}

/* ===== Side Panel ===== */
.auth-card__side {
    width: 40%;
    background: linear-gradient(160deg, var(--e-primary-dark) 0%, var(--e-primary) 50%, var(--e-primary-light) 100%);
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 3rem 2rem;
    position: relative;
    overflow: hidden;
    flex-shrink: 0;
}

.auth-card__side::before {
    content: '';
    position: absolute;
    width: 300px;
    height: 300px;
    border-radius: 50%;
    background: rgba(255, 255, 255, 0.04);
    top: -80px;
    right: -80px;
}

.auth-card__side::after {
    content: '';
    position: absolute;
    width: 200px;
    height: 200px;
    border-radius: 50%;
    background: rgba(243, 164, 70, 0.08);
    bottom: -60px;
    left: -40px;
}

.auth-card__side-content {
    text-align: center;
    position: relative;
    z-index: 1;
}

.auth-card__side-icon {
    width: 70px;
    height: 70px;
    border-radius: 50%;
    background: rgba(255, 255, 255, 0.12);
    display: inline-flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 1.25rem;
    backdrop-filter: blur(10px);
}

.auth-card__side-icon i {
    font-size: 1.6rem;
    color: var(--e-accent);
}

.auth-card__side-title {
    color: #fff;
    font-size: 1.5rem;
    font-weight: 700;
    margin-bottom: 0.6rem;
}

.auth-card__side-text {
    color: rgba(255, 255, 255, 0.7);
    font-size: 0.88rem;
    margin-bottom: 1.5rem;
    line-height: 1.6;
}

.auth-card__side-btn {
    display: inline-flex;
    align-items: center;
    padding: 0.6rem 1.8rem;
    border: 2px solid rgba(255, 255, 255, 0.35);
    border-radius: var(--e-radius-full);
    color: #fff;
    font-size: 0.88rem;
    font-weight: 600;
    text-decoration: none;
    transition: all var(--e-transition);
    backdrop-filter: blur(5px);
}

.auth-card__side-btn:hover {
    background: var(--e-accent);
    border-color: var(--e-accent);
    color: #fff;
    transform: translateY(-2px);
    box-shadow: var(--e-shadow-accent);
}

/* ===== Form Panel ===== */
.auth-card__form {
    flex: 1;
    padding: 2.5rem 3rem;
    display: flex;
    flex-direction: column;
    justify-content: center;
}

/* ===== Logo ===== */
.auth-logo {
    display: inline-block;
    margin-bottom: 0.5rem;
    text-align: center;
}

.auth-logo img {
    height: 44px;
    width: auto;
}

/* ===== Form Title ===== */
.auth-form-title {
    font-size: 1.35rem;
    font-weight: 700;
    color: var(--e-gray-800);
    margin-bottom: 1.5rem;
    text-align: center;
}

/* ===== Social Button ===== */
.auth-social-btn {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.6rem;
    width: 100%;
    padding: 0.7rem 1.2rem;
    border: 1.5px solid var(--e-gray-200);
    border-radius: var(--e-radius);
    background: var(--e-white);
    color: var(--e-gray-700);
    font-size: 0.9rem;
    font-weight: 600;
    text-decoration: none;
    transition: all var(--e-transition);
}

.auth-social-btn:hover {
    border-color: var(--e-gray-300);
    background: var(--e-gray-50);
    box-shadow: var(--e-shadow-sm);
    transform: translateY(-1px);
    color: var(--e-gray-800);
}

.auth-social-btn__icon {
    flex-shrink: 0;
}

/* ===== Divider ===== */
.auth-divider {
    display: flex;
    align-items: center;
    gap: 1rem;
    margin: 1.25rem 0;
}

.auth-divider::before,
.auth-divider::after {
    content: '';
    flex: 1;
    height: 1px;
    background: var(--e-gray-200);
}

.auth-divider span {
    font-size: 0.78rem;
    color: var(--e-gray-400);
    font-weight: 500;
    white-space: nowrap;
}

/* ===== Floating Input Fields (ef-*) ===== */
.auth-form .ef-field {
    position: relative;
    margin-bottom: 1rem;
}

.auth-form .ef-input {
    width: 100%;
    border: 1.5px solid var(--e-gray-200);
    border-radius: var(--e-radius);
    padding: 1.3rem 1rem 0.45rem;
    font-size: 0.92rem;
    color: var(--e-gray-800);
    background: var(--e-white);
    transition: border-color var(--e-transition), box-shadow var(--e-transition);
    outline: none;
    font-family: var(--e-font);
}

.auth-form .ef-input::placeholder {
    color: transparent;
}

.auth-form .ef-input:focus {
    border-color: var(--e-primary);
    box-shadow: 0 0 0 3px var(--e-primary-100);
}

.auth-form .ef-label {
    position: absolute;
    top: 50%;
    right: 1rem;
    transform: translateY(-50%);
    font-size: 0.88rem;
    color: var(--e-gray-400);
    pointer-events: none;
    transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
    transform-origin: right center;
    font-weight: 500;
    line-height: 1;
}

[dir="ltr"] .auth-form .ef-label {
    right: auto;
    left: 1rem;
    transform-origin: left center;
}

.auth-form .ef-input:focus ~ .ef-label,
.auth-form .ef-input:not(:placeholder-shown) ~ .ef-label {
    top: 0.5rem;
    transform: translateY(0) scale(0.78);
    color: var(--e-primary);
    font-weight: 600;
}

/* Icon offset */
.auth-form .ef-field--icon .ef-icon {
    position: absolute;
    top: 50%;
    right: 0.9rem;
    transform: translateY(-50%);
    color: var(--e-gray-300);
    font-size: 0.9rem;
    pointer-events: none;
    transition: color var(--e-transition);
    z-index: 1;
}

[dir="ltr"] .auth-form .ef-field--icon .ef-icon {
    right: auto;
    left: 0.9rem;
}

.auth-form .ef-field--icon .ef-input {
    padding-right: 2.6rem;
}

[dir="ltr"] .auth-form .ef-field--icon .ef-input {
    padding-right: 1rem;
    padding-left: 2.6rem;
}

.auth-form .ef-field--icon .ef-label {
    right: 2.6rem;
}

[dir="ltr"] .auth-form .ef-field--icon .ef-label {
    right: auto;
    left: 2.6rem;
}

.auth-form .ef-field--icon .ef-input:focus ~ .ef-icon {
    color: var(--e-primary);
}

/* Hint text */
.auth-form .ef-hint {
    font-size: 0.74rem;
    color: var(--e-gray-400);
    margin-top: 0.3rem;
    padding-inline-start: 0.25rem;
}

/* ===== Password Toggle ===== */
.auth-toggle-pw {
    position: absolute;
    top: 50%;
    left: 0.85rem;
    transform: translateY(-50%);
    background: none;
    border: none;
    padding: 0.25rem;
    color: var(--e-gray-400);
    cursor: pointer;
    font-size: 0.88rem;
    transition: color var(--e-transition);
    z-index: 2;
}

[dir="ltr"] .auth-toggle-pw {
    left: auto;
    right: 0.85rem;
}

.auth-toggle-pw:hover {
    color: var(--e-primary);
}

/* ===== Validation ===== */
.auth-form .ef-input.is-invalid {
    border-color: var(--e-danger) !important;
    box-shadow: 0 0 0 3px rgba(239, 68, 68, 0.1) !important;
}

.auth-form .ef-input.is-invalid ~ .ef-label {
    color: var(--e-danger) !important;
}

.auth-form .ef-input.is-invalid ~ .ef-icon {
    color: var(--e-danger) !important;
}

.auth-field-error {
    font-size: 0.78rem;
    color: var(--e-danger);
    margin-top: 0.3rem;
    display: flex;
    align-items: center;
    gap: 0.3rem;
}

.auth-field-error::before {
    content: '\f06a';
    font-family: 'Font Awesome 6 Free';
    font-weight: 900;
    font-size: 0.7rem;
}

/* ===== Checkbox ===== */
.auth-check {
    display: flex;
    align-items: center;
    gap: 0.55rem;
    cursor: pointer;
    user-select: none;
    margin-bottom: 1rem;
}

.auth-check__input {
    position: absolute;
    opacity: 0;
    width: 0;
    height: 0;
}

.auth-check__box {
    width: 20px;
    height: 20px;
    border: 2px solid var(--e-gray-300);
    border-radius: 5px;
    flex-shrink: 0;
    position: relative;
    transition: all var(--e-transition);
    background: var(--e-white);
}

.auth-check__input:checked + .auth-check__box {
    background: var(--e-primary);
    border-color: var(--e-primary);
}

.auth-check__input:checked + .auth-check__box::after {
    content: '';
    position: absolute;
    top: 3px;
    left: 6px;
    width: 5px;
    height: 9px;
    border: solid #fff;
    border-width: 0 2px 2px 0;
    transform: rotate(45deg);
}

.auth-check__input:focus-visible + .auth-check__box {
    box-shadow: 0 0 0 3px var(--e-primary-100);
}

.auth-check__text {
    font-size: 0.85rem;
    color: var(--e-gray-600);
}

.auth-check__text a {
    color: var(--e-primary);
    font-weight: 600;
    text-decoration: none;
}

.auth-check__text a:hover {
    text-decoration: underline;
}

/* ===== Auth Options Row ===== */
.auth-options {
    display: flex;
    justify-content: space-between;
    align-items: center;
}

/* ===== Submit Button ===== */
.auth-submit-btn {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
    width: 100%;
    padding: 0.8rem 1.5rem;
    background: linear-gradient(135deg, var(--e-primary) 0%, var(--e-primary-dark) 100%);
    color: #fff;
    border: none;
    border-radius: var(--e-radius-full);
    font-size: 1rem;
    font-weight: 700;
    cursor: pointer;
    transition: all var(--e-transition);
    box-shadow: var(--e-shadow-primary);
    margin-top: 0.5rem;
}

.auth-submit-btn:hover {
    background: linear-gradient(135deg, var(--e-accent) 0%, var(--e-accent-dark) 100%);
    box-shadow: var(--e-shadow-accent);
    transform: translateY(-2px);
    color: #fff;
}

.auth-submit-btn:active {
    transform: translateY(0);
}

/* ===== Account Type Selector (Register) ===== */
.auth-type-selector {
    margin-bottom: 1rem;
}

.auth-type-selector__label {
    font-size: 0.85rem;
    font-weight: 600;
    color: var(--e-gray-600);
    margin-bottom: 0.6rem;
}

.auth-type-cards {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 0.75rem;
}

.auth-type-card {
    cursor: pointer;
    margin: 0;
}

.auth-type-card__input {
    position: absolute;
    opacity: 0;
    width: 0;
    height: 0;
}

.auth-type-card__body {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 0.5rem;
    padding: 1.1rem 0.75rem;
    border: 2px solid var(--e-gray-200);
    border-radius: var(--e-radius-lg);
    background: var(--e-white);
    transition: all var(--e-transition);
    text-align: center;
}

.auth-type-card:hover .auth-type-card__body {
    border-color: var(--e-primary-200);
    background: var(--e-primary-50);
}

.auth-type-card__input:checked ~ .auth-type-card__body {
    border-color: var(--e-primary);
    background: var(--e-primary-50);
    box-shadow: 0 0 0 3px var(--e-primary-100);
}

.auth-type-card__icon {
    width: 42px;
    height: 42px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    background: var(--e-gray-100);
    color: var(--e-gray-500);
    font-size: 1rem;
    transition: all var(--e-transition);
}

.auth-type-card__input:checked ~ .auth-type-card__body .auth-type-card__icon {
    background: var(--e-primary);
    color: #fff;
}

.auth-type-card__text strong {
    font-size: 0.8rem;
    color: var(--e-gray-700);
    font-weight: 600;
}

.auth-type-card__input:checked ~ .auth-type-card__body .auth-type-card__text strong {
    color: var(--e-primary-dark);
}

/* ===== Two-column Row ===== */
.auth-row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 0.75rem;
}

/* ===== Mobile Switch Link ===== */
.auth-mobile-switch {
    display: none;
    text-align: center;
    margin-top: 1.25rem;
    font-size: 0.85rem;
    color: var(--e-gray-500);
}

.auth-mobile-switch a {
    color: var(--e-primary);
    font-weight: 700;
    text-decoration: none;
}

.auth-mobile-switch a:hover {
    text-decoration: underline;
}

/* ===== Responsive ===== */
@media (max-width: 991px) {
    .auth-card__form {
        padding: 2rem;
    }
}

@media (max-width: 768px) {
    .auth-page {
        background: var(--e-white);
    }

    .auth-card {
        flex-direction: column;
        border-radius: 0;
        box-shadow: none;
        min-height: 100vh;
    }

    .auth-card__side {
        display: none;
    }

    .auth-card__form {
        padding: 2rem 1.5rem;
        width: 100%;
    }

    .auth-mobile-switch {
        display: block;
    }

    .auth-row {
        grid-template-columns: 1fr;
        gap: 0;
    }

    .auth-type-cards {
        grid-template-columns: 1fr 1fr;
    }

    .auth-form-title {
        font-size: 1.2rem;
    }

    .auth-bg {
        display: none;
    }
}

@media (max-width: 400px) {
    .auth-type-cards {
        grid-template-columns: 1fr;
    }

    .auth-card__form {
        padding: 1.5rem 1.25rem;
    }
}
</style>
