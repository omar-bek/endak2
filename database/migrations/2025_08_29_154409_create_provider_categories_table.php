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
        Schema::create('provider_categories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('category_id')->constrained()->onDelete('cascade');
            $table->boolean('is_active')->default(true);
            $table->text('description')->nullable(); // وصف إضافي لمزود الخدمة في هذا القسم
            $table->decimal('hourly_rate', 8, 2)->nullable(); // السعر بالساعة (اختياري)
            $table->integer('experience_years')->nullable(); // سنوات الخبرة في هذا القسم
            $table->timestamps();
            
            // Ensure unique combination
            $table->unique(['user_id', 'category_id']);
            
            // Indexes
            $table->index(['user_id', 'is_active']);
            $table->index(['category_id', 'is_active']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('provider_categories');
    }
};
