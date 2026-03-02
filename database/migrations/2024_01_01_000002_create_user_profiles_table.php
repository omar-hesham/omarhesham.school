<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('user_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->unique()->constrained()->cascadeOnDelete();
            $table->enum('age_group', ['child', 'adult'])->default('adult');
            $table->string('guardian_email')->nullable();
            $table->enum('consent_status', ['not_required', 'pending', 'approved', 'denied'])
                  ->default('not_required');
            $table->string('locale', 5)->default('en');
            $table->text('bio')->nullable();
            $table->string('avatar_path')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void { Schema::dropIfExists('user_profiles'); }
};
