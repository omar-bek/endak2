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
        Schema::create('system_settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique(); // مفتاح الإعداد
            $table->text('value'); // قيمة الإعداد
            $table->string('type')->default('string'); // نوع القيمة (string, integer, boolean, json)
            $table->string('group')->default('general'); // مجموعة الإعداد
            $table->text('description')->nullable(); // وصف الإعداد
            $table->timestamps();
        });

        // إدراج الإعدادات الافتراضية
        DB::table('system_settings')->insert([
            [
                'key' => 'provider_max_categories',
                'value' => '3',
                'type' => 'integer',
                'group' => 'provider',
                'description' => 'الحد الأقصى لعدد الأقسام التي يمكن لمزود الخدمة العمل فيها'
            ],
            [
                'key' => 'provider_max_cities',
                'value' => '5',
                'type' => 'integer',
                'group' => 'provider',
                'description' => 'الحد الأقصى لعدد المدن التي يمكن لمزود الخدمة العمل فيها'
            ],
            [
                'key' => 'provider_verification_required',
                'value' => 'true',
                'type' => 'boolean',
                'group' => 'provider',
                'description' => 'هل يتطلب التحقق من مزود الخدمة قبل تفعيل الحساب'
            ],
            [
                'key' => 'provider_auto_approve',
                'value' => 'false',
                'type' => 'boolean',
                'group' => 'provider',
                'description' => 'هل يتم الموافقة التلقائية على مزودي الخدمة'
            ]
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('system_settings');
    }
};
