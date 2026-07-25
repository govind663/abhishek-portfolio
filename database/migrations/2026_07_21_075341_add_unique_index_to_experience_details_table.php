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
        Schema::table('experience_details', function (Blueprint $table) {

            // TEXT -> VARCHAR(255)
            $table->string('description', 255)->change();

            // Composite Unique Index
            $table->unique(
                ['experience_id', 'description'],
                'exp_details_unique'
            );

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('experience_details', function (Blueprint $table) {

            // Remove Unique Index
            $table->dropUnique('exp_details_unique');

            // VARCHAR(255) -> TEXT
            $table->text('description')->change();

        });
    }
};