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
        Schema::table('resumes', function (Blueprint $table) {
            $table->text('summary')->nullable()->after('parsed_content');
            $table->json('projects')->nullable()->after('education');
            $table->json('certifications')->nullable()->after('projects');
            $table->integer('years_of_experience')->nullable()->after('experience');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('resumes', function (Blueprint $table) {
            $table->dropColumn(['summary', 'projects', 'certifications', 'years_of_experience']);
        });
    }
};
