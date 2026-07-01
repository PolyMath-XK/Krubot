<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('divine_messages', function (Blueprint $table) {
            $table->id();

            // Vertical Index: Section (0: Morning, 1: Midday, 2: Evening)
            $table->unsignedTinyInteger('section_index')->index();

            // Horizontal Index: Bucket (0: Crisis ... 4: Victory)
            $table->unsignedTinyInteger('bucket_index')->index();

            // The Message Payload
            // Using 'text' as requested (64KB limit), suitable for typical divine messages.
            $table->text('content');

            // Weight: Higher value = Higher priority/probability (0-255)
            $table->unsignedTinyInteger('weight')->default(10);

            // Active Flag: Kill-switch for specific messages without deleting
            $table->boolean('is_active')->default(true);

            // Soft Deletes: Keeps history of deleted messages
            $table->softDeletes();

            $table->timestamps();

            // Composite Index:
            // Optimized for the exact query: WHERE section=? AND bucket=? [AND is_active=1]
            $table->index(['section_index', 'bucket_index']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('divine_messages');
    }
};
