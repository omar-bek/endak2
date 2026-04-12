<?php

use Illuminate\Support\Facades\Storage;

if (!function_exists('media_is_absolute_url')) {
    function media_is_absolute_url(?string $value): bool
    {
        if ($value === null || $value === '') {
            return false;
        }

        return (bool) filter_var($value, FILTER_VALIDATE_URL);
    }
}

if (!function_exists('media_public_url_from_path')) {
    /**
     * Full URL to persist after storing a file on the public disk.
     */
    function media_public_url_from_path(string $path): string
    {
        return Storage::disk('public')->url($path);
    }
}

if (!function_exists('media_web_url_for_storage_path')) {
    /**
     * Public URL for a path under storage/app/public (same host as the current HTTP request when possible).
     * Avoids broken images when APP_URL uses localhost but the site is opened via 127.0.0.1 or another host.
     */
    function media_web_url_for_storage_path(string $relativePath): string
    {
        $relativePath = ltrim($relativePath, '/');

        if (!app()->runningInConsole() && app()->bound('request')) {
            $host = request()->getSchemeAndHttpHost();
            if ($host !== '') {
                return rtrim($host, '/') . '/storage/' . $relativePath;
            }
        }

        return Storage::disk('public')->url($relativePath);
    }
}

if (!function_exists('media_resolve_url')) {
    /**
     * Resolve a stored media value (absolute URL or legacy relative path) for output.
     */
    function media_resolve_url(?string $stored): ?string
    {
        if ($stored === null || $stored === '') {
            return null;
        }
        if (media_is_absolute_url($stored)) {
            $parsedPath = parse_url($stored, PHP_URL_PATH);
            if (is_string($parsedPath) && preg_match('#/storage/(.+)$#', $parsedPath, $m)) {
                return media_web_url_for_storage_path($m[1]);
            }

            return $stored;
        }
        $trimmed = ltrim($stored, '/');
        if (str_starts_with($trimmed, 'storage/')) {
            $trimmed = substr($trimmed, strlen('storage/'));
        }

        return media_web_url_for_storage_path($trimmed);
    }
}

if (!function_exists('media_public_disk_path')) {
    /**
     * Path relative to storage/app/public for exists/delete operations.
     */
    function media_public_disk_path(?string $stored): ?string
    {
        if ($stored === null || $stored === '') {
            return null;
        }
        if (media_is_absolute_url($stored)) {
            $parsed = parse_url($stored, PHP_URL_PATH);
            if (!$parsed || !is_string($parsed)) {
                return null;
            }
            if (preg_match('#/storage/(.+)$#', $parsed, $m)) {
                return $m[1];
            }

            return null;
        }
        $trimmed = ltrim($stored, '/');
        if (str_starts_with($trimmed, 'storage/')) {
            return substr($trimmed, strlen('storage/'));
        }

        return $trimmed;
    }
}

if (!function_exists('media_site_logo_url')) {
    /**
     * Display URL for site_logo setting (URL, storage path, or public file like home.png).
     */
    function media_site_logo_url(?string $stored): string
    {
        $stored = $stored ?? 'home.png';
        if (media_is_absolute_url($stored)) {
            $parsedPath = parse_url($stored, PHP_URL_PATH);
            if (is_string($parsedPath) && preg_match('#/storage/(.+)$#', $parsedPath, $m)) {
                return media_web_url_for_storage_path($m[1]);
            }

            return $stored;
        }
        if ($stored === 'home.png' || !str_contains($stored, '/')) {
            return asset($stored);
        }

        return media_resolve_url($stored) ?? asset('home.png');
    }
}

if (!function_exists('media_delete_public_file')) {
    function media_delete_public_file(?string $stored): void
    {
        if (!$stored || $stored === 'home.png') {
            return;
        }
        $rel = media_public_disk_path($stored);
        if ($rel && Storage::disk('public')->exists($rel)) {
            Storage::disk('public')->delete($rel);

            return;
        }
        if (!media_is_absolute_url($stored) && file_exists(public_path($stored))) {
            unlink(public_path($stored));
        }
    }
}
