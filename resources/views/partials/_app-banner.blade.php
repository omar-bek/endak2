@php
    $clientEnabled = \App\Models\SystemSetting::get('client_app_enabled', false);
    $providerEnabled = \App\Models\SystemSetting::get('provider_app_enabled', false);

    if (!$clientEnabled && !$providerEnabled) return;

    $clientGoogle = \App\Models\SystemSetting::get('client_app_google_play', '');
    $clientApple  = \App\Models\SystemSetting::get('client_app_appstore', '');
    $provGoogle   = \App\Models\SystemSetting::get('provider_app_google_play', '');
    $provApple    = \App\Models\SystemSetting::get('provider_app_appstore', '');

    // Detect platform
    $ua = request()->header('User-Agent', '');
    $isIos = (bool) preg_match('/iPhone|iPad|iPod/i', $ua);
    $isAndroid = (bool) preg_match('/Android/i', $ua);
    $isMobile = $isIos || $isAndroid;

    if (!$isMobile) return;

    // Pick the best link: client app first, then provider
    $appLink = '';
    $storeName = '';
    $storeIcon = '';

    if ($isIos) {
        $appLink = ($clientEnabled && $clientApple) ? $clientApple : (($providerEnabled && $provApple) ? $provApple : '');
        $storeName = 'App Store';
        $storeIcon = 'fab fa-apple';
    } else {
        $appLink = ($clientEnabled && $clientGoogle) ? $clientGoogle : (($providerEnabled && $provGoogle) ? $provGoogle : '');
        $storeName = 'Google Play';
        $storeIcon = 'fab fa-google-play';
    }

    if (!$appLink) return;

    $siteLogo = media_site_logo_url(\App\Models\SystemSetting::get('site_logo', 'home.png'));
    $siteName = \App\Models\SystemSetting::get('site_name_ar', 'إنداك');
@endphp

<div class="app-banner" id="appBanner">
    <button class="app-banner__close" id="appBannerClose" aria-label="Close">
        <i class="fas fa-times"></i>
    </button>
    <img class="app-banner__icon" src="{{ $siteLogo }}" alt="{{ $siteName }}">
    <div class="app-banner__info">
        <div class="app-banner__title">{{ __('messages.app_banner_title') }}</div>
        <div class="app-banner__subtitle">{{ __('messages.app_banner_subtitle') }}</div>
    </div>
    <a href="{{ $appLink }}" target="_blank" rel="noopener noreferrer" class="app-banner__btn">
        <i class="{{ $storeIcon }}"></i>
        {{ __('messages.app_banner_btn') }}
    </a>
</div>

<style>
/* ===== Smart App Banner ===== */
.app-banner {
    display: none; /* shown by JS if not dismissed */
    position: fixed;
    bottom: 70px; /* above mobile footer nav */
    left: 0;
    right: 0;
    z-index: 999;
    background: var(--e-white, #fff);
    border-top: 1px solid var(--e-gray-200, #e2e8f0);
    box-shadow: 0 -4px 20px rgba(0, 0, 0, 0.1);
    padding: 0.65rem 0.75rem;
    align-items: center;
    gap: 0.65rem;
    animation: bannerSlideUp 0.4s ease;
    border-radius: 16px 16px 0 0;
}

.app-banner.is-visible {
    display: flex;
}

@keyframes bannerSlideUp {
    from { transform: translateY(100%); opacity: 0; }
    to { transform: translateY(0); opacity: 1; }
}

.app-banner__close {
    background: none;
    border: none;
    color: var(--e-gray-400, #94a3b8);
    font-size: 0.85rem;
    padding: 0.3rem;
    cursor: pointer;
    flex-shrink: 0;
    line-height: 1;
    transition: color 0.2s;
}

.app-banner__close:hover {
    color: var(--e-gray-600, #475569);
}

.app-banner__icon {
    width: 44px;
    height: 44px;
    border-radius: 10px;
    object-fit: contain;
    flex-shrink: 0;
    background: var(--e-gray-50, #f8fafc);
    border: 1px solid var(--e-gray-200, #e2e8f0);
    padding: 4px;
}

.app-banner__info {
    flex: 1;
    min-width: 0;
    line-height: 1.3;
}

.app-banner__title {
    font-size: 0.82rem;
    font-weight: 700;
    color: var(--e-gray-800, #1e293b);
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.app-banner__subtitle {
    font-size: 0.7rem;
    color: var(--e-gray-500, #64748b);
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.app-banner__btn {
    display: inline-flex;
    align-items: center;
    gap: 0.35rem;
    background: linear-gradient(135deg, var(--e-primary, #2f5c69) 0%, var(--e-primary-dark, #1e3d47) 100%);
    color: #fff;
    border: none;
    border-radius: 20px;
    padding: 0.45rem 1rem;
    font-size: 0.75rem;
    font-weight: 700;
    text-decoration: none;
    white-space: nowrap;
    flex-shrink: 0;
    transition: all 0.25s;
    box-shadow: 0 2px 8px rgba(47, 92, 105, 0.25);
}

.app-banner__btn:hover {
    background: linear-gradient(135deg, var(--e-accent, #f3a446) 0%, var(--e-accent-dark, #e2933d) 100%);
    color: #fff;
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(243, 164, 70, 0.3);
}

.app-banner__btn i {
    font-size: 0.9rem;
}

/* Hide on desktop */
@media (min-width: 992px) {
    .app-banner { display: none !important; }
}
</style>

<script>
(function() {
    var banner = document.getElementById('appBanner');
    var closeBtn = document.getElementById('appBannerClose');
    if (!banner || !closeBtn) return;

    var STORAGE_KEY = 'endak_app_banner_dismissed';
    var DISMISS_DURATION = 3 * 24 * 60 * 60 * 1000; // 3 days

    // Check if dismissed
    var dismissed = localStorage.getItem(STORAGE_KEY);
    if (dismissed) {
        var dismissedAt = parseInt(dismissed, 10);
        if (Date.now() - dismissedAt < DISMISS_DURATION) return;
    }

    // Show banner after short delay
    setTimeout(function() {
        banner.classList.add('is-visible');
    }, 1500);

    // Close handler
    closeBtn.addEventListener('click', function(e) {
        e.preventDefault();
        banner.classList.remove('is-visible');
        localStorage.setItem(STORAGE_KEY, Date.now().toString());
    });
})();
</script>
