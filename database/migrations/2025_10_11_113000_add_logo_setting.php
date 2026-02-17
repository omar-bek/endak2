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
        // إضافة إعداد اللوجو
        DB::table('system_settings')->insert([
            [
                'key' => 'site_logo',
                'value' => 'home.png',
                'type' => 'string',
                'group' => 'site',
                'description' => 'لوجو الموقع الرئيسي'
            ],
            [
                'key' => 'site_name',
                'value' => 'Endak',
                'type' => 'string',
                'group' => 'site',
                'description' => 'اسم الموقع'
            ],
            [
                'key' => 'site_name_ar',
                'value' => 'إنداك',
                'type' => 'string',
                'group' => 'site',
                'description' => 'اسم الموقع باللغة العربية'
            ]
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('system_settings')->whereIn('key', [
            'site_logo',
            'site_name',
            'site_name_ar'
        ])->delete();
    }
};
