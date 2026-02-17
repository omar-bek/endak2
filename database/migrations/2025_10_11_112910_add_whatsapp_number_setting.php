<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // إضافة إعداد رقم الواتساب
        DB::table('system_settings')->insert([
            [
                'key' => 'whatsapp_number',
                'value' => '+966501234567',
                'type' => 'string',
                'group' => 'contact',
                'description' => 'رقم الواتساب للتواصل مع الدعم الفني'
            ],
            [
                'key' => 'whatsapp_message',
                'value' => 'مرحباً، أريد الاستفسار عن خدمة',
                'type' => 'string',
                'group' => 'contact',
                'description' => 'الرسالة الافتراضية عند فتح الواتساب'
            ],
            [
                'key' => 'whatsapp_enabled',
                'value' => 'true',
                'type' => 'boolean',
                'group' => 'contact',
                'description' => 'تفعيل رابط الواتساب في الموقع'
            ]
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('system_settings')->whereIn('key', [
            'whatsapp_number',
            'whatsapp_message',
            'whatsapp_enabled'
        ])->delete();
    }
};
