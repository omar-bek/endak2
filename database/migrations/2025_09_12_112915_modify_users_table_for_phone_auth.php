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
        Schema::table('users', function (Blueprint $table) {
            // جعل email nullable
            $table->string('email')->nullable()->change();

            // جعل phone مطلوب وفريد
            $table->string('phone')->unique()->change();

            // إضافة عمود phone_verified_at
            $table->timestamp('phone_verified_at')->nullable()->after('phone');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // إرجاع email كمطلوب
            $table->string('email')->nullable(false)->change();

            // إرجاع phone كاختياري
            $table->string('phone')->nullable()->change();

            // حذف عمود phone_verified_at
            $table->dropColumn('phone_verified_at');
        });
    }
};
