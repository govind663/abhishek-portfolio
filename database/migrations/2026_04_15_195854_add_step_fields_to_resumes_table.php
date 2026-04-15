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
            $table->integer('current_step')->default(1)->after('status')->comment('Current step of the resume');
            $table->boolean('is_completed')->default(0)->after('current_step')->comment('Indicates if the resume is completed');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('resumes', function (Blueprint $table) {
            $table->dropColumn('current_step');
            $table->dropColumn('is_completed');
        });
    }
};
