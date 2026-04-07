<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * تعديل عمود type في جدول category_fields لإضافة قيمة 'video' للـ enum
     */
    public function up(): void
    {
        DB::statement("ALTER TABLE `category_fields` MODIFY COLUMN `type` ENUM('title','text','number','select','checkbox','textarea','image','video','date','time') NOT NULL");
    }

    /**
     * إرجاع عمود type لقيمه السابقة
     */
    public function down(): void
    {
        DB::statement("ALTER TABLE `category_fields` MODIFY COLUMN `type` ENUM('title','text','number','select','checkbox','textarea','image','date','time') NOT NULL");
    }
};
