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
        Schema::create('skills', function (Blueprint $table) {
            $table->id();

            $table->string('name')->comment('Name of the skill, e.g., PHP, JavaScript');
            $table->integer('percentage')->comment('Proficiency percentage, e.g., 80 for 80%');
            $table->string('group')->nullable()->comment('Optional grouping, e.g., Programming, Design');

            $table->enum('status', ['active', 'inactive'])->default('active');

            // 🔥 Audit
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->unsignedBigInteger('deleted_by')->nullable();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('skills');
    }
};
