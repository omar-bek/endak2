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
        // التحقق من وجود الجدول
        if (!Schema::hasTable('messages')) {
            Schema::create('messages', function (Blueprint $table) {
                $table->id();
                $table->foreignId('sender_id')->constrained('users')->onDelete('cascade');
                $table->foreignId('receiver_id')->constrained('users')->onDelete('cascade');
                $table->text('content')->nullable();
                $table->string('media_path')->nullable();
                $table->string('voice_note_path')->nullable();
                $table->enum('message_type', ['text', 'image', 'voice', 'file', 'location', 'contact'])->default('text');
                $table->boolean('is_read')->default(false);
                $table->timestamp('read_at')->nullable();
                $table->boolean('is_deleted')->default(false);
                $table->timestamp('deleted_at')->nullable();
                $table->json('metadata')->nullable();
                $table->string('reply_to_message_id')->nullable();
                $table->string('conversation_id')->nullable();
                $table->foreignId('service_id')->nullable()->constrained('services')->onDelete('cascade');
                $table->foreignId('service_offer_id')->nullable()->constrained('service_offers')->onDelete('cascade');
                $table->string('file_name')->nullable();
                $table->bigInteger('file_size')->nullable();
                $table->timestamps();

                // الفهارس
                $table->index(['sender_id', 'receiver_id']);
                $table->index(['receiver_id', 'is_read']);
                $table->index(['conversation_id']);
                $table->index(['service_id']);
                $table->index(['created_at']);
                $table->index(['is_deleted']);
            });
        } else {
            // إضافة الأعمدة المفقودة إذا كان الجدول موجود
            Schema::table('messages', function (Blueprint $table) {
                if (!Schema::hasColumn('messages', 'sender_id')) {
                    $table->foreignId('sender_id')->constrained('users')->onDelete('cascade');
                }
                if (!Schema::hasColumn('messages', 'receiver_id')) {
                    $table->foreignId('receiver_id')->constrained('users')->onDelete('cascade');
                }
                if (!Schema::hasColumn('messages', 'content')) {
                    $table->text('content')->nullable();
                }
                if (!Schema::hasColumn('messages', 'media_path')) {
                    $table->string('media_path')->nullable();
                }
                if (!Schema::hasColumn('messages', 'voice_note_path')) {
                    $table->string('voice_note_path')->nullable();
                }
                if (!Schema::hasColumn('messages', 'message_type')) {
                    $table->enum('message_type', ['text', 'image', 'voice', 'file', 'location', 'contact'])->default('text');
                }
                if (!Schema::hasColumn('messages', 'is_read')) {
                    $table->boolean('is_read')->default(false);
                }
                if (!Schema::hasColumn('messages', 'read_at')) {
                    $table->timestamp('read_at')->nullable();
                }
                if (!Schema::hasColumn('messages', 'is_deleted')) {
                    $table->boolean('is_deleted')->default(false);
                }
                if (!Schema::hasColumn('messages', 'deleted_at')) {
                    $table->timestamp('deleted_at')->nullable();
                }
                if (!Schema::hasColumn('messages', 'metadata')) {
                    $table->json('metadata')->nullable();
                }
                if (!Schema::hasColumn('messages', 'reply_to_message_id')) {
                    $table->string('reply_to_message_id')->nullable();
                }
                if (!Schema::hasColumn('messages', 'conversation_id')) {
                    $table->string('conversation_id')->nullable();
                }
                if (!Schema::hasColumn('messages', 'service_id')) {
                    $table->foreignId('service_id')->nullable()->constrained('services')->onDelete('cascade');
                }
                if (!Schema::hasColumn('messages', 'service_offer_id')) {
                    $table->foreignId('service_offer_id')->nullable()->constrained('service_offers')->onDelete('cascade');
                }
                if (!Schema::hasColumn('messages', 'file_name')) {
                    $table->string('file_name')->nullable();
                }
                if (!Schema::hasColumn('messages', 'file_size')) {
                    $table->bigInteger('file_size')->nullable();
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('messages');
    }
};
