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
            $table->enum('parse_status', ['pending', 'success', 'failed'])
                  ->default('pending')
                  ->after('file_path');
            $table->text('parse_error')->nullable()->after('parse_status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('resumes', function (Blueprint $table) {
            $table->dropColumn(['parse_status', 'parse_error']);
        });
    }
};
