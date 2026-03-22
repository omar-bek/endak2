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
        \App\Models\SystemSetting::set('provider_app_link', '', 'string', 'apps', 'رابط تطبيق مزود الخدمة');
        \App\Models\SystemSetting::set('client_app_link', '', 'string', 'apps', 'رابط تطبيق العميل');
        \App\Models\SystemSetting::set('provider_app_enabled', false, 'boolean', 'apps', 'تفعيل رابط تطبيق المزود');
        \App\Models\SystemSetting::set('client_app_enabled', false, 'boolean', 'apps', 'تفعيل رابط تطبيق العميل');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        \App\Models\SystemSetting::whereIn('key', [
            'provider_app_link',
            'client_app_link',
            'provider_app_enabled',
            'client_app_enabled',
        ])->delete();
    }
};
