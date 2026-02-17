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
        Schema::create('provider_cities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('city_name'); // اسم المدينة
            $table->boolean('is_active')->default(true);
            $table->text('notes')->nullable(); // ملاحظات إضافية عن العمل في هذه المدينة
            $table->timestamps();
            
            // Ensure unique combination
            $table->unique(['user_id', 'city_name']);
            
            // Indexes
            $table->index(['user_id', 'is_active']);
            $table->index(['city_name', 'is_active']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('provider_cities');
    }
};
