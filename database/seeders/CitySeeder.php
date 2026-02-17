<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $cities = [
            ['name_ar' => 'الرياض', 'name_en' => 'Riyadh', 'slug' => 'riyadh', 'is_active' => true, 'sort_order' => 1],
            ['name_ar' => 'جدة', 'name_en' => 'Jeddah', 'slug' => 'jeddah', 'is_active' => true, 'sort_order' => 2],
            ['name_ar' => 'مكة المكرمة', 'name_en' => 'Makkah', 'slug' => 'makkah', 'is_active' => true, 'sort_order' => 3],
            ['name_ar' => 'المدينة المنورة', 'name_en' => 'Madinah', 'slug' => 'madinah', 'is_active' => true, 'sort_order' => 4],
            ['name_ar' => 'الدمام', 'name_en' => 'Dammam', 'slug' => 'dammam', 'is_active' => true, 'sort_order' => 5],
            ['name_ar' => 'الخبر', 'name_en' => 'Khobar', 'slug' => 'khobar', 'is_active' => true, 'sort_order' => 6],
            ['name_ar' => 'الظهران', 'name_en' => 'Dhahran', 'slug' => 'dhahran', 'is_active' => true, 'sort_order' => 7],
            ['name_ar' => 'تبوك', 'name_en' => 'Tabuk', 'slug' => 'tabuk', 'is_active' => true, 'sort_order' => 8],
            ['name_ar' => 'أبها', 'name_en' => 'Abha', 'slug' => 'abha', 'is_active' => true, 'sort_order' => 9],
            ['name_ar' => 'حائل', 'name_en' => 'Hail', 'slug' => 'hail', 'is_active' => true, 'sort_order' => 10],
            ['name_ar' => 'القصيم', 'name_en' => 'Qassim', 'slug' => 'qassim', 'is_active' => true, 'sort_order' => 11],
            ['name_ar' => 'الجوف', 'name_en' => 'Jouf', 'slug' => 'jouf', 'is_active' => true, 'sort_order' => 12],
            ['name_ar' => 'الباحة', 'name_en' => 'Baha', 'slug' => 'baha', 'is_active' => true, 'sort_order' => 13],
            ['name_ar' => 'نجران', 'name_en' => 'Najran', 'slug' => 'najran', 'is_active' => true, 'sort_order' => 14],
            ['name_ar' => 'جازان', 'name_en' => 'Jazan', 'slug' => 'jazan', 'is_active' => true, 'sort_order' => 15],
        ];

        foreach ($cities as $city) {
            \App\Models\City::updateOrCreate(
                ['slug' => $city['slug']],
                $city
            );
        }

        $this->command->info('تم إضافة المدن بنجاح!');
    }
}
