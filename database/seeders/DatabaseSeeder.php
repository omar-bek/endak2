<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // إنشاء مستخدم مدير
        User::factory()->create([
            'name' => 'مدير النظام',
            'email' => 'admin@endak.com',
            'password' => bcrypt('password'),
            'is_admin' => true,
            'user_type' => 'admin',
        ]);

        // إنشاء مستخدم عادي (عميل)
        User::factory()->create([
            'name' => 'مستخدم تجريبي',
            'email' => 'user@endak.com',
            'password' => bcrypt('password'),
            'is_admin' => false,
            'user_type' => 'customer',
            'phone' => '+966501234567',
        ]);

        // إنشاء مزود خدمة
        User::factory()->create([
            'name' => 'أحمد محمد',
            'email' => 'provider@endak.com',
            'password' => bcrypt('password'),
            'is_admin' => false,
            'user_type' => 'provider',
            'phone' => '+966507654321',
            'bio' => 'مزود خدمات تقنية متخصص في تطوير المواقع والتطبيقات',
        ]);

        // تشغيل seeder الأقسام
        $this->call([
            CategorySeeder::class,
        ]);
    }
}
