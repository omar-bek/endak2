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
        Schema::create('provider_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->text('bio')->nullable(); // نبذة عن مزود الخدمة
            $table->string('phone')->nullable();
            $table->string('address')->nullable();
            $table->boolean('is_verified')->default(false); // هل تم التحقق من مزود الخدمة
            $table->boolean('is_active')->default(true); // هل مزود الخدمة نشط
            $table->integer('max_categories')->default(3); // الحد الأقصى للأقسام (قابل للتعديل من الإدارة)
            $table->integer('max_cities')->default(5); // الحد الأقصى للمدن (قابل للتعديل من الإدارة)
            $table->json('working_hours')->nullable(); // ساعات العمل
            $table->decimal('rating', 3, 2)->default(0); // التقييم
            $table->integer('completed_services')->default(0); // عدد الخدمات المكتملة
            $table->timestamps();

            // Indexes
            $table->index(['user_id', 'is_active']);
            $table->index(['is_verified', 'is_active']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('provider_profiles');
    }
};
