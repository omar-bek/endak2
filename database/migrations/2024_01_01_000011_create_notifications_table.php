<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('type'); // offer_received, offer_accepted, offer_rejected, etc.
            $table->string('title');
            $table->text('message');
            $table->json('data')->nullable(); // Additional data like service_id, offer_id, etc.
            $table->timestamp('read_at')->nullable();
            $table->timestamps();

            // Index for better performance
            $table->index(['user_id', 'read_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};
