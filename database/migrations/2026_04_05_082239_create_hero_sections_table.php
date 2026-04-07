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
        Schema::create('hero_sections', function (Blueprint $table) {
            $table->id();

            $table->string('name');
            $table->string('subtitle')->nullable();
            $table->text('description')->nullable();

            $table->string('profile_image')->nullable();
            $table->string('background_image')->nullable();
            $table->string('resume_file')->nullable();

            $table->text('typed_items')->nullable();

            // ===== STATUS
            $table->enum('status', ['active', 'inactive'])->default('active');

            // ===== AUDIT COLUMNS
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('deleted_by')->nullable()->constrained('users')->nullOnDelete();

            // ===== TIMESTAMPS
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hero_sections');
    }
};
