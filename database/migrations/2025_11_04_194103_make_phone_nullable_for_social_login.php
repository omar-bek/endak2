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
        // جعل phone nullable للسماح بالتسجيل عبر Social Login بدون رقم هاتف
        // يجب أولاً حذف unique constraint ثم إعادة إضافته مع nullable
        Schema::table('users', function (Blueprint $table) {
            $table->dropUnique(['phone']);
        });
        
        Schema::table('users', function (Blueprint $table) {
            $table->string('phone')->nullable()->unique()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // إرجاع phone كمطلوب (لكن يجب أن نتحقق من عدم وجود قيم null)
        Schema::table('users', function (Blueprint $table) {
            // إعطاء رقم هاتف افتراضي للمستخدمين الذين ليس لديهم رقم هاتف
            DB::table('users')->whereNull('phone')->update(['phone' => DB::raw("CONCAT('temp_', id)")]);
            
            $table->string('phone')->nullable(false)->unique()->change();
        });
    }
};
