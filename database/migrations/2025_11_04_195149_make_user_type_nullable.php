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
        // جعل user_type nullable للسماح بالتسجيل عبر Social Login بدون اختيار الدور
        Schema::table('users', function (Blueprint $table) {
            // حذف default constraint أولاً
            DB::statement("ALTER TABLE users ALTER COLUMN user_type DROP DEFAULT");
            
            // تغيير enum ليكون nullable
            $table->enum('user_type', ['customer', 'provider', 'admin'])->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // إرجاع user_type مع default
        Schema::table('users', function (Blueprint $table) {
            // إعطاء قيمة افتراضية للمستخدمين الذين ليس لديهم user_type
            DB::table('users')->whereNull('user_type')->update(['user_type' => 'customer']);
            
            // إرجاع default
            $table->enum('user_type', ['customer', 'provider', 'admin'])->default('customer')->change();
        });
    }
};
