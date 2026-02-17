<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Category;
use App\Models\CategoryField;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Clear existing data - disable foreign key checks temporarily
        \DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        CategoryField::truncate();
        Category::truncate();
        \DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $categories = [
            [
                'id' => 27,
                'name' => 'نقل العفش',
                'name_en' => 'Furniture Moving',
                'description' => 'خدمات نقل العفش والأثاث',
                'description_ar' => 'خدمات نقل العفش والأثاث',
                'image' => 'departments/JhetHybFBdaTFyMRQ4YZltDJAmpHFWm5fV5mnCCK.png',
                'is_active' => true,
                'sort_order' => 1,
                'fields' => [
                    [
                        'name' => 'نوع_العفش',
                        'name_ar' => 'نوع العفش',
                        'name_en' => 'Furniture Type',
                        'type' => 'select',
                        'options' => ['كنبة', 'تسريحة', 'مكيفات سبليت'],
                        'is_required' => false,
                        'sort_order' => 1,
                    ],
                    [
                        'name' => 'عدد',
                        'name_ar' => 'عدد',
                        'name_en' => 'Quantity',
                        'type' => 'number',
                        'is_required' => false,
                        'sort_order' => 2,
                    ],
                    [
                        'name' => 'فك',
                        'name_ar' => 'فك',
                        'name_en' => 'Disassemble',
                        'type' => 'checkbox',
                        'is_required' => false,
                        'sort_order' => 3,
                    ],
                    [
                        'name' => 'تركيب',
                        'name_ar' => 'تركيب',
                        'name_en' => 'Install',
                        'type' => 'checkbox',
                        'is_required' => false,
                        'sort_order' => 4,
                    ],
                    [
                        'name' => 'من_الحي',
                        'name_ar' => 'من الحي',
                        'name_en' => 'From Neighborhood',
                        'type' => 'text',
                        'is_required' => false,
                        'sort_order' => 5,
                    ],
                    [
                        'name' => 'من_الدور',
                        'name_ar' => 'من الدور',
                        'name_en' => 'From Floor',
                        'type' => 'select',
                        'options' => ['1', '2', '3', '4', '5', '6', '7'],
                        'is_required' => false,
                        'sort_order' => 6,
                    ],
                    [
                        'name' => 'الي_المدينة',
                        'name_ar' => 'الي المدينة',
                        'name_en' => 'To City',
                        'type' => 'select',
                        'options' => ['مكة', 'الرياض', 'المدينة'],
                        'is_required' => false,
                        'sort_order' => 7,
                    ],
                    [
                        'name' => 'الي_الحي',
                        'name_ar' => 'الي الحي',
                        'name_en' => 'To Neighborhood',
                        'type' => 'text',
                        'is_required' => false,
                        'sort_order' => 8,
                    ],
                    [
                        'name' => 'الي_الدور',
                        'name_ar' => 'الي الدور',
                        'name_en' => 'To Floor',
                        'type' => 'select',
                        'options' => ['1', '2', '3', '4', '5', '6', '7', '8', '9', '10'],
                        'is_required' => false,
                        'sort_order' => 9,
                    ],
                    [
                        'name' => 'ملاحظة_عن_العمل',
                        'name_ar' => 'ملاحظة عن العمل',
                        'name_en' => 'Work Note',
                        'type' => 'textarea',
                        'is_required' => false,
                        'sort_order' => 10,
                    ],
                ]
            ],
            [
                'id' => 30,
                'name' => 'صيانة السيارات',
                'name_en' => 'Cars Maintanence',
                'image' => 'departments/qJ1S1NwB1hgXAT9xuKZdv6jyaRL3awPXpUeE2hbk.png',
                'is_active' => true,
                'sort_order' => 2,
                'fields' => [
                    [
                        'name' => 'نوع السيارة',
                        'name_ar' => 'نوع السيارة',
                        'name_en' => 'Car`s Model',
                        'type' => 'text',
                        'is_required' => true,
                        'sort_order' => 1,
                    ],
                    [
                        'name' => 'سنة الصنع',
                        'name_ar' => 'سنة الصنع',
                        'name_en' => 'Made year',
                        'type' => 'number',
                        'is_required' => true,
                        'sort_order' => 2,
                    ],
                    [
                        'name' => 'شرح_عن_الخلل',
                        'name_ar' => 'شرح عن الخلل',
                        'name_en' => 'Describe Damages',
                        'type' => 'textarea',
                        'is_required' => false,
                        'sort_order' => 3,
                    ],
            [
                        'name' => 'صور السيارة',
                        'name_ar' => 'صور السيارة',
                        'name_en' => 'Car Images',
                        'type' => 'image',
                        'is_required' => false,
                        'sort_order' => 4,
                    ],
                ]
            ],
            [
                'id' => 31,
                'name' => 'قطع غيار',
                'name_en' => 'Cars`s Spare parts',
                'description' => 'The Spare Parts Department is responsible for providing all the components and materials needed for the maintenance and repair of equipment and machinery. Its goal is to ensure the availability of original and replacement parts with high quality and on time to support uninterrupted operational processes.',
                'description_ar' => 'قسم قطع الغيار مسؤول عن توفير جميع المكونات والمواد اللازمة لصيانة وإصلاح المعدات والآلات. يهدف إلى ضمان توافر القطع الأصلية والبديلة بجودة عالية وفي الوقت المناسب لدعم العمليات التشغيلية دون انقطاع.',
                'image' => 'departments/2h8J6DrD6fHoq8YhiknP4IwPhRcSZQat5T1WUe02.png',
                'is_active' => true,
                'sort_order' => 3,
                'fields' => [
                    [
                        'name' => 'الفئة',
                        'name_ar' => 'الفئة',
                        'name_en' => 'Model',
                        'type' => 'text',
                        'is_required' => true,
                        'sort_order' => 1,
                    ],
                    [
                        'name' => 'سنة الصنع',
                        'name_ar' => 'سنة الصنع',
                        'name_en' => 'year made',
                        'type' => 'number',
                        'is_required' => true,
                        'sort_order' => 2,
                    ],
                    [
                        'name' => 'رقم_القطعة_المطلوبة',
                        'name_ar' => 'رقم القطعة المطلوبة',
                        'name_en' => 'Part Number',
                        'type' => 'text',
                        'is_required' => false,
                        'sort_order' => 3,
                    ],
                    [
                        'name' => 'اسم_القطعة',
                        'name_ar' => 'اسم القطعة',
                        'name_en' => 'Part Name',
                        'type' => 'text',
                        'is_required' => false,
                        'sort_order' => 4,
                    ],
                    [
                        'name' => 'ارفاق_صورة',
                        'name_ar' => 'ارفاق صورة',
                        'name_en' => 'Part Image',
                        'type' => 'image',
                        'is_required' => false,
                        'sort_order' => 5,
                    ],
                    [
                        'name' => 'ملاحظة',
                        'name_ar' => 'ملاحظة',
                        'name_en' => 'Note',
                        'type' => 'textarea',
                        'is_required' => false,
                        'sort_order' => 6,
                    ],
                ]
            ],
            [
                'id' => 32,
                'name' => 'معدات ثقيلة',
                'name_en' => 'Heavy a Equipment',
                'image' => 'departments/BcFbyOlvFdSTpXydkn6Qk7R8GQk87XNSOfVrfjyt.png',
                'is_active' => true,
                'sort_order' => 4,
                'fields' => [
                    [
                        'name' => 'ملاحظة',
                        'name_ar' => 'ملاحظة',
                        'name_en' => 'Note',
                        'type' => 'textarea',
                        'is_required' => false,
                        'sort_order' => 1,
                    ],
                    [
                        'name' => 'ارفاق_صورة',
                        'name_ar' => 'ارفاق صورة',
                        'name_en' => 'Attach Image',
                        'type' => 'image',
                        'is_required' => false,
                        'sort_order' => 2,
                    ],
                    [
                        'name' => 'الوقت',
                        'name_ar' => 'الوقت',
                        'name_en' => 'Time',
                        'type' => 'time',
                        'is_required' => false,
                        'sort_order' => 3,
                    ],
                    [
                        'name' => 'التاريخ',
                        'name_ar' => 'التاريخ',
                        'name_en' => 'Date',
                        'type' => 'date',
                        'is_required' => false,
                        'sort_order' => 4,
                    ],
                ]
            ],
            [
                'id' => 33,
                'name' => 'شاحنات',
                'name_en' => 'Heavy Trucks',
                'image' => 'departments/c6noFg0l6DziLgjRlizjRLHwb9XoqNJKTm5wv2EM.png',
                'is_active' => true,
                'sort_order' => 5,
                'fields' => [
                    [
                        'name' => 'من_الحي',
                        'name_ar' => 'من الحي',
                        'name_en' => 'From Neighborhood',
                        'type' => 'text',
                        'is_required' => false,
                        'sort_order' => 1,
                    ],
                    [
                        'name' => 'الي_المدينة',
                        'name_ar' => 'الي المدينة',
                        'name_en' => 'To City',
                        'type' => 'text',
                        'is_required' => false,
                        'sort_order' => 2,
                    ],
                    [
                        'name' => 'الي_الحي',
                        'name_ar' => 'الي الحي',
                        'name_en' => 'To Neighborhood',
                        'type' => 'text',
                        'is_required' => false,
                        'sort_order' => 3,
                    ],
                    [
                        'name' => 'ملاحظة',
                        'name_ar' => 'ملاحظة',
                        'name_en' => 'Note',
                        'type' => 'textarea',
                        'is_required' => false,
                        'sort_order' => 4,
                    ],
                ]
            ],
            [
                'id' => 36,
                'name' => 'صهريج مياه',
                'name_en' => 'Water\'s Car',
                'image' => 'departments/bJuDgRoOMdrZFNKJtOlhh7Dz8UTpXbPOeB8FLybz.png',
                'is_active' => true,
                'sort_order' => 6,
                'fields' => [
                    [
                        'name' => 'مياه_صالحة_للشرب',
                        'name_ar' => 'مياه صالحة للشرب',
                        'name_en' => 'Drinking Water',
                        'type' => 'select',
                        'options' => ['18 طن', '32 طن'],
                        'is_required' => false,
                        'sort_order' => 1,
                    ],
                    [
                        'name' => 'مياه_صرف_صحي',
                        'name_ar' => 'مياه صرف صحي',
                        'name_en' => 'Sewage Water',
                        'type' => 'select',
                        'options' => ['18 طن', '32 طن'],
                        'is_required' => false,
                        'sort_order' => 2,
                    ],
                ]
            ],
            [
                'id' => 37,
                'name' => 'سطحة',
                'name_en' => 'Big A cars',
                'image' => 'departments/wF6CCtMQimNZ7qCRrAN5ZFfO5mnY9Qlou5DDUyHk.png',
                    'is_active' => true,
                'sort_order' => 7,
                'fields' => [
                    [
                        'name' => 'الموقع',
                        'name_ar' => 'الموقع',
                        'name_en' => 'Location',
                        'type' => 'text',
                        'is_required' => false,
                        'sort_order' => 1,
                    ],
                    [
                        'name' => 'نوع_السيارة',
                        'name_ar' => 'نوع السيارة',
                        'name_en' => 'Car Type',
                        'type' => 'text',
                        'is_required' => false,
                        'sort_order' => 2,
                    ],
                    [
                        'name' => 'ارفاق_صورة',
                        'name_ar' => 'ارفاق صورة',
                        'name_en' => 'Attach Images',
                        'type' => 'image',
                        'is_required' => false,
                        'sort_order' => 3,
                    ],
                    [
                        'name' => 'ملاحظة',
                        'name_ar' => 'ملاحظة',
                        'name_en' => 'Note',
                        'type' => 'textarea',
                        'is_required' => false,
                        'sort_order' => 4,
                    ],
                ]
            ],
            [
                'id' => 38,
                'name' => 'اكلا اسر منتجة',
                'name_en' => 'Family food',
                'image' => 'departments/7hl1uE3u7hnQ3xrONdQLNqbHJX9Y1dwFTvBddXgY.png',
                    'is_active' => true,
                'sort_order' => 8,
                'fields' => [
                    [
                        'name' => 'نوع_الاكل',
                        'name_ar' => 'نوع الاكل',
                        'name_en' => 'Food Type',
                        'type' => 'text',
                        'is_required' => false,
                        'sort_order' => 1,
                    ],
                    [
                        'name' => 'الوقت',
                        'name_ar' => 'الوقت',
                        'name_en' => 'Time',
                        'type' => 'time',
                        'is_required' => false,
                        'sort_order' => 2,
                    ],
                    [
                        'name' => 'التاريخ',
                        'name_ar' => 'التاريخ',
                        'name_en' => 'Date',
                        'type' => 'date',
                        'is_required' => false,
                        'sort_order' => 3,
                    ],
                    [
                        'name' => 'ملاحظة',
                        'name_ar' => 'ملاحظة',
                        'name_en' => 'Note',
                        'type' => 'textarea',
                        'is_required' => false,
                        'sort_order' => 4,
                    ],
                ]
            ],
            [
                'id' => 39,
                'name' => 'اصلاح التكييف',
                'name_en' => 'Air condition mantinance',
                'image' => 'departments/bJAhQ7FQhHKeeVHRfx1yYZY0OPJQoFG2G08j4WWB.png',
                    'is_active' => true,
                'sort_order' => 9,
                'fields' => [
                    [
                        'name' => 'نوع التكييف',
                        'name_ar' => 'نوع التكييف',
                        'name_en' => 'Air type',
                        'type' => 'select',
                        'options' => ['سبليت', 'شباك'],
                        'is_required' => false,
                        'sort_order' => 1,
                    ],
                    [
                        'name' => 'نوع الخدمة',
                        'name_ar' => 'نوع الخدمة',
                        'name_en' => 'Service type',
                        'type' => 'title',
                        'is_required' => false,
                        'sort_order' => 2,
                    ],
                    [
                        'name' => 'تنظيف',
                        'name_ar' => 'تنظيف',
                        'name_en' => 'clean',
                        'type' => 'checkbox',
                        'is_required' => false,
                    'sort_order' => 3,
                ],
                    [
                        'name' => 'فيريون',
                        'name_ar' => 'فيريون',
                        'name_en' => 'feroun',
                        'type' => 'checkbox',
                        'is_required' => false,
                        'sort_order' => 4,
                    ],
                    [
                        'name' => 'صيانة',
                        'name_ar' => 'صيانة',
                        'name_en' => 'Maintenance',
                        'type' => 'checkbox',
                        'is_required' => false,
                        'sort_order' => 5,
                    ],
                    [
                        'name' => 'الماركة',
                        'name_ar' => 'الماركة',
                        'name_en' => 'Model',
                        'type' => 'text',
                        'is_required' => false,
                        'sort_order' => 6,
                    ],
                    [
                        'name' => 'العدد',
                        'name_ar' => 'العدد',
                        'name_en' => 'Quantity',
                        'type' => 'number',
                        'is_required' => false,
                        'sort_order' => 7,
                    ],
                    [
                        'name' => 'صورة',
                        'name_ar' => 'صورة',
                        'name_en' => 'images',
                        'type' => 'image',
                        'is_required' => false,
                        'sort_order' => 8,
                    ],
                    [
                        'name' => 'التاريخ',
                        'name_ar' => 'التاريخ',
                        'name_en' => 'Date',
                        'type' => 'date',
                        'is_required' => false,
                        'sort_order' => 9,
                    ],
                ]
            ],
            [
                'id' => 40,
                'name' => 'تنظيف',
                'name_en' => 'Cleanings',
                'image' => 'departments/9Smb1XZLiXF2oMKMHZhMnDq8VKRRRE8hCH5MG3sc.png',
                'is_active' => true,
                'sort_order' => 10,
                'fields' => [
                    [
                        'name' => 'الوقت',
                        'name_ar' => 'الوقت',
                        'name_en' => 'TIme',
                        'type' => 'date',
                        'is_required' => false,
                        'sort_order' => 1,
                    ],
                ]
            ],
            [
                'id' => 41,
                'name' => 'دروس خصوصية',
                'name_en' => 'Private Teacher',
                'image' => 'departments/RlAMGCXlmFMgdZ0nu3BBFGLVdc3tKJv8UbuMfK3j.png',
                    'is_active' => true,
                'sort_order' => 11,
                'fields' => [
                    [
                        'name' => 'ذكر',
                        'name_ar' => 'ذكر',
                        'name_en' => 'Male',
                        'type' => 'checkbox',
                        'is_required' => false,
                    'sort_order' => 1,
                ],
                [
                        'name' => 'انثي',
                        'name_ar' => 'انثي',
                        'name_en' => 'Female',
                        'type' => 'checkbox',
                        'is_required' => false,
                        'sort_order' => 2,
                    ],
                    [
                        'name' => 'الوقت',
                        'name_ar' => 'الوقت',
                        'name_en' => 'TIme',
                        'type' => 'time',
                        'is_required' => false,
                        'sort_order' => 3,
                    ],
                    [
                        'name' => 'التاريخ',
                        'name_ar' => 'التاريخ',
                        'name_en' => 'Date',
                        'type' => 'date',
                        'is_required' => false,
                        'sort_order' => 4,
                    ],
                ]
            ],
            [
                'id' => 42,
                'name' => 'كاميرات مراقبة',
                'name_en' => 'Surveillance Cameras',
                'image' => 'departments/j9A4doAEU1AySRV8BGdJD8Tl0Fqgbbx5pKF9QqIm.png',
                    'is_active' => true,
                'sort_order' => 12,
                'fields' => [
                    [
                        'name' => 'نوع الكاميرا',
                        'name_ar' => 'نوع الكاميرا',
                        'name_en' => 'Camera type',
                        'type' => 'title',
                        'is_required' => false,
                        'sort_order' => 1,
                    ],
                    [
                        'name' => 'بصمة',
                        'name_ar' => 'بصمة',
                        'name_en' => 'Finger print',
                        'type' => 'checkbox',
                        'is_required' => false,
                    'sort_order' => 2,
                ],
                [
                        'name' => 'كاميرات مراقبة',
                        'name_ar' => 'كاميرات مراقبة',
                        'name_en' => 'Surveillance Cameras',
                        'type' => 'checkbox',
                        'is_required' => false,
                        'sort_order' => 3,
                    ],
                    [
                        'name' => 'سمارت',
                        'name_ar' => 'سمارت',
                        'name_en' => 'Smart',
                        'type' => 'checkbox',
                        'is_required' => false,
                        'sort_order' => 4,
                    ],
                    [
                        'name' => 'أنظمة إطفاء حرائق',
                        'name_ar' => 'أنظمة إطفاء حرائق',
                        'name_en' => 'Fire System',
                        'type' => 'checkbox',
                        'is_required' => false,
                        'sort_order' => 5,
                    ],
                    [
                        'name' => 'شبكات',
                        'name_ar' => 'شبكات',
                        'name_en' => 'Networks',
                        'type' => 'checkbox',
                        'is_required' => false,
                        'sort_order' => 6,
                    ],
                    [
                        'name' => 'أنظمة أمنية',
                        'name_ar' => 'أنظمة أمنية',
                        'name_en' => 'Security Systems',
                        'type' => 'checkbox',
                        'is_required' => false,
                        'sort_order' => 7,
                    ],
                ]
            ],
            [
                'id' => 43,
                'name' => 'تجيهز الحفلات',
                'name_en' => 'Party A',
                'image' => 'departments/AnK3obwnxnArX42jWCcmgiT5jbUrDYhByKH29LRX.png',
                    'is_active' => true,
                'sort_order' => 13,
                'fields' => [
                    [
                        'name' => 'ارفاق صورة',
                        'name_ar' => 'ارفاق صورة',
                        'name_en' => 'Attach image',
                        'type' => 'image',
                        'is_required' => false,
                        'sort_order' => 1,
                    ],
                    [
                        'name' => 'الوقت',
                        'name_ar' => 'الوقت',
                        'name_en' => 'TIme',
                        'type' => 'time',
                        'is_required' => false,
                        'sort_order' => 2,
                    ],
                    [
                        'name' => 'التاريخ',
                        'name_ar' => 'التاريخ',
                        'name_en' => 'Date',
                        'type' => 'date',
                        'is_required' => false,
                    'sort_order' => 3,
                    ],
                ]
            ],
            [
                'id' => 44,
                'name' => 'تنسيق الحدائق',
                'name_en' => 'Design Gardens',
                'image' => 'departments/YHZQNln0FdbWBmbjbiZKPKo9S7lH0YfmZZDm7cON.png',
                'is_active' => true,
                'sort_order' => 14,
                'fields' => [
                    [
                        'name' => 'ارفاق صورة',
                        'name_ar' => 'ارفاق صورة',
                        'name_en' => 'Attach image',
                        'type' => 'image',
                        'is_required' => false,
                        'sort_order' => 1,
                    ],
                    [
                        'name' => 'التاريخ',
                        'name_ar' => 'التاريخ',
                        'name_en' => 'Date',
                        'type' => 'date',
                        'is_required' => false,
                        'sort_order' => 2,
                    ],
                ]
            ],
        ];

        foreach ($categories as $categoryData) {
            $fields = $categoryData['fields'] ?? [];
            unset($categoryData['fields']);

            $category = Category::create($categoryData);

            foreach ($fields as $fieldData) {
                $fieldData['category_id'] = $category->id;
                CategoryField::create($fieldData);
            }
        }
    }
}
