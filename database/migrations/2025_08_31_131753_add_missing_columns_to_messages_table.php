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
        Schema::table('messages', function (Blueprint $table) {
            // إضافة الأعمدة الأساسية أولاً
            if (!Schema::hasColumn('messages', 'sender_id')) {
                $table->foreignId('sender_id')->constrained('users')->onDelete('cascade');
            }

            if (!Schema::hasColumn('messages', 'receiver_id')) {
                $table->foreignId('receiver_id')->constrained('users')->onDelete('cascade');
            }

            if (!Schema::hasColumn('messages', 'message')) {
                $table->text('message');
            }

            if (!Schema::hasColumn('messages', 'is_read')) {
                $table->boolean('is_read')->default(false);
            }

            if (!Schema::hasColumn('messages', 'read_at')) {
                $table->timestamp('read_at')->nullable();
            }

            if (!Schema::hasColumn('messages', 'service_id')) {
                $table->foreignId('service_id')->nullable()->constrained()->onDelete('cascade');
            }

            if (!Schema::hasColumn('messages', 'media')) {
                $table->json('media')->nullable();
            }

            // إضافة الأعمدة المفقودة
            if (!Schema::hasColumn('messages', 'is_deleted')) {
                $table->boolean('is_deleted')->default(false)->after('read_at');
            }

            if (!Schema::hasColumn('messages', 'deleted_at')) {
                $table->timestamp('deleted_at')->nullable()->after('is_deleted');
            }

            if (!Schema::hasColumn('messages', 'metadata')) {
                $table->json('metadata')->nullable()->after('deleted_at');
            }

            if (!Schema::hasColumn('messages', 'reply_to_message_id')) {
                $table->string('reply_to_message_id')->nullable()->after('metadata');
            }

            if (!Schema::hasColumn('messages', 'conversation_id')) {
                $table->string('conversation_id')->nullable()->after('reply_to_message_id');
            }

            // إضافة الفهارس
            if (!Schema::hasIndex('messages', 'messages_sender_id_receiver_id_index')) {
                $table->index(['sender_id', 'receiver_id']);
            }

            if (!Schema::hasIndex('messages', 'messages_receiver_id_is_read_index')) {
                $table->index(['receiver_id', 'is_read']);
            }

            if (!Schema::hasIndex('messages', 'messages_conversation_id_index')) {
                $table->index(['conversation_id']);
            }

            if (!Schema::hasIndex('messages', 'messages_service_id_index')) {
                $table->index(['service_id']);
            }

            if (!Schema::hasIndex('messages', 'messages_created_at_index')) {
                $table->index(['created_at']);
            }

            if (!Schema::hasIndex('messages', 'messages_is_deleted_index')) {
                $table->index(['is_deleted']);
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('messages', function (Blueprint $table) {
            // إزالة الفهارس
            $table->dropIndex(['sender_id', 'receiver_id']);
            $table->dropIndex(['receiver_id', 'is_read']);
            $table->dropIndex(['conversation_id']);
            $table->dropIndex(['service_id']);
            $table->dropIndex(['created_at']);
            $table->dropIndex(['is_deleted']);

            // إزالة الأعمدة
            $table->dropColumn([
                'is_deleted',
                'deleted_at',
                'metadata',
                'reply_to_message_id',
                'conversation_id'
            ]);
        });
    }
};
