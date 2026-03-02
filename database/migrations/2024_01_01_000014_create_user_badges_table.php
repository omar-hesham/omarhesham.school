<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('user_badges', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('badge_id')->constrained()->cascadeOnDelete();

            // How many times this badge has been earned (for repeatable badges)
            $table->unsignedSmallInteger('times_earned')->default(1);

            // Snapshot of stats at the moment of earning
            $table->json('context')->nullable();

            $table->timestamp('first_earned_at');
            $table->timestamp('last_earned_at');
            $table->timestamps();

            // Non-repeatable badges: one record per user per badge
            $table->unique(['user_id', 'badge_id']);

            $table->index(['user_id', 'first_earned_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_badges');
    }
};
