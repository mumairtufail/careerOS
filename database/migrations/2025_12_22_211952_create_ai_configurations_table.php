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
        Schema::create('ai_configurations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('provider'); // openai, anthropic, gemini, ollama
            $table->text('api_key')->nullable(); // Encrypted
            $table->string('model')->nullable(); // e.g. gpt-4, claude-3
            $table->boolean('is_active')->default(false);
            $table->timestamps();

            // Ensure a user can only have one config per provider (optional, but good for now)
            $table->unique(['user_id', 'provider']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ai_configurations');
    }
};
