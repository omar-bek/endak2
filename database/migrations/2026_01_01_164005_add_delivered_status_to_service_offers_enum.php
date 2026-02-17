<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // تحديث enum لإضافة 'delivered'
        DB::statement("ALTER TABLE `service_offers` MODIFY COLUMN `status` ENUM('pending', 'accepted', 'rejected', 'expired', 'delivered') DEFAULT 'pending'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // إرجاع enum إلى القيم الأصلية (بدون 'delivered')
        // ملاحظة: يجب التأكد من عدم وجود سجلات بحالة 'delivered' قبل التراجع
        DB::statement("ALTER TABLE `service_offers` MODIFY COLUMN `status` ENUM('pending', 'accepted', 'rejected', 'expired') DEFAULT 'pending'");
    }
};
