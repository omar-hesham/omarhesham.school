<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('badges', function (Blueprint $table) {
            $table->id();

            // Identity
            $table->string('key')->unique();
            $table->string('name');
            $table->string('name_ar')->nullable();
            $table->text('description');
            $table->text('description_ar')->nullable();
            $table->string('emoji', 8);
            $table->string('icon_color', 7)->default('#D4AF37');

            // Categorisation
            $table->enum('category', [
                'streak',
                'volume',
                'quality',
                'consistency',
                'social',
                'program',
                'special',
            ]);

            // Tier
            $table->enum('tier', ['bronze', 'silver', 'gold', 'platinum'])->default('bronze');

            // XP points awarded when this badge is earned
            $table->unsignedSmallInteger('xp_value')->default(50);

            // Whether this badge can be earned multiple times
            $table->boolean('is_repeatable')->default(false);

            // Active flag
            $table->boolean('is_active')->default(true);

            // Sort order for UI display
            $table->unsignedSmallInteger('sort_order')->default(0);

            $table->timestamps();

            $table->index(['category', 'is_active']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('badges');
    }
};
