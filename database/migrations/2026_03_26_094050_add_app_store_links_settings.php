<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Rename existing links to google play
        \App\Models\SystemSetting::where('key', 'provider_app_link')
            ->update(['key' => 'provider_app_google_play', 'description' => 'رابط تطبيق مزود الخدمة - Google Play']);
        \App\Models\SystemSetting::where('key', 'client_app_link')
            ->update(['key' => 'client_app_google_play', 'description' => 'رابط تطبيق العميل - Google Play']);

        // Add App Store links
        \App\Models\SystemSetting::set('provider_app_appstore', '', 'string', 'apps', 'رابط تطبيق مزود الخدمة - App Store');
        \App\Models\SystemSetting::set('client_app_appstore', '', 'string', 'apps', 'رابط تطبيق العميل - App Store');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert rename
        \App\Models\SystemSetting::where('key', 'provider_app_google_play')
            ->update(['key' => 'provider_app_link', 'description' => 'رابط تطبيق مزود الخدمة']);
        \App\Models\SystemSetting::where('key', 'client_app_google_play')
            ->update(['key' => 'client_app_link', 'description' => 'رابط تطبيق العميل']);

        \App\Models\SystemSetting::whereIn('key', [
            'provider_app_appstore',
            'client_app_appstore',
        ])->delete();
    }
};
