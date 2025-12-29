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
        Schema::create('resume_job_matches', function (Blueprint $table) {
            $table->id();
            $table->foreignId('resume_id')->constrained()->onDelete('cascade');
            $table->foreignId('job_application_id')->constrained()->onDelete('cascade');
            $table->integer('match_score')->default(0)->comment('Score from 0-100');
            $table->json('strengths')->nullable()->comment('Matching strengths');
            $table->json('gaps')->nullable()->comment('Skill/experience gaps');
            $table->text('ai_feedback')->nullable()->comment('Detailed AI analysis');
            $table->string('ai_provider')->nullable()->comment('Which provider generated this');
            $table->timestamps();
            
            // Prevent duplicate analyses
            $table->unique(['resume_id', 'job_application_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('resume_job_matches');
    }
};
