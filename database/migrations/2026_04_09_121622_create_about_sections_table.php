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
        Schema::create('about_sections', function (Blueprint $table) {
            $table->id();

            $table->string('name');
            $table->string('subtitle');
            $table->text('description');

            $table->string('profile_image')->nullable();

            $table->string('experience')->nullable();
            $table->string('specialization')->nullable();
            $table->string('phone')->nullable();
            $table->string('location')->nullable();

            $table->string('role')->nullable();
            $table->string('database')->nullable();
            $table->string('email')->nullable();
            $table->string('freelance')->nullable();

            $table->text('extra_description')->nullable();

            // 🔥 Status Column
            $table->enum('status', ['active', 'inactive'])->default('active');

            // 🔥 Audit Columns
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
        Schema::dropIfExists('about_sections');
    }
};
