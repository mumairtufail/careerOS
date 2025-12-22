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
        Schema::create('job_activities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('job_application_id')->constrained()->onDelete('cascade');
            $table->string('activity_type'); // e.g., 'stage_changed', 'note_added', 'interview_scheduled'
            $table->text('description')->nullable();
            $table->json('metadata')->nullable(); // For storing extra data like old_stage_id, new_stage_id
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('job_activities');
    }
};
