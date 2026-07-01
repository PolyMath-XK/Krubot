<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('divine_dispatch_queue', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->unsignedTinyInteger('section_index');
            $table->date('scheduled_for_date');
            $table->timestamp('scheduled_at');
            $table->json('payload');
            $table->timestamps();

            $table->unique(['user_id', 'section_index', 'scheduled_for_date'], 'divine_queue_user_section_day_unique');
            $table->index('scheduled_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('divine_dispatch_queue');
    }
};
