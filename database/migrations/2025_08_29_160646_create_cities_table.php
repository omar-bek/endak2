<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB; // Added this import for DB facade

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('cities', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // اسم المدينة
            $table->string('name_en')->nullable(); // الاسم بالإنجليزية
            $table->string('slug')->unique(); // الرابط المختصر
            $table->boolean('is_active')->default(true); // هل المدينة نشطة
            $table->integer('sort_order')->default(0); // ترتيب المدينة
            $table->timestamps();

            // Indexes
            $table->index(['is_active', 'sort_order']);
        });

        // إدراج المدن الرئيسية في السعودية
        DB::table('cities')->insert([
            [
                'name' => 'الرياض',
                'name_en' => 'Riyadh',
                'slug' => 'riyadh',
                'is_active' => true,
                'sort_order' => 1
            ],
            [
                'name' => 'جدة',
                'name_en' => 'Jeddah',
                'slug' => 'jeddah',
                'is_active' => true,
                'sort_order' => 2
            ],
            [
                'name' => 'مكة المكرمة',
                'name_en' => 'Makkah',
                'slug' => 'makkah',
                'is_active' => true,
                'sort_order' => 3
            ],
            [
                'name' => 'المدينة المنورة',
                'name_en' => 'Madinah',
                'slug' => 'madinah',
                'is_active' => true,
                'sort_order' => 4
            ],
            [
                'name' => 'الدمام',
                'name_en' => 'Dammam',
                'slug' => 'dammam',
                'is_active' => true,
                'sort_order' => 5
            ],
            [
                'name' => 'الخبر',
                'name_en' => 'Khobar',
                'slug' => 'khobar',
                'is_active' => true,
                'sort_order' => 6
            ],
            [
                'name' => 'الظهران',
                'name_en' => 'Dhahran',
                'slug' => 'dhahran',
                'is_active' => true,
                'sort_order' => 7
            ],
            [
                'name' => 'تبوك',
                'name_en' => 'Tabuk',
                'slug' => 'tabuk',
                'is_active' => true,
                'sort_order' => 8
            ],
            [
                'name' => 'أبها',
                'name_en' => 'Abha',
                'slug' => 'abha',
                'is_active' => true,
                'sort_order' => 9
            ],
            [
                'name' => 'حائل',
                'name_en' => 'Hail',
                'slug' => 'hail',
                'is_active' => true,
                'sort_order' => 10
            ],
            [
                'name' => 'القصيم',
                'name_en' => 'Qassim',
                'slug' => 'qassim',
                'is_active' => true,
                'sort_order' => 11
            ],
            [
                'name' => 'جازان',
                'name_en' => 'Jazan',
                'slug' => 'jazan',
                'is_active' => true,
                'sort_order' => 12
            ],
            [
                'name' => 'نجران',
                'name_en' => 'Najran',
                'slug' => 'najran',
                'is_active' => true,
                'sort_order' => 13
            ],
            [
                'name' => 'الباحة',
                'name_en' => 'Baha',
                'slug' => 'baha',
                'is_active' => true,
                'sort_order' => 14
            ],
            [
                'name' => 'الجوف',
                'name_en' => 'Jouf',
                'slug' => 'jouf',
                'is_active' => true,
                'sort_order' => 15
            ]
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cities');
    }
};
