<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\SubCategory;

class SubcategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Clear existing subcategories
        SubCategory::truncate();

        $subCategories = [
            [
                'id' => 5,
                'name_ar' => 'مرسيدس',
                'name_en' => 'Mercedes-Benz',
                'category_id' => 30, // صيانة السيارات
                'image' => 'sub_departments/1F9fgL15sbIQ2MKccUtcyTmbsO3cxlcGYVi0KMTB.png',
                'description_ar' => 'صيانة سيارات مرسيدس',
                'description_en' => 'Mercedes-Benz Car Maintenance',
                'status' => true,
            ],
            [
                'id' => 6,
                'name_ar' => 'BMW',
                'name_en' => 'BMW',
                'category_id' => 30, // صيانة السيارات
                'image' => 'sub_departments/cPqNRgNdQ0CH4891U4zmxxk1okk0RlKIJakTkAqO.png',
                'description_ar' => 'صيانة سيارات BMW',
                'description_en' => 'BMW Car Maintenance',
                'status' => true,
            ],
            [
                'id' => 7,
                'name_ar' => 'مرسيدس',
                'name_en' => 'Mercedes-Benz',
                'category_id' => 31, // قطع غيار
                'image' => 'sub_departments/4MZkE31rDKJcXEkSlBtiXxB6WA7KnRm9FiW2IgID.png',
                'description_ar' => 'قطع غيار مرسيدس',
                'description_en' => 'Mercedes-Benz Spare Parts',
                'status' => true,
            ],
            [
                'id' => 8,
                'name_ar' => 'لودر',
                'name_en' => 'Loader',
                'category_id' => 32, // معدات ثقيلة
                'image' => 'sub_departments/ZuRqeuTOOJ4MMBwi8tFdr3obc0lM6ZlGeVxAXeCJ.png',
                'description_ar' => 'معدات لودر',
                'description_en' => 'Loader Equipment',
                'status' => true,
            ],
            [
                'id' => 9,
                'name_ar' => 'شاحنة 20 م',
                'name_en' => 'Truck 20m',
                'category_id' => 33, // شاحنات
                'image' => null,
                'description_ar' => 'شاحنات 20 متر',
                'description_en' => '20 Meter Trucks',
                'status' => true,
            ],
            [
                'id' => 10,
                'name_ar' => 'خلاطة',
                'name_en' => 'Mixer',
                'category_id' => 32, // معدات ثقيلة
                'image' => 'sub_departments/ZuRqeuTOOJ4MMBwi8tFdr3obc0lM6ZlGeVxAXeCJ.png',
                'description_ar' => 'معدات خلاطة',
                'description_en' => 'Mixer Equipment',
                'status' => true,
            ],
            [
                'id' => 11,
                'name_ar' => 'شاحنة ١٢ م',
                'name_en' => 'Truck 12m',
                'category_id' => 33, // شاحنات
                'image' => null,
                'description_ar' => 'شاحنات 12 متر',
                'description_en' => '12 Meter Trucks',
                'status' => true,
            ],
            [
                'id' => 12,
                'name_ar' => 'نيسان',
                'name_en' => 'Nissan',
                'category_id' => 30, // صيانة السيارات
                'image' => 'sub_departments/NUvrQLteBtqfUOABJt9P28tU3JKrVfSp62srWP1F.jpg',
                'description_ar' => 'صيانة سيارات نيسان',
                'description_en' => 'Nissan Car Maintenance',
                'status' => true,
            ],
            [
                'id' => 13,
                'name_ar' => 'BMW',
                'name_en' => 'BMW',
                'category_id' => 31, // قطع غيار
                'image' => 'sub_departments/4MZkE31rDKJcXEkSlBtiXxB6WA7KnRm9FiW2IgID.png',
                'description_ar' => 'قطع غيار BMW',
                'description_en' => 'BMW Spare Parts',
                'status' => true,
            ],
            [
                'id' => 14,
                'name_ar' => 'نيسان',
                'name_en' => 'Nissan',
                'category_id' => 31, // قطع غيار
                'image' => 'sub_departments/4MZkE31rDKJcXEkSlBtiXxB6WA7KnRm9FiW2IgID.png',
                'description_ar' => 'قطع غيار نيسان',
                'description_en' => 'Nissan Spare Parts',
                'status' => true,
            ],
        ];

        foreach ($subCategories as $subCategoryData) {
            SubCategory::create($subCategoryData);
        }

        $this->command->info('تم إنشاء ' . count($subCategories) . ' قسم فرعي بنجاح!');
    }
}
