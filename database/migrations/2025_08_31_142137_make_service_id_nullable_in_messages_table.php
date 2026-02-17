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
        Schema::table('messages', function (Blueprint $table) {
            // جعل service_id nullable
            $table->foreignId('service_id')->nullable()->change();

            // جعل service_offer_id nullable
            $table->foreignId('service_offer_id')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('messages', function (Blueprint $table) {
            // إعادة جعل service_id مطلوب
            $table->foreignId('service_id')->nullable(false)->change();

            // إعادة جعل service_offer_id مطلوب
            $table->foreignId('service_offer_id')->nullable(false)->change();
        });
    }
};
