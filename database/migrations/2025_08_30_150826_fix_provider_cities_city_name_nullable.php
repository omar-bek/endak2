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
        Schema::table('provider_cities', function (Blueprint $table) {
            // جعل city_name اختياري
            $table->string('city_name')->nullable()->change();

            // إزالة القيد الفريد القديم
            $table->dropUnique(['user_id', 'city_name']);

            // إضافة قيد فريد جديد باستخدام city_id
            $table->unique(['user_id', 'city_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('provider_cities', function (Blueprint $table) {
            // إعادة القيد الفريد القديم
            $table->dropUnique(['user_id', 'city_id']);
            $table->unique(['user_id', 'city_name']);

            // جعل city_name مطلوب مرة أخرى
            $table->string('city_name')->nullable(false)->change();
        });
    }
};
