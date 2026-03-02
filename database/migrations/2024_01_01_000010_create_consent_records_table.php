<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('consent_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('consent_token', 64)->unique();
            $table->enum('action', ['requested', 'approved', 'denied'])->default('requested');
            $table->string('guardian_email');
            $table->timestamp('responded_at')->nullable();
            $table->timestamps();
            $table->index('consent_token');
        });
    }

    public function down(): void { Schema::dropIfExists('consent_records'); }
};
