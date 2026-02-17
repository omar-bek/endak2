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
            // إضافة حقل city_id
            $table->foreignId('city_id')->nullable()->after('user_id')->constrained()->onDelete('cascade');

            // إضافة index جديد
            $table->index(['user_id', 'city_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('provider_cities', function (Blueprint $table) {
            $table->dropForeign(['city_id']);
            $table->dropIndex(['user_id', 'city_id']);
            $table->dropColumn('city_id');
        });
    }
};
